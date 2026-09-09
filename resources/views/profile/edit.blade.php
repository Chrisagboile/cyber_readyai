@extends('layouts.app')

@section('title', 'My Profile')

@section('page-title', 'My Profile')

@section('content')

<div class="assessment-wrapper">

    @if(session('success'))
        <div class="overview-card">
            <p>
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if($errors->any())
        <div class="result-message">

            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach

        </div>
    @endif

    <div class="assessment-card">

        <h1>Profile Information</h1>

        <p>
            Update your account name and email address.
        </p>

        <form
            method="POST"
            action="{{ route('profile.update') }}"
        >
            @csrf
            @method('PUT')

            <div class="form-group">

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

            </div>

            <div class="form-group">

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

            </div>

            <button
                type="submit"
                class="btn btn-primary"
            >
                Save Profile
            </button>

        </form>

    </div>


    <div class="assessment-card">

        <h1>Change Password</h1>

        <p>
            Use your current password to choose a new one.
        </p>

        <form
            method="POST"
            action="{{ route('profile.password.update') }}"
        >
            @csrf
            @method('PUT')

            <div class="form-group">

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
                Update Password
            </button>

        </form>

    </div>

</div>

@endsection