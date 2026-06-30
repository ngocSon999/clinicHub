@extends('layouts.master')

@section('title', 'Tạo tài khoản mới - ClinicHub')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4 px-4">
            <div>
                <h5 class="mb-1 fw-bold text-dark">Thêm Mới Tài Khoản</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Thiết lập</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}" class="text-decoration-none">Nhân viên</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label text-dark">Họ và tên nhân viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Nguyễn Văn A" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="example@clinichub.com" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="role_id" class="form-label text-dark">Vai trò & Phân quyền chi nhánh <span class="text-danger">*</span></label>
                        <select class="form-select user-select2-multiple select2-multiple @error('role_id') is-invalid @enderror" id="role_id" name="role_id[]" multiple="multiple" data-placeholder="-- Chọn các vai trò phân quyền cho nhân viên --" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ is_array(old('role_id')) && in_array($role->id, old('role_id')) ? 'selected' : '' }}>
                                    {{ $role->name }} ({{ $role->team?->name ?? 'Mặc định' }})
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label text-dark">Mật khẩu</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Nhập mật khẩu từ 8 ký tự">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label text-dark">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu để xác nhận">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('user.index') }}" class="btn btn-light border px-4">Hủy</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-plus-circle me-1"></i> Tạo tài khoản
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.user-select2-multiple').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: "-- Chọn các vai trò phân quyền cho nhân viên --",
                allowClear: true,
                closeOnSelect: false,
                dropdownAutoWidth: true
            });
        });
    </script>
@endsection
