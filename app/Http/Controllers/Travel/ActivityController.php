<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tour;
use App\Models\Booking;
use App\Services\BookingService;

/**
 * Activity Controller - handles tour/activity bookings and East African activities.
 *
 * Bookings are stored as generic Booking records (booking_type 'tour'/'activity'),
 * created via BookingService, the same path flights and hotels use.
 */
class ActivityController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Show payment page for a tour booking
     */
    public function payment($reference)
    {
        $booking = Booking::tours()
            ->where('booking_reference', $reference)
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
            'payment_method'    => 'required|in:mpesa,card,bank_transfer',
        ]);

        $booking = Booking::tours()
            ->where('booking_reference', $request->booking_reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $paymentData = [
            'payment_method' => $request->payment_method,
            'transaction_id' => 'TOUR-' . strtoupper(uniqid()),
            'provider_response' => $request->except(['_token', 'card_number', 'cvv']),
        ];

        $this->bookingService->processPayment($booking, $paymentData, Auth::user());

        return redirect()->route('tours.confirmation', ['reference' => $booking->booking_reference])
            ->with('success', 'Payment processed successfully.');
    }

    /**
     * Booking confirmation page
     */
    public function confirmation($reference)
    {
        $booking = Booking::tours()
            ->where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('tours.confirmation', compact('booking', 'reference'));
    }

    /**
     * My bookings list
     */
    public function myBookings()
    {
        $tourBookings = Booking::tours()
            ->with('tour')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('tours.my-bookings', compact('tourBookings'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, $reference)
    {
        $booking = Booking::tours()
            ->where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $booking->update([
            'status' => 'cancelled',
            'booking_status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

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
     * Get East African tours (multi-day) from the real tours catalog
     */
    public function getEastAfricanTours(Request $request)
    {
        return response()->json($this->catalogResponse(false));
    }

    /**
     * Get East African activities (short/day activities) from the real tours catalog
     */
    public function getEastAfricanActivities(Request $request)
    {
        return response()->json($this->catalogResponse(true));
    }

    private function catalogResponse($shortActivities)
    {
        $query = Tour::active();
        $shortActivities ? $query->whereNotNull('duration_hours') : $query->whereNull('duration_hours');

        return $query->get()->map(function (Tour $tour) {
            return [
                'id'          => $tour->id,
                'name'        => $tour->name,
                'destination' => $tour->city,
                'country'     => $tour->country,
                'price'       => (float) $tour->price,
                'currency'    => $tour->currency,
                'duration'    => $tour->duration,
                'category'    => $tour->category,
                'rating'      => (float) $tour->rating,
                'image'       => $tour->main_image,
                'description' => $tour->short_description,
            ];
        })->values();
    }

    /**
     * Book a tour
     */
    public function bookTour(Request $request)
    {
        return $this->createTourBooking($request, 'tour');
    }

    /**
     * Book an activity
     */
    public function bookActivity(Request $request)
    {
        return $this->createTourBooking($request, 'activity', true);
    }

    private function createTourBooking(Request $request, $bookingType, $jsonResponse = false)
    {
        if (!Auth::check()) {
            return $jsonResponse
                ? response()->json(['success' => false, 'message' => 'Please login to complete your booking.'], 401)
                : redirect()->route('login')->with('info', 'Please login to complete your booking.');
        }

        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'date'    => 'required|date|after:today',
            'adults'  => 'required|integer|min:1',
        ]);

        $tour = Tour::findOrFail($request->tour_id);
        $user = Auth::user();
        $totalPrice = $tour->discounted_price * $request->adults;

        $bookingData = [
            'booking_type' => $bookingType,
            'type' => $bookingType,
            'title' => "{$tour->name} - {$tour->city}",
            'tour_id' => $tour->id,
            'activity_name' => $tour->name,
            'destination' => $tour->city,
            'country' => $tour->country,
            'activity_date' => $request->date,
            'duration' => $tour->duration,
            'traveler_count' => $request->adults,
            'adults' => $request->adults,
            'amount' => $tour->discounted_price,
            'total_amount' => $totalPrice,
            'total_price' => $totalPrice,
            'currency' => $tour->currency,
            'tour_details' => $tour->toArray(),
            'contact_email' => $user->email,
            'contact_phone' => $user->phone ?? '',
        ];

        $result = $this->bookingService->createBooking($bookingData, $user);
        $reference = $result['booking']->booking_reference;

        if ($jsonResponse) {
            return response()->json([
                'success' => true,
                'reference' => $reference,
                'redirect_url' => route('tour.payment', ['reference' => $reference]),
            ]);
        }

        return redirect()->route('tours.payment', ['reference' => $reference]);
    }

    /**
     * Show confirmation page (public)
     */
    public function showConfirmation($reference)
    {
        return view('tours.confirmation', compact('reference'));
    }
}
