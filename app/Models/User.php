<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'role',
        'api_key',
        'timezone',
        'is_banned',
        'google_id',
        'ref_code',
        'ref_by',
        'spent',
        'discount_percentage',
        'currency',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:4',
            'spent' => 'decimal:4',
            'is_banned' => 'boolean',
            'discount_percentage' => 'decimal:2',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    /**
     * Generate a new API key for the user.
     */
    public function generateApiKey(): string
    {
        $key = bin2hex(random_bytes(32));
        $this->update(['api_key' => $key]);
        return $key;
    }

    /**
     * Calculate price with user discount applied.
     */
    public function getDiscountedPrice(float $basePrice): float
    {
        if ($this->discount_percentage > 0) {
            return $basePrice * (1 - $this->discount_percentage / 100);
        }
        return $basePrice;
    }
}
