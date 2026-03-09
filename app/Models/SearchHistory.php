<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'searched_by',
        'from_iata',
        'to_iata',
        'departure_date',
        'return_date',
        'adults',
        'children',
        'infants',
        'travel_class',
        'trip_type',
        'search_parameters',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'search_parameters' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function searchedBy()
    {
        return $this->belongsTo(User::class, 'searched_by');
    }
}
