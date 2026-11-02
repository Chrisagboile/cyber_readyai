<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionGuardSecurityCheck extends Model
{
    protected $table = 'pension_guard_security_checks';

    protected $fillable = [
        'pension_guard_account_id', 'check_key', 'title',
        'guidance', 'official_url', 'is_complete', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_complete' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(
            PensionGuardAccount::class,
            'pension_guard_account_id'
        );
    }
}
