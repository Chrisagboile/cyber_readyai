@extends('layouts.app')

@section('title', 'Team Readiness')
@section('page-title', 'Team Readiness')

@section('content')

<style>
    .team-readiness {
        max-width: 1400px;
        margin: 0 auto;
        padding: 8px 0 40px;
    }

    .team-readiness-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
    }

    .team-readiness-eyebrow {
        margin: 0 0 8px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #2563eb;
    }

    .team-readiness-title {
        margin: 0;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 750;
        color: #0f172a;
    }

    .team-readiness-description {
        margin: 9px 0 0;
        max-width: 760px;
        font-size: 15px;
        line-height: 1.65;
        color: #64748b;
    }

    .team-readiness-date {
        padding: 11px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .team-readiness-department {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        padding: 22px 24px;
        border: 1px solid #dbeafe;
        border-radius: 18px;
        background: linear-gradient(
            180deg,
            #ffffff 0%,
            #f8fbff 100%
        );
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .team-readiness-department-label {
        margin: 0 0 5px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: #2563eb;
    }

    .team-readiness-department-name {
        margin: 0;
        font-size: 21px;
        font-weight: 750;
        color: #0f172a;
    }

    .team-readiness-organisation {
        margin: 4px 0 0;
        font-size: 14px;
        color: #64748b;
    }

    .team-readiness-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .team-readiness-stat {
        padding: 22px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .team-readiness-stat-label {
        margin: 0;
        font-size: 13px;
        font-weight: 650;
        color: #64748b;
    }

    .team-readiness-stat-value {
        margin: 8px 0 0;
        font-size: 30px;
        line-height: 1;
        font-weight: 750;
        color: #0f172a;
    }

    .team-readiness-stat-meta {
        margin: 8px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .team-readiness-panel {
        margin-bottom: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
    }

    .team-readiness-panel-header {
        margin-bottom: 18px;
    }

    .team-readiness-panel-title {
        margin: 0;
        font-size: 19px;
        font-weight: 750;
        color: #0f172a;
    }

    .team-readiness-panel-description {
        margin: 5px 0 0;
        font-size: 14px;
        line-height: 1.55;
        color: #64748b;
    }

    .team-readiness-risk-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .team-readiness-risk-card {
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #f8fafc;
    }

    .team-readiness-risk-label {
        margin: 0;
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
    }

    .team-readiness-risk-value {
        margin: 8px 0 0;
        font-size: 28px;
        font-weight: 750;
        color: #0f172a;
    }

    .team-readiness-risk-card.low {
        border-color: #bbf7d0;
        background: #f0fdf4;
    }

    .team-readiness-risk-card.medium {
        border-color: #fde68a;
        background: #fffbeb;
    }

    .team-readiness-risk-card.high {
        border-color: #fecaca;
        background: #fef2f2;
    }

    .team-readiness-table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }

    .team-readiness-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .team-readiness-table th {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .04em;
        text-align: left;
        white-space: nowrap;
    }

    .team-readiness-table td {
        padding: 16px;
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 14px;
        vertical-align: middle;
    }

    .team-readiness-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .team-readiness-table tbody tr:hover {
        background: #fafcff;
    }

    .team-readiness-employee {
        font-weight: 700;
        color: #0f172a;
    }

    .team-readiness-email {
        margin-top: 3px;
        font-size: 12px;
        color: #94a3b8;
    }

    .team-readiness-score {
        font-weight: 750;
        color: #0f172a;
    }

    .team-readiness-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 82px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 750;
        line-height: 1;
    }

    .team-readiness-badge.low {
        background: #dcfce7;
        color: #166534;
    }

    .team-readiness-badge.medium {
        background: #fef3c7;
        color: #92400e;
    }

    .team-readiness-badge.high {
        background: #fee2e2;
        color: #991b1b;
    }

    .team-readiness-badge.neutral {
        background: #e2e8f0;
        color: #475569;
    }

    .team-readiness-result-link {
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
        transition: background .18s ease, border-color .18s ease;
        white-space: nowrap;
    }

    .team-readiness-result-link:hover {
        background: #dbeafe;
        border-color: #93c5fd;
    }

    .team-readiness-empty {
        padding: 38px 24px;
        text-align: center;
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
    }

    .team-readiness-empty-icon {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .team-readiness-empty-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .team-readiness-empty-description {
        margin: 6px 0 0;
        font-size: 14px;
        color: #64748b;
    }

    @media (max-width: 1100px) {
        .team-readiness-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 800px) {
        .team-readiness-header {
            flex-direction: column;
        }

        .team-readiness-date {
            white-space: normal;
        }

        .team-readiness-department {
            flex-direction: column;
            align-items: flex-start;
        }

        .team-readiness-risk-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .team-readiness-grid {
            grid-template-columns: 1fr;
        }

        .team-readiness {
            padding-top: 0;
        }

        .team-readiness-title {
            font-size: 27px;
        }
    }
</style>

<div class="team-readiness">

    {{-- Header --}}
    <div class="team-readiness-header">

        <div>

            <p class="team-readiness-eyebrow">
                Team Management
            </p>

            <h1 class="team-readiness-title">
                Team Readiness
            </h1>

            <p class="team-readiness-description">
                Monitor cybersecurity readiness across your department,
                identify risk exposure, and review the latest employee
                assessment results.
            </p>

        </div>

        <div class="team-readiness-date">
            {{ now()->format('l, F j, Y') }}
        </div>

    </div>


    {{-- Department --}}
    <div class="team-readiness-department">

        <div>

            <p class="team-readiness-department-label">
                Department
            </p>

            <h2 class="team-readiness-department-name">
                {{ $department->name }}
            </h2>

            <p class="team-readiness-organisation">
                {{ $department->organisation->name }}
            </p>

        </div>

    </div>


    {{-- Summary --}}
    <div class="team-readiness-grid">

        <div class="team-readiness-stat">

            <p class="team-readiness-stat-label">
                Team Members
            </p>

            <p class="team-readiness-stat-value">
                {{ $totalEmployees }}
            </p>

        </div>


        <div class="team-readiness-stat">

            <p class="team-readiness-stat-label">
                Assessed
            </p>

            <p class="team-readiness-stat-value">
                {{ $assessedEmployees }}
            </p>

            <p class="team-readiness-stat-meta">
                {{ $assessmentCoverage }}% coverage
            </p>

        </div>


        <div class="team-readiness-stat">

            <p class="team-readiness-stat-label">
                Average Score
            </p>

            <p class="team-readiness-stat-value">

                @if($averageScore !== null)
                    {{ number_format($averageScore, 1) }}%
                @else
                    —
                @endif

            </p>

        </div>


        <div class="team-readiness-stat">

            <p class="team-readiness-stat-label">
                Not Assessed
            </p>

            <p class="team-readiness-stat-value">
                {{ $notAssessedEmployees }}
            </p>

        </div>

    </div>


    {{-- Risk Overview --}}
    <div class="team-readiness-panel">

        <div class="team-readiness-panel-header">

            <h2 class="team-readiness-panel-title">
                Team Risk Overview
            </h2>

            <p class="team-readiness-panel-description">
                Distribution of the latest known risk level across
                assessed employees.
            </p>

        </div>


        <div class="team-readiness-risk-grid">

            <div class="team-readiness-risk-card low">

                <p class="team-readiness-risk-label">
                    Low Risk
                </p>

                <p class="team-readiness-risk-value">
                    {{ $lowRisk }}
                </p>

            </div>


            <div class="team-readiness-risk-card medium">

                <p class="team-readiness-risk-label">
                    Medium Risk
                </p>

                <p class="team-readiness-risk-value">
                    {{ $mediumRisk }}
                </p>

            </div>


            <div class="team-readiness-risk-card high">

                <p class="team-readiness-risk-label">
                    High Risk
                </p>

                <p class="team-readiness-risk-value">
                    {{ $highRisk }}
                </p>

            </div>

        </div>

    </div>


    {{-- Employee Readiness --}}
    <div class="team-readiness-panel">

        <div class="team-readiness-panel-header">

            <h2 class="team-readiness-panel-title">
                Employee Readiness
            </h2>

            <p class="team-readiness-panel-description">
                Latest assessment result for each employee in
                {{ $department->name }}.
            </p>

        </div>


        @if($employeeReadiness->isEmpty())

            <div class="team-readiness-empty">

                <div class="team-readiness-empty-icon">
                    👥
                </div>

                <p class="team-readiness-empty-title">
                    No employees assigned
                </p>

                <p class="team-readiness-empty-description">
                    No employees are currently assigned to this department.
                </p>

            </div>

        @else

            <div class="team-readiness-table-wrap">

                <table class="team-readiness-table">

                    <thead>

                        <tr>

                            <th>
                                Employee
                            </th>

                            <th>
                                Assessment
                            </th>

                            <th>
                                Score
                            </th>

                            <th>
                                Risk
                            </th>

                            <th>
                                Completed
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($employeeReadiness as $employee)

                            <tr>

                                {{-- Employee --}}
                                <td>

                                    <div class="team-readiness-employee">
                                        {{ $employee['user']->name }}
                                    </div>

                                    <div class="team-readiness-email">
                                        {{ $employee['user']->email }}
                                    </div>

                                </td>


                                {{-- Assessment --}}
                                <td>

                                    {{ $employee['attempt']?->assessment?->name
                                        ?? 'Not assessed' }}

                                </td>


                                {{-- Score --}}
                                <td>

                                    @if($employee['score'] !== null)

                                        <span class="team-readiness-score">
                                            {{ number_format($employee['score'], 0) }}%
                                        </span>

                                    @else

                                        <span class="team-readiness-badge neutral">
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

                                        <span class="team-readiness-badge low">
                                            Low
                                        </span>

                                    @elseif($risk === 'medium')

                                        <span class="team-readiness-badge medium">
                                            Medium
                                        </span>

                                    @elseif($risk === 'high')

                                        <span class="team-readiness-badge high">
                                            High
                                        </span>

                                    @else

                                        <span class="team-readiness-badge neutral">
                                            Not Assessed
                                        </span>

                                    @endif

                                </td>


                                {{-- Completed --}}
                                <td>

                                    @if($employee['attempt']?->completed_at)

                                        {{ $employee['attempt']->completed_at->format('M j, Y') }}

                                    @else

                                        <span style="color:#94a3b8;">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td>

                                    @if($employee['attempt'])

                                        <a
                                            href="{{ route(
                                                'assessment.result',
                                                $employee['attempt']
                                            ) }}"
                                            class="team-readiness-result-link"
                                        >
                                            View Result
                                        </a>

                                    @else

                                        <span style="color:#94a3b8;">
                                            Awaiting assessment
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection
