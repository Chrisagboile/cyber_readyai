<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionGuardTrustedContact extends Model
{
    protected $table = 'pension_guard_trusted_contacts';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'relationship',
        'verification_token_hash',
        'verification_expires_at',
        'email_verified_at',
        'consent_given',
        'alert_categories',
        'consent_given_at',
        'consent_revoked_at',
    ];

    protected $hidden = [
        'verification_token_hash',
    ];

    protected function casts(): array
    {
        return [
            'verification_expires_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'consent_given' => 'boolean',
            'alert_categories' => 'array',
            'consent_given_at' => 'datetime',
            'consent_revoked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            PensionGuardUser::class,
            'user_id'
        );
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function hasActiveConsent(): bool
    {
        return $this->consent_given
            && $this->consent_given_at !== null
            && $this->consent_revoked_at === null;
    }

    public function canReceiveAlert(string $category): bool
    {
        return $this->isVerified()
            && $this->hasActiveConsent()
            && in_array(
                $category,
                $this->alert_categories ?? [],
                true
            );
    }
}
