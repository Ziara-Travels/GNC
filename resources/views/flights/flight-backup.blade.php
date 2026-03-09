@extends('layouts.main')

@section('content')
    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        /* Added styles for flight deals section */
        .flight-card {
            transition: all 0.3s ease;
        }
        
        .flight-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .no-flights-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-again-btn {
            background: linear-gradient(45deg, #ffc107, #ffeb3b);
            border: none;
            color: #212529;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .search-again-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
            color: #212529;
        }
        
        .loading-airplane {
            animation: fly 2s ease-in-out infinite alternate;
        }
        
        @keyframes fly {
            0% { transform: translateX(-10px) rotate(-5deg); }
            100% { transform: translateX(10px) rotate(5deg); }
        }
        
        .results-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .flight-deal-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .flight-deal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .flight-deal-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(45deg, #e53935, #ff5252);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        @keyframes pulse-deal {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation {
            animation: pulse-deal 2s infinite;
        }
        .urgency-badge-small {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.6rem;
            font-weight: bold;
            letter-spacing: 0.3px;
            margin-top: 4px;
        }

        .urgency-high {
            background: #dc3545;
            color: white;
        }

        .urgency-medium {
            background: #ffc107;
            color: #212529;
        }

        .urgency-low {
            background: #28a745;
            color: white;
        }

        .forecast-change-small {
            font-weight: bold;
        }

        .typing-dots {
            display: inline-flex;
            gap: 2px;
            align-items: center;
        }

        .typing-dots span {
            width: 4px;
            height: 4px;
            background-color: #6b7280;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-10px);
            }
        }

        .hero-section {
            min-height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .filter-active {
            background-color: #dbeafe !important;
            border-color: #3b82f6 !important;
        }

        .price-slider {
            -webkit-appearance: none;
            appearance: none;
            height: 6px;
            background: #e5e7eb;
            outline: none;
            border-radius: 3px;
        }

        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            background: #3b82f6;
            cursor: pointer;
            border-radius: 50%;
        }

        .hotel-card {
            transition: transform 0.2s;
        }

        .hotel-card:hover {
            transform: translateY(-2px);
        }

        .currency-ksh {
            color: #059669;
        }

        .currency-usd {
            color: #3b82f6;
        }

        /* Existing styles... */

        .wishlist-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 10;
        }

        .wishlist-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }

        .wishlist-btn.active {
            background: #ef4444;
            color: white;
        }

        .wishlist-btn.active:hover {
            background: #dc2626;
        }
    </style>



    @if(isset($noFlightsFound) && $noFlightsFound)
        <!-- No Flights Found Section -->
        <div class="no-flights-container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <div class="bg-white rounded-lg shadow-lg p-5">
                            <!-- Animated Airplane -->
                            <div class="mb-4">
                                <i class="fas fa-plane loading-airplane text-primary" style="font-size: 4rem;"></i>
                            </div>
                            
                            <h2 class="mb-3 text-dark fw-bold">No Flights Found</h2>
                            
                            <p class="text-muted mb-4 fs-5">
                                We couldn't find any flights matching your search criteria. 
                                <br>Please try adjusting your dates or destinations.
                            </p>
                            
                            <!-- Search Tips -->
                            <div class="bg-light p-4 rounded-3 mb-4 text-start">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-lightbulb text-warning me-2"></i>
                                    Try these suggestions:
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        Check different dates (±3 days)
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                        Try nearby airports
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-plane text-info me-2"></i>
                                        Remove "Direct flights only" filter
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        Consider different travel classes
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                <a href="{{ route('search-flight') }}" class="btn search-again-btn">
                                    <i class="fas fa-search me-2"></i>
                                    Search Again
                                </a>
                                
                                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-home me-2"></i>
                                    Back to Home
                                </a>
                                
                                <button class="btn btn-outline-secondary" onclick="showFlexibleSearch()">
                                    <i class="fas fa-calendar-week me-2"></i>
                                    Flexible Dates
                                </button>
                            </div>
                            
                            <!-- Contact Support -->
                            <div class="mt-4 pt-4 border-top">
                                <p class="text-muted mb-2">Need help finding flights?</p>
                                <div class="d-flex gap-3 justify-content-center">
                                    <a href="tel:+1234567890" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-phone me-1"></i>
                                        Call Us
                                    </a>
                                    <a href="mailto:support@example.com" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-envelope me-1"></i>
                                        Email
                                    </a>
                                    <button class="btn btn-sm btn-outline-primary" onclick="openLiveChat()">
                                        <i class="fas fa-comments me-1"></i>
                                        Live Chat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      <!-- Flight Deals Section when no results found -->
        <div class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-4">Or explore these amazing deals</h2>
                @include('partials.flight-deals')
            </div>
        </div>

      @else
        <!-- Flight Results Found -->
        <div class="container py-5">
            <!-- Results Header -->
            <div class="results-header text-center">
                <h1 class="mb-3 fw-bold text-dark">
                    <i class="fas fa-plane-departure text-primary me-3"></i>
                    Flight Search Results
                </h1>
                
                @if(isset($searchParams))
                    <div class="search-summary">
                        <p class="mb-2 text-muted fs-5">
                            <strong>{{ $searchParams['from'] ?? 'Origin' }}</strong> 
                            <i class="fas fa-arrow-right mx-2 text-primary"></i> 
                            <strong>{{ $searchParams['to'] ?? 'Destination' }}</strong>
                        </p>
                        
                        <div class="d-flex flex-wrap justify-content-center gap-3 text-muted">
                            <span>
                                <i class="fas fa-calendar me-1"></i>
                                {{ $searchParams['date'] ?? 'N/A' }}
                                @if(!empty($searchParams['return_date']))
                                    - {{ $searchParams['return_date'] }}
                                @endif
                            </span>
                            <span>
                                <i class="fas fa-users me-1"></i>
                                {{ ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0) }} Passengers
                            </span>
                            <span>
                                <i class="fas fa-star me-1"></i>
                                {{ ucwords(str_replace('_', ' ', strtolower($searchParams['travel_class'] ?? 'Economy'))) }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>
            
            @if(isset($flights) && count($flights) > 0)
                <div class="row">
                    <!-- Filters Column -->
                    <div class="col-lg-3 mb-4">
                        @include('flights.partials.filters')
                    </div>
                    
                    <!-- Results Column -->
                    <div class="col-lg-9">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3>
                                    <span class="badge bg-success fs-6">{{ count($flights) }}</span>
                                    Flights Found
                                </h3>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                                        <i class="fas fa-sort me-2"></i>Sort By
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                        <li><a class="dropdown-item" href="#" data-sort="price-asc">
                                            <i class="fas fa-sort-amount-up me-2"></i>Price (Low to High)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" data-sort="price-desc">
                                            <i class="fas fa-sort-amount-down me-2"></i>Price (High to Low)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" data-sort="duration-asc">
                                            <i class="fas fa-clock me-2"></i>Duration (Shortest)
                                        </a></li>
                                        <li><a class="dropdown-item" href="#" data-sort="stops-asc">
                                            <i class="fas fa-route me-2"></i>Stops (Least first)
                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Flight Results List -->
                        <div class="flight-results">
                            @foreach($flights as $flight)
                                @include('flights.partials.flight-card', ['flight' => $flight, 'isOfferMode' => $isOfferMode ?? false])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @elseif(isset($flights) && count($flights) > 0)
        <!-- Your existing flight results rendering code -->
    @else
        <div class="bg-white rounded-lg shadow-sm border p-16 text-center">
            <i data-lucide="alert-circle" class="w-20 h-20 text-gray-400 mx-auto mb-6"></i>
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">Something went wrong</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">We encountered an error while processing your flight search. Please try again.</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    New Search
                </a>
            </div>
        </div>
    @endif

    <!-- Navigation Bar -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                        <i data-lucide="plane" class="w-8 h-8 inline mr-2"></i>
                        FlightSearch
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 flex items-center gap-2">
                        <i data-lucide="home" class="w-4 h-4"></i>
                        Home
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 flex items-center gap-2">
                        <i data-lucide="plane" class="w-4 h-4"></i>
                        Flights
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 flex items-center gap-2">
                        <i data-lucide="building" class="w-4 h-4"></i>
                        Hotels
                    </a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        Support
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-600" id="locationInfo">
                        <i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i>
                        <span id="userLocation">Loading...</span>
                    </div>
                    <div class="text-sm text-gray-600">
                        <i data-lucide="globe" class="w-4 h-4 inline mr-1"></i>
                        <span id="currencyDisplay">USD</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-4">
                        <i data-lucide="route" class="w-8 h-8"></i>
                        <h1 class="text-4xl font-bold">
                            <span class="text-yellow-300">{{ $searchParams['from_iata'] ?? 'N/A' }}</span>
                            <i data-lucide="arrow-right" class="w-6 h-6 inline mx-2"></i>
                            <span class="text-yellow-300">{{ $searchParams['to_iata'] ?? 'N/A' }}</span>
                        </h1>
                    </div>
                    <div class="flex flex-wrap gap-6 text-lg opacity-90">
                        <span class="flex items-center gap-2">
                            <i data-lucide="calendar-days" class="w-5 h-5"></i>
                            {{ ucfirst(str_replace('_', ' ', $searchParams['trip_type'] ?? 'one_way')) }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            {{ $searchParams['adults'] ?? 1 }} Adult{{ ($searchParams['adults'] ?? 1) > 1 ? 's' : '' }}
                            @if (($searchParams['children'] ?? 0) > 0)
                                , {{ $searchParams['children'] }} Child{{ $searchParams['children'] > 1 ? 'ren' : '' }}
                            @endif
                            @if (($searchParams['infants'] ?? 0) > 0)
                                , {{ $searchParams['infants'] }} Infant{{ $searchParams['infants'] > 1 ? 's' : '' }}
                            @endif
                        </span>
                        <span class="flex items-center gap-2">
                            <i data-lucide="crown" class="w-5 h-5"></i>
                            {{ ucfirst(strtolower($searchParams['travel_class'] ?? 'Economy')) }}
                        </span>
                        <span class="flex items-center gap-2">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                            {{ $searchParams['date'] ?? 'N/A' }}
                            @if (isset($searchParams['return_date']))
                                - {{ $searchParams['return_date'] }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button onclick="modifySearch()"
                        class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors flex items-center gap-2">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                        Modify Search
                    </button>
                    <button onclick="setPriceAlert()"
                        class="bg-blue-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-800 transition-colors flex items-center gap-2">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        Price Alert
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Results Layout -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-8">
            <!-- Left Sidebar - Dynamic Filters -->
            <div class="w-80 flex-shrink-0 space-y-6">
                <!-- Reset Filters -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <button onclick="resetFilters()"
                        class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Reset All Filters
                    </button>
                </div>

                <!-- Price Range Filter -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                        Price Range
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span id="minPriceDisplay">$0</span>
                            <span id="maxPriceDisplay">$2000</span>
                        </div>
                        <div class="relative">
                            <input type="range" id="priceRange" min="0" max="2000" step="50"
                                value="2000" class="price-slider w-full">
                        </div>
                        <div class="text-center text-sm text-gray-600">
                            Max: <span id="selectedPriceDisplay" class="font-semibold">$2000</span>
                        </div>
                    </div>
                </div>

                <!-- Stops Filter -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        Stops
                    </h3>
                    <div class="space-y-3" id="stopsFilter">
                        <!-- Dynamically populated -->
                    </div>
                </div>

                <!-- Airlines Filter -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="plane" class="w-4 h-4"></i>
                        Airlines
                    </h3>
                    <div class="space-y-3" id="airlinesFilter">
                        <!-- Dynamically populated -->
                    </div>
                </div>

                <!-- Departure Time Filter -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        Departure Time
                    </h3>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors"
                            data-time="early">
                            <i data-lucide="sunrise" class="w-4 h-4 mx-auto mb-1"></i>
                            <div class="font-medium">Early Morning</div>
                            <div class="text-gray-500">06:00 - 12:00</div>
                        </button>
                        <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors"
                            data-time="afternoon">
                            <i data-lucide="sun" class="w-4 h-4 mx-auto mb-1"></i>
                            <div class="font-medium">Afternoon</div>
                            <div class="text-gray-500">12:00 - 18:00</div>
                        </button>
                        <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors"
                            data-time="evening">
                            <i data-lucide="sunset" class="w-4 h-4 mx-auto mb-1"></i>
                            <div class="font-medium">Evening</div>
                            <div class="text-gray-500">18:00 - 00:00</div>
                        </button>
                        <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors"
                            data-time="night">
                            <i data-lucide="moon" class="w-4 h-4 mx-auto mb-1"></i>
                            <div class="font-medium">Night</div>
                            <div class="text-gray-500">00:00 - 06:00</div>
                        </button>
                    </div>
                </div>

                <!-- Duration Filter -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="timer" class="w-4 h-4"></i>
                        Flight Duration
                    </h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                class="duration-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                data-duration="short">
                            <span class="text-sm">Short (0-6 hours)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                class="duration-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                data-duration="medium">
                            <span class="text-sm">Medium (6-12 hours)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox"
                                class="duration-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                data-duration="long">
                            <span class="text-sm">Long (12+ hours)</span>
                        </label>
                    </div>
                </div>

                <!-- AI Price Prediction Widget -->
                <div class="bg-white rounded-lg shadow-sm p-6 border mb-6">
                    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-5 h-5 text-blue-600"></i>
                        Price Predictions
                    </h3>

                    <div id="quickPricePrediction" class="space-y-4">
                        <div class="text-center py-4">
                            <button onclick="loadPricePrediction()"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="zap" class="w-4 h-4 inline mr-2"></i>
                                Get Price Forecast
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Results Section -->
            <div class="flex-1 space-y-6">
                <!-- Sort and View Options -->
                <div class="bg-white rounded-lg shadow-sm p-4 border">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <h2 class="font-semibold text-lg">
                                <span id="flightCount">{{ isset($flights) ? count($flights) : 0 }}</span> flights found
                            </h2>
                            <div class="text-sm text-gray-600">
                                <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i>
                                <span id="activeFiltersCount">0</span> filters active
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <select id="sortSelect"
                                class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="best">Best Value</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="duration">Shortest Duration</option>
                                <option value="departure">Earliest Departure</option>
                                <option value="arrival">Latest Arrival</option>
                            </select>
                            <div class="flex border rounded-lg">
                                <button class="px-4 py-2 bg-blue-50 text-blue-600 text-sm rounded-l-lg">
                                    <i data-lucide="list" class="w-4 h-4"></i>
                                </button>
                                <button class="px-4 py-2 text-gray-600 text-sm rounded-r-lg hover:bg-gray-50">
                                    <i data-lucide="calendar" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flight Results -->
                <div id="flightResults" class="space-y-4">
                    @if (isset($flights) && count($flights) > 0)
                        @foreach ($flights as $index => $flight)
                            @php
                                $firstSegment = $flight->itineraries[0]->segments[0];
                                $lastSegment = end($flight->itineraries[0]->segments);
                                $carrierCode = $firstSegment->carrierCode;
                                $airlineName = isset($flight->dictionaries->carriers->{$carrierCode})
                                    ? $flight->dictionaries->carriers->{$carrierCode}
                                    : $carrierCode;
                                $numberOfStops = count($flight->itineraries[0]->segments) - 1;
                                $cabin = $flight->travelerPricings[0]->fareDetailsBySegment[0]->cabin ?? 'N/A';
                                $departureTime = date('H:i', strtotime($firstSegment->departure->at));
                                $arrivalTime = date('H:i', strtotime($lastSegment->arrival->at));
                                $departureDate = date('M j', strtotime($firstSegment->departure->at));
                                $arrivalDate = date('M j', strtotime($lastSegment->arrival->at));
                                $aircraft = $firstSegment->aircraft->code ?? 'N/A';
                            @endphp

                            <div class="flight-card bg-white rounded-lg shadow-sm border hover:shadow-lg transition-all duration-300 p-6"
                                data-price="{{ $flight->price->total }}" data-stops="{{ $numberOfStops }}"
                                data-airline="{{ $carrierCode }}"
                                data-departure-time="{{ date('H', strtotime($firstSegment->departure->at)) }}"
                                data-duration="{{ $flight->itineraries[0]->duration }}"
                                data-flight-index="{{ $index }}">

                                {{-- Wishlist Button --}}
                                <button class="wishlist-btn" onclick="toggleWishlist({{ $index }}, 'flight')"
                                    data-flight-index="{{ $index }}" data-wishlist-status="false">
                                    <i data-lucide="heart" class="w-5 h-5"></i>
                                </button>

                                <!-- Main Flight Card -->
                                <div class="flight-main-info">
                                    <div class="flex items-center justify-between">
                                        <!-- Flight Info -->
                                        <div class="flex items-center gap-8 flex-1">
                                            <!-- Airline Info -->
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
                                                    {{ $carrierCode }}
                                                </div>
                                                <div>
                                                    <div class="font-semibold text-base">{{ $airlineName }}</div>
                                                    <div class="text-sm text-gray-500 flex items-center gap-1">
                                                        <i data-lucide="plane" class="w-3 h-3"></i>
                                                        {{ $firstSegment->number ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Flight Route -->
                                            <div class="flex items-center gap-8 flex-1">
                                                <!-- Departure -->
                                                <div class="text-center">
                                                    <div class="text-2xl font-bold text-gray-800">{{ $departureTime }}
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-600">
                                                        {{ $firstSegment->departure->iataCode }}</div>
                                                    <div class="text-xs text-gray-500">{{ $departureDate }}</div>
                                                </div>

                                                <!-- Flight Path -->
                                                <div class="flex-1 flex flex-col items-center">
                                                    <div class="text-xs text-gray-500 mb-1">
                                                        {{ $flight->itineraries[0]->duration }}</div>
                                                    <div class="flex items-center w-full">
                                                        <div class="flex-1 h-px bg-gray-300 relative">
                                                            <i data-lucide="plane"
                                                                class="w-4 h-4 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-blue-500 bg-white"></i>
                                                        </div>
                                                    </div>
                                                    <div class="text-xs mt-1 flex items-center gap-1">
                                                        @if ($numberOfStops == 0)
                                                            <i data-lucide="zap" class="w-3 h-3 text-green-500"></i>
                                                            <span class="text-green-600 font-medium">Direct</span>
                                                        @else
                                                            <i data-lucide="circle" class="w-3 h-3 text-orange-500"></i>
                                                            <span class="text-orange-600">{{ $numberOfStops }}
                                                                stop{{ $numberOfStops > 1 ? 's' : '' }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Arrival -->
                                                <div class="text-center">
                                                    <div class="text-2xl font-bold text-gray-800">{{ $arrivalTime }}
                                                    </div>
                                                    <div class="text-sm font-medium text-gray-600">
                                                        {{ $lastSegment->arrival->iataCode }}</div>
                                                    <div class="text-xs text-gray-500">{{ $arrivalDate }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Price and Action -->
                                        <div class="text-right ml-8">
                                            <div class="text-3xl font-bold text-green-600 mb-1">
                                                <span class="currency-symbol">$</span><span
                                                    class="flight-price">{{ number_format($flight->price->total, 0) }}</span>
                                            </div>
                                            <div class="text-sm text-gray-500 mb-4">per person</div>

                                            <!-- Action Buttons -->
                                            <div class="space-y-2">
                                                <button onclick="selectFlight({{ $index }})"
                                                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                                    Select Flight
                                                </button>
                                                <button onclick="toggleFlightDetails({{ $index }})"
                                                    class="w-full text-blue-600 text-sm hover:underline flex items-center justify-center gap-1">
                                                    <i data-lucide="info" class="w-4 h-4"></i>
                                                    <span class="toggle-text">View Details</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expandable Flight Details -->
                                <div class="flight-details hidden mt-6 pt-6 border-t">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <!-- Flight Information -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-lg flex items-center gap-2">
                                                <i data-lucide="info" class="w-5 h-5"></i>
                                                Flight Information
                                            </h4>
                                            <div class="space-y-3 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="calendar" class="w-4 h-4 text-gray-500"></i>
                                                    <span>{{ date('M j, Y', strtotime($firstSegment->departure->at)) }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="clock" class="w-4 h-4 text-gray-500"></i>
                                                    <span>{{ $departureTime }} - {{ $arrivalTime }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="timer" class="w-4 h-4 text-gray-500"></i>
                                                    <span>{{ $flight->itineraries[0]->duration }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="settings" class="w-4 h-4 text-gray-500"></i>
                                                    <span>Aircraft: {{ $aircraft }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Passenger & Class Info -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-lg flex items-center gap-2">
                                                <i data-lucide="users" class="w-5 h-5"></i>
                                                Passenger Details
                                            </h4>
                                            <div class="space-y-3 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                                                    <span>{{ $searchParams['adults'] ?? 1 }}
                                                        Adult{{ ($searchParams['adults'] ?? 1) > 1 ? 's' : '' }}</span>
                                                </div>
                                                @if (($searchParams['children'] ?? 0) > 0)
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="baby" class="w-4 h-4 text-gray-500"></i>
                                                        <span>{{ $searchParams['children'] }}
                                                            Child{{ $searchParams['children'] > 1 ? 'ren' : '' }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="crown" class="w-4 h-4 text-gray-500"></i>
                                                    <span>{{ ucfirst(strtolower($cabin)) }} Class</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="shield-check" class="w-4 h-4 text-gray-500"></i>
                                                    <span>Changeable</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Baggage & Amenities -->
                                        <div class="space-y-4">
                                            <h4 class="font-semibold text-lg flex items-center gap-2">
                                                <i data-lucide="luggage" class="w-5 h-5"></i>
                                                Baggage & Amenities
                                            </h4>
                                            <div class="space-y-3 text-sm">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="briefcase" class="w-4 h-4 text-gray-500"></i>
                                                    <span>Carry-on: 1 piece (7kg)</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="package" class="w-4 h-4 text-gray-500"></i>
                                                    <span>Checked: 1 piece (23kg)</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="wifi" class="w-4 h-4 text-gray-500"></i>
                                                    <span>WiFi Available</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="utensils" class="w-4 h-4 text-gray-500"></i>
                                                    <span>Meal Included</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Segment Details -->
                                    @if (count($flight->itineraries[0]->segments) > 1)
                                        <div class="mt-6 pt-6 border-t">
                                            <h4 class="font-semibold text-lg mb-4 flex items-center gap-2">
                                                <i data-lucide="route" class="w-5 h-5"></i>
                                                Flight Segments
                                            </h4>
                                            <div class="space-y-4">
                                                @foreach ($flight->itineraries[0]->segments as $segmentIndex => $segment)
                                                    <div class="bg-gray-50 rounded-lg p-4">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center gap-4">
                                                                <span
                                                                    class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                                                                    Segment {{ $segmentIndex + 1 }}
                                                                </span>
                                                                <span class="text-lg font-semibold">
                                                                    {{ $segment->departure->iataCode }} →
                                                                    {{ $segment->arrival->iataCode }}
                                                                </span>
                                                            </div>
                                                            <div class="text-sm text-gray-600">
                                                                {{ date('H:i', strtotime($segment->departure->at)) }} -
                                                                {{ date('H:i', strtotime($segment->arrival->at)) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white rounded-lg shadow-sm border p-16 text-center">
                            <i data-lucide="plane-off" class="w-20 h-20 text-gray-400 mx-auto mb-6"></i>
                            <h3 class="text-2xl font-semibold text-gray-700 mb-4">No flights found</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">We couldn't find any flights matching your
                                criteria. Try adjusting your search parameters or dates.</p>
                            <div class="flex gap-4 justify-center">
                                <button onclick="modifySearch()"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                    Modify Search
                                </button>
                                <a href="{{ route('home') }}"
                                    class="bg-gray-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                                    New Search
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                 <!-- Add flight deals at the bottom of search results -->
                    @if(isset($flightDeals) && count($flightDeals) > 0)
                    <div class="mt-12 pt-8 border-t">
                        <h3 class="text-xl font-semibold text-gray-700 mb-6">You might also be interested in these deals</h3>
                        @include('flights.flight-deals', ['showFullSection' => false])
                    </div>
                    @endif

            </div>

            <!-- Right Sidebar - Hotels & Recommendations -->
            <div class="w-80 flex-shrink-0 space-y-6">
                <!-- Hotels in Destination -->
                <div class="bg-white rounded-lg shadow-sm p-6 border">
                    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="building" class="w-5 h-5"></i>
                        Hotels in {{ $searchParams['to_iata'] ?? 'Destination' }}
                    </h3>
                    <div class="space-y-4" id="hotelsList">
                        <div class="text-center py-4">
                            <i data-lucide="loader" class="w-6 h-6 animate-spin mx-auto mb-2"></i>
                            <p class="text-sm text-gray-600">Loading hotels...</p>
                        </div>
                    </div>
                    <button onclick="viewAllHotels()"
                        class="w-full mt-4 text-blue-600 text-sm hover:underline flex items-center justify-center gap-1">
                        <i data-lucide="external-link" class="w-4 h-4"></i>
                        View all hotels
                    </button>
                </div>

                <!-- Car Rentals -->
                {{-- <div class="bg-white rounded-lg shadow-sm p-6 border">
                    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="car" class="w-5 h-5"></i>
                        Car Rentals
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i data-lucide="car" class="w-8 h-8 text-green-600"></i>
                                <div>
                                    <div class="font-medium">Economy Car</div>
                                    <div class="text-sm text-gray-600">3 days rental</div>
                                    <div class="text-lg font-bold text-green-600">$89/day</div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-4 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i data-lucide="truck" class="w-8 h-8 text-blue-600"></i>
                                <div>
                                    <div class="font-medium">SUV</div>
                                    <div class="text-sm text-gray-600">3 days rental</div>
                                    <div class="text-lg font-bold text-blue-600">$129/day</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Popular Destinations -->
                <div class="bg-white rounded-lg shadow-sm p-6 border">
                    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                        Popular Destinations
                    </h3>
                    <div class="space-y-4">
                        <div
                            class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                            <div>
                                <div class="font-medium">Paris, France</div>
                                <div class="text-sm text-gray-600">CDG</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-green-600">from $299</div>
                                <div class="text-xs text-gray-500">round trip</div>
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                            <div>
                                <div class="font-medium">Tokyo, Japan</div>
                                <div class="text-sm text-gray-600">NRT</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-green-600">from $599</div>
                                <div class="text-xs text-gray-500">round trip</div>
                            </div>
                        </div>
                        <div
                            class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                            <div>
                                <div class="font-medium">Dubai, UAE</div>
                                <div class="text-sm text-gray-600">DXB</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-green-600">from $399</div>
                                <div class="text-xs text-gray-500">round trip</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Travel Tips -->
                <div class="bg-white rounded-lg shadow-sm p-6 border">
                    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="lightbulb" class="w-5 h-5"></i>
                        Travel Tips
                    </h3>
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="clock" class="w-4 h-4 text-blue-600"></i>
                                <div class="font-medium text-blue-800">Best Booking Time</div>
                            </div>
                            <div class="text-sm text-blue-700">Book 2-3 months in advance for international flights</div>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="bell" class="w-4 h-4 text-green-600"></i>
                                <div class="font-medium text-green-800">Price Alerts</div>
                            </div>
                            <div class="text-sm text-green-700">Set alerts to track price changes for your route</div>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <i data-lucide="calendar" class="w-4 h-4 text-yellow-600"></i>
                                <div class="font-medium text-yellow-800">Flexible Dates</div>
                            </div>
                            <div class="text-sm text-yellow-700">Flying midweek can save you up to 30%</div>
                        </div>
                    </div>
                </div>
            </div>
            @else <!-- Error state -->
        <div class="bg-white rounded-lg shadow-sm border p-16 text-center">
            <i data-lucide="alert-circle" class="w-20 h-20 text-gray-400 mx-auto mb-6"></i>
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">Something went wrong</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">We encountered an error while processing your flight search. Please try again.</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('home') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    New Search
                </a>
            </div>
        </div>

            <!-- Floating AI Chatbot Widget -->
            <div id="aiChatWidget" class="fixed bottom-6 right-6 z-50">
                <!-- Chat Toggle Button -->
                <button id="chatToggle"
                    class="bg-blue-600 text-white w-16 h-16 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 flex items-center justify-center relative">
                    <i data-lucide="message-circle" class="w-8 h-8"></i>
                    <span id="chatNotification"
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-6 h-6 rounded-full flex items-center justify-center opacity-0 scale-0 transition-all duration-300">
                        !
                    </span>
                </button>

                <!-- Chat Window -->
                <div id="chatWindow"
                    class="absolute bottom-20 right-0 w-96 h-96 bg-white rounded-lg shadow-2xl border border-gray-200 transform scale-0 origin-bottom-right transition-all duration-300">
                    <!-- Chat Header -->
                    <div class="bg-blue-600 text-white p-4 rounded-t-lg flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-700 rounded-full flex items-center justify-center">
                                <i data-lucide="bot" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm">AI Travel Assistant</h4>
                                <p class="text-xs opacity-90">Online • Ready to help</p>
                            </div>
                        </div>
                        <button id="closeChatWidget" class="text-white hover:text-gray-300">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Chat Messages -->
                    <div id="widgetChatMessages" class="flex-1 p-4 overflow-y-auto" style="height: 240px;">
                        <div class="chat-message mb-3">
                            <div class="flex items-start gap-2">
                                <div
                                    class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="bot" class="w-3 h-3 text-blue-600"></i>
                                </div>
                                <div class="bg-gray-100 rounded-lg p-2 text-sm">
                                    Hi! I can help you with flight bookings, baggage info, and travel tips. What would you
                                    like to know?
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="p-4 border-t">
                        <form id="widgetChatForm" class="flex gap-2">
                            <input type="text" id="widgetChatInput" placeholder="Ask me anything..."
                                class="flex-1 px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- AI Features Panel -->
            <div class="w-80 flex-shrink-0 space-y-6">
                <!-- Existing content... -->

                <!-- AI Layover Assistant -->
                <div class="bg-white rounded-lg shadow-sm p-6 border">
                    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="clock" class="w-5 h-5 text-purple-600"></i>
                        Layover Assistant
                    </h3>
                    <div class="text-center">
                        <i data-lucide="plane-takeoff" class="w-12 h-12 text-purple-400 mx-auto mb-3"></i>
                        <p class="text-sm text-gray-600 mb-4">Get AI-powered suggestions for your layover time</p>
                        <button onclick="openLayoverAssistant()"
                            class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i data-lucide="zap" class="w-4 h-4 inline mr-2"></i>
                            Get Layover Tips
                        </button>
                    </div>
                </div>

                <!-- AI Packing Assistant -->
                <div class="bg-white rounded-lg shadow-sm p-6 border">
                    <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-green-600"></i>
                        Packing Assistant
                    </h3>
                    <div class="text-center">
                        <i data-lucide="luggage" class="w-12 h-12 text-green-400 mx-auto mb-3"></i>
                        <p class="text-sm text-gray-600 mb-4">AI-generated packing list for your destination</p>
                        <button onclick="openPackingAssistant()"
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <i data-lucide="list-checks" class="w-4 h-4 inline mr-2"></i>
                            Create Packing List
                        </button>
                    </div>
                </div>
            </div>

            <!-- AI Modal for Layover Assistant -->
            <div id="layoverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <i data-lucide="clock" class="w-6 h-6 text-purple-600"></i>
                                AI Layover Assistant
                            </h3>
                            <button onclick="closeLayoverModal()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="layoverForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Airport Code</label>
                                    <input type="text" name="airport" placeholder="e.g., DXB, CDG, NRT"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Layover Duration</label>
                                    <select name="duration"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        required>
                                        <option value="">Select duration</option>
                                        <option value="1-2 hours">1-2 hours</option>
                                        <option value="2-4 hours">2-4 hours</option>
                                        <option value="4-8 hours">4-8 hours</option>
                                        <option value="8+ hours">8+ hours</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Time of Day</label>
                                    <select name="time_of_day"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        required>
                                        <option value="">Select time</option>
                                        <option value="early morning">Early Morning (6-9 AM)</option>
                                        <option value="morning">Morning (9-12 PM)</option>
                                        <option value="afternoon">Afternoon (12-6 PM)</option>
                                        <option value="evening">Evening (6-10 PM)</option>
                                        <option value="late night">Late Night (10 PM-6 AM)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-4">
                                <button type="button" onclick="closeLayoverModal()"
                                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                                    Generate Guide
                                </button>
                            </div>
                        </form>

                        <div id="layoverResults" class="mt-6 hidden">
                            <!-- Results will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Modal for Packing Assistant -->
            <div id="packingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <i data-lucide="package" class="w-6 h-6 text-green-600"></i>
                                AI Packing Assistant
                            </h3>
                            <button onclick="closePackingModal()" class="text-gray-400 hover:text-gray-600">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="packingForm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination</label>
                                    <input type="text" name="destination" placeholder="e.g., Tokyo, Paris, Dubai"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Trip Duration</label>
                                    <select name="duration"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required>
                                        <option value="">Select duration</option>
                                        <option value="1-2 days">1-2 days</option>
                                        <option value="3-5 days">3-5 days</option>
                                        <option value="1 week">1 week</option>
                                        <option value="2 weeks">2 weeks</option>
                                        <option value="1 month">1 month or more</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Season/Weather</label>
                                    <select name="season"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required>
                                        <option value="">Select season</option>
                                        <option value="spring">Spring (15-25°C)</option>
                                        <option value="summer">Summer (25-35°C)</option>
                                        <option value="autumn">Autumn (10-20°C)</option>
                                        <option value="winter">Winter (0-15°C)</option>
                                        <option value="tropical">Tropical (warm & humid)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Trip Type</label>
                                    <select name="trip_type"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required>
                                        <option value="">Select trip type</option>
                                        <option value="business">Business Travel</option>
                                        <option value="leisure">Leisure/Vacation</option>
                                        <option value="adventure">Adventure/Outdoor</option>
                                        <option value="cultural">Cultural/City Tour</option>
                                        <option value="beach">Beach/Resort</option>
                                        <option value="family">Family Trip</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Special Requirements</label>
                                <textarea name="special_requirements"
                                    placeholder="Any special items, medical needs, or specific activities planned..."
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    rows="3"></textarea>
                            </div>

                            <div class="flex justify-end gap-4">
                                <button type="button" onclick="closePackingModal()"
                                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                                    Generate List
                                </button>
                            </div>
                        </form>

                        <div id="packingResults" class="mt-6 hidden">
                            <!-- Results will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>

             <!-- Include Flight Deals at the bottom if results were found -->
        @if(isset($flights) && count($flights) > 0 && (!isset($isOfferMode) || !$isOfferMode))
            <div class="py-5 bg-light">
                <div class="container">
                    <h2 class="text-center mb-4">You might also be interested in</h2>
                    @include('partials.flight-deals')
                </div>
            </div>
        @endif
    @endif

    <!-- Flexible Search Modal -->
    <div class="modal fade" id="flexibleSearchModal" tabindex="-1" aria-labelledby="flexibleSearchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flexibleSearchModalLabel">
                        <i class="fas fa-calendar-week me-2"></i>
                        Flexible Date Search
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">Find the best deals by being flexible with your travel dates</p>
                    
                    <form action="/api/search" method="POST">
                        @csrf
                        <input type="hidden" name="flexible_search" value="1">
                        
                        <!-- Copy search parameters from original search -->
                        @if(isset($searchParams))
                            <input type="hidden" name="from" value="{{ $searchParams['from'] ?? '' }}">
                            <input type="hidden" name="from_iata" value="{{ $searchParams['from_iata'] ?? '' }}">
                            <input type="hidden" name="to" value="{{ $searchParams['to'] ?? '' }}">
                            <input type="hidden" name="to_iata" value="{{ $searchParams['to_iata'] ?? '' }}">
                            <input type="hidden" name="trip_type" value="{{ $searchParams['trip_type'] ?? 'round_trip' }}">
                            <input type="hidden" name="adults" value="{{ $searchParams['adults'] ?? 1 }}">
                            <input type="hidden" name="children" value="{{ $searchParams['children'] ?? 0 }}">
                            <input type="hidden" name="infants" value="{{ $searchParams['infants'] ?? 0 }}">
                            <input type="hidden" name="travel_class" value="{{ $searchParams['travel_class'] ?? 'ECONOMY' }}">
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Departure Month</label>
                                <select class="form-select" name="departure_month" required>
                                    <option value="">Select Month</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Trip Length (days)</label>
                                <select class="form-select" name="trip_length">
                                    <option value="1">1 day</option>
                                    <option value="3">3 days</option>
                                    <option value="7" selected>1 week</option>
                                    <option value="14">2 weeks</option>
                                    <option value="30">1 month</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="includeNearbyAirports" name="include_nearby" value="1">
                                    <label class="form-check-label" for="includeNearbyAirports">
                                        Include nearby airports
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Search Flexible Dates
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

            <!-- Footer -->
            <footer class="bg-gray-900 text-white mt-16">
                <div class="container mx-auto px-4 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                                <i data-lucide="plane" class="w-6 h-6"></i>
                                FlightSearch
                            </h3>
                            <p class="text-gray-400 mb-4">Find the best flights at the best prices. Compare airlines and
                                book with confidence.</p>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <i data-lucide="facebook" class="w-5 h-5"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <i data-lucide="twitter" class="w-5 h-5"></i>
                                </a>
                                <a href="#" class="text-gray-400 hover:text-white">
                                    <i data-lucide="instagram" class="w-5 h-5"></i>
                                </a>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-4">Services</h4>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#" class="hover:text-white">Flight Search</a></li>
                                <li><a href="#" class="hover:text-white">Hotel Booking</a></li>
                                <li><a href="#" class="hover:text-white">Car Rental</a></li>
                                <li><a href="#" class="hover:text-white">Travel Insurance</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-4">Support</h4>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#" class="hover:text-white">Help Center</a></li>
                                <li><a href="#" class="hover:text-white">Contact Us</a></li>
                                <li><a href="#" class="hover:text-white">Cancellation Policy</a></li>
                                <li><a href="#" class="hover:text-white">Terms & Conditions</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-4">Contact</h4>
                            <div class="space-y-2 text-gray-400">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                    <span>+1 (555) 123-4567</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                    <span>support@flightsearch.com</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    <span>123 Travel St, Flight City</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                        <p>&copy; 2024 FlightSearch. All rights reserved.</p>
                    </div>
                </div>
            </footer>

            <script>
    // Add loading state to flight search forms
    document.addEventListener('DOMContentLoaded', function() {
        // Handle loading state for search forms
        const searchForms = document.querySelectorAll('form[action*="search"]');
        searchForms.forEach(form => {
            form.addEventListener('submit', function() {
                document.getElementById('search-loading').style.display = 'block';
            });
        });
        
        // Handle sorting
        const sortOptions = document.querySelectorAll('[data-sort]');
        sortOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                const sortValue = this.getAttribute('data-sort');
                sortFlights(sortValue);
            });
        });
    });
    
    function sortFlights(sortBy) {
        const flightResults = document.querySelector('.flight-results');
        const flights = Array.from(flightResults.children);
        
        flights.sort((a, b) => {
            if (sortBy === 'price-asc') {
                return getFlightPrice(a) - getFlightPrice(b);
            } else if (sortBy === 'price-desc') {
                return getFlightPrice(b) - getFlightPrice(a);
            } else if (sortBy === 'duration-asc') {
                return getFlightDuration(a) - getFlightDuration(b);
            } else if (sortBy === 'stops-asc') {
                return getFlightStops(a) - getFlightStops(b);
            }
            return 0;
        });
        
        // Re-append sorted flights
        flights.forEach(flight => {
            flightResults.appendChild(flight);
        });
    }
    
    function getFlightPrice(flightElement) {
        const priceText = flightElement.querySelector('.flight-price').textContent;
        return parseFloat(priceText.replace(/[^0-9.]/g, ''));
    }
    
    function getFlightDuration(flightElement) {
        const durationText = flightElement.querySelector('.flight-duration').textContent;
        // Parse duration like "2h 30m" to minutes
        const match = durationText.match(/(\d+)h\s*(?:(\d+)m)?/);
        if (match) {
            const hours = parseInt(match[1]) || 0;
            const minutes = parseInt(match[2]) || 0;
            return hours * 60 + minutes;
        }
        return 0;
    }
    
    function getFlightStops(flightElement) {
        const stopsText = flightElement.querySelector('.flight-stops').textContent;
        if (stopsText.includes('Direct')) {
            return 0;
        }
        const match = stopsText.match(/(\d+)\s*stop/i);
        return match ? parseInt(match[1]) : 0;
    }
    
    function showFlexibleSearch() {
        const modal = new bootstrap.Modal(document.getElementById('flexibleSearchModal'));
        modal.show();
    }
    
    function openLiveChat() {
        // Implement your live chat functionality here
        alert('Live chat feature would open here');
    }
</script>

            <script>
                // Initialize everything when DOM is loaded
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize Lucide icons
                    lucide.createIcons();

                    // Initialize geolocation and currency
                    initializeGeolocation();

                    // Initialize filters
                    initializeFilters();

                    // Load hotels
                    loadHotels();

                    // Initialize sorting
                    initializeSorting();
                });

                // Global variables
                let allFlights = [];
                let filteredFlights = [];
                let userLocation = null;
                let userCurrency = 'USD';
                let exchangeRate = 1;

                // Geolocation and Currency Functions
                function initializeGeolocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                fetch(
                                        `https://api.opencagedata.com/geocode/v1/json?q=${position.coords.latitude}+${position.coords.longitude}&key=YOUR_API_KEY`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.results && data.results.length > 0) {
                                            const country = data.results[0].components.country;
                                            const countryCode = data.results[0].components.country_code.toUpperCase();

                                            document.getElementById('userLocation').textContent = country;

                                            // Set currency based on location
                                            if (countryCode === 'KE') {
                                                userCurrency = 'KSH';
                                                document.getElementById('currencyDisplay').textContent = 'KSH';
                                                // Get exchange rate
                                                fetchExchangeRate();
                                            }
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error getting location:', error);
                                        document.getElementById('userLocation').textContent = 'Unknown';
                                    });
                            },
                            function(error) {
                                console.error('Error getting geolocation:', error);
                                document.getElementById('userLocation').textContent = 'Unknown';
                            }
                        );
                    } else {
                        document.getElementById('userLocation').textContent = 'Not supported';
                    }
                }

                function fetchExchangeRate() {
                    // In a real application, you'd use a proper exchange rate API
                    // For this example, we'll use a mock rate
                    exchangeRate = 130; // 1 USD = 130 KSH (example rate)
                    updatePricesDisplay();
                }

                function updatePricesDisplay() {
                    const priceElements = document.querySelectorAll('.flight-price');
                    const currencySymbols = document.querySelectorAll('.currency-symbol');

                    priceElements.forEach(element => {
                        const usdPrice = parseFloat(element.textContent.replace(/,/g, ''));
                        if (userCurrency === 'KSH') {
                            const kshPrice = Math.round(usdPrice * exchangeRate);
                            element.textContent = kshPrice.toLocaleString();
                        }
                    });

                    currencySymbols.forEach(element => {
                        element.textContent = userCurrency === 'KSH' ? 'KSH ' : '$';
                    });
                }

                // Filter Functions
                function initializeFilters() {
                    // Get all flights data
                    allFlights = Array.from(document.querySelectorAll('.flight-card')).map(card => ({
                        element: card,
                        price: parseFloat(card.dataset.price),
                        stops: parseInt(card.dataset.stops),
                        airline: card.dataset.airline,
                        departureTime: parseInt(card.dataset.departureTime),
                        duration: card.dataset.duration
                    }));

                    filteredFlights = [...allFlights];

                    // Initialize dynamic filters
                    initializeStopsFilter();
                    initializeAirlineFilter();
                    initializePriceFilter();
                    initializeTimeFilters();
                    initializeDurationFilters();
                }

                function initializeStopsFilter() {
                    const stopsFilter = document.getElementById('stopsFilter');
                    const stopsData = {};

                    allFlights.forEach(flight => {
                        const stops = flight.stops;
                        const stopsLabel = stops === 0 ? 'Direct' : stops === 1 ? '1 Stop' : `${stops} Stops`;

                        if (!stopsData[stops]) {
                            stopsData[stops] = {
                                label: stopsLabel,
                                count: 0,
                                minPrice: Infinity
                            };
                        }

                        stopsData[stops].count++;
                        stopsData[stops].minPrice = Math.min(stopsData[stops].minPrice, flight.price);
                    });

                    stopsFilter.innerHTML = Object.entries(stopsData)
                        .sort(([a], [b]) => parseInt(a) - parseInt(b))
                        .map(([stops, data]) => `
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" class="stops-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-stops="${stops}">
                <span class="text-sm">${data.label}</span>
                <span class="text-xs text-gray-500 ml-auto">from $${data.minPrice}</span>
            </label>
        `).join('');

                    // Add event listeners
                    stopsFilter.querySelectorAll('.stops-filter').forEach(checkbox => {
                        checkbox.addEventListener('change', applyFilters);
                    });
                }

                function initializeAirlineFilter() {
                    const airlineFilter = document.getElementById('airlinesFilter');
                    const airlineData = {};

                    allFlights.forEach(flight => {
                        const airline = flight.airline;

                        if (!airlineData[airline]) {
                            airlineData[airline] = {
                                count: 0,
                                minPrice: Infinity
                            };
                        }

                        airlineData[airline].count++;
                        airlineData[airline].minPrice = Math.min(airlineData[airline].minPrice, flight.price);
                    });

                    airlineFilter.innerHTML = Object.entries(airlineData)
                        .sort(([a], [b]) => a.localeCompare(b))
                        .map(([airline, data]) => `
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" class="airline-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-airline="${airline}">
                <span class="text-sm">${airline}</span>
                <span class="text-xs text-gray-500 ml-auto">$${data.minPrice}</span>
            </label>
        `).join('');

                    // Add event listeners
                    airlineFilter.querySelectorAll('.airline-filter').forEach(checkbox => {
                        checkbox.addEventListener('change', applyFilters);
                    });
                }

                function initializePriceFilter() {
                    const priceRange = document.getElementById('priceRange');
                    const maxPrice = Math.max(...allFlights.map(f => f.price));

                    priceRange.max = maxPrice;
                    priceRange.value = maxPrice;

                    document.getElementById('maxPriceDisplay').textContent = `$${maxPrice}`;
                    document.getElementById('selectedPriceDisplay').textContent = `$${maxPrice}`;

                    priceRange.addEventListener('input', function() {
                        document.getElementById('selectedPriceDisplay').textContent = `$${this.value}`;
                        applyFilters();
                    });
                }

                function initializeTimeFilters() {
                    document.querySelectorAll('.time-filter').forEach(button => {
                        button.addEventListener('click', function() {
                            this.classList.toggle('filter-active');
                            applyFilters();
                        });
                    });
                }

                function initializeDurationFilters() {
                    document.querySelectorAll('.duration-filter').forEach(checkbox => {
                        checkbox.addEventListener('change', applyFilters);
                    });
                }

                function applyFilters() {
                    let filtered = [...allFlights];

                    // Price filter
                    const maxPrice = parseFloat(document.getElementById('priceRange').value);
                    filtered = filtered.filter(flight => flight.price <= maxPrice);

                    // Stops filter
                    const selectedStops = Array.from(document.querySelectorAll('.stops-filter:checked')).map(cb => parseInt(cb
                        .dataset.stops));
                    if (selectedStops.length > 0) {
                        filtered = filtered.filter(flight => selectedStops.includes(flight.stops));
                    }

                    // Airline filter
                    const selectedAirlines = Array.from(document.querySelectorAll('.airline-filter:checked')).map(cb => cb.dataset
                        .airline);
                    if (selectedAirlines.length > 0) {
                        filtered = filtered.filter(flight => selectedAirlines.includes(flight.airline));
                    }

                    // Time filter
                    const selectedTimes = Array.from(document.querySelectorAll('.time-filter.filter-active')).map(btn => btn.dataset
                        .time);
                    if (selectedTimes.length > 0) {
                        filtered = filtered.filter(flight => {
                            const hour = flight.departureTime;
                            return selectedTimes.some(time => {
                                switch (time) {
                                    case 'early':
                                        return hour >= 6 && hour < 12;
                                    case 'afternoon':
                                        return hour >= 12 && hour < 18;
                                    case 'evening':
                                        return hour >= 18 && hour < 24;
                                    case 'night':
                                        return hour >= 0 && hour < 6;
                                    default:
                                        return true;
                                }
                            });
                        });
                    }

                    // Duration filter
                    const selectedDurations = Array.from(document.querySelectorAll('.duration-filter:checked')).map(cb => cb.dataset
                        .duration);
                    if (selectedDurations.length > 0) {
                        filtered = filtered.filter(flight => {
                            const duration = flight.duration;
                            // Parse duration like "PT5H30M" to hours
                            const hours = parseInt(duration.match(/(\d+)H/)?.[1] || 0) + (parseInt(duration.match(/(\d+)M/)
                                ?.[1] || 0) / 60);

                            return selectedDurations.some(durationType => {
                                switch (durationType) {
                                    case 'short':
                                        return hours < 6;
                                    case 'medium':
                                        return hours >= 6 && hours < 12;
                                    case 'long':
                                        return hours >= 12;
                                    default:
                                        return true;
                                }
                            });
                        });
                    }

                    // Update display
                    filteredFlights = filtered;
                    updateFlightDisplay();
                    updateFilterCounts();
                }

                function updateFlightDisplay() {
                    // Hide all flights
                    allFlights.forEach(flight => {
                        flight.element.style.display = 'none';
                    });

                    // Show filtered flights
                    filteredFlights.forEach(flight => {
                        flight.element.style.display = 'block';
                    });

                    // Update count
                    document.getElementById('flightCount').textContent = filteredFlights.length;

                    // Show no results message if needed
                    const noResultsMessage = document.getElementById('noResultsMessage');
                    if (filteredFlights.length === 0) {
                        if (!noResultsMessage) {
                            const flightResults = document.getElementById('flightResults');
                            const noResults = document.createElement('div');
                            noResults.id = 'noResultsMessage';
                            noResults.className = 'bg-white rounded-lg shadow-sm border p-16 text-center';
                            noResults.innerHTML = `
                <i data-lucide="search-x" class="w-20 h-20 text-gray-400 mx-auto mb-6"></i>
                <h3 class="text-2xl font-semibold text-gray-700 mb-4">No flights match your filters</h3>
                <p class="text-gray-500 mb-8">Try adjusting your filters to see more options</p>
                <button onclick="resetFilters()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Reset Filters
                </button>
            `;
                            flightResults.appendChild(noResults);
                            lucide.createIcons();
                        }
                    } else {
                        if (noResultsMessage) {
                            noResultsMessage.remove();
                        }
                    }
                }

                function updateFilterCounts() {
                    const activeFilters =
                        document.querySelectorAll(
                            '.stops-filter:checked, .airline-filter:checked, .duration-filter:checked, .time-filter.filter-active')
                        .length +
                        (document.getElementById('priceRange').value < document.getElementById('priceRange').max ? 1 : 0);

                    document.getElementById('activeFiltersCount').textContent = activeFilters;
                }

                function resetFilters() {
                    // Reset all checkboxes
                    document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

                    // Reset price range
                    const priceRange = document.getElementById('priceRange');
                    priceRange.value = priceRange.max;
                    document.getElementById('selectedPriceDisplay').textContent = `$${priceRange.max}`;

                    // Reset time filters
                    document.querySelectorAll('.time-filter').forEach(btn => btn.classList.remove('filter-active'));

                    // Apply filters (which will show all flights)
                    applyFilters();
                }

                // Sorting Functions
                function initializeSorting() {
                    document.getElementById('sortSelect').addEventListener('change', function() {
                        sortFlights(this.value);
                    });
                }

                function sortFlights(sortBy) {
                    const container = document.getElementById('flightResults');
                    const flights = Array.from(container.querySelectorAll('.flight-card:not([style*="display: none"])'));

                    flights.sort((a, b) => {
                        const aPrice = parseFloat(a.dataset.price);
                        const bPrice = parseFloat(b.dataset.price);
                        const aStops = parseInt(a.dataset.stops);
                        const bStops = parseInt(b.dataset.stops);
                        const aDeparture = parseInt(a.dataset.departureTime);
                        const bDeparture = parseInt(b.dataset.departureTime);

                        switch (sortBy) {
                            case 'price-low':
                                return aPrice - bPrice;
                            case 'price-high':
                                return bPrice - aPrice;
                            case 'duration':
                                // Parse duration strings to compare
                                const aDuration = parseDuration(a.dataset.duration);
                                const bDuration = parseDuration(b.dataset.duration);
                                return aDuration - bDuration;
                            case 'departure':
                                return aDeparture - bDeparture;
                            case 'arrival':
                                return bDeparture - aDeparture;
                            case 'best':
                            default:
                                // Best value: combination of price and stops
                                const aScore = aPrice + (aStops * 100);
                                const bScore = bPrice + (bStops * 100);
                                return aScore - bScore;
                        }
                    });

                    // Remove all flights and re-add in sorted order
                    flights.forEach(flight => flight.remove());
                    flights.forEach(flight => container.appendChild(flight));
                }

                function parseDuration(duration) {
                    const hours = parseInt(duration.match(/(\d+)H/)?.[1] || 0);
                    const minutes = parseInt(duration.match(/(\d+)M/)?.[1] || 0);
                    return hours * 60 + minutes;
                }

                // Flight Detail Functions
                function toggleFlightDetails(index) {
                    const flightCard = document.querySelectorAll('.flight-card')[index];
                    const detailsDiv = flightCard.querySelector('.flight-details');
                    const toggleText = flightCard.querySelector('.toggle-text');

                    if (detailsDiv.classList.contains('hidden')) {
                        detailsDiv.classList.remove('hidden');
                        toggleText.textContent = 'Hide Details';
                    } else {
                        detailsDiv.classList.add('hidden');
                        toggleText.textContent = 'View Details';
                    }
                }

                function selectFlight(index) {
                    const flightCard = document.querySelectorAll('.flight-card')[index];
                    const flightData = {
                        // Extract flight data from the card
                        price: flightCard.dataset.price,
                        stops: flightCard.dataset.stops,
                        airline: flightCard.dataset.airline,
                        departureTime: flightCard.dataset.departureTime,
                        duration: flightCard.dataset.duration
                    };

                    // Show confirmation modal or redirect to booking
                    if (confirm('Proceed with this flight selection?')) {
                        // In a real application, you would submit the form or redirect
                        console.log('Selected flight:', flightData);
                        // You can add actual form submission here
                    }
                }

                // Hotel Functions
                function loadHotels() {
                    const destination = '{{ $searchParams['to_iata'] ?? 'NYC' }}';
                    const checkIn = '{{ $searchParams['date'] ?? date('Y-m-d') }}';
                    const checkOut = '{{ $searchParams['return_date'] ?? date('Y-m-d', strtotime('+3 days')) }}';

                    // In a real application, you would fetch from Amadeus Hotel API
                    // For now, we'll simulate with mock data
                    setTimeout(() => {
                        const hotelsList = document.getElementById('hotelsList');
                        hotelsList.innerHTML = `
            <div class="hotel-card border rounded-lg p-4 hover:shadow-md transition-shadow">
                <img src="https://via.placeholder.com/300x200" alt="Hotel" class="w-full h-32 object-cover rounded-lg mb-3">
                <h4 class="font-semibold text-base mb-1">Grand Central Hotel</h4>
                <div class="flex items-center gap-1 mb-2">
                    <div class="flex text-yellow-400">
                        ${Array(5).fill().map((_, i) => '<i data-lucide="star" class="w-3 h-3 fill-current"></i>').join('')}
                    </div>
                    <span class="text-xs text-gray-500">4.5 (1,234 reviews)</span>
                </div>
                <div class="flex items-center gap-1 mb-2">
                    <i data-lucide="map-pin" class="w-3 h-3 text-gray-500"></i>
                    <span class="text-xs text-gray-600">City Center • 0.5 km from airport</span>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-lg font-bold text-green-600">$120</span>
                        <span class="text-xs text-gray-500">/night</span>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                        Book Now
                    </button>
                </div>
            </div>
            <div class="hotel-card border rounded-lg p-4 hover:shadow-md transition-shadow">
                <img src="https://via.placeholder.com/300x200" alt="Hotel" class="w-full h-32 object
-cover rounded-lg mb-3">
                <h4 class="font-semibold text-base mb-1">Skyline View Hotel</h4>
                <div class="flex items-center gap-1 mb-2">
                    <div class="flex text-yellow-400">
                        ${Array(5).fill().map((_, i) => '<i data-lucide="star" class="w-3 h-3 fill-current"></i>').join('')}
                    </div>
                    <span class="text-xs text-gray-500">4.7 (987 reviews)</span>
                </div>
                <div class="flex items-center gap-1 mb-2">
                    <i data-lucide="map-pin" class="w-3 h-3 text-gray-500"></i>
                    <span class="text-xs text-gray-600">Downtown • 1 km from airport</span>
                </div>
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-lg font-bold text-green-600">$150</span>
                        <span class="text-xs text-gray-500">/night</span>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg
                        text-sm hover:bg-blue-700 transition-colors">
                        Book Now
                    </button>
                </div>  
            </div>
        `;
                        lucide.createIcons();
                    }, 1000);
                }

                function viewAllHotels() {
                    // Redirect to hotel search page with current flight search parameters
                    const params = new URLSearchParams({
                        from: '{{ $searchParams['from_iata'] ?? 'NYC' }}',
                        to: '{{ $searchParams['to_iata'] ?? 'LAX' }}',
                        date: '{{ $searchParams['date'] ?? date('Y-m-d') }}',
                        return_date: '{{ $searchParams['return_date'] ?? date('Y-m-d', strtotime('+3 days')) }}'
                    });
                    window.location.href = `/hotels?${params.toString()}`;
                }
            </script>
            <!-- AI JavaScript Functions -->
            <script>
                // Chat Widget Functions
                document.addEventListener('DOMContentLoaded', function() {
                    const chatToggle = document.getElementById('chatToggle');
                    const chatWindow = document.getElementById('chatWindow');
                    const closeChatWidget = document.getElementById('closeChatWidget');
                    const widgetChatForm = document.getElementById('widgetChatForm');
                    const widgetChatInput = document.getElementById('widgetChatInput');
                    const widgetChatMessages = document.getElementById('widgetChatMessages');

                    let isOpen = false;

                    chatToggle.addEventListener('click', function() {
                        toggleChat();
                    });

                    closeChatWidget.addEventListener('click', function() {
                        toggleChat();
                    });

                    function toggleChat() {
                        isOpen = !isOpen;
                        if (isOpen) {
                            chatWindow.classList.remove('scale-0');
                            chatWindow.classList.add('scale-100');
                            widgetChatInput.focus();
                        } else {
                            chatWindow.classList.add('scale-0');
                            chatWindow.classList.remove('scale-100');
                        }
                    }

                    // Handle chat form submission
                    widgetChatForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const message = widgetChatInput.value.trim();
                        if (message) {
                            addUserMessage(message);
                            widgetChatInput.value = '';
                            sendMessageToAI(message);
                        }
                    });

                    function addUserMessage(message) {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'chat-message mb-3';
                        messageDiv.innerHTML = `
            <div class="flex items-start gap-2 justify-end">
                <div class="bg-blue-600 text-white rounded-lg p-2 text-sm max-w-xs">
                    ${message}
                </div>
                <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user" class="w-3 h-3 text-white"></i>
                </div>
            </div>
        `;
                        widgetChatMessages.appendChild(messageDiv);
                        widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
                        lucide.createIcons();
                    }

                    function addAIMessage(message) {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'chat-message mb-3';
                        messageDiv.innerHTML = `
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="bot" class="w-3 h-3 text-blue-600"></i>
                </div>
                <div class="bg-gray-100 rounded-lg p-2 text-sm max-w-xs">
                    ${message}
                </div>
            </div>
        `;
                        widgetChatMessages.appendChild(messageDiv);
                        widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
                        lucide.createIcons();
                    }

                    function sendMessageToAI(message) {
                        // Show typing indicator
                        const typingDiv = document.createElement('div');
                        typingDiv.className = 'chat-message mb-3 typing-indicator';
                        typingDiv.innerHTML = `
            <div class="flex items-start gap-2">
                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="bot" class="w-3 h-3 text-blue-600"></i>
                </div>
                <div class="bg-gray-100 rounded-lg p-2 text-sm">
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        `;
                        widgetChatMessages.appendChild(typingDiv);
                        widgetChatMessages.scrollTop = widgetChatMessages.scrollHeight;
                        lucide.createIcons();

                        // Send to AI API
                        fetch('/api/ai/chat', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    message: message
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                // Remove typing indicator
                                typingDiv.remove();

                                if (data.success) {
                                    addAIMessage(data.response);
                                } else {
                                    addAIMessage('Sorry, I encountered an error. Please try again.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                typingDiv.remove();
                                addAIMessage('Sorry, I\'m having trouble connecting right now. Please try again.');
                            });
                    }
                });

                // Layover Assistant Functions
                function openLayoverAssistant() {
                    document.getElementById('layoverModal').classList.remove('hidden');
                    document.getElementById('layoverModal').classList.add('flex');
                }

                function closeLayoverModal() {
                    document.getElementById('layoverModal').classList.add('hidden');
                    document.getElementById('layoverModal').classList.remove('flex');
                }

                document.getElementById('layoverForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData);

                    // Show loading
                    const resultsDiv = document.getElementById('layoverResults');
                    resultsDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Generating your personalized layover guide...</p>
        </div>
    `;
                    resultsDiv.classList.remove('hidden');

                    fetch('/api/ai/layover-guide', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                resultsDiv.innerHTML = `
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
                    <h4 class="font-semibold text-purple-900 mb-4 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Layover Guide for ${data.guide.airport}
                    </h4>
                    <div class="space-y-4">
                        ${data.guide.suggestions.map(suggestion => `
                                        <div class="bg-white rounded-lg p-4 border border-purple-100">
                                            <h5 class="font-medium text-purple-800 mb-2">${suggestion.title}</h5>
                                            <p class="text-gray-700 text-sm mb-2">${suggestion.description}</p>
                                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                                    ${suggestion.time}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                    ${suggestion.location}
                                                </span>
                                            </div>
                                        </div>
                                    `).join('')}
                    </div>
                </div>
            `;
                                lucide.createIcons();
                            } else {
                                resultsDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <i data-lucide="alert-circle" class="w-8 h-8 text-red-500 mx-auto mb-2"></i>
                    <p class="text-red-700">Unable to generate layover guide. Please try again.</p>
                </div>
            `;
                                lucide.createIcons();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            resultsDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <i data-lucide="wifi-off" class="w-8 h-8 text-red-500 mx-auto mb-2"></i>
                <p class="text-red-700">Network error. Please check your connection and try again.</p>
            </div>
        `;
                            lucide.createIcons();
                        });
                });

                // Packing Assistant Functions
                function openPackingAssistant() {
                    document.getElementById('packingModal').classList.remove('hidden');
                    document.getElementById('packingModal').classList.add('flex');
                }

                function closePackingModal() {
                    document.getElementById('packingModal').classList.add('hidden');
                    document.getElementById('packingModal').classList.remove('flex');
                }

                document.getElementById('packingForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData);

                    // Show loading
                    const resultsDiv = document.getElementById('packingResults');
                    resultsDiv.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Creating your personalized packing list...</p>
        </div>
    `;
                    resultsDiv.classList.remove('hidden');

                    fetch('/api/ai/packing-list', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                resultsDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-green-900 flex items-center gap-2">
                            <i data-lucide="list-checks" class="w-5 h-5"></i>
                            Packing List for ${data.list.destination}
                        </h4>
                        <button onclick="downloadPackingList()" class="text-green-600 hover:text-green-800">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        ${data.list.categories.map(category => `
                                        <div class="bg-white rounded-lg p-4 border border-green-100">
                                            <h5 class="font-medium text-green-800 mb-3 flex items-center gap-2">
                                                <i data-lucide="${category.icon}" class="w-4 h-4"></i>
                                                ${category.name}
                                            </h5>
                                            <div class="space-y-2">
                                                ${category.items.map(item => `
                                        <label class="flex items-center gap-2 text-sm">
                                            <input type="checkbox" class="rounded border-green-300 text-green-600 focus:ring-green-500">
                                            <span class="text-gray-700">${item}</span>
                                        </label>
                                    `).join('')}
                                            </div>
                                        </div>
                                    `).join('')}
                    </div>
                </div>
            `;
                                lucide.createIcons();
                            } else {
                                resultsDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <i data-lucide="alert-circle" class="w-8 h-8 text-red-500 mx-auto mb-2"></i>
                    <p class="text-red-700">Unable to generate packing list. Please try again.</p>
                </div>
            `;
                                lucide.createIcons();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            resultsDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <i data-lucide="wifi-off" class="w-8 h-8 text-red-500 mx-auto mb-2"></i>
                <p class="text-red-700">Network error. Please check your connection and try again.</p>
            </div>
        `;
                            lucide.createIcons();
                        });
                });

                function downloadPackingList() {
                    // Implementation for downloading packing list as PDF
                    window.print();
                }

                // Quick reply functions
                function sendQuickReply(message) {
                    document.getElementById('widgetChatInput').value = message;
                    document.getElementById('widgetChatForm').dispatchEvent(new Event('submit'));
                }
            </script>

            <script>
                function loadPricePrediction() {
                    const searchParams = @json($searchParams ?? []);

                    if (!searchParams.from_iata || !searchParams.to_iata) {
                        document.getElementById('quickPricePrediction').innerHTML = `
            <div class="text-center text-gray-500">
                <p class="text-sm">Price prediction not available for this search</p>
            </div>
        `;
                        return;
                    }

                    // Show loading
                    document.getElementById('quickPricePrediction').innerHTML = `
        <div class="text-center py-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
            <p class="text-sm text-gray-600">Analyzing price trends...</p>
        </div>
    `;

                    // Get first flight price for prediction
                    const firstFlightCard = document.querySelector('.flight-card');
                    const currentPrice = firstFlightCard ? firstFlightCard.dataset.price : 500;

                    const data = {
                        from: searchParams.from_iata,
                        to: searchParams.to_iata,
                        date: searchParams.date || new Date().toISOString().split('T')[0],
                        trip_type: searchParams.trip_type || 'one_way',
                        current_price: parseFloat(currentPrice)
                    };

                    fetch('/api/ai/price-prediction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                const prediction = result.prediction;
                                document.getElementById('quickPricePrediction').innerHTML = `
                <div class="price-prediction-widget">
                    <!-- Current Status -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg text-center">
                        <div class="text-xs font-medium text-gray-500 mb-1">Current Price</div>
                        <div class="text-xl font-bold text-gray-800">$${prediction.current_price}</div>
                        <div class="urgency-badge-small urgency-${prediction.urgency_level}">
                            ${prediction.urgency_level.toUpperCase()}
                        </div>
                    </div>
                    
                    <!-- Quick Forecast -->
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-600">Next Week</span>
                            <div class="text-right">
                                <div class="font-medium">$${prediction.predicted_prices.next_week}</div>
                                <div class="text-xs forecast-change-small ${prediction.price_changes.next_week >= 0 ? 'text-red-600' : 'text-green-600'}">
                                    ${prediction.price_changes.next_week >= 0 ? '+' : ''}${prediction.price_changes.next_week}%
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span class="text-sm text-gray-600">2 Weeks</span>
                            <div class="text-right">
                                <div class="font-medium">$${prediction.predicted_prices.next_two_weeks}</div>
                                <div class="text-xs forecast-change-small ${prediction.price_changes.next_two_weeks >= 0 ? 'text-red-600' : 'text-green-600'}">
                                    ${prediction.price_changes.next_two_weeks >= 0 ? '+' : ''}${prediction.price_changes.next_two_weeks}%
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recommendation -->
                    <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="font-medium text-blue-800 text-sm mb-1">💡 Recommendation</div>
                        <div class="text-xs text-blue-700">${prediction.recommendation.action}</div>
                        <div class="text-xs text-blue-600 mt-1">${prediction.recommendation.reason}</div>
                    </div>
                    
                    <!-- Potential Savings -->
                    ${prediction.savings_potential > 0 ? `
                                    <div class="text-center mt-3 p-2 bg-green-50 rounded border border-green-200">
                                        <div class="text-xs text-green-600">Potential Savings</div>
                                        <div class="font-bold text-green-700">$${prediction.savings_potential}</div>
                                    </div>
                                ` : ''}
                </div>
            `;
                            } else {
                                document.getElementById('quickPricePrediction').innerHTML = `
                <div class="text-center text-gray-500">
                    <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2"></i>
                    <p class="text-sm">Unable to load price prediction</p>
                </div>
            `;
                            }
                            lucide.createIcons();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('quickPricePrediction').innerHTML = `
            <div class="text-center text-gray-500">
                <i data-lucide="wifi-off" class="w-8 h-8 mx-auto mb-2"></i>
                <p class="text-sm">Network error</p>
            </div>
        `;
                            lucide.createIcons();
                        });
                }
            </script>
        @endsection
