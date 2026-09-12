<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SuperAdminBillingController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(
            $request->user()->hasRole('super-admin'),
            403
        );

        $subscriptions = Subscription::with('organisation')
            ->orderByRaw("
                CASE status
                    WHEN 'active' THEN 1
                    WHEN 'trial' THEN 2
                    WHEN 'expired' THEN 3
                    WHEN 'cancelled' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('ends_at')
            ->get();

        $activeSubscriptions = $subscriptions
            ->where('status', 'active');

        $trialSubscriptions = $subscriptions
            ->where('status', 'trial');

        $expiredSubscriptions = $subscriptions
            ->where('status', 'expired');

        $cancelledSubscriptions = $subscriptions
            ->where('status', 'cancelled');

        $monthlyRecurringRevenue = $activeSubscriptions
            ->merge($trialSubscriptions)
            ->sum(function ($subscription) {
                return $subscription->billing_interval === 'monthly'
                    ? (float) $subscription->price
                    : (float) $subscription->price / 12;
            });

        $annualRecurringRevenue = $activeSubscriptions
            ->merge($trialSubscriptions)
            ->sum(function ($subscription) {
                return $subscription->billing_interval === 'monthly'
                    ? (float) $subscription->price * 12
                    : (float) $subscription->price;
            });

        $activeMonthlyValue = $activeSubscriptions->sum(function ($subscription) {
            return $subscription->billing_interval === 'monthly'
                ? (float) $subscription->price
                : (float) $subscription->price / 12;
        });

        $activeAnnualValue = $activeSubscriptions->sum(function ($subscription) {
            return $subscription->billing_interval === 'monthly'
                ? (float) $subscription->price * 12
                : (float) $subscription->price;
        });

        $trialMonthlyValue = $trialSubscriptions->sum(function ($subscription) {
            return $subscription->billing_interval === 'monthly'
                ? (float) $subscription->price
                : (float) $subscription->price / 12;
        });

        $trialAnnualValue = $trialSubscriptions->sum(function ($subscription) {
            return $subscription->billing_interval === 'monthly'
                ? (float) $subscription->price * 12
                : (float) $subscription->price;
        });

        $expiringSoon = $subscriptions
            ->filter(function ($subscription) {
                return in_array(
                    $subscription->status,
                    ['active', 'trial'],
                    true
                )
                && $subscription->ends_at !== null
                && $subscription->ends_at->between(
                    today(),
                    today()->copy()->addDays(30)
                );
            })
            ->sortBy('ends_at');

        $expiredRecently = $subscriptions
            ->filter(function ($subscription) {
                return $subscription->status === 'expired'
                    && $subscription->ends_at !== null
                    && $subscription->ends_at->between(
                        today()->copy()->subDays(30),
                        today()
                    );
            })
            ->sortByDesc('ends_at');

        $totalCollectedValue = $subscriptions->sum(
            fn ($subscription) => (float) $subscription->price
        );

        return view('billing.index', compact(
            'subscriptions',
            'activeSubscriptions',
            'trialSubscriptions',
            'expiredSubscriptions',
            'cancelledSubscriptions',
            'monthlyRecurringRevenue',
            'annualRecurringRevenue',
            'activeMonthlyValue',
            'activeAnnualValue',
            'trialMonthlyValue',
            'trialAnnualValue',
            'expiringSoon',
            'expiredRecently',
            'totalCollectedValue'
        ));
    }
}
