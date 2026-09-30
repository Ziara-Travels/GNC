<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HotelSearchController extends Controller
{
    protected $hotelService;

    protected $bookingService;

    public function __construct(HotelService $hotelService, BookingService $bookingService)
    {
        $this->hotelService = $hotelService;
        $this->bookingService = $bookingService;
    }

    /**
     * GET /hotels/search - Skyscanner-style hotel search form.
     */
    public function showSearchForm()
    {
        return view('hotels.hotel-search');
    }

    /**
     * GET /api/hotels/regions - destination autocomplete (hotels.com regions).
     */
    public function regions(Request $request)
    {
        $term = trim((string) $request->input('q'));
        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        return response()->json($this->hotelService->regions($term));
    }

    /**
     * POST /api/hotels/search - resolve destination + search hotels.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destination' => 'required|string|max:120',
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'adults' => 'required|integer|min:1|max:9',
            'children' => 'nullable|integer|min:0|max:9',
            'rooms' => 'required|integer|min:1|max:9',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['redirect' => null, 'errors' => $validator->errors()->all()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $destination = trim($request->input('destination'));
        $checkin = $request->input('checkin');
        $checkout = $request->input('checkout');
        $adults = (int) $request->input('adults', 2);
        $rooms = (int) $request->input('rooms', 1);
        $children = (int) $request->input('children', 0);

        $params = [
            'destination' => $destination,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'adults' => $adults,
            'rooms' => $rooms,
            'children' => $children,
        ];

        // Resolve free-text destination -> region, then search hotels.
        try {
            $regions = $this->hotelService->regions($destination);

            if (count($regions) === 0) {
                if ($request->expectsJson()) {
                    return response()->json(['redirect' => null, 'errors' => ['Could not find "' . $destination . '". Try another destination.']], 422);
                }

                return redirect()->back()->withErrors(['destination' => 'Could not find "' . $destination . '". Try another destination.'])->withInput();
            }

            $region = $this->pickRegion($regions, $destination);

            $hotels = $this->hotelService->searchHotels([
                'dest_id' => $region['dest_id'],
                'dest_type' => $region['dest_type'],
                'destination_name' => $region['name'],
                'checkin' => $checkin,
                'checkout' => $checkout,
                'adults' => $adults,
                'rooms' => $rooms,
                'children' => $children,
            ]);

            // Booking.com (booking-com18) as a secondary source; merge if usable.
            $bookingStays = $this->bookingService->search($params + [
                'dest_id' => $region['dest_id'],
                'dest_type' => $region['dest_type'],
                'destination_name' => $region['name'],
            ]);

            $merged = $this->mergeHotelSources($hotels, $bookingStays);

            session([
                'hotel_search_params' => $params + [
                    'region' => $region,
                    'destination_label' => $region['name'] . ($region['country'] !== '' ? ', ' . $region['country'] : ''),
                ],
                'hotel_search_results' => $merged,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'redirect' => route('hotels.results'),
                    'count' => count($merged),
                ]);
            }

            return redirect()->route('hotels.results');
        } catch (\Exception $e) {
            Log::error('Hotel search failed', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json(['redirect' => null, 'errors' => ['Hotel search failed. Please try again.']], 500);
            }

            return redirect()->back()->with([
                'message' => 'Hotel search failed. Please try again.',
                'alert_type' => 'error',
            ])->withInput();
        }
    }

    /**
     * GET /hotels/results - display stored hotel search results.
     */
    public function showResults(Request $request)
    {
        $searchParams = session('hotel_search_params', []);
        $hotels = session('hotel_search_results', []);

        $currency = $this->hotelService->currency;
        $bookingUrls = [];
        foreach ($hotels as $i => $hotel) {
            $bookingUrls[$i] = $this->bookingService->bookingUrl([
                'destination_name' => $searchParams['destination_label'] ?? ($searchParams['destination'] ?? ''),
                'dest_id' => $searchParams['region']['dest_id'] ?? null,
                'dest_type' => $searchParams['region']['dest_type'] ?? 'city',
                'checkin' => $searchParams['checkin'] ?? null,
                'checkout' => $searchParams['checkout'] ?? null,
                'adults' => $searchParams['adults'] ?? 2,
                'rooms' => $searchParams['rooms'] ?? 1,
                'children' => $searchParams['children'] ?? 0,
            ], $hotel['url'] ?? null);
        }

        return view('hotels.hotel-results', compact('hotels', 'searchParams', 'bookingUrls', 'currency'));
    }

    /**
     * GET /hotels/{hotelId} - hotel detail page.
     */
    public function showHotel($hotelId)
    {
        $hotel = $this->findHotel($hotelId);
        if ($hotel === null) {
            return redirect()->route('hotels.search.form')->withErrors(['destination' => 'Hotel not found. Try searching again.']);
        }

        $searchParams = session('hotel_search_params', []);
        $bookingUrl = $this->bookingService->bookingUrl([
            'destination_name' => $searchParams['destination_label'] ?? '',
            'dest_id' => $searchParams['region']['dest_id'] ?? null,
            'dest_type' => $searchParams['region']['dest_type'] ?? 'city',
            'checkin' => $searchParams['checkin'] ?? null,
            'checkout' => $searchParams['checkout'] ?? null,
            'adults' => $searchParams['adults'] ?? 2,
            'rooms' => $searchParams['rooms'] ?? 1,
            'children' => $searchParams['children'] ?? 0,
        ], $hotel['url'] ?? null);

        return view('hotels.hotel-details', compact('hotel', 'bookingUrl'));
    }

    /**
     * POST /hotels/select (public alias) - store selected hotel then redirect
     * to the Booking.com CTA checkout.
     */
    protected function storeSelectedHotel($hotelId)
    {
        $hotel = $this->findHotel($hotelId);
        if ($hotel === null) {
            return redirect()->route('hotels.results')->with('message', 'Selected hotel could not be found.');
        }

        $searchParams = session('hotel_search_params', []);
        $bookingUrl = $this->bookingService->bookingUrl([
            'destination_name' => $searchParams['destination_label'] ?? '',
            'dest_id' => $searchParams['region']['dest_id'] ?? null,
            'dest_type' => $searchParams['region']['dest_type'] ?? 'city',
            'checkin' => $searchParams['checkin'] ?? null,
            'checkout' => $searchParams['checkout'] ?? null,
            'adults' => $searchParams['adults'] ?? 2,
            'rooms' => $searchParams['rooms'] ?? 1,
            'children' => $searchParams['children'] ?? 0,
        ], $hotel['url'] ?? null);

        $reference = $this->generateBookingReference();

        session(['hotel_booking' => [
            'booking_reference' => $reference,
            'hotel' => $hotel,
            'search_params' => $searchParams,
            'booking_url' => $bookingUrl,
            'status' => 'pending',
            'created_at' => now(),
        ]]);

        return $reference;
    }

    /**
     * GET /hotels/{hotelId}/book - create booking + checkout with Booking.com CTA.
     */
    public function bookingForm($hotelId)
    {
        $reference = $this->storeSelectedHotel($hotelId);

        if (is_object($reference) && method_exists($reference, 'getStatusCode')) {
            return $reference;
        }

        return redirect()->route('hotels.checkout', ['reference' => $reference]);
    }

    /**
     * POST /hotels/book - capture guest details for the Booking.com redirect.
     */
    public function processBooking(Request $request)
    {
        $booking = session('hotel_booking');
        if ($booking === null) {
            return redirect()->route('hotels.search.form');
        }

        $validated = $request->validate([
            'guest_name' => 'required|string|max:120',
            'guest_email' => 'required|email',
            'guest_phone' => 'required|string|max:30',
        ]);

        $booking['guest_details'] = $validated;
        session(['hotel_booking' => $booking]);

        return redirect()->route('hotels.checkout', ['reference' => $booking['booking_reference']]);
    }

    /**
     * GET /hotels/checkout/{reference} - review + Booking.com CTA.
     */
    public function checkout($reference)
    {
        $booking = session('hotel_booking');
        if (!$booking || ($booking['booking_reference'] ?? null) !== $reference) {
            return redirect()->route('hotels.search.form')->with('message', 'Booking not found. Please search again.');
        }

        return view('hotels.hotel-checkout', ['booking' => $booking]);
    }

    /**
     * POST /hotels/payment/{reference} - mark as completed after Booking.com
     * redirect; stores a local confirmation record.
     */
    public function processPayment(Request $request, $reference)
    {
        $booking = session('hotel_booking');
        if (!$booking || ($booking['booking_reference'] ?? null) !== $reference) {
            return redirect()->route('hotels.search.form')->with('message', 'Booking not found. Please search again.');
        }

        $booking['status'] = 'confirmed';
        $booking['paid_amount'] = $booking['hotel']['totalPrice'] ?? 0;
        session(['hotel_booking' => $booking]);

        $this->createLocalBookingRecord($booking);

        return redirect()->route('hotels.confirmation', ['reference' => $reference]);
    }

    /**
     * GET /hotels/confirmation/{reference} - confirmation page.
     */
    public function confirmation($reference)
    {
        $booking = session('hotel_booking');
        if (!$booking || ($booking['booking_reference'] ?? null) !== $reference) {
            return redirect()->route('hotels.search.form')->with('message', 'Booking not found. Please search again.');
        }

        return view('hotels.hotel-confirmation', ['booking' => $booking]);
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    protected function pickRegion(array $regions, $destination)
    {
        $destination = strtolower(trim($destination));

        foreach ($regions as $region) {
            if (strtolower($region['name']) === $destination && ($region['dest_type'] ?? 'city') === 'city') {
                return $region;
            }
        }

        $cities = array_values(array_filter($regions, function ($region) {
            return ($region['dest_type'] ?? '') === 'city';
        }));

        return count($cities) > 0 ? $cities[0] : $regions[0];
    }

    protected function mergeHotelSources(array $hotels, array $stays)
    {
        if (count($stays) === 0 || $this->bookingService->isMock()) {
            return $hotels;
        }

        $byName = [];
        foreach ($hotels as $hotel) {
            $byName[strtolower(trim($hotel['name']))] = true;
        }

        foreach ($stays as $stay) {
            $key = strtolower(trim($stay['name']));
            if ($key === '' || isset($byName[$key])) {
                continue;
            }
            $byName[$key] = true;
            $hotels[] = $stay;
        }

        return $hotels;
    }

    protected function findHotel($hotelId)
    {
        $hotelId = (string) $hotelId;
        $hotels = session('hotel_search_results', []);

        foreach ($hotels as $hotel) {
            if ((string) ($hotel['propertyId'] ?? '') === $hotelId) {
                return $hotel;
            }
        }

        $booking = session('hotel_booking');
        if ($booking && (string) ($booking['hotel']['propertyId'] ?? '') === $hotelId) {
            return $booking['hotel'];
        }

        return null;
    }

    protected function createLocalBookingRecord(array $booking)
    {
        try {
            $hotel = $booking['hotel'] ?? [];

            $record = new \App\Models\Booking();
            $record->user_id = auth()->id();
            $record->booking_reference = $booking['booking_reference'];
            $record->booking_type = 'hotel';
            $record->type = 'hotel';
            $record->title = $hotel['name'] ?? 'Hotel booking';
            $record->amount = $hotel['totalPrice'] ?? 0;
            $record->total_amount = $hotel['totalPrice'] ?? 0;
            $record->paid_amount = $hotel['totalPrice'] ?? 0;
            $record->currency = $hotel['currency'] ?? 'KES';
            $record->status = 'pending';
            $record->payment_method = 'booking_com';
            $record->payment_status = 'external';
            $record->hotel_details = json_encode($booking);
            $record->adults = $booking['search_params']['adults'] ?? 1;
            $record->children = $booking['search_params']['children'] ?? 0;
            $record->save();
        } catch (\Exception $e) {
            Log::warning('Could not store local hotel booking record', ['error' => $e->getMessage()]);
        }
    }

    protected function generateBookingReference()
    {
        $prefix = 'HB';
        $timestamp = substr(time(), -6);
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));

        return $prefix . $timestamp . $random;
    }
}