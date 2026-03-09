@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="heart" class="h-6 w-6 text-white"></i>
                        </div>
                        All User Wishlists
                    </h1>
                    <p class="mt-2 text-gray-600">Manage and monitor all user wishlist activities</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-red-50 px-4 py-2 rounded-xl border border-red-200">
                        <span class="text-sm font-medium text-red-600">Superadmin View</span>
                        <div class="w-2 h-2 bg-red-500 rounded-full inline-block ml-2 animate-pulse"></div>
                    </div>
                    <button onclick="exportWishlists()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="download" class="h-5 w-5"></i>
                        Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Wishlist Items</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_items'] }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i data-lucide="heart" class="h-4 w-4 inline"></i>
                            All items
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="heart" class="h-6 w-6 text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Flight Wishlists</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['flight_items'] }}</p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i data-lucide="plane" class="h-4 w-4 inline"></i>
                            Flights saved
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="plane" class="h-6 w-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Hotel Wishlists</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['hotel_items'] }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            <i data-lucide="building" class="h-4 w-4 inline"></i>
                            Hotels saved
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="building" class="h-6 w-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                        <p class="text-sm text-purple-600 mt-1">
                            <i data-lucide="users" class="h-4 w-4 inline"></i>
                            With wishlists
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="h-6 w-6 text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Filter by Type:</label>
                    <select id="typeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">All Types</option>
                        <option value="flight">Flights</option>
                        <option value="hotel">Hotels</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Filter by User Role:</label>
                    <select id="roleFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">All Roles</option>
                        <option value="user">Users</option>
                        <option value="admin">Admins</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Date Range:</label>
                    <input type="date" id="startDate" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <span class="text-gray-500">to</span>
                    <input type="date" id="endDate" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>
                <button onclick="applyFilters()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Apply Filters
                </button>
                <button onclick="resetFilters()" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors">
                    Reset
                </button>
            </div>
        </div>

        <!-- Wishlist Items Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">All Wishlist Items</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Added</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($wishlistItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->image)
                                        <img class="h-12 w-12 rounded-lg object-cover" src="{{ $item->image }}" alt="{{ $item->title }}">
                                    @else
                                        <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i data-lucide="{{ $item->type === 'flight' ? 'plane' : 'building' }}" class="h-6 w-6 text-gray-500"></i>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->type === 'flight' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    <i data-lucide="{{ $item->type === 'flight' ? 'plane' : 'building' }}" class="w-3 h-3 mr-1"></i>
                                    {{ ucfirst($item->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->price)
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($item->price, 2) }}</div>
                                    @if($item->original_price && $item->original_price > $item->price)
                                        <div class="text-sm text-gray-500 line-through">${{ number_format($item->original_price, 2) }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->location ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewItemDetails({{ $item->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                </button>
                                <button onclick="deleteWishlistItem({{ $item->id }})" class="text-red-600 hover:text-red-900">
                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($wishlistItems->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Item Details Modal -->
<div id="itemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Wishlist Item Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
        <div id="itemModalContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

function viewItemDetails(itemId) {
    fetch(`/superadmin/wishlist/${itemId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('itemModalContent').innerHTML = `
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title</label>
                            <p class="mt-1 text-sm text-gray-900">${data.title}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-gray-900">${data.type}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <p class="mt-1 text-sm text-gray-900">$${data.price || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Location</label>
                            <p class="mt-1 text-sm text-gray-900">${data.location || 'N/A'}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">${data.description || 'No description'}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Item Data</label>
                            <pre class="mt-1 text-xs text-gray-900 bg-gray-100 p-3 rounded">${JSON.stringify(data.item_data, null, 2)}</pre>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('itemModal').classList.remove('hidden');
            document.getElementById('itemModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Error fetching item details:', error);
        });
}

function closeModal() {
    document.getElementById('itemModal').classList.add('hidden');
    document.getElementById('itemModal').classList.remove('flex');
}

function deleteWishlistItem(itemId) {
    if (confirm('Are you sure you want to delete this wishlist item?')) {
        fetch(`/superadmin/wishlist/${itemId}`, {
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
                alert('Failed to delete item');
            }
        });
    }
}

function exportWishlists() {
    window.location.href = '/superadmin/wishlist/export';
}

function applyFilters() {
    const typeFilter = document.getElementById('typeFilter').value;
    const roleFilter = document.getElementById('roleFilter').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    let url = new URL(window.location.href);
    url.searchParams.set('type', typeFilter);
    url.searchParams.set('role', roleFilter);
    url.searchParams.set('start_date', startDate);
    url.searchParams.set('end_date', endDate);
    
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = window.location.pathname;
}
</script>
@endsection