
@extends('pension-guard.layout')

@section('title', 'Assessment Result')

@section('content')
@php
    $allAnswers = is_array($assessment)
        ? ($assessment['answers'] ?? [])
        : ($assessment->answers ?? []);

    $research = $allAnswers['_research'] ?? [];

    $riskBand = is_array($assessment)
        ? ($assessment['risk_band'] ?? '')
        : ($assessment->risk_band ?? '');

    $score = is_array($assessment)
        ? ($assessment['score'] ?? 0)
        : ($assessment->score ?? 0);
@endphp

<section class="panel">
    <h2>Your assessment result</h2>

    <p><strong>Band:</strong> {{ $riskBand }}</p>
    <p><strong>Score:</strong> {{ $score }} / 10</p>

    <p class="muted">
        This is an educational self-assessment based on your answers.
        It is not a validated risk rating, a security scan or proof that
        an offer, account or provider is safe or compromised.
    </p>

    @if ($riskBand === 'Higher')
        <h3>Suggested next steps</h3>
        <p>
            Pause before acting on unexpected pension requests. Independently
            verify the sender, enable available multi-factor authentication
            and review reused passwords.
        </p>
    @elseif ($riskBand === 'Moderate')
        <h3>Review your security habits</h3>
        <p>
            Focus on the answers where you were unsure. Check account recovery
            settings and verify pension-related requests through a trusted channel.
        </p>
    @else
        <h3>Maintain your protective habits</h3>
        <p>
            Continue reviewing security settings and independently verify
            unexpected offers, calls, links and transfer requests.
        </p>
    @endif

    <h3>Your security answers</h3>

    @foreach ($questions as $key => $item)
        @if (array_key_exists($key, $allAnswers))
            <p>
                <strong>{{ $item['question'] }}</strong><br>
                {{ $item['options'][$allAnswers[$key]] ?? 'Answer recorded' }}
            </p>
        @endif
    @endforeach

    @if (!empty($research))
        <h3>Your research responses</h3>

        <p>
            <strong>Age group:</strong>
            {{ $ageGroups[$research['age_group'] ?? ''] ?? 'Not recorded' }}
        </p>

        <p>
            <strong>Interest in a £3–£5 monthly service:</strong>
            {{ $paymentOptions[$research['monthly_payment_interest'] ?? ''] ?? 'Not recorded' }}
        </p>

        <p class="muted">
            These research responses are separate from your security score.
        </p>
    @endif


    @if ($saved)
        <div class="panel">
            <h3>Assessment saved</h3>
            <p>This assessment is saved to your Pension Account Guard account.</p>

            <a class="button"
               href="{{ route('pension-guard.assessments.history') }}">
                View assessment history
            </a>
        </div>
    @else
        <div class="panel">
            <h3>Keep track of future results</h3>
            <p>
                This public demo result is stored in your current session only.
                Create an account or sign in to save future assessments.
            </p>

            <a class="button" href="{{ route('pension-guard.register') }}">
                Create an account
            </a>

            <a class="button" href="{{ route('pension-guard.login') }}">
                Sign in
            </a>
        </div>
    @endif

    <section class="panel">
        <h3>Continue protecting your pension</h3>

        <p>
            <a class="button"
               href="{{ route('pension-guard.assessments.create') }}">
                Take another assessment
            </a>
        </p>

        <p>
            <a class="button"
               href="{{ route('pension-guard.threat-checker.create') }}">
                Check a suspicious offer
            </a>
        </p>

        <p>
            <a class="button"
               href="{{ route('pension-guard.sources.index') }}">
                Read official guidance
            </a>
        </p>

        @auth('pension_guard')
            <p>
                <a class="button"
                   href="{{ route('pension-guard.accounts.index') }}">
                    Review my pensions
                </a>
            </p>

            <p>
                <a class="button"
                   href="{{ route('pension-guard.notifications.index') }}">
                    View notifications
                </a>
            </p>
        @endauth

        <p>
            <a href="{{ route('pension-guard.dashboard') }}">
                Return to Pension Account Guard home
            </a>
        </p>
    </section>
</section>

</section>
@endsection
