
@extends('pension-guard.layout')
@section('title', 'Security Checklist')

@section('content')
@php
    $checks = $account->securityChecks;
    $totalChecks = $checks->count();
    $completedChecks = $checks->where('is_complete', true)->count();
    $progress = $totalChecks > 0
        ? (int) round(($completedChecks / $totalChecks) * 100)
        : 0;
@endphp

<section class="panel">
    <p>
        <a href="{{ route('pension-guard.accounts.show', ['account' => $account->id]) }}">
            &larr; Back to pension record
        </a>
    </p>

    <h2>Security checklist</h2>
    <h3>{{ $account->display_name ?: $account->provider_name }}</h3>

    <p>
        Complete these practical security steps using your provider's
        official website or a contact channel you have independently verified.
    </p>

    <div class="panel" aria-live="polite">
        <h3>Your progress</h3>
        <p>
            <strong>{{ $completedChecks }} of {{ $totalChecks }}</strong>
            security steps completed
        </p>

        <div
            role="progressbar"
            aria-label="Security checklist progress"
            aria-valuemin="0"
            aria-valuemax="100"
            aria-valuenow="{{ $progress }}"
            style="background:#e5e7eb;border-radius:8px;overflow:hidden;height:12px;"
        >
            <div style="width:{{ $progress }}%;background:#2563eb;height:12px;"></div>
        </div>

        <p>{{ $progress }}% complete</p>

        @if ($totalChecks === 0)
            <p>No security checks are currently available for this account.</p>
        @elseif ($completedChecks === $totalChecks)
            <p><strong>All listed checks are marked completed.</strong></p>
        @endif
    </div>
</section>

@forelse ($checks as $check)
    <section class="panel">
        <h3>{{ $check->title }}</h3>

        <p>{{ $check->guidance }}</p>

        @if ($check->official_url)
            <p>
                <a href="{{ $check->official_url }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    Visit provider website ↗
                </a>
            </p>
        @endif

        <p>
            Status:
            @if ($check->is_complete)
                <strong>Completed</strong>
            @else
                <strong>Not completed</strong>
            @endif
        </p>

        <form method="POST"
              action="{{ route('pension-guard.checklist.update', [
                  'account' => $account->id,
                  'check' => $check->id
              ]) }}">
            @csrf
            @method('PATCH')

            <input type="hidden"
                   name="is_complete"
                   value="{{ $check->is_complete ? 0 : 1 }}">

            <button type="submit">
                {{ $check->is_complete ? 'Mark as not completed' : 'Mark as completed' }}
            </button>
        </form>
    </section>
@empty
    <section class="panel">
        <h3>No security checks available</h3>
        <p>
            There are currently no checklist items for this pension account.
            You can return to your pension record or view the available guidance.
        </p>
        <a class="button" href="{{ route('pension-guard.sources.index') }}">
            View official guidance
        </a>
    </section>
@endforelse

<section class="panel">
    <h3>Continue protecting your pension</h3>

    <p>
        <a class="button" href="{{ route('pension-guard.accounts.index') }}">
            My pensions
        </a>
    </p>

    <p>
        <a class="button" href="{{ route('pension-guard.notifications.index') }}">
            View notifications
        </a>
    </p>

    <p>
        <a href="{{ route('pension-guard.dashboard') }}">
            Return to home
        </a>
    </p>
</section>
@endsection
