<x-app-layout>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><span class="text-muted fw-light">OCR /</span> Recognition History</h4>
    <a href="{{ route('recognitions.create') }}" class="btn btn-primary">
      <i class="bx bx-upload me-1"></i> New Recognition
    </a>
  </div>

  @if(session('status'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
      {{ session('status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('batch_status'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
      {{ session('batch_status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('batch_error'))
    <div class="alert alert-danger alert-dismissible mb-4" role="alert">
      {{ session('batch_error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Filename</th>
            <th>Status</th>
            <th>Document Type</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @forelse ($recognitions as $recognition)
            <tr>
              <td><strong>{{ $recognition->id }}</strong></td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-3">
                    <span class="avatar-initial rounded bg-label-primary d-flex align-items-center justify-content-center">
                      <i class="bx bx-image-alt"></i>
                    </span>
                  </div>
                  <span>{{ $recognition->original_filename }}</span>
                </div>
              </td>
              <td>
                @php
                  $badges = ['pending' => 'warning', 'processing' => 'info', 'completed' => 'success', 'failed' => 'danger'];
                  $badge = $badges[$recognition->status] ?? 'secondary';
                @endphp
                <span class="badge bg-label-{{ $badge }} rounded-pill">{{ ucfirst($recognition->status) }}</span>
              </td>
              <td>
                <span class="badge bg-label-secondary rounded-pill">{{ $recognition->document_type ?: 'N/A' }}</span>
              </td>
              <td>{{ $recognition->created_at->format('Y-m-d H:i') }}</td>
              <td>
                <a href="{{ route('recognitions.show', $recognition) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bx bx-show-alt me-1"></i> View
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-5">
                <div class="text-muted">
                  <i class="bx bx-images" style="font-size: 3rem; opacity: 0.3;"></i>
                  <p class="mt-2">No recognitions found. <a href="{{ route('recognitions.create') }}">Upload your first image</a>.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($recognitions->hasPages())
      <div class="card-footer">
        {{ $recognitions->links() }}
      </div>
    @endif
  </div>

</x-app-layout>
