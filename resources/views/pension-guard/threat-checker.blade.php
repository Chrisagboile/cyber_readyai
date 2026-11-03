@extends('pension-guard.layout')

@section('title', 'Pension Scam Warning Checker')

@section('content')
<section class="panel">
    <h2>Pension scam warning checker</h2>

    <p>
        Use this educational tool to identify common warning signs in
        pension-related calls, messages, investment offers and transfer
        requests.
    </p>

    <p>
        Pension scams can put retirement savings at risk. Be cautious
        about unexpected contact, pressure to act quickly, promises of
        unusually high returns and requests to transfer pension savings.
        Verify claims independently before taking action.
    </p>

    <p>
        This tool does not establish whether an offer is genuine.
        It provides general educational information only.
    </p>

    <p>
        <strong>Privacy reminder:</strong>
        Never enter passwords, one-time codes, pension account numbers
        or other sensitive personal information here.
    </p>

    @if ($errors->any())
        <div class="panel" role="alert" aria-live="polite">
            <h3>Please review your answers</h3>
            <p>Some information needs attention. Correct the fields below and try again.</p>
        </div>
    @endif

    <form method="POST"
          action="{{ route('pension-guard.threat-checker.store') }}">
        @csrf

        <fieldset class="panel">
            <legend>
                <strong>1. What are you checking?</strong>
            </legend>

            @foreach ($scenarios as $value => $label)
                <label>
                    <input
                        type="radio"
                        name="scenario"
                        value="{{ $value }}"
                        @checked((string) old('scenario') === (string) $value)
                        required
                    >
                    {{ $label }}
                </label>
            @endforeach

            @error('scenario')
                <p role="alert">{{ $message }}</p>
            @enderror
        </fieldset>

        <fieldset class="panel">
            <legend>
                <strong>2. Which warning signs apply?</strong>
            </legend>

            <p>Select every statement that applies to your situation.</p>

            @forelse ($warningSigns as $value => $sign)
                <label>
                    <input
                        type="checkbox"
                        name="warning_signs[]"
                        value="{{ $value }}"
                        @checked(in_array(
                            (string) $value,
                            array_map('strval', old('warning_signs', [])),
                            true
                        ))
                    >
                    {{ $sign['title'] }}
                </label>
            @empty
                <p>
                    Warning signs are temporarily unavailable.
                    Please try again later.
                </p>
            @endforelse

            @error('warning_signs')
                <p role="alert">{{ $message }}</p>
            @enderror

            @error('warning_signs.*')
                <p role="alert">{{ $message }}</p>
            @enderror
        </fieldset>

        <button type="submit">Check warning signs</button>
    </form>
</section>

@if ($result)
    <section class="panel" aria-live="polite">
        <h2>Assessment outcome: {{ $result['level'] }}</h2>

        <p>{{ $result['message'] }}</p>

        <p class="muted">
            This outcome reflects the warning signs selected. It is not
            a verification of the sender, provider, investment or offer.
        </p>

        <h3>Situation selected</h3>

        <p>
            {{ $scenarios[$result['scenario']] ?? 'Situation recorded' }}
        </p>

        <h3>Warning signs you selected</h3>

        @forelse ($result['matched_signs'] as $sign)
            <article class="panel">
                <h4>{{ $sign['title'] }}</h4>

                <p>{{ $sign['description'] }}</p>

                <p>
                    <strong>Severity category:</strong>
                    {{ ucfirst($sign['severity']) }}
                </p>

                <p>
                    <strong>Suggested action:</strong>
                    {{ $sign['recommended_action'] }}
                </p>

                @if (!empty($sign['official_source_url']))
                    <p>
                        <a href="{{ $sign['official_source_url'] }}"
                           target="_blank"
                           rel="noopener noreferrer">
                            Read related official guidance
                        </a>
                    </p>
                @endif
            </article>
        @empty
            <p>
                You did not select any of the listed warning signs.
                This does not establish that the situation is safe.
            </p>
        @endforelse

        <h3>Recommended next steps</h3>

        <ol>
            <li>
                Pause before transferring money, changing pension
                arrangements or sharing personal information.
            </li>
            <li>
                Contact your pension provider using contact details
                obtained independently from its official website or
                trusted paperwork.
            </li>
            <li>
                Review the
                <a href="https://www.fca.org.uk/scamsmart"
                   target="_blank"
                   rel="noopener noreferrer">
                    FCA ScamSmart guidance
                </a>.
            </li>
            <li>
                Read
                <a href="https://www.moneyhelper.org.uk/en/pensions-and-retirement"
                   target="_blank"
                   rel="noopener noreferrer">
                    MoneyHelper's pensions and retirement guidance
                </a>.
            </li>
        </ol>

        <p>
            <strong>Important:</strong>
            This checker does not investigate messages, verify identities
            or determine conclusively whether an offer is genuine.
            A result is not a guarantee of safety.
        </p>

        <p>
            <a class="button"
               href="{{ route('pension-guard.threat-checker.create') }}">
                Check another situation
            </a>
        </p>

        <p>
            <a href="{{ route('pension-guard.sources.index') }}">
                Browse official guidance
            </a>
        </p>
    </section>
@endif

<section class="panel">
    <h2>Continue protecting your pension</h2>

    @auth('pension_guard')
        <p>
            <a href="{{ route('pension-guard.accounts.index') }}">
                View my pension accounts
            </a>
        </p>

        <p>
            <a href="{{ route('pension-guard.assessments.history') }}">
                View assessment history
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
    @else
        <p>
            <a href="{{ route('pension-guard.register') }}">
                Create an account to save your pension records
            </a>
        </p>

        <p>
            <a href="{{ route('pension-guard.login') }}">
                Sign in to Pension Account Guard
            </a>
        </p>
    @endauth

    <p>
        <a href="{{ route('pension-guard.assessments.create') }}">
            Complete the security awareness assessment
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.dashboard') }}">
            Return to Pension Account Guard home
        </a>
    </p>
</section>
@endsection
