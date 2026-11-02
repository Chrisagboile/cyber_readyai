<?php

namespace Tests\Feature\PensionAccountGuard;

use App\Modules\PensionAccountGuard\Models\PensionGuardSubscription;
use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use App\Modules\PensionAccountGuard\Models\PensionGuardWebhookEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PensionGuardStripeCancellationWebhookTest extends TestCase
{
    use RefreshDatabase;

    private string $webhookSecret = 'whsec_test_secret_for_pension_guard';

    private function createPensionGuardUser(): PensionGuardUser
    {
        $user = PensionGuardUser::create([
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'TestPassword123!',
        ]);

        $user->forceFill([
            'stripe_customer_id' => 'cus_test_cancellation',
        ])->save();

        return $user;
    }

    private function createSubscription(
        PensionGuardUser $user
    ): PensionGuardSubscription {
        return PensionGuardSubscription::create([
            'user_id' => $user->id,
            'stripe_subscription_id' => 'sub_test_cancellation',
            'stripe_customer_id' => 'cus_test_cancellation',
            'status' => 'active',
            'billing_interval' => 'monthly',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'cancel_at_period_end' => false,
        ]);
    }

    private function sendSignedWebhook(array $event): \Illuminate\Testing\TestResponse
    {
        $payload = json_encode($event, JSON_THROW_ON_ERROR);

        $timestamp = time();

        $signature = hash_hmac(
            'sha256',
            $timestamp . '.' . $payload,
            $this->webhookSecret
        );

        return $this->call(
            'POST',
            route('pension-guard.stripe.webhook'),
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' => "t={$timestamp},v1={$signature}",
            ],
            $payload
        );
    }

    private function cancellationEvent(string $eventId): array
    {
        return [
            'id' => $eventId,
            'object' => 'event',
            'api_version' => '2025-03-31.basil',
            'created' => time(),
            'data' => [
                'object' => [
                    'id' => 'sub_test_cancellation',
                    'object' => 'subscription',
                    'customer' => 'cus_test_cancellation',
                    'status' => 'active',
                    'cancel_at_period_end' => true,
                    'cancel_at' => null,
                    'canceled_at' => null,
                    'trial_end' => null,
                    'current_period_start' => time(),
                    'current_period_end' => strtotime('+1 month'),
                    'metadata' => [
                        'pension_guard_user_id' => (string) PensionGuardUser::first()->id,
                        'billing_interval' => 'monthly',
                    ],
                    'items' => [
                        'data' => [],
                    ],
                ],
            ],
            'livemode' => false,
            'pending_webhooks' => 1,
            'type' => 'customer.subscription.updated',
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'pension_guard_billing.stripe.webhook_secret' => $this->webhookSecret,
        ]);
    }

    public function test_signed_cancellation_event_updates_local_subscription(): void
    {
        $user = $this->createPensionGuardUser();

        $subscription = $this->createSubscription($user);

        $event = $this->cancellationEvent('evt_test_cancel_001');

        $response = $this->sendSignedWebhook($event);

        $response->assertOk();

        $this->assertDatabaseHas('pension_guard_subscriptions', [
            'id' => $subscription->id,
            'user_id' => $user->id,
            'status' => 'active',
            'cancel_at_period_end' => true,
        ]);

        $this->assertDatabaseHas('pension_guard_webhook_events', [
            'stripe_event_id' => 'evt_test_cancel_001',
            'event_type' => 'customer.subscription.updated',
            'processing_status' => 'processed',
        ]);
    }

    public function test_duplicate_cancellation_event_is_processed_only_once(): void
    {
        $user = $this->createPensionGuardUser();

        $this->createSubscription($user);

        $event = $this->cancellationEvent('evt_test_cancel_duplicate');

        $firstResponse = $this->sendSignedWebhook($event);
        $firstResponse->assertOk();

        $secondResponse = $this->sendSignedWebhook($event);
        $secondResponse->assertOk();

        $this->assertSame(
            1,
            PensionGuardWebhookEvent::where(
                'stripe_event_id',
                'evt_test_cancel_duplicate'
            )->count()
        );

        $this->assertSame(
            1,
            PensionGuardSubscription::where(
                'stripe_subscription_id',
                'sub_test_cancellation'
            )->count()
        );

        $this->assertDatabaseHas('pension_guard_subscriptions', [
            'stripe_subscription_id' => 'sub_test_cancellation',
            'cancel_at_period_end' => true,
        ]);
    }
}
