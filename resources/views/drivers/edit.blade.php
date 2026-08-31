<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Edit Driver Profile</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1.5">
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
                                    <i class="bi bi-pencil-square me-2"></i>Edit Driver Information
                                </h5>
                                <p class="text-muted small mb-0 mt-1">Make changes to driver registration information below. License number checks remain unique.</p>
                            </div>
                            
                            <form method="POST" action="{{ route('drivers.update', $driver->id) }}" class="card-body p-4">
                                @csrf
                                @method('PUT')
                                
                                {{-- PERSONAL INFORMATION SECTION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        1. Personal Information
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="first_name" class="form-label required-field">First Name <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="first_name" 
                                                id="first_name"
                                                class="form-control @error('first_name') is-invalid @enderror" 
                                                placeholder="e.g. Juan"
                                                value="{{ old('first_name', $driver->first_name) }}"
                                                required
                                            >
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="middle_name" class="form-label">Middle Name</label>
                                            <input 
                                                type="text" 
                                                name="middle_name" 
                                                id="middle_name"
                                                class="form-control @error('middle_name') is-invalid @enderror" 
                                                placeholder="e.g. Reyes"
                                                value="{{ old('middle_name', $driver->middle_name) }}"
                                            >
                                            @error('middle_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="last_name" class="form-label required-field">Last Name <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="last_name" 
                                                id="last_name"
                                                class="form-control @error('last_name') is-invalid @enderror" 
                                                placeholder="e.g. Dela Cruz"
                                                value="{{ old('last_name', $driver->last_name) }}"
                                                required
                                            >
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <label for="address" class="form-label required-field">Residential Address <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="address" 
                                                id="address"
                                                class="form-control @error('address') is-invalid @enderror" 
                                                placeholder="e.g. Piot, Sorsogon City, Sorsogon"
                                                value="{{ old('address', $driver->address) }}"
                                                required
                                            >
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contact_number" class="form-label required-field">Contact Number <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="contact_number" 
                                                id="contact_number"
                                                class="form-control @error('contact_number') is-invalid @enderror" 
                                                placeholder="e.g. 09123456789"
                                                value="{{ old('contact_number', $driver->contact_number) }}"
                                                required
                                            >
                                            @error('contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted" style="opacity: 0.15;">

                                {{-- DRIVER LICENSE INFORMATION SECTION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        2. Driver's License & Status
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="license_number" class="form-label required-field">Driver's License Number <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="license_number" 
                                                id="license_number"
                                                class="form-control @error('license_number') is-invalid @enderror" 
                                                placeholder="e.g. N01-12-345678"
                                                value="{{ old('license_number', $driver->license_number) }}"
                                                required
                                            >
                                            @error('license_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="license_expiration" class="form-label">License Expiration Date</label>
                                            <input 
                                                type="date" 
                                                name="license_expiration" 
                                                id="license_expiration"
                                                class="form-control @error('license_expiration') is-invalid @enderror" 
                                                value="{{ old('license_expiration', $driver->license_expiration) }}"
                                            >
                                            @error('license_expiration')
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
                                                <option value="active" {{ old('status', $driver->status) === 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $driver->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- FORM SUBMIT ACTIONS --}}
                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" style="background-color: #0b2342; border-color: #0b2342;">
                                        <i class="bi bi-save me-1.5"></i> Save Changes
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
