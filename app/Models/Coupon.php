<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:4',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if coupon is valid for use.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->used_count >= $this->max_uses) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        return true;
    }

    /**
     * Check if a specific user has already used this coupon.
     */
    public function usedByUser(int $userId): bool
    {
        return $this->usages()->where('user_id', $userId)->exists();
    }
}
