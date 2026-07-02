<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserService extends BaseDataTableService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getList(Request $request): array
    {
        $query = $this->userRepository->query()
            ->with(['allRoles.team'])
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.created_at'
            ]);

        return $this->paginate(
            $query,
            $request,
            [
                'id',
                'name',
                'email',
                'created_at'
            ],
            [
                'status',
                'branch_id',
                'created_by',
            ]
        );
    }
}
