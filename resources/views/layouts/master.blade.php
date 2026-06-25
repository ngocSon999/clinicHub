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
    <link href="{{ asset('/css/library/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

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
        .custom-dropdown .dropdown-item:hover {
            background-color: #0d6efd !important;
            color: #ffffff !important;
        }

        .custom-dropdown .dropdown-item:hover .text-muted {
            color: #ffffff !important;
        }
        /* Custom css cho thông báo có thanh chạy đếm ngược */
        .global-alert {
            position: relative;
            overflow: hidden; /* Để thanh chạy không bị tràn góc bo tròn */
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
            transition: opacity 0.4s ease, transform 0.4s ease, margin 0.4s ease, max-height 0.4s ease;
        }

        .global-alert::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            animation: alert-progress 5s linear forwards;
        }

        .alert-success::after {
            background-color: #198754;
        }
        .alert-danger::after {
            background-color: #dc3545;
        }

        @keyframes alert-progress {
            from {
                width: 100%;
            }
            to {
                width: 0;
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
                        <a class="nav-link px-3 rounded-2 fw-medium {{ request()->routeIs('dashboard') ? 'active bg-light text-primary' : 'text-secondary' }}" href="{{ route('dashboard') }}">
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
                            <i class="fas fa-prescription me-1.5 small"></i>Đơn thuốc
                        </a>
                    </li>

                    @php
                        $locale = App::getLocale();
                    @endphp
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle text-secondary fw-medium d-flex align-items-center gap-1 px-3" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-globe text-muted me-1.5 small"></i> {{ $locale === 'vi' ? 'Tiếng Việt' : 'English' }}
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
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle text-secondary fw-medium d-flex align-items-center gap-1 px-3" href="#" id="settingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-cog me-1.5 small"></i> {{ __('Setting') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border border-light shadow-sm mt-2" aria-labelledby="settingDropdown">
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center py-2 fw-medium {{ request()->routeIs('role.index') ? 'active' : 'text-dark' }}" href="{{ route('role.index') }}">
                                    <span>{{ __('Role') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                @auth()
                    <div class="d-flex align-items-center border-top border-light pt-3 pt-lg-0 mt-3 mt-lg-0">
                        <div class="dropdown">
                            <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark border-0 px-3 py-1 rounded-2 bg-light-hover" href="#" id="userNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="far fa-user-circle fs-5 me-2 text-secondary"></i>

                                <div class="d-inline-flex flex-column text-start me-1">
                                    <span class="small lh-base">{{ Auth::user()->name ?? 'Test User' }}</span>
                                </div>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end custom-dropdown border border-light shadow-sm mt-2 animate slideIn" aria-labelledby="userNavbarDropdown" style="border-radius: 12px; padding: 6px; min-width: 210px;">
                                <li class="px-3 py-2.5 border-bottom border-light mb-1 d-sm-none bg-light rounded-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-user-circle text-secondary fs-5"></i>
                                        <strong class="text-dark small">{{ Auth::user()->name ?? 'Test User' }}</strong>
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-secondary" href="{{ url('/dashboard') }}" style="border-radius: 8px;">
                                        <i class="fas fa-desktop text-muted" style="width: 16px;"></i>
                                        <span>{{ __('Desk') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-secondary" href="{{ route('profile.edit') }}" style="border-radius: 8px;">
                                        <i class="fas fa-user-cog text-muted" style="width: 16px;"></i>
                                        <span>{{ __('Personal Profile') }}</span>
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
                @endauth
            </div>
        </div>
    </nav>
@endauth

<main class="py-4">
    @include('layouts.message')
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

<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
            <div class="modal-body p-4 text-center">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-circle fa-3x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Xác nhận xóa?</h5>
                <p class="text-secondary small mb-4">
                    Bạn có chắc chắn muốn xóa dữ liệu <strong id="delete-item-name" class="text-dark"></strong>? <br>
                    Hành động này không thể hoàn tác.
                </p>

                {{-- Form này không fix cứng action nữa, JS sẽ điền động --}}
                <form id="global-delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light border px-4 rounded-3" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger px-4 rounded-3">
                            <i class="fas fa-trash-alt me-1"></i> Đồng ý xóa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/js/library/jquery.min.js') }}"></script>
<script src="{{ asset('/js/library/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/js/library/dataTables.min.js') }}"></script>
<script src="{{ asset('/js/library/dataTables.bootstrap5.min.js') }}"></script>

<script>
    window.appLocale = "{{ app()->getLocale() }}";
    $(document).on("keydown", "form", function(event) {
        if (event.key === 'Enter') {
            if (event.target.tagName !== 'TEXTAREA') {
                event.preventDefault();
                return false;
            }
        }
    });

    // config datatable
    const currentLocale = "{{ app()->getLocale() }}";
    const languageUrl = currentLocale === 'vi'
        ? "{{ asset('/library/json/vi.json') }}"
        : "";

    window.DataTableConfig = {
        lengthMenu: [
            {!! Illuminate\Support\Js::from(config('define.datatable.length_menu', [10, 25, 50, 100, 500, -1])) !!},
            [10, 25, 50, 100, 500, "{{ __('All') }}"]
        ],
        pageLength: {{ config('define.datatable.default_page_length', 10) }},
        language: {
            url: languageUrl,
            lengthMenu: "_MENU_ {{ __('record on page') }}",
        }
    };

    $(document).ready(function() {
        $(document).on('click', '.btn-delete-trigger', function() {
            const deleteUrl = $(this).data('url');
            const itemName = $(this).data('name');

            $('#global-delete-form').attr('action', deleteUrl);
            $('#delete-item-name').text(itemName || 'mục này');

            $('#deleteConfirmModal').modal('show');
        });

        setTimeout(function() {
            $(".global-alert").each(function() {
                const $alert = $(this);

                $alert.css({
                    'max-height': $alert.outerHeight() + 'px',
                    'transform': 'translateY(0)',
                    'opacity': '1'
                });

                setTimeout(function() {
                    $alert.css({
                        'opacity': '0',
                        'transform': 'translateY(-10px)',
                        'max-height': '0',
                        'padding-top': '0',
                        'padding-bottom': '0',
                        'margin-top': '0',
                        'margin-bottom': '0',
                        'border': 'none'
                    });
                }, 50);

                setTimeout(function() {
                    $alert.remove();
                }, 450);
            });
        }, 5000);
    });
</script>

@yield('scripts')
</body>
</html>
