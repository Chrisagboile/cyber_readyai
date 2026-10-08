<?php

namespace App\Modules\PublicTest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTestLearningPlan extends Model
{
    use HasFactory;

    protected $table = 'public_test_learning_plans';

    protected $fillable = [
        'public_test_user_id',
        'public_test_attempt_id',
        'title',
        'description',
        'priority',
        'status',
        'progress_percentage',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(
            PublicTestUser::class,
            'public_test_user_id'
        );
    }

    public function attempt()
    {
        return $this->belongsTo(
            PublicTestAttempt::class,
            'public_test_attempt_id'
        );
    }
}
