<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'min_amount' => 'decimal:4',
            'max_amount' => 'decimal:4',
            'fee_percentage' => 'decimal:2',
            'bonus_percentage' => 'decimal:2',
            'bonus_start_amount' => 'decimal:4',
            'is_active' => 'boolean',
            'config' => 'array',
        ];
    }

    /**
     * Calculate fee for a given amount.
     */
    public function calculateFee(float $amount): float
    {
        return round($amount * ($this->fee_percentage / 100), 4);
    }

    /**
     * Calculate bonus for a given amount.
     */
    public function calculateBonus(float $amount): float
    {
        if ($amount >= $this->bonus_start_amount && $this->bonus_percentage > 0) {
            return round($amount * ($this->bonus_percentage / 100), 4);
        }
        return 0;
    }

    /**
     * Get active payment methods.
     */
    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('sort_order')->get();
    }
}
