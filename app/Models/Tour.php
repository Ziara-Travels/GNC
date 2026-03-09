<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_reference',
        'name',
        'slug',
        'short_description',
        'full_description',
        'city',
        'country',
        'latitude',
        'longitude',
        'images',
        'price',
        'original_price',
        'currency',
        'discount_percent',
        'duration',
        'duration_hours',
        'difficulty_level',
        'max_participants',
        'min_participants',
        'rating',
        'review_count',
        'included_services',
        'excluded_services',
        'highlights',
        'itinerary',
        'category',
        'languages',
        'start_time',
        'end_time',
        'meeting_point',
        'pickup_locations',
        'hotel_pickup',
        'instant_confirmation',
        'free_cancellation',
        'cancellation_hours',
        'age_restrictions',
        'requirements',
        'operator_name',
        'operator_contact',
        'featured',
        'seasonal',
        'available_months',
        'available_days',
        'advance_booking_days',
        'status',
        'published_at'
    ];

    protected $casts = [
        'images' => 'array',
        'included_services' => 'array',
        'excluded_services' => 'array',
        'highlights' => 'array',
        'itinerary' => 'array',
        'languages' => 'array',
        'pickup_locations' => 'array',
        'age_restrictions' => 'array',
        'requirements' => 'array',
        'available_months' => 'array',
        'available_days' => 'array',
        'hotel_pickup' => 'boolean',
        'instant_confirmation' => 'boolean',
        'free_cancellation' => 'boolean',
        'featured' => 'boolean',
        'seasonal' => 'boolean',
        'published_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'rating' => 'decimal:2'
    ];

    // Boot method to generate slug and reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tour) {
            if (empty($tour->slug)) {
                $tour->slug = Str::slug($tour->name);
            }
            if (empty($tour->tour_reference)) {
                $tour->tour_reference = 'TOUR' . strtoupper(Str::random(8));
            }
        });
    }

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tour_id');
    }

    public function reviews()
    {
        return $this->hasMany(TourReview::class);
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('featured', true);
    }

    public function scopeByCity(Builder $query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByCategory(Builder $query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriceRange(Builder $query, $minPrice, $maxPrice)
    {
        return $query->whereBetween('price', [$minPrice, $maxPrice]);
    }

    public function scopeByDifficulty(Builder $query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }

    // Accessors
    public function getDiscountedPriceAttribute()
    {
        if ($this->original_price && $this->discount_percent > 0) {
            return $this->original_price - ($this->original_price * $this->discount_percent / 100);
        }
        return $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    public function getMainImageAttribute()
    {
        return $this->images[0] ?? '/images/travel/default-tour.jpg';
    }

    public function getAvailabilityStatusAttribute()
    {
        if ($this->seasonal && !empty($this->available_months)) {
            $currentMonth = date('F');
            return in_array($currentMonth, $this->available_months) ? 'available' : 'seasonal';
        }
        return 'available';
    }

    // Methods
    public function isAvailableToday()
    {
        $today = date('l'); // Get day name
        return in_array($today, $this->available_days);
    }

    public function canBookInAdvance($date)
    {
        $daysUntilDate = ceil((strtotime($date) - time()) / (60 * 60 * 24));
        return $daysUntilDate >= $this->advance_booking_days;
    }

    public function updateRating()
    {
        $avgRating = $this->reviews()->avg('rating');
        $reviewCount = $this->reviews()->count();
        
        $this->update([
            'rating' => round($avgRating, 2),
            'review_count' => $reviewCount
        ]);
    }
}