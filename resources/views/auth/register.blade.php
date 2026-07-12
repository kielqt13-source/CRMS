<x-guest-layout>
  <h4 class="mb-2">Adventure starts here 🚀</h4>
  <p class="mb-4 text-muted">Make your handwriting recognition easy and fun!</p>

  <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Name -->
    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control @error('name') is-invalid @enderror"
             id="name" name="name" value="{{ old('name') }}"
             placeholder="Enter your name" autofocus autocomplete="name" required />
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Email -->
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror"
             id="email" name="email" value="{{ old('email') }}"
             placeholder="Enter your email" autocomplete="username" required />
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- Password -->
    <div class="mb-3 form-password-toggle">
      <label class="form-label" for="password">Password</label>
      <div class="input-group input-group-merge">
        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
               name="password" placeholder="··········" autocomplete="new-password" required />
        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <!-- Confirm Password -->
    <div class="mb-3 form-password-toggle">
      <label class="form-label" for="password_confirmation">Confirm Password</label>
      <div class="input-group input-group-merge">
        <input type="password" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
               name="password_confirmation" placeholder="··········" autocomplete="new-password" required />
        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        @error('password_confirmation')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <button class="btn btn-primary d-grid w-100" type="submit">Sign up</button>
  </form>

  <p class="text-center">
    <span>Already have an account?</span>
    <a href="{{ route('login') }}"><span>Sign in instead</span></a>
  </p>
</x-guest-layout>
