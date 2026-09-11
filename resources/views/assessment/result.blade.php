@extends('layouts.app')

@section('title', 'Assessment Result')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Assessment Result Data
    |--------------------------------------------------------------------------
    */

    $score = (float) ($attempt->score_percentage ?? 0);

    $correctAnswers = (int) ($attempt->correct_answers ?? 0);

    $totalQuestions = (int) ($attempt->total_questions ?? 0);

    $risk = strtolower((string) ($attempt->risk_level ?? ''));

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

    /*
    |--------------------------------------------------------------------------
    | AI Insight
    |--------------------------------------------------------------------------
    */

    $summary = $insight?->summary ?? null;

    $strengths = $insight?->strengths ?? [];

    $priorityAreas = $insight?->priority_areas ?? [];

    $recommendations = $insight?->recommendations ?? [];

    if (is_string($strengths)) {
        $strengths = json_decode($strengths, true) ?: [];
    }

    if (is_string($priorityAreas)) {
        $priorityAreas = json_decode($priorityAreas, true) ?: [];
    }

    if (is_string($recommendations)) {
        $recommendations = json_decode($recommendations, true) ?: [];
    }

    if (! is_array($strengths)) {
        $strengths = [];
    }

    if (! is_array($priorityAreas)) {
        $priorityAreas = [];
    }

    if (! is_array($recommendations)) {
        $recommendations = [];
    }

    /*
    |--------------------------------------------------------------------------
    | Assessment title
    |--------------------------------------------------------------------------
    */

    $assessmentName = $attempt->assessment?->name
        ?? 'Cybersecurity Assessment';
@endphp


<div class="assessment-result-page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="assessment-result-header">

        <div>

            <div class="assessment-result-eyebrow">
                Assessment Complete
            </div>

            <h1 class="assessment-result-title">
                {{ $assessmentName }}
            </h1>

            <p class="assessment-result-description">
                Your cybersecurity assessment has been completed.
                Review your score, risk level and personalised recommendations below.
            </p>

        </div>

        <div class="assessment-result-date">
            @if($attempt->completed_at)
                {{ $attempt->completed_at->format('F j, Y') }}
            @else
                {{ now()->format('F j, Y') }}
            @endif
        </div>

    </div>


    {{-- =========================================================
         SCORE OVERVIEW
    ========================================================== --}}

    <div class="assessment-result-overview">

        <div class="assessment-result-score-panel">

            <span class="assessment-result-panel-label">
                Your Score
            </span>

            <strong class="assessment-result-big-score">
                {{ number_format($score, 1) }}%
            </strong>

            <div class="assessment-result-score-track">

                <div
                    class="assessment-result-score-bar {{ $riskClass }}"
                    style="width: {{ min(100, max(0, $score)) }}%;"
                ></div>

            </div>

            <span class="assessment-result-score-caption">
                Overall assessment score
            </span>

        </div>


        <div class="assessment-result-stat">

            <div class="assessment-result-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polyline points="20 6 9 17 4 12"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>

            <span class="assessment-result-stat-label">
                Correct Answers
            </span>

            <strong>
                {{ $correctAnswers }}
            </strong>

            <small>
                of {{ $totalQuestions }} questions
            </small>

        </div>


        <div class="assessment-result-stat">

            <div class="assessment-result-stat-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2v20"/>
                    <path d="M17 5a5 5 0 0 0-10 0c0 3 2 4 5 5s5 2 5 5a5 5 0 0 1-10 0"/>
                </svg>
            </div>

            <span class="assessment-result-stat-label">
                Questions
            </span>

            <strong>
                {{ $totalQuestions }}
            </strong>

            <small>
                Total questions
            </small>

        </div>


        <div class="assessment-result-risk-stat">

            <span class="assessment-result-stat-label">
                Risk Level
            </span>

            <span class="assessment-result-risk-badge {{ $riskClass }}">
                {{ $riskLabel }}
            </span>

            <small>
                Based on your assessment score
            </small>

        </div>

    </div>


    {{-- =========================================================
         AI INSIGHT
    ========================================================== --}}

    @if($insight)

        <div class="assessment-result-card">

            <div class="assessment-result-card-header">

                <div class="assessment-result-card-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 3a7 7 0 0 0-4 12.74V19h8v-3.26A7 7 0 0 0 12 3Z"/>
                        <path d="M9 22h6"/>
                        <path d="M10 19h4"/>
                    </svg>
                </div>

                <div>
                    <h2>AI Assessment Insight</h2>

                    <p>
                        Personalised analysis based on your assessment results.
                    </p>
                </div>

            </div>


            @if($summary)

                <div class="assessment-result-section">

                    <div class="assessment-result-section-heading">
                        Your Assessment Summary
                    </div>

                    <div class="assessment-result-summary">
                        {{ $summary }}
                    </div>

                </div>

            @endif


            {{-- =====================================================
                 STRENGTHS
            ====================================================== --}}

            @if(count($strengths))

                <div class="assessment-result-section">

                    <div class="assessment-result-section-heading">
                        <span class="section-heading-dot strength"></span>
                        Your Strengths
                    </div>

                    <div class="assessment-result-list">

                        @foreach($strengths as $strength)

                            @php
                                if (is_array($strength)) {
                                    $strengthTitle =
                                        $strength['title']
                                        ?? $strength['name']
                                        ?? null;

                                    $strengthDescription =
                                        $strength['description']
                                        ?? $strength['detail']
                                        ?? null;
                                } else {
                                    $strengthTitle = null;
                                    $strengthDescription = $strength;
                                }
                            @endphp

                            <div class="assessment-result-list-item strength-item">

                                <div class="assessment-result-list-icon">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </div>

                                <div>

                                    @if($strengthTitle)
                                        <strong>
                                            {{ $strengthTitle }}
                                        </strong>
                                    @endif

                                    @if($strengthDescription)
                                        <p>
                                            {{ $strengthDescription }}
                                        </p>
                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 PRIORITY AREAS
            ====================================================== --}}

            @if(count($priorityAreas))

                <div class="assessment-result-section">

                    <div class="assessment-result-section-heading">
                        <span class="section-heading-dot priority"></span>
                        Priority Improvement Areas
                    </div>

                    <div class="assessment-result-list">

                        @foreach($priorityAreas as $area)

                            @php
                                if (is_array($area)) {
                                    $areaName =
                                        $area['title']
                                        ?? $area['name']
                                        ?? $area['area']
                                        ?? 'Improvement Area';

                                    $areaScore =
                                        $area['score']
                                        ?? $area['current_score']
                                        ?? null;

                                    $areaPriority =
                                        $area['priority']
                                        ?? 'High';

                                    $areaDescription =
                                        $area['description']
                                        ?? $area['detail']
                                        ?? null;
                                } else {
                                    $areaName = $area;
                                    $areaScore = null;
                                    $areaPriority = 'High';
                                    $areaDescription = null;
                                }
                            @endphp

                            <div class="assessment-result-priority-item">

                                <div class="assessment-result-priority-top">

                                    <strong>
                                        {{ $areaName }}
                                    </strong>

                                    <span class="assessment-result-priority-badge">
                                        {{ $areaPriority }} Priority
                                    </span>

                                </div>

                                @if($areaScore !== null)
                                    <div class="assessment-result-priority-score">
                                        Current score:
                                        <strong>{{ $areaScore }}%</strong>
                                    </div>
                                @endif

                                @if($areaDescription)
                                    <p>
                                        {{ $areaDescription }}
                                    </p>
                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 RECOMMENDED ACTIONS
            ====================================================== --}}

            @if(count($recommendations))

                <div class="assessment-result-section">

                    <div class="assessment-result-section-heading">
                        <span class="section-heading-dot action"></span>
                        Recommended Actions
                    </div>

                    <div class="assessment-result-recommendations">

                        @foreach($recommendations as $recommendation)

                            @php
                                if (is_array($recommendation)) {
                                    $recommendationTitle =
                                        $recommendation['title']
                                        ?? $recommendation['name']
                                        ?? $recommendation['action']
                                        ?? 'Recommended Action';

                                    $recommendationPriority =
                                        $recommendation['priority']
                                        ?? 'High';

                                    $recommendationDescription =
                                        $recommendation['description']
                                        ?? $recommendation['detail']
                                        ?? null;
                                } else {
                                    $recommendationTitle = $recommendation;
                                    $recommendationPriority = 'High';
                                    $recommendationDescription = null;
                                }
                            @endphp

                            <div class="assessment-result-recommendation">

                                <div class="assessment-result-recommendation-number">
                                    {{ $loop->iteration }}
                                </div>

                                <div class="assessment-result-recommendation-content">

                                    <div class="assessment-result-recommendation-title">

                                        <strong>
                                            {{ $recommendationTitle }}
                                        </strong>

                                        <span class="assessment-result-priority-badge">
                                            {{ $recommendationPriority }} Priority
                                        </span>

                                    </div>

                                    @if($recommendationDescription)

                                        <p>
                                            {{ $recommendationDescription }}
                                        </p>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>

    @else

        <div class="assessment-result-card">

            <div class="assessment-result-no-insight">

                <div class="assessment-result-no-insight-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 3a7 7 0 0 0-4 12.74V19h8v-3.26A7 7 0 0 0 12 3Z"/>
                        <path d="M9 22h6"/>
                    </svg>
                </div>

                <h2>Assessment Result Available</h2>

                <p>
                    Your assessment result is available, but a personalised
                    AI insight has not been generated yet.
                </p>

            </div>

        </div>

    @endif


    {{-- =========================================================
         ACTIONS
    ========================================================== --}}

    <div class="assessment-result-actions">

        <a
            href="{{ route('assessment.index') }}"
            class="assessment-result-button secondary"
        >
            ← My Assessments
        </a>

        <a
            href="{{ route('employee.learning-plans') }}"
            class="assessment-result-button primary"
        >
            View Learning Plans
        </a>

    </div>

</div>


<style>

/* =========================================================
   ASSESSMENT RESULT — SELF-CONTAINED UI
========================================================= */

.assessment-result-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding: 28px 32px 50px;
    box-sizing: border-box;
}


/* =========================================================
   HEADER
========================================================= */

.assessment-result-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 28px;
}

.assessment-result-eyebrow {
    margin-bottom: 8px;
    font-size: 11px;
    line-height: 1.2;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #667085;
}

.assessment-result-title {
    margin: 0 0 8px;
    font-size: 30px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: -.02em;
    color: #172033;
}

.assessment-result-description {
    margin: 0;
    max-width: 800px;
    font-size: 15px;
    line-height: 1.6;
    color: #667085;
}

.assessment-result-date {
    padding-top: 5px;
    white-space: nowrap;
    font-size: 13px;
    color: #667085;
}


/* =========================================================
   OVERVIEW
========================================================= */

.assessment-result-overview {
    display: grid;
    grid-template-columns: minmax(250px, 1.4fr) repeat(2, minmax(150px, .75fr)) minmax(180px, .85fr);
    gap: 16px;
    margin-bottom: 24px;
}

.assessment-result-score-panel,
.assessment-result-stat,
.assessment-result-risk-stat {
    min-height: 150px;
    padding: 20px;
    box-sizing: border-box;

    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(15,23,42,.045);
}

.assessment-result-panel-label,
.assessment-result-stat-label {
    display: block;
    margin-bottom: 8px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #8a94a6;
}

.assessment-result-big-score {
    display: block;
    margin-bottom: 12px;
    font-size: 38px;
    line-height: 1;
    font-weight: 750;
    color: #172033;
}

.assessment-result-score-track {
    width: 100%;
    height: 9px;
    margin-bottom: 8px;
    overflow: hidden;
    border-radius: 999px;
    background: #edf0f3;
}

.assessment-result-score-bar {
    height: 100%;
    border-radius: inherit;
    background: #52677d;
}

.assessment-result-score-bar.high {
    background: #d64545;
}

.assessment-result-score-bar.medium {
    background: #d9a300;
}

.assessment-result-score-bar.low {
    background: #319866;
}

.assessment-result-score-caption {
    font-size: 11px;
    color: #8a94a6;
}

.assessment-result-stat-icon {
    width: 42px;
    height: 42px;
    margin-bottom: 15px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;
    background: #eef2f5;
    color: #334155;
}

.assessment-result-stat-icon svg {
    width: 21px;
    height: 21px;

    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.assessment-result-stat-icon svg * {
    fill: none;
    stroke: currentColor;
}

.assessment-result-stat strong {
    display: block;
    margin-bottom: 5px;
    font-size: 27px;
    line-height: 1;
    color: #172033;
}

.assessment-result-stat small,
.assessment-result-risk-stat small {
    display: block;
    font-size: 11px;
    line-height: 1.4;
    color: #8a94a6;
}

.assessment-result-risk-stat {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
}

.assessment-result-risk-badge {
    display: inline-flex;
    align-items: center;

    margin-bottom: 13px;
    padding: 7px 11px;

    border-radius: 999px;

    font-size: 12px;
    font-weight: 750;
}

.assessment-result-risk-badge.high {
    background: #fde8e8;
    color: #b42318;
}

.assessment-result-risk-badge.medium {
    background: #fff4d6;
    color: #956900;
}

.assessment-result-risk-badge.low {
    background: #e6f6ee;
    color: #18794e;
}

.assessment-result-risk-badge.neutral {
    background: #eef1f4;
    color: #667085;
}


/* =========================================================
   AI CARD
========================================================= */

.assessment-result-card {
    margin-bottom: 24px;
    overflow: hidden;

    background: #fff;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 16px;
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.assessment-result-card-header {
    display: flex;
    align-items: flex-start;
    gap: 13px;

    padding: 23px 24px;

    border-bottom: 1px solid rgba(15,23,42,.08);
}

.assessment-result-card-icon {
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

.assessment-result-card-icon svg {
    width: 21px;
    height: 21px;

    fill: none;
    stroke: currentColor;
    stroke-width: 1.8;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.assessment-result-card-header h2 {
    margin: 1px 0 5px;
    font-size: 19px;
    line-height: 1.3;
    color: #172033;
}

.assessment-result-card-header p {
    margin: 0;
    font-size: 13px;
    color: #667085;
}


/* =========================================================
   SECTIONS
========================================================= */

.assessment-result-section {
    padding: 24px;
    border-bottom: 1px solid rgba(15,23,42,.07);
}

.assessment-result-section:last-child {
    border-bottom: 0;
}

.assessment-result-section-heading {
    display: flex;
    align-items: center;
    gap: 9px;

    margin-bottom: 13px;

    font-size: 14px;
    font-weight: 750;
    color: #172033;
}

.section-heading-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #52677d;
}

.section-heading-dot.strength {
    background: #319866;
}

.section-heading-dot.priority {
    background: #d64545;
}

.section-heading-dot.action {
    background: #2563eb;
}

.assessment-result-summary {
    max-width: 1000px;
    padding: 16px;

    border-radius: 11px;
    background: #f7f8fa;

    font-size: 13px;
    line-height: 1.7;
    color: #556070;
}


/* =========================================================
   STRENGTHS
========================================================= */

.assessment-result-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.assessment-result-list-item {
    display: flex;
    align-items: flex-start;
    gap: 11px;

    padding: 14px;

    border-radius: 10px;
    background: #f8faf9;
}

.assessment-result-list-icon {
    width: 28px;
    height: 28px;
    min-width: 28px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;
    background: #e6f6ee;
    color: #18794e;
}

.assessment-result-list-icon svg {
    width: 15px;
    height: 15px;

    fill: none;
    stroke: currentColor;
    stroke-width: 2;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.assessment-result-list-item strong {
    display: block;
    margin-bottom: 3px;
    font-size: 13px;
}

.assessment-result-list-item p {
    margin: 0;
    font-size: 12px;
    line-height: 1.6;
    color: #667085;
}


/* =========================================================
   PRIORITY AREAS
========================================================= */

.assessment-result-priority-item {
    padding: 15px;

    border: 1px solid rgba(15,23,42,.07);
    border-radius: 11px;

    background: #fff;
}

.assessment-result-priority-item + .assessment-result-priority-item {
    margin-top: 10px;
}

.assessment-result-priority-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 7px;
}

.assessment-result-priority-top strong {
    font-size: 13px;
}

.assessment-result-priority-badge {
    display: inline-flex;
    align-items: center;

    padding: 4px 7px;

    border-radius: 999px;

    background: #fde8e8;
    color: #b42318;

    font-size: 9px;
    font-weight: 750;
    white-space: nowrap;
}

.assessment-result-priority-score {
    margin-bottom: 6px;
    font-size: 11px;
    color: #667085;
}

.assessment-result-priority-item p {
    margin: 0;
    font-size: 12px;
    line-height: 1.6;
    color: #667085;
}


/* =========================================================
   RECOMMENDATIONS
========================================================= */

.assessment-result-recommendations {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.assessment-result-recommendation {
    display: flex;
    align-items: flex-start;
    gap: 13px;

    padding: 16px;

    border-radius: 11px;
    background: #f7f9fc;
}

.assessment-result-recommendation-number {
    width: 29px;
    height: 29px;
    min-width: 29px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;
    background: #e9eff8;
    color: #2257a5;

    font-size: 11px;
    font-weight: 750;
}

.assessment-result-recommendation-content {
    min-width: 0;
}

.assessment-result-recommendation-title {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;

    margin-bottom: 5px;
}

.assessment-result-recommendation-title strong {
    font-size: 13px;
}

.assessment-result-recommendation p {
    margin: 0;
    font-size: 12px;
    line-height: 1.65;
    color: #667085;
}


/* =========================================================
   NO INSIGHT
========================================================= */

.assessment-result-no-insight {
    padding: 55px 24px;
    text-align: center;
}

.assessment-result-no-insight-icon {
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

.assessment-result-no-insight-icon svg {
    width: 26px;
    height: 26px;

    fill: none;
    stroke: currentColor;
    stroke-width: 1.7;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.assessment-result-no-insight h2 {
    margin: 0 0 7px;
    font-size: 17px;
}

.assessment-result-no-insight p {
    max-width: 540px;
    margin: 0 auto;
    font-size: 13px;
    line-height: 1.5;
    color: #667085;
}


/* =========================================================
   ACTIONS
========================================================= */

.assessment-result-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
}

.assessment-result-button {
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

.assessment-result-button.primary {
    background: #2563eb;
    color: #fff;
}

.assessment-result-button.secondary {
    background: #eef1f4;
    color: #172033;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 1100px) {

    .assessment-result-overview {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 750px) {

    .assessment-result-page {
        padding: 20px 16px 40px;
    }

    .assessment-result-header {
        flex-direction: column;
    }

    .assessment-result-date {
        padding-top: 0;
    }

    .assessment-result-overview {
        grid-template-columns: 1fr;
    }

    .assessment-result-card-header,
    .assessment-result-section {
        padding-left: 18px;
        padding-right: 18px;
    }

    .assessment-result-actions {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .assessment-result-button {
        width: 100%;
    }

}

</style>

@endsection
