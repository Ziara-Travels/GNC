<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TourBooking;
use Illuminate\Support\Str;

/**
 * Activity Controller - handles tour/activity bookings and East African activities
 */
class ActivityController extends Controller
{
    /**
     * Show payment page for a tour booking
     */
    public function payment($reference)
    {
        $booking = TourBooking::where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('tours.payment', compact('booking', 'reference'));
    }

    /**
     * Process tour booking payment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_reference' => 'required|string',
            'payment_method'    => 'required|string',
        ]);

        $booking = TourBooking::where('booking_reference', $request->booking_reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Placeholder: update status
        $booking->update(['status' => 'confirmed', 'payment_status' => 'paid']);

        return redirect()->route('tours.confirmation', ['reference' => $booking->booking_reference])
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Booking confirmation page
     */
    public function confirmation($reference)
    {
        $booking = TourBooking::where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('tours.confirmation', compact('booking', 'reference'));
    }

    /**
     * My bookings list
     */
    public function myBookings()
    {
        $bookings = TourBooking::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('tours.my-bookings', compact('bookings'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $reference)
    {
        $booking = TourBooking::where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('tours.my-bookings')
            ->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Get available dates for a tour
     */
    public function getAvailableDates($tourId)
    {
        // Return next 90 days as available dates (placeholder)
        $dates = [];
        for ($i = 1; $i <= 90; $i++) {
            $dates[] = now()->addDays($i)->format('Y-m-d');
        }

        return response()->json(['available_dates' => $dates]);
    }

    /**
     * Get East African tours
     */
    public function getEastAfricanTours(Request $request)
    {
        $tours = [
            [
                'id'          => 'tour-masai-001',
                'name'        => 'Masai Mara Safari',
                'destination' => 'Masai Mara',
                'country'     => 'Kenya',
                'price'       => 45000,
                'currency'    => 'KES',
                'duration'    => '3 days',
                'category'    => 'Safari',
                'rating'      => 4.9,
                'image'       => 'masai-mara.jpg',
                'description' => 'Experience the Great Migration in the world-famous Masai Mara.',
            ],
            [
                'id'          => 'tour-serengeti-002',
                'name'        => 'Serengeti Experience',
                'destination' => 'Serengeti',
                'country'     => 'Tanzania',
                'price'       => 55000,
                'currency'    => 'KES',
                'duration'    => '4 days',
                'category'    => 'Safari',
                'rating'      => 4.8,
                'image'       => 'serengeti.jpg',
                'description' => 'Explore the endless plains of the Serengeti National Park.',
            ],
            [
                'id'          => 'tour-gorilla-003',
                'name'        => 'Gorilla Trekking Rwanda',
                'destination' => 'Volcanoes NP',
                'country'     => 'Rwanda',
                'price'       => 150000,
                'currency'    => 'KES',
                'duration'    => '2 days',
                'category'    => 'Wildlife',
                'rating'      => 5.0,
                'image'       => 'rwanda-gorillas.jpg',
                'description' => 'Trek through misty forests to encounter mountain gorillas.',
            ],
        ];

        return response()->json($tours);
    }

    /**
     * Get East African activities
     */
    public function getEastAfricanActivities(Request $request)
    {
        $activities = [
            [
                'id'          => 'act-zanzibar-001',
                'name'        => 'Zanzibar Spice Tour',
                'destination' => 'Zanzibar',
                'country'     => 'Tanzania',
                'price'       => 3500,
                'currency'    => 'KES',
                'duration'    => '4 hours',
                'category'    => 'Cultural',
                'rating'      => 4.7,
                'image'       => 'zanzibar-spice.jpg',
                'description' => 'Discover the aromatic spice plantations of Zanzibar.',
            ],
            [
                'id'          => 'act-nile-002',
                'name'        => 'White Water Rafting - Nile',
                'destination' => 'Jinja',
                'country'     => 'Uganda',
                'price'       => 8500,
                'currency'    => 'KES',
                'duration'    => '1 day',
                'category'    => 'Adventure',
                'rating'      => 4.8,
                'image'       => 'nile-rafting.jpg',
                'description' => 'Experience thrilling rapids at the source of the Nile.',
            ],
        ];

        return response()->json($activities);
    }

    /**
     * Book a tour
     */
    public function bookTour(Request $request)
    {
        $request->validate([
            'tour_id'   => 'required|string',
            'tour_name' => 'required|string',
            'date'      => 'required|date|after:today',
            'adults'    => 'required|integer|min:1',
        ]);

        $reference = 'TOUR-' . strtoupper(Str::random(8));

        // Store in session for guest users; for auth users create a record
        session(['pending_tour_booking' => array_merge($request->all(), ['reference' => $reference])]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to complete your booking.');
        }

        return redirect()->route('tours.confirmation', ['reference' => $reference]);
    }

    /**
     * Book an activity
     */
    public function bookActivity(Request $request)
    {
        $request->validate([
            'activity_id'   => 'required|string',
            'activity_name' => 'required|string',
            'date'          => 'required|date|after:today',
            'adults'        => 'required|integer|min:1',
        ]);

        $reference = 'ACT-' . strtoupper(Str::random(8));

        session(['pending_activity_booking' => array_merge($request->all(), ['reference' => $reference])]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to complete your booking.');
        }

        return response()->json(['success' => true, 'reference' => $reference]);
    }

    /**
     * Show confirmation page (public)
     */
    public function showConfirmation($reference)
    {
        return view('tours.confirmation', compact('reference'));
    }
}
