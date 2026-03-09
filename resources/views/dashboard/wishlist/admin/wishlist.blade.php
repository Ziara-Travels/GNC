@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-pink-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold flex items-center gap-3">
                        <i data-lucide="heart" class="h-10 w-10"></i>
                        My Wishlist
                    </h1>
                    <p class="mt-2 text-red-100">Your saved flights and hotels for future trips</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                        <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 -mt-16 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Items</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_items'] }}</p>
                        <p class="text-sm text-red-600 mt-1">
                            <i data-lucide="heart" class="h-4 w-4 inline"></i>
                            Saved items
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="heart" class="h-6 w-6 text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Flights</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['flight_items'] }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i data-lucide="plane" class="h-4 w-4 inline"></i>
                            Flight options
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="plane" class="h-6 w-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Hotels</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['hotel_items'] }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i data-lucide="building" class="h-4 w-4 inline"></i>
                            Accommodations
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="building" class="h-6 w-6 text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($wishlistItems->isEmpty())
            <!-- Empty Wishlist State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="heart" class="h-12 w-12 text-red-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Your wishlist is empty</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Start exploring and save your favorite flights and hotels for your next adventure!</p>
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('flights.search') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <i data-lucide="plane" class="h-5 w-5"></i>
                        Search Flights
                    </a>
                    <a href="{{ route('hotels.search') }}" class="bg-green-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-green-700 transition-colors flex items-center gap-2">
                        <i data-lucide="building" class="h-5 w-5"></i>
                        Browse Hotels
                    </a>
                </div>
            </div>
        @else
            <!-- Filter Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Filter by:</label>
                        <button id="allFilter" onclick="filterItems('all')" class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            All Items
                        </button>
                        <button id="flightFilter" onclick="filterItems('flight')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Flights
                        </button>
                        <button id="hotelFilter" onclick="filterItems('hotel')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Hotels
                        </button>
                    </div>
                    <div class="ml-auto">
                        <button onclick="clearAllWishlist()" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition-colors flex items-center gap-2">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                            Clear All
                        </button>
                    </div>
                </div>
            </div>

            <!-- Wishlist Items Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlistItems as $item)
                <div class="wishlist-item bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1" data-type="{{ $item->type }}">
                    <div class="relative">
                        @if($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-r from-{{ $item->type === 'flight' ? 'blue' : 'green' }}-500 to-{{ $item->type === 'flight' ? 'purple' : 'teal' }}-600 flex items-center justify-center">
                                <i data-lucide="{{ $item->type === 'flight' ? 'plane' : 'building' }}" class="h-16 w-16 text-white"></i>
                            </div>
                        @endif
                        
                        <!-- Wishlist Remove Button -->
                        <button onclick="removeFromWishlist({{ $item->id }})" class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors group">
                            <i data-lucide="heart" class="h-4 w-4 text-red-500 group-hover:text-white fill-current"></i>
                        </button>
                        
                        <!-- Type Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->type === 'flight' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                <i data-lucide="{{ $item->type === 'flight' ? 'plane' : 'building' }}" class="w-3 h-3 mr-1"></i>
                                {{ ucfirst($item->type) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 flex-1">{{ $item->title }}</h3>
                            @if($item->price)
                                <div class="text-right ml-4">
                                    <div class="text-xl font-bold text-green-600">${{ number_format($item->price, 2) }}</div>
                                    @if($item->original_price && $item->original_price > $item->price)
                                        <div class="text-sm text-gray-500 line-through">${{ number_format($item->original_price, 2) }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        @if($item->location)
                            <div class="flex items-center gap-2 mb-3">
                                <i data-lucide="map-pin" class="h-4 w-4 text-gray-500"></i>
                                <span class="text-sm text-gray-600">{{ $item->location }}</span>
                            </div>
                        @endif
                        
                        @if($item->description)
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($item->description, 100) }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>Saved {{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="flex gap-2">
                            <button onclick="viewItemDetails({{ $item->id }})" class="flex-1 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="eye" class="h-4 w-4"></i>
                                View Details
                            </button>
                            <button onclick="bookItem({{ $item->id }})" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="calendar-plus" class="h-4 w-4"></i>
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

             <!-- Pagination -->
            @if($wishlistItems->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<!-- Item Details Modal -->
<div id="itemDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Item Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
        <div id="itemDetailsContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

// Filter functionality
function filterItems(type) {
    const items = document.querySelectorAll('.wishlist-item');
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    // Update active filter button
    filterBtns.forEach(btn => btn.classList.remove('active'));
    document.getElementById(type + 'Filter').classList.add('active');
    
    // Filter items
    items.forEach(item => {
        if (type === 'all' || item.dataset.type === type) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Remove item from wishlist
function removeFromWishlist(itemId) {
    if (confirm('Are you sure you want to remove this item from your wishlist?')) {
        fetch(`/wishlist/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error removing item from wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing item from wishlist');
        });
    }
}

// Clear all wishlist items
function clearAllWishlist() {
    if (confirm('Are you sure you want to clear your entire wishlist? This action cannot be undone.')) {
        fetch('/wishlist/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error clearing wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error clearing wishlist');
        });
    }
}

// View item details
function viewItemDetails(itemId) {
    fetch(`/wishlist/${itemId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.item;
                document.getElementById('itemDetailsContent').innerHTML = `
                    <div class="space-y-6">
                        <div class="flex items-start gap-6">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">${item.title}</h2>
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${item.type === 'flight' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'}">
                                        <i data-lucide="${item.type === 'flight' ? 'plane' : 'building'}" class="w-3 h-3 mr-1"></i>
                                        ${item.type.charAt(0).toUpperCase() + item.type.slice(1)}
                                    </span>
                                    <span class="text-sm text-gray-500">Added ${item.created_at}</span>
                                </div>
                                ${item.location ? `
                                    <div class="flex items-center gap-2 mb-4">
                                        <i data-lucide="map-pin" class="h-4 w-4 text-gray-500"></i>
                                        <span class="text-gray-600">${item.location}</span>
                                    </div>
                                ` : ''}
                                ${item.description ? `
                                    <p class="text-gray-600 mb-4">${item.description}</p>
                                ` : ''}
                            </div>
                            ${item.price ? `
                                <div class="text-right">
                                    <div class="text-3xl font-bold text-green-600">$${parseFloat(item.price).toLocaleString()}</div>
                                    ${item.original_price && item.original_price > item.price ? `
                                        <div class="text-lg text-gray-500 line-through">$${parseFloat(item.original_price).toLocaleString()}</div>
                                    ` : ''}
                                </div>
                            ` : ''}
                        </div>
                        
                        <div class="flex gap-4">
                            <button onclick="bookItem(${item.id})" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="calendar-plus" class="h-5 w-5"></i>
                                Book Now
                            </button>
                            <button onclick="removeFromWishlist(${item.id})" class="bg-red-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-red-700 transition-colors flex items-center gap-2">
                                <i data-lucide="trash-2" class="h-5 w-5"></i>
                                Remove
                            </button>
                        </div>
                    </div>
                `;
                
                document.getElementById('itemDetailsModal').classList.remove('hidden');
                document.getElementById('itemDetailsModal').classList.add('flex');
                lucide.createIcons();
            } else {
                alert('Error loading item details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading item details');
        });
}

// Book item
function bookItem(itemId) {
    fetch(`/wishlist/${itemId}/book`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            alert('Error booking item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error booking item');
    });
}

// Close modal
function closeModal() {
    document.getElementById('itemDetailsModal').classList.add('hidden');
    document.getElementById('itemDetailsModal').classList.remove('flex');
}

// CSS for filter buttons
const style = document.createElement('style');
style.textContent = `
    .filter-btn {
        background-color: #f3f4f6;
        color: #6b7280;
        border: 1px solid #e5e7eb;
    }
    .filter-btn:hover {
        background-color: #e5e7eb;
    }
    .filter-btn.active {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
`;
document.head.appendChild(style);
</script>
@endsection