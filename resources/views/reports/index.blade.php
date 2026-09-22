<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Reports & Analytics</h1>
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

                {{-- STATS SUMMARY --}}
                <div class="section-group-title">
                    <i class="bi bi-bar-chart-line-fill"></i> System Metrics Overview
                </div>

                <div class="stats-grid mb-4">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Registered Drivers
                            <span class="stat-badge stat-badge-total">Active: {{ $stats['drivers']['active'] }}</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['drivers']['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Registered Operators
                            <span class="stat-badge stat-badge-active">Active: {{ $stats['operators']['active'] }}</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['operators']['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Total Vehicles
                            <span class="stat-badge stat-badge-expiring">Units</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['vehicles']['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Franchises
                            <span class="stat-badge stat-badge-inactive">Active: {{ $stats['franchises']['active'] }}</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['franchises']['total'] }}</div>
                    </div>
                </div>

                {{-- REPORT MODULES CARDS --}}
                <div class="section-group-title">
                    <i class="bi bi-folder-check"></i> Report Generation
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-3 p-3 bg-primary-subtle text-primary">
                                        <i class="bi bi-person-vcard fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold">Drivers Report</h5>
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $stats['drivers']['total'] }} Total Records</span>
                                    </div>
                                </div>
                                <p class="text-muted small flex-grow-1">
                                    Summary and detailed lists of all active, expiring, and inactive drivers registered in the TODA system.
                                </p>
                                <a href="{{ route('drivers.index') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-2 mt-2">
                                    <i class="bi bi-arrow-right-circle"></i> View Drivers List
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-3 p-3 bg-success-subtle text-success">
                                        <i class="bi bi-person-badge fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold">Operators Report</h5>
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $stats['operators']['total'] }} Total Records</span>
                                    </div>
                                </div>
                                <p class="text-muted small flex-grow-1">
                                    Master list of all tricycle operators, contact numbers, addresses, and affiliated franchise units.
                                </p>
                                <a href="{{ route('operators.index') }}" class="btn btn-outline-success btn-sm d-flex align-items-center justify-content-center gap-2 mt-2">
                                    <i class="bi bi-arrow-right-circle"></i> View Operators List
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="rounded-3 p-3 bg-warning-subtle text-warning">
                                        <i class="bi bi-file-earmark-text fs-3"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1 fw-bold">Franchise & Renewals</h5>
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $stats['franchises']['total'] }} Franchises</span>
                                    </div>
                                </div>
                                <p class="text-muted small flex-grow-1">
                                    Franchise validity tracking, expiration monitoring, and renewal audit logs for Sorsogon City TODA associations.
                                </p>
                                <a href="{{ route('renewals.index') }}" class="btn btn-outline-warning btn-sm d-flex align-items-center justify-content-center gap-2 mt-2">
                                    <i class="bi bi-arrow-right-circle"></i> View Renewals Log
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role?->name === 'admin')
                    <div class="card border-0 shadow-sm bg-primary text-white p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h4 class="fw-bold text-white mb-1"><i class="bi bi-printer me-2"></i>Official Reports Output</h4>
                            <p class="mb-0 text-white-50">Generate, review, and print official government consolidated reports and audit summaries.</p>
                        </div>
                        <a href="{{ route('reports.output') }}" class="btn btn-light fw-bold text-primary px-4 py-2">
                            <i class="bi bi-file-earmark-bar-graph me-1"></i> Open Reports Output
                        </a>
                    </div>
                @endif

            </div>
        </main>
    </div>
</x-app-layout>