<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
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

                {{-- STATS GRID --}}
                <div class="stats-grid mb-4">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Total Members
                            <span class="stat-badge stat-badge-total">All</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Active Members
                            <span class="stat-badge stat-badge-active">Active</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['active'] }}</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Inactive Members
                            <span class="stat-badge stat-badge-inactive">Inactive</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['inactive'] }}</div>
                    </div>
                </div>

                {{-- SEARCH & FILTER PANEL --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('members.index') }}" class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        class="form-control border-start-0 ps-0" 
                                        placeholder="Search by member ID, name, or contact number..." 
                                        value="{{ request('search') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="role" class="form-select">
                                    <option value="all" {{ request('role') === 'all' || !request('role') ? 'selected' : '' }}>All Roles</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1.5" style="background-color: #0b2342; border-color: #0b2342;">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                @if(request('search') || (request('status') && request('status') !== 'all') || (request('role') && request('role') !== 'all'))
                                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                                        Reset
                                    </a>
                                @endif
                                <button type="button" class="btn btn-success d-flex align-items-center gap-1.5 text-nowrap px-3" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                    <i class="bi bi-plus-lg"></i> Add Member
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- MEMBERS TABLE --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title text-primary mb-0 d-flex align-items-center gap-2" style="font-size: 15px; font-weight: 700; color: #0b2342 !important;">
                            <i class="bi bi-people"></i> Registered Members List
                        </h5>
                        <button type="button" class="btn btn-sm btn-success d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                            <i class="bi bi-plus-lg"></i> + Add Member
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
                                <thead class="table-light">
                                    <tr style="border-bottom: 2px solid #dee2e6;">
                                        <th class="ps-4 py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Member ID</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Full Name</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Role</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Address</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Contact Number</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Membership Date</th>
                                        <th class="py-3 text-uppercase text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Status</th>
                                        <th class="py-3 text-uppercase text-muted text-end pe-4" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($members as $member)
                                        @php
                                            $memberCode = $member->member_id ?? ('MEM-' . str_pad((string)$member->id, 4, '0', STR_PAD_LEFT));
                                            $isAdmin = $member->role?->name === 'admin';
                                            $roleName = $member->role ? ucfirst($member->role->name) : 'User';
                                            $isActive = ($member->status ?? 'active') === 'active';
                                            $memberDataJson = json_encode([
                                                'id' => $member->id,
                                                'member_id' => $memberCode,
                                                'name' => $member->name,
                                                'email' => $member->email,
                                                'contact_number' => $member->contact_number ?? '',
                                                'address' => $member->address ?? '',
                                                'role_id' => $member->role_id,
                                                'role_name' => $roleName,
                                                'status' => $member->status ?? 'active',
                                                'profile_picture_url' => $member->profile_picture ? asset('storage/' . $member->profile_picture) : null,
                                                'membership_date' => $member->created_at ? $member->created_at->format('M d, Y') : 'N/A',
                                                'updated_at_formatted' => $member->updated_at ? $member->updated_at->format('M d, Y h:i A') : 'N/A',
                                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                                        @endphp
                                        <tr style="border-bottom: 1px solid #e9ecef;">
                                            <td class="ps-4">
                                                <strong class="text-primary" style="color: #0b2342 !important;">{{ $memberCode }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($member->profile_picture)
                                                        <img src="{{ asset('storage/' . $member->profile_picture) }}" alt="{{ $member->name }}" class="rounded-circle object-fit-cover" style="width: 34px; height: 34px; min-width: 34px; border: 2px solid #e9ecef;">
                                                    @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 34px; height: 34px; min-width: 34px; background: linear-gradient(135deg, #0b2342 0%, #1e40af 100%); font-size: 13px;">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $member->name }}</div>
                                                        <div class="text-muted small" style="font-size: 12px;">{{ $member->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $isAdmin ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-info-subtle text-info-emphasis border border-info-subtle' }} px-2.5 py-1 text-uppercase" style="font-size: 11px; font-weight: 600; border-radius: 6px;">
                                                    <i class="bi {{ $isAdmin ? 'bi-shield-check' : 'bi-person-badge' }} me-1"></i>{{ $roleName }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-secondary" style="max-width: 220px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    {{ $member->address ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span>{{ $member->contact_number ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                {{ $member->created_at ? $member->created_at->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $isActive ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2.5 py-1 text-uppercase" style="font-size: 11px; font-weight: 600; border-radius: 6px;">
                                                    {{ $isActive ? 'ACTIVE' : 'INACTIVE' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1.5">
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-outline-primary px-2 py-1 view-member-btn" 
                                                        title="View Member"
                                                        data-member="{{ $memberDataJson }}"
                                                    >
                                                        <i class="bi bi-eye"></i> View
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-outline-secondary px-2 py-1 edit-member-btn" 
                                                        title="Edit Member"
                                                        data-member="{{ $memberDataJson }}"
                                                    >
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        class="btn btn-sm btn-outline-danger px-2 py-1 delete-member-btn" 
                                                        title="Delete Member"
                                                        data-id="{{ $member->id }}"
                                                        data-member-id="{{ $memberCode }}"
                                                        data-name="{{ $member->name }}"
                                                        data-is-self="{{ auth()->id() === $member->id ? 'true' : 'false' }}"
                                                    >
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                                No member records found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if($members->hasPages())
                        <div class="card-footer bg-white border-top p-3 d-flex align-items-center justify-content-between">
                            <div class="text-muted small">
                                Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} members
                            </div>
                            <div>
                                {{ $members->links() }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    {{-- =========================================================
         ADD MEMBER MODAL
    ========================================================== --}}
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <h5 class="modal-title fw-bold text-primary d-flex align-items-center gap-2" id="addMemberModalLabel" style="color: #0b2342 !important;">
                            <i class="bi bi-person-plus-fill"></i> Add New Member
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
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
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top px-4 py-3">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-1.5" style="background-color: #0b2342; border-color: #0b2342;">
                            <i class="bi bi-check-lg"></i> Register Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================================================
         VIEW MEMBER MODAL
    ========================================================== --}}
    <div class="modal fade" id="viewMemberModal" tabindex="-1" aria-labelledby="viewMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-primary d-flex align-items-center gap-2" id="viewMemberModalLabel" style="color: #0b2342 !important;">
                        <i class="bi bi-person-lines-fill"></i> Member Profile
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Header Profile Card --}}
                    <div class="text-center pb-3 mb-3 border-bottom">
                        <div class="d-inline-block position-relative mb-2">
                            <div id="viewAvatarContainer">
                                {{-- Injected via JS --}}
                            </div>
                        </div>
                        <h4 class="fw-bold mb-1" id="viewMemberName">Member Name</h4>
                        <div class="text-muted small mb-2" id="viewMemberEmail">member@example.com</div>
                        <div class="d-flex justify-content-center gap-2">
                            <span class="badge bg-primary px-2.5 py-1" id="viewMemberRole">Role</span>
                            <span class="badge bg-success px-2.5 py-1" id="viewMemberStatus">Status</span>
                        </div>
                    </div>

                    {{-- Member Info Grid --}}
                    <div class="row g-3" style="font-size: 13.5px;">
                        <div class="col-6">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">
                                <i class="bi bi-card-heading text-primary me-1"></i> Member ID
                            </div>
                            <div class="fw-bold text-dark" id="viewMemberId">N/A</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">
                                <i class="bi bi-telephone text-primary me-1"></i> Contact Number
                            </div>
                            <div class="fw-bold text-dark" id="viewMemberContact">N/A</div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">
                                <i class="bi bi-geo-alt text-primary me-1"></i> Address
                            </div>
                            <div class="fw-bold text-dark" id="viewMemberAddress">N/A</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">
                                <i class="bi bi-calendar-check text-primary me-1"></i> Membership Date
                            </div>
                            <div class="fw-bold text-dark" id="viewMemberDate">N/A</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small text-uppercase fw-semibold mb-1">
                                <i class="bi bi-clock-history text-primary me-1"></i> Last Updated
                            </div>
                            <div class="fw-bold text-dark" id="viewMemberUpdated">N/A</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary px-3 d-flex align-items-center gap-1.5" id="viewToEditBtn" style="background-color: #0b2342; border-color: #0b2342;">
                        <i class="bi bi-pencil"></i> Edit Member
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================================================
         EDIT MEMBER MODAL
    ========================================================== --}}
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <form id="editMemberForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-light border-bottom px-4 py-3">
                        <h5 class="modal-title fw-bold text-primary d-flex align-items-center gap-2" id="editMemberModalLabel" style="color: #0b2342 !important;">
                            <i class="bi bi-pencil-square"></i> Edit Member Information
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="editName" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="editEmail" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Contact Number</label>
                                <input type="text" name="contact_number" id="editContact" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Address</label>
                                <input type="text" name="address" id="editAddress" class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Member ID <span class="text-danger">*</span></label>
                                <input type="text" name="member_id" id="editMemberId" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase text-muted">System Role <span class="text-danger">*</span></label>
                                <select name="role_id" id="editRoleId" class="form-select" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Status <span class="text-danger">*</span></label>
                                <select name="status" id="editStatus" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="card border bg-light">
                                    <div class="card-body p-3">
                                        <div class="fw-semibold text-dark mb-1 small text-uppercase">
                                            <i class="bi bi-shield-lock me-1"></i> Change Password <span class="text-muted fw-normal">(Optional)</span>
                                        </div>
                                        <small class="text-muted d-block mb-3">Leave blank if you do not want to change the member's current password.</small>
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <input type="password" name="password" id="editPassword" class="form-control" placeholder="New Password (min. 8 chars)" minlength="8">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="password" name="password_confirmation" id="editPasswordConfirm" class="form-control" placeholder="Confirm New Password" minlength="8">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-uppercase text-muted">Update Profile Photo <small class="text-muted">(Optional)</small></label>
                                <input type="file" name="profile_picture" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top px-4 py-3">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-1.5" style="background-color: #0b2342; border-color: #0b2342;">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================================================
         DELETE CONFIRMATION MODAL
    ========================================================== --}}
    <div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="deleteMemberForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger-subtle border-bottom px-4 py-3">
                        <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteMemberModalLabel">
                            <i class="bi bi-exclamation-triangle-fill"></i> Confirm Member Deletion
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-2">Are you sure you want to delete member <strong id="deleteMemberName"></strong> (<span id="deleteMemberCode" class="text-muted"></span>)?</p>
                        <p class="text-muted small mb-0">This will remove the user account and revoke access to the system. This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer bg-light border-top px-4 py-3">
                        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 d-flex align-items-center gap-1.5">
                            <i class="bi bi-trash"></i> Delete Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- =========================================================
         MODAL INTERACTIVITY SCRIPT
    ========================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewModal = new bootstrap.Modal(document.getElementById('viewMemberModal'));
            var editModal = new bootstrap.Modal(document.getElementById('editMemberModal'));
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteMemberModal'));

            var currentMemberData = null;

            // View Member Handler
            document.querySelectorAll('.view-member-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var data = JSON.parse(this.getAttribute('data-member'));
                    currentMemberData = data;

                    document.getElementById('viewMemberName').textContent = data.name;
                    document.getElementById('viewMemberEmail').textContent = data.email;
                    document.getElementById('viewMemberId').textContent = data.member_id;
                    document.getElementById('viewMemberRole').textContent = data.role_name;
                    document.getElementById('viewMemberRole').className = 'badge px-2.5 py-1 ' + (data.role_name === 'Admin' ? 'bg-primary' : 'bg-info text-dark');
                    
                    document.getElementById('viewMemberStatus').textContent = (data.status || 'active').toUpperCase();
                    document.getElementById('viewMemberStatus').className = 'badge px-2.5 py-1 ' + (data.status === 'active' ? 'bg-success' : 'bg-danger');

                    document.getElementById('viewMemberContact').textContent = data.contact_number || 'N/A';
                    document.getElementById('viewMemberAddress').textContent = data.address || 'N/A';
                    document.getElementById('viewMemberDate').textContent = data.membership_date || 'N/A';
                    document.getElementById('viewMemberUpdated').textContent = data.updated_at_formatted || 'N/A';

                    var avatarContainer = document.getElementById('viewAvatarContainer');
                    if (data.profile_picture_url) {
                        avatarContainer.innerHTML = '<img src="' + data.profile_picture_url + '" class="rounded-circle object-fit-cover shadow-sm" style="width: 72px; height: 72px; border: 3px solid #fff;">';
                    } else {
                        var initial = data.name ? data.name.charAt(0).toUpperCase() : 'M';
                        avatarContainer.innerHTML = '<div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm mx-auto" style="width: 72px; height: 72px; background: linear-gradient(135deg, #0b2342 0%, #1e40af 100%); font-size: 26px;">' + initial + '</div>';
                    }

                    viewModal.show();
                });
            });

            // Switch from View to Edit
            var viewToEditBtn = document.getElementById('viewToEditBtn');
            if (viewToEditBtn) {
                viewToEditBtn.addEventListener('click', function() {
                    if (currentMemberData) {
                        viewModal.hide();
                        populateEditModal(currentMemberData);
                        editModal.show();
                    }
                });
            }

            // Edit Member Handler
            function populateEditModal(data) {
                var form = document.getElementById('editMemberForm');
                form.action = "{{ url('/members') }}/" + data.id;

                document.getElementById('editName').value = data.name || '';
                document.getElementById('editEmail').value = data.email || '';
                document.getElementById('editContact').value = data.contact_number || '';
                document.getElementById('editAddress').value = data.address || '';
                document.getElementById('editMemberId').value = data.member_id || '';
                document.getElementById('editRoleId').value = data.role_id || '1';
                document.getElementById('editStatus').value = data.status || 'active';
                document.getElementById('editPassword').value = '';
                document.getElementById('editPasswordConfirm').value = '';
            }

            document.querySelectorAll('.edit-member-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var data = JSON.parse(this.getAttribute('data-member'));
                    populateEditModal(data);
                    editModal.show();
                });
            });

            // Delete Member Handler
            document.querySelectorAll('.delete-member-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');
                    var code = this.getAttribute('data-member-id');
                    var isSelf = this.getAttribute('data-is-self') === 'true';

                    if (isSelf) {
                        alert('You cannot delete your own account while logged in.');
                        return;
                    }

                    var form = document.getElementById('deleteMemberForm');
                    form.action = "{{ url('/members') }}/" + id;

                    document.getElementById('deleteMemberName').textContent = name;
                    document.getElementById('deleteMemberCode').textContent = code;

                    deleteModal.show();
                });
            });
        });
    </script>
</x-app-layout>
