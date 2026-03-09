<div class="route-info p-4 mb-4 rounded" style="background-color: #f8f9fa;">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Departure -->
        <div class="text-center">
            <div class="fs-2 fw-bold text-primary">
                {{ $flightData->flightOffers[0]->itineraries[0]->segments[0]->departure->iataCode }}
            </div>
            <div class="fs-5">
                {{ Carbon\Carbon::parse($flightData->flightOffers[0]->itineraries[0]->segments[0]->departure->at)->format('h:i A') }}
            </div>
        </div>

        <!-- Flight Path -->
        <div class="flight-path flex-grow-1 px-4">
            <div class="d-flex align-items-center justify-content-center">
                <div class="flight-line"></div>
                <i class="fas fa-plane" style="color: #e3a300; transform: rotate(90deg);"></i>
            </div>
            <div class="text-center mt-2">
                <span class="badge" style="background-color: #e3a300; color: white; font-size: 0.9rem; padding: 0.5rem 1rem;">
                    <i class="fas fa-clock me-1"></i>
                    {{ $duration ?? 'Duration not available' }}
                </span>
            </div>
        </div>

        <!-- Arrival -->
        <div class="text-center">
            <div class="fs-2 fw-bold text-primary">
                {{ $flightData->flightOffers[0]->itineraries[0]->segments[0]->arrival->iataCode }}
            </div>
            <div class="fs-5">
                {{ Carbon\Carbon::parse($flightData->flightOffers[0]->itineraries[0]->segments[0]->arrival->at)->format('h:i A') }}
            </div>
        </div>
    </div>
</div>