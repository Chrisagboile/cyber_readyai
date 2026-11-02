@extends('pension-guard.layout')

@section('content')
<div class="container py-4">

    <h1>Premium Subscription</h1>

    <p>
        Protect your pension with additional Pension Account Guard
        features.
    </p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4 mt-3">

        @foreach($plans as $plan)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">

                        <h3>{{ $plan['name'] }}</h3>

                        @if($plan['interval'] === 'monthly')
                            <p>Illustrative price: £4.00 per month</p>
                        @else
                            <p>Illustrative price: £48.00 per year</p>
                        @endif

                        @if(!$plan['price_id'])
                            <p class="text-muted">
                                This plan is not yet configured.
                            </p>
                        @elseif(config('pension_guard_billing.enabled'))
                            <form method="POST"
                                  action="{{ route('pension-guard.subscriptions.checkout') }}">
                                @csrf

                                <input type="hidden"
                                       name="interval"
                                       value="{{ $plan['interval'] }}">

                                <button type="submit" class="btn btn-primary">
                                    Subscribe
                                </button>
                            </form>
                        @else
                            <p>Premium subscriptions are not currently available.</p>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach

    </div>

    <hr class="my-4">

    <h2>Your subscriptions</h2>

    @forelse($subscriptions as $subscription)
        <div class="card mb-3">
            <div class="card-body">

                <strong>
                    {{ $subscription->plan?->name ?? 'Premium subscription' }}
                </strong>

                <p class="mb-1">
                    Status: {{ ucfirst($subscription->status) }}
                </p>

                <p class="mb-0">
                    Billing interval:
                    {{ ucfirst($subscription->billing_interval ?? 'Not specified') }}
                </p>

                @if($subscription->current_period_end)
                    <p class="mb-2">
                        Current period ends:
                        {{ $subscription->current_period_end->format('d M Y') }}
                    </p>
                @endif

                @if(
                    in_array($subscription->status, ['active', 'trialing', 'past_due'], true)
                    && !$subscription->cancel_at_period_end
                )
                    <form
                        method="POST"
                        action="{{ route('pension-guard.subscriptions.cancel', $subscription->id) }}"
                        onsubmit="return confirm('Are you sure you want to schedule cancellation at the end of your billing period?');"
                        class="mt-3"
                    >
                        @csrf

                        <button type="submit" class="btn btn-outline-danger">
                            Cancel subscription
                        </button>
                    </form>

                @elseif($subscription->cancel_at_period_end)
                    <div class="alert alert-warning mt-3 mb-0">
                        <strong>Cancellation scheduled.</strong>

                        Your Premium access is expected to continue until
                        @if($subscription->current_period_end)
                            {{ $subscription->current_period_end->format('d M Y') }}.
                        @else
                            the end of your current billing period.
                        @endif
                    </div>
                @endif

            </div>
        </div>
    @empty
        <p>You do not have any recorded subscriptions yet.</p>
    @endforelse

    <h2 class="mt-4">Payment history</h2>

    @forelse($payments as $payment)
        <div class="card mb-2">
            <div class="card-body">
                <strong>
                    {{ strtoupper($payment->currency) }}
                    {{ number_format($payment->amount_pence / 100, 2) }}
                </strong>

                <span class="ms-2">
                    {{ ucfirst($payment->status) }}
                </span>

                @if($payment->paid_at)
                    <p class="mb-0">
                        {{ $payment->paid_at->format('d M Y H:i') }}
                    </p>
                @endif
            </div>
        </div>
    @empty
        <p>No payments recorded yet.</p>
    @endforelse

    @if(auth('pension_guard')->user()->stripe_customer_id)
        <form method="POST"
              action="{{ route('pension-guard.subscriptions.billing-portal') }}"
              class="mt-4">
            @csrf

            <button type="submit" class="btn btn-outline-primary">
                Manage billing with Stripe
            </button>
        </form>
    @endif

</div>
@endsection
