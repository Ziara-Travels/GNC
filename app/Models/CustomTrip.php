<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomTrip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'travel_timing',
        'subscribe_newsletter',
        'status',
        'user_id',
        'admin_notes',
        'last_contacted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'subscribe_newsletter' => 'boolean',
        'last_contacted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the custom trip.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}