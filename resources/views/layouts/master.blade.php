<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ClinicHub - Quản Lý Phòng Khám')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('/css/library/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }
    </style>
    @yield('styles')
</head>
<body>

@auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <span class="fs-4 me-2">🏥</span> ClinicHub
            </a>

            <button class="navbar-expand-lg navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-3">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">Tổng quan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Bệnh nhân</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Lịch hẹn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Đơn thuốc</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center text-white">
                    <span class="me-3 small">Bác sĩ: <strong class="text-warning">{{ Auth::user()->name ?? 'Admin' }}</strong></span>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm fw-medium px-3 rounded-pill">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endauth

<main class="py-4">
    @yield('content')
</main>

@auth
    <footer class="bg-white border-top py-3 mt-auto text-muted">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start small">
                    &copy; {{ date('Y') }} <strong>ClinicHub</strong>. Tất cả quyền được bảo lưu.
                </div>
                <div class="col-md-6 text-center text-md-end small">
                    Version 1.0.0
                </div>
            </div>
        </div>
    </footer>
@endauth

<script src="{{ asset('/js/library/bootstrap.bundle.min.js') }}"></script>
@yield('scripts')
</body>
</html>
