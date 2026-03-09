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
            <!-- Left Column: Hotel Information -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                    <!-- Hotel Images Gallery -->
                    <div class="relative h-80">
                        <div class="swiper-container h-full">
                            <div class="absolute inset-0">
                                <img src="{{ asset('images/hotels/' . ($hotel['hotelImage'] ?? 'default-hotel.jpg')) }}" 
                                     alt="{{ $hotel['hotelName'] ?? 'Hotel Image' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Hotel Gallery Navigation -->
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
                        <!-- Hotel Name and Rating -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $hotel['hotelName'] ?? 'Hotel Name' }}</h1>
                                <p class="text-gray-600 flex items-center mt-1">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    {{ $hotel['location'] ?? 'Location' }}, {{ $hotel['city'] ?? 'City' }}, {{ $hotel['country'] ?? 'Country' }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col items-end">
                                <!-- Star Rating -->
                                <div class="flex items-center mb-1">
                                    @for($i = 0; $i < ($hotel['stars'] ?? 5); $i++)
                                        <i data-lucide="star" class="w-5 h-5 text-yellow-500 fill-current"></i>
                                    @endfor
                                </div>
                                
                                <!-- Reviews -->
                                <span class="text-sm text-gray-600">
                                    {{ $hotel['reviewCount'] ?? '245' }} reviews
                                </span>
                            </div>
                        </div>
                        
                        <!-- Hotel Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Property Highlights -->
                            <div>
                                <h3 class="text-lg font-semibold mb-3">Property Highlights</h3>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2 shrink-0"></i>
                                        <span>{{ $hotel['hotelType'] ?? 'Hotel Type' }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2 shrink-0"></i>
                                        <span>{{ $hotel['meals'] ?? 'Breakfast included' }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2 shrink-0"></i>
                                        <span>{{ $hotel['wifi'] ?? 'Free WiFi' }}</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2 shrink-0"></i>
                                        <span>{{ $hotel['cancellation'] ?? 'Free cancellation before check-in' }}</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Room Amenities -->
                            <div>
                                <h3 class="text-lg font-semibold mb-3">Room Amenities</h3>
                                <ul class="space-y-2">
                                    <li class="flex items-start">
                                        <i data-lucide="check" class="w-5 h-5 text-indigo-600 mr-2 shrink-0"></i>
                                        <span>Air conditioning</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i data-lucide="check" class="w-5 h-5 text-indigo-600 mr-2 shrink-0"></i>
                                        <span>Private bathroom</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i data-lucide="check" class="w-5 h-5 text-indigo-600 mr-2 shrink-0"></i>
                                        <span>Flat-screen TV</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i data-lucide="check" class="w-5 h-5 text-indigo-600 mr-2 shrink-0"></i>
                                        <span>Safe deposit box</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-3">Description</h3>
                            <p class="text-gray-700">
                                {{ $hotel['description'] ?? 'Located in the heart of the city, this premium hotel offers luxurious accommodations with modern amenities. Guests can enjoy breathtaking views, world-class dining, and exceptional service throughout their stay. The property features a fitness center, spa, and swimming pool for relaxation and recreation. With its prime location, guests have easy access to major attractions, shopping districts, and business centers.' }}
                            </p>
                        </div>
                        
                        <!-- Hotel Policies -->
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Hotel Policies</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium">Check-in</p>
                                        <p class="text-gray-600">From {{ $hotel['checkInTime'] ?? '14:00' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium">Check-out</p>
                                        <p class="text-gray-600">Until {{ $hotel['checkOutTime'] ?? '12:00' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="users" class="w-5 h-5 text-gray-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium">Children</p>
                                        <p class="text-gray-600">{{ $hotel['childPolicy'] ?? 'Children of all ages are welcome' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i data-lucide="paw-print" class="w-5 h-5 text-gray-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm font-medium">Pets</p>
                                        <p class="text-gray-600">{{ $hotel['petPolicy'] ?? 'Pets are not allowed' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Room Type Selection -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Available Room Types</h2>
                        
                        <!-- Room Options -->
                        <div class="space-y-6">
                            <!-- Room Option 1 -->
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition-colors">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Room Image -->
                                    <div class="md:w-1/3 h-48 md:h-auto">
                                        <img src="{{ asset('images/hotels/rooms/standard-room.jpg') }}" alt="Standard Room" class="w-full h-full object-cover">
                                    </div>
                                    
                                    <!-- Room Details -->
                                    <div class="md:w-2/3 p-4 flex flex-col">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-lg font-semibold">Standard Room</h3>
                                                    <p class="text-sm text-gray-600 mb-2">1 queen bed • 25m² • City view</p>
                                                </div>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Available</span>
                                            </div>
                                            
                                            <!-- Room Features -->
                                            <div class="flex flex-wrap gap-2 my-2">
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                                    <i data-lucide="wifi" class="w-3 h-3 mr-1"></i>
                                                    Free WiFi
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                                    <i data-lucide="coffee" class="w-3 h-3 mr-1"></i>
                                                    Breakfast
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                                    <i data-lucide="ban" class="w-3 h-3 mr-1"></i>
                                                    Non-smoking
                                                </span>
                                            </div>
                                            
                                            <div class="text-sm text-gray-600 mt-2">
                                                <p>1-2 guests • Non-refundable</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Price and Select Button -->
                                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                            <div>
                                                <p class="text-gray-500 text-sm line-through">{{ $hotel['currency'] ?? 'KES' }} {{ number_format(($hotel['originalPrice'] ?? 15000)) }}</p>
                                                <p class="text-xl font-bold text-indigo-600">{{ $hotel['currency'] ?? 'KES' }} {{ number_format(($hotel['price'] ?? 12000)) }}</p>
                                                <p class="text-gray-500 text-sm">per night, includes taxes</p>
                                            </div>
                                            
                                            <button type="button" class="roomSelectBtn px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors" data-room-type="standard" data-room-price="{{ $hotel['price'] ?? 12000 }}">
                                                Select
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Room Option 2 -->
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-indigo-500 transition-colors">
                                <div class="flex flex-col md:flex-row">
                                    <!-- Room Image -->
                                    <div class="md:w-1/3 h-48 md:h-auto">
                                        <img src="{{ asset('images/hotels/rooms/deluxe-room.jpg') }}" alt="Deluxe Room" class="w-full h-full object-cover">
                                    </div>
                                    
                                    <!-- Room Details -->
                                    <div class="md:w-2/3 p-4 flex flex-col">
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="text-lg font-semibold">Deluxe Room</h3>
                                                    <p class="text-sm text-gray-600 mb-2">1 king bed • 35m² • Ocean view</p>
                                                </div>
                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Available</span>
                                            </div>
                                            
                                            <!-- Room Features -->
                                            <div class="flex flex-wrap gap-2 my-2">
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                                    <i data-lucide="wifi" class="w-3 h-3 mr-1"></i>
                                                    Free WiFi
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                                    <i data-lucide="coffee" class="w-3 h-3 mr-1"></i>
                                                    Breakfast
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                                    <i data-lucide="bath" class="w-3 h-3 mr-1"></i>
                                                    Bathtub
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                                    <i data-lucide="ban" class="w-3 h-3 mr-1"></i>
                                                    Non-smoking
                                                </span>
                                            </div>
                                            
                                            <div class="text-sm text-gray-600 mt-2">
                                                <p>1-2 guests • Free cancellation 24h before check-in</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Price and Select Button -->
                                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                                            <div>
                                                <p class="text-gray-500 text-sm line-through">{{ $hotel['currency'] ?? 'KES' }} {{ number_format(($hotel['originalPrice'] ?? 20000) + 5000) }}</p>
                                                <p class="text-xl font-bold text-indigo-600">{{ $hotel['currency'] ?? 'KES' }} {{ number_format(($hotel['price'] ?? 12000) + 5000) }}</p>
                                                <p class="text-gray-500 text-sm">per night, includes taxes</p>
                                            </div>
                                            
                                            <button type="button" class="roomSelectBtn px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors" data-room-type="deluxe" data-room-price="{{ ($hotel['price'] ?? 12000) + 5000 }}">
                                                Select
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Form -->
                <div id="bookingForm" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" style="display: none;">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Guest Information</h2>
                        
                        <form id="hotelBookingForm" action="{{ route('booking.proceed') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_type" value="hotel">
                            <input type="hidden" name="hotel_id" value="{{ $hotel['id'] ?? '' }}">
                            <input type="hidden" name="hotel_data" value="{{ json_encode($hotel ?? []) }}">
                            <input type="hidden" name="room_type" id="roomTypeInput" value="">
                            <input type="hidden" name="room_price" id="roomPriceInput" value="">
                            
                            <!-- Guest Details -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Primary Guest</h3>
                                
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
                            
                            <!-- Special Requests -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Special Requests</h3>
                                
                                <div>
                                    <label for="specialRequests" class="block text-sm font-medium text-gray-700 mb-1">Special Requests (optional)</label>
                                    <textarea id="specialRequests" name="special_requests" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Let the hotel know about any special requirements..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Special requests are subject to availability and cannot be guaranteed.</p>
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
                                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center">
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
                        
                        <!-- Hotel Info Summary -->
                        <div class="flex items-center mb-6">
                            <div class="w-24 h-24 rounded-lg overflow-hidden shrink-0">
                                <img src="{{ asset('images/hotels/' . ($hotel['hotelImage'] ?? 'default-hotel.jpg')) }}" alt="{{ $hotel['hotelName'] ?? 'Hotel' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-800">{{ $hotel['hotelName'] ?? 'Hotel Name' }}</h3>
                                <div class="flex items-center mt-1">
                                    @for($i = 0; $i < ($hotel['stars'] ?? 5); $i++)
                                        <i data-lucide="star" class="w-4 h-4 text-yellow-500 fill-current"></i>
                                    @endfor
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $hotel['location'] ?? 'Location' }}, {{ $hotel['city'] ?? 'City' }}</p>
                            </div>
                        </div>
                        
                        <!-- Date Info -->
                        <div class="border-t border-b border-gray-200 py-4 mb-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Check-in</p>
                                    <p class="font-semibold">{{ $searchParams['check_in_date'] ?? date('Y-m-d') }}</p>
                                    <p class="text-sm text-gray-500">From {{ $hotel['checkInTime'] ?? '14:00' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Check-out</p>
                                    <p class="font-semibold">{{ $searchParams['check_out_date'] ?? date('Y-m-d', strtotime('+2 days')) }}</p>
                                    <p class="text-sm text-gray-500">Until {{ $hotel['checkOutTime'] ?? '12:00' }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Total length of stay:</p>
                                <p class="font-semibold">
                                    @php
                                        $checkIn = $searchParams['check_in_date'] ?? date('Y-m-d');
                                        $checkOut = $searchParams['check_out_date'] ?? date('Y-m-d', strtotime('+2 days'));
                                        $nights = date_diff(date_create($checkIn), date_create($checkOut))->days;
                                    @endphp
                                    {{ $nights }} night(s)
                                </p>
                            </div>
                        </div>
                        
                        <!-- Room Selection -->
                        <div class="mb-6">
                            <p class="text-sm text-gray-500">Selected Room</p>
                            <p id="selectedRoomType" class="font-semibold text-gray-800">Please select a room</p>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="mb-6" id="priceBreakdown" style="display: none;">
                            <h3 class="font-semibold mb-2">Price Details</h3>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Room Rate</span>
                                    <span id="roomRate">{{ $hotel['currency'] ?? 'KES' }} {{ number_format($hotel['price'] ?? 12000) }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Number of nights</span>
                                    <span>{{ $nights }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Room Charge</span>
                                    <span id="roomCharge">
                                        {{ $hotel['currency'] ?? 'KES' }} {{ number_format(($hotel['price'] ?? 12000) * $nights) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Taxes & Fees (16%)</span>
                                    <span id="taxesFees">
                                        @php
                                            $taxes = ($hotel['price'] ?? 12000) * $nights * 0.16;
                                        @endphp
                                        {{ $hotel['currency'] ?? 'KES' }} {{ number_format($taxes) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between border-t border-gray-200 pt-2 mt-2 font-semibold">
                                    <span>Total</span>
                                    <span id="totalPrice">
                                        @php
                                            $total = ($hotel['price'] ?? 12000) * $nights + $taxes;
                                        @endphp
                                        {{ $hotel['currency'] ?? 'KES' }} {{ number_format($total) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center py-6" id="selectRoomMessage">
                            <i data-lucide="info" class="w-12 h-12 text-gray-300 mx-auto"></i>
                            <p class="text-gray-500 mt-2">Please select a room to continue</p>
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
        
        // Room selection functionality
        const roomSelectBtns = document.querySelectorAll('.roomSelectBtn');
        const selectedRoomType = document.getElementById('selectedRoomType');
        const roomTypeInput = document.getElementById('roomTypeInput');
        const roomPriceInput = document.getElementById('roomPriceInput');
        const bookingForm = document.getElementById('bookingForm');
        const priceBreakdown = document.getElementById('priceBreakdown');
        const selectRoomMessage = document.getElementById('selectRoomMessage');
        const roomRate = document.getElementById('roomRate');
        const roomCharge = document.getElementById('roomCharge');
        const taxesFees = document.getElementById('taxesFees');
        const totalPrice = document.getElementById('totalPrice');
        
        roomSelectBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const roomType = this.dataset.roomType;
                const roomPrice = this.dataset.roomPrice;
                const formattedRoomType = roomType.charAt(0).toUpperCase() + roomType.slice(1) + ' Room';
                
                // Update hidden inputs
                roomTypeInput.value = roomType;
                roomPriceInput.value = roomPrice;
                
                // Update displayed room type
                selectedRoomType.textContent = formattedRoomType;
                
                // Show booking form and price breakdown
                bookingForm.style.display = 'block';
                priceBreakdown.style.display = 'block';
                selectRoomMessage.style.display = 'none';
                
                // Update price breakdown
                const nights = {{ $nights }};
                const currency = '{{ $hotel['currency'] ?? 'KES' }}';
                
                roomRate.textContent = `${currency} ${Number(roomPrice).toLocaleString()}`;
                
                const totalRoomCharge = Number(roomPrice) * nights;
                roomCharge.textContent = `${currency} ${totalRoomCharge.toLocaleString()}`;
                
                const totalTaxes = totalRoomCharge * 0.16;
                taxesFees.textContent = `${currency} ${totalTaxes.toLocaleString()}`;
                
                const finalTotal = totalRoomCharge + totalTaxes;
                totalPrice.textContent = `${currency} ${finalTotal.toLocaleString()}`;
                
                // Scroll to booking form
                bookingForm.scrollIntoView({ behavior: 'smooth' });
            });
        });
        
        // Handle hotel booking form submission
        const hotelBookingForm = document.getElementById('hotelBookingForm');
        const registrationModal = document.getElementById('registrationModal');
        
        hotelBookingForm.addEventListener('submit', function(event) {
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
