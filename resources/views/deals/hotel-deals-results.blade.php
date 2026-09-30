@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Hotel Deals',
    'title'  => '<em>Handpicked</em> Hotel Deals',
    'intro'  => 'Signature stays and lodges across East Africa, with negotiated rates and instant confirmation.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Hotel Deals', 'url' => route('deal.hotel.results')],
    ],
])

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .zh-results {
            background: var(--gold-pale);
            padding: 64px 0 88px;
        }
        .zh-results__head {
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
        .zh-results__title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 34px;
            line-height: 1.15;
            color: var(--maroon-deep);
            margin: 0 0 6px;
        }
        .zh-results__count {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--gold-dim);
            margin: 0;
        }
        .zh-actions { display: flex; align-items: center; gap: 12px; }
        .zh-chip {
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
        .zh-chip:hover { border-color: var(--gold); background: var(--gold-pale); transform: translateY(-2px); }
        .zh-chip.filter-active {
            background: var(--maroon);
            border-color: var(--maroon);
            color: var(--gold-bright);
        }
        .zh-filters {
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
        .zh-deals > .hotel-deal-card { display: block; height: 100%; }
        .zh-deals > .hotel-deal-card > .ziara-deal { height: 100%; }
        .zh-overlay {
            position: absolute;
            inset: auto 0 0 0;
            z-index: 2;
            padding: 18px 18px 16px;
            background: linear-gradient(to top, rgba(74,29,19,.92) 0%, rgba(74,29,19,.45) 55%, transparent 100%);
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
        }
        .zh-overlay__name {
            font-family: var(--font-display);
            font-size: 21px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            line-height: 1.2;
        }
        .zh-overlay__place {
            display: block;
            font-size: 11.5px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,.72);
        }
        .zh-overlay__stars {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            padding: 6px 11px;
            border-radius: 50px;
            background: var(--gold-bright);
            color: var(--maroon-deep);
            font-size: 10.5px;
            white-space: nowrap;
        }
        .zh-wishlist {
            position: absolute;
            top: 14px;
            left: 14px;
            z-index: 3;
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            background: rgba(255,255,255,.94);
            color: var(--maroon);
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(74,29,19,.22);
            transition: background .25s, color .25s, transform .25s;
        }
        .zh-wishlist:hover { background: #fff; transform: scale(1.08); }
        .zh-wishlist.active { background: var(--maroon); color: var(--gold-bright); }
        .zh-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 20px;
            padding: 14px 0;
            margin-bottom: 14px;
            border-top: 1px dashed var(--line);
            border-bottom: 1px dashed var(--line);
        }
        .zh-meta__item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: var(--muted);
        }
        .zh-meta__item i { color: var(--gold); font-size: 12px; }
        .zh-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .7px;
            text-transform: uppercase;
            border-radius: 50px;
        }
        .zh-tag--soft { color: var(--maroon); background: var(--gold-pale); border: 1px solid var(--gold-border); }
        .zh-tag--type { color: #fff; background: var(--maroon); }
        .zh-stars { color: var(--gold); }
        .zh-empty {
            grid-column: 1 / -1;
            background: #fff;
            border: 1px dashed var(--gold-border);
            border-radius: 16px;
            padding: 56px 30px;
            text-align: center;
        }
        .zh-empty h3 {
            font-family: var(--font-display);
            font-size: 26px;
            font-weight: 700;
            color: var(--maroon-deep);
            margin: 0 0 8px;
        }
        .zh-empty p { color: var(--muted); font-size: 14.5px; margin: 0; }
        .zh-empty__icon { font-size: 40px; color: var(--gold); margin-bottom: 14px; }
        .zh-none {
            background:
                radial-gradient(760px 340px at 82% -10%, rgba(201,162,39,.18), transparent 62%),
                linear-gradient(135deg, var(--maroon-deep) 0%, var(--maroon) 100%);
            min-height: 58vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .zh-none__icon {
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
        .zh-none h2 {
            font-family: var(--font-display);
            font-size: 40px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 12px;
        }
        .zh-none p { color: rgba(255,255,255,.78); font-size: 16.5px; margin: 0 0 30px; }
        .zh-results .dropdown-menu {
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            padding: 8px;
        }
        .zh-results .dropdown-item {
            border-radius: 10px;
            font-size: 14px;
            color: var(--ink);
            padding: 10px 14px;
        }
        .zh-results .dropdown-item:hover,
        .zh-results .dropdown-item:focus {
            background: var(--gold-pale);
            color: var(--maroon-deep);
        }
        .zh-results .price-slider { background: var(--gold-border); opacity: 1; }
        .zh-results .price-slider::-webkit-slider-thumb { background: var(--maroon); }
        .zh-results .price-slider::-moz-range-thumb { background: var(--maroon); }
        .zh-results .form-check-input { border-color: var(--gold-border); }
        .zh-results .form-check-input:checked {
            background-color: var(--maroon);
            border-color: var(--maroon);
        }
        .zh-results .form-check-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-border);
        }
        .zh-results .form-check-label { color: var(--ink); }
        .zh-results .form-range { accent-color: var(--maroon); }
        .zh-results .modal-content {
            border: 1px solid var(--line);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        .zh-results .modal-header {
            background: var(--maroon-deep);
            border-bottom: 0;
            padding: 20px 26px;
        }
        .zh-results .modal-title {
            font-family: var(--font-display);
            font-size: 24px;
            font-weight: 700;
            color: #fff;
        }
        .zh-results .modal-body { padding: 26px; }
        .zh-results .modal-footer {
            background: var(--gold-pale);
            border-top: 1px solid var(--line);
            padding: 18px 26px;
        }
        .zh-results .modal-body .form-label {
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
        }
        .zh-results .modal-footer .btn-secondary {
            background: #fff;
            color: var(--maroon);
            border: 1.5px solid var(--gold-border);
        }
        .zh-results .modal-footer .btn-primary {
            background: var(--maroon);
            border-color: var(--maroon);
        }
    </style>

    @if(isset($noDealsFound) && $noDealsFound)
        <!-- No Deals Found Section -->
        <div class="zh-none">
            <div class="container">
                <div class="zh-none__icon"><i class="fas fa-hotel"></i></div>
                <h2>No Hotel Deals Found</h2>
                <p>We couldn't find any hotel deals matching your criteria.</p>
                <a href="{{ route('home') }}" class="ziara-btn ziara-btn--gold">
                    <i class="fas fa-search" aria-hidden="true"></i>Search Again
                </a>
            </div>
        </div>
    @else
        <!-- Hotel Deals Results Found -->
        <section class="zh-results">
            <div class="container">
                <div class="zh-results__head">
                    <div>
                        <h1 class="zh-results__title">{{ $pageTitle ?? 'Hotel Deals' }}</h1>
                        <p class="zh-results__count">{{ isset($deals) ? count($deals) : 0 }} hotels found</p>
                    </div>
                    <div class="zh-actions">
                        <button class="zh-chip" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-sliders" aria-hidden="true"></i>Filter
                        </button>
                        <div class="dropdown">
                            <button class="zh-chip" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-arrow-down-a-z" aria-hidden="true"></i>Sort by
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="#" data-sort="price">Price (Low to High)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="price-desc">Price (High to Low)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="stars-desc">Stars (High to Low)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="discount">Highest Discount</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Filter Pills Row -->
                <div class="zh-filters">
                    <button class="zh-chip filter-active" data-filter="all">All Properties</button>
                    <button class="zh-chip" data-filter="luxury">Luxury</button>
                    <button class="zh-chip" data-filter="boutique">Boutique</button>
                    <button class="zh-chip" data-filter="resort">Resort</button>
                    <button class="zh-chip" data-filter="business">Business</button>
                </div>

                <!-- Hotel Deals Cards -->
                <div class="ziara-deals zh-deals deals-results">
                    @forelse($deals ?? [] as $deal)
                    <div class="hotel-deal-card"
                         data-type="{{ strtolower($deal->hotelType ?? '') }}"
                         data-price="{{ $deal->price ?? 0 }}"
                         data-stars="{{ $deal->stars ?? 0 }}"
                         data-discount="{{ $deal->discountPercent ?? 0 }}">
                        <div class="ziara-deal">
                            <div class="ziara-deal__media">
                                <img src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $deal->city ?? 'default')) . '.jpg') }}"
                                    alt="{{ $deal->hotelName ?? 'Hotel' }}"
                                    onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                                <span class="ziara-deal__badge">{{ $deal->discountPercent ?? 0 }}% OFF</span>
                                <button class="zh-wishlist" onclick="toggleWishlist(this)">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <div class="zh-overlay">
                                    <div>
                                        <h5 class="zh-overlay__name">{{ $deal->hotelName ?? 'Hotel Name' }}</h5>
                                        <small class="zh-overlay__place">{{ $deal->city ?? 'City' }}, {{ $deal->country ?? 'Country' }}</small>
                                    </div>
                                    <span class="zh-overlay__stars">
                                        @for($i = 0; $i < ($deal->stars ?? 0); $i++)
                                        <i class="fas fa-star"></i>
                                        @endfor
                                    </span>
                                </div>
                            </div>
                            <div class="ziara-deal__body">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
                                    <span class="zh-tag zh-tag--soft"><i class="fas fa-award" aria-hidden="true"></i>{{ $deal->highlight ?? 'Highlight' }}</span>
                                    <span class="zh-tag zh-tag--type">{{ $deal->hotelType ?? 'Hotel Type' }}</span>
                                </div>

                                <div class="zh-meta">
                                    <span class="zh-meta__item"><i class="fas fa-location-dot" aria-hidden="true"></i><strong>{{ $deal->location ?? 'Location' }}</strong></span>
                                    <span class="zh-meta__item"><i class="fas fa-calendar-alt" aria-hidden="true"></i>From {{ $deal->availableDate ?? 'Today' }}</span>
                                    <span class="zh-meta__item"><i class="fas fa-utensils" aria-hidden="true"></i>{{ $deal->meals ?? 'Meals' }}</span>
                                    <span class="zh-meta__item"><i class="fas fa-wifi" aria-hidden="true"></i>{{ $deal->wifi ?? 'WiFi' }}</span>
                                </div>

                                <p class="ziara-deal__desc">{{ $deal->description ?? 'No description available.' }}</p>

                                <div class="ziara-deal__price">
                                    <strong>{{ $deal->currency ?? 'KES' }} {{ number_format($deal->price ?? 0) }}</strong>
                                    <s>{{ $deal->currency ?? 'KES' }} {{ number_format($deal->originalPrice ?? 0) }}</s>
                                </div>

                                <form method="POST" action="{{ route('deal.hotel.book') }}">
                                    @csrf
                                    <input type="hidden" name="hotel_data" value="{{ json_encode($deal) }}">
                                    <button type="submit" class="ziara-deal__btn" style="width:100%;">
                                        <i class="fas fa-key" aria-hidden="true"></i>Book Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="zh-empty">
                        <div class="zh-empty__icon"><i class="fas fa-hotel" aria-hidden="true"></i></div>
                        <h3>No hotel deals available at the moment</h3>
                        <p>Please check back later or try different search criteria.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Hotel Deals</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="hotel-filter-form">
                            <!-- Price Range -->
                            <div class="mb-4">
                                <label for="priceRange" class="form-label fw-bold">Price Range</label>
                                <div class="d-flex align-items-center gap-3">
                                    <span id="minPrice">KES 5,000</span>
                                    <input type="range" class="form-range price-slider" min="5000" max="100000" step="1000" id="priceRange">
                                    <span id="maxPrice">KES 100,000</span>
                                </div>
                            </div>

                            <!-- Star Rating -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Star Rating</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="star5" value="5">
                                        <label class="form-check-label" for="star5">
                                            <div class="stars-container zh-stars">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="star4" value="4">
                                        <label class="form-check-label" for="star4">
                                            <div class="stars-container zh-stars">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="star3" value="3">
                                        <label class="form-check-label" for="star3">
                                            <div class="stars-container zh-stars">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Hotel Type -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Hotel Type</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="typeLuxury" value="luxury">
                                        <label class="form-check-label" for="typeLuxury">Luxury</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="typeBoutique" value="boutique">
                                        <label class="form-check-label" for="typeBoutique">Boutique</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="typeResort" value="resort">
                                        <label class="form-check-label" for="typeResort">Resort</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="typeBusiness" value="business">
                                        <label class="form-check-label" for="typeBusiness">Business</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Meals -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Meals</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="mealBreakfast" value="breakfast">
                                        <label class="form-check-label" for="mealBreakfast">Breakfast Included</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="mealHalfBoard" value="half">
                                        <label class="form-check-label" for="mealHalfBoard">Half Board</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="mealFullBoard" value="full">
                                        <label class="form-check-label" for="mealFullBoard">Full Board</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="mealAllInclusive" value="all">
                                        <label class="form-check-label" for="mealAllInclusive">All-Inclusive</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Cities/Countries -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Destination</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="destKenya" value="kenya">
                                        <label class="form-check-label" for="destKenya">Kenya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="destTanzania" value="tanzania">
                                        <label class="form-check-label" for="destTanzania">Tanzania</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="destUganda" value="uganda">
                                        <label class="form-check-label" for="destUganda">Uganda</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="destRwanda" value="rwanda">
                                        <label class="form-check-label" for="destRwanda">Rwanda</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="applyFilters()" style="background-color: var(--maroon); border-color: var(--maroon);">Apply Filters</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle price range slider
        const priceRange = document.getElementById('priceRange');
        const minPriceElement = document.getElementById('minPrice');
        const maxPriceElement = document.getElementById('maxPrice');
        
        if (priceRange) {
            priceRange.addEventListener('input', function() {
                maxPriceElement.textContent = `KES ${parseInt(this.value).toLocaleString()}`;
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
                const hotelCards = document.querySelectorAll('.hotel-deal-card');
                
                hotelCards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        const hotelType = card.getAttribute('data-type');
                        card.style.display = hotelType.includes(filterValue) ? 'block' : 'none';
                    }
                });
            });
        });
        
        // Handle sorting
        const sortOptions = document.querySelectorAll('[data-sort]');
        sortOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                sortHotels(this.getAttribute('data-sort'));
            });
        });
    });
    
    function sortHotels(sortBy) {
        const hotelResults = document.querySelector('.deals-results');
        if (!hotelResults) return;
        
        const hotels = Array.from(hotelResults.children);
        
        hotels.sort((a, b) => {
            if (sortBy === 'price') {
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            } else if (sortBy === 'price-desc') {
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            } else if (sortBy === 'stars-desc') {
                return parseFloat(b.dataset.stars) - parseFloat(a.dataset.stars);
            } else if (sortBy === 'discount') {
                return parseFloat(b.dataset.discount) - parseFloat(a.dataset.discount);
            }
            return 0;
        });
        
        // Re-append sorted hotels
        hotels.forEach(hotel => {
            hotelResults.appendChild(hotel);
        });
    }
    
    function toggleWishlist(button) {
        button.classList.toggle('active');
        
        // You can implement actual wishlist functionality here
        // e.g., make an AJAX call to add/remove from wishlist
    }
    
    function applyFilters() {
        // Get all filter values
        const priceMax = document.getElementById('priceRange').value;
        const star5 = document.getElementById('star5').checked;
        const star4 = document.getElementById('star4').checked;
        const star3 = document.getElementById('star3').checked;
        
        // Get hotel types
        const typeLuxury = document.getElementById('typeLuxury').checked;
        const typeBoutique = document.getElementById('typeBoutique').checked;
        const typeResort = document.getElementById('typeResort').checked;
        const typeBusiness = document.getElementById('typeBusiness').checked;
        
        // Get destinations
        const destKenya = document.getElementById('destKenya').checked;
        const destTanzania = document.getElementById('destTanzania').checked;
        const destUganda = document.getElementById('destUganda').checked;
        const destRwanda = document.getElementById('destRwanda').checked;
        
        // Filter hotels based on criteria
        const hotelCards = document.querySelectorAll('.hotel-deal-card');
        
        hotelCards.forEach(card => {
            const price = parseFloat(card.getAttribute('data-price'));
            const stars = parseFloat(card.getAttribute('data-stars'));
            const type = card.getAttribute('data-type').toLowerCase();
            
            let shouldShow = price <= priceMax;
            
            // Filter by stars
            if (star5 || star4 || star3) {
                shouldShow = shouldShow && (
                    (star5 && stars === 5) ||
                    (star4 && stars === 4) ||
                    (star3 && stars === 3)
                );
            }
            
            // Filter by type
            if (typeLuxury || typeBoutique || typeResort || typeBusiness) {
                shouldShow = shouldShow && (
                    (typeLuxury && type.includes('luxury')) ||
                    (typeBoutique && type.includes('boutique')) ||
                    (typeResort && type.includes('resort')) ||
                    (typeBusiness && type.includes('business'))
                );
            }
            
            // Filter by destination
            // This would need to be modified to check the actual country data
            // For now, just an example
            
            card.style.display = shouldShow ? 'block' : 'none';
        });
        
        // Close modal
        const filterModal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
        filterModal.hide();
    }
</script>
@endsection
