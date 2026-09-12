@extends('layouts.app')

@section('content')

<style>
    .super-billing-page {
        max-width: 1500px;
        margin: 0 auto;
        padding: 8px 0 50px;
    }

    .super-billing-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 24px;
    }

    .super-billing-eyebrow {
        display: block;
        margin-bottom: 7px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .09em;
        opacity: .52;
    }

    .super-billing-header h1 {
        margin: 0;
        font-size: 30px;
        letter-spacing: -.5px;
    }

    .super-billing-header p {
        margin: 8px 0 0;
        max-width: 760px;
        font-size: 13px;
        opacity: .62;
    }

    .super-billing-actions {
        display: flex;
        gap: 9px;
    }

    .super-billing-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 41px;
        padding: 0 14px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 800;
    }

    .super-billing-button-primary {
        background: #111827;
        color: #fff;
    }

    .super-billing-button-primary:hover {
        color: #fff;
        opacity: .92;
    }

    .super-billing-button-secondary {
        border: 1px solid rgba(127,127,127,.15);
        background: rgba(127,127,127,.06);
        color: inherit;
    }

    .super-billing-button-secondary:hover {
        color: inherit;
        background: rgba(127,127,127,.11);
    }

    .super-billing-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 13px;
        margin-bottom: 22px;
    }

    .super-billing-stat {
        padding: 18px;
        border: 1px solid rgba(127,127,127,.14);
        border-radius: 14px;
        background: var(--card-bg, #fff);
        box-shadow: 0 5px 18px rgba(0,0,0,.03);
    }

    .super-billing-stat-label {
        margin-bottom: 8px;
        font-size: 11px;
        opacity: .55;
    }

    .super-billing-stat-value {
        font-size: 27px;
        line-height: 1;
        font-weight: 800;
    }

    .super-billing-stat-meta {
        margin-top: 8px;
        font-size: 10px;
        opacity: .45;
    }

    .super-billing-card {
        margin-bottom: 22px;
        padding: 22px;
        border: 1px solid rgba(127,127,127,.14);
        border-radius: 16px;
        background: var(--card-bg, #fff);
        box-shadow: 0 7px 24px rgba(0,0,0,.035);
    }

    .super-billing-card-heading {
        margin-bottom: 19px;
    }

    .super-billing-card-heading h2 {
        margin: 0;
        font-size: 18px;
    }

    .super-billing-card-heading p {
        margin: 5px 0 0;
        font-size: 12px;
        opacity: .55;
    }

    .super-billing-metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .super-billing-metric {
        padding: 15px;
        border: 1px solid rgba(127,127,127,.11);
        border-radius: 12px;
        background: rgba(127,127,127,.035);
    }

    .super-billing-metric span {
        display: block;
        margin-bottom: 6px;
        font-size: 10px;
        opacity: .52;
    }

    .super-billing-metric strong {
        display: block;
        font-size: 21px;
    }

    .super-billing-two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .super-billing-alert-list {
        display: grid;
        gap: 11px;
    }

    .super-billing-alert-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 12px 0;
        border-bottom: 1px solid rgba(127,127,127,.08);
    }

    .super-billing-alert-item:last-child {
        border-bottom: 0;
    }

    .super-billing-alert-primary {
        font-size: 12px;
        font-weight: 800;
    }

    .super-billing-alert-secondary {
        margin-top: 3px;
        font-size: 10px;
        opacity: .48;
    }

    .super-billing-alert-date {
        font-size: 10px;
        font-weight: 800;
        text-align: right;
        white-space: nowrap;
    }

    .super-billing-alert-warning {
        color: #946d13;
    }

    .super-billing-alert-danger {
        color: #a13d3d;
    }

    .super-billing-table-wrap {
        overflow-x: auto;
    }

    .super-billing-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .super-billing-table th {
        padding: 11px 10px;
        text-align: left;
        border-bottom: 1px solid rgba(127,127,127,.14);
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .05em;
        opacity: .53;
        white-space: nowrap;
    }

    .super-billing-table td {
        padding: 14px 10px;
        border-bottom: 1px solid rgba(127,127,127,.08);
        font-size: 12px;
        vertical-align: middle;
    }

    .super-billing-table tr:last-child td {
        border-bottom: 0;
    }

    .super-billing-org {
        font-weight: 800;
    }

    .super-billing-plan {
        font-weight: 700;
    }

    .super-billing-muted {
        opacity: .45;
    }

    .super-billing-badge {
        display: inline-flex;
        align-items: center;
        min-height: 23px;
        padding: 0 8px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 800;
    }

    .super-billing-success {
        color: #25784c;
        background: rgba(46,157,99,.11);
    }

    .super-billing-info {
        color: #245ea8;
        background: rgba(59,130,246,.11);
    }

    .super-billing-warning {
        color: #946d13;
        background: rgba(245,158,11,.13);
    }

    .super-billing-danger {
        color: #a13d3d;
        background: rgba(220,38,38,.10);
    }

    .super-billing-neutral {
        background: rgba(127,127,127,.10);
    }

    .super-billing-action {
        display: inline-flex;
        padding: 0;
        border: 0;
        background: transparent;
        color: inherit;
        text-decoration: none;
        font-size: 11px;
        font-weight: 800;
    }

    .super-billing-action:hover {
        text-decoration: underline;
    }

    .super-billing-empty {
        padding: 20px 0;
        font-size: 12px;
        opacity: .55;
    }

    @media (max-width: 1050px) {
        .super-billing-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .super-billing-metrics {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .super-billing-two-column {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .super-billing-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .super-billing-actions {
            width: 100%;
        }

        .super-billing-actions a {
            flex: 1;
        }

        .super-billing-stats,
        .super-billing-metrics {
            grid-template-columns: 1fr;
        }
    }
</style>


<div class="super-billing-page">

    <div class="super-billing-header">

        <div>

            <span class="super-billing-eyebrow">
                Platform Finance
            </span>

            <h1>
                Billing
            </h1>

            <p>
                Monitor recurring revenue, subscription lifecycle,
                upcoming renewals and commercial performance.
            </p>

        </div>


        <div class="super-billing-actions">

            <a
                href="{{ route('subscriptions.index') }}"
                class="super-billing-button super-billing-button-secondary"
            >
                Manage Subscriptions
            </a>

            <a
                href="{{ route('subscriptions.create') }}"
                class="super-billing-button super-billing-button-primary"
            >
                + New Subscription
            </a>

        </div>

    </div>


    {{-- Overview --}}
    <div class="super-billing-stats">

        <div class="super-billing-stat">

            <div class="super-billing-stat-label">
                Active Subscriptions
            </div>

            <div class="super-billing-stat-value">
                {{ $activeSubscriptions->count() }}
            </div>

            <div class="super-billing-stat-meta">
                Paying organisations
            </div>

        </div>


        <div class="super-billing-stat">

            <div class="super-billing-stat-label">
                Trial Subscriptions
            </div>

            <div class="super-billing-stat-value">
                {{ $trialSubscriptions->count() }}
            </div>

            <div class="super-billing-stat-meta">
                Potential conversions
            </div>

        </div>


        <div class="super-billing-stat">

            <div class="super-billing-stat-label">
                Expired
            </div>

            <div class="super-billing-stat-value">
                {{ $expiredSubscriptions->count() }}
            </div>

            <div class="super-billing-stat-meta">
                Requiring attention
            </div>

        </div>


        <div class="super-billing-stat">

            <div class="super-billing-stat-label">
                Cancelled
            </div>

            <div class="super-billing-stat-value">
                {{ $cancelledSubscriptions->count() }}
            </div>

            <div class="super-billing-stat-meta">
                Historical cancellations
            </div>

        </div>

    </div>


    {{-- Revenue --}}
    <div class="super-billing-card">

        <div class="super-billing-card-heading">

            <h2>
                Recurring Revenue
            </h2>

            <p>
                Current active and trial subscription value.
            </p>

        </div>


        <div class="super-billing-metrics">

            <div class="super-billing-metric">

                <span>
                    Monthly Recurring Value
                </span>

                <strong>
                    £{{ number_format(
                        $monthlyRecurringRevenue,
                        2
                    ) }}
                </strong>

            </div>


            <div class="super-billing-metric">

                <span>
                    Annual Recurring Value
                </span>

                <strong>
                    £{{ number_format(
                        $annualRecurringRevenue,
                        2
                    ) }}
                </strong>

            </div>


            <div class="super-billing-metric">

                <span>
                    Active Annual Value
                </span>

                <strong>
                    £{{ number_format(
                        $activeAnnualValue,
                        2
                    ) }}
                </strong>

            </div>


            <div class="super-billing-metric">

                <span>
                    Trial Annual Value
                </span>

                <strong>
                    £{{ number_format(
                        $trialAnnualValue,
                        2
                    ) }}
                </strong>

            </div>

        </div>

    </div>


    {{-- Upcoming + recent expiry --}}
    <div class="super-billing-two-column">

        <div class="super-billing-card">

            <div class="super-billing-card-heading">

                <h2>
                    Renewals & Expirations
                </h2>

                <p>
                    Active or trial subscriptions ending within 30 days.
                </p>

            </div>


            @if($expiringSoon->count())

                <div class="super-billing-alert-list">

                    @foreach($expiringSoon as $subscription)

                        <div class="super-billing-alert-item">

                            <div>

                                <div class="super-billing-alert-primary">
                                    {{ $subscription->organisation?->name
                                        ?? 'Unknown Organisation'
                                    }}
                                </div>

                                <div class="super-billing-alert-secondary">

                                    {{ $subscription->plan_name }}

                                    ·

                                    {{ ucfirst(
                                        $subscription->billing_interval
                                    ) }}

                                </div>

                            </div>


                            <div class="
                                super-billing-alert-date
                                super-billing-alert-warning
                            ">

                                {{ $subscription->ends_at->format('d M Y') }}

                                <br>

                                {{ $subscription->ends_at->diffInDays(today()) }}
                                days

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="super-billing-empty">
                    No subscriptions are expiring within the next 30 days.
                </div>

            @endif

        </div>


        <div class="super-billing-card">

            <div class="super-billing-card-heading">

                <h2>
                    Recently Expired
                </h2>

                <p>
                    Subscriptions that expired within the last 30 days.
                </p>

            </div>


            @if($expiredRecently->count())

                <div class="super-billing-alert-list">

                    @foreach($expiredRecently as $subscription)

                        <div class="super-billing-alert-item">

                            <div>

                                <div class="super-billing-alert-primary">
                                    {{ $subscription->organisation?->name
                                        ?? 'Unknown Organisation'
                                    }}
                                </div>

                                <div class="super-billing-alert-secondary">
                                    {{ $subscription->plan_name }}
                                </div>

                            </div>


                            <div class="
                                super-billing-alert-date
                                super-billing-alert-danger
                            ">

                                {{ $subscription->ends_at->format('d M Y') }}

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="super-billing-empty">
                    No subscriptions expired within the last 30 days.
                </div>

            @endif

        </div>

    </div>


    {{-- Subscription ledger --}}
    <div class="super-billing-card">

        <div class="super-billing-card-heading">

            <h2>
                Subscription Ledger
            </h2>

            <p>
                Commercial overview of all organisation subscriptions.
            </p>

        </div>


        @if($subscriptions->count())

            <div class="super-billing-table-wrap">

                <table class="super-billing-table">

                    <thead>

                        <tr>
                            <th>Organisation</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Billing</th>
                            <th>Price</th>
                            <th>Annual Value</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Action</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($subscriptions as $subscription)

                            @php

                                $annualValue =
                                    $subscription->billing_interval === 'monthly'
                                        ? (float) $subscription->price * 12
                                        : (float) $subscription->price;

                            @endphp


                            <tr>

                                <td>
                                    <span class="super-billing-org">
                                        {{ $subscription->organisation?->name
                                            ?? 'Unknown Organisation'
                                        }}
                                    </span>
                                </td>


                                <td>
                                    <span class="super-billing-plan">
                                        {{ $subscription->plan_name }}
                                    </span>
                                </td>


                                <td>

                                    @if($subscription->status === 'active')

                                        <span class="
                                            super-billing-badge
                                            super-billing-success
                                        ">
                                            Active
                                        </span>

                                    @elseif($subscription->status === 'trial')

                                        <span class="
                                            super-billing-badge
                                            super-billing-info
                                        ">
                                            Trial
                                        </span>

                                    @elseif($subscription->status === 'expired')

                                        <span class="
                                            super-billing-badge
                                            super-billing-warning
                                        ">
                                            Expired
                                        </span>

                                    @elseif($subscription->status === 'cancelled')

                                        <span class="
                                            super-billing-badge
                                            super-billing-danger
                                        ">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="
                                            super-billing-badge
                                            super-billing-neutral
                                        ">
                                            {{ ucfirst($subscription->status) }}
                                        </span>

                                    @endif

                                </td>


                                <td>
                                    {{ ucfirst(
                                        $subscription->billing_interval
                                    ) }}
                                </td>


                                <td>
                                    £{{ number_format(
                                        (float) $subscription->price,
                                        2
                                    ) }}
                                </td>


                                <td>
                                    £{{ number_format(
                                        $annualValue,
                                        2
                                    ) }}
                                </td>


                                <td>
                                    {{ $subscription->starts_at?->format(
                                        'd M Y'
                                    ) ?? '—' }}
                                </td>


                                <td>
                                    {{ $subscription->ends_at?->format(
                                        'd M Y'
                                    ) ?? '—' }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'subscriptions.edit',
                                            $subscription
                                        ) }}"
                                        class="super-billing-action"
                                    >
                                        Manage
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="super-billing-empty">
                No subscriptions have been created yet.
            </div>

        @endif

    </div>


    {{-- Pricing composition --}}
    <div class="super-billing-card">

        <div class="super-billing-card-heading">

            <h2>
                Commercial Composition
            </h2>

            <p>
                Breakdown of current active and trial recurring value.
            </p>

        </div>


        <div class="super-billing-metrics">

            <div class="super-billing-metric">

                <span>
                    Active Monthly Value
                </span>

                <strong>
                    £{{ number_format(
                        $activeMonthlyValue,
                        2
                    ) }}
                </strong>

            </div>


            <div class="super-billing-metric">

                <span>
                    Active Annual Value
                </span>

                <strong>
                    £{{ number_format(
                        $activeAnnualValue,
                        2
                    ) }}
                </strong>

            </div>


            <div class="super-billing-metric">

                <span>
                    Trial Monthly Value
                </span>

                <strong>
                    £{{ number_format(
                        $trialMonthlyValue,
                        2
                    ) }}
                </strong>

            </div>


            <div class="super-billing-metric">

                <span>
                    Trial Annual Value
                </span>

                <strong>
                    £{{ number_format(
                        $trialAnnualValue,
                        2
                    ) }}
                </strong>

            </div>

        </div>

    </div>

</div>

@endsection