<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm border border-light-subtle p-4" style="max-width: 450px; width: 100%;">

        <h5 class="card-title fw-bold text-center text-primary mb-4">{{ __('Reset Password') }}</h5>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label fw-medium text-dark">
                    {{ __('Email') }}
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email', $request->email) }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required
                       autofocus
                       autocomplete="username">

                @error('email')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-medium text-dark">
                    {{ __('Password') }}
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required
                       autocomplete="new-password">

                @error('password')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-medium text-dark">
                    {{ __('Confirm Password') }}
                </label>
                <input id="password_confirmation"
                       type="password"
                       name="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror"
                       required
                       autocomplete="new-password">

                @error('password_confirmation')
                <div class="invalid-feedback mt-2">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm fw-medium">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>

    </div>
</div>
