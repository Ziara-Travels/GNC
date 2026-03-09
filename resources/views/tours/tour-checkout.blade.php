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
    
    /* Pulse animation for secured badge */
    .animate-pulse-slow {
        animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
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
                <div class="timeline-step completed relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full bg-gray-200 border-4 border-white flex items-center justify-center text-white">
                        <i data-lucide="check" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium text-gray-600">Book</span>
                </div>
                
                <!-- Step 4: Pay -->
                <div class="timeline-step active relative z-10 flex flex-col items-center">
                    <div class="timeline-circle w-12 h-12 rounded-full bg-white border-4 border-gray-300 flex items-center justify-center text-indigo-600">
                        <i data-lucide="credit-card" class="w-6 h-6"></i>
                    </div>
                    <span class="timeline-text mt-2 text-sm font-medium">Pay</span>
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
            <!-- Left Column: Payment Form -->
            <div class="lg:w-2/3">
                <!-- Tour Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Tour Summary</h2>
                    
                    <div class="flex flex-col md:flex-row gap-6 mb-6">
                        <!-- Tour Image -->
                        <div class="md:w-1/3">
                            <div class="rounded-lg overflow-hidden">
                                <img src="{{ asset('images/tours/' . ($tour['image'] ?? 'tour-placeholder.jpg')) }}" alt="{{ $tour['name'] ?? 'Tour' }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        
                        <!-- Tour Details -->
                        <div class="md:w-2/3">
                            <h3 class="text-lg font-bold text-gray-900">{{ $tour['name'] ?? 'Maasai Mara Safari Adventure' }}</h3>
                            
                            <div class="flex items-center mt-2">
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="map-pin" class="h-4 w-4 mr-1"></i>
                                    {{ $tour['location'] ?? 'Maasai Mara, Kenya' }}
                                </div>
                                
                                <span class="mx-2 text-gray-300">•</span>
                                
                                <div class="flex items-center">
                                    <i data-lucide="star" class="h-4 w-4 text-yellow-500 fill-current"></i>
                                    <span class="ml-1">{{ $tour['rating'] ?? '4.9' }} ({{ $tour['review_count'] ?? '128' }} reviews)</span>
                                </div>
                            </div>
                            
                            <!-- Tour Type -->
                            <div class="mt-4 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                                <div class="font-semibold text-indigo-800">{{ $tour['type'] ?? 'Multi-day Safari Tour' }}</div>
                                <div class="text-gray-700 text-sm mt-1">{{ $tour['duration'] ?? '3 days' }} • {{ $tour['group_size'] ?? 'Small group' }} • {{ $tour['language'] ?? 'English guided' }}</div>
                            </div>
                            
                            <!-- Tour Details -->
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm text-gray-600">Tour Date</div>
                                    <div class="font-semibold">{{ $booking['tour_date'] ?? date('D, M d, Y') }}</div>
                                </div>
                                
                                <div>
                                    <div class="text-sm text-gray-600">Start Time</div>
                                    <div class="font-semibold">{{ $booking['start_time'] ?? '07:30 AM' }}</div>
                                </div>
                                
                                <div>
                                    <div class="text-sm text-gray-600">Duration</div>
                                    <div class="font-semibold">{{ $tour['duration'] ?? '3 days' }}</div>
                                </div>
                                
                                <div>
                                    <div class="text-sm text-gray-600">Participants</div>
                                    <div class="font-semibold">{{ $booking['adults'] ?? '2' }} adults{{ ($booking['children'] ?? 0) > 0 ? ', ' . $booking['children'] . ' children' : '' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Important Information</h3>
                        
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <i data-lucide="clock" class="h-5 w-5 text-indigo-600 mt-0.5 mr-2"></i>
                                <div class="text-sm text-gray-700">
                                    Please arrive at the meeting point 15 minutes before the start time.
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i data-lucide="map" class="h-5 w-5 text-indigo-600 mt-0.5 mr-2"></i>
                                <div class="text-sm text-gray-700">
                                    Meeting point: {{ $tour['meeting_point'] ?? 'Hotel pickup (please confirm your hotel details)' }}
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <i data-lucide="shield-check" class="h-5 w-5 text-green-600 mt-0.5 mr-2"></i>
                                <div class="text-sm text-gray-700">
                                    <span class="font-semibold text-green-700">{{ $tour['cancellation'] ?? 'Free cancellation' }}</span> up to 24 hours before the tour starts
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- What's Included -->
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-900 mb-3">What's Included</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($tour['included'] ?? ['Professional guide', 'Hotel pickup and drop-off', 'All park entrance fees', 'Meals as per itinerary', 'Bottled water'] as $included)
                                <div class="flex items-center">
                                    <i data-lucide="check" class="h-5 w-5 text-green-500 mr-2"></i>
                                    <span class="text-sm">{{ $included }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Payment Method</h2>
                        
                        <!-- Secured Payment Badge -->
                        <div class="flex items-center px-3 py-1 bg-green-100 rounded-full">
                            <i data-lucide="shield" class="h-4 w-4 text-green-700 mr-1 animate-pulse-slow"></i>
                            <span class="text-xs font-medium text-green-700">Secure Payment</span>
                        </div>
                    </div>
                    
                    <!-- Payment Method Selection -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center">
                            <input id="card" name="paymentMethod" type="radio" checked class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <label for="card" class="ml-3 block font-medium text-gray-700">
                                Credit/Debit Card
                            </label>
                            
                            <!-- Card Icons -->
                            <div class="flex ml-auto space-x-2">
                                <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" class="h-6">
                                <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" class="h-6">
                                <img src="{{ asset('images/payment/amex.svg') }}" alt="American Express" class="h-6">
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input id="mpesa" name="paymentMethod" type="radio" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <label for="mpesa" class="ml-3 block font-medium text-gray-700">
                                M-Pesa
                            </label>
                            
                            <!-- MPesa Icon -->
                            <div class="ml-auto">
                                <img src="{{ asset('images/payment/mpesa.svg') }}" alt="MPesa" class="h-6">
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <input id="paypal" name="paymentMethod" type="radio" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <label for="paypal" class="ml-3 block font-medium text-gray-700">
                                PayPal
                            </label>
                            
                            <!-- PayPal Icon -->
                            <div class="ml-auto">
                                <img src="{{ asset('images/payment/paypal.svg') }}" alt="PayPal" class="h-6">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Credit Card Form (Default active) -->
                    <div id="cardPaymentForm" class="border-t border-gray-200 pt-6">
                        <form action="{{ route('tours.payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking['id'] ?? '' }}">
                            <input type="hidden" name="booking_type" value="tour">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Card Holder Name -->
                                <div class="col-span-2">
                                    <label for="cardName" class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name</label>
                                    <input type="text" name="cardName" id="cardName" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="As appears on card">
                                </div>
                                
                                <!-- Card Number -->
                                <div class="col-span-2">
                                    <label for="cardNumber" class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                    <div class="relative">
                                        <input type="text" name="cardNumber" id="cardNumber" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 pr-10" placeholder="1234 5678 9012 3456" maxlength="19">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i data-lucide="credit-card" class="h-5 w-5 text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Expiry Date -->
                                <div>
                                    <label for="cardExpiry" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                    <input type="text" name="cardExpiry" id="cardExpiry" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="MM/YY" maxlength="5">
                                </div>
                                
                                <!-- CVV -->
                                <div>
                                    <label for="cardCvv" class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                    <div class="relative">
                                        <input type="text" name="cardCvv" id="cardCvv" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 pr-10" placeholder="123" maxlength="4">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <i data-lucide="help-circle" class="h-5 w-5 text-gray-400 cursor-help" title="3 or 4 digit security code usually found on the back of your card"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Billing Address -->
                                <div class="col-span-2">
                                    <div class="flex items-center mb-4">
                                        <h3 class="font-semibold text-gray-900">Billing Address</h3>
                                        <span class="ml-2 text-xs bg-gray-100 text-gray-800 py-1 px-2 rounded">Required</span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Country -->
                                        <div class="col-span-2">
                                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                            <select name="country" id="country" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">Select Country</option>
                                                <option value="KE">Kenya</option>
                                                <option value="UG">Uganda</option>
                                                <option value="TZ">Tanzania</option>
                                                <option value="RW">Rwanda</option>
                                                <option value="US">United States</option>
                                                <option value="GB">United Kingdom</option>
                                                <!-- Add more countries as needed -->
                                            </select>
                                        </div>
                                        
                                        <!-- Address -->
                                        <div class="col-span-2">
                                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                                            <input type="text" name="address" id="address" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        
                                        <!-- City -->
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                            <input type="text" name="city" id="city" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                        
                                        <!-- Postal Code -->
                                        <div>
                                            <label for="postalCode" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                            <input type="text" name="postalCode" id="postalCode" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Save Card Info -->
                                <div class="col-span-2 mt-4">
                                    <div class="flex items-center">
                                        <input id="saveCardInfo" name="saveCardInfo" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="saveCardInfo" class="ml-2 block text-sm text-gray-700">
                                            Save my card information securely for future bookings
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mt-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="terms" name="terms" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="terms" class="font-medium text-gray-700">
                                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a>, <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>, and <a href="#" class="text-indigo-600 hover:text-indigo-500">Cancellation Policy</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button type="submit" class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                                    Pay & Confirm Booking
                                    <i data-lucide="lock" class="w-5 h-5 ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Mobile Money Form (Initially hidden) -->
                    <div id="mpesaPaymentForm" class="hidden border-t border-gray-200 pt-6">
                        <form action="{{ route('tours.payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="mpesa">
                            <input type="hidden" name="booking_id" value="{{ $booking['id'] ?? '' }}">
                            <input type="hidden" name="booking_type" value="tour">
                            
                            <!-- Phone Number -->
                            <div class="mb-6">
                                <label for="mpesaPhone" class="block text-sm font-medium text-gray-700 mb-1">M-Pesa Phone Number</label>
                                <div class="relative">
                                    <input type="tel" name="mpesaPhone" id="mpesaPhone" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. 07XXXXXXXX">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i data-lucide="smartphone" class="h-5 w-5 text-gray-400"></i>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">You will receive a payment prompt on this phone number</p>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mt-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="mpesaTerms" name="terms" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="mpesaTerms" class="font-medium text-gray-700">
                                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a>, <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>, and <a href="#" class="text-indigo-600 hover:text-indigo-500">Cancellation Policy</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="mt-8">
                                <button type="submit" class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                                    Pay with M-Pesa
                                    <i data-lucide="smartphone" class="w-5 h-5 ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- PayPal Form (Initially hidden) -->
                    <div id="paypalPaymentForm" class="hidden border-t border-gray-200 pt-6">
                        <form action="{{ route('tours.payment.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" value="paypal">
                            <input type="hidden" name="booking_id" value="{{ $booking['id'] ?? '' }}">
                            <input type="hidden" name="booking_type" value="tour">
                            
                            <div class="text-center py-6">
                                <p class="mb-6 text-gray-700">Click the button below to proceed to PayPal's secure payment page.</p>
                                
                                <button type="submit" class="px-6 py-3 bg-[#0070ba] hover:bg-[#005ea6] text-white font-medium rounded-lg transition-colors flex items-center justify-center mx-auto">
                                    <img src="{{ asset('images/payment/paypal-white.svg') }}" alt="PayPal" class="h-5 mr-2">
                                    Pay with PayPal
                                </button>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mt-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="paypalTerms" name="terms" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="paypalTerms" class="font-medium text-gray-700">
                                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a>, <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>, and <a href="#" class="text-indigo-600 hover:text-indigo-500">Cancellation Policy</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Payment Security Information -->
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-center space-x-6">
                            <div class="flex items-center">
                                <i data-lucide="shield" class="h-5 w-5 text-green-600 mr-2"></i>
                                <span class="text-sm text-gray-700">Secure Payment</span>
                            </div>
                            
                            <div class="flex items-center">
                                <i data-lucide="lock" class="h-5 w-5 text-green-600 mr-2"></i>
                                <span class="text-sm text-gray-700">Encrypted Data</span>
                            </div>
                            
                            <div class="flex items-center">
                                <i data-lucide="eye-off" class="h-5 w-5 text-green-600 mr-2"></i>
                                <span class="text-sm text-gray-700">Privacy Protected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column: Booking Summary -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Tour Details</h2>
                        
                        <!-- Tour Summary -->
                        <div class="mb-6">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-semibold">{{ $tour['name'] ?? 'Maasai Mara Safari Adventure' }}</h3>
                                    <p class="text-sm text-gray-600">{{ $tour['duration'] ?? '3 days' }}</p>
                                </div>
                            </div>
                            
                            <div class="text-sm text-gray-600 mt-2">
                                <div class="flex items-center">
                                    <i data-lucide="calendar" class="h-4 w-4 mr-1"></i>
                                    <span>{{ $booking['tour_date'] ?? date('M d, Y') }}</span>
                                </div>
                                
                                <div class="flex items-center mt-1">
                                    <i data-lucide="users" class="h-4 w-4 mr-1"></i>
                                    <span>{{ $booking['adults'] ?? '2' }} adults{{ ($booking['children'] ?? 0) > 0 ? ', ' . $booking['children'] . ' children' : '' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="border-t border-b border-gray-200 py-4">
                            <div class="space-y-3">
                                <!-- Tour Price -->
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Adult Price ({{ $booking['adults'] ?? '2' }} × {{ $tour['currency'] ?? 'USD' }} {{ $tour['adult_price'] ?? '299' }})</span>
                                    <span>
                                        @php
                                            $adultTotal = ($booking['adults'] ?? 2) * ($tour['adult_price'] ?? 299);
                                        @endphp
                                        {{ $tour['currency'] ?? 'USD' }} {{ $adultTotal }}
                                    </span>
                                </div>
                                
                                @if(($booking['children'] ?? 0) > 0)
                                    <!-- Child Price -->
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Child Price ({{ $booking['children'] }} × {{ $tour['currency'] ?? 'USD' }} {{ $tour['child_price'] ?? '149' }})</span>
                                        <span>
                                            @php
                                                $childTotal = ($booking['children'] ?? 0) * ($tour['child_price'] ?? 149);
                                            @endphp
                                            {{ $tour['currency'] ?? 'USD' }} {{ $childTotal }}
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Booking Fee -->
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Booking fee</span>
                                    <span>
                                        @php
                                            $bookingFee = 25;
                                        @endphp
                                        {{ $tour['currency'] ?? 'USD' }} {{ $bookingFee }}
                                    </span>
                                </div>
                                
                                <!-- Taxes -->
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Taxes (8%)</span>
                                    <span>
                                        @php
                                            $subtotal = $adultTotal + ($childTotal ?? 0) + $bookingFee;
                                            $taxes = $subtotal * 0.08;
                                            $taxes = round($taxes, 2);
                                        @endphp
                                        {{ $tour['currency'] ?? 'USD' }} {{ $taxes }}
                                    </span>
                                </div>
                                
                                @if(($tour['discount'] ?? 0) > 0)
                                    <!-- Discount -->
                                    <div class="flex justify-between text-green-600">
                                        <span>{{ $tour['discount'] }}% Early booking discount</span>
                                        <span>
                                            @php
                                                $discount = $subtotal * ($tour['discount'] ?? 0) / 100;
                                                $discount = round($discount, 2);
                                            @endphp
                                            -{{ $tour['currency'] ?? 'USD' }} {{ $discount }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Total -->
                        <div class="pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-lg">Total</span>
                                <span class="font-bold text-lg">
                                    @php
                                        $total = $subtotal + $taxes;
                                        if(($tour['discount'] ?? 0) > 0) {
                                            $total -= $discount;
                                        }
                                        $total = round($total, 2);
                                    @endphp
                                    {{ $tour['currency'] ?? 'USD' }} {{ $total }}
                                </span>
                            </div>
                            
                            <div class="text-xs text-gray-600 flex items-center justify-end">
                                <i data-lucide="info" class="h-3 w-3 mr-1"></i>
                                Includes taxes and fees
                            </div>
                        </div>
                        
                        <!-- Cancellation Policy -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-2">Cancellation Policy</h3>
                            <p class="text-sm text-gray-600">
                                {{ $tour['cancellation_policy'] ?? 'Free cancellation up to 24 hours before the tour starts. If you cancel less than 24 hours before the start time, you will not be eligible for a refund.' }}
                            </p>
                        </div>
                        
                        <!-- Contact Support -->
                        <div class="mt-6">
                            <div class="flex items-center justify-center">
                                <i data-lucide="headphones" class="h-5 w-5 text-indigo-600 mr-2"></i>
                                <span class="text-sm font-medium">Need help? <a href="#" class="text-indigo-600 hover:text-indigo-500">Contact support</a></span>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Payment method selection
        const paymentOptions = document.querySelectorAll('input[name="paymentMethod"]');
        const cardPaymentForm = document.getElementById('cardPaymentForm');
        const mpesaPaymentForm = document.getElementById('mpesaPaymentForm');
        const paypalPaymentForm = document.getElementById('paypalPaymentForm');
        
        paymentOptions.forEach(option => {
            option.addEventListener('change', function() {
                // Hide all payment forms
                cardPaymentForm.classList.add('hidden');
                mpesaPaymentForm.classList.add('hidden');
                paypalPaymentForm.classList.add('hidden');
                
                // Show the selected payment form
                if (this.id === 'card') {
                    cardPaymentForm.classList.remove('hidden');
                } else if (this.id === 'mpesa') {
                    mpesaPaymentForm.classList.remove('hidden');
                } else if (this.id === 'paypal') {
                    paypalPaymentForm.classList.remove('hidden');
                }
            });
        });
        
        // Credit card input formatting
        const cardNumberInput = document.getElementById('cardNumber');
        const cardExpiryInput = document.getElementById('cardExpiry');
        const cardCvvInput = document.getElementById('cardCvv');
        
        // Format card number with spaces
        if (cardNumberInput) {
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\s+/g, '');
                if (value.length > 0) {
                    value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
                }
                e.target.value = value;
            });
        }
        
        // Format expiry date with slash
        if (cardExpiryInput) {
            cardExpiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 0) {
                    if (value.length > 2) {
                        value = value.slice(0, 2) + '/' + value.slice(2, 4);
                    }
                }
                e.target.value = value;
            });
        }
        
        // Limit CVV to numbers
        if (cardCvvInput) {
            cardCvvInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
            });
        }
        
        // Modal functionality
        const registrationModal = document.getElementById('registrationModal');
        const modalCloseBtn = document.getElementById('modalCloseBtn');
        const quickRegisterForm = document.getElementById('quickRegisterForm');
        const registerSubmitBtn = document.getElementById('registerSubmitBtn');
        
        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', function() {
                registrationModal.classList.add('hidden');
            });
        }
        
        if (registerSubmitBtn) {
            registerSubmitBtn.addEventListener('click', function() {
                quickRegisterForm.submit();
            });
        }
        
        // Show registration modal for guest users (if needed)
        @if(!Auth::check() && isset($showRegistrationModal) && $showRegistrationModal)
            setTimeout(() => {
                registrationModal.classList.remove('hidden');
            }, 500);
        @endif
        
        // Fill stars for star ratings
        document.querySelectorAll('[data-lucide="star"]').forEach(function(star) {
            if (star.classList.contains('fill-current')) {
                star.setAttribute('fill', 'currentColor');
            }
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
            showToast('{{ session('success') }}', 'success');
        @endif
        
        // Show error toast if redirected with error message
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    });
</script>
@endsection
