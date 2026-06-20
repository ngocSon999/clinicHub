<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm border border-light-subtle p-4" style="max-width: 450px; width: 100%;">

        <div class="mb-4 text-muted small">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @if (session('status'))
            <div class="alert alert-success mb-4 small py-2 px-3" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-medium text-dark">
                    {{ __('Email') }}
                </label>

                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required
                       autofocus>

                @error('email')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="d-flex align-items-center justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    {{ __('Email Password Reset Link') }}
                </button>
            </div>
        </form>

    </div>
</div>
