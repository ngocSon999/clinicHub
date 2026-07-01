<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        return view('role.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        $perPage = $request->input('length', 10);
        $start = $request->input('start', 0);
        $page = ($start / $perPage) + 1;

        $request->merge(['page' => $page]);

        $searchValue = $request->input('search.value');

        $query = Role::with(['permissions', 'team'])
            ->select(['id', 'team_id', 'name', 'created_at']);

        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('team_id', 'LIKE', "%{$searchValue}%");
            });
        }

        if ($request->has('order')) {
            $columnIndex = $request->input('order.0.column');
            $columnDirection = $request->input('order.0.dir', 'asc');
            $columnName = $request->input("columns.{$columnIndex}.data");

            $allowableColumns = ['id', 'team_id', 'name', 'created_at'];

            if (!empty($columnName) && in_array($columnName, $allowableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            } else {
                $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $roles = $query->paginate($perPage);

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $roles->total(),
            'recordsFiltered' => $roles->total(),
            'data'            => $roles->items(),
        ]);
    }

    public function edit(Role $role): Factory|\Illuminate\Contracts\View\View
    {
        $role->load('permissions');

        $permissionsGrouped = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        $branches = Branch::all();

        return view('role.edit', compact('role', 'permissionsGrouped', 'branches'));
    }

    public function create(): Factory|\Illuminate\Contracts\View\View
    {
        $permissionsGrouped = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        $branches = Branch::all();

        return view('role.create', compact('permissionsGrouped', 'branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $teamId = $request->branch_id;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where(function ($query) use ($teamId) {
                    return $query->where('team_id', $teamId);
                })
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ], [
            'name.required' => 'Vui lòng nhập tên vai trò.',
            'name.unique'   => 'Tên vai trò này đã tồn tại trong chi nhánh của bạn.',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'       => $request->input('name'),
                'team_id'    => $teamId,
                'guard_name' => 'web'
            ]);

            $permissions = $request->input('permissions', []);

            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }
            DB::table('role_permissions')
                ->where('role_id', $role->id)
                ->update([
                    'team_id' => $teamId,
                ]);

            DB::commit();

            return redirect()->route('role.index')
                ->with('success', 'Thêm mới vai trò và phân quyền thành công!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra trong quá trình thêm mới: ' . $e->getMessage());
        }
    }

    public function update(Role $role, Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where(function ($query) use ($role) {
                        return $query->where('team_id', $role->team_id);
                    })
                    ->ignore($role->id)
            ],
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ], [
            'name.required' => 'Vui lòng nhập tên vai trò.',
            'name.unique'   => 'Tên vai trò này đã tồn tại trong chi nhánh của bạn.',
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->input('name'),
            ]);

            $permissions = $request->input('permissions', []);
            setPermissionsTeamId($role->team_id);
            $role->syncPermissions($permissions);

            DB::commit();

            return redirect()->route('role.index')
                ->with('success', 'Cập nhật vai trò và phân quyền thành công!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra trong quá trình cập nhật: ' . $e->getMessage());
        }
    }

    public function destroy(Role $role): JsonResponse
    {
        $currentUser = Auth::user();

        $hasAccessToTeam = DB::table('user_roles')
            ->where('model_id', $currentUser->id)
            ->where('model_type', get_class($currentUser))
            ->where('team_id', $role->team_id)
            ->exists();

        if (!$hasAccessToTeam) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa vai trò của chi nhánh khác!'
            ], 403);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa! Vai trò này đang được gán cho nhân viên trong hệ thống.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $role->syncPermissions([]);
            $role->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa vai trò thành công!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa vai trò này: ' . $e->getMessage()
            ], 500);
        }
    }
}
