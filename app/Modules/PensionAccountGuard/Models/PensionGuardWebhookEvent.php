<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;

class PensionGuardWebhookEvent extends Model
{
    protected $table = 'pension_guard_webhook_events';

    protected $fillable = [
        'stripe_event_id',
        'event_type',
        'processing_status',
        'processed_at',
        'failure_message',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
        ];
    }
}
