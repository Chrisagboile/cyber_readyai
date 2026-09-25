@extends('layouts.app')

@section('content')

<style>
    /* =========================================================
       MANAGER LEARNING PLANS
    ========================================================= */

    .learning-plans-page {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 36px 34px 60px;
        box-sizing: border-box;
    }

    .learning-plans-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 30px;
        margin-bottom: 26px;
    }

    .learning-plans-title {
        margin: 0;
        font-size: 34px;
        line-height: 1.15;
        font-weight: 800;
        color: #12213f;
        letter-spacing: -0.5px;
    }

    .learning-plans-description {
        margin: 10px 0 0;
        font-size: 16px;
        line-height: 1.6;
        color: #5f6b82;
    }

    .learning-plans-department {
        flex-shrink: 0;
        padding: 10px 15px;
        border-radius: 10px;
        background: #eef5ff;
        border: 1px solid #d8e8ff;
        color: #245a9f;
        font-size: 14px;
        font-weight: 700;
    }

    /* Back link */

    .learning-plans-back {
        margin-bottom: 25px;
    }

    .learning-plans-back a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        text-decoration: none;
        color: #2563eb;
        font-size: 14px;
        font-weight: 700;
    }

    .learning-plans-back a:hover {
        text-decoration: underline;
    }

    /* Cards */

    .learning-card {
        background: #ffffff;
        border: 1px solid #e4e9f2;
        border-radius: 16px;
        box-shadow: 0 5px 18px rgba(18, 33, 63, 0.06);
    }

    .learning-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        padding: 24px 26px 20px;
    }

    .learning-card-title {
        margin: 0;
        color: #142441;
        font-size: 21px;
        line-height: 1.3;
        font-weight: 750;
    }

    .learning-card-description {
        margin: 7px 0 0;
        color: #69758a;
        font-size: 14px;
        line-height: 1.55;
    }

    /* Employee overview */

    .employee-overview {
        margin-bottom: 25px;
    }

    .employee-overview-content {
        padding: 26px;
    }

    .employee-name {
        margin: 0 0 8px;
        font-size: 24px;
        line-height: 1.3;
        font-weight: 750;
        color: #142441;
    }

    .employee-description {
        margin: 0;
        color: #667085;
        font-size: 15px;
        line-height: 1.6;
    }

    /* Statistics */

    .learning-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .learning-stat {
        background: #ffffff;
        border: 1px solid #e4e9f2;
        border-radius: 16px;
        padding: 22px 22px 20px;
        box-shadow: 0 5px 18px rgba(18, 33, 63, 0.05);
    }

    .learning-stat-label {
        margin-bottom: 10px;
        color: #6b768a;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .learning-stat-value {
        color: #142441;
        font-size: 30px;
        line-height: 1;
        font-weight: 800;
    }

    .learning-stat-total {
        border-top: 4px solid #2563eb;
    }

    .learning-stat-not-started {
        border-top: 4px solid #94a3b8;
    }

    .learning-stat-progress {
        border-top: 4px solid #f59e0b;
    }

    .learning-stat-completed {
        border-top: 4px solid #16a34a;
    }

    /* Overall progress */

    .overall-progress-card {
        margin-bottom: 25px;
    }

    .overall-progress-content {
        padding: 26px;
    }

    .overall-progress-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 25px;
        margin-bottom: 18px;
    }

    .overall-progress-title {
        margin: 0;
        color: #142441;
        font-size: 21px;
        line-height: 1.3;
        font-weight: 750;
    }

    .overall-progress-description {
        margin: 7px 0 0;
        color: #69758a;
        font-size: 14px;
    }

    .overall-progress-value {
        flex-shrink: 0;
        color: #2563eb;
        font-size: 27px;
        line-height: 1;
        font-weight: 800;
    }

    /* Progress bars */

    .learning-progress-track {
        width: 100%;
        height: 11px;
        overflow: hidden;
        background: #e9eef5;
        border-radius: 999px;
    }

    .learning-progress-fill {
        height: 100%;
        border-radius: inherit;
        background: #2563eb;
        transition: width 0.25s ease;
    }

    /* Plans section */

    .plans-card {
        overflow: hidden;
    }

    .plans-card-heading {
        padding: 26px 26px 18px;
        border-bottom: 1px solid #edf0f5;
    }

    .plans-card-title {
        margin: 0;
        color: #142441;
        font-size: 22px;
        font-weight: 750;
    }

    .plans-card-description {
        margin: 7px 0 0;
        color: #69758a;
        font-size: 14px;
        line-height: 1.55;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        padding: 26px;
    }

    /* Individual plan */

    .plan-card {
        background: #fbfcfe;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        transition:
            box-shadow 0.2s ease,
            transform 0.2s ease,
            border-color 0.2s ease;
    }

    .plan-card:hover {
        transform: translateY(-2px);
        border-color: #cbd8ea;
        box-shadow: 0 8px 24px rgba(18, 33, 63, 0.08);
    }

    .plan-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 16px;
    }

    .plan-title {
        margin: 0;
        color: #162746;
        font-size: 18px;
        line-height: 1.4;
        font-weight: 750;
    }

    .plan-assessment {
        margin-top: 7px;
        color: #718096;
        font-size: 13px;
        line-height: 1.45;
    }

    /* Badges */

    .plan-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .priority-high {
        background: #fee2e2;
        color: #b91c1c;
    }

    .priority-medium {
        background: #fef3c7;
        color: #a16207;
    }

    .priority-low {
        background: #dcfce7;
        color: #166534;
    }

    .priority-default {
        background: #eef2f7;
        color: #475569;
    }

    .overdue-badge {
        margin-left: 8px;
        background: #fee2e2;
        color: #b91c1c;
    }

    /* Description */

    .plan-description {
        margin: 0 0 20px;
        color: #59667a;
        font-size: 14px;
        line-height: 1.7;
    }

    /* Status rows */

    .plan-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .plan-meta-label {
        color: #748094;
        font-weight: 600;
    }

    .plan-meta-value {
        color: #1e2e49;
        font-weight: 750;
        text-align: right;
    }

    .plan-progress {
        margin-top: 2px;
        margin-bottom: 18px;
    }

    .plan-progress-track {
        width: 100%;
        height: 9px;
        overflow: hidden;
        background: #e5ebf3;
        border-radius: 999px;
    }

    .plan-progress-fill {
        height: 100%;
        background: #2563eb;
        border-radius: inherit;
    }

    /* Date information */

    .plan-date {
        padding-top: 15px;
        border-top: 1px solid #e5eaf1;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .plan-date strong {
        color: #334155;
    }

    .plan-completed {
        margin-top: 6px;
        color: #166534;
        font-weight: 600;
    }

    /* Empty state */

    .plans-empty {
        padding: 55px 26px;
        text-align: center;
    }

    .plans-empty-icon {
        width: 54px;
        height: 54px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eef4ff;
        color: #2563eb;
        font-size: 24px;
    }

    .plans-empty-title {
        margin: 0 0 7px;
        color: #1b2b48;
        font-size: 18px;
        font-weight: 750;
    }

    .plans-empty-text {
        margin: 0;
        color: #718096;
        font-size: 14px;
    }

    /* Responsive */

    @media (max-width: 1100px) {
        .learning-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .plans-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 760px) {
        .learning-plans-page {
            padding: 26px 20px 45px;
        }

        .learning-plans-header {
            flex-direction: column;
            gap: 15px;
        }

        .learning-plans-title {
            font-size: 30px;
        }

        .learning-plans-department {
            align-self: flex-start;
        }

        .learning-stats {
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .learning-stat {
            padding: 19px;
        }

        .learning-stat-value {
            font-size: 26px;
        }

        .overall-progress-heading {
            flex-direction: column;
            gap: 12px;
        }

        .plans-grid {
            padding: 18px;
        }

        .plan-top {
            flex-direction: column;
            gap: 12px;
        }

        .plan-badge {
            align-self: flex-start;
        }
    }

    @media (max-width: 520px) {
        .learning-plans-page {
            padding: 22px 15px 40px;
        }

        .learning-stats {
            grid-template-columns: 1fr;
        }

        .learning-card-header,
        .employee-overview-content,
        .overall-progress-content,
        .plans-card-heading {
            padding: 21px;
        }

        .plans-grid {
            padding: 14px;
        }

        .plan-card {
            padding: 18px;
        }

        .learning-plans-title {
            font-size: 27px;
        }
    }
</style>


<div class="learning-plans-page">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="learning-plans-header">

        <div>

            <h1 class="learning-plans-title">
                Learning Plans
            </h1>

            <p class="learning-plans-description">
                Personalised cybersecurity learning plans for
                {{ $employee->name }}.
            </p>

        </div>

        <div class="learning-plans-department">
            {{ $employee->department?->name ?? 'Department' }}
        </div>

    </div>


    {{-- =====================================================
         BACK NAVIGATION
    ====================================================== --}}

    <div class="learning-plans-back">

        <a href="{{ route('manager.risk-dashboard') }}">
            <span>←</span>
            <span>Back to Risk Dashboard</span>
        </a>

    </div>


    {{-- =====================================================
         EMPLOYEE OVERVIEW
    ====================================================== --}}

    <div class="learning-card employee-overview">

        <div class="employee-overview-content">

            <h2 class="employee-name">
                {{ $employee->name }}
            </h2>

            <p class="employee-description">
                Review the employee's current cybersecurity
                learning-plan progress.
            </p>

        </div>

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="learning-stats">

        <div class="learning-stat learning-stat-total">

            <div class="learning-stat-label">
                Total Plans
            </div>

            <div class="learning-stat-value">
                {{ $totalPlans }}
            </div>

        </div>


        <div class="learning-stat learning-stat-not-started">

            <div class="learning-stat-label">
                Not Started
            </div>

            <div class="learning-stat-value">
                {{ $notStartedPlans }}
            </div>

        </div>


        <div class="learning-stat learning-stat-progress">

            <div class="learning-stat-label">
                In Progress
            </div>

            <div class="learning-stat-value">
                {{ $inProgressPlans }}
            </div>

        </div>


        <div class="learning-stat learning-stat-completed">

            <div class="learning-stat-label">
                Completed
            </div>

            <div class="learning-stat-value">
                {{ $completedPlans }}
            </div>

        </div>

    </div>


    {{-- =====================================================
         OVERALL PROGRESS
    ====================================================== --}}

    <div class="learning-card overall-progress-card">

        <div class="overall-progress-content">

            <div class="overall-progress-heading">

                <div>

                    <h2 class="overall-progress-title">
                        Overall Learning Progress
                    </h2>

                    <p class="overall-progress-description">
                        Average progress across all learning plans.
                    </p>

                </div>

                <div class="overall-progress-value">
                    {{ $averageProgress }}%
                </div>

            </div>


            <div class="learning-progress-track">

                <div
                    class="learning-progress-fill"
                    style="width: {{ max(0, min(100, (int) $averageProgress)) }}%;"
                ></div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LEARNING PLANS
    ====================================================== --}}

    <div class="learning-card plans-card">

        <div class="plans-card-heading">

            <h2 class="plans-card-title">
                Learning Plans
            </h2>

            <p class="plans-card-description">
                AI-generated recommendations based on the
                employee's assessment results.
            </p>

        </div>


        @if($learningPlans->count())

            <div class="plans-grid">

                @foreach($learningPlans as $plan)

                    @php

                        $progress = max(
                            0,
                            min(
                                100,
                                (int) ($plan->progress_percentage ?? 0)
                            )
                        );

                        $priority = strtolower(
                            (string) ($plan->priority ?? 'medium')
                        );

                        $priorityClass = match ($priority) {
                            'high' => 'priority-high',
                            'low' => 'priority-low',
                            'medium' => 'priority-medium',
                            default => 'priority-default',
                        };

                    @endphp

                    <article class="plan-card">

                        {{-- Plan header --}}

                        <div class="plan-top">

                            <div>

                                <h3 class="plan-title">
                                    {{ $plan->title }}
                                </h3>


                                @if($plan->assessmentAttempt?->assessment)

                                    <div class="plan-assessment">

                                        Assessment:
                                        <strong>
                                            {{ $plan->assessmentAttempt->assessment->name }}
                                        </strong>

                                    </div>

                                @endif

                            </div>


                            <span class="plan-badge {{ $priorityClass }}">
                                {{ ucfirst($priority) }}
                            </span>

                        </div>


                        {{-- Description --}}

                        @if($plan->description)

                            <p class="plan-description">
                                {{ $plan->description }}
                            </p>

                        @endif


                        {{-- Status --}}

                        <div class="plan-meta-row">

                            <span class="plan-meta-label">
                                Status
                            </span>

                            <span class="plan-meta-value">
                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $plan->status
                                    )
                                ) }}
                            </span>

                        </div>


                        {{-- Progress label --}}

                        <div class="plan-meta-row">

                            <span class="plan-meta-label">
                                Progress
                            </span>

                            <span class="plan-meta-value">
                                {{ $progress }}%
                            </span>

                        </div>


                        {{-- Progress bar --}}

                        <div class="plan-progress">

                            <div class="plan-progress-track">

                                <div
                                    class="plan-progress-fill"
                                    style="width: {{ $progress }}%;"
                                ></div>

                            </div>

                        </div>


                        {{-- Due date --}}

                        <div class="plan-date">

                            @if($plan->due_date)

                                <div>

                                    <strong>
                                        Due:
                                    </strong>

                                    {{ $plan->due_date->format('d M Y') }}

                                    @if(
                                        $plan->status !== 'completed' &&
                                        $plan->due_date->isPast()
                                    )

                                        <span class="plan-badge overdue-badge">
                                            Overdue
                                        </span>

                                    @endif

                                </div>

                            @else

                                <div>
                                    No due date
                                </div>

                            @endif


                            @if($plan->completed_at)

                                <div class="plan-completed">

                                    Completed:
                                    {{ $plan->completed_at->format('d M Y') }}

                                </div>

                            @endif

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="plans-empty">

                <div class="plans-empty-icon">
                    ✓
                </div>

                <h3 class="plans-empty-title">
                    No Learning Plans
                </h3>

                <p class="plans-empty-text">
                    No learning plans have been generated for
                    {{ $employee->name }} yet.
                </p>

            </div>

        @endif

    </div>

</div>

@endsection