<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'iata_code',
        'name',
        'city',
        'country',
         'region',
        'latitude',
        'longitude',
        'elevation',
        'timezone',
        'is_active',
        'location'
    ];

     protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_active' => 'boolean',
    ];

    /**
     * Get departing flights from this airport
     */
    public function departingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'departure_airport_id', 'id');
    }

    /**
     * Get arriving flights to this airport
     */
    public function arrivingFlights(): HasMany
    {
        return $this->hasMany(Flight::class, 'arrival_airport_id', 'id');
    }

    /**
     * Get the airport's coordinates as an array
     */
    public function getCoordinatesAttribute(): array
    {
        $coords = explode(',', $this->location);
        return [
            'latitude' => (float) trim($coords[0]),
            'longitude' => (float) trim($coords[1])
        ];
    }

    /**
     * Calculate distance between this airport and another airport
     */
    public function getDistanceTo(Airport $destination, string $unit = 'km'): float
    {
        $earthRadius = ($unit === 'mi') ? 3959 : 6371;

        $latFrom = deg2rad($this->coordinates['latitude']);
        $lonFrom = deg2rad($this->coordinates['longitude']);
        $latTo = deg2rad($destination->coordinates['latitude']);
        $lonTo = deg2rad($destination->coordinates['longitude']);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    /**
     * Scope a query to find airports by country
     */
    public function scopeInCountry($query, string $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to find airports by city
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }

      public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Search airports by various criteria
     */
   public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('iata_code', 'LIKE', "%{$term}%")
              ->orWhere('name', 'LIKE', "%{$term}%")
              ->orWhere('city', 'LIKE', "%{$term}%")
              ->orWhere('country', 'LIKE', "%{$term}%");
        });
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->iata_code} - {$this->name}";
    }

    public function getLocationDescriptionAttribute()
    {
        return "{$this->city}, {$this->country}";
    }

    /**
     * Get formatted location string
     */
    public function getLocationString(): string
    {
        return "{$this->city}, {$this->country}";
    }

    /**
     * Check if airport is international
     */
    public function isInternational(): bool
    {
        return str_contains(strtolower($this->name), 'international');
    }
}