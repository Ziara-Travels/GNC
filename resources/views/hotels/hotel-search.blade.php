@extends('layouts.main')

@section('content')
<!-- Hotel Search Header -->
<div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-purple-900 min-h-screen">
    <!-- Navigation Header -->
    <div class="bg-white/10 backdrop-blur-md border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <h2 class="text-xl font-bold text-white">Hotel Search</h2>
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('search-flights') }}" class="text-white/70 hover:text-white transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-plane text-sm"></i>
                            <span>Flights</span>
                        </a>
                        <button class="text-white/90 hover:text-white transition-colors duration-200 flex items-center space-x-2">
                            <i class="fas fa-hotel text-sm"></i>
                            <span>Hotels</span>
                        </button>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white/90 hover:text-white transition-colors duration-200">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-white/90 hover:text-white transition-colors duration-200">
                            <i class="fas fa-sign-in-alt text-xl"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                Find Your Perfect
                <span class="bg-gradient-to-r from-purple-400 to-pink-500 bg-clip-text text-transparent">Hotel</span>
            </h1>
            <p class="text-xl text-white/80 max-w-2xl mx-auto">
                Search thousands of hotels worldwide at the best prices
            </p>
        </div>

        <!-- Search Form -->
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8">
                <form action="{{ route('hotels.search') }}" method="POST" id="hotelSearchForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <!-- Destination -->
                        <div class="col-span-1 md:col-span-2 lg:col-span-1">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Destination</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                </div>
                                <input 
                                    type="text" 
                                    name="destination" 
                                    placeholder="City or Airport" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    required
                                    autocomplete="off"
                                    id="hotel-destination"
                                >
                                <input type="hidden" name="city_code" id="city_code">
                                <div class="city-suggestions absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-10 hidden" id="citySuggestions"></div>
                            </div>
                        </div>

                        <!-- Check-in & Check-out -->
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Check-in</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input 
                                    type="date" 
                                    name="check_in_date" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    required
                                    min="{{ date('Y-m-d') }}"
                                    id="check-in-date"
                                >
                            </div>
                        </div>
                        <div class="col-span-1">
                            <label class="block text-gray-700 text-sm font-medium mb-2">Check-out</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input 
                                    type="date" 
                                    name="check_out_date" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                    required
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    id="check-out-date"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <!-- Rooms -->
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Rooms</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-door-open text-gray-400"></i>
                                </div>
                                <select 
                                    name="rooms" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none"
                                >
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Room' : 'Rooms' }}</option>
                                    @endfor
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Adults -->
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Adults</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <select 
                                    name="adults" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none"
                                >
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Adult' : 'Adults' }}</option>
                                    @endfor
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Children -->
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-2">Children</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-child text-gray-400"></i>
                                </div>
                                <select 
                                    name="children" 
                                    id="children-select"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none"
                                >
                                    @for($i = 0; $i <= 6; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'Child' : 'Children' }}</option>
                                    @endfor
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button 
                                type="submit" 
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition duration-300 flex items-center justify-center"
                                id="search-button"
                            >
                                <i class="fas fa-search mr-2"></i>
                                <span>Search Hotels</span>
                                <span class="ml-2 spinner hidden" id="search-spinner">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    <div id="children-ages-container" class="hidden mb-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-gray-700 font-medium mb-3">Children Ages</h4>
                            <p class="text-sm text-gray-600 mb-3">Please provide the age of each child for accurate pricing and availability.</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3" id="children-ages-inputs">
                                <!-- Child age inputs will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Options -->
                    <div class="border-t border-gray-200 pt-4">
                        <button 
                            type="button"
                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center"
                            id="toggle-advanced-options"
                        >
                            <i class="fas fa-sliders-h mr-2"></i>
                            <span>Advanced Options</span>
                            <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-300" id="advanced-chevron"></i>
                        </button>
                        
                        <div class="mt-4 hidden" id="advanced-options">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Hotel Rating -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">Hotel Rating</label>
                                    <div class="flex space-x-2">
                                        @foreach(range(1, 5) as $stars)
                                            <label class="star-rating-label flex items-center justify-center w-10 h-10 rounded-full border border-gray-300 cursor-pointer hover:bg-gray-50 transition">
                                                <input type="checkbox" name="ratings[]" value="{{ $stars }}" class="sr-only star-rating-input">
                                                <span class="text-gray-400 star-icon">{{ $stars }}★</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Price Range -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">Price Range (per night)</label>
                                    <div class="flex items-center space-x-4">
                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">$</span>
                                            </div>
                                            <input 
                                                type="number" 
                                                name="price_min" 
                                                placeholder="Min"
                                                min="0" 
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            >
                                        </div>
                                        <span class="text-gray-500">-</span>
                                        <div class="relative flex-1">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500">$</span>
                                            </div>
                                            <input 
                                                type="number" 
                                                name="price_max" 
                                                placeholder="Max"
                                                min="0" 
                                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            >
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Search Radius -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-medium mb-2">Search Radius (km)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-map-marker-alt text-gray-400"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            name="radius" 
                                            value="5"
                                            min="1"
                                            max="50"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        >
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Distance from city center</p>
                                </div>
                            </div>
                            
                            <!-- Amenities -->
                            <div class="mt-4">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Amenities</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="WIFI" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Free WiFi</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="POOL" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Swimming Pool</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="SPA" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Spa</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="RESTAURANT" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Restaurant</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="PARKING" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Parking</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="PETS" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Pet Friendly</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="GYM" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Gym</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="amenities[]" value="AC" class="rounded text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700">Air Conditioning</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Popular Destinations Section -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-white mb-6">Popular Destinations</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Card 1: New York -->
                <div class="popular-destination-card bg-gradient-to-br from-black/60 to-black/30 backdrop-blur-sm p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-300 border border-white/10" data-city-code="NYC">
                    <h3 class="text-white font-bold mb-1">New York</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70 text-sm">5,382 properties</span>
                        <span class="text-yellow-400 text-sm font-semibold">Explore</span>
                    </div>
                </div>
                
                <!-- Card 2: London -->
                <div class="popular-destination-card bg-gradient-to-br from-black/60 to-black/30 backdrop-blur-sm p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-300 border border-white/10" data-city-code="LON">
                    <h3 class="text-white font-bold mb-1">London</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70 text-sm">4,209 properties</span>
                        <span class="text-yellow-400 text-sm font-semibold">Explore</span>
                    </div>
                </div>
                
                <!-- Card 3: Paris -->
                <div class="popular-destination-card bg-gradient-to-br from-black/60 to-black/30 backdrop-blur-sm p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-300 border border-white/10" data-city-code="PAR">
                    <h3 class="text-white font-bold mb-1">Paris</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70 text-sm">3,927 properties</span>
                        <span class="text-yellow-400 text-sm font-semibold">Explore</span>
                    </div>
                </div>
                
                <!-- Card 4: Dubai -->
                <div class="popular-destination-card bg-gradient-to-br from-black/60 to-black/30 backdrop-blur-sm p-4 rounded-lg cursor-pointer hover:scale-105 transition-transform duration-300 border border-white/10" data-city-code="DXB">
                    <h3 class="text-white font-bold mb-1">Dubai</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-white/70 text-sm">1,845 properties</span>
                        <span class="text-yellow-400 text-sm font-semibold">Explore</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Why Book Hotels With Us</h2>
        
        <div class="grid md:grid-cols-3 gap-10">
            <!-- Feature 1 -->
            <div class="text-center">
                <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-search text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Comprehensive Search</h3>
                <p class="text-gray-600">Search thousands of hotels worldwide with our powerful search engine that finds the best deals.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="text-center">
                <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Best Price Guarantee</h3>
                <p class="text-gray-600">Find a lower price? We'll match it and give you an additional discount on your booking.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="text-center">
                <div class="bg-indigo-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Secure Booking</h3>
                <p class="text-gray-600">Book with confidence knowing that your personal and payment information is protected.</p>
            </div>
        </div>
    </div>
</section>

<!-- Travel Tips Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Hotel Booking Tips</h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Tip 1 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="text-indigo-600 text-2xl mb-4">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Book in Advance</h3>
                <p class="text-gray-600 text-sm">For the best rates, book your hotel at least 30 days in advance, especially during peak travel seasons.</p>
            </div>
            
            <!-- Tip 2 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="text-indigo-600 text-2xl mb-4">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Check the Location</h3>
                <p class="text-gray-600 text-sm">Consider proximity to attractions and public transportation when selecting a hotel to save time and money.</p>
            </div>
            
            <!-- Tip 3 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="text-indigo-600 text-2xl mb-4">
                    <i class="fas fa-star"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Read Reviews</h3>
                <p class="text-gray-600 text-sm">Check recent guest reviews for insights about the property that might not be mentioned in the official description.</p>
            </div>
            
            <!-- Tip 4 -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="text-indigo-600 text-2xl mb-4">
                    <i class="fas fa-percentage"></i>
                </div>
                <h3 class="font-bold text-lg text-gray-800 mb-2">Compare Packages</h3>
                <p class="text-gray-600 text-sm">Sometimes booking room packages with meals or other amenities can save you more than booking separately.</p>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum dates for check-in and check-out
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const checkInDateInput = document.getElementById('check-in-date');
    const checkOutDateInput = document.getElementById('check-out-date');
    
    // Format dates as YYYY-MM-DD for input value
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };
    
    checkInDateInput.min = formatDate(today);
    checkOutDateInput.min = formatDate(tomorrow);
    
    // Set default values if none selected
    if (!checkInDateInput.value) {
        checkInDateInput.value = formatDate(today);
    }
    
    if (!checkOutDateInput.value) {
        checkOutDateInput.value = formatDate(tomorrow);
    }
    
    // Update check-out min date when check-in changes
    checkInDateInput.addEventListener('change', function() {
        const newCheckInDate = new Date(this.value);
        const newMinCheckOutDate = new Date(newCheckInDate);
        newMinCheckOutDate.setDate(newMinCheckOutDate.getDate() + 1);
        
        checkOutDateInput.min = formatDate(newMinCheckOutDate);
        
        // If current check-out date is before new min date, update it
        const currentCheckOutDate = new Date(checkOutDateInput.value);
        if (currentCheckOutDate <= newCheckInDate) {
            checkOutDateInput.value = formatDate(newMinCheckOutDate);
        }
    });
    
    // Toggle advanced options
    const advancedOptionsToggle = document.getElementById('toggle-advanced-options');
    const advancedOptionsPanel = document.getElementById('advanced-options');
    const advancedChevron = document.getElementById('advanced-chevron');
    
    advancedOptionsToggle.addEventListener('click', function() {
        advancedOptionsPanel.classList.toggle('hidden');
        advancedChevron.classList.toggle('transform');
        advancedChevron.classList.toggle('rotate-180');
    });
    
    // Star rating selection
    const starLabels = document.querySelectorAll('.star-rating-label');
    starLabels.forEach(label => {
        label.addEventListener('click', function() {
            const input = this.querySelector('.star-rating-input');
            const starIcon = this.querySelector('.star-icon');
            
            if (input.checked) {
                this.classList.add('bg-indigo-500');
                this.classList.add('border-indigo-500');
                starIcon.classList.add('text-white');
                starIcon.classList.remove('text-gray-400');
            } else {
                this.classList.remove('bg-indigo-500');
                this.classList.remove('border-indigo-500');
                starIcon.classList.remove('text-white');
                starIcon.classList.add('text-gray-400');
            }
        });
    });
    
    // Children ages inputs
    const childrenSelect = document.getElementById('children-select');
    const childrenAgesContainer = document.getElementById('children-ages-container');
    const childrenAgesInputs = document.getElementById('children-ages-inputs');
    
    childrenSelect.addEventListener('change', function() {
        const numChildren = parseInt(this.value);
        
        if (numChildren > 0) {
            childrenAgesContainer.classList.remove('hidden');
            childrenAgesInputs.innerHTML = '';
            
            for (let i = 0; i < numChildren; i++) {
                const ageInput = document.createElement('div');
                ageInput.innerHTML = `
                    <label class="block text-gray-700 text-xs font-medium mb-1">Child ${i + 1} Age</label>
                    <select name="child_ages[]" class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none text-sm" required>
                        <option value="" disabled selected>Select age</option>
                        ${Array.from({length: 18}, (_, j) => `<option value="${j}">${j} ${j === 1 ? 'year' : 'years'}</option>`).join('')}
                    </select>
                `;
                childrenAgesInputs.appendChild(ageInput);
            }
        } else {
            childrenAgesContainer.classList.add('hidden');
        }
    });
    
    // City search autocomplete
    const destinationInput = document.getElementById('hotel-destination');
    const cityCodeInput = document.getElementById('city_code');
    const citySuggestions = document.getElementById('citySuggestions');
    let debounceTimer;
    
    destinationInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();
        
        if (query.length < 2) {
            citySuggestions.classList.add('hidden');
            return;
        }
        
        debounceTimer = setTimeout(() => {
            fetch(`/api/airports/locations/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        citySuggestions.innerHTML = '';
                        citySuggestions.classList.remove('hidden');
                        
                        data.forEach(item => {
                            const suggestion = document.createElement('div');
                            suggestion.className = 'py-2 px-4 hover:bg-indigo-50 cursor-pointer flex items-center';
                            suggestion.innerHTML = `
                                <div class="mr-3 text-gray-400"><i class="fas fa-${item.type === 'AIRPORT' ? 'plane' : 'building'}"></i></div>
                                <div>
                                    <div class="font-medium">${item.name}</div>
                                    <div class="text-xs text-gray-500">${item.address.cityName}, ${item.address.countryName}</div>
                                </div>
                            `;
                            suggestion.addEventListener('click', function() {
                                destinationInput.value = item.name;
                                cityCodeInput.value = item.iataCode || item.cityCode;
                                citySuggestions.classList.add('hidden');
                            });
                            citySuggestions.appendChild(suggestion);
                        });
                    } else {
                        citySuggestions.innerHTML = '<div class="py-2 px-4 text-gray-500">No results found</div>';
                        citySuggestions.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error searching locations:', error);
                });
        }, 300);
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!destinationInput.contains(e.target) && !citySuggestions.contains(e.target)) {
            citySuggestions.classList.add('hidden');
        }
    });
    
    // Popular destination cards
    const popularDestinationCards = document.querySelectorAll('.popular-destination-card');
    popularDestinationCards.forEach(card => {
        card.addEventListener('click', function() {
            const cityCode = this.dataset.cityCode;
            
            // Set destination in the search form
            let cityName = this.querySelector('h3').innerText;
            destinationInput.value = cityName;
            cityCodeInput.value = cityCode;
            
            // Scroll to search form
            document.getElementById('hotelSearchForm').scrollIntoView({ behavior: 'smooth' });
        });
    });
    
    // Form submission
    const searchForm = document.getElementById('hotelSearchForm');
    const searchButton = document.getElementById('search-button');
    const searchSpinner = document.getElementById('search-spinner');
    
    searchForm.addEventListener('submit', function(e) {
        // Validation
        if (checkInDateInput.value >= checkOutDateInput.value) {
            e.preventDefault();
            alert('Check-out date must be after check-in date');
            return;
        }
        
        if (!cityCodeInput.value) {
            e.preventDefault();
            alert('Please select a valid destination from the suggestions');
            return;
        }
        
        // Show loading state
        searchButton.disabled = true;
        searchSpinner.classList.remove('hidden');
    });
});
</script>
@endpush