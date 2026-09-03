@extends('layouts.app')

@section('title', 'My Assessments')

@section('content')

<div class="dashboard-header">

    <div>
        <h1 class="dashboard-title">
            My Assessments
        </h1>

        <p class="dashboard-description">
            Complete your assigned cybersecurity
            assessments and review your results.
        </p>
    </div>

    <div class="dashboard-date">
        {{ now()->format('d M Y') }}
    </div>

</div>

<div class="section-heading">
    <div>
        <h2>Assigned Assessments</h2>

        <p>
            Assessments assigned to your account.
        </p>
    </div>
</div>

<div class="action-grid">

@forelse($assessments as $assessment)

    @php
        $attempt = $assessment->attempts->first();
    @endphp

    <div class="overview-card">

        <div class="overview-card-header">

            <div>
                <h3 class="overview-card-title">
                    {{ $assessment->name }}
                </h3>

                <p class="overview-description">
                    {{ $assessment->total_questions }}
                    questions ·
                    {{ $assessment->duration_minutes }}
                    minutes
                </p>
            </div>

            @if(!$attempt)

                <span class="status-badge warning">
                    Not Started
                </span>

            @elseif($attempt->status === 'in_progress')

                <span class="status-badge warning">
                    In Progress
                </span>

            @elseif($attempt->status === 'completed')

                <span class="status-badge success">
                    Completed
                </span>

            @elseif($attempt->status === 'expired')

                <span class="status-badge">
                    Expired
                </span>

            @endif

        </div>

        @if($attempt)

            @if($attempt->status === 'in_progress')

                <div class="dashboard-progress">

                    <div class="progress-label">
                        <span>
                            Progress
                        </span>

                        <span>
                            {{ $attempt->answered_questions }}
                            /
                            {{ $attempt->total_questions }}
                        </span>
                    </div>

                    <div class="dashboard-progress-bar">
                        <div
                            style="
                                width:
                                {{ $attempt->total_questions > 0
                                    ? ($attempt->answered_questions / $attempt->total_questions) * 100
                                    : 0
                                }}%;
                            "
                        ></div>
                    </div>

                </div>

                <a
                    href="{{ route(
                        'assessment.start',
                        $assessment
                    ) }}"
                    class="action-button"
                >
                    Resume Assessment
                </a>

            @elseif($attempt->status === 'completed')

                <div class="assessment-score">

                    <strong>
                        {{ number_format(
                            $attempt->score_percentage,
                            2
                        ) }}%
                    </strong>

                    <span>
                        {{ $attempt->risk_level }}
                        Risk
                    </span>

                </div>

                <a
                    href="{{ route(
                        'assessment.result',
                        $attempt
                    ) }}"
                    class="action-button"
                >
                    View Result
                </a>

            @elseif($attempt->status === 'expired')

                <div class="assessment-score">
                    <strong>
                        {{ number_format(
                            $attempt->score_percentage,
                            2
                        ) }}%
                    </strong>

                    <span>
                        Assessment expired
                    </span>
                </div>

                <a
                    href="{{ route(
                        'assessment.result',
                        $attempt
                    ) }}"
                    class="action-button"
                >
                    View Result
                </a>

            @endif

        @else

            <a
                href="{{ route(
                    'assessment.start',
                    $assessment
                ) }}"
                class="action-button"
            >
                Start Assessment
            </a>

        @endif

    </div>

@empty

    <div class="overview-card">

        <div class="empty-state">

            <h3>
                No assessments assigned
            </h3>

            <p>
                You currently have no cybersecurity
                assessments assigned to you.
            </p>

        </div>

    </div>

@endforelse

</div>

<div class="pagination-wrapper">
    {{ $assessments->links() }}
</div>

@endsection
