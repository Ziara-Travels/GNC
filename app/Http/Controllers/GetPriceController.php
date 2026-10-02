<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class GetPriceController extends Controller
{
    public function __invoke (Request $request, Client $client)
    {
        $url = 'https://test.api.amadeus.com/v1/shopping/flight-offers/pricing';

        if (session('access_token')) {
            $access_token = session('access_token');
        } else {
            $access_token = app('App\Http\Controllers\AccessTokenController')->__invoke($client)->access_token;
            session(['access_token' => $access_token]);
        }

        $data = [
            'data' => [
                'type' => 'flight-offers-pricing',
                'flightOffers' => [
                    json_decode($request['flight'])
                ]
            ]
        ];

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

            return view('price')->with('data', $response->data);
        } catch (GuzzleException $exception) {
            return $exception->getMessage();
        }
    }

     protected $eastAfricanDestinations = [
        'NBO' => [
            'city' => 'Nairobi',
            'country' => 'Kenya',
            'image' => 'nairobi.jpg',
            'description' => 'Home to iconic KICC and vibrant urban life',
            'highlight' => 'KICC'
        ],
        'ZNZ' => [
            'city' => 'Zanzibar',
            'country' => 'Tanzania',
            'image' => 'zanzibar.jpg',
            'description' => 'Crystal clear waters and pristine beaches',
            'highlight' => 'Beautiful Beaches'
        ],
        'MBA' => [
            'city' => 'Mombasa',
            'country' => 'Kenya',
            'image' => 'mombasa.jpg',
            'description' => 'Coastal city with rich history and beaches',
            'highlight' => 'Iconic Ivory Tusks'
        ],
        'EDL' => [
            'city' => 'Eldoret',
            'country' => 'Kenya',
            'image' => 'eldoret.jpg',
            'description' => 'High-altitude training center for athletes',
            'highlight' => 'Land of Runners'
        ],
        'KIS' => [
            'city' => 'Kisumu',
            'country' => 'Kenya',
            'image' => 'kisumu.jpg',
            'description' => 'Port city on the shores of Lake Victoria',
            'highlight' => 'Lake Victoria'
        ],
        'ARK' => [
            'city' => 'Arusha',
            'country' => 'Tanzania',
            'image' => 'arusha.jpg',
            'description' => 'Gateway to safari destinations and Mount Meru',
            'highlight' => 'Safari Gateway'
        ],
        'DOD' => [
            'city' => 'Dodoma',
            'country' => 'Tanzania',
            'image' => 'dodoma.jpg',
            'description' => 'Tanzania\'s capital city with historical sites',
            'highlight' => 'Capital City'
        ],
        'DAR' => [
            'city' => 'Dar es Salaam',
            'country' => 'Tanzania',
            'image' => 'dar-es-salaam.jpg',
            'description' => 'Major port city and commercial hub',
            'highlight' => 'Harbor City'
        ],
        'JIN' => [
            'city' => 'Jinja',
            'country' => 'Uganda',
            'image' => 'jinja.jpg',
            'description' => 'Adventure capital and source of the Nile',
            'highlight' => 'Source of the Nile'
        ],
        'EBB' => [
            'city' => 'Entebbe',
            'country' => 'Uganda',
            'image' => 'entebbe.jpg',
            'description' => 'Scenic city on the shores of Lake Victoria',
            'highlight' => 'Gateway to Uganda'
        ],
        'KGL' => [
            'city' => 'Kigali',
            'country' => 'Rwanda',
            'image' => 'kigali.jpg',
            'description' => 'Clean, green, and scenic capital of Rwanda',
            'highlight' => 'Cleanest City'
        ],
        'ADD' => [
            'city' => 'Addis Ababa',
            'country' => 'Ethiopia',
            'image' => 'addis-ababa.jpg',
            'description' => 'Diplomatic capital of Africa with rich culture',
            'highlight' => 'Diplomatic Hub'
        ]
    ];

    /**
     * Get flight offers for East African destinations
     */
    public function getEastAfricanOffers(Request $request, Client $client)
    {
        // Check if we have cached results
        if (Cache::has('east_african_flight_offers')) {
            return Cache::get('east_african_flight_offers');
        }

        // Base airport (most likely Nairobi)
        $originCode = 'NBO';
        $offers = [];
        $totalOffers = 0;
        $maxOffers = 9; // Maximum offers to display

        try {
            // Get access token
            if (session('access_token')) {
                $access_token = session('access_token');
            } else {
                $access_token = app('App\Http\Controllers\AccessTokenController')->__invoke($client)->access_token;
                session(['access_token' => $access_token]);
            }

            // Set API params
            $currentDate = Carbon::now();
            $departureDate = $currentDate->addDays(14)->format('Y-m-d'); // Two weeks from now
            $returnDate = $currentDate->addDays(7)->format('Y-m-d');     // Three weeks from now
            
            // Pick random destinations
            $destinations = array_keys($this->eastAfricanDestinations);
            shuffle($destinations);
            $destinations = array_slice($destinations, 0, min(count($destinations), $maxOffers));
            
            foreach ($destinations as $destinationCode) {
                if ($originCode === $destinationCode) continue; // Skip if same as origin
                
                $url = 'https://test.api.amadeus.com/v2/shopping/flight-offers';
                
                $params = [
                    'originLocationCode' => $originCode,
                    'destinationLocationCode' => $destinationCode,
                    'departureDate' => $departureDate,
                    'returnDate' => $returnDate,
                    'adults' => 1,
                    'max' => 1, // Just get the best offer
                    'currencyCode' => 'KES'
                ];
                
                $response = $client->get($url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $access_token
                    ],
                    'query' => $params
                ]);
                
                $result = json_decode($response->getBody(), true);
                
                if (isset($result['data']) && count($result['data']) > 0) {
                    $flightOffer = $result['data'][0];
                    $price = $flightOffer['price']['total'];
                    $currency = $flightOffer['price']['currency'];
                    
                    // Format duration
                    $duration = $this->formatDuration($flightOffer['itineraries'][0]['duration']);
                    
                    // Calculate discount (simulate 15-25% off)
                    $discountPercent = rand(15, 25);
                    $originalPrice = round($price * (100 / (100 - $discountPercent)), 2);
                    
                    // Get cabin class
                    $cabin = isset($flightOffer['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin']) 
                        ? $flightOffer['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] 
                        : 'ECONOMY';
                    
                    // Get destination info
                    $destinationInfo = $this->eastAfricanDestinations[$destinationCode];
                    
                    $offers[] = [
                        'id' => $flightOffer['id'],
                        'origin' => $originCode,
                        'originCity' => 'Nairobi',
                        'destination' => $destinationCode,
                        'destinationCity' => $destinationInfo['city'],
                        'destinationCountry' => $destinationInfo['country'],
                        'destinationImage' => $destinationInfo['image'],
                        'description' => $destinationInfo['description'],
                        'highlight' => $destinationInfo['highlight'],
                        'departureDate' => $this->formatDate($flightOffer['itineraries'][0]['segments'][0]['departure']['at']),
                        'departureTime' => $this->formatTime($flightOffer['itineraries'][0]['segments'][0]['departure']['at']),
                        'returnDate' => isset($flightOffer['itineraries'][1]) ? 
                            $this->formatDate($flightOffer['itineraries'][1]['segments'][0]['departure']['at']) : 
                            null,
                        'duration' => $duration,
                        'price' => $price,
                        'originalPrice' => $originalPrice,
                        'currency' => $currency,
                        'discountPercent' => $discountPercent,
                        'cabin' => $this->formatCabin($cabin),
                        'airline' => isset($flightOffer['validatingAirlineCodes'][0]) ? 
                            $flightOffer['validatingAirlineCodes'][0] : 'KQ',
                        'stops' => count($flightOffer['itineraries'][0]['segments']) - 1
                    ];
                    
                    $totalOffers++;
                    if ($totalOffers >= $maxOffers) break;
                }
            }
            
            // If we don't have enough offers from the API, supplement with dummy data
            if ($totalOffers < $maxOffers) {
                $additionalNeeded = $maxOffers - $totalOffers;
                $dummyOffers = $this->getDummyOffers($additionalNeeded, $destinations);
                $offers = array_merge($offers, $dummyOffers);
            }
            
            // Cache the results for 1 hour to avoid repeated API calls
            Cache::put('east_african_flight_offers', $offers, now()->addHours(1));
            
            return $offers;
            
        } catch (GuzzleException $exception) {
            // In case of API error, return dummy data
            $dummyOffers = $this->getDummyOffers($maxOffers, array_keys($this->eastAfricanDestinations));
            return $dummyOffers;
        }
    }
    
    /**
     * Get dummy offers for testing or API fallback
     */
    private function getDummyOffers($count, $excludedDestinations = [])
    {
        $dummyOffers = [];
        $availableDestinations = array_diff(array_keys($this->eastAfricanDestinations), $excludedDestinations);
        
        if (empty($availableDestinations)) {
            $availableDestinations = array_keys($this->eastAfricanDestinations);
        }
        
        shuffle($availableDestinations);
        $selectedDestinations = array_slice($availableDestinations, 0, $count);
        
        foreach ($selectedDestinations as $destinationCode) {
            $destinationInfo = $this->eastAfricanDestinations[$destinationCode];
            $price = rand(15000, 45000);
            $discountPercent = rand(15, 25);
            $originalPrice = round($price * (100 / (100 - $discountPercent)), 2);
            
            $dummyOffers[] = [
                'id' => 'dummy-' . rand(1000, 9999),
                'origin' => 'NBO',
                'originCity' => 'Nairobi',
                'destination' => $destinationCode,
                'destinationCity' => $destinationInfo['city'],
                'destinationCountry' => $destinationInfo['country'],
                'destinationImage' => $destinationInfo['image'],
                'description' => $destinationInfo['description'],
                'highlight' => $destinationInfo['highlight'],
                'departureDate' => $this->formatDate(Carbon::now()->addDays(14)->toDateTimeString()),
                'departureTime' => $this->formatTime(Carbon::now()->addDays(14)->toDateTimeString()),
                'returnDate' => $this->formatDate(Carbon::now()->addDays(21)->toDateTimeString()),
                'duration' => rand(1, 3) . 'h ' . rand(0, 59) . 'm',
                'price' => $price,
                'originalPrice' => $originalPrice,
                'currency' => 'KES',
                'discountPercent' => $discountPercent,
                'cabin' => 'Economy',
                'airline' => 'KQ',
                'stops' => rand(0, 1)
            ];
        }
        
        return $dummyOffers;
    }
    
    /**
     * Format ISO duration to human readable
     */
    private function formatDuration($isoDuration)
    {
        // Parse PT3H20M format
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $isoDuration, $matches);
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        
        return $hours . 'h ' . $minutes . 'm';
    }
    
    /**
     * Format date from ISO format
     */
    private function formatDate($isoDate)
    {
        return Carbon::parse($isoDate)->format('M d, Y');
    }
    
    /**
     * Format time from ISO format
     */
    private function formatTime($isoDate)
    {
        return Carbon::parse($isoDate)->format('H:i');
    }
    
    /**
     * Format cabin class to be more readable
     */
    private function formatCabin($cabin)
    {
        $cabins = [
            'ECONOMY' => 'Economy',
            'PREMIUM_ECONOMY' => 'Premium Economy',
            'BUSINESS' => 'Business',
            'FIRST' => 'First Class'
        ];
        
        return $cabins[$cabin] ?? $cabin;
    }

   
    
}
