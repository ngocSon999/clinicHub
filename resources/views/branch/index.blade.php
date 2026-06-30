@extends('layouts.master')

@section('title', 'Quản Lý Chi Nhánh - ClinicHub')

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
                <h5 class="mb-1 fw-bold text-dark">Quản Lý Chi Nhánh</h5>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Thiết lập</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi nhánh</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('branch.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i>{{ __('Add New Branch') }}
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive p-4 bg-white rounded-3 shadow-sm border">
                <table id="branchTable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 5%">#</th>
                        <th style="width: 12%">Mã CN</th>
                        <th style="width: 20%">Tên Chi Nhánh</th>
                        <th style="width: 13%">Số Điện Thoại</th>
                        <th style="width: 25%">Địa Chỉ Đầy Đủ</th>
                        <th style="width: 10%" class="text-center">Trạng Thái</th>
                        <th style="width: 15%" class="text-center">Hành Động</th>
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
            $('#branchTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('branch.list') }}",
                    type: "GET",
                    dataSrc: "data"
                },
                lengthMenu: window.DataTableConfig.lengthMenu,
                pageLength: window.DataTableConfig.pageLength,
                language: window.DataTableConfig.language,
                order: true,
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
                        data: 'code',
                        orderable: true,
                        render: function(data) {
                            return data ? data.toUpperCase() : '-';
                        }
                    },
                    {
                        data: 'name',
                        orderable: true,
                        className: 'text-dark'
                    },
                    {
                        data: 'phone',
                        orderable: true,
                        render: function(data) {
                            return data ? data : `<span class="text-muted small italic">Chưa cập nhật</span>`;
                        }
                    },
                    {
                        data: 'full_address',
                        orderable: true,
                        render: function(data) {
                            return data ? data : `<span class="text-muted small">—</span>`;
                        }
                    },
                    {
                        data: 'status',
                        orderable: true,
                        className: 'text-center',
                        render: function(data) {
                            return data == 1
                                ? `<span class="badge bg-success-subtle text-success px-2.5 py-1.5 fs-7 fw-semibold">Hoạt động</span>`
                                : `<span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 fs-7 fw-semibold">Khóa</span>`;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            let editUrlTemplate = `{{ route('branch.edit', ['branch' => 'PLACEHOLDER_ID']) }}`;
                            let editUrl = editUrlTemplate.replace('PLACEHOLDER_ID', row.id);

                            let deleteUrlTemplate = `{{ route('branch.destroy', ['branch' => 'PLACEHOLDER_ID']) }}`;
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
