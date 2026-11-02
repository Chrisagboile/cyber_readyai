<?php

namespace Tests\Feature\PensionAccountGuard;

use App\Modules\PensionAccountGuard\Models\PensionGuardSubscription;
use App\Modules\PensionAccountGuard\Models\PensionGuardUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PensionGuardPremiumAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware([
            'auth:pension_guard',
            'pension_guard.premium',
        ])->get('/pension-guard/test-premium-access', function () {
            return response('Premium access granted', 200);
        });
    }

    private function createPensionGuardUser(): PensionGuardUser
    {
        return PensionGuardUser::create([
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'TestPassword123!',
        ]);
    }

    private function createSubscription(
        PensionGuardUser $user,
        string $status,
        ?\Illuminate\Support\Carbon $periodEnd = null
    ): PensionGuardSubscription {
        return PensionGuardSubscription::create([
            'user_id' => $user->id,
            'status' => $status,
            'billing_interval' => 'monthly',
            'current_period_start' => now()->subDays(5),
            'current_period_end' => $periodEnd,
            'cancel_at_period_end' => false,
        ]);
    }

    public function test_active_subscription_grants_premium_access(): void
    {
        $user = $this->createPensionGuardUser();

        $this->createSubscription($user, 'active');

        $this->actingAs($user, 'pension_guard')
            ->get('/pension-guard/test-premium-access')
            ->assertOk()
            ->assertSee('Premium access granted');
    }

    public function test_trialing_subscription_grants_premium_access(): void
    {
        $user = $this->createPensionGuardUser();

        $this->createSubscription($user, 'trialing');

        $this->actingAs($user, 'pension_guard')
            ->get('/pension-guard/test-premium-access')
            ->assertOk();
    }

    public function test_past_due_subscription_with_unexpired_period_grants_access(): void
    {
        $user = $this->createPensionGuardUser();

        $this->createSubscription(
            $user,
            'past_due',
            now()->addDays(5)
        );

        $this->actingAs($user, 'pension_guard')
            ->get('/pension-guard/test-premium-access')
            ->assertOk();
    }

    public function test_expired_subscription_does_not_grant_access(): void
    {
        $user = $this->createPensionGuardUser();

        $this->createSubscription(
            $user,
            'past_due',
            now()->subDay()
        );

        $this->actingAs($user, 'pension_guard')
            ->get('/pension-guard/test-premium-access')
            ->assertRedirect(route('pension-guard.subscriptions.index'))
            ->assertSessionHas('error');
    }

    public function test_user_without_subscription_does_not_get_premium_access(): void
    {
        $user = $this->createPensionGuardUser();

        $this->actingAs($user, 'pension_guard')
            ->get('/pension-guard/test-premium-access')
            ->assertRedirect(route('pension-guard.subscriptions.index'));
    }

    public function test_guest_is_redirected_to_pension_guard_login(): void
    {
        $this->get('/pension-guard/test-premium-access')
            ->assertRedirect(route('pension-guard.login'));
    }
}
