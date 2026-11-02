@extends('pension-guard.layout')

@section('content')
<div class="container py-5">
    <h1>Checkout completed</h1>

    <p>
        Thank you. Stripe is confirming your subscription.
        Your premium access will become available once we receive
        and verify the payment confirmation.
    </p>

    <a href="{{ route('pension-guard.subscriptions.index') }}"
       class="btn btn-primary">
        View subscriptions
    </a>
</div>
@endsection