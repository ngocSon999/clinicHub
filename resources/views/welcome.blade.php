<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ClinicHub - Hệ Thống Quản Lý Phòng Khám Toàn Diện</title>

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
        .hero-section {
            background: linear-gradient(135deg, #ffffff 50%, #e7f1ff 100%);
            padding: 80px 0;
        }
        .feature-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.075) !important;
        }
        .icon-box {
            width: 60px;
            height: 60px;
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        .nav-link {
            font-weight: 500;
            color: #495057;
            transition: color 0.15s ease-in-out;
        }
        .nav-link:hover {
            color: #0d6efd;
        }
        .hero-img {
            max-height: 400px;
            object-fit: cover;
            border-radius: 20px;
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
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top border-bottom py-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary fs-3 d-flex align-items-center" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
            <span class="me-2">🏥</span> ClinicHub
        </a>
        <button class="navbar-toggler border-0 shadow-none px-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link px-3" href="#features">Tính năng</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#search-patient">Tra cứu bệnh nhân</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#about">Về chúng tôi</a></li>
            </ul>
            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown">
                        <a class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark border-0 px-3 py-1 rounded-2 bg-light-hover" href="#" id="userNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-user-circle fs-5 me-2 text-secondary"></i>

                            <div class="d-inline-flex flex-column text-start me-1">
                                <span class="fw-semibold small lh-base">{{ Auth::user()->name ?? 'Test User' }}</span>
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
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary fw-semibold px-4 rounded-pill">
                        <i class="fas fa-sign-in-alt me-1.5 small"></i> Đăng nhập
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main>
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="badge text-primary fw-semibold px-3 py-2 rounded-pill mb-3" style="background-color: rgba(13, 110, 253, 0.1);">🚀 Giải pháp số hóa y tế nội bộ</span>
                    <h1 class="display-5 fw-bold text-dark aristocratic-title lh-sm mb-3">
                        Nền tảng vận hành <br><span class="text-primary">Phòng khám chuyên nghiệp</span>
                    </h1>
                    <p class="lead text-muted mb-4">
                        Hệ thống tối ưu quy trình tiếp đón, quản lý nhân sự y tế, đồng bộ hồ sơ bệnh án điện tử nội bộ và hỗ trợ bệnh nhân kiểm tra đơn thuốc trực tuyến nhanh chóng.
                    </p>
                    <div class="d-flex justify-content-center justify-content-lg-start gap-3 flex-wrap">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-semibold px-4 rounded-3 shadow">
                            Cổng đăng nhập nhân viên
                        </a>
                        <a href="#search-patient" class="btn btn-outline-secondary btn-lg fw-semibold px-4 rounded-3">
                            Tra cứu hồ sơ bệnh nhân
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=600&q=80" alt="ClinicHub Panel" class="img-fluid hero-img shadow-lg border">
                </div>
            </div>
        </div>
    </header>

    <section id="search-patient" class="py-5 bg-light border-top border-bottom">
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <div class="icon-box mb-3 fs-3">🔍</div>
                    <h3 class="fw-bold text-dark mb-2">Tra cứu lịch sử khám & Đơn thuốc</h3>
                    <p class="text-muted small mb-4" style="max-width: 540px; margin: 0 auto;">
                        Bệnh nhân có thể tự tra cứu thông tin đơn thuốc và hướng dẫn dặn dò của bác sĩ bằng cách nhập chính xác Mã số bệnh nhân được cấp khi thăm khám.
                    </p>

                    <form action="#" method="GET" class="input-group input-group-lg shadow-sm mb-2" style="max-width: 550px; margin: 0 auto;">
                        <input type="text" name="patient_code" class="form-control fs-6" placeholder="Nhập mã bệnh nhân của bạn (Ví dụ: BN-2026-01)..." required>
                        <button class="btn btn-primary fs-6 fw-medium px-4" type="submit">
                            Tra cứu ngay
                        </button>
                    </form>
                    <small class="text-muted d-block mt-2">Dữ liệu được bảo mật tuyệt đối theo tiêu chuẩn hồ sơ y tế.</small>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5 bg-white">
        <div class="container py-4">
            <div class="text-center mx-auto mb-5" style="max-width: 600px;">
                <h2 class="fw-bold text-dark">Hệ sinh thái số hóa phòng khám</h2>
                <p class="text-muted">Đơn giản hóa nghiệp vụ hàng ngày của điều dưỡng, bác sĩ và nhân viên quản trị nội bộ.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 p-4 feature-card shadow-sm">
                        <div class="card-body">
                            <div class="icon-box mb-4">📑</div>
                            <h4 class="fw-bold text-dark mb-3">Hồ sơ bệnh án điện tử</h4>
                            <p class="text-muted mb-0">Lưu trữ thông tin chi tiết từng ca khám, triệu chứng lâm sàng, tóm tắt bệnh án giúp theo dõi tình trạng dài hạn dễ dàng.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-4 feature-card shadow-sm">
                        <div class="card-body">
                            <div class="icon-box mb-4">📦</div>
                            <h4 class="fw-bold text-dark mb-3">Cổng kết nối bệnh nhân</h4>
                            <p class="text-muted mb-0">Hỗ trợ bệnh nhân truy cập thông tin từ xa bằng ID định danh, nâng cao tính minh bạch và uy tín dịch vụ.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<footer id="about" class="bg-dark text-white-50 py-5">
    <div class="container">
        <div class="row g-4 text-center text-md-start">
            <div class="col-md-6">
                <span class="fw-bold text-white fs-4">🏥 ClinicHub</span>
                <p class="small mt-2 text-white-50" style="max-width: 350px;">
                    Hệ thống quản lý hoạt động khám chữa bệnh nội bộ, mang lại sự tiện nghi và chính xác cho các cơ sở y tế hiện đại.
                </p>
            </div>
            <div class="col-md-6 text-md-end align-self-center">
                <p class="small mb-0">&copy; {{ date('Y') }} ClinicHub Platform. Bảo lưu mọi quyền nội bộ.</p>
                <p class="small text-muted mb-0">Hệ thống phân phối dành riêng cho nhân viên y tế.</p>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset('/js/library/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
