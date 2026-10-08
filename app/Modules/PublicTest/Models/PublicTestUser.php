<?php

namespace App\Modules\PublicTest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PublicTestUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'public_test_users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Public Test Attempts
    |--------------------------------------------------------------------------
    */

    public function attempts()
    {
        return $this->hasMany(
            PublicTestAttempt::class,
            'public_test_user_id'
        )->latest('id');
    }

    /*
    |--------------------------------------------------------------------------
    | Public Test Learning Plans
    |--------------------------------------------------------------------------
    */

    public function learningPlans()
    {
        return $this->hasMany(
            PublicTestLearningPlan::class,
            'public_test_user_id'
        )->latest('id');
    }
}
