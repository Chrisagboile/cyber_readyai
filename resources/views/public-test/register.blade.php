@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')


<style>
    .public-test-auth {
        max-width: 620px;
        margin: 0px auto;
        padding: 0 20px 60px;
    }

    .public-test-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 36px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    .public-test-eyebrow {
        display: inline-block;
        margin-bottom: 12px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .public-test-title {
        margin: 0 0 10px;
        font-size: 32px;
        line-height: 1.2;
        color: #111827;
    }

    .public-test-description {
        margin: 0 0 28px;
        color: #6b7280;
        line-height: 1.7;
    }

    .public-test-field {
        margin-bottom: 18px;
    }

    .public-test-field label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 14px;
        font-weight: 600;
    }

    .public-test-field input {
        width: 100%;
        box-sizing: border-box;
        padding: 13px 14px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        background: #fff;
        color: #111827;
        font-size: 15px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .public-test-field input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
    }

    .public-test-errors {
        margin-bottom: 20px;
        padding: 14px 16px;
        border-radius: 10px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 14px;
    }

    .public-test-errors ul {
        margin: 0;
        padding-left: 18px;
    }

    .public-test-button {
        width: 100%;
        border: 0;
        border-radius: 10px;
        padding: 14px 18px;
        background: #111827;
        color: #fff;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
    }

    .public-test-button:hover {
        background: #1f2937;
    }

    .public-test-note {
        margin-top: 20px;
        padding: 14px 16px;
        border-radius: 10px;
        background: #f9fafb;
        color: #6b7280;
        font-size: 13px;
        line-height: 1.6;
    }

    @media (max-width: 640px) {
        .public-test-auth {
            margin-top: 25px;
        }

        .public-test-card {
            padding: 24px;
        }

        .public-test-title {
            font-size: 26px;
        }
    }
</style>

<div class="public-test-auth">

    <div class="public-test-card">

        <span class="public-test-eyebrow">
            CyberReadyAI Public Test
        </span>

        <h1 class="public-test-title">
            Test Your Cybersecurity Readiness
        </h1>

        <p class="public-test-description">
            Create your account to take a 10-question cybersecurity
            awareness test and receive your score and risk result.
        </p>

        @if ($errors->any())

            <div class="public-test-errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        @endif

        <form
            method="POST"
            action="{{ route('public-test.register.store') }}"
        >
            @csrf

            <div class="public-test-field">

                <label for="name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                >

            </div>


            <div class="public-test-field">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >

            </div>


            <div class="public-test-field">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    autocomplete="new-password"
                >

            </div>


            <div class="public-test-field">

                <label for="password_confirmation">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    minlength="8"
                    autocomplete="new-password"
                >

            </div>


            <button
                type="submit"
                class="public-test-button"
            >
                Create Account & Continue
            </button>

        </form>


        <div class="public-test-note">
            Your account is created specifically for the public
            CyberReadyAI test. No organisation or department needs
            to be selected.
        </div>

    </div>

</div>

@endsection
