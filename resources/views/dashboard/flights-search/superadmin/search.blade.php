@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<!-- Enhanced SuperAdmin Hero Section -->
<section class="relative bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 py-16 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-72 h-72 bg-purple-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-7xl mx-auto">
            <!-- SuperAdmin Header -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-gold-400 to-yellow-600 rounded-2xl flex items-center justify-center shadow-2xl">
                        <i data-lucide="crown" class="h-8 w-8 text-white"></i>
                    </div>
                    <h1 class="text-5xl font-bold text-white">SuperAdmin Flight Search</h1>
                </div>
                <p class="text-xl text-white/90 mb-6">Advanced flight search with full administrative privileges</p>
                
                <!-- SuperAdmin Badges -->
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <span class="bg-gradient-to-r from-gold-500 to-yellow-600 text-white px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                        <i data-lucide="shield-check" class="h-4 w-4"></i>
                        Full System Access
                    </span>
                    <span class="bg-gradient-to-r from-purple-500 to-pink-600 text-white px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                        <i data-lucide="users" class="h-4 w-4"></i>
                        Search for Any User
                    </span>
                    <span class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                        <i data-lucide="bar-chart" class="h-4 w-4"></i>
                        Analytics Dashboard
                    </span>
                    <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                        <i data-lucide="settings" class="h-4 w-4"></i>
                        Advanced Controls
                    </span>
                </div>
            </div>

            <!-- Quick Stats Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 text-white text-center">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search" class="h-6 w-6"></i>
                    </div>
                    <div class="text-3xl font-bold mb-2">{{ $searchStats['total_searches'] }}</div>
                    <div class="text-white/80 text-sm">Total Searches</div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 text-white text-center">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="trending-up" class="h-6 w-6"></i>
                    </div>
                    <div class="text-3xl font-bold mb-2">{{ $searchStats['today_searches'] }}</div>
                    <div class="text-white/80 text-sm">Today's Searches</div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 text-white text-center">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="users" class="h-6 w-6"></i>
                    </div>
                    <div class="text-3xl font-bold mb-2">{{ $searchStats['users_searched_today'] }}</div>
                    <div class="text-white/80 text-sm">Active Users Today</div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 text-white text-center">
                    <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="map" class="h-6 w-6"></i>
                    </div>
                    <div class="text-3xl font-bold mb-2">{{ $searchStats['popular_routes']->count() }}</div>
                    <div class="text-white/80 text-sm">Popular Routes</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Wave separator -->
    <div class="absolute bottom-0 left-0 w-full">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" class="w-full h-auto">
            <path fill="#ffffff" fill-opacity="1" d="M0,64L80,58.7C160,53,320,43,480,42.7C640,43,800,53,960,58.7C1120,64,1280,64,1360,64L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- Main Content -->
<div class="container mx-auto px-4 py-12 -mt-16 relative z-10">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column - Search Form -->
            <div class="xl:col-span-2">
                <!-- Enhanced Search Form -->
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-8 text-white">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                <i data-lucide="plane" class="h-8 w-8"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold">Flight Search</h2>
                                <p class="text-purple-100">Search flights for any user in the system</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-8">
                        <form action="{{ route('flights.search.process') }}" method="POST" id="flightSearchForm">
                            @csrf
                            
                            <!-- User Selection for SuperAdmin -->
                            <div class="mb-8 p-6 bg-gradient-to-r from-gold-50 to-yellow-50 rounded-2xl border border-gold-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 bg-gold-500 rounded-lg flex items-center justify-center">
                                        <i data-lucide="crown" class="h-4 w-4 text-white"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gold-800">Search for User</h3>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                                        <select name="search_for_user" id="userSelect" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                                            <option value="">Search for myself</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" data-role="{{ $user->role }}">
                                                    {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Type</label>
                                        <div class="flex items-center gap-4">
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="search_type" value="personal" checked class="text-purple-600 focus:ring-purple-500">
                                                <span class="text-sm">Personal Search</span>
                                            </label>
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="search_type" value="assisted" class="text-purple-600 focus:ring-purple-500">
                                                <span class="text-sm">Assisted Search</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Trip Type Selection -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Trip Type</label>
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="trip_type" value="round_trip" checked class="sr-only trip-type-radio">
                                        <div class="trip-type-option p-4 border-2 border-gray-200 rounded-xl text-center hover:border-purple-300 transition-all duration-200">
                                            <i data-lucide="refresh-ccw" class="h-6 w-6 mx-auto mb-2 text-gray-600"></i>
                                            <div class="font-semibold">Round Trip</div>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="trip_type" value="one_way" class="sr-only trip-type-radio">
                                        <div class="trip-type-option p-4 border-2 border-gray-200 rounded-xl text-center hover:border-purple-300 transition-all duration-200">
                                            <i data-lucide="arrow-right" class="h-6 w-6 mx-auto mb-2 text-gray-600"></i>
                                            <div class="font-semibold">One Way</div>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="trip_type" value="multi_city" class="sr-only trip-type-radio">
                                        <div class="trip-type-option p-4 border-2 border-gray-200 rounded-xl text-center hover:border-purple-300 transition-all duration-200">
                                            <i data-lucide="map" class="h-6 w-6 mx-auto mb-2 text-gray-600"></i>
                                            <div class="font-semibold">Multi-City</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Flight Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- From -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="plane-takeoff" class="h-5 w-5"></i>
                                        </div>
                                        <input type="text" name="from" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200" placeholder="Departure city or airport" required>
                                        <input type="hidden" name="from_iata" id="fromIata">
                                    </div>
                                </div>

                                <!-- To -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="plane-landing" class="h-5 w-5"></i>
                                        </div>
                                        <input type="text" name="to" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200" placeholder="Destination city or airport" required>
                                        <input type="hidden" name="to_iata" id="toIata">
                                    </div>
                                </div>

                                <!-- Departure Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Departure Date</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="calendar" class="h-5 w-5"></i>
                                        </div>
                                        <input type="date" name="date" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200" required>
                                    </div>
                                </div>

                                <!-- Return Date -->
                                <div id="returnDateField">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="calendar" class="h-5 w-5"></i>
                                        </div>
                                        <input type="date" name="return_date" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                                    </div>
                                </div>
                            </div>

                            <!-- Passengers and Class -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Adults</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="user" class="h-5 w-5"></i>
                                        </div>
                                        <input type="number" name="adults" value="1" min="1" max="9" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-center" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Children</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="baby" class="h-5 w-5"></i>
                                        </div>
                                        <input type="number" name="children" value="0" min="0" max="9" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-center">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Infants</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="baby" class="h-5 w-5"></i>
                                        </div>
                                        <input type="number" name="infants" value="0" min="0" max="9" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-center">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                                    <div class="relative">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <i data-lucide="star" class="h-5 w-5"></i>
                                        </div>
                                        <select name="travel_class" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200" required>
                                            <option value="ECONOMY">Economy</option>
                                            <option value="PREMIUM_ECONOMY">Premium Economy</option>
                                            <option value="BUSINESS">Business</option>
                                            <option value="FIRST">First Class</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Options -->
                            <div class="flex flex-wrap gap-4 mb-8">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="non_stop" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="text-sm text-gray-600">Direct flights only</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="nearby_airports" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="text-sm text-gray-600">Include nearby airports</span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold text-lg rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                                <i data-lucide="search" class="h-5 w-5"></i>
                                <span>Search Flights</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column - Recent Searches & Analytics -->
            <div class="space-y-8">
                <!-- Recent Searches -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="clock" class="h-5 w-5 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Recent Searches</h3>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($recentSearches as $search)
                            <div class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-800">{{ $search->from_iata }} → {{ $search->to_iata }}</span>
                                        @if($search->user_id !== $search->searched_by)
                                            <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full">Searched for {{ $search->user->name }}</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $search->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $search->departure_date->format('M j, Y') }}
                                    @if($search->return_date)
                                        - {{ $search->return_date->format('M j, Y') }}
                                    @endif
                                    • {{ $search->adults }} Adult{{ $search->adults > 1 ? 's' : '' }}
                                    • {{ ucfirst($search->travel_class) }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i data-lucide="search-x" class="h-12 w-12 mx-auto mb-4 opacity-50"></i>
                                <p>No recent searches</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Popular Routes -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="trending-up" class="h-5 w-5 text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Popular Routes</h3>
                    </div>
                    
                    <div class="space-y-3">
                        @forelse($searchStats['popular_routes'] as $route)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="font-medium text-gray-800">{{ $route->route }}</span>
                                <span class="text-sm bg-blue-100 text-blue-600 px-2 py-1 rounded-full">{{ $route->count }} searches</span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <p class="text-sm">No data available</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="zap" class="h-5 w-5 text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Quick Actions</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <a href="#" class="block p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <i data-lucide="users" class="h-5 w-5 text-blue-600"></i>
                                <span class="font-medium text-gray-800">Manage Users</span>
                            </div>
                        </a>
                        
                        <a href="#" class="block p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <i data-lucide="bar-chart" class="h-5 w-5 text-green-600"></i>
                                <span class="font-medium text-gray-800">View Analytics</span>
                            </div>
                        </a>
                        
                        <a href="#" class="block p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <i data-lucide="settings" class="h-5 w-5 text-purple-600"></i>
                                <span class="font-medium text-gray-800">System Settings</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Trip type selection
    const tripTypeRadios = document.querySelectorAll('.trip-type-radio');
    const returnDateField = document.getElementById('returnDateField');
    
    tripTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            // Update visual selection
            document.querySelectorAll('.trip-type-option').forEach(option => {
                option.classList.remove('border-purple-500', 'bg-purple-50');
                option.classList.add('border-gray-200');
            });
            
            if (this.checked) {
                const option = this.parentElement.querySelector('.trip-type-option');
                option.classList.remove('border-gray-200');
                option.classList.add('border-purple-500', 'bg-purple-50');
                
                // Show/hide return date field
                if (this.value === 'one_way' || this.value === 'multi_city') {
                    returnDateField.style.display = 'none';
                    returnDateField.querySelector('input').required = false;
                } else {
                    returnDateField.style.display = 'block';
                    returnDateField.querySelector('input').required = true;
                }
            }
        });
    });
    
    // Initialize with first option selected
    document.querySelector('.trip-type-radio:checked').dispatchEvent(new Event('change'));
    
    // Airport autocomplete (simplified version)
    const airportInputs = document.querySelectorAll('input[name="from"], input[name="to"]');
    
    airportInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Implement airport autocomplete logic here
            // This would typically connect to an airport search API
        });
    });
    
    // Form validation
    document.getElementById('flightSearchForm').addEventListener('submit', function(e) {
        const fromIata = document.getElementById('fromIata').value;
        const toIata = document.getElementById('toIata').value;
        
        if (!fromIata || !toIata) {
            e.preventDefault();
            alert('Please select valid departure and destination airports');
            return;
        }
        
        if (fromIata === toIata) {
            e.preventDefault();
            alert('Departure and destination airports cannot be the same');
            return;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Searching Flights...
        `;
        submitBtn.disabled = true;
    });
});

// Mock airport data for autocomplete
const airports = [
    { code: 'JFK', name: 'John F. Kennedy International Airport', city: 'New York' },
    { code: 'LAX', name: 'Los Angeles International Airport', city: 'Los Angeles' },
    { code: 'LHR', name: 'London Heathrow Airport', city: 'London' },
    { code: 'CDG', name: 'Charles de Gaulle Airport', city: 'Paris' },
    { code: 'NBO', name: 'Jomo Kenyatta International Airport', city: 'Nairobi' },
    { code: 'DXB', name: 'Dubai International Airport', city: 'Dubai' },
    // Add more airports as needed
];
</script>

<style>
.animation-delay-1000 { animation-delay: 1s; }
.animation-delay-2000 { animation-delay: 2s; }

.bg-gold-50 { background-color: #fffdf2; }
.bg-gold-100 { background-color: #fef3c7; }
.bg-gold-400 { background-color: #fbbf24; }
.bg-gold-500 { background-color: #f59e0b; }
.border-gold-200 { border-color: #fde68a; }
.text-gold-800 { color: #92400e; }

.from-gold-400 { --tw-gradient-from: #fbbf24; }
.to-yellow-600 { --tw-gradient-to: #d97706; }
.from-gold-500 { --tw-gradient-from: #f59e0b; }
.from-gold-50 { --tw-gradient-from: #fffdf2; }
.to-yellow-50 { --tw-gradient-to: #fefce8; }
</style>
@endsection