@extends('layouts.master')

@section('title', 'Quản Lý Nhân Viên - ClinicHub')
@section('styles')
    <style>
        div.table-responsive > div.dt-container > div.row {
            --bs-gutter-x: 0;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 px-4">
            <div>
                <h5 class="mb-1 fw-bold text-dark">Quản Lý Nhân Viên (Users)</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Thiết lập</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nhân viên</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('user.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>Thêm nhân viên mới
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive p-4 bg-white rounded-3 shadow-sm border">
                <table id="userTable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">#</th>
                        <th style="width: 20%">Họ và tên</th>
                        <th style="width: 20%">Địa chỉ Email</th>
                        <th style="width: 25%">Vai trò chi nhánh</th>
                        <th style="width: 15%" class="text-center">Ngày tạo</th>
                        <th style="width: 15%" class="text-center">Thao tác</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.list') }}",
                    type: "GET",
                    dataSrc: "data"
                },
                lengthMenu: window.DataTableConfig.lengthMenu,
                pageLength: window.DataTableConfig.pageLength,
                language: window.DataTableConfig.language,
                order: false,
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                        render: function (data, type, row, meta) {
                            return `<span>${meta.row + 1}</span>`;
                        }
                    },
                    {
                        data: 'name',
                        render: function(data) {
                            return `<span class="fw-semibold text-dark">${data}</span>`;
                        }
                    },
                    {
                        data: 'email',
                        render: function(data) {
                            return `<span class="text-secondary">${data}</span>`;
                        }
                    },
                    {
                        data: 'all_roles',
                        orderable: false,
                        render: function(data) {
                            if (!data || data.length === 0) {
                                return `<span class="text-muted small italic">Chưa phân vai trò</span>`;
                            }

                            let badges = '';
                            data.forEach(function(role) {
                                let teamName = role.team ? role.team.name : 'Mặc định';

                                badges += `<span class="badge bg-primary-subtle text-primary me-1 mb-1 px-2.5 py-1.5" style="font-size: 0.75rem;">${role.name} (${teamName})</span>`;
                            });
                            return badges;
                        }
                    },
                    {
                        data: 'created_at',
                        className: 'text-center text-muted small',
                        render: function(data) {
                            if (!data) return '-';
                            let date = new Date(data);
                            return date.toLocaleDateString('vi-VN') + ' ' + date.toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'});
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            // Tạo các URL từ Blade template
                            let showUrl = `{{ route('user.show', ['user' => 'PLACEHOLDER_ID']) }}`.replace('PLACEHOLDER_ID', row.id);
                            let editUrl = `{{ route('user.edit', ['user' => 'PLACEHOLDER_ID']) }}`.replace('PLACEHOLDER_ID', row.id);
                            let deleteUrl = `{{ route('user.destroy', ['user' => 'PLACEHOLDER_ID']) }}`.replace('PLACEHOLDER_ID', row.id);

                            return `
                                <div class="btn-group shadow-sm">
                                    <a href="${showUrl}" class="btn btn-sm btn-outline-light text-dark border border-light-subtle" title="Xem chi tiết">
                                        <i class="fa fa-eye text-info"></i>
                                    </a>
                                    <a href="${editUrl}" class="btn btn-sm btn-outline-light text-dark border border-light-subtle" title="Sửa">
                                        <i class="fa fa-edit text-primary"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-light text-dark border border-light-subtle btn-delete-trigger"
                                            data-url="${deleteUrl}"
                                            data-name="${row.name}"
                                            title="Xóa">
                                        <i class="fa fa-trash text-danger"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
            });
        });
    </script>
@endsection
