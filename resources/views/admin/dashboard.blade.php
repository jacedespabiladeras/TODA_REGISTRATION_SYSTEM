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

                <div class="dashboard-welcome">

                    <h2>
                        Welcome, {{ auth()->user()->name }}!
                    </h2>

                    <p>
                        Use the navigation menu to manage
                        TODA registrations, franchises,
                        members, alerts, and reports.
                    </p>

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