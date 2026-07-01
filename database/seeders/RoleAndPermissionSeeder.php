<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $modules = [
            'role', 'user', 'branch',
            'appointment', 'clinical', 'billing', 'inventory'
        ];

        $actions = ['list', 'show', 'create', 'edit', 'delete'];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::findOrCreate("{$module}.{$action}", 'web');
            }
        }

        $superAdmin = Role::findOrCreate('supper_admin', 'web');
        $superAdmin->syncPermissions(Permission::all());
    }
}
