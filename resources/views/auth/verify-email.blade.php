<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm border border-light-subtle p-4" style="max-width: 500px; width: 100%;">

        <div class="mb-4 text-muted small">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success mb-4 small py-2 px-3" role="alert">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-between mt-4">

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary px-3 shadow-sm btn-sm fw-medium">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-decoration-none text-muted small p-0 hover:text-dark">
                    {{ __('Log Out') }}
                </button>
            </form>

        </div>

    </div>
</div>
