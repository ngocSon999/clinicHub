@extends('layouts.master')

@section('title', 'Đăng nhập hệ thống - ClinicHub')

@section('styles')
    <style>
        /* Tối ưu hóa giao diện chia đôi màn hình */
        .login-wrapper {
            min-height: 100vh;
        }
        .login-aside {
            background-image: linear-gradient(135deg, rgba(13, 110, 253, 0.85), rgba(10, 68, 161, 0.9)),
            url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1000&q=80');
            background-size: cover;
            background-position: center;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .btn-login {
            background-color: #0d6efd;
            border: none;
            transition: all 0.2s ease-in-out;
        }
        .btn-login:hover {
            background-color: #0b5ed7;
            transform: translateY(-1px);
        }
        .brand-logo {
            width: 45px;
            height: 45px;
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 24px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-0 login-wrapper">

            <div class="col-12 col-lg-5 d-flex align-items-center bg-white px-4 px-sm-5 py-5">
                <div class="w-100 mx-auto" style="max-width: 420px;">

                    <div class="mb-5">
                        <div class="brand-logo mb-3">🏥</div>
                        <h2 class="fw-bold text-dark tracking-tight mb-1">Chào mừng trở lại!</h2>
                        <p class="text-muted small">Vui lòng đăng nhập để quản lý phòng khám của bạn.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="branch_id" class="form-label small fw-semibold text-secondary">Chọn chi nhánh làm việc</label>
                            <select name="branch_id" id="branch_id" class="form-control form-control-lg fs-6">
                                <option value="">--- Chọn chi nhánh ---</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-secondary">Địa chỉ Email</label>
                            <input type="email" name="email" id="email"
                                   class="form-control form-control-lg fs-6 @error('email') is-invalid @enderror"
                                   placeholder="bacsi@clinichub.vn"
                                   value="{{ old('email') }}" required autofocus>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label for="password" class="form-label small fw-semibold text-secondary mb-0">Mật khẩu</label>
                                <a href="#" class="text-decoration-none small text-primary fw-medium">Quên mật khẩu?</a>
                            </div>
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-lg fs-6 @error('password') is-invalid @enderror"
                                   placeholder="••••••••" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label text-muted small" for="remember">
                                    Duy trì đăng nhập
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-login w-100 py-2.5 fs-6 fw-semibold text-white rounded-3 shadow-sm">
                            Đăng nhập hệ thống
                        </button>
                    </form>

{{--                    <div class="text-center mt-5">--}}
{{--                        <p class="text-muted small mb-0">--}}
{{--                            Chưa có không gian làm việc?--}}
{{--                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">Đăng ký phòng khám mới</a>--}}
{{--                        </p>--}}
{{--                    </div>--}}

                </div>
            </div>

            <div class="col-lg-7 d-none d-lg-flex flex-column justify-content-between login-aside p-5 text-white">
                <div class="d-flex align-items-center">
                    <span class="fs-4 fw-bold me-2">🏥</span>
                    <span class="fw-bold tracking-wider text-uppercase" style="letter-spacing: 1px;">ClinicHub Ecosystem</span>
                </div>

                <div style="max-width: 550px;" class="mb-5">
                    <h1 class="display-5 fw-bold lh-sm mb-3 text-white">Nâng tầm trải nghiệm <br>quản lý y khoa thế hệ mới.</h1>
                    <p class="lead text-white-50">Tối ưu hóa quy trình tiếp đón bệnh nhân, số hóa bệnh án điện tử và quản lý đơn thuốc chính xác chỉ trên một nền tảng duy nhất.</p>
                </div>

                <div class="d-flex justify-content-between small text-white-50 border-top border-white-10 pt-3">
                    <span>&copy; {{ date('Y') }} ClinicHub Software Inc.</span>
                    <span>Hotline hỗ trợ: 1900 xxxx</span>
                </div>
            </div>

        </div>
    </div>
@endsection
