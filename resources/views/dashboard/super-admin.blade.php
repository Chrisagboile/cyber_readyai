@extends('layouts.app')

@section('content')

<div class="super-admin-dashboard">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="super-admin-header">

        <div>
            <span class="super-admin-eyebrow">
                CyberReadyAI Platform
            </span>

            <h1>Super Admin Dashboard</h1>

            <p>
                Platform-wide visibility across organisations, users,
                assessments, cybersecurity risk and learning activity.
            </p>
        </div>

        <div class="super-admin-header-date">
            {{ now()->format('l, F j, Y') }}
        </div>

    </div>


    {{-- =========================================================
        PLATFORM OVERVIEW
    ========================================================== --}}
    <div class="super-admin-readiness-card">

        <div class="super-admin-readiness-content">

            <div>
                <span class="super-admin-section-eyebrow">
                    Platform Readiness
                </span>

                <div class="super-admin-readiness-title">

                    @if($overallRisk === 'High')
                        <span class="super-admin-badge super-admin-badge-danger">
                            High Risk
                        </span>
                    @elseif($overallRisk === 'Medium')
                        <span class="super-admin-badge super-admin-badge-warning">
                            Medium Risk
                        </span>
                    @elseif($overallRisk === 'Low')
                        <span class="super-admin-badge super-admin-badge-success">
                            Low Risk
                        </span>
                    @else
                        <span class="super-admin-badge super-admin-badge-neutral">
                            Not Assessed
                        </span>
                    @endif

                    <h2>CyberReadyAI Platform</h2>

                </div>

                <p>
                    {{ $totalOrganisations }} organisations,
                    {{ $employeeCount }} employees and
                    {{ $totalUsers }} total users are currently represented
                    across the platform.
                </p>
            </div>


            <div class="super-admin-risk-summary">

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
        KPI GRID
    ========================================================== --}}
    <div class="super-admin-kpi-grid">

        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">O</div>

            <div>
                <span>Organisations</span>
                <strong>{{ $totalOrganisations }}</strong>
                <small>{{ $activeOrganisations }} active</small>
            </div>
        </div>


        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">U</div>

            <div>
                <span>Total Users</span>
                <strong>{{ $totalUsers }}</strong>
                <small>{{ $activeUsers }} active</small>
            </div>
        </div>


        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">E</div>

            <div>
                <span>Employees</span>
                <strong>{{ $employeeCount }}</strong>
                <small>Platform employees</small>
            </div>
        </div>


        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">M</div>

            <div>
                <span>Managers</span>
                <strong>{{ $managerCount }}</strong>
                <small>Platform managers</small>
            </div>
        </div>


        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">A</div>

            <div>
                <span>Assessments</span>
                <strong>{{ $totalAssessments }}</strong>
                <small>{{ $completedAttempts }} completed attempts</small>
            </div>
        </div>


        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">%</div>

            <div>
                <span>Average Score</span>
                <strong>{{ number_format($averageScore, 1) }}%</strong>
                <small>Completed &amp; expired attempts</small>
            </div>
        </div>


        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">R</div>

            <div>
                <span>Assessment Coverage</span>
                <strong>{{ number_format($assessmentCoverage, 1) }}%</strong>
                <small>Employees assessed</small>
            </div>
        </div>


        <div class="super-admin-kpi">
            <div class="super-admin-kpi-icon">L</div>

            <div>
                <span>Learning Plans</span>
                <strong>{{ $totalLearningPlans }}</strong>
                <small>{{ $completedLearningPlans }} completed</small>
            </div>
        </div>

    </div>


    {{-- =========================================================
        PLATFORM HEALTH
    ========================================================== --}}
    <div class="super-admin-two-column">

        {{-- Risk --}}
        <div class="super-admin-card">

            <div class="super-admin-section-heading">
                <div>
                    <h2>Platform Risk Distribution</h2>
                    <p>
                        Risk levels from completed and expired assessment attempts.
                    </p>
                </div>
            </div>


            @php
                $riskTotal = $highRisk + $mediumRisk + $lowRisk;
            @endphp


            <div class="super-admin-risk-list">

                <div class="super-admin-risk-row">

                    <div class="super-admin-risk-row-top">
                        <span>High Risk</span>
                        <strong>{{ $highRisk }}</strong>
                    </div>

                    <div class="super-admin-progress-track">
                        <div
                            class="super-admin-progress-fill super-admin-progress-danger"
                            style="width: {{ $riskTotal > 0 ? min(100, ($highRisk / $riskTotal) * 100) : 0 }}%;"
                        ></div>
                    </div>

                </div>


                <div class="super-admin-risk-row">

                    <div class="super-admin-risk-row-top">
                        <span>Medium Risk</span>
                        <strong>{{ $mediumRisk }}</strong>
                    </div>

                    <div class="super-admin-progress-track">
                        <div
                            class="super-admin-progress-fill super-admin-progress-warning"
                            style="width: {{ $riskTotal > 0 ? min(100, ($mediumRisk / $riskTotal) * 100) : 0 }}%;"
                        ></div>
                    </div>

                </div>


                <div class="super-admin-risk-row">

                    <div class="super-admin-risk-row-top">
                        <span>Low Risk</span>
                        <strong>{{ $lowRisk }}</strong>
                    </div>

                    <div class="super-admin-progress-track">
                        <div
                            class="super-admin-progress-fill super-admin-progress-success"
                            style="width: {{ $riskTotal > 0 ? min(100, ($lowRisk / $riskTotal) * 100) : 0 }}%;"
                        ></div>
                    </div>

                </div>

            </div>

        </div>


        {{-- Learning --}}
        <div class="super-admin-card">

            <div class="super-admin-section-heading">
                <div>
                    <h2>Learning Platform Health</h2>
                    <p>
                        Organisation-wide learning-plan activity.
                    </p>
                </div>
            </div>


            <div class="super-admin-learning-main">

                <div class="super-admin-learning-number">
                    <strong>
                        {{ number_format($averageLearningProgress, 1) }}%
                    </strong>

                    <span>Average learning progress</span>
                </div>

                <div class="super-admin-progress-track">
                    <div
                        class="super-admin-progress-fill"
                        style="width: {{ min(100, max(0, $averageLearningProgress)) }}%;"
                    ></div>
                </div>

            </div>


            <div class="super-admin-learning-stats">

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

                <div>
                    <span>Overdue</span>
                    <strong>{{ $overdueLearningPlans }}</strong>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ORGANISATION OVERVIEW
    ========================================================== --}}
    <div class="super-admin-card">

        <div class="super-admin-section-heading">

            <div>
                <h2>Organisation Overview</h2>

                <p>
                    Platform-wide performance by organisation.
                </p>
            </div>

            <a
                href="{{ route('tenants.index') }}"
                class="super-admin-section-action"
            >
                Manage Organisations
            </a>

        </div>


        @if($organisationReports->count())

            <div class="super-admin-table-wrap">

                <table class="super-admin-table">

                    <thead>
                        <tr>
                            <th>Organisation</th>
                            <th>Status</th>
                            <th>Employees</th>
                            <th>Managers</th>
                            <th>Assessments</th>
                            <th>Coverage</th>
                            <th>Average Score</th>
                            <th>High Risk</th>
                        </tr>
                    </thead>


                    <tbody>

                        @foreach($organisationReports as $row)

                            @php
                                $organisation = $row->organisation;
                                $organisationStatus = strtolower(
                                    (string) $organisation->status
                                );
                            @endphp

                            <tr>

                                <td>
                                    <strong>
                                        {{ $organisation->name }}
                                    </strong>
                                </td>


                                <td>

                                    @if($organisationStatus === 'active')

                                        <span class="super-admin-badge super-admin-badge-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="super-admin-badge super-admin-badge-neutral">
                                            {{ ucfirst($organisationStatus ?: 'Inactive') }}
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ $row->employees }}
                                </td>


                                <td>
                                    {{ $row->managers }}
                                </td>


                                <td>
                                    {{ $row->assessments }}
                                </td>


                                <td>

                                    <div class="super-admin-table-progress">

                                        <span>
                                            {{ number_format($row->coverage, 1) }}%
                                        </span>

                                        <div class="super-admin-progress-track">
                                            <div
                                                class="super-admin-progress-fill"
                                                style="width: {{ min(100, max(0, $row->coverage)) }}%;"
                                            ></div>
                                        </div>

                                    </div>

                                </td>


                                <td>
                                    <strong>
                                        {{ number_format($row->average_score, 1) }}%
                                    </strong>
                                </td>


                                <td>

                                    @if($row->high_risk > 0)

                                        <span class="super-admin-badge super-admin-badge-danger">
                                            {{ $row->high_risk }} High
                                        </span>

                                    @else

                                        <span class="super-admin-badge super-admin-badge-success">
                                            None
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="super-admin-empty">

                <div class="super-admin-empty-icon">
                    O
                </div>

                <h3>No organisations yet</h3>

                <p>
                    Organisations will appear here once they are created.
                </p>

                <a
                    href="{{ route('tenants.index') }}"
                    class="super-admin-btn super-admin-btn-primary"
                >
                    Manage Organisations
                </a>

            </div>

        @endif

    </div>


    {{-- =========================================================
        RECENT ACTIVITY
    ========================================================== --}}
    <div class="super-admin-two-column">

        {{-- Recent Assessments --}}
        <div class="super-admin-card">

            <div class="super-admin-section-heading">

                <div>
                    <h2>Recent Assessment Activity</h2>
                    <p>
                        Latest completed and expired assessment attempts.
                    </p>
                </div>

            </div>


            @if($recentAttempts->count())

                <div class="super-admin-activity-list">

                    @foreach($recentAttempts as $attempt)

                        @php
                            $risk = strtolower(
                                (string) $attempt->risk_level
                            );
                        @endphp

                        <div class="super-admin-activity-row">

                            <div class="super-admin-activity-avatar">
                                {{ strtoupper(
                                    substr(
                                        optional($attempt->user)->name ?? 'U',
                                        0,
                                        1
                                    )
                                ) }}
                            </div>


                            <div class="super-admin-activity-content">

                                <strong>
                                    {{ optional($attempt->user)->name ?? 'Unknown User' }}
                                </strong>

                                <span>
                                    {{ optional($attempt->assessment)->name ?? 'Assessment' }}
                                </span>

                                <small>
                                    {{ optional($attempt->completed_at)->format('M j, Y') ?? '—' }}
                                </small>

                            </div>


                            <div class="super-admin-activity-result">

                                <strong>
                                    {{ number_format((float) $attempt->score_percentage, 1) }}%
                                </strong>

                                @if($risk === 'high')
                                    <span class="super-admin-badge super-admin-badge-danger">
                                        High
                                    </span>
                                @elseif($risk === 'medium')
                                    <span class="super-admin-badge super-admin-badge-warning">
                                        Medium
                                    </span>
                                @elseif($risk === 'low')
                                    <span class="super-admin-badge super-admin-badge-success">
                                        Low
                                    </span>
                                @else
                                    <span class="super-admin-badge super-admin-badge-neutral">
                                        —
                                    </span>
                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="super-admin-empty-small">
                    No assessment activity yet.
                </div>

            @endif

        </div>


        {{-- Recent Organisations --}}
        <div class="super-admin-card">

            <div class="super-admin-section-heading">

                <div>
                    <h2>Recently Added Organisations</h2>
                    <p>
                        Latest organisations added to the platform.
                    </p>
                </div>

            </div>


            @if($recentOrganisations->count())

                <div class="super-admin-recent-org-list">

                    @foreach($recentOrganisations as $organisation)

                        <div class="super-admin-recent-org-row">

                            <div class="super-admin-org-icon">
                                {{ strtoupper(
                                    substr($organisation->name, 0, 1)
                                ) }}
                            </div>


                            <div>
                                <strong>
                                    {{ $organisation->name }}
                                </strong>

                                <span>
                                    {{ $organisation->users_count }}
                                    user{{ $organisation->users_count === 1 ? '' : 's' }}
                                </span>
                            </div>


                            <div>

                                @if(strtolower((string) $organisation->status) === 'active')

                                    <span class="super-admin-badge super-admin-badge-success">
                                        Active
                                    </span>

                                @else

                                    <span class="super-admin-badge super-admin-badge-neutral">
                                        {{ ucfirst($organisation->status ?: 'Inactive') }}
                                    </span>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="super-admin-empty-small">
                    No organisations have been added yet.
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        PLATFORM SUMMARY
    ========================================================== --}}
    <div class="super-admin-card">

        <div class="super-admin-section-heading">

            <div>
                <h2>Platform Summary</h2>
                <p>
                    Current operational indicators across CyberReadyAI.
                </p>
            </div>

        </div>


        <div class="super-admin-summary-grid">

            <div>
                <span>Active Organisations</span>
                <strong>
                    {{ $activeOrganisations }}
                </strong>
            </div>

            <div>
                <span>Inactive Organisations</span>
                <strong>
                    {{ $inactiveOrganisations }}
                </strong>
            </div>

            <div>
                <span>Organisation Admins</span>
                <strong>
                    {{ $organisationAdminCount }}
                </strong>
            </div>

            <div>
                <span>Completed Attempts</span>
                <strong>
                    {{ $completedAttempts }}
                </strong>
            </div>

            <div>
                <span>Expired Attempts</span>
                <strong>
                    {{ $expiredAttempts }}
                </strong>
            </div>

            <div>
                <span>Overdue Learning</span>
                <strong>
                    {{ $overdueLearningPlans }}
                </strong>
            </div>

        </div>

    </div>


    {{-- =========================================================
        QUICK ACTIONS
    ========================================================== --}}
    <div class="super-admin-card">

        <div class="super-admin-section-heading">

            <div>
                <h2>Quick Actions</h2>
                <p>
                    Common Super Admin platform management tasks.
                </p>
            </div>

        </div>


        <div class="super-admin-action-grid">

            <a
                href="{{ route('tenants.index') }}"
                class="super-admin-action"
            >
                <div class="super-admin-action-icon">
                    O
                </div>

                <div>
                    <strong>Manage Organisations</strong>
                    <span>Create, review and manage platform tenants.</span>
                </div>

                <b>→</b>
            </a>


            <a
                href="{{ route('subscriptions.index') }}"
                class="super-admin-action"
            >
                <div class="super-admin-action-icon">
                    S
                </div>

                <div>
                    <strong>Subscriptions</strong>
                    <span>Review organisation subscription activity.</span>
                </div>

                <b>→</b>
            </a>


            <a
                href="{{ route('analytics.index') }}"
                class="super-admin-action"
            >
                <div class="super-admin-action-icon">
                    A
                </div>

                <div>
                    <strong>Analytics</strong>
                    <span>Explore platform-level analytics and trends.</span>
                </div>

                <b>→</b>
            </a>


            <a
                href="{{ route('billing.index') }}"
                class="super-admin-action"
            >
                <div class="super-admin-action-icon">
                    B
                </div>

                <div>
                    <strong>Billing</strong>
                    <span>Review platform billing and account activity.</span>
                </div>

                <b>→</b>
            </a>

        </div>

    </div>

</div>


<style>
.super-admin-dashboard {
    max-width: 1500px;
    margin: 0 auto;
    padding: 8px 0 45px;
}

.super-admin-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 25px;
    margin-bottom: 25px;
}

.super-admin-eyebrow,
.super-admin-section-eyebrow {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .09em;
    opacity: .52;
}

.super-admin-header h1 {
    margin: 0;
    font-size: 30px;
    line-height: 1.15;
}

.super-admin-header p {
    max-width: 800px;
    margin: 8px 0 0;
    font-size: 13px;
    opacity: .63;
}

.super-admin-header-date {
    font-size: 12px;
    font-weight: 700;
    opacity: .55;
    white-space: nowrap;
}

.super-admin-readiness-card,
.super-admin-card {
    border: 1px solid rgba(127, 127, 127, .14);
    border-radius: 16px;
    background: var(--card-bg, #fff);
    box-shadow: 0 7px 24px rgba(0, 0, 0, .035);
}

.super-admin-readiness-card {
    padding: 23px;
    margin-bottom: 22px;
}

.super-admin-readiness-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
}

.super-admin-readiness-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.super-admin-readiness-title h2 {
    margin: 0;
    font-size: 20px;
}

.super-admin-readiness-content p {
    max-width: 800px;
    margin: 9px 0 0;
    font-size: 13px;
    opacity: .63;
}

.super-admin-risk-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    min-width: 300px;
    gap: 10px;
}

.super-admin-risk-summary > div {
    padding: 13px;
    border-radius: 11px;
    background: rgba(127, 127, 127, .07);
}

.super-admin-risk-summary span,
.super-admin-risk-summary strong {
    display: block;
}

.super-admin-risk-summary span {
    margin-bottom: 4px;
    font-size: 10px;
    opacity: .56;
}

.super-admin-risk-summary strong {
    font-size: 20px;
}

.super-admin-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}

.super-admin-kpi {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 1px solid rgba(127, 127, 127, .14);
    border-radius: 14px;
    background: var(--card-bg, #fff);
    box-shadow: 0 5px 18px rgba(0, 0, 0, .03);
}

.super-admin-kpi-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    border-radius: 11px;
    background: rgba(127, 127, 127, .09);
    font-size: 12px;
    font-weight: 800;
}

.super-admin-kpi span,
.super-admin-kpi small {
    display: block;
}

.super-admin-kpi span {
    margin-bottom: 3px;
    font-size: 11px;
    opacity: .57;
}

.super-admin-kpi strong {
    display: block;
    font-size: 22px;
    line-height: 1.05;
}

.super-admin-kpi small {
    margin-top: 5px;
    font-size: 10px;
    opacity: .47;
}

.super-admin-two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

.super-admin-card {
    padding: 22px;
    margin-bottom: 22px;
}

.super-admin-section-heading {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 19px;
}

.super-admin-section-heading h2 {
    margin: 0;
    font-size: 18px;
}

.super-admin-section-heading p {
    margin: 5px 0 0;
    font-size: 12px;
    opacity: .59;
}

.super-admin-section-action {
    color: inherit;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
}

.super-admin-section-action:hover {
    text-decoration: underline;
}

.super-admin-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 24px;
    padding: 0 8px;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 800;
    white-space: nowrap;
}

.super-admin-badge-danger {
    background: rgba(220, 53, 69, .11);
    color: #b42332;
}

.super-admin-badge-warning {
    background: rgba(240, 173, 0, .13);
    color: #966b00;
}

.super-admin-badge-success {
    background: rgba(46, 157, 99, .11);
    color: #25784c;
}

.super-admin-badge-neutral {
    background: rgba(127, 127, 127, .10);
}

.super-admin-risk-list {
    display: grid;
    gap: 18px;
}

.super-admin-risk-row-top {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 7px;
    font-size: 12px;
}

.super-admin-risk-row-top span {
    opacity: .62;
}

.super-admin-risk-row-top strong {
    font-size: 13px;
}

.super-admin-progress-track {
    height: 8px;
    overflow: hidden;
    border-radius: 999px;
    background: rgba(127, 127, 127, .12);
}

.super-admin-progress-fill {
    height: 100%;
    border-radius: inherit;
    background: currentColor;
    opacity: .78;
}

.super-admin-progress-danger {
    color: #c93b4b;
}

.super-admin-progress-warning {
    color: #b78400;
}

.super-admin-progress-success {
    color: #2e9d63;
}

.super-admin-learning-main {
    margin-bottom: 19px;
}

.super-admin-learning-number strong {
    display: block;
    font-size: 37px;
    line-height: 1;
}

.super-admin-learning-number span {
    display: block;
    margin: 7px 0 16px;
    font-size: 11px;
    opacity: .55;
}

.super-admin-learning-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 9px;
}

.super-admin-learning-stats > div {
    padding: 12px;
    border-radius: 10px;
    background: rgba(127, 127, 127, .07);
}

.super-admin-learning-stats span,
.super-admin-learning-stats strong {
    display: block;
}

.super-admin-learning-stats span {
    margin-bottom: 4px;
    font-size: 10px;
    opacity: .55;
}

.super-admin-learning-stats strong {
    font-size: 18px;
}

.super-admin-table-wrap {
    width: 100%;
    overflow-x: auto;
}

.super-admin-table {
    width: 100%;
    border-collapse: collapse;
}

.super-admin-table th {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid rgba(127, 127, 127, .14);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    opacity: .54;
    white-space: nowrap;
}

.super-admin-table td {
    padding: 14px 10px;
    border-bottom: 1px solid rgba(127, 127, 127, .09);
    font-size: 12px;
}

.super-admin-table tr:last-child td {
    border-bottom: 0;
}

.super-admin-table-progress {
    min-width: 110px;
}

.super-admin-table-progress > span {
    display: block;
    margin-bottom: 5px;
    font-size: 10px;
    font-weight: 700;
}

.super-admin-activity-list {
    display: grid;
}

.super-admin-activity-row {
    display: grid;
    grid-template-columns: 36px 1fr auto;
    gap: 11px;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(127, 127, 127, .09);
}

.super-admin-activity-row:last-child {
    border-bottom: 0;
}

.super-admin-activity-avatar,
.super-admin-org-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(127, 127, 127, .09);
    font-weight: 800;
}

.super-admin-activity-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 11px;
}

.super-admin-activity-content strong,
.super-admin-activity-content span,
.super-admin-activity-content small {
    display: block;
}

.super-admin-activity-content strong {
    font-size: 12px;
}

.super-admin-activity-content span {
    margin-top: 2px;
    font-size: 10px;
    opacity: .58;
}

.super-admin-activity-content small {
    margin-top: 3px;
    font-size: 9px;
    opacity: .42;
}

.super-admin-activity-result {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
}

.super-admin-activity-result strong {
    font-size: 13px;
}

.super-admin-recent-org-list {
    display: grid;
}

.super-admin-recent-org-row {
    display: grid;
    grid-template-columns: 36px 1fr auto;
    gap: 11px;
    align-items: center;
    padding: 13px 0;
    border-bottom: 1px solid rgba(127, 127, 127, .09);
}

.super-admin-recent-org-row:last-child {
    border-bottom: 0;
}

.super-admin-org-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    font-size: 11px;
}

.super-admin-recent-org-row strong,
.super-admin-recent-org-row span {
    display: block;
}

.super-admin-recent-org-row strong {
    font-size: 12px;
}

.super-admin-recent-org-row span {
    margin-top: 3px;
    font-size: 10px;
    opacity: .53;
}

.super-admin-summary-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;
}

.super-admin-summary-grid > div {
    padding: 14px;
    border-radius: 11px;
    background: rgba(127, 127, 127, .07);
}

.super-admin-summary-grid span,
.super-admin-summary-grid strong {
    display: block;
}

.super-admin-summary-grid span {
    margin-bottom: 5px;
    font-size: 10px;
    opacity: .55;
}

.super-admin-summary-grid strong {
    font-size: 21px;
}

.super-admin-action-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 11px;
}

.super-admin-action {
    display: grid;
    grid-template-columns: 41px 1fr auto;
    gap: 12px;
    align-items: center;
    min-height: 66px;
    padding: 12px;
    border: 1px solid rgba(127, 127, 127, .11);
    border-radius: 12px;
    color: inherit;
    text-decoration: none;
    background: rgba(127, 127, 127, .025);
}

.super-admin-action:hover {
    color: inherit;
    background: rgba(127, 127, 127, .06);
}

.super-admin-action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 41px;
    height: 41px;
    border-radius: 11px;
    background: rgba(127, 127, 127, .09);
    font-size: 12px;
    font-weight: 800;
}

.super-admin-action strong,
.super-admin-action span {
    display: block;
}

.super-admin-action strong {
    font-size: 12px;
}

.super-admin-action span {
    margin-top: 4px;
    font-size: 10px;
    opacity: .52;
}

.super-admin-action b {
    font-size: 15px;
    opacity: .45;
}

.super-admin-empty {
    padding: 35px 15px 20px;
    text-align: center;
}

.super-admin-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: rgba(127, 127, 127, .09);
    font-size: 12px;
    font-weight: 800;
}

.super-admin-empty h3 {
    margin: 0 0 6px;
    font-size: 16px;
}

.super-admin-empty p {
    margin: 0 auto 18px;
    font-size: 12px;
    opacity: .56;
}

.super-admin-empty-small {
    padding: 20px 0;
    text-align: center;
    font-size: 12px;
    opacity: .55;
}

.super-admin-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 40px;
    padding: 0 15px;
    border-radius: 9px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
}

.super-admin-btn-primary {
    background: #111827;
    color: #fff;
}

.super-admin-btn-primary:hover {
    color: #fff;
    opacity: .92;
}

@media (max-width: 1200px) {
    .super-admin-kpi-grid {
        grid-template-columns: repeat(4, 1fr);
    }

    .super-admin-summary-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 1000px) {
    .super-admin-readiness-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .super-admin-risk-summary {
        width: 100%;
        max-width: 420px;
    }

    .super-admin-two-column {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 760px) {
    .super-admin-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .super-admin-kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .super-admin-learning-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .super-admin-action-grid {
        grid-template-columns: 1fr;
    }

    .super-admin-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 500px) {
    .super-admin-kpi-grid {
        grid-template-columns: 1fr;
    }

    .super-admin-risk-summary {
        grid-template-columns: 1fr;
    }

    .super-admin-summary-grid {
        grid-template-columns: 1fr;
    }

    .super-admin-card,
    .super-admin-readiness-card {
        padding: 17px;
    }

    .super-admin-header h1 {
        font-size: 25px;
    }
}
</style>

@endsection
