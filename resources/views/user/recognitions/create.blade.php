<x-app-layout>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><span class="text-muted fw-light">OCR /</span> New Recognition</h4>
  </div>

  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
      <div class="card">
        <div class="card-body">

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-3" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if(session('status'))
            <div class="alert alert-success alert-dismissible mb-3" role="alert">
              {{ session('status') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <div class="mb-4">
            <label for="global_document_type" class="form-label fw-semibold">Step 1: Document Type</label>
            <select id="global_document_type" class="form-select" required>
              <option value="">Choose document type first</option>
              <option value="Birth Certificate" {{ old('document_type') === 'Birth Certificate' ? 'selected' : '' }}>Birth Certificate</option>
              <option value="Mirrage Certificate" {{ old('document_type') === 'Mirrage Certificate' ? 'selected' : '' }}>Mirrage Certificate</option>
              <option value="Date Certificate" {{ old('document_type') === 'Date Certificate' ? 'selected' : '' }}>Date Certificate</option>
            </select>
            @error('document_type')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
            <small class="text-muted">You must choose document type before selecting Single File or Batch Upload.</small>
          </div>

          <div class="card-header px-0 pb-0 border-0">
            <ul class="nav nav-tabs card-header-tabs" id="uploadTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="single-tab" data-bs-toggle="tab" data-bs-target="#single" type="button" role="tab" aria-controls="single" aria-selected="true">
                  <i class="bx bx-file me-1"></i>Single File
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="batch-tab" data-bs-toggle="tab" data-bs-target="#batch" type="button" role="tab" aria-controls="batch" aria-selected="false">
                  <i class="bx bx-files me-1"></i>Batch Upload
                </button>
              </li>
            </ul>
          </div>

          <div class="tab-content pt-4" id="uploadTabsContent">
            <!-- Single File Upload -->
            <div class="tab-pane fade show active" id="single" role="tabpanel" aria-labelledby="single-tab">

              <p class="text-muted mb-4">
                Upload a clear image, PDF, or Word document of handwritten text. Supported formats: JPEG, PNG, GIF, SVG, PDF, DOC, DOCX (max 20MB).
              </p>

              <form id="single-form" action="{{ route('recognitions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="document_type" id="single_document_type" value="{{ old('document_type') }}">

                <div class="mb-4">
                  <label for="file" class="form-label fw-semibold">File</label>
                  <div class="dropzone-wrapper border rounded p-5 text-center" style="border-style: dashed !important; cursor: pointer;" onclick="document.getElementById('file').click()">
                    <i class="bx bx-cloud-upload" style="font-size: 3rem; color: #696cff;"></i>
                    <p class="mt-2 mb-1 fw-semibold">Click to upload or drag &amp; drop</p>
                    <p class="text-muted small mb-0" id="file-label">PNG, JPG, GIF, SVG, PDF, DOC, DOCX up to 20MB</p>
                    <input type="file" id="file" name="file" class="d-none"
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required
                           onchange="updateSingleLabel(this)">
                  </div>
                  @error('file')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex gap-2">
                  <button id="single-submit" type="submit" class="btn btn-primary">
                    <i class="bx bx-scan me-1"></i> Recognize Text
                  </button>
                  <a href="{{ route('recognitions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
              </form>
            </div>

            <!-- Batch Upload -->
            <div class="tab-pane fade" id="batch" role="tabpanel" aria-labelledby="batch-tab">
              @if(session('batch_error'))
                <div class="alert alert-danger alert-dismissible mb-3" role="alert">
                  {{ session('batch_error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif
              @if(session('batch_status'))
                <div class="alert alert-success alert-dismissible mb-3" role="alert">
                  {{ session('batch_status') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              @endif

              <p class="text-muted mb-4">
                Upload multiple handwritten files at once. Supported formats: JPEG, PNG, GIF, SVG, PDF, DOC, DOCX (max 20MB per file, up to 20 files).
              </p>

              <form id="batch-form" action="{{ route('recognitions.store.batch') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="document_type" id="batch_document_type" value="{{ old('document_type') }}">

                <div class="mb-4">
                  <label for="files" class="form-label fw-semibold">Files</label>
                  <div class="dropzone-wrapper border rounded p-5 text-center" style="border-style: dashed !important; cursor: pointer;" onclick="document.getElementById('files').click()">
                    <i class="bx bx-cloud-upload" style="font-size: 3rem; color: #696cff;"></i>
                    <p class="mt-2 mb-1 fw-semibold">Click to upload or drag &amp; drop multiple files</p>
                    <p class="text-muted small mb-0" id="batch-file-label">PNG, JPG, GIF, SVG, PDF, DOC, DOCX up to 20MB each (max 20 files)</p>
                    <input type="file" id="files" name="files[]" class="d-none"
                           accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" required
                           multiple
                           onchange="updateBatchLabel(this)">
                  </div>
                  @error('files')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                  @error('files.*')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex gap-2">
                  <button id="batch-submit" type="submit" class="btn btn-primary">
                    <i class="bx bx-scan me-1"></i> Recognize All
                  </button>
                  <a href="{{ route('recognitions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    #uploadTabs .nav-link {
      border: 0;
      border-radius: .375rem .375rem 0 0;
      background: #eef0f4;
      color: #566a7f;
      margin-right: .4rem;
      font-weight: 600;
    }

    #uploadTabs .nav-link.active {
      background: #696cff;
      color: #fff;
      box-shadow: 0 2px 6px rgba(105, 108, 255, .35);
    }

    #uploadTabs .nav-link:disabled {
      opacity: .55;
      cursor: not-allowed;
    }
  </style>

  <script>
    const globalDocumentType = document.getElementById('global_document_type');
    const singleDocumentType = document.getElementById('single_document_type');
    const batchDocumentType = document.getElementById('batch_document_type');
    const singleTab = document.getElementById('single-tab');
    const batchTab = document.getElementById('batch-tab');
    const singleSubmit = document.getElementById('single-submit');
    const batchSubmit = document.getElementById('batch-submit');

    function syncDocumentTypeState() {
      const value = globalDocumentType.value;
      singleDocumentType.value = value;
      batchDocumentType.value = value;

      const enabled = value !== '';
      singleTab.disabled = !enabled;
      batchTab.disabled = !enabled;
      singleSubmit.disabled = !enabled;
      batchSubmit.disabled = !enabled;
    }

    function updateSingleLabel(input) {
      const label = document.getElementById('file-label');
      if (input.files.length > 0) {
        label.textContent = '✔ ' + input.files[0].name;
        label.className = 'text-primary small mb-0 fw-semibold';
      }
    }

    function updateBatchLabel(input) {
      const label = document.getElementById('batch-file-label');
      if (input.files.length > 0) {
        label.textContent = '✔ ' + input.files.length + ' file(s) selected';
        label.className = 'text-primary small mb-0 fw-semibold';
      }
    }

    globalDocumentType.addEventListener('change', syncDocumentTypeState);
    syncDocumentTypeState();
  </script>

</x-app-layout>
