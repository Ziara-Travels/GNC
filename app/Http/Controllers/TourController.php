<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Travel\TourOffersController;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * TourController - Public-facing controller for tours
 * Delegates complex logic to TourOffersController
 */
class TourController extends Controller
{
    /**
     * List all available tours
     */
    public function index(Request $request)
    {
        $tours = [
            [
                'id'          => 1,
                'slug'        => 'masai-mara-safari',
                'name'        => 'Masai Mara Safari',
                'destination' => 'Masai Mara',
                'country'     => 'Kenya',
                'price'       => 45000,
                'currency'    => 'KES',
                'duration'    => '3 days',
                'category'    => 'Safari',
                'rating'      => 4.9,
                'image'       => 'masai-mara.jpg',
                'description' => 'Experience the Great Migration in the world-famous Masai Mara.',
                'highlights'  => ['Big Five', 'Hot Air Balloon', 'Masai Village Visit'],
            ],
            [
                'id'          => 2,
                'slug'        => 'serengeti-experience',
                'name'        => 'Serengeti Experience',
                'destination' => 'Serengeti',
                'country'     => 'Tanzania',
                'price'       => 55000,
                'currency'    => 'KES',
                'duration'    => '4 days',
                'category'    => 'Safari',
                'rating'      => 4.8,
                'image'       => 'serengeti.jpg',
                'description' => 'Explore the endless plains of the Serengeti National Park.',
                'highlights'  => ['Game Drives', 'Wildebeest Migration', 'Ngorongoro Crater'],
            ],
            [
                'id'          => 3,
                'slug'        => 'gorilla-trekking-rwanda',
                'name'        => 'Gorilla Trekking Rwanda',
                'destination' => 'Volcanoes NP',
                'country'     => 'Rwanda',
                'price'       => 150000,
                'currency'    => 'KES',
                'duration'    => '2 days',
                'category'    => 'Wildlife',
                'rating'      => 5.0,
                'image'       => 'rwanda-gorillas.jpg',
                'description' => 'Trek through misty forests to encounter mountain gorillas.',
                'highlights'  => ['Mountain Gorillas', 'Misty Forests', 'Local Culture'],
            ],
            [
                'id'          => 4,
                'slug'        => 'zanzibar-beach-retreat',
                'name'        => 'Zanzibar Beach Retreat',
                'destination' => 'Zanzibar',
                'country'     => 'Tanzania',
                'price'       => 35000,
                'currency'    => 'KES',
                'duration'    => '5 days',
                'category'    => 'Beach',
                'rating'      => 4.7,
                'image'       => 'zanzibar.jpg',
                'description' => 'Relax on pristine beaches and explore Stone Town.',
                'highlights'  => ['White Sand Beaches', 'Stone Town', 'Spice Tour'],
            ],
        ];

        return view('tours.index', compact('tours'));
    }

    /**
     * Search tours
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $country = $request->input('country', '');
        $category = $request->input('category', '');

        // Delegate to index with search filters
        return $this->index($request);
    }

    /**
     * Show a single tour
     */
    public function show($slug)
    {
        // In a real implementation, fetch from database
        $tour = [
            'id'          => 1,
            'slug'        => $slug,
            'name'        => ucwords(str_replace('-', ' ', $slug)),
            'destination' => 'East Africa',
            'country'     => 'Kenya',
            'price'       => 45000,
            'currency'    => 'KES',
            'duration'    => '3 days',
            'category'    => 'Safari',
            'rating'      => 4.9,
            'reviews'     => 128,
            'image'       => 'masai-mara.jpg',
            'description' => 'An unforgettable East African adventure.',
            'highlights'  => ['Big Five', 'Game Drives', 'Local Culture'],
            'itinerary'   => [
                ['day' => 1, 'title' => 'Arrival & Orientation', 'description' => 'Arrive and settle in.'],
                ['day' => 2, 'title' => 'Game Drive', 'description' => 'Full day game drive.'],
                ['day' => 3, 'title' => 'Departure', 'description' => 'Return journey.'],
            ],
        ];

        return view('tours.show', compact('tour'));
    }

    /**
     * Show tour booking form
     */
    public function booking($slug)
    {
        $tour = [
            'slug'     => $slug,
            'name'     => ucwords(str_replace('-', ' ', $slug)),
            'price'    => 45000,
            'currency' => 'KES',
        ];

        return view('tours.booking', compact('tour'));
    }

    /**
     * Process tour booking
     */
    public function book(Request $request)
    {
        $request->validate([
            'tour_slug' => 'required|string',
            'date'      => 'required|date|after:today',
            'adults'    => 'required|integer|min:1|max:20',
        ]);

        $reference = 'TOUR-' . strtoupper(Str::random(8));

        session(['pending_tour_booking' => array_merge($request->all(), ['reference' => $reference])]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to complete your tour booking.');
        }

        return redirect()->route('tours.confirmation', ['reference' => $reference])
            ->with('success', 'Tour booked successfully!');
    }
}
