@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')



<style>
    .pt-question-page {
        max-width:1000px;
        margin:0px auto;
        padding:0 20px 60px;
    }

    .pt-question-header {
        margin-bottom:20px;
    }

    .pt-question-header h1 {
        margin:0 0 6px;
        color:#111827;
    }

    .pt-question-header p {
        margin:0;
        color:#6b7280;
    }

    .pt-progress {
        height:9px;
        background:#e5e7eb;
        border-radius:999px;
        overflow:hidden;
        margin:20px 0;
    }

    .pt-progress div {
        height:100%;
        background:#111827;
    }

    .pt-navigation {
        display:flex;
        flex-wrap:wrap;
        gap:7px;
        margin-bottom:25px;
    }

    .pt-navigation a {
        width:38px;
        height:38px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:8px;
        text-decoration:none;
        color:#374151;
        background:#f3f4f6;
        font-weight:700;
    }

    .pt-navigation a.active {
        background:#111827;
        color:white;
    }

    .pt-navigation a.answered {
        border:2px solid #111827;
    }

    .pt-card {
        background:white;
        border:1px solid #e5e7eb;
        border-radius:18px;
        padding:30px;
    }

    .pt-question-number {
        color:#4f46e5;
        font-weight:700;
        font-size:14px;
        margin-bottom:10px;
    }

    .pt-card h2 {
        color:#111827;
        line-height:1.5;
        margin-top:0;
    }

    .pt-option {
        display:block;
        padding:15px;
        border:1px solid #d1d5db;
        border-radius:10px;
        margin-top:12px;
        cursor:pointer;
    }

    .pt-option:hover {
        border-color:#111827;
    }

    .pt-option input {
        margin-right:10px;
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

<div class="pt-question-page">

    <div class="pt-question-header">

        <h1>
            Cybersecurity Assessment
        </h1>

        <p>
            Question {{ $question }}
            of
            {{ $total }}
        </p>

    </div>

    <div class="pt-progress">

        <div
            style="
                width:{{ ($question / $total) * 100 }}%;
            "
        ></div>

    </div>

    <div style="
        margin-bottom:10px;
        color:#6b7280;
    ">

        Answered:
        <strong>
            {{ $answeredCount }}
        </strong>
        /
        {{ $total }}

    </div>

    <div class="pt-navigation">

        @foreach ($navigationQuestions as $navQuestion)

            @php
                $navAnswer = $attempt
                    ->answers
                    ->firstWhere(
                        'question_id',
                        $navQuestion->question_id
                    );
            @endphp

            <a
                href="{{ route('public-test.question', [
                    'attempt' => $attempt->id,
                    'question' => $navQuestion->question_order,
                ]) }}"
                class="
                    {{ $navQuestion->question_order == $question
                        ? 'active'
                        : '' }}
                    {{ $navAnswer ? 'answered' : '' }}
                "
            >
                {{ $navQuestion->question_order }}
            </a>

        @endforeach

    </div>

    <div class="pt-card">

        <div class="pt-question-number">
            Question {{ $question }}
        </div>

        <h2>
            {{ $currentQuestion->question }}
        </h2>

        @if ($currentQuestion->category)

            <div style="
                margin-bottom:20px;
                color:#6b7280;
                font-size:14px;
            ">
                Category:
                {{ $currentQuestion->category->name }}
            </div>

        @endif

        <form
            method="POST"
            action="{{ route('public-test.answer', [
                'attempt' => $attempt->id,
                'question' => $question,
            ]) }}"
        >

            @csrf

            @foreach ($currentQuestion->options as $option)

                <label class="pt-option">

                    <input
                        type="radio"
                        name="question_option_id"
                        value="{{ $option->id }}"
                        {{ $answer &&
                           (int) $answer->question_option_id ===
                           (int) $option->id
                            ? 'checked'
                            : '' }}
                        required
                    >

                    <strong>
                        {{ $option->option_letter }}.
                    </strong>

                    {{ $option->option_text }}

                </label>

            @endforeach

            <div class="pt-actions">

                @if ($question > 1)

                    <a
                        href="{{ route('public-test.question', [
                            'attempt' => $attempt->id,
                            'question' => $question - 1,
                        ]) }}"
                        class="pt-button secondary"
                    >
                        Previous
                    </a>

                @else

                    <span></span>

                @endif

                <button
                    type="submit"
                    class="pt-button"
                >
                    {{ $question < $total
                        ? 'Save & Next'
                        : 'Save & Review' }}
                </button>

            </div>

        </form>

        <div style="
            margin-top:25px;
            padding-top:20px;
            border-top:1px solid #e5e7eb;
            text-align:center;
        ">

            <a
                href="{{ route('public-test.review', $attempt) }}"
                style="
                    color:#4f46e5;
                    font-weight:700;
                    text-decoration:none;
                "
            >
                Preview / Review All Questions
            </a>

        </div>

    </div>

</div>

@endsection
