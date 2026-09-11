@extends('layouts.app')

@section('title', 'My Score')

@section('content')

<div class="dashboard-header">

    <div>
        <h1 class="dashboard-title">
            My Score
        </h1>

        <p class="dashboard-description">
            Review your cybersecurity assessment scores and readiness history.
        </p>
    </div>

    <div class="dashboard-date">
        {{ now()->format('d M Y') }}
    </div>

</div>

<div class="section-heading">
    <div>
        <h2>Assessment Results</h2>

        <p>
            Your completed cybersecurity assessments.
        </p>
    </div>
</div>

<div class="action-grid">

@forelse($attempts as $attempt)

    <div class="overview-card">

        <div class="overview-card-header">

            <div>
                <h3 class="overview-card-title">
                    {{ $attempt->assessment?->name ?? 'Assessment' }}
                </h3>

                <p class="overview-description">
                    Completed
                    {{ $attempt->completed_at
                        ? $attempt->completed_at->format('d M Y')
                        : 'N/A'
                    }}
                </p>
            </div>

            @if($attempt->risk_level === 'Low')
                <span class="status-badge success">
                    Low Risk
                </span>
            @elseif($attempt->risk_level === 'Medium')
                <span class="status-badge warning">
                    Medium Risk
                </span>
            @else
                <span class="status-badge">
                    High Risk
                </span>
            @endif

        </div>

        <div class="assessment-score">

            <strong>
                {{ number_format($attempt->score_percentage, 2) }}%
            </strong>

            <span>
                {{ $attempt->correct_answers }}
                / 
                {{ $attempt->total_questions }}
                correct
            </span>

        </div>

        <a
            href="{{ route('assessment.result', $attempt) }}"
            class="action-button"
        >
            View Detailed Result
        </a>

    </div>

@empty

    <div class="overview-card">

        <div class="empty-state">

            <h3>
                No completed assessments
            </h3>

            <p>
                Complete your first cybersecurity assessment to see your score here.
            </p>

            <a
                href="{{ route('assessment.index') }}"
                class="action-button"
            >
                View Assessments
            </a>

        </div>

    </div>

@endforelse

</div>

<div class="pagination-wrapper">
    {{ $attempts->links() }}
</div>

@endsection