<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Tracking & Expiration Alerts</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-block">
                    {{ now()->format('d M Y') }}
                </span>
                <span class="badge bg-primary">
                    {{ auth()->user()->role?->name === 'admin' ? 'Administrator' : 'Staff' }}
                </span>
                <x-user-menu />
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="content">
            <div class="container-fluid">

                {{-- STATS GRID --}}
                <div class="stats-grid mb-4">
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Total Expiration Alerts
                            <span class="stat-badge stat-badge-expiring">{{ $warningDays }} Days</span>
                        </div>
                        <div class="stat-card-value">{{ $totalAlerts }}</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Expiring Drivers
                            <span class="stat-badge stat-badge-inactive">Licenses</span>
                        </div>
                        <div class="stat-card-value">{{ $totalExpiringDrivers }}</div>
                    </div>
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Expiring Franchises
                            <span class="stat-badge stat-badge-total">Franchises</span>
                        </div>
                        <div class="stat-card-value">{{ $totalExpiringFranchises }}</div>
                    </div>
                </div>

                {{-- EXPIRING DRIVERS TABLE --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-primary mb-0 d-flex align-items-center gap-2" style="font-size: 15px; font-weight: 700; color: #0b2342 !important;">
                            <i class="bi bi-person-vcard"></i> Drivers Expiration Alerts (Within {{ $warningDays }} Days)
                        </h5>
                        <span class="badge bg-danger">{{ $totalExpiringDrivers }} Alerts</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Driver ID</th>
                                        <th>Full Name</th>
                                        <th>Contact Number</th>
                                        <th>License Number</th>
                                        <th>License Expiration</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expiringDrivers as $driver)
                                        @php
                                            $exp = \Carbon\Carbon::parse($driver->license_expiration);
                                            $isExpired = $exp->isPast();
                                            $daysDiff = (int) now()->diffInDays($exp, false);
                                        @endphp
                                        <tr>
                                            <td class="ps-3 fw-bold">{{ $driver->driver_id }}</td>
                                            <td>{{ $driver->first_name }} {{ $driver->last_name }}</td>
                                            <td>{{ $driver->contact_number }}</td>
                                            <td><code>{{ $driver->license_number }}</code></td>
                                            <td>
                                                <span class="fw-semibold {{ $isExpired ? 'text-danger' : 'text-warning' }}">
                                                    {{ $exp->format('M d, Y') }}
                                                </span>
                                                <small class="d-block text-muted">
                                                    {{ $isExpired ? 'Expired ' . abs($daysDiff) . ' days ago' : 'Expires in ' . $daysDiff . ' days' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($isExpired)
                                                    <span class="badge bg-danger">EXPIRED</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">EXPIRING</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('drivers.show', $driver->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                                                No expiring driver licenses within {{ $warningDays }} days.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- EXPIRING FRANCHISES TABLE --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-primary mb-0 d-flex align-items-center gap-2" style="font-size: 15px; font-weight: 700; color: #0b2342 !important;">
                            <i class="bi bi-file-earmark-text"></i> Franchise Expiration Alerts (Within {{ $warningDays }} Days)
                        </h5>
                        <span class="badge bg-danger">{{ $totalExpiringFranchises }} Alerts</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Franchise No.</th>
                                        <th>TODA Association</th>
                                        <th>Driver</th>
                                        <th>Operator</th>
                                        <th>Valid Until</th>
                                        <th>Status</th>
                                        <th class="text-end pe-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expiringFranchises as $franchise)
                                        @php
                                            $validUntil = \Carbon\Carbon::parse($franchise->valid_until);
                                            $isExpired = $validUntil->isPast();
                                            $daysDiff = (int) now()->diffInDays($validUntil, false);
                                        @endphp
                                        <tr>
                                            <td class="ps-3 fw-bold">{{ $franchise->franchise_number }}</td>
                                            <td>{{ $franchise->toda_name }}</td>
                                            <td>{{ $franchise->driver ? $franchise->driver->first_name . ' ' . $franchise->driver->last_name : 'N/A' }}</td>
                                            <td>{{ $franchise->operator ? $franchise->operator->first_name . ' ' . $franchise->operator->last_name : 'N/A' }}</td>
                                            <td>
                                                <span class="fw-semibold {{ $isExpired ? 'text-danger' : 'text-warning' }}">
                                                    {{ $validUntil->format('M d, Y') }}
                                                </span>
                                                <small class="d-block text-muted">
                                                    {{ $isExpired ? 'Expired ' . abs($daysDiff) . ' days ago' : 'Expires in ' . $daysDiff . ' days' }}
                                                </small>
                                            </td>
                                            <td>
                                                @if($isExpired)
                                                    <span class="badge bg-danger">EXPIRED</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">EXPIRING</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('franchises.renew', $franchise->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-arrow-repeat"></i> Renew
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                                                No expiring franchises within {{ $warningDays }} days.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-app-layout>