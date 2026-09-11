@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">{{ $department->name }}</h1>

            <p class="dashboard-description">
                Department details, managers, and team members.
            </p>
        </div>

        <div class="d-flex gap-2">
            <a
                href="{{ route('departments.index') }}"
                class="btn btn-outline-secondary"
            >
                ← Back to Departments
            </a>

            @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))
                <a
                    href="{{ route('departments.edit', $department) }}"
                    class="btn btn-primary"
                >
                    Edit Department
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    {{-- Department Overview --}}
    <div class="stats-grid mb-4">

        <div class="stat-card">
            <div class="stat-card-label">
                Organisation
            </div>

            <div class="stat-card-value">
                {{ $department->organisation->name ?? '—' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-label">
                Team Members
            </div>

            <div class="stat-card-value">
                {{ $department->users->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-label">
                Managers
            </div>

            <div class="stat-card-value">
                {{ $department->managers->count() }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-label">
                Status
            </div>

            <div class="stat-card-value">
                @if($department->status === 'active')
                    <span class="status-badge status-active">
                        Active
                    </span>
                @else
                    <span class="status-badge status-inactive">
                        Inactive
                    </span>
                @endif
            </div>
        </div>

    </div>


    {{-- Department Information --}}
    <div class="overview-card mb-4">

        <div class="section-heading">
            <div>
                <h2>Department Information</h2>
                <p>
                    Basic information about this department.
                </p>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <strong>Department Name</strong>

                <div class="mt-1">
                    {{ $department->name }}
                </div>
            </div>

            <div class="col-md-6">
                <strong>Slug</strong>

                <div class="mt-1 text-muted">
                    {{ $department->slug }}
                </div>
            </div>

            <div class="col-md-6">
                <strong>Organisation</strong>

                <div class="mt-1">
                    {{ $department->organisation->name ?? '—' }}
                </div>
            </div>

            <div class="col-md-6">
                <strong>Status</strong>

                <div class="mt-1">
                    @if($department->status === 'active')
                        <span class="status-badge status-active">
                            Active
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-12">
                <strong>Description</strong>

                <div class="mt-2">
                    @if($department->description)
                        {{ $department->description }}
                    @else
                        <span class="text-muted">
                            No description has been provided.
                        </span>
                    @endif
                </div>
            </div>

        </div>

    </div>


    {{-- Managers --}}
    <div class="overview-card mb-4">

        <div class="section-heading">
            <div>
                <h2>Department Managers</h2>
                <p>
                    Managers currently assigned to this department.
                </p>
            </div>
        </div>

        @if($department->managers->count())

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($department->managers as $manager)
                            <tr>
                                <td>
                                    <strong>{{ $manager->name }}</strong>
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

            <p class="text-muted mb-0">
                No managers are currently assigned to this department.
            </p>

        @endif

    </div>


    {{-- Department Members --}}
    <div class="overview-card">

        <div class="section-heading">
            <div>
                <h2>Department Members</h2>
                <p>
                    Users currently assigned to this department.
                </p>
            </div>
        </div>

        @if($department->users->count())

            <div class="table-responsive">
                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($department->users as $member)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $member->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $member->username }}
                                </td>

                                <td>
                                    @if($member->role)
                                        <span class="status-badge">
                                            {{ ucfirst(str_replace('-', ' ', $member->role->slug)) }}
                                        </span>
                                    @else
                                        <span class="text-muted">
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

            <p class="text-muted mb-0">
                No users are currently assigned to this department.
            </p>

        @endif

    </div>

</div>
@endsection
