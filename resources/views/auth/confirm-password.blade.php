<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm border border-light-subtle p-4" style="max-width: 450px; width: 100%;">

        <div class="mb-4 text-muted small">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-3">
                <label for="password" class="form-label fw-medium text-dark">
                    {{ __('Password') }}
                </label>

                <input id="password"
                       type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required
                       autocomplete="current-password"
                       autofocus>

                @error('password')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>

    </div>
</div>
