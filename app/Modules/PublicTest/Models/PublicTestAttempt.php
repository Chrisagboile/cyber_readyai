<?php

namespace App\Modules\PublicTest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTestAttempt extends Model
{
    use HasFactory;

    protected $table = 'public_test_attempts';

    protected $fillable = [
        'public_test_user_id',
        'status',
        'total_questions',
        'answered_questions',
        'current_position',
        'correct_answers',
        'score_percentage',
        'risk_level',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_questions' => 'integer',
            'answered_questions' => 'integer',
            'current_position' => 'integer',
            'correct_answers' => 'integer',
            'score_percentage' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(
            PublicTestUser::class,
            'public_test_user_id'
        );
    }

    public function questions()
    {
        return $this->hasMany(
            PublicTestQuestion::class,
            'public_test_attempt_id'
        )->orderBy('question_order');
    }

    public function answers()
    {
        return $this->hasMany(
            PublicTestAnswer::class,
            'public_test_attempt_id'
        );
    }

    public function feedback()
    {
        return $this->hasOne(
            PublicTestFeedback::class,
            'public_test_attempt_id'
        );
    }

    public function learningPlans()
    {
        return $this->hasMany(
            PublicTestLearningPlan::class,
            'public_test_attempt_id'
        );
    }
}
