<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amadeus_offer_id',
        'origin_code',
        'origin_city',
        'destination_code', 
        'destination_city',
        'departure_date',
        'departure_time',
        'return_date',
        'return_time',
        'passenger_count',
        'cabin_class',
        'airline_code',
        'flight_duration',
        'stops',
        'total_price',
        'currency',
        'booking_reference',
        'amadeus_data',
        'passenger_details',
        'contact_details',
        'booking_status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'booked_at',
        'cancelled_at',
        'notes'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'amadeus_data' => 'array',
        'passenger_details' => 'array',
        'contact_details' => 'array',
        'booked_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    /**
     * Get the user that owns the booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->booking_status === 'confirmed';
    }

    /**
     * Check if booking is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->booking_status === 'cancelled';
    }
}
