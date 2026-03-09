<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return the authenticated user's profile data as JSON.
     */
    public function getProfile()
    {
        $user = Auth::user();

        return response()->json([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'phone'      => $user->phone ?? null,
            'created_at' => $user->created_at,
        ]);
    }

    /**
     * Return the authenticated user's most recent booking.
     */
    public function getPreviousBooking()
    {
        $user = Auth::user();

        $booking = $user->bookings()->latest()->first();

        if (! $booking) {
            return response()->json(['booking' => null]);
        }

        return response()->json(['booking' => $booking]);
    }
}
