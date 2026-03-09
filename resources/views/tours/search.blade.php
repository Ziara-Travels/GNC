<!-- filepath: c:\xampp\htdocs\GNC\resources\views\tours\search.blade.php -->

@extends('layouts.main')

@section('title', 'Search Tours: ' . $searchTerm)

@section('content')
<div class="container py-5">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="fw-bold mb-2">Tour Search Results</h1>
                            <p class="text-muted mb-md-0">
                                @if($searchTerm)
                                    Showing results for "<strong>{{ $searchTerm }}</strong>"
                                @else
                                    All tours matching your filters
                                @endif
                                
                                <!-- Display active filters -->
                                @if(request('city') || request('category') || request('price_min') || request('price_max'))
                                    <span class="ms-2">|</span>
                                    <span class="ms-2">Filters:</span>
                                    
                                    @if(request('city'))
                                        <span class="badge bg-info ms-1">{{ request('city') }}</span>
                                    @endif
                                    
                                    @if(request('category'))
                                        <span class="badge bg-info ms-1">{{ request('category') }}</span>
                                    @endif
                                    
                                    @if(request('price_min') || request('price_max'))
                                        <span class="badge bg-info ms-1">
                                            Price: 
                                            {{ request('price_min') ? '$' . request('price_min') : '$0' }} 
                                            -
                                            {{ request('price_max') ? '$' . request('price_max') : '+' }}
                                        </span>
                                    @endif
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <form method="GET" action="{{ route('tours.search') }}" class="d-flex">
                                <input type="text" name="q" class="form-control" placeholder="Search tours..." value="{{ $searchTerm }}">
                                <button type="submit" class="btn btn-primary ms-2">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Filter Tours</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('tours.search') }}" id="filterForm">
                        <!-- Preserve search term if any -->
                        @if($searchTerm)
                            <input type="hidden" name="q" value="{{ $searchTerm }}">
                        @endif
                        
                        <!-- City Filter -->
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Destination</label>
                            <select name="city" class="form-select filter-select">
                                <option value="">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Tour Category</label>
                            <select name="category" class="form-select filter-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ ucwords($category) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="price_min" class="form-control filter-input" placeholder="Min" value="{{ request('price_min') }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="price_max" class="form-control filter-input" placeholder="Max" value="{{ request('price_max') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort Order -->
                        <div class="mb-3">
                            <label class="fw-bold mb-2">Sort By</label>
                            <select name="sort" class="form-select filter-select">
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                                <option value="duration" {{ request('sort') == 'duration' ? 'selected' : '' }}>Duration</option>
                            </select>
                        </div>
                        
                        <!-- Filter Actions -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Apply Filters
                            </button>
                            <a href="{{ route('tours.search', ['q' => $searchTerm]) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Search Results -->
        <div class="col-lg-9">
            @if(count($tours) > 0)
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="bg-light p-3 rounded-3 d-flex justify-content-between align-items-center">
                            <div>
                                Showing <strong>{{ $tours->firstItem() ?? 0 }}</strong> - <strong>{{ $tours->lastItem() ?? 0 }}</strong> 
                                of <strong>{{ $tours->total() }}</strong> tours
                            </div>
                            <div>
                                <select class="form-select form-select-sm" style="width: auto;" id="results-per-page">
                                    <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12 per page</option>
                                    <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 per page</option>
                                    <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 per page</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row g-4">
                    @foreach($tours as $tour)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm tour-card">
                                <div class="position-relative">
                                    <img src="{{ $tour->main_image }}" class="card-img-top" alt="{{ $tour->name }}" style="height: 200px; object-fit: cover;">
                                    
                                    @if($tour->has_discount)
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                                            {{ $tour->discount_percent }}% OFF
                                        </span>
                                    @endif
                                    
                                    @if($tour->featured)
                                        <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark">
                                            <i class="fas fa-star me-1"></i>Featured
                                        </span>
                                    @endif
                                    
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="badge bg-success">{{ ucwords($tour->category) }}</span>
                                                <small class="text-white ms-2">{{ $tour->city }}, {{ $tour->country }}</small>
                                            </div>
                                            <div>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-star text-warning me-1"></i>{{ $tour->rating }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $tour->name }}</h5>
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($tour->short_description, 100) }}</p>
                                    
                                    <div class="d-flex justify-content-between text-muted small mb-3">
                                        <span><i class="fas fa-clock me-1"></i>{{ $tour->duration }}</span>
                                        <span><i class="fas fa-signal me-1"></i>{{ ucwords($tour->difficulty_level) }}</span>
                                    </div>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($tour->has_discount)
                                                    <span class="text-muted text-decoration-line-through small">
                                                        ${{ number_format($tour->original_price, 2) }}
                                                    </span>
                                                @endif
                                                <div class="h5 mb-0 text-success">
                                                    ${{ number_format($tour->price, 2) }}
                                                    <small class="text-muted">per person</small>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('tours.show', $tour->slug) }}" class="btn btn-outline-primary btn-sm">
                                                    View Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $tours->appends(request()->query())->links() }}
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body p-5 text-center">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h3>No Tours Found</h3>
                        <p class="text-muted">
                            We couldn't find any tours matching your search criteria. Try adjusting your filters or search term.
                        </p>
                        <a href="{{ route('tours.index') }}" class="btn btn-primary">
                            <i class="fas fa-globe me-1"></i>Browse All Tours
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Handle results per page change
    const resultsPerPage = document.getElementById('results-per-page');
    if (resultsPerPage) {
        resultsPerPage.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });
    }
});
</script>
@endsection