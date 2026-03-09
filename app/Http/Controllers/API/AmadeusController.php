<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AmadeusController extends Controller
{
    protected $apiKey;
    protected $apiSecret;
    protected $accessToken;
    protected $baseUrl;
    protected $tokenUrl;

     // Comprehensive East African destinations
    protected $eastAfricanDestinations = [
        // Kenya
        'NBO' => ['name' => 'Nairobi', 'country' => 'Kenya', 'popular' => true],
        'MBA' => ['name' => 'Mombasa', 'country' => 'Kenya', 'popular' => true],
        'KIS' => ['name' => 'Kisumu', 'country' => 'Kenya', 'popular' => false],
        'LOK' => ['name' => 'Lodwar', 'country' => 'Kenya', 'popular' => false],
        'UAS' => ['name' => 'Samburu', 'country' => 'Kenya', 'popular' => false],
        
        // Tanzania
        'DAR' => ['name' => 'Dar es Salaam', 'country' => 'Tanzania', 'popular' => true],
        'ZNZ' => ['name' => 'Zanzibar', 'country' => 'Tanzania', 'popular' => true],
        'JRO' => ['name' => 'Kilimanjaro', 'country' => 'Tanzania', 'popular' => true],
        'MWZ' => ['name' => 'Mwanza', 'country' => 'Tanzania', 'popular' => false],
        'TBO' => ['name' => 'Tabora', 'country' => 'Tanzania', 'popular' => false],
        'ARK' => ['name' => 'Arusha', 'country' => 'Tanzania', 'popular' => true],
        
        // Uganda
        'EBB' => ['name' => 'Entebbe', 'country' => 'Uganda', 'popular' => true],
        'KLA' => ['name' => 'Kampala', 'country' => 'Uganda', 'popular' => true],
        'ULU' => ['name' => 'Luwero', 'country' => 'Uganda', 'popular' => false],
        'PAF' => ['name' => 'Pakuba', 'country' => 'Uganda', 'popular' => false],
        
        // Rwanda
        'KGL' => ['name' => 'Kigali', 'country' => 'Rwanda', 'popular' => true],
        'KME' => ['name' => 'Kamembe', 'country' => 'Rwanda', 'popular' => false],
        
        // Ethiopia
        'ADD' => ['name' => 'Addis Ababa', 'country' => 'Ethiopia', 'popular' => true],
        'BJR' => ['name' => 'Bahar Dar', 'country' => 'Ethiopia', 'popular' => false],
        'LLI' => ['name' => 'Lalibela', 'country' => 'Ethiopia', 'popular' => true],
        'AWA' => ['name' => 'Awassa', 'country' => 'Ethiopia', 'popular' => false],
        'GON' => ['name' => 'Gondar', 'country' => 'Ethiopia', 'popular' => false],
        
        // Burundi
        'BJM' => ['name' => 'Bujumbura', 'country' => 'Burundi', 'popular' => false],
        
        // South Sudan
        'JUB' => ['name' => 'Juba', 'country' => 'South Sudan', 'popular' => false],
        
        // Djibouti
        'JIB' => ['name' => 'Djibouti', 'country' => 'Djibouti', 'popular' => false],
        
        // Somalia
        'MGQ' => ['name' => 'Mogadishu', 'country' => 'Somalia', 'popular' => false],
        
        // Eritrea
        'ASM' => ['name' => 'Asmara', 'country' => 'Eritrea', 'popular' => false],
    ];


    

    public function __construct()
    {
        // Get API credentials from environment variables
        $this->apiKey = env('AMADEUS_API_KEY', '');
        $this->apiSecret = env('AMADEUS_API_SECRET', '');
        $this->baseUrl = env('AMADEUS_BASE_URL', 'https://test.api.amadeus.com');
        $this->tokenUrl = env('AMADEUS_TOKEN_URL', 'https://test.api.amadeus.com/v1/security/oauth2/token');
        
        // Get access token
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Get Amadeus OAuth2 access token
     */
    protected function getAccessToken()
    {
        // Check if we have a cached token
        if (Cache::has('amadeus_access_token')) {
            return Cache::get('amadeus_access_token');
        }

        try {
            // Request a new access token
            $response = Http::asForm()->post($this->tokenUrl, [
                'grant_type' => 'client_credentials',
                'client_id' => $this->apiKey,
                'client_secret' => $this->apiSecret
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['access_token'])) {
                $expiresIn = $data['expires_in'] ?? 1800;
                
                // Cache the token (subtract 60 seconds to be safe)
                Cache::put('amadeus_access_token', $data['access_token'], $expiresIn - 60);
                
                return $data['access_token'];
            } else {
                Log::error('Failed to obtain Amadeus access token', [
                    'response' => $data,
                    'status' => $response->status()
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('Exception when obtaining Amadeus access token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Helper method to make authenticated API calls to Amadeus
     */
    protected function makeApiCall($endpoint, $params = [])
    {
        if (!$this->accessToken) {
            Log::error('No access token available for Amadeus API call');
            return [
                'error' => 'Could not authenticate with Amadeus API',
                'message' => 'Authentication failed'
            ];
        }

        try {
            Log::info('Making Amadeus API call', [
                'endpoint' => $endpoint,
                'params' => $params,
                'base_url' => $this->baseUrl
            ]);

            $response = Http::withToken($this->accessToken)
                           ->timeout(30)
                           ->get($this->baseUrl . $endpoint, $params);

            Log::info('Amadeus API response', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'has_data' => isset($response->json()['data']),
                'data_count' => isset($response->json()['data']) ? count($response->json()['data']) : 0
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                // Check if token expired (401) and retry once
                if ($response->status() === 401) {
                    Log::info('Access token expired, refreshing...');
                    Cache::forget('amadeus_access_token');
                    $this->accessToken = $this->getAccessToken();
                    
                    if ($this->accessToken) {
                        $retryResponse = Http::withToken($this->accessToken)
                                           ->timeout(30)
                                           ->get($this->baseUrl . $endpoint, $params);
                        
                        if ($retryResponse->successful()) {
                            Log::info('Retry successful after token refresh');
                            return $retryResponse->json();
                        }
                    }
                }
                
                Log::error('Amadeus API error', [
                    'endpoint' => $endpoint,
                    'params' => $params,
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                
                return [
                    'error' => 'API error: ' . $response->status(),
                    'details' => $response->json(),
                    'message' => 'Failed to fetch data from Amadeus API'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception in Amadeus API call: ' . $e->getMessage(), [
                'endpoint' => $endpoint,
                'params' => $params,
                'exception' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => 'API error: ' . $e->getMessage(),
                'message' => 'Network or server error occurred'
            ];
        }
    }

   /**
     * Search for flight offers with comprehensive East African routes
     */
    public function searchFlightOffers(Request $request)
    {
        $popularDestinations = collect($this->eastAfricanDestinations)
            ->where('popular', true)
            ->keys()
            ->toArray();

        $originLocationCode = $request->input('originLocationCode', 'NBO');
        $destinationLocationCode = $request->input('destinationLocationCode', 
            $popularDestinations[array_rand($popularDestinations)]
        );
        $departureDate = $request->input('departureDate', date('Y-m-d', strtotime('+1 week')));
        $adults = $request->input('adults', 1);
        $max = $request->input('max', 15);

        $params = [
            'originLocationCode' => $originLocationCode,
            'destinationLocationCode' => $destinationLocationCode,
            'departureDate' => $departureDate,
            'adults' => $adults,
            'max' => $max,
            'currencyCode' => 'USD'
        ];

        if ($request->has('returnDate')) {
            $params['returnDate'] = $request->input('returnDate');
        }

        if ($request->has('travelClass')) {
            $params['travelClass'] = $request->input('travelClass');
        }

        $cacheKey = 'flight_offers_' . md5(json_encode($params));
        
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $result = $this->makeApiCall('/v2/shopping/flight-offers', $params);

        if (!isset($result['data']) || empty($result['data']) || isset($result['error'])) {
            Log::info('Using comprehensive fallback flight data');
            $fallbackData = $this->getComprehensiveFlightData($originLocationCode, $destinationLocationCode, $departureDate);
            Cache::put($cacheKey, $fallbackData, 600);
            return response()->json($fallbackData);
        }

        Cache::put($cacheKey, $result, 1800);
        return response()->json($result);
    }

    /**
     * Search for hotel offers across East African cities
     */
    public function searchHotelOffers(Request $request)
    {
        $popularCities = collect($this->eastAfricanDestinations)
            ->where('popular', true)
            ->keys()
            ->toArray();

        $cityCode = $request->input('cityCode', $popularCities[array_rand($popularCities)]);
        $checkInDate = $request->input('checkInDate', date('Y-m-d', strtotime('+1 week')));
        $checkOutDate = $request->input('checkOutDate', date('Y-m-d', strtotime('+1 week +2 days')));
        $adults = $request->input('adults', 1);
        $roomQuantity = $request->input('roomQuantity', 1);

        $params = [
            'cityCode' => $cityCode,
            'checkInDate' => $checkInDate,
            'checkOutDate' => $checkOutDate,
            'adults' => $adults,
            'roomQuantity' => $roomQuantity,
            'currency' => 'USD'
        ];

        $cacheKey = 'hotel_offers_' . md5(json_encode($params));
        
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $result = $this->makeApiCall('/v2/shopping/hotel-offers', $params);

        if (!isset($result['data']) || empty($result['data']) || isset($result['error'])) {
            Log::info('Using comprehensive fallback hotel data');
            $fallbackData = $this->getComprehensiveHotelData($cityCode, $checkInDate, $checkOutDate);
            Cache::put($cacheKey, $fallbackData, 600);
            return response()->json($fallbackData);
        }

        Cache::put($cacheKey, $result, 7200);
        return response()->json($result);
    }

    /**
     * Search for activities and tours across East Africa
     */
    public function searchActivities(Request $request)
    {
        $eastAfricanLocations = [
            ['lat' => -1.292066, 'lng' => 36.821946, 'city' => 'Nairobi', 'country' => 'Kenya'],
            ['lat' => -4.043477, 'lng' => 39.668206, 'city' => 'Mombasa', 'country' => 'Kenya'],
            ['lat' => -6.165917, 'lng' => 39.202641, 'city' => 'Zanzibar', 'country' => 'Tanzania'],
            ['lat' => -6.792354, 'lng' => 39.208328, 'city' => 'Dar es Salaam', 'country' => 'Tanzania'],
            ['lat' => -3.386925, 'lng' => 37.101601, 'city' => 'Arusha', 'country' => 'Tanzania'],
            ['lat' => 0.042068, 'lng' => 32.444130, 'city' => 'Entebbe', 'country' => 'Uganda'],
            ['lat' => 0.347596, 'lng' => 32.582520, 'city' => 'Kampala', 'country' => 'Uganda'],
            ['lat' => -1.944386, 'lng' => 30.061890, 'city' => 'Kigali', 'country' => 'Rwanda'],
            ['lat' => 9.024325, 'lng' => 38.746799, 'city' => 'Addis Ababa', 'country' => 'Ethiopia'],
            ['lat' => 11.600000, 'lng' => 37.383333, 'city' => 'Lalibela', 'country' => 'Ethiopia'],
            ['lat' => -3.395190, 'lng' => 29.918886, 'city' => 'Bujumbura', 'country' => 'Burundi'],
            ['lat' => 4.859363, 'lng' => 31.571251, 'city' => 'Juba', 'country' => 'South Sudan'],
        ];

        $latitude = $request->input('latitude', -1.292066);
        $longitude = $request->input('longitude', 36.821946);
        $radius = $request->input('radius', 100);

        $params = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius
        ];

        $cacheKey = 'activities_' . md5(json_encode($params));
        
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $result = $this->makeApiCall('/v1/shopping/activities', $params);

        if (!isset($result['data']) || empty($result['data']) || isset($result['error'])) {
            Log::info('Using comprehensive fallback activities data');
            $fallbackData = $this->getComprehensiveActivitiesData();
            Cache::put($cacheKey, $fallbackData, 600);
            return response()->json($fallbackData);
        }

        Cache::put($cacheKey, $result, 14400);
        return response()->json($result);
    }

    /**
     * Get comprehensive fallback flight data for East African routes
     */
    private function getComprehensiveFlightData($origin, $destination, $departureDate)
    {
        $routes = [
            // Kenya routes
            ['from' => 'NBO', 'to' => 'MBA', 'price' => [120, 180], 'duration' => 'PT1H15M', 'airline' => 'KQ'],
            ['from' => 'NBO', 'to' => 'KIS', 'price' => [80, 120], 'duration' => 'PT1H', 'airline' => 'KQ'],
            
            // Tanzania routes
            ['from' => 'NBO', 'to' => 'DAR', 'price' => [180, 280], 'duration' => 'PT1H30M', 'airline' => 'KQ'],
            ['from' => 'NBO', 'to' => 'ZNZ', 'price' => [200, 320], 'duration' => 'PT1H45M', 'airline' => 'KQ'],
            ['from' => 'NBO', 'to' => 'JRO', 'price' => [150, 250], 'duration' => 'PT1H20M', 'airline' => 'KQ'],
            ['from' => 'DAR', 'to' => 'ZNZ', 'price' => [60, 100], 'duration' => 'PT25M', 'airline' => 'PW'],
            ['from' => 'DAR', 'to' => 'MBA', 'price' => [140, 200], 'duration' => 'PT1H10M', 'airline' => 'PW'],
            ['from' => 'JRO', 'to' => 'ZNZ', 'price' => [80, 140], 'duration' => 'PT45M', 'airline' => 'PW'],
            
            // Uganda routes
            ['from' => 'NBO', 'to' => 'EBB', 'price' => [180, 280], 'duration' => 'PT1H25M', 'airline' => 'KQ'],
            ['from' => 'DAR', 'to' => 'EBB', 'price' => [220, 320], 'duration' => 'PT1H40M', 'airline' => 'PW'],
            ['from' => 'EBB', 'to' => 'JRO', 'price' => [160, 240], 'duration' => 'PT1H15M', 'airline' => 'UG'],
            
            // Rwanda routes
            ['from' => 'NBO', 'to' => 'KGL', 'price' => [200, 300], 'duration' => 'PT1H30M', 'airline' => 'KQ'],
            ['from' => 'EBB', 'to' => 'KGL', 'price' => [120, 180], 'duration' => 'PT1H', 'airline' => 'RW'],
            ['from' => 'DAR', 'to' => 'KGL', 'price' => [250, 350], 'duration' => 'PT1H50M', 'airline' => 'RW'],
            
            // Ethiopia routes
            ['from' => 'NBO', 'to' => 'ADD', 'price' => [220, 350], 'duration' => 'PT2H', 'airline' => 'ET'],
            ['from' => 'DAR', 'to' => 'ADD', 'price' => [280, 420], 'duration' => 'PT2H30M', 'airline' => 'ET'],
            ['from' => 'EBB', 'to' => 'ADD', 'price' => [200, 320], 'duration' => 'PT1H45M', 'airline' => 'ET'],
            ['from' => 'KGL', 'to' => 'ADD', 'price' => [180, 280], 'duration' => 'PT1H30M', 'airline' => 'ET'],
            ['from' => 'ADD', 'to' => 'LLI', 'price' => [80, 120], 'duration' => 'PT1H15M', 'airline' => 'ET'],
            
            // Cross-border routes
            ['from' => 'MBA', 'to' => 'ZNZ', 'price' => [100, 160], 'duration' => 'PT1H', 'airline' => 'PW'],
            ['from' => 'KGL', 'to' => 'BJM', 'price' => [60, 100], 'duration' => 'PT30M', 'airline' => 'RW'],
            ['from' => 'ADD', 'to' => 'JUB', 'price' => [150, 250], 'duration' => 'PT1H20M', 'airline' => 'ET'],
        ];

        $airlines = [
            'KQ' => 'Kenya Airways',
            'PW' => 'Precision Air',
            'ET' => 'Ethiopian Airlines',
            'RW' => 'RwandAir',
            'UG' => 'Uganda Airlines',
            'FD' => 'Fastjet',
            'JM' => 'Jambo Jet'
        ];

        $flights = [];
        
        // Generate flights for the requested route
        $matchingRoutes = collect($routes)->filter(function($route) use ($origin, $destination) {
            return ($route['from'] === $origin && $route['to'] === $destination) ||
                   ($route['from'] === $destination && $route['to'] === $origin);
        });

        if ($matchingRoutes->isEmpty()) {
            // If no direct route, pick random routes
            $matchingRoutes = collect($routes)->random(min(5, count($routes)));
        }

        foreach ($matchingRoutes as $route) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                $basePrice = rand($route['price'][0], $route['price'][1]);
                $flightNumber = $route['airline'] . rand(100, 999);
                
                $departureTime = rand(6, 22) . ':' . (rand(0, 1) ? '00' : '30');
                $arrivalTime = $this->calculateArrivalTime($departureTime, $route['duration']);
                
                $flights[] = [
                    'type' => 'flight-offer',
                    'id' => 'east-africa-flight-' . ($i + 1) . '-' . $route['from'] . '-' . $route['to'],
                    'source' => 'GDS',
                    'instantTicketingRequired' => false,
                    'nonHomogeneous' => false,
                    'oneWay' => !$request->has('returnDate'),
                    'lastTicketingDate' => date('Y-m-d', strtotime('+3 days')),
                    'numberOfBookableSeats' => rand(1, 9),
                    'itineraries' => [[
                        'duration' => $route['duration'],
                        'segments' => [[
                            'departure' => [
                                'iataCode' => $route['from'],
                                'terminal' => rand(1, 3),
                                'at' => $departureDate . 'T' . $departureTime . ':00'
                            ],
                            'arrival' => [
                                'iataCode' => $route['to'],
                                'terminal' => rand(1, 3),
                                'at' => $departureDate . 'T' . $arrivalTime . ':00'
                            ],
                            'carrierCode' => $route['airline'],
                            'number' => $flightNumber,
                            'aircraft' => [
                                'code' => ['320', '737', '788', 'DH8'][array_rand(['320', '737', '788', 'DH8'])]
                            ],
                            'operating' => [
                                'carrierCode' => $route['airline']
                            ],
                            'duration' => $route['duration'],
                            'id' => ($i + 1),
                            'numberOfStops' => 0,
                            'blacklistedInEU' => false
                        ]]
                    ]],
                    'price' => [
                        'currency' => 'USD',
                        'total' => number_format($basePrice + rand(10, 50), 2),
                        'base' => number_format($basePrice, 2),
                        'fees' => [[
                            'amount' => number_format(rand(15, 40), 2),
                            'type' => 'SUPPLIER'
                        ]],
                        'grandTotal' => number_format($basePrice + rand(15, 60), 2)
                    ],
                    'pricingOptions' => [
                        'fareType' => ['PUBLISHED'],
                        'includedCheckedBagsOnly' => true
                    ],
                    'validatingAirlineCodes' => [$route['airline']],
                    'travelerPricings' => [[
                        'travelerId' => '1',
                        'fareOption' => 'STANDARD',
                        'travelerType' => 'ADULT',
                        'price' => [
                            'currency' => 'USD',
                            'total' => number_format($basePrice + rand(10, 50), 2),
                            'base' => number_format($basePrice, 2)
                        ],
                        'fareDetailsBySegment' => [[
                            'segmentId' => '1',
                            'cabin' => 'ECONOMY',
                            'fareBasis' => 'Y1FLEX',
                            'brandedFare' => 'STANDARD',
                            'class' => 'Y',
                            'includedCheckedBags' => [
                                'weight' => 23,
                                'weightUnit' => 'KG'
                            ]
                        ]]
                    ]]
                ];
            }
        }

        return [
            'meta' => [
                'count' => count($flights),
                'links' => [
                    'self' => 'https://test.api.amadeus.com/v2/shopping/flight-offers'
                ]
            ],
            'data' => $flights,
            'dictionaries' => [
                'locations' => $this->getLocationDictionary(),
                'aircraft' => [
                    '320' => 'AIRBUS A320',
                    '737' => 'BOEING 737',
                    '788' => 'BOEING 787-8',
                    'DH8' => 'DE HAVILLAND CANADA DHC-8'
                ],
                'currencies' => [
                    'USD' => 'US DOLLAR'
                ],
                'carriers' => $airlines
            ],
            'fallback' => true,
            'message' => 'Comprehensive East African flight data - API temporarily unavailable'
        ];
    }

    /**
     * Get comprehensive fallback hotel data
     */
    private function getComprehensiveHotelData($cityCode, $checkInDate, $checkOutDate)
    {
        $hotelsByCity = [
            'NBO' => [
                ['name' => 'Nairobi Serena Hotel', 'rating' => 5, 'price' => [180, 250]],
                ['name' => 'Safari Park Hotel', 'rating' => 4, 'price' => [120, 180]],
                ['name' => 'Hilton Nairobi', 'rating' => 5, 'price' => [200, 280]],
                ['name' => 'Villa Rosa Kempinski', 'rating' => 5, 'price' => [220, 300]],
                ['name' => 'Sankara Nairobi', 'rating' => 5, 'price' => [190, 270]],
                ['name' => 'Four Points by Sheraton', 'rating' => 4, 'price' => [150, 200]],
            ],
            'MBA' => [
                ['name' => 'Serena Beach Resort', 'rating' => 5, 'price' => [200, 300]],
                ['name' => 'Voyager Beach Resort', 'rating' => 4, 'price' => [120, 180]],
                ['name' => 'Baobab Beach Resort', 'rating' => 4, 'price' => [150, 220]],
                ['name' => 'Sarova Whitesands', 'rating' => 4, 'price' => [130, 190]],
            ],
            'ZNZ' => [
                ['name' => 'Park Hyatt Zanzibar', 'rating' => 5, 'price' => [400, 600]],
                ['name' => 'Four Seasons Resort Zanzibar', 'rating' => 5, 'price' => [500, 800]],
                ['name' => 'Zanzibar Serena Hotel', 'rating' => 4, 'price' => [180, 280]],
                ['name' => 'Spice Island Hotel', 'rating' => 4, 'price' => [120, 200]],
                ['name' => 'DoubleTree by Hilton Resort', 'rating' => 4, 'price' => [200, 320]],
            ],
            'DAR' => [
                ['name' => 'Hyatt Regency Dar es Salaam', 'rating' => 5, 'price' => [180, 250]],
                ['name' => 'Four Points by Sheraton', 'rating' => 4, 'price' => [120, 180]],
                ['name' => 'Sea Cliff Hotel', 'rating' => 4, 'price' => [100, 150]],
                ['name' => 'Golden Tulip Hotel', 'rating' => 4, 'price' => [110, 160]],
            ],
            'EBB' => [
                ['name' => 'Kampala Serena Hotel', 'rating' => 5, 'price' => [150, 220]],
                ['name' => 'Sheraton Kampala Hotel', 'rating' => 5, 'price' => [160, 240]],
                ['name' => 'Protea Hotel by Marriott', 'rating' => 4, 'price' => [100, 150]],
                ['name' => 'Lake Victoria Serena Golf Resort', 'rating' => 4, 'price' => [120, 180]],
            ],
            'KGL' => [
                ['name' => 'Kigali Marriott Hotel', 'rating' => 5, 'price' => [200, 280]],
                ['name' => 'Radisson Blu Hotel & Convention Centre', 'rating' => 4, 'price' => [150, 220]],
                ['name' => 'The Retreat by Heaven', 'rating' => 4, 'price' => [120, 180]],
                ['name' => 'Ubumwe Grande Hotel', 'rating' => 4, 'price' => [100, 150]],
            ],
            'ADD' => [
                ['name' => 'Sheraton Addis', 'rating' => 5, 'price' => [180, 250]],
                ['name' => 'Radisson Blu Hotel', 'rating' => 4, 'price' => [120, 180]],
                ['name' => 'Hilton Addis Ababa', 'rating' => 5, 'price' => [200, 280]],
                ['name' => 'Ethiopian Skylight Hotel', 'rating' => 4, 'price' => [100, 150]],
            ],
        ];

        $cityName = $this->eastAfricanDestinations[$cityCode]['name'] ?? 'Unknown City';
        $countryName = $this->eastAfricanDestinations[$cityCode]['country'] ?? 'East Africa';
        $cityHotels = $hotelsByCity[$cityCode] ?? $hotelsByCity['NBO'];

        $hotels = [];
        
        foreach ($cityHotels as $index => $hotelData) {
            $basePrice = rand($hotelData['price'][0], $hotelData['price'][1]);
            
            $hotels[] = [
                'type' => 'hotel-offers',
                'hotel' => [
                    'type' => 'hotel',
                    'hotelId' => 'east-africa-hotel-' . ($index + 1) . '-' . $cityCode,
                    'chainCode' => 'XX',
                    'dupeId' => rand(100000, 999999),
                    'name' => $hotelData['name'],
                    'rating' => $hotelData['rating'],
                    'cityCode' => $cityCode,
                    'latitude' => -1.292 + (rand(-200, 200) / 1000),
                    'longitude' => 36.821 + (rand(-200, 200) / 1000),
                    'hotelDistance' => [
                        'distance' => rand(1, 15),
                        'distanceUnit' => 'KM'
                    ],
                    'address' => [
                        'lines' => [rand(1, 999) . ' ' . $cityName . ' Street'],
                        'postalCode' => rand(10000, 99999),
                        'cityName' => $cityName,
                        'countryCode' => substr($countryName, 0, 2)
                    ],
                    'contact' => [
                        'phone' => '+254-' . rand(100000000, 999999999),
                        'email' => 'info@' . strtolower(str_replace(' ', '', $hotelData['name'])) . '.com'
                    ],
                    'amenities' => [
                        ['description' => 'Free WiFi', 'chargeable' => false],
                        ['description' => 'Swimming Pool', 'chargeable' => false],
                        ['description' => 'Fitness Center', 'chargeable' => false],
                        ['description' => 'Restaurant', 'chargeable' => false],
                        ['description' => 'Spa Services', 'chargeable' => true],
                        ['description' => 'Airport Shuttle', 'chargeable' => false]
                    ]
                ],
                'offers' => [[
                    'id' => 'offer-' . ($index + 1),
                    'checkInDate' => $checkInDate,
                    'checkOutDate' => $checkOutDate,
                    'rateCode' => 'RAC',
                    'rateFamilyEstimated' => [
                        'code' => 'SRS',
                        'type' => 'C'
                    ],
                    'room' => [
                        'type' => 'A1K',
                        'typeEstimated' => [
                            'category' => 'SUPERIOR_ROOM',
                            'beds' => rand(1, 2),
                            'bedType' => rand(0, 1) ? 'KING' : 'DOUBLE'
                        ],
                        'description' => [
                            'text' => 'Comfortable room with modern amenities and city/garden view',
                            'lang' => 'EN'
                        ]
                    ],
                    'guests' => [
                        'adults' => 1
                    ],
                    'price' => [
                        'currency' => 'USD',
                        'base' => number_format($basePrice, 2),
                        'total' => number_format($basePrice + rand(20, 50), 2),
                        'taxes' => [[
                            'amount' => number_format(rand(10, 30), 2),
                            'currency' => 'USD',
                            'code' => 'TOTAL_TAX',
                            'percentage' => number_format(rand(10, 18), 2),
                            'included' => true,
                            'description' => 'TAX_AND_SERVICE_FEE',
                            'pricingFrequency' => 'PER_STAY',
                            'pricingMode' => 'PER_PRODUCT'
                        ]],
                        'markups' => []
                    ],
                    'policies' => [
                        'paymentType' => 'GUARANTEE',
                        'cancellation' => [
                            'type' => 'FULL_STAY'
                        ]
                    ],
                    'self' => 'https://test.api.amadeus.com/v2/shopping/hotel-offers/offer-' . ($index + 1)
                ]]
            ];
        }

        return [
            'data' => $hotels,
            'fallback' => true,
            'message' => 'Comprehensive East African hotel data - API temporarily unavailable'
        ];
    }

    /**
     * Get comprehensive fallback activities data
     */
    private function getComprehensiveActivitiesData()
    {
        $activitiesByCity = [
            'Nairobi' => [
                ['name' => 'Nairobi National Park Safari', 'type' => 'Wildlife Safari', 'price' => [80, 120], 'duration' => 'PT4H'],
                ['name' => 'Giraffe Centre Experience', 'type' => 'Wildlife Experience', 'price' => [25, 40], 'duration' => 'PT2H'],
                ['name' => 'David Sheldrick Elephant Orphanage', 'type' => 'Wildlife Experience', 'price' => [15, 25], 'duration' => 'PT1H30M'],
                ['name' => 'Nairobi City Walking Tour', 'type' => 'Cultural Tour', 'price' => [30, 50], 'duration' => 'PT3H'],
                ['name' => 'Karen Blixen Museum Tour', 'type' => 'Cultural Tour', 'price' => [20, 35], 'duration' => 'PT2H'],
            ],
            'Mombasa' => [
                ['name' => 'Old Town Mombasa Tour', 'type' => 'Cultural Tour', 'price' => [35, 55], 'duration' => 'PT3H'],
                ['name' => 'Fort Jesus Historical Tour', 'type' => 'Historical Tour', 'price' => [20, 35], 'duration' => 'PT2H'],
                ['name' => 'Dhow Cruise & Dinner', 'type' => 'Cultural Experience', 'price' => [60, 100], 'duration' => 'PT3H'],
                ['name' => 'Haller Park Nature Walk', 'type' => 'Nature Walk', 'price' => [15, 25], 'duration' => 'PT2H'],
                ['name' => 'Mombasa Spice Market Tour', 'type' => 'Cultural Tour', 'price' => [25, 40], 'duration' => 'PT2H30M'],
            ],
            'Zanzibar' => [
                ['name' => 'Stone Town Cultural Tour', 'type' => 'Cultural Tour', 'price' => [40, 65], 'duration' => 'PT3H'],
                ['name' => 'Spice Plantation Tour', 'type' => 'Cultural Experience', 'price' => [45, 70], 'duration' => 'PT4H'],
                ['name' => 'Prison Island & Tortoise Sanctuary', 'type' => 'Wildlife Experience', 'price' => [35, 55], 'duration' => 'PT3H'],
                ['name' => 'Jozani Forest Tour', 'type' => 'Nature Walk', 'price' => [30, 50], 'duration' => 'PT2H30M'],
                ['name' => 'Sunset Dhow Cruise', 'type' => 'Cultural Experience', 'price' => [50, 80], 'duration' => 'PT2H'],
                ['name' => 'Cooking Class & Market Tour', 'type' => 'Culinary Experience', 'price' => [55, 85], 'duration' => 'PT4H'],
            ],
            'Dar es Salaam' => [
                ['name' => 'Dar es Salaam City Tour', 'type' => 'Cultural Tour', 'price' => [30, 50], 'duration' => 'PT3H'],
                ['name' => 'Village Museum Experience', 'type' => 'Cultural Experience', 'price' => [25, 40], 'duration' => 'PT2H30M'],
                ['name' => 'Kivukoni Fish Market Tour', 'type' => 'Cultural Tour', 'price' => [20, 35], 'duration' => 'PT2H'],
                ['name' => 'Msimbazi River Boat Safari', 'type' => 'Nature Experience', 'price' => [40, 65], 'duration' => 'PT3H'],
            ],
            'Arusha' => [
                ['name' => 'Serengeti Day Safari', 'type' => 'Wildlife Safari', 'price' => [200, 300], 'duration' => 'PT8H'],
                ['name' => 'Ngorongoro Crater Tour', 'type' => 'Wildlife Safari', 'price' => [180, 250], 'duration' => 'PT8H'],
                ['name' => 'Maasai Village Cultural Tour', 'type' => 'Cultural Experience', 'price' => [50, 80], 'duration' => 'PT4H'],
                ['name' => 'Mount Meru Day Hike', 'type' => 'Adventure Tour', 'price' => [60, 100], 'duration' => 'PT6H'],
                ['name' => 'Coffee Plantation Tour', 'type' => 'Cultural Experience', 'price' => [35, 55], 'duration' => 'PT3H'],
            ],
            'Kigali' => [
                ['name' => 'Kigali Genocide Memorial Tour', 'type' => 'Historical Tour', 'price' => [25, 40], 'duration' => 'PT2H30M'],
                ['name' => 'Kigali City Tour', 'type' => 'Cultural Tour', 'price' => [35, 55], 'duration' => 'PT3H'],
                ['name' => 'Nyamirambo Women\'s Center Tour', 'type' => 'Cultural Experience', 'price' => [30, 50], 'duration' => 'PT2H'],
                ['name' => 'Rwanda Art Museum Visit', 'type' => 'Cultural Tour', 'price' => [20, 35], 'duration' => 'PT2H'],
                ['name' => 'Kimisagara Market Experience', 'type' => 'Cultural Tour', 'price' => [25, 40], 'duration' => 'PT2H30M'],
            ],
            'Kampala' => [
                ['name' => 'Kampala City Walking Tour', 'type' => 'Cultural Tour', 'price' => [30, 50], 'duration' => 'PT3H'],
                ['name' => 'Uganda Museum Visit', 'type' => 'Cultural Tour', 'price' => [20, 35], 'duration' => 'PT2H'],
                ['name' => 'Kasubi Tombs Heritage Tour', 'type' => 'Historical Tour', 'price' => [25, 40], 'duration' => 'PT2H30M'],
                ['name' => 'Owino Market Experience', 'type' => 'Cultural Tour', 'price' => [15, 30], 'duration' => 'PT2H'],
                ['name' => 'Lake Victoria Sunset Cruise', 'type' => 'Nature Experience', 'price' => [40, 65], 'duration' => 'PT2H30M'],
            ],
            'Entebbe' => [
                ['name' => 'Uganda Wildlife Education Centre', 'type' => 'Wildlife Experience', 'price' => [35, 55], 'duration' => 'PT3H'],
                ['name' => 'Entebbe Botanical Gardens Tour', 'type' => 'Nature Walk', 'price' => [20, 35], 'duration' => 'PT2H'],
                ['name' => 'Lake Victoria Island Hopping', 'type' => 'Nature Experience', 'price' => [60, 100], 'duration' => 'PT4H'],
                ['name' => 'Ngamba Island Chimpanzee Sanctuary', 'type' => 'Wildlife Experience', 'price' => [80, 120], 'duration' => 'PT6H'],
            ],
            'Addis Ababa' => [
                ['name' => 'Ethiopian Coffee Ceremony', 'type' => 'Cultural Experience', 'price' => [30, 50], 'duration' => 'PT2H'],
                ['name' => 'National Museum Tour', 'type' => 'Cultural Tour', 'price' => [25, 40], 'duration' => 'PT2H30M'],
                ['name' => 'Merkato Market Experience', 'type' => 'Cultural Tour', 'price' => [35, 55], 'duration' => 'PT3H'],
                ['name' => 'Ethiopian Orthodox Church Tour', 'type' => 'Religious Tour', 'price' => [40, 65], 'duration' => 'PT3H'],
                ['name' => 'Entoto Hill & Museum Tour', 'type' => 'Historical Tour', 'price' => [30, 50], 'duration' => 'PT3H'],
            ],
            'Lalibela' => [
                ['name' => 'Rock-Hewn Churches Tour', 'type' => 'Religious Tour', 'price' => [60, 100], 'duration' => 'PT4H'],
                ['name' => 'Lalibela Cultural Village Tour', 'type' => 'Cultural Experience', 'price' => [40, 65], 'duration' => 'PT3H'],
                ['name' => 'Ethiopian Highland Trekking', 'type' => 'Adventure Tour', 'price' => [80, 120], 'duration' => 'PT6H'],
            ],
        ];

        $countryMapping = [
            'Nairobi' => 'Kenya', 'Mombasa' => 'Kenya',
            'Zanzibar' => 'Tanzania', 'Dar es Salaam' => 'Tanzania', 'Arusha' => 'Tanzania',
            'Kigali' => 'Rwanda',
            'Kampala' => 'Uganda', 'Entebbe' => 'Uganda',
            'Addis Ababa' => 'Ethiopia', 'Lalibela' => 'Ethiopia'
        ];

        $cityCoordinates = [
            'Nairobi' => ['lat' => -1.292066, 'lng' => 36.821946],
            'Mombasa' => ['lat' => -4.043477, 'lng' => 39.668206],
            'Zanzibar' => ['lat' => -6.165917, 'lng' => 39.202641],
            'Dar es Salaam' => ['lat' => -6.792354, 'lng' => 39.208328],
            'Arusha' => ['lat' => -3.386925, 'lng' => 37.101601],
            'Kigali' => ['lat' => -1.944386, 'lng' => 30.061890],
            'Kampala' => ['lat' => 0.347596, 'lng' => 32.582520],
            'Entebbe' => ['lat' => 0.042068, 'lng' => 32.444130],
            'Addis Ababa' => ['lat' => 9.024325, 'lng' => 38.746799],
            'Lalibela' => ['lat' => 12.031389, 'lng' => 39.013333],
        ];

        $activities = [];
        $activityId = 1;

        foreach ($activitiesByCity as $city => $cityActivities) {
            foreach ($cityActivities as $activityData) {
                $basePrice = rand($activityData['price'][0], $activityData['price'][1]);
                $rating = number_format(3.5 + (rand(0, 150) / 100), 1);
                
                $activities[] = [
                    'type' => 'activity',
                    'id' => 'east-africa-activity-' . $activityId,
                    'self' => [
                        'href' => '/v1/shopping/activities/east-africa-activity-' . $activityId,
                        'methods' => ['GET']
                    ],
                    'name' => $activityData['name'],
                    'shortDescription' => 'Experience the best of ' . $city . ' with this amazing ' . strtolower($activityData['type']),
                    'description' => 'Join us for an unforgettable ' . strtolower($activityData['type']) . ' in ' . $city . ', ' . $countryMapping[$city] . '. Our expert local guides will show you the hidden gems and must-see attractions of this beautiful destination. This tour includes all the highlights and provides an authentic cultural experience.',
                    'geoCode' => [
                        'latitude' => $cityCoordinates[$city]['lat'] + (rand(-50, 50) / 1000),
                        'longitude' => $cityCoordinates[$city]['lng'] + (rand(-50, 50) / 1000)
                    ],
                    'rating' => $rating,
                    'pictures' => [
                        'https://source.unsplash.com/800x600/?' . urlencode($city . ' ' . $activityData['type']),
                        'https://source.unsplash.com/800x600/?' . urlencode($countryMapping[$city] . ' tourism')
                    ],
                    'bookingLink' => '/tours/book/east-africa-activity-' . $activityId,
                    'price' => [
                        'currencyCode' => 'USD',
                        'amount' => number_format($basePrice, 2)
                    ],
                    'duration' => $activityData['duration'],
                    'minimumDuration' => 'PT2H',
                    'maximumDuration' => 'PT8H',
                    'city' => $city,
                    'country' => $countryMapping[$city],
                    'activityType' => $activityData['type'],
                    'startTimes' => ['08:00', '14:00'],
                    'inclusions' => [
                        'Professional local guide',
                        'Transportation (if applicable)',
                        'Entrance fees',
                        'Light refreshments'
                    ],
                    'highlights' => [
                        'Expert local guide',
                        'Small group experience',
                        'Cultural immersion',
                        'Photo opportunities'
                    ]
                ];
                
                $activityId++;
            }
        }

        return [
            'meta' => [
                'count' => count($activities),
                'links' => [
                    'self' => 'https://test.api.amadeus.com/v1/shopping/activities'
                ]
            ],
            'data' => $activities,
            'fallback' => true,
            'message' => 'Comprehensive East African activities data - API temporarily unavailable'
        ];
    }

    /**
     * Helper methods
     */
    private function calculateArrivalTime($departureTime, $duration)
    {
        $depHour = (int)explode(':', $departureTime)[0];
        $depMin = (int)explode(':', $departureTime)[1];
        
        // Extract hours and minutes from duration (PT1H30M format)
        preg_match('/PT(\d+)H(\d+)?M?/', $duration, $matches);
        $durationHours = isset($matches[1]) ? (int)$matches[1] : 0;
        $durationMins = isset($matches[2]) ? (int)$matches[2] : 0;
        
        $totalMins = ($depHour * 60) + $depMin + ($durationHours * 60) + $durationMins;
        $arrHour = intval($totalMins / 60) % 24;
        $arrMin = $totalMins % 60;
        
        return sprintf('%02d:%02d', $arrHour, $arrMin);
    }

    private function getLocationDictionary()
    {
        $locations = [];
        foreach ($this->eastAfricanDestinations as $code => $info) {
            $locations[$code] = [
                'cityCode' => $code,
                'countryCode' => substr($info['country'], 0, 2),
                'name' => $info['name'],
                'country' => $info['country']
            ];
        }
        return $locations;
    }

    // public function getFlightOffer($id)
    // {
    //     return response()->json([
    //         'error' => 'Flight offer not found'
    //     ], 404);
    // }

    // public function getHotelOffer($id)
    // {
    //     $result = $this->makeApiCall('/v2/shopping/hotel-offers/' . $id);
    //     return response()->json($result);
    // }

    // public function getActivity($id)
    // {
    //     $result = $this->makeApiCall('/v1/shopping/activities/' . $id);
    //     return response()->json($result);
    // }

    /**
     * Get fallback flight data when API is unavailable
     */
    private function getFallbackFlightData($origin, $destination, $departureDate)
    {
        $cityNames = [
            'NBO' => 'Nairobi',
            'MBA' => 'Mombasa',
            'ZNZ' => 'Zanzibar',
            'DAR' => 'Dar es Salaam',
            'JRO' => 'Kilimanjaro',
            'EBB' => 'Entebbe',
            'KGL' => 'Kigali',
            'ADD' => 'Addis Ababa'
        ];

        $airlines = ['KQ', 'ET', 'PW', 'QR', 'EK', 'TK'];
        $aircraftTypes = ['737-800', 'A320', 'A330', 'B787'];
        
        $flights = [];
        
        for ($i = 0; $i < 15; $i++) {
            $airline = $airlines[array_rand($airlines)];
            $flightNumber = $airline . rand(100, 999);
            $basePrice = rand(200, 800);
            $duration = 'PT' . rand(1, 8) . 'H' . rand(0, 55) . 'M';
            
            $flights[] = [
                'type' => 'flight-offer',
                'id' => 'fallback-flight-' . ($i + 1),
                'source' => 'GDS',
                'instantTicketingRequired' => false,
                'nonHomogeneous' => false,
                'oneWay' => false,
                'lastTicketingDate' => date('Y-m-d', strtotime('+3 days')),
                'numberOfBookableSeats' => rand(1, 9),
                'itineraries' => [[
                    'duration' => $duration,
                    'segments' => [[
                        'departure' => [
                            'iataCode' => $origin,
                            'terminal' => rand(1, 3),
                            'at' => $departureDate . 'T' . sprintf('%02d:%02d:00', rand(6, 22), rand(0, 59))
                        ],
                        'arrival' => [
                            'iataCode' => $destination,
                            'terminal' => rand(1, 3),
                            'at' => $departureDate . 'T' . sprintf('%02d:%02d:00', rand(8, 23), rand(0, 59))
                        ],
                        'carrierCode' => $airline,
                        'number' => $flightNumber,
                        'aircraft' => [
                            'code' => $aircraftTypes[array_rand($aircraftTypes)]
                        ],
                        'operating' => [
                            'carrierCode' => $airline
                        ],
                        'duration' => $duration,
                        'id' => ($i + 1),
                        'numberOfStops' => rand(0, 1),
                        'blacklistedInEU' => false
                    ]]
                ]],
                'price' => [
                    'currency' => 'USD',
                    'total' => number_format($basePrice + rand(0, 200), 2),
                    'base' => number_format($basePrice, 2),
                    'fees' => [[
                        'amount' => number_format(rand(20, 80), 2),
                        'type' => 'SUPPLIER'
                    ]],
                    'grandTotal' => number_format($basePrice + rand(20, 280), 2)
                ],
                'pricingOptions' => [
                    'fareType' => ['PUBLISHED'],
                    'includedCheckedBagsOnly' => true
                ],
                'validatingAirlineCodes' => [$airline],
                'travelerPricings' => [[
                    'travelerId' => '1',
                    'fareOption' => 'STANDARD',
                    'travelerType' => 'ADULT',
                    'price' => [
                        'currency' => 'USD',
                        'total' => number_format($basePrice + rand(0, 200), 2),
                        'base' => number_format($basePrice, 2)
                    ],
                    'fareDetailsBySegment' => [[
                        'segmentId' => '1',
                        'cabin' => 'ECONOMY',
                        'fareBasis' => 'Y1FLEX',
                        'class' => 'Y',
                        'includedCheckedBags' => [
                            'weight' => 23,
                            'weightUnit' => 'KG'
                        ]
                    ]]
                ]]
            ];
        }

        return [
            'meta' => [
                'count' => count($flights),
                'links' => [
                    'self' => 'https://test.api.amadeus.com/v2/shopping/flight-offers'
                ]
            ],
            'data' => $flights,
            'dictionaries' => [
                'locations' => [
                    $origin => [
                        'cityCode' => $origin,
                        'countryCode' => 'KE'
                    ],
                    $destination => [
                        'cityCode' => $destination,
                        'countryCode' => 'TZ'
                    ]
                ],
                'aircraft' => [
                    '320' => 'AIRBUS A320',
                    '737' => 'BOEING 737-800'
                ],
                'currencies' => [
                    'USD' => 'US DOLLAR'
                ],
                'carriers' => [
                    'KQ' => 'KENYA AIRWAYS',
                    'ET' => 'ETHIOPIAN AIRLINES',
                    'PW' => 'PRECISION AIR'
                ]
            ],
            'fallback' => true,
            'message' => 'Using sample flight data - API temporarily unavailable'
        ];
    }

    /**
     * Get fallback hotel data when API is unavailable
     */
    private function getFallbackHotelData($cityCode, $checkInDate, $checkOutDate)
    {
        $cityInfo = [
            'NBO' => ['name' => 'Nairobi', 'country' => 'Kenya'],
            'MBA' => ['name' => 'Mombasa', 'country' => 'Kenya'],
            'ZNZ' => ['name' => 'Zanzibar', 'country' => 'Tanzania'],
            'DAR' => ['name' => 'Dar es Salaam', 'country' => 'Tanzania'],
            'JRO' => ['name' => 'Arusha', 'country' => 'Tanzania'],
            'EBB' => ['name' => 'Entebbe', 'country' => 'Uganda'],
            'KGL' => ['name' => 'Kigali', 'country' => 'Rwanda'],
            'ADD' => ['name' => 'Addis Ababa', 'country' => 'Ethiopia']
        ];

        $city = $cityInfo[$cityCode] ?? ['name' => 'Unknown', 'country' => 'East Africa'];
        
        $hotelNames = [
            'Safari Lodge', 'Grand Hotel', 'Luxury Resort', 'City Hotel', 'Beach Resort',
            'Mountain Lodge', 'Palace Hotel', 'Boutique Inn', 'Business Hotel', 'Garden Hotel'
        ];

        $amenities = [
            ['description' => 'Free WiFi', 'chargeable' => false],
            ['description' => 'Swimming Pool', 'chargeable' => false],
            ['description' => 'Fitness Center', 'chargeable' => false],
            ['description' => 'Restaurant', 'chargeable' => false],
            ['description' => 'Spa Services', 'chargeable' => true],
            ['description' => 'Airport Shuttle', 'chargeable' => false]
        ];

        $hotels = [];
        
        for ($i = 0; $i < 12; $i++) {
            $basePrice = rand(50, 300);
            $rating = rand(3, 5);
            
            $hotels[] = [
                'type' => 'hotel-offers',
                'hotel' => [
                    'type' => 'hotel',
                    'hotelId' => 'fallback-hotel-' . ($i + 1),
                    'chainCode' => 'XX',
                    'dupeId' => rand(100000, 999999),
                    'name' => $hotelNames[array_rand($hotelNames)] . ' ' . $city['name'],
                    'rating' => $rating,
                    'cityCode' => $cityCode,
                    'latitude' => -1.292 + (rand(-100, 100) / 1000),
                    'longitude' => 36.821 + (rand(-100, 100) / 1000),
                    'hotelDistance' => [
                        'distance' => rand(1, 20),
                        'distanceUnit' => 'KM'
                    ],
                    'address' => [
                        'lines' => [rand(1, 999) . ' ' . $city['name'] . ' Street'],
                        'postalCode' => rand(10000, 99999),
                        'cityName' => $city['name'],
                        'countryCode' => substr($city['country'], 0, 2)
                    ],
                    'contact' => [
                        'phone' => '+254-' . rand(100000000, 999999999),
                        'fax' => '+254-' . rand(100000000, 999999999),
                        'email' => 'info@hotel' . ($i + 1) . '.com'
                    ],
                    'amenities' => array_slice($amenities, 0, rand(3, 6))
                ],
                'offers' => [[
                    'id' => 'offer-' . ($i + 1),
                    'checkInDate' => $checkInDate,
                    'checkOutDate' => $checkOutDate,
                    'rateCode' => 'RAC',
                    'rateFamilyEstimated' => [
                        'code' => 'SRS',
                        'type' => 'C'
                    ],
                    'room' => [
                        'type' => 'A1K',
                        'typeEstimated' => [
                            'category' => 'SUPERIOR_ROOM',
                            'beds' => rand(1, 2),
                            'bedType' => rand(0, 1) ? 'KING' : 'DOUBLE'
                        ],
                        'description' => [
                            'text' => 'Superior room with city view',
                            'lang' => 'EN'
                        ]
                    ],
                    'guests' => [
                        'adults' => 1
                    ],
                    'price' => [
                        'currency' => 'USD',
                        'base' => number_format($basePrice, 2),
                        'total' => number_format($basePrice + rand(10, 50), 2),
                        'taxes' => [[
                            'amount' => number_format(rand(5, 25), 2),
                            'currency' => 'USD',
                            'code' => 'TOTAL_TAX',
                            'percentage' => number_format(rand(5, 15), 2),
                            'included' => true,
                            'description' => 'TAX_AND_SERVICE_FEE',
                            'pricingFrequency' => 'PER_STAY',
                            'pricingMode' => 'PER_PRODUCT'
                        ]],
                        'markups' => []
                    ],
                    'policies' => [
                        'paymentType' => 'GUARANTEE',
                        'cancellation' => [
                            'type' => 'FULL_STAY'
                        ]
                    ],
                    'self' => 'https://test.api.amadeus.com/v2/shopping/hotel-offers/offer-' . ($i + 1)
                ]]
            ];
        }

        return [
            'data' => $hotels,
            'fallback' => true,
            'message' => 'Using sample hotel data - API temporarily unavailable'
        ];
    }

    /**
     * Get fallback activities data when API is unavailable
     */
    private function getFallbackActivitiesData()
    {
        $cities = [
            ['name' => 'Nairobi', 'country' => 'Kenya', 'lat' => -1.292066, 'lng' => 36.821946],
            ['name' => 'Mombasa', 'country' => 'Kenya', 'lat' => -4.043477, 'lng' => 39.668206],
            ['name' => 'Zanzibar', 'country' => 'Tanzania', 'lat' => -6.165917, 'lng' => 39.202641],
            ['name' => 'Dar es Salaam', 'country' => 'Tanzania', 'lat' => -6.792354, 'lng' => 39.208328],
            ['name' => 'Arusha', 'country' => 'Tanzania', 'lat' => -3.386925, 'lng' => 37.101601],
            ['name' => 'Kigali', 'country' => 'Rwanda', 'lat' => -1.944386, 'lng' => 30.061890],
            ['name' => 'Entebbe', 'country' => 'Uganda', 'lat' => 0.042068, 'lng' => 32.444130],
            ['name' => 'Addis Ababa', 'country' => 'Ethiopia', 'lat' => 9.024325, 'lng' => 38.746799]
        ];

        $activityTypes = [
            'Safari Adventure', 'Cultural Tour', 'City Walking Tour', 'Wildlife Experience',
            'Beach Excursion', 'Mountain Hiking', 'Historical Tour', 'Food Tasting Tour',
            'Nature Walk', 'Boat Safari', 'Photography Tour', 'Local Market Visit'
        ];

        $activities = [];
        
        foreach ($cities as $cityIndex => $city) {
            for ($i = 0; $i < rand(3, 5); $i++) {
                $activityType = $activityTypes[array_rand($activityTypes)];
                $basePrice = rand(25, 150);
                $rating = number_format(3.5 + (rand(0, 150) / 100), 1);
                
                $activities[] = [
                    'type' => 'activity',
                    'id' => 'fallback-activity-' . (($cityIndex * 5) + $i + 1),
                    'self' => [
                        'href' => '/v1/shopping/activities/fallback-activity-' . (($cityIndex * 5) + $i + 1),
                        'methods' => ['GET']
                    ],
                    'name' => $activityType . ' in ' . $city['name'],
                    'shortDescription' => 'Experience the best of ' . $city['name'] . ' with this ' . strtolower($activityType),
                    'description' => 'Join us for an unforgettable ' . strtolower($activityType) . ' in ' . $city['name'] . ', ' . $city['country'] . '. Our expert local guides will show you the hidden gems and must-see attractions of this beautiful destination.',
                    'geoCode' => [
                        'latitude' => $city['lat'] + (rand(-50, 50) / 1000),
                        'longitude' => $city['lng'] + (rand(-50, 50) / 1000)
                    ],
                    'rating' => $rating,
                    'pictures' => [
                        'https://source.unsplash.com/800x600/?' . urlencode($city['name'] . ' ' . $activityType),
                        'https://source.unsplash.com/800x600/?' . urlencode($city['country'] . ' tourism')
                    ],
                    'bookingLink' => '/tours/book/fallback-activity-' . (($cityIndex * 5) + $i + 1),
                    'price' => [
                        'currencyCode' => 'USD',
                        'amount' => number_format($basePrice, 2)
                    ],
                    'duration' => 'PT' . rand(2, 8) . 'H',
                    'minimumDuration' => 'PT2H',
                    'maximumDuration' => 'PT8H',
                    'city' => $city['name'],
                    'country' => $city['country'],
                    'startTimes' => ['08:00', '14:00'],
                    'inclusions' => [
                        'Professional guide',
                        'Transportation',
                        'Entrance fees',
                        'Refreshments'
                    ]
                ];
            }
        }

        return [
            'meta' => [
                'count' => count($activities),
                'links' => [
                    'self' => 'https://test.api.amadeus.com/v1/shopping/activities'
                ]
            ],
            'data' => $activities,
            'fallback' => true,
            'message' => 'Using sample activities data - API temporarily unavailable'
        ];
    }

    /**
     * Get details for a specific flight offer
     */
    public function getFlightOffer($id)
    {
        return response()->json([
            'error' => 'Flight offer not found'
        ], 404);
    }

    /**
     * Get details for a specific hotel offer
     */
    public function getHotelOffer($id)
    {
        $result = $this->makeApiCall('/v2/shopping/hotel-offers/' . $id);
        
        return response()->json($result);
    }

    /**
     * Get details for a specific activity
     */
    public function getActivity($id)
    {
        $result = $this->makeApiCall('/v1/shopping/activities/' . $id);
        
        return response()->json($result);
    }

    /**
     * Get popular East African routes
     */
    public function getPopularEastAfricanRoutes()
    {
        $popularRoutes = [
            ['from' => 'NBO', 'to' => 'DAR', 'popularity' => 95],
            ['from' => 'NBO', 'to' => 'ADD', 'popularity' => 90],
            ['from' => 'NBO', 'to' => 'EBB', 'popularity' => 85],
            ['from' => 'NBO', 'to' => 'KGL', 'popularity' => 80],
            ['from' => 'NBO', 'to' => 'ZNZ', 'popularity' => 88],
            ['from' => 'NBO', 'to' => 'JRO', 'popularity' => 82],
            ['from' => 'DAR', 'to' => 'ZNZ', 'popularity' => 92],
            ['from' => 'DAR', 'to' => 'EBB', 'popularity' => 75],
            ['from' => 'ADD', 'to' => 'EBB', 'popularity' => 70],
            ['from' => 'ADD', 'to' => 'KGL', 'popularity' => 68],
            ['from' => 'EBB', 'to' => 'KGL', 'popularity' => 85],
            ['from' => 'MBA', 'to' => 'ZNZ', 'popularity' => 90],
        ];

        return response()->json([
            'success' => true,
            'data' => $popularRoutes,
            'destinations' => $this->eastAfricanDestinations
        ]);
    }

    /**
     * Get all East African destinations
     */
    public function getEastAfricanDestinations()
    {
        return response()->json([
            'success' => true,
            'data' => $this->eastAfricanDestinations
        ]);
    }

    /**
     * Search multiple East African routes
     */
    public function searchMultiCityRoutes(Request $request)
    {
        $routes = $request->input('routes', []);
        $departureDate = $request->input('departureDate', date('Y-m-d', strtotime('+1 week')));
        $adults = $request->input('adults', 1);

        $results = [];
        
        foreach ($routes as $route) {
            $searchParams = [
                'originLocationCode' => $route['from'],
                'destinationLocationCode' => $route['to'],
                'departureDate' => $departureDate,
                'adults' => $adults,
                'max' => 5
            ];

            $result = $this->makeApiCall('/v2/shopping/flight-offers', $searchParams);
            
            if (!isset($result['data']) || empty($result['data'])) {
                $result = $this->getComprehensiveFlightData($route['from'], $route['to'], $departureDate);
            }

            $results[] = [
                'route' => $route,
                'flights' => $result
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }
}

