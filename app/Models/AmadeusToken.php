<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AmadeusToken extends Model
{
    protected $fillable = [
        'access_token',
        'token_type',
        'expires_at',
        'scope',
        'is_active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isExpiringSoon($minutes = 5)
    {
        return $this->expires_at && $this->expires_at->subMinutes($minutes)->isPast();
    }

    public static function getActiveToken()
    {
        return static::where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
    }
}