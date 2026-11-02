<?php

namespace App\Modules\PensionAccountGuard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PensionGuardProvider extends Model
{
    protected $table = 'pension_guard_providers';

    protected $fillable = [
        'name', 'website_url', 'security_url',
        'contact_url', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(PensionGuardAccount::class, 'provider_id');
    }
}
