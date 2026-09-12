@extends('layouts.app')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Load subscriptions for the organisations on this page.
    |--------------------------------------------------------------------------
    | This avoids an N+1 query while allowing us to keep the existing
    | SuperAdminOrganisationController unchanged.
    */
    $organisations->getCollection()->load('subscriptions');
@endphp


<div class="super-tenants-page">

    <div class="super-tenants-header">

        <div>
            <span class="super-tenants-eyebrow">
                Platform Administration
            </span>

            <h1>Organisations</h1>

            <p>
                Manage CyberReadyAI organisations, tenant status,
                subscriptions, users, departments and assessment activity.
            </p>
        </div>

        <a
            href="{{ route('tenants.create') }}"
            class="super-tenants-btn super-tenants-btn-primary"
        >
            + Add Organisation
        </a>

    </div>


    {{-- =========================================================
        SUMMARY
    ========================================================== --}}
    <div class="super-tenants-summary">

        <div>
            <span>Total Organisations</span>
            <strong>{{ $totalOrganisations }}</strong>
        </div>

        <div>
            <span>Active</span>
            <strong>{{ $activeOrganisations }}</strong>
        </div>

        <div>
            <span>Inactive</span>
            <strong>{{ $inactiveOrganisations }}</strong>
        </div>

    </div>


    {{-- =========================================================
        SEARCH
    ========================================================== --}}
    <div class="super-tenants-card">

        <form
            method="GET"
            action="{{ route('tenants.index') }}"
            class="super-tenants-search"
        >

            <div>
                <label for="search">Search organisations</label>

                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Organisation name or slug..."
                >
            </div>

            <button
                type="submit"
                class="super-tenants-btn super-tenants-btn-primary"
            >
                Search
            </button>

            @if($search !== '')

                <a
                    href="{{ route('tenants.index') }}"
                    class="super-tenants-btn super-tenants-btn-secondary"
                >
                    Reset
                </a>

            @endif

        </form>

    </div>


    {{-- =========================================================
        ORGANISATION TABLE
    ========================================================== --}}
    <div class="super-tenants-card">

        <div class="super-tenants-section-heading">

            <div>
                <h2>Organisation Directory</h2>

                <p>
                    {{ $organisations->total() }}
                    organisation{{ $organisations->total() === 1 ? '' : 's' }}
                    found.
                </p>
            </div>

        </div>


        @if($organisations->count())

            <div class="super-tenants-table-wrap">

                <table class="super-tenants-table">

                    <thead>

                        <tr>
                            <th>Organisation</th>
                            <th>Status</th>
                            <th>Subscription</th>
                            <th>Plan</th>
                            <th>Subscription End</th>
                            <th>Users</th>
                            <th>Employees</th>
                            <th>Managers</th>
                            <th>Departments</th>
                            <th>Assessments</th>
                            <th>Actions</th>
                        </tr>

                    </thead>


                    <tbody>

                    @foreach($organisations as $organisation)

                        @php

                            $stats = $organisation->platform_stats;

                            $status = strtolower(
                                (string) $organisation->status
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | Latest subscription
                            |--------------------------------------------------------------------------
                            */
                            $subscription = $organisation->subscriptions
                                ->sortByDesc(function ($item) {
                                    return $item->starts_at
                                        ? $item->starts_at->timestamp
                                        : 0;
                                })
                                ->sortByDesc(function ($item) {
                                    return $item->created_at
                                        ? $item->created_at->timestamp
                                        : 0;
                                })
                                ->first();

                            $subscriptionStatus = strtolower(
                                (string) ($subscription?->status ?? '')
                            );

                        @endphp


                        <tr>

                            {{-- Organisation --}}
                            <td>

                                <div class="super-tenants-org">

                                    <div class="super-tenants-org-icon">
                                        {{ strtoupper(
                                            substr($organisation->name, 0, 1)
                                        ) }}
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $organisation->name }}
                                        </strong>

                                        <span>
                                            {{ $organisation->slug }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- Organisation Status --}}
                            <td>

                                @if($status === 'active')

                                    <span class="
                                        super-tenants-badge
                                        super-tenants-badge-success
                                    ">
                                        Active
                                    </span>

                                @else

                                    <span class="
                                        super-tenants-badge
                                        super-tenants-badge-neutral
                                    ">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Subscription Status --}}
                            <td>

                                @if($subscription)

                                    @if($subscriptionStatus === 'active')

                                        <span class="
                                            super-tenants-badge
                                            super-tenants-badge-success
                                        ">
                                            Active
                                        </span>

                                    @elseif($subscriptionStatus === 'trial')

                                        <span class="
                                            super-tenants-badge
                                            super-tenants-badge-info
                                        ">
                                            Trial
                                        </span>

                                    @elseif($subscriptionStatus === 'expired')

                                        <span class="
                                            super-tenants-badge
                                            super-tenants-badge-warning
                                        ">
                                            Expired
                                        </span>

                                    @elseif($subscriptionStatus === 'cancelled')

                                        <span class="
                                            super-tenants-badge
                                            super-tenants-badge-danger
                                        ">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="
                                            super-tenants-badge
                                            super-tenants-badge-neutral
                                        ">
                                            {{ ucfirst(
                                                $subscription->status
                                            ) }}
                                        </span>

                                    @endif

                                @else

                                    <span class="
                                        super-tenants-badge
                                        super-tenants-badge-neutral
                                    ">
                                        No Subscription
                                    </span>

                                @endif

                            </td>


                            {{-- Plan --}}
                            <td>

                                @if($subscription)

                                    <div class="super-tenants-plan">

                                        <strong>
                                            {{ $subscription->plan_name }}
                                        </strong>

                                        <span>
                                            £{{ number_format(
                                                (float) $subscription->price,
                                                2
                                            ) }}
                                            /
                                            {{ $subscription->billing_interval === 'yearly'
                                                ? 'year'
                                                : 'month'
                                            }}
                                        </span>

                                    </div>

                                @else

                                    <span class="super-tenants-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Subscription End --}}
                            <td>

                                @if($subscription?->ends_at)

                                    @php
                                        $subscriptionEnd = $subscription->ends_at;
                                    @endphp

                                    <div class="super-tenants-date">

                                        <strong>
                                            {{ $subscriptionEnd->format('d M Y') }}
                                        </strong>

                                        @if(
                                            $subscriptionStatus !== 'cancelled'
                                            &&
                                            $subscriptionStatus !== 'expired'
                                        )

                                            @if($subscriptionEnd->isPast())

                                                <span class="super-tenants-date-overdue">
                                                    Expired
                                                </span>

                                            @elseif($subscriptionEnd->isToday())

                                                <span class="super-tenants-date-warning">
                                                    Ends today
                                                </span>

                                            @elseif($subscriptionEnd->diffInDays(now()) <= 30)

                                                <span class="super-tenants-date-warning">
                                                    {{ $subscriptionEnd->diffInDays(now()) }}
                                                    days left
                                                </span>

                                            @endif

                                        @endif

                                    </div>

                                @else

                                    <span class="super-tenants-muted">
                                        No end date
                                    </span>

                                @endif

                            </td>


                            {{-- Users --}}
                            <td>
                                {{ $stats->users }}
                            </td>


                            {{-- Employees --}}
                            <td>
                                {{ $stats->employees }}
                            </td>


                            {{-- Managers --}}
                            <td>
                                {{ $stats->managers }}
                            </td>


                            {{-- Departments --}}
                            <td>
                                {{ $stats->departments }}
                            </td>


                            {{-- Assessments --}}
                            <td>
                                {{ $stats->assessments }}
                            </td>


                            {{-- Actions --}}
                            <td>

                                <div class="super-tenants-actions">

                                    <a
                                        href="{{ route(
                                            'tenants.edit',
                                            $organisation
                                        ) }}"
                                        class="super-tenants-action-link"
                                    >
                                        Edit
                                    </a>


                                    @if($subscription)

                                        <a
                                            href="{{ route(
                                                'subscriptions.edit',
                                                $subscription
                                            ) }}"
                                            class="super-tenants-action-link"
                                        >
                                            Subscription
                                        </a>

                                    @else

                                        <a
                                            href="{{ route(
                                                'subscriptions.create'
                                            ) }}?organisation_id={{ $organisation->id }}"
                                            class="super-tenants-action-link"
                                        >
                                            Add Subscription
                                        </a>

                                    @endif


                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'tenants.toggle-status',
                                            $organisation
                                        ) }}"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="super-tenants-action-link"
                                        >
                                            {{ $status === 'active'
                                                ? 'Deactivate'
                                                : 'Activate'
                                            }}
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>


            <div class="super-tenants-pagination">
                {{ $organisations->links() }}
            </div>

        @else

            <div class="super-tenants-empty">

                <div class="super-tenants-empty-icon">
                    O
                </div>

                <h3>No organisations found</h3>

                <p>
                    Create an organisation to begin managing a new tenant.
                </p>

                <a
                    href="{{ route('tenants.create') }}"
                    class="super-tenants-btn super-tenants-btn-primary"
                >
                    Add Organisation
                </a>

            </div>

        @endif

    </div>

</div>


<style>

.super-tenants-page {
    max-width: 1500px;
    margin: 0 auto;
    padding: 8px 0 45px;
}

.super-tenants-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 25px;
    margin-bottom: 23px;
}

.super-tenants-eyebrow {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .09em;
    opacity: .52;
}

.super-tenants-header h1 {
    margin: 0;
    font-size: 30px;
}

.super-tenants-header p {
    margin: 8px 0 0;
    max-width: 760px;
    font-size: 13px;
    opacity: .62;
}

.super-tenants-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 13px;
    margin-bottom: 22px;
}

.super-tenants-summary > div {
    padding: 17px;
    border: 1px solid rgba(127,127,127,.14);
    border-radius: 14px;
    background: var(--card-bg, #fff);
    box-shadow: 0 5px 18px rgba(0,0,0,.03);
}

.super-tenants-summary span,
.super-tenants-summary strong {
    display: block;
}

.super-tenants-summary span {
    margin-bottom: 5px;
    font-size: 11px;
    opacity: .55;
}

.super-tenants-summary strong {
    font-size: 25px;
}

.super-tenants-card {
    margin-bottom: 22px;
    padding: 22px;
    border: 1px solid rgba(127,127,127,.14);
    border-radius: 16px;
    background: var(--card-bg, #fff);
    box-shadow: 0 7px 24px rgba(0,0,0,.035);
}

.super-tenants-search {
    display: flex;
    align-items: flex-end;
    gap: 10px;
}

.super-tenants-search > div {
    flex: 1;
}

.super-tenants-search label {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 700;
}

.super-tenants-search input {
    width: 100%;
    min-height: 42px;
    padding: 0 12px;
    border: 1px solid rgba(127,127,127,.18);
    border-radius: 9px;
    background: transparent;
    color: inherit;
    font-size: 13px;
}

.super-tenants-search input:focus {
    outline: none;
    border-color: rgba(80,80,80,.55);
}

.super-tenants-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 41px;
    padding: 0 15px;
    border-radius: 9px;
    border: 1px solid transparent;
    text-decoration: none;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    white-space: nowrap;
}

.super-tenants-btn-primary {
    background: #111827;
    color: #fff;
}

.super-tenants-btn-primary:hover {
    color: #fff;
    opacity: .92;
}

.super-tenants-btn-secondary {
    background: rgba(127,127,127,.08);
    border-color: rgba(127,127,127,.15);
    color: inherit;
}

.super-tenants-btn-secondary:hover {
    color: inherit;
    background: rgba(127,127,127,.13);
}

.super-tenants-section-heading {
    margin-bottom: 19px;
}

.super-tenants-section-heading h2 {
    margin: 0;
    font-size: 18px;
}

.super-tenants-section-heading p {
    margin: 5px 0 0;
    font-size: 12px;
    opacity: .57;
}

.super-tenants-table-wrap {
    overflow-x: auto;
}

.super-tenants-table {
    width: 100%;
    min-width: 1350px;
    border-collapse: collapse;
}

.super-tenants-table th {
    padding: 11px 10px;
    text-align: left;
    border-bottom: 1px solid rgba(127,127,127,.14);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: .05em;
    opacity: .53;
    white-space: nowrap;
}

.super-tenants-table td {
    padding: 14px 10px;
    border-bottom: 1px solid rgba(127,127,127,.09);
    font-size: 12px;
    white-space: nowrap;
    vertical-align: middle;
}

.super-tenants-table tr:last-child td {
    border-bottom: 0;
}

.super-tenants-org {
    display: flex;
    align-items: center;
    gap: 11px;
}

.super-tenants-org-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    border-radius: 10px;
    background: rgba(127,127,127,.09);
    font-size: 12px;
    font-weight: 800;
}

.super-tenants-org strong,
.super-tenants-org span {
    display: block;
}

.super-tenants-org strong {
    font-size: 12px;
}

.super-tenants-org span {
    margin-top: 3px;
    font-size: 10px;
    opacity: .5;
}

.super-tenants-badge {
    display: inline-flex;
    align-items: center;
    min-height: 24px;
    padding: 0 8px;
    border-radius: 999px;
    font-size: 9px;
    font-weight: 800;
}

.super-tenants-badge-success {
    color: #25784c;
    background: rgba(46,157,99,.11);
}

.super-tenants-badge-info {
    color: #245ea8;
    background: rgba(59,130,246,.11);
}

.super-tenants-badge-warning {
    color: #9a6a00;
    background: rgba(245,158,11,.14);
}

.super-tenants-badge-danger {
    color: #a13d3d;
    background: rgba(220,38,38,.10);
}

.super-tenants-badge-neutral {
    color: inherit;
    background: rgba(127,127,127,.1);
}

.super-tenants-plan strong,
.super-tenants-plan span {
    display: block;
}

.super-tenants-plan strong {
    font-size: 11px;
}

.super-tenants-plan span {
    margin-top: 3px;
    font-size: 10px;
    opacity: .58;
}

.super-tenants-muted {
    opacity: .48;
}

.super-tenants-date strong,
.super-tenants-date span {
    display: block;
}

.super-tenants-date-warning,
.super-tenants-date-overdue {
    margin-top: 3px;
    font-size: 9px;
    font-weight: 800;
}

.super-tenants-date-warning {
    color: #9a6a00;
}

.super-tenants-date-overdue {
    color: #a13d3d;
}

.super-tenants-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.super-tenants-actions form {
    margin: 0;
}

.super-tenants-action-link {
    padding: 0;
    border: 0;
    background: transparent;
    color: inherit;
    text-decoration: none;
    font-size: 11px;
    font-weight: 800;
    cursor: pointer;
}

.super-tenants-action-link:hover {
    text-decoration: underline;
}

.super-tenants-pagination {
    margin-top: 17px;
}

.super-tenants-empty {
    padding: 38px 15px 23px;
    text-align: center;
}

.super-tenants-empty-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    margin: 0 auto 12px;
    border-radius: 50%;
    background: rgba(127,127,127,.09);
    font-size: 12px;
    font-weight: 800;
}

.super-tenants-empty h3 {
    margin: 0 0 7px;
    font-size: 16px;
}

.super-tenants-empty p {
    margin: 0 auto 18px;
    font-size: 12px;
    opacity: .56;
}

@media (max-width: 850px) {

    .super-tenants-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .super-tenants-summary {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 650px) {

    .super-tenants-search {
        flex-direction: column;
        align-items: stretch;
    }

    .super-tenants-search .super-tenants-btn {
        width: 100%;
    }

    .super-tenants-card {
        padding: 17px;
    }

}

</style>

@endsection
