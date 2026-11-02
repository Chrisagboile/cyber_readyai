<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;

class PensionGuardThreatRule extends Model
{
    protected $table = 'pension_guard_threat_rules';

    protected $fillable = [
        'rule_key',
        'title',
        'description',
        'severity',
        'recommended_action',
        'official_source_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
