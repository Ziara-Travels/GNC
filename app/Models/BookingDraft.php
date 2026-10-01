<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingDraft extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'session_id',
        'user_id',
        'passenger_name',
        'email',
        'phone',
        'booking_type',
        'flight_details',
        'hotel_details',
        'passenger_details',
        'status',
        'step_completed',
        'total_price',
        'currency',
        'reminder_sent',
        'discount_code',
        'expires_at',
    ];

    protected $casts = [
        'flight_details' => 'array',
        'hotel_details' => 'array',
        'passenger_details' => 'array',
        'reminder_sent' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if the draft has expired
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    // Check if reminder should be sent
    public function shouldSendReminder()
    {
        return !$this->reminder_sent && 
               $this->created_at->diffInHours(now()) >= 24 && 
               $this->status === 'abandoned';
    }

    // Generate a unique discount code
    public function generateDiscountCode()
    {
        $code = 'SAVE10' . strtoupper(substr($this->session_id, 0, 4));
        $this->update(['discount_code' => $code]);
        return $code;
    }
}
