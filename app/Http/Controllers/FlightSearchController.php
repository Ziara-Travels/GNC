<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Services\FlightOffersService;


class FlightSearchController extends Controller
{

//     public function __invoke(Request $request, Client $client)
// {
//     $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers';

//     // Get access token
//     if (session('access_token')) {
//         $access_token = session('access_token');
//     } else {
//         $access_token = app('App\Http\Controllers\AccessTokenController')->__invoke($client)->access_token;
//         session(['access_token' => $access_token]);
//     }

//     // Validate IATA codes
//     $fromIata = $request->input('from_iata');
//     $toIata = $request->input('to_iata');
    
//     if (!$fromIata || !$toIata) {
//         return redirect()->back()->with([
//             'message' => 'Please select valid airports from the suggestions.',
//             'alert_type' => 'error'
//         ])->withInput();
//     }

//     // Build travelers array based on form data
//     $travelers = $this->buildTravelers($request);

//     // Build origin destinations based on trip type
//     $originDestinations = $this->buildOriginDestinations($request);

//     // Build search criteria
//     $searchCriteria = $this->buildSearchCriteria($request);

//     // Construct the API request data
//     $data = [
//         'currencyCode' => 'USD',
//         'originDestinations' => $originDestinations,
//         'travelers' => $travelers,
//         'sources' => ['GDS']
//     ];

//     // Add search criteria if present
//     if (!empty($searchCriteria)) {
//         $data['searchCriteria'] = $searchCriteria;
//     }

//     try {
//         $response = $client->post($url, [
//             'headers' => [
//                 'Accept' => 'application/json',
//                 'Authorization' => 'Bearer ' . $access_token
//             ],
//             'json' => $data
//         ]);

//         $response = $response->getBody();
//         $response = json_decode($response);

//         // Add dictionaries to each flight for easy access in view
//         if (isset($response->data) && isset($response->dictionaries)) {
//             foreach ($response->data as $flight) {
//                 $flight->dictionaries = $response->dictionaries;
//             }
//         }

//         // Store flight data in session
//         session(['flight_data' => json_encode($response)]);
//         session(['search_params' => $request->all()]);
        
//         // Save search to history if user is logged in
//         if (Auth::check()) {
//             $this->saveSearchHistory([
//                 'from_iata' => $fromIata,
//                 'to_iata' => $toIata,
//                 'date' => $request->input('date'),
//                 'return_date' => $request->input('return_date'),
//                 'adults' => (int)$request->input('adults', 1),
//                 'children' => (int)$request->input('children', 0),
//                 'infants' => (int)$request->input('infants', 0),
//                 'travel_class' => $request->input('travel_class', 'ECONOMY'),
//                 'trip_type' => $request->input('trip_type', 'round_trip'),
//             ], Auth::id(), Auth::id());
//         }

//         return view('flights.flight-results', [
//             'flights' => $response->data ?? [],
//             'searchParams' => $request->all(),
//             'dictionaries' => $response->dictionaries ?? null
//         ]);
//     } catch (GuzzleException $exception) {
//         return redirect()->back()->with([
//             'message' => 'Flight search failed. Please try again. Error: ' . $exception->getMessage(),
//             'alert_type' => 'error'
//         ])->withInput();
//     }
// }


public function selectFlight(Request $request)
{
    try {
        $flightData = json_decode($request->input('flight_data'), true);
        
        if (!$flightData) {
            return redirect()->back()->with('error', 'Invalid flight data');
        }
        
        // Store flight data in session
        session(['selected_flight' => $flightData]);
        
        // Allow both guests and logged-in users to proceed to booking page
        // For guests, we'll show a registration modal on the booking page
        
        // Redirect to booking page
        return redirect()->route('booking.show', ['type' => 'flight'])->with([
            'message' => 'Flight selected successfully. Please review your booking details.',
            'alert_type' => 'success'
        ]);
    } catch (\Exception $e) {
        \Log::error('Error selecting flight: ' . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while selecting the flight. Please try again.');
    }
}

    /**
     * Public flight search page for non-logged in users
     */
    public function showPublicSearch()
    {
        return view('flights.search');
    }

    /**
     * Process flight price confirmation
     */
    public function priceItinerary(Request $request)
    {
        $flightData = $request->input('flight');
        
        if (!$flightData) {
            return redirect()->back()->with([
                'message' => 'No flight selected',
                'alert_type' => 'error'
            ]);
        }
        
        $flight = json_decode($flightData);
        $searchParams = session('search_params');
        
        // Store the selected flight in session
        session(['selected_flight' => $flightData]);
        
        // Return the pricing confirmation page
        return view('flights.pricing-confirmation', [
            'flight' => $flight,
            'searchParams' => $searchParams
        ]);
    }

    protected $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // Show search page based on user role
    public function showSearch()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'superadmin':
                return $this->showSuperAdminSearch();
            case 'admin':
                return $this->showAdminSearch();
            default:
                return $this->showUserSearch();
        }
    }

    // SuperAdmin search page with enhanced features
    private function showSuperAdminSearch()
    {
        $users = User::where('id', '!=', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        $recentSearches = SearchHistory::with('user')
                                     ->orderBy('created_at', 'desc')
                                     ->limit(10)
                                     ->get();
        
        $searchStats = [
            'total_searches' => SearchHistory::count(),
            'today_searches' => SearchHistory::whereDate('created_at', today())->count(),
            'users_searched_today' => SearchHistory::whereDate('created_at', today())
                                                 ->distinct('user_id')
                                                 ->count(),
            'popular_routes' => SearchHistory::selectRaw('CONCAT(from_iata, " → ", to_iata) as route, COUNT(*) as count')
                                           ->groupBy('from_iata', 'to_iata')
                                           ->orderBy('count', 'desc')
                                           ->limit(5)
                                           ->get()
        ];

        return view('dashboard.flights.superadmin.search', compact('users', 'recentSearches', 'searchStats'));
    }

    // Admin search page
    private function showAdminSearch()
    {
        $users = User::where('role', 'user')
                    ->orderBy('created_at', 'desc')
                    ->get();
        
        $recentSearches = SearchHistory::with('user')
                                     ->where('searched_by', Auth::id())
                                     ->orWhere('user_id', Auth::id())
                                     ->orderBy('created_at', 'desc')
                                     ->limit(5)
                                     ->get();

        return view('dashboard.flights.admin.search', compact('users', 'recentSearches'));
    }

    // Regular user search page
    private function showUserSearch()
    {
        $recentSearches = SearchHistory::where('user_id', Auth::id())
                                     ->orderBy('created_at', 'desc')
                                     ->limit(5)
                                     ->get();

        return view('dashboard.flights.user.search', compact('recentSearches'));
    }

    // Process flight search
    public function searchFlights(Request $request)
    {
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
            'search_for_user' => 'nullable|exists:users,id', // For admin/superadmin searching on behalf of others
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $searchForUserId = $request->input('search_for_user');
        
        // Check permissions for searching on behalf of others
        if ($searchForUserId && !in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->back()->with('error', 'You do not have permission to search on behalf of other users.');
        }

        // Set the actual user for whom the search is being performed
        $targetUserId = $searchForUserId ?? $user->id;

        try {
            // Get access token
            $accessToken = $this->getAccessToken();
            
            // Prepare search parameters
            $searchParams = $this->prepareSearchParams($request->all());
            
            // Search flights using Amadeus API
            $flights = $this->searchAmadeusFlights($accessToken, $searchParams);
            
            // Save search history
            $this->saveSearchHistory($searchParams, $targetUserId, $user->id);
            
            // Redirect to results page with data
            return $this->redirectToResults($flights, $searchParams, $user->role);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Flight search failed: ' . $e->getMessage());
        }
    }

    // Save search history
    private function saveSearchHistory($searchParams, $targetUserId, $searchedById)
    {
        SearchHistory::create([
            'user_id' => $targetUserId,
            'searched_by' => $searchedById,
            'from_iata' => $searchParams['from_iata'],
            'to_iata' => $searchParams['to_iata'],
            'departure_date' => $searchParams['date'],
            'return_date' => $searchParams['return_date'] ?? null,
            'adults' => $searchParams['adults'],
            'children' => $searchParams['children'] ?? 0,
            'infants' => $searchParams['infants'] ?? 0,
            'travel_class' => $searchParams['travel_class'],
            'trip_type' => $searchParams['trip_type'],
            'search_parameters' => json_encode($searchParams),
        ]);
    }

    // Redirect to appropriate results page based on role
    private function redirectToResults($flights, $searchParams, $userRole)
    {
        session(['flight_search_results' => $flights, 'search_params' => $searchParams]);
        
        switch ($userRole) {
            case 'superadmin':
                return redirect()->route('flights.results.superadmin');
            case 'admin':
                return redirect()->route('flights.results.admin');
            default:
                return redirect()->route('flights.results');
        }
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

    // Show results based on user role
    public function showResults()
    {
        $user = Auth::user();
        $flights = session('flight_search_results');
        $searchParams = session('search_params');
        
        if (!$flights || !$searchParams) {
            return redirect()->route('flights.search')->with('error', 'No search results found. Please search again.');
        }

        switch ($user->role) {
            case 'superadmin':
                return view('dashboard.flight-search.superadmin.flight-results', compact('flights', 'searchParams'));
            case 'admin':
                return view('dashboard.flight-search.admin.flight-results', compact('flights', 'searchParams'));
            case 'user':
                return view('dashboard.flight-search.user.flight-results', compact('flights', 'searchParams'));
            default:
                return view('flights.flight-results', compact('flights', 'searchParams'));
        }
    }
    
    public function showPublicResults()
    {
        $flightDataJson = session('flight_data', null);
        $searchParams = session('search_params', []);

        // Decode the stored JSON response
        $response = $flightDataJson ? json_decode($flightDataJson, true) : null;
        $flights = $response['data'] ?? [];
        $dictionaries = $response['dictionaries'] ?? [];
        $noFlightsFound = empty($flights);

        // If no data at all and no search happened, redirect to search
        if (!$flightDataJson && empty($searchParams)) {
            return redirect()->route('flights.public.search')->with('error', 'No search results found. Please search again.');
        }

        return view('flights.flight-results', [
            'flights' => $flights,
            'searchParams' => $searchParams,
            'dictionaries' => $dictionaries,
            'noFlightsFound' => $noFlightsFound,
        ]);
    }

    // Get users for admin/superadmin dropdown
    public function getUsers(Request $request)
    {
        $user = Auth::user();
        
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = User::query();
        
        if ($user->role === 'admin') {
            $query->where('role', 'user');
        } else {
            $query->where('id', '!=', $user->id);
        }

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->limit(20)->get(['id', 'name', 'email', 'role']);
        
        return response()->json($users);
    }

    // Prepare search parameters for Amadeus API
    private function prepareSearchParams($requestData)
    {
        return [
            'trip_type' => $requestData['trip_type'],
            'from' => $requestData['from'],
            'from_iata' => strtoupper($requestData['from_iata']),
            'to' => $requestData['to'],
            'to_iata' => strtoupper($requestData['to_iata']),
            'date' => $requestData['date'],
            'return_date' => $requestData['return_date'] ?? null,
            'adults' => (int) $requestData['adults'],
            'children' => (int) ($requestData['children'] ?? 0),
            'infants' => (int) ($requestData['infants'] ?? 0),
            'travel_class' => $requestData['travel_class'],
            'non_stop' => isset($requestData['non_stop']) ? true : false,
            'nearby_airports' => isset($requestData['nearby_airports']) ? true : false,
        ];
    }

    // Search flights using Amadeus API
    private function searchAmadeusFlights($accessToken, $searchParams)
    {
        $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers';
        
        $queryParams = [
            'originLocationCode' => $searchParams['from_iata'],
            'destinationLocationCode' => $searchParams['to_iata'],
            'departureDate' => $searchParams['date'],
            'adults' => $searchParams['adults'] ?? 1,
            'children' => $searchParams['children'] ?? 0,
            'infants' => $searchParams['infants'] ?? 0,
            'travelClass' => $searchParams['travel_class'] ?? 'ECONOMY',
            'nonStop' => isset($searchParams['non_stop']) && $searchParams['non_stop'] ? 'true' : 'false',
            'max' => 50,
            'currencyCode' => 'USD'
        ];

        if (!empty($searchParams['return_date']) && $searchParams['trip_type'] === 'round_trip') {
            $queryParams['returnDate'] = $searchParams['return_date'];
        }

        // Remove null values
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        try {
            $client = new Client();
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                ],
                'query' => $queryParams
            ]);

            $data = json_decode($response->getBody(), true);
            
            // Store full response for later use
            session(['amadeus_search_response' => $data]);
            
            return $data['data'] ?? [];
            
        } catch (GuzzleException $e) {
            Log::error('Amadeus flight search failed', [
                'error' => $e->getMessage(),
                'query_params' => $queryParams
            ]);
            throw new \Exception('Failed to search flights: ' . $e->getMessage());
        }
    }

   
   /**
     * Handle the incoming request - Main invoke method
     */
   public function __invoke(Request $request, Client $client)
    {
        $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers';

        // Get access token
        if (session('access_token')) {
            $access_token = session('access_token');
        } else {
            $access_token = app('App\Http\Controllers\AccessTokenController')->__invoke($client)->access_token;
            session(['access_token' => $access_token]);
        }

        // Validate IATA codes
        $fromIata = $request->input('from_iata');
        $toIata = $request->input('to_iata');
        
        if (!$fromIata || !$toIata) {
            return redirect()->back()->with([
                'message' => 'Please select valid airports from the suggestions.',
                'alert_type' => 'error'
            ])->withInput();
        }

        // Build the search request
        $travelers = $this->buildTravelers($request);
        $originDestinations = $this->buildOriginDestinations($request);
        $searchCriteria = $this->buildSearchCriteria($request);

        $data = [
            'currencyCode' => 'USD',
            'originDestinations' => $originDestinations,
            'travelers' => $travelers,
            'sources' => ['GDS']
        ];

        if (!empty($searchCriteria)) {
            $data['searchCriteria'] = $searchCriteria;
        }

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],
                'json' => $data
            ]);

            $response = $response->getBody();
            $response = json_decode($response);

            // Add dictionaries to each flight for easy access in view
            if (isset($response->data) && isset($response->dictionaries)) {
                foreach ($response->data as $flight) {
                    $flight->dictionaries = $response->dictionaries;
                }
            }

            // Store flight data in session
            session(['flight_data' => json_encode($response)]);
            session(['search_params' => $request->all()]);
            
            // AJAX request: return JSON so the frontend can redirect
            if ($request->ajax() || $request->wantsJson()) {
                $count = isset($response->data) ? count($response->data) : 0;
                return response()->json(['redirect' => route('flights.results'), 'count' => $count]);
            }

            // Always redirect to flight-results page
            if (!isset($response->data) || count($response->data) === 0) {
                // No flights found, but still redirect to results page
                return redirect()->route('flights.results')->with([
                    'noFlightsFound' => true,
                    'searchParams' => $request->all()
                ]);
            }

            // Flights found, redirect to results page
            return redirect()->route('flights.results');

        } catch (GuzzleException $exception) {
            \Log::error('Flight search API error: ' . $exception->getMessage());
            
            // Store empty data so results page shows "no flights found"
            session(['flight_data' => json_encode(['data' => []])]);
            session(['search_params' => $request->all()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['redirect' => route('flights.results'), 'count' => 0]);
            }

            return redirect()->route('flights.results');
        }
    }

    /**
     * Build origin destinations based on trip type
     */
    private function buildOriginDestinations($request)
    {
        $tripType = $request->input('trip_type', 'round_trip');
        
        if ($tripType === 'multi_city') {
            $originDestinations = [];
            $froms = $request->input('from_iata', []);
            $tos = $request->input('to_iata', []);
            $dates = $request->input('date', []);
            
            for ($i = 0; $i < count($froms); $i++) {
                if (isset($froms[$i], $tos[$i], $dates[$i])) {
                    $originDestinations[] = [
                        'id' => $i + 1,
                        'originLocationCode' => $froms[$i],
                        'destinationLocationCode' => $tos[$i],
                        'departureDateTimeRange' => [
                            'date' => $dates[$i]
                        ]
                    ];
                }
            }
            
            return $originDestinations;
        }
        
        // Round trip or one way
        $originDestinations = [[
            'id' => 1,
            'originLocationCode' => $request->input('from_iata'),
            'destinationLocationCode' => $request->input('to_iata'),
            'departureDateTimeRange' => [
                'date' => $request->input('date')
            ]
        ]];
        
        // Add return leg for round trip
        if ($tripType === 'round_trip' && $request->input('return_date')) {
            $originDestinations[] = [
                'id' => 2,
                'originLocationCode' => $request->input('to_iata'),
                'destinationLocationCode' => $request->input('from_iata'),
                'departureDateTimeRange' => [
                    'date' => $request->input('return_date')
                ]
            ];
        }
        
        return $originDestinations;
    }

    /**
     * Build travelers array based on form input
     */
    private function buildTravelers(Request $request)
    {
        $travelers = [];
        $travelerId = 1;

        // Add adults
        $adults = (int) ($request->input('adults', 1));
        for ($i = 0; $i < $adults; $i++) {
            $travelers[] = [
                'id' => (string) $travelerId++,
                'travelerType' => 'ADULT'
            ];
        }

        // Add children
        $children = (int) ($request->input('children', 0));
        for ($i = 0; $i < $children; $i++) {
            $travelers[] = [
                'id' => (string) $travelerId++,
                'travelerType' => 'CHILD'
            ];
        }

        // Add infants
        $infants = (int) ($request->input('infants', 0));
        for ($i = 0; $i < $infants; $i++) {
            $travelers[] = [
                'id' => (string) $travelerId++,
                'travelerType' => 'HELD_INFANT'
            ];
        }

        return $travelers;
    }

    /**
     * Build search criteria based on form input
     */
    private function buildSearchCriteria(Request $request)
    {
        $searchCriteria = [
            'maxFlightOffers' => 50 // Default limit
        ];

        $flightFilters = [];

        // Add travel class (cabin restrictions)
        $travelClass = $request->input('travel_class');
        if ($travelClass && $travelClass !== 'ECONOMY') {
            $flightFilters['cabinRestrictions'] = [
                [
                    'cabin' => $travelClass,
                    'coverage' => 'MOST_SEGMENTS',
                    'originDestinationIds' => ['1'] // Apply to first segment
                ]
            ];
        }

        // Add non-stop filter
        if ($request->input('non_stop') === 'true' || $request->has('non_stop')) {
            $flightFilters['connectionRestriction'] = [
                'maxNumberOfConnections' => 0
            ];
        }

        // Add flight filters if any exist
        if (!empty($flightFilters)) {
            $searchCriteria['flightFilters'] = $flightFilters;
        }

        return $searchCriteria;
    }

    /**
     * Calculate flight duration in readable format
     */
    protected function calculateDuration($response)
    {
        if (!isset($response->data[0]->itineraries[0]->duration)) {
            return null;
        }

        $duration = $response->data[0]->itineraries[0]->duration;
        // Convert PT2H30M format to readable duration
        preg_match('/PT(\d+)H(\d+)M/', $duration, $matches);
        if (count($matches) === 3) {
            return "{$matches[1]}h {$matches[2]}m";
        }
        return null;
    }

     /**
     * Get flight schedule by carrier code, flight number and departure date
     */
    public function getFlightSchedule(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'carrierCode' => 'required|string|size:2',
            'flightNumber' => 'required|string|max:4',
            'scheduledDepartureDate' => 'required|date_format:Y-m-d',
            'operationalSuffix' => 'nullable|string|size:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 400);
        }

        $client = new Client();
        $url = 'https://test.api.amadeus.com/v2/schedule/flights';

        // Get access token
        if (session('access_token')) {
            $access_token = session('access_token');
        } else {
            $access_token = app('App\Http\Controllers\AccessTokenController')->__invoke($client)->access_token;
            session(['access_token' => $access_token]);
        }

        // Build query parameters
        $queryParams = [
            'carrierCode' => $request->input('carrierCode'),
            'flightNumber' => $request->input('flightNumber'),
            'scheduledDepartureDate' => $request->input('scheduledDepartureDate')
        ];

        // Add operational suffix if provided
        if ($request->has('operationalSuffix')) {
            $queryParams['operationalSuffix'] = $request->input('operationalSuffix');
        }

        try {
            $response = $client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],
                'query' => $queryParams
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['data']) && !empty($responseData['data'])) {
                $flightSchedule = $responseData['data'][0];
                
                // Process and format the schedule data
                $processedSchedule = $this->processFlightSchedule($flightSchedule);
                
                return response()->json([
                    'success' => true,
                    'data' => $processedSchedule,
                    'meta' => $responseData['meta'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No flight schedule found for the given criteria'
                ], 404);
            }

        } catch (GuzzleException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Flight schedule request failed: ' . $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Search flights by schedule - Enhanced search with schedule data
     */
    public function searchFlightsWithSchedule(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_iata' => 'required|string|size:3',
            'to_iata' => 'required|string|size:3',
            'date' => 'required|date_format:Y-m-d',
            'carrierCode' => 'nullable|string|size:2',
            'flightNumber' => 'nullable|string|max:4',
            'include_schedule' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid search parameters',
                'errors' => $validator->errors()
            ], 400);
        }

        // First, get flight offers
        $flightOffers = $this->getFlightOffers($request);
        
        // If schedule data is requested and we have specific flight criteria
        if ($request->input('include_schedule') && 
            $request->has('carrierCode') && 
            $request->has('flightNumber')) {
            
            // Get schedule data for the specific flight
            $scheduleRequest = new Request([
                'carrierCode' => $request->input('carrierCode'),
                'flightNumber' => $request->input('flightNumber'),
                'scheduledDepartureDate' => $request->input('date')
            ]);
            
            $scheduleResponse = $this->getFlightSchedule($scheduleRequest);
            $scheduleData = json_decode($scheduleResponse->getContent(), true);
            
            if ($scheduleData['success']) {
                // Merge schedule data with flight offers
                $flightOffers['schedule'] = $scheduleData['data'];
            }
        }

        return response()->json($flightOffers);
    }

    /**
     * Get flight offers (existing functionality refactored)
     */
    private function getFlightOffers(Request $request)
    {
        $client = new Client();
        $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers';

        // Get access token
        if (session('access_token')) {
            $access_token = session('access_token');
        } else {
            $access_token = app('App\Http\Controllers\AccessTokenController')->__invoke($client)->access_token;
            session(['access_token' => $access_token]);
        }

        // Build travelers array
        $travelers = $this->buildTravelers($request);

        // Build origin destinations
        $originDestinations = $this->buildOriginDestinations($request);

        // Build search criteria
        $searchCriteria = $this->buildSearchCriteria($request);

        // Construct the API request data
        $data = [
            'currencyCode' => 'USD',
            'originDestinations' => $originDestinations,
            'travelers' => $travelers,
            'sources' => ['GDS']
        ];

        // Add search criteria if present
        if (!empty($searchCriteria)) {
            $data['searchCriteria'] = $searchCriteria;
        }

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $access_token
                ],
                'json' => $data
            ]);

            $responseData = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'data' => $responseData['data'] ?? [],
                'dictionaries' => $responseData['dictionaries'] ?? null,
                'meta' => $responseData['meta'] ?? null
            ];

        } catch (GuzzleException $exception) {
            return [
                'success' => false,
                'message' => 'Flight search failed: ' . $exception->getMessage()
            ];
        }
    }

    /**
     * Process and format flight schedule data
     */
    private function processFlightSchedule($flightSchedule)
    {
        $processed = [
            'type' => $flightSchedule['type'],
            'scheduledDepartureDate' => $flightSchedule['scheduledDepartureDate'],
            'flightDesignator' => [
                'carrierCode' => $flightSchedule['flightDesignator']['carrierCode'],
                'flightNumber' => $flightSchedule['flightDesignator']['flightNumber'],
                'fullFlightNumber' => $flightSchedule['flightDesignator']['carrierCode'] . $flightSchedule['flightDesignator']['flightNumber']
            ],
            'route' => [],
            'segments' => [],
            'legs' => []
        ];

        // Process flight points (route)
        if (isset($flightSchedule['flightPoints'])) {
            foreach ($flightSchedule['flightPoints'] as $point) {
                $routePoint = [
                    'iataCode' => $point['iataCode']
                ];

                if (isset($point['departure'])) {
                    $routePoint['departure'] = [
                        'scheduledTime' => $point['departure']['timings'][0]['value'] ?? null,
                        'localTime' => $this->formatDateTime($point['departure']['timings'][0]['value'] ?? null)
                    ];
                }

                if (isset($point['arrival'])) {
                    $routePoint['arrival'] = [
                        'scheduledTime' => $point['arrival']['timings'][0]['value'] ?? null,
                        'localTime' => $this->formatDateTime($point['arrival']['timings'][0]['value'] ?? null)
                    ];
                }

                $processed['route'][] = $routePoint;
            }
        }

        // Process segments
        if (isset($flightSchedule['segments'])) {
            foreach ($flightSchedule['segments'] as $segment) {
                $processedSegment = [
                    'boardPointIataCode' => $segment['boardPointIataCode'],
                    'offPointIataCode' => $segment['offPointIataCode'],
                    'scheduledSegmentDuration' => $segment['scheduledSegmentDuration'],
                    'durationFormatted' => $this->formatDuration($segment['scheduledSegmentDuration'])
                ];

                if (isset($segment['partnership']['operatingFlight'])) {
                    $processedSegment['operatingFlight'] = [
                        'carrierCode' => $segment['partnership']['operatingFlight']['carrierCode'],
                        'flightNumber' => $segment['partnership']['operatingFlight']['flightNumber'],
                        'fullFlightNumber' => $segment['partnership']['operatingFlight']['carrierCode'] . $segment['partnership']['operatingFlight']['flightNumber']
                    ];
                }

                $processed['segments'][] = $processedSegment;
            }
        }

        // Process legs
        if (isset($flightSchedule['legs'])) {
            foreach ($flightSchedule['legs'] as $leg) {
                $processedLeg = [
                    'boardPointIataCode' => $leg['boardPointIataCode'],
                    'offPointIataCode' => $leg['offPointIataCode'],
                    'scheduledLegDuration' => $leg['scheduledLegDuration'],
                    'durationFormatted' => $this->formatDuration($leg['scheduledLegDuration'])
                ];

                if (isset($leg['aircraftEquipment'])) {
                    $processedLeg['aircraftEquipment'] = [
                        'aircraftType' => $leg['aircraftEquipment']['aircraftType']
                    ];
                }

                $processed['legs'][] = $processedLeg;
            }
        }

        return $processed;
    }

    /**
     * Format ISO 8601 datetime to readable format
     */
    private function formatDateTime($isoDateTime)
    {
        if (!$isoDateTime) {
            return null;
        }

        try {
            $date = new \DateTime($isoDateTime);
            return [
                'date' => $date->format('Y-m-d'),
                'time' => $date->format('H:i'),
                'timezone' => $date->format('P'),
                'formatted' => $date->format('M j, Y H:i')
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Format duration from ISO 8601 format (PT2H35M) to readable format
     */
    private function formatDuration($duration)
    {
        if (!$duration) {
            return null;
        }

        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $duration, $matches);
        
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        
        $formatted = '';
        if ($hours > 0) {
            $formatted .= $hours . 'h ';
        }
        if ($minutes > 0) {
            $formatted .= $minutes . 'm';
        }
        
        return trim($formatted);
    }

    /**
     * Get unique flights by search criteria using schedule API
     */
    public function getUniqueFlights(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'from_iata' => 'required|string|size:3',
            'to_iata' => 'required|string|size:3',
            'date' => 'required|date_format:Y-m-d',
            'carrierCodes' => 'nullable|array',
            'carrierCodes.*' => 'string|size:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 400);
        }

        // First get flight offers to identify available flights
        $flightOffers = $this->getFlightOffers($request);
        
        if (!$flightOffers['success']) {
            return response()->json($flightOffers);
        }

        $uniqueFlights = [];
        $processedFlights = [];

        // Extract unique flight numbers from offers
        foreach ($flightOffers['data'] as $offer) {
            if (isset($offer['itineraries'])) {
                foreach ($offer['itineraries'] as $itinerary) {
                    if (isset($itinerary['segments'])) {
                        foreach ($itinerary['segments'] as $segment) {
                            $carrierCode = $segment['carrierCode'] ?? null;
                            $flightNumber = $segment['number'] ?? null;
                            
                            if ($carrierCode && $flightNumber) {
                                $flightKey = $carrierCode . $flightNumber;
                                
                                if (!isset($processedFlights[$flightKey])) {
                                    $processedFlights[$flightKey] = true;
                                    
                                    // Get schedule for this specific flight
                                    $scheduleRequest = new Request([
                                        'carrierCode' => $carrierCode,
                                        'flightNumber' => $flightNumber,
                                        'scheduledDepartureDate' => $request->input('date')
                                    ]);
                                    
                                    $scheduleResponse = $this->getFlightSchedule($scheduleRequest);
                                    $scheduleData = json_decode($scheduleResponse->getContent(), true);
                                    
                                    if ($scheduleData['success']) {
                                        $uniqueFlights[] = [
                                            'flightKey' => $flightKey,
                                            'carrierCode' => $carrierCode,
                                            'flightNumber' => $flightNumber,
                                            'schedule' => $scheduleData['data'],
                                            'fromOffers' => $segment // Include segment data from offers
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $uniqueFlights,
            'count' => count($uniqueFlights),
            'searchCriteria' => [
                'from' => $request->input('from_iata'),
                'to' => $request->input('to_iata'),
                'date' => $request->input('date')
            ]
        ]);
    }

     /**
     * Get airport direct destinations
     */
    public function getDirectDestinations(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'departureAirportCode' => 'required|string|size:3',
            'max' => 'nullable|integer|min:1|max:100',
            'arrivalCountryCode' => 'nullable|string|size:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 400);
        }

        $url = 'https://test.api.amadeus.com/v1/airport/direct-destinations';
        
        // Get access token
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get access token'
            ], 500);
        }

        // Build query parameters
        $queryParams = [
            'departureAirportCode' => $request->input('departureAirportCode')
        ];

        // Add optional parameters
        if ($request->has('max')) {
            $queryParams['max'] = $request->input('max');
        }

        if ($request->has('arrivalCountryCode')) {
            $queryParams['arrivalCountryCode'] = $request->input('arrivalCountryCode');
        }

        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken
                ],
                'query' => $queryParams
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['data'])) {
                // Process and format the destinations
                $processedDestinations = $this->processDirectDestinations($responseData['data']);
                
                return response()->json([
                    'success' => true,
                    'data' => $processedDestinations,
                    'meta' => $responseData['meta'] ?? null,
                    'searchCriteria' => [
                        'departureAirportCode' => $request->input('departureAirportCode'),
                        'max' => $request->input('max'),
                        'arrivalCountryCode' => $request->input('arrivalCountryCode')
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No direct destinations found'
                ], 404);
            }

        } catch (GuzzleException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Direct destinations request failed: ' . $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Filter flight results to show only direct flights to specific destinations
     */
    public function filterDirectFlights(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'departureAirportCode' => 'required|string|size:3',
            'destinationCodes' => 'nullable|array',
            'destinationCodes.*' => 'string|size:3'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 400);
        }

        // Get flight data from session
        $flightData = session('flight_data');
        if (!$flightData) {
            return response()->json([
                'success' => false,
                'message' => 'No flight data found. Please search for flights first.'
            ], 404);
        }

        $flights = json_decode($flightData, true);
        
        // Get direct destinations for the departure airport
        $directDestinationsRequest = new Request([
            'departureAirportCode' => $request->input('departureAirportCode'),
            'max' => 100
        ]);
        
        $directDestinationsResponse = $this->getDirectDestinations($directDestinationsRequest);
        $directDestinationsData = json_decode($directDestinationsResponse->getContent(), true);
        
        if (!$directDestinationsData['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get direct destinations'
            ], 500);
        }

        // Extract direct destination codes
        $directDestinationCodes = array_map(function($destination) {
            return $destination['iataCode'];
        }, $directDestinationsData['data']);

        // Filter flights to only include direct flights to destinations that have direct routes
        $filteredFlights = [];
        
        if (isset($flights['data'])) {
            foreach ($flights['data'] as $flight) {
                if (isset($flight['itineraries'])) {
                    foreach ($flight['itineraries'] as $itinerary) {
                        if (isset($itinerary['segments']) && count($itinerary['segments']) === 1) {
                            // This is a direct flight
                            $segment = $itinerary['segments'][0];
                            $arrivalCode = $segment['arrival']['iataCode'];
                            
                            // Check if this destination is in our direct destinations list
                            if (in_array($arrivalCode, $directDestinationCodes)) {
                                // If specific destination codes are requested, filter by them
                                if ($request->has('destinationCodes')) {
                                    $requestedDestinations = $request->input('destinationCodes');
                                    if (in_array($arrivalCode, $requestedDestinations)) {
                                        $filteredFlights[] = $flight;
                                    }
                                } else {
                                    $filteredFlights[] = $flight;
                                }
                                break; // Move to next flight
                            }
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $filteredFlights,
            'directDestinations' => $directDestinationsData['data'],
            'filteredCount' => count($filteredFlights),
            'totalCount' => count($flights['data'] ?? [])
        ]);
    }

    /**
     * Get direct destinations with flight availability
     */
    public function getDirectDestinationsWithFlights(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'departureAirportCode' => 'required|string|size:3',
            'departureDate' => 'required|date_format:Y-m-d',
            'max' => 'nullable|integer|min:1|max:50',
            'arrivalCountryCode' => 'nullable|string|size:2'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 400);
        }

        // Get direct destinations
        $directDestinationsRequest = new Request([
            'departureAirportCode' => $request->input('departureAirportCode'),
            'max' => $request->input('max', 20),
            'arrivalCountryCode' => $request->input('arrivalCountryCode')
        ]);

        $directDestinationsResponse = $this->getDirectDestinations($directDestinationsRequest);
        $directDestinationsData = json_decode($directDestinationsResponse->getContent(), true);

        if (!$directDestinationsData['success']) {
            return response()->json($directDestinationsData, 500);
        }

        $destinationsWithFlights = [];

        // For each destination, check if there are available flights
        foreach ($directDestinationsData['data'] as $destination) {
            $flightSearchRequest = new Request([
                'from_iata' => $request->input('departureAirportCode'),
                'to_iata' => $destination['iataCode'],
                'date' => $request->input('departureDate'),
                'trip_type' => 'one_way',
                'adults' => 1
            ]);

            try {
                $flightOffers = $this->getFlightOffers($flightSearchRequest);
                
                $destinationWithFlights = array_merge($destination, [
                    'hasFlights' => $flightOffers['success'] && !empty($flightOffers['data']),
                    'flightCount' => $flightOffers['success'] ? count($flightOffers['data']) : 0,
                    'lowestPrice' => $flightOffers['success'] ? $this->getLowestPrice($flightOffers['data']) : null
                ]);

                $destinationsWithFlights[] = $destinationWithFlights;
            } catch (\Exception $e) {
                // If flight search fails, still include the destination but mark as no flights
                $destinationsWithFlights[] = array_merge($destination, [
                    'hasFlights' => false,
                    'flightCount' => 0,
                    'lowestPrice' => null,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Sort by relevance and flight availability
        usort($destinationsWithFlights, function($a, $b) {
            if ($a['hasFlights'] && !$b['hasFlights']) return -1;
            if (!$a['hasFlights'] && $b['hasFlights']) return 1;
            return ($b['metrics']['relevance'] ?? 0) <=> ($a['metrics']['relevance'] ?? 0);
        });

        return response()->json([
            'success' => true,
            'data' => $destinationsWithFlights,
            'searchCriteria' => [
                'departureAirportCode' => $request->input('departureAirportCode'),
                'departureDate' => $request->input('departureDate'),
                'max' => $request->input('max'),
                'arrivalCountryCode' => $request->input('arrivalCountryCode')
            ],
            'summary' => [
                'totalDestinations' => count($destinationsWithFlights),
                'destinationsWithFlights' => count(array_filter($destinationsWithFlights, function($d) {
                    return $d['hasFlights'];
                }))
            ]
        ]);
    }

    /**
     * Process direct destinations data
     */
    private function processDirectDestinations($data)
    {
        $processed = [];

        foreach ($data as $destination) {
            $processedDestination = [
                'type' => $destination['type'],
                'subtype' => $destination['subtype'],
                'name' => $destination['name'],
                'iataCode' => $destination['iataCode'],
                'geoCode' => $destination['geoCode'] ?? null,
                'address' => [
                    'cityName' => $destination['address']['cityName'] ?? null,
                    'countryName' => $destination['address']['countryName'] ?? null,
                    'stateCode' => $destination['address']['stateCode'] ?? null,
                    'regionCode' => $destination['address']['regionCode'] ?? null
                ],
                'timeZone' => $destination['timeZone'] ?? null,
                'metrics' => [
                    'relevance' => $destination['metrics']['relevance'] ?? 0
                ],
                'displayName' => $this->formatDestinationDisplayName($destination),
                'fullDisplay' => $this->formatDestinationFullDisplay($destination)
            ];

            $processed[] = $processedDestination;
        }

        return $processed;
    }

    /**
     * Format destination display name
     */
    private function formatDestinationDisplayName($destination)
    {
        $iataCode = $destination['iataCode'] ?? '';
        $name = $destination['name'] ?? '';
        $cityName = $destination['address']['cityName'] ?? '';
        
        if ($iataCode && $cityName) {
            return "{$iataCode} - {$cityName}";
        } elseif ($iataCode && $name) {
            return "{$iataCode} - {$name}";
        } elseif ($name) {
            return $name;
        }
        
        return $iataCode ?: 'Unknown';
    }

    /**
     * Format destination full display name
     */
    private function formatDestinationFullDisplay($destination)
    {
        $parts = [];
        
        if (isset($destination['iataCode'])) {
            $parts[] = $destination['iataCode'];
        }
        
        if (isset($destination['address']['cityName'])) {
            $parts[] = $destination['address']['cityName'];
        }
        
        if (isset($destination['address']['countryName'])) {
            $parts[] = $destination['address']['countryName'];
        }
        
        return implode(', ', $parts);
    }
     /**
     * Get lowest price from flight offers
     */
    private function getLowestPrice($flights)
    {
        if (empty($flights)) {
            return null;
        }

        $lowestPrice = null;
        $currency = null;

        foreach ($flights as $flight) {
            if (isset($flight['price']['total'])) {
                $price = (float) $flight['price']['total'];
                $currency = $flight['price']['currency'] ?? 'USD';
                
                if ($lowestPrice === null || $price < $lowestPrice) {
                    $lowestPrice = $price;
                }
            }
        }

        return $lowestPrice ? [
            'amount' => $lowestPrice,
            'currency' => $currency,
            'formatted' => $currency . ' ' . number_format($lowestPrice, 2)
        ] : null;
    }

    public function superadminSearch()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('flights.search_for_others')) {
            abort(403, 'Unauthorized access');
        }

        // Get all users for superadmin to search for
        $users = User::where('role', '!=', 'superadmin')->get();
        
        // Get search statistics
        $searchStats = [
            'total_searches' => \DB::table('flight_searches')->count(),
            'today_searches' => \DB::table('flight_searches')->whereDate('created_at', today())->count(),
            'users_searched_today' => \DB::table('flight_searches')
                ->whereDate('created_at', today())
                ->distinct('user_id')
                ->count('user_id'),
            'api_calls_today' => \DB::table('api_logs')
                ->whereDate('created_at', today())
                ->where('service', 'amadeus')
                ->count(),
            'popular_routes' => \DB::table('flight_searches')
                ->select(\DB::raw('CONCAT(from_iata, " → ", to_iata) as route'), \DB::raw('COUNT(*) as count'))
                ->groupBy('from_iata', 'to_iata')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get()
        ];

        // Get recent searches for dashboard
        $recentSearches = \DB::table('flight_searches')
            ->join('users', 'flight_searches.user_id', '=', 'users.id')
            ->leftJoin('users as searched_by_user', 'flight_searches.searched_by', '=', 'searched_by_user.id')
            ->select('flight_searches.*', 'users.name as user_name', 'searched_by_user.name as searched_by_name')
            ->orderBy('flight_searches.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.flights.superadmin.search', compact('users', 'searchStats', 'recentSearches'));
    }

    // Process SuperAdmin Flight Search
    public function processSuperadminSearch(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('flights.search_for_others')) {
            abort(403, 'Unauthorized access');
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
            'search_for_user' => 'nullable|exists:users,id',
            'search_type' => 'required|in:personal,assisted,corporate,emergency',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $searchForUserId = $request->input('search_for_user') ?? $user->id;
        $searchType = $request->input('search_type', 'personal');

        try {
            // Get Amadeus access token
            $accessToken = $this->getAccessToken();
            
            // Prepare search parameters
            $searchParams = $this->prepareSearchParams($request->all());
            
            // Search flights using Amadeus API
            $flights = $this->searchAmadeusFlights($accessToken, $searchParams);
            
            // Log the search
            $this->logFlightSearch($searchParams, $searchForUserId, $user->id, $searchType);
            
            // Store results in session
            session([
                'flight_search_results' => $flights,
                'search_params' => $searchParams,
                'search_metadata' => [
                    'searched_for_user' => $searchForUserId,
                    'searched_by' => $user->id,
                    'search_type' => $searchType,
                    'timestamp' => now()
                ]
            ]);
            
            return redirect()->route('flights.results.superadmin');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Flight search failed: ' . $e->getMessage());
        }
    }

    // Show SuperAdmin Results
    public function showSuperadminResults()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('flights.search_for_others')) {
            abort(403, 'Unauthorized access');
        }

        $flights = session('flight_search_results');
        $searchParams = session('search_params');
        $metadata = session('search_metadata');
        
        if (!$flights || !$searchParams) {
            return redirect()->route('flights.search.superadmin')->with('error', 'No search results found. Please search again.');
        }

        // Get user being searched for
        $searchedUser = null;
        if ($metadata['searched_for_user'] !== $user->id) {
            $searchedUser = User::find($metadata['searched_for_user']);
        }

        return view('dashboard.flights.superadmin.flight-results', compact('flights', 'searchParams', 'metadata', 'searchedUser'));
    }

    // Log flight search activity
    private function logFlightSearch($searchParams, $userId, $searchedBy, $searchType)
    {
        \DB::table('flight_searches')->insert([
            'user_id' => $userId,
            'searched_by' => $searchedBy,
            'from_iata' => $searchParams['from_iata'],
            'to_iata' => $searchParams['to_iata'],
            'departure_date' => $searchParams['date'],
            'return_date' => $searchParams['return_date'],
            'adults' => $searchParams['adults'],
            'children' => $searchParams['children'] ?? 0,
            'infants' => $searchParams['infants'] ?? 0,
            'travel_class' => $searchParams['travel_class'],
            'trip_type' => $searchParams['trip_type'],
            'search_type' => $searchType,
            'search_parameters' => json_encode($searchParams),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }



}
