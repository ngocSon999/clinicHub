<?php

namespace App\Http\Controllers;

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

        $query = Role::with('permissions')
            ->select(['id', 'team_id', 'name', 'created_at']);

        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('team_id', 'LIKE', "%{$searchValue}%");
            });
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

        return view('role.edit', compact('role', 'permissionsGrouped'));
    }

    public function create(): Factory|\Illuminate\Contracts\View\View
    {
        $permissionsGrouped = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('role.create', compact('permissionsGrouped'));
    }

    public function store(Request $request): RedirectResponse
    {
        $currentTeamId = Auth::user()->team_id ?? 1;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where(function ($query) use ($currentTeamId) {
                    return $query->where('team_id', $currentTeamId);
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
                'team_id'    => $currentTeamId,
                'guard_name' => 'web'
            ]);

            $permissions = $request->input('permissions', []);

            if (!empty($permissions)) {
                $role->givePermissionTo($permissions);
            }

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

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->team_id !== Auth::user()->team_id) {
            return redirect()->route('role.index')
                ->with('error', 'Bạn không có quyền xóa vai trò của chi nhánh khác!');
        }

        DB::beginTransaction();

        try {
            $role->syncPermissions([]);
            $role->delete();
            DB::commit();

            return redirect()->route('role.index')
                ->with('success', 'Xóa vai trò thành công!');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('role.index')
                ->with('error', 'Không thể xóa vai trò này: ' . $e->getMessage());
        }
    }
}
