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
                    Staff Dashboard
                </h1>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-block">
                    {{ now()->format('d M Y') }}
                </span>
                <span class="badge bg-primary">
                    Staff
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
                                Sorsogon City TODA Registration & Franchise Management Portal
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="dash-todo-header-badge" style="background: #e0f2fe; color: #0284c7; border-color: #bae6fd;">
                                <i class="bi bi-person-check-fill"></i> Staff Member
                            </span>
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
                            </div>
                        </div>
                    </a>
                </div>

                {{-- 3. REGISTRATION ACTIVITY OVERVIEW ROW --}}
                <div class="row g-4 mb-4">
                    {{-- Registration Activity Trend --}}
                    <div class="col-lg-8">
                        <div class="dash-card h-100 mb-0">
                            <div class="dash-card-header">
                                <div>
                                    <h3 class="dash-card-title">
                                        <i class="bi bi-graph-up-arrow"></i> Registration Activity
                                    </h3>
                                    <div class="dash-card-sub">Monthly driver and franchise registration flow</div>
                                </div>
                                <span class="badge bg-light text-dark border px-3 py-2" style="font-weight: 600; border-radius: 20px;">
                                    Past 6 Months
                                </span>
                            </div>
                            <div class="dash-card-body">
                                <div class="row align-items-center g-4">
                                    <div class="col-md-8">
                                        <div style="height: 220px; position: relative; width: 100%;">
                                            <canvas id="staffTrendChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 text-center" style="background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0;">
                                            <div style="width: 100px; height: 100px; margin: 0 auto 10px; position: relative;">
                                                <canvas id="staffActiveGaugeChart"></canvas>
                                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: 800; font-size: 16px; color: #0b2342;">
                                                    {{ $stats['activeRate'] }}%
                                                </div>
                                            </div>
                                            <div style="font-size: 12px; font-weight: 700; color: #0b2342;">Active Rate</div>
                                            <div style="font-size: 11px; color: #64748b;">Compliant Records</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Franchise Status Breakdown --}}
                    <div class="col-lg-4">
                        <div class="dash-card h-100 mb-0">
                            <div class="dash-card-header">
                                <div>
                                    <h3 class="dash-card-title">
                                        <i class="bi bi-pie-chart"></i> Franchise Status
                                    </h3>
                                    <div class="dash-card-sub">Current status breakdown</div>
                                </div>
                            </div>
                            <div class="dash-card-body">
                                <div style="height: 150px; position: relative; width: 100%; margin-bottom: 14px;">
                                    <canvas id="staffFranchiseChart"></canvas>
                                </div>

                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: #f8fafc; font-size: 12px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></span>
                                            <span>Active</span>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">{{ $stats['franchises']['active'] }}</span>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background: #f8fafc; font-size: 12px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b;"></span>
                                            <span>Expiring (&lt;30d)</span>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning">{{ $stats['franchises']['expiring'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. MY TO DO'S & RELEVANT TRACKING ROW --}}
                <div class="row g-4 mb-4">
                    {{-- MY TO DO'S --}}
                    <div class="col-lg-7">
                        <div class="dash-card h-100 mb-0">
                            <div class="dash-card-header">
                                <div>
                                    <h3 class="dash-card-title">
                                        <i class="bi bi-check2-circle text-primary"></i> MY TO DO'S
                                    </h3>
                                    <div class="dash-card-sub">Tasks assigned to you by Administrator</div>
                                </div>
                                <span class="dash-todo-header-badge">
                                    <i class="bi bi-calendar-week"></i>
                                    Week: {{ $stats['currentWeekFormatted'] }}
                                </span>
                            </div>
                            <div class="dash-card-body">
                                {{-- Filter Tabs & Progress Counter --}}
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
                                        <div class="mb-2" style="font-size: 36px; color: #10b981;">
                                            <i class="bi bi-check-all"></i>
                                        </div>
                                        <h5 style="color: #0b2342; font-size: 15px; font-weight: 600;">All caught up!</h5>
                                        <p class="text-muted small mb-0">You have no pending tasks assigned for this week.</p>
                                    </div>
                                @else
                                    <div class="dash-todo-list" style="max-height: 420px; overflow-y: auto; padding-right: 4px;">
                                        @foreach($stats['todos'] as $todo)
                                            <div class="dash-todo-item {{ $todo->status === 'completed' ? 'is-completed' : '' }}" data-status="{{ $todo->status }}">
                                                {{-- Toggle Checkbox Button --}}
                                                <form method="POST" action="{{ route('todos.toggle', $todo->id) }}" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dash-todo-checkbox-btn {{ $todo->status === 'completed' ? 'checked' : '' }}" title="{{ $todo->status === 'completed' ? 'Mark as Pending' : 'Mark as Completed' }}">
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
                                                                @if($todo->completed_at)
                                                                    ({{ $todo->completed_at->format('M. d') }})
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span class="dash-todo-chip dash-todo-chip-pending">
                                                                <i class="bi bi-hourglass-split"></i> Pending
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Staff Action Button --}}
                                                <div class="flex-shrink-0">
                                                    @if($todo->status === 'pending')
                                                        <form method="POST" action="{{ route('todos.toggle', $todo->id) }}" class="m-0">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dash-btn-complete">
                                                                <i class="bi bi-check-lg"></i> Mark as Completed
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('todos.toggle', $todo->id) }}" class="m-0">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; font-size: 11px; font-weight: 600;" title="Click to undo">
                                                                <i class="bi bi-arrow-counterclockwise"></i> Undo
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Relevant Tracking & Expirations --}}
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
                            <div class="dash-card-sub">Newest entries submitted to the system</div>
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

            // Trend chart
            const trendCtx = document.getElementById('staffTrendChart');
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
                            legend: { display: false },
                            tooltip: { backgroundColor: '#0b2342', padding: 10, cornerRadius: 8 }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: textColor, font: { size: 11 } } },
                            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor, font: { size: 11 }, precision: 0 } }
                        }
                    }
                });
            }

            // Gauge chart
            const gaugeCtx = document.getElementById('staffActiveGaugeChart');
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
                        cutout: '76%',
                        plugins: { tooltip: { enabled: false } }
                    }
                });
            }

            // Franchise chart
            const statusCtx = document.getElementById('staffFranchiseChart');
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
                        cutout: '68%',
                        plugins: { legend: { display: false } }
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