<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TourBookingController extends Controller
{
    /**
     * Display tour booking form
     */
    public function show($offerId)
    {
        return view('bookings.tour', [
            'offerId' => $offerId,
            'user' => Auth::user()
        ]);
    }
}
