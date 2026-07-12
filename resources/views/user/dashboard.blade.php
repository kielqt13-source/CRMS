<x-app-layout>

  <div class="row">
    <!-- Welcome Banner -->
    <div class="col-12 mb-4">
      <div class="card">
        <div class="d-flex align-items-end row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary">Welcome back, {{ Auth::user()->name }}! 🎉</h5>
              <p class="mb-4">
                Use the CRSL system to recognize handwritten text from images using AI-powered OCR.
              </p>
              <a href="{{ route('recognitions.create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-upload me-1"></i> New Recognition
              </a>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <i class="bx bx-pencil" style="font-size: 80px; color: #696cff; opacity: 0.3;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats -->
    @php
      $total     = Auth::user()->recognitions()->count();
      $completed = Auth::user()->recognitions()->where('status', 'completed')->count();
      $pending   = Auth::user()->recognitions()->whereIn('status', ['pending', 'processing'])->count();
      $failed    = Auth::user()->recognitions()->where('status', 'failed')->count();
    @endphp

    <div class="col-lg-3 col-md-6 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-primary d-flex align-items-center justify-content-center"><i class="bx bx-images"></i></span>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Total</span>
          <h3 class="card-title mb-2">{{ $total }}</h3>
          <small class="text-muted">recognitions</small>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-success d-flex align-items-center justify-content-center"><i class="bx bx-check-circle"></i></span>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Completed</span>
          <h3 class="card-title mb-2">{{ $completed }}</h3>
          <small class="text-success">successfully processed</small>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-warning d-flex align-items-center justify-content-center"><i class="bx bx-time"></i></span>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Pending</span>
          <h3 class="card-title mb-2">{{ $pending }}</h3>
          <small class="text-warning">in queue / processing</small>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <span class="avatar-initial rounded bg-label-danger d-flex align-items-center justify-content-center"><i class="bx bx-error-circle"></i></span>
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Failed</span>
          <h3 class="card-title mb-2">{{ $failed }}</h3>
          <small class="text-danger">need attention</small>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Quick Actions</h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <a href="{{ route('recognitions.create') }}" class="btn btn-primary">
              <i class="bx bx-upload me-1"></i> Upload & Recognize Image
            </a>
            <a href="{{ route('recognitions.index') }}" class="btn btn-outline-primary">
              <i class="bx bx-list-ul me-1"></i> View Recognition History
            </a>
            <a href="{{ route('inference') }}" class="btn btn-outline-secondary">
              <i class="bx bx-brain me-1"></i> Inference Dashboard
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Recognitions -->
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Recent Recognitions</h5>
          <a href="{{ route('recognitions.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
        </div>
        <div class="card-body pt-0">
          @php $recent = Auth::user()->recognitions()->latest()->take(5)->get(); @endphp
          @forelse($recent as $rec)
            <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
              <div class="avatar flex-shrink-0 me-3">
                @php
                  $iconMap = ['completed' => 'bg-label-success', 'pending' => 'bg-label-warning', 'processing' => 'bg-label-info', 'failed' => 'bg-label-danger'];
                  $iconClass = $iconMap[$rec->status] ?? 'bg-label-secondary';
                @endphp
                <span class="avatar-initial rounded {{ $iconClass }} d-flex align-items-center justify-content-center">
                  <i class="bx bx-image"></i>
                </span>
              </div>
              <div class="flex-grow-1 overflow-hidden">
                <small class="d-block text-truncate fw-semibold">{{ $rec->original_filename }}</small>
                <small class="text-muted">{{ $rec->created_at->diffForHumans() }}</small>
              </div>
              <div class="ms-2">
                <span class="badge bg-label-{{ $rec->status === 'completed' ? 'success' : ($rec->status === 'failed' ? 'danger' : 'warning') }} rounded-pill">
                  {{ ucfirst($rec->status) }}
                </span>
              </div>
              <a href="{{ route('recognitions.show', $rec) }}" class="ms-2 text-muted">
                <i class="bx bx-chevron-right"></i>
              </a>
            </div>
          @empty
            <div class="text-center py-4 text-muted">
              <i class="bx bx-images" style="font-size: 3rem; opacity: 0.3;"></i>
              <p class="mt-2 mb-0">No recognitions yet.</p>
              <a href="{{ route('recognitions.create') }}" class="btn btn-sm btn-primary mt-2">Get started</a>
            </div>
          @endforelse
        </div>
      </div>
    </div>

  </div>

</x-app-layout>
