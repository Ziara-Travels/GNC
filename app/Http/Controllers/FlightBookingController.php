<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\BookingService;
use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\User;
use App\Models\Payment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FlightBookingController extends Controller
{
    protected $bookingService;
    protected $client;

    public function __construct(BookingService $bookingService, Client $client)
    {
        $this->bookingService = $bookingService;
        $this->client = $client;
    }

    /**
     * Show flight search page
     */
    public function search()
    {
        return view('flights.search');
    }

    /**
     * Handle flight search request
     */
    public function searchFlights(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date|after:departure_date',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:9',
            'infants' => 'nullable|integer|min:0|max:9',
            'travel_class' => 'required|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST'
        ]);

        try {
            $flights = $this->searchAmadeusFlights($request->all());
            
            return view('flights.flight-results', [
                'flights' => $flights,
                'search_params' => $request->all()
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to search flights: ' . $e->getMessage());
        }
    }

    /**
     * Book flight for current user
     */
    public function bookFlight(Request $request)
    {
        $request->validate([
            'flight_data' => 'required|array',
            'traveler_details' => 'required|array',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string'
        ]);

        try {
            $bookingData = $this->prepareFlightBookingData($request->all());
            
            $result = $this->bookingService->createBooking(
                $bookingData,
                Auth::user()
            );

            return redirect()->route('flights.booking.payment', $result['booking']->id)
                ->with('success', 'Flight booking created successfully. Please complete payment.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin/Superadmin book flight for other users
     */
    public function adminBookFlight(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'flight_data' => 'required|array',
            'traveler_details' => 'required|array',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'book_for_user_id' => 'nullable|exists:users,id'
        ]);

        try {
            $bookingData = $this->prepareFlightBookingData($request->all());
            $bookFor = $request->book_for_user_id ? User::find($request->book_for_user_id) : null;
            
            $result = $this->bookingService->createBooking(
                $bookingData,
                Auth::user(),
                $bookFor
            );

            $message = $bookFor 
                ? "Flight booked for {$bookFor->name}. They will receive a payment notification."
                : 'Flight booked successfully. Please complete payment.';

            if ($bookFor) {
                return redirect()->route('admin.bookings.index')
                    ->with('success', $message);
            } else {
                return redirect()->route('flights.booking.payment', $result['booking']->id)
                    ->with('success', $message);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin/Superadmin bulk book flights for multiple users
     */
    public function bulkBookFlight(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'flight_data' => 'required|array',
            'traveler_details' => 'required|array',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $bookingData = $this->prepareFlightBookingData($request->all());
            
            $result = $this->bookingService->createBulkBookings(
                $bookingData,
                Auth::user(),
                $request->user_ids
            );

            $count = count($result['bookings']);
            return redirect()->route('admin.bookings.index')
                ->with('success', "Successfully created {$count} flight bookings. Users will receive payment notifications.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create bulk bookings: ' . $e->getMessage());
        }
    }

    /**
     * Show payment page for booking
     */
    public function showPayment(Booking $booking)
    {
        if (!$this->canAccessBooking($booking)) {
            abort(403, 'Unauthorized to access this booking');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('bookings.show', $booking)
                ->with('info', 'This booking has already been paid.');
        }

        return view('flights.payment', compact('booking'));
    }

    /**
     * Process payment for booking
     */
    public function processPayment(Request $request, Booking $booking)
    {
        if (!$this->canAccessBooking($booking)) {
            abort(403, 'Unauthorized to access this booking');
        }

        $request->validate([
            'payment_method' => 'required|in:mpesa,card,bank_transfer',
            'payment_details' => 'required|array'
        ]);

        try {
            $paymentData = [
                'payment_method' => $request->payment_method,
                'transaction_id' => $request->payment_details['transaction_id'] ?? null,
                'provider_response' => $request->payment_details
            ];

            $this->bookingService->processPayment($booking, $paymentData, Auth::user());

            return redirect()->route('bookings.show', $booking)
                ->with('success', 'Payment processed successfully. Your flight is confirmed!');
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Show user's pending payments
     */
    public function pendingPayments()
    {
        $pendingBookings = $this->bookingService->getUserPendingPayments(Auth::id());
        
        return view('dashboard.pending-payments', compact('pendingBookings'));
    }

    /**
     * Admin view of bookings they made
     */
    public function adminBookings()
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $bookings = $this->bookingService->getBookingsMadeBy(Auth::id());
        
        return view('dashboard.admin-bookings', compact('bookings'));
    }

    /**
     * Search Amadeus flights
     */
    private function searchAmadeusFlights($params)
    {
        $access_token = $this->getAccessToken();
        
        $searchParams = [
            'originLocationCode' => $params['origin'],
            'destinationLocationCode' => $params['destination'],
            'departureDate' => $params['departure_date'],
            'adults' => $params['adults'],
            'travelClass' => $params['travel_class']
        ];

        if (!empty($params['return_date'])) {
            $searchParams['returnDate'] = $params['return_date'];
        }

        if (!empty($params['children'])) {
            $searchParams['children'] = $params['children'];
        }

        if (!empty($params['infants'])) {
            $searchParams['infants'] = $params['infants'];
        }

        $response = $this->client->get('https://test.api.amadeus.com/v2/shopping/flight-offers', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/json',
            ],
            'query' => $searchParams
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get Amadeus access token
     */
    private function getAccessToken()
    {
        $response = $this->client->post('https://test.api.amadeus.com/v1/security/oauth2/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => env('AMADEUS_CLIENT_ID'),
                'client_secret' => env('AMADEUS_CLIENT_SECRET'),
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['access_token'];
    }

    /**
     * Prepare flight booking data
     */
    private function prepareFlightBookingData($requestData)
    {
        $flightData = $requestData['flight_data'];
        
        return [
            'booking_type' => 'flight',
            'title' => "Flight from {$flightData['origin']} to {$flightData['destination']}",
            'flight_number' => $flightData['flight_number'] ?? null,
            'departure_airport' => $flightData['origin'],
            'arrival_airport' => $flightData['destination'],
            'departure_time' => $flightData['departure_time'] ?? null,
            'arrival_time' => $flightData['arrival_time'] ?? null,
            'total_amount' => $flightData['price']['total'],
            'currency' => $flightData['price']['currency'],
            'adults' => $flightData['adults'] ?? 1,
            'children' => $flightData['children'] ?? 0,
            'infants' => $flightData['infants'] ?? 0,
            'travel_class' => $flightData['travel_class'] ?? 'ECONOMY',
            'contact_email' => $requestData['contact_email'],
            'contact_phone' => $requestData['contact_phone'],
            'flight_details' => $flightData,
            'traveler_details' => $requestData['traveler_details'],
            'status' => 'pending_payment',
            'payment_status' => 'pending'
        ];
    }

    /**
     * Check if user can access booking
     */
    private function canAccessBooking($booking)
    {
        $user = Auth::user();
        
        return $booking->user_id === $user->id || 
               $booking->booked_by === $user->id || 
               $user->hasRole(['admin', 'superadmin']);
    }

    /**
     * Display flight booking form
     */
    public function show($offerId)
    {
        return view('bookings.flight', [
            'offerId' => $offerId,
            'user' => Auth::user()
        ]);
    }

    /**
     * Store a new flight booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'amadeus_offer_id' => 'required|string',
            'amadeus_data' => 'required|json',
            'passenger_details' => 'required|array',
            'contact_details' => 'required|array',
            'origin_code' => 'required|string|max:5',
            'origin_city' => 'required|string|max:100',
            'destination_code' => 'required|string|max:5',
            'destination_city' => 'required|string|max:100',
            'departure_date' => 'required|date',
            'departure_time' => 'nullable|string',
            'return_date' => 'nullable|date',
            'return_time' => 'nullable|string',
            'passenger_count' => 'required|integer|min:1|max:9',
            'cabin_class' => 'required|string|max:20',
            'airline_code' => 'nullable|string|max:10',
            'flight_duration' => 'nullable|string|max:20',
            'stops' => 'required|integer|min:0',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string|max:50',
        ]);

        try {
            $bookingReference = 'FL' . strtoupper(Str::random(8));
            while (FlightBooking::where('booking_reference', $bookingReference)->exists()) {
                $bookingReference = 'FL' . strtoupper(Str::random(8));
            }

            $booking = FlightBooking::create([
                'user_id' => Auth::id(),
                'amadeus_offer_id' => $request->amadeus_offer_id,
                'amadeus_data' => json_decode($request->amadeus_data, true),
                'origin_code' => $request->origin_code,
                'origin_city' => $request->origin_city,
                'destination_code' => $request->destination_code,
                'destination_city' => $request->destination_city,
                'departure_date' => $request->departure_date,
                'departure_time' => $request->departure_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'passenger_count' => $request->passenger_count,
                'cabin_class' => $request->cabin_class,
                'airline_code' => $request->airline_code,
                'flight_duration' => $request->flight_duration,
                'stops' => $request->stops,
                'total_price' => $request->total_price,
                'currency' => $request->currency,
                'booking_reference' => $bookingReference,
                'passenger_details' => $request->passenger_details,
                'contact_details' => $request->contact_details,
                'payment_method' => $request->payment_method,
                'booking_status' => 'pending',
                'payment_status' => 'pending',
                'booked_at' => now(),
            ]);

            Log::info('Flight booking created', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
            ]);

            $booking->update([
                'booking_status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_reference' => 'PAY' . strtoupper(Str::random(10))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Flight booking created successfully!',
                'booking_reference' => $bookingReference,
                'booking_id' => $booking->id,
                'redirect_url' => route('bookings.confirmation', ['type' => 'flight', 'reference' => $bookingReference])
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating flight booking', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.',
            ], 500);
        }
    }

    /**
     * Show user's flight bookings
     */
    public function index()
    {
        $bookings = FlightBooking::where('user_id', Auth::id())
                                 ->orderBy('departure_date', 'desc')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);

        return view('bookings.flights.index', compact('bookings'));
    }

    /**
     * Show specific flight booking details
     */
    public function showBooking($reference)
    {
        $booking = FlightBooking::where('booking_reference', $reference)
                               ->where('user_id', Auth::id())
                               ->firstOrFail();

        return view('bookings.flights.show', compact('booking'));
    }

    /**
     * Cancel a flight booking
     */
    public function cancel(Request $request, $reference)
    {
        $booking = FlightBooking::where('booking_reference', $reference)
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

            Log::info('Flight booking cancelled', [
                'booking_id' => $booking->id,
                'booking_reference' => $reference,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelling flight booking', [
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

            Log::info('Flight booking created', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'route' => $request->origin_city . ' to ' . $request->destination_city,
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
            // TODO: Create tickets/boarding passes

            return response()->json([
                'success' => true,
                'message' => 'Flight booking created successfully!',
                'booking_reference' => $bookingReference,
                'booking_id' => $booking->id,
                'redirect_url' => route('bookings.confirmation', ['type' => 'flight', 'reference' => $bookingReference])
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating flight booking', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['amadeus_data']) // Exclude large data from log
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user's flight bookings
     */
    public function index()
    {
        $bookings = FlightBooking::where('user_id', Auth::id())
                                 ->orderBy('departure_date', 'desc')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);

        return view('bookings.flights.index', compact('bookings'));
    }

    /**
     * Show specific flight booking details
     */
    public function showBooking($reference)
    {
        $booking = FlightBooking::where('booking_reference', $reference)
                               ->where('user_id', Auth::id())
                               ->firstOrFail();

        return view('bookings.flights.show', compact('booking'));
    }

    /**
     * Cancel a flight booking
     */
    public function cancel(Request $request, $reference)
    {
        $booking = FlightBooking::where('booking_reference', $reference)
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

            // TODO: Process refund if applicable
            // TODO: Cancel with airline/Amadeus
            // TODO: Send cancellation confirmation email

            Log::info('Flight booking cancelled', [
                'booking_id' => $booking->id,
                'booking_reference' => $reference,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelling flight booking', [
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
