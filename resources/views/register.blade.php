<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | CyberReadyAI</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #5b2dcc;
            --primary-dark: #4520a5;
            --text: #17203b;
            --muted: #68718b;
            --border: #e2e4ec;
            --background: #f7f6fc;
        }

        body {
            min-height: 100vh;
            font-family: Inter, -apple-system, BlinkMacSystemFont,
                "Segoe UI", Roboto, Arial, sans-serif;

            background:
                radial-gradient(
                    circle at 10% 20%,
                    rgba(91, 45, 204, 0.12),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 90% 80%,
                    rgba(91, 45, 204, 0.10),
                    transparent 30%
                ),
                var(--background);

            color: var(--text);
        }

        a {
            text-decoration: none;
        }

        /* ============================
           PAGE
        ============================ */

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
        }

        /* ============================
           LEFT BRANDING
        ============================ */

        .brand-panel {
            position: relative;
            overflow: hidden;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            padding: 50px 8%;

            color: white;

            background:
                linear-gradient(
                    145deg,
                    #35158f 0%,
                    #5b2dcc 48%,
                    #7046df 100%
                );
        }

        .brand-panel::before {
            content: "";

            position: absolute;

            width: 470px;
            height: 470px;

            border-radius: 50%;

            border: 1px solid rgba(255,255,255,0.12);

            top: -200px;
            right: -180px;
        }

        .brand-panel::after {
            content: "";

            position: absolute;

            width: 360px;
            height: 360px;

            border-radius: 50%;

            border: 1px solid rgba(255,255,255,0.10);

            bottom: -170px;
            left: -170px;
        }

        .brand {
            position: relative;
            z-index: 2;

            display: flex;
            align-items: center;

            gap: 13px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;

            border-radius: 13px;

            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 25px;
        }

        .brand-name {
            font-size: 23px;
            font-weight: 750;
            line-height: 1;
        }

        .brand-subtitle {
            margin-top: 5px;

            font-size: 12px;

            color: rgba(255,255,255,0.75);
        }

        .panel-content {
            position: relative;
            z-index: 2;

            max-width: 520px;
        }

        .panel-content h1 {
            font-size: clamp(38px, 4vw, 56px);

            line-height: 1.08;

            letter-spacing: -1.5px;

            margin-bottom: 22px;
        }

        .panel-content h1 span {
            color: #ded2ff;
        }

        .panel-content p {
            max-width: 470px;

            color: rgba(255,255,255,0.78);

            font-size: 17px;

            line-height: 1.7;
        }

        /* ============================
           BENEFITS
        ============================ */

        .benefits {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 13px;

            margin-top: 30px;
        }

        .benefit {
            display: flex;
            align-items: center;

            gap: 11px;

            padding: 13px;

            border-radius: 10px;

            background: rgba(255,255,255,0.08);

            border: 1px solid rgba(255,255,255,0.10);

            color: rgba(255,255,255,0.88);

            font-size: 13px;
        }

        .benefit-icon {
            width: 32px;
            height: 32px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: rgba(255,255,255,0.13);
        }

        .panel-footer {
            position: relative;
            z-index: 2;

            color: rgba(255,255,255,0.55);

            font-size: 13px;
        }

        /* ============================
           RIGHT REGISTER PANEL
        ============================ */

        .register-panel {
            display: flex;
            align-items: center;
            justify-content: center;

            padding: 40px 8%;

            background: rgba(255,255,255,0.82);
        }

        .register-container {
            width: 100%;
            max-width: 460px;
        }

        .mobile-brand {
            display: none;
        }

        .register-header {
            margin-bottom: 25px;
        }

        .register-header h2 {
            font-size: 32px;

            letter-spacing: -0.7px;

            margin-bottom: 7px;
        }

        .register-header p {
            color: var(--muted);

            font-size: 15px;
        }

        /* ============================
           ERRORS
        ============================ */

        .alert-error {
            padding: 14px 16px;

            margin-bottom: 20px;

            border-radius: 10px;

            background: #fff1f1;

            border: 1px solid #ffd1d1;

            color: #b42318;

            font-size: 13px;
        }

        .alert-error strong {
            display: block;

            margin-bottom: 6px;
        }

        .alert-error ul {
            margin-left: 18px;
        }

        .alert-error li {
            margin-bottom: 3px;
        }

        .field-error {
            margin-top: 6px;

            color: #c62828;

            font-size: 12px;
        }

        /* ============================
           FORM
        ============================ */

        .form-group {
            margin-bottom: 17px;
        }

        .form-label {
            display: block;

            margin-bottom: 7px;

            color: #303850;

            font-size: 14px;

            font-weight: 650;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;

            left: 15px;
            top: 50%;

            transform: translateY(-50%);

            color: #8991a8;

            font-size: 16px;

            pointer-events: none;
        }

        .form-input,
        .form-select {
            width: 100%;

            height: 50px;

            border: 1px solid var(--border);

            border-radius: 10px;

            background: white;

            padding: 0 15px 0 45px;

            outline: none;

            color: var(--text);

            font-size: 14px;

            transition: 0.2s;
        }

        .form-input::placeholder {
            color: #a0a6b7;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary);

            box-shadow:
                0 0 0 4px rgba(91,45,204,0.10);
        }

        .form-input.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
        }

        /* ============================
           ROLE SELECT
        ============================ */

        .select-wrapper {
            position: relative;
        }

        .select-wrapper::after {
            content: "⌄";

            position: absolute;

            right: 16px;
            top: 50%;

            transform: translateY(-55%);

            color: #737b92;

            font-size: 18px;

            pointer-events: none;
        }

        .form-select {
            padding-right: 42px;

            cursor: pointer;

            appearance: none;
        }

        /* ============================
           PASSWORD
        ============================ */

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;

            right: 14px;
            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: none;

            cursor: pointer;

            color: #737b92;

            font-size: 16px;
        }

        .password-wrapper .form-input {
            padding-right: 45px;
        }

        /* ============================
           REGISTER BUTTON
        ============================ */

        .register-button {
            width: 100%;

            height: 52px;

            border: none;

            border-radius: 10px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    #7046df
                );

            color: white;

            font-size: 15px;

            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 10px 22px rgba(91,45,204,0.20);

            transition: 0.2s;
        }

        .register-button:hover {
            background:
                linear-gradient(
                    135deg,
                    var(--primary-dark),
                    var(--primary)
                );

            transform: translateY(-1px);

            box-shadow:
                0 13px 25px rgba(91,45,204,0.25);
        }

        .register-button:active {
            transform: translateY(0);
        }

        /* ============================
           LOGIN LINK
        ============================ */

        .login-link {
            text-align: center;

            margin-top: 22px;

            color: var(--muted);

            font-size: 14px;
        }

        .login-link a {
            color: var(--primary);

            font-weight: 700;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .back-home {
            text-align: center;

            margin-top: 18px;
        }

        .back-home a {
            color: #737b92;

            font-size: 13px;
        }

        .back-home a:hover {
            color: var(--primary);
        }

        /* ============================
           RESPONSIVE
        ============================ */

        @media (max-width: 900px) {

            .page {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                display: none;
            }

            .register-panel {
                min-height: 100vh;

                padding: 40px 6%;
            }

            .mobile-brand {
                display: flex;

                align-items: center;
                justify-content: center;

                gap: 10px;

                margin-bottom: 35px;
            }

            .mobile-brand .brand-logo {
                background: #eee7ff;

                color: var(--primary);

                border: none;
            }

            .mobile-brand .brand-name {
                color: var(--primary);
            }
        }

        @media (max-width: 500px) {

            .register-panel {
                padding: 30px 20px;
            }

            .register-header h2 {
                font-size: 28px;
            }

            .benefits {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="page">

    {{-- =====================================================
         LEFT BRAND PANEL
    ====================================================== --}}

    <section class="brand-panel">

        <a href="{{ url('/') }}" class="brand">

            <div class="brand-logo">
                🛡️
            </div>

            <div>
                <div class="brand-name">
                    CyberReadyAI
                </div>

                <div class="brand-subtitle">
                    Cybersecurity Awareness Platform
                </div>
            </div>

        </a>


        <div class="panel-content">

            <h1>
                Build a stronger
                <span>security culture.</span>
            </h1>

            <p>
                Create your CyberReadyAI account and empower
                your organisation with cybersecurity awareness,
                training, assessments and actionable insights.
            </p>


            <div class="benefits">

                <div class="benefit">

                    <div class="benefit-icon">
                        🛡️
                    </div>

                    <span>
                        Improve security awareness
                    </span>

                </div>


                <div class="benefit">

                    <div class="benefit-icon">
                        📊
                    </div>

                    <span>
                        Track security progress
                    </span>

                </div>


                <div class="benefit">

                    <div class="benefit-icon">
                        🎓
                    </div>

                    <span>
                        Deliver security training
                    </span>

                </div>


                <div class="benefit">

                    <div class="benefit-icon">
                        🔒
                    </div>

                    <span>
                        Reduce organisational risk
                    </span>

                </div>

            </div>

        </div>


        <div class="panel-footer">
            © {{ date('Y') }} CyberReadyAI. All rights reserved.
        </div>

    </section>


    {{-- =====================================================
         REGISTER PANEL
    ====================================================== --}}

    <main class="register-panel">

        <div class="register-container">


            {{-- MOBILE BRAND --}}

            <a href="{{ url('/') }}" class="mobile-brand">

                <div class="brand-logo">
                    🛡️
                </div>

                <div class="brand-name">
                    CyberReadyAI
                </div>

            </a>


            {{-- HEADER --}}

            <div class="register-header">

                <h2>
                    Create your account
                </h2>

                <p>
                    Join CyberReadyAI and start building
                    a stronger security culture.
                </p>

            </div>


            {{-- =================================================
                 YOUR EXISTING FORM
            ================================================== --}}

            <form
                method="POST"
                action="{{ route('register.store') }}"
            >

                @csrf


                {{-- ERRORS --}}

                @if ($errors->any())

                    <div class="alert-error">

                        <strong>
                            Please correct the following:
                        </strong>

                        <ul>

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                {{-- NAME --}}

                <div class="form-group">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Full name
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            👤
                        </span>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            class="form-input @error('name') is-invalid @enderror"
                            required
                            autocomplete="name"
                        >

                    </div>

                    @error('name')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- USERNAME --}}

                <div class="form-group">

                    <label
                        for="username"
                        class="form-label"
                    >
                        Username
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            @
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            placeholder="Choose a username"
                            class="form-input @error('username') is-invalid @enderror"
                            required
                            autocomplete="username"
                        >

                    </div>

                    @error('username')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- ROLE --}}

                <div class="form-group">

                    <label
                        for="role_id"
                        class="form-label"
                    >
                        Account role
                    </label>

                    <div class="input-wrapper select-wrapper">

                        <span class="input-icon">
                            👥
                        </span>

                        <select
                            name="role_id"
                            id="role_id"
                            class="form-select @error('role_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select a role
                            </option>

                            <option
                                value="1"
                                {{ old('role_id') == '1' ? 'selected' : '' }}
                            >
                                Super Admin
                            </option>

                            <option
                                value="2"
                                {{ old('role_id') == '2' ? 'selected' : '' }}
                            >
                                Organisation Admin
                            </option>

                            <option
                                value="3"
                                {{ old('role_id') == '3' ? 'selected' : '' }}
                            >
                                Manager
                            </option>

                            <option
                                value="4"
                                {{ old('role_id') == '4' ? 'selected' : '' }}
                            >
                                Employee
                            </option>

                        </select>

                    </div>

                    @error('role_id')

                        <div class="field-error">
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
                        Email address
                    </label>

                    <div class="input-wrapper">

                        <span class="input-icon">
                            ✉
                        </span>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            class="form-input @error('email') is-invalid @enderror"
                            required
                            autocomplete="email"
                        >

                    </div>

                    @error('email')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- PASSWORD --}}

                <div class="form-group">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <div class="password-wrapper">

                        <span class="input-icon">
                            🔒
                        </span>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Create a secure password"
                            class="form-input @error('password') is-invalid @enderror"
                            required
                            autocomplete="new-password"
                        >

                                            <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('password', this)"
                            aria-label="Show password"
                        >
                            👁
                        </button>

                    </div>
          <div class="form-group">

                    <label
                        for="password_confirmation"
                        class="form-label"
                    >
                        password confirmation
                    </label>

                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm Password"
                            class="form-input @error('password_confirmation') is-invalid @enderror"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            onclick="togglePassword('password_confirmation', this)"
                            aria-label="Show password"
                        >
                            👁
                        </button>

                    </div>

                    @error('password')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- REGISTER BUTTON --}}

                <button
                    type="submit"
                    class="register-button"
                >
                    Create Account
                </button>

            </form>


            {{-- LOGIN --}}

            <div class="login-link">

                Already have an account?

                <a href="{{ route('login') }}">
                    Sign in
                </a>

            </div>


            {{-- HOME --}}

            <div class="back-home">

                <a href="{{ url('/') }}">
                    ← Back to CyberReadyAI
                </a>

            </div>

        </div>

    </main>

</div>


<script>

function togglePassword(inputId, button) {

    const input = document.getElementById(inputId);

    if (input.type === "password") {

        input.type = "text";

        button.textContent = "🙈";

        button.setAttribute(
            "aria-label",
            "Hide password"
        );

    } else {

        input.type = "password";

        button.textContent = "👁";

        button.setAttribute(
            "aria-label",
            "Show password"
        );
    }
}

</script>

</body>
</html>
