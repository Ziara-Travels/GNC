@extends('layouts.main')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Flight Deals',
    'title'  => '<em>Curated</em> Flight Deals',
    'intro'  => 'Hand-picked fares from trusted carriers, ready to sort, filter and reserve in a few clicks.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Flight Deals', 'url' => route('deal.flight.results')],
    ],
])

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .zf-results {
            background: var(--gold-pale);
            padding: 64px 0 88px;
        }
        .zf-results__head {
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
        .zf-results__title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 34px;
            line-height: 1.15;
            color: var(--maroon-deep);
            margin: 0 0 6px;
        }
        .zf-results__count {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: var(--gold-dim);
            margin: 0;
        }
        .zf-actions { display: flex; align-items: center; gap: 12px; }
        .zf-chip {
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
        .zf-chip:hover { border-color: var(--gold); background: var(--gold-pale); transform: translateY(-2px); }
        .zf-chip.filter-active {
            background: var(--maroon);
            border-color: var(--maroon);
            color: var(--gold-bright);
        }
        .zf-filters {
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
        .zf-deals > .deal-card { display: block; height: 100%; }
        .zf-deals > .deal-card > .ziara-deal { height: 100%; }
        .zf-overlay {
            position: absolute;
            inset: auto 0 0 0;
            z-index: 2;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
            padding: 18px 18px 16px;
            background: linear-gradient(to top, rgba(74,29,19,.92) 0%, rgba(74,29,19,.45) 55%, transparent 100%);
        }
        .zf-overlay__city {
            font-family: var(--font-display);
            font-size: 21px;
            font-weight: 700;
            color: #fff;
            margin: 0;
            line-height: 1.2;
        }
        .zf-overlay__country {
            display: block;
            font-size: 11.5px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,.72);
        }
        .zf-overlay__airline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: var(--maroon-deep);
            background: var(--gold-bright);
            border-radius: 50px;
            white-space: nowrap;
        }
        .zf-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 20px;
            padding: 14px 0;
            margin-bottom: 14px;
            border-top: 1px dashed var(--line);
            border-bottom: 1px dashed var(--line);
        }
        .zf-meta__item {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            color: var(--muted);
        }
        .zf-meta__item i { color: var(--gold); font-size: 12px; }
        .zf-meta__item strong { color: var(--ink); font-weight: 600; }
        .zf-route {
            display: flex;
            align-items: center;
            gap: 9px;
            font-family: var(--font-display);
            font-size: 19px;
            font-weight: 700;
            color: var(--maroon-deep);
        }
        .zf-route i { color: var(--gold); font-size: 13px; }
        .zf-tag {
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
        .zf-tag--soft { color: var(--maroon); background: var(--gold-pale); border: 1px solid var(--gold-border); }
        .zf-tag--cabin { color: #fff; background: var(--maroon); }
        .zf-empty {
            grid-column: 1 / -1;
            background: #fff;
            border: 1px dashed var(--gold-border);
            border-radius: 16px;
            padding: 56px 30px;
            text-align: center;
        }
        .zf-empty h3 {
            font-family: var(--font-display);
            font-size: 26px;
            font-weight: 700;
            color: var(--maroon-deep);
            margin: 0 0 8px;
        }
        .zf-empty p { color: var(--muted); font-size: 14.5px; margin: 0; }
        .zf-empty__icon { font-size: 40px; color: var(--gold); margin-bottom: 14px; }
        .zf-none {
            background:
                radial-gradient(760px 340px at 82% -10%, rgba(201,162,39,.18), transparent 62%),
                linear-gradient(135deg, var(--maroon-deep) 0%, var(--maroon) 100%);
            min-height: 58vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .zf-none__icon {
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
        .zf-none h2 {
            font-family: var(--font-display);
            font-size: 40px;
            font-weight: 700;
            color: #fff;
            margin: 0 0 12px;
        }
        .zf-none p { color: rgba(255,255,255,.78); font-size: 16.5px; margin: 0 0 30px; }
        .zf-loading { animation: zf-fly 2.4s ease-in-out infinite alternate; }
        @keyframes zf-fly {
            0% { transform: translateX(-10px) rotate(-5deg); }
            100% { transform: translateX(10px) rotate(5deg); }
        }
        .zf-results .dropdown-menu {
            border: 1px solid var(--line);
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            padding: 8px;
        }
        .zf-results .dropdown-item {
            border-radius: 10px;
            font-size: 14px;
            color: var(--ink);
            padding: 10px 14px;
        }
        .zf-results .dropdown-item:hover,
        .zf-results .dropdown-item:focus {
            background: var(--gold-pale);
            color: var(--maroon-deep);
        }
    </style>

    @if(isset($noDealsFound) && $noDealsFound)
        <!-- No Deals Found Section -->
        <div class="zf-none">
            <div class="container">
                <div class="zf-none__icon zf-loading"><i class="fas fa-plane-departure"></i></div>
                <h2>No Flight Deals Found</h2>
                <p>We couldn't find any flight deals matching your criteria.</p>
                <a href="{{ route('home') }}" class="ziara-btn ziara-btn--gold">
                    <i class="fas fa-search" aria-hidden="true"></i>Search Again
                </a>
            </div>
        </div>
    @else
        <!-- Flight Deals Results Found -->
        <section class="zf-results">
            <div class="container">
                <div class="zf-results__head">
                    <div>
                        <h1 class="zf-results__title">{{ $pageTitle ?? 'Flight Deals' }}</h1>
                        <p class="zf-results__count">{{ isset($deals) ? count($deals) : 0 }} deals found</p>
                    </div>
                    <div class="zf-actions">
                        <button class="zf-chip" onclick="modifySearch()">
                            <i class="fas fa-sliders" aria-hidden="true"></i>Filter
                        </button>
                        <div class="dropdown">
                            <button class="zf-chip" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-arrow-down-a-z" aria-hidden="true"></i>Sort by
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item" href="#" data-sort="price">Price (Low to High)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="price-desc">Price (High to Low)</a></li>
                                <li><a class="dropdown-item" href="#" data-sort="discount">Highest Discount</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Filter Row -->
                <div class="zf-filters">
                    <button class="zf-chip filter-active" data-filter="all">All Destinations</button>
                    <button class="zf-chip" data-filter="kenya">Kenya</button>
                    <button class="zf-chip" data-filter="tanzania">Tanzania</button>
                    <button class="zf-chip" data-filter="uganda">Uganda</button>
                    <button class="zf-chip" data-filter="rwanda">Rwanda</button>
                    <button class="zf-chip" data-filter="ethiopia">Ethiopia</button>
                </div>

                <!-- Deals Cards -->
                <div class="ziara-deals zf-deals deals-results">
                    @forelse($deals ?? [] as $deal)
                    <div class="deal-card"
                         data-country="{{ strtolower($deal->destinationCountry ?? '') }}"
                         data-price="{{ $deal->price ?? 0 }}"
                         data-discount="{{ $deal->discountPercent ?? 0 }}">
                        <div class="ziara-deal">
                            <div class="ziara-deal__media">
                                <img src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $deal->destinationCity ?? 'default')) . '.jpg') }}"
                                    alt="{{ $deal->destinationCity ?? 'Destination' }}"
                                    onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                                <span class="ziara-deal__badge">{{ $deal->discountPercent ?? 0 }}% OFF</span>
                                <div class="zf-overlay">
                                    <div>
                                        <h5 class="zf-overlay__city">{{ $deal->destinationCity ?? 'City' }}</h5>
                                        <small class="zf-overlay__country">{{ $deal->destinationCountry ?? 'Country' }}</small>
                                    </div>
                                    <span class="zf-overlay__airline"><i class="fas fa-plane" aria-hidden="true"></i>{{ $deal->airline ?? 'Airline' }}</span>
                                </div>
                            </div>
                            <div class="ziara-deal__body">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:16px;">
                                    <span class="zf-tag zf-tag--soft"><i class="fas fa-map-marker-alt" aria-hidden="true"></i>{{ $deal->highlight ?? 'Highlight' }}</span>
                                    <span class="zf-tag zf-tag--cabin">{{ $deal->cabin ?? 'Economy' }}</span>
                                </div>

                                <div class="zf-route">
                                    <span>{{ $deal->originCity ?? 'Origin' }}</span>
                                    <i class="fas fa-arrow-right-long" aria-hidden="true"></i>
                                    <span>{{ $deal->destinationCity ?? 'Destination' }}</span>
                                </div>

                                <div class="zf-meta">
                                    <span class="zf-meta__item"><i class="fas fa-calendar-alt" aria-hidden="true"></i><strong>{{ $deal->departureDate ?? 'Date' }}</strong></span>
                                    <span class="zf-meta__item"><i class="fas fa-clock" aria-hidden="true"></i>{{ $deal->duration ?? 'Duration' }}</span>
                                    <span class="zf-meta__item"><i class="fas fa-plane-arrival" aria-hidden="true"></i>{{ isset($deal->stops) && $deal->stops == 0 ? 'Direct' : $deal->stops . ' Stop(s)' }}</span>
                                </div>

                                <p class="ziara-deal__desc">{{ $deal->description ?? 'No description available.' }}</p>

                                <div class="ziara-deal__price">
                                    <strong>{{ $deal->currency ?? 'KES' }} {{ number_format($deal->price ?? 0) }}</strong>
                                    <s>{{ $deal->currency ?? 'KES' }} {{ number_format($deal->originalPrice ?? 0) }}</s>
                                </div>

                                <form method="POST" action="{{ route('deal.flight.book') }}">
                                    @csrf
                                    <input type="hidden" name="flight_data" value="{{ json_encode($deal) }}">
                                    <button type="submit" class="ziara-deal__btn" style="width:100%;">
                                        <i class="fas fa-plane" aria-hidden="true"></i>Book Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="zf-empty">
                        <div class="zf-empty__icon"><i class="fas fa-plane-circle-exclamation" aria-hidden="true"></i></div>
                        <h3>No flight deals available at the moment</h3>
                        <p>Please check back later or try different search criteria.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle filters
        const filterButtons = document.querySelectorAll('[data-filter]');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active filter button
                filterButtons.forEach(btn => btn.classList.remove('filter-active'));
                this.classList.add('filter-active');
                
                const filterValue = this.getAttribute('data-filter');
                const dealCards = document.querySelectorAll('.deal-card');
                
                dealCards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        const country = card.getAttribute('data-country');
                        card.style.display = country.includes(filterValue) ? 'block' : 'none';
                    }
                });
            });
        });
        
        // Handle sorting
        const sortOptions = document.querySelectorAll('[data-sort]');
        sortOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                sortDeals(this.getAttribute('data-sort'));
            });
        });
    });
    
    function sortDeals(sortBy) {
        const dealResults = document.querySelector('.deals-results');
        if (!dealResults) return;
        
        const deals = Array.from(dealResults.children);
        
        deals.sort((a, b) => {
            if (sortBy === 'price') {
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            } else if (sortBy === 'price-desc') {
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            } else if (sortBy === 'discount') {
                return parseFloat(b.dataset.discount) - parseFloat(a.dataset.discount);
            }
            return 0;
        });
        
        // Re-append sorted deals
        deals.forEach(deal => {
            dealResults.appendChild(deal);
        });
    }
    
    function modifySearch() {
        // Redirect to home page for new search or show filter modal
        window.location.href = '{{ route('home') }}';
    }
</script>
@endsection
