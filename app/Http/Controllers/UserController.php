<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\NewUserPasswordMail;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        $currentUser = Auth::user();

        $userTeamIds = DB::table('user_roles')
            ->where('model_id', $currentUser->id)
            ->where('model_type', get_class($currentUser))
            ->pluck('team_id')
            ->unique()
            ->filter()
            ->toArray();

        $roles = Role::whereIn('team_id', $userTeamIds)->get();

        return view('user.create', compact('roles'));
    }

    public function getList(Request $request): JsonResponse
    {
        $perPage = $request->input('length', 10);
        $start = $request->input('start', 0);
        $page = ($start / $perPage) + 1;

        $request->merge(['page' => $page]);

        $searchValue = $request->input('search.value');

        $query = User::with(['allRoles.team'])->select([
            'id',
            'name',
            'email',
            'created_at'
        ]);

        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('email', 'LIKE', "%{$searchValue}%");
            });
        }
        if ($request->has('order')) {
            $columnIndex = $request->input('order.0.column');
            $columnDirection = $request->input('order.0.dir', 'asc');
            $columnName = $request->input("columns.{$columnIndex}.data");

            $allowableColumns = ['id', 'email', 'name', 'created_at'];

            if (!empty($columnName) && in_array($columnName, $allowableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            } else {
                $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $users = $query->paginate($perPage);

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $users->total(),
            'recordsFiltered' => $users->total(),
            'data'            => $users->items(),
        ]);
    }

    protected function syncUserRoles(User $user, array $roleIds): void
    {
        $roles = Role::whereIn('id', $roleIds)->get();

        $groupedRoles = $roles->groupBy('team_id');

        foreach ($groupedRoles as $teamId => $rolesInTeam) {
            $roleNames = $rolesInTeam->pluck('name')->toArray();

            setPermissionsTeamId($teamId);

            $user->syncRoles($roleNames);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $isRandomPassword = empty($validated['password']);
            $plainPassword = $isRandomPassword ? Str::random(10) : $validated['password'];

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
            ]);

            $this->syncUserRoles($user, $validated['role_id']);

            event(new Registered($user));

            if ($isRandomPassword) {
                Mail::to($user->email)->send(new NewUserPasswordMail($user, $plainPassword));
            }

            DB::commit();

            return redirect()->route('user.index')
                ->with('success', 'Tạo tài khoản nhân viên thành công! Một email xác thực hệ thống đã được gửi đi.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể tạo tài khoản nhân viên: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            $this->syncUserRoles($user, $validated['role_id']);

            DB::commit();

            return redirect()->route('user.index')
                ->with('success', 'Cập nhật thông tin tài khoản nhân viên thành công!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể cập nhật tài khoản nhân viên: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Factory|View
    {
        $user = User::with('allRoles.team')->findOrFail($id);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Factory|View
    {
        $currentUser = Auth::user();

        $userTeamIds = DB::table('user_roles')
            ->where('model_id', $currentUser->id)
            ->where('model_type', get_class($currentUser))
            ->pluck('team_id')
            ->unique()
            ->filter()
            ->toArray();

        $roles = Role::whereIn('team_id', $userTeamIds)->get();

        $assignedRoleIds = DB::table('user_roles')
            ->where('model_id', $user->id)
            ->where('model_type', User::class)
            ->pluck('role_id')
            ->toArray();

        return view('user.edit', compact('user', 'roles', 'assignedRoleIds'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
//        if (!auth()->user()->can('delete-users')) {
//            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện thao tác này!'], 403);
//        }

        try {
            DB::transaction(function () use ($id) {
                /** @var User $user */
                $user = User::findOrFail($id);
                $user->syncRoles([]);

                $user->delete();
            });

            return response()->json(['success' => true, 'message' => 'Xóa tài khoản thành công!']);

        } catch (Exception $e) {
            Log::error("Lỗi xóa user ID $id: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống, không thể xóa!'], 500);
        }
    }
}
