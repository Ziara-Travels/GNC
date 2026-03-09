@extends('layouts.main')

@section('content')
    <!-- Include required CSS and JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        .hotel-deal-card {
            transition: all 0.3s ease;
        }
        
        .hotel-deal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .no-deals-container {
            background: linear-gradient(135deg, #6a75ca 0%, #8f95d3 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-again-btn {
            background: linear-gradient(45deg, #6a75ca, #8f95d3);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        
        .search-again-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(106, 117, 202, 0.4);
        }
        
        .results-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .filter-active {
            background-color: #6a75ca;
            color: white;
            font-weight: 600;
        }

        .stars-container {
            color: #ffc107;
        }
        
        .price-slider {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            border-radius: 5px;
            background: #d7dcdf;
            outline: none;
            opacity: 0.7;
            -webkit-transition: .2s;
            transition: opacity .2s;
        }

        .price-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #6a75ca;
            cursor: pointer;
        }

        .wishlist-btn {
            background-color: white;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .wishlist-btn:hover {
            background-color: #f8f9fa;
            transform: scale(1.05);
        }

        .wishlist-btn.active {
            background-color: #ff5a5f;
            color: white;
        }

        .wishlist-btn.active:hover {
            background-color: #ff3b41;
        }
    </style>

    @if(isset($noDealsFound) && $noDealsFound)
        <!-- No Deals Found Section -->
        <div class="no-deals-container">
            <div class="container text-center py-5 text-white">
                <div class="mb-4">
                    <i class="fas fa-hotel text-white-50" style="font-size: 5rem;"></i>
                </div>
                <h2 class="display-4 fw-bold mb-4">No Hotel Deals Found</h2>
                <p class="lead mb-4">We couldn't find any hotel deals matching your criteria.</p>
                <div class="mt-5">
                    <a href="{{ route('home') }}" class="search-again-btn">
                        <i class="fas fa-search me-2"></i>Search Again
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Hotel Deals Results Found -->
        <div class="container py-5">
            <div class="results-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h3 fw-bold mb-2">{{ $pageTitle ?? 'Hotel Deals' }}</h1>
                        <p class="text-muted">{{ isset($deals) ? count($deals) : 0 }} hotels found</p>
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
                                    <li><a class="dropdown-item" href="#" data-sort="stars-desc">Stars (High to Low)</a></li>
                                    <li><a class="dropdown-item" href="#" data-sort="discount">Highest Discount</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter Pills Row -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-3 bg-white rounded-3 shadow-sm">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-sm filter-active" data-filter="all">All Properties</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="luxury">Luxury</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="boutique">Boutique</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="resort">Resort</button>
                            <button class="btn btn-sm btn-outline-secondary" data-filter="business">Business</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hotel Deals Cards -->
            <div class="row g-4 deals-results">
                @forelse($deals ?? [] as $deal)
                <div class="col-md-4 mb-4 hotel-deal-card" 
                     data-type="{{ strtolower($deal->hotelType ?? '') }}" 
                     data-price="{{ $deal->price ?? 0 }}" 
                     data-stars="{{ $deal->stars ?? 0 }}"
                     data-discount="{{ $deal->discountPercent ?? 0 }}">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="position-relative">
                            <img src="{{ asset('images/travel/' . strtolower(str_replace(' ', '-', $deal->city ?? 'default')) . '.jpg') }}" 
                                class="card-img-top" 
                                alt="{{ $deal->hotelName ?? 'Hotel' }}" 
                                style="height: 200px; object-fit: cover;"
                                onerror="this.src='{{ asset('images/travel/destination-default.jpg') }}'">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #e74c3c;">{{ $deal->discountPercent ?? 0 }}% OFF</span>
                            </div>
                            <div class="position-absolute top-0 start-0 m-3">
                                <button class="wishlist-btn" onclick="toggleWishlist(this)">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="text-white mb-0">{{ $deal->hotelName ?? 'Hotel Name' }}</h5>
                                        <small class="text-white-50">{{ $deal->city ?? 'City' }}, {{ $deal->country ?? 'Country' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge rounded-pill" style="background-color: #8f95d3;">
                                            @for($i = 0; $i < ($deal->stars ?? 0); $i++)
                                            <i class="fas fa-star"></i>
                                            @endfor
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge text-dark" style="background-color: rgba(143, 149, 211, 0.3);">
                                    <i class="fas fa-award me-1"></i> {{ $deal->highlight ?? 'Highlight' }}
                                </span>
                                <span class="badge text-white" style="background-color: #27ae60;">{{ $deal->hotelType ?? 'Hotel Type' }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fs-6 fw-semibold text-muted">{{ $deal->location ?? 'Location' }}</span>
                                    </div>
                                    <small class="text-muted">From {{ $deal->availableDate ?? 'Today' }}</small>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="d-flex align-items-center me-3">
                                        <i class="fas fa-utensils text-muted me-1" style="font-size: 12px;"></i>
                                        <small class="text-muted">{{ $deal->meals ?? 'Meals' }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-wifi text-muted me-1" style="font-size: 12px;"></i>
                                        <small class="text-muted">{{ $deal->wifi ?? 'WiFi' }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="card-text text-muted small mb-3">{{ $deal->description ?? 'No description available.' }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fs-4 fw-bold" style="color: #8f95d3;">{{ $deal->currency ?? 'KES' }} {{ number_format($deal->price ?? 0) }}</span>
                                    <small class="text-muted text-decoration-line-through ms-2">{{ $deal->currency ?? 'KES' }} {{ number_format($deal->originalPrice ?? 0) }}</small>
                                </div>
                                <form method="POST" action="{{ route('book.deal-hotel') }}">
                                    @csrf
                                    <input type="hidden" name="hotel_data" value="{{ json_encode($deal) }}">
                                    <button type="submit" class="btn rounded-pill px-4" style="background-color: #8f95d3; color: white;">
                                        <i class="fas fa-key me-1"></i>Book Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-light rounded-3">
                        <h3>No hotel deals available at the moment</h3>
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
                                            <div class="stars-container">
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
                                            <div class="stars-container">
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
                                            <div class="stars-container">
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
                        <button type="button" class="btn btn-primary" onclick="applyFilters()" style="background-color: #6a75ca; border-color: #6a75ca;">Apply Filters</button>
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
