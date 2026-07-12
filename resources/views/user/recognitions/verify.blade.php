<x-app-layout>
@section('title', 'Verify Document')

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="fw-bold mb-0">
    <span class="text-muted fw-light">OCR /</span> Verify Document
  </h4>
  <a href="{{ route('recognitions.show', $recognition) }}" class="btn btn-outline-secondary btn-sm">
    <i class="bx bx-arrow-back me-1"></i> Back to Result
  </a>
</div>

{{-- Document info bar --}}
<div class="card mb-4 border-0 shadow-none" style="background:rgba(105,108,255,.06);">
  <div class="card-body py-2 d-flex align-items-center gap-3">
    <span class="avatar avatar-sm rounded bg-label-primary d-flex align-items-center justify-content-center">
      <i class="bx bx-file"></i>
    </span>
    <div>
      <span class="fw-semibold">{{ $recognition->original_filename }}</span>
      <span class="badge bg-label-primary ms-2">{{ $recognition->document_type }}</span>
    </div>
    @if($recognition->confidence)
      <span class="ms-auto badge bg-label-info">AI Confidence: {{ $recognition->confidence }}%</span>
    @endif
  </div>
</div>

<div class="row">

  {{-- Left: Document Preview --}}
  <div class="col-lg-5 mb-4">
    <div class="card sticky-top" style="top:80px;">
      <div class="card-header">
        <h6 class="mb-0 fw-semibold"><i class="bx bx-image me-1"></i> Document Preview</h6>
      </div>
      <div class="card-body p-2">
        @php $ext = strtolower(pathinfo($recognition->original_filename, PATHINFO_EXTENSION)); @endphp
        @if(in_array($ext, ['jpg','jpeg','png','gif','svg','bmp']))
          <img src="{{ asset('storage/' . $recognition->file_path) }}"
               class="img-fluid rounded w-100" style="max-height:600px;object-fit:contain;"
               alt="Document">
        @elseif($ext === 'pdf')
          <div class="ratio" style="--bs-aspect-ratio:130%;">
            <iframe src="{{ asset('storage/' . $recognition->file_path) }}" style="border:0;border-radius:.375rem;"></iframe>
          </div>
        @else
          <div class="text-center py-5 text-muted">
            <i class="bx bx-file" style="font-size:4rem;opacity:.3;"></i>
            <p class="mt-2">Preview not available</p>
            <a href="{{ asset('storage/' . $recognition->file_path) }}" class="btn btn-sm btn-outline-primary" download>
              Download to view
            </a>
          </div>
        @endif
      </div>

      @if($recognition->recognized_text)
        <div class="card-footer p-0">
          <button class="btn btn-link btn-sm w-100 text-start px-3 py-2" type="button"
                  data-bs-toggle="collapse" data-bs-target="#raw-ocr">
            <i class="bx bx-code-alt me-1"></i> Raw OCR Output
            <i class="bx bx-chevron-down float-end"></i>
          </button>
          <div class="collapse" id="raw-ocr">
            <div class="p-3 bg-light border-top">
              <pre style="font-size:.75rem;white-space:pre-wrap;max-height:180px;overflow-y:auto;margin:0;">{{ $recognition->recognized_text }}</pre>
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>

  {{-- Right: Verification Form --}}
  <div class="col-lg-7 mb-4">
    <form action="{{ route('recognitions.verify.save', $recognition) }}" method="POST" id="verify-form">
      @csrf

      <div class="card mb-3">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h6 class="mb-0 fw-semibold">
            <i class="bx bx-edit me-1"></i> Review & Correct Extracted Data
          </h6>
          <small class="text-muted">Fields from AI recognition — please correct if needed</small>
        </div>
        <div class="card-body">

          @if(empty($fields))
            <div class="alert alert-warning">
              <i class="bx bx-info-circle me-1"></i>
              No template fields available for this document type.
            </div>
          @else
            <div class="row g-3">
              @foreach($fields as $key => $label)
                <div class="col-md-6">
                  <label for="field_{{ $key }}" class="form-label fw-semibold mb-1" style="font-size:.82rem;color:#697a8d;">
                    {{ $label }}
                    @if($prefilled[$key] ?? null)
                      <i class="bx bx-bot text-primary ms-1" title="AI extracted"></i>
                    @endif
                  </label>
                  @if(in_array($key, ['sex']))
                    <select name="{{ $key }}" id="field_{{ $key }}" class="form-select form-select-sm">
                      <option value="">— Select —</option>
                      <option value="Male"   {{ ($prefilled[$key] ?? '') === 'Male'   ? 'selected' : '' }}>Male</option>
                      <option value="Female" {{ ($prefilled[$key] ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                  @elseif(in_array($key, ['cause_of_death']))
                    <textarea name="{{ $key }}" id="field_{{ $key }}" class="form-control form-control-sm" rows="2">{{ $prefilled[$key] ?? '' }}</textarea>
                  @else
                    <input type="{{ in_array($key, ['date_of_birth','date_of_death','date_of_marriage','date_registered']) ? 'text' : 'text' }}"
                           name="{{ $key }}"
                           id="field_{{ $key }}"
                           class="form-control form-control-sm {{ $prefilled[$key] ?? null ? 'border-primary' : '' }}"
                           value="{{ old($key, $prefilled[$key] ?? '') }}"
                           placeholder="{{ $label }}">
                  @endif
                </div>
              @endforeach
            </div>

            @if(!empty($prefilled))
              <div class="mt-3 p-2 rounded" style="background:rgba(105,108,255,.05);font-size:.8rem;">
                <i class="bx bx-bot text-primary me-1"></i>
                Fields highlighted with a blue border were pre-filled by the AI. Please verify their accuracy.
              </div>
            @endif
          @endif

        </div>
      </div>

      {{-- Approve / Reject --}}
      <div class="card">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12">
              <h6 class="fw-semibold mb-3">Decision</h6>
            </div>

            {{-- Approve --}}
            <div class="col-md-6">
              <div class="card border-success h-100" style="cursor:pointer;" onclick="submitVerify('verify')">
                <div class="card-body text-center py-3">
                  <i class="bx bx-check-circle text-success" style="font-size:2.5rem;"></i>
                  <h6 class="fw-bold text-success mt-2 mb-1">Approve & Save</h6>
                  <small class="text-muted">Data is correct. Save as verified record.</small>
                </div>
              </div>
            </div>

            {{-- Reject --}}
            <div class="col-md-6">
              <div class="card border-danger h-100" style="cursor:pointer;" onclick="showRejectPanel()">
                <div class="card-body text-center py-3">
                  <i class="bx bx-x-circle text-danger" style="font-size:2.5rem;"></i>
                  <h6 class="fw-bold text-danger mt-2 mb-1">Reject</h6>
                  <small class="text-muted">Document is unreadable or incorrect. Reject it.</small>
                </div>
              </div>
            </div>

            {{-- Rejection reason (hidden by default) --}}
            <div class="col-12" id="reject-panel" style="display:none;">
              <div class="p-3 border border-danger rounded" style="background:rgba(255,62,29,.04);">
                <label class="form-label fw-semibold text-danger mb-1">
                  <i class="bx bx-message-detail me-1"></i> Rejection Reason <span class="text-muted fw-normal">(required)</span>
                </label>
                <textarea name="rejection_reason" id="rejection_reason" class="form-control mb-2"
                          rows="3" placeholder="Describe why this document is being rejected..."></textarea>
                <div class="d-flex gap-2">
                  <button type="button" class="btn btn-danger" onclick="submitVerify('reject')">
                    <i class="bx bx-x me-1"></i> Confirm Rejection
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="hideRejectPanel()">Cancel</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <input type="hidden" name="action" id="form-action" value="verify">
    </form>
  </div>

</div>

@push('page-js')
<script>
  function submitVerify(action) {
    if (action === 'reject') {
      const reason = document.getElementById('rejection_reason').value.trim();
      if (!reason) {
        document.getElementById('rejection_reason').classList.add('is-invalid');
        return;
      }
    }
    document.getElementById('form-action').value = action;
    document.getElementById('verify-form').submit();
  }

  function showRejectPanel() {
    document.getElementById('reject-panel').style.display = 'block';
    document.getElementById('reject-panel').scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  function hideRejectPanel() {
    document.getElementById('reject-panel').style.display = 'none';
  }
</script>
@endpush

</x-app-layout>
