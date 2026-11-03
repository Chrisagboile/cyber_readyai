<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;

class PensionGuardOfficialSource extends Model
{
    protected $table = 'pension_guard_official_sources';

    protected $fillable = [
        'name',
        'url',
        'source_type',
        'purpose',
        'last_reviewed_at',
        'next_review_due',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'last_reviewed_at' => 'date',
            'next_review_due' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
