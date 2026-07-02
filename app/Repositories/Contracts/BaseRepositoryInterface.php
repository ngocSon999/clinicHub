<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function query(): Builder;

    public function all(array $columns = ['*']): Collection;

    public function paginate(
        Builder $query,
        int $perPage = 15,
        array $columns = ['*'],
        string $pageName = 'page',
        ?int $page = null
    );

    public function find(int|string $id): ?Model;

    public function findOrFail(int|string $id): Model;

    public function create(array $data): Model;

    public function update(int|string $id, array $data): bool;

    public function delete(int|string $id): bool;

    public function first(array $conditions = []): ?Model;

    public function firstOrCreate(array $attributes, array $values = []): Model;

    public function updateOrCreate(array $attributes, array $values = []): Model;

    public function exists(array $conditions): bool;
}
