<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // If user is not authenticated, continue
        if (!$user) {
            return $next($request);
        }
        
        // Check if user has 2FA enabled
        $twoFactorAuth = $user->twoFactorAuth;
        
        // If 2FA is not enabled, user can proceed
        if (!$twoFactorAuth || !$twoFactorAuth->enabled) {
            return $next($request);
        }
        
        // If 2FA is enabled, check if this session is verified
        if (!session('two_factor_verified')) {
            // Allow access to 2FA verification routes
            if ($request->routeIs('two-factor.*')) {
                return $next($request);
            }
            
            // Allow access to logout
            if ($request->routeIs('logout')) {
                return $next($request);
            }
            
            // Redirect to 2FA verification
            return redirect()->route('two-factor.verify-form');
        }
        
        return $next($request);
    }
}
