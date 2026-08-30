<x-app-layout>

    <style>

        body {
            background: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
        }

        .profile-container {
            max-width: 900px;
            margin: auto;
            padding: 30px 20px;
        }

        .profile-header {
            background: white;
            border-left: 5px solid #174a7c;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,.05);
        }

        .profile-header h1 {
            margin: 0;
            color: #174a7c;
            font-size: 26px;
        }

        .profile-header p {
            margin-top: 6px;
            color: #777;
        }

        .profile-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 7px;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }

        .profile-card h2 {
            margin-top: 0;
            color: #174a7c;
            font-size: 19px;
        }

        .profile-card p.description {
            color: #777;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #444;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #2878b5;
            box-shadow: 0 0 0 2px rgba(40,120,181,.10);
        }

        .save-button {
            background: #2878b5;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .save-button:hover {
            background: #216696;
        }

        .success-message {
            background: #eaf7ee;
            color: #287a42;
            border: 1px solid #c6e6d0;
            padding: 10px 14px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .error-message {
            color: #b42318;
            font-size: 12px;
            margin-top: 5px;
        }

        /* PROFILE PICTURE */

        .profile-picture-area {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .profile-picture {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e0e5e9;
        }

        .profile-placeholder {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #174a7c;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
        }

        .profile-picture-info {
            color: #777;
            font-size: 12px;
        }

        @media (max-width: 600px) {

            .profile-picture-area {
                flex-direction: column;
                align-items: flex-start;
            }

        }

    </style>


    <div class="profile-container">

        {{-- HEADER --}}

        <div class="profile-header">

            <h1>
                Profile Settings
            </h1>

            <p>
                Manage your account information and profile picture.
            </p>

        </div>


        {{-- SUCCESS MESSAGE --}}

        @if (session('status'))

            <div class="success-message">
                {{ session('status') }}
            </div>

        @endif


        {{-- VALIDATION ERRORS --}}

        @if ($errors->any())

            <div class="error-message" style="margin-bottom: 20px;">

                Please correct the errors below.

            </div>

        @endif


        {{-- =========================
             PROFILE INFORMATION
        ========================== --}}

        <div class="profile-card">

            <h2>
                Profile Information
            </h2>

            <p class="description">
                Update your name, email address, and profile picture.
            </p>


            <form
                method="POST"
                action="{{ route('profile.update') }}"
                enctype="multipart/form-data"
            >

                @csrf

                @method('PATCH')


                {{-- PROFILE PICTURE --}}

                <div class="profile-picture-area">

                    @if ($user->profile_picture)

                        <img
                            src="{{ asset('storage/' . $user->profile_picture) }}"
                            alt="Profile Picture"
                            class="profile-picture"
                        >

                    @else

                        <div class="profile-placeholder">

                            {{ strtoupper(substr($user->name, 0, 1)) }}

                        </div>

                    @endif


                    <div>

                        <label class="form-label">
                            Profile Picture
                        </label>

                        <input
                            type="file"
                            name="profile_picture"
                            accept="image/png,image/jpeg,image/webp"
                        >

                        <div class="profile-picture-info">
                            JPG, PNG or WEBP. Maximum 2MB.
                        </div>

                        @error('profile_picture')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>


                {{-- NAME --}}

                <div class="form-group">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-input"
                        value="{{ old('name', $user->name) }}"
                        required
                    >

                    @error('name')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- EMAIL --}}

                <div class="form-group">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email', $user->email) }}"
                        required
                    >

                    @error('email')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                <button
                    type="submit"
                    class="save-button"
                >
                    Save Profile
                </button>

            </form>

        </div>


        {{-- =========================
             CHANGE PASSWORD
        ========================== --}}

        <div class="profile-card">

            <h2>
                Change Password
            </h2>

            <p class="description">
                Change your account password.
            </p>


            <form
                method="POST"
                action="{{ route('profile.password') }}"
            >

                @csrf

                @method('PATCH')


                {{-- CURRENT PASSWORD --}}

                <div class="form-group">

                    <label
                        for="current_password"
                        class="form-label"
                    >
                        Current Password
                    </label>

                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="form-input"
                        required
                    >

                    @error('current_password')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- NEW PASSWORD --}}

                <div class="form-group">

                    <label
                        for="password"
                        class="form-label"
                    >
                        New Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        required
                    >

                    @error('password')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- CONFIRM PASSWORD --}}

                <div class="form-group">

                    <label
                        for="password_confirmation"
                        class="form-label"
                    >
                        Confirm New Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="save-button"
                >
                    Change Password
                </button>

            </form>

        </div>

    </div>

</x-app-layout>