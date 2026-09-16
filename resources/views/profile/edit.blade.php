@extends('layouts.app')

@section('title', 'My Profile')

@section('page-title', 'My Profile')

@section('content')

<style>
    .profile-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 10px 0 50px;
    }

    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 25px;
        margin-bottom: 24px;
    }

    .profile-eyebrow {
        display: block;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .09em;
        opacity: .5;
    }

    .profile-header h1 {
        margin: 0;
        font-size: 30px;
        letter-spacing: -.6px;
    }

    .profile-header p {
        margin: 8px 0 0;
        max-width: 720px;
        font-size: 13px;
        opacity: .6;
    }

    .profile-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        gap: 20px;
        align-items: start;
    }

    .profile-main {
        display: grid;
        gap: 20px;
    }

    .profile-card {
        padding: 24px;
        border: 1px solid rgba(127, 127, 127, .14);
        border-radius: 16px;
        background: var(--card-bg, #fff);
        box-shadow: 0 7px 24px rgba(0, 0, 0, .035);
    }

    .profile-card-header {
        margin-bottom: 20px;
    }

    .profile-card-header h2 {
        margin: 0;
        font-size: 18px;
    }

    .profile-card-header p {
        margin: 6px 0 0;
        font-size: 12px;
        opacity: .55;
    }

    .profile-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
    }

    .profile-field-full {
        grid-column: 1 / -1;
    }

    .profile-field label {
        display: block;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 750;
    }

    .profile-field input {
        width: 100%;
        min-height: 43px;
        box-sizing: border-box;
        padding: 0 12px;
        border: 1px solid rgba(127, 127, 127, .18);
        border-radius: 9px;
        background: transparent;
        color: inherit;
        font-size: 13px;
    }

    .profile-field input:focus {
        outline: none;
        border-color: rgba(80, 80, 80, .55);
        box-shadow: 0 0 0 3px rgba(80, 80, 80, .05);
    }

    .profile-field input[readonly] {
        opacity: .65;
        cursor: not-allowed;
    }

    .profile-help {
        margin-top: 6px;
        font-size: 10px;
        opacity: .45;
    }

    .profile-error {
        margin-top: 5px;
        font-size: 10px;
        color: #b42318;
    }

    .profile-action-row {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid rgba(127, 127, 127, .09);
    }

    .profile-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 15px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
    }

    .profile-button-primary {
        border: 1px solid #111827;
        background: #111827;
        color: #fff;
    }

    .profile-button-primary:hover {
        background: #1f2937;
        color: #fff;
    }

    .profile-alert {
        margin-bottom: 20px;
        padding: 13px 15px;
        border-radius: 11px;
        font-size: 12px;
        font-weight: 650;
    }

    .profile-alert-success {
        border: 1px solid #a7f3d0;
        background: #ecfdf5;
        color: #065f46;
    }

    .profile-alert-error {
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    .profile-alert p {
        margin: 0;
    }

    .profile-alert p + p {
        margin-top: 5px;
    }

    .profile-side {
        display: grid;
        gap: 20px;
    }

    .profile-identity {
        padding: 24px;
        border-radius: 16px;
        background: #111827;
        color: #fff;
        box-shadow: 0 9px 28px rgba(17, 24, 39, .14);
    }

    .profile-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin-bottom: 15px;
        border-radius: 16px;
        background: rgba(255, 255, 255, .12);
        font-size: 22px;
        font-weight: 800;
    }

    .profile-identity-name {
        font-size: 19px;
        font-weight: 800;
    }

    .profile-identity-email {
        margin-top: 4px;
        font-size: 11px;
        opacity: .7;
        word-break: break-word;
    }

    .profile-role-badge {
        display: inline-flex;
        align-items: center;
        margin-top: 14px;
        min-height: 25px;
        padding: 0 9px;
        border-radius: 999px;
        background: rgba(255, 255, 255, .12);
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .profile-info-card {
        padding: 20px;
        border: 1px solid rgba(127, 127, 127, .14);
        border-radius: 16px;
        background: var(--card-bg, #fff);
        box-shadow: 0 7px 24px rgba(0, 0, 0, .035);
    }

    .profile-info-title {
        margin: 0 0 13px;
        font-size: 15px;
        font-weight: 750;
    }

    .profile-info-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding: 11px 0;
        border-bottom: 1px solid rgba(127, 127, 127, .08);
    }

    .profile-info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .profile-info-label {
        font-size: 10px;
        opacity: .5;
    }

    .profile-info-value {
        text-align: right;
        font-size: 11px;
        font-weight: 750;
    }

    .profile-status {
        display: inline-flex;
        align-items: center;
        min-height: 23px;
        padding: 0 8px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 800;
    }

    .profile-status-active {
        color: #25784c;
        background: rgba(46, 157, 99, .11);
    }

    .profile-status-inactive {
        color: #a13d3d;
        background: rgba(220, 38, 38, .10);
    }

    .profile-password-note {
        margin-top: 16px;
        padding: 12px;
        border-radius: 10px;
        background: rgba(127, 127, 127, .05);
        font-size: 10px;
        line-height: 1.55;
        opacity: .65;
    }

    @media (max-width: 900px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 650px) {
        .profile-page {
            padding: 5px 0 40px;
        }

        .profile-header {
            align-items: flex-start;
        }

        .profile-form-grid {
            grid-template-columns: 1fr;
        }

        .profile-field-full {
            grid-column: auto;
        }

        .profile-card,
        .profile-identity,
        .profile-info-card {
            padding: 18px;
        }

        .profile-action-row {
            flex-direction: column;
        }

        .profile-button {
            width: 100%;
        }
    }
</style>


<div class="profile-page">

    <div class="profile-header">

        <div>

            <span class="profile-eyebrow">
                Account Settings
            </span>

            <h1>
                My Profile
            </h1>

            <p>
                Manage your CyberReadyAI account information and security settings.
            </p>

        </div>

    </div>


    {{-- =========================================================
        FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="profile-alert profile-alert-success">

            <p>
                {{ session('success') }}
            </p>

        </div>

    @endif


    @if($errors->any())

        <div class="profile-alert profile-alert-error">

            @foreach($errors->all() as $error)

                <p>
                    {{ $error }}
                </p>

            @endforeach

        </div>

    @endif


    <div class="profile-layout">


        {{-- =====================================================
            MAIN
        ====================================================== --}}

        <div class="profile-main">


            {{-- Profile Information --}}
            <div class="profile-card">

                <div class="profile-card-header">

                    <h2>
                        Profile Information
                    </h2>

                    <p>
                        Update the personal information associated with your account.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                >

                    @csrf
                    @method('PUT')


                    <div class="profile-form-grid">

                        <div class="profile-field">

                            <label for="name">
                                Full Name
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autocomplete="name"
                            >

                            @error('name')

                                <div class="profile-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="profile-field">

                            <label for="email">
                                Email Address
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="email"
                            >

                            @error('email')

                                <div class="profile-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="profile-action-row">

                        <button
                            type="submit"
                            class="profile-button profile-button-primary"
                        >
                            Save Profile
                        </button>

                    </div>

                </form>

            </div>


            {{-- Change Password --}}
            <div class="profile-card">

                <div class="profile-card-header">

                    <h2>
                        Change Password
                    </h2>

                    <p>
                        Change your password using your current account credentials.
                    </p>

                </div>


                <form
                    method="POST"
                    action="{{ route('profile.password.update') }}"
                >

                    @csrf
                    @method('PUT')


                    <div class="profile-form-grid">

                        <div class="profile-field profile-field-full">

                            <label for="current_password">
                                Current Password
                            </label>

                            <input
                                id="current_password"
                                type="password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                            >

                            @error('current_password')

                                <div class="profile-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="profile-field">

                            <label for="password">
                                New Password
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                            >

                            @error('password')

                                <div class="profile-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="profile-field">

                            <label for="password_confirmation">
                                Confirm New Password
                            </label>

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                            >

                            @error('password_confirmation')

                                <div class="profile-error">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="profile-password-note">
                        Use a strong password that is unique to your CyberReadyAI account.
                    </div>


                    <div class="profile-action-row">

                        <button
                            type="submit"
                            class="profile-button profile-button-primary"
                        >
                            Update Password
                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
            PROFILE SUMMARY
        ====================================================== --}}

        <aside class="profile-side">


            <div class="profile-identity">

                <div class="profile-avatar">
                    {{ strtoupper(
                        substr($user->name, 0, 1)
                    ) }}
                </div>

                <div class="profile-identity-name">
                    {{ $user->name }}
                </div>

                <div class="profile-identity-email">
                    {{ $user->email }}
                </div>


                @if($user->role)

                    <span class="profile-role-badge">
                        {{ $user->role->name ?? $user->role->slug }}
                    </span>

                @endif

            </div>


            <div class="profile-info-card">

                <h2 class="profile-info-title">
                    Account Details
                </h2>


                @if($user->organisation)

                    <div class="profile-info-row">

                        <span class="profile-info-label">
                            Organisation
                        </span>

                        <span class="profile-info-value">
                            {{ $user->organisation->name }}
                        </span>

                    </div>

                @endif


                @if($user->department)

                    <div class="profile-info-row">

                        <span class="profile-info-label">
                            Department
                        </span>

                        <span class="profile-info-value">
                            {{ $user->department->name }}
                        </span>

                    </div>

                @endif


                <div class="profile-info-row">

                    <span class="profile-info-label">
                        Account Status
                    </span>

                    <span class="profile-info-value">

                        @if($user->status === 'active')

                            <span class="
                                profile-status
                                profile-status-active
                            ">
                                Active
                            </span>

                        @else

                            <span class="
                                profile-status
                                profile-status-inactive
                            ">
                                {{ ucfirst($user->status ?? 'Unknown') }}
                            </span>

                        @endif

                    </span>

                </div>


                <div class="profile-info-row">

                    <span class="profile-info-label">
                        Email Verification
                    </span>

                    <span class="profile-info-value">

                        @if($user->email_verified_at)

                            <span class="
                                profile-status
                                profile-status-active
                            ">
                                Verified
                            </span>

                        @else

                            <span class="
                                profile-status
                                profile-status-inactive
                            ">
                                Not Verified
                            </span>

                        @endif

                    </span>

                </div>


                <div class="profile-info-row">

                    <span class="profile-info-label">
                        Member Since
                    </span>

                    <span class="profile-info-value">
                        {{ $user->created_at?->format('d M Y') ?? '—' }}
                    </span>

                </div>


                @if($user->updated_at)

                    <div class="profile-info-row">

                        <span class="profile-info-label">
                            Last Updated
                        </span>

                        <span class="profile-info-value">
                            {{ $user->updated_at->format('d M Y') }}
                        </span>

                    </div>

                @endif

            </div>

        </aside>

    </div>

</div>

@endsection
