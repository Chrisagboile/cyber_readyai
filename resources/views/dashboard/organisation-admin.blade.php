@extends('layouts.app')

@section('content')
<div class="dashboard-page organisation-admin-dashboard">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="dashboard-header">
        <div>
            <h1 class="dashboard-title">Organisation Dashboard</h1>
            <p class="dashboard-description">
                Organisation-wide cybersecurity readiness, assessment performance,
                risk and learning progress.
            </p>
        </div>

        <div class="dashboard-date">
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>


    {{-- =========================================================
        ORGANISATION READINESS SUMMARY
    ========================================================== --}}
    <div class="overview-card organisation-readiness-card">
        <div class="organisation-readiness-main">
            <div>
                <span class="eyebrow-label">Organisation Readiness</span>

                <div class="readiness-heading">
                    @if($overallRisk === 'High')
                        <span class="status-badge status-high">High</span>
                    @elseif($overallRisk === 'Medium')
                        <span class="status-badge status-medium">Medium</span>
                    @elseif($overallRisk === 'Low')
                        <span class="status-badge status-low">Low</span>
                    @else
                        <span class="status-badge">Not Assessed</span>
                    @endif

                    <h2>{{ $organisation->name }}</h2>
                </div>

                <p>
                    {{ $employeeCount }} employees across
                    {{ $departments->count() }} departments.
                    Assessment coverage is
                    <strong>{{ number_format($assessmentCoverage, 1) }}%</strong>
                    with an organisation-wide average score of
                    <strong>{{ number_format($averageScore, 1) }}%</strong>.
                </p>
            </div>

            <div class="readiness-summary-stats">
                <div>
                    <span>High Risk</span>
                    <strong>{{ $highRisk }}</strong>
                </div>

                <div>
                    <span>Medium Risk</span>
                    <strong>{{ $mediumRisk }}</strong>
                </div>

                <div>
                    <span>Low Risk</span>
                    <strong>{{ $lowRisk }}</strong>
                </div>
            </div>
        </div>
    </div>


    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}
    <div class="stats-grid organisation-stats-grid">

        {{-- Employees --}}
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div>
                <span class="stat-card-label">Employees</span>
                <strong class="stat-card-value">{{ $employeeCount }}</strong>
                <span class="stat-card-meta">Organisation employees</span>
            </div>
        </div>

        {{-- Managers --}}
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M17 3.5a4 4 0 0 1 0 7"/>
                </svg>
            </div>

            <div>
                <span class="stat-card-label">Managers</span>
                <strong class="stat-card-value">{{ $managerCount }}</strong>
                <span class="stat-card-meta">Department managers</span>
            </div>
        </div>

        {{-- Departments --}}
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 21h18"/>
                    <path d="M6 21V5l6-3 6 3v16"/>
                    <path d="M9 9h.01"/>
                    <path d="M15 9h.01"/>
                    <path d="M9 13h.01"/>
                    <path d="M15 13h.01"/>
                    <path d="M9 17h.01"/>
                    <path d="M15 17h.01"/>
                </svg>
            </div>

            <div>
                <span class="stat-card-label">Departments</span>
                <strong class="stat-card-value">{{ $departments->count() }}</strong>
                <span class="stat-card-meta">Organisation departments</span>
            </div>
        </div>

        {{-- Assessments --}}
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h6"/>
                    <path d="M8 9h2"/>
                </svg>
            </div>

            <div>
                <span class="stat-card-label">Assessments</span>
                <strong class="stat-card-value">{{ $assessments->count() }}</strong>
                <span class="stat-card-meta">Assessments created</span>
            </div>
        </div>

        {{-- Completed --}}
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>

            <div>
                <span class="stat-card-label">Completed Assessments</span>
                <strong class="stat-card-value">{{ $completedAssessmentCount }}</strong>
                <span class="stat-card-meta">Completed or expired attempts</span>
            </div>
        </div>

        {{-- Coverage --}}
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3v18h18"/>
                    <path d="m7 15 4-4 3 3 6-7"/>
                </svg>
            </div>

            <div>
                <span class="stat-card-label">Assessment Coverage</span>
                <strong class="stat-card-value">
                    {{ number_format($assessmentCoverage, 1) }}%
                </strong>
                 <span class="stat-card-meta">
                    {{ $assessedEmployeeIds->count() }} of {{ $employeeCount }} employees assessed
                </span>
            </div>
        </div>

        {{-- Average Score --}}
        <div class="stat-card">
            <div class="stat-card-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 3v18"/>
                    <path d="M17 8a5 5 0 0 0-5-5 5 5 0 0 0-5 5c0 3 2 4 5 5s5 2 5 5a5 5 0 0 1-5 5 5 5 0 0 1-5-5"/>
                </svg>
            </div>

            <div>
                <span class="stat-card-label">Average Score</span>
                <strong class="stat-card-value">
                    {{ number_format($averageScore, 1) }}%
                </strong>
                <span class="stat-card-meta">Organisation-wide average</span>
            </div>
        </div>

    </div>


    {{-- =========================================================
        RISK + LEARNING
    ========================================================== --}}
    <div class="dashboard-two-column">

        {{-- Risk Distribution --}}
        <div class="overview-card">
            <div class="section-heading">
                <div>
                    <h2>Risk Distribution</h2>
                    <p>Latest completed assessment risk level for each assessed employee.</p>
                </div>
            </div>

            <div class="risk-overview">

                <div class="risk-item">
                    <div class="risk-item-header">
                        <span>High Risk</span>
                        <strong>{{ $highRisk }}</strong>
                    </div>

                    <div class="dashboard-progress">
                        <div
                            class="dashboard-progress-bar risk-high-progress"
                            style="width: {{ $employeeCount > 0 ? min(100, ($highRisk / $employeeCount) * 100) : 0 }}%;"
                        ></div>
                    </div>
                </div>

                <div class="risk-item">
                    <div class="risk-item-header">
                        <span>Medium Risk</span>
                        <strong>{{ $mediumRisk }}</strong>
                    </div>

                    <div class="dashboard-progress">
                        <div
                            class="dashboard-progress-bar risk-medium-progress"
                            style="width: {{ $employeeCount > 0 ? min(100, ($mediumRisk / $employeeCount) * 100) : 0 }}%;"
                        ></div>
                    </div>
                </div>

                <div class="risk-item">
                    <div class="risk-item-header">
                        <span>Low Risk</span>
                        <strong>{{ $lowRisk }}</strong>
                    </div>

                    <div class="dashboard-progress">
                        <div
                            class="dashboard-progress-bar risk-low-progress"
                            style="width: {{ $employeeCount > 0 ? min(100, ($lowRisk / $employeeCount) * 100) : 0 }}%;"
                        ></div>
                    </div>
                </div>

                <div class="risk-total">
                    <span>Employees not yet assessed</span>
                    <strong>{{ max(0, $employeeCount - $assessedEmployeeIds->count()) }}</strong>                </div>
            </div>
        </div>


        {{-- Learning Plan Progress --}}
        <div class="overview-card">
            <div class="section-heading">
                <div>
                    <h2>Learning-plan Progress</h2>
                    <p>Organisation-wide remediation and learning activity.</p>
                </div>
            </div>

            <div class="learning-overview">

                <div class="learning-progress-number">
                    <strong>{{ number_format($averageLearningProgress, 1) }}%</strong>
                    <span>Average progress</span>
                </div>

                <div class="dashboard-progress dashboard-progress-large">
                    <div
                        class="dashboard-progress-bar"
                        style="width: {{ min(100, max(0, $averageLearningProgress)) }}%;"
                    ></div>
                </div>

                <div class="learning-stat-grid">

                    <div>
                        <span>Total Plans</span>
                        <strong>{{ $learningPlanCount }}</strong>
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

    </div>


    {{-- =========================================================
        DEPARTMENT READINESS COMPARISON
    ========================================================== --}}
    <div class="overview-card">
        <div class="section-heading">
            <div>
                <h2>Department Readiness Comparison</h2>
                <p>Compare cybersecurity readiness across your organisation.</p>
            </div>

            <a href="{{ route('departments.index') }}" class="section-action">
                Manage Departments
            </a>
        </div>

        @if($departmentReadiness->count())
            <div class="table-responsive">
                <table class="dashboard-table">
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
                        @foreach($departmentReadiness as $row)
                            <tr>
                                <td>
                                    <strong>{{ $row['department']->name }}</strong>
                                </td>

                                <td>
                                    {{ $row['employee_count'] }}
                                </td>

                                <td>
                                    {{ $row['assessed_count'] }}
                                </td>

                                <td>
                                    <div class="table-progress">
                                        <span>{{ number_format($row['coverage'], 1) }}%</span>

                                        <div class="dashboard-progress">
                                            <div
                                                class="dashboard-progress-bar"
                                                style="width: {{ min(100, max(0, $row['coverage'])) }}%;"
                                            ></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <strong>{{ number_format($row['average_score'], 1) }}%</strong>
                                </td>

                                <td>
                                    @if($row['high_risk'] > 0)
                                        <span class="status-badge status-high">
                                            {{ $row['high_risk'] }} High
                                        </span>
                                    @else
                                        <span class="status-badge status-low">
                                            None
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ number_format($row['learning_progress'], 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <h3>No departments yet</h3>
                <p>Create departments to begin tracking departmental readiness.</p>

                <a href="{{ route('departments.create') }}" class="btn btn-primary">
                    Create Department
                </a>
            </div>
        @endif
    </div>


    {{-- =========================================================
        EMPLOYEES REQUIRING ATTENTION
    ========================================================== --}}
    <div class="overview-card">
        <div class="section-heading">
            <div>
                <h2>Employees Requiring Attention</h2>
                <p>Employees who may require assessment or remediation follow-up.</p>
            </div>
        </div>

        @php
            $attentionEmployees = $organisationUsers
                ->filter(fn ($member) => $member->hasRole('employee'))
                ->filter(function ($employee) use ($latestAttempts, $learningPlans) {
                    $attempt = $latestAttempts->get($employee->id);

                    $isHighRisk = $attempt
                        && strtolower((string) $attempt->risk_level) === 'high';

                    $isNotAssessed = ! $attempt;

                    $hasIncompletePlans = $learningPlans
                        ->where('user_id', $employee->id)
                        ->contains(
                            fn ($plan) => $plan->status !== 'completed'
                        );

                    return $isHighRisk || $isNotAssessed || $hasIncompletePlans;
                })
                ->take(8);
        @endphp

        @if($attentionEmployees->count())
            <div class="attention-list">

                @foreach($attentionEmployees as $employee)
                    @php
                        $attempt = $latestAttempts->get($employee->id);

                        $employeePlans = $learningPlans
                            ->where('user_id', $employee->id);

                        $incompletePlans = $employeePlans
                            ->filter(fn ($plan) => $plan->status !== 'completed')
                            ->count();
                    @endphp

                    <div class="attention-row">

                        <div class="attention-employee">
                            <div class="attention-avatar">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>

                            <div>
                                <strong>{{ $employee->name }}</strong>

                                <span>
                                    {{ optional($employee->department)->name ?? 'No department' }}
                                </span>
                            </div>
                        </div>

                        <div class="attention-reasons">

                            @if(!$attempt)
                                <span class="status-badge">
                                    Not Assessed
                                </span>
                            @endif

                            @if(
                                $attempt &&
                                strtolower((string) $attempt->risk_level) === 'high'
                            )
                                <span class="status-badge status-high">
                                    High Risk
                                </span>
                            @endif

                            @if($incompletePlans > 0)
                                <span class="status-badge status-medium">
                                    {{ $incompletePlans }} Learning Plan{{ $incompletePlans === 1 ? '' : 's' }} Incomplete
                                </span>
                            @endif

                        </div>

                        @if($attempt)
                            <a
                                href="{{ route('assessment.result', $attempt) }}"
                                class="btn btn-secondary btn-sm"
                            >
                                View Result
                            </a>
                        @else
                            <a
                                href="{{ route('organisation.assessments') }}"
                                class="btn btn-secondary btn-sm"
                            >
                                Manage Assessments
                            </a>
                        @endif

                    </div>
                @endforeach

            </div>
        @else
            <div class="empty-state">
                <h3>No employees currently require attention</h3>
                <p>
                    Your assessed employees have no outstanding high-risk or
                    learning-plan issues.
                </p>
            </div>
        @endif
    </div>


    {{-- =========================================================
        RECENT ASSESSMENT ACTIVITY
    ========================================================== --}}
    <div class="overview-card">
        <div class="section-heading">
            <div>
                <h2>Recent Assessment Activity</h2>
                <p>The latest assessment activity across the organisation.</p>
            </div>

            <a
                href="{{ route('organisation.assessments') }}"
                class="section-action"
            >
                View Assessments
            </a>
        </div>

        @if($recentAttempts->count())
            <div class="table-responsive">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Assessment</th>
                            <th>Score</th>
                            <th>Risk Level</th>
                            <th>Completed</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($recentAttempts as $attempt)
                            @php
                                $risk = strtolower((string) $attempt->risk_level);
                            @endphp

                            <tr>

                                <td>
                                    <strong>
                                        {{ optional($attempt->user)->name ?? 'Unknown Employee' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ optional($attempt->assessment)->name ?? 'Assessment' }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format((float) $attempt->score_percentage, 1) }}%
                                    </strong>
                                </td>

                                <td>
                                    @if($risk === 'high')
                                        <span class="status-badge status-high">High</span>
                                    @elseif($risk === 'medium')
                                        <span class="status-badge status-medium">Medium</span>
                                    @elseif($risk === 'low')
                                        <span class="status-badge status-low">Low</span>
                                    @else
                                        <span class="status-badge">Not Assessed</span>
                                    @endif
                                </td>

                                <td>
                                    {{ optional($attempt->completed_at)->format('M j, Y') ?? '—' }}
                                </td>

                                <td class="table-action-cell">
                                    <a
                                        href="{{ route('assessment.result', $attempt) }}"
                                        class="btn btn-secondary btn-sm"
                                    >
                                        View Result
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <h3>No assessment activity yet</h3>
                <p>
                    Completed assessment activity will appear here once employees
                    begin completing assessments.
                </p>
            </div>
        @endif
    </div>


    {{-- =========================================================
        QUICK ACTIONS
    ========================================================== --}}
    <div class="overview-card">
        <div class="section-heading">
            <div>
                <h2>Quick Actions</h2>
                <p>Common organisation administration tasks.</p>
            </div>
        </div>

        <div class="action-grid">

            <a href="{{ route('organisation.users') }}" class="action-card">
                <div class="action-card-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M19 8v6"/>
                        <path d="M22 11h-6"/>
                    </svg>
                </div>

                <div>
                    <strong>Manage Users</strong>
                    <span>Add, manage and review organisation users.</span>
                </div>
            </a>

            <a href="{{ route('departments.index') }}" class="action-card">
                <div class="action-card-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 21h18"/>
                        <path d="M6 21V5l6-3 6 3v16"/>
                        <path d="M9 9h.01"/>
                        <path d="M15 9h.01"/>
                        <path d="M9 13h.01"/>
                        <path d="M15 13h.01"/>
                    </svg>
                </div>

                <div>
                    <strong>Manage Departments</strong>
                    <span>Organise employees and department managers.</span>
                </div>
            </a>

            <a href="{{ route('organisation.assessments') }}" class="action-card">
                <div class="action-card-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 4h16v16H4z"/>
                        <path d="M8 8h8"/>
                        <path d="M8 12h8"/>
                        <path d="M8 16h5"/>
                    </svg>
                </div>

                <div>
                    <strong>Manage Assessments</strong>
                    <span>Review and manage organisation assessments.</span>
                </div>
            </a>

            <a href="{{ route('organisation.reports') }}" class="action-card">
                <div class="action-card-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 3v18h18"/>
                        <path d="M7 16l4-5 3 3 5-7"/>
                    </svg>
                </div>

                <div>
                    <strong>View Reports</strong>
                    <span>Explore detailed organisation reporting.</span>
                </div>
            </a>

            <a href="{{ route('organisation.training') }}" class="action-card">
                <div class="action-card-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2 3 7l9 5 9-5-9-5Z"/>
                        <path d="m6 10 6 3 6-3"/>
                        <path d="M6 14v3c3 2 9 2 12 0v-3"/>
                    </svg>
                </div>

                <div>
                    <strong>Training</strong>
                    <span>Manage learning and remediation activity.</span>
                </div>
            </a>

        </div>
    </div>

</div>

<style>
/* =========================================================
   ORGANISATION ADMIN DASHBOARD
   Self-contained dashboard styling
========================================================= */

    .dashboard-page {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        padding: 28px 32px 50px;
        box-sizing: border-box;
    }

    /* ---------------------------------------------------------
    HEADER
    --------------------------------------------------------- */

    .dashboard-header {
        display: flex !important;
        align-items: flex-start !important;
        justify-content: space-between !important;
        gap: 24px !important;
        margin-bottom: 28px !important;
    }

    .dashboard-title {
        margin: 0 0 8px !important;
        font-size: 30px !important;
        line-height: 1.15 !important;
        font-weight: 700 !important;
        letter-spacing: -0.02em !important;
    }

    .dashboard-description {
        margin: 0 !important;
        font-size: 15px !important;
        line-height: 1.6 !important;
        opacity: .68 !important;
    }

    .dashboard-date {
        white-space: nowrap !important;
        font-size: 13px !important;
        opacity: .6 !important;
        padding-top: 5px !important;
    }

    /* ---------------------------------------------------------
    SHARED CARDS
    --------------------------------------------------------- */

    .overview-card {
        width: 100% !important;
        box-sizing: border-box !important;
        padding: 24px !important;
        margin-bottom: 24px !important;
        border-radius: 16px !important;
        background: #ffffff !important;
        border: 1px solid rgba(15, 23, 42, .08) !important;
        box-shadow: 0 6px 24px rgba(15, 23, 42, .05) !important;
    }

    .section-heading {
        display: flex !important;
        align-items: flex-start !important;
        justify-content: space-between !important;
        gap: 20px !important;
        margin-bottom: 20px !important;
    }

    .section-heading h2 {
        margin: 0 0 5px !important;
        font-size: 19px !important;
        line-height: 1.3 !important;
        font-weight: 700 !important;
    }

    .section-heading p {
        margin: 0 !important;
        font-size: 13px !important;
        line-height: 1.5 !important;
        opacity: .62 !important;
    }

    .section-action {
        display: inline-flex !important;
        align-items: center !important;
        white-space: nowrap !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }

    /* ---------------------------------------------------------
    ORGANISATION READINESS
    --------------------------------------------------------- */

    .organisation-readiness-card {
        margin-bottom: 24px !important;
    }

    .organisation-readiness-main {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        gap: 40px !important;
    }

    .eyebrow-label {
        display: block !important;
        margin-bottom: 9px !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: .1em !important;
        opacity: .55 !important;
    }

    .readiness-heading {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 12px !important;
        margin-bottom: 10px !important;
    }

    .readiness-heading h2 {
        margin: 0 !important;
        font-size: 23px !important;
        line-height: 1.25 !important;
        font-weight: 700 !important;
    }

    .organisation-readiness-main > div:first-child > p {
        margin: 0 !important;
        max-width: 760px !important;
        font-size: 14px !important;
        line-height: 1.65 !important;
        opacity: .72 !important;
    }

    .readiness-summary-stats {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(105px, 1fr)) !important;
        gap: 12px !important;
        min-width: 350px !important;
    }

    .readiness-summary-stats > div {
        padding: 16px !important;
        border-radius: 12px !important;
        background: #f4f6f8 !important;
    }

    .readiness-summary-stats span {
        display: block !important;
        margin-bottom: 5px !important;
        font-size: 12px !important;
        opacity: .6 !important;
    }

    .readiness-summary-stats strong {
        display: block !important;
        font-size: 22px !important;
        line-height: 1 !important;
    }

    /* ---------------------------------------------------------
    STATUS BADGES
    --------------------------------------------------------- */

    .status-badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 5px 9px !important;
        border-radius: 999px !important;
        font-size: 11px !important;
        line-height: 1.2 !important;
        font-weight: 700 !important;
        white-space: nowrap !important;
        background: #eef1f4 !important;
    }

    .status-high {
        background: #fde8e8 !important;
        color: #b42318 !important;
    }

    .status-medium {
        background: #fff4d6 !important;
        color: #9a6700 !important;
    }

    .status-low {
        background: #e6f6ee !important;
        color: #18794e !important;
    }

    /* ---------------------------------------------------------
    KPI GRID
    --------------------------------------------------------- */

    .organisation-stats-grid,
    .stats-grid {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        gap: 16px !important;
        width: 100% !important;
        margin-bottom: 24px !important;
    }

    /* ---------------------------------------------------------
    KPI CARDS
    --------------------------------------------------------- */

    .organisation-stats-grid .stat-card,
    .stats-grid .stat-card {
        min-width: 0 !important;
        min-height: 118px !important;
        box-sizing: border-box !important;
        padding: 20px !important;
        display: flex !important;
        align-items: flex-start !important;
        gap: 14px !important;
        border-radius: 15px !important;
        background: #ffffff !important;
        border: 1px solid rgba(15, 23, 42, .08) !important;
        box-shadow: 0 5px 20px rgba(15, 23, 42, .045) !important;
    }

    .stat-card-icon {
        width: 44px !important;
        height: 44px !important;
        min-width: 44px !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: #f1f4f7 !important;
    }

    .stat-card-icon svg {
        width: 22px !important;
        height: 22px !important;
        min-width: 22px !important;
        max-width: 22px !important;
        min-height: 22px !important;
        max-height: 22px !important;
        display: block !important;
        fill: none !important;
        stroke: currentColor !important;
        stroke-width: 1.8 !important;
        stroke-linecap: round !important;
        stroke-linejoin: round !important;
    }

    .stat-card-icon svg * {
        fill: none !important;
        stroke: currentColor !important;
        stroke-width: 1.8 !important;
        stroke-linecap: round !important;
        stroke-linejoin: round !important;
    }

    .organisation-stats-grid .stat-card > div:last-child,
    .stats-grid .stat-card > div:last-child {
        min-width: 0 !important;
    }

    .stat-card-label {
        display: block !important;
        margin-bottom: 5px !important;
        font-size: 12px !important;
        line-height: 1.3 !important;
        font-weight: 600 !important;
        opacity: .62 !important;
    }

    .stat-card-value {
        display: block !important;
        margin: 0 0 5px !important;
        font-size: 27px !important;
        line-height: 1 !important;
        font-weight: 750 !important;
        letter-spacing: -.02em !important;
    }

    .stat-card-meta {
        display: block !important;
        font-size: 11px !important;
        line-height: 1.35 !important;
        opacity: .55 !important;
    }

    /* ---------------------------------------------------------
    TWO COLUMN SECTIONS
    --------------------------------------------------------- */

    .dashboard-two-column {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 24px !important;
        margin-bottom: 24px !important;
    }

    .risk-overview,
    .learning-overview {
        display: flex !important;
        flex-direction: column !important;
        gap: 18px !important;
    }

    .risk-item-header {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        margin-bottom: 7px !important;
        font-size: 13px !important;
    }

    .risk-item-header strong {
        font-size: 14px !important;
    }

    /* ---------------------------------------------------------
    PROGRESS BARS
    --------------------------------------------------------- */

    .dashboard-progress {
        width: 100% !important;
        height: 8px !important;
        overflow: hidden !important;
        border-radius: 999px !important;
        background: #edf0f3 !important;
    }

    .dashboard-progress-bar {
        height: 100% !important;
        border-radius: inherit !important;
        background: #52677d !important;
    }

    .risk-high-progress {
        background: #d64545 !important;
    }

    .risk-medium-progress {
        background: #d9a300 !important;
    }

    .risk-low-progress {
        background: #319866 !important;
    }

    .dashboard-progress-large {
        height: 11px !important;
    }

    .risk-total {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding-top: 12px !important;
        border-top: 1px solid rgba(15, 23, 42, .08) !important;
        font-size: 13px !important;
    }

    .learning-progress-number {
        display: flex !important;
        align-items: baseline !important;
        gap: 10px !important;
    }

    .learning-progress-number strong {
        font-size: 36px !important;
        line-height: 1 !important;
        font-weight: 750 !important;
    }

    .learning-progress-number span {
        font-size: 13px !important;
        opacity: .6 !important;
    }

    .learning-stat-grid {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }

    .learning-stat-grid > div {
        padding: 14px !important;
        border-radius: 12px !important;
        background: #f4f6f8 !important;
    }

    .learning-stat-grid span {
        display: block !important;
        margin-bottom: 5px !important;
        font-size: 11px !important;
        opacity: .6 !important;
    }

    .learning-stat-grid strong {
        font-size: 18px !important;
    }

    /* ---------------------------------------------------------
    TABLES
    --------------------------------------------------------- */

    .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
    }

    .dashboard-table {
        width: 100% !important;
        border-collapse: collapse !important;
        table-layout: auto !important;
    }

    .dashboard-table th {
        padding: 11px 12px !important;
        text-align: left !important;
        font-size: 11px !important;
        line-height: 1.3 !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: .04em !important;
        opacity: .55 !important;
        border-bottom: 1px solid rgba(15, 23, 42, .1) !important;
        white-space: nowrap !important;
    }

    .dashboard-table td {
        padding: 14px 12px !important;
        font-size: 13px !important;
        line-height: 1.4 !important;
        vertical-align: middle !important;
        border-bottom: 1px solid rgba(15, 23, 42, .07) !important;
    }

    .dashboard-table tbody tr:last-child td {
        border-bottom: 0 !important;
    }

    .dashboard-table td strong {
        font-weight: 650 !important;
    }

    .table-progress {
        min-width: 120px !important;
    }

    .table-progress > span {
        display: block !important;
        margin-bottom: 5px !important;
        font-size: 11px !important;
        font-weight: 650 !important;
    }

    .table-progress .dashboard-progress {
        height: 6px !important;
    }

    .table-action-cell {
        text-align: right !important;
        white-space: nowrap !important;
    }

    /* ---------------------------------------------------------
    EMPLOYEE ATTENTION LIST
    --------------------------------------------------------- */

    .attention-list {
        display: flex !important;
        flex-direction: column !important;
    }

    .attention-row {
        display: grid !important;
        grid-template-columns: minmax(220px, 1.25fr) minmax(230px, 1fr) auto !important;
        align-items: center !important;
        gap: 18px !important;
        padding: 15px 0 !important;
        border-bottom: 1px solid rgba(15, 23, 42, .07) !important;
    }

    .attention-row:last-child {
        border-bottom: 0 !important;
    }

    .attention-employee {
        display: flex !important;
        align-items: center !important;
        gap: 11px !important;
    }

    .attention-avatar {
        width: 38px !important;
        height: 38px !important;
        min-width: 38px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        background: #edf1f4 !important;
        font-size: 13px !important;
        font-weight: 750 !important;
    }

    .attention-employee strong,
    .attention-employee span {
        display: block !important;
    }

    .attention-employee strong {
        font-size: 13px !important;
    }

    .attention-employee span {
        margin-top: 2px !important;
        font-size: 11px !important;
        opacity: .58 !important;
    }

    .attention-reasons {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        gap: 6px !important;
    }

    /* ---------------------------------------------------------
    BUTTONS
    --------------------------------------------------------- */

    .btn-sm {
        padding: 7px 11px !important;
        font-size: 11px !important;
        line-height: 1.2 !important;
        border-radius: 8px !important;
    }

    .btn-secondary {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
    }

    /* ---------------------------------------------------------
    QUICK ACTIONS
    --------------------------------------------------------- */

    .action-grid {
        display: grid !important;
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        gap: 14px !important;
    }

    .action-card {
        min-width: 0 !important;
        padding: 18px !important;
        display: flex !important;
        align-items: flex-start !important;
        gap: 13px !important;
        border-radius: 14px !important;
        border: 1px solid rgba(15, 23, 42, .08) !important;
        background: #fafbfc !important;
        text-decoration: none !important;
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease !important;
    }

    .action-card:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .07) !important;
        border-color: rgba(15, 23, 42, .14) !important;
    }

    .action-card-icon {
        width: 42px !important;
        height: 42px !important;
        min-width: 42px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 11px !important;
        background: #eef2f5 !important;
    }

    .action-card-icon svg {
        width: 21px !important;
        height: 21px !important;
        min-width: 21px !important;
        max-width: 21px !important;
        min-height: 21px !important;
        max-height: 21px !important;
        fill: none !important;
        stroke: currentColor !important;
        stroke-width: 1.8 !important;
        stroke-linecap: round !important;
        stroke-linejoin: round !important;
    }

    .action-card strong,
    .action-card span {
        display: block !important;
    }

    .action-card strong {
        margin-bottom: 4px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
    }

    .action-card span {
        font-size: 11px !important;
        line-height: 1.45 !important;
        opacity: .6 !important;
    }

    /* ---------------------------------------------------------
    EMPTY STATE
    --------------------------------------------------------- */

    .empty-state {
        padding: 32px 15px !important;
        text-align: center !important;
    }

    .empty-state h3 {
        margin: 0 0 7px !important;
        font-size: 16px !important;
    }

    .empty-state p {
        margin: 0 0 18px !important;
        font-size: 13px !important;
        opacity: .65 !important;
    }

    /* ---------------------------------------------------------
    RESPONSIVE
    --------------------------------------------------------- */

    @media (max-width: 1200px) {
        .organisation-stats-grid,
        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }

        .action-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    }

    @media (max-width: 950px) {
        .organisation-readiness-main {
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        .readiness-summary-stats {
            width: 100% !important;
            min-width: 0 !important;
        }

        .dashboard-two-column {
            grid-template-columns: 1fr !important;
        }

        .attention-row {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 700px) {
        .dashboard-page {
            padding: 20px 16px 40px !important;
        }

        .dashboard-header {
            flex-direction: column !important;
        }

        .dashboard-date {
            padding-top: 0 !important;
        }

        .organisation-stats-grid,
        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .learning-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .action-grid {
            grid-template-columns: 1fr !important;
        }

        .overview-card {
            padding: 18px !important;
        }
    }

    @media (max-width: 480px) {
        .organisation-stats-grid,
        .stats-grid,
        .readiness-summary-stats,
        .learning-stat-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
