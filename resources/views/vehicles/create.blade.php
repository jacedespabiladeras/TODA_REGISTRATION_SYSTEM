<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Register New Vehicle</h1>
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
                
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-car-front-fill me-2"></i>Vehicle Registration Form
                                </h5>
                                <p class="text-muted small mb-0 mt-1">Please provide accurate vehicle details and assign a driver and operator.</p>
                            </div>
                            
                            <form method="POST" action="{{ route('vehicles.store') }}" class="card-body p-4">
                                @csrf
                                
                                {{-- VEHICLE INFORMATION SECTION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        1. Vehicle Specifications
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="plate_number" class="form-label required-field">Plate Number <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="plate_number" 
                                                id="plate_number"
                                                class="form-control @error('plate_number') is-invalid @enderror" 
                                                placeholder="e.g. 123-ABC or MV File No."
                                                value="{{ old('plate_number') }}"
                                                required
                                            >
                                            @error('plate_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="vehicle_type" class="form-label">Vehicle Type</label>
                                            <input 
                                                type="text" 
                                                name="vehicle_type" 
                                                id="vehicle_type"
                                                class="form-control @error('vehicle_type') is-invalid @enderror" 
                                                placeholder="e.g. Tricycle, Motorcycle"
                                                value="{{ old('vehicle_type') }}"
                                            >
                                            @error('vehicle_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="make" class="form-label">Make</label>
                                            <input 
                                                type="text" 
                                                name="make" 
                                                id="make"
                                                class="form-control @error('make') is-invalid @enderror" 
                                                placeholder="e.g. Honda, Kawasaki"
                                                value="{{ old('make') }}"
                                            >
                                            @error('make')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="model" class="form-label">Model</label>
                                            <input 
                                                type="text" 
                                                name="model" 
                                                id="model"
                                                class="form-control @error('model') is-invalid @enderror" 
                                                placeholder="e.g. TMX 125, Barako 175"
                                                value="{{ old('model') }}"
                                            >
                                            @error('model')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="color" class="form-label">Color</label>
                                            <input 
                                                type="text" 
                                                name="color" 
                                                id="color"
                                                class="form-control @error('color') is-invalid @enderror" 
                                                placeholder="e.g. Red, Black, Blue"
                                                value="{{ old('color') }}"
                                            >
                                            @error('color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="status" class="form-label required-field">Registration Status <span class="text-danger">*</span></label>
                                            <select 
                                                name="status" 
                                                id="status"
                                                class="form-select @error('status') is-invalid @enderror" 
                                                required
                                            >
                                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="motor_number" class="form-label">Engine / Motor Number</label>
                                            <input 
                                                type="text" 
                                                name="motor_number" 
                                                id="motor_number"
                                                class="form-control @error('motor_number') is-invalid @enderror" 
                                                placeholder="e.g. ENG-987654"
                                                value="{{ old('motor_number') }}"
                                            >
                                            @error('motor_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="chassis_number" class="form-label">Chassis Number</label>
                                            <input 
                                                type="text" 
                                                name="chassis_number" 
                                                id="chassis_number"
                                                class="form-control @error('chassis_number') is-invalid @enderror" 
                                                placeholder="e.g. CHA-123456"
                                                value="{{ old('chassis_number') }}"
                                            >
                                            @error('chassis_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="registration_expiration" class="form-label">Registration Expiration Date</label>
                                            <input 
                                                type="date" 
                                                name="registration_expiration" 
                                                id="registration_expiration"
                                                class="form-control @error('registration_expiration') is-invalid @enderror" 
                                                value="{{ old('registration_expiration') }}"
                                            >
                                            @error('registration_expiration')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted" style="opacity: 0.15;">

                                {{-- ASSIGNMENT SECTION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        2. Driver & Operator Assignments
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="driver_id" class="form-label">Assigned Driver</label>
                                            <select 
                                                name="driver_id" 
                                                id="driver_id"
                                                class="form-select @error('driver_id') is-invalid @enderror"
                                            >
                                                <option value="">[ Select Driver ▼ ]</option>
                                                @foreach($drivers as $driver)
                                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                                        {{ $driver->last_name }}, {{ $driver->first_name }} ({{ $driver->driver_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('driver_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text small text-muted">Only active drivers are listed here. Optional.</div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="operator_id" class="form-label">Assigned Operator</label>
                                            <select 
                                                name="operator_id" 
                                                id="operator_id"
                                                class="form-select @error('operator_id') is-invalid @enderror"
                                            >
                                                <option value="">[ Select Operator ▼ ]</option>
                                                @foreach($operators as $operator)
                                                    <option value="{{ $operator->id }}" {{ old('operator_id') == $operator->id ? 'selected' : '' }}>
                                                        {{ $operator->last_name }}, {{ $operator->first_name }} ({{ $operator->operator_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('operator_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text small text-muted">Only active operators are listed here. Optional.</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- FORM SUBMIT ACTIONS --}}
                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" style="background-color: #0b2342; border-color: #0b2342;">
                                        <i class="bi bi-save me-1.5"></i> Register Vehicle
                                    </button>
                                </div>

                            </form>
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
