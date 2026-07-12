<x-app-layout>
    <x-slot name="header">
        <h4 class="fw-bold mb-0">Dashboard</h4>
    </x-slot>

    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Welcome Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h5>
                <p class="text-muted mb-0">Use the CRSL system to recognize handwritten text from images.</p>
            </div>
        </div>

        @php
            $total     = Auth::user()->recognitions()->count();
            $completed = Auth::user()->recognitions()->where('status', 'completed')->count();
            $pending   = Auth::user()->recognitions()->whereIn('status', ['pending', 'processing'])->count();
        @endphp

        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-3 fw-bold text-primary">{{ $total }}</div>
                            <div class="text-muted small">Total Recognitions</div>
                        </div>
                        <i class="bx bx-collection fs-1 text-muted"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-3 fw-bold text-success">{{ $completed }}</div>
                            <div class="text-muted small">Completed</div>
                        </div>
                        <i class="bx bx-check-circle fs-1 text-muted"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-3 fw-bold text-warning">{{ $pending }}</div>
                            <div class="text-muted small">Pending / Processing</div>
                        </div>
                        <i class="bx bx-time fs-1 text-muted"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('recognitions.create') }}" class="card text-decoration-none">
                    <div class="card-body d-flex align-items-center gap-3">
                        <i class="bx bx-upload fs-1 text-primary"></i>
                        <div>
                            <div class="fw-semibold">New Recognition</div>
                            <div class="text-muted small">Upload a handwritten image to recognize text</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('recognitions.index') }}" class="card text-decoration-none">
                    <div class="card-body d-flex align-items-center gap-3">
                        <i class="bx bx-history fs-1 text-primary"></i>
                        <div>
                            <div class="fw-semibold">View History</div>
                            <div class="text-muted small">Browse all your past recognition results</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
