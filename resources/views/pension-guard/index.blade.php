@extends('pension-guard.layout')

@section('title', 'My Pension Accounts')

@section('content')
    <section class="panel">
        <h2>My pension accounts</h2>
        <p>Track the security steps for each pension record.</p>

        <a class="button" href="{{ route('pension-guard.accounts.create') }}">
            Add pension
        </a>
    </section>

    @forelse ($accounts as $account)
        <section class="panel">
            <h3>{{ $account->display_name ?: $account->provider_name }}</h3>
            <p>Provider: {{ $account->provider_name }}</p>
            <p>Type: {{ str_replace('_', ' ', $account->account_type) }}</p>

            <p>
                Protection checklist:
                {{ $account->securityChecks->where('is_complete', true)->count() }}
                of {{ $account->securityChecks->count() }} completed
            </p>

            <a href="{{ route('pension-guard.accounts.show', $account) }}">
                View pension record
            </a>
        </section>
    @empty
        <section class="panel">
            <p>You have not added any pension records yet.</p>
        </section>
    @endforelse
@endsection
