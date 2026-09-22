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
                        <form method="GET" action="{{ route('members.index') }}" class="row g-3">
                            <div class="col-md-8">
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
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-1.5">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">
                                        Reset
                                    </a>
                                @endif
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
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Member ID</th>
                                        <th>Full Name</th>
                                        <th>Address</th>
                                        <th>Contact Number</th>
                                        <th>Membership Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($members as $member)
                                        <tr>
                                            <td class="ps-3 fw-bold">{{ $member->member_id }}</td>
                                            <td>{{ $member->first_name }} {{ $member->middle_name ? $member->middle_name . ' ' : '' }}{{ $member->last_name }}</td>
                                            <td>{{ $member->address }}</td>
                                            <td>{{ $member->contact_number ?? 'N/A' }}</td>
                                            <td>{{ $member->membership_date ? \Carbon\Carbon::parse($member->membership_date)->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $member->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ strtoupper($member->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
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
                        <div class="card-footer bg-white border-top p-3">
                            {{ $members->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
</x-app-layout>
