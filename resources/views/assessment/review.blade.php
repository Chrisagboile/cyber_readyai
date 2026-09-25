@extends('layouts.app')

@section('title', 'Review Assessment')

@section('page-title', 'Review Assessment')

@section('content')

<style>
    /* =========================================================
       CYBERREADYAI - ASSESSMENT REVIEW
    ========================================================== */

    .assessment-review-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 36px 34px 60px;
        box-sizing: border-box;
    }

    /* Header */

    .assessment-review-header {
        margin-bottom: 28px;
    }

    .assessment-review-title {
        margin: 0;
        color: #12213f;
        font-size: 34px;
        line-height: 1.2;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .assessment-review-subtitle {
        margin: 10px 0 0;
        color: #667085;
        font-size: 16px;
        line-height: 1.6;
    }

    /* Summary */

    .assessment-review-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 28px;
    }

    .assessment-review-summary-card {
        background: #ffffff;
        border: 1px solid #e3e9f2;
        border-radius: 15px;
        padding: 20px 22px;
        box-shadow: 0 5px 18px rgba(18, 33, 63, 0.05);
    }

    .assessment-review-summary-label {
        margin-bottom: 8px;
        color: #718096;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }

    .assessment-review-summary-value {
        color: #152746;
        font-size: 27px;
        line-height: 1.1;
        font-weight: 800;
    }

    /* Main card */

    .assessment-review-card {
        background: #ffffff;
        border: 1px solid #e3e9f2;
        border-radius: 18px;
        box-shadow: 0 7px 24px rgba(18, 33, 63, 0.06);
        overflow: hidden;
    }

    .assessment-review-card-header {
        padding: 26px 28px 22px;
        border-bottom: 1px solid #edf1f6;
    }

    .assessment-review-card-header h2 {
        margin: 0;
        color: #142441;
        font-size: 22px;
        line-height: 1.3;
        font-weight: 750;
    }

    .assessment-review-card-header p {
        margin: 7px 0 0;
        color: #6b778b;
        font-size: 14px;
        line-height: 1.55;
    }

    /* Question list */

    .assessment-review-list {
        padding: 8px 28px 10px;
    }

    .assessment-review-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 28px;
        padding: 24px 0;
        border-bottom: 1px solid #edf1f6;
    }

    .assessment-review-item:last-child {
        border-bottom: none;
    }

    .assessment-review-question-content {
        flex: 1;
        min-width: 0;
    }

    .assessment-review-question-number {
        display: inline-flex;
        align-items: center;
        min-height: 29px;
        margin-bottom: 11px;
        padding: 5px 10px;
        border-radius: 8px;
        background: #eef4ff;
        color: #255da7;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.2px;
    }

    .assessment-review-question {
        margin: 0;
        color: #162743;
        font-size: 16px;
        line-height: 1.65;
        font-weight: 650;
    }

    /* Answer */

    .assessment-review-answer {
        margin-top: 14px;
    }

    .assessment-review-answer-label {
        color: #758195;
        font-size: 13px;
        font-weight: 700;
    }

    .assessment-review-answer-value {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        margin-left: 7px;
        padding: 0 10px;
        border-radius: 8px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 14px;
        font-weight: 800;
    }

    .assessment-review-unanswered {
        display: inline-flex;
        align-items: center;
        margin-top: 13px;
        padding: 7px 11px;
        border-radius: 8px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: 13px;
        font-weight: 750;
    }

    /* Review button */

    .assessment-review-action {
        flex-shrink: 0;
        padding-top: 4px;
    }

    .assessment-review-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 92px;
        min-height: 40px;
        padding: 9px 16px;
        border: 1px solid #d6deea;
        border-radius: 9px;
        background: #ffffff;
        color: #24466f;
        font-size: 13px;
        font-weight: 750;
        text-decoration: none;
        transition:
            background 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease;
        box-sizing: border-box;
    }

    .assessment-review-button:hover {
        background: #f4f8ff;
        border-color: #b9cbea;
        color: #1d4ed8;
        transform: translateY(-1px);
    }

    /* Submit area */

    .assessment-review-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 25px;
        padding: 24px 28px 28px;
        border-top: 1px solid #edf1f6;
        background: #fbfcfe;
    }

    .assessment-review-footer-text {
        color: #667085;
        font-size: 14px;
        line-height: 1.55;
    }

    .assessment-review-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 11px 23px;
        border: none;
        border-radius: 10px;
        background: #2563eb;
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 5px 14px rgba(37, 99, 235, 0.2);
        transition:
            background 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .assessment-review-submit:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 7px 18px rgba(37, 99, 235, 0.25);
    }

    /* Warning */

    .assessment-review-warning {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 14px;
        color: #8a5a00;
        font-size: 13px;
        line-height: 1.5;
    }

    .assessment-review-warning strong {
        color: #7a4f00;
    }

    /* Responsive */

    @media (max-width: 900px) {
        .assessment-review-summary {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .assessment-review-page {
            padding: 28px 22px 45px;
        }
    }

    @media (max-width: 760px) {
        .assessment-review-summary {
            grid-template-columns: 1fr 1fr;
        }

        .assessment-review-item {
            flex-direction: column;
            gap: 16px;
        }

        .assessment-review-action {
            padding-top: 0;
        }

        .assessment-review-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .assessment-review-submit {
            width: 100%;
        }
    }

    @media (max-width: 520px) {
        .assessment-review-page {
            padding: 22px 15px 40px;
        }

        .assessment-review-title {
            font-size: 28px;
        }

        .assessment-review-summary {
            grid-template-columns: 1fr;
        }

        .assessment-review-card-header,
        .assessment-review-list,
        .assessment-review-footer {
            padding-left: 18px;
            padding-right: 18px;
        }

        .assessment-review-item {
            padding: 20px 0;
        }

        .assessment-review-question {
            font-size: 15px;
        }
    }
</style>


<div class="assessment-review-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="assessment-review-header">

        <h1 class="assessment-review-title">
            Review Your Assessment
        </h1>

        <p class="assessment-review-subtitle">
            Please review your answers carefully before submitting
            your assessment.
        </p>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="assessment-review-summary">

        <div class="assessment-review-summary-card">

            <div class="assessment-review-summary-label">
                Total Questions
            </div>

            <div class="assessment-review-summary-value">
                {{ $questions->count() }}
            </div>

        </div>


        <div class="assessment-review-summary-card">

            <div class="assessment-review-summary-label">
                Answered
            </div>

            <div class="assessment-review-summary-value">
                {{ $answers->count() }}
            </div>

        </div>


        <div class="assessment-review-summary-card">

            <div class="assessment-review-summary-label">
                Not Answered
            </div>

            <div class="assessment-review-summary-value">
                {{ max(0, $questions->count() - $answers->count()) }}
            </div>

        </div>

    </div>


    {{-- =====================================================
         REVIEW CARD
    ====================================================== --}}

    <div class="assessment-review-card">

        <div class="assessment-review-card-header">

            <h2>
                Your Answers
            </h2>

            <p>
                Check each question before submitting your final
                assessment.
            </p>

        </div>


        <div class="assessment-review-list">

            @foreach($questions as $index => $question)

                @php
                    $answer = $answers->get($question->id);
                @endphp

                <div class="assessment-review-item">

                    {{-- Question content --}}

                    <div class="assessment-review-question-content">

                        <div class="assessment-review-question-number">
                            Question {{ $index + 1 }}
                        </div>


                        <p class="assessment-review-question">
                            {{ $question->question }}
                        </p>


                        @if($answer)

                            <div class="assessment-review-answer">

                                <span class="assessment-review-answer-label">
                                    Your answer
                                </span>

                                <span class="assessment-review-answer-value">
                                    {{ $answer->option->option_letter }}
                                </span>

                            </div>

                        @else

                            <div class="assessment-review-unanswered">
                                Not answered
                            </div>

                        @endif

                    </div>


                    {{-- Review action --}}

                    <div class="assessment-review-action">

                        <a
                            href="{{ route(
                                'assessment.question',
                                [
                                    'attempt' => $attempt->id,
                                    'question' => $index + 1
                                ]
                            ) }}"
                            class="assessment-review-button"
                        >
                            Review
                        </a>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- =================================================
             SUBMISSION
        ================================================== --}}

        <div class="assessment-review-footer">

            <div>

                <div class="assessment-review-footer-text">
                    Once submitted, your assessment will be recorded
                    and your results will be calculated.
                </div>

                @if($answers->count() < $questions->count())

                    <div class="assessment-review-warning">
                        <span>⚠</span>

                        <span>
                            <strong>
                                Some questions are unanswered.
                            </strong>
                            You can select Review above to return to
                            any question before submitting.
                        </span>
                    </div>

                @endif

            </div>


            <form
                method="POST"
                action="{{ route(
                    'assessment.submit',
                    $attempt
                ) }}"
            >

                @csrf

                <button
                    type="submit"
                    class="assessment-review-submit"
                >
                    Submit Assessment
                </button>

            </form>

        </div>

    </div>

</div>

@endsection