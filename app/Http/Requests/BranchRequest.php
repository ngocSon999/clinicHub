<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $branchId = $this->route('branch')?->id;

        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $branchId,
            'phone' => 'nullable|string|max:20',
            'province_id' => 'nullable|string',
            'commune_id' => 'nullable|string',
            'address_detail' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên chi nhánh.',
            'code.required' => 'Vui lòng nhập mã chi nhánh.',
            'code.unique'   => 'Mã chi nhánh này đã tồn tại trên hệ thống.',
        ];
    }
}
