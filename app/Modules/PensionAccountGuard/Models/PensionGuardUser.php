<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PensionGuardUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pension_guard_users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'is_admin',
        'stripe_customer_id',
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
            'is_admin' => 'boolean',
        ];
    }

    public function trustedContacts(): HasMany
    {
        return $this->hasMany(
        PensionGuardTrustedContact::class,
        'user_id'
        );
    }
    public function subscriptions(): HasMany
    {
        return $this->hasMany(
            PensionGuardSubscription::class,
            'user_id'
        );
    }
    public function payments(): HasMany
    {
        return $this->hasMany(
            PensionGuardPayment::class,
            'user_id'
        );
    }
}
