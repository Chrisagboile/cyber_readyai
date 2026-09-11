@extends('layouts.app')

@section('content')

@php
    use App\Models\Assessment;
    use App\Models\AssessmentAttempt;
    use App\Models\LearningPlan;

    $employee = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Employee assessment statistics
    |--------------------------------------------------------------------------
    */

    $assignedAssessments = Assessment::query()
        ->where('employee_id', $employee->id)
        ->count();

    $completedAttempts = AssessmentAttempt::query()
        ->where('user_id', $employee->id)
        ->whereIn('status', ['completed', 'expired'])
        ->count();

    $latestAttempt = AssessmentAttempt::query()
        ->where('user_id', $employee->id)
        ->whereIn('status', ['completed', 'expired'])
        ->latest('completed_at')
        ->first();

    $latestScore = $latestAttempt
        ? (float) $latestAttempt->score_percentage
        : null;

    $latestRisk = $latestAttempt
        ? strtolower((string) $latestAttempt->risk_level)
        : null;

    $inProgressAttempts = AssessmentAttempt::query()
        ->where('user_id', $employee->id)
        ->where('status', 'in_progress')
        ->count();

    /*
    |--------------------------------------------------------------------------
    | Learning plans
    |--------------------------------------------------------------------------
    */

    $learningPlans = LearningPlan::query()
        ->where('user_id', $employee->id)
        ->get();

    $learningPlanCount = $learningPlans->count();

    $completedLearningPlans = $learningPlans
        ->where('status', 'completed')
        ->count();

    $inProgressLearningPlans = $learningPlans
        ->where('status', 'in_progress')
        ->count();

    $averageLearningProgress = $learningPlanCount > 0
        ? round((float) $learningPlans->avg('progress_percentage'), 1)
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Remaining assessments
    |--------------------------------------------------------------------------
    */

    $remainingAssessments = max(
        0,
        $assignedAssessments - $completedAttempts
    );
@endphp


<div class="employee-dashboard-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="dashboard-header">

        <div>
            <h1 class="dashboard-title">
                Employee Dashboard
            </h1>

            <p class="dashboard-description">
                Welcome back, {{ $employee->name }}.
                Stay on top of your cybersecurity readiness and learning.
            </p>
        </div>

        <div class="dashboard-date">
            {{ now()->format('l, F j, Y') }}
        </div>

    </div>


    {{-- =========================================================
         READINESS SUMMARY
    ========================================================== --}}

    <div class="employee-readiness-card">

        <div class="employee-readiness-content">

            <div>

                <span class="employee-eyebrow">
                    Your Cybersecurity Readiness
                </span>

                <div class="employee-readiness-heading">

                    @if($latestRisk === 'high')
                        <span class="employee-status employee-status-high">
                            High Risk
                        </span>
                    @elseif($latestRisk === 'medium')
                        <span class="employee-status employee-status-medium">
                            Medium Risk
                        </span>
                    @elseif($latestRisk === 'low')
                        <span class="employee-status employee-status-low">
                            Low Risk
                        </span>
                    @else
                        <span class="employee-status employee-status-neutral">
                            Not Yet Assessed
                        </span>
                    @endif

                    @if($latestScore !== null)
                        <strong>
                            {{ number_format($latestScore, 1) }}%
                        </strong>
                    @endif

                </div>

                <p>
                    @if($latestAttempt)
                        Your latest completed assessment determines your
                        current readiness level.
                    @else
                        Complete your first cybersecurity assessment to
                        establish your readiness score.
                    @endif
                </p>

            </div>

            <div class="employee-readiness-action">

                @if($latestAttempt)

                    <a
                        href="{{ route('assessment.result', $latestAttempt) }}"
                        class="employee-primary-button"
                    >
                        View Latest Result
                    </a>

                @else

                    <a
                        href="{{ route('assessment.index') }}"
                        class="employee-primary-button"
                    >
                        Start Assessment
                    </a>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         STAT CARDS
    ========================================================== --}}

    <div class="employee-stats-grid">

        {{-- Assigned Assessments --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h6"/>
                    <path d="M8 9h2"/>
                </svg>

            </div>

            <div>
                <span>Assigned Assessments</span>
                <strong>{{ $assignedAssessments }}</strong>
                <small>Assessments assigned to you</small>
            </div>

        </div>


        {{-- Completed --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>

            </div>

            <div>
                <span>Completed</span>
                <strong>{{ $completedAttempts }}</strong>
                <small>Completed assessment attempts</small>
            </div>

        </div>


        {{-- Remaining --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <polyline points="12 7 12 12 15 14"/>
                </svg>

            </div>

            <div>
                <span>Remaining</span>
                <strong>{{ $remainingAssessments }}</strong>
                <small>Assessments still to complete</small>
            </div>

        </div>


        {{-- Latest Score --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3v18h18"/>
                    <path d="m7 15 4-4 3 3 6-7"/>
                </svg>

            </div>

            <div>
                <span>Latest Score</span>

                <strong>
                    {{ $latestScore !== null
                        ? number_format($latestScore, 1) . '%'
                        : '—'
                    }}
                </strong>

                <small>
                    Latest assessment result
                </small>
            </div>

        </div>


        {{-- Learning Plans --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                </svg>

            </div>

            <div>
                <span>Learning Plans</span>
                <strong>{{ $learningPlanCount }}</strong>
                <small>Assigned learning plans</small>
            </div>

        </div>


        {{-- Learning Progress --}}
        <div class="employee-stat-card">

            <div class="employee-stat-icon">

                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3v18h18"/>
                    <path d="M7 16l4-5 3 3 5-7"/>
                </svg>

            </div>

            <div>
                <span>Learning Progress</span>
                <strong>
                    {{ number_format($averageLearningProgress, 1) }}%
                </strong>
                <small>Average learning-plan progress</small>
            </div>

        </div>

    </div>


    {{-- =========================================================
         ASSESSMENTS + LEARNING
    ========================================================== --}}

    <div class="employee-two-column">

        {{-- Assessments --}}
        <div class="employee-card">

            <div class="employee-card-header">

                <div>
                    <h2>My Assessments</h2>

                    <p>
                        Complete your assigned cybersecurity assessments.
                    </p>
                </div>

                <a
                    href="{{ route('assessment.index') }}"
                    class="employee-section-link"
                >
                    View All
                </a>

            </div>


            <div class="employee-assessment-summary">

                <div class="employee-summary-number">
                    <strong>{{ $remainingAssessments }}</strong>

                    <span>
                        {{ $remainingAssessments === 1
                            ? 'assessment remaining'
                            : 'assessments remaining'
                        }}
                    </span>
                </div>


                @if($inProgressAttempts > 0)

                    <div class="employee-info-row">
                        <span>In Progress</span>

                        <strong>
                            {{ $inProgressAttempts }}
                        </strong>
                    </div>

                @endif


                <a
                    href="{{ route('assessment.index') }}"
                    class="employee-wide-button"
                >
                    Go to My Assessments
                </a>

            </div>

        </div>


        {{-- Learning Plans --}}
        <div class="employee-card">

            <div class="employee-card-header">

                <div>
                    <h2>Learning Plans</h2>

                    <p>
                        Complete assigned cybersecurity learning and remediation.
                    </p>
                </div>

                <a
                    href="{{ route('employee.learning-plans') }}"
                    class="employee-section-link"
                >
                    View All
                </a>

            </div>


            <div class="employee-learning-summary">

                <div class="employee-learning-progress">

                    <strong>
                        {{ number_format($averageLearningProgress, 1) }}%
                    </strong>

                    <span>
                        Average progress
                    </span>

                </div>


                <div class="employee-progress-track">

                    <div
                        class="employee-progress-bar"
                        style="width: {{ min(100, max(0, $averageLearningProgress)) }}%;"
                    ></div>

                </div>


                <div class="employee-learning-stats">

                    <div>
                        <span>Total</span>
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

                </div>


                <a
                    href="{{ route('employee.learning-plans') }}"
                    class="employee-wide-button"
                >
                    View Learning Plans
                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         QUICK ACTIONS
    ========================================================== --}}

    <div class="employee-card">

        <div class="employee-card-header">

            <div>
                <h2>Quick Actions</h2>

                <p>
                    Access the tools you use most.
                </p>
            </div>

        </div>


        <div class="employee-action-grid">

            {{-- Assessments --}}
            <a
                href="{{ route('assessment.index') }}"
                class="employee-action-card"
            >

                <div class="employee-action-icon">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <path d="M8 13h8"/>
                        <path d="M8 17h6"/>
                    </svg>

                </div>

                <div>
                    <strong>My Assessments</strong>

                    <span>
                        Complete your assigned cybersecurity assessments.
                    </span>
                </div>

            </a>


            {{-- Score --}}
            <a
                href="{{ route('employee.score') }}"
                class="employee-action-card"
            >

                <div class="employee-action-icon">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 3v18h18"/>
                        <path d="m7 15 4-4 3 3 6-7"/>
                    </svg>

                </div>

                <div>
                    <strong>My Score</strong>

                    <span>
                        View your latest cybersecurity readiness score.
                    </span>
                </div>

            </a>


            {{-- Learning Plans --}}
            <a
                href="{{ route('employee.learning-plans') }}"
                class="employee-action-card"
            >

                <div class="employee-action-icon">

                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                    </svg>

                </div>

                <div>
                    <strong>Learning Plans</strong>

                    <span>
                        Work through your assigned learning activities.
                    </span>
                </div>

            </a>

        </div>

    </div>

</div>


<style>

/* =========================================================
   EMPLOYEE DASHBOARD
========================================================= */

.employee-dashboard-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* ---------------------------------------------------------
   HEADER
--------------------------------------------------------- */

.employee-dashboard-page .dashboard-header {
    display: flex !important;
    align-items: flex-start !important;
    justify-content: space-between !important;
    gap: 24px !important;
    margin-bottom: 28px !important;
}

.employee-dashboard-page .dashboard-title {
    margin: 0 0 8px !important;
    font-size: 30px !important;
    line-height: 1.15 !important;
    font-weight: 700 !important;
    letter-spacing: -.02em !important;
}

.employee-dashboard-page .dashboard-description {
    margin: 0 !important;
    font-size: 15px !important;
    line-height: 1.6 !important;
    opacity: .68 !important;
}

.employee-dashboard-page .dashboard-date {
    white-space: nowrap !important;
    font-size: 13px !important;
    opacity: .6 !important;
    padding-top: 5px !important;
}


/* ---------------------------------------------------------
   READINESS
--------------------------------------------------------- */

.employee-readiness-card {
    margin-bottom: 24px;
    padding: 25px;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.employee-readiness-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.employee-eyebrow {
    display: block;
    margin-bottom: 9px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    opacity: .55;
}

.employee-readiness-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 9px;
}

.employee-readiness-heading strong {
    font-size: 27px;
    line-height: 1;
}

.employee-readiness-content p {
    margin: 0;
    max-width: 760px;
    font-size: 13px;
    line-height: 1.55;
    opacity: .65;
}

.employee-status {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}

.employee-status-high {
    background: #fde8e8;
    color: #b42318;
}

.employee-status-medium {
    background: #fff4d6;
    color: #956900;
}

.employee-status-low {
    background: #e6f6ee;
    color: #18794e;
}

.employee-status-neutral {
    background: #eef1f4;
    color: #667085;
}

.employee-primary-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 15px;
    border-radius: 9px;
    background: #2563eb;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
}


/* ---------------------------------------------------------
   STAT GRID
--------------------------------------------------------- */

.employee-stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.employee-stat-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-height: 112px;
    padding: 19px 20px;
    box-sizing: border-box;
    border-radius: 15px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 5px 20px rgba(15,23,42,.045);
}

.employee-stat-icon,
.employee-action-icon {
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef2f5;
}

.employee-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
}

.employee-stat-icon svg,
.employee-action-icon svg {
    width: 22px;
    height: 22px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-stat-icon svg *,
.employee-action-icon svg * {
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-stat-card span,
.employee-stat-card small {
    display: block;
}

.employee-stat-card span {
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 600;
    opacity: .62;
}

.employee-stat-card strong {
    display: block;
    margin-bottom: 5px;
    font-size: 27px;
    line-height: 1;
}

.employee-stat-card small {
    font-size: 11px;
    opacity: .52;
}


/* ---------------------------------------------------------
   TWO COLUMN
--------------------------------------------------------- */

.employee-two-column {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
    margin-bottom: 24px;
}


/* ---------------------------------------------------------
   CARDS
--------------------------------------------------------- */

.employee-card {
    width: 100%;
    padding: 24px;
    box-sizing: border-box;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
    margin-bottom: 24px;
}

.employee-two-column .employee-card {
    margin-bottom: 0;
}

.employee-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 20px;
}

.employee-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    line-height: 1.3;
}

.employee-card-header p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
    opacity: .62;
}

.employee-section-link {
    font-size: 12px;
    font-weight: 650;
    text-decoration: none;
    white-space: nowrap;
}


/* ---------------------------------------------------------
   ASSESSMENT SUMMARY
--------------------------------------------------------- */

.employee-assessment-summary {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.employee-summary-number {
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.employee-summary-number strong {
    font-size: 38px;
    line-height: 1;
}

.employee-summary-number span {
    font-size: 13px;
    opacity: .6;
}

.employee-info-row {
    display: flex;
    justify-content: space-between;
    padding: 13px 0;
    border-top: 1px solid rgba(15,23,42,.08);
    border-bottom: 1px solid rgba(15,23,42,.08);
    font-size: 13px;
}

.employee-wide-button {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 40px;
    padding: 9px 13px;
    box-sizing: border-box;
    border-radius: 8px;
    background: #eef1f4;
    color: #172033;
    text-decoration: none;
    font-size: 12px;
    font-weight: 700;
}


/* ---------------------------------------------------------
   LEARNING
--------------------------------------------------------- */

.employee-learning-summary {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.employee-learning-progress {
    display: flex;
    align-items: baseline;
    gap: 10px;
}

.employee-learning-progress strong {
    font-size: 36px;
    line-height: 1;
}

.employee-learning-progress span {
    font-size: 13px;
    opacity: .6;
}

.employee-progress-track {
    width: 100%;
    height: 10px;
    overflow: hidden;
    border-radius: 999px;
    background: #edf0f3;
}

.employee-progress-bar {
    height: 100%;
    border-radius: inherit;
    background: #52677d;
}

.employee-learning-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.employee-learning-stats > div {
    padding: 13px;
    border-radius: 11px;
    background: #f4f6f8;
}

.employee-learning-stats span,
.employee-learning-stats strong {
    display: block;
}

.employee-learning-stats span {
    margin-bottom: 5px;
    font-size: 11px;
    opacity: .6;
}

.employee-learning-stats strong {
    font-size: 18px;
}


/* ---------------------------------------------------------
   QUICK ACTIONS
--------------------------------------------------------- */

.employee-action-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
}

.employee-action-card {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    min-width: 0;
    padding: 18px;
    box-sizing: border-box;
    border-radius: 14px;
    background: #fafbfc;
    border: 1px solid rgba(15,23,42,.08);
    text-decoration: none;
    transition:
        transform .15s ease,
        box-shadow .15s ease,
        border-color .15s ease;
}

.employee-action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(15,23,42,.07);
    border-color: rgba(15,23,42,.14);
}

.employee-action-icon {
    width: 42px;
    height: 42px;
    min-width: 42px;
    border-radius: 11px;
}

.employee-action-icon svg {
    width: 21px;
    height: 21px;
}

.employee-action-card strong,
.employee-action-card span {
    display: block;
}

.employee-action-card strong {
    margin-bottom: 4px;
    font-size: 13px;
    font-weight: 700;
}

.employee-action-card span {
    font-size: 11px;
    line-height: 1.45;
    opacity: .6;
}


/* ---------------------------------------------------------
   RESPONSIVE
--------------------------------------------------------- */

@media (max-width: 1100px) {

    .employee-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .employee-action-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 850px) {

    .employee-readiness-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .employee-two-column {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 650px) {

    .employee-dashboard-page {
        padding: 20px 16px 40px;
    }

    .employee-dashboard-page .dashboard-header {
        flex-direction: column;
    }

    .employee-date {
        padding-top: 0;
    }

    .employee-stats-grid,
    .employee-action-grid {
        grid-template-columns: 1fr;
    }

    .employee-learning-stats {
        grid-template-columns: 1fr;
    }

    .employee-card,
    .employee-readiness-card {
        padding: 18px;
    }

}
</style>

@endsection
