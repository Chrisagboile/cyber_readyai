@extends('layouts.app')

@section('title', 'Departments')

@section('content')

<div class="departments-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="departments-header">

        <div>
            <span class="departments-eyebrow">
                Organisation Management
            </span>

            <h1 class="departments-title">
                Departments
            </h1>

            <p class="departments-description">
                Manage your organisation's departments, managers,
                and employees.
            </p>
        </div>

        @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))

            <a
                href="{{ route('departments.create') }}"
                class="departments-primary-button"
            >
                + Create Department
            </a>

        @endif

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="departments-alert success">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="departments-alert error">
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
         DIRECTORY
    ========================================================== --}}

    @if($departments->count())

        <div class="departments-card">

            <div class="departments-card-header">

                <div>
                    <h2>Department Directory</h2>

                    <p>
                        {{ $departments->total() }}
                        {{ Str::plural('department', $departments->total()) }}
                        in your accessible scope.
                    </p>
                </div>

                <div class="departments-count-badge">
                    {{ $departments->total() }}
                </div>

            </div>


            <div class="departments-table-wrapper">

                <table class="departments-table">

                    <thead>

                        <tr>

                            <th>Department</th>

                            @if(auth()->user()->hasRole('super-admin'))
                                <th>Organisation</th>
                            @endif

                            <th>Manager(s)</th>
                            <th>Employees</th>
                            <th>Status</th>
                            <th class="departments-actions-heading">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($departments as $department)

                        <tr>

                            {{-- Department --}}
                            <td>

                                <div class="department-directory-name">

                                    <div class="department-directory-icon">
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

                                        <strong>
                                            {{ $department->name }}
                                        </strong>

                                        @if($department->description)

                                            <span>
                                                {{ Str::limit(
                                                    $department->description,
                                                    80
                                                ) }}
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Organisation --}}
                            @if(auth()->user()->hasRole('super-admin'))

                                <td>

                                    <span class="departments-organisation">
                                        {{ $department->organisation->name ?? '—' }}
                                    </span>

                                </td>

                            @endif


                            {{-- Managers --}}
                            <td>

                                @if($department->managers->count())

                                    <div class="department-manager-list">

                                        @foreach($department->managers as $manager)

                                            <span class="department-manager">

                                                <span class="department-manager-avatar">
                                                    {{ strtoupper(
                                                        substr($manager->name, 0, 1)
                                                    ) }}
                                                </span>

                                                {{ $manager->name }}

                                            </span>

                                        @endforeach

                                    </div>

                                @else

                                    <span class="departments-muted">
                                        No manager assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Employees --}}
                            <td>

                                <span class="department-employee-count">
                                    {{ $department->employees_count }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                @if($department->status === 'active')

                                    <span class="department-status active">
                                        Active
                                    </span>

                                @else

                                    <span class="department-status inactive">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="department-actions">

                                    <a
                                        href="{{ route(
                                            'departments.show',
                                            $department
                                        ) }}"
                                        class="department-action-button view"
                                    >
                                        View
                                    </a>


                                    @if(auth()->user()->hasAnyRole([
                                        'super-admin',
                                        'organisation-admin'
                                    ]))

                                        <a
                                            href="{{ route(
                                                'departments.edit',
                                                $department
                                            ) }}"
                                            class="department-action-button edit"
                                        >
                                            Edit
                                        </a>


                                        @if($department->users_count === 0)

                                            <form
                                                action="{{ route(
                                                    'departments.destroy',
                                                    $department
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm(
                                                    'Are you sure you want to delete this department?'
                                                );"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="department-action-button delete"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if($departments->hasPages())

                <div class="departments-pagination">
                    {{ $departments->links() }}
                </div>

            @endif

        </div>

    @else

        <div class="departments-empty-card">

            <div class="departments-empty-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 21h18"/>
                    <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                    <path d="M9 7h1"/>
                    <path d="M14 7h1"/>
                    <path d="M9 11h1"/>
                    <path d="M14 11h1"/>
                </svg>

            </div>

            <h2>No departments yet</h2>

            <p>
                Create your first department to start organising
                employees and assigning managers.
            </p>

            @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))

                <a
                    href="{{ route('departments.create') }}"
                    class="departments-primary-button"
                >
                    Create Department
                </a>

            @endif

        </div>

    @endif

</div>


<style>

/* =========================================================
   DEPARTMENTS DIRECTORY
========================================================= */

.departments-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.departments-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 26px;
}

.departments-eyebrow {
    display: block;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #667085;
}

.departments-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.departments-description {
    margin: 0;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.departments-primary-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 39px;
    padding: 9px 14px;
    border-radius: 8px;
    background: #2563eb;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
}


/* =========================================================
   ALERTS
========================================================= */

.departments-alert {
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 10px;
    font-size: 13px;
}

.departments-alert.success {
    background: #eaf7ef;
    border: 1px solid #ccebd8;
    color: #18794e;
}

.departments-alert.error {
    background: #fde8e8;
    border: 1px solid #f5c5c5;
    color: #9f1d1d;
}


/* =========================================================
   MAIN CARD
========================================================= */

.departments-card {
    overflow: hidden;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.departments-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 24px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.departments-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    line-height: 1.3;
    color: #172033;
}

.departments-card-header p {
    margin: 0;
    font-size: 13px;
    color: #667085;
}

.departments-count-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    box-sizing: border-box;
    border-radius: 10px;
    background: #eef2f5;
    color: #334155;
    font-size: 13px;
    font-weight: 750;
}


/* =========================================================
   TABLE
========================================================= */

.departments-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.departments-table {
    width: 100%;
    border-collapse: collapse;
}

.departments-table th {
    padding: 12px 18px;
    text-align: left;
    white-space: nowrap;

    font-size: 10px;
    line-height: 1.2;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: .05em;

    color: #667085;

    border-bottom: 1px solid rgba(15,23,42,.09);
}

.departments-table td {
    padding: 16px 18px;

    font-size: 13px;
    line-height: 1.4;

    color: #445064;

    vertical-align: middle;

    border-bottom: 1px solid rgba(15,23,42,.07);
}

.departments-table tbody tr {
    transition: background .15s ease;
}

.departments-table tbody tr:hover {
    background: #fafbfc;
}

.departments-table tbody tr:last-child td {
    border-bottom: 0;
}

.departments-actions-heading {
    text-align: right !important;
}


/* =========================================================
   DEPARTMENT NAME
========================================================= */

.department-directory-name {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    min-width: 230px;
}

.department-directory-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;
    background: #eef2f5;
    color: #334155;
}

.department-directory-icon svg {
    width: 20px;
    height: 20px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.department-directory-name strong {
    display: block;
    margin-bottom: 4px;
    font-size: 13px;
    color: #172033;
}

.department-directory-name span {
    display: block;
    max-width: 300px;
    font-size: 11px;
    line-height: 1.45;
    color: #8a94a6;
}


/* =========================================================
   ORGANISATION
========================================================= */

.departments-organisation {
    display: block;
    max-width: 220px;
    font-size: 12px;
    line-height: 1.4;
}


/* =========================================================
   MANAGERS
========================================================= */

.department-manager-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.department-manager {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    font-weight: 600;
    color: #445064;
}

.department-manager-avatar {
    width: 25px;
    height: 25px;
    min-width: 25px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;
    background: #eef2f5;
    color: #334155;

    font-size: 9px;
    font-weight: 750;
}

.departments-muted {
    font-size: 11px;
    color: #8a94a6;
}


/* =========================================================
   EMPLOYEE COUNT
========================================================= */

.department-employee-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 32px;
    height: 28px;

    padding: 0 8px;

    border-radius: 8px;

    background: #f1f4f7;
    color: #172033;

    font-size: 12px;
    font-weight: 750;
}


/* =========================================================
   STATUS
========================================================= */

.department-status {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    line-height: 1.2;
    font-weight: 700;
}

.department-status.active {
    background: #e6f6ee;
    color: #18794e;
}

.department-status.inactive {
    background: #eef1f4;
    color: #667085;
}


/* =========================================================
   ACTIONS
========================================================= */

.department-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    white-space: nowrap;
}

.department-actions form {
    margin: 0;
}

.department-action-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 32px;
    padding: 7px 10px;

    border-radius: 7px;

    font-family: inherit;
    font-size: 10px;
    line-height: 1;
    font-weight: 700;

    text-decoration: none;

    cursor: pointer;
}

.department-action-button.view {
    background: #edf4ff;
    color: #2257a5;
}

.department-action-button.edit {
    background: #eef1f4;
    color: #172033;
}

.department-action-button.delete {
    border: 0;
    background: #fde8e8;
    color: #b42318;
}


/* =========================================================
   PAGINATION
========================================================= */

.departments-pagination {
    padding: 18px 24px;
    border-top: 1px solid rgba(15,23,42,.07);
}


/* =========================================================
   EMPTY STATE
========================================================= */

.departments-empty-card {
    padding: 65px 24px;
    text-align: center;

    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.departments-empty-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 16px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;
    background: #eef2f5;
    color: #334155;
}

.departments-empty-icon svg {
    width: 27px;
    height: 27px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.departments-empty-card h2 {
    margin: 0 0 7px;
    font-size: 19px;
    color: #172033;
}

.departments-empty-card p {
    max-width: 520px;
    margin: 0 auto 20px;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .departments-header {
        flex-direction: column;
    }

    .departments-primary-button {
        align-self: flex-start;
    }

}

@media (max-width: 650px) {

    .departments-page {
        padding: 20px 16px 40px;
    }

    .departments-card-header {
        padding-left: 18px;
        padding-right: 18px;
    }

    .departments-table th,
    .departments-table td {
        padding-left: 14px;
        padding-right: 14px;
    }

    .department-actions {
        justify-content: flex-start;
        flex-wrap: wrap;
    }

}

</style>

@endsection
