<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;

class PensionGuardSecurityIncident extends Model
{
    protected $table = 'pension_guard_security_incidents';

    protected $fillable = [
        'title',
        'affected_organisation',
        'description',
        'source_name',
        'source_url',
        'reported_at',
        'status',
        'review_notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'date',
        ];
    }

    public static function allowedStatuses(): array
    {
        return [
            'unverified',
            'under_review',
            'verified',
            'resolved',
        ];
    }
}
