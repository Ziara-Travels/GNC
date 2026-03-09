<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TwoFactorAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        if ($user && $user->twoFactorAuth && $user->twoFactorAuth->enabled && !Session::get('two_factor_verified')) {
            return redirect()->route('two-factor.verify-form');
        }

        return $next($request);
    }
}
