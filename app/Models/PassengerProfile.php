<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'nationality',
        'passport_number',
        'passport_expiry',
        'gender',
        'frequent_flyer_number',
        'special_requirements',
        'is_primary'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
        'is_primary' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}