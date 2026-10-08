<?php

namespace App\Modules\PublicTest\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTestQuestion extends Model
{
    use HasFactory;

    protected $table = 'public_test_questions';

    protected $fillable = [
        'public_test_attempt_id',
        'question_id',
        'question_order',
    ];

    protected $casts = [
        'question_order' => 'integer',
    ];

    public function attempt()
    {
        return $this->belongsTo(
            PublicTestAttempt::class,
            'public_test_attempt_id'
        );
    }

    /*
     * Questions are deliberately shared with
     * the main CyberReadyAI question bank.
     */
    public function question()
    {
        return $this->belongsTo(
            Question::class,
            'question_id'
        );
    }
}
