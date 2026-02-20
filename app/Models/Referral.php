<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Referral extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'total_funds_referred' => 'decimal:4',
            'earned_commission' => 'decimal:4',
            'requested_commission' => 'decimal:4',
            'total_commission' => 'decimal:4',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(ReferralPayout::class, 'referral_code', 'referral_code');
    }
}
