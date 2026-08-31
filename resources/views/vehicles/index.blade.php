<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Vehicle Registration</h1>
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

                {{-- SEARCH & FILTER PANEL --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('vehicles.index') }}" class="row g-3">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        class="form-control border-start-0 ps-0" 
                                        placeholder="Search plate, engine, chassis, driver, operator..." 
                                        value="{{ request('search') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Expiring (30 Days)</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive / Expired</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4" style="background-color: #0b2342; border-color: #0b2342;">
                                    Filter
                                </button>
                                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary px-3">
                                    Reset
                                </a>
                                <a href="{{ route('vehicles.create') }}" class="btn btn-success ms-auto px-3 d-flex align-items-center gap-2">
                                    <i class="bi bi-plus-lg"></i> Register Vehicle
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- VEHICLES TABLE --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                                <thead class="table-light">
                                    <tr style="border-bottom: 2px solid #dee2e6;">
                                        <th class="ps-4 py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Vehicle ID</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Plate No.</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Make / Model / Color</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Driver Assignment</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Operator Assignment</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Expiration</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Status</th>
                                        <th class="py-3 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vehicles as $vehicle)
                                        @php
                                            $statusText = 'Active';
                                            $today = now()->startOfDay();
                                            $expiration = $vehicle->registration_expiration ? \Carbon\Carbon::parse($vehicle->registration_expiration)->startOfDay() : null;

                                            if ($vehicle->status === 'inactive') {
                                                $statusText = 'Inactive';
                                            } elseif ($expiration) {
                                                if ($expiration->lt($today)) {
                                                    $statusText = 'Inactive';
                                                } elseif ($expiration->diffInDays($today) <= 30) {
                                                    $statusText = 'Expiring';
                                                }
                                            }
                                        @endphp
                                        <tr style="border-bottom: 1px solid #e9ecef;">
                                            <td class="ps-4">
                                                <strong>{{ $vehicle->vehicle_id }}</strong>
                                            </td>
                                            <td><code>{{ $vehicle->plate_number }}</code></td>
                                            <td>
                                                <div class="fw-bold">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                                                <div class="text-muted small">{{ $vehicle->color ?? 'No Color' }} • {{ $vehicle->vehicle_type ?? 'Unspecified' }}</div>
                                            </td>
                                            <td>
                                                @if($vehicle->driver)
                                                    <a href="{{ route('drivers.show', $vehicle->driver->id) }}" class="text-decoration-none fw-semibold">
                                                        {{ $vehicle->driver->first_name }} {{ $vehicle->driver->last_name }}
                                                    </a>
                                                    <div class="text-muted small">{{ $vehicle->driver->driver_id }}</div>
                                                @else
                                                    <span class="text-muted small italic">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($vehicle->operator)
                                                    <a href="{{ route('operators.show', $vehicle->operator->id) }}" class="text-decoration-none fw-semibold">
                                                        {{ $vehicle->operator->first_name }} {{ $vehicle->operator->last_name }}
                                                    </a>
                                                    <div class="text-muted small">{{ $vehicle->operator->operator_id }}</div>
                                                @else
                                                    <span class="text-muted small italic">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $vehicle->registration_expiration ? \Carbon\Carbon::parse($vehicle->registration_expiration)->format('M d, Y') : 'None' }}
                                            </td>
                                            <td>
                                                <span class="badge px-2.5 py-1.5 text-uppercase" style="font-size: 11px; font-weight: 600; border-radius: 6px; {{ $statusText === 'Active' ? 'background-color: #d1e7dd; color: #0f5132;' : ($statusText === 'Expiring' ? 'background-color: #fff3cd; color: #664d03;' : 'background-color: #f8d7da; color: #842029;') }}">
                                                    {{ $statusText }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1.5">
                                                    <a href="{{ route('vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                        <i class="bi bi-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('vehicles.edit', $vehicle->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Info">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    @if(auth()->user()->role?->name === 'admin')
                                                        <button 
                                                            type="button" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Delete Record"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal{{ $vehicle->id }}"
                                                        >
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>

                                                        {{-- DELETE MODAL --}}
                                                        <div class="modal fade" id="deleteModal{{ $vehicle->id }}" tabindex="-1" aria-hidden="true" style="text-align: left;">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content border-0 shadow-lg">
                                                                    <div class="modal-header bg-danger text-white">
                                                                        <h5 class="modal-title d-flex align-items-center gap-2">
                                                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                                                            Confirm Delete
                                                                        </h5>
                                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body p-4">
                                                                        <p class="mb-1">Are you sure you want to delete this vehicle?</p>
                                                                        <h6 class="text-dark fw-bold">Plate: {{ $vehicle->plate_number }} (ID: {{ $vehicle->vehicle_id }})</h6>
                                                                        <p class="text-muted small mt-2 mb-0">This action cannot be undone and will permanently remove this record from the database.</p>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                                                                        <form method="POST" action="{{ route('vehicles.destroy', $vehicle->id) }}" class="d-inline">
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
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="bi bi-car-front display-6 d-block mb-3 text-muted" style="opacity: 0.3;"></i>
                                                No vehicles registered.
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
                    {{ $vehicles->links() }}
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
