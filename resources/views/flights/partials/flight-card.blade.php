{{-- flight-card.blade.php - Flight card partial for displaying flight offers --}}

@php
    $isHighlighted = ($isDirectBooking ?? false) && isset($destination) && isset($flight['id']) && strpos($flight['id'], 'offer-') === 0;
    $cardClass = $isHighlighted ? 'border-2 border-amber-400 ring-2 ring-amber-100' : 'border border-gray-200';
    $itinerary = $flight['itineraries'][0];
    $segments = $itinerary['segments'];
    $originCity = $segments[0]['departure']['iataCode'];
    $destinationCity = $segments[count($segments) - 1]['arrival']['iataCode'];
    $departureTime = \Carbon\Carbon::parse($segments[0]['departure']['at'])->format('H:i');
    $arrivalTime = \Carbon\Carbon::parse($segments[count($segments) - 1]['arrival']['at'])->format('H:i');
    $price = floatval($flight['price']['grandTotal'] ?? $flight['price']['total'] ?? 0);
    $currency = $flight['price']['currency'] ?? 'USD';
    $currencySymbol = $currency === 'USD' ? '$' : ($currency === 'KES' ? 'KSh' : $currency);

    // Duration from itinerary
    $rawDuration = $itinerary['duration'] ?? '';
    $totalDuration = $rawDuration ? formatDuration($rawDuration) : '—';
    // Duration in minutes for sorting
    $durationMinutes = 0;
    if (preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?/', $rawDuration, $dm)) {
        $durationMinutes = (intval($dm[1] ?? 0) * 60) + intval($dm[2] ?? 0);
    }

    $cabinClass = $flight['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] ?? 'ECONOMY';
    $cabinClassFormatted = ucfirst(strtolower($cabinClass));
    $airlineCode = $segments[0]['carrierCode'] ?? '';
    $flightNumber = $segments[0]['number'] ?? '';
    $stopCount = count($segments) - 1;

    // Departure hour for time-of-day filter
    $depHour = intval(\Carbon\Carbon::parse($segments[0]['departure']['at'])->format('H'));
    if ($depHour >= 5 && $depHour < 12) $depTimeSlot = 'morning';
    elseif ($depHour >= 12 && $depHour < 17) $depTimeSlot = 'afternoon';
    elseif ($depHour >= 17 && $depHour < 21) $depTimeSlot = 'evening';
    else $depTimeSlot = 'night';
@endphp

<div class="flight-card bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-200 overflow-hidden mb-4 {{ $cardClass }}"
     data-airline="{{ $airlineCode }}"
     data-price="{{ $price }}"
     data-stops="{{ $stopCount }}"
     data-departure-time="{{ $depTimeSlot }}"
     data-duration="{{ $durationMinutes }}">

    @if($isHighlighted)
        <div class="bg-amber-400 py-1.5 px-4 text-amber-900 font-bold text-center text-xs uppercase tracking-wide">
            <i class="fas fa-star mr-1"></i> Featured Destination
        </div>
    @endif

    <div class="p-5">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
            {{-- Airline --}}
            <div class="flex md:flex-col items-center md:w-20 gap-3 md:gap-1 flex-shrink-0">
                <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center">
                    <img src="https://daisycon.io/images/airline/?width=100&height=50&color=ffffff&iata={{ $airlineCode }}"
                         alt="{{ $airlineCode }}" class="h-6 max-w-[40px]"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-xs font-bold text-gray-600\'>{{ $airlineCode }}</span>';">
                </div>
                <div class="text-center">
                    <p class="text-xs font-semibold text-gray-700">{{ $airlineCode }} {{ $flightNumber }}</p>
                </div>
            </div>

            {{-- Route --}}
            <div class="flex-1">
                <div class="flex items-center justify-between gap-2">
                    <div class="text-center min-w-[70px]">
                        <p class="text-xl font-bold text-gray-900 flight-departure-time">{{ $departureTime }}</p>
                        <p class="text-sm font-medium text-gray-700">{{ $originCity }}</p>
                    </div>

                    <div class="flex-1 flex flex-col items-center px-2">
                        <span class="text-xs text-gray-400 flight-duration">{{ $totalDuration }}</span>
                        <div class="w-full h-px bg-gray-300 relative my-1">
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-gray-300 rounded-full"></div>
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-1.5 bg-gray-300 rounded-full"></div>
                            @for($i = 0; $i < $stopCount; $i++)
                                <div class="absolute w-2 h-2 bg-orange-500 rounded-full top-1/2 -translate-y-1/2" style="left: {{ (($i + 1) / ($stopCount + 1)) * 100 }}%"></div>
                            @endfor
                        </div>
                        <span class="text-[10px] flight-stops {{ $stopCount === 0 ? 'text-emerald-600 font-semibold' : 'text-orange-600' }}">
                            {{ $stopCount === 0 ? 'Direct' : $stopCount . ($stopCount === 1 ? ' stop' : ' stops') }}
                        </span>
                    </div>

                    <div class="text-center min-w-[70px]">
                        <p class="text-xl font-bold text-gray-900">{{ $arrivalTime }}</p>
                        <p class="text-sm font-medium text-gray-700">{{ $destinationCity }}</p>
                    </div>
                </div>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-blue-50 text-blue-700">
                        <i class="fas fa-couch mr-1 text-[8px]"></i> {{ $cabinClassFormatted }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-green-50 text-green-700">
                        <i class="fas fa-suitcase mr-1 text-[8px]"></i> Baggage
                    </span>
                    @if(count($flight['itineraries']) > 1)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-50 text-purple-700">
                        <i class="fas fa-exchange-alt mr-1 text-[8px]"></i> Return included
                    </span>
                    @endif
                </div>
            </div>

            {{-- Price & Action --}}
            <div class="flex md:flex-col items-center md:items-end justify-between md:justify-center gap-2 md:w-44 flex-shrink-0 pt-3 md:pt-0 border-t md:border-t-0 md:border-l md:pl-5 border-gray-100">
                <div class="text-right">
                    <p class="text-xs text-gray-500">per person</p>
                    <p class="text-2xl font-bold text-blue-600 flight-price">{{ $currencySymbol }}{{ number_format($price, 0) }}</p>
                    <p class="text-[10px] text-gray-400">{{ $currency }}</p>
                </div>
                <form action="{{ ($isDirectBooking ?? false) ? route('popular.destination.process') : route('flights.select') }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <input type="hidden" name="flight_data" value="{{ json_encode($flight) }}">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors text-sm">
                        Select <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
