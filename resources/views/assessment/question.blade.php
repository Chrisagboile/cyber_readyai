@extends('layouts.app')

@section('title', 'Assessment')

@section('content')

<div class="assessment-page">

    {{-- ============================================================
        HEADER
    ============================================================= --}}

    <div class="assessment-header">

        <div>
            <div class="assessment-eyebrow">
                Cybersecurity Assessment
            </div>

            <h1 class="assessment-title">
                {{ $attempt->assessment?->name ?? 'Assessment' }}
            </h1>

            <p class="assessment-description">
                Question {{ $question }} of {{ $total }}
            </p>
        </div>

        {{-- TIMER --}}
        <div class="assessment-timer-card">

            <span class="assessment-timer-label">
                Time Remaining
            </span>

            <strong
                id="assessment-timer"
                class="assessment-timer"
            >
                --:--
            </strong>

        </div>

    </div>


    {{-- ============================================================
        PROGRESS
    ============================================================= --}}

    @php
        $progress = $total > 0
            ? ($question / $total) * 100
            : 0;
    @endphp

    <div class="assessment-progress-card">

        <div class="assessment-progress-header">

            <span>
                Assessment Progress
            </span>

            <strong>
                {{ $question }} / {{ $total }}
            </strong>

        </div>

        <div class="assessment-progress-track">

            <div
                class="assessment-progress-fill"
                style="width: {{ $progress }}%;"
            ></div>

        </div>

    </div>


    {{-- ============================================================
        QUESTION
    ============================================================= --}}

    <div class="assessment-question-card">

        <div class="assessment-question-top">

            <span class="question-number">
                Question {{ $question }}
            </span>

            @if($currentQuestion->category)
                <span class="question-category">
                    {{ $currentQuestion->category->name }}
                </span>
            @endif

        </div>

        <h2 class="assessment-question">
            {{ $currentQuestion->question }}
        </h2>


        {{-- ========================================================
            ANSWERS
        ========================================================= --}}

        <form
            id="answer-form"
            method="POST"
            action="{{ route(
                'assessment.answer',
                [
                    'attempt' => $attempt,
                    'question' => $question,
                ]
            ) }}"
        >

            @csrf

            <div class="answer-options">

                @foreach($currentQuestion->options as $option)

                    @php
                        $isSelected =
                            $answer
                            && $answer->question_option_id
                                == $option->id;
                    @endphp

                    <label
                        class="
                            answer-option
                            {{ $isSelected
                                ? 'selected'
                                : '' }}
                        "
                    >

                        <input
                            type="radio"
                            name="question_option_id"
                            value="{{ $option->id }}"
                            {{ $isSelected ? 'checked' : '' }}
                        >

                        <span class="answer-letter">
                            {{ $option->option_letter }}
                        </span>

                        <span class="answer-text">
                            {{ $option->option_text }}
                        </span>

                    </label>

                @endforeach

            </div>


            {{-- ====================================================
                NAVIGATION
            ===================================================== --}}

            <div class="assessment-navigation">

                @if($question > 1)

                    <a
                        href="{{ route(
                            'assessment.question',
                            [
                                'attempt' => $attempt,
                                'question' => $question - 1,
                            ]
                        ) }}"
                        class="assessment-nav-button secondary"
                    >
                        ← Previous
                    </a>

                @else

                    <div></div>

                @endif


                @if($question < $total)

                    <button
                        type="submit"
                        class="assessment-nav-button primary"
                    >
                        Save & Continue →
                    </button>

                @else

                    <button
                        type="submit"
                        class="assessment-nav-button primary"
                    >
                        Save & Review →
                    </button>

                @endif

            </div>

        </form>

    </div>


    {{-- ============================================================
        ASSESSMENT INFO
    ============================================================= --}}

    <div class="assessment-footer-info">

        <div class="assessment-info-item">

            <span class="assessment-info-icon">
                ✓
            </span>

            <div>
                <strong>
                    {{ $answeredCount }}
                    answered
                </strong>

                <span>
                    of {{ $total }} questions
                </span>
            </div>

        </div>

        <div class="assessment-info-item">

            <span class="assessment-info-icon">
                ⏱
            </span>

            <div>
                <strong>
                    {{ $attempt->assessment?->duration_minutes }}
                    minutes
                </strong>

                <span>
                    total assessment time
                </span>
            </div>

        </div>

        <div class="assessment-info-item">

            <span class="assessment-info-icon">
                🔒
            </span>

            <div>
                <strong>
                    Your progress is saved
                </strong>

                <span>
                    You can resume later
                </span>
            </div>

        </div>

    </div>

</div>


{{-- ================================================================
    TIMER
================================================================= --}}

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const timerElement =
                document.getElementById(
                    'assessment-timer'
                );

            const expiresAt =
                new Date(
                    @json(
                        $attempt->expires_at?->toIso8601String()
                    )
                ).getTime();

            let expired = false;

            function updateTimer() {

                if (expired) {
                    return;
                }

                const now = Date.now();

                const remaining =
                    expiresAt - now;

                if (remaining <= 0) {

                    expired = true;

                    timerElement.textContent =
                        '00:00';

                    timerElement.classList.add(
                        'expired'
                    );

                    /*
                     * The server is still the source
                     * of truth.
                     *
                     * Reloading makes the Laravel
                     * controller check expires_at.
                     */
                    setTimeout(
                        function () {
                            window.location.reload();
                        },
                        500
                    );

                    return;
                }

                const totalSeconds =
                    Math.floor(
                        remaining / 1000
                    );

                const minutes =
                    Math.floor(
                        totalSeconds / 60
                    );

                const seconds =
                    totalSeconds % 60;

                timerElement.textContent =
                    String(minutes)
                        .padStart(2, '0')
                    + ':'
                    + String(seconds)
                        .padStart(2, '0');


                /*
                 * Visual warning when less than
                 * five minutes remain.
                 */
                if (remaining <= 300000) {

                    timerElement.classList.add(
                        'warning'
                    );

                }
            }

            updateTimer();

            setInterval(
                updateTimer,
                1000
            );
        }
    );


    /*
     * Highlight selected answer.
     */
    document
        .querySelectorAll(
            '.answer-option input'
        )
        .forEach(function (input) {

            input.addEventListener(
                'change',
                function () {

                    document
                        .querySelectorAll(
                            '.answer-option'
                        )
                        .forEach(function (option) {
                            option.classList.remove(
                                'selected'
                            );
                        });

                    this
                        .closest('.answer-option')
                        .classList.add(
                            'selected'
                        );
                }
            );

        });


    /*
     * Prevent accidentally submitting the
     * form without selecting an answer.
     */
    const answerForm =
        document.getElementById(
            'answer-form'
        );

    if (answerForm) {

        answerForm.addEventListener(
            'submit',
            function (event) {

                const selected =
                    answerForm.querySelector(
                        'input[name="question_option_id"]:checked'
                    );

                if (!selected) {

                    event.preventDefault();

                    alert(
                        'Please select an answer before continuing.'
                    );
                }

            }
        );

    }
</script>


{{-- ================================================================
    STYLES
================================================================= --}}

<style>

    .assessment-page {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    /* ------------------------------------------------------------
       Header
    ------------------------------------------------------------ */

    .assessment-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 24px;
    }

    .assessment-eyebrow {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        opacity: 0.65;
        margin-bottom: 6px;
    }

    .assessment-title {
        margin: 0;
        font-size: 30px;
        font-weight: 700;
    }

    .assessment-description {
        margin: 8px 0 0;
        opacity: 0.65;
    }


    /* ------------------------------------------------------------
       Timer
    ------------------------------------------------------------ */

    .assessment-timer-card {
        min-width: 170px;
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .assessment-timer-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.6;
        margin-bottom: 4px;
    }

    .assessment-timer {
        display: block;
        font-size: 28px;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .assessment-timer.warning {
        color: #b45309;
    }

    .assessment-timer.expired {
        color: #dc2626;
    }


    /* ------------------------------------------------------------
       Progress
    ------------------------------------------------------------ */

    .assessment-progress-card {
        margin-bottom: 24px;
        padding: 18px 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
    }

    .assessment-progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .assessment-progress-header strong {
        font-weight: 700;
    }

    .assessment-progress-track {
        width: 100%;
        height: 8px;
        overflow: hidden;
        border-radius: 999px;
        background: #e5e7eb;
    }

    .assessment-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: #2563eb;
        transition: width 0.3s ease;
    }


    /* ------------------------------------------------------------
       Question Card
    ------------------------------------------------------------ */

    .assessment-question-card {
        padding: 32px;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    }

    .assessment-question-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }

    .question-number {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        opacity: 0.6;
    }

    .question-category {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        background: #f3f4f6;
        font-size: 12px;
        font-weight: 600;
    }

    .assessment-question {
        margin: 0 0 30px;
        max-width: 850px;
        font-size: 23px;
        line-height: 1.45;
        font-weight: 700;
    }


    /* ------------------------------------------------------------
       Answer Options
    ------------------------------------------------------------ */

    .answer-options {
        display: grid;
        gap: 14px;
    }

    .answer-option {
        position: relative;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: #ffffff;
        cursor: pointer;
        transition:
            border-color 0.15s ease,
            background 0.15s ease,
            transform 0.15s ease;
    }

    .answer-option:hover {
        border-color: #9ca3af;
        transform: translateY(-1px);
    }

    .answer-option.selected {
        border-color: #2563eb;
        background: #eff6ff;
    }

    .answer-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .answer-letter {
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid #d1d5db;
        background: #f9fafb;
        font-weight: 700;
    }

    .answer-option.selected .answer-letter {
        border-color: #2563eb;
        background: #2563eb;
        color: #ffffff;
    }

    .answer-text {
        font-size: 15px;
        line-height: 1.5;
    }


    /* ------------------------------------------------------------
       Navigation
    ------------------------------------------------------------ */

    .assessment-navigation {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .assessment-nav-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 0 20px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
    }

    .assessment-nav-button.primary {
        background: #2563eb;
        color: #ffffff;
    }

    .assessment-nav-button.primary:hover {
        background: #1d4ed8;
    }

    .assessment-nav-button.secondary {
        border-color: #d1d5db;
        background: #ffffff;
        color: #374151;
    }

    .assessment-nav-button.secondary:hover {
        background: #f9fafb;
    }


    /* ------------------------------------------------------------
       Footer Information
    ------------------------------------------------------------ */

    .assessment-footer-info {
        display: grid;
        grid-template-columns:
            repeat(3, 1fr);
        gap: 16px;
        margin-top: 20px;
    }

    .assessment-info-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-radius: 12px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
    }

    .assessment-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
    }

    .assessment-info-item strong,
    .assessment-info-item span {
        display: block;
    }

    .assessment-info-item strong {
        font-size: 13px;
    }

    .assessment-info-item div span {
        margin-top: 2px;
        font-size: 12px;
        opacity: 0.6;
    }


    /* ------------------------------------------------------------
       Responsive
    ------------------------------------------------------------ */

    @media (max-width: 768px) {

        .assessment-header {
            flex-direction: column;
        }

        .assessment-timer-card {
            width: 100%;
        }

        .assessment-question-card {
            padding: 22px;
        }

        .assessment-question {
            font-size: 20px;
        }

        .assessment-footer-info {
            grid-template-columns: 1fr;
        }

        .assessment-navigation {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .assessment-nav-button {
            width: 100%;
        }

    }

</style>

@endsection
