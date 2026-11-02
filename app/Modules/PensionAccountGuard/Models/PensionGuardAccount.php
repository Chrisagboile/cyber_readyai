<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PensionGuardAccount extends Model
{
    protected $table = 'pension_guard_accounts';

    protected $fillable = [
        'user_id', 'provider_id', 'provider_name',
        'display_name', 'account_type', 'notes',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(PensionGuardProvider::class, 'provider_id');
    }

    public function securityChecks(): HasMany
    {
        return $this->hasMany(
            PensionGuardSecurityCheck::class,
            'pension_guard_account_id'
        );
    }
}
