@extends('layouts.main')

@section('styles')
<style>
    /* Custom animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeOutDown {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }
    
    .animate-fade-out-down {
        animation: fadeOutDown 0.3s ease-out forwards;
    }
    
    .modal-overlay {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }
    
    /* Timeline styles */
    .timeline-step.active .timeline-circle {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    
    .timeline-step.active .timeline-text {
        color: #4f46e5;
        font-weight: 600;
    }
    
    .timeline-step.completed .timeline-circle {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    
    .timeline-line {
        top: 24px;
        height: 3px;
        transform: translateY(-50%);
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Booking Progress Timeline -->
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="relative flex items-center justify-between">
                <!-- Timeline Line -->
                <div class="absolute timeline-line left-0 w-full bg-gray-200"></div>
                
                <!-- Step 1: Search -->
                <div class="timeline-step completed relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center text-white">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium text-gray-600">Search</span>
                </div>
                
                <!-- Step 2: Select -->
                <div class="timeline-step completed relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center text-white">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium text-gray-600">Select</span>
                </div>
                
                <!-- Step 3: Book -->
                <div class="timeline-step active relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full bg-white border-4 border-gray-300 flex items-center justify-center text-indigo-600">
                        <i data-lucide="clipboard" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium">Book</span>
                </div>
                
                <!-- Step 4: Pay -->
                <div class="timeline-step relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full bg-white border-4 border-gray-300 flex items-center justify-center text-gray-400">
                        <i data-lucide="credit-card" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium text-gray-400">Pay</span>
                </div>
                
                <!-- Step 5: Confirm -->
                <div class="timeline-step relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full bg-white border-4 border-gray-300 flex items-center justify-center text-gray-400">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium text-gray-400">Confirm</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Package Information -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                    <!-- Package Images Gallery -->
                    <div class="relative h-80">
                        <div class="swiper-container h-full">
                            <div class="absolute inset-0">
                                <img src="{{ asset('images/packages/' . ($package['image'] ?? 'default-package.jpg')) }}" 
                                     alt="{{ $package['name'] ?? 'Package Image' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Package Gallery Navigation -->
                            <div class="absolute bottom-4 right-4 flex space-x-2">
                                <button class="bg-black/60 hover:bg-black/80 text-white p-2 rounded-full backdrop-blur-sm">
                                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                                </button>
                                <button class="bg-black/60 hover:bg-black/80 text-white p-2 rounded-full backdrop-blur-sm">
                                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                                </button>
                            </div>
                            
                            <!-- View All Photos Button -->
                            <button class="absolute top-4 right-4 bg-black/60 hover:bg-black/80 text-white px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm flex items-center">
                                <i data-lucide="image" class="w-4 h-4 mr-2"></i>
                                View All Photos
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Package Name and Rating -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $package['name'] ?? 'East African Adventure' }}</h1>
                                <p class="text-gray-600 flex items-center mt-1">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    {{ $package['destination'] ?? 'Destination' }}, {{ $package['country'] ?? 'Country' }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col items-end">
                                <!-- Rating -->
                                <div class="flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 rounded-lg text-sm">
                                    <i data-lucide="star" class="w-4 h-4 fill-current mr-1"></i>
                                    <span>{{ $package['rating'] ?? '4.8' }}</span>
                                </div>
                                
                                <!-- Reviews -->
                                <span class="text-sm text-gray-600 mt-1">
                                    {{ $package['reviewCount'] ?? '124' }} reviews
                                </span>
                            </div>
                        </div>
                        
                        <!-- Package Type & Duration -->
                        <div class="flex flex-wrap gap-3 mb-6">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                                {{ $package['type'] ?? 'Adventure Package' }}
                            </span>
                            
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                                {{ $package['duration'] ?? '7 days' }}
                            </span>
                            
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                                Max {{ $package['groupSize'] ?? '12' }} people
                            </span>
                            
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="languages" class="w-4 h-4 mr-1"></i>
                                {{ $package['languages'] ?? 'English' }}
                            </span>
                        </div>
                        
                        <!-- Package Description -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold mb-3">About This Package</h2>
                            <p class="text-gray-700 mb-4">
                                {{ $package['description'] ?? 'Experience the best of East Africa with this comprehensive package that includes accommodation, transportation, activities, and more. You\'ll explore vibrant cities, discover breathtaking landscapes, encounter incredible wildlife, and immerse yourself in local cultures. Our expert guides will ensure you have an unforgettable journey through this amazing region.' }}
                            </p>
                            
                            <!-- Read More Button -->
                            <button class="text-indigo-600 font-medium flex items-center">
                                Read more
                                <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                            </button>
                        </div>
                        
                        <!-- Package Highlights -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold mb-3">Package Highlights</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($package['highlights'] ?? ['Premium accommodation in central locations', 'All transportation including airport transfers', 'Daily breakfast and selected meals', 'Guided tours of major attractions', 'Authentic cultural experiences', 'Entrance fees to all attractions'] as $highlight)
                                    <div class="flex items-start">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-indigo-500 mr-2 shrink-0"></i>
                                        <span>{{ $highlight }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- What's Included -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold mb-3">What's Included</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($package['included'] ?? ['Hotel accommodation (6 nights)', 'All transportation in destination', 'Airport pickup and drop-off', 'Daily breakfast', '3 lunches and 2 dinners', 'Guided sightseeing tours', 'Entrance fees', '24/7 customer support'] as $included)
                                    <div class="flex items-start">
                                        <i data-lucide="check" class="w-5 h-5 text-indigo-600 mr-2 shrink-0"></i>
                                        <span>{{ $included }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- What's Not Included -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold mb-3">Not Included</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($package['notIncluded'] ?? ['International flights', 'Travel insurance', 'Some meals (as specified in itinerary)', 'Personal expenses', 'Optional activities', 'Visa fees if applicable'] as $notIncluded)
                                    <div class="flex items-start">
                                        <i data-lucide="x" class="w-5 h-5 text-red-500 mr-2 shrink-0"></i>
                                        <span>{{ $notIncluded }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Itinerary Overview -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold mb-3">Itinerary Overview</h2>
                            
                            <div class="space-y-6">
                                @foreach($package['itinerary'] ?? [
                                    ['day' => 'Day 1', 'title' => 'Arrival & Welcome Dinner', 'description' => 'Airport pickup and transfer to your hotel. Evening welcome dinner with your guide and group.'],
                                    ['day' => 'Day 2', 'title' => 'City Tour', 'description' => 'Full day exploring the city\'s highlights including museums, markets, and historical sites.'],
                                    ['day' => 'Day 3', 'title' => 'Cultural Experience', 'description' => 'Visit local villages and participate in traditional activities. Authentic lunch with local families.'],
                                    ['day' => 'Day 4', 'title' => 'Nature & Wildlife', 'description' => 'Full day excursion to nearby national park or nature reserve for wildlife viewing.'],
                                    ['day' => 'Day 5', 'title' => 'Free Day', 'description' => 'Free day to relax or explore on your own. Optional activities available.'],
                                    ['day' => 'Day 6', 'title' => 'Adventure Activities', 'description' => 'Choose between hiking, water activities, or other adventures. Evening farewell dinner.'],
                                    ['day' => 'Day 7', 'title' => 'Departure', 'description' => 'Free morning before airport transfer for your departure flight.']
                                ] as $day)
                                    <div class="flex">
                                        <!-- Day indicator -->
                                        <div class="mr-4">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-800 font-semibold text-sm">
                                                {{ substr($day['day'], 4) }}
                                            </div>
                                            @if(!$loop->last)
                                                <div class="h-full w-px bg-gray-200 mx-auto my-2"></div>
                                            @endif
                                        </div>
                                        
                                        <!-- Day content -->
                                        <div class="flex-1">
                                            <h3 class="font-semibold">{{ $day['day'] }}: {{ $day['title'] }}</h3>
                                            <p class="text-gray-600 text-sm">{{ $day['description'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Cancellation Policy -->
                        <div>
                            <h2 class="text-lg font-semibold mb-3">Cancellation Policy</h2>
                            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <p class="text-gray-700">
                                    {{ $package['cancellationPolicy'] ?? 'Free cancellation up to 30 days before departure for a full refund. 15-29 days before departure: 50% refund. Less than 15 days before departure: no refund. We recommend purchasing travel insurance for unexpected circumstances.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Available Dates -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Available Departure Dates</h2>
                        
                        <!-- Date Selector -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
                            @foreach($package['dates'] ?? [
                                ['date' => '2023-11-15', 'availability' => 'available', 'spotsLeft' => 8],
                                ['date' => '2023-12-05', 'availability' => 'available', 'spotsLeft' => 4],
                                ['date' => '2023-12-20', 'availability' => 'limited', 'spotsLeft' => 2],
                                ['date' => '2024-01-10', 'availability' => 'available', 'spotsLeft' => 12],
                                ['date' => '2024-01-25', 'availability' => 'available', 'spotsLeft' => 6],
                                ['date' => '2024-02-08', 'availability' => 'limited', 'spotsLeft' => 3],
                                ['date' => '2024-02-22', 'availability' => 'available', 'spotsLeft' => 10],
                                ['date' => '2024-03-15', 'availability' => 'available', 'spotsLeft' => 8]
                            ] as $availableDate)
                                @php
                                    $date = \Carbon\Carbon::parse($availableDate['date']);
                                    $colorClass = $availableDate['availability'] === 'available' 
                                        ? 'border-gray-300 hover:border-indigo-500' 
                                        : ($availableDate['availability'] === 'limited' 
                                            ? 'border-yellow-400 bg-yellow-50' 
                                            : 'border-red-300 bg-red-50 opacity-50');
                                    $isDisabled = $availableDate['availability'] === 'soldout';
                                @endphp
                                
                                <button type="button" class="departureDateBtn px-4 py-3 border rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-indigo-500 {{ $colorClass }}" data-date="{{ $availableDate['date'] }}" {{ $isDisabled ? 'disabled' : '' }}>
                                    <div class="font-medium">{{ $date->format('M d, Y') }}</div>
                                    <div class="text-xs mt-1 {{ $availableDate['availability'] === 'limited' ? 'text-yellow-700' : 'text-gray-500' }}">
                                        @if($availableDate['availability'] === 'soldout')
                                            Sold Out
                                        @else
                                            {{ $availableDate['spotsLeft'] }} spots left
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        </div>
                        
                        <p class="text-sm text-gray-500 italic mb-3">
                            * More dates may be available for custom group bookings. Contact us for details.
                        </p>
                    </div>
                </div>
                
                <!-- Traveler Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Traveler Information</h2>
                        
                        <form id="packageBookingForm" action="{{ route('booking.proceed') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_type" value="package">
                            <input type="hidden" name="package_id" value="{{ $package['id'] ?? '' }}">
                            <input type="hidden" name="package_data" value="{{ json_encode($package ?? []) }}">
                            <input type="hidden" name="departure_date" id="departureDateInput" value="">
                            
                            <!-- Number of Travelers -->
                            <div class="mb-6">
                                <label for="travelers" class="block text-sm font-medium text-gray-700 mb-1">Number of Travelers</label>
                                <div class="flex items-center">
                                    <button type="button" id="decreaseTravelers" class="px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 hover:bg-gray-100">
                                        <i data-lucide="minus" class="w-5 h-5 text-gray-600"></i>
                                    </button>
                                    <input type="number" id="travelers" name="travelers" min="1" max="{{ $package['maxGroupSize'] ?? 12 }}" value="2" class="w-16 px-4 py-2 border-y border-gray-300 text-center focus:outline-none">
                                    <button type="button" id="increaseTravelers" class="px-3 py-2 border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100">
                                        <i data-lucide="plus" class="w-5 h-5 text-gray-600"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Lead Traveler -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Lead Traveler</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- First Name -->
                                    <div>
                                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name*</label>
                                        <input type="text" id="firstName" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                    
                                    <!-- Last Name -->
                                    <div>
                                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name*</label>
                                        <input type="text" id="lastName" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                    
                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address*</label>
                                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                    
                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number*</label>
                                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Additional Information</h3>
                                
                                <div class="mb-4">
                                    <label for="specialRequests" class="block text-sm font-medium text-gray-700 mb-1">Special Requests (optional)</label>
                                    <textarea id="specialRequests" name="special_requests" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Dietary requirements, accessibility needs, or other special requests..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Special requests are subject to availability and cannot be guaranteed.</p>
                                </div>
                                
                                <div>
                                    <label for="hearAboutUs" class="block text-sm font-medium text-gray-700 mb-1">How did you hear about us?</label>
                                    <select id="hearAboutUs" name="hear_about_us" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Please select</option>
                                        <option value="search">Search Engine</option>
                                        <option value="social">Social Media</option>
                                        <option value="friend">Friend Recommendation</option>
                                        <option value="advertisement">Advertisement</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="terms" name="terms" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" required>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a> and <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>.</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Continue to Payment -->
                            <div class="flex justify-end">
                                <button type="submit" id="continueToPayment" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center opacity-50 cursor-not-allowed" disabled>
                                    Continue to Payment
                                    <i data-lucide="chevron-right" class="w-5 h-5 ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Price Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Booking Summary</h2>
                        
                        <!-- Package Info Summary -->
                        <div class="flex items-center mb-6">
                            <div class="w-24 h-24 rounded-lg overflow-hidden shrink-0">
                                <img src="{{ asset('images/packages/' . ($package['image'] ?? 'default-package.jpg')) }}" alt="{{ $package['name'] ?? 'Package' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">{{ $package['name'] ?? 'Package Name' }}</h3>
                                <p class="text-sm text-gray-600 mt-1 flex items-center">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    {{ $package['destination'] ?? 'Destination' }}
                                </p>
                                <div class="flex items-center mt-1">
                                    <i data-lucide="star" class="w-4 h-4 text-yellow-500 fill-current"></i>
                                    <span class="ml-1 text-sm">{{ $package['rating'] ?? '4.8' }} ({{ $package['reviewCount'] ?? '124' }} reviews)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date & Duration Info -->
                        <div class="border-t border-b border-gray-200 py-4 mb-4">
                            <div id="dateTimeInfo">
                                <div class="flex items-start mb-3">
                                    <i data-lucide="calendar" class="w-5 h-5 text-gray-500 mr-3 shrink-0"></i>
                                    <div>
                                        <p class="font-medium">Departure Date</p>
                                        <p id="selectedDate" class="text-gray-600">Please select a date</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-500 mr-3 shrink-0"></i>
                                    <div>
                                        <p class="font-medium">Duration</p>
                                        <p class="text-gray-600">{{ $package['duration'] ?? '7 days' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="mb-6">
                            <h3 class="font-semibold mb-3">Price Details</h3>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Package Price (per person)</span>
                                    <span>{{ $package['currency'] ?? 'USD' }} {{ number_format($package['price'] ?? 999) }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Number of travelers</span>
                                    <span id="travelerCount">2</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span id="subtotal">
                                        {{ $package['currency'] ?? 'USD' }} {{ number_format(($package['price'] ?? 999) * 2) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Taxes & Fees (12%)</span>
                                    <span id="taxesFees">
                                        @php
                                            $taxes = ($package['price'] ?? 999) * 2 * 0.12;
                                        @endphp
                                        {{ $package['currency'] ?? 'USD' }} {{ number_format($taxes) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between border-t border-gray-200 pt-2 mt-2 font-semibold">
                                    <span>Total</span>
                                    <span id="totalPrice">
                                        @php
                                            $total = ($package['price'] ?? 999) * 2 + $taxes;
                                        @endphp
                                        {{ $package['currency'] ?? 'USD' }} {{ number_format($total) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Package Policies -->
                        <div class="mb-6">
                            <h3 class="font-semibold mb-3">Policies</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-indigo-600 mr-2 shrink-0"></i>
                                    <span>{{ $package['cancellationPolicySummary'] ?? 'Free cancellation up to 30 days before' }}</span>
                                </div>
                                
                                <div class="flex items-start">
                                    <i data-lucide="credit-card" class="w-4 h-4 text-indigo-600 mr-2 shrink-0"></i>
                                    <span>Reserve now, pay later</span>
                                </div>
                                
                                <div class="flex items-start">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-indigo-600 mr-2 shrink-0"></i>
                                    <span>24/7 customer support</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center py-6" id="selectDateMessage">
                            <i data-lucide="info" class="w-12 h-12 text-gray-300 mx-auto"></i>
                            <p class="text-gray-500 mt-2">Please select a departure date to continue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registration Modal -->
    <div id="registrationModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity modal-overlay" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-fade-in-up">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Create an Account to Continue
                            </h3>
                            
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">
                                    Please create an account to continue with your booking. It only takes a minute!
                                </p>
                            </div>
                            
                            <!-- Registration Form -->
                            <form method="POST" action="{{ route('register') }}" id="quickRegisterForm">
                                @csrf
                                <input type="hidden" name="redirect_to" value="booking">
                                
                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                
                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                
                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                
                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" name="password" id="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                
                                <!-- Terms Checkbox -->
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="terms" name="terms" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a>.</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-6">
                                    <div class="text-sm">
                                        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            Already have an account? Login
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="registerSubmitBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Register & Continue
                    </button>
                    <button type="button" id="modalCloseBtn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Date selection functionality
        const departureDateBtns = document.querySelectorAll('.departureDateBtn');
        const selectedDate = document.getElementById('selectedDate');
        const departureDateInput = document.getElementById('departureDateInput');
        const selectDateMessage = document.getElementById('selectDateMessage');
        const continueToPayment = document.getElementById('continueToPayment');
        
        departureDateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                departureDateBtns.forEach(b => {
                    b.classList.remove('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                    
                    if (!b.classList.contains('border-yellow-400') && !b.classList.contains('border-red-300')) {
                        b.classList.add('border-gray-300');
                    }
                });
                
                // Add active class to selected button (if not already styled for limited availability)
                if (!this.classList.contains('border-yellow-400') && !this.classList.contains('border-red-300')) {
                    this.classList.remove('border-gray-300');
                    this.classList.add('border-indigo-600', 'bg-indigo-50', 'text-indigo-700');
                }
                
                // Update selected date
                const date = this.dataset.date;
                const formattedDate = new Date(date).toLocaleDateString('en-US', { 
                    weekday: 'long',
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                
                selectedDate.textContent = formattedDate;
                departureDateInput.value = date;
                
                // Hide message and enable continue button
                selectDateMessage.style.display = 'none';
                continueToPayment.classList.remove('opacity-50', 'cursor-not-allowed');
                continueToPayment.disabled = false;
            });
        });
        
        // Traveler count functionality
        const decreaseTravelersBtn = document.getElementById('decreaseTravelers');
        const increaseTravelersBtn = document.getElementById('increaseTravelers');
        const travelersInput = document.getElementById('travelers');
        const travelerCount = document.getElementById('travelerCount');
        const subtotal = document.getElementById('subtotal');
        const taxesFees = document.getElementById('taxesFees');
        const totalPrice = document.getElementById('totalPrice');
        
        // Update price calculations based on traveler count
        function updatePrices() {
            const price = {{ $package['price'] ?? 999 }};
            const travelers = parseInt(travelersInput.value);
            const currency = '{{ $package['currency'] ?? 'USD' }}';
            
            travelerCount.textContent = travelers;
            
            const subtotalValue = price * travelers;
            subtotal.textContent = `${currency} ${subtotalValue.toLocaleString()}`;
            
            const taxesValue = subtotalValue * 0.12;
            taxesFees.textContent = `${currency} ${taxesValue.toLocaleString()}`;
            
            const totalValue = subtotalValue + taxesValue;
            totalPrice.textContent = `${currency} ${totalValue.toLocaleString()}`;
        }
        
        decreaseTravelersBtn.addEventListener('click', function() {
            const currentValue = parseInt(travelersInput.value);
            if (currentValue > 1) {
                travelersInput.value = currentValue - 1;
                updatePrices();
            }
        });
        
        increaseTravelersBtn.addEventListener('click', function() {
            const currentValue = parseInt(travelersInput.value);
            const maxValue = {{ $package['maxGroupSize'] ?? 12 }};
            if (currentValue < maxValue) {
                travelersInput.value = currentValue + 1;
                updatePrices();
            }
        });
        
        // Update prices if traveler input changes directly
        travelersInput.addEventListener('change', function() {
            const min = parseInt(this.min);
            const max = parseInt(this.max);
            let value = parseInt(this.value);
            
            if (isNaN(value) || value < min) {
                value = min;
            } else if (value > max) {
                value = max;
            }
            
            this.value = value;
            updatePrices();
        });
        
        // Handle package booking form submission
        const packageBookingForm = document.getElementById('packageBookingForm');
        const registrationModal = document.getElementById('registrationModal');
        
        packageBookingForm.addEventListener('submit', function(event) {
            // Check if a departure date has been selected
            if (!departureDateInput.value) {
                event.preventDefault();
                alert('Please select a departure date.');
                return;
            }
            
            // Check if user is logged in
            const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
            
            if (!isLoggedIn) {
                event.preventDefault();
                registrationModal.classList.remove('hidden');
            }
            // If user is logged in, form submits normally
        });
        
        // Modal functionality
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        modalCloseBtn.addEventListener('click', function() {
            registrationModal.classList.add('hidden');
        });
        
        // Registration form submission
        const quickRegisterForm = document.getElementById('quickRegisterForm');
        const registerSubmitBtn = document.getElementById('registerSubmitBtn');
        
        registerSubmitBtn.addEventListener('click', function() {
            // Trigger form submission
            quickRegisterForm.submit();
        });
    });
    
    // Toast notification functionality
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white transform transition-all duration-300 translate-y-20 opacity-0`;
        
        toast.innerHTML = `
            <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-2"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Initialize the Lucide icon in the toast
        lucide.createIcons();
        
        // Show toast with animation
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
        }, 100);
        
        // Hide toast after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            
            // Remove from DOM after animation completes
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 5000);
    }
    
    // Show success toast if redirected with success message
    @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('success') }}', 'success');
        });
    @endif
    
    // Show error toast if redirected with error message
    @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            showToast('{{ session('error') }}', 'error');
        });
    @endif
</script>
@endsection
