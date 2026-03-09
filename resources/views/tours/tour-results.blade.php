@extends('layouts.main')

@section('content')
    <!-- Enhanced Hero Section -->
    <div class="relative bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-800 py-24 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-cyan-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Tours & Activities Results</h1>
                <p class="text-xl text-white/80 mb-8">Discover amazing experiences for your journey</p>
                
                <!-- Search Parameters Summary -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-6 py-4 inline-block">
                    <div class="flex flex-wrap items-center justify-center gap-4 text-white">
                        <div class="flex items-center">
                            <i data-lucide="map-pin" class="w-5 h-5 mr-2"></i>
                            <span>{{ $searchParams['destination'] ?? 'Any Destination' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="calendar" class="w-5 h-5 mr-2"></i>
                            <span>{{ $searchParams['date'] ?? 'Any Date' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                            <span>{{ $searchParams['travelers'] ?? '2' }} Traveler(s)</span>
                        </div>
                    </div>
                </div>
                
                <!-- Modify Search Button -->
                <button onclick="modifySearch()" class="mt-6 px-6 py-3 bg-white text-teal-700 rounded-full font-semibold hover:bg-gray-100 transition-colors flex items-center mx-auto">
                    <i data-lucide="edit" class="w-5 h-5 mr-2"></i>
                    Modify Search
                </button>
            </div>
        </div>

        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden">
            <svg class="relative block w-full h-[40px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-gray-50"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <!-- Filters and Sorting -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Filters -->
                <div class="flex flex-wrap gap-2">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50 flex items-center">
                        <i data-lucide="sliders" class="w-4 h-4 mr-2"></i>
                        Filters
                    </button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50">
                        Tour Type
                    </button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50">
                        Duration
                    </button>
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50">
                        Price Range
                    </button>
                </div>

                <!-- Sorting -->
                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-2">Sort by:</span>
                    <select class="py-2 px-4 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="recommended">Recommended</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Rating</option>
                        <option value="duration">Duration</option>
                    </select>
                </div>
            </div>

            <!-- Results Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                        <h3 class="text-lg font-semibold mb-4">Filter Results</h3>
                        
                        <!-- Tour Types -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">Tour Type</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Adventure Tours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Cultural Tours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Wildlife Safaris</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">City Tours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Food & Culinary</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Duration -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">Duration</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Less than 3 hours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">3 to 5 hours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">5 to 12 hours (Full day)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Multi-day</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">Price Range</h4>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600">$0</span>
                                <span class="text-sm text-gray-600">$1000+</span>
                            </div>
                            <input type="range" min="0" max="1000" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center border rounded">
                                    <span class="px-2 text-gray-500">$</span>
                                    <input type="number" class="w-24 py-1 px-2 border-0 focus:ring-0" placeholder="Min">
                                </div>
                                <span class="text-gray-400">-</span>
                                <div class="flex items-center border rounded">
                                    <span class="px-2 text-gray-500">$</span>
                                    <input type="number" class="w-24 py-1 px-2 border-0 focus:ring-0" placeholder="Max">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Languages -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">Languages</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">English</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">French</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Spanish</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Swahili</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Amenities -->
                        <div class="mb-6">
                            <h4 class="font-medium mb-3">Special Features</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Free Cancellation</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Private Tour</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Meals Included</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Transportation Included</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="form-checkbox h-5 w-5 text-teal-600">
                                    <span class="ml-2">Kid-friendly</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Apply Filters Button -->
                        <button class="w-full py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <i data-lucide="filter" class="w-5 h-5 mr-2"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Right Column: Tour Results -->
                <div class="lg:col-span-2">
                    @if(isset($noToursFound) && $noToursFound)
                        <!-- No Results Found -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">
                            <div class="mb-6">
                                <i data-lucide="map" class="w-20 h-20 mx-auto text-gray-300"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">No Tours Found</h3>
                            <p class="text-gray-600 mb-6">We couldn't find any tours matching your search criteria. Try adjusting your filters or dates.</p>
                            <button onclick="modifySearch()" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors">
                                Modify Search
                            </button>
                        </div>
                        
                        <!-- Alternative Suggestions -->
                        <div class="mt-8">
                            <h3 class="text-xl font-bold mb-4">Popular Tours You Might Like</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Show some popular tour suggestions -->
                                @foreach($suggestedTours ?? [] as $tour)
                                    @include('tours.partials.tour-card', ['tour' => $tour])
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Results Found -->
                        <div class="mb-6">
                            <p class="text-gray-600">Found <span class="font-semibold">{{ count($tours ?? []) }}</span> tours for your search</p>
                        </div>

                        <!-- Tour Results List -->
                        <div class="space-y-6">
                            @foreach($tours ?? [] as $tour)
                                <!-- Tour Card -->
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
                                    <div class="flex flex-col md:flex-row">
                                        <!-- Tour Image -->
                                        <div class="md:w-1/3 relative">
                                            <img src="{{ asset('images/tours/' . ($tour['tourImage'] ?? 'default-tour.jpg')) }}" alt="{{ $tour['tourName'] ?? 'Tour Image' }}" class="w-full h-full object-cover aspect-[4/3] md:aspect-auto">
                                            <!-- Wishlist Button -->
                                            <button class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm p-2 rounded-full shadow-md hover:bg-white transition-colors">
                                                <i data-lucide="heart" class="w-5 h-5 text-gray-600"></i>
                                            </button>
                                            <!-- Duration Badge -->
                                            <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs flex items-center">
                                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                {{ $tour['duration'] ?? '3 hours' }}
                                            </div>
                                        </div>
                                        
                                        <!-- Tour Info -->
                                        <div class="md:w-2/3 p-6">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <!-- Tour Name and Location -->
                                                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $tour['tourName'] ?? 'Tour Name' }}</h3>
                                                    <p class="text-gray-600 mb-2 flex items-center">
                                                        <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                                        {{ $tour['location'] ?? 'Location' }}, {{ $tour['country'] ?? 'Country' }}
                                                    </p>
                                                </div>
                                                
                                                <!-- Rating -->
                                                <div class="flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-sm">
                                                    <i data-lucide="star" class="w-4 h-4 fill-current mr-1"></i>
                                                    <span>{{ $tour['rating'] ?? '4.5' }}</span>
                                                    <span class="text-gray-500 ml-1">({{ $tour['reviewCount'] ?? '52' }})</span>
                                                </div>
                                            </div>

                                            <!-- Tour Features -->
                                            <div class="flex flex-wrap gap-2 mb-3 mt-3">
                                                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">{{ $tour['tourType'] ?? 'Adventure' }}</span>
                                                @if(isset($tour['languages']))
                                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">{{ $tour['languages'] }}</span>
                                                @endif
                                                @if(isset($tour['groupSize']))
                                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">Max {{ $tour['groupSize'] }} people</span>
                                                @endif
                                                @if(isset($tour['features']) && in_array('Free cancellation', $tour['features'] ?? []))
                                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Free cancellation</span>
                                                @endif
                                            </div>
                                            
                                            <!-- Description -->
                                            <p class="text-gray-600 text-sm mb-4">{{ $tour['description'] ?? 'Experience the beauty and culture of this amazing destination with our expert guides. This tour offers a perfect blend of adventure, history, and natural beauty.' }}</p>

                                            <!-- Highlights -->
                                            <div class="mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i data-lucide="check-circle" class="w-4 h-4 text-teal-500 mr-2"></i>
                                                    <span>{{ $tour['highlights'][0] ?? 'Expertly guided tour experience' }}</span>
                                                </div>
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i data-lucide="check-circle" class="w-4 h-4 text-teal-500 mr-2"></i>
                                                    <span>{{ $tour['highlights'][1] ?? 'Small groups for a personalized experience' }}</span>
                                                </div>
                                            </div>

                                            <!-- Price and Book Button -->
                                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                                <div class="flex flex-col">
                                                    @if(isset($tour['originalPrice']) && $tour['originalPrice'] > ($tour['price'] ?? 0))
                                                        <span class="text-sm line-through text-gray-500">{{ $tour['currency'] ?? 'USD' }} {{ number_format($tour['originalPrice']) }}</span>
                                                    @endif
                                                    <span class="text-2xl font-bold text-teal-600">{{ $tour['currency'] ?? 'USD' }} {{ number_format($tour['price'] ?? 75) }}</span>
                                                    <span class="text-sm text-gray-500">per person</span>
                                                </div>
                                                
                                                <form action="{{ route('tours.select') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="tour_data" value="{{ json_encode($tour) }}">
                                                    <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium rounded-lg transition-colors flex items-center">
                                                        <span>View Details</span>
                                                        <i data-lucide="chevron-right" class="w-5 h-5 ml-1"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="flex justify-center mt-10">
                            <div class="flex items-center space-x-1">
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600 flex items-center">
                                    <i data-lucide="chevron-left" class="w-5 h-5 mr-1"></i>
                                    Previous
                                </button>
                                <button class="px-4 py-2 border border-teal-600 bg-teal-600 text-white rounded-lg">1</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600">2</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600">3</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600">...</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-600 flex items-center">
                                    Next
                                    <i data-lucide="chevron-right" class="w-5 h-5 ml-1"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="bg-teal-700 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Get Exclusive Tour Deals</h2>
                <p class="text-teal-100 mb-6">Subscribe to our newsletter and receive special offers directly to your inbox.</p>
                <form class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Your email address" class="flex-1 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <button type="submit" class="px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-teal-900 font-medium rounded-lg transition-colors">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    // Function to modify search
    function modifySearch() {
        // Redirect to search page with current parameters
        window.location.href = "{{ route('tours.search') }}";
    }
    
    // Sorting function
    function sortTours(sortBy) {
        console.log('Sorting by: ' + sortBy);
        // Implement sorting logic here
    }
</script>
@endsection
