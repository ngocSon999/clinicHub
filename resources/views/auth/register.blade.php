@extends('layouts.master')

@section('title', 'Tạo tài khoản mới - ClinicHub')

@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 px-4 py-4My">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-primary">Đăng Ký Tài Khoản</h2>
                            <p class="text-muted small">Khởi tạo không gian làm việc cho phòng khám của bạn</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Tên bác sĩ / quản trị viên -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium text-secondary">Họ và tên</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium text-secondary">Địa chỉ Email</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mật khẩu -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-medium text-secondary">Mật khẩu</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium text-secondary">Xác nhận mật khẩu</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mt-2">
                                Tạo tài khoản
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">Đã có tài khoản? <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
