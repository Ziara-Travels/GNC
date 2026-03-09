@extends('layouts.main')

@section('content')
    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .deal-card {
            transition: all 0.3s ease;
        }
        
        .deal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .no-deals-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-again-btn {
            background: linear-gradient(45deg, #ffc107, #ffeb3b);
            border: none;
            color: #212529;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .search-again-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
            color: #212529;
        }
        
        .loading-airplane {
            animation: fly 2s ease-in-out infinite alternate;
        }
        
        @keyframes fly {
            0% { transform: translateX(-10px) rotate(-5deg); }
            100% { transform: translateX(10px) rotate(5deg); }
        }
        
        .results-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .flight-deal-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .flight-deal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .flight-deal-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(45deg, #e53935, #ff5252);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }
        
        @keyframes pulse-deal {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .pulse-animation {
            animation: pulse-deal 2s infinite ease-in-out;
        }

        .filter-active {
            background-color: #ffc107;
            color: #212529;
            font-weight: 600;
        }
    </style>

    @if(isset($noDealsFound) && $noDealsFound)
        <!-- No Deals Found Section -->
        <div class="no-deals-container">
            <div class="container text-center py-5 text-white">
                <div class="mb-4">
                    <i class="fas fa-plane text-white-50" style="font-size: 5rem;"></i>
                </div>
                <h2 class="display-4 fw-bold mb-4">No Flight Deals Found</h2>
                <p class="lead mb-4">We couldn't find any flight deals matching your criteria.</p>
                <div class="mt-5">
                    <a href="{{ route('home') }}" class="search-again-btn">
                        <i class="fas fa-search me-2"></i>Search Again
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Flight Deals Results Found -->
        <div class="container py-5">
            <div class="results-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h3 fw-bold mb-2">{{ $pageTitle ?? 'Flight Deals' }}</h1>
                        <p class="text-muted">{{ isset($deals) ? count($deals) : 0 }} deals found</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex justify-content-md-end align-items-center">
                            <div class="me-3">
                                <button class="btn btn-sm btn-outline-secondary" onclick="modifySearch()">
                                    <i class="fas fa-filter me-1"></i>Filter
                                </button>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-sort me-1"></i>Sort by
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                                    <li><a class="dropdown-item" href="#" data-sort="price">Price (Low to High)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="price-desc">Price (High to Low)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="discount">Highest Discount</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter Row -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm filter-active" data-filter="all">All Destinations</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="kenya">Kenya</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="tanzania">Tanzania</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="uganda">Uganda</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="rwanda">Rwanda</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="ethiopia">Ethiopia</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Deals Cards -->
            <div class="row g-4 deals-results">
                @forelse($deals ?? [] as $deal)
                <div class="col-md-4 mb-4 deal-card" 
                     data-country="{{ strtolower($deal->destinationCountry ?? '') }}" 
                     data-price="{{ $deal->price ?? 0 }}" 
                     data-discount="{{ $deal->discountPercent ?? 0 }}">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="position-relative">
                            <img src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $deal->destinationCity ?? 'default')) . '.jpg') }}" 
                                class="card-img-top" 
                                alt="{{ $deal->destinationCity ?? 'Destination' }}" 
                                style="height: 200px; object-fit: cover;"
                                onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-danger rounded-pill px-3 py-2 discount-badge">{{ $deal->discountPercent ?? 0 }}% OFF</span>
                            </div>
                            <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="text-white mb-0">{{ $deal->destinationCity ?? 'City' }}</h5>
                                        <small class="text-white-50">{{ $deal->destinationCountry ?? 'Country' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary rounded-pill">{{ $deal->airline ?? 'Airline' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-light text-dark"><i class="fas fa-map-marker-alt me-1"></i> {{ $deal->highlight ?? 'Highlight' }}</span>
                                <span class="badge bg-success text-white">{{ $deal->cabin ?? 'Economy' }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fs-6 fw-semibold">{{ $deal->originCity ?? 'Origin' }}</span>
                                        <i class="fas fa-arrow-right mx-1 text-muted" style="font-size: 12px;"></i>
                                        <span class="fs-6 fw-semibold">{{ $deal->destinationCity ?? 'Destination' }}</span>
                                    </div>
                                    <small class="text-muted">{{ $deal->departureDate ?? 'Date' }}</small>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="d-flex align-items-center me-3">
                                        <i class="fas fa-clock text-muted me-1" style="font-size: 12px;"></i>
                                        <small class="text-muted">{{ $deal->duration ?? 'Duration' }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-plane-arrival text-muted me-1" style="font-size: 12px;"></i>
                                        <small class="text-muted">{{ isset($deal->stops) && $deal->stops == 0 ? 'Direct' : $deal->stops . ' Stop(s)' }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="card-text text-muted small mb-3">{{ $deal->description ?? 'No description available.' }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fs-4 fw-bold text-warning">{{ $deal->currency ?? 'KES' }} {{ number_format($deal->price ?? 0) }}</span>
                                    <small class="text-muted text-decoration-line-through ms-2">{{ $deal->currency ?? 'KES' }} {{ number_format($deal->originalPrice ?? 0) }}</small>
                                </div>
                                <form method="POST" action="{{ route('book.deal-flight') }}">
                                    @csrf
                                    <input type="hidden" name="flight_data" value="{{ json_encode($deal) }}">
                                    <button type="submit" class="btn btn-warning rounded-pill px-4">
                                        <i class="fas fa-plane me-1"></i>Book Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-light rounded-3">
                        <h3>No flight deals available at the moment</h3>
                        <p class="text-muted">Please check back later or try different search criteria.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    @endif
@endsection

@section('scripts')
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
