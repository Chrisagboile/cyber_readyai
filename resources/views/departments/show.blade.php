@extends('layouts.app')

@section('title', $department->name)

@section('content')

<div class="department-show-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="department-show-header">

        <div>

            <span class="department-show-eyebrow">
                Department Management
            </span>

            <h1 class="department-show-title">
                {{ $department->name }}
            </h1>

            <p class="department-show-description">
                Department details, managers and team members.
            </p>

        </div>

        <div class="department-show-header-actions">

            <a
                href="{{ route('departments.index') }}"
                class="department-show-secondary-button"
            >
                ← Back to Departments
            </a>

            @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))
                <a
                    href="{{ route('departments.edit', $department) }}"
                    class="department-show-primary-button"
                >
                    Edit Department
                </a>
            @endif

        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))

        <div class="department-show-alert success">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="department-show-alert error">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
         OVERVIEW CARDS
    ========================================================== --}}

    <div class="department-show-stat-grid">

        <div class="department-show-stat-card">

            <div class="department-show-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 21h18"/>
                    <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                    <path d="M9 7h1"/>
                    <path d="M14 7h1"/>
                    <path d="M9 11h1"/>
                    <path d="M14 11h1"/>
                    <path d="M9 15h1"/>
                    <path d="M14 15h1"/>
                </svg>
            </div>

            <div>
                <span>Organisation</span>

                <strong class="department-show-stat-text">
                    {{ $department->organisation->name ?? '—' }}
                </strong>

                <small>Organisation assigned</small>
            </div>

        </div>


        <div class="department-show-stat-card">

            <div class="department-show-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div>
                <span>Team Members</span>
                <strong>{{ $department->users->count() }}</strong>
                <small>Users assigned to department</small>
            </div>

        </div>


        <div class="department-show-stat-card">

            <div class="department-show-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <polyline points="16 11 18 13 22 9"/>
                </svg>
            </div>

            <div>
                <span>Managers</span>
                <strong>{{ $department->managers->count() }}</strong>
                <small>Managers assigned</small>
            </div>

        </div>


        <div class="department-show-stat-card">

            <div class="department-show-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <polyline points="9 12 11 14 15 10"/>
                </svg>
            </div>

            <div>
                <span>Status</span>

                @if($department->status === 'active')
                    <strong class="department-show-status active">
                        Active
                    </strong>
                @else
                    <strong class="department-show-status inactive">
                        Inactive
                    </strong>
                @endif

                <small>Department account status</small>
            </div>

        </div>

    </div>


    {{-- =========================================================
         DEPARTMENT INFORMATION
    ========================================================== --}}

    <div class="department-show-card">

        <div class="department-show-card-header">

            <div>
                <h2>Department Information</h2>

                <p>
                    Basic information about this department.
                </p>
            </div>

        </div>


        <div class="department-show-information-grid">

            <div class="department-show-information-item">
                <span>Department Name</span>

                <strong>
                    {{ $department->name }}
                </strong>
            </div>


            <div class="department-show-information-item">
                <span>Slug</span>

                <strong class="department-show-muted-value">
                    {{ $department->slug }}
                </strong>
            </div>


            <div class="department-show-information-item">
                <span>Organisation</span>

                <strong>
                    {{ $department->organisation->name ?? '—' }}
                </strong>
            </div>


            <div class="department-show-information-item">
                <span>Status</span>

                @if($department->status === 'active')

                    <span class="department-show-status-badge active">
                        Active
                    </span>

                @else

                    <span class="department-show-status-badge inactive">
                        Inactive
                    </span>

                @endif

            </div>


            <div class="department-show-information-item full-width">

                <span>Description</span>

                @if($department->description)

                    <p class="department-show-description-text">
                        {{ $department->description }}
                    </p>

                @else

                    <p class="department-show-empty-text">
                        No description has been provided.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         MANAGERS
    ========================================================== --}}

    <div class="department-show-card">

        <div class="department-show-card-header">

            <div>
                <h2>Department Managers</h2>

                <p>
                    Managers currently assigned to this department.
                </p>
            </div>

            @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))
                <a
                    href="{{ route('departments.edit', $department) }}"
                    class="department-show-header-link"
                >
                    Manage Managers
                </a>
            @endif

        </div>


        @if($department->managers->count())

            <div class="department-show-table-wrapper">

                <table class="department-show-table">

                    <thead>
                        <tr>
                            <th>Manager</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($department->managers as $manager)

                            <tr>

                                <td>
                                    <div class="department-show-person">

                                        <div class="department-show-avatar">
                                            {{ strtoupper(substr($manager->name, 0, 1)) }}
                                        </div>

                                        <strong>
                                            {{ $manager->name }}
                                        </strong>

                                    </div>
                                </td>

                                <td>
                                    {{ $manager->username }}
                                </td>

                                <td>
                                    {{ $manager->email }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="department-show-empty-block">

                <div class="department-show-empty-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                    </svg>
                </div>

                <div>
                    <strong>No managers assigned</strong>

                    <p>
                        No managers are currently assigned to this department.
                    </p>
                </div>

            </div>

        @endif

    </div>


    {{-- =========================================================
         DEPARTMENT MEMBERS
    ========================================================== --}}

    <div class="department-show-card">

        <div class="department-show-card-header">

            <div>
                <h2>Department Members</h2>

                <p>
                    Users currently assigned to this department.
                </p>
            </div>

        </div>


        @if($department->users->count())

            <div class="department-show-table-wrapper">

                <table class="department-show-table">

                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($department->users as $member)

                            <tr>

                                <td>
                                    <div class="department-show-person">

                                        <div class="department-show-avatar">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>

                                        <strong>
                                            {{ $member->name }}
                                        </strong>

                                    </div>
                                </td>

                                <td>
                                    {{ $member->username }}
                                </td>

                                <td>

                                    @if($member->role)

                                        @php
                                            $roleSlug = $member->role->slug;
                                        @endphp

                                        @if($roleSlug === 'employee')
                                            <span class="department-show-role employee">
                                                Employee
                                            </span>
                                        @elseif($roleSlug === 'manager')
                                            <span class="department-show-role manager">
                                                Manager
                                            </span>
                                        @elseif($roleSlug === 'organisation-admin')
                                            <span class="department-show-role admin">
                                                Organisation Admin
                                            </span>
                                        @else
                                            <span class="department-show-role">
                                                {{ ucwords(str_replace('-', ' ', $roleSlug)) }}
                                            </span>
                                        @endif

                                    @else

                                        <span class="department-show-empty-text">
                                            —
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $member->email }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="department-show-empty-block">

                <div class="department-show-empty-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                    </svg>
                </div>

                <div>
                    <strong>No department members</strong>

                    <p>
                        No users are currently assigned to this department.
                    </p>
                </div>

            </div>

        @endif

    </div>

</div>


<style>

/* =========================================================
   DEPARTMENT SHOW
========================================================= */

.department-show-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.department-show-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 26px;
}

.department-show-eyebrow {
    display: block;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #667085;
}

.department-show-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.department-show-description {
    margin: 0;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.department-show-header-actions {
    display: flex;
    align-items: center;
    gap: 9px;
    flex-wrap: wrap;
}

.department-show-primary-button,
.department-show-secondary-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 39px;
    padding: 9px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}

.department-show-primary-button {
    background: #2563eb;
    color: #fff;
}

.department-show-secondary-button {
    background: #eef1f4;
    color: #172033;
}


/* =========================================================
   ALERTS
========================================================= */

.department-show-alert {
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 10px;
    font-size: 13px;
}

.department-show-alert.success {
    background: #eaf7ef;
    border: 1px solid #ccebd8;
    color: #18794e;
}

.department-show-alert.error {
    background: #fde8e8;
    border: 1px solid #f5c5c5;
    color: #9f1d1d;
}


/* =========================================================
   STAT CARDS
========================================================= */

.department-show-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.department-show-stat-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-height: 112px;
    padding: 19px 20px;
    box-sizing: border-box;
    border-radius: 15px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 5px 20px rgba(15,23,42,.045);
}

.department-show-stat-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: #eef2f5;
    color: #334155;
}

.department-show-stat-icon svg {
    width: 22px;
    height: 22px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.department-show-stat-icon svg * {
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.department-show-stat-card span {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 600;
    color: #667085;
}

.department-show-stat-card strong {
    display: block;
    margin-bottom: 5px;
    font-size: 27px;
    line-height: 1;
    color: #172033;
}

.department-show-stat-card small {
    display: block;
    font-size: 11px;
    line-height: 1.4;
    color: #8a94a6;
}

.department-show-stat-text {
    overflow: hidden;
    max-width: 230px;
    font-size: 15px !important;
    line-height: 1.3 !important;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.department-show-status {
    font-size: 15px !important;
}

.department-show-status.active {
    color: #18794e !important;
}

.department-show-status.inactive {
    color: #667085 !important;
}


/* =========================================================
   CONTENT CARDS
========================================================= */

.department-show-card {
    margin-bottom: 24px;
    overflow: hidden;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.department-show-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 24px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.department-show-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    line-height: 1.3;
    color: #172033;
}

.department-show-card-header p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}

.department-show-header-link {
    white-space: nowrap;
    font-size: 12px;
    font-weight: 650;
    text-decoration: none;
}


/* =========================================================
   INFORMATION
========================================================= */

.department-show-information-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0;
}

.department-show-information-item {
    padding: 20px 24px;
    border-bottom: 1px solid rgba(15,23,42,.07);
}

.department-show-information-item:nth-child(odd):not(.full-width) {
    border-right: 1px solid rgba(15,23,42,.07);
}

.department-show-information-item.full-width {
    grid-column: 1 / -1;
}

.department-show-information-item span:first-child {
    display: block;
    margin-bottom: 7px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #8a94a6;
}

.department-show-information-item strong {
    display: block;
    font-size: 13px;
    line-height: 1.5;
    color: #172033;
}

.department-show-muted-value {
    color: #667085 !important;
    font-family: monospace;
    font-size: 12px !important;
}

.department-show-description-text {
    margin: 0;
    max-width: 1000px;
    font-size: 13px;
    line-height: 1.7;
    color: #556070;
}

.department-show-empty-text {
    margin: 0;
    font-size: 12px;
    color: #8a94a6;
}

.department-show-status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    line-height: 1.2;
    font-weight: 700;
}

.department-show-status-badge.active {
    background: #e6f6ee;
    color: #18794e;
}

.department-show-status-badge.inactive {
    background: #eef1f4;
    color: #667085;
}


/* =========================================================
   TABLES
========================================================= */

.department-show-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.department-show-table {
    width: 100%;
    border-collapse: collapse;
}

.department-show-table th {
    padding: 12px 20px;
    text-align: left;
    white-space: nowrap;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #667085;
    border-bottom: 1px solid rgba(15,23,42,.09);
}

.department-show-table td {
    padding: 15px 20px;
    font-size: 13px;
    color: #445064;
    vertical-align: middle;
    border-bottom: 1px solid rgba(15,23,42,.07);
}

.department-show-table tbody tr:last-child td {
    border-bottom: 0;
}

.department-show-person {
    display: flex;
    align-items: center;
    gap: 10px;
}

.department-show-person strong {
    color: #172033;
    font-size: 13px;
}

.department-show-avatar {
    width: 34px;
    height: 34px;
    min-width: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #eef2f5;
    color: #334155;
    font-size: 11px;
    font-weight: 750;
}


/* =========================================================
   ROLE BADGES
========================================================= */

.department-show-role {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    background: #eef1f4;
    color: #667085;
    font-size: 10px;
    font-weight: 700;
}

.department-show-role.employee {
    background: #edf4ff;
    color: #2257a5;
}

.department-show-role.manager {
    background: #fff4d6;
    color: #956900;
}

.department-show-role.admin {
    background: #eee9ff;
    color: #6246a5;
}


/* =========================================================
   EMPTY BLOCK
========================================================= */

.department-show-empty-block {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 30px 24px;
}

.department-show-empty-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
    background: #eef2f5;
    color: #667085;
}

.department-show-empty-icon svg {
    width: 21px;
    height: 21px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.department-show-empty-block strong {
    display: block;
    margin-bottom: 4px;
    font-size: 13px;
    color: #172033;
}

.department-show-empty-block p {
    margin: 0;
    font-size: 12px;
    color: #8a94a6;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .department-show-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 750px) {

    .department-show-page {
        padding: 20px 16px 40px;
    }

    .department-show-header {
        flex-direction: column;
    }

    .department-show-header-actions {
        width: 100%;
    }

    .department-show-primary-button,
    .department-show-secondary-button {
        flex: 1;
    }

    .department-show-stat-grid {
        grid-template-columns: 1fr;
    }

    .department-show-information-grid {
        grid-template-columns: 1fr;
    }

    .department-show-information-item:nth-child(odd):not(.full-width) {
        border-right: 0;
    }

    .department-show-card-header {
        flex-direction: column;
    }

    .department-show-header-link {
        align-self: flex-start;
    }

}
</style>

@endsection
