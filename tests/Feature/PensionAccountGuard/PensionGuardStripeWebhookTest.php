<?php

namespace Tests\Feature\PensionAccountGuard;

use App\Modules\PensionAccountGuard\Models\PensionGuardWebhookEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PensionGuardStripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_rejects_request_with_invalid_signature(): void
    {
        config([
            'pension_guard_billing.stripe.webhook_secret' => 'whsec_test_secret',
        ]);

        $response = $this->call(
            'POST',
            '/pension-guard/stripe/webhook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_STRIPE_SIGNATURE' => 'invalid_signature',
            ],
            '{"id":"evt_invalid_test","type":"checkout.session.completed","data":{"object":{}}}'
        );

        $response->assertStatus(400);

        $this->assertDatabaseMissing('pension_guard_webhook_events', [
            'stripe_event_id' => 'evt_invalid_test',
        ]);
    }

    public function test_webhook_returns_service_unavailable_when_secret_is_missing(): void
    {
        config([
            'pension_guard_billing.stripe.webhook_secret' => null,
        ]);

        $response = $this->post(
            '/pension-guard/stripe/webhook',
            [],
            ['Stripe-Signature' => 'any-value']
        );

        $response->assertStatus(503);
    }
}