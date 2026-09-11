@extends('layouts.app')

@section('content')

<div class="dashboard-container">

    {{-- Dashboard Header --}}
    <div class="dashboard-header">

        <div>
            <h1 class="dashboard-title">
                Risk Dashboard
            </h1>

            <p class="dashboard-description">
                Cybersecurity risk overview for
                {{ $department->name }}.
            </p>
        </div>

        <div class="dashboard-date">
            {{ $department->organisation->name }}
        </div>

    </div>


    {{-- Overall Risk --}}
    <div class="overview-card">

        <div class="section-heading">

            <div>
                <h2>Overall Department Risk</h2>

                <p>
                    Based on the latest completed assessment
                    for each employee.
                </p>
            </div>

            <span class="status-badge">
                {{ $overallRisk }}
            </span>

        </div>

    </div>


    {{-- Main Statistics --}}
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-card-label">
                Team Members
            </div>

            <div class="stat-card-value">
                {{ $totalEmployees }}
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
                Assessment Coverage
            </div>

            <div class="stat-card-value">
                {{ $assessmentCoverage }}%
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-card-label">
                Not Assessed
            </div>

            <div class="stat-card-value">
                {{ $notAssessedEmployees }}
            </div>

        </div>

    </div>


    {{-- Risk Distribution --}}
    <div class="overview-card">

        <div class="section-heading">

            <div>
                <h2>Risk Distribution</h2>

                <p>
                    Latest risk level for each assessed employee.
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


    {{-- Employee Risk Overview --}}
    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    Employee Risk & Learning Progress
                </h2>

                <p>
                    Review each employee's latest assessment result
                    and personalised learning-plan progress.
                </p>

            </div>

        </div>


        @if($employeeRisk->count())

            <div class="table-responsive">

                <table class="dashboard-table">

                    <thead>

                        <tr>

                            <th>
                                Employee
                            </th>

                            <th>
                                Latest Assessment
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Risk Level
                            </th>

                            <th>
                                Learning Plans
                            </th>

                            <th>
                                Learning Progress
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($employeeRisk as $employee)

                            <tr>

                                {{-- Employee --}}
                                <td>

                                    <strong>
                                        {{ $employee['user']->name }}
                                    </strong>

                                </td>


                                {{-- Latest Assessment --}}
                                <td>

                                    {{ $employee['attempt']?->assessment?->name
                                        ?? 'Not assessed' }}

                                </td>


                                {{-- Score --}}
                                <td>

                                    @if($employee['score'] !== null)

                                        {{ number_format(
                                            $employee['score'],
                                            1
                                        ) }}%

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- Risk Level --}}
                                <td>

                                    @if($employee['risk_level'])

                                        <span class="status-badge">
                                            {{ $employee['risk_level'] }}
                                        </span>

                                    @else

                                        <span class="status-badge">
                                            Not Assessed
                                        </span>

                                    @endif

                                </td>


                                {{-- Learning Plans --}}
                                <td>

                                    @if($employee['learning_plan_count'] > 0)

                                        <strong>
                                            {{ $employee['learning_plan_count'] }}
                                        </strong>

                                        <div style="
                                            font-size: 13px;
                                            opacity: .7;
                                            margin-top: 4px;
                                        ">

                                            {{ $employee['completed_plans'] }}
                                            completed

                                            @if($employee['in_progress_plans'] > 0)

                                                ·
                                                {{ $employee['in_progress_plans'] }}
                                                active

                                            @endif

                                        </div>

                                    @else

                                        <span style="opacity: .6;">
                                            None
                                        </span>

                                    @endif

                                </td>


                                {{-- Learning Progress --}}
                                <td>

                                    @if($employee['learning_progress'] !== null)

                                        <div style="min-width: 130px;">

                                            <div style="
                                                display: flex;
                                                justify-content: space-between;
                                                align-items: center;
                                                margin-bottom: 6px;
                                                font-size: 13px;
                                            ">

                                                <span>
                                                    {{ $employee['learning_progress'] }}%
                                                </span>

                                            </div>


                                            <div class="dashboard-progress">

                                                <div
                                                    style="
                                                        width: {{ $employee['learning_progress'] }}%;
                                                    "
                                                ></div>

                                            </div>

                                        </div>

                                    @else

                                        —

                                    @endif

                                </td>


                               {{-- Action --}}
                <td>

                    @if($employee['attempt'])
                        <div style="display: flex; flex-direction: column; gap: 6px;">

                            <a
                                href="{{ route(
                                    'assessment.result',
                                    $employee['attempt']
                                ) }}"
                            >
                                View Result
                            </a>

                            @if($employee['learning_plan_count'] > 0)

                                <a
                                    href="{{ route(
                                        'manager.learning-plans',
                                        $employee['user']
                                    ) }}"
                                >
                                    View Learning Plans
                                </a>

                            @endif

                        </div>

                    @else

                        —

                    @endif

                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <p>
                No employees are currently assigned to this department.
            </p>

        @endif

    </div>

</div>

@endsection
