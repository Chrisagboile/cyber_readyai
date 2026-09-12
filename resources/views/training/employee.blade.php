@extends('layouts.app')

@section('content')

<div class="training-employee-page">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="training-employee-header">

        <div>

            <a
                href="{{ route('organisation.training') }}"
                class="training-employee-back"
            >
                ← Back to Training
            </a>

            <span class="training-employee-eyebrow">
                Employee Training Profile
            </span>

            <div class="training-employee-title-row">

                <div class="training-employee-avatar">
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                </div>

                <div>
                    <h1>{{ $employee->name }}</h1>

                    <p>
                        {{ $employee->email }}

                        @if($employee->department)
                            · {{ $employee->department->name }}
                        @endif
                    </p>
                </div>

            </div>

        </div>

        <div class="training-employee-actions">

            <a
                href="{{ route('organisation.reports', ['employee_id' => $employee->id]) }}"
                class="training-employee-btn training-employee-btn-secondary"
            >
                View Reports
            </a>

            <a
                href="{{ route('organisation.training') }}"
                class="training-employee-btn training-employee-btn-primary"
            >
                Training Overview
            </a>

        </div>

    </div>


    {{-- =========================================================
        PROFILE SUMMARY
    ========================================================== --}}
    <div class="training-employee-profile">

        <div class="training-employee-profile-item">
            <span>Department</span>
            <strong>
                {{ optional($employee->department)->name ?? 'No department' }}
            </strong>
        </div>

        <div class="training-employee-profile-item">
            <span>Learning Plans</span>
            <strong>{{ $totalPlans }}</strong>
        </div>

        <div class="training-employee-profile-item">
            <span>Completion Rate</span>
            <strong>{{ number_format($completionRate, 1) }}%</strong>
        </div>

        <div class="training-employee-profile-item">
            <span>Overall Progress</span>
            <strong>{{ number_format($averageProgress, 1) }}%</strong>
        </div>

        <div class="training-employee-profile-item">
            <span>Current Risk</span>

            @if($riskLevel === 'high')
                <strong class="training-risk-high">High</strong>
            @elseif($riskLevel === 'medium')
                <strong class="training-risk-medium">Medium</strong>
            @elseif($riskLevel === 'low')
                <strong class="training-risk-low">Low</strong>
            @else
                <strong>Not Assessed</strong>
            @endif

        </div>

    </div>


    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}
    <div class="training-employee-kpis">

        <div class="training-employee-kpi">
            <span>Total Plans</span>
            <strong>{{ $totalPlans }}</strong>
            <small>Assigned learning plans</small>
        </div>

        <div class="training-employee-kpi">
            <span>Completed</span>
            <strong>{{ $completedPlans }}</strong>
            <small>Completed successfully</small>
        </div>

        <div class="training-employee-kpi">
            <span>In Progress</span>
            <strong>{{ $inProgressPlans }}</strong>
            <small>Currently active</small>
        </div>

        <div class="training-employee-kpi">
            <span>Not Started</span>
            <strong>{{ $notStartedPlans }}</strong>
            <small>Awaiting employee action</small>
        </div>

        <div class="training-employee-kpi">
            <span>Overdue</span>
            <strong>{{ $overduePlans }}</strong>
            <small>Past due date</small>
        </div>

        <div class="training-employee-kpi">
            <span>High Priority</span>
            <strong>{{ $highPriorityPlans }}</strong>
            <small>Outstanding high-priority items</small>
        </div>

    </div>


    {{-- =========================================================
        PROGRESS + ASSESSMENT
    ========================================================== --}}
    <div class="training-employee-two-column">

        <div class="training-employee-card">

            <div class="training-employee-section-head">
                <div>
                    <h2>Training Progress</h2>
                    <p>Overall progress across this employee's learning plans.</p>
                </div>
            </div>

            <div class="training-employee-big-progress">

                <strong>
                    {{ number_format($averageProgress, 1) }}%
                </strong>

                <span>Average learning-plan progress</span>

                <div class="training-employee-progress-track">
                    <div
                        class="training-employee-progress-fill"
                        style="width: {{ min(100, max(0, $averageProgress)) }}%;"
                    ></div>
                </div>

            </div>

            <div class="training-employee-progress-stats">

                <div>
                    <span>Completed</span>
                    <strong>{{ $completedPlans }}</strong>
                </div>

                <div>
                    <span>In Progress</span>
                    <strong>{{ $inProgressPlans }}</strong>
                </div>

                <div>
                    <span>Not Started</span>
                    <strong>{{ $notStartedPlans }}</strong>
                </div>

                <div>
                    <span>Overdue</span>
                    <strong>{{ $overduePlans }}</strong>
                </div>

            </div>

        </div>


        <div class="training-employee-card">

            <div class="training-employee-section-head">
                <div>
                    <h2>Latest Assessment</h2>
                    <p>The assessment associated with recent remediation activity.</p>
                </div>
            </div>

            @if($latestAttempt && $latestAssessment)

                <div class="training-employee-assessment">

                    <div>
                        <span>Assessment</span>
                        <strong>{{ $latestAssessment->name }}</strong>
                    </div>

                    <div>
                        <span>Score</span>
                        <strong>
                            {{ number_format((float) $latestAttempt->score_percentage, 1) }}%
                        </strong>
                    </div>

                    <div>
                        <span>Risk</span>

                        @if($riskLevel === 'high')
                            <strong class="training-risk-high">
                                High
                            </strong>
                        @elseif($riskLevel === 'medium')
                            <strong class="training-risk-medium">
                                Medium
                            </strong>
                        @elseif($riskLevel === 'low')
                            <strong class="training-risk-low">
                                Low
                            </strong>
                        @else
                            <strong>—</strong>
                        @endif
                    </div>

                    <div>
                        <span>Completed</span>
                        <strong>
                            {{ optional($latestAttempt->completed_at)->format('M j, Y') ?? '—' }}
                        </strong>
                    </div>

                </div>

                <a
                    href="{{ route('assessment.result', $latestAttempt) }}"
                    class="training-employee-result-link"
                >
                    View Assessment Result →
                </a>

            @else

                <div class="training-employee-empty-small">
                    <strong>No assessment linked</strong>
                    <span>
                        No completed assessment is currently associated
                        with this employee's learning plans.
                    </span>
                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        LEARNING PLANS
    ========================================================== --}}
    <div class="training-employee-card">

        <div class="training-employee-section-head">

            <div>
                <h2>Learning Plans</h2>
                <p>
                    All remediation and learning activity assigned to this employee.
                </p>
            </div>

            <span class="training-employee-count">
                {{ $totalPlans }}
                {{ $totalPlans === 1 ? 'plan' : 'plans' }}
            </span>

        </div>


        @if($plans->count())

            <div class="training-employee-plans">

                @foreach($plans as $plan)

                    @php
                        $planStatus = strtolower((string) $plan->status);
                        $planPriority = strtolower((string) $plan->priority);

                        $planProgress = min(
                            100,
                            max(0, (int) $plan->progress_percentage)
                        );

                        $isOverdue = $plan->due_date
                            && $planStatus !== 'completed'
                            && $plan->due_date->isPast();
                    @endphp


                    <div class="training-employee-plan">

                        <div class="training-employee-plan-heading">

                            <div class="training-employee-plan-icon">
                                ✓
                            </div>

                            <div>

                                <h3>{{ $plan->title }}</h3>

                                @if($plan->description)
                                    <p>{{ $plan->description }}</p>
                                @endif

                            </div>

                        </div>


                        <div class="training-employee-plan-progress">

                            <div class="training-employee-plan-progress-top">

                                <span>Progress</span>

                                <strong>
                                    {{ $planProgress }}%
                                </strong>

                            </div>

                            <div class="training-employee-progress-track">
                                <div
                                    class="training-employee-progress-fill"
                                    style="width: {{ $planProgress }}%;"
                                ></div>
                            </div>

                        </div>


                        <div class="training-employee-plan-status">

                            @if($planStatus === 'completed')

                                <span class="training-employee-badge training-employee-badge-success">
                                    Completed
                                </span>

                            @elseif($planStatus === 'in_progress')

                                <span class="training-employee-badge training-employee-badge-info">
                                    In Progress
                                </span>

                            @else

                                <span class="training-employee-badge training-employee-badge-neutral">
                                    Not Started
                                </span>

                            @endif


                            @if($planPriority === 'high')

                                <span class="training-employee-badge training-employee-badge-danger">
                                    High Priority
                                </span>

                            @elseif($planPriority === 'medium')

                                <span class="training-employee-badge training-employee-badge-warning">
                                    Medium Priority
                                </span>

                            @elseif($planPriority === 'low')

                                <span class="training-employee-badge training-employee-badge-success">
                                    Low Priority
                                </span>

                            @endif

                        </div>


                        <div class="training-employee-plan-details">

                            @if($plan->due_date)

                                @if($isOverdue)

                                    <span class="training-plan-overdue">
                                        Overdue:
                                        {{ $plan->due_date->format('M j, Y') }}
                                    </span>

                                @else

                                    <span>
                                        Due:
                                        {{ $plan->due_date->format('M j, Y') }}
                                    </span>

                                @endif

                            @else

                                <span>No due date</span>

                            @endif


                            @if($plan->completed_at)

                                <span>
                                    Completed:
                                    {{ $plan->completed_at->format('M j, Y') }}
                                </span>

                            @endif

                        </div>


                        @if($plan->assessmentAttempt && $plan->assessmentAttempt->assessment)

                            <div class="training-employee-plan-source">

                                <span>Source assessment</span>

                                <strong>
                                    {{ $plan->assessmentAttempt->assessment->name }}
                                </strong>

                                <a
                                    href="{{ route('assessment.result', $plan->assessmentAttempt) }}"
                                >
                                    View Result →
                                </a>

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <div class="training-employee-empty">

                <div class="training-employee-empty-icon">
                    LP
                </div>

                <h3>No learning plans</h3>

                <p>
                    This employee currently has no learning or remediation
                    plans assigned.
                </p>

                <a
                    href="{{ route('organisation.assessments') }}"
                    class="training-employee-btn training-employee-btn-primary"
                >
                    Manage Assessments
                </a>

            </div>

        @endif

    </div>

</div>


<style>
.training-employee-page {
    max-width: 1450px;
    margin: 0 auto;
    padding: 8px 0 45px;
}

.training-employee-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 28px;
    margin-bottom: 22px;
}

.training-employee-back {
    display: inline-block;
    margin-bottom: 12px;
    color: inherit;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    opacity: .62;
}

.training-employee-back:hover {
    opacity: .95;
    color: inherit;
}

.training-employee-eyebrow {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .09em;
    opacity: .52;
}

.training-employee-title-row {
    display: flex;
    align-items: center;
    gap: 14px;
}

.training-employee-avatar {
    width: 54px;
    height: 54px;
    flex: 0 0 54px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(127, 127, 127, .10);
    font-size: 19px;
    font-weight: 800;
}

.training-employee-header h1 {
    margin: 0;
    font-size: 29px;
    line-height: 1.15;
}

.training-employee-header p {
    margin: 6px 0 0;
    font-size: 13px;
    opacity: .61;
}

.training-employee-actions {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}

.training-employee-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 41px;
    padding: 0 15px;
    border-radius: 10px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    border: 1px solid transparent;
}

.training-employee-btn-primary {
    background: #111827;
    color: #fff;
}

.training-employee-btn-primary:hover {
    color: #fff;
    opacity: .92;
}

.training-employee-btn-secondary {
    color: inherit;
    background: rgba(127, 127, 127, .08);
    border-color: rgba(127, 127, 127, .16);
}

.training-employee-btn-secondary:hover {
    color: inherit;
    background: rgba(127, 127, 127, .13);
}

.training-employee-profile {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}

.training-employee-profile-item {
    padding: 15px;
    border-radius: 12px;
    border: 1px solid rgba(127, 127, 127, .12);
    background: rgba(127, 127, 127, .035);
}

.training-employee-profile-item span,
.training-employee-profile-item strong {
    display: block;
}

.training-employee-profile-item span {
    margin-bottom: 4px;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    opacity: .55;
}

.training-employee-profile-item strong {
    font-size: 14px;
}

.training-risk-high {
    color: #b42332 !important;
}

.training-risk-medium {
    color: #966b00 !important;
}

.training-risk-low {
    color: #25784c !important;
}

.training-employee-kpis {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 13px;
    margin-bottom: 22px;
}

.training-employee-kpi {
    padding: 17px;
    border: 1px solid rgba(127, 127, 127, .14);
    border-radius: 14px;
    background: var(--card-bg, #fff);
    box-shadow: 0 5px 19px rgba(0, 0, 0, .03);
}

.training-employee-kpi span,
.training-employee-kpi small {
    display: block;
}

.training-employee-kpi span {
    margin-bottom: 4px;
    font-size: 11px;
    opacity: .58;
}

.training-employee-kpi strong {
    display: block;
    font-size: 24px;
    line-height: 1.05;
}

.training-employee-kpi small {
    margin-top: 6px;
    font-size: 10px;
    opacity: .48;
}

.training-employee-two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

.training-employee-card {
    padding: 22px;
    margin-bottom: 22px;
    border: 1px solid rgba(127, 127, 127, .14);
    border-radius: 16px;
    background: var(--card-bg, #fff);
    box-shadow: 0 7px 24px rgba(0, 0, 0, .035);
}

.training-employee-section-head {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    align-items: flex-start;
    margin-bottom: 20px;
}

.training-employee-section-head h2 {
    margin: 0;
    font-size: 18px;
}

.training-employee-section-head p {
    margin: 5px 0 0;
    font-size: 12px;
    opacity: .59;
}

.training-employee-big-progress strong {
    display: block;
    font-size: 38px;
    line-height: 1;
}

.training-employee-big-progress > span {
    display: block;
    margin-top: 7px;
    margin-bottom: 17px;
    font-size: 11px;
    opacity: .55;
}

.training-employee-progress-track {
    height: 8px;
    overflow: hidden;
    border-radius: 999px;
    background: rgba(127, 127, 127, .12);
}

.training-employee-progress-fill {
    height: 100%;
    border-radius: inherit;
    background: currentColor;
    opacity: .78;
}

.training-employee-progress-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 9px;
    margin-top: 18px;
}

.training-employee-progress-stats > div {
    padding: 12px;
    border-radius: 10px;
    background: rgba(127, 127, 127, .07);
}

.training-employee-progress-stats span,
.training-employee-progress-stats strong {
    display: block;
}

.training-employee-progress-stats span {
    margin-bottom: 4px;
    font-size: 10px;
    opacity: .55;
}

.training-employee-progress-stats strong {
    font-size: 18px;
}

.training-employee-assessment {
    display: grid;
    gap: 12px;
}

.training-employee-assessment > div {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(127, 127, 127, .10);
}

.training-employee-assessment > div:last-child {
    border-bottom: 0;
}

.training-employee-assessment span {
    font-size: 11px;
    opacity: .54;
}

.training-employee-assessment strong {
    text-align: right;
    font-size: 13px;
}

.training-employee-result-link {
    display: inline-block;
    margin-top: 15px;
    color: inherit;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
}

.training-employee-result-link:hover {
    text-decoration: underline;
}

.training-employee-empty-small {
    display: grid;
    gap: 7px;
    padding: 20px 0;
}

.training-employee-empty-small strong {
    font-size: 14px;
}

.training-employee-empty-small span {
    font-size: 12px;
    opacity: .58;
}

.training-employee-count {
    padding: 7px 10px;
    border-radius: 999px;
    background: rgba(127, 127, 127, .08);
    font-size: 10px;
    font-weight: 800;
}

.training-employee-plans {
    display: grid;
    gap: 12px;
}

.training-employee-plan {
    display: grid;
    grid-template-columns: minmax(260px, 1.4fr) minmax(180px, .85fr) minmax(175px, .7fr);
    gap: 18px;
    align-items: center;
    padding: 17px;
    border-radius: 12px;
    border: 1px solid rgba(127, 127, 127, .11);
    background: rgba(127, 127, 127, .025);
}

.training-employee-plan-heading {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.training-employee-plan-icon {
    width: 37px;
    height: 37px;
    flex: 0 0 37px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: rgba(127, 127, 127, .10);
    font-size: 12px;
    font-weight: 800;
}

.training-employee-plan-heading h3 {
    margin: 0;
    font-size: 14px;
}

.training-employee-plan-heading p {
    margin: 5px 0 0;
    font-size: 11px;
    line-height: 1.5;
    opacity: .58;
}

.training-employee-plan-progress-top {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 6px;
    font-size: 11px;
}

.training-employee-plan-progress-top span {
    opacity: .56;
}

.training-employee-plan-progress-top strong {
    font-size: 12px;
}

.training-employee-plan-status {
    display: flex;
    justify-content: flex-start;
    flex-wrap: wrap;
    gap: 6px;
}

.training-employee-badge {
    display: inline-flex;
    align-items: center;
    min-height: 24px;
    padding: 0 8px;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 800;
}

.training-employee-badge-neutral {
    background: rgba(127, 127, 127, .10);
}

.training-employee-badge-danger {
    color: #b42332;
    background: rgba(220, 53, 69, .11);
}

.training-employee-badge-warning {
    color: #966b00;
    background: rgba(240, 173, 0, .13);
}

.training-employee-badge-info {
    color: #1f6099;
    background: rgba(30, 110, 190, .11);
}

.training-employee-badge-success {
    color: #25784c;
    background: rgba(46, 157, 99, .11);
}

.training-employee-plan-details {
    grid-column: 1 / -1;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    padding-top: 11px;
    border-top: 1px solid rgba(127, 127, 127, .09);
    font-size: 10px;
    opacity: .56;
}

.training-plan-overdue {
    color: #b42332;
    font-weight: 800;
    opacity: 1;
}

.training-employee-plan-source {
    grid-column: 1 / -1;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    padding-top: 10px;
    font-size: 10px;
}

.training-employee-plan-source span {
    opacity: .52;
}

.training-employee-plan-source strong {
    font-size: 11px;
}

.training-employee-plan-source a {
    margin-left: auto;
    color: inherit;
    text-decoration: none;
    font-weight: 800;
}

.training-employee-plan-source a:hover {
    text-decoration: underline;
}

.training-employee-empty {
    padding: 40px 15px 25px;
    text-align: center;
}

.training-employee-empty-icon {
    width: 47px;
    height: 47px;
    margin: 0 auto 13px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(127, 127, 127, .09);
    font-size: 12px;
    font-weight: 800;
}

.training-employee-empty h3 {
    margin: 0 0 7px;
    font-size: 16px;
}

.training-employee-empty p {
    max-width: 600px;
    margin: 0 auto 18px;
    font-size: 12px;
    opacity: .58;
}

@media (max-width: 1250px) {
    .training-employee-kpis {
        grid-template-columns: repeat(3, 1fr);
    }

    .training-employee-profile {
        grid-template-columns: repeat(3, 1fr);
    }

    .training-employee-plan {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 950px) {
    .training-employee-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .training-employee-two-column {
        grid-template-columns: 1fr;
    }

    .training-employee-plan {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
    .training-employee-profile,
    .training-employee-kpis {
        grid-template-columns: repeat(2, 1fr);
    }

    .training-employee-progress-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .training-employee-card {
        padding: 17px;
    }

    .training-employee-plan-details,
    .training-employee-plan-source {
        grid-column: auto;
    }

    .training-employee-plan-source a {
        margin-left: 0;
    }
}

@media (max-width: 480px) {
    .training-employee-profile,
    .training-employee-kpis {
        grid-template-columns: 1fr;
    }

    .training-employee-header h1 {
        font-size: 25px;
    }

    .training-employee-title-row {
        align-items: flex-start;
    }
}
</style>

@endsection
