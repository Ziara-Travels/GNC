<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'item_data',
        'title',
        'description',
        'image',
        'price',
        'original_price',
        'location',
        'item_id'
    ];

    protected $casts = [
        'item_data' => 'array',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFlights($query)
    {
        return $query->where('type', 'flight');
    }

    public function scopeHotels($query)
    {
        return $query->where('type', 'hotel');
    }

    public function getFormattedPriceAttribute()
    {
        return $this->price ? '$' . number_format($this->price, 2) : 'Price not available';
    }
}