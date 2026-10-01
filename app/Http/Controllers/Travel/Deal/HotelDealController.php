<?php

namespace App\Http\Controllers\Travel\Deal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use App\Http\Controllers\Travel\HotelOffersController;
use App\Events\BookingUpdated;

class HotelDealController extends Controller
{
    protected $hotelOffersController;

    public function __construct(HotelOffersController $hotelOffersController)
    {
        $this->hotelOffersController = $hotelOffersController;
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
