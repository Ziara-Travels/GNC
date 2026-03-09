<div class="details-grid p-4 rounded" style="background-color: #f8f9fa;">
    <div class="row g-4">
        <!-- Flight Information -->
        <div class="col-md-6">
            <div class="detail-item">
                <h5 class="text-muted mb-3">
                    <i class="fas fa-info-circle me-2" style="color: #e3a300;"></i>
                    Flight Information
                </h5>
                <div class="ps-4">
                    <p class="mb-2">
                        <strong>Airline:</strong>
                        <span class="ms-2">{{ $data->flightOffers[0]->validatingAirlineCodes[0] }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Flight Number:</strong>
                        <span class="ms-2">{{ $data->flightOffers[0]->itineraries[0]->segments[0]->number }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Aircraft:</strong>
                        <span class="ms-2">{{ $data->flightOffers[0]->itineraries[0]->segments[0]->aircraft->code }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Class:</strong>
                        <span class="ms-2">{{ $data->flightOffers[0]->travelerPricings[0]->fareDetailsBySegment[0]->cabin }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Travel Details -->
        <div class="col-md-6">
            <div class="detail-item">
                <h5 class="text-muted mb-3">
                    <i class="fas fa-calendar-alt me-2" style="color: #e3a300;"></i>
                    Travel Details
                </h5>
                <div class="ps-4">
                    <p class="mb-2">
                        <strong>Date:</strong>
                        <span class="ms-2">{{ Carbon\Carbon::parse($data->flightOffers[0]->itineraries[0]->segments[0]->departure->at)->format('D, M d, Y') }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Duration:</strong>
                        <span class="ms-2">{{ $duration ?? 'Duration not available' }}</span>
                    </p>
                    <p class="mb-0">
                        <strong>Stops:</strong>
                        <span class="ms-2">{{ count($data->flightOffers[0]->itineraries[0]->segments) - 1 }} stops</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>