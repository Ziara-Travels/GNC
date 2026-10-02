<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HotelOffersController extends Controller
{
    protected $eastAfricanHotels = [
        'NBO' => [
            [
                'id' => 'nbo-001',
                'hotelName' => 'Villa Rosa Kempinski',
                'city' => 'Nairobi',
                'country' => 'Kenya',
                'hotelImage' => 'nairobi-hotel.jpg',
                'description' => 'Luxury hotel with stunning views of the city skyline',
                'highlight' => 'City Luxury',
                'location' => 'City Center',
                'stars' => 5,
                'hotelType' => 'Luxury',
                'meals' => 'Breakfast Included',
                'wifi' => 'Free Premium WiFi'
            ],
            [
                'id' => 'nbo-002',
                'hotelName' => 'Hemingways Nairobi',
                'city' => 'Nairobi',
                'country' => 'Kenya',
                'hotelImage' => 'nairobi-hotel2.jpg',
                'description' => 'Boutique hotel with plantation-style architecture',
                'highlight' => 'Exclusive Suites',
                'location' => 'Karen',
                'stars' => 5,
                'hotelType' => 'Boutique',
                'meals' => 'Full Board Available',
                'wifi' => 'Free WiFi'
            ]
        ],
        'MBA' => [
            [
                'id' => 'mba-001',
                'hotelName' => 'Serena Beach Resort',
                'city' => 'Mombasa',
                'country' => 'Kenya',
                'hotelImage' => 'mombasa-hotel.jpg',
                'description' => 'Beachfront resort with white sand beaches and coral reefs',
                'highlight' => 'Beachfront',
                'location' => 'North Coast',
                'stars' => 5,
                'hotelType' => 'Resort',
                'meals' => 'All-Inclusive Option',
                'wifi' => 'Free WiFi'
            ],
            [
                'id' => 'mba-002',
                'hotelName' => 'Voyager Beach Resort',
                'city' => 'Mombasa',
                'country' => 'Kenya',
                'hotelImage' => 'mombasa-hotel2.jpg',
                'description' => 'Ship-themed beach resort with multiple pools',
                'highlight' => 'Family-Friendly',
                'location' => 'Nyali Beach',
                'stars' => 4,
                'hotelType' => 'Resort',
                'meals' => 'All-Inclusive',
                'wifi' => 'Free WiFi'
            ]
        ],
        'ZNZ' => [
            [
                'id' => 'znz-001',
                'hotelName' => 'Zuri Zanzibar',
                'city' => 'Zanzibar',
                'country' => 'Tanzania',
                'hotelImage' => 'zanzibar-hotel.jpg',
                'description' => 'Luxury eco-resort with private bungalows and pristine beach',
                'highlight' => 'Eco-Luxury',
                'location' => 'Kendwa Beach',
                'stars' => 5,
                'hotelType' => 'Luxury',
                'meals' => 'Half-Board Included',
                'wifi' => 'Free WiFi'
            ],
            [
                'id' => 'znz-002',
                'hotelName' => 'Park Hyatt Zanzibar',
                'city' => 'Zanzibar',
                'country' => 'Tanzania',
                'hotelImage' => 'zanzibar-hotel2.jpg',
                'description' => 'Historic hotel in Stone Town with Arabian-inspired design',
                'highlight' => 'Heritage Property',
                'location' => 'Stone Town',
                'stars' => 5,
                'hotelType' => 'Heritage',
                'meals' => 'Breakfast Included',
                'wifi' => 'Free WiFi'
            ]
        ],
        'ARK' => [
            [
                'id' => 'ark-001',
                'hotelName' => 'Gran Melia Arusha',
                'city' => 'Arusha',
                'country' => 'Tanzania',
                'hotelImage' => 'arusha-hotel.jpg',
                'description' => 'Luxury hotel with stunning views of Mount Meru',
                'highlight' => 'Mountain View',
                'location' => 'City Center',
                'stars' => 5,
                'hotelType' => 'Luxury',
                'meals' => 'Breakfast Included',
                'wifi' => 'Free WiFi'
            ]
        ],
        'EBB' => [
            [
                'id' => 'ebb-001',
                'hotelName' => 'Protea Hotel Entebbe',
                'city' => 'Entebbe',
                'country' => 'Uganda',
                'hotelImage' => 'entebbe-hotel.jpg',
                'description' => 'Resort hotel with panoramic views of Lake Victoria',
                'highlight' => 'Lake View',
                'location' => 'Victoria Mall Area',
                'stars' => 4,
                'hotelType' => 'Business',
                'meals' => 'Breakfast Included',
                'wifi' => 'Free WiFi'
            ]
        ],
        // 'KGL' => [
        //     [
        //         'id' => 'kgl-001',
        //         'hotelName' => 'Kigali Marriott Hotel',
        //         'city' => 'Kigali',
        //         'country' => 'Rwanda',
        //         'hotelImage' => 'kigali-hotel.jpg',
        //         'description' => 'Modern hotel in the heart of Rwanda's capital',
        //         'highlight' => 'City Center',
        //         'location' => 'Downtown',
        //         'stars' => 5,
        //         'hotelType' => 'Business',
        //         'meals' => 'Breakfast Included',
        //         'wifi' => 'Free WiFi'
        //     ]
        // ],
        'DAR' => [
            [
                'id' => 'dar-001',
                'hotelName' => 'Hyatt Regency Dar Es Salaam',
                'city' => 'Dar Es Salaam',
                'country' => 'Tanzania',
                'hotelImage' => 'dar-hotel.jpg',
                'description' => 'Waterfront hotel with views of Dar Es Salaam Harbor',
                'highlight' => 'Harbor View',
                'location' => 'City Center',
                'stars' => 5,
                'hotelType' => 'Luxury',
                'meals' => 'Breakfast Included',
                'wifi' => 'Free WiFi'
            ]
        ],
        'JIN' => [
            [
                'id' => 'jin-001',
                'hotelName' => 'Jinja Nile Resort',
                'city' => 'Jinja',
                'country' => 'Uganda',
                'hotelImage' => 'jinja-hotel.jpg',
                'description' => 'Riverside resort overlooking the source of the Nile',
                'highlight' => 'River View',
                'location' => 'Nile Riverbank',
                'stars' => 4,
                'hotelType' => 'Resort',
                'meals' => 'Half-Board Option',
                'wifi' => 'Free WiFi'
            ]
        ],
        'ADD' => [
            [
                'id' => 'add-001',
                'hotelName' => 'Sheraton Addis',
                'city' => 'Addis Ababa',
                'country' => 'Ethiopia',
                'hotelImage' => 'addis-hotel.jpg',
                'description' => 'Luxury hotel with extensive gardens and pools',
                'highlight' => 'Urban Oasis',
                'location' => 'City Center',
                'stars' => 5,
                'hotelType' => 'Luxury',
                'meals' => 'Breakfast Included',
                'wifi' => 'Free WiFi'
            ]
        ]
    ];

    /**
     * Get hotel offers for East African destinations
     */
    public function getEastAfricanHotels(Request $request)
    {
        // Check if we have cached results
        if (Cache::has('east_african_hotel_offers')) {
            return Cache::get('east_african_hotel_offers');
        }

        $offers = [];
        $totalOffers = 0;
        $maxOffers = 9; // Maximum offers to display

        try {
            // Get destinations with hotels
            $destinations = array_keys($this->eastAfricanHotels);
            shuffle($destinations);
            
            foreach ($destinations as $destinationCode) {
                if (empty($this->eastAfricanHotels[$destinationCode])) continue;
                
                $hotelsInDestination = $this->eastAfricanHotels[$destinationCode];
                shuffle($hotelsInDestination);
                
                foreach ($hotelsInDestination as $hotel) {
                    // Calculate discount (15-30% off)
                    $discountPercent = rand(15, 30);
                    $price = rand(15000, 45000);
                    $originalPrice = round($price * (100 / (100 - $discountPercent)), 0);
                    
                    // Available date (near future)
                    $availableDate = Carbon::now()->addDays(rand(7, 30))->format('M d, Y');
                    
                    $offers[] = array_merge($hotel, [
                        'availableDate' => $availableDate,
                        'price' => $price,
                        'originalPrice' => $originalPrice,
                        'currency' => 'KES',
                        'discountPercent' => $discountPercent
                    ]);
                    
                    $totalOffers++;
                    if ($totalOffers >= $maxOffers) break 2;
                }
            }
            
            // If we don't have enough offers from the API, supplement with dummy data
            if ($totalOffers < $maxOffers) {
                $additionalNeeded = $maxOffers - $totalOffers;
                $dummyOffers = $this->getDummyHotelOffers($additionalNeeded);
                $offers = array_merge($offers, $dummyOffers);
            }
            
            // Cache the results for 1 hour to improve performance
            Cache::put('east_african_hotel_offers', $offers, now()->addHours(1));
            
            return $offers;
            
        } catch (GuzzleException $exception) {
            // In case of API error, return dummy data
            $dummyOffers = $this->getDummyHotelOffers($maxOffers);
            return $dummyOffers;
        }
    }
    
    /**
     * Get dummy hotel offers for testing or API fallback
     */
    private function getDummyHotelOffers($count)
    {
        $dummyOffers = [];
        
        $cities = [
            ['code' => 'NBO', 'name' => 'Nairobi', 'country' => 'Kenya'],
            ['code' => 'MBA', 'name' => 'Mombasa', 'country' => 'Kenya'],
            ['code' => 'ZNZ', 'name' => 'Zanzibar', 'country' => 'Tanzania'],
            ['code' => 'ARK', 'name' => 'Arusha', 'country' => 'Tanzania'],
            ['code' => 'EBB', 'name' => 'Entebbe', 'country' => 'Uganda']
        ];
        
        $hotelTypes = ['Luxury', 'Boutique', 'Resort', 'Business', 'Heritage'];
        $highlights = ['Beach Access', 'Rooftop Pool', 'City View', 'Historic Building', 'Fine Dining'];
        $meals = ['Breakfast Included', 'All-Inclusive', 'Half Board', 'Full Board Available'];
        $wifi = ['Free WiFi', 'Premium WiFi', 'Free High-Speed WiFi'];
        
        for ($i = 0; $i < $count; $i++) {
            $city = $cities[rand(0, count($cities) - 1)];
            $stars = rand(3, 5);
            $price = rand(15000, 45000);
            $discountPercent = rand(15, 30);
            $originalPrice = round($price * (100 / (100 - $discountPercent)), 0);
            
            $dummyOffers[] = [
                'id' => 'dummy-' . rand(1000, 9999),
                'hotelName' => $this->generateHotelName(),
                'city' => $city['name'],
                'country' => $city['country'],
                'hotelImage' => strtolower($city['name']) . '-hotel.jpg',
                'description' => 'Luxury hotel in ' . $city['name'] . ' with exceptional service and amenities',
                'highlight' => $highlights[rand(0, count($highlights) - 1)],
                'location' => 'City Center',
                'availableDate' => Carbon::now()->addDays(rand(7, 30))->format('M d, Y'),
                'price' => $price,
                'originalPrice' => $originalPrice,
                'currency' => 'KES',
                'discountPercent' => $discountPercent,
                'hotelType' => $hotelTypes[rand(0, count($hotelTypes) - 1)],
                'stars' => $stars,
                'meals' => $meals[rand(0, count($meals) - 1)],
                'wifi' => $wifi[rand(0, count($wifi) - 1)]
            ];
        }
        
        return $dummyOffers;
    }
    
    /**
     * Generate random hotel name
     */
    private function generateHotelName()
    {
        $prefixes = ['Grand', 'Royal', 'Imperial', 'Luxury', 'Elite', 'Golden', 'Premium', 'Majestic'];
        $names = ['Horizon', 'Plaza', 'Continental', 'Regency', 'Palace', 'Safari', 'Serena', 'Heights'];
        
        return $prefixes[rand(0, count($prefixes) - 1)] . ' ' . $names[rand(0, count($names) - 1)];
    }
    
    /**
     * Show hotel deals results page
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showHotelDealsResults(Request $request)
    {
        $highlightId = $request->input('highlight_id');
        $hotelDeals = $this->getEastAfricanHotels($request);
        
        // If a specific deal should be highlighted
        if ($highlightId) {
            foreach ($hotelDeals as $key => $deal) {
                if ($deal['id'] == $highlightId) {
                    // Move this deal to the top of the list
                    $highlightedDeal = $hotelDeals[$key];
                    unset($hotelDeals[$key]);
                    array_unshift($hotelDeals, $highlightedDeal);
                    break;
                }
            }
        }
        
        return view('deals.hotel-deals-results', [
            'hotelDeals' => $hotelDeals,
            'highlightId' => $highlightId,
            'pageTitle' => 'Hotel Deals'
        ]);
    }

    /**
     * Show hotel deal booking page
     * 
     * @param Request $request
     * @param string $dealId
     * @return \Illuminate\Http\Response
     */
    public function showHotelDealBooking(Request $request, $dealId)
    {
        // Get all available hotel deals
        $hotelDeals = $this->getEastAfricanHotels($request);
        
        // Find the requested deal
        $selectedDeal = null;
        foreach ($hotelDeals as $deal) {
            if ($deal['id'] == $dealId) {
                $selectedDeal = $deal;
                break;
            }
        }
        
        if (!$selectedDeal) {
            return redirect()->route('deal.hotel.results')
                ->with('error', 'The selected hotel deal was not found.');
        }
        
        // Get user information if logged in
        $user = Auth::check() ? Auth::user() : null;
        
        return view('deals.hotel-deal-booking', [
            'deal' => $selectedDeal,
            'user' => $user,
            'pageTitle' => 'Book Hotel Deal - ' . $selectedDeal['hotelName']
        ]);
    }

    /**
     * Save hotel booking and redirect to checkout
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bookHotelDeal(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'rooms' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            // Process the hotel booking
            $booking = new Booking();
            $booking->user_id = Auth::check() ? Auth::id() : null;
            $booking->type = 'hotel';
            $booking->status = 'processing';
            $booking->booking_reference = 'HD-'.strtoupper(substr(uniqid(), 0, 8));
            $booking->booking_date = now();
            $booking->total_amount = $request->input('total_amount');
            $booking->currency = $request->input('currency', 'KES');
            $booking->payment_status = 'pending';
            $booking->hotel_details = json_encode([
                'hotel_id' => $request->input('hotel_id'),
                'hotel_name' => $request->input('hotel_name'),
                'check_in' => $request->input('check_in'),
                'check_out' => $request->input('check_out'),
                'rooms' => $request->input('rooms'),
                'room_type' => $request->input('room_type'),
                'adults' => $request->input('adults'),
                'children' => $request->input('children', 0)
            ]);
            $booking->guest_details = json_encode($request->input('guest_details'));
            $booking->contact_details = json_encode($request->input('contact_details'));
            $booking->save();
            
            // Redirect to payment page
            return redirect()->route('deal.hotel.checkout', ['reference' => $booking->booking_reference])
                ->with('success', 'Hotel reservation created successfully. Please complete your payment.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show hotel deal checkout page
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function showHotelDealCheckout(Request $request, $reference)
    {
        // Find the booking by reference
        $booking = Booking::where('booking_reference', $reference)->firstOrFail();
        
        // Get hotel details from booking
        $hotelData = json_decode($booking->hotel_details, true);
        $guestDetails = json_decode($booking->guest_details, true);
        $contactDetails = json_decode($booking->contact_details, true);
        
        return view('deals.hotel-deal-checkout', [
            'booking' => $booking,
            'hotelData' => $hotelData,
            'guestDetails' => $guestDetails,
            'contactDetails' => $contactDetails,
            'pageTitle' => 'Complete Your Hotel Booking'
        ]);
    }

    /**
     * Process payment for hotel deal
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function processHotelDealPayment(Request $request, $reference)
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
                    // Send email notification - you'd implement this based on your email setup
                    // Mail::to($emailAddress)->send(new HotelBookingConfirmation($booking));
                }
                
                // Update user dashboard and alert admins
                event(new \App\Events\BookingStatusUpdated($booking));
                
                return redirect()->route('user.dashboard')
                    ->with('success', 'Payment successful! Your hotel booking has been confirmed.');
            } else {
                return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your payment: ' . $e->getMessage());
        }
    }

     /**
     * Show hotel deals results page
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function showResults(Request $request)
    {
        $highlightId = $request->input('highlight_id');
        $hotelDeals = $this->hotelOffersController->getEastAfricanHotels($request);
        
        // If a specific deal should be highlighted
        if ($highlightId) {
            foreach ($hotelDeals as $key => $deal) {
                if ($deal['id'] == $highlightId) {
                    // Move this deal to the top of the list
                    $highlightedDeal = $hotelDeals[$key];
                    unset($hotelDeals[$key]);
                    array_unshift($hotelDeals, $highlightedDeal);
                    break;
                }
            }
        }
        
        return view('deals.hotel-deals-results', [
            'hotelDeals' => $hotelDeals,
            'highlightId' => $highlightId,
            'pageTitle' => 'Hotel Deals'
        ]);
    }

    /**
     * Show hotel deal booking page
     * 
     * @param Request $request
     * @param string $dealId
     * @return \Illuminate\Http\Response
     */
    public function showBooking(Request $request, $dealId)
    {
        // Get all available hotel deals
        $hotelDeals = $this->hotelOffersController->getEastAfricanHotels($request);
        
        // Find the requested deal
        $selectedDeal = null;
        foreach ($hotelDeals as $deal) {
            if ($deal['id'] == $dealId) {
                $selectedDeal = $deal;
                break;
            }
        }
        
        if (!$selectedDeal) {
            return redirect()->route('deal.hotel.results')
                ->with('error', 'The selected hotel deal was not found.');
        }
        
        // Get user information if logged in
        $user = Auth::check() ? Auth::user() : null;
        
        return view('deals.hotel-deal-booking', [
            'deal' => $selectedDeal,
            'user' => $user,
            'pageTitle' => 'Book Hotel Deal - ' . $selectedDeal['hotelName']
        ]);
    }

    /**
     * Save hotel booking and redirect to checkout
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bookDeal(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'rooms' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            // Process the hotel booking
            $booking = new Booking();
            $booking->user_id = Auth::check() ? Auth::id() : null;
            $booking->type = 'hotel';
            $booking->status = 'processing';
            $booking->booking_reference = 'HD-'.strtoupper(substr(uniqid(), 0, 8));
            $booking->booking_date = now();
            $booking->total_amount = $request->input('total_amount');
            $booking->currency = $request->input('currency', 'KES');
            $booking->payment_status = 'pending';
            $booking->hotel_details = json_encode([
                'hotel_id' => $request->input('hotel_id'),
                'hotel_name' => $request->input('hotel_name'),
                'check_in' => $request->input('check_in'),
                'check_out' => $request->input('check_out'),
                'rooms' => $request->input('rooms'),
                'room_type' => $request->input('room_type'),
                'adults' => $request->input('adults'),
                'children' => $request->input('children', 0)
            ]);
            $booking->guest_details = json_encode($request->input('guest_details'));
            $booking->contact_details = json_encode($request->input('contact_details'));
            $booking->save();
            
            // Redirect to payment page
            return redirect()->route('deal.hotel.checkout', ['reference' => $booking->booking_reference])
                ->with('success', 'Hotel reservation created successfully. Please complete your payment.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your booking: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show hotel deal checkout page
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function showCheckout(Request $request, $reference)
    {
        // Find the booking by reference
        $booking = Booking::where('booking_reference', $reference)->firstOrFail();
        
        // Get hotel details from booking
        $hotelData = json_decode($booking->hotel_details, true);
        $guestDetails = json_decode($booking->guest_details, true);
        $contactDetails = json_decode($booking->contact_details, true);
        
        return view('deals.hotel-deal-checkout', [
            'booking' => $booking,
            'hotelData' => $hotelData,
            'guestDetails' => $guestDetails,
            'contactDetails' => $contactDetails,
            'pageTitle' => 'Complete Your Hotel Booking'
        ]);
    }

    /**
     * Process payment for hotel deal
     * 
     * @param Request $request
     * @param string $reference
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request, $reference)
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
                    // Send email notification - you'd implement this based on your email setup
                    // Mail::to($emailAddress)->send(new HotelBookingConfirmation($booking));
                }
                
                // Update user dashboard and alert admins
                event(new \App\Events\BookingStatusUpdated($booking));
                
                return redirect()->route('user.dashboard')
                    ->with('success', 'Payment successful! Your hotel booking has been confirmed.');
            } else {
                return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your payment: ' . $e->getMessage());
        }
    }
}
