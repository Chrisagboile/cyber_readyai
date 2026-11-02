<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PensionGuardPlan extends Model
{
    protected $table = 'pension_guard_plans';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'billing_interval',
        'amount_pence',
        'currency',
        'stripe_price_id',
        'is_active',
        'is_free',
        'features',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'amount_pence' => 'integer',
            'is_active' => 'boolean',
            'is_free' => 'boolean',
            'features' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(
            PensionGuardSubscription::class,
            'plan_id'
        );
    }
}
