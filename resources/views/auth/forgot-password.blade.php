<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Forgot Password | CyberReadyAI</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #5b2dcc;
            --primary-dark: #4520a5;
            --primary-light: #efe9ff;

            --text: #17203b;
            --muted: #727b91;

            --border: #e2e5ee;
            --background: #f7f7fb;
            --white: #ffffff;

            --danger-bg: #fff1f1;
            --danger-border: #ffd2d2;
            --danger-text: #b42318;

            --success-bg: #edfff5;
            --success-border: #c8f0d9;
            --success-text: #087443;

            --shadow: 0 24px 60px rgba(23, 32, 59, 0.10);
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;

            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            color: var(--text);

            background:
                radial-gradient(
                    circle at 8% 15%,
                    rgba(91, 45, 204, 0.10),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 92% 85%,
                    rgba(112, 70, 223, 0.08),
                    transparent 30%
                ),
                var(--background);

            -webkit-font-smoothing: antialiased;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        /* =====================================================
           PAGE
        ====================================================== */

        .auth-page {
            min-height: 100vh;

            display: grid;
            grid-template-columns:
                minmax(0, 1.05fr)
                minmax(420px, 0.95fr);
        }

        /* =====================================================
           BRAND PANEL
        ====================================================== */

        .brand-panel {
            position: relative;
            overflow: hidden;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            padding:
                52px
                clamp(40px, 7vw, 96px);

            color: var(--white);

            background:
                linear-gradient(
                    145deg,
                    #321487 0%,
                    #5b2dcc 48%,
                    #754de5 100%
                );
        }

        .brand-panel::before {
            content: "";

            position: absolute;

            width: 520px;
            height: 520px;

            top: -245px;
            right: -210px;

            border-radius: 50%;

            border:
                1px solid
                rgba(255, 255, 255, 0.13);
        }

        .brand-panel::after {
            content: "";

            position: absolute;

            width: 420px;
            height: 420px;

            bottom: -240px;
            left: -190px;

            border-radius: 50%;

            border:
                1px solid
                rgba(255, 255, 255, 0.10);
        }

        .brand-panel-content {
            position: relative;
            z-index: 2;
        }

        /* =====================================================
           BRAND
        ====================================================== */

        .brand-link {
            display: inline-flex;

            align-items: center;

            gap: 14px;
        }

        .brand-logo {
            width: 50px;
            height: 50px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            background:
                rgba(255, 255, 255, 0.14);

            border:
                1px solid
                rgba(255, 255, 255, 0.22);

            font-size: 25px;

            box-shadow:
                0 12px 30px
                rgba(0, 0, 0, 0.12);
        }

        .brand-text {
            display: flex;

            flex-direction: column;

            gap: 4px;
        }

        .brand-name {
            font-size: 23px;

            line-height: 1;

            font-weight: 800;

            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 12px;

            color:
                rgba(255, 255, 255, 0.72);
        }

        /* =====================================================
           BRAND MESSAGE
        ====================================================== */

        .brand-message {
            max-width: 560px;

            margin-top: auto;
            margin-bottom: auto;

            padding: 80px 0;
        }

        .eyebrow {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            margin-bottom: 20px;

            padding:
                8px
                12px;

            border-radius: 999px;

            background:
                rgba(255, 255, 255, 0.11);

            border:
                1px solid
                rgba(255, 255, 255, 0.16);

            color:
                rgba(255, 255, 255, 0.86);

            font-size: 12px;

            font-weight: 800;

            letter-spacing: 0.3px;
        }

        .brand-message h1 {
            max-width: 600px;

            margin-bottom: 22px;

            font-size:
                clamp(40px, 4.6vw, 62px);

            line-height: 1.06;

            letter-spacing: -2px;
        }

        .brand-message h1 span {
            color: #ddd2ff;
        }

        .brand-message p {
            max-width: 520px;

            color:
                rgba(255, 255, 255, 0.78);

            font-size: 17px;

            line-height: 1.75;
        }

        /* =====================================================
           TRUST ITEMS
        ====================================================== */

        .trust-list {
            display: grid;

            gap: 14px;

            margin-top: 34px;
        }

        .trust-item {
            display: flex;

            align-items: center;

            gap: 13px;

            color:
                rgba(255, 255, 255, 0.90);

            font-size: 14px;
        }

        .trust-icon {
            width: 36px;
            height: 36px;

            flex: 0 0 36px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background:
                rgba(255, 255, 255, 0.12);

            border:
                1px solid
                rgba(255, 255, 255, 0.10);

            font-size: 16px;
        }

        /* =====================================================
           FOOTER
        ====================================================== */

        .brand-footer {
            position: relative;
            z-index: 2;

            color:
                rgba(255, 255, 255, 0.52);

            font-size: 12px;
        }

        /* =====================================================
           RIGHT PANEL
        ====================================================== */

        .auth-panel {
            display: flex;

            align-items: center;
            justify-content: center;

            min-height: 100vh;

            padding:
                48px
                7%;

            background:
                rgba(255, 255, 255, 0.88);

            backdrop-filter: blur(12px);
        }

        .auth-container {
            width: 100%;

            max-width: 460px;
        }

        /* =====================================================
           MOBILE BRAND
        ====================================================== */

        .mobile-brand {
            display: none;
        }

        /* =====================================================
           CARD
        ====================================================== */

        .auth-card {
            padding: 38px;

            border:
                1px solid
                rgba(226, 229, 238, 0.90);

            border-radius: 22px;

            background:
                rgba(255, 255, 255, 0.96);

            box-shadow: var(--shadow);
        }

        .auth-header {
            margin-bottom: 28px;
        }

        .auth-kicker {
            margin-bottom: 10px;

            color: var(--primary);

            font-size: 12px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.9px;
        }

        .auth-header h1 {
            margin-bottom: 10px;

            font-size: 32px;

            line-height: 1.15;

            letter-spacing: -0.8px;
        }

        .auth-header p {
            color: var(--muted);

            font-size: 15px;

            line-height: 1.65;
        }

        /* =====================================================
           ALERTS
        ====================================================== */

        .alert {
            margin-bottom: 22px;

            padding: 14px 15px;

            border-radius: 11px;

            font-size: 13px;

            line-height: 1.5;
        }

        .alert-success {
            background: var(--success-bg);

            border:
                1px solid
                var(--success-border);

            color: var(--success-text);
        }

        .alert-error {
            background: var(--danger-bg);

            border:
                1px solid
                var(--danger-border);

            color: var(--danger-text);
        }

        .alert ul {
            margin: 0;

            padding-left: 18px;
        }

        /* =====================================================
           FORM
        ====================================================== */

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;

            margin-bottom: 8px;

            color: #303850;

            font-size: 13px;

            font-weight: 700;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;

            left: 16px;
            top: 50%;

            width: 18px;
            height: 18px;

            transform:
                translateY(-50%);

            color: #9198aa;

            pointer-events: none;
        }

        .input-icon svg {
            width: 100%;
            height: 100%;
        }

        .form-input {
            width: 100%;
            height: 54px;

            padding:
                0
                16px
                0
                46px;

            border:
                1px solid
                var(--border);

            border-radius: 12px;

            outline: none;

            background: var(--white);

            color: var(--text);

            font-size: 14px;

            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .form-input::placeholder {
            color: #a0a7b8;
        }

        .form-input:hover {
            border-color: #cfd4e0;
        }

        .form-input:focus {
            border-color: var(--primary);

            box-shadow:
                0 0 0 4px
                rgba(91, 45, 204, 0.10);
        }

        .field-error {
            margin-top: 7px;

            color: #c62828;

            font-size: 12px;
        }

        /* =====================================================
           PRIMARY BUTTON
        ====================================================== */

        .primary-button {
            width: 100%;
            height: 54px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 9px;

            border: 0;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    #7046df
                );

            color: white;

            font-size: 14px;

            font-weight: 800;

            cursor: pointer;

            box-shadow:
                0 13px 28px
                rgba(91, 45, 204, 0.22);

            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .primary-button:hover {
            transform: translateY(-1px);

            box-shadow:
                0 16px 32px
                rgba(91, 45, 204, 0.28);
        }

        .primary-button:active {
            transform: translateY(0);
        }

        /* =====================================================
           BACK TO LOGIN
        ====================================================== */

        .back-login {
            margin-top: 22px;

            padding-top: 20px;

            border-top:
                1px solid
                #edf0f5;

            text-align: center;
        }

        .back-login a {
            display: inline-flex;

            align-items: center;

            gap: 7px;

            color: var(--primary);

            font-size: 13px;

            font-weight: 700;

            transition: color 0.2s ease;
        }

        .back-login a:hover {
            color: var(--primary-dark);
        }

        /* =====================================================
           REGISTER
        ====================================================== */

        .register {
            margin-top: 20px;

            text-align: center;

            color: var(--muted);

            font-size: 13px;
        }

        .register a {
            color: var(--primary);

            font-weight: 800;
        }

        .register a:hover {
            text-decoration: underline;
        }

        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 960px) {

            .auth-page {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                display: none;
            }

            .auth-panel {
                padding:
                    34px
                    22px;
            }

            .mobile-brand {
                display: inline-flex;

                align-items: center;
                justify-content: center;

                gap: 11px;

                margin:
                    0
                    auto
                    28px;
            }

            .mobile-brand .brand-logo {
                width: 44px;
                height: 44px;

                background:
                    var(--primary-light);

                border:
                    1px solid
                    #ddd4fa;

                color: var(--primary);

                box-shadow: none;
            }

            .mobile-brand .brand-name {
                color: var(--primary);
            }

            .mobile-brand .brand-subtitle {
                color: var(--muted);
            }

            .auth-card {
                padding: 30px 24px;

                border-radius: 18px;
            }
        }

        @media (max-width: 520px) {

            .auth-panel {
                padding:
                    24px
                    16px;
            }

            .auth-card {
                padding: 24px 18px;

                border-radius: 16px;

                box-shadow:
                    0 15px 35px
                    rgba(23, 32, 59, 0.08);
            }

            .auth-header h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<div class="auth-page">

    {{-- =========================================================
         BRAND PANEL
    ========================================================== --}}

    <section class="brand-panel">

        <div class="brand-panel-content">

            <a
                href="{{ url('/') }}"
                class="brand-link"
            >
                <div class="brand-logo">
                    🛡️
                </div>

                <div class="brand-text">

                    <div class="brand-name">
                        CyberReadyAI
                    </div>

                    <div class="brand-subtitle">
                        Cybersecurity Awareness Platform
                    </div>

                </div>
            </a>

        </div>


        <div class="brand-message">

            <div class="eyebrow">
                <span>🔐</span>
                Account security
            </div>


            <h1>
                Keep your
                <span>account secure.</span>
            </h1>


            <p>
                Reset your CyberReadyAI password securely
                and regain access to your cybersecurity
                awareness workspace.
            </p>


            <div class="trust-list">

                <div class="trust-item">

                    <div class="trust-icon">
                        🔒
                    </div>

                    <span>
                        Secure password recovery
                    </span>

                </div>


                <div class="trust-item">

                    <div class="trust-icon">
                        ✉️
                    </div>

                    <span>
                        Reset instructions sent to your email
                    </span>

                </div>


                <div class="trust-item">

                    <div class="trust-icon">
                        🛡️
                    </div>

                    <span>
                        Protect your CyberReadyAI account
                    </span>

                </div>

            </div>

        </div>


        <div class="brand-footer">
            © {{ date('Y') }} CyberReadyAI. All rights reserved.
        </div>

    </section>


    {{-- =========================================================
         AUTH PANEL
    ========================================================== --}}

    <main class="auth-panel">

        <div class="auth-container">

            {{-- Mobile brand --}}

            <a
                href="{{ url('/') }}"
                class="mobile-brand"
            >
                <div class="brand-logo">
                    🛡️
                </div>

                <div class="brand-text">

                    <div class="brand-name">
                        CyberReadyAI
                    </div>

                    <div class="brand-subtitle">
                        Cybersecurity Awareness Platform
                    </div>

                </div>

            </a>


            <div class="auth-card">

                {{-- Header --}}

                <div class="auth-header">

                    <div class="auth-kicker">
                        Password recovery
                    </div>

                    <h1>
                        Forgot your password?
                    </h1>

                    <p>
                        Enter the email address associated
                        with your account and we'll send you
                        a password reset link.
                    </p>

                </div>


                {{-- Success message --}}

                @if(session('status'))

                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>

                @endif


                {{-- Validation errors --}}

                @if($errors->any())

                    <div class="alert alert-error">

                        <ul>

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- Password reset form --}}

                <form
                    method="POST"
                    action="{{ route('password.email') }}"
                >
                    @csrf


                    <div class="form-group">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email address
                        </label>


                        <div class="input-wrapper">

                            <span class="input-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <rect
                                        x="3"
                                        y="5"
                                        width="18"
                                        height="14"
                                        rx="2"
                                    ></rect>

                                    <path
                                        d="m3 7 9 6 9-6"
                                    ></path>

                                </svg>

                            </span>


                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-input @error('email') is-invalid @enderror"
                                placeholder="you@example.com"
                                autocomplete="email"
                                autofocus
                                required
                            >

                        </div>


                        @error('email')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <button
                        type="submit"
                        class="primary-button"
                    >
                        <span>
                            Send password reset link
                        </span>
                    </button>

                </form>


                {{-- Back to login --}}

                <div class="back-login">

                    <a href="{{ route('login') }}">

                        <span>
                            ←
                        </span>

                        <span>
                            Back to Login
                        </span>

                    </a>

                </div>


                {{-- Register --}}

                @if(Route::has('register'))

                    <div class="register">

                        Don't have an account?

                        <a href="{{ route('register') }}">
                            Create an account
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </main>

</div>

</body>
</html>
