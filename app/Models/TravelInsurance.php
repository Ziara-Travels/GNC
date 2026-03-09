<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelInsurance extends Model
{
    protected $fillable = [
        'booking_id',
        'plan_type',
        'coverage_amount',
        'premium_amount',
        'provider',
        'policy_number',
        'coverage_details',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'coverage_details' => 'array',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}