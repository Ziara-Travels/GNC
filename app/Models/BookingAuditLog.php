<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAuditLog extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'action',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'performed_at'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'performed_at' => 'datetime'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}