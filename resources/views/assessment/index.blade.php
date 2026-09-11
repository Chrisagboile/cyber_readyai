@extends('layouts.app')

@section('title', 'My Assessments')

@section('content')

@php
    $userId = auth()->id();

    $pageCompleted = 0;
    $pageInProgress = 0;
    $pageNotStarted = 0;

    foreach ($assessments as $assessment) {
        $attempt = $assessment->attempts
            ->where('user_id', $userId)
            ->sortByDesc('created_at')
            ->first();

        if (! $attempt) {
            $pageNotStarted++;
        } elseif ($attempt->status === 'in_progress') {
            $pageInProgress++;
        } elseif (in_array($attempt->status, ['completed', 'expired'], true)) {
            $pageCompleted++;
        }
    }
@endphp

<div class="employee-assessments-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="employee-assessments-header">

        <div>
            <h1 class="employee-assessments-title">
                My Assessments
            </h1>

            <p class="employee-assessments-description">
                Complete your assigned cybersecurity assessments
                and review your results.
            </p>
        </div>

        <div class="employee-assessments-date">
            {{ now()->format('d M Y') }}
        </div>

    </div>


    {{-- =========================================================
         SUMMARY CARDS
    ========================================================== --}}

    <div class="employee-assessment-summary-grid">

        <div class="employee-assessment-summary-card">

            <div class="employee-assessment-summary-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h6"/>
                </svg>
            </div>

            <div>
                <span>Assigned Assessments</span>
                <strong>{{ $assessments->total() }}</strong>
                <small>Assessments assigned to you</small>
            </div>

        </div>


        <div class="employee-assessment-summary-card">

            <div class="employee-assessment-summary-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <polyline points="12 7 12 12 15 14"/>
                </svg>
            </div>

            <div>
                <span>In Progress</span>
                <strong>{{ $pageInProgress }}</strong>
                <small>Currently underway</small>
            </div>

        </div>


        <div class="employee-assessment-summary-card">

            <div class="employee-assessment-summary-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>

            <div>
                <span>Completed</span>
                <strong>{{ $pageCompleted }}</strong>
                <small>Completed or expired</small>
            </div>

        </div>


        <div class="employee-assessment-summary-card">

            <div class="employee-assessment-summary-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v4"/>
                    <path d="M12 16h.01"/>
                </svg>
            </div>

            <div>
                <span>Not Started</span>
                <strong>{{ $pageNotStarted }}</strong>
                <small>Waiting for completion</small>
            </div>

        </div>

    </div>


    {{-- =========================================================
         ASSESSMENTS
    ========================================================== --}}

    <div class="employee-assessments-card">

        <div class="employee-assessments-card-header">

            <div>
                <h2>Assigned Assessments</h2>

                <p>
                    Select an assessment to start, continue,
                    or review your result.
                </p>
            </div>

        </div>


        @if($assessments->isEmpty())

            <div class="employee-assessment-empty">

                <div class="employee-assessment-empty-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <path d="M9 13h6"/>
                        <path d="M9 17h4"/>
                    </svg>
                </div>

                <h3>No assessments assigned</h3>

                <p>
                    You currently have no cybersecurity assessments
                    assigned to your account.
                </p>

            </div>

        @else

            <div class="employee-assessment-list">

                @foreach($assessments as $assessment)

                    @php
                        $attempt = $assessment->attempts
                            ->where('user_id', $userId)
                            ->sortByDesc('created_at')
                            ->first();

                        $attemptStatus = $attempt?->status;

                        if ($attemptStatus === 'in_progress') {
                            $statusLabel = 'In Progress';
                            $statusClass = 'in-progress';
                        } elseif ($attemptStatus === 'completed') {
                            $statusLabel = 'Completed';
                            $statusClass = 'completed';
                        } elseif ($attemptStatus === 'expired') {
                            $statusLabel = 'Expired';
                            $statusClass = 'expired';
                        } else {
                            $statusLabel = 'Not Started';
                            $statusClass = 'not-started';
                        }

                        $score = $attempt?->score_percentage;
                        $risk = strtolower((string) ($attempt?->risk_level ?? ''));

                        $answerProgress = 0;

                        if ($attempt && $attempt->total_questions > 0) {
                            $answerProgress = round(
                                (
                                    $attempt->answered_questions
                                    / $attempt->total_questions
                                ) * 100
                            );
                        }

                        $answerProgress = min(
                            100,
                            max(0, $answerProgress)
                        );
                    @endphp


                    <div class="employee-assessment-row">

                        {{-- =================================================
                             ASSESSMENT DETAILS
                        ================================================== --}}

                        <div class="employee-assessment-details">

                            <div class="employee-assessment-document-icon">

                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <path d="M8 13h8"/>
                                    <path d="M8 17h6"/>
                                    <path d="M8 9h2"/>
                                </svg>

                            </div>

                            <div class="employee-assessment-text">

                                <h3>
                                    {{ $assessment->name }}
                                </h3>

                                <div class="employee-assessment-meta">

                                    <span>
                                        {{ $assessment->total_questions }}
                                        {{ $assessment->total_questions == 1 ? 'question' : 'questions' }}
                                    </span>

                                    <span class="employee-assessment-dot">•</span>

                                    <span>
                                        {{ $assessment->duration_minutes }}
                                        minutes
                                    </span>

                                    <span class="employee-assessment-dot">•</span>

                                    <span>
                                        Assigned
                                        {{ $assessment->created_at->format('d M Y') }}
                                    </span>

                                </div>


                                {{-- In Progress --}}
                                @if($attemptStatus === 'in_progress')

                                    <div class="employee-assessment-progress">

                                        <div class="employee-assessment-progress-header">

                                            <span>Progress</span>

                                            <strong>
                                                {{ $attempt->answered_questions }}
                                                /
                                                {{ $attempt->total_questions }}
                                            </strong>

                                        </div>

                                        <div class="employee-assessment-progress-track">

                                            <div
                                                class="employee-assessment-progress-bar"
                                                style="width: {{ $answerProgress }}%;"
                                            ></div>

                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- =================================================
                             STATUS / SCORE
                        ================================================== --}}

                        <div class="employee-assessment-result">

                            <span class="employee-assessment-status {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>

                            @if($score !== null)

                                <strong class="employee-assessment-score">
                                    {{ number_format((float) $score, 1) }}%
                                </strong>

                                @if($risk === 'high')

                                    <span class="employee-assessment-risk high">
                                        High Risk
                                    </span>

                                @elseif($risk === 'medium')

                                    <span class="employee-assessment-risk medium">
                                        Medium Risk
                                    </span>

                                @elseif($risk === 'low')

                                    <span class="employee-assessment-risk low">
                                        Low Risk
                                    </span>

                                @endif

                            @endif

                        </div>


                        {{-- =================================================
                             ACTION
                        ================================================== --}}

                        <div class="employee-assessment-action">

                            @if($attemptStatus === 'in_progress')

                                <a
                                    href="{{ route('assessment.start', $assessment) }}"
                                    class="employee-assessment-primary-button"
                                >
                                    Continue
                                </a>

                            @elseif(
                                $attemptStatus === 'completed'
                                || $attemptStatus === 'expired'
                            )

                                <a
                                    href="{{ route('assessment.result', $attempt) }}"
                                    class="employee-assessment-secondary-button"
                                >
                                    View Result
                                </a>

                            @else

                                <a
                                    href="{{ route('assessment.start', $assessment) }}"
                                    class="employee-assessment-primary-button"
                                >
                                    Start Assessment
                                </a>

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>


            @if($assessments->hasPages())

                <div class="employee-assessment-pagination">
                    {{ $assessments->links() }}
                </div>

            @endif

        @endif

    </div>

</div>


<style>

/* =========================================================
   EMPLOYEE ASSESSMENTS — COMPLETE SELF-CONTAINED UI
========================================================= */

.employee-assessments-page {
    width: 100% !important;
    max-width: 1500px !important;
    margin: 0 auto !important;
    padding: 28px 32px 50px !important;
    box-sizing: border-box !important;
}


/* =========================================================
   HEADER
========================================================= */

.employee-assessments-header {
    display: flex !important;
    align-items: flex-start !important;
    justify-content: space-between !important;
    gap: 24px !important;
    margin-bottom: 28px !important;
}

.employee-assessments-title {
    margin: 0 0 8px !important;
    font-size: 30px !important;
    line-height: 1.15 !important;
    font-weight: 700 !important;
    letter-spacing: -.02em !important;
    color: #172033 !important;
}

.employee-assessments-description {
    margin: 0 !important;
    max-width: 760px !important;
    font-size: 15px !important;
    line-height: 1.6 !important;
    color: #667085 !important;
}

.employee-assessments-date {
    padding-top: 5px !important;
    white-space: nowrap !important;
    font-size: 13px !important;
    color: #667085 !important;
}


/* =========================================================
   SUMMARY
========================================================= */

.employee-assessment-summary-grid {
    display: grid !important;
    grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    gap: 16px !important;
    margin-bottom: 24px !important;
}

.employee-assessment-summary-card {
    display: flex !important;
    align-items: flex-start !important;
    gap: 14px !important;

    min-height: 108px !important;

    padding: 19px 20px !important;
    box-sizing: border-box !important;

    background: #ffffff !important;
    border: 1px solid rgba(15, 23, 42, .08) !important;
    border-radius: 15px !important;

    box-shadow: 0 5px 20px rgba(15, 23, 42, .045) !important;
}

.employee-assessment-summary-icon {
    width: 44px !important;
    height: 44px !important;
    min-width: 44px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    border-radius: 12px !important;
    background: #eef2f5 !important;
    color: #334155 !important;
}

.employee-assessment-summary-icon svg {
    width: 22px !important;
    height: 22px !important;

    display: block !important;

    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.8 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-summary-icon svg * {
    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.8 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-summary-card > div:last-child {
    min-width: 0 !important;
}

.employee-assessment-summary-card span {
    display: block !important;
    margin-bottom: 5px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #667085 !important;
}

.employee-assessment-summary-card strong {
    display: block !important;
    margin-bottom: 5px !important;
    font-size: 28px !important;
    line-height: 1 !important;
    font-weight: 750 !important;
    color: #172033 !important;
}

.employee-assessment-summary-card small {
    display: block !important;
    font-size: 11px !important;
    color: #8a94a6 !important;
}


/* =========================================================
   MAIN CARD
========================================================= */

.employee-assessments-card {
    width: 100% !important;
    overflow: hidden !important;

    background: #ffffff !important;

    border: 1px solid rgba(15, 23, 42, .08) !important;
    border-radius: 16px !important;

    box-shadow: 0 6px 24px rgba(15, 23, 42, .05) !important;
}

.employee-assessments-card-header {
    padding: 22px 24px !important;

    border-bottom: 1px solid rgba(15, 23, 42, .08) !important;
}

.employee-assessments-card-header h2 {
    margin: 0 0 5px !important;
    font-size: 19px !important;
    line-height: 1.3 !important;
    font-weight: 700 !important;
    color: #172033 !important;
}

.employee-assessments-card-header p {
    margin: 0 !important;
    font-size: 13px !important;
    line-height: 1.5 !important;
    color: #667085 !important;
}


/* =========================================================
   ASSESSMENT LIST
========================================================= */

.employee-assessment-list {
    display: flex !important;
    flex-direction: column !important;
    width: 100% !important;
}

.employee-assessment-row {
    display: grid !important;

    grid-template-columns:
        minmax(330px, 1.7fr)
        minmax(130px, .65fr)
        auto !important;

    align-items: center !important;

    gap: 24px !important;

    padding: 21px 24px !important;

    border-bottom: 1px solid rgba(15, 23, 42, .07) !important;

    transition: background .15s ease !important;
}

.employee-assessment-row:hover {
    background: #fafbfc !important;
}

.employee-assessment-row:last-child {
    border-bottom: 0 !important;
}


/* =========================================================
   DETAILS
========================================================= */

.employee-assessment-details {
    display: flex !important;
    align-items: flex-start !important;
    gap: 13px !important;

    min-width: 0 !important;
}

.employee-assessment-document-icon {
    width: 42px !important;
    height: 42px !important;
    min-width: 42px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    border-radius: 11px !important;
    background: #eef2f5 !important;
    color: #334155 !important;
}

.employee-assessment-document-icon svg {
    width: 21px !important;
    height: 21px !important;

    display: block !important;

    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.8 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-document-icon svg * {
    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.8 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-text {
    min-width: 0 !important;
}

.employee-assessment-text h3 {
    overflow: hidden !important;

    margin: 1px 0 7px !important;

    font-size: 14px !important;
    line-height: 1.35 !important;
    font-weight: 700 !important;

    color: #172033 !important;

    white-space: nowrap !important;
    text-overflow: ellipsis !important;
}

.employee-assessment-meta {
    display: flex !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 6px !important;

    font-size: 11px !important;
    line-height: 1.4 !important;

    color: #8a94a6 !important;
}

.employee-assessment-dot {
    opacity: .6 !important;
}


/* =========================================================
   PROGRESS
========================================================= */

.employee-assessment-progress {
    margin-top: 15px !important;
    max-width: 420px !important;
}

.employee-assessment-progress-header {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;

    margin-bottom: 7px !important;

    font-size: 11px !important;
}

.employee-assessment-progress-header span {
    color: #667085 !important;
}

.employee-assessment-progress-header strong {
    color: #172033 !important;
}

.employee-assessment-progress-track {
    width: 100% !important;
    height: 7px !important;

    overflow: hidden !important;

    border-radius: 999px !important;

    background: #edf0f3 !important;
}

.employee-assessment-progress-bar {
    height: 100% !important;

    border-radius: inherit !important;

    background: #52677d !important;
}


/* =========================================================
   STATUS / RESULT
========================================================= */

.employee-assessment-result {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 6px !important;
}

.employee-assessment-status {
    display: inline-flex !important;
    align-items: center !important;

    padding: 5px 9px !important;

    border-radius: 999px !important;

    font-size: 10px !important;
    line-height: 1.2 !important;
    font-weight: 700 !important;

    white-space: nowrap !important;
}

.employee-assessment-status.not-started {
    background: #eef1f4 !important;
    color: #667085 !important;
}

.employee-assessment-status.in-progress {
    background: #edf4ff !important;
    color: #2257a5 !important;
}

.employee-assessment-status.completed {
    background: #e6f6ee !important;
    color: #18794e !important;
}

.employee-assessment-status.expired {
    background: #fde8e8 !important;
    color: #b42318 !important;
}

.employee-assessment-score {
    font-size: 17px !important;
    line-height: 1 !important;
    font-weight: 750 !important;
    color: #172033 !important;
}

.employee-assessment-risk {
    display: inline-flex !important;

    padding: 3px 7px !important;

    border-radius: 999px !important;

    font-size: 9px !important;
    line-height: 1.2 !important;
    font-weight: 700 !important;
}

.employee-assessment-risk.high {
    background: #fde8e8 !important;
    color: #b42318 !important;
}

.employee-assessment-risk.medium {
    background: #fff4d6 !important;
    color: #956900 !important;
}

.employee-assessment-risk.low {
    background: #e6f6ee !important;
    color: #18794e !important;
}


/* =========================================================
   ACTIONS
========================================================= */

.employee-assessment-action {
    text-align: right !important;
    white-space: nowrap !important;
}

.employee-assessment-primary-button,
.employee-assessment-secondary-button {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;

    min-height: 37px !important;

    padding: 9px 13px !important;

    box-sizing: border-box !important;

    border-radius: 8px !important;

    font-size: 11px !important;
    line-height: 1.2 !important;
    font-weight: 700 !important;

    text-decoration: none !important;

    transition:
        transform .15s ease,
        box-shadow .15s ease !important;
}

.employee-assessment-primary-button {
    background: #2563eb !important;
    color: #ffffff !important;
}

.employee-assessment-secondary-button {
    background: #eef1f4 !important;
    color: #172033 !important;
}

.employee-assessment-primary-button:hover,
.employee-assessment-secondary-button:hover {
    transform: translateY(-1px) !important;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.employee-assessment-empty {
    padding: 64px 24px !important;
    text-align: center !important;
}

.employee-assessment-empty-icon {
    width: 54px !important;
    height: 54px !important;

    margin: 0 auto 16px !important;

    display: flex !important;
    align-items: center !important;
    justify-content: center !important;

    border-radius: 14px !important;
    background: #eef2f5 !important;
    color: #334155 !important;
}

.employee-assessment-empty-icon svg {
    width: 26px !important;
    height: 26px !important;

    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.7 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-empty h3 {
    margin: 0 0 7px !important;
    font-size: 17px !important;
    color: #172033 !important;
}

.employee-assessment-empty p {
    margin: 0 !important;
    font-size: 13px !important;
    color: #667085 !important;
}


/* =========================================================
   PAGINATION
========================================================= */

.employee-assessment-pagination {
    padding: 18px 24px !important;
    border-top: 1px solid rgba(15, 23, 42, .07) !important;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .employee-assessment-summary-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .employee-assessment-row {
        grid-template-columns: minmax(280px, 1.4fr) minmax(130px, .7fr) !important;
    }

    .employee-assessment-action {
        grid-column: 2 !important;
        text-align: right !important;
    }
}


@media (max-width: 800px) {

    .employee-assessment-row {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }

    .employee-assessment-action {
        grid-column: auto !important;
        text-align: left !important;
    }

    .employee-assessment-result {
        flex-direction: row !important;
        align-items: center !important;
        flex-wrap: wrap !important;
    }

}


@media (max-width: 650px) {

    .employee-assessments-page {
        padding: 20px 16px 40px !important;
    }

    .employee-assessments-header {
        flex-direction: column !important;
    }

    .employee-assessments-date {
        padding-top: 0 !important;
    }

    .employee-assessment-summary-grid {
        grid-template-columns: 1fr !important;
    }

    .employee-assessments-card-header,
    .employee-assessment-row {
        padding-left: 18px !important;
        padding-right: 18px !important;
    }

    .employee-assessment-meta {
        display: block !important;
    }

    .employee-assessment-meta span {
        display: inline-block !important;
        margin-right: 4px !important;
    }

}

</style>

@endsection
