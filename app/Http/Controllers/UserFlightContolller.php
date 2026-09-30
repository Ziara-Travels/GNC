<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class UserFlightContolller extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->middleware('auth');
    }

    public function search()
    {
        $user = Auth::user();
        
        // Check if user is authenticated and has user role
        if (!$user || $user->role !== 'user') {
            return redirect()->route('login')->with('error', 'You must be logged in as a user to search for flights.');
        }

        // Check if user has permission to search flights
        if (!$user->hasPermission('flights.search')) {
            abort(403, 'You do not have permission to search for flights. Please contact your administrator.');
        }

        // Get user statistics
        $userStats = [
            'searches' => DB::table('flight_searches')
                ->where('user_id', $user->id)
                ->count(),
            'bookings' => Booking::where('user_id', $user->id)
                ->where('type', 'flight')
                ->count(),
            'destinations' => DB::table('flight_searches')
                ->where('user_id', $user->id)
                ->distinct('to_iata')
                ->count()
        ];

        // Get recent searches for this user
        $recentSearches = DB::table('flight_searches')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($search) {
                return (object)[
                    'from_iata' => $search->from_iata,
                    'to_iata' => $search->to_iata,
                    'departure_date' => $search->departure_date,
                    'return_date' => $search->return_date,
                    'adults' => $search->adults,
                    'travel_class' => $search->travel_class,
                    'created_at' => (object)['diffForHumans' => \Carbon\Carbon::parse($search->created_at)->diffForHumans()]
                ];
            });

        // Get recent bookings for this user
        $recentBookings = Booking::where('user_id', $user->id)
            ->where('type', 'flight')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($booking) {
                return (object)[
                    'booking_reference' => $booking->booking_reference,
                    'departure_airport' => $booking->departure_airport,
                    'arrival_airport' => $booking->arrival_airport,
                    'departure_time' => (object)['format' => function($format) use ($booking) {
                        return $booking->departure_time->format($format);
                    }],
                    'status' => $booking->status
                ];
            });

        return view('dashboard.flights-search.user.search', compact('userStats', 'recentSearches', 'recentBookings'));
    }

    public function processSearch(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is authenticated and has user role
        if (!$user || $user->role !== 'user') {
            return redirect()->route('login')->with('error', 'You must be logged in as a user to search for flights.');
        }

        // Check if user has permission to search flights
        if (!$user->hasPermission('flights.search')) {
            abort(403, 'You do not have permission to search for flights. Please contact your administrator.');
        }

        $validator = Validator::make($request->all(), [
            'trip_type' => 'required|in:round_trip,one_way,multi_city',
            'from' => 'required|string',
            'from_iata' => 'required|string|size:3',
            'to' => 'required|string',
            'to_iata' => 'required|string|size:3',
            'date' => 'required|date|after_or_equal:today',
            'return_date' => 'nullable|date|after:date',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:9',
            'infants' => 'nullable|integer|min:0|max:9',
            'travel_class' => 'required|in:ECONOMY,PREMIUM_ECONOMY,BUSINESS,FIRST',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Get Amadeus access token
            $accessToken = $this->getAccessToken();
            
            // Prepare search parameters
            $searchParams = $this->prepareSearchParams($request->all());
            
            // Search flights using Amadeus API
            $flights = $this->searchAmadeusFlights($accessToken, $searchParams);
            
            // Log the search for this user
            $this->logFlightSearch($searchParams, $user->id);
            
            // Store results in session
            session([
                'flight_search_results' => $flights,
                'search_params' => $searchParams,
                'search_metadata' => [
                    'searched_for_user' => $user->id,
                    'searched_by' => $user->id,
                    'search_type' => 'personal',
                    'timestamp' => now()
                ]
            ]);
            
            return redirect()->route('user.flights.results');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Flight search failed: ' . $e->getMessage());
        }
    }

    public function showResults()
    {
        $user = Auth::user();
        
        // Check if user is authenticated and has user role
        if (!$user || $user->role !== 'user') {
            return redirect()->route('login')->with('error', 'You must be logged in as a user to view flight results.');
        }

        // Check if user has permission to view flight results
        if (!$user->hasPermission('flights.view')) {
            abort(403, 'You do not have permission to view flight results. Please contact your administrator.');
        }

        $flights = session('flight_search_results');
        $searchParams = session('search_params');
        $metadata = session('search_metadata');
        
        if (!$flights || !$searchParams) {
            return redirect()->route('user.flights.search')->with('error', 'No search results found. Please search again.');
        }

        // Ensure the results belong to the current user
        if ($metadata['searched_for_user'] !== $user->id) {
            return redirect()->route('user.flights.search')->with('error', 'Access denied. Please search again.');
        }

        return view('dashboard.flights-search.user.flight-results', compact('flights', 'searchParams', 'metadata'));
    }

    public function selectFlight(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is authenticated and has user role
        if (!$user || $user->role !== 'user') {
            return redirect()->route('login')->with('error', 'You must be logged in as a user to select flights.');
        }

        // Check if user has permission to book flights
        if (!$user->hasPermission('flights.book')) {
            abort(403, 'You do not have permission to book flights. Please contact your administrator.');
        }

        $validator = Validator::make($request->all(), [
            'flight_index' => 'required|integer',
            'flight_data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            // Store selected flight data
            session(['selected_flight' => $request->flight_data]);
            
            return redirect()->route('user.flights.booking');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Flight selection failed: ' . $e->getMessage());
        }
    }

    public function showBooking()
    {
        $user = Auth::user();
        
        // Check if user is authenticated and has user role
        if (!$user || $user->role !== 'user') {
            return redirect()->route('login')->with('error', 'You must be logged in as a user to book flights.');
        }

        // Check if user has permission to book flights
        if (!$user->hasPermission('flights.book')) {
            abort(403, 'You do not have permission to book flights. Please contact your administrator.');
        }

        $selectedFlight = session('selected_flight');
        $searchParams = session('search_params');
        
        if (!$selectedFlight || !$searchParams) {
            return redirect()->route('user.flights.search')->with('error', 'No flight selected. Please search and select a flight first.');
        }

        return view('dashboard.flights.user.booking', compact('selectedFlight', 'searchParams'));
    }

    private function prepareSearchParams($data)
    {
        return [
            'originLocationCode' => $data['from_iata'],
            'destinationLocationCode' => $data['to_iata'],
            'departureDate' => $data['date'],
            'returnDate' => $data['return_date'] ?? null,
            'adults' => $data['adults'],
            'children' => $data['children'] ?? 0,
            'infants' => $data['infants'] ?? 0,
            'travelClass' => $data['travel_class'],
            'tripType' => $data['trip_type'],
            'nonStop' => isset($data['non_stop']) ? 'true' : 'false',
            'max' => 50
        ];
    }

    private function searchAmadeusFlights($accessToken, $params)
    {
        try {
            $queryParams = [
                'originLocationCode' => $params['originLocationCode'],
                'destinationLocationCode' => $params['destinationLocationCode'],
                'departureDate' => $params['departureDate'],
                'adults' => $params['adults'],
                'nonStop' => $params['nonStop'],
                'max' => $params['max']
            ];

            if (!empty($params['returnDate'])) {
                $queryParams['returnDate'] = $params['returnDate'];
            }

            if ($params['children'] > 0) {
                $queryParams['children'] = $params['children'];
            }

            if ($params['infants'] > 0) {
                $queryParams['infants'] = $params['infants'];
            }

            if ($params['travelClass'] !== 'ECONOMY') {
                $queryParams['travelClass'] = $params['travelClass'];
            }

            $response = $this->client->get('https://test.api.amadeus.com/v2/shopping/flight-offers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ],
                'query' => $queryParams
            ]);

            $data = json_decode($response->getBody(), true);
            
            // Log API call
            $this->logApiCall('amadeus', 'flight-offers', 'GET', $queryParams, $data, $response->getStatusCode());
            
            return $data['data'] ?? [];

        } catch (GuzzleException $e) {
            // Log API error
            $this->logApiCall('amadeus', 'flight-offers', 'GET', $params, ['error' => $e->getMessage()], $e->getCode());
            
            throw new \Exception('Failed to search flights: ' . $e->getMessage());
        }
    }

    private function logFlightSearch($searchParams, $userId)
    {
        DB::table('flight_searches')->insert([
            'user_id' => $userId,
            'searched_by' => $userId,
            'from_iata' => $searchParams['originLocationCode'],
            'to_iata' => $searchParams['destinationLocationCode'],
            'departure_date' => $searchParams['departureDate'],
            'return_date' => $searchParams['returnDate'],
            'adults' => $searchParams['adults'],
            'children' => $searchParams['children'],
            'infants' => $searchParams['infants'],
            'travel_class' => $searchParams['travelClass'],
            'trip_type' => $searchParams['tripType'],
            'search_type' => 'personal',
            'search_parameters' => json_encode($searchParams),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function logApiCall($service, $endpoint, $method, $requestData, $responseData, $statusCode)
    {
        DB::table('api_logs')->insert([
            'service' => $service,
            'endpoint' => $endpoint,
            'method' => $method,
            'request_data' => json_encode($requestData),
            'response_data' => json_encode($responseData),
            'status_code' => $statusCode,
            'response_time' => 0.5, // Mock response time
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function getAccessToken()
    {
        if (Cache::has('amadeus_access_token')) {
            return Cache::get('amadeus_access_token');
        }

        try {
            $response = $this->client->post('https://test.api.amadeus.com/v1/security/oauth2/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => env('AMADEUS_CLIENT_ID'),
                    'client_secret' => env('AMADEUS_CLIENT_SECRET')
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $accessToken = $data['access_token'];
            
            // Cache token for 1800 seconds (30 minutes)
            Cache::put('amadeus_access_token', $accessToken, 1800);
            
            return $accessToken;
        } catch (GuzzleException $e) {
            throw new \Exception('Failed to get access token: ' . $e->getMessage());
        }
    }
}