@extends('layouts.app')

@section('content')
<div class="dashboard-container">

    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Department Reports</h1>

            <p class="dashboard-description">
                Assessment performance and risk overview for
                {{ $department->name }}.
            </p>
        </div>

        <div class="dashboard-date">
            {{ $department->organisation->name }}
        </div>
    </div>


    {{-- Summary statistics --}}
    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-card-label">
                Assessments
            </div>

            <div class="stat-card-value">
                {{ $totalAssessments }}
            </div>

            <div class="stat-card-description">
                Created for this department
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-card-label">
                Completed Attempts
            </div>

            <div class="stat-card-value">
                {{ $totalAttempts }}
            </div>

            <div class="stat-card-description">
                Completed or expired
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-card-label">
                Average Score
            </div>

            <div class="stat-card-value">
                {{ $averageScore !== null
                    ? number_format($averageScore, 1) . '%'
                    : '—' }}
            </div>

            <div class="stat-card-description">
                Department average
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-card-label">
                High Risk
            </div>

            <div class="stat-card-value">
                {{ $highRisk }}
            </div>

            <div class="stat-card-description">
                Assessment attempts
            </div>
        </div>

    </div>


    {{-- Risk summary --}}
    <div class="overview-card">

        <div class="section-heading">
            <h2>Risk Summary</h2>
        </div>

        <div class="stats-grid">

            <div class="stat-card">
                <div class="stat-card-label">
                    Low Risk
                </div>

                <div class="stat-card-value">
                    {{ $lowRisk }}
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-card-label">
                    Medium Risk
                </div>

                <div class="stat-card-value">
                    {{ $mediumRisk }}
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-card-label">
                    High Risk
                </div>

                <div class="stat-card-value">
                    {{ $highRisk }}
                </div>
            </div>

        </div>

    </div>


    {{-- Assessment reports --}}
    <div class="overview-card">

        <div class="section-heading">
            <div>
                <h2>Assessment Reports</h2>

                <p>
                    Assessments belonging to your department.
                </p>
            </div>
        </div>


        @if($assessments->count())

            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>
                        <tr>
                            <th>Assessment</th>
                            <th>Employee</th>
                            <th>Questions</th>
                            <th>Attempts</th>
                            <th>Status</th>
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
                                    {{ $assessment->employee?->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $assessment->total_questions }}
                                </td>

                                <td>
                                    {{ $assessment->attempts_count }}
                                </td>

                                <td>
                                    <span class="status-badge">
                                        {{ ucfirst($assessment->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $assessment->created_at->format('d M Y') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div style="margin-top: 1.5rem;">
                {{ $assessments->links() }}
            </div>

        @else

            <p>
                No assessments have been created for this department yet.
            </p>

        @endif

    </div>

</div>
@endsection
