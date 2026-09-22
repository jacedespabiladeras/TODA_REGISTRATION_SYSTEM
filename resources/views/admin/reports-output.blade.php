<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Reports Output</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button onclick="window.print()" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1.5">
                    <i class="bi bi-printer"></i> Print Report
                </button>
                <span class="badge bg-primary">Administrator</span>
                <x-user-menu />
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="content">
            <div class="container-fluid">

                {{-- REPORT HEADER --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex align-items-center justify-content-center gap-3 mb-2">
                            <img src="{{ asset('images/Sorsogon_City_Seal.png') }}" alt="Seal" style="width: 50px; height: 50px;">
                            <div>
                                <h4 class="fw-bold mb-0 text-dark">SORSOGON CITY GOVERNMENT</h4>
                                <small class="text-muted text-uppercase fw-semibold">TODA Registration & Franchising Management Office</small>
                            </div>
                        </div>
                        <h5 class="fw-bold text-primary mt-3 mb-1" style="color: #0b2342 !important;">
                            OFFICIAL SYSTEM CONSOLIDATED REPORT
                        </h5>
                        <p class="text-muted small mb-0">Generated on: {{ now()->format('F d, Y - h:i A') }} by {{ auth()->user()->name }} (Admin)</p>
                    </div>
                </div>

                {{-- DRIVERS SUMMARY TABLE --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                        <h6 class="card-title text-primary mb-0 fw-bold" style="color: #0b2342 !important;">
                            <i class="bi bi-person-vcard me-2"></i>1. Registered Drivers Master List
                        </h6>
                        <span class="badge bg-secondary">{{ $drivers->count() }} Records</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Driver ID</th>
                                        <th>Full Name</th>
                                        <th>License Number</th>
                                        <th>License Expiration</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($drivers as $d)
                                        <tr>
                                            <td class="ps-3 fw-bold">{{ $d->driver_id }}</td>
                                            <td>{{ $d->first_name }} {{ $d->last_name }}</td>
                                            <td><code>{{ $d->license_number }}</code></td>
                                            <td>{{ $d->license_expiration ? \Carbon\Carbon::parse($d->license_expiration)->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $d->contact_number }}</td>
                                            <td>
                                                <span class="badge {{ $d->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ strtoupper($d->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-3 text-muted">No driver records found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- FRANCHISES SUMMARY TABLE --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                        <h6 class="card-title text-primary mb-0 fw-bold" style="color: #0b2342 !important;">
                            <i class="bi bi-file-earmark-text me-2"></i>2. Franchise Registrations Master List
                        </h6>
                        <span class="badge bg-secondary">{{ $franchises->count() }} Records</span>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($franchises as $f)
                                        <tr>
                                            <td class="ps-3 fw-bold">{{ $f->franchise_number }}</td>
                                            <td>{{ $f->toda_name }}</td>
                                            <td>{{ $f->driver ? $f->driver->first_name . ' ' . $f->driver->last_name : 'N/A' }}</td>
                                            <td>{{ $f->operator ? $f->operator->first_name . ' ' . $f->operator->last_name : 'N/A' }}</td>
                                            <td>{{ $f->valid_until ? \Carbon\Carbon::parse($f->valid_until)->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $f->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ strtoupper($f->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-3 text-muted">No franchise records found.</td></tr>
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