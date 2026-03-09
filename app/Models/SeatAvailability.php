<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SeatAvailability extends Model
{
    use HasFactory;

    protected $table = 'seat_availability';

    protected $fillable = [
        'flight_number',
        'departure_date',
        'departure_airport',
        'arrival_airport',
        'travel_class',
        'available_seats',
        'total_seats',
        'last_updated',
        'is_critical'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'last_updated' => 'datetime',
        'is_critical' => 'boolean',
        'available_seats' => 'integer',
        'total_seats' => 'integer'
    ];

    /**
     * Check if seats are critically low
     */
    public function isCriticallyLow($threshold = 10)
    {
        return $this->available_seats <= $threshold;
    }

    /**
     * Get occupancy percentage
     */
    public function getOccupancyPercentageAttribute()
    {
        if ($this->total_seats == 0) {
            return 0;
        }
        
        $occupied = $this->total_seats - $this->available_seats;
        return round(($occupied / $this->total_seats) * 100, 2);
    }

    /**
     * Get availability percentage
     */
    public function getAvailabilityPercentageAttribute()
    {
        if ($this->total_seats == 0) {
            return 0;
        }
        
        return round(($this->available_seats / $this->total_seats) * 100, 2);
    }

    /**
     * Scope for critical availability
     */
    public function scopeCritical($query)
    {
        return $query->where('is_critical', true);
    }

    /**
     * Scope for specific flight
     */
    public function scopeForFlight($query, $flightNumber, $departureDate)
    {
        return $query->where('flight_number', $flightNumber)
                    ->where('departure_date', $departureDate);
    }

    /**
     * Scope for route
     */
    public function scopeForRoute($query, $departureAirport, $arrivalAirport)
    {
        return $query->where('departure_airport', $departureAirport)
                    ->where('arrival_airport', $arrivalAirport);
    }

    /**
     * Scope for travel class
     */
    public function scopeForClass($query, $travelClass)
    {
        return $query->where('travel_class', $travelClass);
    }

    /**
     * Update seat availability
     */
    public function updateAvailability($newAvailableSeats)
    {
        $this->update([
            'available_seats' => $newAvailableSeats,
            'last_updated' => now(),
            'is_critical' => $this->isCriticallyLow()
        ]);
    }

    /**
     * Reserve seats
     */
    public function reserveSeats($seatCount)
    {
        if ($this->available_seats >= $seatCount) {
            $this->updateAvailability($this->available_seats - $seatCount);
            return true;
        }
        
        return false;
    }

    /**
     * Release seats
     */
    public function releaseSeats($seatCount)
    {
        $newAvailable = min($this->available_seats + $seatCount, $this->total_seats);
        $this->updateAvailability($newAvailable);
    }
}