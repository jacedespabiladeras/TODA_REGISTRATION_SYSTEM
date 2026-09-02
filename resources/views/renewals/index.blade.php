<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Franchise Renewal Management</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-block">
                    {{ now()->format('d M Y') }}
                </span>
                <span class="badge bg-primary">
                    {{ auth()->user()->role?->name === 'admin' ? 'Administrator' : 'Staff' }}
                </span>
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

                {{-- STATS CARDS --}}
                <div class="section-group-title mt-0">
                    <i class="bi bi-arrow-repeat"></i> Renewal Operations Overview
                </div>
                <div class="stats-grid mb-4">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Total Renewals Logged
                            <span class="stat-badge stat-badge-total">All Time</span>
                        </div>
                        <div class="stat-card-value">{{ $totalRenewalsCount }}</div>
                    </div>
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Franchises Due / Expiring
                            <span class="stat-badge stat-badge-expiring">Action Required</span>
                        </div>
                        <div class="stat-card-value">{{ $totalExpiringCount }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Renewed This Month
                            <span class="stat-badge stat-badge-active">{{ now()->format('M Y') }}</span>
                        </div>
                        <div class="stat-card-value">{{ $thisMonthRenewalsCount }}</div>
                    </div>
                </div>

                {{-- SECTION 1: FRANCHISES DUE FOR RENEWAL --}}
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <h5 class="card-title text-warning mb-0" style="font-weight: 700; color: #d37a00 !important;">
                                    <i class="bi bi-exclamation-circle-fill me-2"></i>Franchises Due for Renewal
                                </h5>
                                <p class="text-muted small mb-0 mt-1">Franchises expiring in 30 days or already expired requiring immediate renewal.</p>
                            </div>
                            <form method="GET" action="{{ route('renewals.index') }}" class="d-flex gap-2">
                                <input 
                                    type="text" 
                                    name="due_search" 
                                    class="form-control form-control-sm" 
                                    placeholder="Search due franchise..." 
                                    value="{{ request('due_search') }}"
                                    style="width: 220px;"
                                >
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Franchise #</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Operator</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Vehicle / Plate</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Expiration Date</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Status</th>
                                        <th class="py-3 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dueFranchises as $dueFranchise)
                                        <tr style="border-bottom: 1px solid #e9ecef;">
                                            <td class="ps-4">
                                                <strong>{{ $dueFranchise->franchise_number }}</strong>
                                            </td>
                                            <td>
                                                {{ $dueFranchise->operator ? $dueFranchise->operator->first_name . ' ' . $dueFranchise->operator->last_name : 'Unassigned' }}
                                                <div class="text-muted small">{{ $dueFranchise->operator?->contact_number }}</div>
                                            </td>
                                            <td>
                                                <code>{{ $dueFranchise->vehicle?->plate_number ?? 'N/A' }}</code>
                                                <div class="text-muted small">{{ $dueFranchise->vehicle?->make }} {{ $dueFranchise->vehicle?->model }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $dueFranchise->expiration_date ? $dueFranchise->expiration_date->format('M d, Y') : 'N/A' }}</div>
                                                <small class="{{ $dueFranchise->days_remaining < 0 ? 'text-danger' : 'text-warning fw-bold' }}">
                                                    @if($dueFranchise->days_remaining < 0)
                                                        Expired {{ abs($dueFranchise->days_remaining) }}d ago
                                                    @elseif($dueFranchise->days_remaining === 0)
                                                        Expires today
                                                    @else
                                                        {{ $dueFranchise->days_remaining }}d left
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge px-2 py-1 text-uppercase" style="font-size: 11px; {{ $dueFranchise->status_badge_style }}">
                                                    {{ $dueFranchise->calculated_status }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('franchises.renew', $dueFranchise->id) }}" class="btn btn-sm btn-success px-3">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Renew Franchise
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                <i class="bi bi-check2-circle display-6 d-block mb-2 text-success" style="opacity: 0.5;"></i>
                                                No franchises are currently due for renewal!
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($dueFranchises->hasPages())
                        <div class="card-footer bg-white border-top p-3">
                            {{ $dueFranchises->links() }}
                        </div>
                    @endif
                </div>

                {{-- SECTION 2: COMPLETE RENEWAL TRANSACTION HISTORY --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-journal-text me-2"></i>Renewal Transaction Logs
                                </h5>
                                <p class="text-muted small mb-0 mt-1">Audit log of all processed renewals across all TODA franchises.</p>
                            </div>
                            <form method="GET" action="{{ route('renewals.index') }}" class="d-flex gap-2">
                                <input 
                                    type="text" 
                                    name="history_search" 
                                    class="form-control form-control-sm" 
                                    placeholder="Search receipt, franchise, operator..." 
                                    value="{{ request('history_search') }}"
                                    style="width: 250px;"
                                >
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Franchise #</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Operator & Vehicle</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Previous Expiration</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">New Expiration</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Renewal Date</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Reference (OR #)</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Fee (PHP)</th>
                                        <th class="py-3 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700;">Processed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($renewalHistory as $record)
                                        <tr style="border-bottom: 1px solid #e9ecef;">
                                            <td class="ps-4">
                                                @if($record->franchise)
                                                    <a href="{{ route('franchises.show', $record->franchise->id) }}" class="fw-bold text-decoration-none">
                                                        {{ $record->franchise->franchise_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Franchise Deleted</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $record->franchise?->operator ? $record->franchise->operator->first_name . ' ' . $record->franchise->operator->last_name : 'N/A' }}</div>
                                                <small class="text-muted">Plate: <code>{{ $record->franchise?->vehicle?->plate_number ?? 'N/A' }}</code></small>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $record->previous_expiration_date ? $record->previous_expiration_date->format('M d, Y') : 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">{{ $record->new_expiration_date ? $record->new_expiration_date->format('M d, Y') : 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                {{ $record->renewal_date ? $record->renewal_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                <code>{{ $record->reference_number ?? '—' }}</code>
                                            </td>
                                            <td>
                                                {{ $record->renewal_fee ? '₱' . number_format($record->renewal_fee, 2) : '—' }}
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="fw-semibold">{{ $record->processedBy?->name ?? 'System' }}</div>
                                                <small class="text-muted">{{ $record->created_at->format('M d, Y h:i A') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="bi bi-journal-x display-6 d-block mb-3 text-muted" style="opacity: 0.3;"></i>
                                                No renewal transactions found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($renewalHistory->hasPages())
                        <div class="card-footer bg-white border-top p-3">
                            {{ $renewalHistory->links() }}
                        </div>
                    @endif
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
