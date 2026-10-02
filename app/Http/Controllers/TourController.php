<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BookingService;

/**
 * TourController - Public-facing controller for tours
 */
class TourController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Reference categories for the listing filter, as label/slug pairs.
     */
    protected function categoryOptions()
    {
        return collect(Tour::active()->distinct()->pluck('category'))
            ->filter()
            ->sort()
            ->map(fn ($category) => [
                'slug'  => $category,
                'label' => Tour::categoryLabel($category),
            ])
            ->values();
    }

    /**
     * List / search available tours
     */
    public function index(Request $request)
    {
        $query = Tour::active();
        $searchTerm = $request->input('q', '');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('city', 'like', "%{$searchTerm}%")
                  ->orWhere('country', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('city')) {
            $query->byCity($request->input('city'));
        }

        if ($request->filled('category')) {
            $query->byCategory($request->input('category'));
        }

        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->byPriceRange($request->input('price_min', 0), $request->input('price_max', PHP_INT_MAX));
        }

        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderByDesc('price');
                break;
            case 'rating':
                $query->orderByDesc('rating');
                break;
            case 'duration':
                $query->orderBy('duration_hours');
                break;
            default:
                $query->orderByDesc('featured')->orderByDesc('rating');
        }

        $tours = $query->paginate(12)->appends($request->query());
        $cities = Tour::active()->distinct()->pluck('city');
        $categories = $this->categoryOptions();

        return view('tours.search', compact('tours', 'searchTerm', 'cities', 'categories') + [
            'activeCategory' => null,
        ]);
    }

    /**
     * Search tours
     */
    public function search(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Tours belonging to a reference category ("kenya-safaris", "dubai-package").
     *
     * Parent categories include their children, matching the reference site
     * where "Safaris" lists the "Kenya Safaris" tours and "International
     * packages" lists Dubai and Bangkok.
     */
    public function category(Request $request, string $slug)
    {
        $children = collect(Tour::active()->distinct()->pluck('category'))
            ->filter(fn ($category) => Tour::parentCategory($category) === $slug)
            ->push($slug)
            ->unique()
            ->values();

        $tours = Tour::active()
            ->whereIn('category', $children)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = $request->input('q');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('city', 'like', "%{$term}%")
                      ->orWhere('country', 'like', "%{$term}%");
                });
            })
            ->orderByDesc('featured')
            ->orderBy('id')
            ->paginate(12)
            ->appends($request->query());

        $categories = $this->categoryOptions();

        return view('tours.search', [
            'tours'       => $tours,
            'searchTerm'  => $request->input('q', ''),
            'cities'      => Tour::active()->distinct()->pluck('city'),
            'categories'  => $categories,
            'activeCategory' => $slug,
        ]);
    }

    /**
     * Show a single tour
     */
    public function show($slug)
    {
        $tour = Tour::active()->where('slug', $slug)->firstOrFail();

        // The reference always shows three related tours, preferring siblings in
        // the same top-level group (e.g. Dubai shows Bangkok, Cape Town and
        // Zanzibar) and topping up with other active tours when needed.
        $siblingCategories = Tour::active()
            ->where('category', '!=', $tour->category)
            ->pluck('category')
            ->filter(fn ($category) => Tour::parentCategory($category) === $tour->category_parent)
            ->unique()
            ->values();

        $relatedTours = Tour::active()
            ->where('id', '!=', $tour->id)
            ->whereIn('category', $siblingCategories)
            ->orderBy('id')
            ->limit(3)
            ->get();

        if ($relatedTours->count() < 3) {
            $topUp = Tour::active()
                ->whereNotIn('id', $relatedTours->pluck('id')->push($tour->id))
                ->orderBy('id')
                ->limit(3 - $relatedTours->count())
                ->get();

            $relatedTours = $relatedTours->concat($topUp);
        }

        return view('tours.show', compact('tour', 'relatedTours'));
    }

    /**
     * Show tour booking form
     */
    public function booking($slug)
    {
        $tour = Tour::active()->where('slug', $slug)->firstOrFail();

        return view('tours.booking', compact('tour'));
    }

    /**
     * Process tour booking
     */
    public function book(Request $request)
    {
        if (!Auth::check()) {
            session(['url.intended' => url()->previous()]);
            return redirect()->route('login')->with('info', 'Please login to complete your tour booking.');
        }

        $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'tour_date' => 'required|date|after:today',
            'participants' => 'required|integer|min:1',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:30',
            'hotel_pickup' => 'nullable|boolean',
            'pickup_location' => 'nullable|string|max:255',
            'special_requirements' => 'nullable|string|max:1000',
        ]);

        $tour = Tour::active()->findOrFail($request->tour_id);
        $user = Auth::user();
        $totalPrice = $tour->discounted_price * $request->participants;

        $bookingData = [
            'booking_type' => 'tour',
            'type' => 'tour',
            'title' => "{$tour->name} - {$tour->city}",
            'tour_id' => $tour->id,
            'activity_name' => $tour->name,
            'destination' => $tour->city,
            'country' => $tour->country,
            'activity_date' => $request->tour_date,
            'duration' => $tour->duration,
            'traveler_count' => $request->participants,
            'adults' => $request->participants,
            'amount' => $tour->discounted_price,
            'total_amount' => $totalPrice,
            'total_price' => $totalPrice,
            'currency' => $tour->currency,
            'tour_details' => $tour->toArray(),
            'traveler_details' => [
                'hotel_pickup' => (bool) $request->boolean('hotel_pickup'),
                'pickup_location' => $request->input('pickup_location'),
                'special_requirements' => $request->input('special_requirements'),
            ],
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'notes' => $request->input('special_requirements'),
        ];

        $result = $this->bookingService->createBooking($bookingData, $user);

        return redirect()->route('tours.payment', ['reference' => $result['booking']->booking_reference])
            ->with('success', 'Tour booked. Please complete payment to confirm.');
    }
}
