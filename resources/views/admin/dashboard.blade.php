<x-app-layout>
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-1">Admin Dashboard</h4>
          <p class="text-muted mb-0">System-wide overview</p>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <span class="fw-semibold d-block mb-1">Total Users</span>
          <h3 class="mb-0">{{ $totalUsers }}</h3>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <span class="fw-semibold d-block mb-1">Total Recognitions</span>
          <h3 class="mb-0">{{ $totalRecognitions }}</h3>
        </div>
      </div>
    </div>

    <div class="col-lg-6 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <span class="fw-semibold d-block mb-1">Pending / Processing Recognitions</span>
          <h3 class="mb-0">{{ $pendingRecognitions }}</h3>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
