@extends('layouts.app')

@section('title', 'Organisation Reports')

@section('content')

<div class="organisation-report-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="organisation-report-header">

        <div>

            <span class="organisation-report-eyebrow">
                Organisation Analytics
            </span>

            <h1 class="organisation-report-title">
                Organisation Reports
            </h1>

            <p class="organisation-report-description">
                Analyse cybersecurity assessment performance,
                risk and learning progress using flexible reporting filters.
            </p>

        </div>

        <div class="organisation-report-date">
            {{ now()->format('F j, Y') }}
        </div>

    </div>


    {{-- =========================================================
         FILTER PANEL
    ========================================================== --}}

    <div class="organisation-report-filter-card">

        <div class="organisation-report-filter-header">

            <div>
                <h2>Report Filters</h2>

                <p>
                    Adjust the criteria below to change the report results.
                </p>
            </div>

            <span class="organisation-report-filter-range">
                {{ $rangeLabel }}
            </span>

        </div>


        <form
            method="GET"
            action="{{ route('organisation.reports') }}"
            class="organisation-report-filter-form"
        >

            {{-- Time Range --}}
            <div class="organisation-report-filter-field">

                <label for="range">
                    Time Range
                </label>

                <select name="range" id="range">

                    <option
                        value="7"
                        @selected($range === '7')
                    >
                        Last 7 days
                    </option>

                    <option
                        value="30"
                        @selected($range === '30')
                    >
                        Last 30 days
                    </option>

                    <option
                        value="90"
                        @selected($range === '90')
                    >
                        Last 90 days
                    </option>

                    <option
                        value="year"
                        @selected($range === 'year')
                    >
                        This year
                    </option>

                    <option
                        value="custom"
                        @selected($range === 'custom')
                    >
                        Custom range
                    </option>

                </select>

            </div>


            {{-- From --}}
            <div class="organisation-report-filter-field">

                <label for="from">
                    From
                </label>

                <input
                    type="date"
                    name="from"
                    id="from"
                    value="{{ request('from') }}"
                >

            </div>


            {{-- To --}}
            <div class="organisation-report-filter-field">

                <label for="to">
                    To
                </label>

                <input
                    type="date"
                    name="to"
                    id="to"
                    value="{{ request('to') }}"
                >

            </div>


            {{-- Department --}}
            <div class="organisation-report-filter-field">

                <label for="department_id">
                    Department
                </label>

                <select
                    name="department_id"
                    id="department_id"
                >

                    <option value="">
                        All Departments
                    </option>

                    @foreach($departments as $department)

                        <option
                            value="{{ $department->id }}"
                            @selected(
                                (string) $departmentId
                                === (string) $department->id
                            )
                        >
                            {{ $department->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Employee --}}
            <div class="organisation-report-filter-field">

                <label for="employee_id">
                    Employee
                </label>

                <select
                    name="employee_id"
                    id="employee_id"
                >

                    <option value="">
                        All Employees
                    </option>

                    @foreach($employees as $employee)

                        <option
                            value="{{ $employee->id }}"
                            @selected(
                                (string) $employeeId
                                === (string) $employee->id
                            )
                        >
                            {{ $employee->name }}
                            @if($employee->department)
                                — {{ $employee->department->name }}
                            @endif
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Assessment --}}
            <div class="organisation-report-filter-field">

                <label for="assessment_id">
                    Assessment
                </label>

                <select
                    name="assessment_id"
                    id="assessment_id"
                >

                    <option value="">
                        All Assessments
                    </option>

                    @foreach($assessments as $assessment)

                        <option
                            value="{{ $assessment->id }}"
                            @selected(
                                (string) $assessmentId
                                === (string) $assessment->id
                            )
                        >
                            {{ $assessment->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Risk --}}
            <div class="organisation-report-filter-field">

                <label for="risk_level">
                    Risk Level
                </label>

                <select
                    name="risk_level"
                    id="risk_level"
                >

                    <option value="">
                        All Risk Levels
                    </option>

                    <option
                        value="high"
                        @selected($riskLevel === 'high')
                    >
                        High Risk
                    </option>

                    <option
                        value="medium"
                        @selected($riskLevel === 'medium')
                    >
                        Medium Risk
                    </option>

                    <option
                        value="low"
                        @selected($riskLevel === 'low')
                    >
                        Low Risk
                    </option>

                </select>

            </div>


            {{-- Assessment Status --}}
            <div class="organisation-report-filter-field">

                <label for="assessment_status">
                    Assessment Status
                </label>

                <select
                    name="assessment_status"
                    id="assessment_status"
                >

                    <option value="">
                        Completed + Expired
                    </option>

                    <option
                        value="completed"
                        @selected($assessmentStatus === 'completed')
                    >
                        Completed
                    </option>

                    <option
                        value="expired"
                        @selected($assessmentStatus === 'expired')
                    >
                        Expired
                    </option>

                </select>

            </div>


            {{-- Learning Status --}}
            <div class="organisation-report-filter-field">

                <label for="learning_status">
                    Learning-plan Status
                </label>

                <select
                    name="learning_status"
                    id="learning_status"
                >

                    <option value="">
                        All Learning Statuses
                    </option>

                    <option
                        value="not_started"
                        @selected($learningStatus === 'not_started')
                    >
                        Not Started
                    </option>

                    <option
                        value="in_progress"
                        @selected($learningStatus === 'in_progress')
                    >
                        In Progress
                    </option>

                    <option
                        value="completed"
                        @selected($learningStatus === 'completed')
                    >
                        Completed
                    </option>

                    <option
                        value="none"
                        @selected($learningStatus === 'none')
                    >
                        No Learning Plan
                    </option>

                </select>

            </div>


            {{-- Learning Progress --}}
            <div class="organisation-report-filter-field">

                <label for="learning_progress">
                    Learning Progress
                </label>

                <select
                    name="learning_progress"
                    id="learning_progress"
                >

                    <option value="">
                        Any Progress
                    </option>

                    <option
                        value="0"
                        @selected($learningProgress === '0')
                    >
                        0%
                    </option>

                    <option
                        value="1-49"
                        @selected($learningProgress === '1-49')
                    >
                        1–49%
                    </option>

                    <option
                        value="50-99"
                        @selected($learningProgress === '50-99')
                    >
                        50–99%
                    </option>

                    <option
                        value="100"
                        @selected($learningProgress === '100')
                    >
                        100%
                    </option>

                </select>

            </div>


            <div class="organisation-report-filter-actions">

                <a
                    href="{{ route('organisation.reports') }}"
                    class="organisation-report-clear-button"
                >
                    Clear Filters
                </a>

                <button
                    type="submit"
                    class="organisation-report-apply-button"
                >
                    Apply Filters
                </button>

            </div>

        </form>

    </div>


    {{-- =========================================================
         FILTER SUMMARY
    ========================================================== --}}

    <div class="organisation-report-filter-summary">

        <span>
            Reporting period:
        </span>

        <strong>
            {{ $from->format('d M Y') }}
            –
            {{ $to->format('d M Y') }}
        </strong>

        <span class="organisation-report-filter-summary-divider">
            |
        </span>

        <span>
            {{ $totalAttempts }}
            {{ $totalAttempts === 1 ? 'result' : 'results' }}
        </span>

    </div>


    {{-- =========================================================
         KPI CARDS
    ========================================================== --}}

    <div class="organisation-report-stat-grid">

        <div class="organisation-report-stat-card">

            <span>Assessment Attempts</span>

            <strong>
                {{ $totalAttempts }}
            </strong>

            <small>
                Matching selected criteria
            </small>

        </div>


        <div class="organisation-report-stat-card">

            <span>Employees Assessed</span>

            <strong>
                {{ $uniqueEmployees }}
            </strong>

            <small>
                Unique employees in results
            </small>

        </div>


        <div class="organisation-report-stat-card">

            <span>Average Score</span>

            <strong>
                {{ number_format($averageScore, 1) }}%
            </strong>

            <small>
                Across filtered attempts
            </small>

        </div>


        <div class="organisation-report-stat-card">

            <span>High Risk</span>

            <strong>
                {{ $highRisk }}
            </strong>

            <small>
                High-risk results
            </small>

        </div>


        <div class="organisation-report-stat-card">

            <span>Learning Plans</span>

            <strong>
                {{ $totalLearningPlans }}
            </strong>

            <small>
                For filtered employees
            </small>

        </div>


        <div class="organisation-report-stat-card">

            <span>Learning Progress</span>

            <strong>
                {{ number_format($averageLearningProgress, 1) }}%
            </strong>

            <small>
                Average remediation progress
            </small>

        </div>

    </div>


    {{-- =========================================================
         RISK + LEARNING
    ========================================================== --}}

    <div class="organisation-report-two-column">

        <div class="organisation-report-card">

            <div class="organisation-report-card-header">

                <div>
                    <h2>Risk Distribution</h2>

                    <p>
                        Risk levels within the selected reporting criteria.
                    </p>
                </div>

            </div>

            <div class="organisation-report-risk-grid">

                <div class="organisation-report-risk high">
                    <span>High Risk</span>
                    <strong>{{ $highRisk }}</strong>
                </div>

                <div class="organisation-report-risk medium">
                    <span>Medium Risk</span>
                    <strong>{{ $mediumRisk }}</strong>
                </div>

                <div class="organisation-report-risk low">
                    <span>Low Risk</span>
                    <strong>{{ $lowRisk }}</strong>
                </div>

            </div>

        </div>


        <div class="organisation-report-card">

            <div class="organisation-report-card-header">

                <div>
                    <h2>Learning Plan Progress</h2>

                    <p>
                        Remediation progress for employees in the filtered report.
                    </p>
                </div>

            </div>


            <div class="organisation-report-learning-progress">

                <div>
                    <strong>
                        {{ number_format($averageLearningProgress, 1) }}%
                    </strong>

                    <span>
                        Average progress
                    </span>
                </div>

                <div class="organisation-report-progress-track">

                    <div
                        class="organisation-report-progress-bar"
                        style="width: {{ min(100, max(0, $averageLearningProgress)) }}%;"
                    ></div>

                </div>

            </div>


            <div class="organisation-report-learning-grid">

                <div>
                    <span>Total</span>
                    <strong>{{ $totalLearningPlans }}</strong>
                </div>

                <div>
                    <span>Completed</span>
                    <strong>{{ $completedLearningPlans }}</strong>
                </div>

                <div>
                    <span>In Progress</span>
                    <strong>{{ $inProgressLearningPlans }}</strong>
                </div>

                <div>
                    <span>Not Started</span>
                    <strong>{{ $notStartedLearningPlans }}</strong>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         DEPARTMENT REPORT
    ========================================================== --}}

    <div class="organisation-report-card">

        <div class="organisation-report-card-header">

            <div>
                <h2>Department Readiness</h2>

                <p>
                    Department performance using the selected report criteria.
                </p>
            </div>

            <a
                href="{{ route('departments.index') }}"
                class="organisation-report-header-link"
            >
                Manage Departments
            </a>

        </div>


        <div class="organisation-report-table-wrapper">

            <table class="organisation-report-table">

                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Employees</th>
                        <th>Assessed</th>
                        <th>Coverage</th>
                        <th>Average Score</th>
                        <th>High Risk</th>
                        <th>Learning Progress</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($departmentReports as $report)

                        <tr>

                            <td>
                                <strong>
                                    {{ $report['department']->name }}
                                </strong>
                            </td>

                            <td>
                                {{ $report['employees'] }}
                            </td>

                            <td>
                                {{ $report['assessed'] }}
                            </td>

                            <td>
                                <strong>
                                    {{ $report['coverage'] }}%
                                </strong>
                            </td>

                            <td>
                                <strong>
                                    {{ $report['average_score'] }}%
                                </strong>
                            </td>

                            <td>

                                @if($report['high_risk'] > 0)

                                    <span class="organisation-report-inline-risk">
                                        {{ $report['high_risk'] }} High
                                    </span>

                                @else

                                    <span class="organisation-report-none">
                                        None
                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="organisation-report-table-progress">

                                    <span>
                                        {{ $report['learning_progress'] }}%
                                    </span>

                                    <div class="organisation-report-small-progress">
                                        <div
                                            style="width: {{ $report['learning_progress'] }}%;"
                                        ></div>
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         EMPLOYEE REPORT
    ========================================================== --}}

    <div class="organisation-report-card">

        <div class="organisation-report-card-header">

            <div>
                <h2>Employee Readiness</h2>

                <p>
                    Latest matching assessment and learning-plan position for each employee.
                </p>
            </div>

        </div>


        <div class="organisation-report-table-wrapper">

            <table class="organisation-report-table">

                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Score</th>
                        <th>Risk</th>
                        <th>Learning Plans</th>
                        <th>Learning Progress</th>
                        <th>Result</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($employeeReports as $report)

                        @php
                            $employeeRisk = strtolower(
                                (string) ($report['risk'] ?? '')
                            );
                        @endphp

                        <tr>

                            <td>

                                <div class="organisation-report-person">

                                    <div class="organisation-report-avatar">
                                        {{ strtoupper(
                                            substr(
                                                $report['employee']->name,
                                                0,
                                                1
                                            )
                                        ) }}
                                    </div>

                                    <strong>
                                        {{ $report['employee']->name }}
                                    </strong>

                                </div>

                            </td>

                            <td>
                                {{ $report['employee']->department?->name ?? '—' }}
                            </td>

                            <td>

                                @if($report['score'] !== null)

                                    <strong>
                                        {{ number_format(
                                            $report['score'],
                                            1
                                        ) }}%
                                    </strong>

                                @else

                                    <span class="organisation-report-none">
                                        —
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($employeeRisk === 'high')

                                    <span class="organisation-report-risk-badge high">
                                        High
                                    </span>

                                @elseif($employeeRisk === 'medium')

                                    <span class="organisation-report-risk-badge medium">
                                        Medium
                                    </span>

                                @elseif($employeeRisk === 'low')

                                    <span class="organisation-report-risk-badge low">
                                        Low
                                    </span>

                                @else

                                    <span class="organisation-report-risk-badge neutral">
                                        —
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $report['learning']['total'] }}
                            </td>

                            <td>

                                <div class="organisation-report-table-progress">

                                    <span>
                                        {{ $report['learning']['progress'] }}%
                                    </span>

                                    <div class="organisation-report-small-progress">
                                        <div
                                            style="width: {{ $report['learning']['progress'] }}%;"
                                        ></div>
                                    </div>

                                </div>

                            </td>

                            <td>

                                @if($report['attempt'])

                                    <a
                                        href="{{ route(
                                            'assessment.result',
                                            $report['attempt']
                                        ) }}"
                                        class="organisation-report-result-link"
                                    >
                                        View Result
                                    </a>

                                @else

                                    <span class="organisation-report-none">
                                        No result
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7">

                                <div class="organisation-report-empty">
                                    No employees match the selected criteria.
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         ASSESSMENT ACTIVITY
    ========================================================== --}}

    <div class="organisation-report-card">

        <div class="organisation-report-card-header">

            <div>
                <h2>Assessment Activity</h2>

                <p>
                    Assessment attempts matching the current filters.
                </p>
            </div>

        </div>


        <div class="organisation-report-table-wrapper">

            <table class="organisation-report-table">

                <thead>

                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Assessment</th>
                        <th>Score</th>
                        <th>Risk</th>
                        <th>Status</th>
                        <th>Completed</th>
                        <th>Result</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($attempts as $attempt)

                        @php
                            $attemptRisk = strtolower(
                                (string) $attempt->risk_level
                            );
                        @endphp

                        <tr>

                            <td>
                                <strong>
                                    {{ $attempt->user?->name ?? 'Unknown' }}
                                </strong>
                            </td>

                            <td>
                                {{ $attempt->user?->department?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $attempt->assessment?->name ?? 'Assessment' }}
                            </td>

                            <td>
                                <strong>
                                    {{ number_format(
                                        (float) $attempt->score_percentage,
                                        1
                                    ) }}%
                                </strong>
                            </td>

                            <td>

                                @if($attemptRisk === 'high')

                                    <span class="organisation-report-risk-badge high">
                                        High
                                    </span>

                                @elseif($attemptRisk === 'medium')

                                    <span class="organisation-report-risk-badge medium">
                                        Medium
                                    </span>

                                @elseif($attemptRisk === 'low')

                                    <span class="organisation-report-risk-badge low">
                                        Low
                                    </span>

                                @else

                                    <span class="organisation-report-risk-badge neutral">
                                        —
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ ucfirst($attempt->status) }}
                            </td>

                            <td>
                                {{ $attempt->completed_at
                                    ? $attempt->completed_at->format('M j, Y')
                                    : '—'
                                }}
                            </td>

                            <td>

                                <a
                                    href="{{ route(
                                        'assessment.result',
                                        $attempt
                                    ) }}"
                                    class="organisation-report-result-link"
                                >
                                    View Result
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">

                                <div class="organisation-report-empty">
                                    No assessment activity matches the selected criteria.
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<style>

/* =========================================================
   ORGANISATION REPORT PAGE
========================================================= */

.organisation-report-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.organisation-report-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 26px;
}

.organisation-report-eyebrow {
    display: block;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #667085;
}

.organisation-report-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.organisation-report-description {
    margin: 0;
    max-width: 850px;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.organisation-report-date {
    padding-top: 5px;
    white-space: nowrap;
    font-size: 13px;
    color: #667085;
}


/* =========================================================
   FILTER CARD
========================================================= */

.organisation-report-filter-card {
    margin-bottom: 16px;
    overflow: hidden;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.organisation-report-filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 24px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.organisation-report-filter-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    color: #172033;
}

.organisation-report-filter-header p {
    margin: 0;
    font-size: 13px;
    color: #667085;
}

.organisation-report-filter-range {
    display: inline-flex;
    padding: 6px 10px;
    border-radius: 999px;
    background: #eef1f4;
    color: #667085;
    font-size: 10px;
    font-weight: 700;
}

.organisation-report-filter-form {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    padding: 22px 24px;
}

.organisation-report-filter-field label {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 700;
    color: #172033;
}

.organisation-report-filter-field input,
.organisation-report-filter-field select {
    width: 100%;
    height: 41px;
    box-sizing: border-box;
    padding: 0 11px;
    border: 1px solid #d7dce2;
    border-radius: 8px;
    background: #fff;
    color: #172033;
    font-family: inherit;
    font-size: 12px;
}

.organisation-report-filter-field input:focus,
.organisation-report-filter-field select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.10);
}

.organisation-report-filter-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 9px;
    padding-top: 4px;
}

.organisation-report-clear-button,
.organisation-report-apply-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 39px;
    padding: 9px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

.organisation-report-clear-button {
    background: #eef1f4;
    color: #172033;
}

.organisation-report-apply-button {
    border: 0;
    background: #2563eb;
    color: #fff;
    cursor: pointer;
}


/* =========================================================
   FILTER SUMMARY
========================================================= */

.organisation-report-filter-summary {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 20px;
    padding: 12px 14px;
    border-radius: 9px;
    background: #f7f8fa;
    font-size: 12px;
    color: #667085;
}

.organisation-report-filter-summary strong {
    color: #172033;
}

.organisation-report-filter-summary-divider {
    opacity: .4;
}


/* =========================================================
   KPI
========================================================= */

.organisation-report-stat-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.organisation-report-stat-card {
    min-height: 105px;
    padding: 18px 20px;
    box-sizing: border-box;
    border-radius: 15px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 5px 20px rgba(15,23,42,.045);
}

.organisation-report-stat-card span,
.organisation-report-stat-card small {
    display: block;
}

.organisation-report-stat-card span {
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #667085;
}

.organisation-report-stat-card strong {
    display: block;
    margin-bottom: 5px;
    font-size: 27px;
    line-height: 1;
    color: #172033;
}

.organisation-report-stat-card small {
    font-size: 11px;
    color: #8a94a6;
}


/* =========================================================
   TWO COLUMN
========================================================= */

.organisation-report-two-column {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}


/* =========================================================
   CARDS
========================================================= */

.organisation-report-card {
    margin-bottom: 24px;
    overflow: hidden;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.organisation-report-two-column .organisation-report-card {
    margin-bottom: 0;
}

.organisation-report-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 24px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.organisation-report-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    line-height: 1.3;
    color: #172033;
}

.organisation-report-card-header p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}

.organisation-report-header-link,
.organisation-report-result-link {
    font-size: 12px;
    font-weight: 650;
    text-decoration: none;
    white-space: nowrap;
}


/* =========================================================
   RISK
========================================================= */

.organisation-report-risk-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    padding: 24px;
}

.organisation-report-risk {
    padding: 18px;
    border-radius: 12px;
}

.organisation-report-risk span {
    display: block;
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 600;
}

.organisation-report-risk strong {
    display: block;
    font-size: 28px;
    line-height: 1;
}

.organisation-report-risk.high {
    background: #fde8e8;
    color: #b42318;
}

.organisation-report-risk.medium {
    background: #fff4d6;
    color: #956900;
}

.organisation-report-risk.low {
    background: #e6f6ee;
    color: #18794e;
}


/* =========================================================
   LEARNING
========================================================= */

.organisation-report-learning-progress {
    padding: 22px 24px 12px;
}

.organisation-report-learning-progress > div:first-child {
    display: flex;
    align-items: baseline;
    gap: 10px;
    margin-bottom: 12px;
}

.organisation-report-learning-progress strong {
    font-size: 35px;
    line-height: 1;
    color: #172033;
}

.organisation-report-learning-progress span {
    font-size: 13px;
    color: #667085;
}

.organisation-report-progress-track {
    width: 100%;
    height: 9px;
    overflow: hidden;
    border-radius: 999px;
    background: #edf0f3;
}

.organisation-report-progress-bar {
    height: 100%;
    border-radius: inherit;
    background: #52677d;
}

.organisation-report-learning-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
    padding: 0 24px 24px;
}

.organisation-report-learning-grid div {
    padding: 13px;
    border-radius: 11px;
    background: #f4f6f8;
}

.organisation-report-learning-grid span,
.organisation-report-learning-grid strong {
    display: block;
}

.organisation-report-learning-grid span {
    margin-bottom: 5px;
    font-size: 11px;
    color: #667085;
}

.organisation-report-learning-grid strong {
    font-size: 18px;
}


/* =========================================================
   TABLES
========================================================= */

.organisation-report-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.organisation-report-table {
    width: 100%;
    border-collapse: collapse;
}

.organisation-report-table th {
    padding: 12px 17px;
    text-align: left;
    white-space: nowrap;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    color: #667085;
    border-bottom: 1px solid rgba(15,23,42,.09);
}

.organisation-report-table td {
    padding: 15px 17px;
    font-size: 12px;
    line-height: 1.4;
    color: #445064;
    vertical-align: middle;
    border-bottom: 1px solid rgba(15,23,42,.07);
}

.organisation-report-table tbody tr:last-child td {
    border-bottom: 0;
}

.organisation-report-table td strong {
    color: #172033;
}


/* =========================================================
   PEOPLE
========================================================= */

.organisation-report-person {
    display: flex;
    align-items: center;
    gap: 9px;
}

.organisation-report-avatar {
    width: 30px;
    height: 30px;
    min-width: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #eef2f5;
    color: #334155;
    font-size: 10px;
    font-weight: 750;
}


/* =========================================================
   PROGRESS
========================================================= */

.organisation-report-table-progress {
    min-width: 110px;
}

.organisation-report-table-progress > span {
    display: block;
    margin-bottom: 5px;
    font-size: 10px;
    font-weight: 650;
}

.organisation-report-small-progress {
    width: 100%;
    height: 6px;
    overflow: hidden;
    border-radius: 999px;
    background: #edf0f3;
}

.organisation-report-small-progress > div {
    height: 100%;
    border-radius: inherit;
    background: #52677d;
}


/* =========================================================
   RISK BADGES
========================================================= */

.organisation-report-risk-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 8px;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 700;
}

.organisation-report-risk-badge.high {
    background: #fde8e8;
    color: #b42318;
}

.organisation-report-risk-badge.medium {
    background: #fff4d6;
    color: #956900;
}

.organisation-report-risk-badge.low {
    background: #e6f6ee;
    color: #18794e;
}

.organisation-report-risk-badge.neutral {
    background: #eef1f4;
    color: #667085;
}

.organisation-report-inline-risk {
    display: inline-flex;
    padding: 4px 7px;
    border-radius: 999px;
    background: #fde8e8;
    color: #b42318;
    font-size: 9px;
    font-weight: 700;
}

.organisation-report-none {
    color: #8a94a6;
}


/* =========================================================
   EMPTY
========================================================= */

.organisation-report-empty {
    padding: 42px 20px;
    text-align: center;
    font-size: 12px;
    color: #8a94a6;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .organisation-report-filter-form {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .organisation-report-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .organisation-report-two-column {
        grid-template-columns: 1fr;
    }

    .organisation-report-learning-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 700px) {

    .organisation-report-page {
        padding: 20px 16px 40px;
    }

    .organisation-report-header {
        flex-direction: column;
    }

    .organisation-report-date {
        padding-top: 0;
    }

    .organisation-report-filter-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .organisation-report-filter-form {
        grid-template-columns: 1fr;
    }

    .organisation-report-stat-grid,
    .organisation-report-risk-grid,
    .organisation-report-learning-grid {
        grid-template-columns: 1fr;
    }

    .organisation-report-filter-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .organisation-report-clear-button,
    .organisation-report-apply-button {
        width: 100%;
    }

    .organisation-report-card-header {
        padding-left: 18px;
        padding-right: 18px;
    }
}

</style>

@endsection
