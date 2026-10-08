@extends('layouts.public-test')

@section('title', 'My Assessments')

@section('content')


<style>
    .pt-dashboard {
        max-width: 1100px;
        margin: 0px auto;
        padding: 0 20px 60px;
    }

    .pt-header {
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:20px;
        margin-bottom:25px;
    }

    .pt-header h1 {
        margin:0 0 6px;
        color:#111827;
    }

    .pt-header p {
        margin:0;
        color:#6b7280;
    }

    .pt-nav {
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        margin-bottom:25px;
    }

    .pt-nav a {
        padding:10px 14px;
        border-radius:9px;
        text-decoration:none;
        color:#374151;
        background:#f3f4f6;
        font-weight:600;
    }

    .pt-nav a.active,
    .pt-nav a:hover {
        background:#111827;
        color:white;
    }

    .pt-grid {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:15px;
        margin-bottom:25px;
    }

    .pt-card {
        background:white;
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:22px;
    }

    .pt-card span {
        display:block;
        color:#6b7280;
        font-size:13px;
        margin-bottom:7px;
    }

    .pt-card strong {
        font-size:26px;
        color:#111827;
    }

    .pt-main-card {
        background:white;
        border:1px solid #e5e7eb;
        border-radius:16px;
        padding:25px;
        margin-bottom:20px;
    }

    .pt-main-card h2 {
        margin:0 0 10px;
        color:#111827;
    }

    .pt-button {
        display:inline-block;
        padding:12px 18px;
        border-radius:9px;
        text-decoration:none;
        background:#111827;
        color:white;
        font-weight:700;
    }

    .pt-button.secondary {
        background:#f3f4f6;
        color:#374151;
    }

    .pt-table {
        width:100%;
        border-collapse:collapse;
    }

    .pt-table th,
    .pt-table td {
        padding:12px;
        text-align:left;
        border-bottom:1px solid #e5e7eb;
    }

    .pt-table th {
        color:#6b7280;
        font-size:13px;
    }

    @media(max-width:700px) {
        .pt-grid {
            grid-template-columns:1fr;
        }

        .pt-header {
            flex-direction:column;
            align-items:flex-start;
        }

        .pt-table {
            font-size:13px;
        }
    }
</style>

<div class="pt-dashboard">

    <div class="pt-header">

        <div>
            <h1>
                Public Test Dashboard
            </h1>

            <p>
                Welcome, {{ $user->name }}.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('public-test.logout') }}"
        >
            @csrf

            <button
                type="submit"
                class="pt-button secondary"
            >
                Logout
            </button>
        </form>

    </div>

    <nav class="pt-nav">

        <a
            href="{{ route('public-test.dashboard') }}"
            class="active"
        >
            Dashboard
        </a>

        <a
            href="{{ route('public-test.assessments') }}"
        >
            Assessments
        </a>

        <a
            href="{{ route('public-test.learning') }}"
        >
            My Learning
        </a>

    </nav>

    <div class="pt-grid">

        <div class="pt-card">

            <span>
                Completed Tests
            </span>

            <strong>
                {{ $completedAttempts }}
            </strong>

        </div>

        <div class="pt-card">

            <span>
                Average Score
            </span>

            <strong>
                {{ $averageScore !== null
                    ? number_format($averageScore, 1) . '%'
                    : '—' }}
            </strong>

        </div>

        <div class="pt-card">

            <span>
                Learning Plans
            </span>

            <strong>
                {{ $learningPlans->count() }}
            </strong>

        </div>

    </div>

    <div class="pt-main-card">

        <h2>
            Public Cybersecurity Assessment
        </h2>

        <p>
            Take a cybersecurity awareness assessment using the shared
            CyberReadyAI question bank.
        </p>

        <a
            href="{{ route('public-test.start') }}"
            class="pt-button"
        >
            Start / Continue Test
        </a>

    </div>

    @if ($latestAttempt)

        <div class="pt-main-card">

            <h2>
                Latest Assessment
            </h2>

            <table class="pt-table">

                <thead>

                    <tr>
                        <th>Status</th>
                        <th>Score</th>
                        <th>Answered</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>
                            {{ ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $latestAttempt->status
                                )
                            ) }}
                        </td>

                        <td>
                            {{ number_format(
                                (float) $latestAttempt->score_percentage,
                                1
                            ) }}%
                        </td>

                        <td>
                            {{ $latestAttempt->answered_questions }}
                            /
                            {{ $latestAttempt->total_questions }}
                        </td>

                        <td>

                            @if ($latestAttempt->status === 'in_progress')

                                <a
                                    href="{{ route('public-test.question', [
                                        'attempt' => $latestAttempt->id,
                                        'question' => max(
                                            1,
                                            $latestAttempt->current_position
                                        ),
                                    ]) }}"
                                    class="pt-button"
                                >
                                    Continue
                                </a>

                            @else

                                <a
                                    href="{{ route('public-test.result', $latestAttempt) }}"
                                    class="pt-button"
                                >
                                    View Result
                                </a>

                            @endif

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    @endif

</div>

@endsection
