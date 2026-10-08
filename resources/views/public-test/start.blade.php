@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')


<style>
    .public-test-start {
        max-width: 900px;
        margin: 50px auto;
        padding: 0 20px 60px;
    }

    .public-test-hero {
        background: #111827;
        color: #fff;
        border-radius: 20px;
        padding: 44px;
        margin-bottom: 24px;
    }

    .public-test-hero span {
        display: inline-block;
        margin-bottom: 12px;
        color: #c7d2fe;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
    }

    .public-test-hero h1 {
        margin: 0 0 14px;
        font-size: 38px;
        line-height: 1.2;
    }

    .public-test-hero p {
        margin: 0;
        max-width: 700px;
        color: #d1d5db;
        line-height: 1.7;
    }

    .public-test-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .public-test-info {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 22px;
    }

    .public-test-info strong {
        display: block;
        margin-bottom: 7px;
        color: #111827;
        font-size: 18px;
    }

    .public-test-info span {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.6;
    }

    .public-test-start-actions {
        display: flex;
        justify-content: center;
        padding-top: 12px;
    }

    .public-test-start-button {
        display: inline-block;
        padding: 14px 28px;
        border-radius: 10px;
        background: #111827;
        color: #fff;
        text-decoration: none;
        font-weight: 700;
    }

    .public-test-start-button:hover {
        background: #1f2937;
    }

    @media (max-width: 750px) {
        .public-test-grid {
            grid-template-columns: 1fr;
        }

        .public-test-hero {
            padding: 30px 24px;
        }

        .public-test-hero h1 {
            font-size: 30px;
        }
    }
</style>

<div class="public-test-start">

    <div class="public-test-hero">

        <span>
            CyberReadyAI Public Assessment
        </span>

        <h1>
            Check Your Cybersecurity Readiness
        </h1>

        <p>
            You are about to take a 10-question cybersecurity awareness
            assessment. Your questions will be selected and stored when
            your test begins so the assessment remains consistent.
        </p>

    </div>


    <div class="public-test-grid">

        <div class="public-test-info">
            <strong>10 Questions</strong>
            <span>
                A focused cybersecurity awareness test.
            </span>
        </div>

        <div class="public-test-info">
            <strong>Random Selection</strong>
            <span>
                Questions are selected from the CyberReadyAI question bank.
            </span>
        </div>

        <div class="public-test-info">
            <strong>Instant Result</strong>
            <span>
                See your score, correct answers, risk level and category
                performance after submission.
            </span>
        </div>

    </div>


    <div class="public-test-start-actions">

        <a
            href="{{ route('public-test.start') }}"
            class="public-test-start-button"
        >
            Start Test
        </a>

    </div>

</div>

@endsection
