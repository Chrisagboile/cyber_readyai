@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')


<style>
    .pt-result {
        max-width:1000px;
        margin:0px auto;
        padding:0 20px 60px;
    }

    .pt-hero {
        background:#111827;
        color:white;
        border-radius:20px;
        padding:40px;
        margin-bottom:22px;
    }

    .pt-hero h1 {
        margin:0 0 8px;
        font-size:34px;
    }

    .pt-hero p {
        margin:0;
        color:#d1d5db;
    }

    .pt-score-grid {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:15px;
        margin-top:25px;
    }

    .pt-score-card {
        padding:20px;
        border-radius:13px;
        background:rgba(255,255,255,.08);
    }

    .pt-score-card span {
        display:block;
        color:#d1d5db;
        font-size:13px;
        margin-bottom:5px;
    }

    .pt-score-card strong {
        font-size:27px;
    }

    .pt-card {
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:25px;
        margin-bottom:20px;
    }

    .pt-category {
        margin-bottom:18px;
    }

    .pt-category-header {
        display:flex;
        justify-content:space-between;
        margin-bottom:7px;
    }

    .pt-bar {
        height:9px;
        background:#e5e7eb;
        border-radius:999px;
        overflow:hidden;
    }

    .pt-bar div {
        height:100%;
        background:#111827;
    }

    .pt-actions {
        display:flex;
        justify-content:center;
        gap:12px;
        flex-wrap:wrap;
    }

    .pt-button {
        display:inline-block;
        padding:13px 20px;
        border-radius:9px;
        background:#111827;
        color:white;
        text-decoration:none;
        font-weight:700;
    }

    .pt-button.secondary {
        background:#f3f4f6;
        color:#374151;
    }

    @media(max-width:700px) {
        .pt-score-grid {
            grid-template-columns:1fr;
        }
    }
</style>

@php
    $score = (float) $attempt->score_percentage;
@endphp

<div class="pt-result">

    <div class="pt-hero">

        <h1>
            Your Cybersecurity Test Result
        </h1>

        <p>
            Your public CyberReadyAI assessment has been completed.
        </p>

        <div class="pt-score-grid">

            <div class="pt-score-card">

                <span>
                    Score
                </span>

                <strong>
                    {{ number_format($score, 1) }}%
                </strong>

            </div>

            <div class="pt-score-card">

                <span>
                    Correct Answers
                </span>

                <strong>
                    {{ $attempt->correct_answers }}
                </strong>

            </div>

            <div class="pt-score-card">

                <span>
                    Risk Level
                </span>

                <strong>
                    {{ ucfirst($attempt->risk_level ?? 'N/A') }}
                </strong>

            </div>

        </div>

    </div>

    @if (session('success'))

        <div style="
            margin-bottom:20px;
            padding:14px;
            border-radius:10px;
            background:#ecfdf5;
            color:#065f46;
        ">
            {{ session('success') }}
        </div>

    @endif

    <div class="pt-card">

        <h2>
            Test Summary
        </h2>

        <p style="color:#6b7280;">

            You answered

            <strong>
                {{ $attempt->answered_questions }}
            </strong>

            of

            <strong>
                {{ $attempt->total_questions }}
            </strong>

            questions.

        </p>

    </div>

    <div class="pt-card">

        <h2>
            Category Performance
        </h2>

        <p style="color:#6b7280;">
            Your performance across the categories covered by this test.
        </p>

        @forelse ($categoryResults as $result)

            @php
                $category = $result['category'];
                $percentage = (float) $result['percentage'];
            @endphp

            <div class="pt-category">

                <div class="pt-category-header">

                    <strong>
                        {{ $category->name }}
                    </strong>

                    <span>
                        {{ number_format($percentage, 1) }}%
                    </span>

                </div>

                <div class="pt-bar">

                    <div
                        style="width:{{ max(
                            0,
                            min(
                                100,
                                $percentage
                            )
                        ) }}%;"
                    ></div>

                </div>

            </div>

        @empty

            <p>
                No category information is available.
            </p>

        @endforelse

    </div>

    <div class="pt-card">

        <h2>
            Learning Plan
        </h2>

        @foreach ($attempt->learningPlans as $plan)

            <div style="
                padding:15px;
                background:#f9fafb;
                border-radius:10px;
                margin-top:12px;
            ">

                <strong>
                    {{ $plan->title }}
                </strong>

                <p style="color:#6b7280;">
                    {{ $plan->description }}
                </p>

                <strong>
                    Priority:
                </strong>

                {{ ucfirst($plan->priority) }}

            </div>

        @endforeach

    </div>

    <div class="pt-actions">

        <a
            href="{{ route(
                'public-test.feedback',
                $attempt
            ) }}"
            class="pt-button"
        >
            Leave Feedback
        </a>

        <a
            href="{{ route(
                'public-test.dashboard'
            ) }}"
            class="pt-button secondary"
        >
            Return to Dashboard
        </a>

    </div>

</div>

@endsection
