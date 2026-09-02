<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Franchise Management</h1>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #dc3545;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- SUMMARY STATS CARDS --}}
                <div class="section-group-title mt-0">
                    <i class="bi bi-file-earmark-text"></i> Franchise Statistics
                </div>
                <div class="stats-grid mb-4">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Total Franchises
                            <span class="stat-badge stat-badge-total">All</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Active Franchises
                            <span class="stat-badge stat-badge-active">Active</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['active'] }}</div>
                    </div>
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Expiring Franchises
                            <span class="stat-badge stat-badge-expiring">30 Days</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['expiring'] }}</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Inactive Franchises
                            <span class="stat-badge stat-badge-inactive">Expired</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['inactive'] }}</div>
                    </div>
                </div>

                {{-- UPCOMING EXPIRATIONS WIDGET --}}
                @if($upcomingExpirations->isNotEmpty())
                    <div class="expirations-card mb-4">
                        <div class="expirations-header">
                            <h3 class="expirations-title">
                                <i class="bi bi-exclamation-triangle-fill text-warning"></i> Upcoming Franchise Expirations
                            </h3>
                            <span class="badge bg-warning text-dark" style="font-size: 11px; font-weight: 600; padding: 5px 10px;">
                                Warning Period: Next 30 Days
                            </span>
                        </div>
                        <div class="expirations-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4 py-2.5 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Franchise #</th>
                                            <th class="py-2.5 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Operator</th>
                                            <th class="py-2.5 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Vehicle / Plate</th>
                                            <th class="py-2.5 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Expiration Date</th>
                                            <th class="py-2.5 text-uppercase text-muted" style="font-size: 11px; font-weight: 700;">Days Remaining</th>
                                            <th class="py-2.5 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingExpirations as $expFranchise)
                                            <tr>
                                                <td class="ps-4">
                                                    <strong>{{ $expFranchise->franchise_number }}</strong>
                                                </td>
                                                <td>
                                                    {{ $expFranchise->operator ? $expFranchise->operator->first_name . ' ' . $expFranchise->operator->last_name : 'Unassigned' }}
                                                </td>
                                                <td>
                                                    <code>{{ $expFranchise->vehicle ? $expFranchise->vehicle->plate_number : 'Unassigned' }}</code>
                                                </td>
                                                <td>
                                                    {{ $expFranchise->expiration_date ? $expFranchise->expiration_date->format('M d, Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark px-2 py-1">
                                                        <i class="bi bi-clock-history me-1"></i>
                                                        {{ $expFranchise->days_remaining }} {{ $expFranchise->days_remaining == 1 ? 'day' : 'days' }} remaining
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('franchises.renew', $expFranchise->id) }}" class="btn btn-sm btn-success px-2.5 py-1" style="font-size: 12px;">
                                                        <i class="bi bi-arrow-repeat me-1"></i> Renew Now
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- SEARCH & FILTER PANEL --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('franchises.index') }}" class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        class="form-control border-start-0 ps-0" 
                                        placeholder="Search franchise #, operator, plate, receipt..." 
                                        value="{{ request('search') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <select name="status" class="form-select">
                                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Expiring (30 Days)</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive / Expired</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <select name="expiration_filter" class="form-select">
                                    <option value="all" {{ request('expiration_filter') === 'all' ? 'selected' : '' }}>All Expirations</option>
                                    <option value="7_days" {{ request('expiration_filter') === '7_days' ? 'selected' : '' }}>Expiring within 7 days</option>
                                    <option value="30_days" {{ request('expiration_filter') === '30_days' ? 'selected' : '' }}>Expiring within 30 days</option>
                                    <option value="expired" {{ request('expiration_filter') === 'expired' ? 'selected' : '' }}>Already Expired</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-3" style="background-color: #0b2342; border-color: #0b2342;">
                                    <i class="bi bi-funnel me-1"></i> Filter
                                </button>
                                <a href="{{ route('franchises.index') }}" class="btn btn-outline-secondary px-3">
                                    Reset
                                </a>
                                <a href="{{ route('franchises.create') }}" class="btn btn-success ms-auto px-3 d-flex align-items-center gap-1.5">
                                    <i class="bi bi-plus-lg"></i> Register New Franchise
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- FRANCHISE LIST TABLE --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                                <thead class="table-light">
                                    <tr style="border-bottom: 2px solid #dee2e6;">
                                        <th class="ps-4 py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Franchise Number</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Operator</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Vehicle / Plate No.</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Registration Date</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Expiration Date</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Status</th>
                                        <th class="py-3 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($franchises as $franchise)
                                        @php
                                            $calculatedStatus = $franchise->calculated_status;
                                            $badgeStyle = $franchise->status_badge_style;
                                        @endphp
                                        <tr style="border-bottom: 1px solid #e9ecef;">
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $franchise->franchise_number }}</div>
                                                <small class="text-muted">ID: #{{ $franchise->id }}</small>
                                            </td>
                                            <td>
                                                @if($franchise->operator)
                                                    <a href="{{ route('operators.show', $franchise->operator->id) }}" class="text-decoration-none fw-semibold">
                                                        {{ $franchise->operator->first_name }} {{ $franchise->operator->last_name }}
                                                    </a>
                                                    <div class="text-muted small">{{ $franchise->operator->contact_number }}</div>
                                                @else
                                                    <span class="text-muted small italic">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($franchise->vehicle)
                                                    <code>{{ $franchise->vehicle->plate_number }}</code>
                                                    <div class="text-muted small">{{ $franchise->vehicle->make }} {{ $franchise->vehicle->model }}</div>
                                                @else
                                                    <span class="text-muted small italic">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $franchise->franchise_date ? $franchise->franchise_date->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $franchise->expiration_date ? $franchise->expiration_date->format('M d, Y') : 'N/A' }}</div>
                                                @if($franchise->days_remaining !== null)
                                                    <small class="{{ $franchise->days_remaining < 0 ? 'text-danger' : ($franchise->days_remaining <= 30 ? 'text-warning fw-bold' : 'text-muted') }}">
                                                        @if($franchise->days_remaining < 0)
                                                            Expired {{ abs($franchise->days_remaining) }} days ago
                                                        @elseif($franchise->days_remaining === 0)
                                                            Expires today
                                                        @else
                                                            {{ $franchise->days_remaining }} days remaining
                                                        @endif
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge px-2.5 py-1.5 text-uppercase" style="font-size: 11px; font-weight: 600; border-radius: 6px; {{ $badgeStyle }}">
                                                    {{ $calculatedStatus }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1.5">
                                                    <a href="{{ route('franchises.show', $franchise->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('franchises.edit', $franchise->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Franchise">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <a href="{{ route('franchises.renew', $franchise->id) }}" class="btn btn-sm btn-outline-success" title="Renew Franchise">
                                                        <i class="bi bi-arrow-repeat"></i> Renew
                                                    </a>

                                                    @if(auth()->user()->role?->name === 'admin')
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete Franchise"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal{{ $franchise->id }}"
                                                        >
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>

                                                        {{-- DELETE MODAL --}}
                                                        <div class="modal fade" id="deleteModal{{ $franchise->id }}" tabindex="-1" aria-hidden="true" style="text-align: left;">
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
                                                                        <p class="text-muted small mt-2 mb-0">This will also delete associated renewal histories. This action cannot be undone.</p>
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
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="bi bi-file-earmark-text display-6 d-block mb-3 text-muted" style="opacity: 0.3;"></i>
                                                No franchises registered matching your criteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $franchises->links() }}
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
