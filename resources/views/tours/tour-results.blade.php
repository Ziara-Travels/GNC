@extends('layouts.main')

@section('content')
    <style>
        .ziara-flow-hero {
            background:
                radial-gradient(900px 400px at 88% -20%, rgba(201,162,39,.18), transparent 60%),
                linear-gradient(135deg, #4a1d13 0%, #6b2c1f 60%, #7e3425 100%);
            border-bottom: 3px solid #c9a227;
        }
        .ziara-flow-glow--a { background: rgba(201,162,39,.22); }
        .ziara-flow-glow--b { background: rgba(123,42,27,.22); }
        .ziara-flow-hero h1 { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; letter-spacing: .3px; }
        .ziara-flow-chip { background: rgba(255,255,255,.10); border: 1px solid rgba(201,162,39,.38); border-radius: 14px; }
        .ziara-flow-chip [data-lucide] { color: #f3d24a; }
        .ziara-flow-modify { background: #fff; color: #6b2c1f; }
        .ziara-flow-modify:hover { background: #f3d24a; color: #4a1d13; }
        .ziara-flow-main { background: #fdf8eb; }
        .ziara-flow-panel { background: #fff; border: 1px solid #eadfca; border-radius: 16px; box-shadow: 0 2px 12px rgba(74,29,19,.10); }
        .ziara-flow-panel--sticky { position: sticky; top: 6rem; }
        .ziara-flow-panel h3,
        .ziara-flow-panel h4 { color: #4a1d13; }
        .ziara-flow-pill { background: #fff; border: 1px solid #eadfca; color: #4a1d13; }
        .ziara-flow-pill:hover { background: #fdf6df; border-color: #c9a227; }
        .ziara-flow-pill--on { background: #6b2c1f; border-color: #6b2c1f; color: #fff; }
        .ziara-flow-select { background: #fff; border: 1px solid #eadfca; border-radius: 10px; color: #1c1612; }
        .ziara-flow-select:focus { outline: none; box-shadow: 0 0 0 3px rgba(201,162,39,.25); border-color: #c9a227; }
        .ziara-flow-check { accent-color: #6b2c1f; }
        .ziara-flow-checklist h4 { font-size: 15px; font-weight: 600; color: #4a1d13; }
        .ziara-flow-checklist span { color: #3a322c; }
        .ziara-flow-checklist [data-lucide] { color: #c9a227; }
        .ziara-flow-apply { background: #7b2a1b; color: #fff; border-radius: 10px; }
        .ziara-flow-apply:hover { background: #6b1d15; }
        .ziara-flow-count strong { color: #4a1d13; }
        .ziara-flow-card { background: #fff; border: 1px solid #eadfca; border-radius: 16px; overflow: hidden; }
        .ziara-flow-card:hover { box-shadow: 0 8px 32px rgba(74,29,19,.16); }
        .ziara-flow-card h3 { color: #4a1d13; }
        .ziara-flow-card p { color: #7a6a60; }
        .ziara-flow-duration { background: rgba(28,14,10,.72); border: 1px solid rgba(201,162,39,.4); color: #fff; }
        .ziara-flow-duration [data-lucide] { color: #f3d24a; }
        .ziara-flow-rating { background: #fdf6df; color: #6b1d15; border: 1px solid rgba(201,162,39,.4); }
        .ziara-flow-rating [data-lucide] { color: #c9a227; }
        .ziara-flow-tag { background: #f6efe2; color: #4a1d13; border: 1px solid #eadfca; }
        .ziara-flow-tag--ok { background: #eef7ef; color: #167a3a; border-color: rgba(22,122,58,.28); }
        .ziara-flow-hl [data-lucide] { color: #c9a227; }
        .ziara-flow-price { color: #7b2a1b; }
        .ziara-flow-old { color: #a8968a; }
        .ziara-flow-per { color: #7a6a60; }
        .ziara-flow-view { background: #7b2a1b; color: #fff; border-radius: 10px; }
        .ziara-flow-view:hover { background: #6b1d15; }
        .ziara-flow-page { background: #fff; border: 1px solid #eadfca; color: #7a6a60; border-radius: 10px; }
        .ziara-flow-page:hover { background: #fdf8eb; border-color: #c9a227; }
        .ziara-flow-page--on { background: #6b2c1f; border-color: #6b2c1f; color: #fff; }
        .ziara-flow-empty { background: #fff; border: 1px solid #eadfca; border-radius: 16px; }
        .ziara-flow-empty h3 { color: #4a1d13; }
        .ziara-flow-empty p { color: #7a6a60; }
        .ziara-flow-empty i[data-lucide] { color: #d8c9b6; }
        .ziara-flow-modify2 { background: #7b2a1b; color: #fff; border-radius: 10px; }
        .ziara-flow-modify2:hover { background: #6b1d15; }
        .ziara-flow-sugg h3 { color: #4a1d13; }
        .ziara-flow-news { background: radial-gradient(900px 400px at 12% -20%, rgba(201,162,39,.18), transparent 60%), #4a1d13; border-top: 3px solid #c9a227; }
        .ziara-flow-news h2 { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; }
        .ziara-flow-news__title { font-family: 'Cormorant Garamond', Georgia, serif; }
        .ziara-flow-news__sub { color: #e8d5b0; }
        .ziara-flow-news__input { background: #fff; border: 1px solid #eadfca; border-radius: 10px; color: #1c1612; }
        .ziara-flow-news__input:focus { outline: none; box-shadow: 0 0 0 3px rgba(243,210,74,.45); }
        .ziara-flow-news__btn { background: #c9a227; color: #4a1d13; border-radius: 10px; }
        .ziara-flow-news__btn:hover { background: #f3d24a; }
    </style>

    <!-- Enhanced Hero Section -->
    <div class="relative ziara-flow-hero py-24 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 ziara-flow-glow--a rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 ziara-flow-glow--b rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Tours & Activities Results</h1>
                <p class="text-xl text-white/80 mb-8">Discover amazing experiences for your journey</p>
                
                <!-- Search Parameters Summary -->
                <div class="ziara-flow-chip px-6 py-4 inline-block">
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
                <button onclick="modifySearch()" class="mt-6 px-6 py-3 ziara-flow-modify rounded-full font-semibold transition-colors flex items-center mx-auto">
                    <i data-lucide="edit" class="w-5 h-5 mr-2"></i>
                    Modify Search
                </button>
            </div>
        </div>

        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden">
            <svg class="relative block w-full h-[40px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-[#fdf8eb]"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-[#fdf8eb] py-12">
        <div class="container mx-auto px-4">
            <!-- Filters and Sorting -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Filters -->
                <div class="flex flex-wrap gap-2">
                    <button class="px-4 py-2 ziara-flow-pill rounded-full text-sm font-medium flex items-center">
                        <i data-lucide="sliders" class="w-4 h-4 mr-2"></i>
                        Filters
                    </button>
                    <button class="px-4 py-2 ziara-flow-pill rounded-full text-sm font-medium">
                        Tour Type
                    </button>
                    <button class="px-4 py-2 ziara-flow-pill rounded-full text-sm font-medium">
                        Duration
                    </button>
                    <button class="px-4 py-2 ziara-flow-pill rounded-full text-sm font-medium">
                        Price Range
                    </button>
                </div>

                <!-- Sorting -->
                <div class="flex items-center">
                    <span class="text-sm text-[#7a6a60] mr-2">Sort by:</span>
                    <select class="ziara-flow-select py-2 px-4 text-sm">
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
                    <div class="ziara-flow-panel ziara-flow-panel--sticky p-6">
                        <h3 class="text-lg font-semibold mb-4">Filter Results</h3>
                        
                        <!-- Tour Types -->
                        <div class="mb-6">
                            <h4 class="ziara-flow-checklist mb-3">Tour Type</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Adventure Tours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Cultural Tours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Wildlife Safaris</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">City Tours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Food & Culinary</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Duration -->
                        <div class="mb-6">
                            <h4 class="ziara-flow-checklist mb-3">Duration</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Less than 3 hours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">3 to 5 hours</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">5 to 12 hours (Full day)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Multi-day</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-6">
                            <h4 class="ziara-flow-checklist mb-3">Price Range</h4>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-[#7a6a60]">$0</span>
                                <span class="text-sm text-[#7a6a60]">$1000+</span>
                            </div>
                            <input type="range" min="0" max="1000" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            <div class="flex items-center justify-between mt-2">
                                <div class="flex items-center border rounded">
                                    <span class="px-2 text-[#7a6a60]">$</span>
                                    <input type="number" class="w-24 py-1 px-2 border-0 focus:ring-0" placeholder="Min">
                                </div>
                                <span class="text-[#a8968a]">-</span>
                                <div class="flex items-center border rounded">
                                    <span class="px-2 text-[#7a6a60]">$</span>
                                    <input type="number" class="w-24 py-1 px-2 border-0 focus:ring-0" placeholder="Max">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Languages -->
                        <div class="mb-6">
                            <h4 class="ziara-flow-checklist mb-3">Languages</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">English</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">French</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Spanish</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Swahili</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Amenities -->
                        <div class="mb-6">
                            <h4 class="ziara-flow-checklist mb-3">Special Features</h4>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Free Cancellation</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Private Tour</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Meals Included</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Transportation Included</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="ziara-flow-check form-checkbox h-5 w-5">
                                    <span class="ml-2">Kid-friendly</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Apply Filters Button -->
                        <button class="w-full py-3 ziara-flow-apply font-medium transition-colors flex items-center justify-center">
                            <i data-lucide="filter" class="w-5 h-5 mr-2"></i>
                            Apply Filters
                        </button>
                    </div>
                </div>

                <!-- Right Column: Tour Results -->
                <div class="lg:col-span-2">
                    @if(isset($noToursFound) && $noToursFound)
                        <!-- No Results Found -->
                        <div class="ziara-flow-empty p-10 text-center">
                            <div class="mb-6">
                                <i data-lucide="map" class="w-20 h-20 mx-auto text-[#d8c9b6]"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#4a1d13] mb-2">No Tours Found</h3>
                            <p class="text-[#7a6a60] mb-6">We couldn't find any tours matching your search criteria. Try adjusting your filters or dates.</p>
                            <button onclick="modifySearch()" class="px-6 py-3 ziara-flow-modify2 font-medium transition-colors">
                                Modify Search
                            </button>
                        </div>
                        
                        <!-- Alternative Suggestions -->
                        <div class="mt-8">
                            <h3 class="ziara-flow-sugg text-xl font-bold mb-4">Popular Tours You Might Like</h3>
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
                            <p class="ziara-flow-count text-[#7a6a60]">Found <span class="font-semibold">{{ count($tours ?? []) }}</span> tours for your search</p>
                        </div>

                        <!-- Tour Results List -->
                        <div class="space-y-6">
                            @foreach($tours ?? [] as $tour)
                                <!-- Tour Card -->
                                <div class="ziara-flow-card transition-all">
                                    <div class="flex flex-col md:flex-row">
                                        <!-- Tour Image -->
                                        <div class="md:w-1/3 relative">
                                            <img src="{{ asset('images/tours/' . ($tour['tourImage'] ?? 'default-tour.jpg')) }}" alt="{{ $tour['tourName'] ?? 'Tour Image' }}" class="w-full h-full object-cover aspect-[4/3] md:aspect-auto">
                                            <!-- Wishlist Button -->
                                            <button class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm p-2 rounded-full shadow-md hover:bg-white transition-colors">
                                                <i data-lucide="heart" class="w-5 h-5 text-[#7a6a60]"></i>
                                            </button>
                                            <!-- Duration Badge -->
                                            <div class="ziara-flow-duration absolute bottom-3 left-3 backdrop-blur-sm px-3 py-1 rounded-full text-xs flex items-center">
                                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                                {{ $tour['duration'] ?? '3 hours' }}
                                            </div>
                                        </div>
                                        
                                        <!-- Tour Info -->
                                        <div class="md:w-2/3 p-6">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <!-- Tour Name and Location -->
                                                    <h3 class="text-lg font-bold text-[#4a1d13] mb-1">{{ $tour['tourName'] ?? 'Tour Name' }}</h3>
                                                    <p class="text-[#7a6a60] mb-2 flex items-center">
                                                        <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                                        {{ $tour['location'] ?? 'Location' }}, {{ $tour['country'] ?? 'Country' }}
                                                    </p>
                                                </div>
                                                
                                                <!-- Rating -->
                                                <div class="ziara-flow-rating flex items-center px-2 py-1 rounded-lg text-sm">
                                                    <i data-lucide="star" class="w-4 h-4 fill-current mr-1"></i>
                                                    <span>{{ $tour['rating'] ?? '4.5' }}</span>
                                                    <span class="text-[#7a6a60] ml-1">({{ $tour['reviewCount'] ?? '52' }})</span>
                                                </div>
                                            </div>

                                            <!-- Tour Features -->
                                            <div class="flex flex-wrap gap-2 mb-3 mt-3">
                                                <span class="px-3 py-1 ziara-flow-tag text-xs font-medium rounded-full">{{ $tour['tourType'] ?? 'Adventure' }}</span>
                                                @if(isset($tour['languages']))
                                                    <span class="px-3 py-1 ziara-flow-tag text-xs font-medium rounded-full">{{ $tour['languages'] }}</span>
                                                @endif
                                                @if(isset($tour['groupSize']))
                                                    <span class="px-3 py-1 ziara-flow-tag text-xs font-medium rounded-full">Max {{ $tour['groupSize'] }} people</span>
                                                @endif
                                                @if(isset($tour['features']) && in_array('Free cancellation', $tour['features'] ?? []))
                                                    <span class="px-3 py-1 ziara-flow-tag ziara-flow-tag--ok text-xs font-medium rounded-full">Free cancellation</span>
                                                @endif
                                            </div>
                                            
                                            <!-- Description -->
                                            <p class="text-[#7a6a60] text-sm mb-4">{{ $tour['description'] ?? 'Experience the beauty and culture of this amazing destination with our expert guides. This tour offers a perfect blend of adventure, history, and natural beauty.' }}</p>

                                            <!-- Highlights -->
                                            <div class="mb-4">
                                                <div class="ziara-flow-hl flex items-center text-sm text-[#7a6a60]">
                                                    <i data-lucide="check-circle" class="w-4 h-4 text-[#c9a227] mr-2"></i>
                                                    <span>{{ $tour['highlights'][0] ?? 'Expertly guided tour experience' }}</span>
                                                </div>
                                                <div class="ziara-flow-hl flex items-center text-sm text-[#7a6a60]">
                                                    <i data-lucide="check-circle" class="w-4 h-4 text-[#c9a227] mr-2"></i>
                                                    <span>{{ $tour['highlights'][1] ?? 'Small groups for a personalized experience' }}</span>
                                                </div>
                                            </div>

                                            <!-- Price and Book Button -->
                                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-[#eadfca]">
                                                <div class="flex flex-col">
                                                    @if(isset($tour['originalPrice']) && $tour['originalPrice'] > ($tour['price'] ?? 0))
                                                        <span class="ziara-flow-old text-sm line-through">{{ $tour['currency'] ?? 'USD' }} {{ number_format($tour['originalPrice']) }}</span>
                                                    @endif
                                                    <span class="ziara-flow-price text-2xl font-bold">{{ $tour['currency'] ?? 'USD' }} {{ number_format($tour['price'] ?? 75) }}</span>
                                                    <span class="ziara-flow-per text-sm">per person</span>
                                                </div>
                                                
                                                <form action="{{ route('tours.book') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="tour_data" value="{{ json_encode($tour) }}">
                                                    <button type="submit" class="px-6 py-3 ziara-flow-view font-medium transition-colors flex items-center">
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
                                <button class="px-4 py-2 ziara-flow-page flex items-center">
                                    <i data-lucide="chevron-left" class="w-5 h-5 mr-1"></i>
                                    Previous
                                </button>
                                <button class="px-4 py-2 ziara-flow-page ziara-flow-page--on">1</button>
                                <button class="px-4 py-2 ziara-flow-page">2</button>
                                <button class="px-4 py-2 ziara-flow-page">3</button>
                                <button class="px-4 py-2 ziara-flow-page">...</button>
                                <button class="px-4 py-2 ziara-flow-page flex items-center">
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
    <div class="ziara-flow-news py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-white mb-4 ziara-flow-news__title">Get Exclusive Tour Deals</h2>
                <p class="ziara-flow-news__sub mb-6">Subscribe to our newsletter and receive special offers directly to your inbox.</p>
                <form class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Your email address" class="ziara-flow-news__input flex-1 px-4 py-3">
                    <button type="submit" class="px-6 py-3 ziara-flow-news__btn font-medium transition-colors">Subscribe</button>
                </form>
            </div>
        </div>
    </div>

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
