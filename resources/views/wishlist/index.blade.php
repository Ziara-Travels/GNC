@extends('layouts.app')

@section('title', 'My Wishlist - Global Navigation Company')

@section('meta_description', 'View and manage all your favorite flights, hotels, tours, and destinations in one place')

@section('content')
<div class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="mb-8 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">My Wishlist</h1>
            <p class="text-lg text-gray-600">All your favorite travel items in one place</p>
        </div>
        
        <!-- Stats Section -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Total Items</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_items'] }}</h3>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Flights</p>
                    <h3 class="text-2xl font-bold text-blue-600">{{ $stats['flight_items'] }}</h3>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Hotels</p>
                    <h3 class="text-2xl font-bold text-amber-500">{{ $stats['hotel_items'] }}</h3>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Tours</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $stats['tour_items'] }}</h3>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <div class="text-center">
                    <p class="text-sm text-gray-500">Destinations</p>
                    <h3 class="text-2xl font-bold text-purple-600">{{ $stats['destination_items'] }}</h3>
                </div>
            </div>
        </div>
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap border-b border-gray-200 mb-8">
            <button class="filter-tab active px-4 py-2 font-medium text-sm text-gray-900 border-b-2 border-amber-500" data-filter="all">
                All Items
            </button>
            <button class="filter-tab px-4 py-2 font-medium text-sm text-gray-500 hover:text-gray-900" data-filter="flight">
                Flights
            </button>
            <button class="filter-tab px-4 py-2 font-medium text-sm text-gray-500 hover:text-gray-900" data-filter="hotel">
                Hotels
            </button>
            <button class="filter-tab px-4 py-2 font-medium text-sm text-gray-500 hover:text-gray-900" data-filter="tour">
                Tours & Activities
            </button>
            <button class="filter-tab px-4 py-2 font-medium text-sm text-gray-500 hover:text-gray-900" data-filter="destination">
                Destinations
            </button>
        </div>
        
        @if($wishlistItems->isEmpty())
            <div class="text-center py-16 bg-white rounded-lg shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Your wishlist is empty</h3>
                <p class="text-gray-500 mb-6">Start adding your favorite travel items as you browse</p>
                <div class="flex justify-center">
                    <a href="{{ route('home') }}" class="px-5 py-2 bg-amber-500 text-white font-medium rounded-md hover:bg-amber-600 transition-colors">
                        Browse Offers
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="wishlist-card bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 transition-transform hover:shadow-md hover:-translate-y-1" data-type="{{ $item->type }}">
                        <div class="relative h-48 overflow-hidden">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3 px-2 py-1 rounded-full text-xs font-medium
                                @if($item->type == 'flight') bg-blue-100 text-blue-800
                                @elseif($item->type == 'hotel') bg-amber-100 text-amber-800
                                @elseif($item->type == 'tour') bg-green-100 text-green-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($item->type) }}
                            </div>
                            <div class="absolute top-3 right-3">
                                <button class="remove-from-wishlist p-2 rounded-full bg-white/70 hover:bg-white text-gray-700 hover:text-red-600 transition-colors" 
                                        data-id="{{ $item->id }}"
                                        data-type="{{ $item->type }}"
                                        title="Remove from wishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12,21.35L10.55,20.03C5.4,15.36 2,12.27 2,8.5C2,5.41 4.42,3 7.5,3C9.24,3 10.91,3.81 12,5.08C13.09,3.81 14.76,3 16.5,3C19.58,3 22,5.41 22,8.5C22,12.27 18.6,15.36 13.45,20.03L12,21.35Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">{{ $item->title }}</h3>
                            
                            @if($item->location)
                                <div class="flex items-center text-gray-600 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm">{{ $item->location }}</span>
                                </div>
                            @endif
                            
                            @if($item->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    @if($item->price)
                                        <span class="text-lg font-bold text-gray-900">${{ number_format($item->price, 2) }}</span>
                                        @if($item->original_price && $item->original_price > $item->price)
                                            <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($item->original_price, 2) }}</span>
                                        @endif
                                    @endif
                                </div>
                                
                                <div>
                                    @if($item->type == 'flight')
                                        <a href="{{ route('flights.show', ['id' => $item->item_id]) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                                            View Flight
                                        </a>
                                    @elseif($item->type == 'hotel')
                                        <a href="{{ route('hotels.show', ['id' => $item->item_id]) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-amber-600 rounded-md hover:bg-amber-700 transition-colors">
                                            View Hotel
                                        </a>
                                    @elseif($item->type == 'tour')
                                        <a href="{{ route('tours.show', ['slug' => $item->item_id]) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                                            View Tour
                                        </a>
                                    @else
                                        <a href="{{ route('destinations.show', ['slug' => $item->item_id]) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700 transition-colors">
                                            View Destination
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $wishlistItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter tabs functionality
        const filterTabs = document.querySelectorAll('.filter-tab');
        const wishlistCards = document.querySelectorAll('.wishlist-card');
        
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                filterTabs.forEach(t => t.classList.remove('active', 'border-amber-500', 'text-gray-900'));
                filterTabs.forEach(t => t.classList.add('text-gray-500'));
                
                // Add active class to clicked tab
                this.classList.add('active', 'border-b-2', 'border-amber-500', 'text-gray-900');
                this.classList.remove('text-gray-500');
                
                const filter = this.getAttribute('data-filter');
                
                // Show/hide cards based on filter
                wishlistCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-type') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Remove from wishlist functionality
        document.querySelectorAll('.remove-from-wishlist').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const card = this.closest('.wishlist-card');
                
                if (confirm('Are you sure you want to remove this item from your wishlist?')) {
                    fetch(`/wishlist/remove/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the card with animation
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';
                            
                            setTimeout(() => {
                                card.remove();
                                
                                // Update counter
                                document.dispatchEvent(new CustomEvent('wishlistUpdated'));
                                
                                // Check if empty
                                if (document.querySelectorAll('.wishlist-card').length === 0) {
                                    location.reload(); // Reload to show empty state
                                }
                            }, 300);
                        }
                    })
                    .catch(error => console.error('Error removing item from wishlist:', error));
                }
            });
        });
    });
</script>
@endsection

@section('styles')
<style>
    .filter-tab.active {
        border-bottom-width: 2px;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .wishlist-card {
        transition: all 0.3s ease;
    }
</style>
@endsection
