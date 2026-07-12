<x-guest-layout>
  <h4 class="mb-2">Confirm Password 🔐</h4>
  <p class="mb-4 text-muted">This is a secure area. Please confirm your password before continuing.</p>

  <form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="mb-3 form-password-toggle">
      <label class="form-label" for="password">Password</label>
      <div class="input-group input-group-merge">
        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
               name="password" placeholder="··········" autocomplete="current-password" required />
        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        @error('password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <button class="btn btn-primary d-grid w-100" type="submit">Confirm</button>
  </form>
</x-guest-layout>
