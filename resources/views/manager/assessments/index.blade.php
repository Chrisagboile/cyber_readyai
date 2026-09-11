@extends('layouts.app')

@section('title', 'My Assessments')

@section('content')

@php
    $currentUserId = auth()->id();

    $completedCount = 0;
    $inProgressCount = 0;

    foreach ($assessments as $assessment) {
        $attempt = $assessment->attempts
            ->where('user_id', $currentUserId)
            ->sortByDesc('created_at')
            ->first();

        if ($attempt) {
            if (in_array($attempt->status, ['completed', 'expired'], true)) {
                $completedCount++;
            } elseif ($attempt->status === 'in_progress') {
                $inProgressCount++;
            }
        }
    }
@endphp

<div class="employee-assessments-page">

    {{-- =========================================================
         HEADER
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

    <div class="employee-assessment-stats">

        <div class="employee-assessment-stat">

            <div class="employee-assessment-stat-icon">
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
                <strong>{{ $assessments->total() }}</strong>
                <small>Assessments assigned to your account</small>
            </div>

        </div>


        <div class="employee-assessment-stat">

            <div class="employee-assessment-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <polyline points="12 7 12 12 15 14"/>
                </svg>
            </div>

            <div>
                <span>In Progress</span>
                <strong>{{ $inProgressCount }}</strong>
                <small>Assessments currently underway</small>
            </div>

        </div>


        <div class="employee-assessment-stat">

            <div class="employee-assessment-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>

            <div>
                <span>Completed</span>
                <strong>{{ $completedCount }}</strong>
                <small>Completed or expired assessments</small>
            </div>

        </div>

    </div>


    {{-- =========================================================
         MAIN ASSESSMENTS CARD
    ========================================================== --}}

    <div class="employee-assessments-card">

        <div class="employee-assessments-card-header">

            <div>
                <h2>Assigned Assessments</h2>

                <p>
                    Select an assessment below to begin, continue,
                    or review your result.
                </p>
            </div>

        </div>


        @if($assessments->isEmpty())

            <div class="employee-assessment-empty">

                <div class="employee-empty-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <path d="M9 13h6"/>
                        <path d="M9 17h4"/>
                    </svg>
                </div>

                <h3>No assessments assigned yet</h3>

                <p>
                    Your assigned cybersecurity assessments will appear here.
                </p>

            </div>

        @else

            <div class="employee-assessment-list">

                @foreach($assessments as $assessment)

                    @php
                        $attempt = $assessment->attempts
                            ->where('user_id', $currentUserId)
                            ->sortByDesc('created_at')
                            ->first();

                        $status = $attempt?->status;

                        if ($status === 'completed') {
                            $displayStatus = 'Completed';
                            $statusClass = 'completed';
                        } elseif ($status === 'expired') {
                            $displayStatus = 'Expired';
                            $statusClass = 'expired';
                        } elseif ($status === 'in_progress') {
                            $displayStatus = 'In Progress';
                            $statusClass = 'progress';
                        } else {
                            $displayStatus = 'Not Started';
                            $statusClass = 'not-started';
                        }

                        $score = $attempt?->score_percentage;
                        $risk = strtolower((string) ($attempt?->risk_level ?? ''));
                    @endphp

                    <div class="employee-assessment-item">

                        <div class="employee-assessment-main">

                            <div class="employee-assessment-icon">
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <path d="M8 13h8"/>
                                    <path d="M8 17h6"/>
                                    <path d="M8 9h2"/>
                                </svg>
                            </div>

                            <div class="employee-assessment-info">

                                <h3>
                                    {{ $assessment->name }}
                                </h3>

                                <div class="employee-assessment-meta">

                                    <span>
                                        {{ $assessment->total_questions }}
                                        {{ $assessment->total_questions == 1 ? 'question' : 'questions' }}
                                    </span>

                                    <span class="meta-separator">•</span>

                                    <span>
                                        {{ $assessment->duration_minutes }} minutes
                                    </span>

                                    <span class="meta-separator">•</span>

                                    <span>
                                        Assigned
                                        {{ $assessment->created_at->format('d M Y') }}
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="employee-assessment-status-area">

                            <span class="employee-assessment-status {{ $statusClass }}">
                                {{ $displayStatus }}
                            </span>

                            @if($score !== null)

                                <strong class="employee-assessment-score">
                                    {{ number_format((float) $score, 1) }}%
                                </strong>

                                @if($risk === 'high')
                                    <span class="employee-risk high">
                                        High Risk
                                    </span>
                                @elseif($risk === 'medium')
                                    <span class="employee-risk medium">
                                        Medium Risk
                                    </span>
                                @elseif($risk === 'low')
                                    <span class="employee-risk low">
                                        Low Risk
                                    </span>
                                @endif

                            @endif

                        </div>


                        <div class="employee-assessment-action">

                            @if($status === 'in_progress')

                                <a
                                    href="{{ route('assessment.start', $assessment) }}"
                                    class="employee-assessment-button primary"
                                >
                                    Continue
                                </a>

                            @elseif(in_array($status, ['completed', 'expired'], true))

                                <a
                                    href="{{ route('assessment.result', $attempt) }}"
                                    class="employee-assessment-button secondary"
                                >
                                    View Result
                                </a>

                            @else

                                <a
                                    href="{{ route('assessment.start', $assessment) }}"
                                    class="employee-assessment-button primary"
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
   PAGE
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
    padding-top: 6px !important;
    white-space: nowrap !important;
    font-size: 13px !important;
    color: #667085 !important;
}


/* =========================================================
   SUMMARY CARDS
========================================================= */

.employee-assessment-stats {
    display: grid !important;
    grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    gap: 16px !important;
    margin-bottom: 24px !important;
}

.employee-assessment-stat {
    display: flex !important;
    align-items: flex-start !important;
    gap: 14px !important;
    min-height: 112px !important;
    padding: 20px !important;
    box-sizing: border-box !important;

    background: #ffffff !important;
    border: 1px solid rgba(15, 23, 42, .08) !important;
    border-radius: 15px !important;
    box-shadow: 0 5px 20px rgba(15, 23, 42, .045) !important;
}

.employee-assessment-stat-icon {
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

.employee-assessment-stat-icon svg {
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

.employee-assessment-stat-icon svg * {
    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.8 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-stat > div:last-child {
    min-width: 0 !important;
}

.employee-assessment-stat > div:last-child > span {
    display: block !important;
    margin-bottom: 5px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    line-height: 1.3 !important;
    color: #667085 !important;
}

.employee-assessment-stat > div:last-child > strong {
    display: block !important;
    margin: 0 0 5px !important;
    font-size: 28px !important;
    line-height: 1 !important;
    font-weight: 750 !important;
    color: #172033 !important;
}

.employee-assessment-stat > div:last-child > small {
    display: block !important;
    font-size: 11px !important;
    line-height: 1.4 !important;
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

.employee-assessment-item {
    display: grid !important;
    grid-template-columns: minmax(320px, 1.7fr) minmax(160px, .75fr) auto !important;
    align-items: center !important;
    gap: 24px !important;

    padding: 20px 24px !important;

    border-bottom: 1px solid rgba(15, 23, 42, .07) !important;
    box-sizing: border-box !important;

    transition: background .15s ease !important;
}

.employee-assessment-item:hover {
    background: #fafbfc !important;
}

.employee-assessment-item:last-child {
    border-bottom: 0 !important;
}


/* =========================================================
   ASSESSMENT INFORMATION
========================================================= */

.employee-assessment-main {
    display: flex !important;
    align-items: flex-start !important;
    gap: 13px !important;
    min-width: 0 !important;
}

.employee-assessment-icon {
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

.employee-assessment-icon svg {
    width: 21px !important;
    height: 21px !important;
    min-width: 21px !important;
    max-width: 21px !important;
    min-height: 21px !important;
    max-height: 21px !important;

    display: block !important;

    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.8 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-icon svg * {
    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.8 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-assessment-info {
    min-width: 0 !important;
}

.employee-assessment-info h3 {
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

.meta-separator {
    opacity: .7 !important;
}


/* =========================================================
   STATUS
========================================================= */

.employee-assessment-status-area {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
    gap: 6px !important;
    min-width: 0 !important;
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

.employee-assessment-status.progress {
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

.employee-risk {
    display: inline-flex !important;
    align-items: center !important;

    padding: 3px 7px !important;

    border-radius: 999px !important;

    font-size: 9px !important;
    line-height: 1.2 !important;
    font-weight: 700 !important;
}

.employee-risk.high {
    background: #fde8e8 !important;
    color: #b42318 !important;
}

.employee-risk.medium {
    background: #fff4d6 !important;
    color: #956900 !important;
}

.employee-risk.low {
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

.employee-assessment-button {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;

    min-height: 36px !important;

    padding: 8px 13px !important;

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

.employee-assessment-button:hover {
    transform: translateY(-1px) !important;
}

.employee-assessment-button.primary {
    background: #2563eb !important;
    color: #ffffff !important;
}

.employee-assessment-button.secondary {
    background: #eef1f4 !important;
    color: #172033 !important;
}


/* =========================================================
   EMPTY STATE
========================================================= */

.employee-assessment-empty {
    padding: 64px 24px !important;
    text-align: center !important;
}

.employee-empty-icon {
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

.employee-empty-icon svg {
    width: 26px !important;
    height: 26px !important;

    fill: none !important;
    stroke: currentColor !important;
    stroke-width: 1.7 !important;
    stroke-linecap: round !important;
    stroke-linejoin: round !important;
}

.employee-empty-icon svg * {
    fill: none !important;
    stroke: currentColor !important;
}

.employee-assessment-empty h3 {
    margin: 0 0 7px !important;
    font-size: 17px !important;
    line-height: 1.3 !important;
    color: #172033 !important;
}

.employee-assessment-empty p {
    margin: 0 !important;
    font-size: 13px !important;
    line-height: 1.5 !important;
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

@media (max-width: 1050px) {

    .employee-assessment-stats {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }

    .employee-assessment-item {
        grid-template-columns: minmax(260px, 1.4fr) minmax(130px, .8fr) !important;
    }

    .employee-assessment-action {
        grid-column: 2 !important;
        text-align: right !important;
    }
}


@media (max-width: 850px) {

    .employee-assessment-stats {
        grid-template-columns: 1fr !important;
    }

    .employee-assessment-item {
        grid-template-columns: 1fr !important;
        gap: 14px !important;
    }

    .employee-assessment-status-area {
        flex-direction: row !important;
        align-items: center !important;
        flex-wrap: wrap !important;
    }

    .employee-assessment-action {
        grid-column: auto !important;
        text-align: left !important;
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

    .employee-assessments-card-header,
    .employee-assessment-item {
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
