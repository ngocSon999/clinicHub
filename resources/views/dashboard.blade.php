@extends('layouts.master')

@section('title', 'Bảng Điều Khiển - ClinicHub')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
                    <div class="container-fluid py-5">
                        <h1 class="display-5 fw-bold text-primary">Xin chào Bác sĩ! 🏥</h1>
                        <p class="col-md-8 fs-4 text-muted mt-3">Chào mừng bạn đến với hệ thống quản lý phòng khám ClinicHub. Tại đây bạn có thể quản lý lịch hẹn, thông tin bệnh nhân và kê đơn thuốc dễ dàng.</p>
                        <button class="btn btn-primary btn-lg mt-3" type="button">Xem lịch hẹn hôm nay</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
