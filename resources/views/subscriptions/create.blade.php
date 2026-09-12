@extends('layouts.app')

@section('content')

<style>
    .subscription-form-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 32px 20px 50px;
    }

    .subscription-form-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 25px;
    }

    .subscription-form-title {
        margin: 0;
        font-size: 30px;
        font-weight: 750;
        letter-spacing: -.6px;
        color: #111827;
    }

    .subscription-form-subtitle {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 15px;
    }

    .subscription-back {
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
    }

    .subscription-back:hover {
        background: #f9fafb;
        color: #111827;
    }

    .subscription-form-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
        padding: 26px;
    }

    .subscription-section {
        margin-bottom: 28px;
    }

    .subscription-section:last-child {
        margin-bottom: 0;
    }

    .subscription-section-title {
        margin: 0 0 5px;
        font-size: 17px;
        font-weight: 700;
        color: #111827;
    }

    .subscription-section-description {
        margin: 0 0 18px;
        color: #6b7280;
        font-size: 13px;
    }

    .subscription-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .subscription-field {
        display: flex;
        flex-direction: column;
    }

    .subscription-field-full {
        grid-column: 1 / -1;
    }

    .subscription-label {
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 650;
        color: #374151;
    }

    .subscription-required {
        color: #dc2626;
    }

    .subscription-input,
    .subscription-select,
    .subscription-textarea {
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

    .subscription-input:focus,
    .subscription-select:focus,
    .subscription-textarea:focus {
        border-color: #9ca3af;
        box-shadow: 0 0 0 3px rgba(17, 24, 39, .06);
    }

    .subscription-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .subscription-help {
        margin-top: 6px;
        font-size: 12px;
        color: #9ca3af;
    }

    .subscription-error {
        margin-top: 6px;
        font-size: 12px;
        color: #dc2626;
    }

    .subscription-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        margin-top: 28px;
        border-top: 1px solid #e5e7eb;
    }

    .subscription-cancel-link,
    .subscription-submit-button {
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

    .subscription-cancel-link {
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
    }

    .subscription-submit-button {
        border: 1px solid #111827;
        background: #111827;
        color: #ffffff;
    }

    .subscription-submit-button:hover {
        background: #1f2937;
    }

    @media (max-width: 720px) {
        .subscription-form-page {
            padding: 22px 14px 40px;
        }

        .subscription-form-header {
            flex-direction: column;
        }

        .subscription-back {
            width: 100%;
            justify-content: center;
        }

        .subscription-grid {
            grid-template-columns: 1fr;
        }

        .subscription-field-full {
            grid-column: auto;
        }

        .subscription-form-actions {
            flex-direction: column-reverse;
        }

        .subscription-cancel-link,
        .subscription-submit-button {
            width: 100%;
        }
    }
</style>

<div class="subscription-form-page">

    <div class="subscription-form-header">

        <div>
            <h1 class="subscription-form-title">
                Create Subscription
            </h1>

            <p class="subscription-form-subtitle">
                Create and assign a subscription plan to an organisation.
            </p>
        </div>

        <a
            href="{{ route('subscriptions.index') }}"
            class="subscription-back"
        >
            ← Back to Subscriptions
        </a>

    </div>


    <form
        method="POST"
        action="{{ route('subscriptions.store') }}"
        class="subscription-form-card"
    >

        @csrf


        {{-- Organisation & Plan --}}
        <div class="subscription-section">

            <h2 class="subscription-section-title">
                Organisation & Plan
            </h2>

            <p class="subscription-section-description">
                Select the organisation and define its subscription plan.
            </p>


            <div class="subscription-grid">

                <div class="subscription-field">

                    <label
                        for="organisation_id"
                        class="subscription-label"
                    >
                        Organisation
                        <span class="subscription-required">*</span>
                    </label>

                    <select
                        id="organisation_id"
                        name="organisation_id"
                        class="subscription-select"
                        required
                    >

                        <option value="">
                            Select an organisation
                        </option>

                        @foreach($organisations as $organisation)

                            <option
                                value="{{ $organisation->id }}"
                                @selected(
                                    old('organisation_id') == $organisation->id
                                )
                            >
                                {{ $organisation->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('organisation_id')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="subscription-field">

                    <label
                        for="plan_name"
                        class="subscription-label"
                    >
                        Plan Name
                        <span class="subscription-required">*</span>
                    </label>

                    <input
                        id="plan_name"
                        type="text"
                        name="plan_name"
                        class="subscription-input"
                        value="{{ old('plan_name') }}"
                        placeholder="e.g. Professional"
                        required
                    >

                    @error('plan_name')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Billing --}}
        <div class="subscription-section">

            <h2 class="subscription-section-title">
                Billing
            </h2>

            <p class="subscription-section-description">
                Configure the subscription status, billing interval and price.
            </p>


            <div class="subscription-grid">

                <div class="subscription-field">

                    <label
                        for="status"
                        class="subscription-label"
                    >
                        Status
                        <span class="subscription-required">*</span>
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="subscription-select"
                        required
                    >

                        <option
                            value="active"
                            @selected(old('status', 'active') === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="trial"
                            @selected(old('status') === 'trial')
                        >
                            Trial
                        </option>

                        <option
                            value="expired"
                            @selected(old('status') === 'expired')
                        >
                            Expired
                        </option>

                        <option
                            value="cancelled"
                            @selected(old('status') === 'cancelled')
                        >
                            Cancelled
                        </option>

                    </select>

                    @error('status')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="subscription-field">

                    <label
                        for="billing_interval"
                        class="subscription-label"
                    >
                        Billing Interval
                        <span class="subscription-required">*</span>
                    </label>

                    <select
                        id="billing_interval"
                        name="billing_interval"
                        class="subscription-select"
                        required
                    >

                        <option
                            value="monthly"
                            @selected(
                                old('billing_interval', 'monthly') === 'monthly'
                            )
                        >
                            Monthly
                        </option>

                        <option
                            value="yearly"
                            @selected(
                                old('billing_interval') === 'yearly'
                            )
                        >
                            Yearly
                        </option>

                    </select>

                    @error('billing_interval')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="subscription-field">

                    <label
                        for="price"
                        class="subscription-label"
                    >
                        Price
                        <span class="subscription-required">*</span>
                    </label>

                    <input
                        id="price"
                        type="number"
                        name="price"
                        class="subscription-input"
                        value="{{ old('price', '0.00') }}"
                        min="0"
                        step="0.01"
                        placeholder="0.00"
                        required
                    >

                    <div class="subscription-help">
                        Enter the amount charged for the selected billing interval.
                    </div>

                    @error('price')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Limits --}}
        <div class="subscription-section">

            <h2 class="subscription-section-title">
                Usage Limits
            </h2>

            <p class="subscription-section-description">
                Leave a limit blank to allow unlimited usage.
            </p>


            <div class="subscription-grid">

                <div class="subscription-field">

                    <label
                        for="max_users"
                        class="subscription-label"
                    >
                        Maximum Users
                    </label>

                    <input
                        id="max_users"
                        type="number"
                        name="max_users"
                        class="subscription-input"
                        value="{{ old('max_users') }}"
                        min="1"
                        placeholder="Unlimited"
                    >

                    @error('max_users')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="subscription-field">

                    <label
                        for="max_assessments"
                        class="subscription-label"
                    >
                        Maximum Assessments
                    </label>

                    <input
                        id="max_assessments"
                        type="number"
                        name="max_assessments"
                        class="subscription-input"
                        value="{{ old('max_assessments') }}"
                        min="1"
                        placeholder="Unlimited"
                    >

                    @error('max_assessments')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Dates --}}
        <div class="subscription-section">

            <h2 class="subscription-section-title">
                Subscription Dates
            </h2>

            <p class="subscription-section-description">
                Set when the subscription starts and, where applicable, when it ends.
            </p>


            <div class="subscription-grid">

                <div class="subscription-field">

                    <label
                        for="starts_at"
                        class="subscription-label"
                    >
                        Start Date
                        <span class="subscription-required">*</span>
                    </label>

                    <input
                        id="starts_at"
                        type="date"
                        name="starts_at"
                        class="subscription-input"
                        value="{{ old('starts_at', now()->toDateString()) }}"
                        required
                    >

                    @error('starts_at')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="subscription-field">

                    <label
                        for="ends_at"
                        class="subscription-label"
                    >
                        End Date
                    </label>

                    <input
                        id="ends_at"
                        type="date"
                        name="ends_at"
                        class="subscription-input"
                        value="{{ old('ends_at') }}"
                    >

                    @error('ends_at')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="subscription-field">

                    <label
                        for="trial_ends_at"
                        class="subscription-label"
                    >
                        Trial End Date
                    </label>

                    <input
                        id="trial_ends_at"
                        type="date"
                        name="trial_ends_at"
                        class="subscription-input"
                        value="{{ old('trial_ends_at') }}"
                    >

                    @error('trial_ends_at')
                        <div class="subscription-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Notes --}}
        <div class="subscription-section">

            <h2 class="subscription-section-title">
                Notes
            </h2>

            <p class="subscription-section-description">
                Optional internal notes for the subscription.
            </p>


            <div class="subscription-field">

                <label
                    for="notes"
                    class="subscription-label"
                >
                    Notes
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    class="subscription-textarea"
                    placeholder="Add any internal notes..."
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <div class="subscription-error">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        <div class="subscription-form-actions">

            <a
                href="{{ route('subscriptions.index') }}"
                class="subscription-cancel-link"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="subscription-submit-button"
            >
                Create Subscription
            </button>

        </div>

    </form>

</div>

@endsection