<link href="{{ asset('/css/library/bootstrap.min.css') }}" rel="stylesheet">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg border-0 p-4 p-md-5" style="max-width: 500px; width: 100%; border-radius: 15px;">

        <div class="text-center mb-4">
            <h3 class="fw-bold">{{ __('Verify Your Email') }}</h3>
            <hr class="w-25 mx-auto text-primary" style="opacity: 1;">
        </div>

        <div class="mb-4 text-secondary text-center" style="line-height: 1.6;">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success border-0 bg-success-subtle text-success text-center mb-4 p-3" role="alert" style="border-radius: 10px;">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="d-grid gap-3 mt-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold shadow-sm" style="border-radius: 8px;">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none text-muted mt-2">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

    </div>
</div>
