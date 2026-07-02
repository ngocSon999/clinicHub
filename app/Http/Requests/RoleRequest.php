<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Role|null $role */
        $role = $this->route('role');

        $teamId = $role?->team_id ?? $this->input('branch_id');

        $rule = Rule::unique('roles', 'name')
            ->where(fn ($query) => $query->where('team_id', $teamId));

        if ($role) {
            $rule->ignore($role->id);
        }

        return [
            'branch_id' => $role ? ['nullable'] : ['required', 'integer'],
            'name' => [
                'required',
                'string',
                'max:255',
                $rule,
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required_without' => 'Vui lòng chọn chi nhánh.',
            'name.required' => 'Vui lòng nhập tên vai trò.',
            'name.unique' => 'Tên vai trò này đã tồn tại trong chi nhánh của bạn.',
        ];
    }
}
