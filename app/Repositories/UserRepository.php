<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function updateLastBranch(int $userId, int $branchId): bool
    {
        return $this->update($userId, [
            'last_branch_id' => $branchId,
        ]);
    }


//    public function query(): Builder
//    {
//        return $this->model->newQuery()
//            ->with(['allRoles.team'])
//            ->select([
//                'users.id',
//                'users.name',
//                'users.email',
//                'users.created_at',
//            ]);
//    }
}
