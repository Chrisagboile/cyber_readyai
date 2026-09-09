<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAssessmentInsight extends Model
{
    protected $fillable = [
        'assessment_attempt_id',
        'summary',
        'strengths',
        'priority_areas',
        'recommendations',
        'model',
        'prompt_version',
        'generated_at',
    ];

    protected $casts = [
        'strengths' => 'array',
        'priority_areas' => 'array',
        'recommendations' => 'array',
        'generated_at' => 'datetime',
    ];

    public function assessmentAttempt(): BelongsTo
    {
        return $this->belongsTo(
            AssessmentAttempt::class
        );
    }
}
