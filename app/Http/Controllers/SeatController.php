<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Subscribe user to seat availability alerts.
     */
    public function subscribeAlerts(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|string',
            'seat_class' => 'required|string',
            'email' => 'nullable|email',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'You will be notified when seats become available.',
        ]);
    }
}
