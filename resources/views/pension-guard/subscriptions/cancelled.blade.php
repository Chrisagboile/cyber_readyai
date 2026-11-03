@extends('pension-guard.layout')

@section('content')
<div class="container py-5">
    <h1>Checkout cancelled</h1>

    <p>
        No subscription was confirmed through this checkout.
        You can return to the subscription page whenever you are ready.
    </p>

    <a href="{{ route('pension-guard.subscriptions.index') }}"
       class="btn btn-primary">
        Return to subscriptions
    </a>
</div>
@endsection