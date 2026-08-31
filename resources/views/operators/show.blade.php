<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Operator Profile</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('operators.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="content">
            <div class="container-fluid">
                
                <div class="row g-4">
                    {{-- PROFILE OVERVIEW CARD --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="card-body">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-primary mb-3" style="width: 80px; height: 80px; font-size: 32px; border: 3px solid #0b2342;">
                                    <i class="bi bi-person-badge text-primary" style="color: #0b2342 !important;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">
                                    {{ $operator->first_name }} {{ $operator->middle_name ? $operator->middle_name . ' ' : '' }}{{ $operator->last_name }}
                                </h5>
                                <p class="text-muted small mb-3">Operator ID: {{ $operator->operator_id }}</p>
                                
                                <span class="badge px-3 py-2 text-uppercase mb-4" style="font-size: 12px; font-weight: 600; border-radius: 20px; {{ $operator->status === 'active' ? 'background-color: #d1e7dd; color: #0f5132;' : 'background-color: #f8d7da; color: #842029;' }}">
                                    {{ $operator->status }}
                                </span>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('operators.edit', $operator->id) }}" class="btn btn-primary" style="background-color: #0b2342; border-color: #0b2342;">
                                        <i class="bi bi-pencil-square me-1.5"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL SPECIFICATIONS CARD --}}
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    Operator Details
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-striped table-borderless align-middle mb-0" style="font-size: 14px;">
                                    <tbody>
                                        <tr>
                                            <th class="text-muted py-2.5" style="width: 30%;">Operator ID</th>
                                            <td class="text-dark fw-bold py-2.5">{{ $operator->operator_id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">First Name</th>
                                            <td class="text-dark py-2.5">{{ $operator->first_name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Middle Name</th>
                                            <td class="text-dark py-2.5">{{ $operator->middle_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Last Name</th>
                                            <td class="text-dark py-2.5">{{ $operator->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Address</th>
                                            <td class="text-dark py-2.5">{{ $operator->address }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Contact Number</th>
                                            <td class="text-dark py-2.5">{{ $operator->contact_number }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Email Address</th>
                                            <td class="text-dark py-2.5">{{ $operator->email ?? 'None' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Date Registered</th>
                                            <td class="text-dark py-2.5">{{ $operator->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    {{-- ASSIGNED VEHICLES CARD --}}
                    <div class="col-12 mt-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-car-front me-2"></i>Owned / Managed Vehicles
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                @if($operator->vehicles->isEmpty())
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-car-front display-6 d-block mb-3 text-muted" style="opacity: 0.3;"></i>
                                        No vehicles assigned to this operator.
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-4 py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Vehicle ID</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Plate Number</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Type</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Make / Model / Color</th>
                                                    <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Registration Expiration</th>
                                                    <th class="py-3 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($operator->vehicles as $vehicle)
                                                    <tr style="border-bottom: 1px solid #e9ecef;">
                                                        <td class="ps-4">
                                                            <strong>{{ $vehicle->vehicle_id }}</strong>
                                                        </td>
                                                        <td><code>{{ $vehicle->plate_number }}</code></td>
                                                        <td>{{ $vehicle->vehicle_type ?? 'N/A' }}</td>
                                                        <td>{{ $vehicle->color }} {{ $vehicle->make }} {{ $vehicle->model }}</td>
                                                        <td>
                                                            {{ $vehicle->registration_expiration ? \Carbon\Carbon::parse($vehicle->registration_expiration)->format('M d, Y') : 'None' }}
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye"></i> View Vehicle
                                                            </a>
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
