<x-app-layout>

 ```html
<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f5f6f8;
        font-family: Arial, Helvetica, sans-serif;
        color: #333;
    }

    /* =========================
       SIDEBAR
    ========================== */

    .gov-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 235px;
        height: 100vh;
        background: #ffffff;
        color: #333;
        z-index: 1000;
        border-right: 1px solid #e6e6e6;
    }

    .sidebar-header {
        height: 78px;
        display: flex;
        align-items: center;
        padding: 14px 18px;
        background: #ffffff;
        border-bottom: 1px solid #eeeeee;
    }

    .gov-logo {
        width: 43px;
        height: 43px;
        border-radius: 50%;
        object-fit: cover;
        background: #ffffff;
        border: 1px solid #e5e5e5;
        margin-right: 10px;
    }

    .government-name {
        line-height: 1.2;
    }

    .government-name strong {
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }

    .government-name span {
        display: block;
        font-size: 11px;
        color: #999;
        margin-top: 4px;
    }

    /* MENU */

    .sidebar-menu {
        padding: 20px 12px;
    }

    .menu-title {
        font-size: 11px;
        text-transform: uppercase;
        color: #999;
        padding: 0 12px;
        margin-bottom: 8px;
        letter-spacing: .5px;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 13px;
        color: #666;
        text-decoration: none;
        border-radius: 7px;
        font-size: 14px;
        margin-bottom: 4px;
        transition: .2s;
    }

    .sidebar-menu a:hover {
        background: #f5f5f5;
        color: #333;
    }

    .sidebar-menu a.active {
        background: #f0f0f0;
        color: #222;
        font-weight: 600;
    }

    .sidebar-menu .icon {
        width: 20px;
        text-align: center;
        font-size: 17px;
        color: #888;
    }

    .sidebar-menu a.active .icon {
        color: #444;
    }

    /* LOGOUT */

    .sidebar-bottom {
        position: absolute;
        bottom: 18px;
        left: 12px;
        right: 12px;
    }

    .logout-button {
        width: 100%;
        border: 1px solid #e3e3e3;
        background: #ffffff;
        color: #666;
        padding: 11px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        transition: .2s;
    }

    .logout-button:hover {
        background: #f5f5f5;
        color: #333;
    }

    /* =========================
       MAIN
    ========================== */

    .gov-main {
        margin-left: 235px;
        min-height: 100vh;
    }

    /* =========================
       TOP BAR
    ========================== */

    .topbar {
        height: 65px;
        background: #ffffff;
        border-bottom: 1px solid #e5e5e5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
    }

    .page-title {
        font-size: 19px;
        font-weight: 600;
        color: #333;
    }

    .page-title small {
        display: block;
        font-size: 11px;
        color: #999;
        font-weight: normal;
        margin-top: 4px;
    }

    .user-area {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        background: #f3f3f3;
        color: #555;
        border: 1px solid #e2e2e2;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
        font-size: 14px;
    }

    .user-info {
        line-height: 1.2;
    }

    .user-info strong {
        font-size: 13px;
        color: #333;
    }

    .user-info span {
        display: block;
        font-size: 11px;
        color: #999;
        margin-top: 3px;
    }

    /* =========================
       CONTENT
    ========================== */

    .content {
        padding: 25px 30px;
        max-width: 1400px;
    }

    /* =========================
       WELCOME
    ========================== */

    .welcome-box {
        background: #ffffff;
        border: 1px solid #e2e2e2;
        border-left: 4px solid #777;
        border-radius: 8px;
        padding: 23px 25px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .welcome-box h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 600;
        color: #333;
    }

    .welcome-box p {
        margin: 8px 0 0;
        font-size: 13px;
        color: #777;
    }

    .welcome-badge {
        display: inline-block;
        margin-top: 13px;
        background: #f5f5f5;
        border: 1px solid #e1e1e1;
        color: #777;
        padding: 6px 10px;
        border-radius: 4px;
        font-size: 11px;
    }

    /* =========================
       STATISTICS
    ========================== */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e3e3e3;
        border-radius: 7px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: .2s;
    }

    .stat-card:hover {
        border-color: #cccccc;
        transform: translateY(-1px);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 6px;
        background: #f4f4f4;
        color: #555;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 19px;
    }

    .stat-info span {
        font-size: 11px;
        color: #999;
        display: block;
    }

    .stat-info strong {
        display: block;
        font-size: 23px;
        margin-top: 4px;
        color: #333;
    }

    /* =========================
       DASHBOARD GRID
    ========================== */

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 15px;
    }

    .panel {
        background: #ffffff;
        border: 1px solid #e3e3e3;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .panel-header {
        padding: 15px 17px;
        border-bottom: 1px solid #eeeeee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-header h3 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .panel-header span {
        font-size: 11px;
        color: #aaa;
    }

    .panel-body {
        padding: 17px;
    }

    /* =========================
       QUICK ACTIONS
    ========================== */

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .quick-action {
        border: 1px solid #e4e4e4;
        border-radius: 6px;
        padding: 15px;
        text-decoration: none;
        color: #333;
        transition: .2s;
        background: #fff;
    }

    .quick-action:hover {
        border-color: #ccc;
        background: #fafafa;
        transform: translateY(-1px);
    }

    .quick-action-icon {
        width: 36px;
        height: 36px;
        background: #f3f3f3;
        color: #666;
        border-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 9px;
        font-size: 16px;
    }

    .quick-action strong {
        display: block;
        font-size: 13px;
        color: #333;
    }

    .quick-action small {
        display: block;
        font-size: 10px;
        color: #999;
        margin-top: 4px;
        line-height: 1.4;
    }

    /* =========================
       ACTIVITY
    ========================== */

    .activity {
        display: flex;
        gap: 11px;
        padding: 13px 0;
        border-bottom: 1px solid #eeeeee;
    }

    .activity:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #f3f3f3;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .activity-text strong {
        display: block;
        font-size: 12px;
        color: #444;
    }

    .activity-text span {
        display: block;
        font-size: 10px;
        color: #aaa;
        margin-top: 4px;
    }

    /* =========================
       ANNOUNCEMENTS
    ========================== */

    .announcement {
        padding: 13px 0;
        border-bottom: 1px solid #eeeeee;
    }

    .announcement:last-child {
        border-bottom: none;
    }

    .announcement strong {
        font-size: 12px;
        color: #444;
    }

    .announcement p {
        margin: 5px 0 0;
        font-size: 10px;
        color: #999;
        line-height: 1.6;
    }

    /* =========================
       ACCOUNT
    ========================== */

    .profile-button {
        display: block;
        text-align: center;
        margin-top: 15px;
        background: #f5f5f5;
        color: #555;
        border: 1px solid #e2e2e2;
        padding: 9px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 12px;
        transition: .2s;
    }

    .profile-button:hover {
        background: #eeeeee;
        color: #333;
    }

    /* =========================
       FOOTER
    ========================== */

    .footer {
        text-align: center;
        padding: 22px;
        color: #aaa;
        font-size: 11px;
    }

    .footer strong {
        color: #666;
        font-size: 12px;
    }

    /* =========================
       MOBILE
    ========================== */

    @media (max-width: 1000px) {

        .gov-sidebar {
            width: 210px;
        }

        .gov-main {
            margin-left: 210px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {

        .gov-sidebar {
            position: relative;
            width: 100%;
            height: auto;
        }

        .sidebar-bottom {
            position: relative;
            bottom: auto;
            left: auto;
            right: auto;
            padding: 0 12px 15px;
        }

        .gov-main {
            margin-left: 0;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .topbar {
            padding: 0 15px;
        }

        .content {
            padding: 20px 15px;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }
    }
</style>
```



    <!-- SIDEBAR -->

    <aside class="gov-sidebar">

        <div class="sidebar-header">

            <img
                src="{{ asset('images/Sorsogon_City_Seal.png') }}"
                class="gov-logo"
                alt="Sorsogon City Government Logo"
            >

            <div class="government-name">
                <strong>Sorsogon City</strong>
                <span>Government Portal</span>
            </div>

        </div>


        <div class="sidebar-menu">

            <div class="menu-title">
                Main Menu
            </div>

            <a href="{{ route('dashboard') }}" class="active">
                <span class="icon">▦</span>
                Dashboard
            </a>

            <a href="#">
                <span class="icon">▤</span>
                Management
            </a>

            <a href="#">
                <span class="icon">♟</span>
                Records
            </a>

            <a href="#">
                <span class="icon">▣</span>
                Reports
            </a>


            <div class="menu-title" style="margin-top:25px;">
                Account
            </div>

            <a href="#">
                <span class="icon">👤</span>
                My Profile
            </a>

            <a href="#">
                <span class="icon">⚙</span>
                Settings
            </a>

        </div>


        <div class="sidebar-bottom">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >
                    ⇥ &nbsp; Logout
                </button>

            </form>

        </div>

    </aside>


    <!-- MAIN -->

    <main class="gov-main">

        <!-- TOP BAR -->

        <div class="topbar">

            <div class="page-title">
                Dashboard

                <small>
                    Sorsogon City Government Online Portal
                </small>
            </div>


            <div class="user-area">

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="user-info">

                    <strong>
                        {{ auth()->user()->name }}
                    </strong>

                    <span>
                        Administrator
                    </span>

                </div>

            </div>

        </div>


        <!-- CONTENT -->

        <div class="content">


            <!-- WELCOME -->

            <div class="welcome-box">

                <h2>
                    Welcome, {{ auth()->user()->name }}!
                </h2>

                <p>
                    Welcome to the Sorsogon City Government Online Portal.
                    Manage government records and services from one place.
                </p>

                <span class="welcome-badge">
                    SYSTEM ADMINISTRATOR
                </span>

            </div>


            <!-- STATISTICS -->

            <div class="stats-grid">


                <div class="stat-card">

                    <div class="stat-icon">
                        👥
                    </div>

                    <div class="stat-info">

                        <span>
                            Total Users
                        </span>

                        <strong>
                            24
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        📁
                    </div>

                    <div class="stat-info">

                        <span>
                            Total Records
                        </span>

                        <strong>
                            128
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        📝
                    </div>

                    <div class="stat-info">

                        <span>
                            Pending Requests
                        </span>

                        <strong>
                            08
                        </strong>

                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-icon">
                        ✓
                    </div>

                    <div class="stat-info">

                        <span>
                            Completed
                        </span>

                        <strong>
                            96
                        </strong>

                    </div>

                </div>


            </div>


            <!-- DASHBOARD GRID -->

            <div class="dashboard-grid">


                <!-- LEFT COLUMN -->

                <div>


                    <!-- QUICK ACCESS -->

                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                Quick Access
                            </h3>

                            <span>
                                Frequently used
                            </span>

                        </div>


                        <div class="panel-body">

                            <div class="quick-actions">


                                <a href="#" class="quick-action">

                                    <div class="quick-action-icon">
                                        📋
                                    </div>

                                    <strong>
                                        Manage Records
                                    </strong>

                                    <small>
                                        View and manage government records
                                    </small>

                                </a>


                                <a href="#" class="quick-action">

                                    <div class="quick-action-icon">
                                        👥
                                    </div>

                                    <strong>
                                        Manage Users
                                    </strong>

                                    <small>
                                        View registered portal users
                                    </small>

                                </a>


                                <a href="#" class="quick-action">

                                    <div class="quick-action-icon">
                                        📊
                                    </div>

                                    <strong>
                                        Generate Reports
                                    </strong>

                                    <small>
                                        View and generate system reports
                                    </small>

                                </a>


                                <a href="#" class="quick-action">

                                    <div class="quick-action-icon">
                                        ⚙
                                    </div>

                                    <strong>
                                        System Settings
                                    </strong>

                                    <small>
                                        Configure portal settings
                                    </small>

                                </a>


                            </div>

                        </div>

                    </div>


                    <!-- RECENT ACTIVITY -->

                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                Recent Activity
                            </h3>

                            <span>
                                Latest updates
                            </span>

                        </div>


                        <div class="panel-body">


                            <div class="activity">

                                <div class="activity-icon">
                                    👤
                                </div>

                                <div class="activity-text">

                                    <strong>
                                        User account logged in
                                    </strong>

                                    <span>
                                        Today · Just now
                                    </span>

                                </div>

                            </div>


                            <div class="activity">

                                <div class="activity-icon">
                                    📄
                                </div>

                                <div class="activity-text">

                                    <strong>
                                        New record added to the system
                                    </strong>

                                    <span>
                                        Today · 10 minutes ago
                                    </span>

                                </div>

                            </div>


                            <div class="activity">

                                <div class="activity-icon">
                                    ✓
                                </div>

                                <div class="activity-text">

                                    <strong>
                                        Request successfully processed
                                    </strong>

                                    <span>
                                        Today · 30 minutes ago
                                    </span>

                                </div>

                            </div>


                        </div>

                    </div>


                </div>


                <!-- RIGHT COLUMN -->

                <div>


                    <!-- ANNOUNCEMENTS -->

                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                Announcements
                            </h3>

                            <span>
                                City Updates
                            </span>

                        </div>


                        <div class="panel-body">


                            <div class="announcement">

                                <strong>
                                    Government Office Advisory
                                </strong>

                                <p>
                                    Please check the latest announcements
                                    and schedules from the city government.
                                </p>

                            </div>


                            <div class="announcement">

                                <strong>
                                    Portal System Update
                                </strong>

                                <p>
                                    The online government portal has been
                                    updated with improved management features.
                                </p>

                            </div>


                            <div class="announcement">

                                <strong>
                                    Public Service Information
                                </strong>

                                <p>
                                    Citizens may use this portal to access
                                    available online government services.
                                </p>

                            </div>


                        </div>

                    </div>


                    <!-- ACCOUNT -->

                    <div class="panel">

                        <div class="panel-header">

                            <h3>
                                My Account
                            </h3>

                        </div>


                        <div class="panel-body">

                            <div style="
                                display:flex;
                                align-items:center;
                                gap:12px;
                            ">

                                <div
                                    class="user-avatar"
                                    style="
                                        width:45px;
                                        height:45px;
                                        font-size:16px;
                                    "
                                >
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>


                                <div>

                                    <strong style="
                                        font-size:12px;
                                        color:#333;
                                    ">
                                        {{ auth()->user()->name }}
                                    </strong>

                                    <div style="
                                        font-size:9px;
                                        color:#999;
                                        margin-top:3px;
                                    ">
                                        {{ auth()->user()->email }}
                                    </div>

                                </div>

                            </div>


                            <a
                                href="#"
                                class="profile-button"
                            >
                                View Profile
                            </a>


                        </div>

                    </div>


                </div>


            </div>


        </div>


        <!-- FOOTER -->

        <div class="footer">

            <strong>
                Sorsogon City Government
            </strong>

            <br>

            © {{ date('Y') }} All Rights Reserved.

            <br>

            <span style="font-size:8px;">
                Sorsogon City Government Online Portal
            </span>

        </div>


    </main>

</x-app-layout>