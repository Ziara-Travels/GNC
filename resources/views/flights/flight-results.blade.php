@extends('layouts.main')

@section('title', 'Flight Search Results - Ziara Travel')

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
<section class="zt-section zt-section--pale ziara-flight-results" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;min-height:60vh;display:flex;align-items:center;">
    <div class="container" style="max-width:640px;">
        <div class="ziara-panel" style="text-align:center;padding:44px;">
            <div class="mb-6">
                <i class="fas fa-plane" aria-hidden="true" style="font-size:52px;color:var(--maroon);"></i>
            </div>
            <h2 class="ziara-panel__title" style="font-size:32px;">No flights found</h2>
            <p style="color:var(--muted);font-size:15px;margin-bottom:26px;">We couldn't find flights matching your criteria. Try adjusting your search.</p>

            <div class="ziara-panel" style="text-align:left;padding:22px;background:var(--gold-pale);border-color:var(--gold-border);">
                <h3 style="font-family:'Cormorant Garamond',Georgia,serif;font-size:22px;color:var(--maroon);margin:0 0 12px;"><i class="fas fa-lightbulb" aria-hidden="true" style="color:var(--gold);margin-right:8px;"></i>Suggestions</h3>
                <ul class="ziara-checklist" style="gap:10px;">
                    <li class="ziara-checklist__item" style="font-size:14px;">Try different dates (&plusmn;3 days)</li>
                    <li class="ziara-checklist__item" style="font-size:14px;">Try nearby airports</li>
                    <li class="ziara-checklist__item" style="font-size:14px;">Remove "Direct flights only" filter</li>
                    <li class="ziara-checklist__item" style="font-size:14px;">Consider different cabin classes</li>
                </ul>
            </div>

            <div class="flex flex-wrap gap-3 justify-center" style="margin-top:26px;">
                <a href="{{ route('flights.public.search') }}" class="ziara-btn ziara-btn--gold">
                    <i class="fas fa-search" aria-hidden="true"></i> Search Again
                </a>
                <a href="{{ url('/') }}" class="ziara-btn ziara-btn--outline">
                    <i class="fas fa-home" aria-hidden="true"></i> Home
                </a>
            </div>
        </div>
    </div>
</section>

@else
@include('partials.ziara-page-hero', [
    'kicker' => 'Flight Results',
    'title'  => '<em>Compare</em> Flights',
    'intro'  => 'Sort, filter and choose the itinerary that best fits your trip.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Flights', 'url' => route('flights.public.search')],
        ['label' => 'Results', 'url' => route('flights.public.search')],
    ],
])

<section class="zt-section zt-section--pale ziara-flight-results" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @if(isset($searchParams))
        <div class="ziara-panel" style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;padding:20px 24px;margin-bottom:28px;">
            <span class="ziara-flight-results__chip">
                <strong>{{ $searchParams['from'] ?? 'Origin' }}</strong>
                <i class="fas fa-arrow-right" aria-hidden="true" style="color:var(--gold);margin:0 8px;"></i>
                <strong>{{ $searchParams['to'] ?? 'Destination' }}</strong>
            </span>
            <span class="ziara-flight-results__chip">
                <i class="fas fa-calendar" aria-hidden="true" style="color:var(--gold);margin-right:6px;"></i>
                {{ $searchParams['date'] ?? '' }}
                @if(!empty($searchParams['return_date']))
                    &mdash; {{ $searchParams['return_date'] }}
                @endif
            </span>
            <span class="ziara-flight-results__chip">
                <i class="fas fa-users" aria-hidden="true" style="color:var(--gold);margin-right:6px;"></i>
                {{ ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0) }} pax
            </span>
            <span class="ziara-flight-results__chip">
                <i class="fas fa-couch" aria-hidden="true" style="color:var(--gold);margin-right:6px;"></i>
                {{ ucwords(str_replace('_', ' ', strtolower($searchParams['travel_class'] ?? 'Economy'))) }}
            </span>
        </div>
        @endif

        @if(isset($flights) && count($flights) > 0)
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="lg:w-72 flex-shrink-0 ziara-flight-results__filters">
                @include('flights.partials.filters')
            </div>

            <div class="flex-1">
                <div class="ziara-flight-results__tabs">
                    <button onclick="sortFlights('best')" class="ziara-sort-tab active flex-1 text-center text-sm font-bold px-4 py-3 transition-colors" data-sort="best">
                        <i class="fas fa-crown text-[10px] mr-1"></i> Best
                    </button>
                    <button onclick="sortFlights('price-asc')" class="ziara-sort-tab flex-1 text-center text-sm font-bold px-4 py-3 transition-colors" data-sort="price-asc">
                        Cheapest
                    </button>
                    <button onclick="sortFlights('duration-asc')" class="ziara-sort-tab flex-1 text-center text-sm font-bold px-4 py-3 transition-colors" data-sort="duration-asc">
                        Fastest
                    </button>
                </div>

                <div class="ziara-card ziara-flight-results__sortbar">
                    <div style="font-size:14px;color:var(--muted);">
                        <span id="results-count" class="ziara-result-count">{{ count($flights) }}</span> flights found
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span style="font-size:12px;color:var(--muted);">Sort by:</span>
                        <button onclick="sortFlights('price-asc')" class="ziara-sort-tab text-xs font-semibold px-3 py-1.5 transition-colors" data-sort="price-asc">
                            Price <i class="fas fa-arrow-up text-[9px]"></i>
                        </button>
                        <button onclick="sortFlights('price-desc')" class="ziara-sort-tab text-xs font-semibold px-3 py-1.5 transition-colors" data-sort="price-desc">
                            Price <i class="fas fa-arrow-down text-[9px]"></i>
                        </button>
                        <button onclick="sortFlights('duration-asc')" class="ziara-sort-tab text-xs font-semibold px-3 py-1.5 transition-colors" data-sort="duration-asc">
                            Fastest
                        </button>
                        <button onclick="sortFlights('stops-asc')" class="ziara-sort-tab text-xs font-semibold px-3 py-1.5 transition-colors" data-sort="stops-asc">
                            Stops
                        </button>
                    </div>
                </div>

                <div class="flight-results ziara-deals space-y-0">
                    @foreach($flights as $flight)
                        @include('flights.partials.flight-card', [
                            'flight' => $flight,
                            'isOfferMode' => $isOfferMode ?? false,
                            'isDirectBooking' => $isDirectBooking,
                            'destination' => $destination
                        ])
                    @endforeach
                </div>

                <div class="text-center mt-6" id="show-more-wrap">
                    <button onclick="showMoreFlights()" id="show-more-btn" class="ziara-btn ziara-btn--gold font-semibold text-sm">
                        Show more flights <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('flights.public.search') }}" class="ziara-btn ziara-btn--outline font-semibold text-sm">
                        <i class="fas fa-edit" aria-hidden="true"></i> Modify Search
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
var VISIBLE_STEP = 10;
var visibleCount = VISIBLE_STEP;

function sortFlights(sortBy) {
    var container = document.querySelector('.flight-results');
    if (!container) return;
    var cards = Array.from(container.querySelectorAll('.flight-card'));

    // Highlight active button in whichever bar it came from (best/cheapest/fastest tabs, or the granular sort bar)
    document.querySelectorAll('.ziara-sort-tab').forEach(function(b) {
        b.classList.remove('active');
    });
    document.querySelectorAll('[data-sort="' + sortBy + '"]').forEach(function(b) {
        b.classList.add('active');
    });

    if (sortBy === 'best') {
        var prices = cards.map(getPrice), durations = cards.map(getDuration);
        var minP = Math.min.apply(null, prices), maxP = Math.max.apply(null, prices);
        var minD = Math.min.apply(null, durations), maxD = Math.max.apply(null, durations);
        var score = function (el) {
            var p = maxP > minP ? (getPrice(el) - minP) / (maxP - minP) : 0;
            var d = maxD > minD ? (getDuration(el) - minD) / (maxD - minD) : 0;
            // Weighted toward price, matching the "best value" framing most OTAs use.
            return (p * 0.65) + (d * 0.35);
        };
        cards.sort(function (a, b) { return score(a) - score(b); });
    } else {
        cards.sort(function(a, b) {
            if (sortBy === 'price-asc') return getPrice(a) - getPrice(b);
            if (sortBy === 'price-desc') return getPrice(b) - getPrice(a);
            if (sortBy === 'duration-asc') return getDuration(a) - getDuration(b);
            if (sortBy === 'stops-asc') return getStops(a) - getStops(b);
            return 0;
        });
    }

    cards.forEach(function(c) { container.appendChild(c); });
    visibleCount = VISIBLE_STEP;
    applyVisibility();
}

function applyVisibility() {
    var cards = document.querySelectorAll('.flight-results .flight-card');
    cards.forEach(function (card, i) {
        card.style.display = i < visibleCount ? '' : 'none';
    });
    var wrap = document.getElementById('show-more-wrap');
    var btn = document.getElementById('show-more-btn');
    if (!wrap || !btn) return;
    var remaining = cards.length - visibleCount;
    if (remaining <= 0) {
        wrap.style.display = 'none';
    } else {
        wrap.style.display = '';
        btn.innerHTML = 'Show ' + Math.min(remaining, VISIBLE_STEP) + ' more flights <i class="fas fa-chevron-down text-[10px]"></i>';
    }
}

function showMoreFlights() {
    visibleCount += VISIBLE_STEP;
    applyVisibility();
}

function getPrice(el) { return parseFloat(el.getAttribute('data-price') || 0); }
function getDuration(el) { return parseInt(el.getAttribute('data-duration') || 0); }
function getStops(el) { return parseInt(el.getAttribute('data-stops') || 0); }

document.addEventListener('DOMContentLoaded', function () {
    sortFlights('best');
});
</script>
@endpush

@push('styles')
<style>
    .flight-card { transition: all 0.15s ease; }
    .flight-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }

    .ziara-flight-results .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
    .ziara-flight-results .ziara-flight-results__chip {
        display:inline-flex;align-items:center;padding:9px 18px;border-radius:50px;
        background:var(--gold-pale);border:1px solid var(--gold-border);
        font-size:13.5px;color:var(--ink);
    }
    .ziara-flight-results .ziara-flight-results__tabs { display:flex;gap:8px;margin-bottom:20px; }
    .ziara-flight-results .ziara-sort-tab {
        border:1.5px solid var(--gold-border);background:#fff;border-radius:12px;color:var(--muted);
        font-weight:600;transition:all .2s ease;
    }
    .ziara-flight-results .ziara-sort-tab:hover { border-color:var(--gold);color:var(--maroon); }
    .ziara-flight-results .ziara-sort-tab.active { background:var(--maroon);border-color:var(--maroon);color:var(--gold-bright); }
    .ziara-flight-results .ziara-flight-results__sortbar {
        display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;
        padding:16px 22px;margin-bottom:22px;
    }
    .ziara-flight-results .flight-results.ziara-deals { grid-template-columns:1fr;gap:0; }
    .ziara-flight-results .flight-results > .flight-card { border-color:var(--gold-border) !important; }
    .ziara-flight-results .flight-results > .flight-card:hover { box-shadow:var(--shadow-md); }
    .ziara-flight-results .ziara-flight-results__filters > div { border-color:var(--gold-border) !important; }
    .ziara-flight-results .ziara-flight-results__filters > div:first-child { background:var(--maroon-deep) !important; }
    .ziara-flight-results .ziara-flight-results__filters h3 { font-family:'Cormorant Garamond',Georgia,serif;font-size:20px;color:var(--gold-bright); }
    .ziara-flight-results .ziara-flight-results__filters .text-blue-300 { color:var(--gold-bright) !important; }
    .ziara-flight-results .ziara-flight-results__filters input[type="checkbox"] { accent-color:#7b2a1b; }
    .ziara-flight-results .ziara-flight-results__filters input[type="range"] { accent-color:#7b2a1b; }
    .ziara-flight-results .ziara-flight-results__filters .text-blue-600 { color:#7b2a1b !important; }
</style>
@endpush
