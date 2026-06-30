<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'role_id' => 'required|array',
            'role_id.*' => 'required|exists:roles,id',
            'password' => $userId ? 'nullable|string|min:8|confirmed' : 'nullable|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Địa chỉ email không được để trống.',
            'email.unique'   => 'Địa chỉ email này đã được sử dụng.',
            'role_id.required' => 'Vui lòng chọn ít nhất một vai trò.',
            'password.min'   => 'Mật khẩu phải từ 8 ký tự trở lên.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }
}
