<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use App\Services\RoleService;
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
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

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
        return response()->json(
            $this->roleService->getList($request)
        );
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

    public function store(RoleRequest $request): RedirectResponse
    {
        try {
            $this->roleService->store($request->validated());

            return redirect()
                ->route('role.index')
                ->with('success', 'Thêm mới vai trò và phân quyền thành công!');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function update(Role $role, RoleRequest $request): RedirectResponse
    {
        try {
            $this->roleService->update($role, $request->validated());

            return redirect()
                ->route('role.index')
                ->with('success', 'Cập nhật vai trò và phân quyền thành công!');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
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
