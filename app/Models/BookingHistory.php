<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_reference',
        'flight_data',
        'passenger_details',
        'booking_details',
        'total_amount',
        'currency',
        'status',
        'departure_date',
        'return_date',
        'route_from',
        'route_to'
    ];

    protected $casts = [
        'flight_data' => 'json',
        'passenger_details' => 'json',
        'booking_details' => 'json',
        'departure_date' => 'date',
        'return_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}