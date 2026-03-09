<!-- Flight Filters -->
<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Filter Results</h5>
    </div>
    <div class="card-body">
        <form id="filter-form">
            <!-- Airlines Filter -->
            <div class="mb-4">
                <h6 class="fw-bold">Airlines</h6>
                <div class="airlines-filter">
                    @php
                    $airlines = collect($flights)->pluck('airline')->unique();
                    @endphp
                    
                    @foreach($airlines as $airline)
                    <div class="form-check">
                        <input class="form-check-input airline-filter" type="checkbox" value="{{ $airline }}" id="airline-{{ Str::slug($airline) }}">
                        <label class="form-check-label" for="airline-{{ Str::slug($airline) }}">
                            {{ $airline }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Price Range Filter -->
            <div class="mb-4">
                <h6 class="fw-bold">Price Range</h6>
                @php
                $minPrice = collect($flights)->min(function($flight) {
                    return $flight->price ?? $flight->offerPrice ?? 0;
                });
                $maxPrice = collect($flights)->max(function($flight) {
                    return $flight->price ?? $flight->offerPrice ?? 0;
                });
                @endphp
                
                <div class="price-slider mb-2">
                    <input type="range" class="form-range" id="price-range" 
                           min="{{ floor($minPrice) }}" 
                           max="{{ ceil($maxPrice) }}" 
                           step="10">
                </div>
                <div class="d-flex justify-content-between">
                    <span id="min-price">${{ number_format($minPrice, 0) }}</span>
                    <span id="max-price">${{ number_format($maxPrice, 0) }}</span>
                </div>
                <div class="text-center mt-2">
                    <span id="selected-price">Up to $<span id="price-value">{{ number_format($maxPrice, 0) }}</span></span>
                </div>
            </div>
            
            <!-- Stops Filter -->
            <div class="mb-4">
                <h6 class="fw-bold">Stops</h6>
                <div class="stops-filter">
                    <div class="form-check">
                        <input class="form-check-input stops-filter" type="checkbox" value="0" id="direct-flights" checked>
                        <label class="form-check-label" for="direct-flights">
                            Direct flights
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input stops-filter" type="checkbox" value="1" id="one-stop" checked>
                        <label class="form-check-label" for="one-stop">
                            1 Stop
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input stops-filter" type="checkbox" value="2+" id="multi-stop" checked>
                        <label class="form-check-label" for="multi-stop">
                            2+ Stops
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Departure Time Filter -->
            <div class="mb-4">
                <h6 class="fw-bold">Departure Time</h6>
                <div class="time-filter">
                    <div class="form-check">
                        <input class="form-check-input time-filter" type="checkbox" value="morning" id="morning" checked>
                        <label class="form-check-label" for="morning">
                            Morning (6AM - 12PM)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input time-filter" type="checkbox" value="afternoon" id="afternoon" checked>
                        <label class="form-check-label" for="afternoon">
                            Afternoon (12PM - 6PM)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input time-filter" type="checkbox" value="evening" id="evening" checked>
                        <label class="form-check-label" for="evening">
                            Evening (6PM - 12AM)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input time-filter" type="checkbox" value="night" id="night" checked>
                        <label class="form-check-label" for="night">
                            Night (12AM - 6AM)
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Reset Filters -->
            <button type="button" id="reset-filters" class="btn btn-outline-secondary w-100">Reset Filters</button>
        </form>
    </div>
</div>

<!-- Filter Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Price range slider
        const priceRange = document.getElementById('price-range');
        const priceValue = document.getElementById('price-value');
        
        if (priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                priceValue.textContent = Number(this.value).toLocaleString();
                applyFilters();
            });
        }
        
        // Apply filters when checkboxes change
        const filterCheckboxes = document.querySelectorAll('.form-check-input');
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', applyFilters);
        });
        
        // Reset filters button
        const resetButton = document.getElementById('reset-filters');
        if (resetButton) {
            resetButton.addEventListener('click', resetFilters);
        }
        
        // Apply filters function
        function applyFilters() {
            const selectedAirlines = Array.from(document.querySelectorAll('.airline-filter:checked'))
                .map(input => input.value);
                
            const selectedStops = Array.from(document.querySelectorAll('.stops-filter:checked'))
                .map(input => input.value);
                
            const selectedTimes = Array.from(document.querySelectorAll('.time-filter:checked'))
                .map(input => input.value);
                
            const maxPrice = parseInt(priceRange.value);
            
            // Get all flight cards
            const flights = document.querySelectorAll('.flight-card');
            
            // Loop through each flight and check if it matches filters
            flights.forEach(flight => {
                const airline = flight.getAttribute('data-airline');
                const price = parseFloat(flight.getAttribute('data-price'));
                const stops = flight.getAttribute('data-stops');
                const departureTime = flight.getAttribute('data-departure-time');
                
                // Determine time of day
                let timeOfDay = '';
                if (departureTime) {
                    const hour = parseInt(departureTime.split(':')[0]);
                    if (hour >= 6 && hour < 12) timeOfDay = 'morning';
                    else if (hour >= 12 && hour < 18) timeOfDay = 'afternoon';
                    else if (hour >= 18 && hour < 24) timeOfDay = 'evening';
                    else timeOfDay = 'night';
                }
                
                // Check if flight matches all filters
                const matchesAirline = selectedAirlines.length === 0 || selectedAirlines.includes(airline);
                const matchesPrice = price <= maxPrice;
                const matchesStops = selectedStops.length === 0 || selectedStops.includes(stops);
                const matchesTime = selectedTimes.length === 0 || selectedTimes.includes(timeOfDay);
                
                // Show/hide flight based on filter matches
                if (matchesAirline && matchesPrice && matchesStops && matchesTime) {
                    flight.style.display = '';
                } else {
                    flight.style.display = 'none';
                }
            });
            
            // Update count of visible flights
            updateVisibleCount();
        }
        
        function resetFilters() {
            // Reset all checkboxes to checked
            document.querySelectorAll('.form-check-input').forEach(checkbox => {
                checkbox.checked = true;
            });
            
            // Reset price slider to max
            priceRange.value = priceRange.max;
            priceValue.textContent = Number(priceRange.max).toLocaleString();
            
            // Show all flights
            document.querySelectorAll('.flight-card').forEach(flight => {
                flight.style.display = '';
            });
            
            // Update count
            updateVisibleCount();
        }
        
        function updateVisibleCount() {
            const visibleFlights = document.querySelectorAll('.flight-card:not([style*="display: none"])').length;
            const resultsHeading = document.querySelector('.flight-results').previousElementSibling.querySelector('h3');
            if (resultsHeading) {
                resultsHeading.textContent = `${visibleFlights} Flights Found`;
            }
        }
    });
</script>
