<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Register New Franchise</h1>
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

                <div class="row justify-content-center">
                    <div class="col-lg-10">

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-file-earmark-plus me-2"></i>TODA Franchise Registration Form
                                </h5>
                                <p class="text-muted small mb-0 mt-1">
                                    Register a new TODA franchise and assign the registered operator and vehicle.
                                </p>
                            </div>

                            <form method="POST" action="{{ route('franchises.store') }}" class="card-body p-4">
                                @csrf

                                {{-- SECTION 1: FRANCHISE INFORMATION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        1. Franchise Details
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="franchise_number" class="form-label">Franchise Number <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="franchise_number" 
                                                id="franchise_number" 
                                                class="form-control @error('franchise_number') is-invalid @enderror" 
                                                value="{{ old('franchise_number', $suggestedFranchiseNumber) }}"
                                                placeholder="e.g. FR-2026-0001"
                                                required
                                            >
                                            @error('franchise_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text small text-muted">Auto-suggested sequential franchise number.</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="status" class="form-label">Franchise Status <span class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="expired" {{ old('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="franchise_date" class="form-label">Registration / Issue Date <span class="text-danger">*</span></label>
                                            <input 
                                                type="date" 
                                                name="franchise_date" 
                                                id="franchise_date" 
                                                class="form-control @error('franchise_date') is-invalid @enderror" 
                                                value="{{ old('franchise_date', now()->toDateString()) }}"
                                                required
                                            >
                                            @error('franchise_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="expiration_date" class="form-label">Expiration Date <span class="text-danger">*</span></label>
                                            <input 
                                                type="date" 
                                                name="expiration_date" 
                                                id="expiration_date" 
                                                class="form-control @error('expiration_date') is-invalid @enderror" 
                                                value="{{ old('expiration_date', now()->addYear()->toDateString()) }}"
                                                required
                                            >
                                            @error('expiration_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text small text-muted">Standard franchise validity is 1 year from issue date.</div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted" style="opacity: 0.15;">

                                {{-- SECTION 2: OPERATOR INFORMATION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        2. Operator Assignment
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="operator_id" class="form-label">Select Operator <span class="text-danger">*</span></label>
                                            <select 
                                                name="operator_id" 
                                                id="operator_id" 
                                                class="form-select @error('operator_id') is-invalid @enderror" 
                                                required
                                                onchange="updateOperatorPreview(this)"
                                            >
                                                <option value="">[ Select Operator ▼ ]</option>
                                                @foreach($operators as $operator)
                                                    <option 
                                                        value="{{ $operator->id }}" 
                                                        data-name="{{ $operator->first_name }} {{ $operator->last_name }}"
                                                        data-id="{{ $operator->operator_id }}"
                                                        data-contact="{{ $operator->contact_number }}"
                                                        data-address="{{ $operator->address }}"
                                                        {{ old('operator_id') == $operator->id ? 'selected' : '' }}
                                                    >
                                                        {{ $operator->last_name }}, {{ $operator->first_name }} ({{ $operator->operator_id }}) - {{ $operator->contact_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('operator_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- OPERATOR PREVIEW CARD --}}
                                        <div class="col-12" id="operatorPreviewCard" style="display: none;">
                                            <div class="p-3 bg-light rounded border">
                                                <div class="d-flex align-items-center gap-2 mb-2 text-primary" style="color: #0b2342 !important;">
                                                    <i class="bi bi-person-badge-fill"></i>
                                                    <strong class="small text-uppercase">Selected Operator Details:</strong>
                                                </div>
                                                <div class="row g-2 small text-muted">
                                                    <div class="col-md-4"><strong>Name:</strong> <span id="opPrevName" class="text-dark"></span></div>
                                                    <div class="col-md-4"><strong>Operator ID:</strong> <span id="opPrevId" class="text-dark"></span></div>
                                                    <div class="col-md-4"><strong>Contact:</strong> <span id="opPrevContact" class="text-dark"></span></div>
                                                    <div class="col-md-12"><strong>Address:</strong> <span id="opPrevAddress" class="text-dark"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted" style="opacity: 0.15;">

                                {{-- SECTION 3: VEHICLE INFORMATION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        3. Vehicle Assignment
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="vehicle_id" class="form-label">Select Vehicle <span class="text-danger">*</span></label>
                                            <select 
                                                name="vehicle_id" 
                                                id="vehicle_id" 
                                                class="form-select @error('vehicle_id') is-invalid @enderror" 
                                                required
                                                onchange="updateVehiclePreview(this)"
                                            >
                                                <option value="">[ Select Vehicle ▼ ]</option>
                                                @foreach($vehicles as $vehicle)
                                                    <option 
                                                        value="{{ $vehicle->id }}"
                                                        data-plate="{{ $vehicle->plate_number }}"
                                                        data-id="{{ $vehicle->vehicle_id }}"
                                                        data-make="{{ $vehicle->make }} {{ $vehicle->model }}"
                                                        data-type="{{ $vehicle->vehicle_type ?? 'Tricycle' }}"
                                                        data-color="{{ $vehicle->color ?? 'N/A' }}"
                                                        data-driver="{{ $vehicle->driver ? $vehicle->driver->first_name . ' ' . $vehicle->driver->last_name : 'No Driver Assigned' }}"
                                                        {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}
                                                    >
                                                        {{ $vehicle->plate_number }} ({{ $vehicle->vehicle_id }}) - {{ $vehicle->make }} {{ $vehicle->model }} [{{ $vehicle->color }}]
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vehicle_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- VEHICLE PREVIEW CARD --}}
                                        <div class="col-12" id="vehiclePreviewCard" style="display: none;">
                                            <div class="p-3 bg-light rounded border">
                                                <div class="d-flex align-items-center gap-2 mb-2 text-primary" style="color: #0b2342 !important;">
                                                    <i class="bi bi-car-front-fill"></i>
                                                    <strong class="small text-uppercase">Selected Vehicle Details:</strong>
                                                </div>
                                                <div class="row g-2 small text-muted">
                                                    <div class="col-md-3"><strong>Plate Number:</strong> <code id="vehPrevPlate" class="text-dark"></code></div>
                                                    <div class="col-md-3"><strong>Vehicle ID:</strong> <span id="vehPrevId" class="text-dark"></span></div>
                                                    <div class="col-md-3"><strong>Make/Model:</strong> <span id="vehPrevMake" class="text-dark"></span></div>
                                                    <div class="col-md-3"><strong>Color:</strong> <span id="vehPrevColor" class="text-dark"></span></div>
                                                    <div class="col-md-12"><strong>Assigned Driver:</strong> <span id="vehPrevDriver" class="text-dark"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- FORM SUBMIT ACTIONS --}}
                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="{{ route('franchises.index') }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" style="background-color: #0b2342; border-color: #0b2342;">
                                        <i class="bi bi-save me-1.5"></i> Register Franchise
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- DYNAMIC PREVIEWS SCRIPT --}}
    <script>
        function updateOperatorPreview(select) {
            const card = document.getElementById('operatorPreviewCard');
            const selected = select.options[select.selectedIndex];
            if (select.value) {
                document.getElementById('opPrevName').textContent = selected.getAttribute('data-name');
                document.getElementById('opPrevId').textContent = selected.getAttribute('data-id');
                document.getElementById('opPrevContact').textContent = selected.getAttribute('data-contact');
                document.getElementById('opPrevAddress').textContent = selected.getAttribute('data-address');
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }

        function updateVehiclePreview(select) {
            const card = document.getElementById('vehiclePreviewCard');
            const selected = select.options[select.selectedIndex];
            if (select.value) {
                document.getElementById('vehPrevPlate').textContent = selected.getAttribute('data-plate');
                document.getElementById('vehPrevId').textContent = selected.getAttribute('data-id');
                document.getElementById('vehPrevMake').textContent = selected.getAttribute('data-make');
                document.getElementById('vehPrevColor').textContent = selected.getAttribute('data-color');
                document.getElementById('vehPrevDriver').textContent = selected.getAttribute('data-driver');
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }

        // Initialize previews on load if old value exists
        document.addEventListener('DOMContentLoaded', function () {
            const opSelect = document.getElementById('operator_id');
            if (opSelect && opSelect.value) updateOperatorPreview(opSelect);

            const vehSelect = document.getElementById('vehicle_id');
            if (vehSelect && vehSelect.value) updateVehiclePreview(vehSelect);

            // Expiration date auto-calculation when franchise_date changes
            const franchiseDateInput = document.getElementById('franchise_date');
            const expirationDateInput = document.getElementById('expiration_date');

            franchiseDateInput.addEventListener('change', function () {
                if (this.value) {
                    const d = new Date(this.value);
                    d.setFullYear(d.getFullYear() + 1);
                    expirationDateInput.value = d.toISOString().split('T')[0];
                }
            });
        });

        // Sidebar toggle
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
