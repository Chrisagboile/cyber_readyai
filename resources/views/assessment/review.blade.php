@extends('layouts.app')

@section('title', 'Review Assessment')

@section('page-title', 'Review Assessment')

@section('content')

<div class="assessment-wrapper">

    <div class="assessment-card">

        <h1>
            Review Your Assessment
        </h1>

        <p>
            Please review your answers before submitting.
        </p>


        <div class="review-list">

            @foreach($questions as $index => $question)

                @php
                    $answer = $answers->get($question->id);
                @endphp

                <div class="review-item">

                    <div>

                        <strong>
                            Question {{ $index + 1 }}
                        </strong>

                        <p>
                            {{ $question->question }}
                        </p>

                        @if($answer)

                            <span>
                                Your answer:
                                <strong>
                                    {{ $answer->option->option_letter }}
                                </strong>
                            </span>

                        @else

                            <span style="color:#dc2626;">
                                Not answered
                            </span>

                        @endif

                    </div>


                    <a
                        href="{{ route(
                            'assessment.question',
                            [
                                'attempt' => $attempt->id,
                                'question' => $index + 1
                            ]
                        ) }}"
                        class="btn btn-secondary"
                    >
                        Review
                    </a>

                </div>

            @endforeach

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
                class="btn btn-primary"
                style="margin-top:25px;"
            >
                Submit Assessment
            </button>

        </form>

    </div>

</div>

@endsection
