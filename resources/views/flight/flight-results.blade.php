@extends('layouts.main')

@section('content')
    <!-- Main Results Section -->
    <div class="container py-5">
        <h1 class="mb-4">{{ $pageTitle ?? 'Flight Search Results' }}</h1>
        
        @if(isset($flights) && count($flights) > 0)
            <div class="row">
                <!-- Filters Column -->
                <div class="col-lg-3 mb-4">
                    @include('flight.partials.filters')
                </div>
                
                <!-- Results Column -->
                <div class="col-lg-9">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>{{ count($flights) }} Flights Found</h3>
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown">
                                    Sort By
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="#" data-sort="price-asc">Price (Low to High)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="price-desc">Price (High to Low)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="duration-asc">Duration (Shortest)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="stops-asc">Stops (Least first)</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Flight Results List -->
                    <div class="flight-results">
                        @foreach($flights as $flight)
                            @include('flight.partials.flight-card', ['flight' => $flight, 'isOfferMode' => $isOfferMode ?? false])
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <h4 class="alert-heading">No flights found!</h4>
                <p>We couldn't find any flights matching your search criteria. Please try different dates or destinations.</p>
            </div>
            
            <!-- Show loading animation during search -->
            <div id="search-loading" class="text-center my-5" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Searching for flights...</p>
            </div>
            
            <!-- Include Flight Deals section when no results are found -->
            @include('partials.flight-deals')
        @endif
    </div>
    
    <!-- Include Flight Deals at the bottom of the page if results were found -->
    @if(isset($flights) && count($flights) > 0 && (!isset($isOfferMode) || !$isOfferMode))
        <div class="py-4 bg-light">
            <div class="container">
                <h2 class="text-center mb-4">You might also be interested in</h2>
                @include('partials.flight-deals')
            </div>
        </div>
    @endif
@endsection

@section('scripts')
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
</script>
@endsection
