@extends('layouts.master')

@section('title', 'Thêm Mới Vai Trò - ClinicHub')

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex justify-content-between align-items-center mb-4 px-4">
            <div>
                <h5 class="mb-1 fw-bold text-dark">{{ __('Add New Role') }}</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Thiết lập</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('role.index') }}" class="text-decoration-none">Vai trò</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('role.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label text-dark fw-semibold">Tên Vai Trò</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ví dụ: admin, doctor, receptionist..." required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label text-dark mb-0">Phân Quyền Hạn (Permissions)</label>

                            <div class="form-check form-switch bg-light border px-3 py-1.5 rounded-3 d-flex align-items-center gap-2 cursor-pointer">
                                <input class="form-check-input ms-0" type="checkbox" id="check-all-global">
                                <label class="form-check-label text-dark small fw-bold cursor-pointer mb-0" for="check-all-global">
                                    Chọn tất cả quyền hệ thống
                                </label>
                            </div>
                        </div>

                        @foreach($permissionsGrouped as $groupName => $groupPermissions)
                            <div class="card border border-light-subtle shadow-sm mb-4 permission-group-card" style="border-radius: 12px;">

                                <div class="card-header bg-light py-2.5 px-4 d-flex justify-content-between align-items-center" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                    <h6 class="mb-0 fw-bold text-primary text-uppercase small" style="letter-spacing: 0.5px;">
                                        <i class="fas fa-cubes me-2"></i>Quản lý {{ $groupName }}
                                    </h6>

                                    <div class="d-flex align-items-center gap-2 mb-0">
                                        <input class="form-check-input check-all-group align-middle mt-0" type="checkbox" id="check-all-{{ $groupName }}">
                                        <label class="form-check-label text-secondary small cursor-pointer mb-0" for="check-all-{{ $groupName }}">
                                            Chọn tất cả nhóm này
                                        </label>
                                    </div>
                                </div>

                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-check p-3 border rounded-3 bg-light-hover d-flex align-items-center gap-2" style="cursor: pointer;">
                                                    <input class="form-check-input ms-0 perm-checkbox" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}"
                                                        {{ is_array(old('permissions')) && in_array($permission->name, old('permissions')) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark small fw-medium cursor-pointer mb-0" for="perm-{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @error('permissions')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hành động --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('role.index') }}" class="btn btn-light border px-4">Hủy</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-plus-circle me-1"></i> Thêm Vai Trò
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const globalCheckAll = document.getElementById('check-all-global');
            const groupCards = document.querySelectorAll('.permission-group-card');
            const allPermCheckboxes = document.querySelectorAll('.perm-checkbox');

            function updateGlobalState() {
                if (!globalCheckAll) return;
                const totalChecked = document.querySelectorAll('.perm-checkbox:checked').length;
                globalCheckAll.checked = (totalChecked === allPermCheckboxes.length && allPermCheckboxes.length > 0);
            }

            function updateGroupState(card) {
                const groupCheckAll = card.querySelector('.check-all-group');
                const totalItems = card.querySelectorAll('.perm-checkbox').length;
                const totalCheckedItems = card.querySelectorAll('.perm-checkbox:checked').length;

                if (groupCheckAll) {
                    groupCheckAll.checked = (totalItems === totalCheckedItems && totalItems > 0);
                }
            }

            // Đồng bộ trạng thái ban đầu (hữu ích khi old() dữ liệu được load lại do validate lỗi)
            groupCards.forEach(card => updateGroupState(card));
            updateGlobalState();

            groupCards.forEach(card => {
                const groupCheckAll = card.querySelector('.check-all-group');
                const checkboxes = card.querySelectorAll('.perm-checkbox');

                if (groupCheckAll) {
                    groupCheckAll.addEventListener('change', function () {
                        checkboxes.forEach(cb => {
                            cb.checked = groupCheckAll.checked;
                        });
                        updateGlobalState();
                    });
                }
            });

            if (globalCheckAll) {
                globalCheckAll.addEventListener('change', function () {
                    allPermCheckboxes.forEach(cb => {
                        cb.checked = globalCheckAll.checked;
                    });
                    groupCards.forEach(card => updateGroupState(card));
                });
            }

            allPermCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    const parentCard = cb.closest('.permission-group-card');
                    if (parentCard) {
                        updateGroupState(parentCard);
                    }
                    updateGlobalState();
                });
            });
        });
    </script>
@endsection
