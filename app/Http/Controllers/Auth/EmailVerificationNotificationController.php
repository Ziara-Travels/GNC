<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
    
    /**
     * Verify email and activate user account
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        
        if ($request->user()->markEmailAsVerified()) {
            // Activate the user
            $user->is_active = true;
            $user->save();
            
            event(new \Illuminate\Auth\Events\Verified($request->user()));
        }
        
        return redirect()->intended(
            $request->input('redirect', RouteServiceProvider::HOME) . '?verified=1'
        );
    }
}
