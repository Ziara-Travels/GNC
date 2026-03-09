{{-- flight-card.blade.php - Flight card partial for displaying flight offers --}}

@php
    $isHighlighted = $isDirectBooking && isset($destination) && isset($flight['id']) && strpos($flight['id'], 'offer-') === 0;
    $cardClass = $isHighlighted ? 'border-4 border-yellow-400' : 'border border-gray-200';
    $itinerary = $flight['itineraries'][0];
    $segments = $itinerary['segments'];
    $originCity = $segments[0]['departure']['iataCode'];
    $destinationCity = $segments[count($segments) - 1]['arrival']['iataCode'];
    $departureTime = \Carbon\Carbon::parse($segments[0]['departure']['at'])->format('h:i A');
    $arrivalTime = \Carbon\Carbon::parse($segments[count($segments) - 1]['arrival']['at'])->format('h:i A');
    $price = $flight['price']['grandTotal'] ?? $flight['price']['total'] ?? 0;
    $currency = $flight['price']['currency'] ?? 'USD';
    
    // Format the currency symbol
    $currencySymbol = $currency === 'USD' ? '$' : ($currency === 'KES' ? 'KSh' : $currency);
    
    // Calculate the total duration
    $totalDuration = isset($segments[0]['duration']) ? formatDuration($segments[0]['duration']) : '?h ?m';
    
    // Get cabin class
    $cabinClass = $flight['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] ?? 'ECONOMY';
    $cabinClassFormatted = ucfirst(strtolower($cabinClass));
    
    // Get airline info
    $airlineCode = $segments[0]['carrierCode'] ?? '';
    $flightNumber = $segments[0]['number'] ?? '';
    
    // Check if this is a multi-segment flight
    $isMultiSegment = count($segments) > 1;
    $stopCount = count($segments) - 1;
@endphp

<div class="flight-card bg-white rounded-xl shadow-md overflow-hidden mb-6 {{ $cardClass }}">
    @if($isHighlighted)
        <div class="bg-yellow-400 py-2 px-4 text-black font-bold text-center">
            Featured Destination
        </div>
    @endif
    <div class="p-6">
        <div class="grid grid-cols-12 gap-4 items-center">
            <!-- Airline Info -->
            <div class="col-span-12 md:col-span-2 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $airlineCode }}" 
                         alt="{{ $airlineCode }}" class="h-8"
                         onerror="this.onerror=null; this.src='/images/flight-icon.svg';">
                </div>
                <div class="text-center">
                    <p class="text-gray-900 font-semibold">{{ $airlineCode }}</p>
                    <p class="text-xs text-gray-500">Flight #{{ $flightNumber }}</p>
                </div>
            </div>
            
            <!-- Flight Details -->
            <div class="col-span-12 md:col-span-7">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-start">
                    <!-- Departure -->
                    <div class="text-center mb-4 md:mb-0">
                        <p class="text-3xl font-bold text-gray-900">{{ $departureTime }}</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $originCity }}</p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($segments[0]['departure']['at'])->format('D, M j') }}</p>
                    </div>
                    
                    <!-- Flight Duration/Stops -->
                    <div class="flex flex-col items-center px-6">
                        <p class="text-gray-500 text-sm mb-1">{{ $totalDuration }}</p>
                        <div class="w-32 md:w-48 h-0.5 bg-gray-300 relative">
                            @if($isMultiSegment)
                                @for($i = 1; $i <= $stopCount; $i++)
                                    <div class="absolute w-2 h-2 bg-orange-500 rounded-full" style="left: {{ ($i / ($stopCount + 1)) * 100 }}%; top: -3px;"></div>
                                @endfor
                            @endif
                        </div>
                        <p class="text-xs mt-1 text-gray-500">
                            @if($isMultiSegment)
                                {{ $stopCount }} {{ $stopCount == 1 ? 'stop' : 'stops' }}
                            @else
                                Direct
                            @endif
                        </p>
                    </div>
                    
                    <!-- Arrival -->
                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-900">{{ $arrivalTime }}</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $destinationCity }}</p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($segments[count($segments) - 1]['arrival']['at'])->format('D, M j') }}</p>
                    </div>
                </div>
                
                <!-- Features/Amenities -->
                <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> {{ $cabinClassFormatted }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i data-lucide="briefcase" class="w-3 h-3 mr-1"></i> Baggage included
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i data-lucide="wifi" class="w-3 h-3 mr-1"></i> In-flight entertainment
                    </span>
                </div>
            </div>
            
            <!-- Price & Actions -->
            <div class="col-span-12 md:col-span-3 text-center md:text-right border-t pt-4 mt-4 md:border-t-0 md:pt-0 md:mt-0">
                <p class="text-gray-500 text-sm">Per passenger from</p>
                <p class="text-3xl font-bold text-blue-600">{{ $currencySymbol }} {{ number_format($price, 2) }}</p>
                <p class="text-gray-500 text-xs mb-3">{{ $currency }}</p>
                
                <form action="{{ $isDirectBooking ? route('popular.destination.process') : route('flights.select') }}" method="POST">
                    @csrf
                    <input type="hidden" name="flight_data" value="{{ json_encode($flight) }}">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:-translate-y-1 shadow-md">
                        Select Flight
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
