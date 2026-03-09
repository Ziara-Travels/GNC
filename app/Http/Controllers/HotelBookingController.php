<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\BookingService;
use App\Models\Booking;
use App\Models\HotelBooking;
use App\Models\User;
use App\Models\Payment;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HotelBookingController extends Controller
{
    protected $bookingService;
    protected $client;

    public function __construct(BookingService $bookingService, Client $client)
    {
        $this->bookingService = $bookingService;
        $this->client = $client;
    }

    /**
     * Show hotel search page
     */
    public function search()
    {
        return view('hotels.hotel-search');
    }

    /**
     * Handle hotel search request
     */
    public function searchHotels(Request $request)
    {
        $request->validate([
            'destination' => 'required|string',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0|max:10',
            'rooms' => 'required|integer|min:1|max:8'
        ]);

        try {
            $hotels = $this->searchAmadeusHotels($request->all());
            
            return view('hotels.hotel-results', [
                'hotels' => $hotels,
                'search_params' => $request->all()
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to search hotels: ' . $e->getMessage());
        }
    }

    /**
     * Book hotel for current user
     */
    public function bookHotel(Request $request)
    {
        $request->validate([
            'hotel_data' => 'required|array',
            'guest_details' => 'required|array',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string'
        ]);

        try {
            $bookingData = $this->prepareHotelBookingData($request->all());
            
            $result = $this->bookingService->createBooking(
                $bookingData,
                Auth::user()
            );

            return redirect()->route('hotels.booking.payment', $result['booking']->id)
                ->with('success', 'Hotel booking created successfully. Please complete payment.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin/Superadmin book hotel for other users
     */
    public function adminBookHotel(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'hotel_data' => 'required|array',
            'guest_details' => 'required|array',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'book_for_user_id' => 'nullable|exists:users,id'
        ]);

        try {
            $bookingData = $this->prepareHotelBookingData($request->all());
            $bookFor = $request->book_for_user_id ? User::find($request->book_for_user_id) : null;
            
            $result = $this->bookingService->createBooking(
                $bookingData,
                Auth::user(),
                $bookFor
            );

            $message = $bookFor 
                ? "Hotel booked for {$bookFor->name}. They will receive a payment notification."
                : 'Hotel booked successfully. Please complete payment.';

            if ($bookFor) {
                return redirect()->route('admin.bookings.index')
                    ->with('success', $message);
            } else {
                return redirect()->route('hotels.booking.payment', $result['booking']->id)
                    ->with('success', $message);
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    /**
     * Admin/Superadmin bulk book hotels for multiple users
     */
    public function bulkBookHotel(Request $request)
    {
        if (!Auth::user()->hasRole(['admin', 'superadmin'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'hotel_data' => 'required|array',
            'guest_details' => 'required|array',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $bookingData = $this->prepareHotelBookingData($request->all());
            
            $result = $this->bookingService->createBulkBookings(
                $bookingData,
                Auth::user(),
                $request->user_ids
            );

            $count = count($result['bookings']);
            return redirect()->route('admin.bookings.index')
                ->with('success', "Successfully created {$count} hotel bookings. Users will receive payment notifications.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create bulk bookings: ' . $e->getMessage());
        }
    }

    /**
     * Show payment page for hotel booking
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

        return view('hotels.payment', compact('booking'));
    }

    /**
     * Process payment for hotel booking
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
                ->with('success', 'Payment processed successfully. Your hotel is confirmed!');
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    /**
     * Search Amadeus hotels
     */
    private function searchAmadeusHotels($params)
    {
        $access_token = $this->getAccessToken();
        
        // First, get city code from destination
        $cityCode = $this->getCityCode($params['destination'], $access_token);
        
        $searchParams = [
            'cityCode' => $cityCode,
            'checkInDate' => $params['check_in'],
            'checkOutDate' => $params['check_out'],
            'adults' => $params['adults'],
            'roomQuantity' => $params['rooms']
        ];

        if (!empty($params['children'])) {
            $searchParams['childAges'] = implode(',', array_fill(0, $params['children'], '10'));
        }

        $response = $this->client->get('https://test.api.amadeus.com/v3/shopping/hotel-offers', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/json',
            ],
            'query' => $searchParams
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get city code from destination name
     */
    private function getCityCode($destination, $access_token)
    {
        $response = $this->client->get('https://test.api.amadeus.com/v1/reference-data/locations/cities', [
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'Accept' => 'application/json',
            ],
            'query' => [
                'keyword' => $destination,
                'max' => 1
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        
        if (empty($data['data'])) {
            throw new \Exception('Destination not found');
        }

        return $data['data'][0]['iataCode'];
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
     * Prepare hotel booking data
     */
    private function prepareHotelBookingData($requestData)
    {
        $hotelData = $requestData['hotel_data'];
        
        return [
            'booking_type' => 'hotel',
            'title' => "Hotel {$hotelData['name']} in {$hotelData['location']}",
            'hotel_id' => $hotelData['hotel_id'] ?? null,
            'check_in_date' => $hotelData['check_in'],
            'check_out_date' => $hotelData['check_out'],
            'total_amount' => $hotelData['price']['total'],
            'currency' => $hotelData['price']['currency'],
            'adults' => $hotelData['adults'] ?? 1,
            'children' => $hotelData['children'] ?? 0,
            'contact_email' => $requestData['contact_email'],
            'contact_phone' => $requestData['contact_phone'],
            'hotel_details' => $hotelData,
            'traveler_details' => $requestData['guest_details'],
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

    // ---- Methods from merged duplicate class ----

    /**
     * Display hotel booking form
     */
    public function show($offerId)
    {
        return view('bookings.hotel', [
            'offerId' => $offerId,
            'user' => Auth::user()
        ]);
    }

    /**
     * Store a new hotel booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'amadeus_offer_id' => 'required|string',
            'amadeus_data' => 'required|json',
            'guest_details' => 'required|array',
            'contact_details' => 'required|array',
            'hotel_code' => 'required|string|max:10',
            'hotel_name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'room_type' => 'required|string|max:100',
            'guest_count' => 'required|integer|min:1|max:20',
            'room_count' => 'required|integer|min:1|max:10',
            'nights' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string|max:50',
        ]);

        try {
            // Generate unique booking reference
            $bookingReference = 'HT' . strtoupper(Str::random(8));
            
            // Ensure the booking reference is unique
            while (HotelBooking::where('booking_reference', $bookingReference)->exists()) {
                $bookingReference = 'HT' . strtoupper(Str::random(8));
            }

            // Create the hotel booking
            $booking = HotelBooking::create([
                'user_id' => Auth::id(),
                'amadeus_offer_id' => $request->amadeus_offer_id,
                'amadeus_data' => json_decode($request->amadeus_data, true),
                'hotel_code' => $request->hotel_code,
                'hotel_name' => $request->hotel_name,
                'city' => $request->city,
                'country' => $request->country,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'room_type' => $request->room_type,
                'guest_count' => $request->guest_count,
                'room_count' => $request->room_count,
                'nights' => $request->nights,
                'total_price' => $request->total_price,
                'currency' => $request->currency,
                'booking_reference' => $bookingReference,
                'guest_details' => $request->guest_details,
                'contact_details' => $request->contact_details,
                'payment_method' => $request->payment_method,
                'booking_status' => 'pending',
                'payment_status' => 'pending',
                'booked_at' => now(),
            ]);

            Log::info('Hotel booking created', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'hotel' => $request->hotel_name . ' in ' . $request->city,
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
            // TODO: Generate voucher/confirmation document

            return response()->json([
                'success' => true,
                'message' => 'Hotel booking created successfully!',
                'booking_reference' => $bookingReference,
                'booking_id' => $booking->id,
                'redirect_url' => route('bookings.confirmation', ['type' => 'hotel', 'reference' => $bookingReference])
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating hotel booking', [
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
     * Show user's hotel bookings
     */
    public function index()
    {
        $bookings = HotelBooking::where('user_id', Auth::id())
                                ->orderBy('check_in_date', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('bookings.hotels.index', compact('bookings'));
    }

    /**
     * Show specific hotel booking details
     */
    public function showBooking($reference)
    {
        $booking = HotelBooking::where('booking_reference', $reference)
                              ->where('user_id', Auth::id())
                              ->firstOrFail();

        return view('bookings.hotels.show', compact('booking'));
    }

    /**
     * Cancel a hotel booking
     */
    public function cancel(Request $request, $reference)
    {
        $booking = HotelBooking::where('booking_reference', $reference)
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

            Log::info('Hotel booking cancelled', [
                'booking_id' => $booking->id,
                'booking_reference' => $reference,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelling hotel booking', [
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
