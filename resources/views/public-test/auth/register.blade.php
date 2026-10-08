@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')

<style>
    .pt-auth {
        max-width: 500px;
        margin: 50px auto;
        padding: 0 20px 60px;
    }

    .pt-auth-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 32px;
    }

    .pt-auth h1 {
        margin: 0 0 8px;
        color: #111827;
    }

    .pt-auth p {
        color: #6b7280;
        margin-bottom: 25px;
    }

    .pt-field {
        margin-bottom: 18px;
    }

    .pt-field label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-weight: 600;
    }

    .pt-field input {
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        border: 1px solid #d1d5db;
        border-radius: 9px;
    }

    .pt-button {
        width: 100%;
        border: 0;
        border-radius: 9px;
        padding: 13px;
        background: #111827;
        color: white;
        font-weight: 700;
        cursor: pointer;
    }

    .pt-auth-link {
        display: block;
        margin-top: 18px;
        text-align: center;
        color: #4f46e5;
        text-decoration: none;
        font-weight: 600;
    }

    .pt-error {
        margin-bottom: 18px;
        padding: 12px;
        border-radius: 9px;
        background: #fef2f2;
        color: #991b1b;
    }
</style>

<div class="pt-auth">

    <div class="pt-auth-card">

        <h1>Create Your Account</h1>

        <p>
            Create a separate account for the CyberReadyAI public assessment.
        </p>

        @if ($errors->any())

            <div class="pt-error">

                <ul style="margin:0;padding-left:20px;">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            method="POST"
            action="{{ route('public-test.register.submit') }}"
        >
            @csrf

            <div class="pt-field">

                <label for="name">
                    Full Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                >

            </div>

            <div class="pt-field">

                <label for="username">
                    Username
                </label>

                <input
                    id="username"
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                >

            </div>

            <div class="pt-field">

                <label for="email">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                >

            </div>

            <div class="pt-field">

                <label for="password">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                >

            </div>

            <div class="pt-field">

                <label for="password_confirmation">
                    Confirm Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                >

            </div>

            <button
                type="submit"
                class="pt-button"
            >
                Create Account
            </button>

        </form>

        <a
            href="{{ route('public-test.login') }}"
            class="pt-auth-link"
        >
            Already have an account? Sign in
        </a>

    </div>

</div>

@endsection
