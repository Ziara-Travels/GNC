<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ReferralCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'code',
        'reward_percentage',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active',
        'admin_approved',
    ];

    protected $casts = [
        'reward_percentage' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'admin_approved' => 'boolean',
    ];

    /**
     * Get the user who owns this referral code
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all referrals made using this code
     */
    public function referrals()
    {
        return $this->hasMany(UserReferral::class, 'referral_code_id');
    }

    /**
     * Get all bookings made using this referral code
     */
    public function bookings()
    {
        return $this->hasManyThrough(
            Booking::class,
            UserReferral::class,
            'referral_code_id', // Foreign key on UserReferral table
            'id',               // Foreign key on Booking table
            'id',               // Local key on ReferralCode table
            'booking_id'        // Local key on UserReferral table
        );
    }

    /**
     * Boot method to generate referral code
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($referralCode) {
            if (empty($referralCode->code)) {
                $referralCode->code = $referralCode->generateCode();
            }
        });
    }

    /**
     * Generate unique referral code
     */
    public function generateCode()
    {
        do {
            $code = 'Ziara Travels' . strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());
        
        return $code;
    }

    /**
     * Check if code is expired
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if code has reached usage limit
     */
    public function isUsageLimitReached()
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    /**
     * Check if code can be used
     */
    public function canBeUsed()
    {
        return $this->is_active && 
               $this->admin_approved && 
               !$this->isExpired() && 
               !$this->isUsageLimitReached();
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Scope to get active codes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get approved codes
     */
    public function scopeApproved($query)
    {
        return $query->where('admin_approved', true);
    }

    /**
     * Scope to get usable codes
     */
    public function scopeUsable($query)
    {
        return $query->where('is_active', true)
                    ->where('admin_approved', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    })
                    ->whereRaw('(usage_limit IS NULL OR used_count < usage_limit)');
    }

    /**
     * Get total commission earned from this code
     */
    public function getTotalCommissionAttribute()
    {
        return $this->referrals()->sum('commission_earned');
    }

    /**
     * Get total successful referrals
     */
    public function getSuccessfulReferralsCountAttribute()
    {
        return $this->referrals()->where('status', 'approved')->count();
    }
}