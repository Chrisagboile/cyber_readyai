@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')


<style>
    .pt-review {
        max-width:950px;
        margin:0px auto;
        padding:0 20px 60px;
    }

    .pt-review-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:24px;
        margin-bottom:15px;
    }

    .pt-review-card h2 {
        margin:8px 0 15px;
        color:#111827;
    }

    .pt-number {
        color:#4f46e5;
        font-size:13px;
        font-weight:700;
    }

    .pt-answer {
        padding:13px;
        border-radius:9px;
        background:#f3f4f6;
        color:#374151;
    }

    .pt-unanswered {
        background:#fff7ed;
        color:#9a3412;
    }

    .pt-actions {
        display:flex;
        justify-content:space-between;
        gap:15px;
        margin-top:25px;
    }

    .pt-button {
        display:inline-block;
        padding:13px 20px;
        border:0;
        border-radius:9px;
        background:#111827;
        color:white;
        text-decoration:none;
        font-weight:700;
        cursor:pointer;
    }

    .pt-button.secondary {
        background:#f3f4f6;
        color:#374151;
    }

    @media(max-width:700px) {
        .pt-actions {
            flex-direction:column;
        }

        .pt-button {
            text-align:center;
        }
    }
</style>

<div class="pt-review">

    <h1>
        Review Your Answers
    </h1>

    <p style="color:#6b7280;">
        You can change any answer before submitting.
    </p>

    <div style="
        margin:20px 0;
        padding:18px;
        background:#f9fafb;
        border-radius:12px;
    ">

        <strong>
            Answered:
        </strong>

        {{ $attempt->answered_questions }}

        /

        {{ $attempt->total_questions }}

    </div>

    @foreach ($questions as $reviewQuestion)

        @php
            $questionModel =
                $reviewQuestion->question;

            $answer =
                $answers[$reviewQuestion->question_id]
                ?? null;

            $selectedOption =
                $answer?->option;
        @endphp

        <div class="pt-review-card">

            <div class="pt-number">
                Question {{ $reviewQuestion->question_order }}
            </div>

            <h2>
                {{ $questionModel?->question }}
            </h2>

            @if ($selectedOption)

                <div class="pt-answer">

                    <strong>
                        Your answer:
                    </strong>

                    {{ $selectedOption->option_letter }}.
                    {{ $selectedOption->option_text }}

                </div>

            @else

                <div class="pt-answer pt-unanswered">

                    This question has not been answered.

                </div>

            @endif

            <div style="margin-top:14px;">

                <a
                    href="{{ route('public-test.question', [
                        'attempt' => $attempt->id,
                        'question' => $reviewQuestion->question_order,
                    ]) }}"
                    style="
                        color:#4f46e5;
                        text-decoration:none;
                        font-weight:700;
                    "
                >
                    Change Answer
                </a>

            </div>

        </div>

    @endforeach

    <div class="pt-actions">

        <a
            href="{{ route('public-test.question', [
                'attempt' => $attempt->id,
                'question' => max(
                    1,
                    $attempt->current_position
                ),
            ]) }}"
            class="pt-button secondary"
        >
            Return to Test
        </a>

        <form
            method="POST"
            action="{{ route('public-test.submit', $attempt) }}"
        >

            @csrf

            <button
                type="submit"
                class="pt-button"
            >
                Submit Test
            </button>

        </form>

    </div>

</div>

@endsection
