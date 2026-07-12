<x-app-layout>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><span class="text-muted fw-light">OCR /</span> Recognition Result</h4>
    <a href="{{ route('recognitions.index') }}" class="btn btn-outline-secondary btn-sm">
      <i class="bx bx-arrow-back me-1"></i> Back to History
    </a>
  </div>

  @if(session('status'))
    <div class="alert alert-info alert-dismissible mb-4" role="alert">
      <i class="bx bx-info-circle me-1"></i> {{ session('status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Status & Meta -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-primary d-flex align-items-center justify-content-center">
                <i class="bx bx-image-alt"></i>
              </span>
            </div>
            <div>
              <h6 class="mb-0">{{ $recognition->original_filename }}</h6>
              <small class="text-muted">Uploaded {{ $recognition->created_at->format('Y-m-d H:i:s') }}</small>
              <div class="mt-1">
                <span class="badge bg-label-secondary">{{ $recognition->document_type ?: 'N/A' }}</span>
              </div>
            </div>
          </div>
          @php
            $badges = ['pending' => 'warning', 'processing' => 'info', 'completed' => 'success', 'failed' => 'danger'];
            $badge = $badges[$recognition->status] ?? 'secondary';
          @endphp
          <span class="badge bg-{{ $badge }} fs-6 px-3 py-2">
            <i class="bx bx-{{ $recognition->status === 'completed' ? 'check' : ($recognition->status === 'failed' ? 'x' : 'time') }} me-1"></i>
            {{ ucfirst($recognition->status) }}
          </span>
        </div>
      </div>
    </div>
  </div>

  <!-- File + Result -->
  <div class="row">
    <!-- Uploaded File -->
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <h5 class="mb-0"><i class="bx bx-file me-2"></i>Uploaded File</h5>
        </div>
        <div class="card-body text-center">
          @php
            $ext = strtolower(pathinfo($recognition->original_filename, PATHINFO_EXTENSION));
            $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'bmp'];
          @endphp
          @if(in_array($ext, $imageExts))
            <img src="{{ asset('storage/' . $recognition->file_path) }}"
                 alt="Uploaded file"
                 class="img-fluid rounded shadow-sm"
                 style="max-height: 400px; object-fit: contain;">
          @else
            <div class="p-5">
              <i class="bx bx-file text-muted" style="font-size:5rem;"></i>
              <p class="text-muted mt-3">Preview not available</p>
            </div>
          @endif
          <div class="mt-3">
            <a href="{{ asset('storage/' . $recognition->file_path) }}" class="btn btn-outline-primary btn-sm" download>
              <i class="bx bx-download me-1"></i> Download File
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Recognized Text -->
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0"><i class="bx bx-text me-2"></i>Recognized Text</h5>
          @if($recognition->status === 'completed' && $recognition->recognized_text)
            <button class="btn btn-sm btn-outline-primary" onclick="copyText()" id="copy-btn">
              <i class="bx bx-copy me-1"></i> Copy
            </button>
          @endif
        </div>
        <div class="card-body">
          @if($recognition->status === 'completed')
                <div class="p-3 bg-light rounded" style="min-height: 200px;">
                    @if($recognition->confidence)
                        <div class="mb-3">
                            <span class="fw-semibold">Confidence: </span>
                            <span class="badge bg-label-primary">{{ $recognition->confidence }}%</span>
                        </div>
                    @endif
                    <pre class="mb-0" style="white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 0.9rem;">{{ $recognition->recognized_text ?: 'No text was recognized in this image.' }}</pre>
                </div>
            @elseif($recognition->status === 'failed')
            <div class="alert alert-danger">
              <i class="bx bx-error-circle me-2"></i>
              Processing failed. Please try uploading the image again.
            </div>
            <a href="{{ route('recognitions.create') }}" class="btn btn-primary btn-sm mt-2">
              <i class="bx bx-upload me-1"></i> Try Again
            </a>
          @else
            <div class="alert alert-info">
              <div class="d-flex align-items-center gap-3">
                <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                <div>
                  <strong>Processing in progress...</strong><br>
                  <small>Refresh this page to check for results.</small>
                </div>
              </div>
            </div>
            <button onclick="window.location.reload()" class="btn btn-outline-info btn-sm">
              <i class="bx bx-refresh me-1"></i> Refresh Page
            </button>
          @endif
        </div>
      </div>
    </div>
  </div>

  @if($recognition->recognized_text)
  <script>
    function copyText() {
      const text = @json($recognition->recognized_text);
      navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copy-btn');
        btn.innerHTML = '<i class="bx bx-check me-1"></i> Copied!';
        btn.className = 'btn btn-sm btn-success';
        setTimeout(() => {
          btn.innerHTML = '<i class="bx bx-copy me-1"></i> Copy';
          btn.className = 'btn btn-sm btn-outline-primary';
        }, 2000);
      });
    }
  </script>
  @endif

</x-app-layout>
