<?php

namespace App\Http\Controllers\Travel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminBookingNotification;
use App\Mail\BookingConfirmationMail;
use App\Mail\LoyaltyPointsUpdateMail;
use App\Jobs\SendBookingConfirmationJob;
use App\Jobs\SendPreTripReminderJob;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use App\Models\CustomTrip;
use App\Models\Newsletter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

class PopularDestinationController extends Controller
{
    protected $client;
    
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get popular East African destinations
     */
    public function getDestinations()
    {
        $destinations = [
            [
                'name' => 'Mombasa', 
                'price' => 'KES 15,000', 
                'image' => 'travel/mombasa.jpg', 
                'country' => 'Kenya', 
                'rating' => '4.8', 
                'reviews' => '2.5K', 
                'tag' => 'Beach', 
                'from' => 'NBO',
                'to' => 'MBA',
                'duration' => '1h 5m',
                'departure_date' => date('Y-m-d', strtotime('+7 days')),
                'return_date' => date('Y-m-d', strtotime('+14 days')),
                'price_numeric' => 15000,
                'currency' => 'KES',
                'airline' => 'KQ',
                'flight_number' => 'KQ612',
                'cabin_class' => 'ECONOMY'
            ],
            [
                'name' => 'Zanzibar', 
                'price' => 'KES 22,000', 
                'image' => 'travel/zanzibar.jpg', 
                'country' => 'Tanzania', 
                'rating' => '4.9', 
                'reviews' => '3.2K', 
                'tag' => 'Island', 
                'from' => 'NBO',
                'to' => 'ZNZ',
                'duration' => '1h 30m',
                'departure_date' => date('Y-m-d', strtotime('+10 days')),
                'return_date' => date('Y-m-d', strtotime('+17 days')),
                'price_numeric' => 22000,
                'currency' => 'KES',
                'airline' => 'KQ',
                'flight_number' => 'KQ490',
                'cabin_class' => 'ECONOMY'
            ],
            [
                'name' => 'Kigali', 
                'price' => 'KES 18,500', 
                'image' => 'travel/kigali.jpg', 
                'country' => 'Rwanda', 
                'rating' => '4.7', 
                'reviews' => '1.8K', 
                'tag' => 'Cultural', 
                'from' => 'NBO',
                'to' => 'KGL',
                'duration' => '1h 20m',
                'departure_date' => date('Y-m-d', strtotime('+8 days')),
                'return_date' => date('Y-m-d', strtotime('+15 days')),
                'price_numeric' => 18500,
                'currency' => 'KES',
                'airline' => 'RW',
                'flight_number' => 'RW205',
                'cabin_class' => 'ECONOMY'
            ],
            [
                'name' => 'Dar es Salaam', 
                'price' => 'KES 19,000', 
                'image' => 'travel/dar-es-salaam.jpg', 
                'country' => 'Tanzania', 
                'rating' => '4.6', 
                'reviews' => '1.9K', 
                'tag' => 'Coastal', 
                'from' => 'NBO',
                'to' => 'DAR',
                'duration' => '1h 35m',
                'departure_date' => date('Y-m-d', strtotime('+12 days')),
                'return_date' => date('Y-m-d', strtotime('+19 days')),
                'price_numeric' => 19000,
                'currency' => 'KES',
                'airline' => 'KQ',
                'flight_number' => 'KQ482',
                'cabin_class' => 'ECONOMY'
            ],
            [
                'name' => 'Entebbe', 
                'price' => 'KES 16,500', 
                'image' => 'travel/entebbe.jpg', 
                'country' => 'Uganda', 
                'rating' => '4.5', 
                'reviews' => '1.7K', 
                'tag' => 'Lake', 
                'from' => 'NBO',
                'to' => 'EBB',
                'duration' => '1h 10m',
                'departure_date' => date('Y-m-d', strtotime('+9 days')),
                'return_date' => date('Y-m-d', strtotime('+16 days')),
                'price_numeric' => 16500,
                'currency' => 'KES',
                'airline' => 'KQ',
                'flight_number' => 'KQ410',
                'cabin_class' => 'ECONOMY'
            ],
            [
                'name' => 'Arusha', 
                'price' => 'KES 17,800', 
                'image' => 'travel/arusha.jpg', 
                'country' => 'Tanzania', 
                'rating' => '4.7', 
                'reviews' => '2.0K', 
                'tag' => 'Safari', 
                'from' => 'NBO',
                'to' => 'ARK',
                'duration' => '1h 15m',
                'departure_date' => date('Y-m-d', strtotime('+11 days')),
                'return_date' => date('Y-m-d', strtotime('+18 days')),
                'price_numeric' => 17800,
                'currency' => 'KES',
                'airline' => 'PW',
                'flight_number' => 'PW722',
                'cabin_class' => 'ECONOMY'
            ],
            [
                'name' => 'Addis Ababa', 
                'price' => 'KES 24,000', 
                'image' => 'travel/addis-ababa.jpg', 
                'country' => 'Ethiopia', 
                'rating' => '4.6', 
                'reviews' => '1.6K', 
                'tag' => 'Historic', 
                'from' => 'NBO',
                'to' => 'ADD',
                'duration' => '2h 10m',
                'departure_date' => date('Y-m-d', strtotime('+13 days')),
                'return_date' => date('Y-m-d', strtotime('+20 days')),
                'price_numeric' => 24000,
                'currency' => 'KES',
                'airline' => 'ET',
                'flight_number' => 'ET858',
                'cabin_class' => 'ECONOMY'
            ],
            [
                'name' => 'Juba', 
                'price' => 'KES 22,500', 
                'image' => 'travel/juba.jpg', 
                'country' => 'South Sudan', 
                'rating' => '4.4', 
                'reviews' => '1.1K', 
                'tag' => 'Emerging', 
                'from' => 'NBO',
                'to' => 'JUB',
                'duration' => '1h 45m',
                'departure_date' => date('Y-m-d', strtotime('+14 days')),
                'return_date' => date('Y-m-d', strtotime('+21 days')),
                'price_numeric' => 22500,
                'currency' => 'KES',
                'airline' => 'KQ',
                'flight_number' => 'KQ352',
                'cabin_class' => 'ECONOMY'
            ]
        ];

        return response()->json($destinations);
    }

    /**
     * Process booking from popular destinations section
     */
    public function bookDestination(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destination_data' => 'required|json',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $destinationData = json_decode($request->input('destination_data'), true);
        
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store destination data in session for after login
            session(['pending_destination_booking' => $destinationData]);
            
            return redirect()->route('login')->with([
                'message' => 'Please login or create an account to proceed with booking.',
                'alert_type' => 'info'
            ]);
        }

        // User is authenticated, process the booking
        $searchParams = [
            'from' => $destinationData['from'],
            'to' => $destinationData['to'],
            'departure_date' => $destinationData['departure_date'],
            'return_date' => $destinationData['return_date'],
            'adults' => 1,
            'children' => 0,
            'infants' => 0,
            'travel_class' => $destinationData['cabin_class'] ?? 'ECONOMY',
            'trip_type' => 'round_trip',
            'is_direct_booking' => true,
            'selected_destination' => $destinationData
        ];
        
        // Store search parameters in session
        session(['destination_search_params' => $searchParams]);
        
        return redirect()->route('popular.destination.results');
    }
    
    /**
     * Show search results for the selected destination
     */
    public function showDestinationResults()
    {
        $searchParams = session('destination_search_params');
        
        if (!$searchParams) {
            return redirect()->route('home')->with('error', 'No destination selected. Please try again.');
        }
        
        $destination = $searchParams['selected_destination'];
        
        // Create a simplified flight offer based on the destination data
        $flightOffer = [
            'id' => 'offer-' . Str::random(8),
            'itineraries' => [
                [
                    'segments' => [
                        [
                            'departure' => [
                                'iataCode' => $destination['from'],
                                'at' => date('Y-m-d\TH:i:s', strtotime($destination['departure_date'] . ' 08:00:00'))
                            ],
                            'arrival' => [
                                'iataCode' => $destination['to'],
                                'at' => date('Y-m-d\TH:i:s', strtotime($destination['departure_date'] . ' 09:00:00'))
                            ],
                            'carrierCode' => $destination['airline'],
                            'number' => $destination['flight_number'],
                            'duration' => 'PT' . str_replace('h ', 'H', str_replace('m', 'M', $destination['duration']))
                        ]
                    ]
                ],
                [
                    'segments' => [
                        [
                            'departure' => [
                                'iataCode' => $destination['to'],
                                'at' => date('Y-m-d\TH:i:s', strtotime($destination['return_date'] . ' 10:00:00'))
                            ],
                            'arrival' => [
                                'iataCode' => $destination['from'],
                                'at' => date('Y-m-d\TH:i:s', strtotime($destination['return_date'] . ' 11:00:00'))
                            ],
                            'carrierCode' => $destination['airline'],
                            'number' => $destination['flight_number'],
                            'duration' => 'PT' . str_replace('h ', 'H', str_replace('m', 'M', $destination['duration']))
                        ]
                    ]
                ]
            ],
            'price' => [
                'currency' => $destination['currency'],
                'total' => $destination['price_numeric'],
                'base' => $destination['price_numeric'] * 0.8,
                'fees' => [
                    [
                        'amount' => $destination['price_numeric'] * 0.1,
                        'type' => 'TAX'
                    ],
                    [
                        'amount' => $destination['price_numeric'] * 0.1,
                        'type' => 'SERVICE_CHARGE'
                    ]
                ],
                'grandTotal' => $destination['price_numeric']
            ],
            'travelerPricings' => [
                [
                    'travelerId' => '1',
                    'fareOption' => 'STANDARD',
                    'travelerType' => 'ADULT',
                    'price' => [
                        'currency' => $destination['currency'],
                        'total' => $destination['price_numeric'],
                    ],
                    'fareDetailsBySegment' => [
                        [
                            'segmentId' => '1',
                            'cabin' => $destination['cabin_class'],
                            'class' => substr($destination['cabin_class'], 0, 1)
                        ],
                        [
                            'segmentId' => '2',
                            'cabin' => $destination['cabin_class'],
                            'class' => substr($destination['cabin_class'], 0, 1)
                        ]
                    ]
                ]
            ]
        ];
        
        return view('flights.flight-results', [
            'flights' => [$flightOffer],
            'searchParams' => $searchParams,
            'isDirectBooking' => true,
            'destination' => $destination
        ]);
    }
    
    /**
     * Process the final booking for a destination
     */
    public function processDestinationBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flight_data' => 'required|json',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'passenger_details' => 'required|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $flightData = json_decode($request->input('flight_data'), true);
        
        $user = Auth::user();
        
        try {
            // Create booking record
            $booking = $this->createBooking($user, $flightData, $request);

            // Process payment using OrderFlightController payment methods
            $orderController = app(OrderFlightController::class);
            $paymentResult = $orderController->processPayment(
                $booking, 
                $request->input('payment_method', 'mpesa'), 
                $request
            );

            if ($paymentResult['success']) {
                // Update booking status
                $booking->update([
                    'status' => 'confirmed',
                    'payment_status' => 'completed'
                ]);
                
                // Schedule pre-trip reminders
                $this->schedulePreTripReminders($booking);
                
                // Award loyalty points
                $this->awardLoyaltyPoints($booking);
                
                return redirect()->route('booking.success', $booking->booking_reference)
                    ->with('success', 'Your destination booking has been confirmed!');
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

                return redirect()->route('booking.failure')
                    ->with('error', 'Payment failed: ' . $paymentResult['message']);
            }

        } catch (\Exception $e) {
            // Store failure details in session
            session([
                'failure_reason' => $e->getMessage(),
                'error_code' => 'BOOKING_FAILED',
                'suggested_action' => 'Please try again or contact support',
                'failed_payment_method' => $request->input('payment_method', 'unknown'),
                'failed_amount' => $flightData['price']['grandTotal'] ?? 0,
                'failed_currency' => $flightData['price']['currency'] ?? 'KES',
                'failed_booking_reference' => 'N/A',
            ]);

            return redirect()->route('booking.failure')
                ->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    /**
     * Create a booking record for a destination
     */
    private function createBooking($user, $flightData, $request)
    {
        // Build passenger data
        $passengerDetails = [];
        if ($request->has('passenger_details')) {
            foreach ($request->passenger_details as $index => $passenger) {
                $passengerDetails[] = [
                    'title' => $passenger['title'] ?? 'Mr',
                    'first_name' => $passenger['first_name'] ?? '',
                    'last_name' => $passenger['last_name'] ?? '',
                    'dob' => $passenger['dob'] ?? '',
                    'passport_number' => $passenger['passport_number'] ?? '',
                    'passport_expiry' => $passenger['passport_expiry'] ?? '',
                    'nationality' => $passenger['nationality'] ?? 'KE'
                ];
            }
        }
        
        // Create booking reference
        $bookingReference = 'DEST' . strtoupper(Str::random(6));
        
        // Extract destination data
        $destination = session('destination_search_params')['selected_destination'] ?? [];
        
        return Booking::create([
            'user_id' => $user->id,
            'role' => $user->role ?? 'customer',
            'booking_reference' => $bookingReference,
            'booking_type' => 'flight',
            'type' => 'flight',
            'title' => 'Flight to ' . ($destination['name'] ?? $flightData['itineraries'][0]['segments'][0]['arrival']['iataCode']),
            'amount' => $flightData['price']['grandTotal'],
            'total_amount' => $flightData['price']['grandTotal'],
            'currency' => $flightData['price']['currency'],
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $request->input('payment_method', 'mpesa'),
            'traveler_details' => json_encode([
                'contact_name' => $user->name,
                'contact_email' => $request->input('contact_email'),
                'contact_phone' => $request->input('contact_phone'),
                'passengers' => $passengerDetails
            ]),
            'flight_number' => $flightData['itineraries'][0]['segments'][0]['number'] ?? ($destination['flight_number'] ?? ''),
            'departure_airport' => $flightData['itineraries'][0]['segments'][0]['departure']['iataCode'],
            'arrival_airport' => $flightData['itineraries'][0]['segments'][0]['arrival']['iataCode'],
            'departure_time' => $flightData['itineraries'][0]['segments'][0]['departure']['at'],
            'arrival_time' => $flightData['itineraries'][0]['segments'][0]['arrival']['at'],
            'flight_details' => json_encode($flightData),
            'adults' => count($flightData['travelerPricings']),
            'travel_class' => $flightData['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'],
            'destination_details' => json_encode($destination),
        ]);
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
                dispatch(new SendPreTripReminderJob($booking, '24_hours'))
                    ->delay($departureTime->subHours(24));
            }
            
            // Schedule 2-hour reminder
            if ($departureTime->diffInHours(now()) > 2) {
                dispatch(new SendPreTripReminderJob($booking, '2_hours'))
                    ->delay($departureTime->subHours(2));
            }
            
            // Schedule check-in reminder (24 hours before departure)
            if ($departureTime->diffInHours(now()) > 24) {
                dispatch(new SendPreTripReminderJob($booking, 'check_in'))
                    ->delay($departureTime->subHours(24));
            }
        }
    }

    /**
     * Award loyalty points
     */
    private function awardLoyaltyPoints($booking)
    {
        if ($booking->user_id) {
            $user = $booking->user;
            $pointsEarned = floor($booking->amount * 0.1); // 10% of price as points
            
            // Update user's loyalty points
            $user->increment('loyalty_points', $pointsEarned);
            
            // Send loyalty points update email
            Mail::to($user->email)->send(new LoyaltyPointsUpdateMail(
                $user, 
                $pointsEarned, 
                $user->loyalty_points, 
                $booking
            ));
        }
    }

    /**
     * Store a new custom trip request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCustomTrip(Request $request)
    {
        // Validate form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'required|string',
            'travel_timing' => 'nullable|string|max:50',
            'subscribe_newsletter' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create a custom trip record
            $customTrip = CustomTrip::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
                'travel_timing' => $request->travel_timing,
                'subscribe_newsletter' => $request->has('subscribe_newsletter'),
                'user_id' => Auth::id(), // Associate with user if logged in
                'status' => 'new',
            ]);
            
            // If user opted for newsletter subscription
            if ($request->has('subscribe_newsletter') && $request->subscribe_newsletter) {
                // Add to newsletter list if not already subscribed
                $existingSubscriber = Newsletter::where('email', $request->email)->first();
                
                if (!$existingSubscriber) {
                    Newsletter::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'weekly_emails' => true,
                        'monthly_emails' => true,
                        'promotional_emails' => true,
                        'source' => 'custom_trip',
                    ]);
                } elseif (!$existingSubscriber->is_subscribed) {
                    // Reactivate if previously unsubscribed
                    $existingSubscriber->update(['is_subscribed' => true]);
                }
            }
            
            // Send notification emails
            // To customer
            try {
                Mail::to($request->email)->send(new CustomTripRequestMail($customTrip, 'customer'));
            } catch (\Exception $e) {
                // Log email sending failure but continue
                \Log::error("Failed to send customer email for custom trip: " . $e->getMessage());
            }
            
            // To admin
            try {
                Mail::to(config('mail.admin_address', 'admin@example.com'))->send(new CustomTripRequestMail($customTrip, 'admin'));
            } catch (\Exception $e) {
                // Log email sending failure but continue
                \Log::error("Failed to send admin email for custom trip: " . $e->getMessage());
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Your custom trip request has been submitted successfully!',
                'trip_id' => $customTrip->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit your request. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
