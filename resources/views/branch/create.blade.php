@extends('layouts.master')

@section('title', 'Thêm Chi Nhánh Mới - ClinicHub')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4 px-4">
            <div>
                <h5 class="mb-1 fw-bold text-dark">{{ __('Add New Branch') }}</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Thiết lập</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('branch.index') }}" class="text-decoration-none">Chi nhánh</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('branch.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <form action="{{ route('branch.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12 mb-2">
                            <label for="name" class="form-label text-dark fw-semibold">Tên Chi Nhánh <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ví dụ: Phòng khám ClinicHub Cơ sở 1..." required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="code" class="form-label text-dark fw-semibold">Mã Chi Nhánh <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Ví dụ: CN01, CN-HN..." style="text-transform: uppercase;" required>
                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="phone" class="form-label text-dark fw-semibold">Số Điện Thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Nhập số điện thoại chi nhánh">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="text-primary text-uppercase fw-bold small mb-3" style="letter-spacing: 0.5px;">
                                <i class="fas fa-map-marker-alt me-2"></i>Thông tin địa chỉ
                            </h6>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="province_select" class="form-label text-dark fw-semibold">Tỉnh / Thành phố</label>
                            <select name="province_id" id="province_select" class="form-select @error('province_id') is-invalid @enderror">
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province['idProvince'] }}" {{ old('province_id') == $province['idProvince'] ? 'selected' : '' }}>
                                        {{ $province['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="commune_select" class="form-label text-dark fw-semibold">Phường / Xã</label>
                            <select name="commune_id" id="commune_select" class="form-select @error('commune_id') is-invalid @enderror" disabled>
                                <option value="">-- Vui lòng chọn Tỉnh/Thành trước --</option>
                            </select>
                            @error('commune_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-2">
                            <label for="address_detail" class="form-label text-dark fw-semibold">Địa chỉ chi tiết</label>
                            <input type="text" class="form-control @error('address_detail') is-invalid @enderror" id="address_detail" name="address_detail" value="{{ old('address_detail') }}" placeholder="Số nhà, tên đường, tòa nhà...">
                            @error('address_detail')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="status" class="form-label text-dark fw-semibold">Trạng Thái</label>
                            <select name="status" id="status" class="form-select">
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Ngừng hoạt động (Khóa)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('branch.index') }}" class="btn btn-light border px-4">Hủy</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-plus-circle me-1"></i> Thêm Chi Nhánh
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const provinceSelect = document.getElementById('province_select');
            const communeSelect = document.getElementById('commune_select');

            const oldCommuneId = "{{ old('commune_id') }}";

            function loadCommunes(provinceId, selectedCommuneId = null) {
                if (!provinceId) {
                    communeSelect.innerHTML = '<option value="">-- Vui lòng chọn Tỉnh/Thành trước --</option>';
                    communeSelect.disabled = true;
                    return;
                }

                fetch(`{{ route('branches.get-communes') }}?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        communeSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';

                        data.forEach(commune => {
                            const option = document.createElement('option');
                            option.value = commune.idCommune;
                            option.textContent = commune.name;
                            if (selectedCommuneId && commune.idCommune === selectedCommuneId) {
                                option.selected = true;
                            }
                            communeSelect.appendChild(option);
                        });

                        communeSelect.disabled = false;
                    })
                    .catch(error => console.error('Lỗi lấy dữ liệu xã phường:', error));
            }

            provinceSelect.addEventListener('change', function() {
                loadCommunes(this.value);
            });

            if (provinceSelect.value) {
                loadCommunes(provinceSelect.value, oldCommuneId);
            }
        });
    </script>
@endsection
