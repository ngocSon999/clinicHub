<?php

namespace App\Services;

use Spatie\Permission\DefaultTeamResolver;

class PermissionTeamResolve extends DefaultTeamResolver
{
    public function getPermissionsTeamId(): int|string|null
    {
        return session('current_branch_id');
    }

    public function setPermissionsTeamId($id): void
    {
        session(['current_branch_id' => $id]);
    }
}
