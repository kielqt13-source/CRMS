<div class="card">
  <div class="card-header">
    <h5 class="mb-0">Profile Information</h5>
    <small class="text-muted">Update your account name and email address.</small>
  </div>
  <div class="card-body">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
      @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
      @csrf
      @method('patch')

      <div class="row g-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Name</label>
          <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="alert alert-warning mt-3 mb-0">
          Your email address is unverified.
          <button form="send-verification" class="btn btn-link p-0 ms-1 align-baseline">Click here to re-send verification email.</button>

          @if (session('status') === 'verification-link-sent')
            <div class="small text-success mt-2">A new verification link has been sent.</div>
          @endif
        </div>
      @endif

      <div class="mt-4 d-flex align-items-center gap-2">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        @if (session('status') === 'profile-updated')
          <span class="text-success small">Saved.</span>
        @endif
      </div>
    </form>
  </div>
</div>
