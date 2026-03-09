<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'booked_by',
        'booking_reference',
        'booking_type',
        'type',
        'title',
        'flight_number',
        'hotel_id',
        'departure_airport',
        'arrival_airport',
        'departure_time',
        'arrival_time',
        'check_in_date',
        'check_out_date',
        'amount',
        'total_amount',
        'paid_amount',
        'currency',
        'status',
        'payment_method',
        'payment_status',
        'transaction_id',
        'flight_details',
        'hotel_details',
        'traveler_details',
        'passengers',
        'adults',
        'children',
        'infants',
        'travel_class',
        'contact_email',
        'contact_phone',
        'confirmation_sent',
        'reminder_sent',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'check_in_date' => 'datetime',
        'check_out_date' => 'datetime',
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'flight_details' => 'array',
        'hotel_details' => 'array',
        'traveler_details' => 'array',
        'passengers' => 'array',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
        'confirmation_sent' => 'boolean',
        'reminder_sent' => 'boolean',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the referral associated with this booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function referral()
    {
        return $this->hasOne(UserReferral::class, 'booking_id');
    }

    /**
     * Get the referral code used for this booking through the referral relationship.
     */
    public function referralCode()
    {
        return $this->hasOneThrough(
            ReferralCode::class,
            UserReferral::class,
            'booking_id',       // Foreign key on UserReferral table
            'id',               // Foreign key on ReferralCode table
            'id',               // Local key on Booking table
            'referral_code_id'  // Local key on UserReferral table
        );
    }

    /**
     * Get the departure airport.
     */
    public function departureAirport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport', 'iata_code');
    }

    /**
     * Get the arrival airport.
     */
    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport', 'iata_code');
    }

    /**
     * Scope a query to only include flight bookings.
     */
    public function scopeFlights($query)
    {
        return $query->where('booking_type', 'flight');
    }

    /**
     * Scope a query to only include hotel bookings.
     */
    public function scopeHotels($query)
    {
        return $query->where('booking_type', 'hotel');
    }

    /**
     * Scope a query to only include confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if booking is a flight.
     */
    public function isFlight()
    {
        return $this->booking_type === 'flight';
    }

    /**
     * Check if booking is a hotel.
     */
    public function isHotel()
    {
        return $this->booking_type === 'hotel';
    }

    /**
     * Check if booking is confirmed.
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if booking is cancelled.
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if payment is completed.
     */
    public function isPaymentCompleted()
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Get remaining balance.
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    /**
     * Check if booking is fully paid.
     */
    public function isFullyPaid()
    {
        return $this->paid_amount >= $this->total_amount;
    }

    /**
     * Get total passengers count.
     */
    public function getTotalPassengersAttribute()
    {
        return $this->adults + $this->children + $this->infants;
    }

    /**
     * Get formatted booking reference.
     */
    public function getFormattedReferenceAttribute()
    {
        return 'Ziara Travels-' . $this->booking_reference;
    }

    /**
     * Mark confirmation as sent.
     */
    public function markConfirmationSent()
    {
        $this->update(['confirmation_sent' => true]);
    }

    /**
     * Mark reminder as sent.
     */
    public function markReminderSent()
    {
        $this->update(['reminder_sent' => true]);
    }

    /**
     * Generate booking reference.
     */
    public static function generateBookingReference()
    {
        do {
            $reference = strtoupper(uniqid());
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Boot method to auto-generate booking reference.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = self::generateBookingReference();
            }
        });
    }

    /**
     * Accessor for getting formatted flight details
     */
    public function getFormattedFlightDetailsAttribute()
    {
        if (!$this->flight_details) {
            return null;
        }

        $details = $this->flight_details;
        
        return [
            'from' => $this->departure_airport,
            'to' => $this->arrival_airport,
            'departure_time' => $this->departure_time ? $this->departure_time->format('H:i') : 'N/A',
            'arrival_time' => $this->arrival_time ? $this->arrival_time->format('H:i') : 'N/A',
            'departure_date' => $this->departure_time ? $this->departure_time->format('M j, Y') : 'N/A',
            'arrival_date' => $this->arrival_time ? $this->arrival_time->format('M j, Y') : 'N/A',
            'flight_number' => $this->flight_number,
            'airline' => $details['airline'] ?? 'N/A',
            'duration' => $details['duration'] ?? 'N/A',
            'class' => $this->travel_class ?? 'Economy',
        ];
    }

    /**
     * Accessor for getting passenger information
     */
    public function getPassengersAttribute($value)
    {
        $passengers = json_decode($value, true);
        
        if (!$passengers || empty($passengers)) {
            return collect([]);
        }

        return collect($passengers)->map(function ($passenger) {
            return (object) $passenger;
        });
    }

    /**
     * Check if booking was made through a referral
     */
    public function isReferralBooking()
    {
        return $this->referral()->exists();
    }

    /**
     * Get the referrer user if this booking was made through a referral
     */
    public function getReferrerAttribute()
    {
        if ($this->referral) {
            return $this->referral->referrer;
        }
        return null;
    }

    /**
     * Get the commission earned from this booking for the referrer
     */
    public function getReferralCommissionAttribute()
    {
        if ($this->referral) {
            return $this->referral->commission_earned;
        }
        return 0;
    }

    /**
     * Check if referral discount was applied to this booking
     */
    public function hasReferralDiscount()
    {
        if ($this->referral) {
            return $this->referral->discount_applied;
        }
        return false;
    }

    /**
     * Get the referral reward percentage for this booking
     */
    public function getReferralRewardPercentageAttribute()
    {
        if ($this->referral) {
            return $this->referral->reward_percentage;
        }
        return 0;
    }

    /**
     * Calculate and set referral commission
     */
    public function calculateReferralCommission()
    {
        if ($this->referral && $this->referral->status === 'approved') {
            $commission = ($this->amount * $this->referral->reward_percentage) / 100;
            $this->referral->update(['commission_earned' => $commission]);
            return $commission;
        }
        return 0;
    }
}