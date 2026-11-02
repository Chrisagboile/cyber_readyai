<?php

namespace App\Modules\PensionAccountGuard\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PensionAccountGuard\Models\PensionGuardPayment;
use App\Modules\PensionAccountGuard\Models\PensionGuardPlan;
use App\Modules\PensionAccountGuard\Models\PensionGuardSubscription;
use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Models\PensionGuardWebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Throwable;
use UnexpectedValueException;

class PensionGuardStripeWebhookController extends Controller
{
    /**
     * Receive and process verified Stripe webhook events.
     */
    public function handle(Request $request)
    {
        $secret = config('pension_guard_billing.stripe.webhook_secret');

        if (empty($secret)) {
            return response('Webhook is not configured.', 503);
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $request->header('Stripe-Signature', ''),
                $secret
            );
        } catch (UnexpectedValueException | SignatureVerificationException $exception) {
            return response('Invalid webhook signature or payload.', 400);
        } catch (Throwable $exception) {
            report($exception);

            return response('Webhook verification failed.', 400);
        }

        try {
            DB::transaction(function () use ($event) {
                $storedEvent = PensionGuardWebhookEvent::where(
                    'stripe_event_id',
                    $event->id
                )->lockForUpdate()->first();

                if ($storedEvent && $storedEvent->processing_status === 'processed') {
                    return;
                }

                if (!$storedEvent) {
                    $storedEvent = PensionGuardWebhookEvent::create([
                        'stripe_event_id' => $event->id,
                        'event_type' => $event->type,
                        'processing_status' => 'pending',
                    ]);
                }

                $payload = json_decode(
                    json_encode($event->data->object),
                    true
                );

                if (!is_array($payload)) {
                    throw new UnexpectedValueException(
                        'Invalid Stripe event object.'
                    );
                }

                $this->processEvent($event->type, $payload);

                $storedEvent->update([
                    'processing_status' => 'processed',
                    'processed_at' => now(),
                    'failure_message' => null,
                ]);
            });

            return response('Webhook received.', 200);
        } catch (Throwable $exception) {
            report($exception);

            PensionGuardWebhookEvent::where(
                'stripe_event_id',
                $event->id
            )->update([
                'processing_status' => 'failed',
                'failure_message' => substr($exception->getMessage(), 0, 1000),
            ]);

            return response('Webhook processing failed.', 500);
        }
    }

    /**
     * Dispatch supported Stripe event types.
     */
    protected function processEvent(string $type, array $object): void
    {
        match ($type) {
            'checkout.session.completed' => $this->checkoutCompleted($object),
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted' => $this->subscriptionChanged($object),
            'invoice.paid',
            'invoice.payment_succeeded' => $this->invoicePaid($object),
            'invoice.payment_failed' => $this->invoiceFailed($object),
            default => null,
        };
    }

    /**
     * Link a completed Checkout Session to the local user.
     *
     * Subscription access is still controlled by subscription webhooks.
     */
    protected function checkoutCompleted(array $object): void
    {
        if (($object['mode'] ?? null) !== 'subscription') {
            return;
        }

        $customerId = $this->stripeId($object['customer'] ?? null);
        $subscriptionId = $this->stripeId($object['subscription'] ?? null);

        if (!$customerId || !$subscriptionId) {
            return;
        }

        $user = $this->findUser($customerId, $object['client_reference_id'] ?? null);

        if (!$user) {
            return;
        }

        $user->forceFill([
            'stripe_customer_id' => $customerId,
        ])->save();

        $subscription = PensionGuardSubscription::firstOrNew([
            'stripe_subscription_id' => $subscriptionId,
        ]);

        if ($subscription->exists && (int) $subscription->user_id !== (int) $user->id) {
            throw new UnexpectedValueException(
                'Stripe subscription is already linked to another user.'
            );
        }

        $subscription->user_id = $user->id;
        $subscription->stripe_customer_id = $customerId;
        $subscription->status = $subscription->status ?: 'incomplete';
        $subscription->billing_interval =
            $object['metadata']['billing_interval'] ?? $subscription->billing_interval;

        $subscription->save();
    }

    /**
     * Synchronise local subscription status from Stripe.
     */
    protected function subscriptionChanged(array $object): void
    {
        $subscriptionId = $this->stripeId($object['id'] ?? null);
        $customerId = $this->stripeId($object['customer'] ?? null);

        if (!$subscriptionId || !$customerId) {
            return;
        }

        $user = $this->findUser(
            $customerId,
            $object['metadata']['pension_guard_user_id'] ?? null
        );

        if (!$user) {
            return;
        }

        $existing = PensionGuardSubscription::where(
            'stripe_subscription_id',
            $subscriptionId
        )->first();

        if ($existing && (int) $existing->user_id !== (int) $user->id) {
            throw new UnexpectedValueException(
                'Subscription ownership mismatch.'
            );
        }

        $priceId = data_get($object, 'items.data.0.price.id');
        $plan = $priceId
            ? PensionGuardPlan::where('stripe_price_id', $priceId)->first()
            : null;

        $interval = $object['metadata']['billing_interval'] ?? null;

        if (!$interval && $plan) {
            $interval = $plan->billing_interval;
        }

        $status = $object['status'] ?? 'incomplete';

        PensionGuardSubscription::updateOrCreate(
            ['stripe_subscription_id' => $subscriptionId],
            [
                'user_id' => $user->id,
                'plan_id' => $plan?->id,
                'stripe_customer_id' => $customerId,
                'status' => $status,
                'billing_interval' => $interval,
                'current_period_start' => $this->stripeDate(
                    $object['current_period_start'] ?? null
                ),
                'current_period_end' => $this->stripeDate(
                    $object['current_period_end'] ?? null
                ),
                'trial_ends_at' => $this->stripeDate(
                    $object['trial_end'] ?? null
                ),
                'cancel_at' => $this->stripeDate(
                    $object['cancel_at'] ?? null
                ),
                'canceled_at' => $this->stripeDate(
                    $object['canceled_at'] ?? null
                ),
                'ended_at' => $status === 'canceled'
                    ? $this->stripeDate($object['ended_at'] ?? time())
                    : null,
                'cancel_at_period_end' => (bool) (
                    $object['cancel_at_period_end'] ?? false
                ),
            ]
        );
    }

    /**
     * Record a successfully paid invoice.
     */
    protected function invoicePaid(array $object): void
    {
        $customerId = $this->stripeId($object['customer'] ?? null);
        $invoiceId = $object['id'] ?? null;

        if (!$customerId || !$invoiceId) {
            return;
        }

        $user = $this->findUser($customerId);

        if (!$user) {
            return;
        }

        $subscriptionId = $this->invoiceSubscriptionId($object);

        $subscription = $subscriptionId
            ? PensionGuardSubscription::where(
                'stripe_subscription_id',
                $subscriptionId
            )->where('user_id', $user->id)->first()
            : null;

        PensionGuardPayment::updateOrCreate(
            ['stripe_invoice_id' => $invoiceId],
            [
                'user_id' => $user->id,
                'subscription_id' => $subscription?->id,
                'stripe_payment_intent_id' => $this->stripeId(
                    $object['payment_intent'] ?? null
                ),
                'amount_pence' => (int) (
                    $object['amount_paid'] ?? $object['total'] ?? 0
                ),
                'currency' => strtolower($object['currency'] ?? 'gbp'),
                'status' => 'paid',
                'paid_at' => $this->stripeDate(
                    $object['status_transitions']['paid_at'] ?? time()
                ),
                'metadata' => [
                    'invoice_number' => $object['number'] ?? null,
                    'hosted_invoice_url' => $object['hosted_invoice_url'] ?? null,
                ],
            ]
        );
    }

    /**
     * Record a failed invoice payment.
     */
    protected function invoiceFailed(array $object): void
    {
        $customerId = $this->stripeId($object['customer'] ?? null);
        $invoiceId = $object['id'] ?? null;

        if (!$customerId || !$invoiceId) {
            return;
        }

        $user = $this->findUser($customerId);

        if (!$user) {
            return;
        }

        $subscriptionId = $this->invoiceSubscriptionId($object);

        $subscription = $subscriptionId
            ? PensionGuardSubscription::where(
                'stripe_subscription_id',
                $subscriptionId
            )->where('user_id', $user->id)->first()
            : null;

        PensionGuardPayment::updateOrCreate(
            ['stripe_invoice_id' => $invoiceId],
            [
                'user_id' => $user->id,
                'subscription_id' => $subscription?->id,
                'stripe_payment_intent_id' => $this->stripeId(
                    $object['payment_intent'] ?? null
                ),
                'amount_pence' => (int) (
                    $object['amount_due'] ?? $object['total'] ?? 0
                ),
                'currency' => strtolower($object['currency'] ?? 'gbp'),
                'status' => 'failed',
                'paid_at' => null,
                'metadata' => [
                    'attempt_count' => $object['attempt_count'] ?? null,
                ],
            ]
        );
    }

    protected function findUser(
        string $customerId,
        ?string $metadataUserId = null
    ): ?PensionGuardUser {
        $user = PensionGuardUser::where(
            'stripe_customer_id',
            $customerId
        )->first();

        if ($user) {
            return $user;
        }

        if ($metadataUserId && ctype_digit($metadataUserId)) {
            $candidate = PensionGuardUser::find((int) $metadataUserId);

            if ($candidate && (
                empty($candidate->stripe_customer_id)
                || $candidate->stripe_customer_id === $customerId
            )) {
                return $candidate;
            }
        }

        return null;
    }

    protected function stripeId(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            return $value['id'] ?? null;
        }

        return null;
    }

    protected function stripeDate(mixed $timestamp): mixed
    {
        return is_numeric($timestamp)
            ? now()->setTimestamp((int) $timestamp)
            : null;
    }

    protected function invoiceSubscriptionId(array $invoice): ?string
    {
        return $this->stripeId(
            data_get($invoice, 'subscription')
            ?? data_get($invoice, 'parent.subscription_details.subscription')
        );
    }
}