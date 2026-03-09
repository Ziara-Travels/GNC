@extends('layouts.main')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Search Summary Bar -->
    <div class="bg-indigo-900 text-white py-4 sticky top-0 z-10 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between">
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-6 mb-3 sm:mb-0">
                    <div>
                        <div class="text-sm text-indigo-200">Your search</div>
                        <h1 class="text-xl font-bold">
                            {{ $searchParams['destination'] ?? 'Hotels' }}
                        </h1>
                    </div>
                    <div class="flex items-center space-x-1 text-sm">
                        <div class="bg-indigo-800 py-1 px-3 rounded-full">
                            {{ \Carbon\Carbon::parse($searchParams['check_in_date'])->format('M d') }}
                            -
                            {{ \Carbon\Carbon::parse($searchParams['check_out_date'])->format('M d, Y') }}
                        </div>
                        <div class="bg-indigo-800 py-1 px-3 rounded-full">
                            {{ $searchParams['rooms'] }} {{ $searchParams['rooms'] > 1 ? 'rooms' : 'room' }}
                        </div>
                        <div class="bg-indigo-800 py-1 px-3 rounded-full">
                            {{ $searchParams['adults'] }} {{ $searchParams['adults'] > 1 ? 'adults' : 'adult' }}
                            @if(isset($searchParams['children']) && $searchParams['children'] > 0)
                                , {{ $searchParams['children'] }} {{ $searchParams['children'] > 1 ? 'children' : 'child' }}
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('hotels.search.form') }}" class="inline-flex items-center bg-white text-indigo-800 px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Modify Search
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Results Count & Sorting -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ count($hotels) }} Hotels Found
                <span class="text-sm font-normal text-gray-500">
                    in {{ $searchParams['destination'] ?? 'your search area' }}
                </span>
            </h2>
            <div class="mt-3 md:mt-0 flex items-center">
                <label for="sort-by" class="mr-2 text-sm text-gray-700">Sort by:</label>
                <select id="sort-by" class="border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="recommended">Recommended</option>
                    <option value="price_low">Price (Low to High)</option>
                    <option value="price_high">Price (High to Low)</option>
                    <option value="rating_high">Rating (High to Low)</option>
                    <option value="name">Hotel Name (A-Z)</option>
                </select>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col lg:flex-row">
            <!-- Filters Sidebar -->
            <div class="w-full lg:w-64 mb-6 lg:mb-0 lg:mr-8">
                <div class="bg-white rounded-lg shadow-md p-4 lg:sticky lg:top-24">
                    <h3 class="font-bold text-gray-800 mb-4 flex justify-between items-center">
                        <span>Filters</span>
                        <button id="clear-filters" class="text-sm text-indigo-600 hover:text-indigo-800">Clear all</button>
                    </h3>

                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Price Range</h4>
                        <div class="px-2">
                            <div class="relative">
                                <div class="h-1 bg-gray-200 rounded-full">
                                    <div class="absolute h-1 bg-indigo-500 rounded-full" style="left: 15%; right: 30%;"></div>
                                </div>
                                <div class="absolute w-4 h-4 bg-white border-2 border-indigo-500 rounded-full -mt-1.5" style="left: 15%;"></div>
                                <div class="absolute w-4 h-4 bg-white border-2 border-indigo-500 rounded-full -mt-1.5" style="left: 70%;"></div>
                            </div>
                            <div class="flex justify-between mt-4 text-xs text-gray-500">
                                <span>$0</span>
                                <span>$1000+</span>
                            </div>
                            <div class="flex justify-between mt-2">
                                <span class="text-sm font-medium text-gray-700">$50 - $700</span>
                            </div>
                        </div>
                    </div>

                    <!-- Star Rating Filter -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Star Rating</h4>
                        <div class="space-y-2">
                            @foreach(range(5, 1) as $stars)
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span class="text-yellow-400">
                                        @for($i = 0; $i < $stars; $i++)
                                            ★
                                        @endfor
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Amenities Filter -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Amenities</h4>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Free WiFi</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Swimming Pool</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Parking</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Restaurant</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Air Conditioning</span>
                            </label>
                            <div class="mt-1">
                                <button class="text-sm text-indigo-600 hover:text-indigo-800">
                                    + Show more
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Property Type Filter -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Property Type</h4>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Hotel</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Resort</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Apartment</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                <span class="text-gray-700 text-sm">Villa</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hotel Results -->
            <div class="flex-1">
                <!-- Results List -->
                <div class="space-y-6" id="hotel-results">
                    @if(count($hotels) > 0)
                        @foreach($hotels as $hotel)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Hotel Image -->
                                    <div class="md:w-1/3 relative">
                                        @if(isset($hotel->hotel->media) && count($hotel->hotel->media) > 0)
                                            <img 
                                                src="{{ $hotel->hotel->media[0]->uri }}" 
                                                alt="{{ $hotel->hotel->name }}" 
                                                class="w-full h-48 md:h-full object-cover"
                                                onerror="this.onerror=null; this.src='https://placehold.co/600x400?text=No+Image';"
                                            >
                                        @else
                                            <div class="w-full h-48 md:h-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-hotel text-gray-400 text-4xl"></i>
                                            </div>
                                        @endif
                                        <!-- Favorite Button -->
                                        <button class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full flex items-center justify-center hover:bg-gray-100">
                                            <i class="far fa-heart text-gray-500 hover:text-red-500"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Hotel Info -->
                                    <div class="md:w-2/3 p-4 flex flex-col">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="font-bold text-lg text-gray-800">{{ $hotel->hotel->name }}</h3>
                                                    <div class="flex items-center mt-1">
                                                        <!-- Star Rating -->
                                                        <div class="flex text-yellow-400">
                                                            @for($i = 0; $i < ($hotel->hotel->rating ?? 0); $i++)
                                                                <span>★</span>
                                                            @endfor
                                                        </div>
                                                        
                                                        <!-- Address -->
                                                        <span class="text-gray-500 text-sm ml-2">
                                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                                            {{ $hotel->hotel->address->cityName ?? '' }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Review Score -->
                                                @if(isset($hotel->hotel->rating) && $hotel->hotel->rating > 0)
                                                    <div class="bg-indigo-600 text-white rounded-lg px-3 py-2 text-sm font-bold">
                                                        {{ number_format($hotel->hotel->rating, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Amenities -->
                                            <div class="mt-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @if(isset($hotel->hotel->amenities) && count($hotel->hotel->amenities) > 0)
                                                        @foreach(array_slice($hotel->hotel->amenities, 0, 5) as $amenity)
                                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                                                {{ $amenity }}
                                                            </span>
                                                        @endforeach
                                                        @if(count($hotel->hotel->amenities) > 5)
                                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                                                +{{ count($hotel->hotel->amenities) - 5 }} more
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-gray-500 text-sm">Amenities information not available</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 flex justify-between items-end">
                                            <!-- Price Info -->
                                            <div>
                                                <div class="text-gray-500 text-sm">
                                                    {{ $searchParams['rooms'] }} room, {{ $searchParams['adults'] }} adults, 
                                                    {{ \Carbon\Carbon::parse($searchParams['check_in_date'])->diffInDays(\Carbon\Carbon::parse($searchParams['check_out_date'])) }} nights
                                                </div>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-2xl font-bold text-gray-800">
                                                        ${{ number_format($hotel->offers[0]->price->total, 2) }}
                                                    </span>
                                                    <span class="text-gray-500 text-sm ml-1">
                                                        {{ $hotel->offers[0]->price->currency }}
                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">Includes taxes & fees</div>
                                            </div>
                                            
                                            <!-- View Deal Button -->
                                            <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-300">
                                                View Deal
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Load More Button -->
                        <div class="text-center pt-4">
                            <button class="bg-white hover:bg-gray-50 text-indigo-600 font-medium py-3 px-8 rounded-lg border border-gray-300 shadow-sm transition-colors duration-300">
                                Load More Hotels
                            </button>
                        </div>
                    @else
                        <!-- No Results -->
                        <div class="bg-white rounded-lg shadow-md p-8 text-center">
                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-hotel"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">No hotels found</h3>
                            <p class="text-gray-600 mb-4">
                                We couldn't find any hotels matching your search criteria.
                            </p>
                            <a href="{{ route('hotels.search.form') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Modify your search
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sorting functionality
    const sortSelect = document.getElementById('sort-by');
    sortSelect.addEventListener('change', function() {
        const hotelResults = document.getElementById('hotel-results');
        const hotels = Array.from(hotelResults.querySelectorAll('.bg-white.rounded-lg.shadow-md'));
        
        // Add loading state
        hotelResults.classList.add('opacity-50');
        
        setTimeout(() => {
            // Sort based on selected option
            // This is just for demonstration - actual sorting would depend on your data structure
            
            // Remove loading state
            hotelResults.classList.remove('opacity-50');
        }, 500);
    });
    
    // Clear all filters
    const clearFiltersButton = document.getElementById('clear-filters');
    clearFiltersButton.addEventListener('click', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Reset price range (this would be more complex with a real range slider)
    });
    
    // Initialize favorite functionality
    const favoriteButtons = document.querySelectorAll('.far.fa-heart');
    favoriteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.target.classList.toggle('far');
            e.target.classList.toggle('fas');
            e.target.classList.toggle('text-red-500');
            
            // In a real app, you'd save this to the user's favorites
            // For example: axios.post('/api/favorites/add', { hotelId: hotelId })
        });
    });
});
</script>
@endpush