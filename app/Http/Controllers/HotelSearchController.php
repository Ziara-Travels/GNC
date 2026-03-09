<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HotelSearchController extends Controller
{

     public function showSearchForm()
    {
        return view('hotels.hotel-search');
    }
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_code' => 'required|string|size:3',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:9',
            'rooms' => 'required|integer|min:1|max:9',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $url = 'https://test.api.amadeus.com/v3/shopping/hotel-offers';
        $access_token = $this->getAccessToken();

        $params = [
            'cityCode' => strtoupper($request->input('city_code')),
            'checkInDate' => $request->input('check_in_date'),
            'checkOutDate' => $request->input('check_out_date'),
            'adults' => $request->input('adults', 1),
            'rooms' => $request->input('rooms', 1),
            'currency' => 'USD',
            'lang' => 'EN',
            'paymentPolicy' => 'NONE',
            'includeClosed' => 'false'
        ];

        if ($request->has('children') && $request->input('children') > 0) {
            $params['children'] = $request->input('children');
        }

        if ($request->has('radius')) {
            $params['radius'] = $request->input('radius');
            $params['radiusUnit'] = 'KM';
        }

        try {
            $client = new Client();
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],
                'query' => $params
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Store search params and results for the results page
            session([
                'hotel_search_params' => $request->all(),
                'hotel_search_results' => $responseData['data'] ?? [],
                'amadeus_hotel_response' => $responseData
            ]);

            return redirect()->route('hotels.results');

        } catch (GuzzleException $exception) {
            Log::error('Hotel search failed', [
                'error' => $exception->getMessage(),
                'params' => $params
            ]);
            
            return redirect()->back()->with([
                'message' => 'Hotel search failed. Please try again.',
                'alert_type' => 'error'
            ])->withInput();
        }
    }
    
    /**
     * Show hotel search results
     */
    public function showResults(Request $request)
    {
        $searchParams = session('hotel_search_params') ?? [];
        $hotels = session('hotel_search_results') ?? [];
        
        return view('hotels.hotel-results', [
            'hotels' => $hotels,
            'searchParams' => $searchParams
        ]);
    }
    
    /**
     * Show a specific hotel details
     */
    public function showHotel($hotelId, Client $client)
    {
        $url = 'https://test.api.amadeus.com/v3/shopping/hotel-offers/by-hotel';
        $access_token = $this->getAccessToken($client);
        
        try {
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],
                'query' => [
                    'hotelId' => $hotelId
                ]
            ]);
            
            $response = json_decode($response->getBody());
            $hotel = $response->data ?? null;
            
            if (!$hotel) {
                return redirect()->route('hotels.search.form')->with([
                    'message' => 'Hotel not found. Please try your search again.',
                    'alert_type' => 'error'
                ]);
            }
            
            return view('hotels.hotel-details', [
                'hotel' => $hotel
            ]);
            
        } catch (GuzzleException $exception) {
            return redirect()->route('hotels.search.form')->with([
                'message' => 'Failed to retrieve hotel details. Please try again.',
                'alert_type' => 'error'
            ]);
        }
    }
    
    /**
     * Show the booking form for a hotel
     */
    public function bookingForm($hotelId, Client $client)
    {
        $url = 'https://test.api.amadeus.com/v3/shopping/hotel-offers/' . $hotelId;
        $access_token = $this->getAccessToken($client);
        
        try {
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ]
            ]);
            
            $response = json_decode($response->getBody());
            $hotel = $response->data ?? null;
            
            if (!$hotel) {
                return redirect()->route('hotels.search.form')->with([
                    'message' => 'Hotel not found. Please try your search again.',
                    'alert_type' => 'error'
                ]);
            }
            
            return view('hotels.hotel-booking', [
                'hotel' => $hotel
            ]);
            
        } catch (GuzzleException $exception) {
            return redirect()->route('hotels.search.form')->with([
                'message' => 'Failed to retrieve booking information. Please try again.',
                'alert_type' => 'error'
            ]);
        }
    }
    
    /**
     * Process the hotel booking
     */
    public function processBooking(Request $request)
    {
        // Validate booking information
        $validated = $request->validate([
            'offer_id' => 'required|string',
            'hotel_id' => 'required|string',
            'hotel_name' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'rooms' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'guest_details' => 'required|array',
            'contact_details' => 'required|array',
            'payment_method' => 'required|string|in:mpesa,mpesa_stk,bank,card'
        ]);
        
        try {
            // Create a booking reference
            $bookingReference = $this->generateBookingReference();
            
            // Store booking details in the session for checkout
            $bookingDetails = array_merge($validated, [
                'booking_reference' => $bookingReference,
                'status' => 'pending',
                'created_at' => now()
            ]);
            
            session(['hotel_booking' => $bookingDetails]);
            
            // Redirect to checkout
            return redirect()->route('hotels.checkout', ['reference' => $bookingReference]);
            
        } catch (\Exception $e) {
            Log::error('Hotel booking creation failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->except(['guest_details', 'contact_details'])
            ]);
            
            return redirect()->back()->with('error', 'Failed to create booking. Please try again.')->withInput();
        }
    }
    
    /**
     * Show the checkout page
     */
    public function checkout($reference)
    {
        $booking = session('hotel_booking');
        
        if (!$booking || $booking['booking_reference'] !== $reference) {
            return redirect()->route('hotels.search.form')->with([
                'message' => 'Booking not found. Please try your search again.',
                'alert_type' => 'error'
            ]);
        }
        
        return view('hotels.hotel-checkout', [
            'booking' => $booking
        ]);
    }
    
    /**
     * Process the payment for a hotel booking
     */
    public function processPayment(Request $request, $reference)
    {
        $booking = session('hotel_booking');
        
        if (!$booking || $booking['booking_reference'] !== $reference) {
            return redirect()->route('hotels.search.form')->with([
                'message' => 'Booking not found. Please try your search again.',
                'alert_type' => 'error'
            ]);
        }
        
        // Validate payment information
        $validated = $request->validate([
            'payment_method' => 'required|string|in:card,mpesa,paypal',
            'amount' => 'required|numeric',
        ]);
        
        // Process the payment (in a real app, you'd handle payment gateway integration here)
        
        // If successful, create the booking record in the database
        $hotelBooking = new \App\Models\Booking([
            'user_id' => auth()->id() ?? 1, // Guest user ID if not logged in
            'booking_reference' => $booking['booking_reference'],
            'booking_type' => 'hotel',
            'type' => 'hotel',
            'title' => $booking['room_type'],
            'hotel_id' => $booking['hotel_id'],
            'check_in_date' => $booking['check_in_date'],
            'check_out_date' => $booking['check_out_date'],
            'amount' => $booking['amount'],
            'total_amount' => $booking['amount'],
            'paid_amount' => $validated['amount'],
            'currency' => 'USD', // Default currency
            'status' => 'confirmed',
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'completed',
            'hotel_details' => json_encode($booking),
            'adults' => $booking['adults'],
            'children' => $booking['children'] ?? 0,
        ]);
        
        $hotelBooking->save();
        
        // Generate an e-ticket
        $this->generateETicket($hotelBooking);
        
        // Clear booking session data
        session()->forget('hotel_booking');
        
        // Redirect to confirmation page
        return redirect()->route('hotels.confirmation', ['reference' => $booking['booking_reference']]);
    }
    
    /**
     * Show the booking confirmation page
     */
    public function confirmation($reference)
    {
        $booking = \App\Models\Booking::where('booking_reference', $reference)->first();
        
        if (!$booking) {
            return redirect()->route('hotels.search.form')->with([
                'message' => 'Booking not found. Please try your search again.',
                'alert_type' => 'error'
            ]);
        }
        
        return view('hotels.hotel-confirmation', [
            'booking' => $booking
        ]);
    }
    
    /**
     * Generate a PDF e-ticket for the booking
     */
    private function generateETicket($booking)
    {
        // In a real app, you'd use something like dompdf to generate a PDF ticket
        // For now, we'll just return a flag indicating success
        return true;
    }
    
    /**
     * Generate a unique booking reference
     */
    private function generateBookingReference()
    {
        $prefix = 'HB'; // Hotel Booking
        $timestamp = substr(time(), -6);
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
        
        return $prefix . $timestamp . $random;
    }

    private function getAccessToken()
    {
        // Check if we have a cached token
        if (session('amadeus_access_token') && session('amadeus_token_expires') > time()) {
            return session('amadeus_access_token');
        }

        $url = 'https://test.api.amadeus.com/v1/security/oauth2/token';
        
        try {
            $client = new Client();
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => env('AMADEUS_API_KEY'),
                    'client_secret' => env('AMADEUS_API_SECRET')
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $accessToken = $data['access_token'];
            $expiresIn = $data['expires_in'] ?? 3600;

            // Cache the token
            session([
                'amadeus_access_token' => $accessToken,
                'amadeus_token_expires' => time() + $expiresIn - 60 // Subtract 1 minute for safety
            ]);

            return $accessToken;
            
        } catch (GuzzleException $e) {
            Log::error('Failed to get Amadeus access token', ['error' => $e->getMessage()]);
            throw new \Exception('Failed to authenticate with Amadeus API');
        }
    }
}