<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Services\SkyscannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\GuzzleException;


// class AirportController extends Controller
// {
//     public function search(Request $request)
//     {
//         $term = $request->get('q', '');
        
//         if (strlen($term) < 1) {
//             return response()->json([]);
//         }

//         $airports = Airport::active()
//             ->search($term)
//             ->limit(10)
//             ->get()
//             ->map(function ($airport) {
//                 return [
//                     'iata_code' => $airport->iata_code,
//                     'name' => $airport->name,
//                     'city' => $airport->city,
//                     'country' => $airport->country,
//                     'display_name' => $airport->display_name,
//                     'location_description' => $airport->location_description,
//                     'full_display' => "{$airport->iata_code} - {$airport->name}, {$airport->city}, {$airport->country}"
//                 ];
//             });

//         return response()->json($airports);
//     }

//     public function scopeSearch($query, $term)
// {
//     return $query->where(function ($q) use ($term) {
//         $q->where('iata_code', 'like', '%' . strtoupper($term) . '%')
//           ->orWhere('name', 'like', '%' . $term . '%')
//           ->orWhere('city', 'like', '%' . $term . '%')
//           ->orWhere('country', 'like', '%' . $term . '%')
//           ->orWhere('display_name', 'like', '%' . $term . '%');
//     });
// }


//     /**
//      * Search locations using Amadeus API
//      */
//     public function searchLocations(Request $request)
//     {
//         $validator = \Validator::make($request->all(), [
//             'keyword' => 'required|string|min:1|max:50',
//             'subType' => 'nullable|array',
//             'subType.*' => 'in:AIRPORT,CITY',
//             'countryCode' => 'nullable|string|size:2',
//             'page_limit' => 'nullable|integer|min:1|max:100',
//             'page_offset' => 'nullable|integer|min:0',
//             'sort' => 'nullable|string|in:analytics.travelers.score',
//             'view' => 'nullable|string|in:LIGHT,FULL'
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Invalid search parameters',
//                 'errors' => $validator->errors()
//             ], 400);
//         }

//         $url = 'https://test.api.amadeus.com/v1/reference-data/locations';
        
//         // Get access token
//         $accessToken = $this->getAccessToken();
//         if (!$accessToken) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to get access token'
//             ], 500);
//         }

//         // Build query parameters
//         $queryParams = [
//             'keyword' => $request->input('keyword'),
//             'subType' => implode(',', $request->input('subType', ['AIRPORT', 'CITY'])),
//             'page[limit]' => $request->input('page_limit', 10),
//             'page[offset]' => $request->input('page_offset', 0),
//             'sort' => $request->input('sort', 'analytics.travelers.score'),
//             'view' => $request->input('view', 'FULL')
//         ];

//         // Add optional parameters
//         if ($request->has('countryCode')) {
//             $queryParams['countryCode'] = $request->input('countryCode');
//         }

//         try {
//             $response = $this->client->get($url, [
//                 'headers' => [
//                     'Accept' => 'application/json',
//                     'Authorization' => 'Bearer ' . $accessToken
//                 ],
//                 'query' => $queryParams
//             ]);

//             $responseData = json_decode($response->getBody(), true);

//             if (isset($responseData['data'])) {
//                 // Process and format the response
//                 $processedData = $this->processLocationData($responseData['data']);
                
//                 return response()->json([
//                     'success' => true,
//                     'data' => $processedData,
//                     'meta' => $responseData['meta'] ?? null,
//                     'raw_data' => $responseData['data'] // Include raw data for debugging
//                 ]);
//             } else {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'No locations found'
//                 ], 404);
//             }

//         } catch (GuzzleException $exception) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Location search failed: ' . $exception->getMessage()
//             ], 500);
//         }
//     }

//     /**
//      * Get location details by ID using Amadeus API
//      */
//     public function getLocationById(Request $request, $locationId)
//     {
//         $validator = \Validator::make(['locationId' => $locationId], [
//             'locationId' => 'required|string|max:10'
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Invalid location ID',
//                 'errors' => $validator->errors()
//             ], 400);
//         }

//         $url = "https://test.api.amadeus.com/v1/reference-data/locations/{$locationId}";
        
//         // Get access token
//         $accessToken = $this->getAccessToken();
//         if (!$accessToken) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to get access token'
//             ], 500);
//         }

//         try {
//             $response = $this->client->get($url, [
//                 'headers' => [
//                     'Accept' => 'application/json',
//                     'Authorization' => 'Bearer ' . $accessToken
//                 ]
//             ]);

//             $responseData = json_decode($response->getBody(), true);

//             if (isset($responseData['data'])) {
//                 // Process single location data
//                 $processedData = $this->processLocationData([$responseData['data']])[0];
                
//                 return response()->json([
//                     'success' => true,
//                     'data' => $processedData,
//                     'meta' => $responseData['meta'] ?? null
//                 ]);
//             } else {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Location not found'
//                 ], 404);
//             }

//         } catch (GuzzleException $exception) {
//             $statusCode = $exception->getCode();
            
//             if ($statusCode === 404) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Location not found'
//                 ], 404);
//             }

//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to get location details: ' . $exception->getMessage()
//             ], 500);
//         }
//     }

 

//     /**
//      * Get popular airports/cities
//      */
//     public function getPopularLocations(Request $request)
//     {
//         $validator = \Validator::make($request->all(), [
//             'subType' => 'nullable|array',
//             'subType.*' => 'in:AIRPORT,CITY',
//             'countryCode' => 'nullable|string|size:2',
//             'limit' => 'nullable|integer|min:1|max:50'
//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Invalid parameters',
//                 'errors' => $validator->errors()
//             ], 400);
//         }

//         // Popular keywords to search for
//         $popularKeywords = ['LON', 'NYC', 'PAR', 'TOK', 'SIN', 'DXB', 'FRA', 'AMS', 'CDG', 'LAX'];
//         $allResults = [];

//         foreach ($popularKeywords as $keyword) {
//             $searchRequest = new Request([
//                 'keyword' => $keyword,
//                 'subType' => $request->input('subType', ['AIRPORT']),
//                 'countryCode' => $request->input('countryCode'),
//                 'page_limit' => 1,
//                 'view' => 'FULL'
//             ]);

//             try {
//                 $response = $this->searchLocations($searchRequest);
//                 $data = json_decode($response->getContent(), true);
                
//                 if (isset($data['data']) && !empty($data['data'])) {
//                     $allResults[] = $data['data'][0];
//                 }
//             } catch (\Exception $e) {
//                 continue; // Skip failed searches
//             }
//         }

//         // Sort by traveler score and limit results
//         usort($allResults, function($a, $b) {
//             $scoreA = $a['analytics']['travelers']['score'] ?? 0;
//             $scoreB = $b['analytics']['travelers']['score'] ?? 0;
//             return $scoreB <=> $scoreA;
//         });

//         $limit = $request->input('limit', 10);
//         $allResults = array_slice($allResults, 0, $limit);

//         return response()->json([
//             'success' => true,
//             'data' => $allResults,
//             'count' => count($allResults)
//         ]);
//     }

//     /**
//      * Process location data from Amadeus API
//      */
//     private function processLocationData($data)
//     {
//         $processed = [];

//         foreach ($data as $location) {
//             $processedLocation = [
//                 'id' => $location['id'],
//                 'type' => $location['type'],
//                 'subType' => $location['subType'],
//                 'name' => $location['name'],
//                 'detailedName' => $location['detailedName'] ?? null,
//                 'iataCode' => $location['iataCode'] ?? null,
//                 'timeZoneOffset' => $location['timeZoneOffset'] ?? null,
//                 'geoCode' => $location['geoCode'] ?? null,
//                 'address' => [
//                     'cityName' => $location['address']['cityName'] ?? null,
//                     'cityCode' => $location['address']['cityCode'] ?? null,
//                     'countryName' => $location['address']['countryName'] ?? null,
//                     'countryCode' => $location['address']['countryCode'] ?? null,
//                     'regionCode' => $location['address']['regionCode'] ?? null
//                 ],
//                 'analytics' => [
//                     'travelers' => [
//                         'score' => $location['analytics']['travelers']['score'] ?? 0
//                     ]
//                 ],
//                 'display_name' => $this->formatDisplayName($location),
//                 'full_display' => $this->formatFullDisplay($location)
//             ];

//             $processed[] = $processedLocation;
//         }

//         return $processed;
//     }

//     /**
//      * Format display name for location
//      */
//     private function formatDisplayName($location)
//     {
//         $iataCode = $location['iataCode'] ?? '';
//         $name = $location['name'] ?? '';
//         $cityName = $location['address']['cityName'] ?? '';
        
//         if ($iataCode && $name && $cityName) {
//             return "{$iataCode} - {$name}, {$cityName}";
//         } elseif ($iataCode && $name) {
//             return "{$iataCode} - {$name}";
//         } elseif ($name) {
//             return $name;
//         }
        
//         return $iataCode ?: 'Unknown';
//     }

//     /**
//      * Format full display name for location
//      */
//     private function formatFullDisplay($location)
//     {
//         $iataCode = $location['iataCode'] ?? '';
//         $name = $location['name'] ?? '';
//         $cityName = $location['address']['cityName'] ?? '';
//         $countryName = $location['address']['countryName'] ?? '';
        
//         $parts = [];
        
//         if ($iataCode) {
//             $parts[] = $iataCode;
//         }
        
//         if ($name) {
//             $parts[] = $name;
//         }
        
//         if ($cityName) {
//             $parts[] = $cityName;
//         }
        
//         if ($countryName) {
//             $parts[] = $countryName;
//         }
        
//         return implode(', ', $parts);
//     }

  

//     /**
//      * Get access token for Amadeus API
//      */
//     private function getAccessToken()
//     {
//         if (session('access_token')) {
//             return session('access_token');
//         }

//         try {
//             $access_token = app('App\Http\Controllers\AccessTokenController')
//                 ->__invoke($this->client)->access_token;
//             session(['access_token' => $access_token]);
//             return $access_token;
//         } catch (\Exception $e) {
//             return null;
//         }
//     }
// }
class AirportController extends Controller
{
    // Cache duration in seconds (5 minutes)
    private const CACHE_DURATION = 300;
    
    /**
     * City to airports mapping for East African cities
     */
    private $cityAirportMapping = [
        // Kenya
        'nairobi' => [
            ['iata' => 'NBO', 'name' => 'Jomo Kenyatta International Airport', 'city' => 'Nairobi', 'country' => 'Kenya'],
            ['iata' => 'WIL', 'name' => 'Wilson Airport', 'city' => 'Nairobi', 'country' => 'Kenya']
        ],
        'mombasa' => [
            ['iata' => 'MBA', 'name' => 'Moi International Airport', 'city' => 'Mombasa', 'country' => 'Kenya']
        ],
        'eldoret' => [
            ['iata' => 'EDL', 'name' => 'Eldoret International Airport', 'city' => 'Eldoret', 'country' => 'Kenya']
        ],
        'kisumu' => [
            ['iata' => 'KIS', 'name' => 'Kisumu International Airport', 'city' => 'Kisumu', 'country' => 'Kenya']
        ],
        'malindi' => [
            ['iata' => 'MYD', 'name' => 'Malindi Airport', 'city' => 'Malindi', 'country' => 'Kenya']
        ],
        'lamu' => [
            ['iata' => 'LAU', 'name' => 'Lamu Airport', 'city' => 'Lamu', 'country' => 'Kenya']
        ],
        'ukunda' => [
            ['iata' => 'UKA', 'name' => 'Ukunda Airport', 'city' => 'Ukunda', 'country' => 'Kenya']
        ],
        
        // Uganda
        'kampala' => [
            ['iata' => 'EBB', 'name' => 'Entebbe International Airport', 'city' => 'Kampala/Entebbe', 'country' => 'Uganda']
        ],
        'entebbe' => [
            ['iata' => 'EBB', 'name' => 'Entebbe International Airport', 'city' => 'Entebbe', 'country' => 'Uganda']
        ],
        'jinja' => [
            ['iata' => 'JIN', 'name' => 'Jinja Airport', 'city' => 'Jinja', 'country' => 'Uganda']
        ],
        'gulu' => [
            ['iata' => 'ULU', 'name' => 'Gulu Airport', 'city' => 'Gulu', 'country' => 'Uganda']
        ],
        'arua' => [
            ['iata' => 'RUA', 'name' => 'Arua Airport', 'city' => 'Arua', 'country' => 'Uganda']
        ],
        
        // Tanzania
        'dar es salaam' => [
            ['iata' => 'DAR', 'name' => 'Julius Nyerere International Airport', 'city' => 'Dar es Salaam', 'country' => 'Tanzania']
        ],
        'dar-es-salaam' => [
            ['iata' => 'DAR', 'name' => 'Julius Nyerere International Airport', 'city' => 'Dar es Salaam', 'country' => 'Tanzania']
        ],
        'dar' => [
            ['iata' => 'DAR', 'name' => 'Julius Nyerere International Airport', 'city' => 'Dar es Salaam', 'country' => 'Tanzania']
        ],
        'dodoma' => [
            ['iata' => 'DOD', 'name' => 'Dodoma Airport', 'city' => 'Dodoma', 'country' => 'Tanzania']
        ],
        'arusha' => [
            ['iata' => 'ARK', 'name' => 'Arusha Airport', 'city' => 'Arusha', 'country' => 'Tanzania'],
            ['iata' => 'JRO', 'name' => 'Kilimanjaro International Airport', 'city' => 'Arusha/Moshi', 'country' => 'Tanzania']
        ],
        'zanzibar' => [
            ['iata' => 'ZNZ', 'name' => 'Abeid Amani Karume International Airport', 'city' => 'Zanzibar', 'country' => 'Tanzania']
        ],
        'mwanza' => [
            ['iata' => 'MWZ', 'name' => 'Mwanza Airport', 'city' => 'Mwanza', 'country' => 'Tanzania']
        ],
        'kilimanjaro' => [
            ['iata' => 'JRO', 'name' => 'Kilimanjaro International Airport', 'city' => 'Kilimanjaro', 'country' => 'Tanzania']
        ],
        'moshi' => [
            ['iata' => 'JRO', 'name' => 'Kilimanjaro International Airport', 'city' => 'Moshi', 'country' => 'Tanzania']
        ],
        
        // Rwanda
        'kigali' => [
            ['iata' => 'KGL', 'name' => 'Kigali International Airport', 'city' => 'Kigali', 'country' => 'Rwanda']
        ],
        'butare' => [
            ['iata' => 'BTQ', 'name' => 'Butare Airport', 'city' => 'Butare', 'country' => 'Rwanda']
        ],
        
        // Burundi
        'bujumbura' => [
            ['iata' => 'BJM', 'name' => 'Bujumbura International Airport', 'city' => 'Bujumbura', 'country' => 'Burundi']
        ],
        
        // South Sudan
        'juba' => [
            ['iata' => 'JUB', 'name' => 'Juba International Airport', 'city' => 'Juba', 'country' => 'South Sudan']
        ],
        
        // Ethiopia (border cities)
        'addis ababa' => [
            ['iata' => 'ADD', 'name' => 'Addis Ababa Bole International Airport', 'city' => 'Addis Ababa', 'country' => 'Ethiopia']
        ],
        'addis' => [
            ['iata' => 'ADD', 'name' => 'Addis Ababa Bole International Airport', 'city' => 'Addis Ababa', 'country' => 'Ethiopia']
        ],
    ];

    /**
     * Ultra-fast airport search with enhanced city location support
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 1) {
            return $this->getPopularAirports();
        }

        // Cache key for this search
        $cacheKey = 'airport_search_' . md5(strtolower($query));
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($query) {
            return $this->performOptimizedSearch($query);
        });
    }

    /**
     * Ultra-fast optimized search combining city mapping and database
     */
    private function performOptimizedSearch($query)
    {
        $searchTerm = strtolower(trim($query));
        $results = [];
        
        // First, check if the search term matches any city in our mapping (instant results)
        $cityResults = $this->searchByCityName($query);
        if (!empty($cityResults)) {
            $results = array_merge($results, $cityResults);
        }
        
        // Then search the database for additional matches
        $isIataSearch = preg_match('/^[A-Za-z]{1,3}$/', $query);
        
        if ($isIataSearch) {
            // For IATA-like searches, prioritize IATA code matches
            $dbResults = Airport::where(function ($q) use ($searchTerm) {
                $q->where(DB::raw('LOWER(iata_code)'), 'LIKE', $searchTerm . '%')
                  ->orWhere(DB::raw('LOWER(iata_code)'), 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere(DB::raw('LOWER(name)'), 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere(DB::raw('LOWER(city)'), 'LIKE', '%' . $searchTerm . '%');
            })
            ->orderByRaw("
                CASE 
                    WHEN LOWER(iata_code) = ? THEN 1
                    WHEN LOWER(iata_code) LIKE ? THEN 2
                    WHEN LOWER(iata_code) LIKE ? THEN 3
                    WHEN LOWER(name) LIKE ? THEN 4
                    ELSE 5
                END
            ", [$searchTerm, $searchTerm . '%', '%' . $searchTerm . '%', $searchTerm . '%'])
            ->limit(8)
            ->get();
        } else {
            // For text searches, prioritize city matches
            $dbResults = Airport::where(function ($q) use ($searchTerm) {
                $q->where(DB::raw('LOWER(city)'), 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere(DB::raw('LOWER(name)'), 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere(DB::raw('LOWER(country)'), 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere(DB::raw('LOWER(iata_code)'), 'LIKE', '%' . $searchTerm . '%');
            })
            ->orderByRaw("
                CASE 
                    WHEN LOWER(city) = ? THEN 1
                    WHEN LOWER(city) LIKE ? THEN 2
                    WHEN LOWER(iata_code) LIKE ? THEN 3
                    WHEN LOWER(name) LIKE ? THEN 4
                    ELSE 5
                END
            ", [$searchTerm, $searchTerm . '%', '%' . $searchTerm . '%', $searchTerm . '%'])
            ->limit(8)
            ->get();
        }
        
        // Convert database results to consistent format
        $formattedDbResults = $dbResults->map(function ($airport) {
            return [
                'iata_code' => $airport->iata_code,
                'name' => $airport->name,
                'city' => $airport->city,
                'country' => $airport->country,
                'display_name' => $airport->display_name ?? "{$airport->iata_code} - {$airport->name}",
                'location_description' => $airport->location_description ?? "{$airport->city}, {$airport->country}",
                'full_display' => "{$airport->iata_code} - {$airport->name}, {$airport->city}, {$airport->country}",
                'source' => 'database'
            ];
        })->toArray();
        
        // Merge city results with database results, removing duplicates
        $existingIataCodes = collect($results)->pluck('iata_code')->toArray();
        foreach ($formattedDbResults as $dbResult) {
            if (!in_array($dbResult['iata_code'], $existingIataCodes)) {
                $results[] = $dbResult;
            }
        }

        // Merge RapidAPI (Sky Scanner) autocomplete so cities outside the local DB work.
        $rapidApiResults = $this->fromRapidApi($query);
        $mergedByCode = [];
        foreach (array_merge($rapidApiResults, $results) as $item) {
            $code = isset($item['iata_code']) ? strtoupper($item['iata_code']) : '';
            $item = $this->withEntity($item);
            if ($code !== '') {
                if (!isset($mergedByCode[$code])) {
                    $mergedByCode[$code] = $item;
                }
            } elseif ($code === '') {
                $mergedByCode[] = $item;
            }
        }

        return response()->json(array_slice(array_values($mergedByCode), 0, 12));
    }

    /**
     * Enrich a search result item with entity identifiers the widget forwards
     * to the flight search endpoint.
     */
    private function withEntity(array $item)
    {
        $code = isset($item['iata_code']) ? strtoupper($item['iata_code']) : '';
        $item['entity_id'] = $item['entity_id'] ?? ($item['sky_id'] ?? $code);
        $item['entity_type'] = $item['entity_type'] ?? 'AIRPORT';
        $item['sky_id'] = $item['sky_id'] ?? $code;
        return $item;
    }

    /**
     * Ask RapidAPI Sky Scanner autocomplete for places matching the query.
     * Failures are swallowed - local results still work on their own.
     */
    private function fromRapidApi($query)
    {
        try {
            $service = app(SkyscannerService::class);
            $places = $service->autoComplete($query, 10);

            $items = [];
            foreach ($places as $place) {
                $iata = isset($place['iata_code']) ? $place['iata_code'] : '';
                $items[] = [
                    'iata_code' => $iata,
                    'name' => $place['name'] ?? '',
                    'city' => $place['city'] ?? '',
                    'country' => $place['country'] ?? '',
                    'display_name' => $place['display_name'] ?? '',
                    'location_description' => $place['location_description'] ?? '',
                    'full_display' => $place['full_display'] ?? '',
                    'entity_id' => $place['entity_id'] ?? $iata,
                    'entity_type' => $place['entity_type'] ?? 'CITY',
                    'sky_id' => $place['sky_id'] ?? $iata,
                    'source' => 'rapidapi',
                ];
            }

            return $items;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Search airports by city name using predefined mapping
     */
    private function searchByCityName($term)
    {
        $searchTerm = strtolower(trim($term));
        $results = [];
        
        // Direct city match
        if (isset($this->cityAirportMapping[$searchTerm])) {
            foreach ($this->cityAirportMapping[$searchTerm] as $airport) {
                $results[] = [
                    'iata_code' => $airport['iata'],
                    'name' => $airport['name'],
                    'city' => $airport['city'],
                    'country' => $airport['country'],
                    'display_name' => "{$airport['iata']} - {$airport['name']}",
                    'location_description' => "{$airport['city']}, {$airport['country']}",
                    'full_display' => "{$airport['iata']} - {$airport['name']}, {$airport['city']}, {$airport['country']}",
                    'source' => 'city_mapping'
                ];
            }
        } else {
            // Partial city name match
            foreach ($this->cityAirportMapping as $cityName => $airports) {
                if (strpos($cityName, $searchTerm) !== false || strpos($searchTerm, $cityName) !== false) {
                    foreach ($airports as $airport) {
                        $results[] = [
                            'iata_code' => $airport['iata'],
                            'name' => $airport['name'],
                            'city' => $airport['city'],
                            'country' => $airport['country'],
                            'display_name' => "{$airport['iata']} - {$airport['name']}",
                            'location_description' => "{$airport['city']}, {$airport['country']}",
                            'full_display' => "{$airport['iata']} - {$airport['name']}, {$airport['city']}, {$airport['country']}",
                            'source' => 'city_mapping'
                        ];
                    }
                }
            }
        }
        
        return $results;
    }

    /**
     * Ultra-fast IATA code search
     */
    public function searchByIataCode(Request $request)
    {
        $code = strtoupper($request->get('code', ''));
        
        if (strlen($code) < 1) {
            return response()->json([]);
        }

        $cacheKey = 'iata_search_' . $code;
        
        $airports = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($code) {
            $results = [];
            
            // First check our city mapping for IATA codes (instant results)
            foreach ($this->cityAirportMapping as $cityName => $airports) {
                foreach ($airports as $airport) {
                    $airportIata = strtoupper($airport['iata']);
                    if (strpos($airportIata, $code) !== false) {
                        $results[] = [
                            'iata_code' => $airport['iata'],
                            'name' => $airport['name'],
                            'city' => $airport['city'],
                            'country' => $airport['country'],
                            'display_name' => "{$airport['iata']} - {$airport['name']}",
                            'location_description' => "{$airport['city']}, {$airport['country']}",
                            'full_display' => "{$airport['iata']} - {$airport['name']}, {$airport['city']}, {$airport['country']}",
                            'source' => 'city_mapping'
                        ];
                    }
                }
            }
            
            // Then search database for additional matches
            $dbResults = Airport::where('iata_code', 'LIKE', $code . '%')
                ->orWhere('iata_code', 'LIKE', '%' . $code . '%')
                ->orderByRaw("CASE WHEN iata_code = ? THEN 1 WHEN iata_code LIKE ? THEN 2 ELSE 3 END", [$code, $code . '%'])
                ->limit(8)
                ->get()
                ->map(function ($airport) {
                    return [
                        'iata_code' => $airport->iata_code,
                        'name' => $airport->name,
                        'city' => $airport->city,
                        'country' => $airport->country,
                        'display_name' => $airport->display_name ?? "{$airport->iata_code} - {$airport->name}",
                        'location_description' => $airport->location_description ?? "{$airport->city}, {$airport->country}",
                        'full_display' => "{$airport->iata_code} - {$airport->name}, {$airport->city}, {$airport->country}",
                        'source' => 'database'
                    ];
                })->toArray();
                
            // Merge results, removing duplicates
            $existingCodes = collect($results)->pluck('iata_code')->toArray();
            foreach ($dbResults as $dbResult) {
                if (!in_array($dbResult['iata_code'], $existingCodes)) {
                    $results[] = $dbResult;
                }
            }
            
            return array_slice($results, 0, 10);
        });

        return response()->json(array_map([$this, 'withEntity'], $airports));
    }

    /**
     * Get airports by city with enhanced mapping
     */
    public function getByCity(Request $request, $city)
    {
        $cacheKey = 'airport_city_' . md5(strtolower($city));
        
        $airports = Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($city) {
            // First check our city mapping
            $cityResults = $this->searchByCityName($city);
            
            // Then check database
            $dbResults = Airport::where('city', 'LIKE', '%' . $city . '%')
                ->orWhere('name', 'LIKE', '%' . $city . '%')
                ->orderBy('city')
                ->get()
                ->map(function ($airport) {
                    return [
                        'iata_code' => $airport->iata_code,
                        'name' => $airport->name,
                        'city' => $airport->city,
                        'country' => $airport->country,
                        'display_name' => $airport->display_name ?? "{$airport->iata_code} - {$airport->name}",
                        'location_description' => $airport->location_description ?? "{$airport->city}, {$airport->country}",
                        'full_display' => "{$airport->iata_code} - {$airport->name}, {$airport->city}, {$airport->country}",
                        'source' => 'database'
                    ];
                })->toArray();
                
            // Merge results
            $existingCodes = collect($cityResults)->pluck('iata_code')->toArray();
            foreach ($dbResults as $dbResult) {
                if (!in_array($dbResult['iata_code'], $existingCodes)) {
                    $cityResults[] = $dbResult;
                }
            }
            
            return $cityResults;
        });

        return response()->json(array_map([$this, 'withEntity'], $airports));
    }

    /**
     * Enhanced popular airports including East African hubs
     */
    public function getPopularAirports(Request $request = null)
    {
        $limit = $request ? $request->get('limit', 20) : 20;
        
        $cacheKey = 'popular_airports_' . $limit;
        
        $airports = Cache::remember($cacheKey, 3600, function () use ($limit) {
            // Include major East African airports in popular list
            $popularCodes = [
                // East African hubs (prioritized)
                'NBO', 'EBB', 'KGL', 'DAR', 'ADD', 'ZNZ', 'JRO', 'MBA', 'EDL', 'KIS',
                // International hubs
                'LAX', 'JFK', 'LHR', 'CDG', 'DXB', 'NRT', 'SIN', 'FRA', 
                'AMS', 'MAD', 'FCO', 'MUC', 'ZUR', 'VIE', 'CPH', 'ARN',
                'BRU', 'LIS', 'ATH', 'IST', 'SVO', 'LED', 'WAW', 'PRG'
            ];
            
            $results = [];
            
            // First, get from our city mapping (instant results)
            foreach ($this->cityAirportMapping as $cityName => $airports) {
                foreach ($airports as $airport) {
                    if (in_array($airport['iata'], $popularCodes)) {
                        $results[] = [
                            'iata_code' => $airport['iata'],
                            'name' => $airport['name'],
                            'city' => $airport['city'],
                            'country' => $airport['country'],
                            'display_name' => "{$airport['iata']} - {$airport['name']}",
                            'location_description' => "{$airport['city']}, {$airport['country']}",
                            'full_display' => "{$airport['iata']} - {$airport['name']}, {$airport['city']}, {$airport['country']}",
                            'source' => 'popular_mapping'
                        ];
                    }
                }
            }
            
            // Then get from database for remaining slots
            $existingCodes = collect($results)->pluck('iata_code')->toArray();
            $remainingCodes = array_diff($popularCodes, $existingCodes);
            
            if (!empty($remainingCodes) && count($results) < $limit) {
                $dbResults = Airport::whereIn('iata_code', $remainingCodes)
                    ->orderByRaw("FIELD(iata_code, '" . implode("','", $remainingCodes) . "')")
                    ->limit($limit - count($results))
                    ->get()
                    ->map(function ($airport) {
                        return [
                            'iata_code' => $airport->iata_code,
                            'name' => $airport->name,
                            'city' => $airport->city,
                            'country' => $airport->country,
                            'display_name' => $airport->display_name ?? "{$airport->iata_code} - {$airport->name}",
                            'location_description' => $airport->location_description ?? "{$airport->city}, {$airport->country}",
                            'full_display' => "{$airport->iata_code} - {$airport->name}, {$airport->city}, {$airport->country}",
                            'source' => 'popular_database'
                        ];
                    })->toArray();
                    
                $results = array_merge($results, $dbResults);
            }
            
            return array_slice($results, 0, $limit);
        });

        return response()->json(array_map([$this, 'withEntity'], $airports));
    }

    // Keep all your existing methods for Amadeus search, etc.
    public function searchLocations(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'keyword' => 'required|string|min:1|max:50',
            'subType' => 'nullable|array',
            'subType.*' => 'in:AIRPORT,CITY',
            'countryCode' => 'nullable|string|size:2',
            'page_limit' => 'nullable|integer|min:1|max:50',
            'page_offset' => 'nullable|integer|min:0',
            'sort' => 'nullable|string|in:analytics.travelers.score',
            'view' => 'nullable|string|in:LIGHT,FULL'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid search parameters',
                'errors' => $validator->errors()
            ], 400);
        }

        $keyword = $request->input('keyword');
        $cacheKey = 'amadeus_search_' . md5($keyword . serialize($request->except(['_token'])));
        
        return Cache::remember($cacheKey, 600, function () use ($request) {
            return $this->performAmadeusSearch($request);
        });
    }

    private function performAmadeusSearch(Request $request)
    {
        $url = 'https://test.api.amadeus.com/v1/reference-data/locations';
        
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get access token'
            ], 500);
        }

        $queryParams = [
            'keyword' => $request->input('keyword'),
            'subType' => implode(',', $request->input('subType', ['AIRPORT'])),
            'page[limit]' => $request->input('page_limit', 10),
            'page[offset]' => $request->input('page_offset', 0),
            'sort' => $request->input('sort', 'analytics.travelers.score'),
            'view' => $request->input('view', 'FULL')
        ];

        if ($request->has('countryCode')) {
            $queryParams['countryCode'] = $request->input('countryCode');
        }

        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken
                ],
                'query' => $queryParams,
                'timeout' => 5
            ]);

            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['data'])) {
                $processedData = $this->processLocationData($responseData['data']);
                
                return response()->json([
                    'success' => true,
                    'data' => $processedData,
                    'meta' => $responseData['meta'] ?? null
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No locations found'
                ], 404);
            }

        } catch (GuzzleException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Location search failed: ' . $exception->getMessage()
            ], 500);
        }
    }

    private function processLocationData($data)
    {
        $processed = [];

        foreach ($data as $location) {
            $processedLocation = [
                'id' => $location['id'],
                'type' => $location['type'],
                'subType' => $location['subType'],
                'name' => $location['name'],
                'detailedName' => $location['detailedName'] ?? null,
                'iataCode' => $location['iataCode'] ?? null,
                'timeZoneOffset' => $location['timeZoneOffset'] ?? null,
                'geoCode' => $location['geoCode'] ?? null,
                'address' => [
                    'cityName' => $location['address']['cityName'] ?? null,
                    'cityCode' => $location['address']['cityCode'] ?? null,
                    'countryName' => $location['address']['countryName'] ?? null,
                    'countryCode' => $location['address']['countryCode'] ?? null,
                    'regionCode' => $location['address']['regionCode'] ?? null
                ],
                'analytics' => [
                    'travelers' => [
                        'score' => $location['analytics']['travelers']['score'] ?? 0
                    ]
                ],
                'display_name' => $this->formatDisplayName($location),
                'full_display' => $this->formatFullDisplay($location)
            ];

            $processed[] = $processedLocation;
        }

        return $processed;
    }

    private function formatDisplayName($location)
    {
        $iataCode = $location['iataCode'] ?? '';
        $name = $location['name'] ?? '';
        $cityName = $location['address']['cityName'] ?? '';
        
        if ($iataCode && $name && $cityName) {
            return "{$iataCode} - {$name}, {$cityName}";
        } elseif ($iataCode && $name) {
            return "{$iataCode} - {$name}";
        } elseif ($name) {
            return $name;
        }
        
        return $iataCode ?: 'Unknown';
    }

    private function formatFullDisplay($location)
    {
        $iataCode = $location['iataCode'] ?? '';
        $name = $location['name'] ?? '';
        $cityName = $location['address']['cityName'] ?? '';
        $countryName = $location['address']['countryName'] ?? '';
        
        $parts = array_filter([$iataCode, $name, $cityName, $countryName]);
        return implode(', ', $parts);
    }

    private function getAccessToken()
    {
        return Cache::remember('amadeus_access_token', 1700, function () {
            try {
                $accessToken = app('App\Http\Controllers\AccessTokenController')
                    ->__invoke($this->client)->access_token;
                return $accessToken;
            } catch (\Exception $e) {
                return null;
            }
        });
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('iata_code', 'like', '%' . strtoupper($term) . '%')
              ->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('city', 'like', '%' . $term . '%')
              ->orWhere('country', 'like', '%' . $term . '%')
              ->orWhere('display_name', 'like', '%' . $term . '%');
        });
    }
}