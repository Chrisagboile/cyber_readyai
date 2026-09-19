@extends('layouts.app')

@section('title', 'Reset Password')

@section('page-title', 'Reset Password')

@section('content')

<style>
    .password-reset-page {
        min-height: calc(100vh - 120px);

        display: flex;
        align-items: center;
        justify-content: center;

        padding: 40px 20px;

        background:
            radial-gradient(
                circle at 10% 15%,
                rgba(91, 45, 204, 0.08),
                transparent 28%
            ),
            radial-gradient(
                circle at 90% 85%,
                rgba(112, 70, 223, 0.07),
                transparent 30%
            ),
            #f7f7fb;
    }

    .password-reset-container {
        width: 100%;
        max-width: 520px;
    }

    .password-reset-card {
        position: relative;

        padding: 38px;

        border: 1px solid #e5e7ef;
        border-radius: 20px;

        background: #ffffff;

        box-shadow:
            0 24px 60px rgba(23, 32, 59, 0.10);
    }

    .password-reset-header {
        margin-bottom: 30px;
    }

    .password-reset-icon {
        width: 54px;
        height: 54px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-bottom: 20px;

        border-radius: 15px;

        background: #efe9ff;
        border: 1px solid #ddd4fa;

        color: #5b2dcc;

        font-size: 23px;
    }

    .password-reset-kicker {
        margin-bottom: 9px;

        color: #5b2dcc;

        font-size: 12px;
        font-weight: 800;

        text-transform: uppercase;
        letter-spacing: 0.9px;
    }

    .password-reset-header h1 {
        margin: 0 0 10px;

        color: #17203b;

        font-size: 32px;
        line-height: 1.15;

        letter-spacing: -0.8px;
    }

    .password-reset-header p {
        margin: 0;

        color: #727b91;

        font-size: 14px;
        line-height: 1.7;
    }

    /* =========================================================
       VALIDATION
    ========================================================== */

    .password-reset-alert {
        margin-bottom: 22px;

        padding: 14px 15px;

        border-radius: 11px;

        font-size: 13px;
        line-height: 1.5;
    }

    .password-reset-alert-error {
        background: #fff1f1;
        border: 1px solid #ffd2d2;
        color: #b42318;
    }

    .password-reset-alert-error ul {
        margin: 0;
        padding-left: 18px;
    }

    .password-reset-field-error {
        margin-top: 7px;

        color: #c62828;

        font-size: 12px;
        line-height: 1.4;
    }

    /* =========================================================
       FORM
    ========================================================== */

    .password-reset-form-group {
        margin-bottom: 21px;
    }

    .password-reset-label {
        display: block;

        margin-bottom: 8px;

        color: #303850;

        font-size: 13px;
        font-weight: 700;
    }

    .password-reset-input-wrapper {
        position: relative;
    }

    .password-reset-input-icon {
        position: absolute;

        left: 15px;
        top: 50%;

        width: 18px;
        height: 18px;

        transform: translateY(-50%);

        color: #9198aa;

        pointer-events: none;
    }

    .password-reset-input-icon svg {
        width: 100%;
        height: 100%;
    }

    .password-reset-input {
        width: 100%;
        height: 54px;

        padding: 0 48px 0 45px;

        border: 1px solid #e2e5ee;
        border-radius: 12px;

        outline: none;

        background: #ffffff;
        color: #17203b;

        font-size: 14px;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .password-reset-input::placeholder {
        color: #a0a7b8;
    }

    .password-reset-input:hover {
        border-color: #cfd4e0;
    }

    .password-reset-input:focus {
        border-color: #5b2dcc;

        box-shadow:
            0 0 0 4px rgba(91, 45, 204, 0.10);
    }

    .password-reset-input.is-invalid {
        border-color: #dc3545;
    }

    /* =========================================================
       PASSWORD TOGGLE
    ========================================================== */

    .password-reset-toggle {
        position: absolute;

        right: 11px;
        top: 50%;

        width: 35px;
        height: 35px;

        display: flex;
        align-items: center;
        justify-content: center;

        transform: translateY(-50%);

        border: 0;
        border-radius: 9px;

        background: transparent;
        color: #8a92a5;

        cursor: pointer;

        transition:
            background 0.2s ease,
            color 0.2s ease;
    }

    .password-reset-toggle:hover {
        background: #f2efff;
        color: #5b2dcc;
    }

    .password-reset-toggle svg {
        width: 18px;
        height: 18px;
    }

    /* =========================================================
       PASSWORD REQUIREMENTS
    ========================================================== */

    .password-reset-help {
        margin-top: 9px;

        color: #8991a4;

        font-size: 11px;
        line-height: 1.5;
    }

    /* =========================================================
       BUTTON
    ========================================================== */

    .password-reset-button {
        width: 100%;
        height: 54px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 9px;

        margin-top: 5px;

        border: 0;
        border-radius: 12px;

        background:
            linear-gradient(
                135deg,
                #5b2dcc,
                #7046df
            );

        color: #ffffff;

        font-size: 14px;
        font-weight: 800;

        cursor: pointer;

        box-shadow:
            0 13px 28px
            rgba(91, 45, 204, 0.22);

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            background 0.2s ease;
    }

    .password-reset-button:hover {
        transform: translateY(-1px);

        background:
            linear-gradient(
                135deg,
                #4520a5,
                #5b2dcc
            );

        box-shadow:
            0 16px 32px
            rgba(91, 45, 204, 0.28);
    }

    .password-reset-button:active {
        transform: translateY(0);
    }

    /* =========================================================
       BACK TO LOGIN
    ========================================================== */

    .password-reset-back {
        margin-top: 22px;

        padding-top: 20px;

        border-top: 1px solid #edf0f5;

        text-align: center;
    }

    .password-reset-back a {
        display: inline-flex;
        align-items: center;

        gap: 7px;

        color: #5b2dcc;

        font-size: 13px;
        font-weight: 700;

        transition: color 0.2s ease;
    }

    .password-reset-back a:hover {
        color: #4520a5;
    }

    /* =========================================================
       SECURITY NOTE
    ========================================================== */

    .password-reset-security {
        display: flex;
        align-items: flex-start;

        gap: 10px;

        margin-top: 22px;
        padding: 13px 14px;

        border-radius: 11px;

        background: #f8f7fc;
        border: 1px solid #ece9f7;

        color: #70798f;

        font-size: 11px;
        line-height: 1.55;
    }

    .password-reset-security-icon {
        flex: 0 0 auto;

        font-size: 15px;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================== */

    @media (max-width: 600px) {

        .password-reset-page {
            min-height: calc(100vh - 80px);

            padding:
                28px
                16px;
        }

        .password-reset-card {
            padding:
                28px
                20px;

            border-radius: 17px;

            box-shadow:
                0 16px 40px
                rgba(23, 32, 59, 0.08);
        }

        .password-reset-header h1 {
            font-size: 28px;
        }

        .password-reset-icon {
            width: 48px;
            height: 48px;

            border-radius: 13px;

            font-size: 21px;
        }
    }
</style>


<div class="password-reset-page">

    <div class="password-reset-container">

        <div class="password-reset-card">

            {{-- =====================================================
                 HEADER
            ====================================================== --}}

            <div class="password-reset-header">

                <div class="password-reset-icon">
                    🔐
                </div>

                <div class="password-reset-kicker">
                    Account security
                </div>

                <h1>
                    Reset Your Password
                </h1>

                <p>
                    Choose a new password for your CyberReadyAI
                    account. Your new password will replace the
                    existing password immediately after submission.
                </p>

            </div>


            {{-- =====================================================
                 VALIDATION ERRORS
            ====================================================== --}}

            @if($errors->any())

                <div class="password-reset-alert password-reset-alert-error">

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- =====================================================
                 PASSWORD RESET FORM
            ====================================================== --}}

            <form
                method="POST"
                action="{{ route('password.update') }}"
            >

                @csrf


                {{-- Password reset token --}}

                <input
                    type="hidden"
                    name="token"
                    value="{{ $token }}"
                >


                {{-- Email --}}

                <div class="password-reset-form-group">

                    <label
                        for="email"
                        class="password-reset-label"
                    >
                        Email address
                    </label>


                    <div class="password-reset-input-wrapper">

                        <span class="password-reset-input-icon">

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
                            value="{{ old('email', $email) }}"
                            class="password-reset-input @error('email') is-invalid @enderror"
                            placeholder="you@example.com"
                            required
                            autocomplete="email"
                        >

                    </div>


                    @error('email')

                        <div class="password-reset-field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- New password --}}

                <div class="password-reset-form-group">

                    <label
                        for="password"
                        class="password-reset-label"
                    >
                        New password
                    </label>


                    <div class="password-reset-input-wrapper">

                        <span class="password-reset-input-icon">

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
                                    x="5"
                                    y="10"
                                    width="14"
                                    height="10"
                                    rx="2"
                                ></rect>

                                <path
                                    d="M8 10V7a4 4 0 0 1 8 0v3"
                                ></path>

                            </svg>

                        </span>


                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="password-reset-input @error('password') is-invalid @enderror"
                            placeholder="Enter your new password"
                            required
                            autocomplete="new-password"
                        >


                        <button
                            type="button"
                            class="password-reset-toggle"
                            data-target="password"
                            aria-label="Show password"
                            aria-pressed="false"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path
                                    d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                                ></path>

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                ></circle>

                            </svg>

                        </button>

                    </div>


                    <div class="password-reset-help">
                        Use a strong password that you do not reuse
                        on other websites.
                    </div>


                    @error('password')

                        <div class="password-reset-field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Confirm password --}}

                <div class="password-reset-form-group">

                    <label
                        for="password_confirmation"
                        class="password-reset-label"
                    >
                        Confirm new password
                    </label>


                    <div class="password-reset-input-wrapper">

                        <span class="password-reset-input-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path
                                    d="M12 3 5 6v5c0 4.6 2.9 8.6 7 10 4.1-1.4 7-5.4 7-10V6l-7-3Z"
                                ></path>

                                <path
                                    d="m9 12 2 2 4-4"
                                ></path>

                            </svg>

                        </span>


                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="password-reset-input @error('password_confirmation') is-invalid @enderror"
                            placeholder="Re-enter your new password"
                            required
                            autocomplete="new-password"
                        >


                        <button
                            type="button"
                            class="password-reset-toggle"
                            data-target="password_confirmation"
                            aria-label="Show password"
                            aria-pressed="false"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path
                                    d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                                ></path>

                                <circle
                                    cx="12"
                                    cy="12"
                                    r="2.5"
                                ></circle>

                            </svg>

                        </button>

                    </div>


                    @error('password_confirmation')

                        <div class="password-reset-field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Submit --}}

                <button
                    type="submit"
                    class="password-reset-button"
                >

                    <span>
                        Reset Password
                    </span>

                </button>

            </form>


            {{-- =====================================================
                 BACK TO LOGIN
            ====================================================== --}}

            <div class="password-reset-back">

                <a href="{{ route('login') }}">

                    <span>
                        ←
                    </span>

                    <span>
                        Back to Login
                    </span>

                </a>

            </div>


            {{-- =====================================================
                 SECURITY NOTE
            ====================================================== --}}

            <div class="password-reset-security">

                <span class="password-reset-security-icon">
                    🛡️
                </span>

                <span>
                    For your security, never share your password
                    or password-reset link with anyone.
                </span>

            </div>

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const toggleButtons =
            document.querySelectorAll(
                '.password-reset-toggle'
            );


        toggleButtons.forEach(function (button) {

            button.addEventListener('click', function () {

                const targetId =
                    button.getAttribute('data-target');

                const input =
                    document.getElementById(targetId);

                if (!input) {
                    return;
                }


                const showPassword =
                    input.type === 'password';


                input.type =
                    showPassword
                        ? 'text'
                        : 'password';


                button.setAttribute(
                    'aria-label',
                    showPassword
                        ? 'Hide password'
                        : 'Show password'
                );


                button.setAttribute(
                    'aria-pressed',
                    showPassword
                        ? 'true'
                        : 'false'
                );


                if (showPassword) {

                    button.innerHTML = `
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M3 3l18 18"></path>

                            <path
                                d="M10.6 10.6a2.5 2.5 0 0 0 3.5 3.5"
                            ></path>

                            <path
                                d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a18 18 0 0 1-4.1 4.8"
                            ></path>

                            <path
                                d="M6.1 6.1C3.6 8 2 12 2 12s3.5 7 10 7a10.7 10.7 0 0 0 4.1-.8"
                            ></path>
                        </svg>
                    `;

                } else {

                    button.innerHTML = `
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path
                                d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                            ></path>

                            <circle
                                cx="12"
                                cy="12"
                                r="2.5"
                            ></circle>
                        </svg>
                    `;
                }

            });

        });

    });
</script>

@endsection
