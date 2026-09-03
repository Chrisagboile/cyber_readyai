@extends('layouts.app')

@section('title', 'Assessments')

@section('content')

<div class="dashboard-header">
    <div>
        <h1 class="dashboard-title">
            Assessments
        </h1>

        <p class="dashboard-description">
            Create and monitor cybersecurity assessments
            assigned to your employees.
        </p>
    </div>

    <div class="dashboard-date">
        {{ now()->format('d M Y') }}
    </div>
</div>

<div class="section-heading">
    <div>
        <h2>Assessment Library</h2>
        <p>
            View assessments you have created and their
            current progress.
        </p>
    </div>

    <a
        href="{{ route('manager.assessments.create') }}"
        class="action-button"
    >
        Create Assessment
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="overview-card">

    @if($assessments->isEmpty())

        <div class="empty-state">
            <h3>No assessments yet</h3>

            <p>
                Create your first cybersecurity assessment
                to assign questions to an employee.
            </p>

            <a
                href="{{ route('manager.assessments.create') }}"
                class="action-button"
            >
                Create Assessment
            </a>
        </div>

    @else

        <div class="assessment-table-wrapper">

            <table class="assessment-table">

                <thead>
                    <tr>
                        <th>Assessment</th>
                        <th>Employee</th>
                        <th>Questions</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Attempt</th>
                        <th>Created</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($assessments as $assessment)

                    <tr>

                        <td>
                            <strong>
                                {{ $assessment->name }}
                            </strong>
                        </td>

                        <td>
                            {{ $assessment->employee?->name ?? 'Unknown' }}
                        </td>

                        <td>
                            {{ $assessment->total_questions }}
                        </td>

                        <td>
                            {{ $assessment->duration_minutes }}
                            min
                        </td>

                        <td>

                            @if($assessment->status === 'active')

                                <span class="status-badge success">
                                    Active
                                </span>

                            @else

                                <span class="status-badge">
                                    {{ ucfirst($assessment->status) }}
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $assessment->attempts_count }}
                        </td>

                        <td>
                            {{ $assessment->created_at->format('d M Y') }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">
            {{ $assessments->links() }}
        </div>

    @endif

</div>

@endsection
