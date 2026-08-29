<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentAnswer extends Model
{
    protected $fillable = [
        'assessment_attempt_id',
        'question_id',
        'question_option_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(
            AssessmentAttempt::class,
            'assessment_attempt_id'
        );
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            Question::class
        );
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(
            QuestionOption::class,
            'question_option_id'
        );
    }
}
