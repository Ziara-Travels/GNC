@extends('layouts.main')

@section('content')
    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .tour-card {
            transition: all 0.3s ease;
        }
        
        .tour-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .no-tours-container {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-again-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .search-again-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .results-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .filter-active {
            background-color: #28a745;
            color: white;
            font-weight: 600;
        }

        .currency-ksh {
            color: #28a745;
            font-weight: bold;
        }

        .currency-usd {
            color: #17a2b8;
            font-weight: bold;
        }

        .tour-rating {
            color: #ffc107;
        }

        .tour-duration {
            color: #6c757d;
        }

        .tour-category-badge {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid #28a745;
        }
    </style>

    @if(isset($noToursFound) && $noToursFound)
        <!-- No Tours Found Section -->
        <div class="no-tours-container">
            <div class="container text-center py-5 text-white">
                <div class="mb-4">
                    <i class="fas fa-map-marked-alt text-white-50" style="font-size: 5rem;"></i>
                </div>
                <h2 class="display-4 fw-bold mb-4">No Tours Found</h2>
                <p class="lead mb-4">We couldn't find any tours matching your criteria.</p>
                <div class="mt-5">
                    <a href="{{ route('home') }}" class="search-again-btn">
                        <i class="fas fa-search me-2"></i>Search Again
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Tour Results Found -->
        <div class="container py-5">
            <div class="results-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h3 fw-bold mb-2">{{ $pageTitle ?? 'Tour & Activity Deals' }}</h1>
                        <p class="text-muted">{{ isset($tours) ? count($tours) : 0 }} tours found</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex justify-content-md-end align-items-center">
                            <div class="me-3">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterModal">
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
                                    <li><a class="dropdown-item" href="#" data-sort="rating-desc">Rating (High to Low)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="duration">Duration (Short to Long)</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tour Filter Pills -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm filter-active" data-filter="all">All Tours</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="adventure">Adventure</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="cultural">Cultural</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="wildlife">Wildlife</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="scenic">Scenic</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="beach">Beach</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tour Cards -->
            <div class="row g-4 tours-results">
                @forelse($tours ?? [] as $tour)
                <div class="col-md-4 mb-4 tour-card" 
                     data-category="{{ strtolower($tour->category ?? '') }}" 
                     data-price="{{ $tour->price_usd ?? 0 }}" 
                     data-rating="{{ $tour->rating ?? 0 }}"
                     data-duration="{{ $tour->duration_days ?? 0 }}">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="position-relative">
                            <img src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $tour->city ?? 'default')) . '.jpg') }}" 
                                class="card-img-top" 
                                alt="{{ $tour->name ?? 'Tour' }}" 
                                style="height: 200px; object-fit: cover;"
                                onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-success rounded-pill px-3 py-2 tour-rating">
                                    <i class="fas fa-star me-1"></i>{{ number_format($tour->rating ?? 4.5, 1) }}
                                </span>
                            </div>
                            <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <div>
                                    <small class="text-white-50">{{ $tour->city ?? 'City' }}, {{ $tour->country ?? 'Country' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-2">{{ $tour->name ?? 'Tour Name' }}</h5>
                            
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge tour-category-badge px-3 py-2">
                                    <i class="fas fa-tag me-1"></i>{{ $tour->category ?? 'Category' }}
                                </span>
                                <span class="tour-duration">
                                    <i class="fas fa-clock me-1"></i>{{ $tour->duration_days ?? '1' }} day{{ ($tour->duration_days ?? 1) > 1 ? 's' : '' }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted small mb-3">{{ $tour->description ?? 'No description available.' }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="currency-usd">USD {{ number_format($tour->price_usd ?? 45, 2) }}</span>
                                    <span class="text-muted mx-1">|</span>
                                    <span class="currency-ksh">KES {{ number_format($tour->price_ksh ?? 5814, 0) }}</span>
                                </div>
                                <form method="POST" action="{{ route('book.tour') }}">
                                    @csrf
                                    <input type="hidden" name="tour_data" value="{{ json_encode($tour) }}">
                                    <button type="submit" class="btn btn-outline-success rounded-pill px-4">
                                        <i class="fas fa-ticket-alt me-1"></i>Book Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-light rounded-3">
                        <h3>No tours available at the moment</h3>
                        <p class="text-muted">Please check back later or try different search criteria.</p>
                    </div>
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
                                <label class="form-label fw-bold">Price Range (USD)</label>
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
    @endif
@endsection

@section('scripts')
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
