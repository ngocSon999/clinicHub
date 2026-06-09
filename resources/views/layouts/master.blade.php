<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ClinicHub - Quản Lý Phòng Khám')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
        .nav-link {
            transition: color 0.15s ease-in-out;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .dropdown-item.active {
            background-color: #0d6efd;
        }
        .navbar-nav .nav-link {
            color: #6c757d !important;
            font-size: 14px;
            padding-top: 8px;
            padding-bottom: 8px;
            transition: all 0.2s ease-in-out;
        }
        .navbar-nav .nav-link:hover {
            color: #0d6efd !important;
            background-color: #f1f7ff;
        }
        .navbar-nav .nav-link.active {
            color: #0d6efd !important;
            background-color: #e7f1ff !important;
        }
        .dropdown-menu {
            border-radius: 12px !important;
            padding: 6px !important;
        }
        .dropdown-item {
            border-radius: 8px !important;
            font-size: 14px;
        }

        @media (max-width: 992px) {
            .dropdown-menu-end {
                left: 0 !important;
                right: auto !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

@auth
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom py-2">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center fw-bold text-dark fs-4" href="{{ route('dashboard') }}">
                <span class="me-2" style="filter: drop-shadow(0px 2px 4px rgba(13,110,253,0.2));">🏥</span>
                <span style="background: linear-gradient(45deg, #0d6efd, #0a58ca); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">ClinicHub</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none px-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-1">
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-2 fw-medium {{ request()->routeIs('dashboard') ? 'active bg-light text-primary fw-semibold' : 'text-secondary' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-pie me-1.5 small"></i>Tổng quan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-2 fw-medium text-secondary" href="#">
                            <i class="fas fa-user-injured me-1.5 small"></i>Bệnh nhân
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-2 fw-medium text-secondary" href="#">
                            <i class="fas fa-calendar-alt me-1.5 small"></i>Lịch hẹn
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-2 fw-medium text-secondary" href="#">
                            <i class="fas fa-prescriptions me-1.5 small"></i>Đơn thuốc
                        </a>
                    </li>

                    @php
                        $locale = App::getLocale();
                    @endphp
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle text-secondary fw-medium d-flex align-items-center gap-1 px-3" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-globe text-muted me-1"></i> {{ $locale === 'vi' ? 'Tiếng Việt' : 'English' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border border-light shadow-sm mt-2" aria-labelledby="langDropdown">
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center py-2 fw-medium {{ $locale === 'vi' ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('locale', ['locale' => 'vi']) }}">
                                    <span>🇻🇳 Tiếng Việt</span>
                                    <i class="fas fa-check small ms-3"></i>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center py-2 fw-medium {{ $locale === 'en' ? 'active bg-primary text-white' : 'text-dark' }}" href="{{ route('locale', ['locale' => 'en']) }}">
                                    <span>🇺🇸 English</span>
                                    <i class="fas fa-check small ms-3"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="d-flex align-items-center border-top border-light pt-3 pt-lg-0 mt-3 mt-lg-0">
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark border-0 px-3 py-1 rounded-2 bg-light-hover" href="#" id="userNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-user-circle fs-5 me-2 text-secondary"></i>

                            <div class="d-inline-flex flex-column text-start me-1">
                                <span class="fw-semibold small lh-base">{{ Auth::user()->name ?? 'Test User' }}</span>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border border-light shadow-sm mt-2 animate slideIn" aria-labelledby="userNavbarDropdown" style="border-radius: 12px; padding: 6px; min-width: 210px;">
                            <li class="px-3 py-2.5 border-bottom border-light mb-1 d-sm-none bg-light rounded-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="far fa-user-circle text-secondary fs-5"></i>
                                    <strong class="text-dark small">{{ Auth::user()->name ?? 'Test User' }}</strong>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-secondary" href="{{ url('/dashboard') }}" style="border-radius: 8px;">
                                    <i class="fas fa-desktop text-muted" style="width: 16px;"></i>
                                    <span>Bàn làm việc</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-secondary" href="#" style="border-radius: 8px;">
                                    <i class="fas fa-user-cog text-muted" style="width: 16px;"></i>
                                    <span>Hồ sơ cá nhân</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider bg-light"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger" style="border-radius: 8px;">
                                        <i class="fas fa-sign-out-alt" style="width: 16px;"></i>
                                        <span>{{ __('Logout') }}</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
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
                <div class="col-md-6 text-center text-md-start small mb-2 mb-md-0">
                    &copy; {{ date('Y') }} <strong>ClinicHub</strong>. Tất cả quyền được bảo lưu.
                </div>
                <div class="col-md-6 text-center text-md-end small text-secondary">
                    Hệ thống quản lý nội bộ <span class="badge bg-secondary-soft text-dark border ms-1">v1.0.0</span>
                </div>
            </div>
        </div>
    </footer>
@endauth

<script src="{{ asset('/js/library/bootstrap.bundle.min.js') }}"></script>
@yield('scripts')
</body>
</html>
