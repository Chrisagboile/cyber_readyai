```blade
@extends('layouts.app')

@section('title', 'Learning Plans')

@section('content')

<div class="dashboard-header">
    <div>
        <h1 class="dashboard-title">Learning Plans</h1>
        <p class="dashboard-description">
            Your personalised cybersecurity learning plans based on your assessment results.
        </p>
    </div>

    <div class="dashboard-date">
        {{ now()->format('F j, Y') }}
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Learning Plan Statistics --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-label">Total Plans</div>
        <div class="stat-value">{{ $totalPlans }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Not Started</div>
        <div class="stat-value">{{ $notStartedPlans }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">In Progress</div>
        <div class="stat-value">{{ $inProgressPlans }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Completed</div>
        <div class="stat-value">{{ $completedPlans }}</div>
    </div>

</div>

<div class="section-heading" style="margin-top: 32px;">
    <div>
        <h2>Your Learning Plans</h2>
        <p>
            Work through these personalised recommendations to improve your cybersecurity readiness.
        </p>
    </div>
</div>

@if($learningPlans->isEmpty())

    <div class="overview-card">

        <div class="empty-state">

            <h3>No learning plans yet</h3>

            <p>
                Complete a cybersecurity assessment to receive personalised learning plans
                based on your results.
            </p>

            <a href="{{ route('assessment.index') }}" class="btn btn-primary">
                View My Assessments
            </a>

        </div>

    </div>

@else

    <div class="action-grid">

        @foreach($learningPlans as $plan)

            <div class="overview-card">

                {{-- Plan Header --}}
                <div class="section-heading">

                    <div>
                        <h2>{{ $plan->title }}</h2>

                        @if($plan->assessmentAttempt?->assessment)
                            <p>
                                From:
                                {{ $plan->assessmentAttempt->assessment->name }}
                            </p>
                        @endif
                    </div>

                    <span class="status-badge">
                        {{ ucfirst($plan->priority) }} Priority
                    </span>

                </div>

                {{-- Description --}}
                @if($plan->description)

                    <div style="margin-bottom: 20px;">
                        <p>{{ $plan->description }}</p>
                    </div>

                @endif

                {{-- Progress --}}
                <div style="margin-bottom: 20px;">

                    <div style="
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 8px;
                    ">
                        <strong>Progress</strong>

                        <span>
                            {{ $plan->progress_percentage }}%
                        </span>
                    </div>

                    <div class="dashboard-progress">
                        <div
                            style="width: {{ $plan->progress_percentage }}%;"
                        ></div>
                    </div>

                </div>

                {{-- Status and Due Date --}}
                <div style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 20px;
                    margin-bottom: 20px;
                ">

                    <div>

                        <strong>Status:</strong>

                        {{ ucwords(str_replace('_', ' ', $plan->status)) }}

                        @if($plan->due_date)
                            <div style="margin-top: 5px; opacity: .7;">
                                Due {{ $plan->due_date->format('F j, Y') }}
                            </div>
                        @endif

                    </div>

                    @if($plan->status === 'not_started')

                        <form
                            method="POST"
                            action="{{ route('employee.learning-plans.status', $plan) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <input
                                type="hidden"
                                name="status"
                                value="in_progress"
                            >

                            <button type="submit" class="btn btn-primary">
                                Start Plan
                            </button>
                        </form>

                    @elseif($plan->status === 'in_progress')

                        <form
                            method="POST"
                            action="{{ route('employee.learning-plans.status', $plan) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <input
                                type="hidden"
                                name="status"
                                value="completed"
                            >

                            <button type="submit" class="btn btn-primary">
                                Mark Complete
                            </button>
                        </form>

                    @else

                        <span class="status-badge">
                            Completed
                        </span>

                    @endif

                </div>

            </div>

        @endforeach

    </div>

    {{-- Pagination --}}
    @if($learningPlans->hasPages())

        <div style="margin-top: 24px;">
            {{ $learningPlans->links() }}
        </div>

    @endif

@endif

@endsection
```
