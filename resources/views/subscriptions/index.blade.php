@extends('layouts.app')

@section('content')

<style>
    .subscriptions-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 32px 20px 50px;
    }

    .subscriptions-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 28px;
    }

    .subscriptions-title {
        margin: 0;
        font-size: 30px;
        font-weight: 750;
        letter-spacing: -0.6px;
    }

    .subscriptions-subtitle {
        margin: 8px 0 0;
        color: #6b7280;
        font-size: 15px;
    }

    .subscriptions-primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 650;
        background: #111827;
        color: #ffffff;
        transition: .2s ease;
    }

    .subscriptions-primary-button:hover {
        background: #1f2937;
        color: #ffffff;
    }

    .subscriptions-alert {
        padding: 13px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 550;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .subscriptions-stats {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .subscription-stat {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 19px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, .04);
    }

    .subscription-stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 9px;
    }

    .subscription-stat-value {
        font-size: 25px;
        line-height: 1;
        font-weight: 750;
        color: #111827;
    }

    .subscription-stat-meta {
        margin-top: 8px;
        font-size: 12px;
        color: #9ca3af;
    }

    .subscriptions-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .subscriptions-card-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .subscriptions-card-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .subscriptions-card-header p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .subscriptions-filters {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 180px auto auto;
        gap: 12px;
        margin-top: 17px;
    }

    .subscriptions-input,
    .subscriptions-select {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        padding: 10px 12px;
        font-size: 14px;
        background: #ffffff;
        color: #111827;
        outline: none;
    }

    .subscriptions-input:focus,
    .subscriptions-select:focus {
        border-color: #9ca3af;
        box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
    }

    .subscriptions-filter-button,
    .subscriptions-reset-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
    }

    .subscriptions-filter-button {
        border: 1px solid #111827;
        background: #111827;
        color: #ffffff;
    }

    .subscriptions-reset-button {
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
    }

    .subscriptions-table-wrap {
        overflow-x: auto;
    }

    .subscriptions-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .subscriptions-table th {
        padding: 13px 18px;
        text-align: left;
        background: #f9fafb;
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
    }

    .subscriptions-table td {
        padding: 16px 18px;
        border-top: 1px solid #f0f1f3;
        color: #374151;
        font-size: 14px;
        vertical-align: middle;
    }

    .subscriptions-org {
        font-weight: 700;
        color: #111827;
    }

    .subscriptions-plan {
        font-weight: 650;
        color: #111827;
    }

    .subscriptions-muted {
        color: #9ca3af;
    }

    .subscription-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .subscription-badge-active {
        background: #dcfce7;
        color: #166534;
    }

    .subscription-badge-trial {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .subscription-badge-expired {
        background: #fef3c7;
        color: #92400e;
    }

    .subscription-badge-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .subscription-action {
        display: inline-flex;
        align-items: center;
        padding: 7px 10px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        color: #374151;
        text-decoration: none;
        font-size: 12px;
        font-weight: 650;
        background: #ffffff;
    }

    .subscription-action:hover {
        background: #f9fafb;
        color: #111827;
    }

    .subscription-cancel-form {
        display: inline;
    }

    .subscription-cancel-button {
        border: 0;
        padding: 7px 10px;
        border-radius: 8px;
        color: #991b1b;
        background: #fff1f2;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        margin-left: 5px;
    }

    .subscriptions-empty {
        padding: 45px 20px;
        text-align: center;
        color: #6b7280;
    }

    .subscriptions-pagination {
        padding: 18px 20px;
        border-top: 1px solid #e5e7eb;
    }

    @media (max-width: 1100px) {
        .subscriptions-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .subscriptions-filters {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 700px) {
        .subscriptions-page {
            padding: 22px 14px 40px;
        }

        .subscriptions-header {
            flex-direction: column;
        }

        .subscriptions-primary-button {
            width: 100%;
        }

        .subscriptions-stats {
            grid-template-columns: 1fr 1fr;
        }

        .subscriptions-filters {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="subscriptions-page">

    <div class="subscriptions-header">

        <div>
            <h1 class="subscriptions-title">
                Subscriptions
            </h1>

            <p class="subscriptions-subtitle">
                Manage organisation plans, billing periods and subscription status.
            </p>
        </div>

        <a
            href="{{ route('subscriptions.create') }}"
            class="subscriptions-primary-button"
        >
            + Create Subscription
        </a>

    </div>


    @if(session('success'))
        <div class="subscriptions-alert">
            {{ session('success') }}
        </div>
    @endif


    <div class="subscriptions-stats">

        <div class="subscription-stat">
            <div class="subscription-stat-label">
                Total Subscriptions
            </div>

            <div class="subscription-stat-value">
                {{ $totalSubscriptions }}
            </div>
        </div>


        <div class="subscription-stat">
            <div class="subscription-stat-label">
                Active
            </div>

            <div class="subscription-stat-value">
                {{ $activeSubscriptions }}
            </div>

            <div class="subscription-stat-meta">
                Currently active plans
            </div>
        </div>


        <div class="subscription-stat">
            <div class="subscription-stat-label">
                Trials
            </div>

            <div class="subscription-stat-value">
                {{ $trialSubscriptions }}
            </div>

            <div class="subscription-stat-meta">
                Trial subscriptions
            </div>
        </div>


        <div class="subscription-stat">
            <div class="subscription-stat-label">
                Expired
            </div>

            <div class="subscription-stat-value">
                {{ $expiredSubscriptions }}
            </div>

            <div class="subscription-stat-meta">
                Plans requiring attention
            </div>
        </div>


        <div class="subscription-stat">
            <div class="subscription-stat-label">
                Cancelled
            </div>

            <div class="subscription-stat-value">
                {{ $cancelledSubscriptions }}
            </div>

            <div class="subscription-stat-meta">
                Cancelled plans
            </div>
        </div>

    </div>


    <div class="subscriptions-card">

        <div class="subscriptions-card-header">

            <h2>
                Subscription Management
            </h2>

            <p>
                Search and filter organisation subscriptions.
            </p>


            <form
                method="GET"
                action="{{ route('subscriptions.index') }}"
                class="subscriptions-filters"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="subscriptions-input"
                    placeholder="Search organisation or plan..."
                >


                <select
                    name="status"
                    class="subscriptions-select"
                >
                    <option value="">
                        All statuses
                    </option>

                    <option
                        value="active"
                        @selected(request('status') === 'active')
                    >
                        Active
                    </option>

                    <option
                        value="trial"
                        @selected(request('status') === 'trial')
                    >
                        Trial
                    </option>

                    <option
                        value="expired"
                        @selected(request('status') === 'expired')
                    >
                        Expired
                    </option>

                    <option
                        value="cancelled"
                        @selected(request('status') === 'cancelled')
                    >
                        Cancelled
                    </option>
                </select>


                <button
                    type="submit"
                    class="subscriptions-filter-button"
                >
                    Filter
                </button>


                <a
                    href="{{ route('subscriptions.index') }}"
                    class="subscriptions-reset-button"
                >
                    Reset
                </a>

            </form>

        </div>


        @if($subscriptions->count())

            <div class="subscriptions-table-wrap">

                <table class="subscriptions-table">

                    <thead>

                        <tr>
                            <th>Organisation</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Billing</th>
                            <th>Price</th>
                            <th>Limits</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($subscriptions as $subscription)

                            <tr>

                                <td>
                                    <div class="subscriptions-org">
                                        {{ $subscription->organisation?->name ?? 'Unknown Organisation' }}
                                    </div>
                                </td>


                                <td>
                                    <div class="subscriptions-plan">
                                        {{ $subscription->plan_name }}
                                    </div>
                                </td>


                                <td>

                                    <span class="
                                        subscription-badge
                                        subscription-badge-{{ $subscription->status }}
                                    ">
                                        {{ $subscription->status }}
                                    </span>

                                </td>


                                <td>
                                    {{ ucfirst($subscription->billing_interval) }}
                                </td>


                                <td>
                                    £{{ number_format((float) $subscription->price, 2) }}
                                </td>


                                <td>

                                    <div>
                                        Users:
                                        {{ $subscription->max_users ?? 'Unlimited' }}
                                    </div>

                                    <div class="subscriptions-muted">
                                        Assessments:
                                        {{ $subscription->max_assessments ?? 'Unlimited' }}
                                    </div>

                                </td>


                                <td>
                                    {{ $subscription->starts_at?->format('d M Y') ?? '—' }}
                                </td>


                                <td>
                                    {{ $subscription->ends_at?->format('d M Y') ?? '—' }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route(
                                            'subscriptions.edit',
                                            $subscription
                                        ) }}"
                                        class="subscription-action"
                                    >
                                        Edit
                                    </a>


                                    @if(! in_array(
                                        $subscription->status,
                                        ['cancelled', 'expired'],
                                        true
                                    ))

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'subscriptions.cancel',
                                                $subscription
                                            ) }}"
                                            class="subscription-cancel-form"
                                            onsubmit="
                                                return confirm(
                                                    'Cancel this subscription?'
                                                );
                                            "
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="subscription-cancel-button"
                                            >
                                                Cancel
                                            </button>

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="subscriptions-pagination">
                {{ $subscriptions->links() }}
            </div>

        @else

            <div class="subscriptions-empty">

                <strong>
                    No subscriptions found.
                </strong>

                <div style="margin-top: 6px;">
                    Create your first organisation subscription to get started.
                </div>

            </div>

        @endif

    </div>


    <div style="
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-top: 20px;
    ">

        <div class="subscription-stat">

            <div class="subscription-stat-label">
                Active / Trial Monthly Value
            </div>

            <div class="subscription-stat-value">
                £{{ number_format((float) $monthlyRevenue, 2) }}
            </div>

            <div class="subscription-stat-meta">
                Current monthly subscriptions
            </div>

        </div>


        <div class="subscription-stat">

            <div class="subscription-stat-label">
                Active / Trial Annual Value
            </div>

            <div class="subscription-stat-value">
                £{{ number_format((float) $annualRevenue, 2) }}
            </div>

            <div class="subscription-stat-meta">
                Current yearly subscriptions
            </div>

        </div>

    </div>

</div>

@endsection