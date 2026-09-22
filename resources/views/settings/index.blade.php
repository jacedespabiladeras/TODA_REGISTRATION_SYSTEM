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
                    aria-label="Toggle navigation menu"
                >
                    <i class="bi bi-list"></i>
                </button>

                <h1 class="page-title">
                    Settings
                </h1>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small d-none d-md-block">
                    {{ now()->format('d M Y') }}
                </span>

                <span class="badge bg-primary">
                    {{ auth()->user()->role?->name === 'admin' ? 'Administrator' : 'Staff' }}
                </span>

                <x-user-menu />
            </div>
        </header>


        {{-- MAIN CONTENT --}}
        <main class="content" x-data="{
            activeTab: '{{ old('active_tab', $activeTab ?? 'profile') }}',
            selectedTheme: '{{ old('theme', auth()->user()->theme ?? 'light') }}',
            photoPreview: null,
            previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.photoPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            toggleThemePreview(theme) {
                this.selectedTheme = theme;
                if (window.setAppTheme) {
                    window.setAppTheme(theme);
                } else {
                    document.documentElement.setAttribute('data-theme', theme);
                    if (document.body) document.body.setAttribute('data-theme', theme);
                    if (theme === 'dark') {
                        document.documentElement.classList.add('dark');
                        if (document.body) document.body.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        if (document.body) document.body.classList.remove('dark');
                    }
                }
            }
        }">
            <div class="container-fluid" style="max-width: 1050px;">

                {{-- FLASH STATUS ALERT --}}
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                        <div>
                            {{ session('status') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                        <div>
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center mb-1">
                            <i class="bi bi-x-circle-fill fs-5 me-2"></i>
                            <strong>Please correct the following errors:</strong>
                        </div>
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif


                {{-- PAGE HEADER BANNER --}}
                <div class="settings-banner mb-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div>
                            <h2 class="settings-banner-title">
                                <i class="bi bi-gear-wide-connected me-2"></i>Account & System Settings
                            </h2>
                            <p class="settings-banner-desc mb-0">
                                Manage your personal profile, credentials, and system interface preferences.
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="user-pill-badge">
                                <i class="bi bi-shield-check me-1"></i>
                                {{ auth()->user()->role?->name === 'admin' ? 'Administrator' : 'Staff Member' }}
                            </span>
                        </div>
                    </div>
                </div>


                {{-- TAB NAVIGATION --}}
                <div class="settings-tabs-wrapper mb-4">
                    <div class="settings-tabs-nav">
                        <button
                            type="button"
                            class="settings-tab-btn"
                            :class="{ 'active': activeTab === 'profile' }"
                            @click="activeTab = 'profile'"
                        >
                            <i class="bi bi-person-bounding-box me-2"></i>
                            <span>Profile</span>
                        </button>

                        <button
                            type="button"
                            class="settings-tab-btn"
                            :class="{ 'active': activeTab === 'preferences' }"
                            @click="activeTab = 'preferences'"
                        >
                            <i class="bi bi-palette me-2"></i>
                            <span>Preferences</span>
                        </button>
                    </div>
                </div>


                {{-- =========================================================
                     TAB 1: PROFILE
                ========================================================== --}}
                <div x-show="activeTab === 'profile'" x-cloak class="settings-tab-content">

                    {{-- 1. PROFILE PHOTO SECTION --}}
                    <div class="card settings-card mb-4">
                        <div class="card-header settings-card-header">
                            <h3 class="settings-card-title">
                                <i class="bi bi-camera me-2 text-primary"></i>Profile Photo
                            </h3>
                            <p class="settings-card-subtitle">
                                Your profile photo is displayed in the dashboard header, user menu, and sidebar.
                            </p>
                        </div>
                        <div class="card-body p-4">
                            <form
                                method="POST"
                                action="{{ route('settings.profile.update') }}"
                                enctype="multipart/form-data"
                                id="profilePhotoForm"
                            >
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="name" value="{{ old('name', $user->name) }}">
                                <input type="hidden" name="email" value="{{ old('email', $user->email) }}">
                                <input type="hidden" name="active_tab" value="profile">

                                <div class="d-flex flex-column flex-sm-row align-items-center gap-4">
                                    {{-- Photo Preview Box --}}
                                    <div class="position-relative">
                                        <template x-if="photoPreview">
                                            <img
                                                :src="photoPreview"
                                                alt="New Profile Preview"
                                                class="profile-photo-img"
                                            >
                                        </template>

                                        <template x-if="!photoPreview">
                                            <div>
                                                @if ($user->profile_picture)
                                                    <img
                                                        src="{{ asset('storage/' . $user->profile_picture) }}"
                                                        alt="{{ $user->name }}"
                                                        class="profile-photo-img"
                                                    >
                                                @else
                                                    <div class="profile-photo-placeholder">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </template>

                                        <span class="photo-status-badge" title="Active Account Photo">
                                            <i class="bi bi-check"></i>
                                        </span>
                                    </div>

                                    {{-- Upload Actions --}}
                                    <div class="flex-grow-1 text-center text-sm-start">
                                        <label for="profile_picture_input" class="form-label fw-bold mb-1">
                                            Change Profile Photo
                                        </label>
                                        <p class="text-muted small mb-3">
                                            Upload a JPG, JPEG, PNG, or WEBP image. Maximum allowed file size is 2MB.
                                        </p>

                                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-sm-start gap-2">
                                            <label class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2" for="profile_picture_input" style="cursor: pointer;">
                                                <i class="bi bi-upload"></i>
                                                <span>Choose File</span>
                                            </label>

                                            <input
                                                type="file"
                                                id="profile_picture_input"
                                                name="profile_picture"
                                                class="d-none"
                                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                                @change="previewImage($event)"
                                            >

                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-sm d-inline-flex align-items-center gap-2"
                                                x-show="photoPreview"
                                            >
                                                <i class="bi bi-check2-circle"></i>
                                                <span>Save Photo</span>
                                            </button>

                                            @if ($user->profile_picture)
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-2"
                                                    onclick="if(confirm('Are you sure you want to remove your profile photo? A default avatar will be used instead.')) { document.getElementById('removePhotoForm').submit(); }"
                                                >
                                                    <i class="bi bi-trash3"></i>
                                                    <span>Remove Photo</span>
                                                </button>
                                            @endif
                                        </div>

                                        @error('profile_picture')
                                            <div class="text-danger small mt-2">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </form>

                            {{-- Hidden Form for Removing Photo --}}
                            @if ($user->profile_picture)
                                <form
                                    id="removePhotoForm"
                                    method="POST"
                                    action="{{ route('settings.profile.photo.remove') }}"
                                    class="d-none"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="active_tab" value="profile">
                                </form>
                            @endif
                        </div>
                    </div>


                    {{-- 2. ACCOUNT INFORMATION SECTION --}}
                    <div class="card settings-card mb-4">
                        <div class="card-header settings-card-header">
                            <h3 class="settings-card-title">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>Account Information
                            </h3>
                            <p class="settings-card-subtitle">
                                Update your personal account details. Role and account permissions are managed by system administrators.
                            </p>
                        </div>
                        <div class="card-body p-4">
                            <form
                                method="POST"
                                action="{{ route('settings.profile.update') }}"
                            >
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="active_tab" value="profile">

                                <div class="row g-3">
                                    {{-- User ID (Read-only) --}}
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">
                                            User ID <span class="badge bg-light text-secondary border ms-1"><i class="bi bi-lock-fill"></i> Read-only</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control bg-light"
                                            value="USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}"
                                            readonly
                                            tabindex="-1"
                                        >
                                    </div>

                                    {{-- Assigned Role (Read-only) --}}
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">
                                            Assigned Role <span class="badge bg-light text-secondary border ms-1"><i class="bi bi-shield-lock-fill"></i> Managed</span>
                                        </label>
                                        <div class="form-control bg-light d-flex align-items-center justify-content-between">
                                            <span class="fw-semibold text-dark">
                                                {{ $user->role?->name === 'admin' ? 'Administrator' : 'Staff Member' }}
                                            </span>
                                            <span class="badge bg-primary text-white">
                                                {{ strtoupper($user->role?->name ?? 'USER') }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Full Name --}}
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            Full Name <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input
                                                type="text"
                                                id="name"
                                                name="name"
                                                class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                                value="{{ old('name', $user->name) }}"
                                                placeholder="Enter your full name"
                                                required
                                            >
                                        </div>
                                        @error('name')
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Email Address --}}
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input
                                                type="email"
                                                id="email"
                                                name="email"
                                                class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                                value="{{ old('email', $user->email) }}"
                                                placeholder="name@example.com"
                                                required
                                            >
                                        </div>
                                        @error('email')
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4 pt-2 border-top d-flex justify-content-end">
                                    <button
                                        type="submit"
                                        class="btn btn-primary d-inline-flex align-items-center gap-2 px-4"
                                    >
                                        <i class="bi bi-save"></i>
                                        <span>Save Profile</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>


                    {{-- 3. SECURITY & CHANGE PASSWORD SECTION --}}
                    <div class="card settings-card mb-4" x-data="{
                        showCurrent: false,
                        showNew: false,
                        showConfirm: false
                    }">
                        <div class="card-header settings-card-header">
                            <h3 class="settings-card-title">
                                <i class="bi bi-key-fill me-2 text-primary"></i>Security & Password
                            </h3>
                            <p class="settings-card-subtitle">
                                Ensure your account uses a strong and confidential password to prevent unauthorized access.
                            </p>
                        </div>
                        <div class="card-body p-4">
                            <form
                                method="POST"
                                action="{{ route('settings.password.update') }}"
                            >
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="active_tab" value="profile">

                                <div class="row g-3">
                                    {{-- Current Password --}}
                                    <div class="col-md-12">
                                        <label for="current_password" class="form-label fw-semibold">
                                            Current Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                            <input
                                                :type="showCurrent ? 'text' : 'password'"
                                                id="current_password"
                                                name="current_password"
                                                class="form-control border-start-0 border-end-0 ps-0 @error('current_password') is-invalid @enderror"
                                                placeholder="Enter your current password"
                                                required
                                                autocomplete="current-password"
                                            >
                                            <button
                                                type="button"
                                                class="input-group-text bg-white border-start-0 text-muted"
                                                @click="showCurrent = !showCurrent"
                                                style="cursor: pointer;"
                                                title="Toggle password visibility"
                                            >
                                                <i class="bi" :class="showCurrent ? 'bi-eye-slash' : 'bi-eye'"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- New Password --}}
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-semibold">
                                            New Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted">
                                                <i class="bi bi-lock"></i>
                                            </span>
                                            <input
                                                :type="showNew ? 'text' : 'password'"
                                                id="password"
                                                name="password"
                                                class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror"
                                                placeholder="At least 8 characters"
                                                required
                                                autocomplete="new-password"
                                            >
                                            <button
                                                type="button"
                                                class="input-group-text bg-white border-start-0 text-muted"
                                                @click="showNew = !showNew"
                                                style="cursor: pointer;"
                                                title="Toggle password visibility"
                                            >
                                                <i class="bi" :class="showNew ? 'bi-eye-slash' : 'bi-eye'"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="text-danger small mt-1">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label fw-semibold">
                                            Confirm New Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted">
                                                <i class="bi bi-lock-check"></i>
                                            </span>
                                            <input
                                                :type="showConfirm ? 'text' : 'password'"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                class="form-control border-start-0 border-end-0 ps-0"
                                                placeholder="Repeat new password"
                                                required
                                                autocomplete="new-password"
                                            >
                                            <button
                                                type="button"
                                                class="input-group-text bg-white border-start-0 text-muted"
                                                @click="showConfirm = !showConfirm"
                                                style="cursor: pointer;"
                                                title="Toggle password visibility"
                                            >
                                                <i class="bi" :class="showConfirm ? 'bi-eye-slash' : 'bi-eye'"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-2 border-top d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                                    <span class="text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Password must be at least 8 characters long.
                                    </span>
                                    <button
                                        type="submit"
                                        class="btn btn-primary d-inline-flex align-items-center gap-2 px-4"
                                    >
                                        <i class="bi bi-shield-check"></i>
                                        <span>Change Password</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>


                {{-- =========================================================
                     TAB 2: PREFERENCES
                ========================================================== --}}
                <div x-show="activeTab === 'preferences'" x-cloak class="settings-tab-content">

                    <div class="card settings-card mb-4">
                        <div class="card-header settings-card-header">
                            <h3 class="settings-card-title">
                                <i class="bi bi-palette-fill me-2 text-primary"></i>System Appearance
                            </h3>
                            <p class="settings-card-subtitle">
                                Customize the interface theme to suit your work environment. Your choice is saved specifically to your account.
                            </p>
                        </div>
                        <div class="card-body p-4">
                            <form
                                method="POST"
                                action="{{ route('settings.preferences.update') }}"
                            >
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="active_tab" value="preferences">

                                <div class="row g-4 mb-4">
                                    {{-- Light Mode Card --}}
                                    <div class="col-md-6">
                                        <div
                                            class="theme-select-card"
                                            :class="{ 'selected': selectedTheme === 'light' }"
                                            @click="toggleThemePreview('light')"
                                        >
                                            <div class="theme-select-header">
                                                <div class="form-check m-0">
                                                    <input
                                                        class="form-check-input"
                                                        type="radio"
                                                        name="theme"
                                                        id="theme_light"
                                                        value="light"
                                                        x-model="selectedTheme"
                                                        @change="toggleThemePreview('light')"
                                                    >
                                                    <label class="form-check-label fw-bold ms-2" for="theme_light" style="cursor: pointer;">
                                                        Light Mode
                                                    </label>
                                                </div>
                                                <span class="badge bg-secondary-subtle text-secondary border">Default</span>
                                            </div>

                                            {{-- Mini Preview Diagram --}}
                                            <div class="theme-preview-box light-preview mt-3">
                                                <div class="theme-preview-topbar"></div>
                                                <div class="theme-preview-body">
                                                    <div class="theme-preview-sidebar"></div>
                                                    <div class="theme-preview-content">
                                                        <div class="theme-preview-card"></div>
                                                        <div class="theme-preview-card short"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="theme-select-desc mt-3 mb-0">
                                                Clean, government-standard light palette with navy accents, crisp white cards, and clear typography.
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Dark Mode Card --}}
                                    <div class="col-md-6">
                                        <div
                                            class="theme-select-card"
                                            :class="{ 'selected': selectedTheme === 'dark' }"
                                            @click="toggleThemePreview('dark')"
                                        >
                                            <div class="theme-select-header">
                                                <div class="form-check m-0">
                                                    <input
                                                        class="form-check-input"
                                                        type="radio"
                                                        name="theme"
                                                        id="theme_dark"
                                                        value="dark"
                                                        x-model="selectedTheme"
                                                        @change="toggleThemePreview('dark')"
                                                    >
                                                    <label class="form-check-label fw-bold ms-2" for="theme_dark" style="cursor: pointer;">
                                                        Dark Mode
                                                    </label>
                                                </div>
                                                <span class="badge bg-primary-subtle text-primary border">High Contrast</span>
                                            </div>

                                            {{-- Mini Preview Diagram --}}
                                            <div class="theme-preview-box dark-preview mt-3">
                                                <div class="theme-preview-topbar"></div>
                                                <div class="theme-preview-body">
                                                    <div class="theme-preview-sidebar"></div>
                                                    <div class="theme-preview-content">
                                                        <div class="theme-preview-card"></div>
                                                        <div class="theme-preview-card short"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="theme-select-desc mt-3 mb-0">
                                                Professional slate-charcoal interface that reduces eye strain in low-light settings while maintaining high contrast.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3 mb-4">
                                    <i class="bi bi-info-circle text-primary fs-4"></i>
                                    <div class="small text-muted">
                                        <strong>Live Preview Enabled:</strong> Clicking any theme applies an instant preview. Click <strong>"Save Preferences"</strong> to persist this choice across your sessions and devices.
                                    </div>
                                </div>

                                <div class="pt-2 border-top d-flex justify-content-end">
                                    <button
                                        type="submit"
                                        class="btn btn-primary d-inline-flex align-items-center gap-2 px-4"
                                    >
                                        <i class="bi bi-check2-circle"></i>
                                        <span>Save Preferences</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </main>

    </div>


    {{-- =========================================================
         SETTINGS PAGE STYLES
    ========================================================== --}}
    <style>
        [x-cloak] { display: none !important; }

        /* SETTINGS BANNER */
        .settings-banner {
            background: linear-gradient(135deg, #0b2342 0%, #174a7c 100%);
            color: #ffffff;
            padding: 24px 28px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(11, 35, 66, 0.12);
        }

        .settings-banner-title {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .settings-banner-desc {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.82);
        }

        .user-pill-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        /* TABS NAVIGATION */
        .settings-tabs-wrapper {
            background: #ffffff;
            border-radius: 8px;
            padding: 6px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .settings-tabs-nav {
            display: flex;
            gap: 6px;
        }

        .settings-tab-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 22px;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .settings-tab-btn:hover {
            color: #0b2342;
            background: #f1f5f9;
        }

        .settings-tab-btn.active {
            color: #ffffff;
            background: #0b2342;
            box-shadow: 0 2px 6px rgba(11, 35, 66, 0.2);
        }

        /* SETTINGS CARDS */
        .settings-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .settings-card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 18px 24px;
        }

        .settings-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #0b2342;
            margin: 0 0 3px;
        }

        .settings-card-subtitle {
            font-size: 12.5px;
            color: #64748b;
            margin: 0;
        }

        /* PROFILE PHOTO AVATARS */
        .profile-photo-img {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #0b2342;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .profile-photo-placeholder {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: #0b2342;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            font-weight: 700;
            border: 3px solid #e2e8f0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .photo-status-badge {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #10b981;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
            border: 2px solid #ffffff;
        }

        /* THEME SELECT CARDS */
        .theme-select-card {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
        }

        .theme-select-card:hover {
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.06);
        }

        .theme-select-card.selected {
            border-color: #0b2342;
            background: #f8fafc;
            box-shadow: 0 0 0 3px rgba(11, 35, 66, 0.12);
        }

        .theme-select-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .theme-select-desc {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.4;
        }

        /* THEME MINI PREVIEW DIAGRAMS */
        .theme-preview-box {
            height: 100px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .light-preview {
            background: #f1f5f9;
        }
        .light-preview .theme-preview-topbar {
            height: 20px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }
        .light-preview .theme-preview-body {
            display: flex;
            flex: 1;
        }
        .light-preview .theme-preview-sidebar {
            width: 32px;
            background: #e9ecef;
            border-right: 1px solid #dee2e6;
        }
        .light-preview .theme-preview-content {
            flex: 1;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .light-preview .theme-preview-card {
            height: 24px;
            background: #ffffff;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .light-preview .theme-preview-card.short {
            height: 16px;
            width: 70%;
        }

        .dark-preview {
            background: #0f172a;
        }
        .dark-preview .theme-preview-topbar {
            height: 20px;
            background: #1e293b;
            border-bottom: 1px solid #334155;
        }
        .dark-preview .theme-preview-body {
            display: flex;
            flex: 1;
        }
        .dark-preview .theme-preview-sidebar {
            width: 32px;
            background: #111827;
            border-right: 1px solid #1f2937;
        }
        .dark-preview .theme-preview-content {
            flex: 1;
            padding: 8px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .dark-preview .theme-preview-card {
            height: 24px;
            background: #1e293b;
            border-radius: 4px;
            border: 1px solid #334155;
        }
        .dark-preview .theme-preview-card.short {
            height: 16px;
            width: 70%;
        }

        /* DARK MODE OVERRIDES FOR SETTINGS PAGE */
        [data-theme="dark"] .settings-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }

        [data-theme="dark"] .settings-tabs-wrapper {
            background: #1e293b;
            border-color: #334155;
        }

        [data-theme="dark"] .settings-tab-btn {
            color: #94a3b8;
        }

        [data-theme="dark"] .settings-tab-btn:hover {
            color: #60a5fa;
            background: #2d3d54;
        }

        [data-theme="dark"] .settings-tab-btn.active {
            color: #ffffff;
            background: #2563eb;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
        }

        [data-theme="dark"] .settings-card {
            background: #1e293b;
            border-color: #334155;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        [data-theme="dark"] .settings-card-header {
            background: #162032;
            border-color: #334155;
        }

        [data-theme="dark"] .settings-card-title {
            color: #60a5fa;
        }

        [data-theme="dark"] .settings-card-subtitle {
            color: #94a3b8;
        }

        [data-theme="dark"] .profile-photo-img {
            border-color: #3b82f6;
        }

        [data-theme="dark"] .profile-photo-placeholder {
            background: #1e3a8a;
            border-color: #334155;
        }

        [data-theme="dark"] .theme-select-card {
            background: #1e293b;
            border-color: #334155;
        }

        [data-theme="dark"] .theme-select-card:hover {
            border-color: #60a5fa;
        }

        [data-theme="dark"] .theme-select-card.selected {
            border-color: #3b82f6;
            background: #162032;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        [data-theme="dark"] .theme-select-desc {
            color: #94a3b8;
        }

        [data-theme="dark"] .bg-light {
            background-color: #162032 !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .input-group-text {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .form-control {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }

        [data-theme="dark"] .form-control:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
        }

        [data-theme="dark"] .border-top {
            border-color: #334155 !important;
        }
    </style>

</x-app-layout>