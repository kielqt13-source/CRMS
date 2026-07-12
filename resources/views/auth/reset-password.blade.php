<x-guest-layout>
  <h4 class="mb-2">Reset Password 🔑</h4>
  <p class="mb-4 text-muted">Enter your new password below</p>

  <form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <!-- Email -->
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror"
             id="email" name="email" value="{{ old('email', $request->email) }}"
             placeholder="Enter your email" autofocus autocomplete="username" required />
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    <!-- New Password -->
    <div class="mb-3 form-password-toggle">
      <label class="form-label" for="password">New Password</label>
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

    <button class="btn btn-primary d-grid w-100" type="submit">Reset Password</button>
  </form>
</x-guest-layout>
