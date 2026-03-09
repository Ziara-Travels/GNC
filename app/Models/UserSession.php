<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class UserSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'location_country',
        'location_city',
        'location_region',
        'is_suspicious',
        'login_at',
        'logout_at',
        'last_activity'
    ];

    protected $casts = [
        'is_suspicious' => 'boolean',
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'last_activity' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return !$this->logout_at && $this->last_activity > now()->subMinutes(30);
    }

    public static function createFromRequest($userId)
    {
        $userAgent = Request::userAgent();
        $parser = new \WhichBrowser\Parser($userAgent);
        
        return static::create([
            'user_id' => $userId,
            'session_id' => session()->getId(),
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'device_type' => $parser->device->type ?? 'unknown',
            'browser' => $parser->browser->name ?? 'unknown',
            'platform' => $parser->os->name ?? 'unknown',
            'location_country' => self::getLocationFromIP(Request::ip())['country'] ?? null,
            'location_city' => self::getLocationFromIP(Request::ip())['city'] ?? null,
            'location_region' => self::getLocationFromIP(Request::ip())['region'] ?? null,
            'is_suspicious' => self::isSuspiciousActivity($userId, Request::ip()),
            'login_at' => now(),
            'last_activity' => now()
        ]);
    }

    private static function getLocationFromIP($ip)
    {
        // Implement IP geolocation logic
        return ['country' => 'Kenya', 'city' => 'Nairobi', 'region' => 'Nairobi'];
    }

    private static function isSuspiciousActivity($userId, $ip)
    {
        // Check for suspicious patterns
        $recentSessions = static::where('user_id', $userId)
            ->where('login_at', '>=', now()->subHours(24))
            ->get();

        return $recentSessions->pluck('ip_address')->unique()->count() > 5;
    }
}