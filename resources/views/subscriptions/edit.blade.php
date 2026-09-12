@extends('layouts.app')

@section('content')

<style>
    .subscription-edit-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 20px 50px;
    }

    .subscription-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 25px;
    }

    .subscription-edit-title {
        margin: 0;
        font-size: 30px;
        font-weight: 750;
        letter-spacing: -.6px;
        color: #111827;
    }

    .subscription-edit-subtitle {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 15px;
    }

    .subscription-edit-back {
        display: inline-flex;
        align-items: center;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        text-decoration: none;
        color: #374151;
        background: #ffffff;
        font-size: 14px;
        font-weight: 650;
        white-space: nowrap;
    }

    .subscription-edit-back:hover {
        background: #f9fafb;
        color: #111827;
    }

    .subscription-edit-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 20px;
        align-items: start;
    }

    .subscription-edit-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
        padding: 26px;
    }

    .subscription-edit-section {
        margin-bottom: 28px;
    }

    .subscription-edit-section:last-child {
        margin-bottom: 0;
    }

    .subscription-edit-section-title {
        margin: 0 0 5px;
        font-size: 17px;
        font-weight: 700;
        color: #111827;
    }

    .subscription-edit-section-description {
        margin: 0 0 18px;
        color: #6b7280;
        font-size: 13px;
    }

    .subscription-edit-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .subscription-edit-field {
        display: flex;
        flex-direction: column;
    }

    .subscription-edit-field-full {
        grid-column: 1 / -1;
    }

    .subscription-edit-label {
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 650;
        color: #374151;
    }

    .subscription-edit-required {
        color: #dc2626;
    }

    .subscription-edit-input,
    .subscription-edit-select,
    .subscription-edit-textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 9px;
        padding: 11px 12px;
        font-size: 14px;
        color: #111827;
        background: #ffffff;
        outline: none;
        transition: .2s ease;
    }

    .subscription-edit-input:focus,
    .subscription-edit-select:focus,
    .subscription-edit-textarea:focus {
        border-color: #9ca3af;
        box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
    }

    .subscription-edit-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .subscription-edit-help {
        margin-top: 6px;
        font-size: 12px;
        color: #9ca3af;
    }

    .subscription-edit-error {
        margin-top: 6px;
        font-size: 12px;
        color: #dc2626;
    }

    .subscription-edit-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 28px;
        border-top: 1px solid #e5e7eb;
    }

    .subscription-edit-cancel,
    .subscription-edit-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 17px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
    }

    .subscription-edit-cancel {
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
    }

    .subscription-edit-save {
        border: 1px solid #111827;
        background: #111827;
        color: #ffffff;
    }

    .subscription-edit-save:hover {
        background: #1f2937;
    }

    .subscription-summary {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
    }

    .subscription-summary-title {
        margin: 0 0 16px;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .subscription-summary-row {
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .subscription-summary-row:last-child {
        border-bottom: 0;
    }

    .subscription-summary-label {
        margin-bottom: 4px;
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .subscription-summary-value {
        font-size: 14px;
        font-weight: 650;
        color: #111827;
        word-break: break-word;
    }

    .subscription-status {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .subscription-status-active {
        background: #dcfce7;
        color: #166534;
    }

    .subscription-status-trial {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .subscription-status-expired {
        background: #fef3c7;
        color: #92400e;
    }

    .subscription-status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    @media (max-width: 900px) {
        .subscription-edit-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 720px) {
        .subscription-edit-page {
            padding: 22px 14px 40px;
        }

        .subscription-edit-header {
            flex-direction: column;
        }

        .subscription-edit-back {
            width: 100%;
            justify-content: center;
        }

        .subscription-edit-grid {
            grid-template-columns: 1fr;
        }

        .subscription-edit-field-full {
            grid-column: auto;
        }

        .subscription-edit-actions {
            flex-direction: column-reverse;
        }

        .subscription-edit-cancel,
        .subscription-edit-save {
            width: 100%;
        }
    }
</style>

<div class="subscription-edit-page">

    <div class="subscription-edit-header">

        <div>
            <h1 class="subscription-edit-title">
                Edit Subscription
            </h1>

            <p class="subscription-edit-subtitle">
                Update the organisation plan, billing and subscription status.
            </p>
        </div>

        <a
            href="{{ route('subscriptions.index') }}"
            class="subscription-edit-back"
        >
            ← Back to Subscriptions
        </a>

    </div>


    <div class="subscription-edit-layout">

        <form
            method="POST"
            action="{{ route('subscriptions.update', $subscription) }}"
            class="subscription-edit-card"
        >

            @csrf
            @method('PUT')


            {{-- Organisation & Plan --}}
            <div class="subscription-edit-section">

                <h2 class="subscription-edit-section-title">
                    Organisation & Plan
                </h2>

                <p class="subscription-edit-section-description">
                    Update the organisation and plan assigned to this subscription.
                </p>


                <div class="subscription-edit-grid">

                    <div class="subscription-edit-field">

                        <label
                            for="organisation_id"
                            class="subscription-edit-label"
                        >
                            Organisation
                            <span class="subscription-edit-required">*</span>
                        </label>

                        <select
                            id="organisation_id"
                            name="organisation_id"
                            class="subscription-edit-select"
                            required
                        >

                            <option value="">
                                Select an organisation
                            </option>

                            @foreach($organisations as $organisation)

                                <option
                                    value="{{ $organisation->id }}"
                                    @selected(
                                        old(
                                            'organisation_id',
                                            $subscription->organisation_id
                                        ) == $organisation->id
                                    )
                                >
                                    {{ $organisation->name }}
                                </option>

                            @endforeach

                        </select>

                        @error('organisation_id')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="subscription-edit-field">

                        <label
                            for="plan_name"
                            class="subscription-edit-label"
                        >
                            Plan Name
                            <span class="subscription-edit-required">*</span>
                        </label>

                        <input
                            id="plan_name"
                            type="text"
                            name="plan_name"
                            class="subscription-edit-input"
                            value="{{ old(
                                'plan_name',
                                $subscription->plan_name
                            ) }}"
                            required
                        >

                        @error('plan_name')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Billing --}}
            <div class="subscription-edit-section">

                <h2 class="subscription-edit-section-title">
                    Billing
                </h2>

                <p class="subscription-edit-section-description">
                    Update the billing interval, amount and current status.
                </p>


                <div class="subscription-edit-grid">

                    <div class="subscription-edit-field">

                        <label
                            for="status"
                            class="subscription-edit-label"
                        >
                            Status
                            <span class="subscription-edit-required">*</span>
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="subscription-edit-select"
                            required
                        >

                            @foreach([
                                'active' => 'Active',
                                'trial' => 'Trial',
                                'expired' => 'Expired',
                                'cancelled' => 'Cancelled',
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(
                                        old(
                                            'status',
                                            $subscription->status
                                        ) === $value
                                    )
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                        @error('status')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="subscription-edit-field">

                        <label
                            for="billing_interval"
                            class="subscription-edit-label"
                        >
                            Billing Interval
                            <span class="subscription-edit-required">*</span>
                        </label>

                        <select
                            id="billing_interval"
                            name="billing_interval"
                            class="subscription-edit-select"
                            required
                        >

                            <option
                                value="monthly"
                                @selected(
                                    old(
                                        'billing_interval',
                                        $subscription->billing_interval
                                    ) === 'monthly'
                                )
                            >
                                Monthly
                            </option>

                            <option
                                value="yearly"
                                @selected(
                                    old(
                                        'billing_interval',
                                        $subscription->billing_interval
                                    ) === 'yearly'
                                )
                            >
                                Yearly
                            </option>

                        </select>

                        @error('billing_interval')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="subscription-edit-field">

                        <label
                            for="price"
                            class="subscription-edit-label"
                        >
                            Price
                            <span class="subscription-edit-required">*</span>
                        </label>

                        <input
                            id="price"
                            type="number"
                            name="price"
                            class="subscription-edit-input"
                            value="{{ old(
                                'price',
                                $subscription->price
                            ) }}"
                            min="0"
                            step="0.01"
                            required
                        >

                        @error('price')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Limits --}}
            <div class="subscription-edit-section">

                <h2 class="subscription-edit-section-title">
                    Usage Limits
                </h2>

                <p class="subscription-edit-section-description">
                    Leave a limit blank for unlimited usage.
                </p>


                <div class="subscription-edit-grid">

                    <div class="subscription-edit-field">

                        <label
                            for="max_users"
                            class="subscription-edit-label"
                        >
                            Maximum Users
                        </label>

                        <input
                            id="max_users"
                            type="number"
                            name="max_users"
                            class="subscription-edit-input"
                            value="{{ old(
                                'max_users',
                                $subscription->max_users
                            ) }}"
                            min="1"
                            placeholder="Unlimited"
                        >

                        @error('max_users')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="subscription-edit-field">

                        <label
                            for="max_assessments"
                            class="subscription-edit-label"
                        >
                            Maximum Assessments
                        </label>

                        <input
                            id="max_assessments"
                            type="number"
                            name="max_assessments"
                            class="subscription-edit-input"
                            value="{{ old(
                                'max_assessments',
                                $subscription->max_assessments
                            ) }}"
                            min="1"
                            placeholder="Unlimited"
                        >

                        @error('max_assessments')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Dates --}}
            <div class="subscription-edit-section">

                <h2 class="subscription-edit-section-title">
                    Subscription Dates
                </h2>

                <p class="subscription-edit-section-description">
                    Update the subscription lifecycle dates.
                </p>


                <div class="subscription-edit-grid">

                    <div class="subscription-edit-field">

                        <label
                            for="starts_at"
                            class="subscription-edit-label"
                        >
                            Start Date
                            <span class="subscription-edit-required">*</span>
                        </label>

                        <input
                            id="starts_at"
                            type="date"
                            name="starts_at"
                            class="subscription-edit-input"
                            value="{{ old(
                                'starts_at',
                                $subscription->starts_at?->format('Y-m-d')
                            ) }}"
                            required
                        >

                        @error('starts_at')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="subscription-edit-field">

                        <label
                            for="ends_at"
                            class="subscription-edit-label"
                        >
                            End Date
                        </label>

                        <input
                            id="ends_at"
                            type="date"
                            name="ends_at"
                            class="subscription-edit-input"
                            value="{{ old(
                                'ends_at',
                                $subscription->ends_at?->format('Y-m-d')
                            ) }}"
                        >

                        @error('ends_at')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="subscription-edit-field">

                        <label
                            for="trial_ends_at"
                            class="subscription-edit-label"
                        >
                            Trial End Date
                        </label>

                        <input
                            id="trial_ends_at"
                            type="date"
                            name="trial_ends_at"
                            class="subscription-edit-input"
                            value="{{ old(
                                'trial_ends_at',
                                $subscription->trial_ends_at?->format('Y-m-d')
                            ) }}"
                        >

                        @error('trial_ends_at')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="subscription-edit-field">

                        <label
                            for="cancelled_at"
                            class="subscription-edit-label"
                        >
                            Cancellation Date
                        </label>

                        <input
                            id="cancelled_at"
                            type="date"
                            name="cancelled_at"
                            class="subscription-edit-input"
                            value="{{ old(
                                'cancelled_at',
                                $subscription->cancelled_at?->format('Y-m-d')
                            ) }}"
                        >

                        @error('cancelled_at')
                            <div class="subscription-edit-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Notes --}}
            <div class="subscription-edit-section">

                <h2 class="subscription-edit-section-title">
                    Notes
                </h2>

                <p class="subscription-edit-section-description">
                    Internal notes relating to this subscription.
                </p>


                <div class="subscription-edit-field">

                    <textarea
                        id="notes"
                        name="notes"
                        class="subscription-edit-textarea"
                        placeholder="Add internal notes..."
                    >{{ old(
                        'notes',
                        $subscription->notes
                    ) }}</textarea>

                    @error('notes')
                        <div class="subscription-edit-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            <div class="subscription-edit-actions">

                <a
                    href="{{ route('subscriptions.index') }}"
                    class="subscription-edit-cancel"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="subscription-edit-save"
                >
                    Save Changes
                </button>

            </div>

        </form>


        {{-- Subscription Summary --}}
        <aside class="subscription-summary">

            <h2 class="subscription-summary-title">
                Subscription Summary
            </h2>


            <div class="subscription-summary-row">

                <div class="subscription-summary-label">
                    Organisation
                </div>

                <div class="subscription-summary-value">
                    {{ $subscription->organisation?->name ?? 'Unknown' }}
                </div>

            </div>


            <div class="subscription-summary-row">

                <div class="subscription-summary-label">
                    Current Plan
                </div>

                <div class="subscription-summary-value">
                    {{ $subscription->plan_name }}
                </div>

            </div>


            <div class="subscription-summary-row">

                <div class="subscription-summary-label">
                    Current Status
                </div>

                <div class="subscription-summary-value">

                    <span class="
                        subscription-status
                        subscription-status-{{ $subscription->status }}
                    ">
                        {{ $subscription->status }}
                    </span>

                </div>

            </div>


            <div class="subscription-summary-row">

                <div class="subscription-summary-label">
                    Current Price
                </div>

                <div class="subscription-summary-value">
                    £{{ number_format(
                        (float) $subscription->price,
                        2
                    ) }}
                    /
                    {{ $subscription->billing_interval === 'yearly'
                        ? 'year'
                        : 'month' }}
                </div>

            </div>


            <div class="subscription-summary-row">

                <div class="subscription-summary-label">
                    Created
                </div>

                <div class="subscription-summary-value">
                    {{ $subscription->created_at?->format('d M Y') ?? '—' }}
                </div>

            </div>


            <div class="subscription-summary-row">

                <div class="subscription-summary-label">
                    Last Updated
                </div>

                <div class="subscription-summary-value">
                    {{ $subscription->updated_at?->format('d M Y') ?? '—' }}
                </div>

            </div>

        </aside>

    </div>

</div>

@endsection