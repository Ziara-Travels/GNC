@extends('layouts.main')

@section('content')
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
        background-color: #6b2c1f;
        border-color: #6b2c1f;
    }

    .timeline-step.active .timeline-text {
        color: #6b2c1f;
        font-weight: 600;
    }

    .timeline-step.completed .timeline-circle {
        background-color: #c9a227;
        border-color: #c9a227;
    }

    .timeline-line {
        top: 24px;
        height: 3px;
        transform: translateY(-50%);
    }
</style>
    <style>
        .ziara-flow-booking { background: #fdf8eb; }
        .ziara-flow-booking__bar { background: #fff; border-bottom: 1px solid #eadfca; box-shadow: 0 2px 12px rgba(74,29,19,.08); }
        .ziara-flow-booking__panel { background: #fff; border: 1px solid #eadfca; border-radius: 16px; box-shadow: 0 2px 12px rgba(74,29,19,.10); }
        .ziara-flow-booking__head { border-bottom: 1px solid #eadfca; background: #fdf6df; }
        .ziara-flow-booking__head h2 { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; color: #4a1d13; }
        .ziara-flow-booking__head p { color: #7a6a60; }
        .ziara-flow-booking__tag { background: #fdf6df; color: #6b1d15; border: 1px solid rgba(201,162,39,.4); }
        .ziara-flow-booking__rating { background: #fdf6df; color: #6b1d15; border: 1px solid rgba(201,162,39,.4); }
        .ziara-flow-booking__icon { color: #c9a227; }
        .ziara-flow-booking__price { color: #6b2c1f; }
        .ziara-flow-booking__secure { background: #fdf8eb; border: 1px solid #eadfca; border-radius: 14px; }
        .ziara-flow-booking__cta { background: #6b2c1f; color: #fff; border-radius: 10px; }
        .ziara-flow-booking__cta:hover { background: #4a1d13; }
        .ziara-flow-booking__cta:disabled { background: #a8968a; }
        .ziara-flow-booking__link { color: #7b2a1b; }
        .ziara-flow-booking__link:hover { color: #a8861a; }
        .ziara-flow-booking .border-teal-600 { border-color: #c9a227 !important; }
        .ziara-flow-booking .bg-teal-50 { background-color: #fdf6df !important; }
        .ziara-flow-booking .text-teal-700 { color: #6b1d15 !important; }
        .ziara-flow-booking__title { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; color: #4a1d13; }
        .ziara-flow-booking__subtitle { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; color: #4a1d13; }
        .ziara-flow-booking__modal-title { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; color: #4a1d13; }
        .ziara-flow-booking__btn-ghost { background: #fff; color: #4a1d13; border-color: #eadfca; }
        .ziara-flow-booking__btn-ghost:hover { background: #fdf8eb; border-color: #c9a227; }        .ziara-flow-booking__modal { background: #fff; border: 1px solid #eadfca; border-radius: 16px; box-shadow: 0 30px 90px rgba(28,22,18,.4); }
        .ziara-flow-booking__modal-head { background: linear-gradient(110deg, #4a1d13 0%, #6b2c1f 100%); border-bottom: 3px solid #c9a227; }
        .ziara-flow-booking__modal-head h3 { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; color: #fff; }
        .ziara-flow-booking__modal-close { color: rgba(255,255,255,.85); }
        .ziara-flow-booking__modal-close:hover { background: rgba(255,255,255,.18); color: #fff; }
    </style>
<div class="ziara-flow-booking min-h-screen pb-16">
    <!-- Booking Progress Timeline -->
    <div class="ziara-flow-booking__bar sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="relative flex items-center justify-between">
                <!-- Timeline Line -->
                <div class="absolute timeline-line left-0 w-full"></div>
                
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
                    <div class="timeline-circle w-12 h-12 rounded-full bg-white border-4 border-gray-300 flex items-center justify-center text-[#c9a227]">
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
            <!-- Left Column: Tour Information -->
            <div class="lg:w-2/3">
                <div class="ziara-flow-booking__panel overflow-hidden mb-8">
                    <!-- Tour Images Gallery -->
                    <div class="relative h-80">
                        <div class="swiper-container h-full">
                            <div class="absolute inset-0">
                                <img src="{{ asset('images/tours/' . ($tour['tourImage'] ?? 'default-tour.jpg')) }}" 
                                     alt="{{ $tour['tourName'] ?? 'Tour Image' }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Tour Gallery Navigation -->
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
                        <!-- Tour Name and Rating -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $tour['tourName'] ?? 'Tour Name' }}</h1>
                                <p class="text-gray-600 flex items-center mt-1">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    {{ $tour['location'] ?? 'Location' }}, {{ $tour['country'] ?? 'Country' }}
                                </p>
                            </div>
                            
                            <div class="flex flex-col items-end">
                                <!-- Rating -->
                                <div class="ziara-flow-booking__rating flex items-center px-2 py-1 rounded-lg text-sm">
                                    <i data-lucide="star" class="w-4 h-4 fill-current mr-1"></i>
                                    <span>{{ $tour['rating'] ?? '4.5' }}</span>
                                </div>
                                
                                <!-- Reviews -->
                                <span class="text-sm text-gray-600 mt-1">
                                    {{ $tour['reviewCount'] ?? '52' }} reviews
                                </span>
                            </div>
                        </div>
                        
                        <!-- Tour Type & Duration -->
                        <div class="flex flex-wrap gap-3 mb-6">
                            <span class="ziara-flow-booking__tag px-3 py-1 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                                {{ $tour['tourType'] ?? 'Adventure Tour' }}
                            </span>
                            
                            <span class="ziara-flow-booking__tag px-3 py-1 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                {{ $tour['duration'] ?? '3 hours' }}
                            </span>
                            
                            <span class="ziara-flow-booking__tag px-3 py-1 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                                Max {{ $tour['groupSize'] ?? '15' }} people
                            </span>
                            
                            <span class="ziara-flow-booking__tag px-3 py-1 text-sm font-medium rounded-lg inline-flex items-center">
                                <i data-lucide="languages" class="w-4 h-4 mr-1"></i>
                                {{ $tour['languages'] ?? 'English' }}
                            </span>
                        </div>
                        
                        <!-- Tour Description -->
                        <div class="mb-8">
                            <h2 class="ziara-flow-booking__title text-lg font-semibold mb-3">About This Tour</h2>
                            <p class="text-gray-700 mb-4">
                                {{ $tour['description'] ?? 'Experience the beauty and culture of this amazing destination with our expert guides. This tour offers a perfect blend of adventure, history, and natural beauty. You\'ll have the opportunity to explore iconic landmarks, learn about local traditions, and create unforgettable memories along the way.' }}
                            </p>
                            
                            <!-- Read More Button -->
                            <button class="ziara-flow-booking__link font-medium flex items-center">
                                Read more
                                <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                            </button>
                        </div>
                        
                        <!-- Tour Highlights -->
                        <div class="mb-8">
                            <h2 class="ziara-flow-booking__title text-lg font-semibold mb-3">Tour Highlights</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($tour['highlights'] ?? ['Visit iconic landmarks and attractions', 'Learn about local history and culture', 'Enjoy spectacular views and photo opportunities', 'Experience authentic local cuisine'] as $highlight)
                                    <div class="flex items-start">
                                        <i data-lucide="check-circle" class="ziara-flow-booking__icon w-5 h-5 mr-2 shrink-0"></i>
                                        <span>{{ $highlight }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- What's Included -->
                        <div class="mb-8">
                            <h2 class="ziara-flow-booking__title text-lg font-semibold mb-3">What's Included</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($tour['included'] ?? ['Expert local guide', 'All entrance fees', 'Transport during the tour', 'Bottled water'] as $included)
                                    <div class="flex items-start">
                                        <i data-lucide="check" class="ziara-flow-booking__icon w-5 h-5 mr-2 shrink-0"></i>
                                        <span>{{ $included }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- What's Not Included -->
                        <div class="mb-8">
                            <h2 class="ziara-flow-booking__title text-lg font-semibold mb-3">Not Included</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($tour['notIncluded'] ?? ['Hotel pickup and drop-off', 'Gratuities', 'Food and drinks, unless specified', 'Personal expenses'] as $notIncluded)
                                    <div class="flex items-start">
                                        <i data-lucide="x" class="w-5 h-5 text-red-500 mr-2 shrink-0"></i>
                                        <span>{{ $notIncluded }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Meeting & Pickup -->
                        <div class="mb-8">
                            <h2 class="ziara-flow-booking__title text-lg font-semibold mb-3">Meeting & Pickup</h2>
                            <div class="flex items-start mb-3">
                                <i data-lucide="map-pin" class="w-5 h-5 text-gray-500 mr-3 shrink-0"></i>
                                <div>
                                    <p class="font-medium">Meeting point</p>
                                    <p class="text-gray-600">{{ $tour['meetingPoint'] ?? 'Central Tourist Office, Main Square' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i data-lucide="clock" class="w-5 h-5 text-gray-500 mr-3 shrink-0"></i>
                                <div>
                                    <p class="font-medium">Start time</p>
                                    <p class="text-gray-600">{{ $tour['startTime'] ?? '9:00 AM' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cancellation Policy -->
                        <div>
                            <h2 class="ziara-flow-booking__title text-lg font-semibold mb-3">Cancellation Policy</h2>
                            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <p class="text-gray-700">
                                    {{ $tour['cancellationPolicy'] ?? 'Free cancellation up to 24 hours before the start time for a full refund. If you cancel less than 24 hours before the experience, no refund will be issued.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Available Times -->
                <div class="ziara-flow-booking__panel overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="ziara-flow-booking__title text-xl font-bold mb-6">Available Times</h2>
                        
                        <!-- Date Selector -->
                        <div class="mb-6">
                            <label for="tourDate" class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
                            <input type="date" id="tourDate" name="tour_date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" value="{{ $searchParams['date'] ?? date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <!-- Time Slots -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
                            <button type="button" class="timeSlotBtn px-4 py-3 border border-gray-300 rounded-lg text-center hover:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500" data-time="09:00">
                                9:00 AM
                            </button>
                            <button type="button" class="timeSlotBtn px-4 py-3 border border-gray-300 rounded-lg text-center hover:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500" data-time="11:00">
                                11:00 AM
                            </button>
                            <button type="button" class="timeSlotBtn px-4 py-3 border border-gray-300 rounded-lg text-center hover:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500" data-time="13:00">
                                1:00 PM
                            </button>
                            <button type="button" class="timeSlotBtn px-4 py-3 border border-gray-300 rounded-lg text-center hover:border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-500" data-time="15:00">
                                3:00 PM
                            </button>
                        </div>
                        
                        <p class="text-sm text-gray-500 italic mb-3">
                            * Times are shown in your local timezone
                        </p>
                    </div>
                </div>
                
                <!-- Traveler Information -->
                <div class="ziara-flow-booking__panel overflow-hidden mb-8">
                    <div class="p-6">
                        <h2 class="ziara-flow-booking__title text-xl font-bold mb-6">Traveler Information</h2>
                        
                        <form id="tourBookingForm" action="{{ route('tours.book') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_type" value="tour">
                            <input type="hidden" name="tour_id" value="{{ $tour['id'] ?? '' }}">
                            <input type="hidden" name="tour_data" value="{{ json_encode($tour ?? []) }}">
                            <input type="hidden" name="tour_date" id="tourDateInput" value="{{ $searchParams['date'] ?? date('Y-m-d') }}">
                            <input type="hidden" name="tour_time" id="tourTimeInput" value="">
                            
                            <!-- Number of Travelers -->
                            <div class="mb-6">
                                <label for="travelers" class="block text-sm font-medium text-gray-700 mb-1">Number of Travelers</label>
                                <div class="flex items-center">
                                    <button type="button" id="decreaseTravelers" class="px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 hover:bg-gray-100">
                                        <i data-lucide="minus" class="w-5 h-5 text-gray-600"></i>
                                    </button>
                                    <input type="number" id="travelers" name="travelers" min="1" max="{{ $tour['maxGroupSize'] ?? 10 }}" value="2" class="w-16 px-4 py-2 border-y border-gray-300 text-center focus:outline-none">
                                    <button type="button" id="increaseTravelers" class="px-3 py-2 border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100">
                                        <i data-lucide="plus" class="w-5 h-5 text-gray-600"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Lead Traveler -->
                            <div class="mb-8">
                                <h3 class="ziara-flow-booking__title text-lg font-semibold mb-4">Lead Traveler</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- First Name -->
                                    <div>
                                        <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name*</label>
                                        <input type="text" id="firstName" name="first_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
                                    </div>
                                    
                                    <!-- Last Name -->
                                    <div>
                                        <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name*</label>
                                        <input type="text" id="lastName" name="last_name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
                                    </div>
                                    
                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address*</label>
                                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
                                    </div>
                                    
                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number*</label>
                                        <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Special Requirements -->
                            <div class="mb-8">
                                <h3 class="ziara-flow-booking__title text-lg font-semibold mb-4">Special Requirements</h3>
                                
                                <div>
                                    <label for="specialRequests" class="block text-sm font-medium text-gray-700 mb-1">Special Requests (optional)</label>
                                    <textarea id="specialRequests" name="special_requests" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" placeholder="Any dietary requirements, accessibility needs, or other special requests..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Special requests are subject to availability and cannot be guaranteed.</p>
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="terms" name="terms" type="checkbox" class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded" required>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="ziara-flow-booking__link">Terms and Conditions</a> and <a href="#" class="ziara-flow-booking__link">Privacy Policy</a>.</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Continue to Payment -->
                            <div class="flex justify-end">
                                <button type="submit" id="continueToPayment" class="ziara-flow-booking__cta px-6 py-3 font-medium transition-colors flex items-center opacity-50 cursor-not-allowed" disabled>
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
                <div class="ziara-flow-booking__panel overflow-hidden sticky top-24">
                    <div class="p-6">
                        <h2 class="ziara-flow-booking__title text-xl font-bold mb-6">Booking Summary</h2>
                        
                        <!-- Tour Info Summary -->
                        <div class="flex items-center mb-6">
                            <div class="w-24 h-24 rounded-lg overflow-hidden shrink-0">
                                <img src="{{ asset('images/tours/' . ($tour['tourImage'] ?? 'default-tour.jpg')) }}" alt="{{ $tour['tourName'] ?? 'Tour' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="ml-4">
                                <h3 class="ziara-flow-booking__subtitle font-semibold">{{ $tour['tourName'] ?? 'Tour Name' }}</h3>
                                <p class="text-sm text-gray-600 mt-1 flex items-center">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                    {{ $tour['location'] ?? 'Location' }}
                                </p>
                                <div class="flex items-center mt-1">
                                    <i data-lucide="star" class="w-4 h-4 text-[#c9a227] fill-current"></i>
                                    <span class="ml-1 text-sm">{{ $tour['rating'] ?? '4.5' }} ({{ $tour['reviewCount'] ?? '52' }} reviews)</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date & Time Info -->
                        <div class="border-t border-b border-gray-200 py-4 mb-4">
                            <div id="dateTimeInfo">
                                <div class="flex items-start mb-3">
                                    <i data-lucide="calendar" class="w-5 h-5 text-gray-500 mr-3 shrink-0"></i>
                                    <div>
                                        <p class="font-medium">Date</p>
                                        <p class="text-gray-600">{{ $searchParams['date'] ?? date('F j, Y') }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <i data-lucide="clock" class="w-5 h-5 text-gray-500 mr-3 shrink-0"></i>
                                    <div>
                                        <p class="font-medium">Time</p>
                                        <p id="selectedTime" class="text-gray-600">Please select a time</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="mb-6">
                            <h3 class="ziara-flow-booking__subtitle font-semibold mb-3">Price Details</h3>
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span>Adult Price (per person)</span>
                                    <span>{{ $tour['currency'] ?? 'USD' }} {{ number_format($tour['price'] ?? 75) }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Number of travelers</span>
                                    <span id="travelerCount">2</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span id="subtotal">
                                        {{ $tour['currency'] ?? 'USD' }} {{ number_format(($tour['price'] ?? 75) * 2) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span>Taxes & Fees (10%)</span>
                                    <span id="taxesFees">
                                        @php
                                            $taxes = ($tour['price'] ?? 75) * 2 * 0.1;
                                        @endphp
                                        {{ $tour['currency'] ?? 'USD' }} {{ number_format($taxes) }}
                                    </span>
                                </div>
                                
                                <div class="flex justify-between border-t border-gray-200 pt-2 mt-2 font-semibold">
                                    <span>Total</span>
                                    <span id="totalPrice">
                                        @php
                                            $total = ($tour['price'] ?? 75) * 2 + $taxes;
                                        @endphp
                                        {{ $tour['currency'] ?? 'USD' }} {{ number_format($total) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tour Policies -->
                        <div class="mb-6">
                            <h3 class="ziara-flow-booking__subtitle font-semibold mb-3">Policies</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start">
                                    <i data-lucide="shield-check" class="ziara-flow-booking__icon w-4 h-4 mr-2 shrink-0"></i>
                                    <span>{{ $tour['cancellationPolicySummary'] ?? 'Free cancellation up to 24 hours before' }}</span>
                                </div>
                                
                                <div class="flex items-start">
                                    <i data-lucide="calendar" class="ziara-flow-booking__icon w-4 h-4 mr-2 shrink-0"></i>
                                    <span>Reserve now, pay later</span>
                                </div>
                                
                                <div class="flex items-start">
                                    <i data-lucide="check-circle" class="ziara-flow-booking__icon w-4 h-4 mr-2 shrink-0"></i>
                                    <span>Instant confirmation</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center py-6" id="selectTimeMessage">
                            <i data-lucide="info" class="w-12 h-12 text-gray-300 mx-auto"></i>
                            <p class="text-gray-500 mt-2">Please select a time to continue</p>
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
            <div class="ziara-flow-booking__modal inline-block align-bottom text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-fade-in-up">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="ziara-flow-booking__modal-title text-lg leading-6 font-medium mb-4" id="modal-title">
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
                                    <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                                
                                <!-- Email Address -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                                
                                <!-- Phone Number -->
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                                
                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                    <input type="password" name="password" id="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                                </div>
                                
                                <!-- Terms Checkbox -->
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="terms" name="terms" type="checkbox" required class="focus:ring-teal-500 h-4 w-4 text-teal-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="terms" class="font-medium text-gray-700">I agree to the <a href="#" class="ziara-flow-booking__link">Terms and Conditions</a>.</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between mt-6">
                                    <div class="text-sm">
                                        <a href="{{ route('login') }}" class="ziara-flow-booking__link font-medium">
                                            Already have an account? Login
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="registerSubmitBtn" class="ziara-flow-booking__cta w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium sm:ml-3 sm:w-auto sm:text-sm">
                        Register & Continue
                    </button>
                    <button type="button" id="modalCloseBtn" class="ziara-flow-booking__btn-ghost mt-3 w-full inline-flex justify-center rounded-md border shadow-sm px-4 py-2 text-base font-medium focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
        
        // Time slot selection
        const timeSlotBtns = document.querySelectorAll('.timeSlotBtn');
        const selectedTime = document.getElementById('selectedTime');
        const tourTimeInput = document.getElementById('tourTimeInput');
        const selectTimeMessage = document.getElementById('selectTimeMessage');
        const continueToPayment = document.getElementById('continueToPayment');
        
        timeSlotBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                timeSlotBtns.forEach(b => {
                    b.classList.remove('border-teal-600', 'bg-teal-50', 'text-teal-700');
                    b.classList.add('border-gray-300');
                });
                
                // Add active class to selected button
                this.classList.remove('border-gray-300');
                this.classList.add('border-teal-600', 'bg-teal-50', 'text-teal-700');
                
                // Update selected time
                const time = this.dataset.time;
                let formattedTime = '';
                
                // Format time for display (convert 24h to 12h format)
                const hour = parseInt(time.split(':')[0]);
                const minute = time.split(':')[1];
                const period = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour % 12 || 12;
                formattedTime = `${hour12}:${minute} ${period}`;
                
                selectedTime.textContent = formattedTime;
                tourTimeInput.value = time;
                
                // Hide message and enable continue button
                selectTimeMessage.style.display = 'none';
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
            const price = {{ $tour['price'] ?? 75 }};
            const travelers = parseInt(travelersInput.value);
            const currency = '{{ $tour['currency'] ?? 'USD' }}';
            
            travelerCount.textContent = travelers;
            
            const subtotalValue = price * travelers;
            subtotal.textContent = `${currency} ${subtotalValue.toLocaleString()}`;
            
            const taxesValue = subtotalValue * 0.1;
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
            const maxValue = {{ $tour['maxGroupSize'] ?? 10 }};
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
        
        // Handle tour booking form submission
        const tourBookingForm = document.getElementById('tourBookingForm');
        const registrationModal = document.getElementById('registrationModal');
        
        tourBookingForm.addEventListener('submit', function(event) {
            // Check if a time slot has been selected
            if (!tourTimeInput.value) {
                event.preventDefault();
                alert('Please select a time slot.');
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
