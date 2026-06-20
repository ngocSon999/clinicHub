<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        return view('role.index');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getList(Request $request): JsonResponse
    {
        $perPage = $request->input('length', 10);
        $start = $request->input('start', 0);
        $page = ($start / $perPage) + 1;

        $request->merge(['page' => $page]);

        $searchValue = $request->input('search.value');

        $query = Role::with('permissions')
            ->select(['id', 'team_id', 'name', 'created_at']);

        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('team_id', 'LIKE', "%{$searchValue}%");
            });
        }

        $roles = $query->paginate($perPage);

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $roles->total(),
            'recordsFiltered' => $roles->total(),
            'data'            => $roles->items(),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {

    }
}
