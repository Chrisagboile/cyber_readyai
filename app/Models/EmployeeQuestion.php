<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeQuestion extends Model
{
    protected $table = 'employee_questions';

    protected $fillable = [
        'employee_id',
        'question_id',
        'question_order',
        'assessment_attempt_id',
    ];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'employee_id'
        );
    }


    public function question(): BelongsTo
    {
        return $this->belongsTo(
            Question::class
        );
    }


    public function attempt(): BelongsTo
    {
        return $this->belongsTo(
            AssessmentAttempt::class,
            'assessment_attempt_id'
        );
    }
}
