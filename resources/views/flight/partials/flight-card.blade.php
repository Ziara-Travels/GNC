<div class="card mb-4 flight-card shadow-sm">
    <div class="card-body">
        <div class="row">
            <!-- Airline Info -->
            <div class="col-md-2 text-center border-end">
                @php
                    $carrierCode = isset($flight['itineraries'][0]['segments'][0]['carrierCode']) 
                        ? $flight['itineraries'][0]['segments'][0]['carrierCode'] 
                        : ($flight['validatingAirlineCodes'][0] ?? 'UNKNOWN');
                    
                    $airlineName = isset($flight['dictionaries']['carriers'][$carrierCode]) 
                        ? $flight['dictionaries']['carriers'][$carrierCode] 
                        : $carrierCode;
                @endphp
                
                <div class="airline-logo mb-2 d-flex align-items-center justify-content-center mx-auto" 
                    style="width: 50px; height: 50px; background: linear-gradient(45deg, #667eea, #764ba2); border-radius: 50%; color: white; font-weight: bold;">
                    {{ $carrierCode }}
                </div>
                <div class="airline-name">{{ $airlineName }}</div>
                
                @if(isset($flight['originalOffer']) && isset($flight['originalOffer']['discountPercent']))
                    <div class="mt-2">
                        <span class="badge bg-danger">{{ $flight['originalOffer']['discountPercent'] }}% OFF</span>
                    </div>
                @endif
            </div>
            
            <!-- Flight Details -->
            <div class="col-md-6 py-2">
                @php
                    $firstSegment = $flight['itineraries'][0]['segments'][0];
                    $lastSegment = end($flight['itineraries'][0]['segments']);
                    
                    $departureTime = isset($firstSegment['departure']['at']) 
                        ? \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('H:i')
                        : 'N/A';
                    
                    $arrivalTime = isset($lastSegment['arrival']['at'])
                        ? \Carbon\Carbon::parse($lastSegment['arrival']['at'])->format('H:i')
                        : 'N/A';
                    
                    $departureDate = isset($firstSegment['departure']['at'])
                        ? \Carbon\Carbon::parse($firstSegment['departure']['at'])->format('M d, Y')
                        : 'N/A';
                    
                    $departureAirport = $firstSegment['departure']['iataCode'] ?? 'N/A';
                    $arrivalAirport = $lastSegment['arrival']['iataCode'] ?? 'N/A';
                    
                    $duration = $flight['itineraries'][0]['duration'] ?? 'N/A';
                    if (preg_match('/PT(\d+)H(\d+)M/', $duration, $matches)) {
                        $duration = $matches[1] . 'h ' . $matches[2] . 'm';
                    }
                    
                    $stops = count($flight['itineraries'][0]['segments']) - 1;
                @endphp
                
                <div class="flight-route mb-3">
                    <div class="d-flex justify-content-between">
                        <div class="departure">
                            <div class="time fw-bold fs-4">{{ $departureTime }}</div>
                            <div class="airport">{{ $departureAirport }}</div>
                        </div>
                        
                        <div class="flight-path flex-grow-1 px-3 text-center d-flex flex-column justify-content-center">
                            <div class="flight-duration text-muted">{{ $duration }}</div>
                            <div class="d-flex align-items-center">
                                <div class="flight-line flex-grow-1" style="height: 2px; background-color: #ddd;"></div>
                                <i class="fas fa-plane mx-2 text-primary"></i>
                                <div class="flight-line flex-grow-1" style="height: 2px; background-color: #ddd;"></div>
                            </div>
                            <div class="flight-stops text-muted">
                                @if($stops == 0)
                                    Direct Flight
                                @else
                                    {{ $stops }} stop{{ $stops > 1 ? 's' : '' }}
                                @endif
                            </div>
                        </div>
                        
                        <div class="arrival text-end">
                            <div class="time fw-bold fs-4">{{ $arrivalTime }}</div>
                            <div class="airport">{{ $arrivalAirport }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="flight-info">
                    <div class="d-flex text-muted small">
                        <div class="me-3">
                            <i class="fas fa-calendar-alt me-1"></i> {{ $departureDate }}
                        </div>
                        @if(isset($flight['originalOffer']) && isset($flight['originalOffer']['highlight']))
                            <div class="me-3">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $flight['originalOffer']['highlight'] }}
                            </div>
                        @endif
                        <div class="ms-auto">
                            @php
                                $cabin = isset($flight['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin']) 
                                    ? $flight['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] 
                                    : 'ECONOMY';
                                $cabin = str_replace('_', ' ', $cabin);
                            @endphp
                            <span class="badge bg-secondary">{{ ucwords(strtolower($cabin)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Price and Booking -->
            <div class="col-md-4 d-flex flex-column justify-content-center align-items-center border-start">
                <div class="price-container text-center mb-3">
                    @if(isset($flight['originalOffer']) && isset($flight['originalOffer']['originalPrice']))
                        <div class="original-price text-decoration-line-through text-muted">
                            {{ $flight['originalOffer']['currency'] }} {{ number_format($flight['originalOffer']['originalPrice']) }}
                        </div>
                    @endif
                    <div class="current-price fs-3 fw-bold text-primary flight-price">
                        {{ $flight['price']['currency'] }} {{ number_format((float)$flight['price']['total']) }}
                    </div>
                    <div class="price-note text-muted small">
                        Per passenger, including taxes
                    </div>
                </div>
                
                <div class="booking-actions">
                    <form action="{{ $isOfferMode ? '/select-flight' : '/api/price' }}" method="POST">
                        @csrf
                        <input type="hidden" name="flight" value="{{ json_encode($flight) }}">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-ticket-alt me-2"></i> Select Flight
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
