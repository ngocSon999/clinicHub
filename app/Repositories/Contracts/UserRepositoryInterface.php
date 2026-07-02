<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function updateLastBranch(int $userId, int $branchId): bool;
}
