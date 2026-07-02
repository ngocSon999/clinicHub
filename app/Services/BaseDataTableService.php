<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BaseDataTableService
{
    protected function paginate(Builder $query, Request $request, array $allowColumns, array $allowFilters = []): array
    {
        $perPage = (int) $request->input('length', 10);
        $start = (int) $request->input('start', 0);
        $page = (int) ($start / $perPage) + 1;

        if ($keyword = $request->input('search.value')) {
            $this->search($query, $keyword);
        }

        $this->filters($query, $request, $allowFilters);

        $this->sort($query, $request, $allowColumns);

        $data = $query->paginate(
            $perPage,
            $allowColumns,
            'page',
            $page
        );

        return [
            'draw' => (int)$request->draw,
            'recordsTotal' => $data->total(),
            'recordsFiltered' => $data->total(),
            'data' => $data->items(),
        ];
    }

    protected function search(Builder $query, string $keyword): void
    {
        $model = $query->getModel();

        if (!method_exists($model, 'getSearchable')) {
            return;
        }

        $columns = $model->getSearchable();

        if (empty($columns)) {
            return;
        }

        $query->where(function ($q) use ($columns, $keyword) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'LIKE', "%{$keyword}%");
            }
        });
    }

    protected function filters(Builder $query, Request $request, array $allowFilters = []): void
    {
        foreach ($allowFilters as $column) {
            if (!$request->filled($column)) {
                continue;
            }

            $value = $request->input($column);

            if (is_array($value)) {
                $query->whereIn($column, $value);
            } else {
                $query->where($column, $value);
            }
        }
    }

    protected function sort(
        Builder $query,
        Request $request,
        array $allowColumns,
        string $defaultColumn = 'id',
        string $defaultDirection = 'desc'
    ): void {

        $columnIndex = (int) $request->input('order.0.column', -1);

        $column = $request->input("columns.$columnIndex.data");

        $direction = strtolower(
            $request->input('order.0.dir', $defaultDirection)
        );

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = $defaultDirection;
        }

        if (!in_array($column, $allowColumns, true)) {
            $column = $defaultColumn;
        }

        $query->orderBy($column, $direction);
    }
}
