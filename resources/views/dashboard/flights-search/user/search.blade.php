@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

@auth
    @if(auth()->user()->role === 'user' && auth()->user()->isActive())
        <!-- User Hero Section -->
        <section class="relative bg-gradient-to-br from-green-600 via-teal-700 to-blue-800 py-16 overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-72 h-72 bg-green-400/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-5xl mx-auto">
                    <!-- User Header -->
                    <div class="text-center mb-8">
                        <div class="flex items-center justify-center gap-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-2xl">
                                <i data-lucide="user" class="h-8 w-8 text-white"></i>
                            </div>
                            <h1 class="text-5xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h1>
                        </div>
                        <p class="text-xl text-white/90 mb-6">Search and book flights for your next journey</p>
                        
                        <!-- User Status Badge -->
                        <div class="flex justify-center mb-6">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl px-6 py-3 border border-white/20">
                                <div class="flex items-center gap-2 text-white">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-300"></i>
                                    <span class="font-medium">Authenticated User</span>
                                    <span class="text-green-300">•</span>
                                    <span class="text-sm">{{ ucfirst(auth()->user()->role) }} Access</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 text-white text-center">
                            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="search" class="h-6 w-6"></i>
                            </div>
                            <div class="text-3xl font-bold mb-2">{{ $userStats['searches'] ?? 0 }}</div>
                            <div class="text-white/80 text-sm">Your Searches</div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 text-white text-center">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="calendar" class="h-6 w-6"></i>
                            </div>
                            <div class="text-3xl font-bold mb-2">{{ $userStats['bookings'] ?? 0 }}</div>
                            <div class="text-white/80 text-sm">Your Bookings</div>
                        </div>
                        
                        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/20 text-white text-center">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="map" class="h-6 w-6"></i>
                            </div>
                            <div class="text-3xl font-bold mb-2">{{ $userStats['destinations'] ?? 0 }}</div>
                            <div class="text-white/80 text-sm">Destinations</div>
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
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Search Form -->
                    <div class="lg:col-span-2">
                        <!-- Search Form -->
                        <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-600 to-teal-600 p-8 text-white">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                        <i data-lucide="plane" class="h-8 w-8"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold">Search Flights</h2>
                                        <p class="text-green-100">Find the best flights for your trip</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-8">
                                @if(auth()->user()->hasPermission('flights.search'))
                                    <form action="{{ route('user.flights.search.process') }}" method="POST" id="flightSearchForm">
                                        @csrf
                                        
                                        <!-- Trip Type Selection -->
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-3">Trip Type</label>
                                            <div class="flex gap-4">
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="trip_type" value="round_trip" checked class="sr-only trip-type-radio">
                                                    <div class="trip-type-option p-4 border-2 border-gray-200 rounded-xl text-center hover:border-green-300 transition-all duration-200">
                                                        <i data-lucide="refresh-ccw" class="h-6 w-6 mx-auto mb-2 text-gray-600"></i>
                                                        <div class="font-semibold">Round Trip</div>
                                                    </div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="trip_type" value="one_way" class="sr-only trip-type-radio">
                                                    <div class="trip-type-option p-4 border-2 border-gray-200 rounded-xl text-center hover:border-green-300 transition-all duration-200">
                                                        <i data-lucide="arrow-right" class="h-6 w-6 mx-auto mb-2 text-gray-600"></i>
                                                        <div class="font-semibold">One Way</div>
                                                    </div>
                                                </label>
                                                <label class="flex-1 cursor-pointer">
                                                    <input type="radio" name="trip_type" value="multi_city" class="sr-only trip-type-radio">
                                                    <div class="trip-type-option p-4 border-2 border-gray-200 rounded-xl text-center hover:border-green-300 transition-all duration-200">
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
                                                    <input type="text" name="from" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200" placeholder="Departure city or airport" required>
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
                                                    <input type="text" name="to" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200" placeholder="Destination city or airport" required>
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
                                                    <input type="date" name="date" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200" required>
                                                </div>
                                            </div>

                                            <!-- Return Date -->
                                            <div id="returnDateField">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Return Date</label>
                                                <div class="relative">
                                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                                        <i data-lucide="calendar" class="h-5 w-5"></i>
                                                    </div>
                                                    <input type="date" name="return_date" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200">
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
                                                    <input type="number" name="adults" value="1" min="1" max="9" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 text-center" required>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Children</label>
                                                <div class="relative">
                                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                                        <i data-lucide="baby" class="h-5 w-5"></i>
                                                    </div>
                                                    <input type="number" name="children" value="0" min="0" max="9" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 text-center">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Infants</label>
                                                <div class="relative">
                                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                                        <i data-lucide="baby" class="h-5 w-5"></i>
                                                    </div>
                                                    <input type="number" name="infants" value="0" min="0" max="9" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 text-center">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                                                <div class="relative">
                                                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                                        <i data-lucide="star" class="h-5 w-5"></i>
                                                    </div>
                                                    <select name="travel_class" class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200" required>
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
                                                <input type="checkbox" name="non_stop" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                                <span class="text-sm text-gray-600">Direct flights only</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="nearby_airports" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                                <span class="text-sm text-gray-600">Include nearby airports</span>
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-semibold text-lg rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                                            <i data-lucide="search" class="h-5 w-5"></i>
                                            <span>Search Flights</span>
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center py-8">
                                        <i data-lucide="lock" class="h-16 w-16 text-gray-400 mx-auto mb-4"></i>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Access Restricted</h3>
                                        <p class="text-gray-600 mb-4">You don't have permission to search for flights.</p>
                                        <p class="text-sm text-gray-500">Please contact your administrator to request flight search permissions.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - User Info -->
                    <div class="space-y-8">
                        <!-- User Profile Card -->
                        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h3>
                                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">{{ ucfirst(auth()->user()->role) }}</span>
                                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">{{ auth()->user()->isActive() ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3 text-sm">
                                @if(auth()->user()->phone)
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="phone" class="h-4 w-4 text-gray-500"></i>
                                        <span>{{ auth()->user()->phone }}</span>
                                    </div>
                                @endif
                                @if(auth()->user()->country)
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="map-pin" class="h-4 w-4 text-gray-500"></i>
                                        <span>{{ auth()->user()->country }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="h-4 w-4 text-gray-500"></i>
                                    <span>Member since {{ auth()->user()->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Searches -->
                        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                    <i data-lucide="clock" class="h-5 w-5 text-green-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Recent Searches</h3>
                            </div>
                            
                            <div class="space-y-4">
                                @forelse($recentSearches ?? [] as $search)
                                    <div class="p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer" onclick="fillSearchForm('{{ $search->from_iata }}', '{{ $search->to_iata }}', '{{ $search->departure_date }}', '{{ $search->return_date }}')">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-semibold text-gray-800">{{ $search->from_iata }} → {{ $search->to_iata }}</span>
                                            <span class="text-xs text-gray-500">{{ $search->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($search->departure_date)->format('M j, Y') }}
                                            @if($search->return_date)
                                                - {{ \Carbon\Carbon::parse($search->return_date)->format('M j, Y') }}
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

                        <!-- My Bookings -->
                        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i data-lucide="calendar" class="h-5 w-5 text-blue-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">My Bookings</h3>
                            </div>
                            
                            <div class="space-y-3">
                                @forelse($recentBookings ?? [] as $booking)
                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-medium text-gray-800">{{ $booking->booking_reference }}</span>
                                            <span class="text-xs px-2 py-1 rounded-full {{ $booking->status === 'completed' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ $booking->departure_airport }} → {{ $booking->arrival_airport }}
                                            • {{ $booking->departure_time->format('M j, Y') }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-gray-500">
                                        <p class="text-sm">No bookings yet</p>
                                    </div>
                                @endforelse
                            </div>
                            
                            @if(auth()->user()->hasPermission('bookings.view_own'))
                                <a href="{{ route('user.bookings.all') }}" class="block mt-4 text-center text-blue-600 hover:text-blue-800 font-medium">View All Bookings</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Access Denied for non-users or inactive users -->
        <div class="min-h-screen bg-gray-50 flex items-center justify-center">
            <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="shield-x" class="h-10 w-10 text-red-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Access Restricted</h2>
                @if(!auth()->user()->isActive())
                    <p class="text-gray-600 mb-6">Your account has been deactivated. Please contact support for assistance.</p>
                @else
                    <p class="text-gray-600 mb-6">This area is restricted to users only. You need a user account to search for flights.</p>
                @endif
                <div class="space-y-3">
                    <a href="{{ route('home') }}" class="block w-full bg-blue-600 text-white py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                        Go to Home
                    </a>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="block w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        Logout
                    </a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    @endif
@else
    <!-- Not authenticated -->
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="lock" class="h-10 w-10 text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Authentication Required</h2>
            <p class="text-gray-600 mb-6">You must be logged in to access the flight search dashboard.</p>
            <div class="space-y-3">
                <a href="{{ route('login') }}" class="block w-full bg-blue-600 text-white py-3 rounded-xl font-medium hover:bg-blue-700 transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block w-full bg-gray-100 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                    Create Account
                </a>
                <a href="{{ route('home') }}" class="block w-full text-blue-600 hover:text-blue-800 font-medium">
                    Go to Home
                </a>
            </div>
        </div>
    </div>
@endauth

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    @auth
        @if(auth()->user()->role === 'user' && auth()->user()->isActive() && auth()->user()->hasPermission('flights.search'))
            // Trip type selection with green colors
            const tripTypeRadios = document.querySelectorAll('.trip-type-radio');
            const returnDateField = document.getElementById('returnDateField');
            
            tripTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.trip-type-option').forEach(option => {
                        option.classList.remove('border-green-500', 'bg-green-50');
                        option.classList.add('border-gray-200');
                    });
                    
                    if (this.checked) {
                        const option = this.parentElement.querySelector('.trip-type-option');
                        option.classList.remove('border-gray-200');
                        option.classList.add('border-green-500', 'bg-green-50');
                        
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
            
            document.querySelector('.trip-type-radio:checked').dispatchEvent(new Event('change'));

            // Form submission
            document.getElementById('flightSearchForm').addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Searching Flights...</span>
                `;
                submitBtn.disabled = true;
            });
        @endif
    @endauth
});

function fillSearchForm(from, to, departure, returnDate) {
    document.querySelector('input[name="from"]').value = from;
    document.querySelector('input[name="to"]').value = to;
    document.querySelector('input[name="date"]').value = departure;
    if (returnDate) {
        document.querySelector('input[name="return_date"]').value = returnDate;
    }
    document.getElementById('fromIata').value = from;
    document.getElementById('toIata').value = to;
}

// Airport data
const airports = [
    { code: 'JFK', name: 'John F. Kennedy International Airport', city: 'New York' },
    { code: 'LAX', name: 'Los Angeles International Airport', city: 'Los Angeles' },
    { code: 'LHR', name: 'London Heathrow Airport', city: 'London' },
    { code: 'CDG', name: 'Charles de Gaulle Airport', city: 'Paris' },
    { code: 'NBO', name: 'Jomo Kenyatta International Airport', city: 'Nairobi' },
    { code: 'DXB', name: 'Dubai International Airport', city: 'Dubai' }
];
</script>

<style>
.animation-delay-1000 { animation-delay: 1s; }
</style>
@endsection