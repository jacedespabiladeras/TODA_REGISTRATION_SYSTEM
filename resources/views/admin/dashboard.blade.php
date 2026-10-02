<x-app-layout>

    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">

        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">
                    Admin Dashboard
                </h1>
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

        {{-- CONTENT --}}
        <main class="content">
            <div class="container-fluid">

                {{-- FLASH MESSAGES --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #10b981; border-radius: 12px;">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #ef4444; border-radius: 12px;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 1. DASHBOARD HEADER --}}
                <div class="dash-welcome-banner mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h2 class="dash-welcome-title">
                                Welcome back, {{ auth()->user()->name }}! 👋
                            </h2>
                            <p class="dash-welcome-sub">
                                Sorsogon City TODA Registration & Franchise Management Portal Overview
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="dash-btn-primary" data-bs-toggle="modal" data-bs-target="#addTodoModal">
                                <i class="bi bi-plus-lg"></i>
                                <span>Add Task</span>
                            </button>
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px; font-weight: 600; padding: 7px 14px;">
                                <i class="bi bi-bar-chart me-1"></i> Reports
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 2. SUMMARY KPI METRIC CARDS --}}
                <div class="dash-kpi-grid">
                    {{-- Total Drivers --}}
                    <a href="{{ route('drivers.index') }}" class="dash-kpi-card">
                        <div class="dash-kpi-icon-box dash-kpi-icon-navy">
                            <i class="bi bi-person-vcard"></i>
                        </div>
                        <div class="dash-kpi-info">
                            <div class="dash-kpi-label">Total Drivers</div>
                            <div class="dash-kpi-val">{{ number_format($stats['drivers']['total']) }}</div>
                            <div class="dash-kpi-meta">
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    {{ $stats['drivers']['active'] }} Active
                                </span>
                                @if($stats['drivers']['expiring'] > 0)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        {{ $stats['drivers']['expiring'] }} Expiring
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>

                    {{-- Total Operators --}}
                    <a href="{{ route('operators.index') }}" class="dash-kpi-card">
                        <div class="dash-kpi-icon-box dash-kpi-icon-blue">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <div class="dash-kpi-info">
                            <div class="dash-kpi-label">Total Operators</div>
                            <div class="dash-kpi-val">{{ number_format($stats['operators']['total']) }}</div>
                            <div class="dash-kpi-meta">
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    {{ $stats['operators']['active'] }} Active
                                </span>
                                @if($stats['operators']['inactive'] > 0)
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        {{ $stats['operators']['inactive'] }} Inactive
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>

                    {{-- Total Vehicles --}}
                    <a href="{{ route('vehicles.index') }}" class="dash-kpi-card">
                        <div class="dash-kpi-icon-box dash-kpi-icon-indigo">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <div class="dash-kpi-info">
                            <div class="dash-kpi-label">Total Vehicles</div>
                            <div class="dash-kpi-val">{{ number_format($stats['vehicles']['total']) }}</div>
                            <div class="dash-kpi-meta">
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    {{ $stats['vehicles']['active'] }} Registered
                                </span>
                                @if($stats['vehicles']['expiring'] > 0)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        {{ $stats['vehicles']['expiring'] }} Expiring
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>

                    {{-- Total Franchises --}}
                    <a href="{{ route('franchises.index') }}" class="dash-kpi-card">
                        <div class="dash-kpi-icon-box dash-kpi-icon-teal">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="dash-kpi-info">
                            <div class="dash-kpi-label">Total Franchises</div>
                            <div class="dash-kpi-val">{{ number_format($stats['franchises']['total']) }}</div>
                            <div class="dash-kpi-meta">
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    {{ $stats['franchises']['active'] }} Active
                                </span>
                                @if($stats['franchises']['expiring'] > 0)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        {{ $stats['franchises']['expiring'] }} Expiring
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>

                {{-- 3. ANALYTICS ROW: REGISTRATION ACTIVITY + FRANCHISE STATUS --}}
                <div class="row g-4 mb-4">
                    {{-- Registration Activity Trend (Wider) --}}
                    <div class="col-lg-8">
                        <div class="dash-card h-100 mb-0">
                            <div class="dash-card-header">
                                <div>
                                    <h3 class="dash-card-title">
                                        <i class="bi bi-graph-up-arrow"></i> Registration Activity
                                    </h3>
                                    <div class="dash-card-sub">Monthly driver & franchise registration trends</div>
                                </div>
                                <span class="badge bg-light text-dark border px-3 py-2" style="font-weight: 600; border-radius: 20px;">
                                    Past 6 Months
                                </span>
                            </div>
                            <div class="dash-card-body">
                                <div class="row align-items-center g-4">
                                    <div class="col-md-8">
                                        <div style="height: 240px; position: relative; width: 100%;">
                                            <canvas id="registrationTrendChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 text-center" style="background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0;">
                                            <div style="width: 110px; height: 110px; margin: 0 auto 12px; position: relative;">
                                                <canvas id="activeRateGaugeChart"></canvas>
                                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; font-size: 18px; color: #0b2342;">
                                                    {{ $stats['activeRate'] }}%
                                                </div>
                                            </div>
                                            <div style="font-size: 13px; font-weight: 700; color: #0b2342;">Active Rate</div>
                                            <div style="font-size: 11px; color: #64748b;">Compliant TODA Records</div>
                                            <div class="d-flex justify-content-center gap-3 mt-3 pt-2 border-top" style="font-size: 11px;">
                                                <div><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#0b2342;" class="me-1"></span> Drivers</div>
                                                <div><span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#0284c7;" class="me-1"></span> Franchises</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Franchise Status (Narrower) --}}
                    <div class="col-lg-4">
                        <div class="dash-card h-100 mb-0">
                            <div class="dash-card-header">
                                <div>
                                    <h3 class="dash-card-title">
                                        <i class="bi bi-pie-chart"></i> Franchise Status
                                    </h3>
                                    <div class="dash-card-sub">Current distribution & compliance</div>
                                </div>
                            </div>
                            <div class="dash-card-body">
                                <div style="height: 170px; position: relative; width: 100%; margin-bottom: 18px;">
                                    <canvas id="franchiseStatusChart"></canvas>
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: #f8fafc; font-size: 12px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></span>
                                            <strong>Active Franchises</strong>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">{{ $stats['franchises']['active'] }}</span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: #f8fafc; font-size: 12px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></span>
                                            <strong>Expiring (&lt;30 Days)</strong>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning">{{ $stats['franchises']['expiring'] }}</span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: #f8fafc; font-size: 12px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444;"></span>
                                            <strong>Inactive / Expired</strong>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger">{{ $stats['franchises']['inactive'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. TO DO'S (WEEKLY TASKS) & TRACKING/ALERTS --}}
                <div class="row g-4 mb-4">
                    {{-- TO DO'S Widget --}}
                    <div class="col-lg-7">
                        <div class="dash-card h-100 mb-0">
                            <div class="dash-card-header">
                                <div>
                                    <h3 class="dash-card-title">
                                        <i class="bi bi-check2-square text-primary"></i> TO DO'S
                                    </h3>
                                    <div class="dash-card-sub">Staff Communication & Weekly Assignments</div>
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="dash-todo-header-badge">
                                        <i class="bi bi-calendar-week"></i>
                                        Week: {{ $stats['currentWeekFormatted'] }}
                                    </span>
                                    <button type="button" class="dash-btn-primary dash-btn-sm" data-bs-toggle="modal" data-bs-target="#addTodoModal">
                                        <i class="bi bi-plus-lg"></i> Add Task
                                    </button>
                                </div>
                            </div>
                            <div class="dash-card-body">
                                {{-- Filter Tabs & Status Counter --}}
                                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom flex-wrap gap-2">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-primary todo-filter-btn active" data-filter="all" style="border-radius: 20px; font-size: 11px; font-weight: 600; padding: 4px 14px;">
                                            All ({{ $stats['todoStats']['total'] }})
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary todo-filter-btn" data-filter="pending" style="border-radius: 20px; font-size: 11px; font-weight: 600; padding: 4px 14px;">
                                            Pending ({{ $stats['todoStats']['pending'] }})
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary todo-filter-btn" data-filter="completed" style="border-radius: 20px; font-size: 11px; font-weight: 600; padding: 4px 14px;">
                                            Completed ({{ $stats['todoStats']['completed'] }})
                                        </button>
                                    </div>
                                    <div class="text-muted small" style="font-size: 11px;">
                                        <strong>{{ $stats['todoStats']['completed'] }}</strong> of <strong>{{ $stats['todoStats']['total'] }}</strong> Completed
                                    </div>
                                </div>

                                {{-- Task Items List --}}
                                @if($stats['todos']->isEmpty())
                                    <div class="text-center py-5">
                                        <div class="mb-2" style="font-size: 36px; color: #cbd5e1;">
                                            <i class="bi bi-clipboard-check"></i>
                                        </div>
                                        <h5 style="color: #64748b; font-size: 15px; font-weight: 600;">No tasks scheduled for this week</h5>
                                        <p class="text-muted small mb-3">Create assignments for staff members to track registration updates.</p>
                                        <button type="button" class="dash-btn-primary dash-btn-sm" data-bs-toggle="modal" data-bs-target="#addTodoModal">
                                            <i class="bi bi-plus-lg"></i> Create First Task
                                        </button>
                                    </div>
                                @else
                                    <div class="dash-todo-list" style="max-height: 420px; overflow-y: auto; padding-right: 4px;">
                                        @foreach($stats['todos'] as $todo)
                                            <div class="dash-todo-item {{ $todo->status === 'completed' ? 'is-completed' : '' }}" data-status="{{ $todo->status }}">
                                                {{-- Toggle Form/Button --}}
                                                <form method="POST" action="{{ route('todos.toggle', $todo->id) }}" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dash-todo-checkbox-btn {{ $todo->status === 'completed' ? 'checked' : '' }}" title="Toggle Completion Status">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                </form>

                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="dash-todo-title {{ $todo->status === 'completed' ? 'completed' : '' }}">
                                                        {{ $todo->title }}
                                                    </div>
                                                    @if($todo->description)
                                                        <div class="dash-todo-desc">
                                                            {{ $todo->description }}
                                                        </div>
                                                    @endif
                                                    <div class="dash-todo-meta-row">
                                                        @if($todo->assignedUser)
                                                            <span class="dash-todo-chip dash-todo-chip-staff">
                                                                <i class="bi bi-person-fill"></i>
                                                                Assigned: {{ $todo->assignedUser->name }}
                                                            </span>
                                                        @else
                                                            <span class="dash-todo-chip dash-todo-chip-date text-muted">
                                                                <i class="bi bi-person-dash"></i> Unassigned
                                                            </span>
                                                        @endif

                                                        @if($todo->deadline)
                                                            <span class="dash-todo-chip dash-todo-chip-date">
                                                                <i class="bi bi-clock"></i>
                                                                Due: {{ $todo->deadline->format('M. d, Y') }}
                                                            </span>
                                                        @elseif($todo->week_start)
                                                            <span class="dash-todo-chip dash-todo-chip-date">
                                                                <i class="bi bi-calendar3"></i>
                                                                {{ $todo->week_range_display }}
                                                            </span>
                                                        @endif

                                                        @if($todo->status === 'completed')
                                                            <span class="dash-todo-chip dash-todo-chip-done">
                                                                <i class="bi bi-check-circle-fill"></i> Completed
                                                            </span>
                                                        @else
                                                            <span class="dash-todo-chip dash-todo-chip-pending">
                                                                <i class="bi bi-hourglass-split"></i> Pending
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Admin Actions --}}
                                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                                    <button type="button" class="btn btn-sm btn-light border p-1" style="width: 30px; height: 30px; border-radius: 6px;" title="Edit Task" data-bs-toggle="modal" data-bs-target="#editTodoModal{{ $todo->id }}">
                                                        <i class="bi bi-pencil" style="font-size: 13px;"></i>
                                                    </button>
                                                    <form method="POST" action="{{ route('todos.destroy', $todo->id) }}" onsubmit="return confirm('Are you sure you want to delete this task?');" class="m-0">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light border text-danger p-1" style="width: 30px; height: 30px; border-radius: 6px;" title="Delete Task">
                                                            <i class="bi bi-trash" style="font-size: 13px;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tracking / Expiration Widget --}}
                    <div class="col-lg-5">
                        <div class="dash-card h-100 mb-0">
                            <div class="dash-card-header">
                                <div>
                                    <h3 class="dash-card-title">
                                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> Upcoming Expirations
                                    </h3>
                                    <div class="dash-card-sub">Warning window: next 30 days</div>
                                </div>
                                <a href="{{ route('tracking.index') }}" class="btn btn-sm btn-link text-decoration-none p-0" style="font-size: 12px; font-weight: 600;">
                                    View Tracking →
                                </a>
                            </div>
                            <div class="dash-card-body p-0">
                                @if($stats['upcomingExpirations']->isEmpty())
                                    <div class="text-center py-5">
                                        <i class="bi bi-check-circle-fill text-success" style="font-size: 36px;"></i>
                                        <h5 style="color: #0b2342; font-size: 14px; font-weight: 600; margin-top: 10px;">✓ No records expiring within 30 days.</h5>
                                        <p class="text-muted small mb-0">All driver licenses, vehicle registrations, and franchises are active.</p>
                                    </div>
                                @else
                                    <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                                        <table class="dash-table">
                                            <thead>
                                                <tr>
                                                    <th>Type & Record</th>
                                                    <th>Expires</th>
                                                    <th class="text-end">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($stats['upcomingExpirations'] as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="badge {{ $item['type'] === 'Driver' ? 'bg-success-subtle text-success' : ($item['type'] === 'Vehicle' ? 'bg-info-subtle text-info' : 'bg-primary-subtle text-primary') }}" style="font-size: 10px;">
                                                                    {{ $item['type'] }}
                                                                </span>
                                                                <strong style="font-size: 13px;">{{ $item['name_id'] }}</strong>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div style="font-size: 12px; font-weight: 600;">{{ $item['expiration_date'] }}</div>
                                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 10px;">
                                                                {{ $item['days_remaining'] }}d left
                                                            </span>
                                                        </td>
                                                        <td class="text-end">
                                                            <a href="{{ $item['link'] }}" class="btn btn-sm btn-light border py-1 px-2" style="border-radius: 6px; font-size: 11px; font-weight: 600;">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. RECENT REGISTRATIONS (RESPONSIVE 3-COLUMN CARD) --}}
                <div class="dash-card mb-4">
                    <div class="dash-card-header">
                        <div>
                            <h3 class="dash-card-title">
                                <i class="bi bi-clock-history"></i> Recent Registrations
                            </h3>
                            <div class="dash-card-sub">Latest records submitted into the TODA management system</div>
                        </div>
                    </div>
                    <div class="dash-card-body p-0">
                        <div class="dash-recent-grid">
                            {{-- Recent Drivers --}}
                            <div class="dash-recent-col">
                                <div class="dash-recent-header">
                                    <strong style="font-size: 13px; color: #0b2342;">
                                        <i class="bi bi-person-vcard text-primary me-1"></i> Recent Drivers
                                    </strong>
                                    <a href="{{ route('drivers.index') }}" class="small text-muted text-decoration-none" style="font-size: 11px;">View all</a>
                                </div>
                                <div class="dash-recent-body">
                                    @forelse($stats['recent']['drivers'] as $drv)
                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                            <div>
                                                <div style="font-weight: 600; font-size: 13px;">{{ $drv->first_name }} {{ $drv->last_name }}</div>
                                                <small class="text-muted">{{ $drv->driver_id }}</small>
                                            </div>
                                            <a href="{{ route('drivers.show', $drv->id) }}" class="btn btn-sm btn-light border" style="font-size: 11px; font-weight: 600;">View</a>
                                        </div>
                                    @empty
                                        <p class="text-muted small my-2">No driver records found.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Recent Franchises --}}
                            <div class="dash-recent-col">
                                <div class="dash-recent-header">
                                    <strong style="font-size: 13px; color: #0b2342;">
                                        <i class="bi bi-file-earmark-text text-primary me-1"></i> Recent Franchises
                                    </strong>
                                    <a href="{{ route('franchises.index') }}" class="small text-muted text-decoration-none" style="font-size: 11px;">View all</a>
                                </div>
                                <div class="dash-recent-body">
                                    @forelse($stats['recent']['franchises'] as $fr)
                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                            <div>
                                                <div style="font-weight: 600; font-size: 13px;">{{ $fr->franchise_number }}</div>
                                                <small class="text-muted">{{ $fr->operator ? $fr->operator->first_name . ' ' . $fr->operator->last_name : 'No Operator' }}</small>
                                            </div>
                                            <a href="{{ route('franchises.show', $fr->id) }}" class="btn btn-sm btn-light border" style="font-size: 11px; font-weight: 600;">View</a>
                                        </div>
                                    @empty
                                        <p class="text-muted small my-2">No franchise records found.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Recent Vehicles --}}
                            <div class="dash-recent-col">
                                <div class="dash-recent-header">
                                    <strong style="font-size: 13px; color: #0b2342;">
                                        <i class="bi bi-car-front text-primary me-1"></i> Recent Vehicles
                                    </strong>
                                    <a href="{{ route('vehicles.index') }}" class="small text-muted text-decoration-none" style="font-size: 11px;">View all</a>
                                </div>
                                <div class="dash-recent-body">
                                    @forelse($stats['recent']['vehicles'] as $veh)
                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                            <div>
                                                <div style="font-weight: 600; font-size: 13px;">{{ $veh->plate_number }}</div>
                                                <small class="text-muted">{{ $veh->make }} {{ $veh->model }}</small>
                                            </div>
                                            <a href="{{ route('vehicles.show', $veh->id) }}" class="btn btn-sm btn-light border" style="font-size: 11px; font-weight: 600;">View</a>
                                        </div>
                                    @empty
                                        <p class="text-muted small my-2">No vehicle records found.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- =========================================================
         MODALS
         ========================================================= --}}

    {{-- ADD TODO MODAL (ADMIN ONLY) --}}
    <div class="modal fade" id="addTodoModal" tabindex="-1" aria-labelledby="addTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <form method="POST" action="{{ route('todos.store') }}">
                    @csrf
                    <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                        <h5 class="modal-title" id="addTodoModalLabel" style="font-size: 16px; font-weight: 700; color: #0b2342;">
                            <i class="bi bi-plus-circle text-primary me-2"></i> Create Task
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 24px;">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold small text-muted text-uppercase">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Verify driver registration records" required style="border-radius: 8px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold small text-muted text-uppercase">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Review newly submitted driver registration records..." style="border-radius: 8px;"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold small text-muted text-uppercase">Assign To</label>
                            <select name="assigned_to" class="form-select" style="border-radius: 8px;">
                                <option value="">-- Select Staff --</option>
                                @foreach($stats['staffMembers'] as $staff)
                                    <option value="{{ $staff->id }}">
                                        {{ $staff->name }} ({{ $staff->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label font-weight-bold small text-muted text-uppercase">Week Start</label>
                                <input type="date" name="week_start" class="form-control" value="{{ now()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString() }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label font-weight-bold small text-muted text-uppercase">Week End</label>
                                <input type="date" name="week_end" class="form-control" value="{{ now()->endOfWeek(\Carbon\Carbon::SUNDAY)->toDateString() }}" style="border-radius: 8px;">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label font-weight-bold small text-muted text-uppercase">Deadline (Optional)</label>
                            <input type="date" name="deadline" class="form-control" style="border-radius: 8px;">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 16px 24px;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" class="dash-btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT TODO MODALS --}}
    @foreach($stats['todos'] as $todo)
        <div class="modal fade" id="editTodoModal{{ $todo->id }}" tabindex="-1" aria-labelledby="editTodoModalLabel{{ $todo->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <form method="POST" action="{{ route('todos.update', $todo->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                            <h5 class="modal-title font-weight-bold" id="editTodoModalLabel{{ $todo->id }}" style="font-size: 16px; font-weight: 700; color: #0b2342;">
                                <i class="bi bi-pencil-square text-primary me-2"></i> Edit Task
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="padding: 24px;">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold small text-muted text-uppercase">Task Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ $todo->title }}" required style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold small text-muted text-uppercase">Task Description</label>
                                <textarea name="description" class="form-control" rows="3" style="border-radius: 8px;">{{ $todo->description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold small text-muted text-uppercase">Assign To Staff</label>
                                <select name="assigned_to" class="form-select" style="border-radius: 8px;">
                                    <option value="">-- Unassigned --</option>
                                    @foreach($stats['staffMembers'] as $staff)
                                        <option value="{{ $staff->id }}" {{ $todo->assigned_to == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }} ({{ $staff->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label font-weight-bold small text-muted text-uppercase">Week Start</label>
                                    <input type="date" name="week_start" class="form-control" value="{{ $todo->week_start ? $todo->week_start->toDateString() : '' }}" style="border-radius: 8px;">
                                </div>
                                <div class="col-6">
                                    <label class="form-label font-weight-bold small text-muted text-uppercase">Week End</label>
                                    <input type="date" name="week_end" class="form-control" value="{{ $todo->week_end ? $todo->week_end->toDateString() : '' }}" style="border-radius: 8px;">
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label font-weight-bold small text-muted text-uppercase">Deadline</label>
                                    <input type="date" name="deadline" class="form-control" value="{{ $todo->deadline ? $todo->deadline->toDateString() : '' }}" style="border-radius: 8px;">
                                </div>
                                <div class="col-6">
                                    <label class="form-label font-weight-bold small text-muted text-uppercase">Status</label>
                                    <select name="status" class="form-select" style="border-radius: 8px;">
                                        <option value="pending" {{ $todo->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="completed" {{ $todo->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 16px 24px;">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                            <button type="submit" class="dash-btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- CHART.JS SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Task Filter tabs logic
            const filterButtons = document.querySelectorAll('.todo-filter-btn');
            const todoItems = document.querySelectorAll('.dash-todo-item');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterButtons.forEach(b => {
                        b.classList.remove('btn-primary', 'active');
                        b.classList.add('btn-outline-secondary');
                    });
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-primary', 'active');

                    const filter = this.getAttribute('data-filter');
                    todoItems.forEach(item => {
                        const status = item.getAttribute('data-status');
                        if (filter === 'all' || status === filter) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });

            // Dark Mode helper for charts
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark' || document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const gridColor = isDark ? '#334155' : '#f1f5f9';

            // 1. REGISTRATION TREND CHART
            const trendCtx = document.getElementById('registrationTrendChart');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($stats['trends']['labels']) !!},
                        datasets: [
                            {
                                label: 'Drivers',
                                data: {!! json_encode($stats['trends']['drivers']) !!},
                                backgroundColor: '#0b2342',
                                borderRadius: 6,
                                barPercentage: 0.6,
                                categoryPercentage: 0.7,
                            },
                            {
                                label: 'Franchises',
                                data: {!! json_encode($stats['trends']['franchises']) !!},
                                backgroundColor: '#0284c7',
                                borderRadius: 6,
                                barPercentage: 0.6,
                                categoryPercentage: 0.7,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#0b2342',
                                padding: 10,
                                cornerRadius: 8,
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: textColor, font: { size: 11 } }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: { color: textColor, font: { size: 11 }, precision: 0 }
                            }
                        }
                    }
                });
            }

            // 2. ACTIVE RATE GAUGE (Donut Ring)
            const gaugeCtx = document.getElementById('activeRateGaugeChart');
            if (gaugeCtx) {
                const activeVal = {{ $stats['activeRate'] }};
                new Chart(gaugeCtx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [activeVal, 100 - activeVal],
                            backgroundColor: ['#2563eb', '#e2e8f0'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '78%',
                        plugins: {
                            tooltip: { enabled: false }
                        }
                    }
                });
            }

            // 3. FRANCHISE STATUS DONUT
            const statusCtx = document.getElementById('franchiseStatusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Expiring', 'Inactive'],
                        datasets: [{
                            data: [
                                {{ $stats['franchises']['active'] }},
                                {{ $stats['franchises']['expiring'] }},
                                {{ $stats['franchises']['inactive'] }}
                            ],
                            backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0b2342',
                                padding: 10,
                                cornerRadius: 8,
                            }
                        }
                    }
                });
            }
        });
    </script>

    {{-- SIDEBAR TOGGLE SCRIPT --}}
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const toggle = document.getElementById('sidebarToggle');

        if (toggle) {
            toggle.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    sidebar.classList.toggle('mobile-show');
                } else {
                    sidebar.classList.toggle('collapsed');
                    mainWrapper.classList.toggle('expanded');
                }
            });
        }
    </script>

</x-app-layout>