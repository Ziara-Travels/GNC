@extends('layouts.main')

@php
function formatDuration($duration) {
    $pattern = '/PT(?:(\d+)H)?(?:(\d+)M)?/';
    if (preg_match($pattern, $duration, $matches)) {
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        if ($hours > 0 && $minutes > 0) return "{$hours}h {$minutes}m";
        elseif ($hours > 0) return "{$hours}h";
        elseif ($minutes > 0) return "{$minutes}m";
    }
    return $duration;
}

$isDirectBooking = isset($isDirectBooking) && $isDirectBooking === true;
$destination = $destination ?? null;
@endphp

@section('content')

@if(isset($noFlightsFound) && $noFlightsFound)
{{-- ========== NO FLIGHTS FOUND ========== --}}
<section class="min-h-[60vh] bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 flex items-center justify-center py-20">
    <div class="max-w-xl mx-auto px-4 text-center">
        <div class="bg-white rounded-2xl shadow-2xl p-10">
            <div class="mb-6">
                <i class="fas fa-plane text-blue-500 text-5xl animate-bounce"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-3">No Flights Found</h2>
            <p class="text-gray-600 mb-6">We couldn't find flights matching your criteria. Try adjusting your search.</p>

            <div class="bg-gray-50 rounded-xl p-5 mb-6 text-left">
                <h3 class="font-semibold text-gray-800 text-sm mb-3"><i class="fas fa-lightbulb text-amber-500 mr-2"></i>Suggestions</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li><i class="fas fa-calendar-alt text-blue-500 mr-2"></i>Try different dates (&plusmn;3 days)</li>
                    <li><i class="fas fa-map-marker-alt text-green-500 mr-2"></i>Try nearby airports</li>
                    <li><i class="fas fa-plane text-cyan-500 mr-2"></i>Remove "Direct flights only" filter</li>
                    <li><i class="fas fa-star text-amber-500 mr-2"></i>Consider different cabin classes</li>
                </ul>
            </div>

            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('flights.public.search') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Search Again
                </a>
                <a href="{{ url('/') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
            </div>
        </div>
    </div>
</section>

@else
{{-- ========== FLIGHT RESULTS ========== --}}
<section class="bg-gradient-to-br from-slate-900 to-blue-900 pt-28 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white mb-3">
                <i class="fas fa-plane-departure mr-2"></i>Flight Results
            </h1>
            @if(isset($searchParams))
            <div class="flex flex-wrap justify-center gap-4 text-blue-200 text-sm">
                <span class="bg-white/10 px-4 py-1.5 rounded-full">
                    <strong class="text-white">{{ $searchParams['from'] ?? 'Origin' }}</strong>
                    <i class="fas fa-arrow-right mx-2 text-blue-400"></i>
                    <strong class="text-white">{{ $searchParams['to'] ?? 'Destination' }}</strong>
                </span>
                <span class="bg-white/10 px-4 py-1.5 rounded-full">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $searchParams['date'] ?? '' }}
                    @if(!empty($searchParams['return_date']))
                        &mdash; {{ $searchParams['return_date'] }}
                    @endif
                </span>
                <span class="bg-white/10 px-4 py-1.5 rounded-full">
                    <i class="fas fa-users mr-1"></i>
                    {{ ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0) }} pax
                </span>
                <span class="bg-white/10 px-4 py-1.5 rounded-full">
                    <i class="fas fa-couch mr-1"></i>
                    {{ ucwords(str_replace('_', ' ', strtolower($searchParams['travel_class'] ?? 'Economy'))) }}
                </span>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        @if(isset($flights) && count($flights) > 0)
        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Filters Sidebar --}}
            <div class="lg:w-72 flex-shrink-0">
                @include('flights.partials.filters')
            </div>

            {{-- Results --}}
            <div class="flex-1">
                {{-- Sort bar --}}
                <div class="flex items-center justify-between mb-5 bg-white rounded-xl border border-gray-200 px-5 py-3">
                    <div class="text-sm text-gray-600">
                        <span id="results-count" class="font-bold text-blue-600 text-lg">{{ count($flights) }}</span> flights found
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 hidden sm:inline">Sort by:</span>
                        <button onclick="sortFlights('price-asc')" class="sort-btn text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-700 transition-colors" data-sort="price-asc">
                            Price <i class="fas fa-arrow-up text-[9px]"></i>
                        </button>
                        <button onclick="sortFlights('price-desc')" class="sort-btn text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-700 transition-colors" data-sort="price-desc">
                            Price <i class="fas fa-arrow-down text-[9px]"></i>
                        </button>
                        <button onclick="sortFlights('duration-asc')" class="sort-btn text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-700 transition-colors" data-sort="duration-asc">
                            Fastest
                        </button>
                        <button onclick="sortFlights('stops-asc')" class="sort-btn text-xs font-semibold px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-700 transition-colors" data-sort="stops-asc">
                            Stops
                        </button>
                    </div>
                </div>

                {{-- Flight cards --}}
                <div class="flight-results space-y-0">
                    @foreach($flights as $flight)
                        @include('flights.partials.flight-card', [
                            'flight' => $flight,
                            'isOfferMode' => $isOfferMode ?? false,
                            'isDirectBooking' => $isDirectBooking,
                            'destination' => $destination
                        ])
                    @endforeach
                </div>

                {{-- Modify search --}}
                <div class="text-center mt-8">
                    <a href="{{ route('flights.public.search') }}" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-semibold text-sm">
                        <i class="fas fa-edit"></i> Modify Search
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function sortFlights(sortBy) {
    var container = document.querySelector('.flight-results');
    if (!container) return;
    var cards = Array.from(container.querySelectorAll('.flight-card'));

    // Highlight active sort button
    document.querySelectorAll('.sort-btn').forEach(function(b) {
        b.classList.remove('bg-blue-600', 'text-white');
        b.classList.add('bg-gray-100', 'text-gray-700');
    });
    var activeBtn = document.querySelector('.sort-btn[data-sort="' + sortBy + '"]');
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        activeBtn.classList.add('bg-blue-600', 'text-white');
    }

    cards.sort(function(a, b) {
        if (sortBy === 'price-asc') return getPrice(a) - getPrice(b);
        if (sortBy === 'price-desc') return getPrice(b) - getPrice(a);
        if (sortBy === 'duration-asc') return getDuration(a) - getDuration(b);
        if (sortBy === 'stops-asc') return getStops(a) - getStops(b);
        return 0;
    });
    cards.forEach(function(c) { container.appendChild(c); });
}

function getPrice(el) { return parseFloat(el.getAttribute('data-price') || 0); }
function getDuration(el) { return parseInt(el.getAttribute('data-duration') || 0); }
function getStops(el) { return parseInt(el.getAttribute('data-stops') || 0); }
</script>
@endpush

@push('styles')
<style>
    .flight-card { transition: all 0.15s ease; }
    .flight-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
</style>
@endpush
