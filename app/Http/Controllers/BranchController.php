<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Models\Branch;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Exception;

class BranchController extends Controller
{
    public function index(): Factory|View
    {
        return view('branch.index');
    }

    public function create(): Factory|View
    {
        $jsonPath = storage_path('app/json/address.json');

        $addressData = [];
        if (File::exists($jsonPath)) {
            $addressData = json_decode(File::get($jsonPath), true);
        }

        $provinces = $addressData['province'] ?? [];

        return view('branch.create', compact('provinces'));
    }

    public function getList(Request $request): JsonResponse
    {
        $perPage = $request->input('length', 10);
        $start = $request->input('start', 0);
        $page = ($start / $perPage) + 1;

        $request->merge(['page' => $page]);

        $searchValue = $request->input('search.value');

        $query = Branch::select([
            'id',
            'code',
            'name',
            'phone',
            'full_address',
            'status',
            'created_at'
        ]);

        if (!empty($searchValue)) {
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                    ->orWhere('code', 'LIKE', "%{$searchValue}%")
                    ->orWhere('phone', 'LIKE', "%{$searchValue}%")
                    ->orWhere('full_address', 'LIKE', "%{$searchValue}%");
            });
        }

        if ($request->has('order')) {
            $columnIndex = $request->input('order.0.column');
            $columnDirection = $request->input('order.0.dir', 'asc');
            $columnName = $request->input("columns.{$columnIndex}.data");

            $allowableColumns = ['code', 'name', 'phone', 'full_address', 'status', 'created_at'];

            if (in_array($columnName, $allowableColumns)) {
                $query->orderBy($columnName, $columnDirection);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $branches = $query->paginate($perPage);

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $branches->total(),
            'recordsFiltered' => $branches->total(),
            'data'            => $branches->items(),
        ]);
    }

    public function getCommunes(Request $request): JsonResponse
    {
        $provinceId = $request->get('province_id');

        $jsonPath = storage_path('app/json/address.json');
        if (!File::exists($jsonPath)) {
            return response()->json([]);
        }

        $addressData = json_decode(File::get($jsonPath), true);
        $communes = $addressData['commune'] ?? [];

        $filteredCommunes = array_filter($communes, function ($commune) use ($provinceId) {
            return $commune['idProvince'] == $provinceId;
        });

        return response()->json(array_values($filteredCommunes));
    }

    public function store(BranchRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $validated['code'] = strtoupper($validated['code']);
            $validated['full_address'] = $this->getFullAddress($request);

            Branch::create($validated);

            DB::commit();

            return redirect()->route('branch.index')
                ->with('success', 'Tạo chi nhánh mới thành công!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể thêm mới chi nhánh: ' . $e->getMessage());
        }
    }

    public function edit(Branch $branch): Factory|View
    {
        $jsonPath = storage_path('app/json/address.json');

        $provinces = [];
        if (File::exists($jsonPath)) {
            $addressData = json_decode(File::get($jsonPath), true);
            $provinces = $addressData['province'] ?? [];
        }

        return view('branch.edit', compact('branch', 'provinces'));
    }

    protected function getFullAddress(BranchRequest $request): string
    {
        $provinceName = '';
        $communeName = '';

        if ($request->filled('province_id') || $request->filled('commune_id')) {
            $jsonPath = storage_path('app/json/address.json');
            if (File::exists($jsonPath)) {
                $addressData = json_decode(File::get($jsonPath), true);

                if ($request->filled('province_id')) {
                    $province = collect($addressData['province'])->firstWhere('idProvince', $request->province_id);
                    $provinceName = $province['name'] ?? '';
                }

                if ($request->filled('commune_id')) {
                    $commune = collect($addressData['commune'])->firstWhere('idCommune', $request->commune_id);
                    $communeName = $commune['name'] ?? '';
                }
            }
        }

        $addressParts = array_filter([$request->address_detail, $communeName, $provinceName]);

        return implode(', ', $addressParts);
    }

    public function update(BranchRequest $request, Branch $branch): RedirectResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $validated['code'] = strtoupper($validated['code']);
            $validated['full_address'] = $this->getFullAddress($request);

            $branch->update($validated);

            DB::commit();

            return redirect()->route('branch.index')
                ->with('success', 'Cập nhật thông tin chi nhánh thành công!');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Không thể cập nhật chi nhánh: ' . $e->getMessage());
        }
    }

    public function destroy(Branch $branch): JsonResponse
    {
        DB::beginTransaction();

        try {
            if ($branch->users()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa! Chi nhánh này đang có nhân viên đang hoạt động hoặc được phân quyền.'
                ], 422);
            }

            $branch->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa chi nhánh thành công!'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            if (str_contains($e->getMessage(), '23000')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa chi nhánh này vì đã có dữ liệu phát sinh (lịch hẹn, hóa đơn...) liên quan trên hệ thống!'
                ], 422);
            }

            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    public function switchBranch(Request $request): RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        session(['current_branch_id' => $request->branch_id]);

        return back()->with('success', 'Đã chuyển chi nhánh thành công!');
    }
}
