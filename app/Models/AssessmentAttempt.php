<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentAttempt extends Model
{
    protected $fillable = [
        'assessment_id',
        'user_id',
        'current_position',
        'status',
        'total_questions',
        'answered_questions',
        'correct_answers',
        'score_percentage',
        'risk_level',
        'started_at',
        'expires_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
        'score_percentage' => 'decimal:2',
    ];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(
            Assessment::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function answers(): HasMany
    {
        return $this->hasMany(
            AssessmentAnswer::class
        );
    }

    public function employeeQuestions(): HasMany
    {
        return $this->hasMany(
            EmployeeQuestion::class,
            'assessment_attempt_id'
        )->orderBy('question_order');
    }
}
