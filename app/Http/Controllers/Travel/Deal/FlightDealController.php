<?php

namespace App\Http\Controllers\Travel\Deal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use App\Http\Controllers\Travel\FlightOffersController;
use GuzzleHttp\Client;
use App\Jobs\SendBookingConfirmationJob;
use App\Events\BookingUpdated;

class FlightDealController extends Controller
{
    protected $flightOffersController;

    public function __construct(FlightOffersController $flightOffersController)
    {
        $this->flightOffersController = $flightOffersController;
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
                    dispatch(new SendBookingConfirmationJob($booking));
                }
                
                // Update user dashboard and alert admins
                event(new \App\Events\BookingStatusUpdated($booking));
                
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
}
