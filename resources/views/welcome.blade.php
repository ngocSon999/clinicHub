@extends('layouts.master')

@section('title', 'ClinicHub - Hệ Thống Quản Lý Phòng Khám Toàn Diện')

@section('styles')
    <style>
        /* Tối ưu giao diện Landing Page chuyên nghiệp */
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
        }
        .nav-link:hover {
            color: #0d6efd;
        }
        .hero-img {
            max-height: 400px;
            object-fit: cover;
            border-radius: 20px;
        }
    </style>
@endsection

@section('content')
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary fs-3 d-flex align-items-center" href="#">
                <span class="me-2">🏥</span> ClinicHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link px-3" href="#features">Tính năng</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#search-patient">Tra cứu bệnh nhân</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#about">Về chúng tôi</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="badge text-primary fw-semibold px-3 py-2 rounded-pill mb-3" style="background-color: rgba(13, 110, 253, 0.1);">🚀 Giải pháp số hóa y tế nội bộ</span>
                    <h1 class="display-5 fw-bold text-dark lh-sm mb-3">
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
                <div class="col-md-4">
                    <div class="card h-100 p-4 feature-card shadow-sm">
                        <div class="card-body">
                            <div class="icon-box mb-4">👥</div>
                            <h4 class="fw-bold text-dark mb-3">Phân quyền nhân sự</h4>
                            <p class="text-muted mb-0">Tạo không gian làm việc chuyên biệt cho từng bộ phận: Bác sĩ khám bệnh, Tiếp tân đón tiếp, Kế toán xuất hóa đơn.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 p-4 feature-card shadow-sm">
                        <div class="card-body">
                            <div class="icon-box mb-4">📑</div>
                            <h4 class="fw-bold text-dark mb-3">Hồ sơ bệnh án điện tử</h4>
                            <p class="text-muted mb-0">Lưu trữ thông tin chi tiết từng ca khám, triệu chứng lâm sàng, tóm tắt bệnh án giúp theo dõi tình trạng dài hạn dễ dàng.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
@endsection
