<div class="card border-danger">
  <div class="card-header bg-label-danger">
    <h5 class="mb-0 text-danger">Delete Account</h5>
    <small class="text-muted">This action is permanent and cannot be undone.</small>
  </div>
  <div class="card-body">
    <p class="text-muted mb-3">
      Once your account is deleted, all of its resources and data will be permanently deleted.
      Please enter your password to confirm.
    </p>

    <form method="post" action="{{ route('profile.destroy') }}" class="row g-3 align-items-end">
      @csrf
      @method('delete')

      <div class="col-md-6">
        <label for="delete_password" class="form-label">Password</label>
        <input id="delete_password" name="password" type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" placeholder="Enter your current password" required>
        @error('password', 'userDeletion')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="col-md-6">
        <button type="submit" class="btn btn-danger">
          <i class="bx bx-trash me-1"></i> Delete Account
        </button>
      </div>
    </form>
  </div>
</div>
