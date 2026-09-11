@extends('layouts.app')

@section('content')

<div class="dashboard-header">

    <div>
        <h1 class="dashboard-title">Team Readiness</h1>

        <p class="dashboard-description">
            Monitor cybersecurity readiness across your department.
        </p>
    </div>

    <div class="dashboard-date">
        {{ now()->format('l, F j, Y') }}
    </div>

</div>

{{-- Department --}}
<div class="overview-card" style="margin-bottom: 24px;">

    <h2 class="section-heading">
        {{ $department->name }}
    </h2>

    <p class="dashboard-description">
        {{ $department->organisation->name }}
    </p>

</div>


{{-- Summary --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-card-label">Team Members</div>

        <div class="stat-card-value">
            {{ $totalEmployees }}
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-card-label">Assessed</div>

        <div class="stat-card-value">
            {{ $assessedEmployees }}
        </div>

        <div class="dashboard-description">
            {{ $assessmentCoverage }}% coverage
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-card-label">Average Score</div>

        <div class="stat-card-value">
            @if($averageScore !== null)
                {{ number_format($averageScore, 1) }}%
            @else
                —
            @endif
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-card-label">Not Assessed</div>

        <div class="stat-card-value">
            {{ $notAssessedEmployees }}
        </div>
    </div>

</div>


{{-- Risk Summary --}}
<div class="overview-card" style="margin-top: 24px;">

    <h2 class="section-heading">
        Team Risk Overview
    </h2>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-card-label">Low Risk</div>
            <div class="stat-card-value">
                {{ $lowRisk }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-label">Medium Risk</div>
            <div class="stat-card-value">
                {{ $mediumRisk }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-label">High Risk</div>
            <div class="stat-card-value">
                {{ $highRisk }}
            </div>
        </div>

    </div>

</div>


{{-- Employee Readiness --}}
<div class="overview-card" style="margin-top: 24px;">

    <div style="margin-bottom: 20px;">

        <h2 class="section-heading">
            Employee Readiness
        </h2>

        <p class="dashboard-description">
            Latest assessment result for each employee.
        </p>

    </div>


    @if($employeeReadiness->isEmpty())

        <div class="dashboard-description">
            No employees are currently assigned to this department.
        </div>

    @else

        <div style="overflow-x:auto;">

            <table style="width:100%; border-collapse:collapse;">

                <thead>

                    <tr>

                        <th style="text-align:left; padding:12px;">
                            Employee
                        </th>

                        <th style="text-align:left; padding:12px;">
                            Assessment
                        </th>

                        <th style="text-align:left; padding:12px;">
                            Score
                        </th>

                        <th style="text-align:left; padding:12px;">
                            Risk
                        </th>

                        <th style="text-align:left; padding:12px;">
                            Completed
                        </th>

                        <th style="text-align:left; padding:12px;">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($employeeReadiness as $employee)

                        <tr style="border-top:1px solid #e5e7eb;">

                            {{-- Employee --}}
                            <td style="padding:12px;">

                                <strong>
                                    {{ $employee['user']->name }}
                                </strong>

                                <div style="font-size:13px; opacity:.7;">
                                    {{ $employee['user']->email }}
                                </div>

                            </td>


                            {{-- Assessment --}}
                            <td style="padding:12px;">

                                {{ $employee['attempt']?->assessment?->name ?? 'Not assessed' }}

                            </td>


                            {{-- Score --}}
                            <td style="padding:12px;">

                                @if($employee['score'] !== null)

                                    <strong>
                                        {{ number_format($employee['score'], 0) }}%
                                    </strong>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Risk --}}
                            <td style="padding:12px;">

                                @if($employee['risk_level'])

                                    <span class="status-badge">
                                        {{ $employee['risk_level'] }}
                                    </span>

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Completed --}}
                            <td style="padding:12px;">

                                @if($employee['attempt']?->completed_at)

                                    {{ $employee['attempt']->completed_at->format('M j, Y') }}

                                @else

                                    —

                                @endif

                            </td>


                            {{-- Action --}}
                            <td style="padding:12px;">

                                @if($employee['attempt'])

                                    <a
                                        href="{{ route('assessment.result', $employee['attempt']) }}"
                                        class="btn btn-secondary"
                                    >
                                        View Result
                                    </a>

                                @else

                                    <span class="dashboard-description">
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

@endsection
