{{-- filepath: resources/views/dashboard/wishlist.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'My Wishlist')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="fas fa-heart text-danger me-2"></i>
                                My Wishlist
                            </h5>
                            <p class="text-muted small mb-0">Items you've saved for later</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="clearWishlist()">
                                <i class="fas fa-trash me-1"></i>Clear All
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    @if($wishlistItems->isEmpty())
                    <!-- Empty Wishlist State -->
                    <div class="empty-wishlist text-center py-5">
                        <i class="fas fa-heart-broken fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Your wishlist is empty</h4>
                        <p class="text-muted mb-4">Start exploring and save your favorite destinations, hotels, and deals!</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('search-flights') }}" class="btn btn-primary">
                                <i class="fas fa-plane me-2"></i>Search Flights
                            </a>
                            <a href="{{ route('home') }}#hotels" class="btn btn-success">
                                <i class="fas fa-bed me-2"></i>Browse Hotels
                            </a>
                        </div>
                    </div>
                    @else
                    <!-- Wishlist Items -->
                    <div class="wishlist-grid">
                        <div class="row g-4">
                            @foreach($wishlistItems as $item)
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="wishlist-card">
                                    <div class="wishlist-image">
                                        <img src="{{ $item->image ?? 'https://via.placeholder.com/300x200' }}" alt="{{ $item->title }}" class="card-img-top">
                                        <div class="wishlist-actions">
                                            <button class="btn btn-light btn-sm wishlist-remove" onclick="removeFromWishlist({{ $item->id }})">
                                                <i class="fas fa-heart text-danger"></i>
                                            </button>
                                            <button class="btn btn-light btn-sm" onclick="shareItem({{ $item->id }})">
                                                <i class="fas fa-share"></i>
                                            </button>
                                        </div>
                                        @if($item->type === 'deal')
                                        <div class="deal-badge">
                                            <span class="badge bg-danger">Hot Deal</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="wishlist-content">
                                        <div class="wishlist-header">
                                            <h6 class="wishlist-title">{{ $item->title }}</h6>
                                            <span class="wishlist-type badge bg-light text-dark">
                                                @if($item->type === 'hotel')
                                                    <i class="fas fa-bed me-1"></i>Hotel
                                                @elseif($item->type === 'destination')
                                                    <i class="fas fa-map-marker-alt me-1"></i>Destination
                                                @elseif($item->type === 'deal')
                                                    <i class="fas fa-tag me-1"></i>Deal
                                                @else
                                                    <i class="fas fa-plane me-1"></i>Flight
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="wishlist-details">
                                            <p class="text-muted small mb-2">{{ $item->description ?? 'No description available' }}</p>
                                            
                                            @if($item->location)
                                            <div class="location-info mb-2">
                                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                <small class="text-muted">{{ $item->location }}</small>
                                            </div>
                                            @endif
                                            
                                            @if($item->price)
                                            <div class="price-info">
                                                <span class="current-price fw-bold text-success">
                                                    KES {{ number_format($item->price, 0) }}
                                                </span>
                                                @if($item->original_price && $item->original_price > $item->price)
                                                <span class="original-price text-muted text-decoration-line-through ms-2">
                                                    KES {{ number_format($item->original_price, 0) }}
                                                </span>
                                                @endif
                                            </div>
                                            @endif
                                            
                                            <div class="saved-date">
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Saved {{ $item->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="wishlist-footer">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <button class="btn btn-outline-primary btn-sm" onclick="viewDetails('{{ $item->type }}', {{ $item->item_id }})">
                                                    <i class="fas fa-eye me-1"></i>View Details
                                                </button>
                                                <button class="btn btn-primary btn-sm" onclick="bookNow('{{ $item->type }}', {{ $item->item_id }})">
                                                    <i class="fas fa-calendar-plus me-1"></i>Book Now
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    @if($wishlistItems->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $wishlistItems->links() }}
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Account Creation Modal (for guests) -->
@guest
<div class="modal fade" id="createAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus text-primary me-2"></i>
                    Create Account to Save Wishlist
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-4">Create an account to save your favorite destinations, hotels, and deals!</p>
                
                <form id="createAccountForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createAccount()">
                    <i class="fas fa-user-plus me-2"></i>Create Account
                </button>
            </div>
        </div>
    </div>
</div>
@endguest

<script>
function removeFromWishlist(itemId) {
    if (confirm('Remove this item from your wishlist?')) {
        // Handle wishlist removal
        fetch(`/dashboard/wishlist/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error removing item from wishlist');
            }
        });
    }
}

function clearWishlist() {
    if (confirm('Are you sure you want to clear your entire wishlist?')) {
        // Handle clear all wishlist
        fetch('/dashboard/wishlist/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function viewDetails(type, itemId) {
    // Redirect to appropriate details page based on type
    switch(type) {
        case 'hotel':
            window.open(`/hotels/${itemId}`, '_blank');
            break;
        case 'destination':
            window.open(`/destinations/${itemId}`, '_blank');
            break;
        case 'deal':
            window.open(`/deals/${itemId}`, '_blank');
            break;
        default:
            window.open(`/flights/${itemId}`, '_blank');
    }
}

function bookNow(type, itemId) {
    // Redirect to booking page based on type
    switch(type) {
        case 'hotel':
            window.location.href = `/hotels/${itemId}/book`;
            break;
        case 'flight':
            window.location.href = `/flights/${itemId}/book`;
            break;
        case 'deal':
            window.location.href = `/deals/${itemId}/book`;
            break;
        default:
            window.location.href = '/search';
    }
}

function shareItem(itemId) {
    // Simple share functionality
    if (navigator.share) {
        navigator.share({
            title: 'Check out this item I saved',
            url: window.location.href
        });
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

@guest
function createAccount() {
    // Handle account creation
    const formData = new FormData(document.getElementById('createAccountForm'));
    
    fetch('/register', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error creating account');
        }
    });
}
@endguest
</script>

<style>
.empty-wishlist {
    min-height: 400px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.wishlist-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
}

.wishlist-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.wishlist-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.wishlist-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.wishlist-card:hover .wishlist-image img {
    transform: scale(1.05);
}

.wishlist-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 5px;
}

.wishlist-actions .btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.9);
}

.deal-badge {
    position: absolute;
    top: 10px;
    left: 10px;
}

.wishlist-content {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    height: calc(100% - 200px);
}

.wishlist-header {
    display: flex;
    justify-content: between;
    align-items: start;
    margin-bottom: 1rem;
}

.wishlist-title {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0;
    flex: 1;
    margin-right: 0.5rem;
}

.wishlist-type {
    font-size: 0.75rem;
    white-space: nowrap;
}

.wishlist-details {
    flex: 1;
    margin-bottom: 1rem;
}

.location-info {
    display: flex;
    align-items: center;
}

.price-info {
    margin-bottom: 0.5rem;
}

.current-price {
    font-size: 1.1rem;
}

.original-price {
    font-size: 0.9rem;
}

.saved-date {
    border-top: 1px solid #e2e8f0;
    padding-top: 0.5rem;
    margin-top: 0.5rem;
}

.wishlist-footer {
    margin-top: auto;
}

@media (max-width: 768px) {
    .wishlist-grid .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .wishlist-header {
        flex-direction: column;
        align-items: start;
    }
    
    .wishlist-type {
        margin-top: 0.5rem;
    }
    
    .wishlist-footer .d-flex {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .wishlist-footer .btn {
        width: 100%;
    }
}
</style>
@endsection