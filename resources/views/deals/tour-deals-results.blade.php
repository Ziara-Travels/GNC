@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Tours & Experiences',
    'title'  => '<em>Signature</em> Tour Deals',
    'intro'  => 'Guided safaris, cultural journeys and coastal escapes, curated for small groups and private guiding.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Tour Deals', 'url' => route('deal.tour.results')],
    ],
])

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .ztr-results {
            background: var(--gold-pale);
            padding: 64px 0 88px;
        }
        .ztr-results .text-warning { color: var(--gold) !important; }
        .ztr-results__head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 22px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            padding: 28px 30px;
            margin-bottom: 26px;
        }
        .ztr-results__title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 34px;
            line-height: 1.15;
            color: var(--maroon-deep);
            margin: 0 0 6px;
        }
        .ztr-results__count {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--gold-dim);
            margin: 0;
        }
        .ztr-actions { display: flex; align-items: center; gap: 12px; }
        .ztr-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            font-family: var(--font-body);
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: var(--maroon);
            background: #fff;
            border: 1.5px solid var(--gold-border);
            border-radius: 50px;
            cursor: pointer;
            transition: background .3s, color .3s, border-color .3s, transform .3s;
        }
        .ztr-chip:hover { border-color: var(--gold); background: var(--gold-pale); transform: translateY(-2px); }
        .ztr-chip.filter-active {
            background: var(--maroon);
            border-color: var(--maroon);
            color: var(--gold-bright);
        }
        .ztr-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            padding: 18px 22px;
            margin-bottom: 34px;
        }
        .ztr-deals > .tour-card { display: block; height: 100%; }
        .ztr-deals > .tour-card > .ziara-deal { height: 100%; }
        .ztr-overlay {
            position: absolute;
            inset: auto 0 0 0;
            z-index: 2;
            padding: 18px 18px 16px;
            background: linear-gradient(to top, rgba(74,29,19,.92) 0%, rgba(74,29,19,.45) 55%, transparent 100%);
        }
        .ztr-overlay__place {
            display: block;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: rgba(255,255,255,.82);
        }
        .ztr-deal__name {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 700;
            color: var(--maroon-deep);
            margin: 0 0 14px;
            line-height: 1.25;
        }
        .ztr-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 20px;
            padding: 14px 0;
            margin-bottom: 14px;
            border-top: 1px dashed var(--line);
            border-bottom: 1px dashed var(--line);
        }
        .ztr-meta__item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: var(--muted);
        }
        .ztr-meta__item i { color: var(--gold); font-size: 12px; }
        .currency-usd { color: var(--maroon); font-weight: 700; }
        .currency-ksh { color: var(--gold-dim); font-weight: 700; }
        .tour-duration { color: var(--muted); }
        .tour-category-badge {
            color: var(--maroon);
            background: var(--gold-pale);
            border: 1px solid var(--gold-border);
        }
        .ztr-divider { color: var(--gold-border); }
        .ztr-empty {
            grid-column: 1 / -1;
            background: #fff;
            border: 1px dashed var(--gold-border);
            border-radius: 16px;
            padding: 56px 30px;
            text-align: center;
        }
        .ztr-empty h3 {
            font-family: var(--font-display);
            font-size: 26px;
            font-weight: 700;
            color: var(--maroon-deep);
            margin: 0 0 8px;
        }
        .ztr-empty p { color: var(--muted); font-size: 14.5px; margin: 0; }
        .ztr-empty__icon { font-size: 40px; color: var(--gold); margin-bottom: 14px; }
        .ztr-none {
            background:
                radial-gradient(760px 340px at 82% -10%, rgba(201,162,39,.18), transparent 62%),
                linear-gradient(135deg, var(--maroon-deep) 0%, var(--maroon) 100%);
            min-height: 58vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .ztr-none__icon {
            width: 108px;
            height: 108px;
            margin: 0 auto 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(243,210,74,.34);
            font-size: 40px;
            color: var(--gold-bright);
        }
        .ztr-none h2 {
            font-family: var(--font-display);
            font-size: 40px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 12px;
        }
        .ztr-none p { color: rgba(255,255,255,.78); font-size: 16.5px; margin: 0 0 30px; }
        .ztr-results .dropdown-menu {
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            padding: 8px;
        }
        .ztr-results .dropdown-item {
            border-radius: 10px;
            font-size: 14px;
            color: var(--ink);
            padding: 10px 14px;
        }
        .ztr-results .dropdown-item:hover,
        .ztr-results .dropdown-item:focus {
            background: var(--gold-pale);
            color: var(--maroon-deep);
        }
        .ztr-results .form-check-input { border-color: var(--gold-border); }
        .ztr-results .form-check-input:checked {
            background-color: var(--maroon);
            border-color: var(--maroon);
        }
        .ztr-results .form-check-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-border);
        }
        .ztr-results .form-check-label { color: var(--ink); }
        .ztr-results .form-range { accent-color: var(--maroon); }
        .ztr-results .modal-content {
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        .ztr-results .modal-header {
            background: var(--maroon-deep);
            border-bottom: 0;
            padding: 20px 26px;
        }
        .ztr-results .modal-title {
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 700;
            color: #fff;
        }
        .ztr-results .modal-body { padding: 26px; }
        .ztr-results .modal-footer {
            background: var(--gold-pale);
            border-top: 1px solid var(--line);
            padding: 18px 26px;
        }
        .ztr-results .modal-body .form-label {
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
        }
        .ztr-results .modal-footer .btn-secondary {
            background: #fff;
            color: var(--maroon);
            border: 1.5px solid var(--gold-border);
        }
        .ztr-results .modal-footer .btn-success {
            background: var(--maroon);
            border-color: var(--maroon);
        }
    </style>

    @if(isset($noToursFound) && $noToursFound)
        <!-- No Tours Found Section -->
        <div class="ztr-none">
            <div class="container">
                <div class="ztr-none__icon"><i class="fas fa-map-marked-alt"></i></div>
                <h2>No Tours Found</h2>
                <p>We couldn't find any tours matching your criteria.</p>
                <a href="{{ route('home') }}" class="ziara-btn ziara-btn--gold">
                    <i class="fas fa-search" aria-hidden="true"></i>Search Again
                </a>
            </div>
        </div>
    @else
        <!-- Tour Results Found -->
        <section class="ztr-results">
            <div class="container">
                <div class="ztr-results__head">
                    <div>
                        <h1 class="ztr-results__title">{{ $pageTitle ?? 'Tour & Activity Deals' }}</h1>
                        <p class="ztr-results__count">{{ isset($tours) ? count($tours) : 0 }} tours found</p>
                    </div>
                    <div class="ztr-actions">
                        <button class="ztr-chip" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-sliders" aria-hidden="true"></i>Filter
                        </button>
                        <div class="dropdown">
                            <button class="ztr-chip" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-arrow-down-a-z" aria-hidden="true"></i>Sort by
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="#" data-sort="price">Price (Low to High)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="price-desc">Price (High to Low)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="rating-desc">Rating (High to Low)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="duration">Duration (Short to Long)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tour Filter Pills -->
                <div class="ztr-filters">
                    <button class="ztr-chip filter-active" data-filter="all">All Tours</button>
                    <button class="ztr-chip" data-filter="adventure">Adventure</button>
                    <button class="ztr-chip" data-filter="cultural">Cultural</button>
                    <button class="ztr-chip" data-filter="wildlife">Wildlife</button>
                    <button class="ztr-chip" data-filter="scenic">Scenic</button>
                    <button class="ztr-chip" data-filter="beach">Beach</button>
                </div>

                <!-- Tour Cards -->
                <div class="ziara-deals ztr-deals tours-results">
                    @forelse($tours ?? [] as $tour)
                    <div class="tour-card"
                         data-category="{{ strtolower($tour->category ?? '') }}"
                         data-price="{{ $tour->price_usd ?? 0 }}"
                         data-rating="{{ $tour->rating ?? 0 }}"
                         data-duration="{{ $tour->duration_days ?? 0 }}">
                        <div class="ziara-deal">
                            <div class="ziara-deal__media">
                                <img src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $tour->city ?? 'default')) . '.jpg') }}"
                                    alt="{{ $tour->name ?? 'Tour' }}"
                                    onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                                <span class="ziara-deal__badge"><i class="fas fa-star" aria-hidden="true"></i>{{ number_format($tour->rating ?? 4.5, 1) }}</span>
                                <div class="ztr-overlay">
                                    <small class="ztr-overlay__place">{{ $tour->city ?? 'City' }}, {{ $tour->country ?? 'Country' }}</small>
                                </div>
                            </div>
                            <div class="ziara-deal__body">
                                <h5 class="ztr-deal__name">{{ $tour->name ?? 'Tour Name' }}</h5>

                                <div class="ztr-meta">
                                    <span class="tour-category-badge badge px-3 py-2"><i class="fas fa-tag me-1" aria-hidden="true"></i>{{ $tour->category ?? 'Category' }}</span>
                                    <span class="tour-duration"><i class="fas fa-clock me-1" aria-hidden="true"></i>{{ $tour->duration_days ?? '1' }} day{{ ($tour->duration_days ?? 1) > 1 ? 's' : '' }}</span>
                                </div>

                                <p class="ziara-deal__desc">{{ $tour->description ?? 'No description available.' }}</p>

                                <div class="ziara-deal__price" style="flex-wrap:wrap;gap:4px 12px;">
                                    <span class="currency-usd">USD {{ number_format($tour->price_usd ?? 45, 2) }}</span>
                                    <span class="ztr-divider">|</span>
                                    <span class="currency-ksh">KES {{ number_format($tour->price_ksh ?? 5814, 0) }}</span>
                                </div>

                                <form method="POST" action="{{ route('book.tour') }}">
                                    @csrf
                                    <input type="hidden" name="tour_data" value="{{ json_encode($tour) }}">
                                    <button type="submit" class="ziara-deal__btn" style="width:100%;">
                                        <i class="fas fa-ticket-alt" aria-hidden="true"></i>Book Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="ztr-empty">
                        <div class="ztr-empty__icon"><i class="fas fa-map-marked-alt" aria-hidden="true"></i></div>
                        <h3>No tours available at the moment</h3>
                        <p>Please check back later or try different search criteria.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Filter Modal -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filter Tours</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="tour-filter-form">
                                <!-- Price Range -->
                                <div class="mb-4">
                                    <label for="priceRange" class="form-label fw-bold">Price Range (USD)</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <span id="minPrice">$0</span>
                                        <input type="range" class="form-range" min="0" max="500" step="10" id="priceRange">
                                        <span id="maxPrice">$500</span>
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tour Duration</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="duration1" value="1">
                                            <label class="form-check-label" for="duration1">Day Trip (1 day)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="duration2" value="2-3">
                                            <label class="form-check-label" for="duration2">Short Trip (2-3 days)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="duration4" value="4-7">
                                            <label class="form-check-label" for="duration4">Medium Trip (4-7 days)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="duration8" value="8+">
                                            <label class="form-check-label" for="duration8">Long Trip (8+ days)</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Categories -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Tour Categories</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="catAdventure" value="adventure">
                                            <label class="form-check-label" for="catAdventure">Adventure</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="catWildlife" value="wildlife">
                                            <label class="form-check-label" for="catWildlife">Wildlife Safari</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="catCultural" value="cultural">
                                            <label class="form-check-label" for="catCultural">Cultural</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="catScenic" value="scenic">
                                            <label class="form-check-label" for="catScenic">Scenic</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="catBeach" value="beach">
                                            <label class="form-check-label" for="catBeach">Beach</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Countries -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Countries</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="countryKenya" value="kenya">
                                            <label class="form-check-label" for="countryKenya">Kenya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="countryTanzania" value="tanzania">
                                            <label class="form-check-label" for="countryTanzania">Tanzania</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="countryUganda" value="uganda">
                                            <label class="form-check-label" for="countryUganda">Uganda</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="countryRwanda" value="rwanda">
                                            <label class="form-check-label" for="countryRwanda">Rwanda</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Minimum Rating</label>
                                    <div class="d-flex align-items-center">
                                        <div id="ratingStars" class="me-2">
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="fas fa-star text-warning"></i>
                                            <i class="far fa-star text-warning"></i>
                                        </div>
                                        <span id="ratingValue">4.0</span>
                                        <input type="range" class="form-range ms-3" min="1" max="5" step="0.5" id="ratingRange" value="4" style="width: 100px;">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success" onclick="applyTourFilters()">Apply Filters</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle price range slider
        const priceRange = document.getElementById('priceRange');
        const maxPriceElement = document.getElementById('maxPrice');
        
        if (priceRange) {
            priceRange.addEventListener('input', function() {
                maxPriceElement.textContent = `$${parseInt(this.value)}`;
            });
        }
        
        // Handle rating slider
        const ratingRange = document.getElementById('ratingRange');
        const ratingValue = document.getElementById('ratingValue');
        const ratingStars = document.getElementById('ratingStars');
        
        if (ratingRange) {
            ratingRange.addEventListener('input', function() {
                const rating = parseFloat(this.value);
                ratingValue.textContent = rating.toFixed(1);
                
                // Update stars display
                ratingStars.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(rating)) {
                        ratingStars.innerHTML += '<i class="fas fa-star text-warning"></i>';
                    } else if (i - 0.5 <= rating) {
                        ratingStars.innerHTML += '<i class="fas fa-star-half-alt text-warning"></i>';
                    } else {
                        ratingStars.innerHTML += '<i class="far fa-star text-warning"></i>';
                    }
                }
            });
        }
        
        // Handle filters
        const filterButtons = document.querySelectorAll('[data-filter]');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active filter button
                filterButtons.forEach(btn => btn.classList.remove('filter-active'));
                this.classList.add('filter-active');
                
                const filterValue = this.getAttribute('data-filter');
                const tourCards = document.querySelectorAll('.tour-card');
                
                tourCards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        const category = card.getAttribute('data-category');
                        card.style.display = category.includes(filterValue) ? 'block' : 'none';
                    }
                });
            });
        });
        
        // Handle sorting
        const sortOptions = document.querySelectorAll('[data-sort]');
        sortOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                sortTours(this.getAttribute('data-sort'));
            });
        });
    });
    
    function sortTours(sortBy) {
        const tourResults = document.querySelector('.tours-results');
        if (!tourResults) return;
        
        const tours = Array.from(tourResults.children);
        
        tours.sort((a, b) => {
            if (sortBy === 'price') {
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            } else if (sortBy === 'price-desc') {
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            } else if (sortBy === 'rating-desc') {
                return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            } else if (sortBy === 'duration') {
                return parseFloat(a.dataset.duration) - parseFloat(b.dataset.duration);
            }
            return 0;
        });
        
        // Re-append sorted tours
        tours.forEach(tour => {
            tourResults.appendChild(tour);
        });
    }
    
    function applyTourFilters() {
        // Get filter values
        const maxPrice = document.getElementById('priceRange').value;
        const minRating = document.getElementById('ratingRange').value;
        
        // Get duration checkboxes
        const duration1 = document.getElementById('duration1').checked;
        const duration2To3 = document.getElementById('duration2').checked;
        const duration4To7 = document.getElementById('duration4').checked;
        const duration8Plus = document.getElementById('duration8').checked;
        
        // Get category checkboxes
        const catAdventure = document.getElementById('catAdventure').checked;
        const catWildlife = document.getElementById('catWildlife').checked;
        const catCultural = document.getElementById('catCultural').checked;
        const catScenic = document.getElementById('catScenic').checked;
        const catBeach = document.getElementById('catBeach').checked;
        
        // Get country checkboxes
        const countryKenya = document.getElementById('countryKenya').checked;
        const countryTanzania = document.getElementById('countryTanzania').checked;
        const countryUganda = document.getElementById('countryUganda').checked;
        const countryRwanda = document.getElementById('countryRwanda').checked;
        
        // Apply filters
        const tourCards = document.querySelectorAll('.tour-card');
        
        tourCards.forEach(card => {
            const price = parseFloat(card.getAttribute('data-price'));
            const rating = parseFloat(card.getAttribute('data-rating'));
            const duration = parseInt(card.getAttribute('data-duration'));
            const category = card.getAttribute('data-category').toLowerCase();
            
            // For country we would need to have data attributes on the cards
            // This is a simplified example
            
            let shouldShow = price <= maxPrice && rating >= minRating;
            
            // Filter by duration
            if (duration1 || duration2To3 || duration4To7 || duration8Plus) {
                shouldShow = shouldShow && (
                    (duration1 && duration === 1) ||
                    (duration2To3 && duration >= 2 && duration <= 3) ||
                    (duration4To7 && duration >= 4 && duration <= 7) ||
                    (duration8Plus && duration >= 8)
                );
            }
            
            // Filter by category
            if (catAdventure || catWildlife || catCultural || catScenic || catBeach) {
                shouldShow = shouldShow && (
                    (catAdventure && category.includes('adventure')) ||
                    (catWildlife && category.includes('wildlife')) ||
                    (catCultural && category.includes('cultural')) ||
                    (catScenic && category.includes('scenic')) ||
                    (catBeach && category.includes('beach'))
                );
            }
            
            // Show/hide card
            card.style.display = shouldShow ? 'block' : 'none';
        });
        
        // Close modal
        const filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
        filterModal.hide();
    }
</script>
@endsection
