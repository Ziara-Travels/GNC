<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Tour extends Model
{
    use HasFactory;

    // MySQL doesn't allow a DB-level DEFAULT on JSON columns, so these are
    // enforced here instead (see the create_tours_table migration).
    protected $attributes = [
        'languages' => '["English"]',
        'available_days' => '["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"]',
    ];

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
        'pricing_options',
        'card_excerpt',
        'gallery',
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
        'pricing_options' => 'array',
        'gallery' => 'array',
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

    /**
     * Parent category used by the reference breadcrumb, derived from the
     * category slug ("Kenya Safaris" sits under "Safaris").
     */
    public function getCategoryParentAttribute(): string
    {
        return self::parentCategory($this->category);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::categoryLabel($this->category);
    }

    /**
     * Human label for a category slug, usable without a hydrated model.
     */
    public static function categoryLabel(?string $category): string
    {
        return match ($category) {
            'kenya-safaris' => 'Kenya Safaris',
            'international-packages' => 'International packages',
            'dubai-package' => 'Dubai package',
            'bangkok-packages' => 'Bangkok Packages',
            'safaris' => 'Safaris',
            default => ucwords(str_replace('-', ' ', (string) $category)),
        };
    }

    /**
     * Parent category slug for the reference breadcrumb.
     */
    public static function parentCategory(?string $category): string
    {
        return match ($category) {
            'kenya-safaris' => 'safaris',
            'dubai-package', 'bangkok-packages' => 'international-packages',
            default => (string) $category ?: 'safaris',
        };
    }

    // Accessors
    public function getDiscountedPriceAttribute()
    {
        if ($this->original_price && $this->discount_percent > 0) {
            return $this->original_price - ($this->original_price * $this->discount_percent / 100);
        }
        return $this->price;
    }

    public function getHasDiscountAttribute()
    {
        return (bool) ($this->original_price && $this->discount_percent > 0);
    }

    public function getFormattedPriceAttribute()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    public function getMainImageAttribute()
    {
        return $this->hero_image_url;
    }

    /**
     * Resolves a stored image path to a public URL. Reference images are
     * seeded as root-relative public paths (e.g. "images/tours/x-hero.jpg").
     */
    protected static function publicImageUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/travel/default-tour.svg');
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return asset(ltrim($path, '/'));
    }

    /**
     * Large image used for the detail-page hero. Seeded first in `images`.
     */
    public function getHeroImageUrlAttribute(): string
    {
        $images = is_array($this->images) ? $this->images : [];

        return self::publicImageUrl($images[0] ?? null);
    }

    /**
     * Listing/card image, or the hero when the tour has no dedicated card asset
     * (the reference only shows it in the homepage carousel, which omits the
     * three international packages).
     */
    public function getCardImageUrlAttribute(): string
    {
        $images = is_array($this->images) ? $this->images : [];

        foreach ($images as $image) {
            if (! preg_match('/-hero\.[a-z0-9]+$/i', (string) $image)) {
                return self::publicImageUrl($image);
            }
        }

        return $this->hero_image_url;
    }

    /**
     * Detail-page gallery, stored explicitly so it never overlaps the hero or
     * the listing card image.
     */
    public function getGalleryUrlsAttribute(): array
    {
        return array_values(array_filter(array_map(
            [self::class, 'publicImageUrl'],
            is_array($this->gallery) ? $this->gallery : []
        )));
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