<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'drip_feed_active' => 'boolean',
        'refill_available' => 'boolean',
        'price' => 'decimal:4',
        'provider_rate' => 'decimal:4',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(SmmProvider::class, 'smm_provider_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
