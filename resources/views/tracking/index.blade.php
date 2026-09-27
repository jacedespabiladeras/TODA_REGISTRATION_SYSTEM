<x-app-layout>
    <x-sidebar />

    <div id="mainWrapper" class="main-wrapper">
        {{-- TOPBAR --}}
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebarToggle" class="sidebar-toggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">Tracking & Expiration Alerts</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <x-user-menu />
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="content">
            <div class="container-fluid">
            </div>
        </main>
    </div>
</x-app-layout>