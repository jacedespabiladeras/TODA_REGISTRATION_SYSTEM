<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Franchise Profile</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('franchises.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="content">
            <div class="container-fluid">

                {{-- FLASH MESSAGES --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #198754;">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #dc3545;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @php
                    $calculatedStatus = $franchise->calculated_status;
                    $badgeStyle = $franchise->status_badge_style;
                @endphp

                <div class="row g-4">
                    {{-- PROFILE OVERVIEW CARD --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-primary mb-3" style="width: 80px; height: 80px; font-size: 32px; border: 3px solid #0b2342;">
                                        <i class="bi bi-file-earmark-text text-primary" style="color: #0b2342 !important;"></i>
                                    </div>
                                    <h4 class="fw-bold text-dark mb-1">
                                        {{ $franchise->franchise_number }}
                                    </h4>
                                    <p class="text-muted small mb-3">Franchise ID: #{{ $franchise->id }}</p>

                                    <div class="mb-4">
                                        <span class="badge px-3 py-2 text-uppercase" style="font-size: 12px; font-weight: 600; border-radius: 20px; {{ $badgeStyle }}">
                                            {{ $calculatedStatus }}
                                        </span>
                                    </div>

                                    <div class="p-3 bg-light rounded text-start mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted small">Expiration:</span>
                                            <strong class="small text-dark">{{ $franchise->expiration_date ? $franchise->expiration_date->format('M d, Y') : 'N/A' }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted small">Status:</span>
                                            <span class="small {{ $franchise->days_remaining < 0 ? 'text-danger fw-bold' : ($franchise->days_remaining <= 30 ? 'text-warning fw-bold' : 'text-success') }}">
                                                @if($franchise->days_remaining === null)
                                                    N/A
                                                @elseif($franchise->days_remaining < 0)
                                                    Expired ({{ abs($franchise->days_remaining) }}d ago)
                                                @elseif($franchise->days_remaining === 0)
                                                    Expires Today
                                                @else
                                                    Valid ({{ $franchise->days_remaining }}d left)
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('franchises.renew', $franchise->id) }}" class="btn btn-success">
                                        <i class="bi bi-arrow-repeat me-1.5"></i> Renew Franchise
                                    </a>
                                    <a href="{{ route('franchises.edit', $franchise->id) }}" class="btn btn-outline-primary" style="border-color: #0b2342; color: #0b2342;">
                                        <i class="bi bi-pencil-square me-1.5"></i> Edit Franchise
                                    </a>

                                    @if(auth()->user()->role?->name === 'admin')
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteFranchiseModal"
                                        >
                                            <i class="bi bi-trash me-1.5"></i> Delete Record
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL SPECIFICATIONS CARD --}}
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-info-circle me-2"></i>Franchise Information
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-striped table-borderless align-middle mb-0" style="font-size: 14px;">
                                    <tbody>
                                        <tr>
                                            <th class="text-muted py-2.5" style="width: 35%;">Franchise Number</th>
                                            <td class="text-dark fw-bold py-2.5">{{ $franchise->franchise_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Franchise / Issue Date</th>
                                            <td class="text-dark py-2.5">{{ $franchise->franchise_date ? $franchise->franchise_date->format('F d, Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Current Expiration Date</th>
                                            <td class="text-dark py-2.5">
                                                <strong>{{ $franchise->expiration_date ? $franchise->expiration_date->format('F d, Y') : 'N/A' }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Recorded Status</th>
                                            <td class="text-dark py-2.5 text-capitalize">{{ $franchise->status }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Registration Created</th>
                                            <td class="text-dark py-2.5">{{ $franchise->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- ASSOCIATED OPERATOR & VEHICLE CARDS --}}
                        <div class="row g-3">
                            {{-- OPERATOR CARD --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold text-dark">
                                            <i class="bi bi-person-badge me-1.5 text-primary"></i> Operator
                                        </h6>
                                        @if($franchise->operator)
                                            <a href="{{ route('operators.show', $franchise->operator->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 11px;">
                                                View
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        @if($franchise->operator)
                                            <div class="fw-bold text-dark">{{ $franchise->operator->first_name }} {{ $franchise->operator->last_name }}</div>
                                            <div class="text-muted small mb-2">ID: {{ $franchise->operator->operator_id }}</div>
                                            <div class="small"><i class="bi bi-telephone me-1 text-muted"></i> {{ $franchise->operator->contact_number }}</div>
                                            <div class="small text-truncate"><i class="bi bi-geo-alt me-1 text-muted"></i> {{ $franchise->operator->address }}</div>
                                        @else
                                            <span class="text-muted italic small">No operator assigned.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- VEHICLE CARD --}}
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold text-dark">
                                            <i class="bi bi-car-front me-1.5 text-primary"></i> Vehicle
                                        </h6>
                                        @if($franchise->vehicle)
                                            <a href="{{ route('vehicles.show', $franchise->vehicle->id) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 11px;">
                                                View
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        @if($franchise->vehicle)
                                            <div class="fw-bold text-dark">Plate: <code>{{ $franchise->vehicle->plate_number }}</code></div>
                                            <div class="text-muted small mb-2">ID: {{ $franchise->vehicle->vehicle_id }}</div>
                                            <div class="small">{{ $franchise->vehicle->make }} {{ $franchise->vehicle->model }} ({{ $franchise->vehicle->color ?? 'No Color' }})</div>
                                            <div class="small text-muted">
                                                Driver: 
                                                @if($franchise->vehicle->driver)
                                                    <strong>{{ $franchise->vehicle->driver->first_name }} {{ $franchise->vehicle->driver->last_name }}</strong>
                                                @else
                                                    <span class="italic">None</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted italic small">No vehicle assigned.</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RENEWAL HISTORY CARD --}}
                    <div class="col-12 mt-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                        <i class="bi bi-clock-history me-2"></i>Renewal History
                                    </h5>
                                    <p class="text-muted small mb-0 mt-1">Complete historical record of all renewals processed for this franchise.</p>
                                </div>
                                <a href="{{ route('franchises.renew', $franchise->id) }}" class="btn btn-sm btn-success px-3">
                                    <i class="bi bi-plus-lg me-1"></i> New Renewal
                                </a>
                            </div>
                            <div class="card-body p-0">
                                @if($franchise->renewals->isEmpty())
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-text display-6 d-block mb-3 text-muted" style="opacity: 0.3;"></i>
                                        No renewals recorded yet. When this franchise is renewed, full history will appear here.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4 py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Renewal #</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Previous Expiration</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">New Expiration</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Renewal Date</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Reference / OR #</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Fee (PHP)</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Remarks</th>
                                                    <th class="py-3 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700;">Processed By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($franchise->renewals as $index => $renewal)
                                                    <tr style="border-bottom: 1px solid #e9ecef;">
                                                        <td class="ps-4">
                                                            <span class="badge bg-secondary">
                                                                #{{ $franchise->renewals->count() - $index }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $renewal->previous_expiration_date ? $renewal->previous_expiration_date->format('M d, Y') : 'N/A' }}</span>
                                                        </td>
                                                        <td>
                                                            <strong class="text-success">{{ $renewal->new_expiration_date ? $renewal->new_expiration_date->format('M d, Y') : 'N/A' }}</strong>
                                                        </td>
                                                        <td>
                                                            {{ $renewal->renewal_date ? $renewal->renewal_date->format('M d, Y') : 'N/A' }}
                                                        </td>
                                                        <td>
                                                            <code>{{ $renewal->reference_number ?? '—' }}</code>
                                                        </td>
                                                        <td>
                                                            {{ $renewal->renewal_fee ? '₱' . number_format($renewal->renewal_fee, 2) : '—' }}
                                                        </td>
                                                        <td>
                                                            <span class="text-muted">{{ $renewal->remarks ?? 'None' }}</span>
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <div class="fw-semibold">{{ $renewal->processedBy?->name ?? 'System' }}</div>
                                                            <small class="text-muted">{{ $renewal->created_at->format('M d, Y h:i A') }}</small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- DELETE CONFIRMATION MODAL --}}
    @if(auth()->user()->role?->name === 'admin')
        <div class="modal fade" id="deleteFranchiseModal" tabindex="-1" aria-hidden="true" style="text-align: left;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Confirm Delete Franchise
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-1">Are you sure you want to delete this franchise record?</p>
                        <h6 class="text-dark fw-bold">Franchise No: {{ $franchise->franchise_number }}</h6>
                        <p class="text-muted small mt-2 mb-0">This action cannot be undone and will remove all associated renewal histories.</p>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="{{ route('franchises.destroy', $franchise->id) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-3">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
