<x-guest-layout>

    <style>

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #333;
        }

        .government-page {
            min-height: 100vh;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 15px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* =========================
           GOVERNMENT HEADER
        ========================= */

        .government-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .city-logo {
            width: 200px;
            height: 200px;
            object-fit: contain;
            margin-bottom: 10px;
            margin-left: 110px;
           
        }

        .city-name {
            font-size: 25px;
            font-weight: 700;
            color: #174a7c;
            margin: 0;
        }

        .city-government {
            font-size: 14px;
            color: #666;
            margin-top: 3px;
        }

        .portal-name {
            font-size: 13px;
            color: #888;
            margin-top: 10px;
        }

        /* =========================
           LOGIN CARD
        ========================= */

        .login-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .login-heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .login-heading h2 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .login-heading p {
            font-size: 13px;
            color: #777;
            margin-top: 6px;
        }

        /* =========================
           FORM
        ========================= */

        .login-field {
            margin-bottom: 18px;
        }

        .login-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #555;
            margin-bottom: 6px;
        }

        .login-input {
            width: 100%;
            height: 43px;
            box-sizing: border-box;

            border: 1px solid #ced4da;
            border-radius: 5px;

            padding: 8px 12px;
            font-size: 14px;

            background: white;
            color: #333;

            outline: none;
        }

        .login-input:focus {
            border-color: #2878b5;
            box-shadow: 0 0 0 3px rgba(40, 120, 181, 0.12);
        }

        /* =========================
           PASSWORD
        ========================= */

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .login-input {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;

            transform: translateY(-50%);

            border: none;
            background: transparent;

            color: #777;

            cursor: pointer;
            font-size: 16px;
        }

        /* =========================
           REMEMBER + LOGIN
        ========================= */

        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-top: 22px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 7px;

            font-size: 13px;
            color: #666;
        }

        .remember-checkbox {
            width: 15px;
            height: 15px;
        }

        .login-button {
            border: none;
            border-radius: 5px;

            background: #2878b5;
            color: white;

            padding: 9px 22px;

            font-size: 14px;
            font-weight: 500;

            cursor: pointer;
        }

        .login-button:hover {
            background: #216696;
        }

        /* =========================
           LINKS
        ========================= */

        .forgot-password {
            display: block;

            margin-top: 20px;

            font-size: 13px;
            color: #2878b5;

            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .home-link {
            display: block;

            text-align: center;

            margin-top: 20px;

            font-size: 13px;
            color: #666;

            text-decoration: none;
        }

        .home-link:hover {
            color: #2878b5;
        }

        /* =========================
           FOOTER
        ========================= */

        .government-footer {
            text-align: center;

            margin-top: 22px;

            font-size: 12px;
            color: #888;
        }

        .government-footer a {
            color: #2878b5;
            text-decoration: none;
        }

        .government-footer a:hover {
            text-decoration: underline;
        }

        /* =========================
           ERRORS
        ========================= */

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .status-message {
            color: #198754;
            font-size: 13px;
            margin-bottom: 15px;
        }

    </style>


    <div class="login-page">

        <div class="login-wrapper">


            <!-- =================================
                 GOVERNMENT HEADER
            ================================== -->

            <div class="government-header">

                <!-- Your logo -->
                <img
                    src="{{ asset('images/Sorsogon_City_Seal.png') }}"
                    alt="Sorsogon City Government Logo"
                    class="city-logo"
                >

                <h1 class="city-name">
                    Sorsogon City
                </h1>

                <div class="city-government">
                    City Government
                </div>

                <div class="portal-name">
                    Online Government Portal
                </div>

            </div>


            <!-- =================================
                 LOGIN CARD
            ================================== -->

            <div class="login-card">


                <div class="login-heading">

                    <h2>
                        Welcome
                    </h2>

                    <p>
                        Sign in to access the government portal
                    </p>

                </div>


                <!-- Session Status -->

                <x-auth-session-status
                    class="status-message"
                    :status="session('status')"
                />


                <form method="POST" action="{{ route('login') }}">

                    @csrf


                    <!-- EMAIL -->

                    <div class="login-field">

                        <label
                            for="email"
                            class="login-label"
                        >
                            Email Address
                        </label>

                        <input
                            id="email"
                            class="login-input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email"
                        >

                        @if ($errors->get('email'))

                            <div class="error-message">
                                {{ $errors->first('email') }}
                            </div>

                        @endif

                    </div>


                    <!-- PASSWORD -->

                    <div class="login-field">

                        <label
                            for="password"
                            class="login-label"
                        >
                            Password
                        </label>

                        <div class="password-wrapper">

                            <input
                                id="password"
                                class="login-input"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Enter your password"
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword()"
                            >
                                👁
                            </button>

                        </div>

                        @if ($errors->get('password'))

                            <div class="error-message">
                                {{ $errors->first('password') }}
                            </div>

                        @endif

                    </div>


                    <!-- REMEMBER + LOGIN -->

                    <div class="login-options">

                        <label
                            for="remember_me"
                            class="remember-label"
                        >

                            <input
                                id="remember_me"
                                type="checkbox"
                                class="remember-checkbox"
                                name="remember"
                            >

                            <span>
                                Remember Me
                            </span>

                        </label>


                        <button
                            type="submit"
                            class="login-button"
                        >
                            Log In
                        </button>

                    </div>


                    <!-- FORGOT PASSWORD -->

                    @if (Route::has('password.request'))

                        <a
                            class="forgot-password"
                            href="{{ route('password.request') }}"
                        >
                            Forgot your password?
                        </a>

                    @endif

                </form>


            </div>


            <!-- HOME -->

            <a
                href="{{ url('/') }}"
                class="home-link"
            >
                ← Back to Sorsogon City Government Portal
            </a>


            <!-- FOOTER -->

            <div class="government-footer">

                <div>
                    Sorsogon City Government
                </div>

                <div>
                    © {{ date('Y') }} All Rights Reserved
                </div>

                <div style="margin-top: 5px;">
                    <a href="#">
                        Privacy Policy
                    </a>
                </div>

            </div>


        </div>

    </div>


    <!-- PASSWORD TOGGLE -->

    <script>

        function togglePassword() {

            const passwordInput =
                document.getElementById('password');

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';

            } else {

                passwordInput.type = 'password';

            }

        }

    </script>

</x-guest-layout>