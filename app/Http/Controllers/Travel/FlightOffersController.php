<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use App\Models\User;
use App\Jobs\SendBookingConfirmationJob;
use App\Jobs\SendPreTripReminderJob;
use App\Mail\BookingConfirmation;
use App\Mail\AdminBookingNotification;
use App\Mail\LoyaltyPointsUpdateMail;


class FlightOffersController extends Controller
{

    /**
 * Handle booking for a deal flight offer
 * 
 * @param Request $request
 * @return \Illuminate\Http\Response
 */
public function bookDealFlight(Request $request)
{
    // Validate request
    $validator = Validator::make($request->all(), [
        'flight_data' => 'required|string',
        '_token' => 'required'
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
    }

    try {
        // Decode flight data from the form submission
        $flightData = json_decode($request->input('flight_data'), true);
        
        if (!$flightData) {
            return redirect()->back()->with([
                'error' => 'Invalid flight data format'
            ]);
        }
        
        // Convert the deal offer into a format compatible with flight search results
        $convertedFlight = $this->convertOfferToSearchResult($flightData);
        
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store flight data in session for after login
            session(['pending_flight_booking' => $convertedFlight]);
            
            return redirect()->route('login')->with([
                'message' => 'Please login or create an account to proceed with booking.',
                'alert_type' => 'info'
            ]);
        }

        // User is authenticated, proceed to flight results page
        return $this->showFlightResultForOffer($convertedFlight);

    } catch (\Exception $e) {
        return redirect()->back()->with([
            'error' => 'An error occurred: ' . $e->getMessage()
        ]);
    }
}

/**
 * Convert a flight offer to a format compatible with flight search results
 * 
 * @param array $offer
 * @return array
 */
private function convertOfferToSearchResult($offer)
{
    // Generate a unique flight ID
    $flightId = 'offer-' . $offer['destination'] . '-' . substr(md5(json_encode($offer)), 0, 8);
    
    // Create segments for the flight itinerary
    $departureSegment = [
        'departure' => [
            'iataCode' => $offer['origin'],
            'terminal' => '1', // Default terminal
            'at' => $this->formatDateForAPI($offer['departureDate'], $offer['departureTime'] ?? '08:00')
        ],
        'arrival' => [
            'iataCode' => $offer['destination'],
            'terminal' => '1', // Default terminal
            'at' => $this->calculateArrivalTime($offer['departureDate'], $offer['departureTime'] ?? '08:00', $offer['duration'])
        ],
        'carrierCode' => $offer['airline'] ?? 'KQ',
        'number' => $this->generateFlightNumber($offer['airline'] ?? 'KQ'),
        'aircraft' => [
            'code' => 'B737'  // Default aircraft
        ],
        'duration' => $this->formatDurationForAPI($offer['duration']),
        'id' => '1'
    ];
    
    // If we have return date, create return segment
    $returnSegment = null;
    if (!empty($offer['returnDate'])) {
        $returnSegment = [
            'departure' => [
                'iataCode' => $offer['destination'],
                'terminal' => '1',
                'at' => $this->formatDateForAPI($offer['returnDate'], '10:00') // Default return time
            ],
            'arrival' => [
                'iataCode' => $offer['origin'],
                'terminal' => '1',
                'at' => $this->calculateArrivalTime($offer['returnDate'], '10:00', $offer['duration'])
            ],
            'carrierCode' => $offer['airline'] ?? 'KQ',
            'number' => $this->generateFlightNumber($offer['airline'] ?? 'KQ'),
            'aircraft' => [
                'code' => 'B737'
            ],
            'duration' => $this->formatDurationForAPI($offer['duration']),
            'id' => '2'
        ];
    }
    
    // Create the itineraries
    $itineraries = [
        [
            'duration' => $this->formatDurationForAPI($offer['duration']),
            'segments' => [$departureSegment]
        ]
    ];
    
    if ($returnSegment) {
        $itineraries[] = [
            'duration' => $this->formatDurationForAPI($offer['duration']),
            'segments' => [$returnSegment]
        ];
    }
    
    // Create price object
    $price = [
        'currency' => $offer['currency'],
        'total' => (string)$offer['price'],
        'base' => (string)($offer['price'] * 0.85), // Estimate base fare as 85% of total
        'fees' => [
            [
                'amount' => (string)($offer['price'] * 0.10),
                'type' => 'SUPPLIER'
            ],
            [
                'amount' => (string)($offer['price'] * 0.05),
                'type' => 'TICKETING'
            ]
        ],
        'grandTotal' => (string)$offer['price']
    ];
    
    // Create traveler pricing
    $travelerPricing = [
        [
            'travelerId' => '1',
            'fareOption' => 'STANDARD',
            'travelerType' => 'ADULT',
            'price' => [
                'currency' => $offer['currency'],
                'total' => (string)$offer['price'],
                'base' => (string)($offer['price'] * 0.85)
            ],
            'fareDetailsBySegment' => [
                [
                    'segmentId' => '1',
                    'cabin' => strtoupper(preg_replace('/\s+/', '_', $offer['cabin'] ?? 'ECONOMY')),
                    'fareBasis' => 'ELIGHT',
                    'class' => 'E',
                    'includedCheckedBags' => [
                        'quantity' => 1
                    ]
                ]
            ]
        ]
    ];
    
    if ($returnSegment) {
        $travelerPricing[0]['fareDetailsBySegment'][] = [
            'segmentId' => '2',
            'cabin' => strtoupper(preg_replace('/\s+/', '_', $offer['cabin'] ?? 'ECONOMY')),
            'fareBasis' => 'ELIGHT',
            'class' => 'E',
            'includedCheckedBags' => [
                'quantity' => 1
            ]
        ];
    }
    
    // Create dictionaries for carriers, aircraft, locations, etc.
    $dictionaries = [
        'carriers' => [
            $offer['airline'] => $this->getAirlineName($offer['airline'])
        ],
        'aircraft' => [
            'B737' => 'BOEING 737'
        ],
        'locations' => [
            $offer['origin'] => [
                'cityCode' => $offer['origin'],
                'countryCode' => $this->getCountryCodeFromDestination($offer['origin'])
            ],
            $offer['destination'] => [
                'cityCode' => $offer['destination'],
                'countryCode' => $this->getCountryCodeFromDestination($offer['destination'])
            ]
        ]
    ];
    
    // Create the final flight object
    $flight = [
        'type' => 'flight-offer',
        'id' => $flightId,
        'source' => 'East African Deals',
        'instantTicketingRequired' => false,
        'nonHomogeneous' => false,
        'oneWay' => empty($offer['returnDate']),
        'lastTicketingDate' => date('Y-m-d', strtotime('+7 days')),
        'numberOfBookableSeats' => 9,
        'itineraries' => $itineraries,
        'price' => $price,
        'pricingOptions' => [
            'fareType' => ['PUBLISHED'],
            'includedCheckedBagsOnly' => true
        ],
        'validatingAirlineCodes' => [$offer['airline'] ?? 'KQ'],
        'travelerPricings' => $travelerPricing,
        'dictionaries' => $dictionaries,
        // Include the original offer data for reference
        'originalOffer' => $offer
    ];
    
    return $flight;
}

/**
 * Format a date and time for API use
 * 
 * @param string $date
 * @param string $time
 * @return string
 */
private function formatDateForAPI($date, $time)
{
    // Convert date formats like "Aug 15, 2023" to "2023-08-15"
    $dateObj = Carbon::parse($date);
    
    // Format as ISO 8601
    return $dateObj->format('Y-m-d') . 'T' . $time . ':00';
}

/**
 * Calculate arrival time based on departure and duration
 * 
 * @param string $departureDate
 * @param string $departureTime
 * @param string $duration
 * @return string
 */
private function calculateArrivalTime($departureDate, $departureTime, $duration)
{
    // Parse the duration (e.g., "1h 30m")
    preg_match('/(\d+)h\s*(?:(\d+)m)?/', $duration, $matches);
    $hours = isset($matches[1]) ? (int)$matches[1] : 0;
    $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
    
    // Parse departure date and time
    $departure = Carbon::parse($this->formatDateForAPI($departureDate, $departureTime));
    
    // Add duration
    $arrival = $departure->copy()->addHours($hours)->addMinutes($minutes);
    
    // Format as ISO 8601
    return $arrival->format('Y-m-d\TH:i:00');
}

/**
 * Format duration for API use (convert "1h 30m" to PT1H30M)
 * 
 * @param string $duration
 * @return string
 */
private function formatDurationForAPI($duration)
{
    // Parse the duration (e.g., "1h 30m")
    preg_match('/(\d+)h\s*(?:(\d+)m)?/', $duration, $matches);
    $hours = isset($matches[1]) ? (int)$matches[1] : 0;
    $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
    
    return 'PT' . $hours . 'H' . $minutes . 'M';
}

/**
 * Generate a random flight number for the given airline code
 * 
 * @param string $airlineCode
 * @return string
 */
private function generateFlightNumber($airlineCode)
{
    return $airlineCode . rand(100, 999);
}

/**
 * Get airline name from airline code
 * 
 * @param string $airlineCode
 * @return string
 */
private function getAirlineName($airlineCode)
{
    $airlines = [
        'KQ' => 'Kenya Airways',
        'ET' => 'Ethiopian Airlines',
        'PW' => 'Precision Air',
        'WB' => 'Rwandair',
        'TL' => 'Tanzania Airways',
        'UG' => 'Uganda Airlines',
        'RW' => 'Rwandair'
    ];
    
    return $airlines[$airlineCode] ?? 'Unknown Airline (' . $airlineCode . ')';
}

/**
 * Get country code from destination code
 * 
 * @param string $destinationCode
 * @return string
 */
private function getCountryCodeFromDestination($destinationCode)
{
    $countryCodes = [
        // Kenya
        'NBO' => 'KE', 'MBA' => 'KE', 'KIS' => 'KE', 'EDL' => 'KE',
        // Tanzania
        'DAR' => 'TZ', 'ZNZ' => 'TZ', 'JRO' => 'TZ', 'ARK' => 'TZ', 'DOD' => 'TZ',
        // Uganda
        'EBB' => 'UG', 'JIN' => 'UG',
        // Rwanda
        'KGL' => 'RW',
        // Ethiopia
        'ADD' => 'ET'
    ];
    
    return $countryCodes[$destinationCode] ?? 'XX';
}

/**
 * Show flight result for a deal offer
 * 
 * @param array $convertedFlight
 * @return \Illuminate\Http\Response
 */
public function showFlightResultForOffer($convertedFlight)
{
    // Create a collection with just this flight for the results page
    $flights = collect([$convertedFlight]);
    
    // Get origin and destination for the page title
    $originCity = $convertedFlight['originalOffer']['originCity'] ?? 'Origin';
    $destinationCity = $convertedFlight['originalOffer']['destinationCity'] ?? 'Destination';
    
    // Set page title
    $pageTitle = "Flights from {$originCity} to {$destinationCity}";
    
    // Set offer mode flag to true for the view
    $isOfferMode = true;
    
    // Pass the flight to the flight-results view
    return view('flight.flight-results', compact('flights', 'pageTitle', 'isOfferMode'));
}

/**
 * Create a route for booking flight offers
 */
public function setupRoutes()
{
    // This is just for documentation - add this to your routes file
    // Route::post('/book-flight', [FlightOffersController::class, 'bookDealFlight'])->name('book.deal-flight');
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

    /**
     * Book a selected flight
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bookFlight(Request $request)
    {
        $flightData = json_decode($request->input('flight_data'), true);
        
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store flight data in session for after login
            session(['pending_flight_booking' => $flightData]);
            
            return redirect()->route('login')->with([
                'message' => 'Please login or create an account to proceed with booking.',
                'alert_type' => 'info'
            ]);
        }

        // User is authenticated, proceed to booking
        session(['selected_flight' => $flightData]);
        
        return redirect()->route('booking.passenger-details', ['type' => 'flight'])->with([
            'message' => 'Flight selected successfully. Please provide passenger details.',
            'alert_type' => 'success'
        ]);
    }

    /**
     * Show passenger details form
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showPassengerDetailsForm(Request $request)
    {
        $type = $request->route('type');
        $bookingData = session("selected_{$type}");
        
        if (!$bookingData) {
            return redirect()->route('home')->with([
                'message' => 'No ' . $type . ' selected. Please search and select a ' . $type . ' first.',
                'alert_type' => 'warning'
            ]);
        }

        return view('booking.passenger-details', compact('bookingData', 'type'));
    }

    /**
     * Save passenger details and proceed to payment
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function savePassengerDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_type' => 'required|in:flight,hotel',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'passengers.*.first_name' => 'required|string|max:100',
            'passengers.*.last_name' => 'required|string|max:100',
            'passengers.*.dob' => 'required|date|before:today',
            'passengers.*.nationality' => 'required|string|max:100',
            'passengers.*.passport_number' => 'required_if:booking_type,flight|string|max:50',
            'passengers.*.passport_expiry' => 'required_if:booking_type,flight|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store passenger details in session
        session([
            'passenger_details' => $request->passengers,
            'contact_details' => [
                'email' => $request->contact_email,
                'phone' => $request->contact_phone
            ]
        ]);

        return redirect()->route('booking.payment', ['type' => $request->booking_type]);
    }

    /**
     * Show payment page
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showPaymentPage(Request $request)
    {
        $type = $request->route('type');
        $bookingData = session("selected_{$type}");
        $passengerDetails = session('passenger_details');
        $contactDetails = session('contact_details');
        
        if (!$bookingData || !$passengerDetails || !$contactDetails) {
            return redirect()->route('home')->with([
                'message' => 'Booking information missing. Please start again.',
                'alert_type' => 'warning'
            ]);
        }

        // Apply any available discounts
        $user = Auth::user();
        $originalPrice = $bookingData['price'];
        $finalPrice = $originalPrice;
        $discountInfo = null;

        // Check for referral discounts
        if ($user && method_exists($this, 'applyReferralDiscount')) {
            $discountResult = $this->applyReferralDiscount($user->id, $originalPrice);
            if ($discountResult['discount'] > 0) {
                $finalPrice = $discountResult['final_amount'];
                $discountInfo = [
                    'amount' => $discountResult['discount'],
                    'type' => 'Referral',
                    'description' => 'Discount from referral by ' . ($discountResult['referrer_name'] ?? 'a friend')
                ];
            }
        }

        // Check for loyalty discounts
        if ($user && $user->loyalty_points >= 1000) {
            $potentialDiscount = floor($user->loyalty_points / 100); // 10 points = 1 currency unit
            $maxDiscount = $originalPrice * 0.25; // Maximum 25% discount
            $loyaltyDiscount = min($potentialDiscount, $maxDiscount);
            
            // If loyalty discount is better than referral, use it
            if (!$discountInfo || $loyaltyDiscount > $discountInfo['amount']) {
                $finalPrice = $originalPrice - $loyaltyDiscount;
                $discountInfo = [
                    'amount' => $loyaltyDiscount,
                    'type' => 'Loyalty',
                    'description' => 'Discount from ' . $user->loyalty_points . ' loyalty points',
                    'points_required' => $loyaltyDiscount * 100 // 100 points per currency unit
                ];
            }
        }

        return view('booking.payment', compact('bookingData', 'type', 'passengerDetails', 'contactDetails', 'originalPrice', 'finalPrice', 'discountInfo'));
    }

    /**
     * Process payment
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_type' => 'required|in:flight,hotel',
            'payment_method' => 'required|in:mpesa,bank,card',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'mpesa_phone' => 'required_if:payment_method,mpesa|string',
            'bank_account' => 'required_if:payment_method,bank|string',
            'bank_name' => 'required_if:payment_method,bank|string',
            'card_number' => 'required_if:payment_method,card|string|size:16',
            'card_expiry' => 'required_if:payment_method,card|string',
            'card_cvv' => 'required_if:payment_method,card|string|size:3',
            'discount_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $type = $request->booking_type;
            $bookingData = session("selected_{$type}");
            $passengerDetails = session('passenger_details');
            $contactDetails = session('contact_details');
            
            if (!$bookingData || !$passengerDetails || !$contactDetails) {
                return redirect()->route('home')->with([
                    'message' => 'Booking information missing. Please start again.',
                    'alert_type' => 'warning'
                ]);
            }

            // Create booking reference
            $bookingReference = $this->generateBookingReference();

            // Process loyalty points redemption if applicable
            if ($request->discount_type === 'Loyalty' && $request->has('points_to_redeem')) {
                $pointsToRedeem = (int)$request->points_to_redeem;
                if ($user->loyalty_points >= $pointsToRedeem) {
                    $user->decrement('loyalty_points', $pointsToRedeem);
                }
            }

            // Create the booking record
            $booking = $this->createBookingRecord($user, $bookingData, $type, $bookingReference, $request, $passengerDetails, $contactDetails);

            // Process the payment
            $paymentResult = $this->processPaymentTransaction($booking, $request);

            if ($paymentResult['success']) {
                // Update booking status
                $booking->update([
                    'payment_status' => 'completed',
                    'status' => 'confirmed',
                    'transaction_id' => $paymentResult['transaction_id']
                ]);

                // Clear booking session data
                session()->forget([
                    "selected_{$type}",
                    'passenger_details',
                    'contact_details'
                ]);

                // Dispatch confirmation job
                SendBookingConfirmationJob::dispatch($booking);

                // Schedule pre-trip reminders (only for flights)
                if ($type === 'flight') {
                    $this->schedulePreTripReminders($booking);
                }

                // Award loyalty points
                $this->awardLoyaltyPoints($booking);

                return redirect()->route('booking.success', $booking->booking_reference);
            } else {
                $booking->update(['payment_status' => 'failed']);
                
                // Store failure details in session
                session([
                    'failure_reason' => $paymentResult['message'],
                    'error_code' => $paymentResult['error_code'] ?? 'PAYMENT_FAILED',
                    'suggested_action' => 'Please check your payment details and try again',
                    'failed_booking_data' => $booking->toArray(),
                    'failed_payment_method' => $request->input('payment_method'),
                    'failed_amount' => $booking->amount,
                    'failed_currency' => $booking->currency,
                    'failed_booking_reference' => $booking->booking_reference,
                ]);

                return redirect()->route('booking.failure')->with('error', 'Payment failed: ' . $paymentResult['message']);
            }

        } catch (\Exception $e) {
            // Store failure details in session
            session([
                'failure_reason' => $e->getMessage(),
                'error_code' => 'BOOKING_FAILED',
                'suggested_action' => 'Please try again or contact support',
                'failed_payment_method' => $request->input('payment_method'),
                'failed_amount' => $request->input('amount'),
                'failed_currency' => $request->input('currency'),
                'failed_booking_reference' => $bookingReference ?? 'N/A',
            ]);

            return redirect()->route('booking.failure')->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    /**
     * Create booking record
     */
    private function createBookingRecord($user, $bookingData, $bookingType, $bookingReference, $request, $passengerDetails, $contactDetails)
    {
        $bookingFields = [
            'user_id' => $user->id,
            'booking_reference' => $bookingReference,
            'booking_type' => $bookingType,
            'amount' => $request->input('amount'),
            'currency' => $request->input('currency'),
            'status' => 'pending',
            'payment_method' => $request->input('payment_method'),
            'payment_status' => 'pending',
            'contact_email' => $contactDetails['email'],
            'contact_phone' => $contactDetails['phone'],
            'passengers' => json_encode($passengerDetails),
        ];

        if ($bookingType === 'flight') {
            $bookingFields = array_merge($bookingFields, [
                'flight_number' => $bookingData['flight_number'] ?? null,
                'departure_airport' => $bookingData['origin'] ?? null,
                'arrival_airport' => $bookingData['destination'] ?? null,
                'departure_time' => $bookingData['departureTime'] ?? null,
                'arrival_time' => null, // We may not have this in the basic offer data
                'flight_details' => json_encode($bookingData),
                'adults' => count($passengerDetails),
                'children' => 0, // Add children count if we have it
                'travel_class' => $bookingData['cabin'] ?? null,
            ]);
        } else {
            $bookingFields = array_merge($bookingFields, [
                'hotel_id' => $bookingData['id'] ?? null,
                'check_in_date' => $bookingData['availableDate'] ?? null,
                'check_out_date' => null, // We may need to calculate this
                'hotel_details' => json_encode($bookingData),
                'adults' => count($passengerDetails),
                'children' => 0, // Add children count if we have it
            ]);
        }

        return Booking::create($bookingFields);
    }

    /**
     * Process payment transaction
     */
    private function processPaymentTransaction($booking, $request)
    {
        try {
            $paymentMethod = $request->payment_method;
            
            // For demonstration, always return success
            // In a real application, you would integrate with actual payment gateways
            return [
                'success' => true,
                'transaction_id' => strtoupper($paymentMethod) . '_' . time() . '_' . rand(1000, 9999),
                'message' => ucfirst($paymentMethod) . ' payment successful'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error_code' => 'PAYMENT_ERROR'
            ];
        }
    }

    /**
     * Schedule pre-trip reminders
     */
    private function schedulePreTripReminders($booking)
    {
        if (!$booking->departure_time) {
            return;
        }

        $departureTime = Carbon::parse($booking->departure_time);
        
        // Only schedule if departure is in the future
        if ($departureTime->isFuture()) {
            // Schedule 24-hour reminder
            if ($departureTime->diffInHours(now()) > 24) {
                SendPreTripReminderJob::dispatch($booking, '24_hours')
                    ->delay($departureTime->copy()->subHours(24));
            }
            
            // Schedule 2-hour reminder
            if ($departureTime->diffInHours(now()) > 2) {
                SendPreTripReminderJob::dispatch($booking, '2_hours')
                    ->delay($departureTime->copy()->subHours(2));
            }
            
            // Schedule check-in reminder (24 hours before departure)
            if ($departureTime->diffInHours(now()) > 24) {
                SendPreTripReminderJob::dispatch($booking, 'check_in')
                    ->delay($departureTime->copy()->subHours(24));
            }
        }
    }

    /**
     * Award loyalty points
     */
    private function awardLoyaltyPoints($booking)
    {
        if ($booking->user_id) {
            $user = User::find($booking->user_id);
            $pointsEarned = floor($booking->amount * 0.1); // 10% of price as points
            
            // Update user's loyalty points
            $user->increment('loyalty_points', $pointsEarned);
            
            // Send loyalty points update email (if class exists)
            if (class_exists('App\Mail\LoyaltyPointsUpdateMail')) {
                Mail::to($user->email)->queue(new LoyaltyPointsUpdateMail(
                    $user, 
                    $pointsEarned, 
                    $user->loyalty_points, 
                    $booking
                ));
            }
        }
    }

    /**
     * Show booking success page
     */
    public function bookingSuccess($bookingReference)
    {
        $booking = Booking::where('booking_reference', $bookingReference)->firstOrFail();
        
        // Check if user can access this booking
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        return view('booking.accepted', compact('booking'));
    }

    /**
     * Show booking failure page
     */
    public function bookingFailure()
    {
        return view('booking.bad', [
            'failure_reason' => session('failure_reason', 'Payment processing failed'),
            'error_code' => session('error_code', 'PAYMENT_ERROR'),
            'suggested_action' => session('suggested_action', 'Please try again or contact support'),
            'payment_method' => session('failed_payment_method', 'Unknown'),
            'amount' => session('failed_amount', '0.00'),
            'currency' => session('failed_currency', 'USD'),
            'booking_reference' => session('failed_booking_reference', 'N/A'),
        ]);
    }

    /**
     * Generate booking reference
     */
    private function generateBookingReference()
    {
        return 'Ziara' . strtoupper(substr(uniqid(), -6)) . '-' . date('ymd');
    }

    /**
     * Apply referral discount
     */
    public function applyReferralDiscount($userId, $bookingAmount)
    {
        // This would normally check for real referral data
        // For demonstration, return no discount
        return [
            'discount' => 0,
            'final_amount' => $bookingAmount
        ];
    }
    
    /**
     * Show flight deals results page
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showFlightDealsResults(Request $request)
    {
        $highlightId = $request->input('highlight_id');
        $flightDeals = $this->getEastAfricanOffers($request, new Client());
        
        // If a specific deal should be highlighted
        if ($highlightId) {
            foreach ($flightDeals as $key => $deal) {
                if ($deal['id'] == $highlightId) {
                    // Move this deal to the top of the list
                    $highlightedDeal = $flightDeals[$key];
                    unset($flightDeals[$key]);
                    array_unshift($flightDeals, $highlightedDeal);
                    break;
                }
            }
        }
        
        return view('deals.flight-deals-results', [
            'flightDeals' => $flightDeals,
            'highlightId' => $highlightId,
            'pageTitle' => 'Flight Deals'
        ]);
    }

    /**
     * Show flight deal booking page
     * 
     * @param Request $request
     * @param string $dealId
     * @return \Illuminate\Http\Response
     */
    public function showFlightDealBooking(Request $request, $dealId)
    {
        // Get all available flight deals
        $flightDeals = $this->getEastAfricanOffers($request, new Client());
        
        // Find the requested deal
        $selectedDeal = null;
        foreach ($flightDeals as $deal) {
            if ($deal['id'] == $dealId) {
                $selectedDeal = $deal;
                break;
            }
        }
        
        if (!$selectedDeal) {
            return redirect()->route('deal.flight.results')
                ->with('error', 'The selected flight deal was not found.');
        }
        
        // Get user information if logged in
        $user = Auth::user();
        
        return view('deals.flight-deal-booking', [
            'deal' => $selectedDeal,
            'user' => $user,
            'pageTitle' => 'Book Flight Deal - ' . $selectedDeal['originCity'] . ' to ' . $selectedDeal['destinationCity']
        ]);
    }

    /**
     * Show flight deal checkout page
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function showFlightDealCheckout(Request $request, $reference)
    {
        // Find the booking by reference
        $booking = Booking::where('booking_reference', $reference)->firstOrFail();
        
        // Get flight details from booking
        $flightData = json_decode($booking->flight_details, true);
        $passengerDetails = json_decode($booking->passenger_details, true);
        $contactDetails = json_decode($booking->contact_details, true);
        
        return view('deals.flight-deal-checkout', [
            'booking' => $booking,
            'flightData' => $flightData,
            'passengerDetails' => $passengerDetails,
            'contactDetails' => $contactDetails,
            'pageTitle' => 'Complete Your Flight Booking'
        ]);
    }

    /**
     * Process payment for flight deal
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function processFlightDealPayment(Request $request, $reference)
    {
        // Validate the payment information
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'terms_agreed' => 'required|accepted'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Find the booking
        $booking = Booking::where('booking_reference', $reference)->firstOrFail();
        
        try {
            // Process payment based on selected method
            $paymentMethod = $request->input('payment_method');
            $paymentSuccess = false;
            
            switch ($paymentMethod) {
                case 'credit_card':
                    // Process credit card payment
                    $paymentSuccess = true; // Simulated for now
                    break;
                    
                case 'mpesa':
                    // Process M-Pesa payment
                    $paymentSuccess = true; // Simulated for now
                    break;
                    
                case 'paypal':
                    // Process PayPal payment
                    $paymentSuccess = true; // Simulated for now
                    break;
                    
                default:
                    return redirect()->back()->with('error', 'Invalid payment method selected.');
            }
            
            if ($paymentSuccess) {
                // Update booking status
                $booking->payment_status = 'completed';
                $booking->status = 'confirmed';
                $booking->payment_date = now();
                $booking->save();
                
                // Send confirmation to user
                if (Auth::check() || $booking->contact_details) {
                    dispatch(new SendBookingConfirmationJob($booking));
                }
                
                // Update user dashboard and alert admins
                event(new \App\Events\BookingUpdated($booking));
                
                return redirect()->route('user.dashboard')
                    ->with('success', 'Payment successful! Your flight booking has been confirmed.');
            } else {
                return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your payment: ' . $e->getMessage());
        }
    }

     /**
     * Show flight deals results page
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showResults(Request $request)
    {
        $highlightId = $request->input('highlight_id');
        $flightDeals = $this->flightOffersController->getEastAfricanOffers($request, new Client());
        
        // If a specific deal should be highlighted
        if ($highlightId) {
            foreach ($flightDeals as $key => $deal) {
                if ($deal['id'] == $highlightId) {
                    // Move this deal to the top of the list
                    $highlightedDeal = $flightDeals[$key];
                    unset($flightDeals[$key]);
                    array_unshift($flightDeals, $highlightedDeal);
                    break;
                }
            }
        }
        
        return view('deals.flight-deals-results', [
            'flightDeals' => $flightDeals,
            'highlightId' => $highlightId,
            'pageTitle' => 'Flight Deals'
        ]);
    }

    /**
     * Show flight deal booking page
     * 
     * @param Request $request
     * @param string $dealId
     * @return \Illuminate\Http\Response
     */
    public function showBooking(Request $request, $dealId)
    {
        // Get all available flight deals
        $flightDeals = $this->flightOffersController->getEastAfricanOffers($request, new Client());
        
        // Find the requested deal
        $selectedDeal = null;
        foreach ($flightDeals as $deal) {
            if ($deal['id'] == $dealId) {
                $selectedDeal = $deal;
                break;
            }
        }
        
        if (!$selectedDeal) {
            return redirect()->route('deal.flight.results')
                ->with('error', 'The selected flight deal was not found.');
        }
        
        // Get user information if logged in
        $user = Auth::user();
        
        return view('deals.flight-deal-booking', [
            'deal' => $selectedDeal,
            'user' => $user,
            'pageTitle' => 'Book Flight Deal - ' . $selectedDeal['originCity'] . ' to ' . $selectedDeal['destinationCity']
        ]);
    }

    /**
     * Handle booking for a flight deal
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bookDeal(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            '_token' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Process the flight booking
            $booking = new Booking();
            $booking->user_id = Auth::check() ? Auth::id() : null;
            $booking->type = 'flight';
            $booking->status = 'processing';
            $booking->booking_reference = 'FD-'.strtoupper(substr(uniqid(), 0, 8));
            $booking->booking_date = now();
            $booking->total_amount = $request->input('total_amount');
            $booking->currency = $request->input('currency', 'KES');
            $booking->payment_status = 'pending';
            $booking->flight_details = json_encode($request->input('flight_data'));
            $booking->passenger_details = json_encode($request->input('passenger_details'));
            $booking->contact_details = json_encode($request->input('contact_details'));
            $booking->save();

            // Redirect to payment page
            return redirect()->route('deal.flight.checkout', ['reference' => $booking->booking_reference])
                ->with('success', 'Flight reservation created successfully. Please complete your payment.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show flight deal checkout page
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function showCheckout(Request $request, $reference)
    {
        // Find the booking by reference
        $booking = Booking::where('booking_reference', $reference)->firstOrFail();
        
        // Get flight details from booking
        $flightData = json_decode($booking->flight_details, true);
        $passengerDetails = json_decode($booking->passenger_details, true);
        $contactDetails = json_decode($booking->contact_details, true);
        
        return view('deals.flight-deal-checkout', [
            'booking' => $booking,
            'flightData' => $flightData,
            'passengerDetails' => $passengerDetails,
            'contactDetails' => $contactDetails,
            'pageTitle' => 'Complete Your Flight Booking'
        ]);
    }

    /**
     * Process payment for flight deal
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    // public function processPayment(Request $request, $reference)
    // {
    //     // Validate the payment information
    //     $validator = Validator::make($request->all(), [
    //         'payment_method' => 'required|string',
    //         'terms_agreed' => 'required|accepted'
    //     ]);
        
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
        
    //     // Find the booking
    //     $booking = Booking::where('booking_reference', $reference)->firstOrFail();
        
    //     try {
    //         // Process payment based on selected method
    //         $paymentMethod = $request->input('payment_method');
    //         $paymentSuccess = false;
            
    //         switch ($paymentMethod) {
    //             case 'credit_card':
    //                 // Process credit card payment
    //                 $paymentSuccess = true; // Simulated for now
    //                 break;
                    
    //             case 'mpesa':
    //                 // Process M-Pesa payment
    //                 $paymentSuccess = true; // Simulated for now
    //                 break;
                    
    //             case 'paypal':
    //                 // Process PayPal payment
    //                 $paymentSuccess = true; // Simulated for now
    //                 break;
                    
    //             default:
    //                 return redirect()->back()->with('error', 'Invalid payment method selected.');
    //         }
            
    //         if ($paymentSuccess) {
    //             // Update booking status
    //             $booking->payment_status = 'completed';
    //             $booking->status = 'confirmed';
    //             $booking->payment_date = now();
    //             $booking->save();
                
    //             // Send confirmation to user
    //             if (Auth::check() || $booking->contact_details) {
    //                 dispatch(new SendBookingConfirmationJob($booking));
    //             }
                
    //             // Update user dashboard and alert admins
    //             event(new BookingUpdated($booking));
                
    //             return redirect()->route('user.dashboard')
    //                 ->with('success', 'Payment successful! Your flight booking has been confirmed.');
    //         } else {
    //             return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
    //         }
            
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'There was a problem processing your payment: ' . $e->getMessage());
    //     }
    // }
}
