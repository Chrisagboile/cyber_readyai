<?php

namespace App\Modules\PensionAccountGuard\Services;

use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Models\PensionGuardSubscription;
use Stripe\StripeClient;
use RuntimeException;

class StripeSubscriptionService
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $secret = config('pension_guard_billing.stripe.secret');

        if (empty($secret)) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        $this->stripe = new StripeClient($secret);
    }

    /**
     * Create a Stripe customer for a Pension Guard user,
     * or reuse the customer's existing Stripe ID.
     */
    public function getOrCreateCustomer(PensionGuardUser $user): string
    {
        if (!empty($user->stripe_customer_id)) {
            return $user->stripe_customer_id;
        }

        $customer = $this->stripe->customers->create([
            'name' => $user->name,
            'email' => $user->email,
            'metadata' => [
                'pension_guard_user_id' => (string) $user->id,
                'application' => 'Pension Account Guard',
            ],
        ]);

        $user->forceFill([
            'stripe_customer_id' => $customer->id,
        ])->save();

        return $customer->id;
    }

    /**
     * Create a Stripe Checkout Session for a monthly or annual plan.
     */
    public function createCheckoutSession(
        PensionGuardUser $user,
        string $interval
    ) {
        if (!in_array($interval, ['monthly', 'annual'], true)) {
            throw new RuntimeException('Invalid subscription interval.');
        }

        $priceId = config("pension_guard_billing.prices.{$interval}");

        if (empty($priceId)) {
            throw new RuntimeException(
                "The Stripe {$interval} price ID is not configured."
            );
        }

        $customerId = $this->getOrCreateCustomer($user);

        return $this->stripe->checkout->sessions->create([
            'mode' => 'subscription',
            'customer' => $customerId,
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ],
            ],
            'client_reference_id' => (string) $user->id,
            'metadata' => [
                'pension_guard_user_id' => (string) $user->id,
                'billing_interval' => $interval,
            ],
            'subscription_data' => [
                'metadata' => [
                    'pension_guard_user_id' => (string) $user->id,
                    'billing_interval' => $interval,
                ],
            ],
            'success_url' => route('pension-guard.subscriptions.success')
                . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('pension-guard.subscriptions.cancelled'),
            'allow_promotion_codes' => true,
        ]);
    }

    /**
     * Create a Stripe Billing Portal session for the signed-in user.
     */
    public function createBillingPortalSession(
        PensionGuardUser $user
    ) {
        if (empty($user->stripe_customer_id)) {
            throw new RuntimeException(
                'No Stripe customer account exists for this user.'
            );
        }

        return $this->stripe->billingPortal->sessions->create([
            'customer' => $user->stripe_customer_id,
            'return_url' => route('pension-guard.subscriptions.index'),
        ]);
    }

    /**
     * Schedule cancellation at the end of the current billing period.
     *
     * The subscription is not cancelled immediately.
     */
    public function cancelAtPeriodEnd(
        PensionGuardUser $user,
        PensionGuardSubscription $subscription
    ) {
        // Ensure the subscription belongs to the signed-in user.
        if ((int) $subscription->user_id !== (int) $user->id) {
            throw new RuntimeException(
                'You are not authorised to cancel this subscription.'
            );
        }

        if (empty($subscription->stripe_subscription_id)) {
            throw new RuntimeException(
                'This subscription does not have a valid Stripe subscription ID.'
            );
        }

        if (!in_array($subscription->status, [
            'active',
            'trialing',
            'past_due',
        ], true)) {
            throw new RuntimeException(
                'This subscription cannot be cancelled in its current status.'
            );
        }

        // Ask Stripe to schedule cancellation at period end.
        // The signed webhook will update the local subscription record.
        return $this->stripe->subscriptions->update(
            $subscription->stripe_subscription_id,
            [
                'cancel_at_period_end' => true,
            ]
        );
    }

}