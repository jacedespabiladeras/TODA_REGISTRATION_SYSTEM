<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Vehicle Profile</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="content">
            <div class="container-fluid">
                
                {{-- STATUS COMPUTATION --}}
                @php
                    $statusText = 'Active';
                    $badgeStyle = 'background-color: #d1e7dd; color: #0f5132;';
                    $today = now()->startOfDay();
                    $expiration = $vehicle->registration_expiration ? \Carbon\Carbon::parse($vehicle->registration_expiration)->startOfDay() : null;

                    if ($vehicle->status === 'inactive') {
                        $statusText = 'Inactive';
                        $badgeStyle = 'background-color: #f8d7da; color: #842029;';
                    } elseif ($expiration) {
                        if ($expiration->lt($today)) {
                            $statusText = 'Inactive';
                            $badgeStyle = 'background-color: #f8d7da; color: #842029;';
                        } elseif ($expiration->diffInDays($today) <= 30) {
                            $statusText = 'Expiring';
                            $badgeStyle = 'background-color: #fff3cd; color: #664d03;';
                        }
                    }
                @endphp

                <div class="row g-4">
                    {{-- OVERVIEW CARD --}}
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="card-body">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-primary mb-3" style="width: 80px; height: 80px; font-size: 32px; border: 3px solid #0b2342;">
                                    <i class="bi bi-car-front text-primary" style="color: #0b2342 !important;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">
                                    {{ $vehicle->make }} {{ $vehicle->model }}
                                </h5>
                                <p class="text-muted small mb-3">Plate No: <code class="bg-light px-2 py-1 rounded text-dark fw-bold">{{ $vehicle->plate_number }}</code></p>
                                
                                <span class="badge px-3 py-2 text-uppercase mb-4" style="font-size: 12px; font-weight: 600; border-radius: 20px; {{ $badgeStyle }}">
                                    {{ $statusText }}
                                </span>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-primary" style="background-color: #0b2342; border-color: #0b2342;">
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
                                    Vehicle Specifications
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <table class="table table-striped table-borderless align-middle mb-0" style="font-size: 14px;">
                                    <tbody>
                                        <tr>
                                            <th class="text-muted py-2.5" style="width: 35%;">Vehicle ID</th>
                                            <td class="text-dark fw-bold py-2.5">{{ $vehicle->vehicle_id }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Plate Number</th>
                                            <td class="text-dark py-2.5"><code>{{ $vehicle->plate_number }}</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Vehicle Type</th>
                                            <td class="text-dark py-2.5">{{ $vehicle->vehicle_type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Make</th>
                                            <td class="text-dark py-2.5">{{ $vehicle->make ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Model</th>
                                            <td class="text-dark py-2.5">{{ $vehicle->model ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Color</th>
                                            <td class="text-dark py-2.5">{{ $vehicle->color ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Engine / Motor Number</th>
                                            <td class="text-dark py-2.5"><code>{{ $vehicle->motor_number ?? 'N/A' }}</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Chassis Number</th>
                                            <td class="text-dark py-2.5"><code>{{ $vehicle->chassis_number ?? 'N/A' }}</code></td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Registration Expiration</th>
                                            <td class="text-dark py-2.5">
                                                @if($vehicle->registration_expiration)
                                                    {{ \Carbon\Carbon::parse($vehicle->registration_expiration)->format('F d, Y') }}
                                                    @if($statusText === 'Expiring')
                                                        <span class="badge bg-warning text-dark ms-2" style="font-size: 10px;">Expiring soon</span>
                                                    @elseif($statusText === 'Inactive')
                                                        <span class="badge bg-danger text-white ms-2" style="font-size: 10px;">Expired</span>
                                                    @endif
                                                @else
                                                    None
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted py-2.5">Date Registered</th>
                                            <td class="text-dark py-2.5">{{ $vehicle->created_at->format('F d, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    {{-- ASSIGNED ROLES AND RELATIONS --}}
                    <div class="col-md-6 mt-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-person-vcard me-2"></i>Assigned Driver
                                </h5>
                            </div>
                            <div class="card-body p-4 text-center">
                                @if($vehicle->driver)
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-primary mb-3" style="width: 60px; height: 60px; font-size: 24px;">
                                        <i class="bi bi-person-vcard"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $vehicle->driver->first_name }} {{ $vehicle->driver->last_name }}</h6>
                                    <p class="text-muted small mb-3">ID: {{ $vehicle->driver->driver_id }}</p>
                                    <a href="{{ route('drivers.show', $vehicle->driver->id) }}" class="btn btn-sm btn-outline-primary px-3">
                                        View Driver Profile
                                    </a>
                                @else
                                    <div class="text-muted py-4">
                                        <i class="bi bi-person-dash display-6 d-block mb-2 text-muted" style="opacity: 0.3;"></i>
                                        No driver assigned to this vehicle.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mt-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-person-badge me-2"></i>Assigned Operator
                                </h5>
                            </div>
                            <div class="card-body p-4 text-center">
                                @if($vehicle->operator)
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-primary mb-3" style="width: 60px; height: 60px; font-size: 24px;">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <h6 class="fw-bold mb-1">{{ $vehicle->operator->first_name }} {{ $vehicle->operator->last_name }}</h6>
                                    <p class="text-muted small mb-3">ID: {{ $vehicle->operator->operator_id }}</p>
                                    <a href="{{ route('operators.show', $vehicle->operator->id) }}" class="btn btn-sm btn-outline-primary px-3">
                                        View Operator Profile
                                    </a>
                                @else
                                    <div class="text-muted py-4">
                                        <i class="bi bi-person-dash display-6 d-block mb-2 text-muted" style="opacity: 0.3;"></i>
                                        No operator assigned to this vehicle.
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
