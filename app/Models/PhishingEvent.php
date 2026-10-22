<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhishingEvent extends Model
{
    protected $fillable = [
        'recipient_id',
        'event_type',
        'event_data',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'event_data' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(
            PhishingCampaignRecipient::class,
            'recipient_id'
        );
    }
}
