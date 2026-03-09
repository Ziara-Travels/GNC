@extends('layouts.main')

@section('content')
    <!-- Enhanced Hero Section -->
    <div class="relative bg-gradient-to-br from-purple-600 via-indigo-700 to-blue-800 py-24 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Explore {{ $destination['name'] ?? 'East African Cities' }}</h1>
                <p class="text-xl text-white/80 mb-8">Discover the beauty and culture of East Africa's most vibrant destinations</p>
                
                <!-- Search Box -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 inline-block w-full max-w-3xl">
                    <form action="{{ route('destinations.search') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label for="destination" class="block text-sm font-medium text-white mb-1">Destination</label>
                            <div class="relative">
                                <i data-lucide="map-pin" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="text" id="destination" name="destination" class="w-full pl-10 pr-4 py-3 bg-white/20 text-white placeholder-white/70 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50" placeholder="Enter city or region" value="{{ $searchParams['destination'] ?? '' }}">
                            </div>
                        </div>
                        <div class="md:w-1/4">
                            <label for="date" class="block text-sm font-medium text-white mb-1">Date</label>
                            <div class="relative">
                                <i data-lucide="calendar" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                <input type="date" id="date" name="date" class="w-full pl-10 pr-4 py-3 bg-white/20 text-white placeholder-white/70 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50" value="{{ $searchParams['date'] ?? '' }}">
                            </div>
                        </div>
                        <div class="md:w-1/6 md:self-end">
                            <button type="submit" class="w-full px-6 py-3 bg-white text-indigo-700 rounded-lg font-medium hover:bg-gray-100 transition-colors flex items-center justify-center">
                                <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                                Search
                            </button>
                        </div>
                    </form>
                </div>
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
            <!-- Destination Overview -->
            <div class="mb-12">
                <div class="flex flex-col md:flex-row items-start gap-8">
                    <!-- Destination Image -->
                    <div class="md:w-1/3">
                        <div class="rounded-xl overflow-hidden shadow-lg">
                            <img src="{{ asset('images/destinations/' . ($destination['image'] ?? 'default-destination.jpg')) }}" alt="{{ $destination['name'] ?? 'Destination' }}" class="w-full h-auto">
                        </div>
                    </div>
                    
                    <!-- Destination Info -->
                    <div class="md:w-2/3">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $destination['name'] ?? 'Destination Name' }}</h2>
                        
                        <!-- Quick Facts -->
                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                <i data-lucide="map" class="w-4 h-4 mr-1"></i>
                                {{ $destination['country'] ?? 'Country' }}
                            </div>
                            <div class="flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                                {{ $destination['population'] ?? 'Population' }}
                            </div>
                            <div class="flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                <i data-lucide="languages" class="w-4 h-4 mr-1"></i>
                                {{ $destination['language'] ?? 'Language' }}
                            </div>
                            <div class="flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm">
                                <i data-lucide="thermometer" class="w-4 h-4 mr-1"></i>
                                {{ $destination['climate'] ?? 'Climate' }}
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="prose max-w-none mb-6">
                            <p>{{ $destination['description'] ?? 'Experience the vibrant culture, stunning landscapes, and warm hospitality of this East African gem. From bustling markets to serene natural attractions, this destination offers a perfect blend of adventure, relaxation, and cultural immersion. Explore historic sites, indulge in local cuisine, and create unforgettable memories in one of Africa\'s most beautiful regions.' }}</p>
                        </div>
                        
                        <!-- Top Attractions -->
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold mb-3">Top Attractions</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($destination['attractions'] ?? ['National Park', 'Cultural Museum', 'Historic Market', 'Beautiful Beach', 'Mountain Range', 'Wildlife Reserve'] as $attraction)
                                    <div class="flex items-center">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-indigo-600 mr-2"></i>
                                        <span>{{ $attraction }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Best Time to Visit -->
                        <div>
                            <h3 class="text-xl font-semibold mb-3">Best Time to Visit</h3>
                            <p class="text-gray-700">{{ $destination['bestTimeToVisit'] ?? 'The dry season (June to October) offers the best weather for exploring the city and its surroundings. December to February is also pleasant with fewer crowds.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Travel Options Tabs -->
            <div class="mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button id="tab-hotels" class="tab-btn border-indigo-500 text-indigo-600 py-4 px-1 border-b-2 font-medium text-sm" aria-current="page">
                            Hotels & Accommodation
                        </button>
                        <button id="tab-flights" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm">
                            Flights
                        </button>
                        <button id="tab-tours" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm">
                            Tours & Activities
                        </button>
                        <button id="tab-packages" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 border-b-2 font-medium text-sm">
                            Vacation Packages
                        </button>
                    </nav>
                </div>
            </div>
            
            <!-- Tab Content -->
            <div id="tab-content">
                <!-- Hotels Tab Content (Default Active) -->
                <div id="content-hotels" class="tab-content">
                    <!-- Filter and Sort Bar -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                        <div class="flex flex-wrap gap-2">
                            <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50 flex items-center">
                                <i data-lucide="sliders" class="w-4 h-4 mr-2"></i>
                                Filters
                            </button>
                            <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50">
                                Price Range
                            </button>
                            <button class="px-4 py-2 bg-white border border-gray-300 rounded-full text-sm font-medium hover:bg-gray-50">
                                Star Rating
                            </button>
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Sort by:</span>
                            <select class="py-2 px-4 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="recommended">Recommended</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating">Rating</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Hotels Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @forelse($hotels ?? [] as $hotel)
                            <!-- Hotel Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md group">
                                <!-- Hotel Image -->
                                <div class="relative h-48">
                                    <img src="{{ asset('images/hotels/' . ($hotel['image'] ?? 'default-hotel.jpg')) }}" alt="{{ $hotel['name'] }}" class="w-full h-full object-cover">
                                    <!-- Wishlist Button -->
                                    <button class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm p-2 rounded-full shadow-md hover:bg-white transition-colors">
                                        <i data-lucide="heart" class="w-5 h-5 text-gray-600"></i>
                                    </button>
                                </div>
                                
                                <!-- Hotel Info -->
                                <div class="p-4">
                                    <!-- Rating -->
                                    <div class="flex items-center mb-2">
                                        @for($i = 0; $i < ($hotel['stars'] ?? 5); $i++)
                                            <i data-lucide="star" class="w-4 h-4 text-yellow-500 fill-current"></i>
                                        @endfor
                                    </div>
                                    
                                    <!-- Hotel Name and Location -->
                                    <h3 class="font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">{{ $hotel['name'] ?? 'Hotel Name' }}</h3>
                                    <p class="text-sm text-gray-600 mb-2 flex items-center">
                                        <i data-lucide="map-pin" class="w-3 h-3 mr-1"></i>
                                        {{ $hotel['location'] ?? 'Location' }}
                                    </p>
                                    
                                    <!-- Amenities -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @foreach($hotel['amenities'] ?? ['Free WiFi', 'Pool'] as $amenity)
                                            <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">{{ $amenity }}</span>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Price and View Button -->
                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-500">From</span>
                                            <span class="text-lg font-bold text-indigo-600">{{ $hotel['currency'] ?? 'USD' }} {{ number_format($hotel['price'] ?? 100) }}</span>
                                            <span class="text-xs text-gray-500">per night</span>
                                        </div>
                                        
                                        <a href="{{ route('hotels.select', ['hotel_id' => $hotel['id'] ?? 1]) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            View Deal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- No Hotels Found -->
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">
                                <div class="mb-6">
                                    <i data-lucide="hotel" class="w-16 h-16 mx-auto text-gray-300"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">No Hotels Found</h3>
                                <p class="text-gray-600 mb-6">We couldn't find any hotels in this destination matching your criteria.</p>
                                <button onclick="modifySearch()" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                    Modify Search
                                </button>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- View All Hotels Button -->
                    @if(count($hotels ?? []) > 9)
                        <div class="text-center">
                            <a href="{{ route('hotels.all', ['destination' => $destination['slug'] ?? 'destination']) }}" class="px-6 py-3 border border-indigo-600 text-indigo-600 font-medium rounded-lg hover:bg-indigo-50 transition-colors inline-flex items-center">
                                View All Hotels
                                <i data-lucide="chevron-right" class="w-5 h-5 ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Flights Tab Content -->
                <div id="content-flights" class="tab-content hidden">
                    <!-- Flight Search Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                        <h3 class="text-lg font-semibold mb-4">Search Flights to {{ $destination['name'] ?? 'Destination' }}</h3>
                        
                        <form action="{{ route('flights.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <input type="hidden" name="destination" value="{{ $destination['iata_code'] ?? '' }}">
                            
                            <div>
                                <label for="departure" class="block text-sm font-medium text-gray-700 mb-1">From</label>
                                <div class="relative">
                                    <i data-lucide="plane-takeoff" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <input type="text" id="departure" name="departure" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="City or airport">
                                </div>
                            </div>
                            
                            <div>
                                <label for="departure_date" class="block text-sm font-medium text-gray-700 mb-1">Departure Date</label>
                                <div class="relative">
                                    <i data-lucide="calendar" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <input type="date" id="departure_date" name="departure_date" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">Return Date</label>
                                <div class="relative">
                                    <i data-lucide="calendar" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                                    <input type="date" id="return_date" name="return_date" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors flex items-center justify-center">
                                    <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                                    Search Flights
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Popular Routes -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-6">Popular Routes to {{ $destination['name'] ?? 'Destination' }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($popularRoutes ?? [
                                ['from' => 'Nairobi', 'from_code' => 'NBO', 'price' => 250, 'currency' => 'USD', 'airlines' => ['Kenya Airways', 'Ethiopian Airlines']],
                                ['from' => 'Dar es Salaam', 'from_code' => 'DAR', 'price' => 180, 'currency' => 'USD', 'airlines' => ['Air Tanzania', 'Kenya Airways']],
                                ['from' => 'Entebbe', 'from_code' => 'EBB', 'price' => 210, 'currency' => 'USD', 'airlines' => ['Uganda Airlines', 'RwandAir']],
                                ['from' => 'Kigali', 'from_code' => 'KGL', 'price' => 190, 'currency' => 'USD', 'airlines' => ['RwandAir', 'Kenya Airways']],
                                ['from' => 'Addis Ababa', 'from_code' => 'ADD', 'price' => 320, 'currency' => 'USD', 'airlines' => ['Ethiopian Airlines']],
                                ['from' => 'Zanzibar', 'from_code' => 'ZNZ', 'price' => 150, 'currency' => 'USD', 'airlines' => ['Air Tanzania', 'Precision Air']]
                            ] as $route)
                                <!-- Route Card -->
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex flex-col">
                                                <span class="text-sm text-gray-500">From</span>
                                                <span class="font-semibold text-gray-900">{{ $route['from'] }}</span>
                                                <span class="text-sm text-gray-500">{{ $route['from_code'] }}</span>
                                            </div>
                                            <div class="flex-1 flex items-center justify-center px-4">
                                                <div class="h-px bg-gray-300 flex-grow"></div>
                                                <i data-lucide="plane" class="w-5 h-5 text-gray-400 mx-2"></i>
                                                <div class="h-px bg-gray-300 flex-grow"></div>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm text-gray-500">To</span>
                                                <span class="font-semibold text-gray-900">{{ $destination['name'] ?? 'Destination' }}</span>
                                                <span class="text-sm text-gray-500">{{ $destination['iata_code'] ?? 'DST' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="text-sm text-gray-500 mb-3">Airlines: {{ implode(', ', $route['airlines']) }}</div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm text-gray-500">From</span>
                                                <span class="text-lg font-bold text-indigo-600">{{ $route['currency'] }} {{ number_format($route['price']) }}</span>
                                            </div>
                                            
                                            <a href="{{ route('flights.search', ['departure' => $route['from_code'], 'destination' => $destination['iata_code'] ?? '']) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors">
                                                Find Flights
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Tours Tab Content -->
                <div id="content-tours" class="tab-content hidden">
                    <!-- Tours Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @forelse($tours ?? [] as $tour)
                            <!-- Tour Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
                                <!-- Tour Image -->
                                <div class="relative h-48">
                                    <img src="{{ asset('images/tours/' . ($tour['image'] ?? 'default-tour.jpg')) }}" alt="{{ $tour['name'] ?? 'Tour Name' }}" class="w-full h-full object-cover">
                                    <!-- Duration Badge -->
                                    <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs flex items-center">
                                        <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                        {{ $tour['duration'] ?? '3 hours' }}
                                    </div>
                                </div>
                                
                                <!-- Tour Info -->
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <!-- Tour Name -->
                                        <h3 class="font-bold text-gray-900">{{ $tour['name'] ?? 'Tour Name' }}</h3>
                                        
                                        <!-- Rating -->
                                        <div class="flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-xs">
                                            <i data-lucide="star" class="w-3 h-3 fill-current mr-1"></i>
                                            <span>{{ $tour['rating'] ?? '4.5' }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Tour Type -->
                                    <div class="mb-2">
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">{{ $tour['type'] ?? 'City Tour' }}</span>
                                    </div>
                                    
                                    <!-- Description -->
                                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $tour['description'] ?? 'Experience the beauty and culture of this amazing destination with our expert guides.' }}</p>
                                    
                                    <!-- Price and View Button -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-500">From</span>
                                            <span class="text-lg font-bold text-purple-600">{{ $tour['currency'] ?? 'USD' }} {{ number_format($tour['price'] ?? 65) }}</span>
                                            <span class="text-xs text-gray-500">per person</span>
                                        </div>
                                        
                                        <a href="{{ route('tours.select', ['tour_id' => $tour['id'] ?? 1]) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- No Tours Found -->
                            <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">
                                <div class="mb-6">
                                    <i data-lucide="map" class="w-16 h-16 mx-auto text-gray-300"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">No Tours Found</h3>
                                <p class="text-gray-600 mb-6">We couldn't find any tours in this destination matching your criteria.</p>
                                <button onclick="modifySearch()" class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                                    Modify Search
                                </button>
                            </div>
                        @endforelse
                    </div>
                    
                    <!-- View All Tours Button -->
                    @if(count($tours ?? []) > 9)
                        <div class="text-center">
                            <a href="{{ route('tours.all', ['destination' => $destination['slug'] ?? 'destination']) }}" class="px-6 py-3 border border-purple-600 text-purple-600 font-medium rounded-lg hover:bg-purple-50 transition-colors inline-flex items-center">
                                View All Tours
                                <i data-lucide="chevron-right" class="w-5 h-5 ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Vacation Packages Tab Content -->
                <div id="content-packages" class="tab-content hidden">
                    <!-- Packages Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        @forelse($packages ?? [] as $package)
                            <!-- Package Card -->
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Package Image -->
                                    <div class="md:w-2/5 relative">
                                        <img src="{{ asset('images/packages/' . ($package['image'] ?? 'default-package.jpg')) }}" alt="{{ $package['name'] ?? 'Package Name' }}" class="w-full h-full object-cover aspect-[4/3] md:aspect-auto">
                                        <!-- Duration Badge -->
                                        <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs flex items-center">
                                            <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                            {{ $package['duration'] ?? '5 days' }}
                                        </div>
                                    </div>
                                    
                                    <!-- Package Info -->
                                    <div class="md:w-3/5 p-4">
                                        <h3 class="font-bold text-gray-900 mb-2">{{ $package['name'] ?? 'Package Name' }}</h3>
                                        
                                        <!-- Package Features -->
                                        <div class="space-y-2 mb-4">
                                            @foreach($package['features'] ?? ['Hotel accommodation', 'Airport transfers', 'Daily breakfast', 'City tour included'] as $feature)
                                                <div class="flex items-center text-sm">
                                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2"></i>
                                                    <span>{{ $feature }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        <!-- Price and View Button -->
                                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                            <div class="flex flex-col">
                                                <span class="text-sm text-gray-500">From</span>
                                                <span class="text-lg font-bold text-indigo-600">{{ $package['currency'] ?? 'USD' }} {{ number_format($package['price'] ?? 499) }}</span>
                                                <span class="text-xs text-gray-500">per person</span>
                                            </div>
                                            
                                            <a href="{{ route('packages.select', ['package_id' => $package['id'] ?? 1]) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                View Package
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <!-- No Packages Found -->
                            <div class="col-span-1 md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-10 text-center">
                                <div class="mb-6">
                                    <i data-lucide="package" class="w-16 h-16 mx-auto text-gray-300"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">No Packages Found</h3>
                                <p class="text-gray-600 mb-6">We couldn't find any vacation packages for this destination matching your criteria.</p>
                                <button onclick="modifySearch()" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                                    Modify Search
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Travel Guide -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-12">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $destination['name'] ?? 'Destination' }} Travel Guide</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Getting There & Around -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i data-lucide="plane" class="w-5 h-5 text-indigo-600 mr-2"></i>
                                Getting There & Around
                            </h3>
                            <div class="prose prose-sm">
                                <p>{{ $destination['gettingAround'] ?? 'The city is serviced by an international airport with regular flights from major East African cities. Once in the city, taxis, ride-sharing services, and public transportation are readily available. For exploring nearby attractions, car rentals or organized tours are recommended.' }}</p>
                            </div>
                        </div>
                        
                        <!-- Where to Stay -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i data-lucide="hotel" class="w-5 h-5 text-indigo-600 mr-2"></i>
                                Where to Stay
                            </h3>
                            <div class="prose prose-sm">
                                <p>{{ $destination['whereToStay'] ?? 'Accommodation options range from luxury hotels and boutique guesthouses to budget-friendly hostels. The city center offers proximity to attractions and dining, while beachfront properties provide stunning views and a more relaxed atmosphere.' }}</p>
                            </div>
                        </div>
                        
                        <!-- Food & Dining -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 flex items-center">
                                <i data-lucide="utensils" class="w-5 h-5 text-indigo-600 mr-2"></i>
                                Food & Dining
                            </h3>
                            <div class="prose prose-sm">
                                <p>{{ $destination['foodAndDining'] ?? 'Sample local East African cuisine featuring fresh seafood, grilled meats, and flavorful spices. From street food vendors to upscale restaurants, there are dining options for every budget. Don\'t miss trying traditional dishes and local beverages for an authentic experience.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="bg-indigo-700 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Get Travel Inspiration</h2>
                <p class="text-indigo-100 mb-6">Subscribe to our newsletter for exclusive deals and travel tips for East African destinations.</p>
                <form class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Your email address" class="flex-1 px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    <button type="submit" class="px-6 py-3 bg-yellow-500 hover:bg-yellow-400 text-indigo-900 font-medium rounded-lg transition-colors">Subscribe</button>
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
        
        // Tab functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                tabBtns.forEach(b => {
                    b.classList.remove('border-indigo-500', 'text-indigo-600');
                    b.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Add active class to clicked button
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-indigo-500', 'text-indigo-600');
                
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Show selected tab content
                const tabId = this.id.replace('tab-', 'content-');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });
    });
    
    // Function to modify search
    function modifySearch() {
        // Scroll to search box
        document.querySelector('form[action="{{ route('destinations.search') }}"]').scrollIntoView({ behavior: 'smooth' });
    }
    
    // Toast notification functionality
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white transform transition-all duration-300 translate-y-20 opacity-0`;
        
        toast.innerHTML = `
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Initialize the Lucide icon in the toast
        lucide.createIcons();
        
        // Show toast with animation
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
        }, 100);
        
        // Hide toast after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            
            // Remove from DOM after animation completes
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
    }
    
    // Show success toast if redirected with success message
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('success') }}', 'success');
        });
    @endif
    
    // Show error toast if redirected with error message
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('error') }}', 'error');
        });
    @endif
</script>
@endsection
