@extends('layouts.app')

@section('title', 'Learning Plans')

@section('content')

<div class="dashboard-header">
    <div>
        <h1 class="dashboard-title">Learning Plans</h1>
        <p class="dashboard-description">
            Your personalised cybersecurity learning recommendations based on your assessment results.
        </p>
    </div>

    <div class="dashboard-date">
        {{ now()->format('F j, Y') }}
    </div>
</div>

@if($insights->isEmpty())

    <div class="overview-card">
        <div class="section-heading">
            <div>
                <h2>Your Learning Plan</h2>
                <p>Complete an assessment to receive personalised learning recommendations.</p>
            </div>
        </div>

        <div class="empty-state">
            <h3>No learning recommendations yet</h3>

            <p>
                Once you complete an assessment, CyberReadyAI will analyse your results
                and create personalised recommendations for you.
            </p>

            <a href="{{ route('assessment.index') }}" class="btn btn-primary">
                View My Assessments
            </a>
        </div>
    </div>

@else

    @foreach($insights as $insight)

        @php
            $attempt = $insight->assessmentAttempt;
            $assessment = $attempt?->assessment;
        @endphp

        <div class="overview-card" style="margin-bottom: 24px;">

            <div class="section-heading">
                <div>
                    <h2>{{ $assessment?->name ?? 'Cybersecurity Assessment' }}</h2>

                    <p>
                        Assessment completed
                        {{ $attempt?->completed_at?->format('F j, Y') ?? 'recently' }}
                    </p>
                </div>

                @if($attempt)
                    <span class="status-badge">
                        {{ number_format($attempt->score_percentage, 0) }}%
                        · {{ $attempt->risk_level }} Risk
                    </span>
                @endif
            </div>

            @if($insight->summary)
                <div style="margin-bottom: 24px;">
                    <h3>AI Assessment Summary</h3>

                    <p>
                        {{ $insight->summary }}
                    </p>
                </div>
            @endif

            @if(!empty($insight->priority_areas))
                <div style="margin-bottom: 24px;">
                    <h3>Priority Learning Areas</h3>

                    <div class="action-grid">

                        @foreach($insight->priority_areas as $area)

                            <div class="action-card">
                                <div class="action-card-icon">🎯</div>

                                <div>
                                    @if(is_array($area))
                                        <h3>{{ $area['title'] ?? $area['area'] ?? 'Learning Area' }}</h3>

                                        @if(!empty($area['description']))
                                            <p>{{ $area['description'] }}</p>
                                        @endif
                                    @else
                                        <h3>{{ $area }}</h3>
                                    @endif
                                </div>
                            </div>

                        @endforeach

                    </div>
                </div>
            @endif

            @if(!empty($insight->recommendations))
                <div style="margin-bottom: 24px;">
                    <h3>Recommended Learning Activities</h3>

                    <div class="action-grid">

                        @foreach($insight->recommendations as $recommendation)

                            <div class="action-card">
                                <div class="action-card-icon">📚</div>

                                <div>
                                    @if(is_array($recommendation))
                                        <h3>
                                            {{ $recommendation['title']
                                                ?? $recommendation['topic']
                                                ?? 'Recommended Learning' }}
                                        </h3>

                                        @if(!empty($recommendation['description']))
                                            <p>{{ $recommendation['description'] }}</p>
                                        @elseif(!empty($recommendation['reason']))
                                            <p>{{ $recommendation['reason'] }}</p>
                                        @endif
                                    @else
                                        <h3>{{ $recommendation }}</h3>
                                    @endif
                                </div>
                            </div>

                        @endforeach

                    </div>
                </div>
            @endif

            @if(!empty($insight->strengths))
                <div>
                    <h3>Your Strengths</h3>

                    <div class="action-grid">

                        @foreach($insight->strengths as $strength)

                            <div class="action-card">
                                <div class="action-card-icon">✓</div>

                                <div>
                                    @if(is_array($strength))
                                        <h3>
                                            {{ $strength['title']
                                                ?? $strength['area']
                                                ?? 'Strength' }}
                                        </h3>

                                        @if(!empty($strength['description']))
                                            <p>{{ $strength['description'] }}</p>
                                        @endif
                                    @else
                                        <h3>{{ $strength }}</h3>
                                    @endif
                                </div>
                            </div>

                        @endforeach

                    </div>
                </div>
            @endif

        </div>

    @endforeach

@endif

@endsection
