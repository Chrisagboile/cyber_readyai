@extends('layouts.app')

@section('title', 'Department Reports')
@section('page-title', 'Department Reports')

@section('content')

<style>
    .department-reports {
        max-width: 1400px;
        margin: 0 auto;
        padding: 8px 0 40px;
    }

    .department-reports-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
    }

    .department-reports-eyebrow {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #2563eb;
    }

    .department-reports-title {
        margin: 0;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 750;
        color: #0f172a;
    }

    .department-reports-description {
        margin: 9px 0 0;
        max-width: 780px;
        font-size: 15px;
        line-height: 1.65;
        color: #64748b;
    }

    .department-reports-organisation {
        padding: 11px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .department-reports-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .department-reports-stat {
        padding: 22px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .department-reports-stat-label {
        margin: 0;
        font-size: 13px;
        font-weight: 650;
        color: #64748b;
    }

    .department-reports-stat-value {
        margin: 8px 0 0;
        font-size: 30px;
        line-height: 1;
        font-weight: 750;
        color: #0f172a;
    }

    .department-reports-panel {
        margin-bottom: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .department-reports-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 20px;
    }

    .department-reports-panel-title {
        margin: 0;
        font-size: 19px;
        font-weight: 750;
        color: #0f172a;
    }

    .department-reports-panel-description {
        margin: 5px 0 0;
        font-size: 14px;
        line-height: 1.55;
        color: #64748b;
    }

    .department-reports-summary-value {
        font-size: 18px;
        font-weight: 750;
        color: #2563eb;
        white-space: nowrap;
    }

    .department-reports-risk-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .department-reports-risk-card {
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #f8fafc;
    }

    .department-reports-risk-card.low {
        border-color: #bbf7d0;
        background: #f0fdf4;
    }

    .department-reports-risk-card.medium {
        border-color: #fde68a;
        background: #fffbeb;
    }

    .department-reports-risk-card.high {
        border-color: #fecaca;
        background: #fef2f2;
    }

    .department-reports-risk-label {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
    }

    .department-reports-risk-value {
        margin: 8px 0 0;
        font-size: 29px;
        line-height: 1;
        font-weight: 750;
        color: #0f172a;
    }

    .department-reports-learning-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .department-reports-learning-stat {
        padding: 19px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #f8fafc;
    }

    .department-reports-learning-label {
        margin: 0;
        font-size: 13px;
        font-weight: 650;
        color: #64748b;
    }

    .department-reports-learning-value {
        margin: 7px 0 0;
        font-size: 27px;
        line-height: 1;
        font-weight: 750;
        color: #0f172a;
    }

    .department-reports-progress {
        margin-top: 8px;
    }

    .department-reports-progress-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 7px;
        font-size: 13px;
        color: #475569;
    }

    .department-reports-progress-track {
        width: 100%;
        height: 8px;
        overflow: hidden;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .department-reports-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: #2563eb;
    }

    .department-reports-table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }

    .department-reports-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .department-reports-table th {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 750;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .department-reports-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .department-reports-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .department-reports-table tbody tr:hover {
        background: #fafcff;
    }

    .department-reports-assessment {
        font-weight: 700;
        color: #0f172a;
    }

    .department-reports-meta {
        margin-top: 4px;
        font-size: 12px;
        color: #94a3b8;
    }

    .department-reports-score {
        font-weight: 750;
        color: #0f172a;
    }

    .department-reports-number {
        font-weight: 700;
        color: #334155;
    }

    .department-reports-risk-count {
        display: inline-flex;
        min-width: 30px;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 750;
    }

    .department-reports-risk-count.high {
        background: #fee2e2;
        color: #991b1b;
    }

    .department-reports-risk-count.medium {
        background: #fef3c7;
        color: #92400e;
    }

    .department-reports-risk-count.low {
        background: #dcfce7;
        color: #166534;
    }

    .department-reports-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border: 1px solid #bfdbfe;
        border-radius: 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .department-reports-action:hover {
        background: #dbeafe;
        border-color: #93c5fd;
    }

    .department-reports-empty {
        padding: 38px 24px;
        text-align: center;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
    }

    .department-reports-empty-icon {
        margin-bottom: 10px;
        font-size: 28px;
    }

    .department-reports-empty-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .department-reports-empty-description {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748b;
    }

    .department-reports-pagination {
        margin-top: 20px;
    }

    @media (max-width: 1100px) {
        .department-reports-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .department-reports-learning-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 800px) {
        .department-reports-header {
            flex-direction: column;
        }

        .department-reports-organisation {
            white-space: normal;
        }

        .department-reports-risk-grid {
            grid-template-columns: 1fr;
        }

        .department-reports-panel-header {
            flex-direction: column;
        }
    }

    @media (max-width: 600px) {
        .department-reports-stats,
        .department-reports-learning-stats {
            grid-template-columns: 1fr;
        }

        .department-reports-title {
            font-size: 27px;
        }
    }
</style>

<div class="department-reports">

    {{-- Header --}}
    <div class="department-reports-header">

        <div>

            <p class="department-reports-eyebrow">
                Department Analytics
            </p>

            <h1 class="department-reports-title">
                Department Reports
            </h1>

            <p class="department-reports-description">
                Cybersecurity assessment and learning-plan performance
                for {{ $department->name }}.
            </p>

        </div>

        <div class="department-reports-organisation">
            {{ $department->organisation->name }}
        </div>

    </div>


    {{-- Assessment Statistics --}}
    <div class="department-reports-stats">

        <div class="department-reports-stat">

            <p class="department-reports-stat-label">
                Assessments
            </p>

            <p class="department-reports-stat-value">
                {{ $totalAssessments }}
            </p>

        </div>


        <div class="department-reports-stat">

            <p class="department-reports-stat-label">
                Completed Attempts
            </p>

            <p class="department-reports-stat-value">
                {{ $totalAttempts }}
            </p>

        </div>


        <div class="department-reports-stat">

            <p class="department-reports-stat-label">
                Average Score
            </p>

            <p class="department-reports-stat-value">

                @if($averageScore !== null)
                    {{ number_format($averageScore, 1) }}%
                @else
                    —
                @endif

            </p>

        </div>


        <div class="department-reports-stat">

            <p class="department-reports-stat-label">
                High Risk Results
            </p>

            <p class="department-reports-stat-value">
                {{ $highRisk }}
            </p>

        </div>

    </div>


    {{-- Risk Distribution --}}
    <div class="department-reports-panel">

        <div class="department-reports-panel-header">

            <div>

                <h2 class="department-reports-panel-title">
                    Department Risk Distribution
                </h2>

                <p class="department-reports-panel-description">
                    Risk levels across completed and expired
                    assessment attempts.
                </p>

            </div>

        </div>


        <div class="department-reports-risk-grid">

            <div class="department-reports-risk-card low">

                <p class="department-reports-risk-label">
                    Low Risk
                </p>

                <p class="department-reports-risk-value">
                    {{ $lowRisk }}
                </p>

            </div>


            <div class="department-reports-risk-card medium">

                <p class="department-reports-risk-label">
                    Medium Risk
                </p>

                <p class="department-reports-risk-value">
                    {{ $mediumRisk }}
                </p>

            </div>


            <div class="department-reports-risk-card high">

                <p class="department-reports-risk-label">
                    High Risk
                </p>

                <p class="department-reports-risk-value">
                    {{ $highRisk }}
                </p>

            </div>

        </div>

    </div>


    {{-- Learning & Remediation --}}
    <div class="department-reports-panel">

        <div class="department-reports-panel-header">

            <div>

                <h2 class="department-reports-panel-title">
                    Learning & Remediation
                </h2>

                <p class="department-reports-panel-description">
                    Progress against personalised cybersecurity
                    learning plans.
                </p>

            </div>

            <div class="department-reports-summary-value">
                {{ $averageLearningProgress }}%
            </div>

        </div>


        <div class="department-reports-learning-stats">

            <div class="department-reports-learning-stat">

                <p class="department-reports-learning-label">
                    Total Plans
                </p>

                <p class="department-reports-learning-value">
                    {{ $totalLearningPlans }}
                </p>

            </div>


            <div class="department-reports-learning-stat">

                <p class="department-reports-learning-label">
                    Not Started
                </p>

                <p class="department-reports-learning-value">
                    {{ $notStartedLearningPlans }}
                </p>

            </div>


            <div class="department-reports-learning-stat">

                <p class="department-reports-learning-label">
                    In Progress
                </p>

                <p class="department-reports-learning-value">
                    {{ $inProgressLearningPlans }}
                </p>

            </div>


            <div class="department-reports-learning-stat">

                <p class="department-reports-learning-label">
                    Completed
                </p>

                <p class="department-reports-learning-value">
                    {{ $completedLearningPlans }}
                </p>

            </div>

        </div>


        <div class="department-reports-progress">

            <div class="department-reports-progress-top">

                <span>
                    Overall learning-plan progress
                </span>

                <strong>
                    {{ $averageLearningProgress }}%
                </strong>

            </div>

            <div class="department-reports-progress-track">

                <div
                    class="department-reports-progress-fill"
                    style="
                        width: {{ max(
                            0,
                            min(
                                100,
                                (float) $averageLearningProgress
                            )
                        ) }}%;
                    "
                ></div>

            </div>

        </div>

    </div>


    {{-- Assessment Performance --}}
    <div class="department-reports-panel">

        <div class="department-reports-panel-header">

            <div>

                <h2 class="department-reports-panel-title">
                    Assessment Performance
                </h2>

                <p class="department-reports-panel-description">
                    Performance and risk distribution for each
                    department assessment.
                </p>

            </div>

        </div>


        @if($assessmentReports->count())

            <div class="department-reports-table-wrap">

                <table class="department-reports-table">

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

                                    <div class="department-reports-assessment">
                                        {{ $report['assessment']->name }}
                                    </div>

                                    <div class="department-reports-meta">

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

                                    <span class="department-reports-number">
                                        {{ $report['attempt_count'] }}
                                    </span>

                                </td>


                                {{-- Average Score --}}
                                <td>

                                    @if($report['average_score'] !== null)

                                        <span class="department-reports-score">
                                            {{ number_format(
                                                $report['average_score'],
                                                1
                                            ) }}%
                                        </span>

                                    @else

                                        <span style="color:#94a3b8;">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- High --}}
                                <td>

                                    <span class="department-reports-risk-count high">
                                        {{ $report['high_risk'] }}
                                    </span>

                                </td>


                                {{-- Medium --}}
                                <td>

                                    <span class="department-reports-risk-count medium">
                                        {{ $report['medium_risk'] }}
                                    </span>

                                </td>


                                {{-- Low --}}
                                <td>

                                    <span class="department-reports-risk-count low">
                                        {{ $report['low_risk'] }}
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="department-reports-pagination">
                {{ $assessments->links() }}
            </div>

        @else

            <div class="department-reports-empty">

                <div class="department-reports-empty-icon">
                    📊
                </div>

                <p class="department-reports-empty-title">
                    No assessments found
                </p>

                <p class="department-reports-empty-description">
                    No assessments have been created for this
                    department yet.
                </p>

            </div>

        @endif

    </div>


    {{-- Employee Learning Progress --}}
    <div class="department-reports-panel">

        <div class="department-reports-panel-header">

            <div>

                <h2 class="department-reports-panel-title">
                    Employee Learning Progress
                </h2>

                <p class="department-reports-panel-description">
                    Review remediation progress for employees
                    with personalised learning plans.
                </p>

            </div>

        </div>


        @if($employeeLearningProgress->count())

            <div class="department-reports-table-wrap">

                <table class="department-reports-table">

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
                                $employeeProgress = round(
                                    (float) $progress->average_progress
                                );
                            @endphp

                            <tr>

                                {{-- Employee --}}
                                <td>

                                    <div class="department-reports-assessment">
                                        {{ $employee?->name ?? 'Employee' }}
                                    </div>

                                    @if($employee?->email)

                                        <div class="department-reports-meta">
                                            {{ $employee->email }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Total Plans --}}
                                <td>

                                    <span class="department-reports-number">
                                        {{ $progress->total_plans }}
                                    </span>

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

                                    <div class="department-reports-progress">

                                        <div class="department-reports-progress-top">

                                            <span>
                                                Learning progress
                                            </span>

                                            <strong>
                                                {{ $employeeProgress }}%
                                            </strong>

                                        </div>

                                        <div class="department-reports-progress-track">

                                            <div
                                                class="department-reports-progress-fill"
                                                style="
                                                    width: {{ max(
                                                        0,
                                                        min(
                                                            100,
                                                            $employeeProgress
                                                        )
                                                    ) }}%;
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
                                        class="department-reports-action"
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

            <div class="department-reports-empty">

                <div class="department-reports-empty-icon">
                    🎓
                </div>

                <p class="department-reports-empty-title">
                    No learning plans yet
                </p>

                <p class="department-reports-empty-description">
                    No learning plans have been generated for
                    employees in this department yet.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
