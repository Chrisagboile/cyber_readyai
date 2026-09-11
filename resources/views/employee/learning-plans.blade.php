@extends('layouts.app')

@section('title', 'Learning Plans')

@section('content')

<div class="employee-learning-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="employee-learning-header">

        <div>
            <h1 class="employee-learning-title">
                Learning Plans
            </h1>

            <p class="employee-learning-description">
                Your personalised cybersecurity learning plans based on
                your assessment results.
            </p>
        </div>

        <div class="employee-learning-date">
            {{ now()->format('F j, Y') }}
        </div>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="employee-learning-alert">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}

    <div class="employee-learning-stats">

        {{-- Total --}}
        <div class="employee-learning-stat-card">

            <div class="employee-learning-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                </svg>

            </div>

            <div>
                <span>Total Plans</span>
                <strong>{{ $totalPlans }}</strong>
                <small>Assigned learning plans</small>
            </div>

        </div>


        {{-- Not Started --}}
        <div class="employee-learning-stat-card">

            <div class="employee-learning-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <polyline points="12 7 12 12 15 14"/>
                </svg>

            </div>

            <div>
                <span>Not Started</span>
                <strong>{{ $notStartedPlans }}</strong>
                <small>Plans awaiting action</small>
            </div>

        </div>


        {{-- In Progress --}}
        <div class="employee-learning-stat-card">

            <div class="employee-learning-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2a10 10 0 1 0 10 10"/>
                    <path d="M12 6v6l4 2"/>
                </svg>

            </div>

            <div>
                <span>In Progress</span>
                <strong>{{ $inProgressPlans }}</strong>
                <small>Plans currently underway</small>
            </div>

        </div>


        {{-- Completed --}}
        <div class="employee-learning-stat-card">

            <div class="employee-learning-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>

            </div>

            <div>
                <span>Completed</span>
                <strong>{{ $completedPlans }}</strong>
                <small>Successfully completed plans</small>
            </div>

        </div>

    </div>


    {{-- =========================================================
         SECTION HEADER
    ========================================================== --}}

    <div class="employee-learning-section-header">

        <div>
            <h2>Your Learning Plans</h2>

            <p>
                Work through these personalised recommendations
                to improve your cybersecurity readiness.
            </p>
        </div>

    </div>


    {{-- =========================================================
         EMPTY STATE
    ========================================================== --}}

    @if($learningPlans->isEmpty())

        <div class="employee-learning-card">

            <div class="employee-learning-empty">

                <div class="employee-learning-empty-icon">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                    </svg>

                </div>

                <h3>No learning plans yet</h3>

                <p>
                    Complete a cybersecurity assessment to receive
                    personalised learning plans based on your results.
                </p>

                <a
                    href="{{ route('assessment.index') }}"
                    class="employee-learning-primary-button"
                >
                    View My Assessments
                </a>

            </div>

        </div>

    @else


        {{-- =====================================================
             LEARNING PLAN LIST
        ====================================================== --}}

        <div class="employee-learning-list">

            @foreach($learningPlans as $plan)

                @php
                    $priority = strtolower((string) $plan->priority);
                    $status = strtolower((string) $plan->status);

                    $priorityClass = match ($priority) {
                        'high' => 'priority-high',
                        'medium' => 'priority-medium',
                        'low' => 'priority-low',
                        default => 'priority-neutral',
                    };

                    $statusClass = match ($status) {
                        'completed' => 'plan-completed',
                        'in_progress' => 'plan-progress',
                        'not_started' => 'plan-not-started',
                        default => 'plan-neutral',
                    };

                    $statusLabel = ucwords(
                        str_replace('_', ' ', $plan->status)
                    );

                    $progress = min(
                        100,
                        max(
                            0,
                            (float) $plan->progress_percentage
                        )
                    );

                    $isOverdue = $plan->due_date
                        && $plan->due_date->isPast()
                        && $plan->status !== 'completed';
                @endphp


                <div class="employee-learning-plan-card">

                    {{-- =================================================
                         PLAN HEADER
                    ================================================== --}}

                    <div class="employee-learning-plan-header">

                        <div class="employee-learning-plan-title-area">

                            <div class="employee-learning-plan-icon">

                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                </svg>

                            </div>

                            <div>

                                <h3>
                                    {{ $plan->title }}
                                </h3>

                                @if($plan->assessmentAttempt?->assessment)

                                    <span class="employee-learning-source">
                                        From:
                                        {{ $plan->assessmentAttempt->assessment->name }}
                                    </span>

                                @endif

                            </div>

                        </div>


                        <span
                            class="employee-learning-priority {{ $priorityClass }}"
                        >
                            {{ ucfirst($plan->priority) }} Priority
                        </span>

                    </div>


                    {{-- =================================================
                         DESCRIPTION
                    ================================================== --}}

                    @if($plan->description)

                        <div class="employee-learning-description-box">

                            {{ $plan->description }}

                        </div>

                    @endif


                    {{-- =================================================
                         PROGRESS
                    ================================================== --}}

                    <div class="employee-learning-progress-section">

                        <div class="employee-learning-progress-header">

                            <strong>Progress</strong>

                            <span>
                                {{ number_format($progress, 0) }}%
                            </span>

                        </div>

                        <div class="employee-learning-progress-track">

                            <div
                                class="employee-learning-progress-bar"
                                style="width: {{ $progress }}%;"
                            ></div>

                        </div>

                    </div>


                    {{-- =================================================
                         STATUS / DUE DATE / ACTION
                    ================================================== --}}

                    <div class="employee-learning-plan-footer">

                        <div class="employee-learning-plan-meta">

                            <div>

                                <span class="employee-learning-meta-label">
                                    Status
                                </span>

                                <span
                                    class="employee-learning-status {{ $statusClass }}"
                                >
                                    {{ $statusLabel }}
                                </span>

                            </div>


                            @if($plan->due_date)

                                <div>

                                    <span class="employee-learning-meta-label">
                                        Due Date
                                    </span>

                                    <span
                                        class="employee-learning-due {{ $isOverdue ? 'overdue' : '' }}"
                                    >
                                        {{ $plan->due_date->format('F j, Y') }}

                                        @if($isOverdue)
                                            <small>Overdue</small>
                                        @endif
                                    </span>

                                </div>

                            @endif


                            @if($plan->completed_at)

                                <div>

                                    <span class="employee-learning-meta-label">
                                        Completed
                                    </span>

                                    <span class="employee-learning-completed-date">
                                        {{ $plan->completed_at->format('F j, Y') }}
                                    </span>

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                             ACTION
                        ================================================== --}}

                        <div class="employee-learning-plan-action">

                            @if($plan->status === 'not_started')

                                <form
                                    method="POST"
                                    action="{{ route('employee.learning-plans.status', $plan) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="in_progress"
                                    >

                                    <button
                                        type="submit"
                                        class="employee-learning-action-button primary"
                                    >
                                        Start Plan
                                    </button>

                                </form>

                            @elseif($plan->status === 'in_progress')

                                <form
                                    method="POST"
                                    action="{{ route('employee.learning-plans.status', $plan) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="completed"
                                    >

                                    <button
                                        type="submit"
                                        class="employee-learning-action-button primary"
                                    >
                                        Mark Complete
                                    </button>

                                </form>

                            @else

                                <span class="employee-learning-complete-label">
                                    ✓ Completed
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($learningPlans->hasPages())

            <div class="employee-learning-pagination">
                {{ $learningPlans->links() }}
            </div>

        @endif

    @endif

</div>


<style>

/* =========================================================
   EMPLOYEE LEARNING PLANS
========================================================= */

.employee-learning-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.employee-learning-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 28px;
}

.employee-learning-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.employee-learning-description {
    margin: 0;
    max-width: 760px;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.employee-learning-date {
    padding-top: 5px;
    white-space: nowrap;
    font-size: 13px;
    color: #667085;
}


/* =========================================================
   ALERT
========================================================= */

.employee-learning-alert {
    margin-bottom: 20px;
    padding: 13px 15px;
    border-radius: 10px;
    background: #eaf7ef;
    border: 1px solid #ccebd8;
    color: #18794e;
    font-size: 13px;
}


/* =========================================================
   STATISTICS
========================================================= */

.employee-learning-stats {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 30px;
}

.employee-learning-stat-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-height: 112px;
    padding: 20px;
    box-sizing: border-box;

    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, .08);
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .045);
}

.employee-learning-stat-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;
    background: #eef2f5;
    color: #334155;
}

.employee-learning-stat-icon svg {
    width: 22px;
    height: 22px;

    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-learning-stat-icon svg * {
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-learning-stat-card span {
    display: block;
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 600;
    color: #667085;
}

.employee-learning-stat-card strong {
    display: block;
    margin-bottom: 5px;
    font-size: 28px;
    line-height: 1;
    color: #172033;
}

.employee-learning-stat-card small {
    display: block;
    font-size: 11px;
    line-height: 1.4;
    color: #8a94a6;
}


/* =========================================================
   SECTION HEADER
========================================================= */

.employee-learning-section-header {
    margin-bottom: 18px;
}

.employee-learning-section-header h2 {
    margin: 0 0 5px;
    font-size: 20px;
    line-height: 1.3;
    color: #172033;
}

.employee-learning-section-header p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}


/* =========================================================
   EMPTY CARD
========================================================= */

.employee-learning-card {
    overflow: hidden;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.employee-learning-empty {
    padding: 64px 24px;
    text-align: center;
}

.employee-learning-empty-icon {
    width: 54px;
    height: 54px;
    margin: 0 auto 16px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 14px;
    background: #eef2f5;
    color: #334155;
}

.employee-learning-empty-icon svg {
    width: 26px;
    height: 26px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-learning-empty h3 {
    margin: 0 0 7px;
    font-size: 17px;
    color: #172033;
}

.employee-learning-empty p {
    max-width: 520px;
    margin: 0 auto 20px;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}

.employee-learning-primary-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 38px;
    padding: 9px 14px;

    border-radius: 8px;
    background: #2563eb;
    color: #ffffff;

    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}


/* =========================================================
   PLAN LIST
========================================================= */

.employee-learning-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}


/* =========================================================
   PLAN CARD
========================================================= */

.employee-learning-plan-card {
    padding: 24px;
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}


/* =========================================================
   PLAN HEADER
========================================================= */

.employee-learning-plan-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 18px;
}

.employee-learning-plan-title-area {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    min-width: 0;
}

.employee-learning-plan-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;
    background: #eef2f5;
    color: #334155;
}

.employee-learning-plan-icon svg {
    width: 21px;
    height: 21px;

    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-learning-plan-title-area h3 {
    overflow: hidden;
    margin: 1px 0 5px;

    font-size: 16px;
    line-height: 1.35;
    font-weight: 700;
    color: #172033;

    text-overflow: ellipsis;
}

.employee-learning-source {
    display: block;
    font-size: 11px;
    color: #8a94a6;
}


/* =========================================================
   PRIORITY
========================================================= */

.employee-learning-priority {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 999px;

    font-size: 10px;
    line-height: 1.2;
    font-weight: 700;
    white-space: nowrap;
}

.priority-high {
    background: #fde8e8;
    color: #b42318;
}

.priority-medium {
    background: #fff4d6;
    color: #956900;
}

.priority-low {
    background: #e6f6ee;
    color: #18794e;
}

.priority-neutral {
    background: #eef1f4;
    color: #667085;
}


/* =========================================================
   DESCRIPTION
========================================================= */

.employee-learning-description-box {
    margin-bottom: 22px;
    padding: 15px 16px;

    border-radius: 10px;
    background: #f7f8fa;

    font-size: 13px;
    line-height: 1.65;
    color: #556070;
}


/* =========================================================
   PROGRESS
========================================================= */

.employee-learning-progress-section {
    margin-bottom: 22px;
}

.employee-learning-progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;

    font-size: 12px;
}

.employee-learning-progress-header strong {
    font-weight: 700;
    color: #172033;
}

.employee-learning-progress-header span {
    font-weight: 700;
    color: #52677d;
}

.employee-learning-progress-track {
    width: 100%;
    height: 9px;
    overflow: hidden;
    border-radius: 999px;
    background: #edf0f3;
}

.employee-learning-progress-bar {
    height: 100%;
    border-radius: inherit;
    background: #52677d;
    transition: width .2s ease;
}


/* =========================================================
   FOOTER
========================================================= */

.employee-learning-plan-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;

    padding-top: 18px;
    border-top: 1px solid rgba(15,23,42,.08);
}

.employee-learning-plan-meta {
    display: flex;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 22px;
}

.employee-learning-plan-meta > div {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.employee-learning-meta-label {
    font-size: 10px !important;
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 700 !important;
    color: #8a94a6 !important;
}

.employee-learning-due,
.employee-learning-completed-date {
    font-size: 12px;
    font-weight: 600;
    color: #526070;
}

.employee-learning-due.overdue {
    color: #b42318;
}

.employee-learning-due small {
    display: inline-flex;
    margin-left: 5px;
    padding: 3px 6px;
    border-radius: 999px;
    background: #fde8e8;
    color: #b42318;
    font-size: 9px;
    font-weight: 700;
}

.employee-learning-plan-action {
    flex: 0 0 auto;
}

.employee-learning-action-button {
    min-height: 37px;
    padding: 9px 13px;

    border: 0;
    border-radius: 8px;

    font-size: 11px;
    font-weight: 700;

    cursor: pointer;
}

.employee-learning-action-button.primary {
    background: #2563eb;
    color: #ffffff;
}

.employee-learning-complete-label {
    display: inline-flex;
    align-items: center;

    min-height: 37px;
    padding: 9px 12px;

    border-radius: 8px;
    background: #e6f6ee;
    color: #18794e;

    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   PAGINATION
========================================================= */

.employee-learning-pagination {
    margin-top: 20px;
    padding: 18px 22px;

    border-radius: 14px;
    background: #ffffff;
    border: 1px solid rgba(15,23,42,.08);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .employee-learning-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 850px) {

    .employee-learning-header {
        flex-direction: column;
    }

    .employee-learning-date {
        padding-top: 0;
    }

    .employee-learning-plan-header {
        flex-direction: column;
    }

    .employee-learning-plan-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .employee-learning-plan-action {
        width: 100%;
    }

    .employee-learning-action-button {
        width: 100%;
    }

}

@media (max-width: 650px) {

    .employee-learning-page {
        padding: 20px 16px 40px;
    }

    .employee-learning-stats {
        grid-template-columns: 1fr;
    }

    .employee-learning-plan-card {
        padding: 18px;
    }

    .employee-learning-plan-meta {
        flex-direction: column;
        gap: 14px;
    }

}

</style>

@endsection
