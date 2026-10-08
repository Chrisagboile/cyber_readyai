@extends('layouts.app')

@section('title', 'Feedback Report')

@section('content')

@php
    $attempt = $feedback->attempt;

    $risk = strtolower(
        (string) $attempt?->risk_level
    );

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


<div class="feedback-detail-page">

    <div class="feedback-detail-header">

        <a
            href="{{ route('feedback-reports.index') }}"
            class="feedback-detail-back"
        >
            ← Back to Feedback Reports
        </a>

        <h1>Feedback Report</h1>

        <p>
            Detailed feedback and assessment information.
        </p>

    </div>


    <section class="feedback-detail-card">

        <div class="feedback-detail-card-header">
            <h2>Respondent</h2>
        </div>

        <div class="feedback-detail-grid">

            <div>
                <span>Name</span>

                <strong>
                    {{ $feedback->user?->name ?? 'Unknown' }}
                </strong>
            </div>

            <div>
                <span>Email</span>

                <strong>
                    {{ $feedback->user?->email ?? 'Not available' }}
                </strong>
            </div>

            <div>
                <span>External Organisation</span>

                <strong>
                    {{ $feedback->organization_name ?: 'Not provided' }}
                </strong>
            </div>

            <div>
                <span>Organisation Size</span>

                <strong>
                    {{ $feedback->organization_size ?: 'Not provided' }}
                </strong>
            </div>

        </div>

    </section>


    <section class="feedback-detail-card">

        <div class="feedback-detail-card-header">
            <h2>Assessment</h2>
        </div>

        <div class="feedback-detail-grid">

            <div>
                <span>Score</span>

                <strong>
                    @if($attempt)
                        {{ number_format((float) $attempt->score_percentage, 1) }}%
                    @else
                        —
                    @endif
                </strong>
            </div>

            <div>
                <span>Risk Level</span>

                <strong>
                    <span class="feedback-risk {{ $riskClass }}">
                        {{ $riskLabel }}
                    </span>
                </strong>
            </div>

            <div>
                <span>Correct Answers</span>

                <strong>
                    {{ $attempt?->correct_answers ?? '—' }}
                    /
                    {{ $attempt?->total_questions ?? '—' }}
                </strong>
            </div>

            <div>
                <span>Completed</span>

                <strong>
                    {{ $attempt?->completed_at?->format('d M Y H:i') ?? '—' }}
                </strong>
            </div>

        </div>

    </section>


    <section class="feedback-detail-card">

        <div class="feedback-detail-card-header">
            <h2>Survey Responses</h2>
        </div>

        <div class="feedback-detail-response-list">

            <div class="feedback-detail-response">

                <span>
                    Awareness Test Frequency
                </span>

                <strong>
                    {{ $feedback->awareness_test_frequency ?: 'Not provided' }}
                </strong>

            </div>


            <div class="feedback-detail-response">

                <span>
                    Recommended For
                </span>

                @if(
                    is_array($feedback->recommended_for) &&
                    count($feedback->recommended_for)
                )

                    <div class="feedback-tags">

                        @foreach($feedback->recommended_for as $item)

                            <span>
                                {{ $item }}
                            </span>

                        @endforeach

                    </div>

                @else

                    <strong>
                        Not provided
                    </strong>

                @endif

            </div>


            <div class="feedback-detail-response">

                <span>
                    Awareness Testing Impact
                </span>

                <strong>
                    {{ $feedback->awareness_impact ?: 'Not provided' }}
                </strong>

            </div>


            <div class="feedback-detail-response">

                <span>
                    Assessment Rating
                </span>

                <strong>
                    {{ $feedback->rating }}/5
                </strong>

            </div>

        </div>

    </section>


    <section class="feedback-detail-card">

        <div class="feedback-detail-card-header">
            <h2>Comments</h2>
        </div>

        <div class="feedback-detail-text">

            @if($feedback->comments)

                {!! nl2br(e($feedback->comments)) !!}

            @else

                <span class="feedback-detail-muted">
                    No additional comments were provided.
                </span>

            @endif

        </div>

    </section>


    <section class="feedback-detail-card">

        <div class="feedback-detail-card-header">
            <h2>Improvement Suggestions</h2>
        </div>

        <div class="feedback-detail-text">

            @if($feedback->improvement_suggestions)

                {!! nl2br(e($feedback->improvement_suggestions)) !!}

            @else

                <span class="feedback-detail-muted">
                    No improvement suggestions were provided.
                </span>

            @endif

        </div>

    </section>

</div>


<style>

.feedback-detail-page {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
    padding: 30px 24px 60px;
}

.feedback-detail-header {
    margin-bottom: 25px;
}

.feedback-detail-back {
    display: inline-block;
    margin-bottom: 15px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
}

.feedback-detail-header h1 {
    margin: 0 0 7px;
    font-size: 30px;
    color: #172033;
}

.feedback-detail-header p {
    margin: 0;
    color: #667085;
    font-size: 14px;
}

.feedback-detail-card {
    margin-bottom: 18px;
    overflow: hidden;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 6px 24px rgba(15,23,42,.05);
}

.feedback-detail-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid #edf0f3;
}

.feedback-detail-card-header h2 {
    margin: 0;
    font-size: 17px;
    color: #172033;
}

.feedback-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.feedback-detail-grid > div {
    padding: 20px 24px;
    border-bottom: 1px solid #edf0f3;
}

.feedback-detail-grid span:first-child,
.feedback-detail-response > span {
    display: block;
    margin-bottom: 6px;
    font-size: 10px;
    font-weight: 700;
    color: #8a94a6;
    text-transform: uppercase;
    letter-spacing: .05em;
}

.feedback-detail-grid strong,
.feedback-detail-response strong {
    color: #172033;
    font-size: 13px;
}

.feedback-detail-response-list {
    padding: 0 24px;
}

.feedback-detail-response {
    padding: 18px 0;
    border-bottom: 1px solid #edf0f3;
}

.feedback-detail-response:last-child {
    border-bottom: 0;
}

.feedback-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
}

.feedback-tags span {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    background: #eef2f5;
    color: #344054;
    font-size: 10px;
    font-weight: 700;
}

.feedback-risk {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 700;
}

.feedback-risk.high {
    background: #fde8e8;
    color: #b42318;
}

.feedback-risk.medium {
    background: #fff4d6;
    color: #956900;
}

.feedback-risk.low {
    background: #e6f6ee;
    color: #18794e;
}

.feedback-risk.neutral {
    background: #eef1f4;
    color: #667085;
}

.feedback-detail-text {
    padding: 22px 24px;
    color: #475467;
    font-size: 13px;
    line-height: 1.7;
}

.feedback-detail-muted {
    color: #98a2b3;
}

@media (max-width: 650px) {

    .feedback-detail-page {
        padding-left: 16px;
        padding-right: 16px;
    }

    .feedback-detail-grid {
        grid-template-columns: 1fr;
    }

}

</style>

@endsection
