<aside id="sidebar" class="sidebar">

    {{-- =====================================================
         LOGO / BRAND
    ====================================================== --}}

    <div class="sidebar-brand">

        <div class="brand-logo">
            <img
                src="{{ asset('images/Sorsogon_City_Seal.png') }}"
                alt="Sorsogon City Seal"
            >
        </div>

        <div class="brand-text">

            <strong>
                SORSOGON CITY
            </strong>

            <small>
                Government Portal
            </small>

        </div>

    </div>


    {{-- =====================================================
         SIDEBAR MENU
    ====================================================== --}}

    <div class="sidebar-menu">


        {{-- MAIN MENU --}}

        <div class="menu-title">
            MAIN MENU
        </div>


        {{-- DASHBOARD --}}

        <a
            href="{{ route('dashboard') }}"
            class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >

            <i class="bi bi-speedometer2"></i>

            <span>
                Dashboard
            </span>

        </a>


        {{-- DRIVER REGISTRATION --}}

        <a
            href="{{ route('drivers.index') }}"
            class="sidebar-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}"
        >

            <i class="bi bi-person-vcard"></i>

            <span>
                Driver Registration
            </span>

        </a>


        {{-- OPERATOR REGISTRATION --}}

        <a
            href="{{ route('operators.index') }}"
            class="sidebar-link {{ request()->routeIs('operators.*') ? 'active' : '' }}"
        >

            <i class="bi bi-person-badge"></i>

            <span>
                Operator Registration
            </span>

        </a>


        {{-- VEHICLE REGISTRATION --}}

        <a
            href="{{ route('vehicles.index') }}"
            class="sidebar-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}"
        >

            <i class="bi bi-car-front"></i>

            <span>
                Vehicle Registration
            </span>

        </a>


        {{-- =================================================
             FRANCHISE
        ================================================== --}}

        <a
            href="#franchiseMenu"
            class="sidebar-link"
            data-bs-toggle="collapse"
            role="button"
            aria-expanded="false"
            aria-controls="franchiseMenu"
        >

            <i class="bi bi-file-earmark-text"></i>

            <span>
                Franchise
            </span>

            <i class="bi bi-chevron-down ms-auto sidebar-arrow"></i>

        </a>


        <div
            class="collapse sidebar-submenu"
            id="franchiseMenu"
        >

            {{-- FRANCHISE REGISTRATION --}}

            <a
                href="{{ route('franchises.index') }}"
                class="sidebar-sublink"
            >

                <i class="bi bi-dot"></i>

                Franchise Registration

            </a>


            {{-- FRANCHISE RENEWAL --}}

            <a
                href="{{ route('renewals.index') }}"
                class="sidebar-sublink"
            >

                <i class="bi bi-dot"></i>

                Franchise Renewal

            </a>

        </div>


        {{-- TRACKING & ALERTS --}}

        <a
            href="{{ route('tracking.index') }}"
            class="sidebar-link {{ request()->routeIs('tracking.*') ? 'active' : '' }}"
        >

            <i class="bi bi-bell"></i>

            <span>
                Tracking & Alerts
            </span>

        </a>


        {{-- REPORTS --}}

        <a
            href="{{ route('reports.index') }}"
            class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
        >

            <i class="bi bi-bar-chart"></i>

            <span>
                Reports
            </span>

        </a>



        {{-- =================================================
             ADMINISTRATION
        ================================================== --}}

        @if(auth()->user()->role?->name === 'admin')

            <div class="menu-title admin-title">
                ADMINISTRATION
            </div>


            {{-- MEMBER REGISTRATION --}}

            <a
                href="{{ route('members.index') }}"
                class="sidebar-link {{ request()->routeIs('members.*') ? 'active' : '' }}"
            >

                <i class="bi bi-people"></i>

                <span>
                    Member Registration
                </span>

            </a>


            {{-- REPORTS OUTPUT --}}

            <a
                href="{{ route('reports.output') }}"
                class="sidebar-link"
            >

                <i class="bi bi-file-earmark-bar-graph"></i>

                <span>
                    Reports Output
                </span>

            </a>

        @endif


    </div>


    {{-- =====================================================
         LOGOUT
    ====================================================== --}}

    <div class="sidebar-bottom">

        <form
            method="POST"
            action="{{ route('logout') }}"
        >

            @csrf

            <button
                type="submit"
                class="logout-btn"
            >

                <i class="bi bi-box-arrow-right"></i>

                <span>
                    Logout
                </span>

            </button>

        </form>

    </div>

</aside>