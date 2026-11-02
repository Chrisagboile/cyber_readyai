<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;

class PensionGuardAuditLog extends Model
{
    protected $table = 'pension_guard_audit_logs';

    public const UPDATED_AT = null;

    protected $fillable = [
        'actor_id',
        'action',
        'target_type',
        'target_id',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }
}