@extends('layouts.main')

@section('content')
<style>
    .ziara-results-search { display: grid; grid-template-columns: 2fr 1fr auto; gap: 18px; align-items: end; }
    .ziara-results-label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
    .ziara-results-input-wrap { position: relative; }
    .ziara-results-input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--maroon); font-size: 16px; pointer-events: none; }
    .ziara-results-input { width: 100%; padding: 13px 14px 13px 42px; border: 1.5px solid var(--line); border-radius: 10px; font-size: 15px; color: var(--ink); background: #fff; font-family: var(--font-body); }
    .ziara-results-input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-border); }
    .ziara-results-submit { display: flex; align-items: flex-end; }
    .ziara-results-submit .ziara-btn { width: 100%; }
    .ziara-results-destination-media { aspect-ratio: 4 / 3; }
    .ziara-results-facts { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 22px; }
    .ziara-results-fact { display: inline-flex; align-items: center; gap: 7px; }
    .ziara-results-fact i { color: var(--maroon); font-size: 13px; }
    .ziara-results-copy { margin-bottom: 24px; }
    .ziara-results-subtitle { font-size: 19px; margin-top: 26px; }
    .ziara-results-attractions, .ziara-results-package-features { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px 22px; margin-bottom: 24px; }
    .ziara-results-tabbar { display: flex; flex-wrap: wrap; gap: 8px; border-bottom: 2px solid var(--line); margin-bottom: 30px; }
    .ziara-results-tab { display: inline-flex; align-items: center; gap: 8px; padding: 13px 20px; background: none; border: 0; border-bottom: 3px solid transparent; margin-bottom: -2px; font-family: var(--font-body); font-size: 13.5px; font-weight: 700; letter-spacing: .4px; color: var(--muted); cursor: pointer; transition: color .25s, border-color .25s; }
    .ziara-results-tab i { color: var(--maroon); font-size: 15px; }
    .ziara-results-tab:hover { color: var(--maroon); }
    .ziara-results-tab.border-indigo-500 { border-color: var(--maroon); color: var(--maroon); }
    .ziara-results-tab.text-indigo-600 { color: var(--maroon); }
    .ziara-results-tab.text-gray-500 { color: var(--muted); }
    .ziara-results-toolbar { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; margin-bottom: 28px; }
    .ziara-results-filters { display: flex; flex-wrap: wrap; gap: 10px; }
    .ziara-results-filters .ziara-btn { padding: 9px 16px; font-size: 12px; }
    .ziara-results-sort { display: flex; align-items: center; gap: 10px; font-size: 13.5px; color: var(--muted); }
    .ziara-results-sort select { padding: 10px 14px; border: 1.5px solid var(--line); border-radius: 10px; background: #fff; font-size: 13.5px; color: var(--ink); font-family: var(--font-body); }
    .ziara-results-wishlist { position: absolute; top: 12px; right: 12px; width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; border: 0; border-radius: 50%; background: rgba(255,255,255,.94); color: var(--maroon); cursor: pointer; box-shadow: var(--shadow-sm); transition: transform .25s, color .25s; }
    .ziara-results-wishlist:hover { transform: scale(1.08); color: var(--gold-dim); }
    .ziara-results-rating { display: flex; gap: 3px; margin-bottom: 10px; }
    .ziara-results-star { color: var(--gold); font-size: 14px; fill: currentColor; }
    .ziara-results-location { display: flex; align-items: center; gap: 7px; font-size: 13.5px; color: var(--muted); margin: 0 0 14px; }
    .ziara-results-location i { color: var(--maroon); font-size: 14px; }
    .ziara-results-amenities { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
    .ziara-results-card-footer { align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; }
    .ziara-results-price { display: flex; flex-direction: column; line-height: 1.3; }
    .ziara-results-price span { font-size: 11px; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); }
    .ziara-results-price small { font-size: 12px; color: var(--muted); }
    .ziara-results-empty { grid-column: 1 / -1; text-align: center; padding: 48px 28px; }
    .ziara-results-empty i { color: var(--maroon); font-size: 34px; }
    .ziara-results-empty h3 { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 24px; color: var(--maroon-deep); margin: 16px 0 8px; }
    .ziara-results-empty p { font-size: 15px; color: var(--muted); margin: 0 0 22px; }
    .ziara-results-more { display: flex; justify-content: center; margin-top: 34px; }
    .ziara-form-grid { display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 18px; align-items: end; }
    .ziara-field label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
    .ziara-input-wrap { position: relative; }
    .ziara-input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--maroon); font-size: 15px; pointer-events: none; }
    .ziara-input-wrap input { width: 100%; padding: 13px 14px 13px 40px; border: 1.5px solid var(--line); border-radius: 10px; font-size: 15px; color: var(--ink); font-family: var(--font-body); }
    .ziara-input-wrap input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-border); }
    .ziara-field-action .ziara-btn { white-space: nowrap; }
    .ziara-results-section-title { margin: 34px 0 22px; }
    .ziara-results-route-head { display: flex; align-items: center; gap: 14px; margin-bottom: 16px; }
    .ziara-results-route-head > div { display: flex; flex-direction: column; line-height: 1.35; }
    .ziara-results-route-head > div:last-child { text-align: right; }
    .ziara-results-route-head span { font-size: 11px; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); }
    .ziara-results-route-head strong { font-size: 16px; color: var(--maroon-deep); }
    .ziara-results-route-head small { font-size: 12px; color: var(--muted); }
    .ziara-results-route-line { flex: 1; display: flex; align-items: center; gap: 8px; color: var(--maroon); }
    .ziara-results-route-line span { flex: 1; height: 1px; background: var(--line); }
    .ziara-results-duration { position: absolute; left: 12px; bottom: 12px; display: inline-flex; align-items: center; gap: 6px; }
    .ziara-results-tour-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
    .ziara-results-rating-pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 999px; background: var(--gold-pale); color: var(--maroon); font-size: 12.5px; font-weight: 700; white-space: nowrap; }
    .ziara-results-rating-pill i { color: var(--gold-dim); font-size: 13px; fill: currentColor; }
    .ziara-results-tour-type { align-self: flex-start; margin-bottom: 12px; }
    .ziara-results-guide-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 26px; }
    .ziara-results-guide-title { display: flex; align-items: center; gap: 10px; font-family: 'Cormorant Garamond', Georgia, serif; font-size: 20px; font-weight: 700; color: var(--maroon-deep); margin: 0 0 12px; }
    .ziara-results-guide-title i { color: var(--maroon); font-size: 18px; }
    .ziara-results-newsletter { display: flex; flex-wrap: wrap; gap: 14px; justify-content: center; max-width: 520px; margin: 0 auto; }
    .ziara-results-newsletter input { flex: 1; min-width: 240px; padding: 14px 18px; border: 1.5px solid rgba(255,255,255,.34); border-radius: 10px; background: rgba(255,255,255,.1); color: #fff; font-size: 15px; font-family: var(--font-body); }
    .ziara-results-newsletter input::placeholder { color: rgba(255,255,255,.7); }
    .ziara-results-newsletter input:focus { outline: none; border-color: var(--gold); }
    @media (max-width: 992px) {
        .ziara-results-search, .ziara-form-grid { grid-template-columns: 1fr; }
        .ziara-results-guide-grid { grid-template-columns: 1fr; gap: 24px; }
    }
    @media (max-width: 700px) {
        .ziara-results-attractions, .ziara-results-package-features { grid-template-columns: 1fr; }
        .ziara-results-toolbar { align-items: flex-start; }
    }
</style>
@include('partials.ziara-page-hero', [
    'kicker' => 'Destination Discovery',
    'title'  => 'Explore <em>' . e($destination['name'] ?? 'East African Cities') . '</em>',
    'intro'  => 'Discover the beauty, culture, and unforgettable moments of East Africa\'s most inspiring destinations.',
])

<section class="zt-section zt-section--pale" id="destination-search">
    <div class="container">
        <div class="ziara-panel">
            <h2 class="ziara-panel__title">Refine Your Destination</h2>
            <form action="{{ route('destinations.east-african') }}" method="GET" class="ziara-results-search">
                <div class="ziara-results-field">
                    <label for="destination" class="ziara-results-label">Destination</label>
                    <div class="ziara-results-input-wrap">
                        <i data-lucide="map-pin" class="ziara-results-input-icon" aria-hidden="true"></i>
                        <input type="text" id="destination" name="destination" class="ziara-results-input" placeholder="Enter city or region" value="{{ $searchParams['destination'] ?? '' }}">
                    </div>
                </div>
                <div class="ziara-results-field">
                    <label for="date" class="ziara-results-label">Date</label>
                    <div class="ziara-results-input-wrap">
                        <i data-lucide="calendar" class="ziara-results-input-icon" aria-hidden="true"></i>
                        <input type="date" id="date" name="date" class="ziara-results-input" value="{{ $searchParams['date'] ?? '' }}">
                    </div>
                </div>
                <div class="ziara-results-submit">
                    <button type="submit" class="ziara-btn ziara-btn--gold">
                        <i data-lucide="search" class="w-5 h-5" aria-hidden="true"></i>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-split ziara-results-overview">
            <div class="ziara-deal__media ziara-results-destination-media">
                <img src="{{ asset('images/destinations/' . ($destination['image'] ?? 'default-destination.jpg')) }}" alt="{{ $destination['name'] ?? 'Destination' }}">
            </div>
            <div>
                <h2 class="ziara-panel__title">{{ $destination['name'] ?? 'Destination Name' }}</h2>
                <div class="ziara-results-facts">
                    <div class="ziara-deal__badge ziara-results-fact"><i data-lucide="map" aria-hidden="true"></i>{{ $destination['country'] ?? 'Country' }}</div>
                    <div class="ziara-deal__badge ziara-results-fact"><i data-lucide="users" aria-hidden="true"></i>{{ $destination['population'] ?? 'Population' }}</div>
                    <div class="ziara-deal__badge ziara-results-fact"><i data-lucide="languages" aria-hidden="true"></i>{{ $destination['language'] ?? 'Language' }}</div>
                    <div class="ziara-deal__badge ziara-results-fact"><i data-lucide="thermometer" aria-hidden="true"></i>{{ $destination['climate'] ?? 'Climate' }}</div>
                </div>
                <div class="ziara-prose ziara-results-copy">
                    <p>{{ $destination['description'] ?? 'Experience the vibrant culture, stunning landscapes, and warm hospitality of this East African gem. From bustling markets to serene natural attractions, this destination offers a perfect blend of adventure, relaxation, and cultural immersion. Explore historic sites, indulge in local cuisine, and create unforgettable memories in one of Africa\'s most beautiful regions.' }}</p>
                </div>
                <h3 class="ziara-panel__title ziara-results-subtitle">Top Attractions</h3>
                <div class="ziara-results-attractions">
                    @foreach($destination['attractions'] ?? ['National Park', 'Cultural Museum', 'Historic Market', 'Beautiful Beach', 'Mountain Range', 'Wildlife Reserve'] as $attraction)
                        <div class="ziara-checklist__item"><span class="ziara-checklist__icon"><i data-lucide="check" aria-hidden="true"></i></span><span>{{ $attraction }}</span></div>
                    @endforeach
                </div>
                <h3 class="ziara-panel__title ziara-results-subtitle">Best Time to Visit</h3>
                <p class="ziara-prose ziara-results-copy">{{ $destination['bestTimeToVisit'] ?? 'The dry season (June to October) offers the best weather for exploring the city and its surroundings. December to February is also pleasant with fewer crowds.' }}</p>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-results-tabs" id="tab-content">
            <nav class="ziara-results-tabbar" aria-label="Travel options">
                <button id="tab-hotels" class="tab-btn ziara-results-tab border-indigo-500 text-indigo-600" aria-current="page">
                    Hotels &amp; Accommodation
                </button>
                <button id="tab-flights" class="tab-btn ziara-results-tab border-transparent text-gray-500">
                    Flights
                </button>
                <button id="tab-tours" class="tab-btn ziara-results-tab border-transparent text-gray-500">
                    Tours &amp; Activities
                </button>
                <button id="tab-packages" class="tab-btn ziara-results-tab border-transparent text-gray-500">
                    Vacation Packages
                </button>
            </nav>

            <div class="tab-content" id="content-hotels">
                <div class="ziara-results-toolbar">
                    <div class="ziara-results-filters">
                        <button class="ziara-btn ziara-btn--outline"><i data-lucide="sliders" aria-hidden="true"></i> Filters</button>
                        <button class="ziara-btn ziara-btn--outline">Price Range</button>
                        <button class="ziara-btn ziara-btn--outline">Star Rating</button>
                    </div>
                    <div class="ziara-results-sort">
                        <span>Sort by:</span>
                        <select aria-label="Sort results">
                            <option value="recommended">Recommended</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="rating">Rating</option>
                        </select>
                    </div>
                </div>
                <div class="ziara-deals ziara-results-grid">
                    @forelse($hotels ?? [] as $hotel)
                        <div class="ziara-deal">
                            <div class="ziara-deal__media">
                                <img src="{{ asset('images/hotels/' . ($hotel['image'] ?? 'default-hotel.jpg')) }}" alt="{{ $hotel['name'] }}">
                                <button class="ziara-results-wishlist" aria-label="Add to wishlist"><i data-lucide="heart" aria-hidden="true"></i></button>
                            </div>
                            <div class="ziara-deal__body">
                                <div class="ziara-results-rating">
                                    @for($i = 0; $i < ($hotel['stars'] ?? 5); $i++)
                                        <i data-lucide="star" class="ziara-results-star" aria-hidden="true"></i>
                                    @endfor
                                </div>
                                <h3 class="ziara-deal__title">{{ $hotel['name'] ?? 'Hotel Name' }}</h3>
                                <p class="ziara-results-location"><i data-lucide="map-pin" aria-hidden="true"></i>{{ $hotel['location'] ?? 'Location' }}</p>
                                <div class="ziara-results-amenities">
                                    @foreach($hotel['amenities'] ?? ['Free WiFi', 'Pool'] as $amenity)
                                        <span class="ziara-deal__badge">{{ $amenity }}</span>
                                    @endforeach
                                </div>
                                <div class="ziara-deal__price ziara-results-card-footer">
                                    <div class="ziara-results-price">
                                        <span>From</span>
                                        <strong>{{ $hotel['currency'] ?? 'USD' }} {{ number_format($hotel['price'] ?? 100) }}</strong>
                                        <small>per night</small>
                                    </div>
                                    <a href="{{ route('hotels.select', ['hotel_id' => $hotel['id'] ?? 1]) }}" class="ziara-deal__btn">View Deal</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="ziara-panel ziara-results-empty">
                            <i data-lucide="hotel" aria-hidden="true"></i>
                            <h3>No Hotels Found</h3>
                            <p>We couldn't find any hotels in this destination matching your criteria.</p>
                            <button onclick="modifySearch()" class="ziara-btn ziara-btn--gold">Modify Search</button>
                        </div>
                    @endforelse
                </div>
                @if(count($hotels ?? []) > 9)
                    <div class="ziara-results-more">
                        <a href="{{ route('hotels.search.form', ['destination' => $destination['slug'] ?? 'destination']) }}" class="ziara-btn ziara-btn--outline">View All Hotels <i data-lucide="chevron-right" aria-hidden="true"></i></a>
                    </div>
                @endif
            </div>

            <div class="tab-content hidden" id="content-flights">
                <div class="ziara-panel ziara-results-flight-search">
                    <h3 class="ziara-panel__title">Search Flights to {{ $destination['name'] ?? 'Destination' }}</h3>
                    <form action="{{ route('flights.search') }}" method="GET" class="ziara-form-grid">
                        <input type="hidden" name="destination" value="{{ $destination['iata_code'] ?? '' }}">
                        <div class="ziara-field">
                            <label for="departure">From</label>
                            <div class="ziara-input-wrap"><i data-lucide="plane-takeoff" aria-hidden="true"></i><input type="text" id="departure" name="departure" placeholder="City or airport"></div>
                        </div>
                        <div class="ziara-field">
                            <label for="departure_date">Departure Date</label>
                            <div class="ziara-input-wrap"><i data-lucide="calendar" aria-hidden="true"></i><input type="date" id="departure_date" name="departure_date"></div>
                        </div>
                        <div class="ziara-field">
                            <label for="return_date">Return Date</label>
                            <div class="ziara-input-wrap"><i data-lucide="calendar" aria-hidden="true"></i><input type="date" id="return_date" name="return_date"></div>
                        </div>
                        <div class="ziara-field ziara-field-action"><button type="submit" class="ziara-btn ziara-btn--gold"><i data-lucide="search" aria-hidden="true"></i> Search Flights</button></div>
                    </form>
                </div>
                <h3 class="ziara-panel__title ziara-results-section-title">Popular Routes to {{ $destination['name'] ?? 'Destination' }}</h3>
                <div class="ziara-deals">
                    @foreach($popularRoutes ?? [
                        ['from' => 'Nairobi', 'from_code' => 'NBO', 'price' => 250, 'currency' => 'USD', 'airlines' => ['Kenya Airways', 'Ethiopian Airlines']],
                        ['from' => 'Dar es Salaam', 'from_code' => 'DAR', 'price' => 180, 'currency' => 'USD', 'airlines' => ['Air Tanzania', 'Kenya Airways']],
                        ['from' => 'Entebbe', 'from_code' => 'EBB', 'price' => 210, 'currency' => 'USD', 'airlines' => ['Uganda Airlines', 'RwandAir']],
                        ['from' => 'Kigali', 'from_code' => 'KGL', 'price' => 190, 'currency' => 'USD', 'airlines' => ['RwandAir', 'Kenya Airways']],
                        ['from' => 'Addis Ababa', 'from_code' => 'ADD', 'price' => 320, 'currency' => 'USD', 'airlines' => ['Ethiopian Airlines']],
                        ['from' => 'Zanzibar', 'from_code' => 'ZNZ', 'price' => 150, 'currency' => 'USD', 'airlines' => ['Air Tanzania', 'Precision Air']]
                    ] as $route)
                        <div class="ziara-deal">
                            <div class="ziara-deal__body">
                                <div class="ziara-results-route-head">
                                    <div><span>From</span><strong>{{ $route['from'] }}</strong><small>{{ $route['from_code'] }}</small></div>
                                    <div class="ziara-results-route-line"><span></span><i data-lucide="plane" aria-hidden="true"></i><span></span></div>
                                    <div><span>To</span><strong>{{ $destination['name'] ?? 'Destination' }}</strong><small>{{ $destination['iata_code'] ?? 'DST' }}</small></div>
                                </div>
                                <p class="ziara-deal__desc">Airlines: {{ implode(', ', $route['airlines']) }}</p>
                                <div class="ziara-deal__price ziara-results-card-footer">
                                    <div class="ziara-results-price"><span>From</span><strong>{{ $route['currency'] }} {{ number_format($route['price']) }}</strong></div>
                                    <a href="{{ route('flights.search', ['departure' => $route['from_code'], 'destination' => $destination['iata_code'] ?? '']) }}" class="ziara-deal__btn">Find Flights</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-content hidden" id="content-tours">
                <div class="ziara-deals">
                    @forelse($tours ?? [] as $tour)
                        <div class="ziara-deal">
                            <div class="ziara-deal__media">
                                <img src="{{ asset('images/tours/' . ($tour['image'] ?? 'default-tour.jpg')) }}" alt="{{ $tour['name'] ?? 'Tour Name' }}">
                                <span class="ziara-deal__badge ziara-results-duration"><i data-lucide="clock" aria-hidden="true"></i>{{ $tour['duration'] ?? '3 hours' }}</span>
                            </div>
                            <div class="ziara-deal__body">
                                <div class="ziara-results-tour-head"><h3 class="ziara-deal__title">{{ $tour['name'] ?? 'Tour Name' }}</h3><span class="ziara-results-rating-pill"><i data-lucide="star" aria-hidden="true"></i>{{ $tour['rating'] ?? '4.5' }}</span></div>
                                <span class="ziara-deal__badge ziara-results-tour-type">{{ $tour['type'] ?? 'City Tour' }}</span>
                                <p class="ziara-deal__desc">{{ $tour['description'] ?? 'Experience the beauty and culture of this amazing destination with our expert guides.' }}</p>
                                <div class="ziara-deal__price ziara-results-card-footer">
                                    <div class="ziara-results-price"><span>From</span><strong>{{ $tour['currency'] ?? 'USD' }} {{ number_format($tour['price'] ?? 65) }}</strong><small>per person</small></div>
                                    <a href="{{ route('tours.show', $tour['slug'] ?? $tour['id'] ?? 1) }}" class="ziara-deal__btn">View Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="ziara-panel ziara-results-empty">
                            <i data-lucide="map" aria-hidden="true"></i>
                            <h3>No Tours Found</h3>
                            <p>We couldn't find any tours in this destination matching your criteria.</p>
                            <button onclick="modifySearch()" class="ziara-btn ziara-btn--gold">Modify Search</button>
                        </div>
                    @endforelse
                </div>
                @if(count($tours ?? []) > 9)
                    <div class="ziara-results-more">
                        <a href="{{ route('tours.index', ['destination' => $destination['slug'] ?? 'destination']) }}" class="ziara-btn ziara-btn--outline">View All Tours <i data-lucide="chevron-right" aria-hidden="true"></i></a>
                    </div>
                @endif
            </div>

            <div class="tab-content hidden" id="content-packages">
                <div class="ziara-deals">
                    @forelse($packages ?? [] as $package)
                        <div class="ziara-deal">
                            <div class="ziara-deal__media">
                                <img src="{{ asset('images/packages/' . ($package['image'] ?? 'default-package.jpg')) }}" alt="{{ $package['name'] ?? 'Package Name' }}">
                                <span class="ziara-deal__badge ziara-results-duration"><i data-lucide="calendar" aria-hidden="true"></i>{{ $package['duration'] ?? '5 days' }}</span>
                            </div>
                            <div class="ziara-deal__body">
                                <h3 class="ziara-deal__title">{{ $package['name'] ?? 'Package Name' }}</h3>
                                <div class="ziara-results-package-features">
                                    @foreach($package['features'] ?? ['Hotel accommodation', 'Airport transfers', 'Daily breakfast', 'City tour included'] as $feature)
                                        <div class="ziara-checklist__item"><span class="ziara-checklist__icon"><i data-lucide="check" aria-hidden="true"></i></span><span>{{ $feature }}</span></div>
                                    @endforeach
                                </div>
                                <div class="ziara-deal__price ziara-results-card-footer">
                                    <div class="ziara-results-price"><span>From</span><strong>{{ $package['currency'] ?? 'USD' }} {{ number_format($package['price'] ?? 499) }}</strong><small>per person</small></div>
                                    <a href="{{ route('tours.show', $package['slug'] ?? $package['id'] ?? 1) }}" class="ziara-deal__btn">View Package</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="ziara-panel ziara-results-empty">
                            <i data-lucide="package" aria-hidden="true"></i>
                            <h3>No Packages Found</h3>
                            <p>We couldn't find any vacation packages for this destination matching your criteria.</p>
                            <button onclick="modifySearch()" class="ziara-btn ziara-btn--gold">Modify Search</button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-panel ziara-results-guide">
            <h2 class="ziara-panel__title">{{ $destination['name'] ?? 'Destination' }} Travel Guide</h2>
            <div class="ziara-results-guide-grid">
                <div>
                    <h3 class="ziara-results-guide-title"><i data-lucide="plane" aria-hidden="true"></i>Getting There &amp; Around</h3>
                    <div class="ziara-prose"><p>{{ $destination['gettingAround'] ?? 'The city is serviced by an international airport with regular flights from major East African cities. Once in the city, taxis, ride-sharing services, and public transportation are readily available. For exploring nearby attractions, car rentals or organized tours are recommended.' }}</p></div>
                </div>
                <div>
                    <h3 class="ziara-results-guide-title"><i data-lucide="hotel" aria-hidden="true"></i>Where to Stay</h3>
                    <div class="ziara-prose"><p>{{ $destination['whereToStay'] ?? 'Accommodation options range from luxury hotels and boutique guesthouses to budget-friendly hostels. The city center offers proximity to attractions and dining, while beachfront properties provide stunning views and a more relaxed atmosphere.' }}</p></div>
                </div>
                <div>
                    <h3 class="ziara-results-guide-title"><i data-lucide="utensils" aria-hidden="true"></i>Food &amp; Dining</h3>
                    <div class="ziara-prose"><p>{{ $destination['foodAndDining'] ?? 'Sample local East African cuisine featuring fresh seafood, grilled meats, and flavorful spices. From street food vendors to upscale restaurants, there are dining options for every budget. Don\'t miss trying traditional dishes and local beverages for an authentic experience.' }}</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section ziara-cta-band">
    <div class="container">
        <h2 class="ziara-cta-band__title">Get Travel Inspiration</h2>
        <p class="ziara-cta-band__desc">Subscribe to our newsletter for exclusive deals and travel tips for East African destinations.</p>
        <form class="ziara-results-newsletter">
            <input type="email" placeholder="Your email address" aria-label="Your email address">
            <button type="submit" class="ziara-btn ziara-btn--gold">Subscribe</button>
        </form>
    </div>
</section>

<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Tab functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                tabBtns.forEach(b => {
                    b.classList.remove('border-indigo-500', 'text-indigo-600');
                    b.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Add active class to clicked button
                this.classList.remove('border-transparent', 'text-gray-500');
                this.classList.add('border-indigo-500', 'text-indigo-600');
                
                // Hide all tab contents
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Show selected tab content
                const tabId = this.id.replace('tab-', 'content-');
                document.getElementById(tabId).classList.remove('hidden');
            });
        });
    });
    
    // Function to modify search
    function modifySearch() {
        // Scroll to search box
        document.querySelector('form[action="{{ route('destinations.east-african') }}"]').scrollIntoView({ behavior: 'smooth' });
    }
    
    // Toast notification functionality
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white transform transition-all duration-300 translate-y-20 opacity-0`;
        
        toast.innerHTML = `
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Initialize the Lucide icon in the toast
        lucide.createIcons();
        
        // Show toast with animation
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
        }, 100);
        
        // Hide toast after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            
            // Remove from DOM after animation completes
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
    }
    
    // Show success toast if redirected with success message
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('success') }}', 'success');
        });
    @endif
    
    // Show error toast if redirected with error message
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('error') }}', 'error');
        });
    @endif
</script>
@endsection
