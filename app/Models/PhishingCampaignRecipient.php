<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhishingCampaignRecipient extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'status',
        'delivered_at',
        'clicked_at',
        'landing_page_visited_at',
        'reported_at',
    ];

    protected function casts(): array
    {
        return [
            'delivered_at' => 'datetime',
            'clicked_at' => 'datetime',
            'landing_page_visited_at' => 'datetime',
            'reported_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(PhishingCampaign::class, 'campaign_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(PhishingEvent::class, 'recipient_id');
    }
}
