@extends('pension-guard.layout')

@section('title', 'My Pensions | Pension Account Guard')

@section('content')
<div class="panel">
    <h2>My pension accounts</h2>

    <p class="muted">
        Keep a personal list of your pension accounts and review the
        security checklist for each one.
    </p>

    <p>
        <a class="button" href="{{ route('pension-guard.accounts.create') }}">
            + Add a pension account
        </a>
    </p>

    <hr>

    <h3>Protect yourself from pension scams</h3>
    <p>
        Unsure about a pension call, message, investment offer or transfer request?
        Use the educational scam checker to review common warning signs.
    </p>

    <p>
        <a class="button" href="{{ route('pension-guard.threat-checker.create') }}">
            Open scam checker
        </a>

        <a class="button" href="{{ route('pension-guard.sources.index') }}">
            Official guidance
        </a>
    </p>
</div>

@if ($accounts->isEmpty())
    <div class="panel">
        <h3>No pension accounts added yet</h3>
        <p>
            Add a pension provider or enter your provider manually to
            create your first record.
        </p>
        <a class="button" href="{{ route('pension-guard.accounts.create') }}">
            Add your first pension
        </a>
    </div>
@else
    @foreach ($accounts as $account)
        <div class="panel">
            <h3>{{ $account->display_name ?: $account->provider_name }}</h3>

            <p>
                <strong>Provider:</strong>
                {{ $account->provider_name }}
            </p>

            <p>
                <strong>Account type:</strong>
                {{ ucwords(str_replace('_', ' ', $account->account_type)) }}
            </p>

            @if ($account->notes)
                <p>
                    <strong>Notes:</strong> {{ $account->notes }}
                </p>
            @endif

            <p>
                <strong>Security checklist:</strong>
                {{ $account->securityChecks->where('is_complete', true)->count() }}
                of {{ $account->securityChecks->count() }} completed
            </p>

            <p>
                <a class="button"
                   href="{{ route('pension-guard.accounts.show', $account) }}">
                    View account
                </a>

                <a class="button"
                   href="{{ route('pension-guard.checklist.index', $account) }}">
                    Security checklist
                </a>
            </p>
        </div>
    @endforeach
@endif

<div class="panel">
    <h3>Continue your security review</h3>

    <p>Review your overall security assessment and any saved results.</p>

    <p>
        <a class="button"
           href="{{ route('pension-guard.assessments.create') }}">
            Start security assessment
        </a>

        <a class="button"
           href="{{ route('pension-guard.assessments.history') }}">
            Assessment history
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.trusted-contacts.index') }}">
            Manage trusted contacts
        </a>
        |
        <a href="{{ route('pension-guard.notifications.index') }}">
            View notifications
        </a>
    </p>
</div>
@endsection