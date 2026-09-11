```blade
@extends('layouts.app')

@section('content')

<div class="dashboard-container">

    {{-- ============================================================
         Dashboard Header
    ============================================================= --}}

    <div class="dashboard-header">

        <div>

            <h1 class="dashboard-title">
                Department Reports
            </h1>

            <p class="dashboard-description">
                Cybersecurity assessment and learning-plan
                performance for {{ $department->name }}.
            </p>

        </div>

        <div class="dashboard-date">
            {{ $department->organisation->name }}
        </div>

    </div>


    {{-- ============================================================
         Assessment Statistics
    ============================================================= --}}

    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-card-label">
                Assessments
            </div>

            <div class="stat-card-value">
                {{ $totalAssessments }}
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-card-label">
                Completed Attempts
            </div>

            <div class="stat-card-value">
                {{ $totalAttempts }}
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

        </div>


        <div class="stat-card">

            <div class="stat-card-label">
                High Risk Results
            </div>

            <div class="stat-card-value">
                {{ $highRisk }}
            </div>

        </div>

    </div>


    {{-- ============================================================
         Risk Distribution
    ============================================================= --}}

    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    Department Risk Distribution
                </h2>

                <p>
                    Risk levels across completed and expired
                    assessment attempts.
                </p>

            </div>

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


    {{-- ============================================================
         Learning & Remediation
    ============================================================= --}}

    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    Learning & Remediation
                </h2>

                <p>
                    Progress against personalised cybersecurity
                    learning plans.
                </p>

            </div>

            <strong>
                {{ $averageLearningProgress }}%
            </strong>

        </div>


        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-card-label">
                    Total Plans
                </div>

                <div class="stat-card-value">
                    {{ $totalLearningPlans }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-card-label">
                    Not Started
                </div>

                <div class="stat-card-value">
                    {{ $notStartedLearningPlans }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-card-label">
                    In Progress
                </div>

                <div class="stat-card-value">
                    {{ $inProgressLearningPlans }}
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-card-label">
                    Completed
                </div>

                <div class="stat-card-value">
                    {{ $completedLearningPlans }}
                </div>

            </div>

        </div>


        {{-- Overall Learning Progress --}}

        <div style="margin-top: 20px;">

            <div style="
                display: flex;
                justify-content: space-between;
                margin-bottom: 6px;
                font-size: 14px;
            ">

                <span>
                    Overall learning-plan progress
                </span>

                <strong>
                    {{ $averageLearningProgress }}%
                </strong>

            </div>


            <div class="dashboard-progress">

                <div
                    style="
                        width: {{ $averageLearningProgress }}%;
                    "
                ></div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         Assessment Performance
    ============================================================= --}}

    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    Assessment Performance
                </h2>

                <p>
                    Performance and risk distribution for each
                    department assessment.
                </p>

            </div>

        </div>


        @if($assessmentReports->count())

            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>

                        <tr>

                            <th>
                                Assessment
                            </th>

                            <th>
                                Employee
                            </th>

                            <th>
                                Attempts
                            </th>

                            <th>
                                Average Score
                            </th>

                            <th>
                                High
                            </th>

                            <th>
                                Medium
                            </th>

                            <th>
                                Low
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($assessmentReports as $report)

                            <tr>

                                {{-- Assessment --}}

                                <td>

                                    <strong>
                                        {{ $report['assessment']->name }}
                                    </strong>

                                    <div style="
                                        font-size: 13px;
                                        opacity: .7;
                                        margin-top: 4px;
                                    ">

                                        Created
                                        {{ $report['assessment']->created_at->format('d M Y') }}

                                    </div>

                                </td>


                                {{-- Employee --}}

                                <td>

                                    {{ $report['assessment']->employee?->name
                                        ?? '—' }}

                                </td>


                                {{-- Attempts --}}

                                <td>
                                    {{ $report['attempt_count'] }}
                                </td>


                                {{-- Average Score --}}

                                <td>

                                    @if($report['average_score'] !== null)

                                        {{ number_format(
                                            $report['average_score'],
                                            1
                                        ) }}%

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- High Risk --}}

                                <td>
                                    {{ $report['high_risk'] }}
                                </td>


                                {{-- Medium Risk --}}

                                <td>
                                    {{ $report['medium_risk'] }}
                                </td>


                                {{-- Low Risk --}}

                                <td>
                                    {{ $report['low_risk'] }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            <div style="margin-top: 20px;">

                {{ $assessments->links() }}

            </div>

        @else

            <p>
                No assessments have been created for this
                department yet.
            </p>

        @endif

    </div>


    {{-- ============================================================
         Employee Learning Progress
    ============================================================= --}}

    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    Employee Learning Progress
                </h2>

                <p>
                    Review remediation progress for employees
                    with personalised learning plans.
                </p>

            </div>

        </div>


        @if($employeeLearningProgress->count())

            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>

                        <tr>

                            <th>
                                Employee
                            </th>

                            <th>
                                Total Plans
                            </th>

                            <th>
                                Completed
                            </th>

                            <th>
                                In Progress
                            </th>

                            <th>
                                Progress
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($employeeLearningProgress as $employeeId => $progress)

                            @php
                                $employee = $employees->get($employeeId);
                            @endphp


                            <tr>

                                {{-- Employee --}}

                                <td>

                                    <strong>
                                        {{ $employee?->name ?? 'Employee' }}
                                    </strong>

                                </td>


                                {{-- Total Plans --}}

                                <td>

                                    <strong>
                                        {{ $progress->total_plans }}
                                    </strong>

                                </td>


                                {{-- Completed --}}

                                <td>
                                    {{ $progress->completed_plans }}
                                </td>


                                {{-- In Progress --}}

                                <td>
                                    {{ $progress->in_progress_plans }}
                                </td>


                                {{-- Progress --}}

                                <td>

                                    <div style="min-width: 130px;">

                                        <div style="
                                            display: flex;
                                            justify-content: space-between;
                                            margin-bottom: 6px;
                                            font-size: 13px;
                                        ">

                                            <span>
                                                {{ round((float) $progress->average_progress) }}%
                                            </span>

                                        </div>


                                        <div class="dashboard-progress">

                                            <div
                                                style="
                                                    width: {{ round((float) $progress->average_progress) }}%;
                                                "
                                            ></div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Action --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'manager.learning-plans',
                                            $employeeId
                                        ) }}"
                                    >
                                        View Plans
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <p>
                No learning plans have been generated for
                employees in this department yet.
            </p>

        @endif

    </div>

</div>

@endsection
```
