<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Comma-separated list of allowed roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }
        
        // Get the authenticated user
        $user = auth()->user();
        
        // Handle multiple role checks (comma-separated roles)
        $roles = explode(',', $role);
        
        // Check if the user has one of the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        // If user doesn't have the required role, redirect to the appropriate dashboard
        // based on their actual role
        Log::warning("User {$user->id} with role '{$user->role}' attempted to access a route requiring '{$role}'");
        
        // Redirect to the appropriate dashboard based on user's role
        switch ($user->role) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard')
                    ->with('error', 'You do not have permission to access that page.');
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('error', 'You do not have permission to access that page.');
            case 'user':
                return redirect()->route('user.dashboard')
                    ->with('error', 'You do not have permission to access that page.');
            default:
                // For any other role or if role is not set properly
                return redirect()->route('home')
                    ->with('error', 'You do not have permission to access that page.');
        }
    }
}