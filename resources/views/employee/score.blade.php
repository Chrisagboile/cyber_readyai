@extends('layouts.app')

@section('title', 'My Score')

@section('content')

@php
    $latestAttempt = $attempts->first();

    $completedCount = $attempts->count();

    $averageScore = $completedCount > 0
        ? round((float) $attempts->avg('score_percentage'), 1)
        : 0;

    $highRiskCount = $attempts->filter(
        fn ($attempt) => strtolower((string) $attempt->risk_level) === 'high'
    )->count();

    $mediumRiskCount = $attempts->filter(
        fn ($attempt) => strtolower((string) $attempt->risk_level) === 'medium'
    )->count();

    $lowRiskCount = $attempts->filter(
        fn ($attempt) => strtolower((string) $attempt->risk_level) === 'low'
    )->count();

    $latestScore = $latestAttempt
        ? (float) $latestAttempt->score_percentage
        : null;

    $latestRisk = strtolower(
        (string) ($latestAttempt?->risk_level ?? '')
    );

    $latestRiskLabel = match ($latestRisk) {
        'high' => 'High Risk',
        'medium' => 'Medium Risk',
        'low' => 'Low Risk',
        default => 'Not Assessed',
    };

    $latestRiskClass = match ($latestRisk) {
        'high' => 'high',
        'medium' => 'medium',
        'low' => 'low',
        default => 'neutral',
    };
@endphp


<div class="employee-score-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="employee-score-header">

        <div>
            <h1 class="employee-score-title">
                My Score
            </h1>

            <p class="employee-score-description">
                Review your cybersecurity assessment scores
                and readiness history.
            </p>
        </div>

        <div class="employee-score-date">
            {{ now()->format('d M Y') }}
        </div>

    </div>


    {{-- =========================================================
         CURRENT READINESS
    ========================================================== --}}

    @if($latestAttempt)

        <div class="employee-score-readiness">

            <div class="employee-score-readiness-main">

                <div>

                    <span class="employee-score-eyebrow">
                        Latest Readiness
                    </span>

                    <div class="employee-score-readiness-heading">

                        <strong>
                            {{ number_format($latestScore, 1) }}%
                        </strong>

                        <span class="employee-score-risk-badge {{ $latestRiskClass }}">
                            {{ $latestRiskLabel }}
                        </span>

                    </div>

                    <p>
                        Based on your latest assessment,
                        <strong>
                            {{ $latestAttempt->assessment?->name ?? 'Cybersecurity Assessment' }}
                        </strong>.
                    </p>

                </div>

                <div>
                    <a
                        href="{{ route('assessment.result', $latestAttempt) }}"
                        class="employee-score-primary-button"
                    >
                        View Latest Result
                    </a>
                </div>

            </div>

            <div class="employee-score-readiness-progress">

                <div class="employee-score-progress-header">
                    <span>Latest assessment score</span>

                    <strong>
                        {{ number_format($latestScore, 1) }}%
                    </strong>
                </div>

                <div class="employee-score-progress-track">
                    <div
                        class="employee-score-progress-bar {{ $latestRiskClass }}"
                        style="width: {{ min(100, max(0, $latestScore)) }}%;"
                    ></div>
                </div>

            </div>

        </div>

    @endif


    {{-- =========================================================
         SCORE SUMMARY
    ========================================================== --}}

    <div class="employee-score-stat-grid">

        <div class="employee-score-stat-card">

            <div class="employee-score-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <path d="M8 13h8"/>
                    <path d="M8 17h6"/>
                </svg>
            </div>

            <div>
                <span>Completed Assessments</span>
                <strong>{{ $completedCount }}</strong>
                <small>Completed assessment attempts</small>
            </div>

        </div>


        <div class="employee-score-stat-card">

            <div class="employee-score-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M3 3v18h18"/>
                    <path d="m7 15 4-4 3 3 6-7"/>
                </svg>
            </div>

            <div>
                <span>Average Score</span>

                <strong>
                    {{ $completedCount > 0
                        ? number_format($averageScore, 1) . '%'
                        : '—'
                    }}
                </strong>

                <small>Across your completed assessments</small>
            </div>

        </div>


        <div class="employee-score-stat-card">

            <div class="employee-score-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5"/>
                    <path d="M12 16h.01"/>
                </svg>
            </div>

            <div>
                <span>High Risk Results</span>
                <strong>{{ $highRiskCount }}</strong>
                <small>Assessments requiring attention</small>
            </div>

        </div>


        <div class="employee-score-stat-card">

            <div class="employee-score-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M8 12h8"/>
                </svg>
            </div>

            <div>
                <span>Medium Risk Results</span>
                <strong>{{ $mediumRiskCount }}</strong>
                <small>Assessments needing improvement</small>
            </div>

        </div>

    </div>


    {{-- =========================================================
         ASSESSMENT RESULTS
    ========================================================== --}}

    <div class="employee-score-card">

        <div class="employee-score-card-header">

            <div>
                <h2>Assessment Results</h2>

                <p>
                    Your completed cybersecurity assessments.
                </p>
            </div>

            <a
                href="{{ route('assessment.index') }}"
                class="employee-score-section-link"
            >
                My Assessments
            </a>

        </div>


        @if($attempts->isEmpty())

            <div class="employee-score-empty">

                <div class="employee-score-empty-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <path d="M9 13h6"/>
                        <path d="M9 17h4"/>
                    </svg>
                </div>

                <h3>No completed assessments</h3>

                <p>
                    Complete your first cybersecurity assessment
                    to see your score here.
                </p>

                <a
                    href="{{ route('assessment.index') }}"
                    class="employee-score-primary-button"
                >
                    View Assessments
                </a>

            </div>

        @else

            <div class="employee-score-list">

                @foreach($attempts as $attempt)

                    @php
                        $score = (float) $attempt->score_percentage;
                        $risk = strtolower((string) $attempt->risk_level);

                        $riskLabel = match ($risk) {
                            'high' => 'High Risk',
                            'medium' => 'Medium Risk',
                            'low' => 'Low Risk',
                            default => 'Not Assessed',
                        };

                        $riskClass = match ($risk) {
                            'high' => 'high',
                            'medium' => 'medium',
                            'low' => 'low',
                            default => 'neutral',
                        };
                    @endphp

                    <div class="employee-score-result-row">

                        <div class="employee-score-result-main">

                            <div class="employee-score-result-icon">

                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <path d="M8 13h8"/>
                                    <path d="M8 17h6"/>
                                </svg>

                            </div>

                            <div class="employee-score-result-info">

                                <h3>
                                    {{ $attempt->assessment?->name ?? 'Assessment' }}
                                </h3>

                                <div class="employee-score-result-meta">

                                    <span>
                                        Completed
                                        {{ $attempt->completed_at
                                            ? $attempt->completed_at->format('d M Y')
                                            : 'N/A'
                                        }}
                                    </span>

                                    <span>•</span>

                                    <span>
                                        {{ $attempt->correct_answers }}
                                        / {{ $attempt->total_questions }}
                                        correct
                                    </span>

                                </div>

                            </div>

                        </div>


                        <div class="employee-score-result-score">

                            <strong>
                                {{ number_format($score, 1) }}%
                            </strong>

                            <span class="employee-score-risk-badge {{ $riskClass }}">
                                {{ $riskLabel }}
                            </span>

                        </div>


                        <div class="employee-score-result-action">

                            <a
                                href="{{ route('assessment.result', $attempt) }}"
                                class="employee-score-secondary-button"
                            >
                                View Detailed Result
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>


            @if($attempts->hasPages())

                <div class="employee-score-pagination">
                    {{ $attempts->links() }}
                </div>

            @endif

        @endif

    </div>


    {{-- =========================================================
         RISK SUMMARY
    ========================================================== --}}

    @if($completedCount > 0)

        <div class="employee-score-card employee-score-risk-card">

            <div class="employee-score-card-header">

                <div>
                    <h2>Readiness History</h2>

                    <p>
                        Risk distribution across your completed assessments.
                    </p>
                </div>

            </div>


            <div class="employee-score-risk-grid">

                <div class="employee-score-risk-summary high">

                    <span>High Risk</span>
                    <strong>{{ $highRiskCount }}</strong>

                </div>

                <div class="employee-score-risk-summary medium">

                    <span>Medium Risk</span>
                    <strong>{{ $mediumRiskCount }}</strong>

                </div>

                <div class="employee-score-risk-summary low">

                    <span>Low Risk</span>
                    <strong>{{ $lowRiskCount }}</strong>

                </div>

            </div>

        </div>

    @endif


</div>


<style>

/* =========================================================
   EMPLOYEE MY SCORE
========================================================= */

.employee-score-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.employee-score-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 28px;
}

.employee-score-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.employee-score-description {
    margin: 0;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.employee-score-date {
    padding-top: 5px;
    white-space: nowrap;
    font-size: 13px;
    color: #667085;
}


/* =========================================================
   READINESS CARD
========================================================= */

.employee-score-readiness {
    margin-bottom: 24px;
    padding: 25px;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.employee-score-readiness-main {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.employee-score-eyebrow {
    display: block;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #667085;
}

.employee-score-readiness-heading {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 9px;
}

.employee-score-readiness-heading strong {
    font-size: 36px;
    line-height: 1;
    color: #172033;
}

.employee-score-readiness-main p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}

.employee-score-readiness-progress {
    margin-top: 22px;
}

.employee-score-progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 11px;
}

.employee-score-progress-header span {
    color: #667085;
}

.employee-score-progress-header strong {
    color: #172033;
}

.employee-score-progress-track {
    width: 100%;
    height: 10px;
    overflow: hidden;
    border-radius: 999px;
    background: #edf0f3;
}

.employee-score-progress-bar {
    height: 100%;
    border-radius: inherit;
    background: #52677d;
}

.employee-score-progress-bar.high {
    background: #d64545;
}

.employee-score-progress-bar.medium {
    background: #d9a300;
}

.employee-score-progress-bar.low {
    background: #319866;
}


/* =========================================================
   BUTTONS
========================================================= */

.employee-score-primary-button,
.employee-score-secondary-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 38px;
    padding: 9px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

.employee-score-primary-button {
    background: #2563eb;
    color: #fff;
}

.employee-score-secondary-button {
    background: #eef1f4;
    color: #172033;
}


/* =========================================================
   SUMMARY STAT CARDS
========================================================= */

.employee-score-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.employee-score-stat-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    min-height: 112px;
    padding: 20px;
    box-sizing: border-box;
    border-radius: 15px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 5px 20px rgba(15,23,42,.045);
}

.employee-score-stat-icon {
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

.employee-score-stat-icon svg {
    width: 22px;
    height: 22px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-score-stat-icon svg * {
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
}

.employee-score-stat-card span,
.employee-score-stat-card small {
    display: block;
}

.employee-score-stat-card span {
    margin-bottom: 5px;
    font-size: 12px;
    font-weight: 600;
    color: #667085;
}

.employee-score-stat-card strong {
    display: block;
    margin-bottom: 5px;
    font-size: 28px;
    line-height: 1;
    color: #172033;
}

.employee-score-stat-card small {
    font-size: 11px;
    line-height: 1.4;
    color: #8a94a6;
}


/* =========================================================
   MAIN RESULT CARD
========================================================= */

.employee-score-card {
    margin-bottom: 24px;
    overflow: hidden;
    border-radius: 16px;
    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.employee-score-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding: 22px 24px;
    border-bottom: 1px solid rgba(15,23,42,.08);
}

.employee-score-card-header h2 {
    margin: 0 0 5px;
    font-size: 19px;
    line-height: 1.3;
    color: #172033;
}

.employee-score-card-header p {
    margin: 0;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}

.employee-score-section-link {
    white-space: nowrap;
    font-size: 12px;
    font-weight: 650;
    text-decoration: none;
}


/* =========================================================
   RESULTS LIST
========================================================= */

.employee-score-list {
    display: flex;
    flex-direction: column;
}

.employee-score-result-row {
    display: grid;
    grid-template-columns: minmax(330px, 1.7fr) minmax(150px, .7fr) auto;
    align-items: center;
    gap: 24px;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(15,23,42,.07);
    transition: background .15s ease;
}

.employee-score-result-row:hover {
    background: #fafbfc;
}

.employee-score-result-row:last-child {
    border-bottom: 0;
}

.employee-score-result-main {
    display: flex;
    align-items: flex-start;
    gap: 13px;
    min-width: 0;
}

.employee-score-result-icon {
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

.employee-score-result-icon svg {
    width: 21px;
    height: 21px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-score-result-info {
    min-width: 0;
}

.employee-score-result-info h3 {
    overflow: hidden;
    margin: 1px 0 7px;
    font-size: 14px;
    line-height: 1.35;
    font-weight: 700;
    color: #172033;
    white-space: nowrap;
    text-overflow: ellipsis;
}

.employee-score-result-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    font-size: 11px;
    color: #8a94a6;
}

.employee-score-result-score {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
}

.employee-score-result-score > strong {
    font-size: 19px;
    line-height: 1;
    color: #172033;
}

.employee-score-result-action {
    text-align: right;
    white-space: nowrap;
}


/* =========================================================
   RISK BADGES
========================================================= */

.employee-score-risk-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    line-height: 1.2;
    font-weight: 700;
    white-space: nowrap;
}

.employee-score-risk-badge.high {
    background: #fde8e8;
    color: #b42318;
}

.employee-score-risk-badge.medium {
    background: #fff4d6;
    color: #956900;
}

.employee-score-risk-badge.low {
    background: #e6f6ee;
    color: #18794e;
}

.employee-score-risk-badge.neutral {
    background: #eef1f4;
    color: #667085;
}


/* =========================================================
   RISK SUMMARY
========================================================= */

.employee-score-risk-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    padding: 24px;
}

.employee-score-risk-summary {
    padding: 18px;
    border-radius: 12px;
}

.employee-score-risk-summary span {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 600;
}

.employee-score-risk-summary strong {
    display: block;
    font-size: 26px;
    line-height: 1;
}

.employee-score-risk-summary.high {
    background: #fde8e8;
    color: #b42318;
}

.employee-score-risk-summary.medium {
    background: #fff4d6;
    color: #956900;
}

.employee-score-risk-summary.low {
    background: #e6f6ee;
    color: #18794e;
}


/* =========================================================
   EMPTY
========================================================= */

.employee-score-empty {
    padding: 64px 24px;
    text-align: center;
}

.employee-score-empty-icon {
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

.employee-score-empty-icon svg {
    width: 26px;
    height: 26px;
    fill: none;
    stroke: currentColor;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.employee-score-empty h3 {
    margin: 0 0 7px;
    font-size: 17px;
    color: #172033;
}

.employee-score-empty p {
    margin: 0 auto 20px;
    max-width: 520px;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}


/* =========================================================
   PAGINATION
========================================================= */

.employee-score-pagination {
    padding: 18px 24px;
    border-top: 1px solid rgba(15,23,42,.07);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .employee-score-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .employee-score-result-row {
        grid-template-columns: minmax(280px, 1.4fr) minmax(130px, .7fr);
    }

    .employee-score-result-action {
        grid-column: 2;
        text-align: right;
    }
}

@media (max-width: 800px) {

    .employee-score-readiness-main {
        align-items: flex-start;
        flex-direction: column;
    }

    .employee-score-result-row {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .employee-score-result-action {
        grid-column: auto;
        text-align: left;
    }

    .employee-score-result-score {
        flex-direction: row;
        align-items: center;
        flex-wrap: wrap;
    }

    .employee-score-risk-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 650px) {

    .employee-score-page {
        padding: 20px 16px 40px;
    }

    .employee-score-header {
        flex-direction: column;
    }

    .employee-score-date {
        padding-top: 0;
    }

    .employee-score-stat-grid {
        grid-template-columns: 1fr;
    }

    .employee-score-card-header,
    .employee-score-result-row,
    .employee-score-risk-grid {
        padding-left: 18px;
        padding-right: 18px;
    }
}

</style>

@endsection
