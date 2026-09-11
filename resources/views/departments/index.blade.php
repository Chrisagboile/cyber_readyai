@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Departments</h1>
            <p class="dashboard-description">
                Manage your organisation's departments, managers, and employees.
            </p>
        </div>

        @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))
            <div>
                <a href="{{ route('departments.create') }}" class="btn btn-primary">
                    + Create Department
                </a>
            </div>
        @endif
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

    @if($departments->count())
        <div class="overview-card">

            <div class="section-heading">
                <div>
                    <h2>Department Directory</h2>
                    <p>
                        {{ $departments->total() }}
                        {{ Str::plural('department', $departments->total()) }}
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Department</th>

                            @if(auth()->user()->hasRole('super-admin'))
                                <th>Organisation</th>
                            @endif

                            <th>Manager(s)</th>
                            <th>Employees</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($departments as $department)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $department->name }}</strong>

                                        @if($department->description)
                                            <div class="text-muted small mt-1">
                                                {{ Str::limit($department->description, 80) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                @if(auth()->user()->hasRole('super-admin'))
                                    <td>
                                        {{ $department->organisation->name ?? '—' }}
                                    </td>
                                @endif

                                <td>
                                    @forelse($department->managers as $manager)
                                        <div>
                                            {{ $manager->name }}
                                        </div>
                                    @empty
                                        <span class="text-muted">
                                            No manager assigned
                                        </span>
                                    @endforelse
                                </td>

                                <td>
                                    <strong>{{ $department->employees_count }}</strong>
                                </td>

                                <td>
                                    @if($department->status === 'active')
                                        <span class="status-badge status-active">
                                            Active
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex gap-2">

                                        <a
                                            href="{{ route('departments.show', $department) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            View
                                        </a>

                                        @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))
                                            <a
                                                href="{{ route('departments.edit', $department) }}"
                                                class="btn btn-sm btn-outline-secondary"
                                            >
                                                Edit
                                            </a>

                                            @if($department->users_count === 0)
                                                <form
                                                    action="{{ route('departments.destroy', $department) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this department?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
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

            <div class="mt-4">
                {{ $departments->links() }}
            </div>

        </div>
    @else

        <div class="overview-card text-center py-5">
            <h2>No departments yet</h2>

            <p class="text-muted mb-4">
                Create your first department to start organising employees
                and assigning managers.
            </p>

            @if(auth()->user()->hasAnyRole(['super-admin', 'organisation-admin']))
                <a
                    href="{{ route('departments.create') }}"
                    class="btn btn-primary"
                >
                    Create Department
                </a>
            @endif
        </div>

    @endif

</div>
@endsection
