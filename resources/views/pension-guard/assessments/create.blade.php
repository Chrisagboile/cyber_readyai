@extends('pension-guard.layout')

@section('title', 'Pension Scam Awareness Check')

@section('content')
<section class="panel">
    <h2>Pension scam awareness check</h2>

    <p>
        Pension scams can put retirement savings at risk. Stay alert to
        unexpected offers, unsolicited calls and requests to transfer
        pension savings. Verify information independently before acting.
    </p>

    <p>
        This educational assessment helps you review your current security
        habits. It is not a government assessment, financial advice,
        a security scan or a guarantee that your pension is safe.
    </p>

    <p>
        Answer the five security questions honestly. The age-group and
        monthly-payment questions are product research questions and do
        not affect your score.
    </p>

    <p>
        <strong>Privacy reminder:</strong> Do not enter pension account
        numbers, balances, passwords, one-time codes or private messages.
    </p>

    <div class="panel">
        <h3>Before you begin</h3>
        <p>There are seven questions in total:</p>
        <ul>
            <li>Five questions about security habits.</li>
            <li>Two questions about age group and service preferences.</li>
        </ul>
        <p>You must answer each question to submit this form.</p>
    </div>

    @if ($errors->any())
        <div class="panel" role="alert" aria-live="polite">
            <h3>Please check your answers</h3>
            <p>Some answers are missing or invalid. Review the highlighted
                questions and try again.</p>
        </div>
    @endif

    <form method="POST"
          action="{{ route('pension-guard.assessments.store') }}">
        @csrf

        <h3>Part 1: Security habits</h3>
        <p class="muted">
            These five answers determine your educational assessment score.
        </p>

        @foreach ($questions as $key => $item)
            <fieldset class="panel">
                <legend>
                    <strong>
                        {{ $loop->iteration }}. {{ $item['question'] }}
                    </strong>
                </legend>

                @foreach ($item['options'] as $value => $label)
                    <label>
                        <input
                            type="radio"
                            name="answers[{{ $key }}]"
                            value="{{ $value }}"
                            @checked((string) old("answers.$key") === (string) $value)
                            required
                        >
                        {{ $label }}
                    </label>
                @endforeach

                @error("answers.$key")
                    <p role="alert">{{ $message }}</p>
                @enderror
            </fieldset>
        @endforeach

        @error('answers')
            <p role="alert">{{ $message }}</p>
        @enderror

        <h3>Part 2: Product research questions</h3>

        <fieldset class="panel">
            <legend>
                <strong>6. Which age group are you in?</strong>
            </legend>

            <p class="muted">
                This helps us understand which age groups the service
                may be useful for. You can choose not to say.
            </p>

            @foreach ($ageGroups as $value => $label)
                <label>
                    <input
                        type="radio"
                        name="age_group"
                        value="{{ $value }}"
                        @checked(old('age_group') === $value)
                        required
                    >
                    {{ $label }}
                </label>
            @endforeach

            @error('age_group')
                <p role="alert">{{ $message }}</p>
            @enderror
        </fieldset>

        <fieldset class="panel">
            <legend>
                <strong>
                    7. Would you consider paying £3–£5 per month for a
                    service that helps you improve pension account security
                    and identify potential scam warning signs?
                </strong>
            </legend>

            <p class="muted">
                This is a research question about service preferences.
                Your answer does not affect your assessment score.
            </p>

            @foreach ($paymentOptions as $value => $label)
                <label>
                    <input
                        type="radio"
                        name="monthly_payment_interest"
                        value="{{ $value }}"
                        @checked(old('monthly_payment_interest') === $value)
                        required
                    >
                    {{ $label }}
                </label>
            @endforeach

            @error('monthly_payment_interest')
                <p role="alert">{{ $message }}</p>
            @enderror
        </fieldset>

        <p>
            <button type="submit">Submit answers and view result</button>
        </p>
    </form>
</section>

<section class="panel">
    <h2>Explore other protection features</h2>

    <p>
        <a href="{{ route('pension-guard.threat-checker.create') }}">
            Check a suspicious pension offer
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.sources.index') }}">
            Read official guidance
        </a>
    </p>

    @auth('pension_guard')
        <p>
            <a href="{{ route('pension-guard.accounts.index') }}">
                Manage my pension records
            </a>
        </p>

        <p>
            <a href="{{ route('pension-guard.assessments.history') }}">
                View saved assessment history
            </a>
        </p>
    @else
        <p>
            <a href="{{ route('pension-guard.register') }}">
                Create an account to save future assessments
            </a>
        </p>

        <p>
            <a href="{{ route('pension-guard.login') }}">
                Sign in to Pension Account Guard
            </a>
        </p>
    @endauth

    <p>
        <a href="{{ route('pension-guard.dashboard') }}">
            Return to home
        </a>
    </p>
</section>
@endsection
