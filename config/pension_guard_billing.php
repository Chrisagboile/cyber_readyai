<?php

return [
    'enabled' => env('PENSION_GUARD_PREMIUM_ENABLED', false),

    'currency' => env('PENSION_GUARD_CURRENCY', 'gbp'),

    'prices' => [
        'monthly' => env('PENSION_GUARD_STRIPE_MONTHLY_PRICE'),
        'annual' => env('PENSION_GUARD_STRIPE_ANNUAL_PRICE'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'checkout' => [
        'success_route' => 'pension-guard.subscriptions.success',
        'cancel_route' => 'pension-guard.subscriptions.cancelled',
    ],
];
