<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
        'employee_id',
        'status',
        'total_questions',
        'duration_minutes',
    ];

    protected $casts = [
        'total_questions' => 'integer',
        'duration_minutes' => 'integer',
    ];

   /* public function attempts(): HasMany
    {
        return $this->hasMany(
            AssessmentAttempt::class
        );
    }

    public function employeeQuestions(): HasMany
    {
        return $this->hasMany(
            EmployeeQuestion::class
        )->orderBy('question_order');
    }*/
public function employee(): BelongsTo
{
    return $this->belongsTo(
        User::class,
        'employee_id'
    );
}

public function creator(): BelongsTo
{
    return $this->belongsTo(
        User::class,
        'created_by'
    );
}

    public function attempts(): HasMany
    {
        return $this->hasMany(
            AssessmentAttempt::class
        );
    }

    public function employeeQuestions(): HasMany
    {
        return $this->hasMany(
            EmployeeQuestion::class
        )->orderBy('question_order');
    }
/*
    public function attemptFor(User $user)
    {
        return $this->attempts()
            ->where('user_id', $user->id)
            ->first();
    }*/
}
