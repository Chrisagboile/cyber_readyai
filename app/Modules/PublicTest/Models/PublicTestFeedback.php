<?php

namespace App\Modules\PublicTest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTestFeedback extends Model
{
    use HasFactory;

    protected $table = 'public_test_feedback';

    protected $fillable = [
        'public_test_attempt_id',
        'public_test_user_id',
        'organization_name',
        'awareness_test_frequency',
        'recommended_for',
        'organization_size',
        'awareness_impact',
        'rating',
        'comments',
        'improvement_suggestions',
    ];

    protected $casts = [
        'recommended_for' => 'array',
        'rating' => 'integer',
    ];

    public function attempt()
    {
        return $this->belongsTo(
            PublicTestAttempt::class,
            'public_test_attempt_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            PublicTestUser::class,
            'public_test_user_id'
        );
    }
}
