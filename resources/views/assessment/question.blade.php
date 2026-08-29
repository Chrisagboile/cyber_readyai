@extends('layouts.app')

@section('title', 'Cybersecurity Assessment')

@section('page-title', 'Cybersecurity Assessment')

@section('content')

<div class="assessment-wrapper">

    {{-- Header --}}
    <div class="assessment-header">

        <div>
            <h1>
                Cybersecurity Assessment
            </h1>

            <p>
                Question {{ $question }} of {{ $total }}
            </p>
        </div>

        <div class="assessment-progress">

            <span>
                {{ $answeredCount }} / {{ $total }} answered
            </span>

        </div>

    </div>


    {{-- Progress --}}
    <div class="progress-container">

        @php
            $progress = ($question / $total) * 100;
        @endphp

        <div
            class="progress-bar"
            style="width: {{ $progress }}%"
        ></div>

    </div>


    {{-- Question Card --}}
    <div class="assessment-card">

        {{-- Category --}}
        @if($currentQuestion->category)

            <div class="question-category">

                {{ $currentQuestion->category->name }}

            </div>

        @endif


        {{-- Difficulty --}}
        <div class="question-meta">

            <span>
                {{ $currentQuestion->difficulty }}
            </span>

            <span>
                Risk: {{ $currentQuestion->risk_level }}
            </span>

        </div>


        {{-- Question --}}
        <h2 class="question-title">

            {{ $currentQuestion->question }}

        </h2>


        {{-- Answers --}}
        <form
            method="POST"
            action="{{ route(
                'assessment.answer',
                [
                    'attempt' => $attempt->id,
                    'question' => $question
                ]
            ) }}"
        >

            @csrf


            <div class="answer-list">

                @foreach(
                    $currentQuestion->options
                    as $option
                )

                    <label
                        class="answer-option
                        {{ $answer &&
                           $answer->question_option_id
                           == $option->id
                           ? 'selected'
                           : '' }}"
                    >

                        <input
                            type="radio"
                            name="question_option_id"
                            value="{{ $option->id }}"

                            {{ $answer &&
                               $answer->question_option_id
                               == $option->id
                               ? 'checked'
                               : '' }}

                            required
                        >

                        <span class="option-letter">

                            {{ $option->option_letter }}

                        </span>

                        <span class="option-text">

                            {{ $option->option_text }}

                        </span>

                    </label>

                @endforeach

            </div>


            {{-- Navigation --}}
            <div class="assessment-navigation">

                @if($question > 1)

                    <a
                        href="{{ route(
                            'assessment.question',
                            [
                                'attempt' => $attempt->id,
                                'question' => $question - 1
                            ]
                        ) }}"
                        class="btn btn-secondary"
                    >
                        ← Previous
                    </a>

                @else

                    <span></span>

                @endif


                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    @if($question < $total)

                        Save & Next →

                    @else

                        Finish Assessment →

                    @endif

                </button>

            </div>

        </form>

    </div>

</div>

@endsection
