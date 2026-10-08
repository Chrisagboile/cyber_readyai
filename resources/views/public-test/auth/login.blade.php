@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')
<style>
    .pt-auth {
        max-width: 480px;
        margin: 60px auto;
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

        <h1>Public Cybersecurity Test</h1>

        <p>
            Sign in to continue your assessment.
        </p>

        @if ($errors->any())

            <div class="pt-error">
                {{ $errors->first() }}
            </div>

        @endif

        <form
            method="POST"
            action="{{ route('public-test.login.submit') }}"
        >
            @csrf

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
                    autofocus
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

            <label style="display:flex;gap:8px;margin-bottom:20px;">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                >

                Remember me
            </label>

            <button
                type="submit"
                class="pt-button"
            >
                Sign In
            </button>

        </form>

        <a
            href="{{ route('public-test.register') }}"
            class="pt-auth-link"
        >
            Create a public test account
        </a>

    </div>

</div>

@endsection
