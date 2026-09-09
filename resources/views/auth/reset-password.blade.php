@extends('layouts.app')

@section('title', 'Reset Password')

@section('page-title', 'Reset Password')

@section('content')

<div class="assessment-wrapper">

    <div class="assessment-card">

        <h1>Reset Your Password</h1>

        <p>
            Choose a new password for your CyberReadyAI account.
        </p>

        @if($errors->any())
            <div class="result-message">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('password.update') }}"
        >
            @csrf

            <input
                type="hidden"
                name="token"
                value="{{ $token }}"
            >

            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    required
                    autocomplete="email"
                >

            </div>

            <div class="form-group">

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

            </div>

            <div class="form-group">

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

            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Reset Password
            </button>

        </form>

    </div>

</div>

@endsection