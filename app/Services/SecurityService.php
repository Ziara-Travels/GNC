<?php


namespace App\Services;

use App\Models\UserSession;
use App\Models\User;
use App\Notifications\SuspiciousActivityAlert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class SecurityService
{
    public function detectSuspiciousActivity($userId)
    {
        $user = User::find($userId);
        $currentIP = Request::ip();
        $userAgent = Request::userAgent();

        $suspiciousIndicators = [];

        // Check for multiple IP addresses in short time
        $recentSessions = UserSession::where('user_id', $userId)
            ->where('login_at', '>=', now()->subHours(1))
            ->get();

        $uniqueIPs = $recentSessions->pluck('ip_address')->unique();
        if ($uniqueIPs->count() > 3) {
            $suspiciousIndicators[] = 'Multiple IP addresses in short time';
        }

        // Check for unusual location
        $lastSession = UserSession::where('user_id', $userId)
            ->where('id', '!=', session()->getId())
            ->orderBy('login_at', 'desc')
            ->first();

        if ($lastSession && $lastSession->location_country) {
            $currentLocation = $this->getLocationFromIP($currentIP);
            if ($currentLocation['country'] !== $lastSession->location_country) {
                $suspiciousIndicators[] = 'Login from different country';
            }
        }

        // Check for rapid login attempts
        $loginAttempts = Cache::get('login_attempts_' . $currentIP, 0);
        if ($loginAttempts > 5) {
            $suspiciousIndicators[] = 'Multiple login attempts';
        }

        // Check for unusual user agent
        if ($lastSession && $lastSession->user_agent !== $userAgent) {
            $suspiciousIndicators[] = 'Different device/browser';
        }

        // If suspicious activity detected, alert user
        if (count($suspiciousIndicators) >= 2) {
            $this->alertSuspiciousActivity($user, $suspiciousIndicators, $currentIP);
            return true;
        }

        return false;
    }

    private function alertSuspiciousActivity($user, $indicators, $ip)
    {
        $user->notify(new SuspiciousActivityAlert($indicators, $ip));
        
        // Log the suspicious activity
        \Log::warning('Suspicious activity detected', [
            'user_id' => $user->id,
            'ip_address' => $ip,
            'indicators' => $indicators
        ]);
    }

    private function getLocationFromIP($ip)
    {
        // Mock implementation - integrate with actual IP geolocation service
        return [
            'country' => 'Kenya',
            'city' => 'Nairobi',
            'region' => 'Nairobi'
        ];
    }
}