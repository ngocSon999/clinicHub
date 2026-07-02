<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleService extends BaseDataTableService
{
    protected RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getList(Request $request): array
    {
        $query = $this->roleRepository->query()
            ->with(['permissions', 'team']);

        /** @var User $user */
        $user = Auth::user();
        if (!$user->hasRole('super_admin')) {
            $query->where('name', '!=', 'super_admin');
        }

        return $this->paginate(
            $query,
            $request,
            [
                'roles.id',
                'roles.team_id',
                'roles.name',
                'roles.created_at'
            ]
        );
    }

    public function store(array $data): void
    {
        DB::transaction(function () use ($data) {
            /** @var Role $role */
            $role = $this->roleRepository->create([
                'name' => $data['name'],
                'team_id' => $data['branch_id'],
                'guard_name' => 'web',
            ]);

            $permissions = $data['permissions'] ?? [];

            if ($permissions) {
                $role->syncPermissions($permissions);
            }
        });
    }

    public function update(Role $role, array $data): void
    {
        DB::transaction(function () use ($role, $data) {

            $this->roleRepository->update($role->id,
                [
                    'name' => $data['name'],
                ]
            );

            $role->syncPermissions(
                $data['permissions'] ?? []
            );
        });
    }
}
