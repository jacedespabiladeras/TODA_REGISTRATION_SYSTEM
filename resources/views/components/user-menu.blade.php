<div class="user-menu" x-data="{ open: false }">

    {{-- USER BUTTON --}}
    <button
        type="button"
        class="user-button"
        @click="open = !open"
    >

        {{-- Profile Picture --}}
        @if(auth()->user()->profile_picture)
            <img
                src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                class="user-avatar"
                alt="Profile Picture"
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
        <span class="user-arrow">
            ▼
        </span>

    </button>


    {{-- DROPDOWN --}}
    <div
        x-show="open"
        @click.outside="open = false"
        class="user-dropdown"
        style="display: none;"
    >

        {{-- USER INFORMATION --}}
        <div class="user-dropdown-header">

            @if(auth()->user()->profile_picture)

                <img
                    src="{{ asset('storage/' . auth()->user()->profile_picture) }}"
                    class="user-dropdown-avatar"
                    alt="Profile Picture"
                >

            @else

                <div class="user-dropdown-avatar user-avatar-default">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

            @endif


            <div>

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <small>
                    {{ auth()->user()->email }}
                </small>

            </div>

        </div>


        {{-- DASHBOARD --}}
        @if(auth()->user()->role->name === 'admin')

            <a
                href="{{ route('admin.dashboard') }}"
                class="user-dropdown-link"
            >
                <span>⌂</span>
                Dashboard
            </a>

        @else

            <a
                href="{{ route('staff.dashboard') }}"
                class="user-dropdown-link"
            >
                <span>⌂</span>
                Dashboard
            </a>

        @endif


        {{-- PROFILE --}}
        <a
            href="{{ route('profile.edit') }}"
            class="user-dropdown-link"
        >
            <span>♙</span>
            Profile
        </a>


        {{-- SETTINGS --}}
        <a
            href="{{ route('settings') }}"
            class="user-dropdown-link"
        >
            <span>⚙</span>
            Settings
        </a>


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
                <span>⇥</span>
                Log out
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


    /* =========================
       USER BUTTON
    ========================== */

    .user-button {
        display: flex;
        align-items: center;
        gap: 10px;

        background: white;
        border: 1px solid #ddd;

        padding: 6px 10px;

        border-radius: 6px;

        cursor: pointer;

        font-family: Arial, Helvetica, sans-serif;
    }

    .user-button:hover {
        background: #f5f5f5;
    }


    /* =========================
       AVATAR
    ========================== */

    .user-avatar {
        width: 38px;
        height: 38px;

        border-radius: 50%;

        object-fit: cover;
    }


    .user-avatar-default {
        display: flex;
        align-items: center;
        justify-content: center;

        background: #174a7c;
        color: white;

        font-weight: bold;
        font-size: 16px;
    }


    .user-name {
        color: #333;
        font-size: 14px;
        font-weight: 600;
    }


    .user-arrow {
        font-size: 10px;
        color: #777;
    }


    /* =========================
       DROPDOWN
    ========================== */

    .user-dropdown {
        position: absolute;

        right: 0;
        top: calc(100% + 8px);

        width: 260px;

        background: white;

        border: 1px solid #ddd;

        border-radius: 6px;

        box-shadow: 0 4px 15px rgba(0,0,0,.12);

        z-index: 9999;

        overflow: hidden;
    }


    /* =========================
       USER HEADER
    ========================== */

    .user-dropdown-header {
        display: flex;
        align-items: center;

        gap: 12px;

        padding: 15px;

        border-bottom: 1px solid #eee;
    }


    .user-dropdown-avatar {
        width: 45px;
        height: 45px;

        border-radius: 50%;

        object-fit: cover;
    }


    .user-dropdown-header strong {
        display: block;

        color: #333;

        font-size: 14px;
    }


    .user-dropdown-header small {
        display: block;

        margin-top: 3px;

        color: #888;

        font-size: 12px;
    }


    /* =========================
       DROPDOWN LINKS
    ========================== */

    .user-dropdown-link {
        display: flex;
        align-items: center;

        gap: 12px;

        width: 100%;

        padding: 13px 15px;

        background: white;

        border: none;

        color: #333;

        text-decoration: none;

        font-size: 14px;

        text-align: left;

        cursor: pointer;

        box-sizing: border-box;
    }


    .user-dropdown-link:hover {
        background: #f4f6f8;
        color: #174a7c;
    }


    .user-dropdown-link span {
        width: 20px;

        text-align: center;

        font-size: 17px;
    }


    .user-dropdown-divider {
        height: 1px;

        background: #eee;
    }


    .logout-link {
        color: #b42318;
    }


    .logout-link:hover {
        color: #941d14;
        background: #fff5f5;
    }


    /* =========================
       MOBILE
    ========================== */

    @media (max-width: 600px) {

        .user-name {
            display: none;
        }

        .user-dropdown {
            right: 0;
            width: 240px;
        }

    }

</style>