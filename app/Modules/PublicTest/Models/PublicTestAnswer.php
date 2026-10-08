<?php

namespace App\Modules\PublicTest\Models;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTestAnswer extends Model
{
    use HasFactory;

    protected $table = 'public_test_answers';

    protected $fillable = [
        'public_test_attempt_id',
        'question_id',
        'question_option_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function attempt()
    {
        return $this->belongsTo(
            PublicTestAttempt::class,
            'public_test_attempt_id'
        );
    }

    public function question()
    {
        return $this->belongsTo(
            Question::class,
            'question_id'
        );
    }

    public function option()
    {
        return $this->belongsTo(
            QuestionOption::class,
            'question_option_id'
        );
    }
}
