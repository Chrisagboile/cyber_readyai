@extends('layouts.app')

@section('content')
<div class="organisation-users-page">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Organisation Users</h1>
            <p class="dashboard-description">
                Manage employees, managers and organisation administrators.
            </p>
        </div>

        <a href="{{ route('organisation.users.create') }}" class="users-primary-button">
            + Add User
        </a>
    </div>

    @if(session('success'))
        <div class="users-alert users-alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="users-stat-grid">

        <div class="users-stat-card">
            <span>Employees</span>
            <strong>{{ $employeeCount }}</strong>
            <small>Organisation employees</small>
        </div>

        <div class="users-stat-card">
            <span>Managers</span>
            <strong>{{ $managerCount }}</strong>
            <small>Department managers</small>
        </div>

        <div class="users-stat-card">
            <span>Organisation Admins</span>
            <strong>{{ $organisationAdminCount }}</strong>
            <small>Administrative users</small>
        </div>

        <div class="users-stat-card">
            <span>Total Users</span>
            <strong>{{ $employeeCount + $managerCount + $organisationAdminCount }}</strong>
            <small>Users in this organisation</small>
        </div>

    </div>

    <div class="users-card">

        <div class="users-card-header">
            <div>
                <h2>Users</h2>
                <p>Users belonging to your organisation.</p>
            </div>

            <form method="GET" action="{{ route('organisation.users') }}" class="users-search">
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search name or email..."
                >

                <button type="submit">Search</button>

                @if($search)
                    <a href="{{ route('organisation.users') }}">Clear</a>
                @endif
            </form>
        </div>

        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Verified</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-identity">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <strong>{{ $user->name }}</strong>

                                        @if(auth()->id() === $user->id)
                                            <small>You</small>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($user->hasRole('employee'))
                                    <span class="user-role employee">Employee</span>
                                @elseif($user->hasRole('manager'))
                                    <span class="user-role manager">Manager</span>
                                @elseif($user->hasRole('organisation-admin'))
                                    <span class="user-role admin">Organisation Admin</span>
                                @else
                                    <span class="user-role">Unknown</span>
                                @endif
                            </td>

                            <td>
                                {{ $user->department?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                @if($user->status === 'active')
                                    <span class="users-active">Active</span>
                                @else
                                    <span class="users-inactive">Inactive</span>
                                @endif
                            </td>

                            <td>
                                @if($user->email_verified_at)
                                    <span class="users-verified">Verified</span>
                                @else
                                    <span class="users-unverified">Not verified</span>
                                @endif
                            </td>
                            <td class="users-actions">

                                <a
                                    href="{{ route('organisation.users.edit', $user) }}"
                                    class="users-edit-button"
                                >
                                    Edit
                                </a>

                                @if(auth()->id() !== $user->id)

                                    <form
                                        method="POST"
                                        action="{{ route('organisation.users.toggle-status', $user) }}"
                                        style="display:inline;"
                                        onsubmit="return confirm(
                                            '{{ $user->status === 'active'
                                                ? 'Deactivate'
                                                : 'Activate' }} {{ addslashes($user->name) }}?'
                                        );"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="{{ $user->status === 'active'
                                                ? 'users-disable-button'
                                                : 'users-enable-button' }}"
                                        >
                                            {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <form
                                        method="POST"
                                        action="{{ route('organisation.users.destroy', $user) }}"
                                        style="display:inline;"
                                        onsubmit="return confirm(
                                            'Remove {{ addslashes($user->name) }} from this organisation?'
                                        );"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="users-delete-button">
                                            Remove
                                        </button>
                                    </form>

                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="users-empty">
                                    <h3>No users found</h3>
                                    <p>
                                        @if($search)
                                            No users matched your search.
                                        @else
                                            There are no users in this organisation yet.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="users-pagination">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>

<style>
    .organisation-users-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 28px 32px 50px;
    }

    .organisation-users-page .dashboard-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        gap: 24px !important;
        margin-bottom: 26px !important;
    }

    .users-primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 16px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        background: #2563eb;
        color: #fff;
        white-space: nowrap;
    }

    .users-alert {
        padding: 13px 15px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13px;
    }

    .users-alert-success {
        background: #eaf7ef;
        color: #18794e;
        border: 1px solid #ccebd8;
    }

    .users-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .users-stat-card {
        padding: 19px 20px;
        border-radius: 15px;
        border: 1px solid rgba(15, 23, 42, .08);
        background: #fff;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .045);
    }

    .users-stat-card span,
    .users-stat-card small {
        display: block;
    }

    .users-stat-card span {
        font-size: 12px;
        opacity: .6;
        margin-bottom: 6px;
    }

    .users-stat-card strong {
        display: block;
        font-size: 28px;
        line-height: 1;
        margin-bottom: 6px;
    }

    .users-stat-card small {
        font-size: 11px;
        opacity: .52;
    }

    .users-card {
        background: #fff;
        border: 1px solid rgba(15, 23, 42, .08);
        border-radius: 16px;
        box-shadow: 0 6px 24px rgba(15, 23, 42, .05);
        overflow: hidden;
    }

    .users-card-header {
        padding: 22px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        border-bottom: 1px solid rgba(15, 23, 42, .08);
    }

    .users-card-header h2 {
        margin: 0 0 5px;
        font-size: 19px;
    }

    .users-card-header p {
        margin: 0;
        font-size: 13px;
        opacity: .6;
    }

    .users-search {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .users-search input {
        width: 250px;
        height: 38px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1px solid #d7dce2;
        font-size: 13px;
        box-sizing: border-box;
    }

    .users-search button {
        height: 38px;
        padding: 0 13px;
        border: 0;
        border-radius: 8px;
        background: #172033;
        color: #fff;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
    }

    .users-search a {
        font-size: 12px;
        text-decoration: none;
    }

    .users-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th {
        padding: 12px 20px;
        text-align: left;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .05em;
        opacity: .55;
        border-bottom: 1px solid rgba(15, 23, 42, .09);
        white-space: nowrap;
    }

    .users-table td {
        padding: 15px 20px;
        font-size: 13px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(15, 23, 42, .07);
    }

    .users-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .user-identity {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #eef2f7;
        font-size: 12px;
        font-weight: 750;
    }

    .user-identity strong,
    .user-identity small {
        display: block;
    }

    .user-identity strong {
        font-size: 13px;
    }

    .user-identity small {
        margin-top: 2px;
        font-size: 10px;
        opacity: .55;
    }

    .user-role {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        background: #eef1f4;
        font-size: 11px;
        font-weight: 700;
    }

    .user-role.employee {
        background: #edf4ff;
        color: #2257a5;
    }

    .user-role.manager {
        background: #fff4d6;
        color: #956900;
    }

    .user-role.admin {
        background: #eee9ff;
        color: #6246a5;
    }

    .users-verified,
    .users-unverified {
        font-size: 11px;
        font-weight: 650;
    }

    .users-verified {
        color: #18794e;
    }

    .users-unverified {
        color: #a66a00;
    }

    .users-actions {
        white-space: nowrap;
        text-align: right;
    }

    .users-actions form {
        display: inline;
    }

    .users-edit-button,
    .users-delete-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 10px;
        border-radius: 7px;
        font-size: 11px;
        text-decoration: none;
        cursor: pointer;
    }

    .users-edit-button {
        background: #f1f4f7;
        color: #172033;
    }

    .users-delete-button {
        border: 0;
        background: #fde8e8;
        color: #b42318;
        margin-left: 5px;
    }

    .users-empty {
        padding: 50px 20px;
        text-align: center;
    }

    .users-empty h3 {
        margin: 0 0 7px;
        font-size: 16px;
    }

    .users-empty p {
        margin: 0;
        font-size: 13px;
        opacity: .6;
    }

    .users-pagination {
        padding: 18px 24px;
        border-top: 1px solid rgba(15, 23, 42, .07);
    }

    @media (max-width: 1050px) {
        .users-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .users-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .users-search {
            width: 100%;
        }

        .users-search input {
            flex: 1;
            width: auto;
        }
    }

    @media (max-width: 650px) {
        .organisation-users-page {
            padding: 20px 16px 40px;
        }

        .organisation-users-page .dashboard-header {
            flex-direction: column;
        }

        .users-stat-grid {
            grid-template-columns: 1fr;
        }

        .users-search {
            flex-wrap: wrap;
        }
    }
        .users-active,
        .users-inactive {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .users-active {
            background: #e6f6ee;
            color: #18794e;
        }

        .users-inactive {
            background: #eef1f4;
            color: #667085;
        }

        .users-disable-button,
        .users-enable-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 10px;
            margin-left: 5px;
            border: 0;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
        }

        .users-disable-button {
            background: #fff4d6;
            color: #956900;
        }

        .users-enable-button {
            background: #e6f6ee;
            color: #18794e;
        }

</style>
@endsection
