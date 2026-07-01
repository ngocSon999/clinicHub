@extends('layouts.master')

@section('title', 'Quản Lý Vai Trò - ClinicHub')
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
                <h5 class="mb-1 fw-bold text-dark">Quản Lý Vai Trò (Roles)</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Thiết lập</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vai trò</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('role.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>{{ __('Add New Role') }}
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive p-4 bg-white rounded-3 shadow-sm border">
                <table id="roleTable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">#</th>
                        <th style="width: 15%">{{ __('Role Name') }}</th>
                        <th style="width: 30%">{{ __('Permissions') }}</th>
                        <th style="width: 10%" class="text-center">{{ __('Created At') }}</th>
                        <th style="width: 15%" class="text-center">{{ __('Actions') }}</th>
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
            $('#roleTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('role.list') }}",
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
                        data: null,
                        render: function(data, type, row) {
                            let teamInfo = row.team ? ` (${row.team.name})` : '';

                            return `<span class="badge bg-primary-subtle text-primary px-2.5 py-1.5 fs-7 fw-semibold">${row.name}${teamInfo}</span>`;
                        }
                    },
                    {
                        data: 'permissions',
                        orderable: false,
                        render: function(data) {
                            if (!data || data.length === 0) {
                                return `<span class="text-muted small italic">Chưa phân quyền</span>`;
                            }
                            let badges = '';
                            data.forEach(function(permission) {
                                badges += `<span class="badge bg-secondary-subtle text-secondary me-1 mb-1 font-monospace" style="font-size: 0.75rem;">${permission.name}</span>`;
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
                            let editUrlTemplate = `{{ route('role.edit', ['role' => 'PLACEHOLDER_ID']) }}`;
                            let editUrl = editUrlTemplate.replace('PLACEHOLDER_ID', row.id);

                            let deleteUrlTemplate = `{{ route('role.destroy', ['role' => 'PLACEHOLDER_ID']) }}`;
                            let deleteUrl = deleteUrlTemplate.replace('PLACEHOLDER_ID', row.id);

                            return `
                                    <div class="btn-group shadow-sm">
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
