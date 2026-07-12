<x-app-layout>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Account /</span> Profile Settings</h4>
  </div>

  <div class="row g-4">
    <div class="col-12">
      @include('user.profile.partials.update-profile-information-form')
    </div>

    <div class="col-12">
      @include('user.profile.partials.update-password-form')
    </div>

    <div class="col-12">
      @include('user.profile.partials.delete-user-form')
    </div>
  </div>

</x-app-layout>
