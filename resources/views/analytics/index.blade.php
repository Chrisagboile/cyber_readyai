@extends('layouts.app')

@section('content')

<style>
    .super-analytics-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 8px 0 50px;
    }

    .super-analytics-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
    }

    .super-analytics-eyebrow {
        display: block;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .09em;
        opacity: .52;
    }

    .super-analytics-header h1 {
        margin: 0;
        font-size: 30px;
        letter-spacing: -.5px;
    }

    .super-analytics-header p {
        margin: 8px 0 0;
        max-width: 780px;
        font-size: 13px;
        opacity: .62;
    }

    .super-analytics-period {
        display: flex;
        gap: 7px;
        padding: 5px;
        border: 1px solid rgba(127,127,127,.14);
        border-radius: 10px;
        background: var(--card-bg, #fff);
    }

    .super-analytics-period a {
        padding: 8px 11px;
        border-radius: 7px;
        color: inherit;
        text-decoration: none;
        font-size: 11px;
        font-weight: 800;
    }

    .super-analytics-period a.active {
        background: #111827;
        color: #fff;
    }

    .super-analytics-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 13px;
        margin-bottom: 22px;
    }

    .super-analytics-stat {
        padding: 18px;
        border: 1px solid rgba(127,127,127,.14);
        border-radius: 14px;
        background: var(--card-bg, #fff);
        box-shadow: 0 5px 18px rgba(0,0,0,.03);
    }

    .super-analytics-stat-label {
        margin-bottom: 8px;
        font-size: 11px;
        font-weight: 650;
        opacity: .56;
    }

    .super-analytics-stat-value {
        font-size: 27px;
        line-height: 1;
        font-weight: 800;
    }

    .super-analytics-stat-meta {
        margin-top: 8px;
        font-size: 10px;
        opacity: .48;
    }

    .super-analytics-card {
        margin-bottom: 22px;
        padding: 22px;
        border: 1px solid rgba(127,127,127,.14);
        border-radius: 16px;
        background: var(--card-bg, #fff);
        box-shadow: 0 7px 24px rgba(0,0,0,.035);
    }

    .super-analytics-card-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 19px;
    }

    .super-analytics-card-heading h2 {
        margin: 0;
        font-size: 18px;
    }

    .super-analytics-card-heading p {
        margin: 5px 0 0;
        font-size: 12px;
        opacity: .55;
    }

    .super-analytics-two-column {
        display: grid;
        grid-template-columns: 1.2fr .8fr;
        gap: 22px;
    }

    .super-analytics-three-column {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 13px;
    }

    .super-analytics-metric {
        padding: 15px;
        border: 1px solid rgba(127,127,127,.11);
        border-radius: 12px;
        background: rgba(127,127,127,.035);
    }

    .super-analytics-metric span {
        display: block;
        margin-bottom: 6px;
        font-size: 10px;
        opacity: .55;
    }

    .super-analytics-metric strong {
        display: block;
        font-size: 21px;
    }

    .super-analytics-risk {
        display: grid;
        gap: 13px;
    }

    .super-analytics-risk-row {
        display: grid;
        grid-template-columns: 100px 1fr 55px;
        gap: 10px;
        align-items: center;
        font-size: 11px;
    }

    .super-analytics-risk-bar {
        height: 9px;
        overflow: hidden;
        border-radius: 999px;
        background: rgba(127,127,127,.10);
    }

    .super-analytics-risk-fill {
        height: 100%;
        border-radius: inherit;
    }

    .super-analytics-risk-fill.low {
        background: #55a878;
    }

    .super-analytics-risk-fill.medium {
        background: #d2a13d;
    }

    .super-analytics-risk-fill.high {
        background: #cf6060;
    }

    .super-analytics-activity {
        min-height: 180px;
        display: flex;
        align-items: flex-end;
        gap: 7px;
        padding: 10px 5px 0;
    }

    .super-analytics-activity-column {
        flex: 1;
        min-width: 5px;
        text-align: center;
    }

    .super-analytics-activity-bar-wrap {
        height: 135px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .super-analytics-activity-bar {
        width: 100%;
        max-width: 24px;
        min-height: 3px;
        border-radius: 5px 5px 2px 2px;
        background: #111827;
        opacity: .82;
    }

    .super-analytics-activity-label {
        margin-top: 7px;
        font-size: 8px;
        opacity: .45;
        white-space: nowrap;
        overflow: hidden;
    }

    .super-analytics-table-wrap {
        overflow-x: auto;
    }

    .super-analytics-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .super-analytics-table th {
        padding: 11px 10px;
        text-align: left;
        border-bottom: 1px solid rgba(127,127,127,.14);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .05em;
        opacity: .53;
        white-space: nowrap;
    }

    .super-analytics-table td {
        padding: 13px 10px;
        border-bottom: 1px solid rgba(127,127,127,.08);
        font-size: 12px;
    }

    .super-analytics-table tr:last-child td {
        border-bottom: 0;
    }

    .super-analytics-org-name {
        font-weight: 800;
    }

    .super-analytics-muted {
        opacity: .45;
    }

    .super-analytics-badge {
        display: inline-flex;
        align-items: center;
        min-height: 23px;
        padding: 0 8px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 800;
    }

    .super-analytics-success {
        color: #25784c;
        background: rgba(46,157,99,.11);
    }

    .super-analytics-warning {
        color: #946d13;
        background: rgba(245,158,11,.13);
    }

    .super-analytics-danger {
        color: #a13d3d;
        background: rgba(220,38,38,.10);
    }

    .super-analytics-info {
        color: #245ea8;
        background: rgba(59,130,246,.11);
    }

    .super-analytics-learning {
        display: grid;
        gap: 11px;
    }

    .super-analytics-learning-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding-bottom: 11px;
        border-bottom: 1px solid rgba(127,127,127,.09);
        font-size: 12px;
    }

    .super-analytics-learning-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .super-analytics-learning-row strong {
        font-size: 14px;
    }

    .super-analytics-recent {
        display: grid;
        gap: 11px;
    }

    .super-analytics-recent-item {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        padding-bottom: 11px;
        border-bottom: 1px solid rgba(127,127,127,.09);
    }

    .super-analytics-recent-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .super-analytics-recent-primary {
        font-size: 12px;
        font-weight: 750;
    }

    .super-analytics-recent-secondary {
        margin-top: 3px;
        font-size: 10px;
        opacity: .48;
    }

    .super-analytics-recent-score {
        text-align: right;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    @media (max-width: 1050px) {
        .super-analytics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .super-analytics-two-column {
            grid-template-columns: 1fr;
        }

        .super-analytics-three-column {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .super-analytics-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .super-analytics-period {
            width: 100%;
            overflow-x: auto;
        }

        .super-analytics-period a {
            flex: 1;
            text-align: center;
        }

        .super-analytics-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .super-analytics-grid {
            grid-template-columns: 1fr;
        }
    }
</style>


<div class="super-analytics-page">

    <div class="super-analytics-header">

        <div>

            <span class="super-analytics-eyebrow">
                Platform Intelligence
            </span>

            <h1>
                Analytics
            </h1>

            <p>
                Monitor platform adoption, assessment performance,
                cybersecurity risk, learning activity and subscription value.
            </p>

        </div>


        <div class="super-analytics-period">

            <a
                href="{{ route('analytics.index', ['period' => '7']) }}"
                class="{{ $period === '7' ? 'active' : '' }}"
            >
                7 Days
            </a>

            <a
                href="{{ route('analytics.index', ['period' => '30']) }}"
                class="{{ $period === '30' ? 'active' : '' }}"
            >
                30 Days
            </a>

            <a
                href="{{ route('analytics.index', ['period' => '90']) }}"
                class="{{ $period === '90' ? 'active' : '' }}"
            >
                90 Days
            </a>

            <a
                href="{{ route('analytics.index', ['period' => '365']) }}"
                class="{{ $period === '365' ? 'active' : '' }}"
            >
                1 Year
            </a>

            <a
                href="{{ route('analytics.index', ['period' => 'all']) }}"
                class="{{ $period === 'all' ? 'active' : '' }}"
            >
                All
            </a>

        </div>

    </div>


    {{-- Platform overview --}}
    <div class="super-analytics-grid">

        <div class="super-analytics-stat">
            <div class="super-analytics-stat-label">
                Organisations
            </div>

            <div class="super-analytics-stat-value">
                {{ $totalOrganisations }}
            </div>

            <div class="super-analytics-stat-meta">
                {{ $activeOrganisations }} active
            </div>
        </div>


        <div class="super-analytics-stat">
            <div class="super-analytics-stat-label">
                Platform Users
            </div>

            <div class="super-analytics-stat-value">
                {{ $totalUsers }}
            </div>

            <div class="super-analytics-stat-meta">
                {{ $activeUsers }} active
            </div>
        </div>


        <div class="super-analytics-stat">
            <div class="super-analytics-stat-label">
                Assessments
            </div>

            <div class="super-analytics-stat-value">
                {{ $totalAssessments }}
            </div>

            <div class="super-analytics-stat-meta">
                {{ $totalAttempts }} attempts
            </div>
        </div>


        <div class="super-analytics-stat">
            <div class="super-analytics-stat-label">
                Assessment Coverage
            </div>

            <div class="super-analytics-stat-value">
                {{ number_format($assessmentCoverage, 1) }}%
            </div>

            <div class="super-analytics-stat-meta">
                {{ $assessedUsers }} users assessed
            </div>
        </div>

    </div>


    {{-- Period performance --}}
    <div class="super-analytics-card">

        <div class="super-analytics-card-heading">

            <div>

                <h2>
                    Assessment Performance
                </h2>

                <p>
                    Activity for the selected period.
                </p>

            </div>

        </div>


        <div class="super-analytics-three-column">

            <div class="super-analytics-metric">

                <span>
                    Assessment Attempts
                </span>

                <strong>
                    {{ $periodAttemptCount }}
                </strong>

            </div>


            <div class="super-analytics-metric">

                <span>
                    Completed
                </span>

                <strong>
                    {{ $periodCompletedCount }}
                </strong>

            </div>


            <div class="super-analytics-metric">

                <span>
                    Average Score
                </span>

                <strong>
                    {{ $periodAverageScore !== null
                        ? number_format($periodAverageScore, 1) . '%'
                        : '—'
                    }}
                </strong>

            </div>

        </div>

    </div>


    {{-- Activity + risk --}}
    <div class="super-analytics-two-column">

        <div class="super-analytics-card">

            <div class="super-analytics-card-heading">

                <div>
                    <h2>
                        Assessment Activity
                    </h2>

                    <p>
                        Daily assessment attempt volume.
                    </p>
                </div>

            </div>


            @php
                $maxActivity = max(
                    $dailyActivity->max('total') ?? 1,
                    1
                );
            @endphp


            <div class="super-analytics-activity">

                @forelse($dailyActivity as $activity)

                    @php
                        $height = max(
                            3,
                            ($activity['total'] / $maxActivity) * 100
                        );
                    @endphp

                    <div class="super-analytics-activity-column">

                        <div class="super-analytics-activity-bar-wrap">

                            <div
                                class="super-analytics-activity-bar"
                                style="height: {{ $height }}%;"
                                title="{{ $activity['total'] }} attempts"
                            ></div>

                        </div>

                        <div class="super-analytics-activity-label">
                            {{ \Carbon\Carbon::parse(
                                $activity['date']
                            )->format('d M') }}
                        </div>

                    </div>

                @empty

                    <div class="super-analytics-muted">
                        No assessment activity for this period.
                    </div>

                @endforelse

            </div>

        </div>


        <div class="super-analytics-card">

            <div class="super-analytics-card-heading">

                <div>
                    <h2>
                        Platform Risk Distribution
                    </h2>

                    <p>
                        All recorded assessment risk levels.
                    </p>
                </div>

            </div>


            <div class="super-analytics-risk">

                <div class="super-analytics-risk-row">

                    <span>Low</span>

                    <div class="super-analytics-risk-bar">
                        <div
                            class="super-analytics-risk-fill low"
                            style="width: {{ $lowRiskPercentage }}%;"
                        ></div>
                    </div>

                    <strong>
                        {{ $lowRisk }}
                    </strong>

                </div>


                <div class="super-analytics-risk-row">

                    <span>Medium</span>

                    <div class="super-analytics-risk-bar">
                        <div
                            class="super-analytics-risk-fill medium"
                            style="width: {{ $mediumRiskPercentage }}%;"
                        ></div>
                    </div>

                    <strong>
                        {{ $mediumRisk }}
                    </strong>

                </div>


                <div class="super-analytics-risk-row">

                    <span>High</span>

                    <div class="super-analytics-risk-bar">
                        <div
                            class="super-analytics-risk-fill high"
                            style="width: {{ $highRiskPercentage }}%;"
                        ></div>
                    </div>

                    <strong>
                        {{ $highRisk }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- Learning + commercial --}}
    <div class="super-analytics-two-column">

        <div class="super-analytics-card">

            <div class="super-analytics-card-heading">

                <div>
                    <h2>
                        Learning Plan Performance
                    </h2>

                    <p>
                        Platform-wide training progress.
                    </p>
                </div>

            </div>


            <div class="super-analytics-learning">

                <div class="super-analytics-learning-row">
                    <span>Total Plans</span>
                    <strong>{{ $totalLearningPlans }}</strong>
                </div>

                <div class="super-analytics-learning-row">
                    <span>Completed</span>
                    <strong>{{ $completedLearningPlans }}</strong>
                </div>

                <div class="super-analytics-learning-row">
                    <span>In Progress</span>
                    <strong>{{ $inProgressLearningPlans }}</strong>
                </div>

                <div class="super-analytics-learning-row">
                    <span>Not Started</span>
                    <strong>{{ $notStartedLearningPlans }}</strong>
                </div>

                <div class="super-analytics-learning-row">
                    <span>Average Progress</span>
                    <strong>
                        {{ $averageLearningProgress !== null
                            ? number_format(
                                $averageLearningProgress,
                                1
                            ) . '%'
                            : '—'
                        }}
                    </strong>
                </div>

                <div class="super-analytics-learning-row">
                    <span>Overdue</span>

                    <strong>
                        @if($overdueLearningPlans > 0)

                            <span class="super-analytics-badge super-analytics-danger">
                                {{ $overdueLearningPlans }}
                            </span>

                        @else

                            <span class="super-analytics-badge super-analytics-success">
                                0
                            </span>

                        @endif
                    </strong>

                </div>

            </div>

        </div>


        <div class="super-analytics-card">

            <div class="super-analytics-card-heading">

                <div>
                    <h2>
                        Subscription Value
                    </h2>

                    <p>
                        Current active and trial recurring value.
                    </p>
                </div>

            </div>


            <div class="super-analytics-three-column">

                <div class="super-analytics-metric">

                    <span>
                        Active
                    </span>

                    <strong>
                        {{ $activeSubscriptions }}
                    </strong>

                </div>


                <div class="super-analytics-metric">

                    <span>
                        Trials
                    </span>

                    <strong>
                        {{ $trialSubscriptions }}
                    </strong>

                </div>


                <div class="super-analytics-metric">

                    <span>
                        Monthly Value
                    </span>

                    <strong>
                        £{{ number_format(
                            $monthlyRecurringValue,
                            2
                        ) }}
                    </strong>

                </div>

            </div>


            <div style="
                margin-top: 16px;
                padding: 15px;
                border-radius: 12px;
                background: rgba(127,127,127,.035);
            ">

                <div style="
                    font-size: 10px;
                    opacity: .55;
                    margin-bottom: 5px;
                ">
                    Annual Recurring Value
                </div>

                <div style="
                    font-size: 25px;
                    font-weight: 800;
                ">
                    £{{ number_format(
                        $annualRecurringValue,
                        2
                    ) }}
                </div>

            </div>

        </div>

    </div>


    {{-- Organisation performance --}}
    <div class="super-analytics-card">

        <div class="super-analytics-card-heading">

            <div>

                <h2>
                    Organisation Performance
                </h2>

                <p>
                    Assessment activity and cybersecurity performance by organisation.
                </p>

            </div>

        </div>


        @if($organisationAnalytics->count())

            <div class="super-analytics-table-wrap">

                <table class="super-analytics-table">

                    <thead>

                        <tr>
                            <th>Organisation</th>
                            <th>Users</th>
                            <th>Attempts</th>
                            <th>Average Score</th>
                            <th>High Risk</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($organisationAnalytics as $item)

                            <tr>

                                <td>
                                    <span class="super-analytics-org-name">
                                        {{ $item['organisation']->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item['users'] }}
                                </td>

                                <td>
                                    {{ $item['attempts'] }}
                                </td>

                                <td>

                                    @if($item['average_score'] !== null)

                                        {{ number_format(
                                            $item['average_score'],
                                            1
                                        ) }}%

                                    @else

                                        <span class="super-analytics-muted">
                                            —
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($item['high_risk'] > 0)

                                        <span class="
                                            super-analytics-badge
                                            super-analytics-danger
                                        ">
                                            {{ $item['high_risk'] }}
                                        </span>

                                    @else

                                        <span class="
                                            super-analytics-badge
                                            super-analytics-success
                                        ">
                                            0
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="super-analytics-muted">
                No organisations are currently available.
            </div>

        @endif

    </div>


    {{-- Recent assessment activity --}}
    <div class="super-analytics-card">

        <div class="super-analytics-card-heading">

            <div>
                <h2>
                    Recent Assessment Activity
                </h2>

                <p>
                    Latest assessment attempts across the platform.
                </p>
            </div>

        </div>


        @if($recentAttempts->count())

            <div class="super-analytics-recent">

                @foreach($recentAttempts as $attempt)

                    <div class="super-analytics-recent-item">

                        <div>

                            <div class="super-analytics-recent-primary">
                                {{ $attempt->user?->name ?? 'Unknown User' }}
                            </div>

                            <div class="super-analytics-recent-secondary">

                                {{ $attempt->user?->organisation?->name
                                    ?? 'No organisation'
                                }}

                                ·

                                {{ $attempt->assessment?->name
                                    ?? 'Assessment'
                                }}

                                ·

                                {{ $attempt->created_at?->format('d M Y H:i') }}

                            </div>

                        </div>


                        <div class="super-analytics-recent-score">

                            @if($attempt->score_percentage !== null)

                                {{ number_format(
                                    $attempt->score_percentage,
                                    1
                                ) }}%

                            @else

                                —

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="super-analytics-muted">
                No assessment activity has been recorded yet.
            </div>

        @endif

    </div>


    {{-- Recently added organisations --}}
    <div class="super-analytics-card">

        <div class="super-analytics-card-heading">

            <div>
                <h2>
                    Recently Added Organisations
                </h2>

                <p>
                    Latest organisations added to the CyberReadyAI platform.
                </p>
            </div>

        </div>


        @if($recentOrganisations->count())

            <div class="super-analytics-table-wrap">

                <table class="super-analytics-table">

                    <thead>

                        <tr>
                            <th>Organisation</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($recentOrganisations as $organisation)

                            <tr>

                                <td>
                                    <span class="super-analytics-org-name">
                                        {{ $organisation->name }}
                                    </span>
                                </td>

                                <td>

                                    @if($organisation->status === 'active')

                                        <span class="
                                            super-analytics-badge
                                            super-analytics-success
                                        ">
                                            Active
                                        </span>

                                    @else

                                        <span class="
                                            super-analytics-badge
                                            super-analytics-warning
                                        ">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $organisation->created_at?->format('d M Y') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="super-analytics-muted">
                No organisations have been added yet.
            </div>

        @endif

    </div>

</div>

@endsection