@extends('pension-guard.layout')

@section('title', 'My Assessment History')

@section('content')
<section class="panel">
    <h2>My saved assessments</h2>

    <p>
        Review your previous security self-assessments and revisit the
        suggested protective steps. These results are educational and
        are not a validated security rating.
    </p>

    <p>
        <a class="button"
           href="{{ route('pension-guard.assessments.create') }}">
            Take a new assessment
        </a>

        <a class="button"
           href="{{ route('pension-guard.dashboard') }}">
            Return to dashboard
        </a>
    </p>

    @forelse ($assessments as $assessment)
        <article class="panel">
            <h3>
                Assessment on
                {{ $assessment->created_at->format('d M Y, H:i') }}
            </h3>

            <p>
                <strong>Risk band:</strong>
                {{ $assessment->risk_band }}
            </p>

            <p>
                <strong>Score:</strong>
                {{ $assessment->score }} / 10
            </p>

            <a class="button"
               href="{{ route('pension-guard.assessments.show', ['assessment' => $assessment->id]) }}">
                View saved result
            </a>
        </article>
    @empty
        <p>You have no saved assessments yet.</p>

        <p>
            Complete your first assessment to receive educational
            guidance based on your answers.
        </p>

        <a class="button"
           href="{{ route('pension-guard.assessments.create') }}">
            Start your first assessment
        </a>
    @endforelse

    @if ($assessments->hasPages())
        <nav aria-label="Assessment history pagination">
            {{ $assessments->links() }}
        </nav>
    @endif
</section>

<section class="panel">
    <h2>Other protection tools</h2>

    <p>
        <a href="{{ route('pension-guard.accounts.index') }}">
            My pension accounts
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.threat-checker.create') }}">
            Check a suspicious pension offer
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.sources.index') }}">
            Official guidance and sources
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.trusted-contacts.index') }}">
            Manage trusted contacts
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.notifications.index') }}">
            View notifications
        </a>
    </p>
</section>
@endsection
