<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'referrer_id',
        'referred_user_id', 
        'referral_code_id',
        'booking_id',
        'name',
        'email',
        'phone',
        'relationship',
        'message',
        'code',
        'reward_percentage',
        'discount_applied',
        'commission_earned',
        'status',
        'submitted_at',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'completed_at'
    ];

    protected $casts = [
        'reward_percentage' => 'decimal:2',
        'discount_applied' => 'boolean',
        'commission_earned' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the user who made the referral (referrer)
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the user who was referred
     */
    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /**
     * Get the referral code used
     */
    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class, 'referral_code_id');
    }

    /**
     * Get the booking associated with this referral
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Scope to get approved referrals
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get pending referrals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get rejected referrals
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Check if referral is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if referral is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if referral is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve the referral
     */
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    /**
     * Reject the referral
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }
}