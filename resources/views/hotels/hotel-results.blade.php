@extends('layouts.main')

@section('content')
@php
    // Normalize variable naming — HotelBookingController passes search_params, HotelSearchController passes searchParams
    $searchParams = $searchParams ?? $search_params ?? [];

    // Normalize hotels data — HotelBookingController may pass full response with 'data' key
    if (isset($hotels['data'])) {
        $hotels = $hotels['data'];
    }
    $hotels = $hotels ?? [];

    // Extract unique star ratings and price range for filters
    $starRatings = [];
    $allPrices = [];
    $allAmenities = [];
    foreach ($hotels as $h) {
        $rating = intval($h['hotel']['rating'] ?? 0);
        if ($rating > 0) $starRatings[$rating] = $rating;
        $price = floatval($h['offers'][0]['price']['total'] ?? 0);
        if ($price > 0) $allPrices[] = $price;
        foreach ($h['hotel']['amenities'] ?? [] as $a) {
            $key = str_replace('_', ' ', $a);
            $allAmenities[$key] = ($allAmenities[$key] ?? 0) + 1;
        }
    }
    arsort($allAmenities);
    $topAmenities = array_slice(array_keys($allAmenities), 0, 8);
    krsort($starRatings);
    $minPrice = !empty($allPrices) ? floor(min($allPrices)) : 0;
    $maxPrice = !empty($allPrices) ? ceil(max($allPrices)) : 1000;
    $nights = 1;
    if (!empty($searchParams['check_in_date']) && !empty($searchParams['check_out_date'])) {
        $nights = \Carbon\Carbon::parse($searchParams['check_in_date'])->diffInDays(\Carbon\Carbon::parse($searchParams['check_out_date']));
    }
@endphp

<div class="bg-gray-50 min-h-screen">
    {{-- Search Summary Header --}}
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 text-white py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-slate-400 text-sm">Hotel search results</p>
                    <h1 class="text-2xl font-bold mt-1">{{ $searchParams['destination'] ?? 'Hotels' }}</h1>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    @if(!empty($searchParams['check_in_date']))
                        <span class="bg-white/10 backdrop-blur px-3 py-1.5 rounded-full">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ \Carbon\Carbon::parse($searchParams['check_in_date'])->format('M d') }} &ndash; {{ \Carbon\Carbon::parse($searchParams['check_out_date'])->format('M d, Y') }}
                        </span>
                    @endif
                    @if(!empty($searchParams['rooms']))
                        <span class="bg-white/10 backdrop-blur px-3 py-1.5 rounded-full">
                            <i class="fas fa-door-open mr-1"></i>
                            {{ $searchParams['rooms'] }} {{ $searchParams['rooms'] > 1 ? 'rooms' : 'room' }}
                        </span>
                    @endif
                    @if(!empty($searchParams['adults']))
                        <span class="bg-white/10 backdrop-blur px-3 py-1.5 rounded-full">
                            <i class="fas fa-user mr-1"></i>
                            {{ $searchParams['adults'] }} {{ $searchParams['adults'] > 1 ? 'guests' : 'guest' }}
                        </span>
                    @endif
                    <span class="bg-white/10 backdrop-blur px-3 py-1.5 rounded-full">
                        <i class="fas fa-moon mr-1"></i>
                        {{ $nights }} {{ $nights > 1 ? 'nights' : 'night' }}
                    </span>
                </div>
                <a href="{{ route('hotels.search.form') }}" class="inline-flex items-center bg-white text-slate-800 px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-100 transition">
                    <i class="fas fa-search mr-2"></i> Modify Search
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Results Count & Sort --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
            <h2 class="text-xl font-bold text-gray-900">
                <span id="results-count">{{ count($hotels) }}</span> hotels found
                <span class="text-sm font-normal text-gray-500">in {{ $searchParams['destination'] ?? 'your area' }}</span>
            </h2>
            <div class="flex items-center gap-2">
                <label for="sort-by" class="text-sm text-gray-600 whitespace-nowrap">Sort by:</label>
                <select id="sort-by" class="border border-gray-300 rounded-lg text-sm py-2 px-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                    <option value="recommended">Recommended</option>
                    <option value="price_low">Price: Low → High</option>
                    <option value="price_high">Price: High → Low</option>
                    <option value="rating_high">Stars: High → Low</option>
                    <option value="name_asc">Name: A → Z</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            {{-- Filters Sidebar --}}
            <aside class="w-full lg:w-72 shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden lg:sticky lg:top-24">
                    <div class="bg-slate-800 text-white px-5 py-3 flex justify-between items-center">
                        <span class="font-semibold text-sm"><i class="fas fa-sliders-h mr-2"></i>Filters</span>
                        <button id="clear-filters" class="text-xs text-slate-300 hover:text-white transition">Clear all</button>
                    </div>

                    <div class="p-5 space-y-6">
                        {{-- Price Range --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Price per stay</h4>
                            <div class="flex gap-2 items-center">
                                <div class="flex-1">
                                    <label class="text-xs text-gray-500">Min</label>
                                    <input type="number" id="filter-price-min" value="{{ $minPrice }}" min="0"
                                        class="w-full border border-gray-300 rounded-lg text-sm py-1.5 px-2 mt-0.5 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <span class="text-gray-400 mt-4">&ndash;</span>
                                <div class="flex-1">
                                    <label class="text-xs text-gray-500">Max</label>
                                    <input type="number" id="filter-price-max" value="{{ $maxPrice }}" min="0"
                                        class="w-full border border-gray-300 rounded-lg text-sm py-1.5 px-2 mt-0.5 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        {{-- Star Rating --}}
                        @if(!empty($starRatings))
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Star Rating</h4>
                            <div class="space-y-2">
                                @foreach($starRatings as $stars)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" class="filter-star rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4" value="{{ $stars }}">
                                        <span class="text-yellow-400 text-sm tracking-tight">
                                            @for($s = 0; $s < $stars; $s++) ★ @endfor
                                        </span>
                                        <span class="text-gray-400 text-xs group-hover:text-gray-600">{{ $stars }}-star</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- Amenities --}}
                        @if(!empty($topAmenities))
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Amenities</h4>
                            <div class="space-y-2">
                                @foreach($topAmenities as $amenity)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" class="filter-amenity rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4" value="{{ $amenity }}">
                                        <span class="text-gray-700 text-sm">{{ ucwords(strtolower($amenity)) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </aside>

            {{-- Hotel Cards --}}
            <div class="flex-1 min-w-0">
                <div class="space-y-5" id="hotel-results">
                    @forelse($hotels as $hotel)
                        @php
                            $hotelData = $hotel['hotel'] ?? [];
                            $hotelName = $hotelData['name'] ?? 'Unknown Hotel';
                            $hotelRating = intval($hotelData['rating'] ?? 0);
                            $hotelCity = $hotelData['address']['cityName'] ?? ($hotelData['address']['lines'][0] ?? '');
                            $hotelAmenities = $hotelData['amenities'] ?? [];
                            $hotelMedia = $hotelData['media'] ?? [];
                            $hotelId = $hotelData['hotelId'] ?? '';
                            $offer = $hotel['offers'][0] ?? [];
                            $price = floatval($offer['price']['total'] ?? 0);
                            $currency = $offer['price']['currency'] ?? 'USD';
                            $offerId = $offer['id'] ?? '';
                            $roomDesc = $offer['room']['description']['text'] ?? '';
                            $roomType = $offer['room']['typeEstimated']['category'] ?? '';
                            $amenityStr = implode(',', array_map(function($a){ return str_replace('_',' ',$a); }, $hotelAmenities));
                        @endphp
                        <div class="hotel-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow"
                             data-price="{{ $price }}"
                             data-rating="{{ $hotelRating }}"
                             data-name="{{ strtolower($hotelName) }}"
                             data-amenities="{{ strtolower($amenityStr) }}">
                            <div class="flex flex-col md:flex-row">
                                {{-- Image --}}
                                <div class="md:w-72 relative shrink-0">
                                    @if(!empty($hotelMedia))
                                        <img src="{{ $hotelMedia[0]['uri'] ?? '' }}"
                                             alt="{{ $hotelName }}"
                                             class="w-full h-52 md:h-full object-cover"
                                             loading="lazy"
                                             onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=No+Image';">
                                    @else
                                        <div class="w-full h-52 md:h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                            <i class="fas fa-hotel text-slate-300 text-5xl"></i>
                                        </div>
                                    @endif
                                    @if($hotelRating >= 4)
                                        <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-md shadow">
                                            <i class="fas fa-star mr-0.5"></i> {{ $hotelRating }}-Star
                                        </span>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 p-5 flex flex-col">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="min-w-0">
                                                <h3 class="font-bold text-lg text-gray-900 truncate">{{ $hotelName }}</h3>
                                                <div class="flex items-center gap-2 mt-1 text-sm">
                                                    @if($hotelRating > 0)
                                                        <span class="text-yellow-400">
                                                            @for($s = 0; $s < $hotelRating; $s++) ★ @endfor
                                                        </span>
                                                    @endif
                                                    @if($hotelCity)
                                                        <span class="text-gray-500"><i class="fas fa-map-marker-alt text-indigo-400 mr-1"></i>{{ $hotelCity }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($hotelRating > 0)
                                                <div class="bg-indigo-600 text-white rounded-lg px-3 py-2 text-center shrink-0">
                                                    <div class="text-lg font-bold leading-tight">{{ $hotelRating }}.0</div>
                                                    <div class="text-[10px] opacity-80">
                                                        @if($hotelRating >= 5) Excellent
                                                        @elseif($hotelRating >= 4) Very Good
                                                        @elseif($hotelRating >= 3) Good
                                                        @else Average
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Room Type --}}
                                        @if($roomType || $roomDesc)
                                            <p class="text-sm text-gray-500 mt-2">
                                                <i class="fas fa-bed text-gray-400 mr-1"></i>
                                                {{ $roomType ? ucwords(strtolower(str_replace('_', ' ', $roomType))) : '' }}
                                                @if($roomDesc) &mdash; {{ \Illuminate\Support\Str::limit($roomDesc, 80) }} @endif
                                            </p>
                                        @endif

                                        {{-- Amenities --}}
                                        @if(!empty($hotelAmenities))
                                            <div class="flex flex-wrap gap-1.5 mt-3">
                                                @foreach(array_slice($hotelAmenities, 0, 5) as $amenity)
                                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-medium">
                                                        {{ ucwords(strtolower(str_replace('_', ' ', $amenity))) }}
                                                    </span>
                                                @endforeach
                                                @if(count($hotelAmenities) > 5)
                                                    <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-xs font-medium">
                                                        +{{ count($hotelAmenities) - 5 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Price & CTA --}}
                                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-end">
                                        <div>
                                            <p class="text-xs text-gray-500">
                                                {{ $searchParams['rooms'] ?? 1 }} room &middot; {{ $nights }} {{ $nights > 1 ? 'nights' : 'night' }}
                                            </p>
                                            <div class="flex items-baseline gap-1 mt-0.5">
                                                <span class="text-2xl font-extrabold text-gray-900">${{ number_format($price, 2) }}</span>
                                                <span class="text-sm text-gray-500">{{ $currency }}</span>
                                            </div>
                                            <p class="text-xs text-gray-400">Includes taxes &amp; fees</p>
                                        </div>
                                        @if($hotelId)
                                            <a href="{{ route('hotels.show', $hotelId) }}"
                                               class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition shadow-sm">
                                                View Deal <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hotel text-slate-400 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No hotels found</h3>
                            <p class="text-gray-500 mb-6">We couldn't find any hotels matching your criteria. Try adjusting your search.</p>
                            <a href="{{ route('hotels.search.form') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                                <i class="fas fa-arrow-left mr-2"></i> Modify Search
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('hotel-results');
    const countEl = document.getElementById('results-count');

    function getCards() {
        return Array.from(container.querySelectorAll('.hotel-card'));
    }

    // --- Sorting ---
    document.getElementById('sort-by').addEventListener('change', function() {
        const cards = getCards();
        const val = this.value;
        cards.sort(function(a, b) {
            if (val === 'price_low') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            if (val === 'price_high') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            if (val === 'rating_high') return parseInt(b.dataset.rating) - parseInt(a.dataset.rating);
            if (val === 'name_asc') return a.dataset.name.localeCompare(b.dataset.name);
            return 0;
        });
        cards.forEach(function(card) { container.appendChild(card); });
    });

    // --- Filtering ---
    function applyFilters() {
        var cards = getCards();
        var pMin = parseFloat(document.getElementById('filter-price-min').value) || 0;
        var pMax = parseFloat(document.getElementById('filter-price-max').value) || Infinity;
        var checkedStars = Array.from(document.querySelectorAll('.filter-star:checked')).map(function(el){ return el.value; });
        var checkedAmenities = Array.from(document.querySelectorAll('.filter-amenity:checked')).map(function(el){ return el.value.toLowerCase(); });
        var visible = 0;

        cards.forEach(function(card) {
            var price = parseFloat(card.dataset.price);
            var rating = card.dataset.rating;
            var amenities = card.dataset.amenities;
            var show = true;

            if (price < pMin || price > pMax) show = false;
            if (checkedStars.length > 0 && checkedStars.indexOf(rating) === -1) show = false;
            if (checkedAmenities.length > 0) {
                for (var i = 0; i < checkedAmenities.length; i++) {
                    if (amenities.indexOf(checkedAmenities[i]) === -1) { show = false; break; }
                }
            }

            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countEl) countEl.textContent = visible;
    }

    document.querySelectorAll('.filter-star, .filter-amenity').forEach(function(el) {
        el.addEventListener('change', applyFilters);
    });
    document.getElementById('filter-price-min').addEventListener('input', applyFilters);
    document.getElementById('filter-price-max').addEventListener('input', applyFilters);

    // --- Clear Filters ---
    document.getElementById('clear-filters').addEventListener('click', function() {
        document.querySelectorAll('.filter-star, .filter-amenity').forEach(function(el) { el.checked = false; });
        document.getElementById('filter-price-min').value = '{{ $minPrice }}';
        document.getElementById('filter-price-max').value = '{{ $maxPrice }}';
        applyFilters();
    });
});
</script>
@endpush