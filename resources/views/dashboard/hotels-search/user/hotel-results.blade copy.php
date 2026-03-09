@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-green-600 to-blue-600 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="building" class="w-6 h-6"></i>
                    <h1 class="text-2xl font-bold">
                        Hotels in {{ $searchParams['city_code'] ?? 'Destination' }}
                    </h1>
                </div>
                <div class="text-sm opacity-90">
                    <span class="mr-4">{{ $searchParams['check_in_date'] ?? 'Check-in' }} - {{ $searchParams['check_out_date'] ?? 'Check-out' }}</span>
                    <span class="mr-4">{{ $searchParams['adults'] ?? 1 }} Adult(s)</span>
                    <span>{{ $searchParams['rooms'] ?? 1 }} Room(s)</span>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors">
                    <i data-lucide="edit-3" class="w-4 h-4 inline mr-2"></i>
                    Modify Search
                </button>
                <button class="bg-green-700 text-white px-4 py-2 rounded-lg font-medium hover:bg-green-800 transition-colors">
                    <i data-lucide="map" class="w-4 h-4 inline mr-2"></i>
                    View Map
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Main Results Layout -->
<div class="container mx-auto px-4 py-6">
    <div class="flex gap-6">
        <!-- Left Sidebar - Filters (15%) -->
        <div class="w-64 flex-shrink-0 space-y-6">
            <!-- Price Range -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <h3 class="font-semibold mb-4">Price per night</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm mb-2">
                        <span>$0</span>
                        <span>$500+</span>
                    </div>
                    <input type="range" min="0" max="500" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>$50</span>
                        <span>$350</span>
                    </div>
                </div>
            </div>

            <!-- Star Rating -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <h3 class="font-semibold mb-4">Star Rating</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <div class="flex text-yellow-400">
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                        </div>
                        <span class="text-sm">5 stars</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <div class="flex text-yellow-400">
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3"></i>
                        </div>
                        <span class="text-sm">4 stars</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <div class="flex text-yellow-400">
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                            <i data-lucide="star" class="w-3 h-3"></i>
                            <i data-lucide="star" class="w-3 h-3"></i>
                        </div>
                        <span class="text-sm">3 stars</span>
                    </label>
                </div>
            </div>

            <!-- Property Type -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <h3 class="font-semibold mb-4">Property Type</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Hotels</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Resorts</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Apartments</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Villas</span>
                    </label>
                </div>
            </div>

            <!-- Amenities -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <h3 class="font-semibold mb-4">Amenities</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Free WiFi</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Swimming Pool</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Free Parking</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Fitness Center</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm">Spa</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Main Results (70%) -->
        <div class="flex-1 space-y-4">
            <!-- Sort Options -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold">
                        {{ isset($hotels) ? count($hotels) : 0 }} hotels found
                    </h2>
                    <div class="flex items-center gap-4">
                        <select class="border rounded px-3 py-1 text-sm">
                            <option>Recommended</option>
                            <option>Price (Low to High)</option>
                            <option>Price (High to Low)</option>
                            <option>Star Rating</option>
                            <option>Guest Rating</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Hotel Results -->
            @if(isset($hotels) && count($hotels) > 0)
                <div class="space-y-4">
                    @foreach($hotels as $hotel)
                        <div class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow overflow-hidden">
                            <div class="flex">
                                <!-- Hotel Image -->
                                <div class="w-64 h-48 flex-shrink-0">
                                    <img src="https://via.placeholder.com/300x200" alt="Hotel" class="w-full h-full object-cover">
                                </div>

                                <!-- Hotel Info -->
                                <div class="flex-1 p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-xl font-semibold mb-1">{{ $hotel->hotel->name ?? 'Hotel Name' }}</h3>
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 0; $i < 4; $i++)
                                                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-600">4.5 (128 reviews)</span>
                                            </div>
                                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                                <span>{{ $hotel->hotel->address->cityName ?? 'City Center' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="text-right">
                                            <div class="text-sm text-gray-500 line-through">$200</div>
                                            <div class="text-2xl font-bold text-green-600">
                                                ${{ number_format($hotel->offers[0]->price->total ?? 150, 0) }}
                                            </div>
                                            <div class="text-sm text-gray-500">per night</div>
                                        </div>
                                    </div>

                                    <!-- Amenities -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">Free WiFi</span>
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">Pool</span>
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">Gym</span>
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">Spa</span>
                                    </div>

                                    <!-- Description -->
                                    <p class="text-gray-600 text-sm mb-4">
                                        Luxurious hotel in the heart of the city with modern amenities and exceptional service.
                                    </p>

                                    <!-- Action Buttons -->
                                    <div class="flex gap-3">
                                        <button class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg font-medium hover:bg-green-700 transition-colors">
                                            <i data-lucide="calendar-check" class="w-4 h-4 inline mr-2"></i>
                                            Reserve Now
                                        </button>
                                        <button class="border border-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                            <i data-lucide="heart" class="w-4 h-4 inline mr-2"></i>
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border p-12 text-center">
                    <i data-lucide="building-off" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hotels found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search criteria or dates.</p>
                    <a href="{{ route('home') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                        New Search
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Sidebar - Value Added (15%) -->
        <div class="w-64 flex-shrink-0 space-y-6">
            <!-- Flight Deals -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="plane" class="w-4 h-4"></i>
                    Flight Deals
                </h3>
                <div class="space-y-3">
                    <div class="border rounded-lg p-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-sm">NYC → LAX</span>
                            <span class="text-sm font-semibold text-blue-600">$299</span>
                        </div>
                        <div class="text-xs text-gray-500">Round trip • 2 stops</div>
                    </div>
                    <div class="border rounded-lg p-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium text-sm">LAX → NRT</span>
                            <span class="text-sm font-semibold text-blue-600">$599</span>
                        </div>
                        <div class="text-xs text-gray-500">Round trip • Direct</div>
                    </div>
                </div>
                <button class="w-full mt-3 text-blue-600 text-sm hover:underline">View all flights</button>
            </div>

            <!-- Travel Insurance -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Travel Insurance
                </h3>
                <div class="space-y-3">
                    <div class="p-3 bg-green-50 rounded">
                        <div class="font-medium text-green-800 text-sm">Trip Protection</div>
                        <div class="text-green-600 text-sm">From $29/person</div>
                        <div class="text-xs text-gray-600 mt-1">Cancel for any reason</div>
                    </div>
                    <button class="w-full bg-green-600 text-white py-2 rounded text-sm hover:bg-green-700 transition-colors">
                        Get Quote
                    </button>
                </div>
            </div>

            <!-- Local Attractions -->
            <div class="bg-white rounded-lg shadow-sm p-4 border">
                <h3 class="font-semibold mb-4 flex items-center gap-2">
                    <i data-lucide="camera" class="w-4 h-4"></i>
                    Local Attractions
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded"></div>
                        <div>
                            <div class="font-medium text-sm">Central Park</div>
                            <div class="text-xs text-gray-500">0.5 miles away</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded"></div>
                        <div>
                            <div class="font-medium text-sm">Times Square</div>
                            <div class="text-xs text-gray-500">1.2 miles away</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded"></div>
                        <div>
                            <div class="font-medium text-sm">Broadway</div>
                            <div class="text-xs text-gray-500">0.8 miles away</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
lucide.createIcons();
</script>
@endsection