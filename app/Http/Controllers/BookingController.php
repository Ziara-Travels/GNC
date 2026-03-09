<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Save a booking draft to the session.
     */
    public function saveDraft(Request $request)
    {
        $request->validate([
            'booking_type' => 'required|in:flight,hotel,tour',
            'booking_data' => 'required|array',
        ]);

        Session::put('booking_draft_' . $request->booking_type, $request->booking_data);

        return response()->json([
            'success' => true,
            'message' => 'Booking draft saved.',
        ]);
    }
}
