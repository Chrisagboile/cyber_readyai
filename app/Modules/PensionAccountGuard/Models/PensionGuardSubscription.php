<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PensionGuardSubscription extends Model
{
    protected $table = 'pension_guard_subscriptions';

    protected $fillable = [
        'user_id',
        'plan_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'status',
        'billing_interval',
        'current_period_start',
        'current_period_end',
        'trial_ends_at',
        'cancel_at',
        'canceled_at',
        'ended_at',
        'cancel_at_period_end',
    ];

    protected function casts(): array
    {
        return [
            'current_period_start' => 'datetime',
            'current_period_end' => 'datetime',
            'trial_ends_at' => 'datetime',
            'cancel_at' => 'datetime',
            'canceled_at' => 'datetime',
            'ended_at' => 'datetime',
            'cancel_at_period_end' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PensionGuardUser::class, 'user_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(PensionGuardPlan::class, 'plan_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PensionGuardPayment::class, 'subscription_id');
    }

    public function hasPremiumAccess(): bool
    {
        if ($this->status === 'active') {
            return true;
        }

        if ($this->status === 'trialing') {
            return true;
        }

        return $this->status === 'past_due'
            && $this->current_period_end !== null
            && $this->current_period_end->isFuture();
    }
}
