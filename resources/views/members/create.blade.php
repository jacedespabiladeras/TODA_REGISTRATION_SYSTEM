<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Member Registration</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-block">
                    {{ now()->format('d M Y') }}
                </span>
                <span class="badge bg-primary">
                    Administrator
                </span>
                <x-user-menu />
            </div>
        </header>

        <main class="content">
            <div class="container-fluid">
                <div class="mb-4">
                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-arrow-left"></i> Back to Members List
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #dc3545;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong class="d-block mb-1">Please correct the following errors:</strong>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-3">
                        <h5 class="card-title text-primary mb-0 d-flex align-items-center gap-2" style="font-size: 15px; font-weight: 700; color: #0b2342 !important;">
                            <i class="bi bi-person-plus"></i> Register New Member
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Juan Dela Cruz" required value="{{ old('name') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="e.g. juan@example.com" required value="{{ old('email') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" placeholder="e.g. 09123456789" value="{{ old('contact_number') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Address</label>
                                    <input type="text" name="address" class="form-control" placeholder="e.g. Bibincahan, Sorsogon City" value="{{ old('address') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Member ID <small class="text-muted">(Optional)</small></label>
                                    <input type="text" name="member_id" class="form-control" placeholder="Auto-generated if empty" value="{{ old('member_id') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">System Role <span class="text-danger">*</span></label>
                                    <select name="role_id" class="form-select" required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id', 2) == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required minlength="8">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type password" required minlength="8">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-uppercase text-muted">Profile Photo <small class="text-muted">(Optional, max 2MB)</small></label>
                                    <input type="file" name="profile_picture" class="form-control" accept="image/*">
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top">
                                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary px-3">Cancel</a>
                                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-1.5" style="background-color: #0b2342; border-color: #0b2342;">
                                        <i class="bi bi-check-lg"></i> Register Member
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
