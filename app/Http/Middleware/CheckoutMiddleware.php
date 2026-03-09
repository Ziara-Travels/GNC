<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutMiddleware
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
        // Check if there's flight data in the session
        if (!Session::has('flight_booking_data')) {
            return redirect()->route('flights.search')
                           ->with('error', 'Please select a flight before proceeding to checkout');
        }

        // Check if user is authenticated
        if (!auth()->check()) {
            // Store the intended URL
            Session::put('checkout', true);
            return redirect()->route('login')
                           ->with('message', 'Please login or register to complete your booking');
        }

        // Check if the flight is still available
        $flightData = Session::get('flight_booking_data');
        if (!$this->isFlightAvailable($flightData)) {
            Session::forget('flight_booking_data');
            return redirect()->route('flights.search')
                           ->with('error', 'Selected flight is no longer available. Please choose another flight.');
        }

        return $next($request);
    }

    /**
     * Check if the flight is still available
     *
     * @param array $flightData
     * @return bool
     */
    private function isFlightAvailable($flightData)
    {
        // Add your flight availability check logic here
        // This could involve checking against your database or external API
        return true; // Placeholder return
    }
}
