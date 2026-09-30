@extends('layouts.main')

@section('title', 'Hotel Search Results - Ziara Travel')

@section('content')
@php
    $searchParams = $searchParams ?? $search_params ?? [];
    $hotels = $hotels ?? [];
    $bookingUrls = $bookingUrls ?? [];

    $nights = 1;
    if (!empty($searchParams['checkin']) && !empty($searchParams['checkout'])) {
        $nights = max(1, (int) \Carbon\Carbon::parse($searchParams['checkin'])->diffInDays(\Carbon\Carbon::parse($searchParams['checkout'])));
    }

    $starRatings = [];
    $minPrice = null;
    $maxPrice = null;
    foreach ($hotels as $h) {
        $s = intval($h['starRating'] ?? 0);
        if ($s > 0) $starRatings[$s] = $s;
        $p = floatval($h['price'] ?? 0);
        if ($p > 0) {
            if ($minPrice === null || $p < $minPrice) $minPrice = $p;
            if ($maxPrice === null || $p > $maxPrice) $maxPrice = $p;
        }
    }
    krsort($starRatings);
    $minPrice = $minPrice !== null ? floor($minPrice) : 1000;
    $maxPrice = $maxPrice !== null ? ceil($maxPrice) : 50000;
    $destLabel = $searchParams['destination_label'] ?? ($searchParams['destination'] ?? 'Hotels');
    $currency = $currency ?? 'KES';
@endphp

<style>
.ziara-hotel-results .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
.ziara-hotel-results .ziara-deals { grid-template-columns:1fr;gap:20px; }
.ziara-hotel-results .ziara-deal { flex-direction:column;border-color:var(--gold-border); }
@media (min-width:768px) { .ziara-hotel-results .ziara-deal { flex-direction:row; } }
.ziara-hotel-results .ziara-hotel-results__media { background:linear-gradient(135deg,#f6efe6,#efe3d2); }
.ziara-hotel-results .ziara-hotel-results__score { background:#7b2a1b;color:#f3d24a;border-radius:10px;padding:6px 10px;text-align:center;line-height:1.1; }
.ziara-hotel-results .ziara-hotel-results__amenity { background:var(--gold-pale);color:var(--maroon);border:1px solid var(--gold-border);border-radius:999px;padding:2px 10px;font-size:12px;font-weight:600; }
.ziara-hotel-results .ziara-hotel-results__amenity + .ziara-hotel-results__amenity { margin-left:6px; }
.ziara-hotel-results .ziara-sort-tab.active { background:#7b2a1b;border-color:#7b2a1b;color:#f3d24a; }
.ziara-hotel-results .ziara-sort-tab:hover { border-color:#d9a561;color:#7b2a1b; }
.ziara-hotel-results #filter-price { accent-color:#7b2a1b; }
.ziara-hotel-results .filter-star, .ziara-hotel-results .filter-score { accent-color:#7b2a1b; }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'Hotel Search Results',
    'title'  => '<em>' . e($destLabel) . '</em>',
    'intro'  => 'Compare available stays, refine your filters and continue to the property that suits your trip.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Hotels', 'url' => route('hotels.search.form')],
        ['label' => 'Results', 'url' => route('hotels.search.form')],
    ],
])

<div class="zt-section zt-section--pale ziara-hotel-results" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        <div class="ziara-panel" style="padding:20px 24px;margin-bottom:26px;display:flex;flex-wrap:wrap;align-items:center;gap:14px;">
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:14px;color:var(--muted);">
                <i class="far fa-calendar-alt" aria-hidden="true" style="color:var(--gold);"></i>
                @if(!empty($searchParams['checkin']))
                    {{ \Carbon\Carbon::parse($searchParams['checkin'])->format('M d') }} &ndash; {{ \Carbon\Carbon::parse($searchParams['checkout'])->format('M d, Y') }}
                    &middot; {{ $nights }} {{ $nights > 1 ? 'nights' : 'night' }}
                @else
                    Add dates to see nightly totals
                @endif
            </span>
            <span style="display:inline-flex;align-items:center;gap:8px;font-size:14px;color:var(--muted);">
                <i class="fas fa-user" aria-hidden="true" style="color:var(--gold);"></i> {{ $searchParams['adults'] ?? 2 }} guests &middot; {{ $searchParams['rooms'] ?? 1 }} room
            </span>
            <a href="{{ route('hotels.search.form') }}" class="ziara-btn ziara-btn--outline" style="margin-left:auto;">
                <i class="fas fa-sliders-h" aria-hidden="true"></i> Modify Search
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <aside class="w-full lg:w-72 shrink-0">
                <div class="ziara-card ziara-panel overflow-hidden lg:sticky lg:top-24 ziara-filter-card" style="padding:0;">
                    <div class="ziara-filter-head px-5 py-3 flex justify-between items-center">
                        <span class="font-semibold text-sm"><i class="fas fa-sliders-h mr-2"></i>Filters</span>
                        <button id="clear-filters" class="text-xs text-blue-300 hover:text-white transition">Clear all</button>
                    </div>
                    <div class="p-5 space-y-6">
                        <div>
                            <h4 class="ziara-panel__title" style="font-size:16px;margin-bottom:12px;">Max price / night</h4>
                            <input type="range" id="filter-price" class="w-full accent-blue-600" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ $maxPrice }}" step="500">
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ number_format($minPrice, 0) }}</span>
                                <span class="font-semibold text-blue-600" data-price-label>Up to {{ number_format($maxPrice, 0) }}</span>
                            </div>
                        </div>

                        @if(!empty($starRatings))
                        <div>
                            <h4 class="ziara-panel__title" style="font-size:16px;margin-bottom:12px;">Star rating</h4>
                            <div class="space-y-2">
                                @foreach($starRatings as $stars)
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" class="filter-star rounded text-blue-600 focus:ring-blue-500 h-4 w-4" value="{{ $stars }}" checked>
                                        <span class="text-amber-400 text-sm tracking-tight">@for($s = 0; $s < $stars; $s++)★@endfor</span>
                                        <span class="text-gray-400 text-xs group-hover:text-gray-600">{{ $stars }}-star</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div>
                            <h4 class="ziara-panel__title" style="font-size:16px;margin-bottom:12px;">Review score</h4>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="filter-score rounded text-blue-600 focus:ring-blue-500 h-4 w-4" value="8" checked>
                                <span class="text-gray-700 text-sm">8+ Fabulous</span>
                            </label>
                            <label class="flex items-center gap-2 mt-2">
                                <input type="checkbox" class="filter-score rounded text-blue-600 focus:ring-blue-500 h-4 w-4" value="7">
                                <span class="text-gray-700 text-sm">7+ Good</span>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                    <h2 class="ziara-panel__title" style="font-size:22px;"><span id="results-count">{{ count($hotels) }}</span> stays found</h2>
                    <div class="flex items-center gap-2 flex-wrap">
                        <button class="ziara-sort-tab active text-xs px-4 py-2" data-sort="recommended">Recommended</button>
                        <button class="ziara-sort-tab text-xs px-4 py-2" data-sort="price_low">Price ↑</button>
                        <button class="ziara-sort-tab text-xs px-4 py-2" data-sort="price_high">Price ↓</button>
                        <button class="ziara-sort-tab text-xs px-4 py-2" data-sort="rating_high">Top rated</button>
                    </div>
                </div>

                <div id="hotel-results" class="ziara-deals">
                    @forelse($hotels as $i => $hotel)
                        @php
                            $name = $hotel['name'] ?? 'Unknown Hotel';
                            $stars = intval($hotel['starRating'] ?? 0);
                            $score = floatval($hotel['reviewScore'] ?? 0);
                            $reviews = intval($hotel['reviewCount'] ?? 0);
                            $price = floatval($hotel['price'] ?? 0);
                            $total = floatval($hotel['totalPrice'] ?? 0);
                            $image = $hotel['imageUrl'] ?? null;
                            $amenities = $hotel['amenities'] ?? [];
                            $url = $bookingUrls[$i] ?? '#';
                            $id = $hotel['propertyId'] ?? '';
                        @endphp
                        <div class="hotel-card ziara-card ziara-deal overflow-hidden flex flex-col md:flex-row"
                             data-price="{{ $price }}"
                             data-rating="{{ $stars }}"
                             data-score="{{ $score }}"
                             data-name="{{ strtolower($name) }}">
                            <div class="md:w-72 relative shrink-0 ziara-hotel-results__media bg-gray-100">
                                @if($image)
                                    <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-52 md:h-full object-cover" loading="lazy"
                                         onerror="this.onerror=null;this.src='https://placehold.co/600x400/e2e8f0/94a3b8?text=No+Image';">
                                @else
                                    <div class="w-full h-52 md:h-full flex items-center justify-center">
                                        <i class="fas fa-hotel text-gray-300 text-5xl"></i>
                                    </div>
                                @endif
                                @if($stars >= 4)
                                    <span class="ziara-deal__badge">
                                        <i class="fas fa-star mr-0.5"></i> {{ $stars }}-Star
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 p-5 flex flex-col">
                                <div class="flex-1">
                                    <div class="flex justify-between items-start gap-3">
                                        <div class="min-w-0">
                                            <h3 class="ziara-deal__title" style="font-size:23px;">{{ $name }}</h3>
                                            <div class="flex items-center gap-2 mt-1 text-sm">
                                                @if($stars > 0)
                                                    <span class="text-amber-400">@for($s = 0; $s < $stars; $s++)★@endfor</span>
                                                @endif
                                                @if(!empty($hotel['city']))
                                                    <span class="text-gray-500"><i class="fas fa-map-marker-alt text-blue-400 mr-1"></i>{{ $hotel['city'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($score > 0)
                                            <div class="flex items-center gap-2 shrink-0">
                                                <div class="ziara-hotel-results__score">
                                                    <div class="text-sm font-bold leading-tight">{{ number_format($score, 1) }}</div>
                                                </div>
                                                <div class="text-left">
                                                    <div class="text-sm font-bold text-gray-800">
                                                        @if($score >= 8.5) Excellent
                                                        @elseif($score >= 8) Very good
                                                        @elseif($score >= 7) Good
                                                        @else Okay
                                                        @endif
                                                    </div>
                                                    @if($reviews > 0)<div class="text-[11px] text-gray-400">{{ $reviews }} reviews</div>@endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!empty($amenities))
                                        <div class="flex flex-wrap gap-1.5 mt-3">
                                            @foreach(array_slice($amenities, 0, 5) as $amenity)
                                                <span class="ziara-hotel-results__amenity">{{ $amenity }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-end justify-between gap-3">
                                    <div>
                                        <p class="text-xs text-gray-500">{{ $nights }} {{ $nights > 1 ? 'nights' : 'night' }} &middot; {{ number_format($total, 2) }} total</p>
                                        <div class="flex items-baseline gap-1 mt-0.5">
                                            <span class="ziara-price ziara-deal__price" style="font-size:26px;">{{ number_format($total, 0) }}</span>
                                            <span class="text-sm text-gray-500">{{ $currency }}</span>
                                        </div>
                                        <p class="text-xs text-gray-400">&asymp; {{ number_format($price, 0) }}/night</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($id)
                                            <a href="{{ route('hotels.show', $id) }}" class="ziara-btn ziara-btn--outline">
                                                Details <i class="fas fa-chevron-right text-xs" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        <a href="{{ $url }}" target="_blank" rel="nofollow noopener" class="ziara-btn ziara-btn--gold">
                                            <i class="fas fa-book-open" aria-hidden="true"></i> See availability
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="ziara-card ziara-panel p-12 text-center">
                            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4" style="background:var(--gold-pale);color:var(--maroon);">
                                <i class="fas fa-bed text-3xl"></i>
                            </div>
                            <h3 class="ziara-panel__title" style="font-size:24px;">No hotels found</h3>
                            <p class="text-gray-500 mb-6">We couldn't find any hotels matching that search. Try another destination or dates.</p>
                            <a href="{{ route('hotels.search.form') }}" class="ziara-btn ziara-btn--gold">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i> Modify Search
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
document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('hotel-results');
    var countEl = document.getElementById('results-count');

    function getCards() { return Array.from(container.querySelectorAll('.hotel-card')); }

    document.querySelectorAll('.ziara-sort-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.ziara-sort-tab').forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            var cards = getCards();
            var val = tab.dataset.sort;
            cards.sort(function (a, b) {
                if (val === 'price_low') return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                if (val === 'price_high') return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                if (val === 'rating_high') return (parseFloat(b.dataset.score) || 0) - (parseFloat(a.dataset.score) || 0);
                return 0;
            });
            cards.forEach(function (card) { container.appendChild(card); });
            applyFilters();
        });
    });

    var maxP = {!! json_encode($maxPrice) !!};
    var priceRange = document.getElementById('filter-price');

    function applyFilters() {
        var cards = getCards();
        var pMax = priceRange ? parseFloat(priceRange.value) : Infinity;
        var checkedStars = Array.from(document.querySelectorAll('.filter-star:checked')).map(function (el) { return el.value; });
        var checkedScores = Array.from(document.querySelectorAll('.filter-score:checked')).map(function (el) { return parseFloat(el.value); });
        var visible = 0;

        cards.forEach(function (card) {
            var price = parseFloat(card.dataset.price) || 0;
            var rating = card.dataset.rating;
            var score = parseFloat(card.dataset.score) || 0;
            var show = price <= pMax;
            if (checkedStars.length > 0 && checkedStars.indexOf(rating) === -1) show = false;
            if (checkedScores.length > 0) {
                var ok = checkedScores.some(function (min) { return score >= min; });
                if (!ok) show = false;
            }
            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (countEl) countEl.textContent = visible;
    }

    priceRange.addEventListener('input', function () {
        var label = document.querySelector('[data-price-label]');
        if (label) label.textContent = Number(this.value).toLocaleString();
        applyFilters();
    });

    document.querySelectorAll('.filter-star, .filter-score').forEach(function (el) {
        el.addEventListener('change', applyFilters);
    });

    document.getElementById('clear-filters').addEventListener('click', function () {
        document.querySelectorAll('.filter-star, .filter-score').forEach(function (el) { el.checked = false; });
        if (priceRange) { priceRange.value = maxP; }
        applyFilters();
    });
});
</script>
@endpush
