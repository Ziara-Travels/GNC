<?php

namespace App\Http\Controllers\Travel\Deal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Tour;
use App\Http\Controllers\Travel\ActivityController;
use GuzzleHttp\Client;
use App\Events\TourBookingUpdated;

class TourDealController extends Controller
{
    protected $activityController;

    public function __construct(ActivityController $activityController)
    {
        $this->activityController = $activityController;
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
    public function processPayment(Request $request, $reference)
    {
        // Validate the payment information
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
            'currency_preference' => 'required|string|in:USD,KES',
            'terms_agreed' => 'required|accepted'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Find the booking
        $booking = Tour::where('booking_reference', $reference)->firstOrFail();
        
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
                $booking->currency_paid = $request->input('currency_preference');
                $booking->save();
                
                // Send confirmation to user
                if (Auth::check() || $booking->contact_details) {
                    // Send email notification - you'd implement this based on your email setup
                    // Mail::to($emailAddress)->send(new TourBookingConfirmation($booking));
                }
                
                // Update user dashboard and alert admins
                event(new \App\Events\BookingStatusUpdated($booking));
                
                return redirect()->route('user.dashboard')
                    ->with('success', 'Payment successful! Your tour booking has been confirmed.');
            } else {
                return redirect()->back()->with('error', 'Payment processing failed. Please try again.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'There was a problem processing your payment: ' . $e->getMessage());
        }
    }
}
