<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmmProvider extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'balance' => 'decimal:4',
        'conversion_rate' => 'decimal:6',
        'last_synced_at' => 'datetime',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'smm_provider_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'smm_provider_id');
    }
}
