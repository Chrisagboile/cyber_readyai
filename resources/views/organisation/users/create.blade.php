@extends('layouts.app')

@section('content')
<div class="user-form-page">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Add Organisation User</h1>
            <p class="dashboard-description">
                Create an employee, manager or organisation administrator.
            </p>
        </div>

        <a href="{{ route('organisation.users') }}" class="user-back-link">
            ← Back to Users
        </a>
    </div>

    <div class="user-form-card">

        <div class="user-form-header">
            <h2>User Details</h2>
            <p>New users are automatically verified when created by an Organisation Admin.</p>
        </div>

        @if($errors->any())
            <div class="user-form-errors">
                <strong>Please correct the following:</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('organisation.users.store') }}">
            @csrf

            @include('organisation.users._form')

            <div class="user-form-actions">
                <a href="{{ route('organisation.users') }}" class="user-cancel-button">
                    Cancel
                </a>

                <button type="submit" class="user-save-button">
                    Create User
                </button>
            </div>
        </form>

    </div>

</div>

<style>
    .user-form-page {
        max-width: 1050px;
        margin: 0 auto;
        padding: 28px 32px 50px;
    }

    .user-back-link {
        text-decoration: none;
        font-size: 13px;
    }

    .user-form-card {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
        overflow: hidden;
    }

    .user-form-header {
        padding: 23px 25px;
        border-bottom: 1px solid rgba(15, 23, 42, .08);
    }

    .user-form-header h2 {
        margin: 0 0 5px;
        font-size: 19px;
    }

    .user-form-header p {
        margin: 0;
        font-size: 13px;
        opacity: .6;
    }

    .user-form-card form {
        padding: 25px;
    }

    .user-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .user-form-field label {
        display: block;
        margin-bottom: 7px;
        font-size: 12px;
        font-weight: 700;
    }

    .user-form-field label small {
        font-weight: 400;
        opacity: .55;
    }

    .user-form-field input,
    .user-form-field select {
        width: 100%;
        height: 42px;
        box-sizing: border-box;
        padding: 0 12px;
        border: 1px solid #d7dce2;
        border-radius: 8px;
        background: #fff;
        font-size: 13px;
    }

    .user-form-field small {
        display: block;
        margin-top: 6px;
        font-size: 11px;
        line-height: 1.45;
        opacity: .58;
    }

    .user-form-error {
        display: block;
        margin-top: 6px;
        font-size: 11px;
        color: #b42318;
    }

    .user-form-errors {
        margin: 20px 25px 0;
        padding: 13px 15px;
        border-radius: 10px;
        background: #fde8e8;
        color: #9f1d1d;
        font-size: 12px;
    }

    .user-form-errors ul {
        margin: 7px 0 0;
        padding-left: 18px;
    }

    .user-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid rgba(15, 23, 42, .08);
    }

    .user-cancel-button,
    .user-save-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .user-cancel-button {
        background: #eef1f4;
        color: #172033;
    }

    .user-save-button {
        border: 0;
        background: #2563eb;
        color: #fff;
    }

    @media (max-width: 700px) {
        .user-form-page {
            padding: 20px 16px 40px;
        }

        .user-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
