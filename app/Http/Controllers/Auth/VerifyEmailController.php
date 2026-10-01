<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectAfterVerification($request);
        }

        if ($request->user()->markEmailAsVerified()) {
            // Activate user account
            $request->user()->is_active = true;
            $request->user()->save();
            
            event(new Verified($request->user()));
        }

        return $this->redirectAfterVerification($request);
    }

    /**
     * Resume an in-progress booking after email verification.
     *
     * Checkout requires a verified email, so a guest who selected a flight,
     * registered, and only then verified would otherwise land on the dashboard
     * and have to start the search over. The offer is still parked in the
     * `selected_flight` session key, so continue at the passenger details step.
     */
    private function redirectAfterVerification(EmailVerificationRequest $request)
    {
        if (session()->has('selected_flight')
            && ! in_array($request->user()->role, ['admin', 'superadmin'], true)) {
            return redirect()->route('booking.passengers.show')
                ->with('message', 'Email verified. Please enter passenger details to complete your booking.');
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
