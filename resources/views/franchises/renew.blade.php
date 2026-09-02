<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Renew Franchise</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('franchises.show', $franchise->id) }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5">
                    <i class="bi bi-arrow-left"></i> Back to Profile
                </a>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="content">
            <div class="container-fluid">

                <div class="row justify-content-center">
                    <div class="col-lg-9">

                        {{-- EXISTING FRANCHISE REFERENCE SUMMARY CARD --}}
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                        <i class="bi bi-file-earmark-check me-2"></i>Franchise Reference Information
                                    </h5>
                                    <span class="badge px-3 py-1.5 text-uppercase" style="font-size: 11px; font-weight: 600; {{ $franchise->status_badge_style }}">
                                        {{ $franchise->calculated_status }}
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row g-3 small">
                                    <div class="col-md-3">
                                        <span class="text-muted d-block">Franchise Number:</span>
                                        <strong class="text-dark fs-6">{{ $franchise->franchise_number }}</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted d-block">Assigned Operator:</span>
                                        <strong class="text-dark">{{ $franchise->operator ? $franchise->operator->first_name . ' ' . $franchise->operator->last_name : 'N/A' }}</strong>
                                        <div class="text-muted" style="font-size: 11px;">{{ $franchise->operator?->operator_id }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted d-block">Assigned Vehicle:</span>
                                        <strong class="text-dark"><code>{{ $franchise->vehicle?->plate_number }}</code></strong>
                                        <div class="text-muted" style="font-size: 11px;">{{ $franchise->vehicle?->make }} {{ $franchise->vehicle?->model }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted d-block">Current Expiration:</span>
                                        <strong class="text-danger fs-6">{{ $franchise->expiration_date ? $franchise->expiration_date->format('M d, Y') : 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RENEWAL FORM CARD --}}
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-success mb-0" style="font-weight: 600;">
                                    <i class="bi bi-arrow-repeat me-2"></i>Submit Franchise Renewal
                                </h5>
                                <p class="text-muted small mb-0 mt-1">
                                    Fill in the renewal details below. The new expiration date will become the active date, and previous records are preserved in history.
                                </p>
                            </div>

                            <form method="POST" action="{{ route('franchises.renew.process', $franchise->id) }}" class="card-body p-4">
                                @csrf

                                <div class="row g-3">
                                    {{-- RENEWAL DATE --}}
                                    <div class="col-md-6">
                                        <label for="renewal_date" class="form-label required-field">
                                            Renewal Date <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="date" 
                                            name="renewal_date" 
                                            id="renewal_date" 
                                            class="form-control @error('renewal_date') is-invalid @enderror" 
                                            value="{{ old('renewal_date', now()->toDateString()) }}"
                                            required
                                        >
                                        @error('renewal_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text small text-muted">Date the renewal transaction is filed.</div>
                                    </div>

                                    {{-- NEW EXPIRATION DATE --}}
                                    <div class="col-md-6">
                                        <label for="new_expiration_date" class="form-label required-field">
                                            New Expiration Date <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="date" 
                                            name="new_expiration_date" 
                                            id="new_expiration_date" 
                                            class="form-control @error('new_expiration_date') is-invalid @enderror" 
                                            value="{{ old('new_expiration_date', $suggestedNewExpiration) }}"
                                            required
                                        >
                                        @error('new_expiration_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text small text-muted">Must be later than current expiration date ({{ $franchise->expiration_date ? $franchise->expiration_date->format('M d, Y') : 'N/A' }}).</div>
                                    </div>

                                    {{-- REFERENCE NUMBER (OR #) --}}
                                    <div class="col-md-6">
                                        <label for="reference_number" class="form-label">
                                            Reference / Official Receipt (OR) Number
                                        </label>
                                        <input 
                                            type="text" 
                                            name="reference_number" 
                                            id="reference_number" 
                                            class="form-control @error('reference_number') is-invalid @enderror" 
                                            placeholder="e.g. OR-2026-987654"
                                            value="{{ old('reference_number') }}"
                                        >
                                        @error('reference_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- RENEWAL FEE --}}
                                    <div class="col-md-6">
                                        <label for="renewal_fee" class="form-label">
                                            Renewal Fee (PHP)
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">₱</span>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0"
                                                name="renewal_fee" 
                                                id="renewal_fee" 
                                                class="form-control @error('renewal_fee') is-invalid @enderror" 
                                                placeholder="e.g. 500.00"
                                                value="{{ old('renewal_fee') }}"
                                            >
                                        </div>
                                        @error('renewal_fee')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- REMARKS --}}
                                    <div class="col-12">
                                        <label for="remarks" class="form-label">Remarks / Notes</label>
                                        <textarea 
                                            name="remarks" 
                                            id="remarks" 
                                            rows="3" 
                                            class="form-control @error('remarks') is-invalid @enderror" 
                                            placeholder="Optional notes regarding this renewal (e.g. annual inspection completed, sticker issued)..."
                                        >{{ old('remarks') }}</textarea>
                                        @error('remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- PROCESSED BY INFO --}}
                                    <div class="col-12 mt-3">
                                        <div class="p-3 bg-light rounded border d-flex align-items-center justify-content-between">
                                            <div>
                                                <small class="text-muted d-block">Processed By:</small>
                                                <strong>{{ auth()->user()->name }}</strong>
                                                <span class="badge bg-secondary ms-1">{{ auth()->user()->role?->name === 'admin' ? 'Administrator' : 'Staff' }}</span>
                                            </div>
                                            <div class="text-muted small">
                                                {{ now()->format('F d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- FORM ACTIONS --}}
                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="{{ route('franchises.show', $franchise->id) }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success px-4 d-flex align-items-center gap-1.5">
                                        <i class="bi bi-check-circle"></i> Confirm & Process Renewal
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- SIDEBAR TOGGLE SCRIPT --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggle = document.getElementById('sidebarToggle');

        toggle.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainWrapper.classList.toggle('expanded');
            }
        });
    </script>
</x-app-layout>
