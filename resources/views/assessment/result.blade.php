@extends('layouts.app')

@section('title', 'Assessment Result')

@section('page-title', 'Assessment Result')

@section('content')

<div class="assessment-wrapper">

    <div class="assessment-card">

        <div class="text-center">

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
                    <span>Correct Answers</span>
                </div>

                <div>
                    <strong>
                        {{ $attempt->total_questions }}
                    </strong>
                    <span>Total Questions</span>
                </div>

                <div>
                    <strong>
                        {{ $attempt->risk_level }}
                    </strong>
                    <span>Risk Level</span>
                </div>

            </div>

        </div>

        <div class="result-message">

            @if($attempt->risk_level === 'Low')

                <h3>Low Cybersecurity Risk</h3>

                <p>
                    Your assessment indicates a strong level
                    of cybersecurity awareness.
                </p>

            @elseif($attempt->risk_level === 'Medium')

                <h3>Medium Cybersecurity Risk</h3>

                <p>
                    Some areas of cybersecurity awareness may
                    benefit from additional training.
                </p>

            @else

                <h3>High Cybersecurity Risk</h3>

                <p>
                    Additional cybersecurity awareness training
                    is recommended.
                </p>

            @endif

        </div>

        @if($insight)

            <div class="section-heading">
                <h2>AI Assessment Insight</h2>
            </div>

            <div class="overview-card">

                <h3>Your Assessment Summary</h3>

                <p>
                    {{ $insight->summary }}
                </p>

            </div>

            @if(!empty($insight->strengths))

                <div class="section-heading">
                    <h2>Your Strengths</h2>
                </div>

                <div class="action-grid">

                    @foreach($insight->strengths as $strength)

                        <div class="action-card">

                            <h3>
                                {{ $strength }}
                            </h3>

                            <p>
                                You demonstrated strong
                                cybersecurity awareness in this area.
                            </p>

                        </div>

                    @endforeach

                </div>

            @endif

            @if(!empty($insight->priority_areas))

                <div class="section-heading">
                    <h2>Priority Improvement Areas</h2>
                </div>

                <div class="action-grid">

                    @foreach($insight->priority_areas as $area)

                        <div class="action-card">

                            <h3>
                                {{ $area['category'] }}
                            </h3>

                            <p>
                                Current score:
                                <strong>
                                    {{ $area['score'] }}%
                                </strong>
                            </p>

                            <span class="status-badge">
                                {{ ucfirst($area['priority']) }} Priority
                            </span>

                        </div>

                    @endforeach

                </div>

            @endif

            @if(!empty($insight->recommendations))

                <div class="section-heading">
                    <h2>Recommended Actions</h2>
                </div>

                <div class="action-grid">

                    @foreach($insight->recommendations as $recommendation)

                        <div class="action-card">

                            <h3>
                                {{ $recommendation['title'] }}
                            </h3>

                            <p>
                                {{ $recommendation['reason'] }}
                            </p>

                            <span class="status-badge">
                                {{ ucfirst($recommendation['priority']) }}
                                Priority
                            </span>

                        </div>

                    @endforeach

                </div>

            @endif

        @endif

    </div>

</div>

@endsection
