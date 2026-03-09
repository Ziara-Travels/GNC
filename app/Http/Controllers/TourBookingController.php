<?php

namespace App\Http\Controllers;

use App\Models\TourBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    /**
     * Store a new tour booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'amadeus_activity_id' => 'required|string',
            'amadeus_data' => 'required|json',
            'traveler_details' => 'required|array',
            'contact_details' => 'required|array',
            'activity_name' => 'required|string|max:255',
            'destination' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'activity_date' => 'required|date',
            'activity_time' => 'nullable|string',
            'duration' => 'nullable|string|max:50',
            'traveler_count' => 'required|integer|min:1|max:20',
            'activity_type' => 'nullable|string|max:100',
            'difficulty_level' => 'nullable|string|max:50',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string|max:50',
        ]);

        try {
            // Generate unique booking reference
            $bookingReference = 'TR' . strtoupper(Str::random(8));
            
            // Ensure the booking reference is unique
            while (TourBooking::where('booking_reference', $bookingReference)->exists()) {
                $bookingReference = 'TR' . strtoupper(Str::random(8));
            }

            // Create the tour booking
            $booking = TourBooking::create([
                'user_id' => Auth::id(),
                'amadeus_activity_id' => $request->amadeus_activity_id,
                'amadeus_data' => json_decode($request->amadeus_data, true),
                'activity_name' => $request->activity_name,
                'destination' => $request->destination,
                'country' => $request->country,
                'activity_date' => $request->activity_date,
                'activity_time' => $request->activity_time,
                'duration' => $request->duration,
                'traveler_count' => $request->traveler_count,
                'activity_type' => $request->activity_type,
                'difficulty_level' => $request->difficulty_level,
                'total_price' => $request->total_price,
                'currency' => $request->currency,
                'booking_reference' => $bookingReference,
                'traveler_details' => $request->traveler_details,
                'contact_details' => $request->contact_details,
                'payment_method' => $request->payment_method,
                'booking_status' => 'pending',
                'payment_status' => 'pending',
                'booked_at' => now(),
            ]);

            Log::info('Tour booking created', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'activity' => $request->activity_name . ' in ' . $request->destination,
                'total_price' => $request->total_price . ' ' . $request->currency
            ]);

            // TODO: Integrate with payment gateway
            // For now, we'll mark it as confirmed for demo purposes
            $booking->update([
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_reference' => 'PAY' . strtoupper(Str::random(10))
            ]);

            // TODO: Send booking confirmation email
            // TODO: Generate tour voucher/tickets

            return response()->json([
                'success' => true,
                'message' => 'Tour booking created successfully!',
                'booking_reference' => $bookingReference,
                'booking_id' => $booking->id,
                'redirect_url' => route('bookings.confirmation', ['type' => 'tour', 'reference' => $bookingReference])
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating tour booking', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['amadeus_data'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user's tour bookings
     */
    public function index()
    {
        $bookings = TourBooking::where('user_id', Auth::id())
                              ->orderBy('activity_date', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);

        return view('bookings.tours.index', compact('bookings'));
    }

    /**
     * Show specific tour booking details
     */
    public function showBooking($reference)
    {
        $booking = TourBooking::where('booking_reference', $reference)
                             ->where('user_id', Auth::id())
                             ->firstOrFail();

        return view('bookings.tours.show', compact('booking'));
    }

    /**
     * Cancel a tour booking
     */
    public function cancel(Request $request, $reference)
    {
        $booking = TourBooking::where('booking_reference', $reference)
                             ->where('user_id', Auth::id())
                             ->firstOrFail();

        if ($booking->isCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking is already cancelled.'
            ]);
        }

        try {
            $booking->update([
                'booking_status' => 'cancelled',
                'cancelled_at' => now(),
                'notes' => $request->input('cancellation_reason', 'Cancelled by user')
            ]);

            Log::info('Tour booking cancelled', [
                'booking_id' => $booking->id,
                'booking_reference' => $reference,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelling tour booking', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking. Please contact support.'
            ], 500);
        }
    }
}
