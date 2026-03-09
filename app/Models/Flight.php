<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_number',
        'departure_airport_id',
        'arrival_airport_id',
        'departure_time',
        'arrival_time',
        'aircraft_type',
        'price',
        'status'
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'price' => 'decimal:2'
    ];

    /**
     * Get the departure airport
     */
    public function departureAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    /**
     * Get the arrival airport
     */
    public function arrivalAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }
}