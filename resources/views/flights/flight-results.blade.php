
@extends('layouts.main')

@php
// Add this helper function at the top of your flight-results.blade.php file
function formatDuration($duration) {
    // Parse PT5H30M format to readable hours and minutes
    $pattern = '/PT(?:(\d+)H)?(?:(\d+)M)?/';
    if (preg_match($pattern, $duration, $matches)) {
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } elseif ($minutes > 0) {
            return "{$minutes}m";
        }
    }
    
    return $duration; 
}

// Check if this is a direct booking from popular destinations
$isDirectBooking = isset($isDirectBooking) && $isDirectBooking === true;
$destination = $destination ?? null;
@endphp

@section('content')
    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
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
            0%, 60%, 100% {
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
                                @include('flights.partials.flight-card', [
                                    'flight' => $flight, 
                                    'isOfferMode' => $isOfferMode ?? false,
                                    'isDirectBooking' => $isDirectBooking,
                                    'destination' => $destination
                                ])
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
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
@endsection

@section('scripts')
<script>
    // Add loading state to flight search forms
    document.addEventListener('DOMContentLoaded', function() {
        // Handle loading state for search forms
        const searchForms = document.querySelectorAll('form[action*="search"]');
        searchForms.forEach(form => {
            form.addEventListener('submit', function() {
                const loadingElement = document.getElementById('search-loading');
                if (loadingElement) {
                    loadingElement.style.display = 'block';
                }
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
        if (!flightResults) return;
        
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
        const priceElement = flightElement.querySelector('.flight-price');
        if (!priceElement) return 0;
        const priceText = priceElement.textContent;
        return parseFloat(priceText.replace(/[^0-9.]/g, ''));
    }
    
    function getFlightDuration(flightElement) {
        const durationElement = flightElement.querySelector('.flight-duration');
        if (!durationElement) return 0;
        const durationText = durationElement.textContent;
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
        const stopsElement = flightElement.querySelector('.flight-stops');
        if (!stopsElement) return 0;
        const stopsText = stopsElement.textContent;
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

    function modifySearch() {
        // Redirect to home page for new search
        window.location.href = '{{ url('/') }}';
    }

    function setPriceAlert() {
        // Implement price alert functionality
        alert('Price alert feature would be implemented here');
    }
</script>
@endsection