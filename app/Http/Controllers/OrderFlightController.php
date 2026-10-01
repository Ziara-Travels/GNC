<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminBookingNotification;
use App\Mail\BookingConfirmation;
use App\Mail\LoyaltyPointsUpdateMail;
use App\Jobs\SendBookingConfirmationJob;
use App\Jobs\SendPreTripReminderJob;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use App\Models\FlightBooking;
use App\Models\HotelBooking;
use App\Models\TourBooking;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;
use App\Models\UserReferral;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;

class OrderFlightController extends Controller
{
    protected $client;
    protected $jengaApiUrl;
    protected $jengaApiKey;
    protected $jengaConsumerSecret;
    protected $jengaTransactionUrl;
    protected $jengaPaymentGatewayUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
        // Read from config, and rtrim() so that appending '/auth/token' cannot
        // produce a double slash (".../v3.0//auth/token" -> 404 at the gateway).
        $this->jengaApiUrl = rtrim((string) config('services.jenga.base_url'), '/');
        $this->jengaApiKey = (string) config('services.jenga.api_key');
        $this->jengaConsumerSecret = (string) config('services.jenga.consumer_secret');
        $this->jengaTransactionUrl = rtrim((string) config('services.jenga.transaction_api_url'), '/');
        $this->jengaPaymentGatewayUrl = rtrim((string) config('services.jenga.payment_gateway_url'), '/');
    }

    // Show checkout page
    public function showCheckout()
    {
        // Still require login for checkout, but handle it with a more user-friendly approach
        if (!Auth::check()) {
            // Store intended URL so we can redirect back after login/register
            session(['url.intended' => url()->current()]);
            
            // Set checkout redirect flag for the registration process
            session(['checkout_redirect' => true]);
            
            // Redirect to login with a friendlier message
            return redirect()->route('login')->with([
                'message' => 'Please log in or create an account to complete your booking.',
                'alert_type' => 'info'
            ]);
        }

        $flightData = session('selected_flight');
        $hotelData = session('selected_hotel');

        if (!$flightData && !$hotelData) {
            return redirect()->route('home')->with([
                'message' => 'No booking data found. Please search and select a flight or hotel first.',
                'alert_type' => 'warning'
            ]);
        }

        if ($flightData && !session('booking_passenger_details')) {
            return redirect()->route('booking.passengers.show')->with([
                'message' => 'Please enter passenger details to continue.',
                'alert_type' => 'info'
            ]);
        }

        return view('booking.checkout');
    }

    public function bookingFailure(Request $request)
    {
        // Store failure information in session
        session([
            'failure_reason' => $request->get('reason', 'Payment processing failed'),
            'error_code' => $request->get('error_code', 'PAYMENT_ERROR'),
            'suggested_action' => $request->get('suggested_action', 'Please try again or contact support'),
            'failed_booking_data' => $request->get('booking_data', []),
            'failed_payment_method' => $request->get('payment_method', 'Unknown'),
            'failed_amount' => $request->get('amount', '0.00'),
            'failed_currency' => $request->get('currency', 'USD'),
            'failed_booking_reference' => $request->get('booking_reference', 'N/A'),
        ]);

        return view('booking.fail');
    }
    
    public function showSuccess($reference)
    {
        // Find the booking by reference
        $booking = Booking::where('booking_reference', $reference)->first();
        
        if (!$booking) {
            return redirect()->route('home')->with([
                'message' => 'Booking not found.',
                'alert_type' => 'error'
            ]);
        }
        
        // Ensure user has access to this booking
        if (Auth::check() && $booking->user_id !== Auth::id()) {
            return redirect()->route('home')->with([
                'message' => 'You do not have access to this booking.',
                'alert_type' => 'error'
            ]);
        }
        
        return view('booking.success', [
            'booking' => $booking
        ]);
    }

    /**
     * Download e-ticket as PDF
     */
    public function downloadETicket($reference)
    {
        $booking = Booking::with('user')->where('booking_reference', $reference)->first();

        if (!$booking) {
            abort(404, 'Booking not found.');
        }

        if (Auth::check() && $booking->user_id !== Auth::id() && !in_array(Auth::user()->role ?? '', ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('booking.eticket-pdf', compact('booking'));
        $pdf->setPaper('a4');

        return $pdf->download('eticket-' . $booking->booking_reference . '.pdf');
    }

    // Process checkout payment
    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_type' => 'required|in:flight,hotel',
            'booking_data' => 'required|json',
            'payment_method' => 'required|in:mpesa,mpesa_stk,bank,card,equitel,equity',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'booking_reference' => 'required|string',
            'mpesa_phone' => 'required_if:payment_method,mpesa,mpesa_stk|string',
            'equitel_phone' => 'required_if:payment_method,equitel|string',
            'bank_account' => 'required_if:payment_method,bank,equity|string',
            'bank_name' => 'required_if:payment_method,bank|string',
            'card_number' => 'required_if:payment_method,card|string',
            'card_expiry' => 'required_if:payment_method,card|string',
            'card_cvv' => 'required_if:payment_method,card|string',
            'cardholder_name' => 'required_if:payment_method,card|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $bookingData = json_decode($request->input('booking_data'), true);
        $bookingType = $request->input('booking_type');

        try {
            // Create booking record first
            $booking = $this->createBooking($user, $bookingData, $bookingType, $request);

            // Passenger details are now persisted on the booking row; clear
            // the session copy so a later, unrelated booking can't reuse it.
            session()->forget(['booking_passenger_details', 'booking_contact_email', 'booking_contact_phone']);

            // Create Amadeus booking if flight
            if ($bookingType === 'flight') {
                $amadeusResult = $this->createAmadeusBooking($booking, $bookingData);
                if (!$amadeusResult['success']) {
                    $booking->update(['status' => 'failed', 'notes' => 'Amadeus booking failed: ' . $amadeusResult['message']]);
                    throw new \Exception('Flight booking failed: ' . $amadeusResult['message']);
                }
                
                // Store Amadeus booking details
                $booking->update([
                    'amadeus_booking_id' => $amadeusResult['booking_id'],
                    'amadeus_booking_data' => $amadeusResult['booking_data'],
                    'e_ticket_data' => $amadeusResult['e_ticket_data'] ?? null
                ]);
            }

            // Process payment with Jenga API
            $paymentResult = $this->processPayment($booking, $request->input('payment_method'), $request);

            if ($paymentResult['success']) {
                // Handle successful payment
                $booking->update([
                    'payment_status' => 'completed',
                    'status' => 'confirmed',
                    'transaction_id' => $paymentResult['transaction_id'] ?? 'TRANS_' . time(),
                    'payment_reference' => $paymentResult['payment_reference'] ?? null
                ]);

                // Send confirmation emails
                $this->sendConfirmationEmails($booking);
                
                // Update dashboard statistics
                $this->updateDashboardStats($booking);

                // Return successful response for AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Payment processed successfully',
                        'redirect_url' => route('booking.success', $booking->booking_reference)
                    ]);
                }

                // Redirect to success page for regular form submission
                return redirect()->route('booking.success', $booking->booking_reference);
            } else {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);
                
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

                // Return error response for AJAX request
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment failed: ' . $paymentResult['message'],
                        'redirect_url' => route('booking.failure')
                    ]);
                }

                return redirect()->route('booking.failure')->with('error', 'Payment failed: ' . $paymentResult['message']);
            }
        } catch (\Exception $e) {
            Log::error('Booking process error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'booking_type' => $bookingType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Store failure details in session
            session([
                'failure_reason' => $e->getMessage(),
                'error_code' => 'BOOKING_FAILED',
                'suggested_action' => 'Please try again or contact support',
                'failed_payment_method' => $request->input('payment_method'),
                'failed_amount' => $request->input('amount'),
                'failed_currency' => $request->input('currency'),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking failed: ' . $e->getMessage(),
                    'redirect_url' => route('booking.failure')
                ]);
            }

            return redirect()->route('booking.failure')->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    // NEW: Enhanced store method with communication features
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_type' => 'required|in:flight,hotel',
            'flight_data' => 'required_if:booking_type,flight|json',
            'hotel_data' => 'required_if:booking_type,hotel|json',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'passengers' => 'required|array',
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Create booking
            $booking = Booking::create([
                'booking_reference' => $this->generateBookingReference(),
                'user_id' => auth()->id(),
                'flight_details' => $request->booking_type === 'flight' ? json_decode($request->flight_data, true) : null,
                'hotel_details' => $request->booking_type === 'hotel' ? json_decode($request->hotel_data, true) : null,
                'passengers' => $request->passengers,
                'amount' => $request->total_price,
                'currency' => $request->currency ?? 'USD',
                'status' => 'confirmed',
                'payment_status' => 'completed',
                'booking_type' => $request->booking_type,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
            ]);

            // Dispatch confirmation job
            SendBookingConfirmationJob::dispatch($booking);

            // Schedule pre-trip reminders (only for flights)
            if ($request->booking_type === 'flight') {
                $this->schedulePreTripReminders($booking);
            }

            // Award loyalty points
            $this->awardLoyaltyPoints($booking);

            return redirect()->route('booking.success', $booking->booking_reference);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    // NEW: Schedule pre-trip reminders
    private function schedulePreTripReminders($booking)
    {
        if (!$booking->flight_details || !isset($booking->flight_details['departure_time'])) {
            return;
        }

        $departureTime = Carbon::parse($booking->flight_details['departure_time']);
        
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

    // NEW: Award loyalty points
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

    // NEW: Generate booking reference
    private function generateBookingReference()
    {
        return 'BK' . strtoupper(uniqid()) . rand(100, 999);
    }

    /**
     * Derive the authoritative price from the offer payload itself rather than
     * trusting the client-submitted 'amount'/'currency' fields, which can be
     * edited in the checkout request independently of the selected offer.
     */
    private function resolveAuthoritativePrice($bookingData, $bookingType)
    {
        if ($bookingType === 'flight') {
            $amount = $bookingData['price']['total'] ?? null;
            $currency = $bookingData['price']['currency'] ?? null;
        } else {
            $amount = $bookingData['offers'][0]['price']['total'] ?? null;
            $currency = $bookingData['offers'][0]['price']['currency'] ?? null;
        }

        if ($amount === null || $currency === null) {
            throw new \Exception('Unable to determine booking price from the selected offer.');
        }

        return [(float) $amount, $currency];
    }

    private function createBooking($user, $bookingData, $bookingType, $request)
    {
        [$amount, $currency] = $this->resolveAuthoritativePrice($bookingData, $bookingType);

        $bookingFields = [
            'user_id' => $user->id,
            'booking_reference' => $this->generateBookingReference(),
            'booking_type' => $bookingType,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'payment_method' => $request->input('payment_method'),
            'payment_status' => 'pending',
            'contact_email' => session('booking_contact_email', $user->email),
            'contact_phone' => session('booking_contact_phone', $user->phone ?? ''),
        ];

        if ($bookingType === 'flight') {
            $passengers = session('booking_passenger_details', []);
            $types = array_column($passengers, 'type');

            $bookingFields = array_merge($bookingFields, [
                'flight_number' => $bookingData['itineraries'][0]['segments'][0]['number'] ?? null,
                'departure_airport' => $bookingData['itineraries'][0]['segments'][0]['departure']['iataCode'] ?? null,
                'arrival_airport' => $bookingData['itineraries'][0]['segments'][count($bookingData['itineraries'][0]['segments']) - 1]['arrival']['iataCode'] ?? null,
                'departure_time' => $bookingData['itineraries'][0]['segments'][0]['departure']['at'] ?? null,
                'arrival_time' => $bookingData['itineraries'][0]['segments'][count($bookingData['itineraries'][0]['segments']) - 1]['arrival']['at'] ?? null,
                'flight_details' => $bookingData,
                'passenger_details' => $passengers,
                'adults' => count(array_filter($types, fn ($t) => $t === 'ADULT')) ?: count($bookingData['travelerPricings']),
                'children' => count(array_filter($types, fn ($t) => $t === 'CHILD')),
                'infants' => count(array_filter($types, fn ($t) => in_array($t, ['HELD_INFANT', 'SEATED_INFANT']))),
                'travel_class' => $bookingData['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] ?? null,
            ]);
        } else {
            $bookingFields = array_merge($bookingFields, [
                'hotel_id' => $bookingData['hotel']['hotelId'] ?? null,
                'check_in_date' => $bookingData['offers'][0]['checkInDate'] ?? null,
                'check_out_date' => $bookingData['offers'][0]['checkOutDate'] ?? null,
                'hotel_details' => $bookingData,
                'adults' => $bookingData['adults'] ?? 1,
                'children' => $bookingData['children'] ?? 0,
            ]);
        }

        return Booking::create($bookingFields);
    }
    
    private function createAmadeusBooking($booking, $bookingData)
    {
        // RapidAPI/Skyscanner offers are metasearch results - there is no
        // Amadeus flight-order to create. Record the itinerary locally instead.
        if (isset($bookingData['source']) && $bookingData['source'] === 'skyscanner') {
            return [
                'success' => true,
                'booking_id' => 'SKY-' . $booking->booking_reference,
                'booking_data' => $bookingData,
                'e_ticket_data' => $this->buildSkyETicketData($bookingData),
                'message' => 'Flight itinerary recorded (Sky Scanner offer)',
            ];
        }

        try {
            $accessToken = $this->getAccessToken();
            
            $url = 'https://test.api.amadeus.com/v1/booking/flight-orders';
            
            // Prepare booking data for Amadeus
            $amadeusBookingData = [
                'data' => [
                    'type' => 'flight-order',
                    'flightOffers' => [$bookingData],
                    'travelers' => $this->prepareTravelersForAmadeus($booking),
                    'remarks' => [
                        'general' => [
                            [
                                'subType' => 'GENERAL_MISCELLANEOUS',
                                'text' => 'GNC Booking Reference: ' . $booking->booking_reference
                            ]
                        ]
                    ],
                    'contacts' => [
                        [
                            'addresseeName' => [
                                'firstName' => $booking->user->first_name ?? 'Traveler',
                                'lastName' => $booking->user->last_name ?? 'GNC'
                            ],
                            'companyName' => 'GNC Travel',
                            'purpose' => 'STANDARD',
                            'phones' => [
                                [
                                    'deviceType' => 'MOBILE',
                                    'countryCallingCode' => '254',
                                    'number' => str_replace('+254', '', $booking->contact_phone)
                                ]
                            ],
                            'emailAddress' => $booking->contact_email
                        ]
                    ]
                ]
            ];

            $response = $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => $amadeusBookingData
            ]);

            $responseData = json_decode($response->getBody(), true);
            
            if (isset($responseData['data'])) {
                // Extract e-ticket information
                $eTicketData = $this->extractETicketData($responseData['data']);
                
                return [
                    'success' => true,
                    'booking_id' => $responseData['data']['id'],
                    'booking_data' => $responseData['data'],
                    'e_ticket_data' => $eTicketData,
                    'message' => 'Flight booked successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Amadeus booking failed: Invalid response format'
                ];
            }
            
        } catch (GuzzleException $e) {
            Log::error('Amadeus booking failed', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            
            return [
                'success' => false,
                'message' => 'Failed to create flight booking: ' . $e->getMessage()
            ];
        }
    }
    
    private function prepareTravelersForAmadeus($booking)
    {
        $travelers = [];
        // passenger_details is cast to 'array' on the model already — do not
        // json_decode() it again (that throws a TypeError in PHP 8 once it's
        // actually populated, since the value is no longer a JSON string here).
        $passengerDetails = $booking->passenger_details ?? [];

        foreach ($passengerDetails as $index => $passenger) {
            $travelers[] = [
                'id' => (string)($index + 1),
                'dateOfBirth' => $passenger['date_of_birth'] ?? '1990-01-01',
                'name' => [
                    'firstName' => $passenger['first_name'] ?? 'Traveler',
                    'lastName' => $passenger['last_name'] ?? 'GNC'
                ],
                'gender' => strtoupper($passenger['gender'] ?? 'MALE'),
                'contact' => [
                    'emailAddress' => $passenger['email'] ?? $booking->contact_email,
                    'phones' => [
                        [
                            'deviceType' => 'MOBILE',
                            'countryCallingCode' => '254',
                            'number' => str_replace('+254', '', $passenger['phone'] ?? $booking->contact_phone)
                        ]
                    ]
                ],
                'documents' => [
                    [
                        'documentType' => 'PASSPORT',
                        'number' => $passenger['passport_number'] ?? 'A1234567',
                        'expiryDate' => $passenger['passport_expiry'] ?? '2030-12-31',
                        'issuanceCountry' => $passenger['passport_country'] ?? 'KE',
                        'validityCountry' => 'XX',
                        'nationality' => $passenger['nationality'] ?? 'KE',
                        'holder' => true
                    ]
                ]
            ];
        }
        
        return $travelers;
    }
    
    private function extractETicketData($amadeusBookingData)
    {
        $eTickets = [];
        
        if (isset($amadeusBookingData['flightOffers'][0]['itineraries'])) {
            foreach ($amadeusBookingData['flightOffers'][0]['itineraries'] as $itinerary) {
                foreach ($itinerary['segments'] as $segment) {
                    $eTickets[] = [
                        'ticket_number' => $amadeusBookingData['id'] . '-' . $segment['id'],
                        'flight_number' => $segment['carrierCode'] . $segment['number'],
                        'departure_airport' => $segment['departure']['iataCode'],
                        'arrival_airport' => $segment['arrival']['iataCode'],
                        'departure_time' => $segment['departure']['at'],
                        'arrival_time' => $segment['arrival']['at'],
                        'cabin_class' => $segment['cabin'] ?? 'ECONOMY',
                        'aircraft_type' => $segment['aircraft']['code'] ?? null
                    ];
                }
            }
        }
        
        return $eTickets;
    }

    /**
     * Build e-ticket style data from a normalized Sky Scanner offer so the
     * e-ticket PDF view keeps working without an Amadeus flight-order.
     */
    private function buildSkyETicketData($bookingData)
    {
        $eTickets = [];

        foreach (($bookingData['itineraries'] ?? []) as $itinerary) {
            foreach (($itinerary['segments'] ?? []) as $segment) {
                $eTickets[] = [
                    'ticket_number' => 'SKY-' . ($bookingData['id'] ?? '') . '-' . ($segment['number'] ?? ''),
                    'flight_number' => ($segment['carrierCode'] ?? '') . ($segment['number'] ?? ''),
                    'departure_airport' => $segment['departure']['iataCode'] ?? null,
                    'arrival_airport' => $segment['arrival']['iataCode'] ?? null,
                    'departure_time' => $segment['departure']['at'] ?? null,
                    'arrival_time' => $segment['arrival']['at'] ?? null,
                    'cabin_class' => $bookingData['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] ?? 'ECONOMY',
                    'aircraft_type' => $segment['aircraft'] ?? null,
                ];
            }
        }

        return $eTickets;
    }
    
    private function sendConfirmationEmails($booking)
    {
        try {
            // Send email to user
            Mail::to($booking->contact_email)->send(new BookingConfirmation($booking));
            
            // Send email to admin
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new AdminBookingNotification($booking));
            }
            
            Log::info('Confirmation emails sent', [
                'booking_id' => $booking->id,
                'user_email' => $booking->contact_email,
                'admin_emails' => $adminEmails ?? []
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation emails', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function processPayment($booking, $paymentMethod, $request)
    {
        try {
            // Create payment record first
            $payment = Payment::create([
                'user_id' => $booking->user_id,
                'booking_id' => $booking->id,
                'payment_reference' => $this->generatePaymentReference(),
                'amount' => $booking->amount,
                'currency' => $booking->currency,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
                'payment_details' => json_encode([
                    'phone_number' => $request->input('mpesa_phone'),
                    'account_holder_name' => $request->input('account_holder_name'),
                    'account_number' => $request->input('bank_account'),
                    'bank_name' => $request->input('bank_name'),
                    'card_number' => $request->input('card_number') ? '**** **** **** ' . substr($request->input('card_number'), -4) : null,
                    'cardholder_name' => $request->input('cardholder_name'),
                ])
            ]);

            // Process payment based on method using Jenga API
            switch ($paymentMethod) {
                case 'mpesa':
                case 'mpesa_stk':
                    return $this->processMpesaPayment($payment, $request->input('mpesa_phone'));
                
                case 'equitel':
                    return $this->processEquitelPayment($payment, $request->input('equitel_phone'));

                case 'equity':
                case 'bank':
                    return $this->processBankPayment($payment, $request->input('bank_account'), $request->input('bank_name', 'Equity Bank'));
                
                case 'card':
                    $cardDetails = [
                        'card_number' => $request->input('card_number'),
                        'expiry_month' => substr($request->input('card_expiry'), 0, 2),
                        'expiry_year' => substr($request->input('card_expiry'), 3, 2),
                        'cvv' => $request->input('card_cvv'),
                        'cardholder_name' => $request->input('cardholder_name')
                    ];
                    return $this->processCardPayment($payment, $cardDetails);
                
                default:
                    $payment->update(['status' => 'failed']);
                    return [
                        'success' => false,
                        'message' => 'Unsupported payment method',
                        'error_code' => 'UNSUPPORTED_METHOD'
                    ];
            }
        } catch (\Exception $e) {
            Log::error('Payment processing error', [
                'booking_id' => $booking->id,
                'payment_method' => $paymentMethod,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage(),
                'error_code' => 'PAYMENT_ERROR'
            ];
        }
    }

    /**
     * Detect card type based on card number
     * 
     * @param string $cardNumber
     * @return string
     */
    private function detectCardType($cardNumber)
{
    // Remove any non-digit characters
    $cardNumber = preg_replace('/\D/', '', $cardNumber);
    
    // Check card patterns
    if (preg_match('/^4/', $cardNumber)) {
        return 'VISA';
    } elseif (preg_match('/^(5[1-5])/', $cardNumber)) {
        return 'MASTERCARD';
    } elseif (preg_match('/^3[47]/', $cardNumber)) {
        return 'AMEX';
    } elseif (preg_match('/^6(?:011|5)/', $cardNumber)) {
        return 'DISCOVER';
    } elseif (preg_match('/^(?:2131|1800|35)/', $cardNumber)) {
        return 'JCB';
    } elseif (preg_match('/^3(?:0[0-5]|[68])/', $cardNumber)) {
        return 'DINERS';
    } elseif (preg_match('/^(62|88)/', $cardNumber)) {
        return 'UNIONPAY';
    } else {
        return 'UNKNOWN';
    }
}


    private function processMpesaPayment($booking, $phoneNumber)
{
    try {
        // Ensure phone number is properly formatted (remove leading zeros if present and ensure it's at least 9 digits)
        $phoneNumber = preg_replace('/^0+/', '', $phoneNumber);
        if (strlen($phoneNumber) < 9) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format',
                'error_code' => 'INVALID_PHONE'
            ];
        }

        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for M-Pesa payment');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }

        // Prepare payment request data
        $paymentData = [
            'customer' => [
                'mobileNumber' => $phoneNumber,
                'countryCode' => 'KE',
            ],
            'transaction' => [
                'amount' => $booking->amount,
                'currency' => $booking->currency,
                'reference' => $booking->booking_reference,
                'description' => ucfirst($booking->booking_type) . ' Booking Payment'
            ]
        ];

        // Make API request to Jenga
        try {
            $response = $this->client->post($this->jengaTransactionUrl . '/v2/payments', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $paymentData,
                'timeout' => 30, // Increase timeout to handle slow API responses
            ]);

            $result = json_decode($response->getBody(), true);
            
            if (isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'transaction_id' => $result['transactionId'] ?? 'MPESA_' . time(),
                    'message' => 'M-Pesa payment successful'
                ];
            } else {
                \Log::error('M-Pesa payment failed with result: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'M-Pesa payment failed: ' . ($result['message'] ?? 'Unknown error'),
                    'error_code' => 'MPESA_FAILED'
                ];
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error('M-Pesa API error: ' . $e->getMessage());
            
            // For demo purposes in development, simulate success
            if (app()->environment('local', 'development')) {
                return [
                    'success' => true,
                    'transaction_id' => 'MPESA_DEV_' . time(),
                    'message' => 'M-Pesa payment successful (demo)'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'M-Pesa payment service unavailable: ' . $e->getMessage(),
                'error_code' => 'MPESA_SERVICE_ERROR'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('M-Pesa processing error: ' . $e->getMessage());
        
        // For demo purposes in development, simulate success
        if (app()->environment('local', 'development')) {
            return [
                'success' => true,
                'transaction_id' => 'MPESA_DEV_' . time(),
                'message' => 'M-Pesa payment successful (demo)'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error processing M-Pesa payment: ' . $e->getMessage(),
            'error_code' => 'MPESA_PROCESS_ERROR'
        ];
    }
}

    private function processBankPayment($booking, $accountNumber, $bankName)
{
    try {
        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for Bank payment');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }

        // Prepare bank transfer request data
        $transferData = [
            'source' => [
                'accountNumber' => $accountNumber,
                'bankName' => $bankName,
            ],
            'destination' => [
                'accountNumber' => env('JENGA_ACCOUNT_NUMBER', 'GNC001'),
                'bankName' => env('JENGA_BANK_NAME', 'KCB'),
            ],
            'transaction' => [
                'amount' => $booking->amount,
                'currency' => $booking->currency,
                'reference' => $booking->booking_reference,
                'description' => ucfirst($booking->booking_type) . ' Booking Payment'
            ]
        ];

        // Make API request to Jenga
        try {
            $response = $this->client->post($this->jengaTransactionUrl . '/v2/remittance', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $transferData,
                'timeout' => 30, // Increase timeout to handle slow API responses
            ]);

            $result = json_decode($response->getBody(), true);
            
            if (isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'transaction_id' => $result['transactionId'] ?? 'BANK_' . time(),
                    'message' => 'Bank payment successful'
                ];
            } else {
                \Log::error('Bank payment failed with result: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'Bank payment failed: ' . ($result['message'] ?? 'Unknown error'),
                    'error_code' => 'BANK_FAILED'
                ];
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error('Bank API error: ' . $e->getMessage());
            
            // For demo purposes in development, simulate success
            if (app()->environment('local', 'development')) {
                return [
                    'success' => true,
                    'transaction_id' => 'BANK_DEV_' . time(),
                    'message' => 'Bank payment successful (demo)'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Bank payment service unavailable: ' . $e->getMessage(),
                'error_code' => 'BANK_SERVICE_ERROR'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('Bank processing error: ' . $e->getMessage());
        
        // For demo purposes in development, simulate success
        if (app()->environment('local', 'development')) {
            return [
                'success' => true,
                'transaction_id' => 'BANK_DEV_' . time(),
                'message' => 'Bank payment successful (demo)'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error processing Bank payment: ' . $e->getMessage(),
            'error_code' => 'BANK_PROCESS_ERROR'
        ];
    }
}

    private function sendBookingConfirmationEmails($booking)
    {
        $user = $booking->user;
        
        // Send email to user with e-ticket
        Mail::to($user->email)->send(new BookingConfirmation($booking));
        
        // Send email to specific admin email
        Mail::to('admin@omniviatech.co.ke')->send(new AdminBookingNotification($booking));
        
        // Send email to other admins and superadmins
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new AdminBookingNotification($booking));
        }
        
        // Create user notification in database
        if ($user) {
            \App\Models\Notification::create([
                'user_id' => $user->id,
                'title' => 'Booking Confirmed',
                'message' => 'Your booking #' . $booking->booking_reference . ' has been confirmed.',
                'type' => 'booking_success',
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                ],
                'action_url' => route('bookings.detail', $booking->booking_reference),
                'priority' => 'high'
            ]);
        }
        
        // Create admin notifications in database
        $adminUser = User::where('email', 'admin@omniviatech.co.ke')->first();
        if ($adminUser) {
            \App\Models\Notification::create([
                'user_id' => $adminUser->id,
                'title' => 'New Booking Created',
                'message' => 'A new booking #' . $booking->booking_reference . ' has been created by ' . $user->name,
                'type' => 'new_booking',
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'user_id' => $user->id,
                ],
                'action_url' => route('admin.bookings.all'),
                'priority' => 'medium'
            ]);
        }
    }

    public function bookingSuccess($bookingReference)
    {
        $booking = Booking::where('booking_reference', $bookingReference)->firstOrFail();
        
        // Check if user can access this booking
        if (Auth::id() !== $booking->user_id) {
            abort(403);
        }

        return view('booking.success', compact('booking'));
    }

    // Flight Selection - Updated to handle both guest and authenticated users
    public function selectFlight(Request $request)
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

        // User is authenticated, proceed to passenger details before checkout
        session(['selected_flight' => $flightData]);
        session()->forget('booking_passenger_details');

        return redirect()->route('booking.passengers.show')->with([
            'message' => 'Flight selected successfully. Please enter passenger details.',
            'alert_type' => 'success'
        ]);
    }

    /**
     * Derive the ordered list of traveler types (ADULT/CHILD/HELD_INFANT/...)
     * from the Amadeus offer's travelerPricings, so the passenger form asks
     * for exactly the passengers that were searched for.
     */
    private function getTravelerTypesFromOffer($flightData)
    {
        $pricings = $flightData['travelerPricings'] ?? [];

        if (empty($pricings)) {
            return ['ADULT'];
        }

        return array_map(function ($pricing) {
            return $pricing['travelerType'] ?? 'ADULT';
        }, $pricings);
    }

    /**
     * Show the passenger details form, sized to the travelers on the selected offer.
     */
    public function showPassengerDetails()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $rawFlight = session('selected_flight');
        $flightData = is_string($rawFlight) ? json_decode($rawFlight, true) : $rawFlight;

        if (!$flightData) {
            return redirect()->route('home')->with([
                'message' => 'No flight selected. Please search and select a flight first.',
                'alert_type' => 'warning'
            ]);
        }

        $travelerTypes = $this->getTravelerTypesFromOffer($flightData);

        return view('booking.passenger-details-wizard', [
            'travelerTypes' => $travelerTypes,
            'flightData' => $flightData,
        ]);
    }

    /**
     * Persist passenger details in session (not yet in the DB — the Booking
     * row doesn't exist until processCheckout runs) and continue to checkout.
     */
    public function savePassengerDetails(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $rawFlight = session('selected_flight');
        $flightData = is_string($rawFlight) ? json_decode($rawFlight, true) : $rawFlight;

        if (!$flightData) {
            return redirect()->route('home')->with([
                'message' => 'No flight selected. Please search and select a flight first.',
                'alert_type' => 'warning'
            ]);
        }

        $travelerTypes = $this->getTravelerTypesFromOffer($flightData);
        $expectedCount = count($travelerTypes);

        $validated = $request->validate([
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:30',
            'passengers' => "required|array|size:{$expectedCount}",
            'passengers.*.first_name' => 'required|string|max:100',
            'passengers.*.last_name' => 'required|string|max:100',
            'passengers.*.date_of_birth' => 'required|date|before:today',
            'passengers.*.gender' => 'required|in:male,female',
            'passengers.*.nationality' => 'required|string|max:2',
            'passengers.*.passport_number' => 'required|string|max:30',
            'passengers.*.passport_expiry' => 'required|date|after:today',
            'passengers.*.passport_country' => 'required|string|max:2',
        ]);

        // Attach the traveler type Amadeus originally priced this seat as,
        // so prepareTravelersForAmadeus() stays consistent with the offer.
        $passengers = array_map(function ($passenger, $index) use ($travelerTypes) {
            $passenger['type'] = $travelerTypes[$index] ?? 'ADULT';
            $passenger['email'] = $passenger['email'] ?? null;
            $passenger['phone'] = $passenger['phone'] ?? null;
            return $passenger;
        }, $validated['passengers'], array_keys($validated['passengers']));

        session([
            'booking_passenger_details' => $passengers,
            'booking_contact_email' => $validated['contact_email'],
            'booking_contact_phone' => $validated['contact_phone'],
        ]);

        return redirect()->route('booking.checkout');
    }

    // Hotel Selection
    public function selectHotel(Request $request)
    {
        $hotelData = json_decode($request->input('hotel_data'), true);
        
        if (!Auth::check()) {
            session(['pending_hotel_booking' => $hotelData]);
            
            return redirect()->route('login')->with([
                'message' => 'Please login or create an account to proceed with booking.',
                'alert_type' => 'info'
            ]);
        }

        session(['selected_hotel' => $hotelData]);
        
        return redirect()->route('booking.show', ['type' => 'hotel'])->with([
            'message' => 'Hotel selected successfully. Please review your booking details.',
            'alert_type' => 'success'
        ]);
    }

    // Show booking page during checkout
    public function showBookingPage(Request $request)
    {
        $type = $request->route('type');
        $bookingData = session("selected_{$type}");
        
        if (!$bookingData) {
            return redirect()->route('home')->with([
                'message' => 'No ' . $type . ' selected. Please search and select a ' . $type . ' first.',
                'alert_type' => 'warning'
            ]);
        }

        // Check if user is logged in and set flag for view
        $isGuest = !Auth::check();
        
        return view('booking.booking', compact('bookingData', 'type', 'isGuest'));
    }

    private function getAccessToken()
    {
        if (session('access_token')) {
            return session('access_token');
        }

        $access_token = app('App\Http\Controllers\AccessTokenController')
            ->__invoke($this->client)->access_token;
        session(['access_token' => $access_token]);
        
        return $access_token;
    }

    private function getJengaAccessToken()
    {
        if ($this->jengaApiKey === '' || $this->jengaConsumerSecret === '') {
            throw new \Exception('Jenga is not configured: JENGA_API_KEY and JENGA_CONSUMER_SECRET are required.');
        }

        // Cache the token (30 min) so every payment does not trigger a fresh
        // token round-trip. Falls back to no caching if the cache is unusable.
        return Cache::remember('jenga_access_token', 1800, function () {
            $response = Http::asForm()->post($this->jengaApiUrl . '/auth/token', [
                'api_key'        => $this->jengaApiKey,
                'consumer_secret'=> $this->jengaConsumerSecret,
                'grant_type'     => 'client_credentials',
            ]);

            $token = $response->successful() ? $response->json('access_token') : null;

            if (!$token) {
                Log::error('Jenga token request failed', [
                    'status' => $response->status(),
                    'body'   => mb_substr($response->body(), 0, 500),
                ]);

                throw new \Exception('Failed to obtain Jenga access token: ' . $response->status());
            }

            return $token;
        });
    }

    public function applyReferralDiscount($userId, $bookingAmount)
    {
        $user = User::find($userId);
        
        if (!$user || !$user->referred_by) {
            return ['discount' => 0, 'final_amount' => $bookingAmount];
        }

        $referral = UserReferral::where('referred_user_id', $userId)
            ->where('discount_applied', false)
            ->where('status', 'pending')
            ->first();

        if (!$referral) {
            return ['discount' => 0, 'final_amount' => $bookingAmount];
        }

        // Calculate discount
        $discountAmount = ($bookingAmount * $referral->reward_percentage) / 100;
        $finalAmount = $bookingAmount - $discountAmount;

        // Update referral record
        $referral->update([
            'discount_applied' => true,
            'status' => 'applied',
            'commission_earned' => $discountAmount
        ]);

        return [
            'discount' => $discountAmount,
            'final_amount' => $finalAmount,
            'referral_code' => $referral->referralCode->code,
            'referrer_name' => $referral->referrer->name
        ];
    }

    public function paymentStatus($paymentId)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view')) {
            abort(403, 'Unauthorized access');
        }

        $payment = Payment::with(['user', 'booking'])->findOrFail($paymentId);

        // Check real-time status from Jenga API
        $this->updatePaymentStatus($payment);

        return view('dashboard.payments.status', compact('payment'));
    }

    private function updatePaymentStatus($payment)
    {
        if ($payment->status === 'completed' || $payment->status === 'failed') {
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getJengaAccessToken(),
                'Content-Type' => 'application/json',
            ])->get($this->jengaApiUrl . '/payment/status/' . $payment->provider_transaction_id);

            if ($response->successful()) {
                $status = $response->json('status');
                $payment->update([
                    'status' => $this->mapJengaStatus($status),
                    'provider_response' => json_encode($response->json()),
                    'updated_at' => now()
                ]);

                if ($payment->status === 'completed') {
                    $payment->booking->update(['status' => 'confirmed']);
                } elseif ($payment->status === 'failed') {
                    $payment->booking->update(['status' => 'cancelled']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Payment status update error: ' . $e->getMessage());
        }
    }

    // Imported from PaymentController
    private function mapJengaStatus($jengaStatus)
    {
        $statusMap = [
            'SUCCESS' => 'completed',
            'FAILED' => 'failed',
            'PENDING' => 'processing',
            'CANCELLED' => 'failed',
        ];

        return $statusMap[$jengaStatus] ?? 'pending';
    }

    // Imported from PaymentController
    private function getBankCode($bankName)
    {
        $bankCodes = [
            'KCB' => '01',
            'Equity' => '68',
            'Standard Chartered' => '02',
            'Barclays' => '03',
            'Co-operative' => '11',
            'NCBA' => '07',
        ];

        return $bankCodes[$bankName] ?? '01';
    }

    // Imported from PaymentController
    private function generatePaymentReference()
    {
        return 'PAY-' . strtoupper(uniqid()) . '-' . now()->format('YmdHis');
    }
    
    // Imported from PaymentController - Adding the callback handler
    public function handleJengaCallback(Request $request)
    {
        // Log the raw callback for debugging
        Log::channel('payments')->info('Jenga Callback received', [
            'payload' => $request->all(),
            'headers' => $request->header()
        ]);
        
        try {
            // Validate basic structure of the callback
            $request->validate([
                'callbackType' => 'required|string',
                'customer' => 'required|array',
                'transaction' => 'required|array',
                'transaction.reference' => 'required|string',
                'transaction.status' => 'required|string'
            ]);

            // Extract relevant data. NOTE: this endpoint has no session/CSRF
            // protection (it's a server-to-server webhook) and Jenga does not
            // sign callbacks, so the body is untrusted input. We only use it to
            // learn *which* transaction to look at, then independently ask
            // Jenga's own status API for the authoritative outcome below —
            // an attacker posting a forged "SUCCESS" body cannot make Jenga's
            // API agree with them.
            $callbackType = $request->input('callbackType');
            $transactionRef = $request->input('transaction.reference');
            $paymentMode = $request->input('transaction.paymentMode');
            $remarks = $request->input('transaction.remarks');
            $customerRef = $request->input('customer.reference');

            // Find the booking by reference
            $booking = Booking::where('booking_reference', $customerRef)
                ->orWhere('transaction_id', $transactionRef)
                ->first();

            if (!$booking) {
                Log::channel('payments')->warning('Booking not found for Jenga callback', [
                    'transaction_reference' => $transactionRef,
                    'customer_reference' => $customerRef
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Booking not found'
                ], 404);
            }

            // Independently verify the transaction outcome with Jenga rather
            // than trusting the callback body's 'transaction.status'.
            $verifiedStatus = $this->verifyJengaTransactionStatus($transactionRef);

            if ($verifiedStatus === null) {
                Log::channel('payments')->warning('Could not independently verify Jenga transaction status; ignoring callback', [
                    'transaction_reference' => $transactionRef,
                    'booking_reference' => $booking->booking_reference,
                    'claimed_status' => $request->input('transaction.status'),
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Unable to verify transaction status'
                ], 409);
            }

            $status = $verifiedStatus['status'];
            $amount = $verifiedStatus['amount'] ?? $booking->amount;
            $currency = $verifiedStatus['currency'] ?? $booking->currency;

            // Find or create payment record
            $payment = Payment::where('booking_id', $booking->id)->first();

            if (!$payment) {
                $payment = new Payment();
                $payment->user_id = $booking->user_id;
                $payment->booking_id = $booking->id;
                $payment->payment_reference = $this->generatePaymentReference();
                $payment->amount = $amount;
                $payment->currency = $currency;
                $payment->payment_method = strtolower($paymentMode);
            }

            // Update payment status
            $payment->status = $status === 'SUCCESS' ? 'completed' : 'failed';
            $payment->provider_transaction_id = $transactionRef;
            $payment->provider_response = json_encode($verifiedStatus['raw']);
            $payment->save();

            // Update booking status based on the Jenga-verified payment result
            if ($status === 'SUCCESS') {
                $booking->payment_status = 'completed';
                $booking->status = 'confirmed';
                $booking->transaction_id = $transactionRef;
                $booking->save();
                
                // Send confirmation email
                $this->sendBookingConfirmationEmails($booking);
                
                Log::channel('payments')->info('Payment successful', [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'transaction_reference' => $transactionRef
                ]);
            } else {
                $booking->payment_status = 'failed';
                $booking->status = 'pending';
                $booking->save();
                
                Log::channel('payments')->warning('Payment failed', [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'transaction_reference' => $transactionRef,
                    'remarks' => $remarks
                ]);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Callback processed successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::channel('payments')->error('Error processing Jenga callback', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Error processing callback: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Query Jenga's own transaction-status API for the authoritative outcome
     * of a transaction, instead of trusting a caller-supplied status. Returns
     * null if the status can't be confirmed (network/API failure), which
     * callers must treat as "not verified" rather than falling back to any
     * caller-supplied claim.
     */
    private function verifyJengaTransactionStatus($transactionRef)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getJengaAccessToken(),
                'Content-Type' => 'application/json',
            ])->get($this->jengaApiUrl . '/payment/status/' . $transactionRef);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $status = $data['status'] ?? null;

            if (!$status) {
                return null;
            }

            return [
                'status' => $this->mapJengaStatus($status) === 'completed' ? 'SUCCESS' : 'FAILED',
                'amount' => $data['amount'] ?? null,
                'currency' => $data['currency'] ?? null,
                'raw' => $data,
            ];
        } catch (\Exception $e) {
            Log::channel('payments')->error('Jenga transaction status verification failed', [
                'transaction_reference' => $transactionRef,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    // Add payment dashboard methods
    public function flightPayments()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_all')) {
            abort(403, 'Unauthorized access');
        }

        $flightPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.booking_type', 'flight')
            ->with(['user', 'booking'])
            ->select('payments.*')
            ->paginate(20);

        $flightStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'flight')
                ->count(),
            'total_revenue' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'flight')
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'flight')
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'flight')
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.flights', compact('flightPayments', 'flightStats'));
    }

    public function hotelPayments()
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.view_all')) {
            abort(403, 'Unauthorized access');
        }

        $hotelPayments = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.booking_type', 'hotel')
            ->with(['user', 'booking'])
            ->select('payments.*')
            ->paginate(20);

        $hotelStats = [
            'total_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'hotel')
                ->count(),
            'total_revenue' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'hotel')
                ->sum('payments.amount'),
            'pending_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'hotel')
                ->where('payments.status', 'pending')
                ->count(),
            'completed_payments' => Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->where('bookings.booking_type', 'hotel')
                ->where('payments.status', 'completed')
                ->count(),
        ];

        return view('dashboard.payments.hotels', compact('hotelPayments', 'hotelStats'));
    }

    public function exportPayments(Request $request)
    {
        $user = auth()->user();
        
        if (!$user->hasPermission('payments.export')) {
            abort(403, 'Unauthorized access');
        }

        $type = $request->input('type', 'all');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new PaymentsExport($type, $startDate, $endDate), 'payments-' . now()->format('Y-m-d') . '.xlsx');
    }

    private function processCardPayment($payment, $cardDetails)
{
    try {
        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for Card payment');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }

        // Generate signature for the card payment
        $signature = $this->generateCardSignature($cardDetails);

        // Prepare card payment request data
        $paymentData = [
            'cardDetails' => [
                'cardNumber' => $cardDetails['card_number'],
                'cvv' => $cardDetails['cvv'],
                'expiryMonth' => $cardDetails['expiry_month'],
                'expiryYear' => $cardDetails['expiry_year'],
                'cardHolderName' => $cardDetails['cardholder_name']
            ],
            'transaction' => [
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'reference' => $payment->payment_reference,
                'description' => ucfirst($payment->booking->booking_type) . ' Booking Payment'
            ],
            'billingAddress' => [
                'address1' => $cardDetails['billing_address'] ?? 'Address Line 1',
                'city' => $cardDetails['billing_city'] ?? 'City',
                'country' => $cardDetails['billing_country'] ?? 'KE',
                'zipCode' => $cardDetails['billing_zip'] ?? '00100'
            ],
            'callbackUrl' => route('payments.jenga.callback')
        ];

        // Make API request to Jenga
        try {
            $response = $this->client->post($this->jengaTransactionUrl . '/v2/payments/card', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'Signature' => $signature
                ],
                'json' => $paymentData,
                'timeout' => 30 // Increase timeout to handle slow API responses
            ]);

            $result = json_decode($response->getBody(), true);
            
            if (isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'transaction_id' => $result['transactionId'] ?? 'CARD_' . time(),
                    'message' => 'Card payment successful',
                    'response' => $result
                ];
            } else {
                \Log::error('Card payment failed with result: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'Card payment failed: ' . ($result['message'] ?? 'Unknown error'),
                    'error_code' => 'CARD_PAYMENT_FAILED'
                ];
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error('Card API error: ' . $e->getMessage());
            
            // For demo purposes in development, simulate success
            if (app()->environment('local', 'development')) {
                return [
                    'success' => true,
                    'transaction_id' => 'CARD_DEV_' . time(),
                    'message' => 'Card payment successful (demo)',
                    'response' => ['simulated' => true]
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Card payment service unavailable: ' . $e->getMessage(),
                'error_code' => 'CARD_SERVICE_ERROR'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('Card processing error: ' . $e->getMessage());
        
        // For demo purposes in development, simulate success
        if (app()->environment('local', 'development')) {
            return [
                'success' => true,
                'transaction_id' => 'CARD_DEV_' . time(),
                'message' => 'Card payment successful (demo)',
                'response' => ['simulated' => true]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error processing card payment: ' . $e->getMessage(),
            'error_code' => 'CARD_PROCESS_ERROR'
        ];
    }
}

/**
 * Generate signature for card payment
 * 
 * @param array $cardDetails
 * @return string
 */
private function generateCardSignature($cardDetails)
{
    $merchantCode = config('services.jenga.merchant_code');
    $apiKey = config('services.jenga.api_key');
    
    $signatureData = $merchantCode . $cardDetails['card_number'] . $cardDetails['cvv'];
    
    return base64_encode(hash_hmac('sha256', $signatureData, $apiKey, true));
}

/**
 * Process Mpesa STK Push payment through Jenga API
 * 
 * @param Payment $payment
 * @param string $phoneNumber
 * @return array
 */
// private function processMpesaStkPush($payment, $phoneNumber)
// {
//     try {
//         // Ensure phone number is properly formatted
//         $phoneNumber = preg_replace('/^0+/', '', $phoneNumber);
//         if (strlen($phoneNumber) < 9) {
//             return [
//                 'success' => false,
//                 'message' => 'Invalid phone number format',
//                 'error_code' => 'INVALID_PHONE'
//             ];
//         }
        
//         $formattedPhone = '254' . $phoneNumber;
        
//         // Get Jenga access token
//         $accessToken = $this->getJengaAccessToken();
        
//         if (!$accessToken) {
//             \Log::error('Failed to get Jenga access token for M-Pesa STK Push');
//             return [
//                 'success' => false,
//                 'message' => 'Payment gateway authentication failed',
//                 'error_code' => 'AUTH_FAILED'
//             ];
//         }
        
//         // Prepare STK push request data
//         $stkData = [
//             'customer' => [
//                 'mobileNumber' => $formattedPhone,
//                 'countryCode' => 'KE'
//             ],
//             'transaction' => [
//                 'amount' => $payment->amount,
//                 'currency' => $payment->currency,
//                 'reference' => $payment->payment_reference,
//                 'description' => ucfirst($payment->booking->booking_type) . ' Booking Payment'
//             ],
//             'paymentDetails' => [
//                 'method' => 'mpesa-stk-push',
//                 'billReference' => '389626',
//                 'businessNumber' => '247247'  // Paybill number
//             ],
//             'callbackUrl' => route('payments.jenga.callback')
//         ];
        
//         // Make API request to Jenga
//         try {
//             $response = $this->client->post('https://sandbox.jengahq.io/transaction/v2/payments/mpesa/stkpush', [
//                 'headers' => [
//                     'Authorization' => 'Bearer ' . $accessToken,
//                     'Content-Type' => 'application/json',
//                 ],
//                 'json' => $stkData,
//                 'timeout' => 30
//             ]);
            
//             $result = json_decode($response->getBody(), true);
            
//             if (isset($result['status']) && $result['status'] === 'success') {
//                 return [
//                     'success' => true,
//                     'transaction_id' => $result['transactionId'] ?? 'MPESA_STK_' . time(),
//                     'message' => 'M-Pesa STK Push initiated. Please check your phone to complete the payment.',
//                     'response' => $result
//                 ];
//             } else {
//                 \Log::error('M-Pesa STK Push failed with result: ' . json_encode($result));
//                 return [
//                     'success' => false,
//                     'message' => 'M-Pesa STK Push failed: ' . ($result['message'] ?? 'Unknown error'),
//                     'error_code' => 'MPESA_STK_FAILED'
//                 ];
//             }
//         } catch (\GuzzleHttp\Exception\GuzzleException $e) {
//             \Log::error('M-Pesa STK Push API error: ' . $e->getMessage());
            
//             // For demo purposes in development, simulate success
//             if (app()->environment('local', 'development')) {
//                 return [
//                     'success' => true,
//                     'transaction_id' => 'MPESA_STK_DEV_' . time(),
//                     'message' => 'M-Pesa STK Push successful (demo)',
//                     'response' => ['simulated' => true]
//                 ];
//             }
            
//             return [
//                 'success' => false,
//                 'message' => 'M-Pesa STK Push service unavailable: ' . $e->getMessage(),
//                 'error_code' => 'MPESA_STK_SERVICE_ERROR'
//             ];
//         }
//     } catch (\Exception $e) {
//         \Log::error('M-Pesa STK Push processing error: ' . $e->getMessage());
        
//         // For demo purposes in development, simulate success
//         if (app()->environment('local', 'development')) {
//             return [
//                 'success' => true,
//                 'transaction_id' => 'MPESA_STK_DEV_' . time(),
//                 'message' => 'M-Pesa STK Push successful (demo)',
//                 'response' => ['simulated' => true]
//             ];
//         }
        
//         return [
//             'success' => false,
//             'message' => 'Error processing M-Pesa STK Push: ' . $e->getMessage(),
//             'error_code' => 'MPESA_STK_PROCESS_ERROR'
//         ];
//     }
// }

private function processMpesaStkPush($payment, $phoneNumber)
{
    try {
        // Ensure phone number is properly formatted
        $phoneNumber = preg_replace('/^0+/', '', $phoneNumber);
        if (strlen($phoneNumber) < 9) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format',
                'error_code' => 'INVALID_PHONE'
            ];
        }
        
        $formattedPhone = '254' . $phoneNumber;
        
        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for M-Pesa STK Push');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }

        // Generate signature for the STK Push
        $signature = $this->generateMpesaSignature($payment->payment_reference);
        
        // Prepare STK push request data according to the new API format
        $stkData = [
            "merchant" => [
                "accountNumber" => env('JENGA_ACCOUNT_NUMBER', '0170194290581'),
                "countryCode" => "KE",
                "name" => env('APP_NAME', 'Ziara-Travels')
            ],
            "payment" => [
                "ref" => $payment->payment_reference,
                "amount" => (string)$payment->amount,
                "currency" => $payment->currency,
                "telco" => "Safaricom",
                "mobileNumber" => $formattedPhone,
                "date" => date('Y-m-d'),
                "callBackUrl" => route('payments.jenga.callback'),
                "pushType" => "USSD"
            ]
        ];
        
        // Make API request to Jenga
        try {
            $response = $this->client->post('https://uat.finserve.africa/v3-apis/payment-api/v3.0/stkussdpush/initiate', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'Signature' => $signature
                ],
                'json' => $stkData,
                'timeout' => 30
            ]);
            
            $result = json_decode($response->getBody(), true);
            
            if (isset($result['status']) && $result['status'] === true) {
                // Store the transaction reference for checking status later
                $transactionId = $result['transactionId'] ?? $result['reference'] ?? null;
                
                return [
                    'success' => true,
                    'transaction_id' => $transactionId,
                    'message' => 'M-Pesa STK Push initiated. Please check your phone to complete the payment.',
                    'response' => $result
                ];
            } else {
                \Log::error('M-Pesa STK Push failed with result: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'M-Pesa STK Push failed: ' . ($result['message'] ?? 'Unknown error'),
                    'error_code' => 'MPESA_STK_FAILED'
                ];
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error('M-Pesa STK Push API error: ' . $e->getMessage());
            
            // For demo purposes in development, simulate success
            if (app()->environment('local', 'development')) {
                return [
                    'success' => true,
                    'transaction_id' => 'MPESA_STK_DEV_' . time(),
                    'message' => 'M-Pesa STK Push successful (demo)',
                    'response' => ['simulated' => true]
                ];
            }
            
            return [
                'success' => false,
                'message' => 'M-Pesa STK Push service unavailable: ' . $e->getMessage(),
                'error_code' => 'MPESA_STK_SERVICE_ERROR'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('M-Pesa STK Push processing error: ' . $e->getMessage());
        
        // For demo purposes in development, simulate success
        if (app()->environment('local', 'development')) {
            return [
                'success' => true,
                'transaction_id' => 'MPESA_STK_DEV_' . time(),
                'message' => 'M-Pesa STK Push successful (demo)',
                'response' => ['simulated' => true]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error processing M-Pesa STK Push: ' . $e->getMessage(),
            'error_code' => 'MPESA_STK_PROCESS_ERROR'
        ];
    }
}

/**
 * Generate signature for Mpesa STK Push
 * 
 * @param string $referenceNumber
 * @return string
 */
private function generateMpesaSignature($referenceNumber)
{
    $merchantCode = config('services.jenga.merchant_code');
    $apiKey = config('services.jenga.api_key');
    
    $signatureData = $merchantCode . $referenceNumber;
    
    return base64_encode(hash_hmac('sha256', $signatureData, $apiKey, true));
}

/**
 * Check Mpesa STK Push status
 * 
 * @param string $orderReference
 * @return array
 */
public function checkMpesaOrderStatus($orderReference)
{
    try {
        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for M-Pesa order status check');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }
        
        // Make API request to check order status
        $response = $this->client->get('https://uat.finserve.africa/api-checkout/mpesa-stk-push/v3.0/status/order/' . $orderReference, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ],
            'timeout' => 30
        ]);
        
        $result = json_decode($response->getBody(), true);
        
        if (isset($result['status']) && $result['status'] === true) {
            $orderStatus = $result['data']['order']['orderStatus'] ?? 'Unknown';
            $isPaid = $orderStatus === 'Paid';
            
            return [
                'success' => true,
                'is_paid' => $isPaid,
                'order_status' => $orderStatus,
                'response' => $result
            ];
        } else {
            \Log::error('M-Pesa order status check failed with result: ' . json_encode($result));
            return [
                'success' => false,
                'message' => 'M-Pesa order status check failed: ' . ($result['message'] ?? 'Unknown error'),
                'error_code' => 'MPESA_STATUS_CHECK_FAILED'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('M-Pesa order status check error: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error checking M-Pesa order status: ' . $e->getMessage(),
            'error_code' => 'MPESA_STATUS_CHECK_ERROR'
        ];
    }
}


/**
 * Process Equitel payment through Jenga API
 * 
 * @param Payment $payment
 * @param string $phoneNumber
 * @return array
 */
private function processEquitelPayment($payment, $phoneNumber)
{
    try {
        // Ensure phone number is properly formatted
        $phoneNumber = preg_replace('/^0+/', '', $phoneNumber);
        if (strlen($phoneNumber) < 9) {
            return [
                'success' => false,
                'message' => 'Invalid phone number format',
                'error_code' => 'INVALID_PHONE'
            ];
        }
        
        $formattedPhone = '254' . $phoneNumber;
        
        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for Equitel payment');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }
        
        // Prepare Equitel payment request data
        $equitelData = [
            'biller' => [
                // TODO: env('JENGA_EQUITEL_BILLER_CODE') is unset in .env.example —
                // this must be the real Jenga-issued biller code before going live.
                'billerCode' => env('JENGA_EQUITEL_BILLER_CODE', '320320'),
                'countryCode' => 'KE'
            ],
            'bill' => [
                'reference' => $payment->payment_reference,
                'amount' => (string)$payment->amount,
                'currency' => $payment->currency
            ],
            'payer' => [
                'name' => Auth::user()->name,
                'account' => Auth::user()->id,
                'reference' => $payment->payment_reference,
                'mobileNumber' => $formattedPhone
            ],
            'partnerId' => env('JENGA_PARTNER_ID', '0020100014605'),
            'remarks' => ucfirst($payment->booking->booking_type) . ' Booking Payment'
        ];
        
        // Make API request to Jenga
        try {
            $response = $this->client->post($this->jengaTransactionUrl . '/v3/bills/pay', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $equitelData,
                'timeout' => 30
            ]);
            
            $result = json_decode($response->getBody(), true);
            
            if (isset($result['status']) && $result['status'] === true) {
                return [
                    'success' => true,
                    'transaction_id' => $result['data']['transactionId'] ?? 'EQUITEL_' . time(),
                    'message' => 'Equitel payment successful',
                    'response' => $result
                ];
            } else {
                \Log::error('Equitel payment failed with result: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'Equitel payment failed: ' . ($result['message'] ?? 'Unknown error'),
                    'error_code' => 'EQUITEL_FAILED'
                ];
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error('Equitel API error: ' . $e->getMessage());
            
            // For demo purposes in development, simulate success
            if (app()->environment('local', 'development')) {
                return [
                    'success' => true,
                    'transaction_id' => 'EQUITEL_DEV_' . time(),
                    'message' => 'Equitel payment successful (demo)',
                    'response' => ['simulated' => true]
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Equitel payment service unavailable: ' . $e->getMessage(),
                'error_code' => 'EQUITEL_SERVICE_ERROR'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('Equitel processing error: ' . $e->getMessage());
        
        // For demo purposes in development, simulate success
        if (app()->environment('local', 'development')) {
            return [
                'success' => true,
                'transaction_id' => 'EQUITEL_DEV_' . time(),
                'message' => 'Equitel payment successful (demo)',
                'response' => ['simulated' => true]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error processing Equitel payment: ' . $e->getMessage(),
            'error_code' => 'EQUITEL_PROCESS_ERROR'
        ];
    }
}

    /**
     * Process Pay With Equity payment through Jenga API
     * 
     * @param Payment $payment
     * @param array $bankDetails
     * @return array
     */
    private function processPayWithEquity($payment, $bankDetails)
    {
        try {
        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for Pay With Equity');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }

        // Prepare pay with equity request data
        $eqData = [
            'customer' => [
                'name' => $bankDetails['account_holder_name'],
                'accountNumber' => $bankDetails['account_number'],
                'countryCode' => 'KE',
                'email' => Auth::user()->email,
                'mobileNumber' => Auth::user()->phone ?? '254700000000'
            ],
            'transaction' => [
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'reference' => $payment->payment_reference,
                'description' => ucfirst($payment->booking->booking_type) . ' Booking Payment'
            ],
            'callbackUrl' => route('payments.jenga.callback')
        ];

        // Make API request to Jenga
        try {
            $response = $this->client->post($this->jengaTransactionUrl . '/v2/payments/equity', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $eqData,
                'timeout' => 30 // Increase timeout to handle slow API responses
            ]);

            $result = json_decode($response->getBody(), true);
            
            if (isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'transaction_id' => $result['transactionId'] ?? 'PWE_' . time(),
                    'message' => 'Pay With Equity successful',
                    'response' => $result
                ];
            } else {
                \Log::error('Pay With Equity failed with result: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'Pay With Equity failed: ' . ($result['message'] ?? 'Unknown error'),
                    'error_code' => 'PWE_FAILED'
                ];
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error('Pay With Equity API error: ' . $e->getMessage());
            
            // For demo purposes in development, simulate success
            if (app()->environment('local', 'development')) {
                return [
                    'success' => true,
                    'transaction_id' => 'PWE_DEV_' . time(),
                    'message' => 'Pay With Equity successful (demo)',
                    'response' => ['simulated' => true]
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Pay With Equity service unavailable: ' . $e->getMessage(),
                'error_code' => 'PWE_SERVICE_ERROR'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('Pay With Equity processing error: ' . $e->getMessage());
        
        // For demo purposes in development, simulate success
        if (app()->environment('local', 'development')) {
            return [
                'success' => true,
                'transaction_id' => 'PWE_DEV_' . time(),
                'message' => 'Pay With Equity successful (demo)',
                'response' => ['simulated' => true]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error processing Pay With Equity: ' . $e->getMessage(),
            'error_code' => 'PWE_PROCESS_ERROR'
        ];
    }
}

/**
 * Generate payment link through Jenga API
 * 
 * @param Payment $payment
 * @return array
 */
private function generatePaymentLink($payment)
{
    try {
        // Get Jenga access token
        $accessToken = $this->getJengaAccessToken();
        
        if (!$accessToken) {
            \Log::error('Failed to get Jenga access token for Payment Link');
            return [
                'success' => false,
                'message' => 'Payment gateway authentication failed',
                'error_code' => 'AUTH_FAILED'
            ];
        }
        
        $user = Auth::user();
        
        // Prepare payment link request data
        $linkData = [
            'merchantName' => env('APP_NAME', 'Ziara-Travels'),
            'customerFirstName' => explode(' ', $user->name)[0] ?? 'Customer',
            'customerLastName' => explode(' ', $user->name)[1] ?? 'User',
            'customerEmail' => $user->email,
            'customerPhone' => $user->phone ?? '254700000000',
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'referenceNumber' => $payment->payment_reference,
            'description' => ucfirst($payment->booking->booking_type) . ' Booking Payment',
            'callbackUrl' => route('payments.jenga.callback'),
            'expiryDate' => Carbon::now()->addDays(3)->format('Y-m-d\TH:i:s\Z')
        ];
        
        // Make API request to Jenga
        try {
            $response = $this->client->post($this->jengaPaymentGatewayUrl . '/v1/links', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $linkData,
                'timeout' => 30
            ]);
            
            $result = json_decode($response->getBody(), true);
            
            if (isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'transaction_id' => $result['linkId'] ?? 'LINK_' . time(),
                    'payment_link' => $result['paymentLink'],
                    'message' => 'Payment link generated successfully',
                    'response' => $result
                ];
            } else {
                \Log::error('Payment link generation failed with result: ' . json_encode($result));
                return [
                    'success' => false,
                    'message' => 'Payment link generation failed: ' . ($result['message'] ?? 'Unknown error'),
                    'error_code' => 'PAYMENT_LINK_FAILED'
                ];
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            \Log::error('Payment link API error: ' . $e->getMessage());
            
            // For demo purposes in development, simulate success
            if (app()->environment('local', 'development')) {
                return [
                    'success' => true,
                    'transaction_id' => 'LINK_DEV_' . time(),
                    'payment_link' => route('booking.checkout') . '?demo_link=true',
                    'message' => 'Payment link generated successfully (demo)',
                    'response' => ['simulated' => true]
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Payment link service unavailable: ' . $e->getMessage(),
                'error_code' => 'PAYMENT_LINK_SERVICE_ERROR'
            ];
        }
    } catch (\Exception $e) {
        \Log::error('Payment link processing error: ' . $e->getMessage());
        
        // For demo purposes in development, simulate success
        if (app()->environment('local', 'development')) {
            return [
                'success' => true,
                'transaction_id' => 'LINK_DEV_' . time(),
                'payment_link' => route('booking.checkout') . '?demo_link=true',
                'message' => 'Payment link generated successfully (demo)',
                'response' => ['simulated' => true]
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Error generating payment link: ' . $e->getMessage(),
            'error_code' => 'PAYMENT_LINK_PROCESS_ERROR'
        ];
    }
}
    
    /**
     * Update dashboard statistics when a booking is completed
     * 
     * @param \App\Models\Booking $booking
     * @return void
     */
    private function updateDashboardStats($booking)
    {
        // Clear cache for booking and payment stats
        \Illuminate\Support\Facades\Cache::forget('booking_stats_all');
        \Illuminate\Support\Facades\Cache::forget('payment_stats_all');
        
        if ($booking->user_id) {
            \Illuminate\Support\Facades\Cache::forget('booking_stats_user_' . $booking->user_id);
            \Illuminate\Support\Facades\Cache::forget('payment_stats_user_' . $booking->user_id);
        }
        
        // Trigger dashboard refresh for real-time updates
        event(new \App\Events\BookingStatusUpdated($booking));
    }

    // =====================================
    // UNIFIED BOOKING METHODS
    // =====================================

    /**
     * Store a new flight booking in unified bookings table
     */
    public function storeFlightBooking(Request $request)
    {
        $request->validate([
            'amadeus_offer_id' => 'required|string',
            'amadeus_data' => 'required|json',
            'passenger_details' => 'required|array',
            'contact_details' => 'required|array',
            'origin_code' => 'required|string|max:5',
            'origin_city' => 'required|string|max:100',
            'destination_code' => 'required|string|max:5', 
            'destination_city' => 'required|string|max:100',
            'departure_date' => 'required|date',
            'departure_time' => 'nullable|string',
            'return_date' => 'nullable|date',
            'return_time' => 'nullable|string',
            'passenger_count' => 'required|integer|min:1|max:9',
            'cabin_class' => 'required|string|max:20',
            'airline_code' => 'nullable|string|max:10',
            'flight_duration' => 'nullable|string|max:20',
            'stops' => 'required|integer|min:0',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string|max:50',
        ]);

        try {
            // Generate unique booking reference
            $bookingReference = 'FL' . strtoupper(\Illuminate\Support\Str::random(8));
            
            // Ensure the booking reference is unique
            while (Booking::where('booking_reference', $bookingReference)->exists()) {
                $bookingReference = 'FL' . strtoupper(\Illuminate\Support\Str::random(8));
            }

            // Create the flight booking in unified table
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'booking_type' => 'flight',
                'type' => 'flight',
                'amadeus_offer_id' => $request->amadeus_offer_id,
                'amadeus_data' => json_decode($request->amadeus_data, true),
                'origin_code' => $request->origin_code,
                'origin_city' => $request->origin_city,
                'destination_code' => $request->destination_code,
                'destination_city' => $request->destination_city,
                'departure_date' => $request->departure_date,
                'departure_time_only' => $request->departure_time,
                'return_date' => $request->return_date,
                'return_time' => $request->return_time,
                'passenger_count' => $request->passenger_count,
                'cabin_class' => $request->cabin_class,
                'airline_code' => $request->airline_code,
                'flight_duration' => $request->flight_duration,
                'stops' => $request->stops,
                'total_price' => $request->total_price,
                'total_amount' => $request->total_price,
                'amount' => $request->total_price,
                'currency' => $request->currency,
                'passenger_details' => $request->passenger_details,
                'contact_details' => $request->contact_details,
                'payment_method' => $request->payment_method,
                'booking_status' => 'pending',
                'status' => 'pending',
                'payment_status' => 'pending',
                'booked_at' => now(),
                'contact_email' => $request->contact_details['email'] ?? null,
                'contact_phone' => $request->contact_details['phone'] ?? null,
            ]);

            Log::info('Flight booking created in unified table', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'route' => $request->origin_city . ' to ' . $request->destination_city,
                'total_price' => $request->total_price . ' ' . $request->currency
            ]);

            // Mark as confirmed for demo purposes
            $booking->update([
                'booking_status' => 'confirmed',
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_reference' => 'PAY' . strtoupper(\Illuminate\Support\Str::random(10))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Flight booking created successfully!',
                'booking_reference' => $bookingReference,
                'booking_id' => $booking->id,
                'redirect_url' => route('bookings.confirmation', ['type' => 'flight', 'reference' => $bookingReference])
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating flight booking in unified table', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['amadeus_data'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new hotel booking in unified bookings table
     */
    public function storeHotelBooking(Request $request)
    {
        $request->validate([
            'amadeus_offer_id' => 'required|string',
            'amadeus_data' => 'required|json',
            'guest_details' => 'required|array',
            'contact_details' => 'required|array',
            'hotel_code' => 'required|string|max:10',
            'hotel_name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'room_type' => 'required|string|max:100',
            'guest_count' => 'required|integer|min:1|max:20',
            'room_count' => 'required|integer|min:1|max:10',
            'nights' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string|max:50',
        ]);

        try {
            // Generate unique booking reference
            $bookingReference = 'HT' . strtoupper(\Illuminate\Support\Str::random(8));
            
            while (Booking::where('booking_reference', $bookingReference)->exists()) {
                $bookingReference = 'HT' . strtoupper(\Illuminate\Support\Str::random(8));
            }

            // Create the hotel booking in unified table
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'booking_type' => 'hotel',
                'type' => 'hotel',
                'amadeus_offer_id' => $request->amadeus_offer_id,
                'amadeus_data' => json_decode($request->amadeus_data, true),
                'hotel_code' => $request->hotel_code,
                'hotel_name' => $request->hotel_name,
                'city' => $request->city,
                'country' => $request->country,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'room_type' => $request->room_type,
                'guest_count' => $request->guest_count,
                'room_count' => $request->room_count,
                'nights' => $request->nights,
                'total_price' => $request->total_price,
                'total_amount' => $request->total_price,
                'amount' => $request->total_price,
                'currency' => $request->currency,
                'guest_details' => $request->guest_details,
                'contact_details' => $request->contact_details,
                'payment_method' => $request->payment_method,
                'booking_status' => 'pending',
                'status' => 'pending',
                'payment_status' => 'pending',
                'booked_at' => now(),
                'contact_email' => $request->contact_details['email'] ?? null,
                'contact_phone' => $request->contact_details['phone'] ?? null,
            ]);

            Log::info('Hotel booking created in unified table', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'hotel' => $request->hotel_name . ' in ' . $request->city,
                'total_price' => $request->total_price . ' ' . $request->currency
            ]);

            // Mark as confirmed for demo purposes
            $booking->update([
                'booking_status' => 'confirmed',
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_reference' => 'PAY' . strtoupper(\Illuminate\Support\Str::random(10))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hotel booking created successfully!',
                'booking_reference' => $bookingReference,
                'booking_id' => $booking->id,
                'redirect_url' => route('bookings.confirmation', ['type' => 'hotel', 'reference' => $bookingReference])
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating hotel booking in unified table', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['amadeus_data'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new tour booking in unified bookings table
     */
    public function storeTourBooking(Request $request)
    {
        $request->validate([
            'amadeus_activity_id' => 'required|string',
            'amadeus_data' => 'required|json',
            'traveler_details' => 'required|array',
            'contact_details' => 'required|array',
            'activity_name' => 'required|string|max:255',
            'destination' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'activity_date' => 'required|date',
            'activity_time' => 'nullable|string',
            'duration' => 'nullable|string|max:50',
            'traveler_count' => 'required|integer|min:1|max:20',
            'activity_type' => 'nullable|string|max:100',
            'difficulty_level' => 'nullable|string|max:50',
            'total_price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|string|max:50',
        ]);

        try {
            // Generate unique booking reference
            $bookingReference = 'TR' . strtoupper(\Illuminate\Support\Str::random(8));
            
            while (Booking::where('booking_reference', $bookingReference)->exists()) {
                $bookingReference = 'TR' . strtoupper(\Illuminate\Support\Str::random(8));
            }

            // Create the tour booking in unified table
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'booking_type' => 'tour',
                'type' => 'tour',
                'amadeus_activity_id' => $request->amadeus_activity_id,
                'amadeus_data' => json_decode($request->amadeus_data, true),
                'activity_name' => $request->activity_name,
                'destination' => $request->destination,
                'country' => $request->country,
                'activity_date' => $request->activity_date,
                'activity_time' => $request->activity_time,
                'duration' => $request->duration,
                'traveler_count' => $request->traveler_count,
                'activity_type' => $request->activity_type,
                'difficulty_level' => $request->difficulty_level,
                'total_price' => $request->total_price,
                'total_amount' => $request->total_price,
                'amount' => $request->total_price,
                'currency' => $request->currency,
                'traveler_details' => $request->traveler_details,
                'contact_details' => $request->contact_details,
                'payment_method' => $request->payment_method,
                'booking_status' => 'pending',
                'status' => 'pending',
                'payment_status' => 'pending',
                'booked_at' => now(),
                'contact_email' => $request->contact_details['email'] ?? null,
                'contact_phone' => $request->contact_details['phone'] ?? null,
            ]);

            Log::info('Tour booking created in unified table', [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'booking_reference' => $bookingReference,
                'activity' => $request->activity_name . ' in ' . $request->destination,
                'total_price' => $request->total_price . ' ' . $request->currency
            ]);

            // Mark as confirmed for demo purposes
            $booking->update([
                'booking_status' => 'confirmed',
                'status' => 'confirmed',
                'payment_status' => 'paid',
                'payment_reference' => 'PAY' . strtoupper(\Illuminate\Support\Str::random(10))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tour booking created successfully!',
                'booking_reference' => $bookingReference,
                'booking_id' => $booking->id,
                'redirect_url' => route('bookings.confirmation', ['type' => 'tour', 'reference' => $bookingReference])
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating tour booking in unified table', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->except(['amadeus_data'])
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user's bookings (all types)
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all'); // all, flight, hotel, tour
        
        $query = Booking::where('user_id', Auth::id());
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('bookings.index', compact('bookings', 'type'));
    }

    /**
     * Show specific booking details
     */
    public function showBooking($reference)
    {
        $booking = Booking::where('booking_reference', $reference)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking(Request $request, $reference)
    {
        $booking = Booking::where('booking_reference', $reference)
                         ->where('user_id', Auth::id())
                         ->firstOrFail();

        if ($booking->status === 'cancelled' || $booking->booking_status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Booking is already cancelled.'
            ]);
        }

        try {
            $booking->update([
                'booking_status' => 'cancelled',
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'notes' => $request->input('cancellation_reason', 'Cancelled by user')
            ]);

            Log::info('Booking cancelled', [
                'booking_id' => $booking->id,
                'booking_reference' => $reference,
                'user_id' => Auth::id(),
                'type' => $booking->type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error cancelling booking', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel booking. Please contact support.'
            ], 500);
        }
    }

    /**
     * Show a specific booking
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $booking = Booking::with(['user'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            return view('bookings.show', compact('booking'));

        } catch (\Exception $e) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Booking not found or access denied.');
        }
    }

    /**
     * Download booking confirmation
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        try {
            $booking = Booking::with(['user'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Generate PDF or return confirmation view
            return view('bookings.download', compact('booking'));

        } catch (\Exception $e) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Booking not found or access denied.');
        }
    }
}


