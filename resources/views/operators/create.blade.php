<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Register New Operator</h1>
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
                
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom p-4">
                                <h5 class="card-title text-primary mb-0" style="font-weight: 600; color: #0b2342 !important;">
                                    <i class="bi bi-person-badge-fill me-2"></i>Operator Information Form
                                </h5>
                                <p class="text-muted small mb-0 mt-1">Please provide accurate operator registration details below.</p>
                            </div>
                            
                            <form method="POST" action="{{ route('operators.store') }}" class="card-body p-4">
                                @csrf
                                
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
                                                placeholder="e.g. Emilio"
                                                value="{{ old('first_name') }}"
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
                                                placeholder="e.g. Famy"
                                                value="{{ old('middle_name') }}"
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
                                                placeholder="e.g. Aguinaldo"
                                                value="{{ old('last_name') }}"
                                                required
                                            >
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-8">
                                            <label for="address" class="form-label required-field">Address <span class="text-danger">*</span></label>
                                            <input 
                                                type="text" 
                                                name="address" 
                                                id="address"
                                                class="form-control @error('address') is-invalid @enderror" 
                                                placeholder="e.g. Bacon, Sorsogon City, Sorsogon"
                                                value="{{ old('address') }}"
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
                                                placeholder="e.g. 09171234567"
                                                value="{{ old('contact_number') }}"
                                                required
                                            >
                                            @error('contact_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4 text-muted" style="opacity: 0.15;">

                                {{-- OPERATOR DETAILS SECTION --}}
                                <div class="mb-4">
                                    <h6 class="text-muted text-uppercase mb-3" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">
                                        2. Contact Email & Status
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email Address</label>
                                            <input 
                                                type="email" 
                                                name="email" 
                                                id="email"
                                                class="form-control @error('email') is-invalid @enderror" 
                                                placeholder="e.g. emilio@gmail.com"
                                                value="{{ old('email') }}"
                                            >
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="status" class="form-label required-field">Status <span class="text-danger">*</span></label>
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
                                    </div>
                                </div>

                                {{-- FORM SUBMIT ACTIONS --}}
                                <div class="d-flex justify-content-end gap-2 mt-5">
                                    <a href="{{ route('operators.index') }}" class="btn btn-outline-secondary px-4">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" style="background-color: #0b2342; border-color: #0b2342;">
                                        <i class="bi bi-save me-1.5"></i> Register Operator
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
