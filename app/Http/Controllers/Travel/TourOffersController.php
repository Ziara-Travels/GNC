<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\Tour;
use App\Models\CustomTrip;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TourOffersController extends Controller
{
    protected $client;
    protected $baseUrl;
    protected $tokenUrl;
    protected $clientId;
    protected $clientSecret;
    protected $token;
    protected $tokenExpiresAt;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->baseUrl = config('services.amadeus.base_url');
        $this->tokenUrl = config('services.amadeus.token_url');
        $this->clientId = config('services.amadeus.client_id');
        $this->clientSecret = config('services.amadeus.client_secret');
    }

    /**
     * Display tours index page
     */
    public function index(Request $request)
    {
        $query = Tour::active();
        
        // Apply filters
        if ($request->filled('city')) {
            $query->byCity($request->city);
        }
        
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'duration':
                $query->orderBy('duration_hours', 'asc');
                break;
            default:
                $query->orderBy('featured', 'desc')->orderBy('rating', 'desc');
        }
        
        $perPage = $request->get('per_page', 12);
        $tours = $query->paginate($perPage);
        
        // Get filter options for sidebar
        $cities = Tour::active()->distinct('city')->pluck('city');
        $categories = Tour::active()->distinct('category')->pluck('category');
        
        return view('tours.index', compact('tours', 'cities', 'categories'));
    }

    /**
     * Search tours
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('q');
        $category = $request->get('category');
        $city = $request->get('city');
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        
        $query = Tour::active();
        
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('short_description', 'like', "%{$searchTerm}%")
                ->orWhere('full_description', 'like', "%{$searchTerm}%");
            });
        }
        
        if ($category) {
            $query->where('category', $category);
        }
        
        if ($city) {
            $query->where('city', $city);
        }
        
        if ($priceMin) {
            $query->where('price', '>=', $priceMin);
        }
        
        if ($priceMax) {
            $query->where('price', '<=', $priceMax);
        }
        
        $tours = $query->paginate(12);
        
        // Get filter options for sidebar
        $cities = Tour::active()->distinct('city')->pluck('city');
        $categories = Tour::active()->distinct('category')->pluck('category');
        
        return view('tours.search', compact('tours', 'cities', 'categories', 'searchTerm'));
    }

    /**
     * Get an access token from Amadeus API
     */
    private function getAccessToken()
    {
        if (Cache::has('amadeus_token')) {
            return Cache::get('amadeus_token');
        }

        try {
            $response = $this->client->request('POST', $this->tokenUrl, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                ]
            ]);
            
            $tokenData = json_decode($response->getBody()->getContents(), true);
            $expiresIn = $tokenData['expires_in'] ?? 1800;
            
            // Cache the token for a bit less than its expiry time
            Cache::put('amadeus_token', $tokenData['access_token'], now()->addSeconds($expiresIn - 60));
            
            return $tokenData['access_token'];
        } catch (GuzzleException $e) {
            // Log error and return null
            \Log::error('Failed to get Amadeus token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get activities for east African cities
     */
    public function getEastAfricanActivities()
    {
        // East African major cities with coordinates
        $cities = [
            'Nairobi' => ['latitude' => '-1.286389', 'longitude' => '36.817223', 'country' => 'Kenya'],
            'Mombasa' => ['latitude' => '-4.043740', 'longitude' => '39.658871', 'country' => 'Kenya'],
            'Zanzibar' => ['latitude' => '-6.165917', 'longitude' => '39.202641', 'country' => 'Tanzania'],
            'Dar es Salaam' => ['latitude' => '-6.792354', 'longitude' => '39.208328', 'country' => 'Tanzania'],
            'Arusha' => ['latitude' => '-3.386925', 'longitude' => '36.683192', 'country' => 'Tanzania'],
            'Kampala' => ['latitude' => '0.347596', 'longitude' => '32.582520', 'country' => 'Uganda'],
            'Kigali' => ['latitude' => '-1.970579', 'longitude' => '30.104429', 'country' => 'Rwanda'],
            'Addis Ababa' => ['latitude' => '9.005401', 'longitude' => '38.763611', 'country' => 'Ethiopia']
        ];

        // Check if we have cached results
        if (Cache::has('east_african_activities')) {
            return response()->json(Cache::get('east_african_activities'));
        }

        $token = $this->getAccessToken();
        $activities = [];

        foreach ($cities as $cityName => $coordinates) {
            try {
                // Get activities by geolocation
                if ($token) {
                    $response = $this->client->request('GET', $this->baseUrl . '/shopping/activities', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $token
                        ],
                        'query' => [
                            'latitude' => $coordinates['latitude'],
                            'longitude' => $coordinates['longitude'],
                            'radius' => 20
                        ]
                    ]);
                    
                    $cityActivities = json_decode($response->getBody()->getContents(), true);
                    
                    // If we have results, save the first 3 activities
                    if (isset($cityActivities['data']) && count($cityActivities['data']) > 0) {
                        $activities[$cityName] = array_slice($cityActivities['data'], 0, 3);
                        
                        // Add city info to each activity
                        foreach ($activities[$cityName] as $key => $activity) {
                            $activities[$cityName][$key]['city'] = $cityName;
                            $activities[$cityName][$key]['country'] = $coordinates['country'];
                            
                            // Use fallback images if necessary
                            if (empty($activity['pictures']) || !isset($activity['pictures'][0])) {
                                $activities[$cityName][$key]['pictures'] = ["/images/travel/{$this->formatCityName($cityName)}.jpg"];
                            }
                        }
                        continue;
                    }
                }
                
                // If token is invalid or no results, use dummy activities
                $activities[$cityName] = $this->getDummyActivitiesForCity($cityName, $coordinates['country']);
                
            } catch (\Exception $e) {
                // If API fails, use dummy activities
                $activities[$cityName] = $this->getDummyActivitiesForCity($cityName, $coordinates['country']);
            }
        }

        // Cache results for 6 hours
        Cache::put('east_african_activities', $activities, now()->addHours(6));
        
        return response()->json($activities);
    }

    /**
     * Helper to format city name for image paths
     */
    private function formatCityName($cityName)
    {
        return strtolower(str_replace(' ', '-', $cityName));
    }
    
    /**
     * Get dummy activities for a city when API fails
     */
    private function getDummyActivitiesForCity($cityName, $country)
    {
        $formattedCityName = $this->formatCityName($cityName);
        $imagePath = "/images/travel/{$formattedCityName}.jpg";
        
        $activities = [];
        
        // Create city-specific dummy activities
        switch ($cityName) {
            case 'Nairobi':
                $activities = [
                    [
                        'id' => 'nbo-1',
                        'type' => 'activity',
                        'name' => 'Nairobi National Park Safari',
                        'shortDescription' => 'Experience wildlife just minutes from the city center in the world\'s only national park within a capital city.',
                        'geoCode' => ['latitude' => '-1.368221', 'longitude' => '36.827137'],
                        'rating' => '4.700000',
                        'pictures' => [$imagePath],
                        'price' => ['currencyCode' => 'KES', 'amount' => '7500.00'],
                        'city' => $cityName,
                        'country' => $country
                    ],
                    [
                        'id' => 'nbo-2',
                        'type' => 'activity',
                        'name' => 'David Sheldrick Wildlife Trust',
                        'shortDescription' => 'Visit the famous elephant orphanage and watch the baby elephants being fed and playing in the mud.',
                        'geoCode' => ['latitude' => '-1.376696', 'longitude' => '36.785378'],
                        'rating' => '4.800000',
                        'pictures' => ["/images/travel/elephant-orphanage.jpg"],
                        'price' => ['currencyCode' => 'KES', 'amount' => '1500.00'],
                        'city' => $cityName,
                        'country' => $country
                    ],
                    [
                        'id' => 'nbo-3',
                        'type' => 'activity',
                        'name' => 'Giraffe Centre',
                        'shortDescription' => 'Feed and kiss giraffes at this conservation center dedicated to the endangered Rothschild\'s giraffe.',
                        'geoCode' => ['latitude' => '-1.375798', 'longitude' => '36.745247'],
                        'rating' => '4.600000',
                        'pictures' => ["/images/travel/giraffe-center.jpg"],
                        'price' => ['currencyCode' => 'KES', 'amount' => '1200.00'],
                        'city' => $cityName,
                        'country' => $country
                    ]
                ];
                break;
            case 'Mombasa':
                $activities = [
                    [
                        'id' => 'mba-1',
                        'type' => 'activity',
                        'name' => 'Fort Jesus Historical Tour',
                        'shortDescription' => 'Explore this UNESCO World Heritage site built by the Portuguese in the 16th century.',
                        'geoCode' => ['latitude' => '-4.063386', 'longitude' => '39.674712'],
                        'rating' => '4.400000',
                        'pictures' => ["/images/travel/fort-jesus.jpg"],
                        'price' => ['currencyCode' => 'KES', 'amount' => '1000.00'],
                        'city' => $cityName,
                        'country' => $country
                    ],
                    [
                        'id' => 'mba-2',
                        'type' => 'activity',
                        'name' => 'Diani Beach Day Trip',
                        'shortDescription' => 'Relax on the white sands of one of Africa\'s most beautiful beaches.',
                        'geoCode' => ['latitude' => '-4.277824', 'longitude' => '39.590616'],
                        'rating' => '4.900000',
                        'pictures' => ["/images/travel/diani-beach.jpg"],
                        'price' => ['currencyCode' => 'KES', 'amount' => '3500.00'],
                        'city' => $cityName,
                        'country' => $country
                    ],
                    [
                        'id' => 'mba-3',
                        'type' => 'activity',
                        'name' => 'Haller Park Wildlife Sanctuary',
                        'shortDescription' => 'Visit this ecological success story that transformed a quarry into a thriving wildlife sanctuary.',
                        'geoCode' => ['latitude' => '-3.994689', 'longitude' => '39.736453'],
                        'rating' => '4.300000',
                        'pictures' => ["/images/travel/haller-park.jpg"],
                        'price' => ['currencyCode' => 'KES', 'amount' => '800.00'],
                        'city' => $cityName,
                        'country' => $country
                    ]
                ];
                break;
            // Add more cases for other cities...
            default:
                $activities = [
                    [
                        'id' => $formattedCityName . '-1',
                        'type' => 'activity',
                        'name' => 'City Tour of ' . $cityName,
                        'shortDescription' => 'Explore the best sights and landmarks of ' . $cityName . ' with a knowledgeable local guide.',
                        'geoCode' => ['latitude' => '0', 'longitude' => '0'],
                        'rating' => '4.300000',
                        'pictures' => [$imagePath],
                        'price' => ['currencyCode' => 'USD', 'amount' => '45.00'],
                        'city' => $cityName,
                        'country' => $country
                    ],
                    [
                        'id' => $formattedCityName . '-2',
                        'type' => 'activity',
                        'name' => 'Cultural Experience in ' . $cityName,
                        'shortDescription' => 'Immerse yourself in the local culture with traditional food, music and crafts.',
                        'geoCode' => ['latitude' => '0', 'longitude' => '0'],
                        'rating' => '4.500000',
                        'pictures' => [$imagePath],
                        'price' => ['currencyCode' => 'USD', 'amount' => '35.00'],
                        'city' => $cityName,
                        'country' => $country
                    ],
                    [
                        'id' => $formattedCityName . '-3',
                        'type' => 'activity',
                        'name' => 'Nature Adventure near ' . $cityName,
                        'shortDescription' => 'Discover the natural beauty surrounding ' . $cityName . ' with this guided outdoor experience.',
                        'geoCode' => ['latitude' => '0', 'longitude' => '0'],
                        'rating' => '4.600000',
                        'pictures' => [$imagePath],
                        'price' => ['currencyCode' => 'USD', 'amount' => '55.00'],
                        'city' => $cityName,
                        'country' => $country
                    ]
                ];
        }
        
        return $activities;
    }

    public function bookActivity(Request $request)
    {
        $activityData = json_decode($request->input('activity_data'), true);
        
        if (!$activityData) {
            return back()->with('error', 'Invalid activity data');
        }
        
        // Create a new booking
        $booking = new Booking();
        $booking->user_id = Auth::id() ?? 1;
        $booking->role = Auth::user()->role ?? 'customer';
        $booking->booking_reference = 'ACT' . strtoupper(Str::random(8));
        $booking->booking_type = 'activity';
        $booking->type = 'activity';
        $booking->title = $activityData['name'];
        
        // Set the price
        if (isset($activityData['price']) && isset($activityData['price']['amount'])) {
            $booking->amount = $activityData['price']['amount'];
            $booking->total_amount = $activityData['price']['amount'];
            $booking->currency = $activityData['price']['currencyCode'];
        } else {
            $booking->amount = 0;
            $booking->total_amount = 0;
            $booking->currency = 'USD';
        }
        
        // Store details
        $booking->status = 'pending';
        $booking->payment_status = 'pending';
        $booking->traveler_details = json_encode([
            'contact_name' => Auth::user()->name ?? 'Guest User',
            'contact_email' => Auth::user()->email ?? $request->input('email', 'guest@example.com'),
        ]);
        
        $booking->hotel_details = json_encode($activityData);
        $booking->adults = 1;
        
        $booking->save();
        
        return redirect()->route('booking.confirmation', ['reference' => $booking->booking_reference])
            ->with('success', 'Your activity has been booked successfully!');
    }

    /**
     * Get East African tours with pagination and filtering
     */
    public function getEastAfricanTours(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 6);
        $city = $request->input('city');
        $showAll = $request->input('show_all', false);
        
        // Build cache key based on filters
        $cacheKey = "east_african_tours_page_{$page}_city_" . ($city ?? 'all') . "_per_page_{$perPage}";
        
        if (Cache::has($cacheKey) && !$showAll) {
            return response()->json(Cache::get($cacheKey));
        }

        // Get tours from database or create dummy data
        $tours = $this->getToursFromDatabase($city, $perPage, $page, $showAll);
        
        // Cache for 1 hour
        Cache::put($cacheKey, $tours, now()->addHour());
        
        return response()->json($tours);
    }

    /**
     * Get tours from database or create dummy data
     */
    private function getToursFromDatabase($city = null, $perPage = 6, $page = 1, $showAll = false)
    {
        // Try to get from database first
        $query = Tour::active();
        
        if ($city && $city !== 'all') {
            $query->byCity($city);
        }
        
        $totalTours = $query->count();
        
        if ($totalTours > 0) {
            if ($showAll) {
                $tours = $query->orderBy('featured', 'desc')
                              ->orderBy('rating', 'desc')
                              ->get();
                return $this->formatToursResponse($tours, $totalTours, $page, $perPage, true);
            } else {
                $tours = $query->orderBy('featured', 'desc')
                              ->orderBy('rating', 'desc')
                              ->paginate($perPage, ['*'], 'page', $page);
                return $this->formatToursResponse($tours->items(), $tours->total(), $page, $perPage, false, $tours->hasMorePages());
            }
        }
        
        // Fallback to dummy data if no tours in database
        return $this->getDummyTours($city, $perPage, $page, $showAll);
    }

    /**
     * Format tours response
     */
    private function formatToursResponse($tours, $total, $page, $perPage, $isShowAll = false, $hasMore = false)
    {
        $groupedByCity = [];
        
        foreach ($tours as $tour) {
            if (!isset($groupedByCity[$tour->city])) {
                $groupedByCity[$tour->city] = [];
            }
            $groupedByCity[$tour->city][] = $tour;
        }
        
        return [
            'data' => $groupedByCity,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'has_more_pages' => $hasMore,
                'showing_all' => $isShowAll
            ],
            'meta' => [
                'total_cities' => count($groupedByCity),
                'total_tours' => $total
            ]
        ];
    }

    /**
     * Get dummy tours data organized by cities
     */
    private function getDummyTours($city = null, $perPage = 6, $page = 1, $showAll = false)
    {
        $allTours = [
            'Nairobi' => [
                [
                    'id' => 'nbo-tour-1',
                    'tour_reference' => 'TOURNBO001',
                    'name' => 'Nairobi National Park Safari',
                    'slug' => 'nairobi-national-park-safari',
                    'short_description' => 'Experience wildlife just minutes from the city center in the world\'s only national park within a capital city.',
                    'city' => 'Nairobi',
                    'country' => 'Kenya',
                    'images' => ['/images/travel/nairobi-national-park.jpg', '/images/travel/nairobi-safari.jpg'],
                    'price' => 75.00,
                    'original_price' => 95.00,
                    'currency' => 'USD',
                    'discount_percent' => 21,
                    'duration' => 'Half Day',
                    'duration_hours' => 4,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.7,
                    'review_count' => 245,
                    'category' => 'Wildlife',
                    'highlights' => ['Lions', 'Giraffes', 'Rhinos', 'City Skyline Views'],
                    'included_services' => ['Transport', 'Guide', 'Entry Fees'],
                    'hotel_pickup' => true,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => true
                ],
                [
                    'id' => 'nbo-tour-2',
                    'tour_reference' => 'TOURNBO002',
                    'name' => 'David Sheldrick Elephant Orphanage',
                    'slug' => 'david-sheldrick-elephant-orphanage',
                    'short_description' => 'Visit the famous elephant orphanage and watch baby elephants being fed and playing.',
                    'city' => 'Nairobi',
                    'country' => 'Kenya',
                    'images' => ['/images/travel/elephant-orphanage.jpg'],
                    'price' => 25.00,
                    'currency' => 'USD',
                    'duration' => '2 Hours',
                    'duration_hours' => 2,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.8,
                    'review_count' => 189,
                    'category' => 'Wildlife',
                    'highlights' => ['Baby Elephants', 'Feeding Time', 'Conservation Story'],
                    'included_services' => ['Entry Fee', 'Guided Tour'],
                    'hotel_pickup' => false,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => false
                ],
                [
                    'id' => 'nbo-tour-3',
                    'tour_reference' => 'TOURNBO003',
                    'name' => 'Giraffe Centre Experience',
                    'slug' => 'giraffe-centre-experience',
                    'short_description' => 'Feed and interact with endangered Rothschild\'s giraffes at this conservation center.',
                    'city' => 'Nairobi',
                    'country' => 'Kenya',
                    'images' => ['/images/travel/giraffe-center.jpg'],
                    'price' => 20.00,
                    'currency' => 'USD',
                    'duration' => '1.5 Hours',
                    'duration_hours' => 1.5,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.6,
                    'review_count' => 156,
                    'category' => 'Wildlife',
                    'highlights' => ['Giraffe Feeding', 'Conservation Education', 'Photo Opportunities'],
                    'included_services' => ['Entry Fee', 'Feeding Experience'],
                    'hotel_pickup' => false,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => false
                ]
            ],
            'Mombasa' => [
                [
                    'id' => 'mba-tour-1',
                    'tour_reference' => 'TOURMBA001',
                    'name' => 'Fort Jesus Historical Tour',
                    'slug' => 'fort-jesus-historical-tour',
                    'short_description' => 'Explore this UNESCO World Heritage site built by the Portuguese in the 16th century.',
                    'city' => 'Mombasa',
                    'country' => 'Kenya',
                    'images' => ['/images/travel/fort-jesus.jpg'],
                    'price' => 30.00,
                    'currency' => 'USD',
                    'duration' => '2 Hours',
                    'duration_hours' => 2,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.4,
                    'review_count' => 98,
                    'category' => 'Historical',
                    'highlights' => ['UNESCO Site', 'Portuguese Architecture', 'Museum Collection'],
                    'included_services' => ['Entry Fee', 'Guided Tour'],
                    'hotel_pickup' => true,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => true
                ],
                [
                    'id' => 'mba-tour-2',
                    'tour_reference' => 'TOURMBA002',
                    'name' => 'Diani Beach Day Trip',
                    'slug' => 'diani-beach-day-trip',
                    'short_description' => 'Relax on the pristine white sands of one of Africa\'s most beautiful beaches.',
                    'city' => 'Mombasa',
                    'country' => 'Kenya',
                    'images' => ['/images/travel/diani-beach.jpg'],
                    'price' => 85.00,
                    'original_price' => 100.00,
                    'currency' => 'USD',
                    'discount_percent' => 15,
                    'duration' => 'Full Day',
                    'duration_hours' => 8,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.9,
                    'review_count' => 267,
                    'category' => 'Beach',
                    'highlights' => ['White Sand Beach', 'Crystal Clear Waters', 'Beach Activities'],
                    'included_services' => ['Transport', 'Lunch', 'Beach Activities'],
                    'hotel_pickup' => true,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => true
                ]
            ],
            'Zanzibar' => [
                [
                    'id' => 'znz-tour-1',
                    'tour_reference' => 'TOURZNZ001',
                    'name' => 'Stone Town Heritage Walk',
                    'slug' => 'stone-town-heritage-walk',
                    'short_description' => 'Discover the rich history and culture of Zanzibar\'s UNESCO-listed Stone Town.',
                    'city' => 'Zanzibar',
                    'country' => 'Tanzania',
                    'images' => ['/images/travel/stone-town.jpg'],
                    'price' => 35.00,
                    'currency' => 'USD',
                    'duration' => '3 Hours',
                    'duration_hours' => 3,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.5,
                    'review_count' => 134,
                    'category' => 'Cultural',
                    'highlights' => ['UNESCO Site', 'Spice Markets', 'Architecture', 'Local Culture'],
                    'included_services' => ['Guided Tour', 'Spice Tasting'],
                    'hotel_pickup' => true,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => true
                ],
                [
                    'id' => 'znz-tour-2',
                    'tour_reference' => 'TOURZNZ002',
                    'name' => 'Spice Tour & Cooking Class',
                    'slug' => 'spice-tour-cooking-class',
                    'short_description' => 'Learn about Zanzibar\'s famous spices and cook traditional dishes.',
                    'city' => 'Zanzibar',
                    'country' => 'Tanzania',
                    'images' => ['/images/travel/spice-tour.jpg'],
                    'price' => 45.00,
                    'currency' => 'USD',
                    'duration' => '4 Hours',
                    'duration_hours' => 4,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.7,
                    'review_count' => 89,
                    'category' => 'Culinary',
                    'highlights' => ['Spice Farm Visit', 'Cooking Class', 'Traditional Meal'],
                    'included_services' => ['Transport', 'Cooking Class', 'Lunch'],
                    'hotel_pickup' => true,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => false
                ]
            ],
            'Kigali' => [
                [
                    'id' => 'kgl-tour-1',
                    'tour_reference' => 'TOURKGL001',
                    'name' => 'Kigali City & Genocide Memorial',
                    'slug' => 'kigali-city-genocide-memorial',
                    'short_description' => 'Learn about Rwanda\'s history and see how Kigali has transformed into a modern African city.',
                    'city' => 'Kigali',
                    'country' => 'Rwanda',
                    'images' => ['/images/travel/kigali-memorial.jpg'],
                    'price' => 40.00,
                    'currency' => 'USD',
                    'duration' => '4 Hours',
                    'duration_hours' => 4,
                    'difficulty_level' => 'Easy',
                    'rating' => 4.6,
                    'review_count' => 76,
                    'category' => 'Historical',
                    'highlights' => ['Genocide Memorial', 'City Tour', 'Modern Rwanda'],
                    'included_services' => ['Transport', 'Guide', 'Entry Fees'],
                    'hotel_pickup' => true,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => true
                ]
            ],
            'Arusha' => [
                [
                    'id' => 'jro-tour-1',
                    'tour_reference' => 'TOURJRO001',
                    'name' => 'Arusha National Park Day Trip',
                    'slug' => 'arusha-national-park-day-trip',
                    'short_description' => 'Explore diverse ecosystems from montane forest to crater lakes with Mount Meru views.',
                    'city' => 'Arusha',
                    'country' => 'Tanzania',
                    'images' => ['/images/travel/arusha-national-park.jpg'],
                    'price' => 120.00,
                    'original_price' => 150.00,
                    'currency' => 'USD',
                    'discount_percent' => 20,
                    'duration' => 'Full Day',
                    'duration_hours' => 8,
                    'difficulty_level' => 'Moderate',
                    'rating' => 4.8,
                    'review_count' => 112,
                    'category' => 'Wildlife',
                    'highlights' => ['Mount Meru Views', 'Crater Lakes', 'Diverse Wildlife'],
                    'included_services' => ['Transport', 'Guide', 'Lunch', 'Park Fees'],
                    'hotel_pickup' => true,
                    'instant_confirmation' => true,
                    'free_cancellation' => true,
                    'featured' => true
                ]
            ]
        ];

        // Filter by city if specified
        if ($city && $city !== 'all' && isset($allTours[$city])) {
            $allTours = [$city => $allTours[$city]];
        }

        // If showing all, return everything
        if ($showAll) {
            return [
                'data' => $allTours,
                'pagination' => [
                    'showing_all' => true,
                    'total' => array_sum(array_map('count', $allTours))
                ],
                'meta' => [
                    'total_cities' => count($allTours),
                    'total_tours' => array_sum(array_map('count', $allTours))
                ]
            ];
        }

        // Apply pagination
        $flatTours = [];
        foreach ($allTours as $cityName => $cityTours) {
            foreach ($cityTours as $tour) {
                $flatTours[] = $tour;
            }
        }

        $total = count($flatTours);
        $offset = ($page - 1) * $perPage;
        $paginatedTours = array_slice($flatTours, $offset, $perPage);

        // Group back by city
        $groupedPaginated = [];
        foreach ($paginatedTours as $tour) {
            if (!isset($groupedPaginated[$tour['city']])) {
                $groupedPaginated[$tour['city']] = [];
            }
            $groupedPaginated[$tour['city']][] = $tour;
        }

        return [
            'data' => $groupedPaginated,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'has_more_pages' => ($offset + $perPage) < $total,
                'showing_all' => false
            ],
            'meta' => [
                'total_cities' => count($groupedPaginated),
                'total_tours' => count($paginatedTours)
            ]
        ];
    }

     /**
     * Show tour details
     */
    public function show($slug)
    {
        $tour = Tour::active()->where('slug', $slug)->firstOrFail();
        
        // Get related tours
        $relatedTours = Tour::active()
            ->where('id', '!=', $tour->id)
            ->where(function($query) use ($tour) {
                $query->where('city', $tour->city)
                      ->orWhere('category', $tour->category);
            })
            ->orderBy('rating', 'desc')
            ->limit(4)
            ->get();
        
        return view('tours.show', compact('tour', 'relatedTours'));
    }

    /**
     * Show tour booking page
     */
    public function booking($slug)
    {
        $tour = Tour::where('slug', $slug)->where('status', 'active')->first();
        
        if (!$tour) {
            $tour = $this->getDummyTourBySlug($slug);
            if (!$tour) {
                abort(404, 'Tour not found');
            }
        }

        return view('tours.booking', compact('tour'));
    }

   /**
     * Process tour booking
     */
    public function book(Request $request)
    {
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'tour_date' => 'required|date|after:today',
            'participants' => 'required|integer|min:1|max:20',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'special_requirements' => 'nullable|string|max:1000',
            'pickup_location' => 'nullable|string|max:500',
            'hotel_pickup' => 'boolean',
            'participant_details' => 'nullable|array',
            'participant_details.*.name' => 'required_with:participant_details|string|max:255',
            'participant_details.*.age' => 'required_with:participant_details|integer|min:1|max:120',
            'participant_details.*.dietary_requirements' => 'nullable|string|max:255'
        ]);

        $tour = Tour::findOrFail($request->tour_id);
        
        // Check availability
        $existingBookings = TourBooking::where('tour_id', $tour->id)
            ->where('tour_date', $request->tour_date)
            ->where('status', '!=', 'cancelled')
            ->sum('participants');
        
        if (($existingBookings + $request->participants) > $tour->max_participants) {
            return back()->withErrors(['participants' => 'Not enough spaces available for this date.']);
        }

        // Calculate pricing
        $pricePerPerson = $tour->price;
        $totalAmount = $pricePerPerson * $request->participants;

        // Create booking
        $booking = new TourBooking();
        $booking->user_id = Auth::id();
        $booking->tour_id = $tour->id;
        $booking->tour_date = $request->tour_date;
        $booking->tour_time = $tour->start_time;
        $booking->participants = $request->participants;
        $booking->price_per_person = $pricePerPerson;
        $booking->total_amount = $totalAmount;
        $booking->currency = $tour->currency;
        $booking->contact_name = $request->contact_name;
        $booking->contact_email = $request->contact_email;
        $booking->contact_phone = $request->contact_phone;
        $booking->special_requirements = $request->special_requirements;
        $booking->pickup_location = $request->pickup_location;
        $booking->hotel_pickup = $request->boolean('hotel_pickup');
        $booking->participant_details = $request->participant_details;
        $booking->status = 'pending';
        $booking->payment_status = 'pending';
        
        $booking->save();

        // Send confirmation emails
        try {
            Mail::to($booking->contact_email)->send(new TourBookingConfirmation($booking));
            Mail::to(config('mail.admin_email', 'admin@example.com'))->send(new AdminTourBookingNotification($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send tour booking emails: ' . $e->getMessage());
        }

        return redirect()->route('tour.payment', ['reference' => $booking->booking_reference])
            ->with('success', 'Tour booking created successfully! Please complete payment to confirm your booking.');
    }

    public function bookTour(Request $request)
{
    try {
        // Validate the incoming request
        $request->validate([
            'tour_data' => 'required|json',
            'participants' => 'nullable|integer|min:1|max:20',
            'tour_date' => 'nullable|date|after:today',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'special_requirements' => 'nullable|string|max:1000',
            'pickup_location' => 'nullable|string',
            'hotel_pickup' => 'nullable|boolean'
        ]);

        $tourData = json_decode($request->tour_data, true);
        
        if (!$tourData) {
            return back()->with('error', 'Invalid tour data')->withInput();
        }

        // Check if this is a database tour or dummy data
        $tour = null;
        if (isset($tourData['id']) && is_numeric($tourData['id'])) {
            $tour = Tour::find($tourData['id']);
        }

        // Default values for booking
        $participants = $request->participants ?? 1;
        $tourDate = $request->tour_date ?? now()->addDays(7)->format('Y-m-d');
        $contactName = $request->contact_name ?? (Auth::user()->name ?? 'Guest User');
        $contactEmail = $request->contact_email ?? (Auth::user()->email ?? 'guest@example.com');
        $contactPhone = $request->contact_phone ?? '';

        // Calculate pricing in both USD and KES
        $basePrice = $tourData['price'] ?? 50.00;
        $currency = $tourData['currency'] ?? 'USD';
        $totalAmount = $basePrice * $participants;
        
        // Calculate KES equivalent if currency is USD
        $kesAmount = ($currency === 'USD') ? ($totalAmount * 125) : $totalAmount;

        if ($tour) {
            // Use TourBooking model for database tours
            $booking = new TourBooking();
            $booking->user_id = Auth::id();
            $booking->tour_id = $tour->id;
            $booking->booking_reference = 'TBK' . strtoupper(Str::random(8));
            $booking->tour_date = $tourDate;
            $booking->tour_time = $tour->start_time;
            $booking->participants = $participants;
            $booking->price_per_person = $basePrice;
            $booking->total_amount = $totalAmount;
            $booking->currency = $currency;
            $booking->contact_name = $contactName;
            $booking->contact_email = $contactEmail;
            $booking->contact_phone = $contactPhone;
            $booking->special_requirements = $request->special_requirements;
            $booking->pickup_location = $request->pickup_location;
            $booking->hotel_pickup = $request->boolean('hotel_pickup');
            $booking->status = 'pending';
            $booking->payment_status = 'pending';
            
            if ($currency === 'USD') {
                $booking->kes_amount = $kesAmount;
            }
            
            $booking->save();

            return redirect()->route('tour.payment', ['reference' => $booking->booking_reference])
                ->with('success', 'Tour booking created successfully! Please complete payment to confirm your booking.');
        } else {
            // Use general Booking model for dummy/API tours
            $booking = new Booking();
            $booking->user_id = Auth::id() ?? 1;
            $booking->role = Auth::user()->role ?? 'customer';
            $booking->booking_reference = 'TOUR' . strtoupper(Str::random(8));
            $booking->booking_type = 'tour';
            $booking->type = 'tour';
            $booking->title = $tourData['name'];
            $booking->amount = $totalAmount;
            $booking->total_amount = $totalAmount;
            $booking->currency = $currency;
            $booking->status = 'pending';
            $booking->payment_status = 'pending';
            $booking->adults = $participants;
            $booking->contact_email = $contactEmail;
            $booking->contact_phone = $contactPhone;
            
            if ($currency === 'USD') {
                $booking->kes_amount = $kesAmount;
            }
            
            // Store tour details
            $booking->traveler_details = json_encode([
                'contact_name' => $contactName,
                'contact_email' => $contactEmail,
                'contact_phone' => $contactPhone,
                'participants' => $participants,
                'tour_date' => $tourDate,
                'special_requirements' => $request->special_requirements,
                'pickup_location' => $request->pickup_location,
                'hotel_pickup' => $request->boolean('hotel_pickup')
            ]);
            
            // Store tour data in hotel_details (reusing existing field)
            $booking->hotel_details = json_encode($tourData);
            
            $booking->save();

            return redirect()->route('booking.confirmation', ['reference' => $booking->booking_reference])
                ->with('success', 'Your tour has been booked successfully!');
        }

    } catch (\Exception $e) {
        \Log::error('Tour booking error: ' . $e->getMessage());
        return back()->with('error', 'Failed to create tour booking. Please try again.')->withInput();
    }
    }

    /**
     * Show payment page
     */
    public function payment($reference)
    {
        $booking = TourBooking::where('booking_reference', $reference)->firstOrFail();
        
        // Check if user can access this booking
        if (Auth::id() !== $booking->user_id && !Auth::user()?->isAdmin()) {
            abort(403, 'Unauthorized access to booking');
        }
        
        return view('tours.payment', compact('booking'));
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_reference' => 'required|exists:tour_bookings,booking_reference',
            'payment_method' => 'required|in:mpesa,bank_transfer,card',
            'phone_number' => 'required_if:payment_method,mpesa|string',
            'bank_details' => 'required_if:payment_method,bank_transfer|array'
        ]);

        $booking = TourBooking::where('booking_reference', $request->booking_reference)->firstOrFail();
        
        // Process payment based on method
        switch ($request->payment_method) {
            case 'mpesa':
                return $this->processMpesaPayment($booking, $request);
            case 'bank_transfer':
                return $this->processBankTransfer($booking, $request);
            case 'card':
                return $this->processCardPayment($booking, $request);
            default:
                return back()->withErrors(['payment_method' => 'Invalid payment method']);
        }
    }

    /**
     * Show booking confirmation
     */
    public function confirmation($reference)
    {
        $booking = TourBooking::with('tour')
            ->where('booking_reference', $reference)
            ->firstOrFail();
        
        return view('tours.confirmation', compact('booking'));
    }

    /**
     * My bookings page
     */
    public function myBookings()
    {
        $bookings = TourBooking::with('tour')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('tours.my-bookings', compact('bookings'));
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $reference)
    {
        $booking = TourBooking::where('booking_reference', $reference)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking is already cancelled');
        }
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);
        
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now()
        ]);
        
        return back()->with('success', 'Booking cancelled successfully');
    }

    /**
     * Get available dates for a tour
     */
    public function getAvailableDates($tourId)
    {
        $tour = Tour::findOrFail($tourId);
        
        // Get next 30 days
        $dates = [];
        $startDate = now();
        
        for ($i = 1; $i <= 30; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            // Check if tour operates on this day
            if ($this->isTourAvailableOnDate($tour, $date)) {
                $availableSpaces = $tour->max_participants - $this->getBookedSpacesForDate($tour->id, $date);
                
                if ($availableSpaces > 0) {
                    $dates[] = [
                        'date' => $date->format('Y-m-d'),
                        'formatted_date' => $date->format('M d, Y'),
                        'available_spaces' => $availableSpaces
                    ];
                }
            }
        }
        
        return response()->json($dates);
    }

    /**
     * Process M-Pesa payment
     */
    private function processMpesaPayment($booking, $request)
    {
        $booking->update([
            'payment_method' => 'mpesa',
            'payment_details' => [
                'phone_number' => $request->phone_number,
                'transaction_id' => 'MP' . time(),
                'status' => 'pending'
            ]
        ]);
        
        return redirect()->route('tour.confirmation', ['reference' => $booking->booking_reference])
            ->with('success', 'Payment initiated. You will receive an M-Pesa prompt shortly.');
    }

    /**
     * Process bank transfer
     */
    private function processBankTransfer($booking, $request)
    {
        $booking->update([
            'payment_method' => 'bank_transfer',
            'payment_details' => [
                'bank_details' => $request->bank_details,
                'status' => 'pending_verification'
            ]
        ]);
        
        return redirect()->route('tour.confirmation', ['reference' => $booking->booking_reference])
            ->with('info', 'Bank transfer details recorded. Please complete the transfer and send proof to our team.');
    }

    /**
     * Process card payment
     */
    private function processCardPayment($booking, $request)
    {
        $booking->update([
            'payment_method' => 'card',
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'payment_date' => now(),
            'payment_details' => [
                'transaction_id' => 'CARD' . time(),
                'status' => 'completed'
            ]
        ]);
        
        return redirect()->route('tour.confirmation', ['reference' => $booking->booking_reference])
            ->with('success', 'Payment successful! Your tour is confirmed.');
    }

    /**
     * Check if tour is available on a specific date
     */
    private function isTourAvailableOnDate($tour, $date)
    {
        return true; // Add your business logic here
    }

    /**
     * Get booked spaces for a specific date
     */
    private function getBookedSpacesForDate($tourId, $date)
    {
        return TourBooking::where('tour_id', $tourId)
            ->where('tour_date', $date->format('Y-m-d'))
            ->where('status', '!=', 'cancelled')
            ->sum('participants');
    }
    /**
     * Get dummy tour by slug (fallback method)
     */
    private function getDummyTourBySlug($slug)
    {
        $dummyTours = $this->getDummyTours();
        
        foreach ($dummyTours['data'] as $city => $tours) {
            foreach ($tours as $tour) {
                if ($tour['slug'] === $slug) {
                    return (object) $tour;
                }
            }
        }
        
        return null;
    }

     /**
     * Show tour deals results page
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showResults(Request $request)
    {
        $highlightId = $request->input('highlight_id');
        $tourDeals = $this->activityController->getEastAfricanTours($request);
        
        // If a specific deal should be highlighted
        if ($highlightId) {
            foreach ($tourDeals as $key => $deal) {
                if ($deal['id'] == $highlightId) {
                    // Move this deal to the top of the list
                    $highlightedDeal = $tourDeals[$key];
                    unset($tourDeals[$key]);
                    array_unshift($tourDeals, $highlightedDeal);
                    break;
                }
            }
        }
        
        return view('deals.tour-deals-results', [
            'tourDeals' => $tourDeals,
            'highlightId' => $highlightId,
            'pageTitle' => 'Tour Deals & Activities'
        ]);
    }

    /**
     * Show tour deal booking page
     * 
     * @param Request $request
     * @param string $dealId
     * @return \Illuminate\Http\Response
     */
    public function showBooking(Request $request, $dealId)
    {
        // Get all available tour deals
        $tourDeals = $this->activityController->getEastAfricanTours($request);
        
        // Find the requested deal
        $selectedDeal = null;
        foreach ($tourDeals as $deal) {
            if ($deal['id'] == $dealId) {
                $selectedDeal = $deal;
                break;
            }
        }
        
        if (!$selectedDeal) {
            return redirect()->route('deal.tour.results')
                ->with('error', 'The selected tour deal was not found.');
        }
        
        // Get user information if logged in
        $user = Auth::check() ? Auth::user() : null;
        
        return view('deals.tour-deal-booking', [
            'deal' => $selectedDeal,
            'user' => $user,
            'pageTitle' => 'Book Tour Deal - ' . $selectedDeal['tourName']
        ]);
    }

    /**
     * Save tour booking and redirect to checkout
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bookDeal(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'tour_id' => 'required|string',
            'tour_date' => 'required|date|after_or_equal:today',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            // Process the tour booking
            $tour = new Tour();
            $tour->user_id = Auth::check() ? Auth::id() : null;
            $tour->tour_id = $request->input('tour_id');
            $tour->tour_name = $request->input('tour_name');
            $tour->status = 'processing';
            $tour->booking_reference = 'TD-'.strtoupper(substr(uniqid(), 0, 8));
            $tour->booking_date = now();
            $tour->tour_date = $request->input('tour_date');
            $tour->total_amount = $request->input('total_amount');
            $tour->currency = $request->input('currency', 'KES');
            $tour->usd_amount = $request->input('usd_amount');
            $tour->payment_status = 'pending';
            $tour->adults = $request->input('adults');
            $tour->children = $request->input('children', 0);
            $tour->addons = json_encode($request->input('addons', []));
            $tour->traveler_details = json_encode($request->input('traveler_details'));
            $tour->contact_details = json_encode($request->input('contact_details'));
            $tour->save();
            
            // Redirect to payment page
            return redirect()->route('deal.tour.checkout', ['reference' => $tour->booking_reference])
                ->with('success', 'Tour reservation created successfully. Please complete your payment.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show tour deal checkout page
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function showCheckout(Request $request, $reference)
    {
        // Find the tour booking by reference
        $booking = Tour::where('booking_reference', $reference)->firstOrFail();
        
        // Get tour details from booking
        $tourData = [
            'tour_id' => $booking->tour_id,
            'tour_name' => $booking->tour_name,
            'tour_date' => $booking->tour_date,
            'adults' => $booking->adults,
            'children' => $booking->children,
            'addons' => json_decode($booking->addons, true)
        ];
        
        $travelerDetails = json_decode($booking->traveler_details, true);
        $contactDetails = json_decode($booking->contact_details, true);
        
        return view('deals.tour-deal-checkout', [
            'booking' => $booking,
            'tourData' => $tourData,
            'travelerDetails' => $travelerDetails,
            'contactDetails' => $contactDetails,
            'pageTitle' => 'Complete Your Tour Booking'
        ]);
    }

    /**
     * Process payment for tour deal
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
    //         'currency_preference' => 'required|string|in:USD,KES',
    //         'terms_agreed' => 'required|accepted'
    //     ]);
        
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }
        
    //     // Find the booking
    //     $booking = Tour::where('booking_reference', $reference)->firstOrFail();
        
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
    //             $booking->currency_paid = $request->input('currency_preference');
    //             $booking->save();
                
    //             // Send confirmation to user
    //             if (Auth::check() || $booking->contact_details) {
    //                 // Send email notification - you'd implement this based on your email setup
    //                 // Mail::to($emailAddress)->send(new TourBookingConfirmation($booking));
    //             }
                
    //             // Update user dashboard and alert admins
    //             event(new TourBookingUpdated($booking));
                
    //             return redirect()->route('user.dashboard')
    //                 ->with('success', 'Payment successful! Your tour booking has been confirmed.');
    //         } else {
    //             return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
    //         }
            
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'There was a problem processing your payment: ' . $e->getMessage());
    //     }
    // }


}