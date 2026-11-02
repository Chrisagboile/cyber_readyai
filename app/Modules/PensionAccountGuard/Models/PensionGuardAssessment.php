<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionGuardAssessment extends Model
{
    protected $table = 'pension_guard_assessments';

    protected $fillable = ['user_id', 'answers', 'score', 'risk_band'];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'score' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
       return $this->belongsTo(PensionGuardUser::class, 'user_id');
    }
}
