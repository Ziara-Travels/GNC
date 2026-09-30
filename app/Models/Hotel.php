<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'country',
        'location_id',
        'price_per_night',
        'star_rating',
        'image',
        'status',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'star_rating' => 'integer',
    ];

    /**
     * Hotel bookings are stored in the unified bookings table, which carries
     * hotel_id alongside the stay details (check_in_date, check_out_date,
     * room_type, room_count, nights, guest_count).
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'hotel_id')->hotels();
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenity');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCountry(Builder $query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByCity(Builder $query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByPriceRange(Builder $query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price_per_night', [$minPrice, $maxPrice]);
    }

    public function scopeByStarRating(Builder $query, $starRating)
    {
        return $query->where('star_rating', $starRating);
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('city', 'LIKE', "%{$search}%")
                ->orWhere('country', 'LIKE', "%{$search}%")
                ->orWhere('address', 'LIKE', "%{$search}%")
                ->orWhereHas('location', function (Builder $location) use ($search) {
                    $location->where('name', 'LIKE', "%{$search}%");
                });
        });
    }

    public function getFormattedPriceAttribute()
    {
        return number_format((float) $this->price_per_night, 2);
    }
}
