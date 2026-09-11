```blade
@extends('layouts.app')

@section('content')

<div class="dashboard-container">

    {{-- Dashboard Header --}}
    <div class="dashboard-header">

        <div>

            <h1 class="dashboard-title">
                Learning Plans
            </h1>

            <p class="dashboard-description">
                Personalised cybersecurity learning plans for
                {{ $employee->name }}.
            </p>

        </div>

        <div class="dashboard-date">
            {{ $employee->department?->name ?? 'Department' }}
        </div>

    </div>


    {{-- Back Navigation --}}
    <div style="margin-bottom: 24px;">

        <a href="{{ route('manager.risk-dashboard') }}">
            ← Back to Risk Dashboard
        </a>

    </div>


    {{-- Employee Overview --}}
    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    {{ $employee->name }}
                </h2>

                <p>
                    Review the employee's current cybersecurity
                    learning-plan progress.
                </p>

            </div>

        </div>

    </div>


    {{-- Statistics --}}
    <div class="stats-grid">

        <div class="stat-card">

            <div class="stat-card-label">
                Total Plans
            </div>

            <div class="stat-card-value">
                {{ $totalPlans }}
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-card-label">
                Not Started
            </div>

            <div class="stat-card-value">
                {{ $notStartedPlans }}
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-card-label">
                In Progress
            </div>

            <div class="stat-card-value">
                {{ $inProgressPlans }}
            </div>

        </div>


        <div class="stat-card">

            <div class="stat-card-label">
                Completed
            </div>

            <div class="stat-card-value">
                {{ $completedPlans }}
            </div>

        </div>

    </div>


    {{-- Overall Progress --}}
    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    Overall Learning Progress
                </h2>

                <p>
                    Average progress across all learning plans.
                </p>

            </div>

            <strong>
                {{ $averageProgress }}%
            </strong>

        </div>


        <div class="dashboard-progress">

            <div
                style="width: {{ $averageProgress }}%;"
            ></div>

        </div>

    </div>


    {{-- Learning Plans --}}
    <div class="overview-card">

        <div class="section-heading">

            <div>

                <h2>
                    Learning Plans
                </h2>

                <p>
                    AI-generated recommendations based on the
                    employee's assessment results.
                </p>

            </div>

        </div>


        @if($learningPlans->count())

            <div class="action-grid">

                @foreach($learningPlans as $plan)

                    <div class="action-card">

                        <div style="
                            display: flex;
                            justify-content: space-between;
                            gap: 16px;
                            align-items: flex-start;
                            margin-bottom: 14px;
                        ">

                            <div>

                                <h3 style="margin-bottom: 6px;">
                                    {{ $plan->title }}
                                </h3>

                                @if($plan->assessmentAttempt?->assessment)

                                    <div style="
                                        font-size: 13px;
                                        opacity: .7;
                                    ">
                                        Assessment:
                                        {{ $plan->assessmentAttempt->assessment->name }}
                                    </div>

                                @endif

                            </div>


                            <span class="status-badge">
                                {{ ucfirst($plan->priority) }}
                            </span>

                        </div>


                        @if($plan->description)

                            <p style="
                                margin-bottom: 18px;
                                line-height: 1.6;
                            ">
                                {{ $plan->description }}
                            </p>

                        @endif


                        {{-- Status --}}
                        <div style="
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 8px;
                        ">

                            <span style="font-size: 14px;">
                                Status
                            </span>

                            <strong style="font-size: 14px;">
                                {{ ucwords(str_replace('_', ' ', $plan->status)) }}
                            </strong>

                        </div>


                        {{-- Progress --}}
                        <div style="
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            margin-bottom: 6px;
                        ">

                            <span style="font-size: 14px;">
                                Progress
                            </span>

                            <strong style="font-size: 14px;">
                                {{ $plan->progress_percentage }}%
                            </strong>

                        </div>


                        <div class="dashboard-progress">

                            <div
                                style="
                                    width: {{ $plan->progress_percentage }}%;
                                "
                            ></div>

                        </div>


                        {{-- Due Date --}}
                        <div style="
                            margin-top: 16px;
                            font-size: 14px;
                        ">

                            @if($plan->due_date)

                                <strong>
                                    Due:
                                </strong>

                                {{ $plan->due_date->format('d M Y') }}

                                @if(
                                    $plan->status !== 'completed' &&
                                    $plan->due_date->isPast()
                                )

                                    <span class="status-badge">
                                        Overdue
                                    </span>

                                @endif

                            @else

                                No due date

                            @endif

                        </div>


                        {{-- Completion --}}
                        @if($plan->completed_at)

                            <div style="
                                margin-top: 8px;
                                font-size: 14px;
                                opacity: .75;
                            ">

                                Completed:
                                {{ $plan->completed_at->format('d M Y') }}

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        @else

            <div style="
                padding: 24px;
                text-align: center;
                opacity: .7;
            ">

                <p>
                    No learning plans have been generated for
                    {{ $employee->name }} yet.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection
```
