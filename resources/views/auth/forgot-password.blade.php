<x-guest-layout>
  <h4 class="mb-2">Forgot Password? 🔒</h4>
  <p class="mb-4 text-muted">Enter your email and we'll send you instructions to reset your password</p>

  <!-- Session Status -->
  @if (session('status'))
    <div class="alert alert-success mb-3" role="alert">{{ session('status') }}</div>
  @endif

  <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror"
             id="email" name="email" value="{{ old('email') }}"
             placeholder="Enter your email" autofocus required />
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <button class="btn btn-primary d-grid w-100" type="submit">Send Reset Link</button>
  </form>

  <p class="text-center">
    <a href="{{ route('login') }}">
      <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
      Back to login
    </a>
  </p>
</x-guest-layout>
