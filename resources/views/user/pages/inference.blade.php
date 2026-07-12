<x-app-layout>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0">
    <span class="text-muted fw-light">OCR /</span> Inference
  </h4>
  <a href="{{ route('recognitions.create') }}" class="btn btn-primary">
    <i class="bx bx-upload me-1"></i> New Recognition
  </a>
</div>

<div class="row">

  {{-- AI Service Status --}}
  <div class="col-12 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">AI Service Status</h5>
        <small class="text-muted">Python FastAPI processing service</small>
      </div>
      <div class="card-body">
        <div class="row g-3">
          @php
            $services = [
              ['name' => 'FastAPI Service',     'status' => 'Connecting...', 'color' => 'warning', 'icon' => 'bx-server'],
              ['name' => 'TrOCR Model',         'status' => 'Standby',       'color' => 'info',    'icon' => 'bx-edit-alt'],
              ['name' => 'PaddleOCR Model',     'status' => 'Standby',       'color' => 'info',    'icon' => 'bx-text'],
              ['name' => 'OpenCV Preprocessor', 'status' => 'Ready',         'color' => 'success', 'icon' => 'bx-image-alt'],
            ];
          @endphp
          @foreach($services as $svc)
            <div class="col-md-3 col-6">
              <div class="d-flex flex-column align-items-center gap-2 p-3 border rounded">
                <span class="avatar avatar-sm rounded bg-label-{{ $svc['color'] }} d-flex align-items-center justify-content-center">
                  <i class="bx {{ $svc['icon'] }}"></i>
                </span>
                <div class="text-center">
                  <div style="font-size:.8rem;font-weight:600;">{{ $svc['name'] }}</div>
                  <span class="badge bg-label-{{ $svc['color'] }}" style="font-size:.68rem;">{{ $svc['status'] }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  {{-- Quick Test --}}
  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0"><i class="bx bx-beaker me-1"></i> Quick Inference Test</h5>
        <small class="text-muted">Test the OCR pipeline with a single document</small>
      </div>
      <div class="card-body">
        <div class="alert alert-info mb-3" style="font-size:.85rem;">
          <i class="bx bx-info-circle me-1"></i>
          For full processing with human verification, use
          <a href="{{ route('recognitions.create') }}" class="alert-link">New Recognition</a>.
          This tool is for quick model testing.
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Document Type</label>
          <select class="form-select" id="inf-doc-type">
            <option value="">Select type...</option>
            <option>Birth Certificate</option>
            <option>Marriage Certificate</option>
            <option>Death Certificate</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Upload Document</label>
          <div class="border rounded p-4 text-center" style="border-style:dashed !important;cursor:pointer;background:#f8f9fa;"
               onclick="document.getElementById('inf-file').click()">
            <i class="bx bx-cloud-upload text-primary" style="font-size:2rem;"></i>
            <p class="mb-0 mt-1 small text-muted">Click to select a file</p>
            <input type="file" id="inf-file" class="d-none"
                   accept="image/*,application/pdf">
          </div>
          <div id="inf-file-name" class="text-muted small mt-1"></div>
        </div>

        <button class="btn btn-primary w-100" onclick="runInference()" id="inf-btn">
          <i class="bx bx-play-circle me-1"></i> Run Inference
        </button>

        <div id="inf-result" class="mt-3" style="display:none;">
          <div class="alert alert-warning">
            <i class="bx bx-info-circle me-1"></i>
            AI backend not connected. Connect the Python FastAPI service to enable live inference.
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Recent Processed --}}
  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Recent Recognitions</h5>
        <a href="{{ route('recognitions.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
      </div>
      <div class="card-body pt-0">
        @forelse($recentRecognitions as $rec)
          @php $badge = \App\Models\Recognition::statusBadge($rec->status); @endphp
          <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
            <span class="avatar avatar-sm rounded bg-label-{{ $badge }} me-3 flex-shrink-0 d-flex align-items-center justify-content-center">
              <i class="bx bx-file" style="font-size:.85rem;"></i>
            </span>
            <div class="flex-grow-1 overflow-hidden">
              <div class="fw-semibold text-truncate" style="font-size:.85rem;">{{ $rec->original_filename }}</div>
              <div class="text-muted" style="font-size:.75rem;">
                {{ $rec->document_type ?? 'N/A' }} &bull; {{ $rec->created_at->diffForHumans() }}
              </div>
            </div>
            <div class="ms-2 d-flex align-items-center gap-1">
              <span class="badge bg-label-{{ $badge }} rounded-pill">{{ ucfirst($rec->status) }}</span>
              <a href="{{ route('recognitions.show', $rec) }}" class="text-muted">
                <i class="bx bx-chevron-right"></i>
              </a>
            </div>
          </div>
        @empty
          <div class="text-center py-4 text-muted">
            <i class="bx bx-images d-block mb-2" style="font-size:2.5rem;opacity:.3;"></i>
            <p class="small mb-1">No recognitions yet.</p>
            <a href="{{ route('recognitions.create') }}" class="btn btn-sm btn-primary">Upload first document</a>
          </div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Processing Steps --}}
  <div class="col-12 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">How Inference Works</h5>
        <small class="text-muted">Document processing pipeline</small>
      </div>
      <div class="card-body">
        <div class="row g-3">
          @php
            $steps = [
              ['n'=>'1','icon'=>'bx-upload',        'color'=>'primary',   'title'=>'Upload',        'desc'=>'User uploads scanned PDF or image'],
              ['n'=>'2','icon'=>'bx-photo-album',  'color'=>'info',      'title'=>'Preprocess',    'desc'=>'OpenCV: noise removal, deskew, enhance'],
              ['n'=>'3','icon'=>'bx-brain',         'color'=>'warning',   'title'=>'OCR / HTR',     'desc'=>'TrOCR (handwritten) + PaddleOCR (typed)'],
              ['n'=>'4','icon'=>'bx-spreadsheet',   'color'=>'success',   'title'=>'Field Extract', 'desc'=>'Map text to template fields with regex/NLP'],
              ['n'=>'5','icon'=>'bx-shield-check',  'color'=>'primary',   'title'=>'Verify',        'desc'=>'Human reviews & corrects extracted data'],
              ['n'=>'6','icon'=>'bx-save',          'color'=>'secondary', 'title'=>'Save',          'desc'=>'Verified record stored in database'],
            ];
          @endphp
          @foreach($steps as $step)
            <div class="col-md-2 col-6 text-center">
              <div class="p-3 rounded h-100" style="background:rgba(105,108,255,.04);position:relative;">
                <span class="avatar avatar-sm rounded bg-label-{{ $step['color'] }} mb-2 d-flex align-items-center justify-content-center">
                  <i class="bx {{ $step['icon'] }}"></i>
                </span>
                <div class="fw-semibold" style="font-size:.8rem;">{{ $step['title'] }}</div>
                <div class="text-muted" style="font-size:.73rem;">{{ $step['desc'] }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

</div>

@push('page-js')
<script>
  document.getElementById('inf-file').addEventListener('change', function() {
    const n = document.getElementById('inf-file-name');
    n.textContent = this.files.length ? '✔ ' + this.files[0].name : '';
  });

  function runInference() {
    document.getElementById('inf-result').style.display = 'block';
  }
</script>
@endpush

</x-app-layout>
