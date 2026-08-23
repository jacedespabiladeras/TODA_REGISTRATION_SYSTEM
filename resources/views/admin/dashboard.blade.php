<x-app-layout>

    <style>

        body {
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }

        .admin-container {
            max-width: 1200px;
            margin: auto;
            padding: 30px 20px;
        }

        /* =========================
           DASHBOARD HEADER
        ========================== */

        .dashboard-header {
            background: white;
            border-left: 5px solid #174a7c;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);

            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dashboard-header-content {
            flex: 1;
        }

        .dashboard-header h1 {
            margin: 0;
            font-size: 28px;
            color: #174a7c;
        }

        .dashboard-header p {
            margin: 6px 0 0;
            color: #777;
        }

        .role-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 12px;
            background: #e8f1f8;
            color: #174a7c;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* =========================
           LOGOUT BUTTON
        ========================== */

        .logout-form {
            margin-left: 20px;
        }

        .logout-button {
            background: #b42318;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .logout-button:hover {
            background: #941d14;
        }

        /* =========================
           DASHBOARD GRID
        ========================== */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .dashboard-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 7px;
            padding: 22px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }

        .dashboard-card h3 {
            color: #333;
            margin-top: 0;
            font-size: 18px;
        }

        .dashboard-card p {
            color: #777;
            font-size: 13px;
            line-height: 1.5;
        }

        .dashboard-button {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #2878b5;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }

        .dashboard-button:hover {
            background: #216696;
        }

        /* =========================
           ADMIN ONLY CARDS
        ========================== */

        .admin-card {
            border-top: 4px solid #c99a00;
        }

        /* =========================
           RESPONSIVE
        ========================== */

        @media (max-width: 800px) {

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .logout-form {
                margin-left: 0;
            }

            .logout-button {
                width: 100%;
            }

        }

    </style>


    <div class="admin-container">


        {{-- =========================
             ADMIN HEADER
        ========================== --}}

        <div class="dashboard-header">

            <div class="dashboard-header-content">

                <h1>
                    Admin Dashboard
                </h1>

                <p>
                    Welcome, {{ auth()->user()->name }}.
                </p>

                <span class="role-badge">
                    Administrator
                </span>

            </div>


            {{-- =========================
                 LOGOUT
            ========================== --}}

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="logout-form"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >
                    Log Out
                </button>

            </form>

        </div>


        {{-- =========================
             DASHBOARD CARDS
        ========================== --}}

        <div class="dashboard-grid">


            {{-- DRIVER --}}

            <div class="dashboard-card">

                <h3>
                    Add New Driver
                </h3>

                <p>
                    Register and manage TODA drivers.
                </p>

                <a
                    href="{{ route('drivers.index') }}"
                    class="dashboard-button"
                >
                    Manage Drivers
                </a>

            </div>


            {{-- OPERATOR --}}

            <div class="dashboard-card">

                <h3>
                    Add New Operator
                </h3>

                <p>
                    Register and manage TODA operators.
                </p>

                <a
                    href="{{ route('operators.index') }}"
                    class="dashboard-button"
                >
                    Manage Operators
                </a>

            </div>


            {{-- VEHICLES --}}

            <div class="dashboard-card">

                <h3>
                    Vehicle Registration
                </h3>

                <p>
                    Register and manage TODA vehicles.
                </p>

                <a
                    href="{{ route('vehicles.index') }}"
                    class="dashboard-button"
                >
                    Manage Vehicles
                </a>

            </div>


            {{-- FRANCHISE --}}

            <div class="dashboard-card">

                <h3>
                    Franchise Registration
                </h3>

                <p>
                    Register and manage franchise records.
                </p>

                <a
                    href="{{ route('franchises.index') }}"
                    class="dashboard-button"
                >
                    Manage Franchises
                </a>

            </div>


            {{-- RENEWAL --}}

            <div class="dashboard-card">

                <h3>
                    Franchise Renewal
                </h3>

                <p>
                    Process franchise renewals.
                </p>

                <a
                    href="{{ route('renewals.index') }}"
                    class="dashboard-button"
                >
                    Renew Franchise
                </a>

            </div>


            {{-- TRACKING --}}

            <div class="dashboard-card">

                <h3>
                    Tracking & Alerts
                </h3>

                <p>
                    Monitor expiration dates and alerts.
                </p>

                <a
                    href="{{ route('tracking.index') }}"
                    class="dashboard-button"
                >
                    View Tracking
                </a>

            </div>


            {{-- REPORTS --}}

            <div class="dashboard-card">

                <h3>
                    Reports
                </h3>

                <p>
                    View system records and reports.
                </p>

                <a
                    href="{{ route('reports.index') }}"
                    class="dashboard-button"
                >
                    View Reports
                </a>

            </div>


            {{-- ADMIN ONLY: MEMBERS --}}

            <div class="dashboard-card admin-card">

                <h3>
                    Member Registration
                </h3>

                <p>
                    Admin-only registration and management
                    of TODA members.
                </p>

                <a
                    href="{{ route('members.index') }}"
                    class="dashboard-button"
                >
                    Manage Members
                </a>

            </div>


            {{-- ADMIN ONLY: REPORT OUTPUT --}}

            <div class="dashboard-card admin-card">

                <h3>
                    Reports Output
                </h3>

                <p>
                    Generate and export official reports.
                </p>

                <a
                    href="{{ route('reports.output') }}"
                    class="dashboard-button"
                >
                    Generate Output
                </a>

            </div>


        </div>

    </div>

</x-app-layout>