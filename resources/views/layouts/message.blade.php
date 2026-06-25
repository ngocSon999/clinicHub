<div class="container-fluid px-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm global-alert d-flex align-items-center p-3 mb-4" role="alert">
            <i class="fas fa-check-circle fs-5 me-3"></i>
            <div class="small fw-medium">{{ session('success') }}</div>
            <button type="button" class="btn-close shadow-none py-3" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm global-alert d-flex align-items-center p-3 mb-4" role="alert">
            <i class="fas fa-times-circle fs-5 me-3"></i>
            <div class="small fw-medium">{{ session('error') }}</div>
            <button type="button" class="btn-close shadow-none py-3" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm global-alert p-3 mb-4" role="alert">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-exclamation-triangle fs-5 me-3"></i>
                <div class="small fw-bold">Vui lòng kiểm tra lại các trường dữ liệu:</div>
            </div>
            <ul class="mb-0 small ps-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close shadow-none py-3" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
