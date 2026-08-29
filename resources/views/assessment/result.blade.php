@extends('layouts.app')

@section('title', 'Assessment Result')

@section('page-title', 'Assessment Result')

@section('content')

<div class="assessment-wrapper">

    <div class="assessment-card text-center">

        <h1>
            Assessment Complete
        </h1>

        <p>
            Your cybersecurity assessment has been completed.
        </p>


        <div class="result-score">

            {{ $attempt->score_percentage }}%

        </div>


        <div class="result-details">

            <div>
                <strong>
                    {{ $attempt->correct_answers }}
                </strong>

                <span>
                    Correct Answers
                </span>
            </div>


            <div>
                <strong>
                    {{ $attempt->total_questions }}
                </strong>

                <span>
                    Total Questions
                </span>
            </div>


            <div>
                <strong>
                    {{ $attempt->risk_level }}
                </strong>

                <span>
                    Risk Level
                </span>
            </div>

        </div>


        <div class="result-message">

            @if($attempt->risk_level === 'Low')

                <h3>
                    Low Cybersecurity Risk
                </h3>

                <p>
                    Your assessment indicates a strong level
                    of cybersecurity awareness.
                </p>

            @elseif($attempt->risk_level === 'Medium')

                <h3>
                    Medium Cybersecurity Risk
                </h3>

                <p>
                    Some areas of cybersecurity awareness
                    may benefit from additional training.
                </p>

            @else

                <h3>
                    High Cybersecurity Risk
                </h3>

                <p>
                    Additional cybersecurity awareness
                    training is recommended.
                </p>

            @endif

        </div>

    </div>

</div>

@endsection
