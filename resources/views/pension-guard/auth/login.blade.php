
@extends('pension-guard.layout')

@section('title', 'Sign in | Pension Account Guard')

@section('content')
<div class="panel" style="max-width:520px;margin:20px auto">
    <h2>Sign in to Pension Account Guard</h2>

    <p class="muted">
        Sign in to manage your pension records and view your saved
        security awareness assessments.
    </p>

    <form method="POST" action="{{ route('pension-guard.login.submit') }}">
        @csrf

        <label for="email">Email address</label>
        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            autocomplete="email"
            required
            style="width:100%"
        >

        <label for="password">Password</label>
        <input
            id="password"
            type="password"
            name="password"
            autocomplete="current-password"
            required
            style="width:100%"
        >

        <p>
            <button type="submit">Sign in</button>
        </p>
    </form>

    <p>
        Don't have a Pension Guard account?
        <a href="{{ route('pension-guard.register') }}">Create one</a>.
    </p>

    <p class="muted">
        This account is separate from your CyberReadyAI and PublicTest
        accounts.
    </p>
</div>
@endsection
