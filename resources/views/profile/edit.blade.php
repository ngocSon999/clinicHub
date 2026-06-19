@extends('layouts.master')

@section('title', 'Quản Lý Hồ Sơ - ClinicHub')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="p-2 mb-3 bg-white rounded-3 shadow-sm border">
                    <div class="container-fluid py-3">
                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow-sm border rounded-3 h-100">
                                    <div class="card-body p-4">
                                        <div class="max-w-xl">
                                            @include('profile.partials.update-profile-information-form')
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3 mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>{{ __('Change Password') }}</h5>
                                </div>
                                <div class="card shadow-sm border rounded-3 h-100">
                                    <div class="card-body p-4">
                                        @include('profile.partials.update-password-form')
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0 text-danger"><i class="fas fa-user-slash me-2"></i>{{ __('Delete Account') }}</h5>
                                </div>
                                <div class="card shadow-sm border border-danger rounded-3">
                                    <div class="card-body p-4">
                                        @include('profile.partials.delete-user-form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
