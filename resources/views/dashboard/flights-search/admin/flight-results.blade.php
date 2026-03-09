@extends('layouts.main')

@section('content')
<!-- Include required CSS and JS -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<style>
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
</style>

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
                        @if(($searchParams['children'] ?? 0) > 0)
                            , {{ $searchParams['children'] }} Child{{ $searchParams['children'] > 1 ? 'ren' : '' }}
                        @endif
                        @if(($searchParams['infants'] ?? 0) > 0)
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
                        @if(isset($searchParams['return_date']))
                            - {{ $searchParams['return_date'] }}
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="flex gap-4">
                <button onclick="modifySearch()" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5"></i>
                    Modify Search
                </button>
                <button onclick="setPriceAlert()" class="bg-blue-700 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-800 transition-colors flex items-center gap-2">
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
                <button onclick="resetFilters()" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
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
                        <input type="range" id="priceRange" min="0" max="2000" step="50" value="2000" class="price-slider w-full">
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
                    <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors" data-time="early">
                        <i data-lucide="sunrise" class="w-4 h-4 mx-auto mb-1"></i>
                        <div class="font-medium">Early Morning</div>
                        <div class="text-gray-500">06:00 - 12:00</div>
                    </button>
                    <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors" data-time="afternoon">
                        <i data-lucide="sun" class="w-4 h-4 mx-auto mb-1"></i>
                        <div class="font-medium">Afternoon</div>
                        <div class="text-gray-500">12:00 - 18:00</div>
                    </button>
                    <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors" data-time="evening">
                        <i data-lucide="sunset" class="w-4 h-4 mx-auto mb-1"></i>
                        <div class="font-medium">Evening</div>
                        <div class="text-gray-500">18:00 - 00:00</div>
                    </button>
                    <button class="time-filter p-3 border rounded-lg text-xs hover:bg-gray-50 transition-colors" data-time="night">
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
                        <input type="checkbox" class="duration-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-duration="short">
                        <span class="text-sm">Short (0-6 hours)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="duration-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-duration="medium">
                        <span class="text-sm">Medium (6-12 hours)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="duration-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-duration="long">
                        <span class="text-sm">Long (12+ hours)</span>
                    </label>
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
                        <select id="sortSelect" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                @if(isset($flights) && count($flights) > 0)
                    @foreach($flights as $index => $flight)
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
                             data-price="{{ $flight->price->total }}" 
                             data-stops="{{ $numberOfStops }}" 
                             data-airline="{{ $carrierCode }}" 
                             data-departure-time="{{ date('H', strtotime($firstSegment->departure->at)) }}"
                             data-duration="{{ $flight->itineraries[0]->duration }}">
                            
                            <!-- Main Flight Card -->
                            <div class="flight-main-info">
                                <div class="flex items-center justify-between">
                                    <!-- Flight Info -->
                                    <div class="flex items-center gap-8 flex-1">
                                        <!-- Airline Info -->
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-lg">
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
                                                <div class="text-2xl font-bold text-gray-800">{{ $departureTime }}</div>
                                                <div class="text-sm font-medium text-gray-600">{{ $firstSegment->departure->iataCode }}</div>
                                                <div class="text-xs text-gray-500">{{ $departureDate }}</div>
                                            </div>

                                            <!-- Flight Path -->
                                            <div class="flex-1 flex flex-col items-center">
                                                <div class="text-xs text-gray-500 mb-1">{{ $flight->itineraries[0]->duration }}</div>
                                                <div class="flex items-center w-full">
                                                    <div class="flex-1 h-px bg-gray-300 relative">
                                                        <i data-lucide="plane" class="w-4 h-4 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-blue-500 bg-white"></i>
                                                    </div>
                                                </div>
                                                <div class="text-xs mt-1 flex items-center gap-1">
                                                    @if($numberOfStops == 0)
                                                        <i data-lucide="zap" class="w-3 h-3 text-green-500"></i>
                                                        <span class="text-green-600 font-medium">Direct</span>
                                                    @else
                                                        <i data-lucide="circle" class="w-3 h-3 text-orange-500"></i>
                                                        <span class="text-orange-600">{{ $numberOfStops }} stop{{ $numberOfStops > 1 ? 's' : '' }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Arrival -->
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-gray-800">{{ $arrivalTime }}</div>
                                                <div class="text-sm font-medium text-gray-600">{{ $lastSegment->arrival->iataCode }}</div>
                                                <div class="text-xs text-gray-500">{{ $arrivalDate }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price and Action -->
                                    <div class="text-right ml-8">
                                        <div class="text-3xl font-bold text-green-600 mb-1">
                                            <span class="currency-symbol">$</span><span class="flight-price">{{ number_format($flight->price->total, 0) }}</span>
                                        </div>
                                        <div class="text-sm text-gray-500 mb-4">per person</div>
                                        
                                        <!-- Action Buttons -->
                                        <div class="space-y-2">
                                            <button onclick="selectFlight({{ $index }})" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                                Select Flight
                                            </button>
                                            <button onclick="toggleFlightDetails({{ $index }})" class="w-full text-blue-600 text-sm hover:underline flex items-center justify-center gap-1">
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
                                                <span>{{ $searchParams['adults'] ?? 1 }} Adult{{ ($searchParams['adults'] ?? 1) > 1 ? 's' : '' }}</span>
                                            </div>
                                            @if(($searchParams['children'] ?? 0) > 0)
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="baby" class="w-4 h-4 text-gray-500"></i>
                                                    <span>{{ $searchParams['children'] }} Child{{ $searchParams['children'] > 1 ? 'ren' : '' }}</span>
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
                                @if(count($flight->itineraries[0]->segments) > 1)
                                    <div class="mt-6 pt-6 border-t">
                                        <h4 class="font-semibold text-lg mb-4 flex items-center gap-2">
                                            <i data-lucide="route" class="w-5 h-5"></i>
                                            Flight Segments
                                        </h4>
                                        <div class="space-y-4">
                                            @foreach($flight->itineraries[0]->segments as $segmentIndex => $segment)
                                                <div class="bg-gray-50 rounded-lg p-4">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-4">
                                                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                                                                Segment {{ $segmentIndex + 1 }}
                                                            </span>
                                                            <span class="text-lg font-semibold">
                                                                {{ $segment->departure->iataCode }} → {{ $segment->arrival->iataCode }}
                                                            </span>
                                                        </div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ date('H:i', strtotime($segment->departure->at)) }} - {{ date('H:i', strtotime($segment->arrival->at)) }}
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
                        <p class="text-gray-500 mb-8 max-w-md mx-auto">We couldn't find any flights matching your criteria. Try adjusting your search parameters or dates.</p>
                        <div class="flex gap-4 justify-center">
                            <button onclick="modifySearch()" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                Modify Search
                            </button>
                            <a href="{{ route('home') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                                New Search
                            </a>
                        </div>
                    </div>
                @endif
            </div>
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
                <button onclick="viewAllHotels()" class="w-full mt-4 text-blue-600 text-sm hover:underline flex items-center justify-center gap-1">
                    <i data-lucide="external-link" class="w-4 h-4"></i>
                    View all hotels
                </button>
            </div>

            <!-- Car Rentals -->
            <div class="bg-white rounded-lg shadow-sm p-6 border">
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
            </div>

            <!-- Popular Destinations -->
            <div class="bg-white rounded-lg shadow-sm p-6 border">
                <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-5 h-5"></i>
                    Popular Destinations
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                        <div>
                            <div class="font-medium">Paris, France</div>
                            <div class="text-sm text-gray-600">CDG</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-green-600">from $299</div>
                            <div class="text-xs text-gray-500">round trip</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
                        <div>
                            <div class="font-medium">Tokyo, Japan</div>
                            <div class="text-sm text-gray-600">NRT</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-green-600">from $599</div>
                            <div class="text-xs text-gray-500">round trip</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 cursor-pointer">
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
                <p class="text-gray-400 mb-4">Find the best flights at the best prices. Compare airlines and book with confidence.</p>
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
                fetch(`https://api.opencagedata.com/geocode/v1/json?q=${position.coords.latitude}+${position.coords.longitude}&key=YOUR_API_KEY`)
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
    const selectedStops = Array.from(document.querySelectorAll('.stops-filter:checked')).map(cb => parseInt(cb.dataset.stops));
    if (selectedStops.length > 0) {
        filtered = filtered.filter(flight => selectedStops.includes(flight.stops));
    }
    
    // Airline filter
    const selectedAirlines = Array.from(document.querySelectorAll('.airline-filter:checked')).map(cb => cb.dataset.airline);
    if (selectedAirlines.length > 0) {
        filtered = filtered.filter(flight => selectedAirlines.includes(flight.airline));
    }
    
    // Time filter
    const selectedTimes = Array.from(document.querySelectorAll('.time-filter.filter-active')).map(btn => btn.dataset.time);
    if (selectedTimes.length > 0) {
        filtered = filtered.filter(flight => {
            const hour = flight.departureTime;
            return selectedTimes.some(time => {
                switch(time) {
                    case 'early': return hour >= 6 && hour < 12;
                    case 'afternoon': return hour >= 12 && hour < 18;
                    case 'evening': return hour >= 18 && hour < 24;
                    case 'night': return hour >= 0 && hour < 6;
                    default: return true;
                }
            });
        });
    }
    
    // Duration filter
    const selectedDurations = Array.from(document.querySelectorAll('.duration-filter:checked')).map(cb => cb.dataset.duration);
    if (selectedDurations.length > 0) {
        filtered = filtered.filter(flight => {
            const duration = flight.duration;
            // Parse duration like "PT5H30M" to hours
            const hours = parseInt(duration.match(/(\d+)H/)?.[1] || 0) + (parseInt(duration.match(/(\d+)M/)?.[1] || 0) / 60);
            
            return selectedDurations.some(durationType => {
                switch(durationType) {
                    case 'short': return hours < 6;
                    case 'medium': return hours >= 6 && hours < 12;
                    case 'long': return hours >= 12;
                    default: return true;
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
        document.querySelectorAll('.stops-filter:checked, .airline-filter:checked, .duration-filter:checked, .time-filter.filter-active').length +
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
        
        switch(sortBy) {
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
    const destination = '{{ $searchParams["to_iata"] ?? "NYC" }}';
    const checkIn = '{{ $searchParams["date"] ?? date("Y-m-d") }}';
    const checkOut = '{{ $searchParams["return_date"] ?? date("Y-m-d", strtotime("+3 days")) }}';
    
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
        from: '{{ $searchParams["from_iata"] ?? "NYC" }}',
        to: '{{ $searchParams["to_iata"] ?? "LAX" }}',
        date: '{{ $searchParams["date"] ?? date("Y-m-d") }}',
        return_date: '{{ $searchParams["return_date"] ?? date("Y-m-d", strtotime("+3 days")) }}'
    });
    window.location.href = `/hotels?${params.toString()}`;
}
</script>
@endsection

