<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailVerifiedAndActive
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
        if (!$request->user() ||
            !$request->user()->hasVerifiedEmail() ||
            !$request->user()->is_active) {
            
            if (!$request->user()->hasVerifiedEmail()) {
                return $request->expectsJson()
                        ? abort(403, 'Your email address is not verified.')
                        : Redirect::route('verification.notice');
            }
            
            if (!$request->user()->is_active) {
                Auth::logout();
                
                return $request->expectsJson()
                        ? abort(403, 'Your account is not active.')
                        : Redirect::route('login')
                            ->with('error', 'Your account is not active. Please contact support.');
            }
        }

        return $next($request);
    }
}
