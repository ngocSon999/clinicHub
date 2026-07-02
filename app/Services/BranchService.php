<?php

namespace App\Services;

use App\Repositories\Contracts\BranchRepositoryInterface;
use Illuminate\Http\Request;

class BranchService extends BaseDataTableService
{
    protected BranchRepositoryInterface $branchRepository;

    public function __construct(BranchRepositoryInterface $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function getList(Request $request): array
    {
        $query = $this->branchRepository->query();

        return $this->paginate(
            $query,
            $request,
            [
                'id',
                'code',
                'name',
                'phone',
                'full_address',
                'status',
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
