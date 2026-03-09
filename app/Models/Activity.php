<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'read_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'read_at' => 'datetime'
    ];

    /**
     * Get the user that performed the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

      public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Get the model that the activity was performed on
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Scope for unread activities
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}