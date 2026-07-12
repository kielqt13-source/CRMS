<x-guest-layout>
  <h4 class="mb-2">Verify your Email ✉️</h4>
  <p class="mb-4 text-muted">
    Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just sent you.
    If you didn't receive the email, we'll gladly send you another.
  </p>

  @if (session('status') == 'verification-link-sent')
    <div class="alert alert-success mb-3" role="alert">
      <i class="bx bx-check-circle me-1"></i>
      A new verification link has been sent to your email address.
    </div>
  @endif

  <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
    @csrf
    <button type="submit" class="btn btn-primary d-grid w-100">
      <i class="bx bx-envelope me-1"></i> Resend Verification Email
    </button>
  </form>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-outline-secondary d-grid w-100">
      <i class="bx bx-power-off me-1"></i> Log Out
    </button>
  </form>
</x-guest-layout>
