<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionGuardPayment extends Model
{
    protected $table = 'pension_guard_payments';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'stripe_invoice_id',
        'stripe_payment_intent_id',
        'amount_pence',
        'currency',
        'status',
        'paid_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount_pence' => 'integer',
            'paid_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(PensionGuardUser::class, 'user_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(
            PensionGuardSubscription::class,
            'subscription_id'
        );
    }
}
