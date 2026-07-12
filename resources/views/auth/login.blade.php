<x-guest-layout>
  <h4 class="mb-2">Welcome to CRSL! 👋</h4>
  <p class="mb-4 text-muted">Please sign in to your account</p>

  <!-- Session Status -->
  @if (session('status'))
    <div class="alert alert-success mb-3" role="alert">{{ session('status') }}</div>
  @endif

  <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror"
             id="email" name="email" value="{{ old('email') }}"
             placeholder="Enter your email" autofocus autocomplete="username" required />
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Password -->
    <div class="mb-3 form-password-toggle">
      <div class="d-flex justify-content-between">
        <label class="form-label" for="password">Password</label>
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}"><small>Forgot Password?</small></a>
        @endif
      </div>
      <div class="input-group input-group-merge">
        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
               name="password" placeholder="··········" autocomplete="current-password" required />
        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <!-- Remember Me -->
    <div class="mb-3">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="remember_me" name="remember" />
        <label class="form-check-label" for="remember_me">Remember Me</label>
      </div>
    </div>

    <div class="mb-3">
      <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
    </div>
  </form>

  <p class="text-center">
    <span>New on our platform?</span>
    @if (Route::has('register'))
      <a href="{{ route('register') }}"><span>Create an account</span></a>
    @endif
  </p>
</x-guest-layout>
