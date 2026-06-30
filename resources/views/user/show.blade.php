@extends('layouts.master')

@section('title', 'Chi tiết tài khoản - ClinicHub')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4 px-4">
            <div>
                <h5 class="mb-1 fw-bold text-dark">Chi Tiết Tài Khoản</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Thiết lập</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}" class="text-decoration-none">Nhân viên</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('user.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted">Họ và tên nhân viên</label>
                        <div class="p-2 border rounded bg-light">{{ $user->name }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted">Địa chỉ Email</label>
                        <div class="p-2 border rounded bg-light">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted">Vai trò & Phân quyền chi nhánh</label>
                    <div class="p-2 border rounded bg-light">
                        @forelse($user->allRoles as $role)
                            <span class="badge bg-primary me-1">{{ $role->name }} ({{ $role->team?->name ?? 'Mặc định' }})</span>
                        @empty
                            <span class="text-muted">Chưa được phân quyền</span>
                        @endforelse
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label text-muted">Ngày tạo</label>
                        <div class="p-2 border rounded bg-light">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary px-4">
                        <i class="fas fa-edit me-1"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
