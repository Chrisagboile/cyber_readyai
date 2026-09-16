@extends('layouts.app')

@section('title', 'Risk Dashboard')
@section('page-title', 'Risk Dashboard')

@section('content')

<style>
    .manager-risk {
        max-width: 1400px;
        margin: 0 auto;
        padding: 8px 0 40px;
    }

    .manager-risk-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
    }

    .manager-risk-eyebrow {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #2563eb;
    }

    .manager-risk-title {
        margin: 0;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-risk-description {
        margin: 9px 0 0;
        max-width: 780px;
        font-size: 15px;
        line-height: 1.65;
        color: #64748b;
    }

    .manager-risk-organisation {
        padding: 11px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .manager-risk-panel {
        margin-bottom: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .manager-risk-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .manager-risk-panel-title {
        margin: 0;
        font-size: 19px;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-risk-panel-description {
        margin: 5px 0 0;
        font-size: 14px;
        line-height: 1.55;
        color: #64748b;
    }

    .manager-risk-overall {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 24px;
        border: 1px solid #dbeafe;
        border-radius: 16px;
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #f8fbff 100%
        );
    }

    .manager-risk-overall-label {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #2563eb;
    }

    .manager-risk-overall-title {
        margin: 5px 0 0;
        font-size: 23px;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-risk-overall-copy {
        margin: 5px 0 0;
        font-size: 14px;
        color: #64748b;
    }

    .manager-risk-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 105px;
        padding: 9px 15px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 750;
        background: #e2e8f0;
        color: #475569;
    }

    .manager-risk-badge.low {
        background: #dcfce7;
        color: #166534;
    }

    .manager-risk-badge.medium {
        background: #fef3c7;
        color: #92400e;
    }

    .manager-risk-badge.high {
        background: #fee2e2;
        color: #991b1b;
    }

    .manager-risk-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .manager-risk-stat {
        padding: 22px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .manager-risk-stat-label {
        margin: 0;
        font-size: 13px;
        font-weight: 650;
        color: #64748b;
    }

    .manager-risk-stat-value {
        margin: 8px 0 0;
        font-size: 30px;
        line-height: 1;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-risk-stat-meta {
        margin: 8px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .manager-risk-distribution {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .manager-risk-distribution-card {
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #f8fafc;
    }

    .manager-risk-distribution-card.low {
        border-color: #bbf7d0;
        background: #f0fdf4;
    }

    .manager-risk-distribution-card.medium {
        border-color: #fde68a;
        background: #fffbeb;
    }

    .manager-risk-distribution-card.high {
        border-color: #fecaca;
        background: #fef2f2;
    }

    .manager-risk-distribution-label {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
    }

    .manager-risk-distribution-value {
        margin: 8px 0 0;
        font-size: 29px;
        line-height: 1;
        font-weight: 750;
        color: #0f172a;
    }

    .manager-risk-table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }

    .manager-risk-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .manager-risk-table th {
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

    .manager-risk-table td {
        padding: 16px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .manager-risk-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .manager-risk-table tbody tr:hover {
        background: #fafcff;
    }

    .manager-risk-employee {
        font-weight: 700;
        color: #0f172a;
    }

    .manager-risk-assessment {
        color: #334155;
    }

    .manager-risk-score {
        font-weight: 750;
        color: #0f172a;
    }

    .manager-risk-plan-count {
        font-weight: 750;
        color: #0f172a;
    }

    .manager-risk-plan-meta {
        margin-top: 4px;
        font-size: 12px;
        color: #94a3b8;
    }

    .manager-risk-progress {
        min-width: 140px;
    }

    .manager-risk-progress-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
    }

    .manager-risk-progress-track {
        width: 100%;
        height: 7px;
        overflow: hidden;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .manager-risk-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: #2563eb;
    }

    .manager-risk-action-list {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .manager-risk-action {
        display: inline-flex;
        align-items: center;
        width: fit-content;
        padding: 7px 10px;
        border: 1px solid #bfdbfe;
        border-radius: 9px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .manager-risk-action:hover {
        background: #dbeafe;
        border-color: #93c5fd;
    }

    .manager-risk-none {
        color: #94a3b8;
    }

    .manager-risk-empty {
        padding: 38px 24px;
        text-align: center;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
    }

    .manager-risk-empty-icon {
        margin-bottom: 10px;
        font-size: 28px;
    }

    .manager-risk-empty-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .manager-risk-empty-description {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748b;
    }

    @media (max-width: 1050px) {
        .manager-risk-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 800px) {
        .manager-risk-header {
            flex-direction: column;
        }

        .manager-risk-organisation {
            white-space: normal;
        }

        .manager-risk-overall {
            align-items: flex-start;
            flex-direction: column;
        }

        .manager-risk-distribution {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .manager-risk-stats {
            grid-template-columns: 1fr;
        }

        .manager-risk-title {
            font-size: 27px;
        }
    }
</style>

<div class="manager-risk">

    {{-- Header --}}
    <div class="manager-risk-header">

        <div>

            <p class="manager-risk-eyebrow">
                Security Monitoring
            </p>

            <h1 class="manager-risk-title">
                Risk Dashboard
            </h1>

            <p class="manager-risk-description">
                Cybersecurity risk overview for
                {{ $department->name }}.
                Review the latest assessment results,
                employee risk levels, and learning-plan progress.
            </p>

        </div>

        <div class="manager-risk-organisation">
            {{ $department->organisation->name }}
        </div>

    </div>


    {{-- Overall Risk --}}
    <div class="manager-risk-panel">

        <div class="manager-risk-overall">

            <div>

                <p class="manager-risk-overall-label">
                    Overall Department Risk
                </p>

                <h2 class="manager-risk-overall-title">
                    {{ $overallRisk }}
                </h2>

                <p class="manager-risk-overall-copy">
                    Based on the latest completed assessment
                    for each employee.
                </p>

            </div>

            @php
                $overallRiskClass = strtolower(
                    (string) $overallRisk
                );
            @endphp

            <span class="manager-risk-badge {{ $overallRiskClass }}">
                {{ $overallRisk }}
            </span>

        </div>

    </div>


    {{-- Main Statistics --}}
    <div class="manager-risk-stats">

        <div class="manager-risk-stat">

            <p class="manager-risk-stat-label">
                Team Members
            </p>

            <p class="manager-risk-stat-value">
                {{ $totalEmployees }}
            </p>

        </div>


        <div class="manager-risk-stat">

            <p class="manager-risk-stat-label">
                Average Score
            </p>

            <p class="manager-risk-stat-value">

                @if($averageScore !== null)
                    {{ number_format($averageScore, 1) }}%
                @else
                    —
                @endif

            </p>

        </div>


        <div class="manager-risk-stat">

            <p class="manager-risk-stat-label">
                Assessment Coverage
            </p>

            <p class="manager-risk-stat-value">
                {{ $assessmentCoverage }}%
            </p>

        </div>


        <div class="manager-risk-stat">

            <p class="manager-risk-stat-label">
                Not Assessed
            </p>

            <p class="manager-risk-stat-value">
                {{ $notAssessedEmployees }}
            </p>

        </div>

    </div>


    {{-- Risk Distribution --}}
    <div class="manager-risk-panel">

        <div class="manager-risk-panel-header">

            <div>

                <h2 class="manager-risk-panel-title">
                    Risk Distribution
                </h2>

                <p class="manager-risk-panel-description">
                    Latest risk level for each assessed employee.
                </p>

            </div>

        </div>


        <div class="manager-risk-distribution">

            <div class="manager-risk-distribution-card low">

                <p class="manager-risk-distribution-label">
                    Low Risk
                </p>

                <p class="manager-risk-distribution-value">
                    {{ $lowRisk }}
                </p>

            </div>


            <div class="manager-risk-distribution-card medium">

                <p class="manager-risk-distribution-label">
                    Medium Risk
                </p>

                <p class="manager-risk-distribution-value">
                    {{ $mediumRisk }}
                </p>

            </div>


            <div class="manager-risk-distribution-card high">

                <p class="manager-risk-distribution-label">
                    High Risk
                </p>

                <p class="manager-risk-distribution-value">
                    {{ $highRisk }}
                </p>

            </div>

        </div>

    </div>


    {{-- Employee Risk --}}
    <div class="manager-risk-panel">

        <div class="manager-risk-panel-header">

            <div>

                <h2 class="manager-risk-panel-title">
                    Employee Risk & Learning Progress
                </h2>

                <p class="manager-risk-panel-description">
                    Review each employee's latest assessment result
                    and personalised learning-plan progress.
                </p>

            </div>

        </div>


        @if($employeeRisk->count())

            <div class="manager-risk-table-wrap">

                <table class="manager-risk-table">

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

                                    <div class="manager-risk-employee">
                                        {{ $employee['user']->name }}
                                    </div>

                                </td>


                                {{-- Assessment --}}
                                <td>

                                    <span class="manager-risk-assessment">
                                        {{ $employee['attempt']?->assessment?->name
                                            ?? 'Not assessed' }}
                                    </span>

                                </td>


                                {{-- Score --}}
                                <td>

                                    @if($employee['score'] !== null)

                                        <span class="manager-risk-score">
                                            {{ number_format(
                                                $employee['score'],
                                                1
                                            ) }}%
                                        </span>

                                    @else

                                        <span class="manager-risk-none">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Risk --}}
                                <td>

                                    @php
                                        $risk = strtolower(
                                            (string) ($employee['risk_level'] ?? '')
                                        );
                                    @endphp

                                    @if($risk === 'low')

                                        <span class="manager-risk-badge low">
                                            Low
                                        </span>

                                    @elseif($risk === 'medium')

                                        <span class="manager-risk-badge medium">
                                            Medium
                                        </span>

                                    @elseif($risk === 'high')

                                        <span class="manager-risk-badge high">
                                            High
                                        </span>

                                    @else

                                        <span class="manager-risk-badge">
                                            Not Assessed
                                        </span>

                                    @endif

                                </td>


                                {{-- Learning Plans --}}
                                <td>

                                    @if($employee['learning_plan_count'] > 0)

                                        <div class="manager-risk-plan-count">
                                            {{ $employee['learning_plan_count'] }}
                                        </div>

                                        <div class="manager-risk-plan-meta">

                                            {{ $employee['completed_plans'] }}
                                            completed

                                            @if($employee['in_progress_plans'] > 0)

                                                ·
                                                {{ $employee['in_progress_plans'] }}
                                                active

                                            @endif

                                        </div>

                                    @else

                                        <span class="manager-risk-none">
                                            None
                                        </span>

                                    @endif

                                </td>


                                {{-- Learning Progress --}}
                                <td>

                                    @if($employee['learning_progress'] !== null)

                                        <div class="manager-risk-progress">

                                            <div class="manager-risk-progress-top">

                                                <span>
                                                    Progress
                                                </span>

                                                <span>
                                                    {{ $employee['learning_progress'] }}%
                                                </span>

                                            </div>

                                            <div class="manager-risk-progress-track">

                                                <div
                                                    class="manager-risk-progress-fill"
                                                    style="
                                                        width: {{ max(
                                                            0,
                                                            min(
                                                                100,
                                                                (float) $employee['learning_progress']
                                                            )
                                                        ) }}%;
                                                    "
                                                ></div>

                                            </div>

                                        </div>

                                    @else

                                        <span class="manager-risk-none">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td>

                                    @if($employee['attempt'])

                                        <div class="manager-risk-action-list">

                                            <a
                                                href="{{ route(
                                                    'assessment.result',
                                                    $employee['attempt']
                                                ) }}"
                                                class="manager-risk-action"
                                            >
                                                View Result
                                            </a>

                                            @if($employee['learning_plan_count'] > 0)

                                                <a
                                                    href="{{ route(
                                                        'manager.learning-plans',
                                                        $employee['user']
                                                    ) }}"
                                                    class="manager-risk-action"
                                                >
                                                    View Learning Plans
                                                </a>

                                            @endif

                                        </div>

                                    @else

                                        <span class="manager-risk-none">
                                            Awaiting assessment
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="manager-risk-empty">

                <div class="manager-risk-empty-icon">
                    👥
                </div>

                <p class="manager-risk-empty-title">
                    No employees found
                </p>

                <p class="manager-risk-empty-description">
                    No employees are currently assigned to this department.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
