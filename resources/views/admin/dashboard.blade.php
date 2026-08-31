<x-app-layout>

    <x-sidebar />


    <div id="mainWrapper" class="main-wrapper">


        {{-- TOPBAR --}}

        <header class="topbar">

            <div class="d-flex align-items-center gap-3">

                <button
                    id="sidebarToggle"
                    class="sidebar-toggle"
                    type="button"
                >

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

            </div>

        </header>


        {{-- CONTENT --}}

        <main class="content">

            <div class="container-fluid">

                <div class="dashboard-welcome mb-4">

                    <h2>
                        Welcome, {{ auth()->user()->name }}!
                    </h2>

                    <p>
                        Use the navigation menu to manage
                        TODA registrations, franchises,
                        members, alerts, and reports.
                    </p>

                </div>

                {{-- DRIVERS OVERVIEW --}}
                <div class="section-group-title">
                    <i class="bi bi-person-vcard"></i> Drivers Overview
                </div>
                <div class="stats-grid">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Total Drivers
                            <span class="stat-badge stat-badge-total">All</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['drivers']['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Active Drivers
                            <span class="stat-badge stat-badge-active">Active</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['drivers']['active'] }}</div>
                    </div>
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Expiring Drivers
                            <span class="stat-badge stat-badge-expiring">30 Days</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['drivers']['expiring'] }}</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Inactive Drivers
                            <span class="stat-badge stat-badge-inactive">Expired</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['drivers']['inactive'] }}</div>
                    </div>
                </div>

                {{-- OPERATORS OVERVIEW --}}
                <div class="section-group-title">
                    <i class="bi bi-person-badge"></i> Operators Overview
                </div>
                <div class="stats-grid">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Total Operators
                            <span class="stat-badge stat-badge-total">All</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['operators']['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Active Operators
                            <span class="stat-badge stat-badge-active">Active</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['operators']['active'] }}</div>
                    </div>
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Expiring Operators
                            <span class="stat-badge stat-badge-expiring">N/A</span>
                        </div>
                        <div class="stat-card-value">0</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Inactive Operators
                            <span class="stat-badge stat-badge-inactive">Inactive</span>
                        </div>
                    </div>
                </div>

                {{-- VEHICLES OVERVIEW --}}
                <div class="section-group-title">
                    <i class="bi bi-car-front"></i> Vehicles Overview
                </div>
                <div class="stats-grid">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Total Vehicles
                            <span class="stat-badge stat-badge-total">All</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['vehicles']['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Active Vehicles
                            <span class="stat-badge stat-badge-active">Active</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['vehicles']['active'] }}</div>
                    </div>
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Expiring Vehicles
                            <span class="stat-badge stat-badge-expiring">30 Days</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['vehicles']['expiring'] }}</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Inactive Vehicles
                            <span class="stat-badge stat-badge-inactive">Expired</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['vehicles']['inactive'] }}</div>
                    </div>
                </div>

                {{-- FRANCHISES OVERVIEW --}}
                <div class="section-group-title">
                    <i class="bi bi-file-earmark-text"></i> Franchises Overview
                </div>
                <div class="stats-grid">
                    <div class="stat-card stat-card-total">
                        <div class="stat-card-label">
                            Total Franchises
                            <span class="stat-badge stat-badge-total">All</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['franchises']['total'] }}</div>
                    </div>
                    <div class="stat-card stat-card-active">
                        <div class="stat-card-label">
                            Active Franchises
                            <span class="stat-badge stat-badge-active">Active</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['franchises']['active'] }}</div>
                    </div>
                    <div class="stat-card stat-card-expiring">
                        <div class="stat-card-label">
                            Expiring Franchises
                            <span class="stat-badge stat-badge-expiring">30 Days</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['franchises']['expiring'] }}</div>
                    </div>
                    <div class="stat-card stat-card-inactive">
                        <div class="stat-card-label">
                            Inactive Franchises
                            <span class="stat-badge stat-badge-inactive">Expired</span>
                        </div>
                        <div class="stat-card-value">{{ $stats['franchises']['inactive'] }}</div>
                    </div>
                </div>

                {{-- UPCOMING EXPIRATIONS --}}
                <div class="expirations-card mb-4">
                    <div class="expirations-header">
                        <h3 class="expirations-title">
                            <i class="bi bi-exclamation-triangle"></i> Upcoming Expirations
                        </h3>
                        <span class="badge bg-warning text-dark" style="font-size: 11px; font-weight: 600; padding: 5px 10px;">
                            Warning Period: 30 Days
                        </span>
                    </div>
                    <div class="expirations-body">
                        @if($stats['upcomingExpirations']->isEmpty())
                            <p class="text-muted text-center my-3">No upcoming expirations within the next 30 days.</p>
                        @else
                            <div class="table-responsive-custom">
                                <table class="expirations-table">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Name/ID</th>
                                            <th>Expiration Date</th>
                                            <th>Days Remaining</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['upcomingExpirations'] as $item)
                                            <tr>
                                                <td>
                                                    <span class="badge-type-{{ strtolower($item['type']) }}">
                                                        <i class="bi {{ $item['type'] === 'Driver' ? 'bi-person-vcard' : ($item['type'] === 'Vehicle' ? 'bi-car-front' : 'bi-file-earmark-text') }}"></i>
                                                        {{ $item['type'] }}
                                                    </span>
                                                </td>
                                                <td><strong>{{ $item['name_id'] }}</strong></td>
                                                <td>{{ $item['expiration_date'] }}</td>
                                                <td>
                                                    <span class="badge-days-left badge-days-warning">
                                                        {{ $item['days_remaining'] }} {{ $item['days_remaining'] == 1 ? 'day' : 'days' }} remaining
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark" style="font-size: 11px; font-weight: 600;">
                                                        {{ $item['status'] }}
                                                    </span>
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

        </main>

    </div>


    {{-- SIDEBAR TOGGLE --}}

    <script>

        const sidebar =
            document.getElementById('sidebar');

        const mainWrapper =
            document.getElementById('mainWrapper');

        const toggle =
            document.getElementById('sidebarToggle');


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