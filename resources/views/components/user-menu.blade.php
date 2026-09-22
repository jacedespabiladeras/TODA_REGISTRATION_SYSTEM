<div class="user-menu" x-data="{ open: false }">

    {{-- USER BUTTON --}}
    <button
        type="button"
        class="user-button"
        @click="open = !open"
        :aria-expanded="open"
        aria-label="User account menu"
    >

        {{-- Profile Picture --}}
        @if(auth()->user()->profile_picture)
            <img
                src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                class="user-avatar"
                alt="{{ auth()->user()->name }}"
            >
        @else
            <div class="user-avatar user-avatar-default">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif

        {{-- User Name --}}
        <span class="user-name">
            {{ auth()->user()->name }}
        </span>

        {{-- Arrow --}}
        <i class="bi bi-chevron-down user-arrow" :class="{ 'rotate-180': open }"></i>

    </button>


    {{-- DROPDOWN --}}
    <div
        x-show="open"
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="user-dropdown"
        style="display: none;"
    >

        {{-- USER INFORMATION --}}
        <div class="user-dropdown-header">

            @if(auth()->user()->profile_picture)
                <img
                    src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                    class="user-dropdown-avatar"
                    alt="{{ auth()->user()->name }}"
                >
            @else
                <div class="user-dropdown-avatar user-avatar-default">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif

            <div class="user-dropdown-info">
                <strong>
                    {{ auth()->user()->name }}
                </strong>
                <small>
                    {{ auth()->user()->email }}
                </small>
                <span class="user-role-badge">
                    {{ auth()->user()->role?->name === 'admin' ? 'Administrator' : 'Staff' }}
                </span>
            </div>

        </div>


        {{-- DASHBOARD --}}
        @if(auth()->user()->role?->name === 'admin')
            <a
                href="{{ route('admin.dashboard') }}"
                class="user-dropdown-link"
            >
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        @else
            <a
                href="{{ route('staff.dashboard') }}"
                class="user-dropdown-link"
            >
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        @endif


        {{-- PROFILE --}}
        <a
            href="{{ route('settings', ['tab' => 'profile']) }}"
            class="user-dropdown-link {{ request()->routeIs('settings*') && request('tab', 'profile') === 'profile' ? 'active' : '' }}"
        >
            <i class="bi bi-person"></i>
            <span>Profile</span>
        </a>


        {{-- SETTINGS --}}
        <a
            href="{{ route('settings', ['tab' => 'preferences']) }}"
            class="user-dropdown-link {{ request()->routeIs('settings*') && request('tab') === 'preferences' ? 'active' : '' }}"
        >
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>


        {{-- QUICK THEME SWITCHER --}}
        <button
            type="button"
            class="user-dropdown-link"
            onclick="window.toggleAppTheme()"
            title="Switch between Light and Dark Mode"
        >
            <i class="bi bi-circle-half"></i>
            <span>Switch Theme</span>
        </button>


        {{-- LOG OUT --}}
        <div class="user-dropdown-divider"></div>

        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf
            <button
                type="submit"
                class="user-dropdown-link logout-link"
            >
                <i class="bi bi-box-arrow-right"></i>
                <span>Log out</span>
            </button>
        </form>

    </div>

</div>


<style>
    /* =========================
       USER MENU
    ========================== */
    .user-menu {
        position: relative;
        margin-left: auto;
    }

    /* USER BUTTON */
    .user-button {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #d0d7de;
        padding: 5px 12px 5px 6px;
        border-radius: 30px;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .user-button:hover {
        background: #f8f9fa;
        border-color: #0b2342;
    }

    /* AVATAR */
    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e2e8f0;
    }

    .user-avatar-default {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #0b2342;
        color: white;
        font-weight: 700;
        font-size: 14px;
        border: 2px solid #e2e8f0;
    }

    .user-name {
        color: #1e293b;
        font-size: 13px;
        font-weight: 600;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .user-arrow {
        font-size: 11px;
        color: #64748b;
        transition: transform 0.2s ease;
    }

    .user-arrow.rotate-180 {
        transform: rotate(180deg);
    }

    /* DROPDOWN */
    .user-dropdown {
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        width: 270px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        z-index: 1050;
        overflow: hidden;
    }

    /* USER HEADER */
    .user-dropdown-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .user-dropdown-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #cbd5e1;
        flex-shrink: 0;
    }

    .user-dropdown-info {
        overflow: hidden;
    }

    .user-dropdown-header strong {
        display: block;
        color: #0f172a;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-dropdown-header small {
        display: block;
        color: #64748b;
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 4px;
    }

    .user-role-badge {
        display: inline-block;
        padding: 2px 8px;
        background: #e0f2fe;
        color: #0369a1;
        font-size: 10px;
        font-weight: 700;
        border-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* DROPDOWN LINKS */
    .user-dropdown-link {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 10px 16px;
        background: transparent;
        border: none;
        color: #334155;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 500;
        text-align: left;
        cursor: pointer;
        box-sizing: border-box;
        transition: background-color 0.15s ease, color 0.15s ease;
    }

    .user-dropdown-link i {
        font-size: 16px;
        color: #64748b;
        width: 18px;
        text-align: center;
        transition: color 0.15s ease;
    }

    .user-dropdown-link:hover {
        background: #f1f5f9;
        color: #0b2342;
    }

    .user-dropdown-link:hover i {
        color: #0b2342;
    }

    .user-dropdown-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 4px 0;
    }

    .logout-link {
        color: #dc2626;
    }

    .logout-link i {
        color: #dc2626;
    }

    .logout-link:hover {
        color: #b91c1c;
        background: #fef2f2;
    }

    .logout-link:hover i {
        color: #b91c1c;
    }

    /* MOBILE */
    @media (max-width: 600px) {
        .user-name {
            display: none;
        }
        .user-dropdown {
            right: 0;
            width: 250px;
        }
    }

    /* DARK MODE OVERRIDES */
    [data-theme="dark"] .user-button {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    [data-theme="dark"] .user-button:hover {
        background: #283548;
        border-color: #60a5fa;
    }
    [data-theme="dark"] .user-avatar,
    [data-theme="dark"] .user-avatar-default {
        border-color: #475569;
    }
    [data-theme="dark"] .user-avatar-default {
        background: #1e3a8a;
    }
    [data-theme="dark"] .user-name {
        color: #f1f5f9;
    }
    [data-theme="dark"] .user-arrow {
        color: #94a3b8;
    }
    [data-theme="dark"] .user-dropdown {
        background: #1e293b;
        border-color: #334155;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
    }
    [data-theme="dark"] .user-dropdown-header {
        background: #162032;
        border-color: #334155;
    }
    [data-theme="dark"] .user-dropdown-header strong {
        color: #f8fafc;
    }
    [data-theme="dark"] .user-dropdown-header small {
        color: #94a3b8;
    }
    [data-theme="dark"] .user-role-badge {
        background: #1e3a8a;
        color: #93c5fd;
    }
    [data-theme="dark"] .user-dropdown-link {
        color: #cbd5e1;
    }
    [data-theme="dark"] .user-dropdown-link i {
        color: #94a3b8;
    }
    [data-theme="dark"] .user-dropdown-link:hover {
        background: #283548;
        color: #60a5fa;
    }
    [data-theme="dark"] .user-dropdown-link:hover i {
        color: #60a5fa;
    }
    [data-theme="dark"] .user-dropdown-divider {
        background: #334155;
    }
    [data-theme="dark"] .logout-link {
        color: #f87171;
    }
    [data-theme="dark"] .logout-link i {
        color: #f87171;
    }
    [data-theme="dark"] .logout-link:hover {
        background: #450a0a;
        color: #fca5a5;
    }
</style>