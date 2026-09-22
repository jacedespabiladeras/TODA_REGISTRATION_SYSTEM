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
                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-arrow-left"></i> Back to Members List
                    </a>
                    <a href="{{ route('members.edit', $member->id) }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1" style="background-color: #0b2342; border-color: #0b2342;">
                        <i class="bi bi-pencil"></i> Edit Member
                    </a>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom p-3">
                        <h5 class="card-title text-primary mb-0 d-flex align-items-center gap-2" style="font-size: 15px; font-weight: 700; color: #0b2342 !important;">
                            <i class="bi bi-person-lines-fill"></i> Member Details
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $memberCode = $member->member_id ?? ('MEM-' . str_pad((string)$member->id, 4, '0', STR_PAD_LEFT));
                            $isAdmin = $member->role?->name === 'admin';
                            $roleName = $member->role ? ucfirst($member->role->name) : 'User';
                            $isActive = ($member->status ?? 'active') === 'active';
                        @endphp

                        <div class="row g-4 align-items-center mb-4 pb-4 border-bottom">
                            <div class="col-auto">
                                @if($member->profile_picture)
                                    <img src="{{ asset('storage/' . $member->profile_picture) }}" alt="{{ $member->name }}" class="rounded-circle object-fit-cover shadow-sm" style="width: 80px; height: 80px; border: 3px solid #dee2e6;">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 80px; height: 80px; background: linear-gradient(135deg, #0b2342 0%, #1e40af 100%); font-size: 30px;">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <h3 class="fw-bold mb-1" style="color: #0b2342;">{{ $member->name }}</h3>
                                <div class="text-muted mb-2">{{ $member->email }}</div>
                                <div class="d-flex gap-2">
                                    <span class="badge {{ $isAdmin ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-info-subtle text-info-emphasis border border-info-subtle' }} px-2.5 py-1 text-uppercase" style="font-size: 12px; font-weight: 600; border-radius: 6px;">
                                        <i class="bi {{ $isAdmin ? 'bi-shield-check' : 'bi-person-badge' }} me-1"></i>{{ $roleName }}
                                    </span>
                                    <span class="badge {{ $isActive ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2.5 py-1 text-uppercase" style="font-size: 12px; font-weight: 600; border-radius: 6px;">
                                        {{ $isActive ? 'ACTIVE' : 'INACTIVE' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3" style="font-size: 14px;">
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light border">
                                    <div class="text-muted small text-uppercase fw-semibold mb-1">
                                        <i class="bi bi-card-heading text-primary me-1"></i> Member ID
                                    </div>
                                    <div class="fw-bold text-dark fs-6">{{ $memberCode }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light border">
                                    <div class="text-muted small text-uppercase fw-semibold mb-1">
                                        <i class="bi bi-telephone text-primary me-1"></i> Contact Number
                                    </div>
                                    <div class="fw-bold text-dark fs-6">{{ $member->contact_number ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="p-3 rounded bg-light border">
                                    <div class="text-muted small text-uppercase fw-semibold mb-1">
                                        <i class="bi bi-geo-alt text-primary me-1"></i> Address
                                    </div>
                                    <div class="fw-bold text-dark fs-6">{{ $member->address ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light border">
                                    <div class="text-muted small text-uppercase fw-semibold mb-1">
                                        <i class="bi bi-calendar-check text-primary me-1"></i> Membership Date
                                    </div>
                                    <div class="fw-bold text-dark fs-6">{{ $member->created_at ? $member->created_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded bg-light border">
                                    <div class="text-muted small text-uppercase fw-semibold mb-1">
                                        <i class="bi bi-clock-history text-primary me-1"></i> Last Profile Update
                                    </div>
                                    <div class="fw-bold text-dark fs-6">{{ $member->updated_at ? $member->updated_at->format('M d, Y h:i A') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
