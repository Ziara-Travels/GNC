@extends('layouts.main')

@php
    // Get booking details from session
    $bookingDetails = session('booking_details', []);
    $flightData = json_decode(session('selected_flight'), true);
    $bookingType = 'flight';

    // Flight information from selected flight data
    $fromCity = $flightData['itineraries'][0]['segments'][0]['departure']['iataCode'] ?? 'N/A';
    $toCity = $flightData['itineraries'][0]['segments'][count($flightData['itineraries'][0]['segments']) - 1]['arrival']['iataCode'] ?? 'N/A';
    $departureTime = $flightData['itineraries'][0]['segments'][0]['departure']['at'] ?? null;
    $arrivalTime = $flightData['itineraries'][0]['segments'][count($flightData['itineraries'][0]['segments']) - 1]['arrival']['at'] ?? null;
    $flightNumber = $flightData['itineraries'][0]['segments'][0]['number'] ?? 'N/A';
    $airlineCode = $flightData['itineraries'][0]['segments'][0]['carrierCode'] ?? 'N/A';
    $airlineName = $airlineCode;  // You would need a mapping function to get the full airline name
    $travelClass = $flightData['travelerPricings'][0]['fareDetailsBySegment'][0]['cabin'] ?? 'ECONOMY';

    // Passenger details
    $adults = count($flightData['travelerPricings'] ?? []);
    $children = 0;  // Would need to calculate based on traveler data
    $infants = 0;   // Would need to calculate based on traveler data
    $totalPassengers = $adults + $children + $infants;

    // Pricing
    $basePrice = $flightData['price']['base'] ?? 0;
    $totalPrice = $flightData['price']['total'] ?? 0;
    $currency = $flightData['price']['currency'] ?? 'USD';
    $taxes = $totalPrice - $basePrice;

    // Dynamic pricing based on user location
    $userCountry = request()->header('CF-IPCountry', 'US');
    $isKenyan = $userCountry === 'KE' || $currency === 'KES';
    $exchangeRate = 130.5; // KES to USD rate

    if ($isKenyan && $currency === 'USD') {
        $displayPrice = $totalPrice * $exchangeRate;
        $displayCurrency = 'KES';
        $displaySymbol = 'KSh';
    } else {
        $displayPrice = $totalPrice;
        $displayCurrency = $currency;
        $displaySymbol = $currency === 'USD' ? '$' : ($currency === 'KES' ? 'KSh' : $currency);
    }

    // Generate booking reference
    $bookingReference = 'ZT-' . strtoupper(uniqid()) . '-' . date('ymd');
@endphp

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Enhanced Hero Section -->
        <div class="relative bg-gradient-to-br from-blue-800 via-indigo-900 to-purple-900 py-24 overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400/20 rounded-full blur-3xl animate-pulse"></div>
                <div
                    class="absolute bottom-20 right-10 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000">
                </div>
                <div
                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl animate-pulse animation-delay-2000">
                </div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-6xl mx-auto">
                    @php
                        // Get booking details from session
                        $bookingDetails = session('booking_details', []);
                        $flightDetails = $bookingDetails['flight_details'] ?? [];
                        $bookingType = $bookingDetails['booking_type'] ?? 'flight';

                        // Flight information
                        $fromCity = $flightDetails['departure_airport'] ?? 'N/A';
                        $toCity = $flightDetails['arrival_airport'] ?? 'N/A';
                        $departureTime = $flightDetails['departure_time'] ?? null;
                        $arrivalTime = $flightDetails['arrival_time'] ?? null;
                        $flightNumber = $flightDetails['flight_number'] ?? 'N/A';
                        $airlineName = $flightDetails['airline_name'] ?? 'N/A';
                        $travelClass = $flightDetails['travel_class'] ?? 'ECONOMY';

                        // Passenger details
                        $adults = $bookingDetails['adults'] ?? 1;
                        $children = $bookingDetails['children'] ?? 0;
                        $infants = $bookingDetails['infants'] ?? 0;
                        $totalPassengers = $adults + $children + $infants;

                        // Pricing
                        $basePrice = $bookingDetails['amount'] ?? 0;
                        $currency = $bookingDetails['currency'] ?? 'USD';
                        $taxes = $basePrice * 0.15; // 15% taxes
                        $totalPrice = $basePrice + $taxes;

                        // Dynamic pricing based on user location
                        $userCountry = request()->header('CF-IPCountry', 'US');
                        $isKenyan = $userCountry === 'KE';
                        $exchangeRate = 130.5; // KES to USD rate

                        if ($isKenyan && $currency === 'USD') {
                            $displayPrice = $totalPrice * $exchangeRate;
                            $displayCurrency = 'KES';
                            $displaySymbol = 'KSh';
                        } else {
                            $displayPrice = $totalPrice;
                            $displayCurrency = $currency;
                            $displaySymbol = $currency === 'USD' ? '$' : $currency;
                        }

                        // Generate booking reference
                        $bookingReference = 'Ziara Travels-' . strtoupper(uniqid());
                    @endphp

                    <!-- Navigation -->
                    <div class="flex items-center justify-between mb-8">
                        <button onclick="history.back()"
                            class="flex items-center gap-2 text-white/90 hover:text-white transition-colors duration-200 group">
                            <div
                                class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:bg-white/30 transition-colors">
                                <i data-lucide="arrow-left" class="h-5 w-5"></i>
                            </div>
                            <span class="font-medium">Back to Booking</span>
                        </button>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2 text-white/90">
                                <i data-lucide="shield-check" class="h-5 w-5"></i>
                                <span class="text-sm">Secure Checkout</span>
                            </div>
                            <div class="flex items-center gap-2 text-white/90">
                                <i data-lucide="clock" class="h-5 w-5"></i>
                                <span class="text-sm">Time remaining: <span id="countdown">15:00</span></span>
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Header -->
                    <div class="text-center mb-8">
                        <h1
                            class="text-5xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                            Complete Your Booking
                        </h1>
                        <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                            You're just one step away from your {{ $bookingType === 'flight' ? 'flight' : 'hotel' }} booking
                        </p>
                    </div>

                    <!-- Flight Summary Card -->
                    <div class="bg-white/10 backdrop-blur-lg rounded-3xl border border-white/20 p-8 shadow-2xl">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                            <div class="flex items-center gap-6 flex-1">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <i data-lucide="plane" class="h-10 w-10 text-white"></i>
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold text-white mb-2">{{ $fromCity }} → {{ $toCity }}
                                    </h2>
                                    <div class="flex flex-wrap items-center gap-4 text-white/90">
                                        <span class="flex items-center gap-2">
                                            <i data-lucide="plane" class="h-4 w-4"></i>
                                            {{ $airlineName }} {{ $flightNumber }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i data-lucide="users" class="h-4 w-4"></i>
                                            {{ $totalPassengers }} {{ $totalPassengers > 1 ? 'Passengers' : 'Passenger' }}
                                        </span>
                                        <span class="flex items-center gap-2">
                                            <i data-lucide="star" class="h-4 w-4"></i>
                                            {{ ucwords(str_replace('_', ' ', strtolower($travelClass))) }}
                                        </span>
                                    </div>
                                    @if ($departureTime)
                                        <div class="flex items-center gap-6 mt-4 text-white/80">
                                            <div class="text-center">
                                                <div class="text-2xl font-bold">
                                                    {{ \Carbon\Carbon::parse($departureTime)->format('H:i') }}</div>
                                                <div class="text-sm">
                                                    {{ \Carbon\Carbon::parse($departureTime)->format('M j') }}</div>
                                            </div>
                                            <div class="flex-1 h-0.5 bg-white/30 relative">
                                                <i data-lucide="plane"
                                                    class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 h-4 w-4 text-white bg-blue-600 rounded-full p-1"></i>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold">
                                                    {{ \Carbon\Carbon::parse($arrivalTime)->format('H:i') }}</div>
                                                <div class="text-sm">
                                                    {{ \Carbon\Carbon::parse($arrivalTime)->format('M j') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Booking Reference -->
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 border border-white/30">
                                <div class="text-center">
                                    <p class="text-white/80 text-sm mb-2">Booking Reference</p>
                                    <div class="text-2xl font-bold text-white mb-4">{{ $bookingReference }}</div>
                                    <div class="text-white/70 text-sm">
                                        <i data-lucide="info" class="h-4 w-4 inline mr-1"></i>
                                        Save this reference number
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wave separator -->
            <div class="absolute bottom-0 left-0 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" class="w-full h-auto">
                    <path fill="#ffffff" fill-opacity="1"
                        d="M0,64L80,58.7C160,53,320,43,480,42.7C640,43,800,53,960,58.7C1120,64,1280,64,1360,64L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Main Checkout Content -->
        <div class="container mx-auto px-4 py-12 -mt-16 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col xl:flex-row gap-8">
                    <!-- Left Column - Payment Methods -->
                    <div class="xl:w-2/3">
                        <form id="checkoutForm" method="POST" action="{{ route('booking.process-payment') }}">
                            @csrf
                            <input type="hidden" name="booking_reference" value="{{ $bookingReference }}">
                            <input type="hidden" name="booking_type" value="{{ $bookingType }}">
                            <input type="hidden" name="amount" value="{{ $totalPrice }}">
                            <input type="hidden" name="currency" value="{{ $displayCurrency }}">
                            <input type="hidden" name="flight_details" value="{{ json_encode($flightDetails) }}">
                            <input type="hidden" name="traveler_details" value="{{ json_encode($bookingDetails) }}">

                            <!-- Payment Method Selection -->
                            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 mb-8 overflow-hidden">
                                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-6 text-white">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                            <i data-lucide="credit-card" class="h-6 w-6"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">Select Payment Method</h3>
                                            <p class="text-green-100">Choose your preferred payment option</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- M-Pesa Payment -->
                                        <div class="payment-method-card group cursor-pointer" data-method="mpesa">
                                            <div
                                                class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-green-500 hover:shadow-lg">
                                                <div class="flex items-center gap-4 mb-4">
                                                    <div
                                                        class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                                        <i data-lucide="smartphone" class="h-8 w-8 text-green-600"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-xl font-bold text-gray-800">M-Pesa</h4>
                                                        <p class="text-gray-600">Mobile Money Payment</p>
                                                    </div>
                                                </div>
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                        <span>Instant payment</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                        <span>Secure & encrypted</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                        <span>No card required</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bank Payment -->
                                        <div class="payment-method-card group cursor-pointer" data-method="bank">
                                            <div
                                                class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-blue-500 hover:shadow-lg">
                                                <div class="flex items-center gap-4 mb-4">
                                                    <div
                                                        class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                                        <i data-lucide="building-2" class="h-8 w-8 text-blue-600"></i>
                                                    </div>
                                                    <div>
                                                        <h4 class="text-xl font-bold text-gray-800">Bank Transfer</h4>
                                                        <p class="text-gray-600">Direct bank payment</p>
                                                    </div>
                                                </div>
                                                <div class="space-y-3">
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <i data-lucide="check" class="h-4 w-4 text-blue-500"></i>
                                                        <span>Secure bank transfer</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <i data-lucide="check" class="h-4 w-4 text-blue-500"></i>
                                                        <span>Bank-level security</span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                                        <i data-lucide="check" class="h-4 w-4 text-blue-500"></i>
                                                        <span>Processing within 24h</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Form Fields -->
                                    <div class="mt-8">
                                        <!-- M-Pesa Fields -->
                                        <div id="mpesaFields" class="payment-fields hidden">
                                            <div class="bg-green-50 rounded-2xl p-6 border border-green-200">
                                                <h4
                                                    class="text-lg font-semibold text-green-800 mb-4 flex items-center gap-2">
                                                    <i data-lucide="smartphone" class="h-5 w-5"></i>
                                                    M-Pesa Payment Details
                                                </h4>
                                                <div class="space-y-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone
                                                            Number</label>
                                                        <div class="relative">
                                                            <div
                                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">+254</span>
                                                            </div>
                                                            <input type="tel" name="phone_number" id="phoneNumber"
                                                                class="block w-full pl-12 pr-3 py-4 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200"
                                                                placeholder="700123456" required>
                                                        </div>
                                                        <p class="text-sm text-gray-600 mt-2">
                                                            <i data-lucide="info" class="h-4 w-4 inline mr-1"></i>
                                                            You'll receive a prompt on your phone to complete payment
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bank Fields -->
                                        <div id="bankFields" class="payment-fields hidden">
                                            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                                                <h4
                                                    class="text-lg font-semibold text-blue-800 mb-4 flex items-center gap-2">
                                                    <i data-lucide="building-2" class="h-5 w-5"></i>
                                                    Bank Transfer Details
                                                </h4>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Account
                                                            Holder Name</label>
                                                        <input type="text" name="account_holder_name"
                                                            class="block w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                                            placeholder="John Doe" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Account
                                                            Number</label>
                                                        <input type="text" name="account_number"
                                                            class="block w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                                            placeholder="1234567890" required>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank
                                                            Name</label>
                                                        <select name="bank_name"
                                                            class="block w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                                            required>
                                                            <option value="">Select your bank</option>
                                                            <option value="KCB">KCB Bank</option>
                                                            <option value="Equity">Equity Bank</option>
                                                            <option value="Standard Chartered">Standard Chartered</option>
                                                            <option value="Barclays">Barclays Bank</option>
                                                            <option value="Co-operative">Co-operative Bank</option>
                                                            <option value="NCBA">NCBA Bank</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Branch
                                                            Code</label>
                                                        <input type="text" name="branch_code"
                                                            class="block w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                                            placeholder="001" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Terms and Conditions -->
                                    <div class="mt-8 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                                        <label class="flex items-start gap-3 cursor-pointer">
                                            <input type="checkbox" name="terms_accepted" required
                                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                                            <div class="text-sm text-gray-700">
                                                I accept the <a href="#" class="text-blue-600 hover:underline">Terms
                                                    and Conditions</a> and
                                                <a href="#" class="text-blue-600 hover:underline">Privacy
                                                    Policy</a>.
                                                I understand that this booking is subject to the airline's fare rules and
                                                cancellation policies.
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Right Column - Booking Summary -->
                    <div class="xl:w-1/3">
                        <div class="sticky top-8 space-y-6">
                            <!-- Price Summary -->
                            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-6 text-white">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                            <i data-lucide="receipt" class="h-6 w-6"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">Booking Summary</h3>
                                            <p class="text-blue-100">Total amount breakdown</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="space-y-4">
                                        <!-- Flight Details -->
                                        <div class="flex items-center gap-4 pb-4 border-b border-gray-200">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                                <i data-lucide="plane" class="h-6 w-6 text-white"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800">{{ $fromCity }} →
                                                    {{ $toCity }}</div>
                                                <div class="text-sm text-gray-600">{{ $airlineName }}
                                                    {{ $flightNumber }}</div>
                                            </div>
                                        </div>

                                        <!-- Passenger Breakdown -->
                                        <div class="space-y-3">
                                            @if ($adults > 0)
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="user" class="h-4 w-4 text-gray-500"></i>
                                                        <span class="text-sm text-gray-600">{{ $adults }}
                                                            Adult{{ $adults > 1 ? 's' : '' }}</span>
                                                    </div>
                                                    <span
                                                        class="font-semibold">{{ $displaySymbol }}{{ number_format($basePrice, $isKenyan ? 0 : 2) }}</span>
                                                </div>
                                            @endif

                                            @if ($children > 0)
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="baby" class="h-4 w-4 text-gray-500"></i>
                                                        <span class="text-sm text-gray-600">{{ $children }}
                                                            Child{{ $children > 1 ? 'ren' : '' }}</span>
                                                    </div>
                                                    <span
                                                        class="font-semibold">{{ $displaySymbol }}{{ number_format($basePrice * 0.75, $isKenyan ? 0 : 2) }}</span>
                                                </div>
                                            @endif

                                            @if ($infants > 0)
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="heart" class="h-4 w-4 text-gray-500"></i>
                                                        <span class="text-sm text-gray-600">{{ $infants }}
                                                            Infant{{ $infants > 1 ? 's' : '' }}</span>
                                                    </div>
                                                    <span
                                                        class="font-semibold">{{ $displaySymbol }}{{ number_format($basePrice * 0.1, $isKenyan ? 0 : 2) }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        @php
                                            // Check for referral discount
                                            $referralDiscount = 0;
                                            $referralInfo = null;

                                            if (auth()->check()) {
                                                $discountData = app(
                                                    'App\Http\Controllers\BookingController',
                                                )->applyReferralDiscount(auth()->id(), $basePrice);
                                                $referralDiscount = $discountData['discount'];
                                                $referralInfo = $discountData;
                                            }

                                            // Recalculate total with discount
                                            $discountedPrice = $basePrice - $referralDiscount;
                                            $taxes = $discountedPrice * 0.15;
                                            $totalPrice = $discountedPrice + $taxes;
                                        @endphp

                                        <!-- Add this in the price breakdown section -->
                                        @if ($referralDiscount > 0)
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="gift" class="h-4 w-4 text-green-500"></i>
                                                    <span class="text-sm text-gray-600">Referral Discount</span>
                                                    <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded">
                                                        {{ $referralInfo['referral_code'] }}
                                                    </span>
                                                </div>
                                                <span
                                                    class="font-semibold text-green-600">-{{ $displaySymbol }}{{ number_format($referralDiscount, $isKenyan ? 0 : 2) }}</span>
                                            </div>
                                        @endif

                                        <!-- Fees and Taxes -->
                                        <div class="pt-4 border-t border-gray-200 space-y-3">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="calculator" class="h-4 w-4 text-gray-500"></i>
                                                    <span class="text-sm text-gray-600">Taxes & Fees</span>
                                                </div>
                                                <span
                                                    class="font-semibold">{{ $displaySymbol }}{{ number_format($taxes, $isKenyan ? 0 : 2) }}</span>
                                            </div>

                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="shield-check" class="h-4 w-4 text-green-500"></i>
                                                    <span class="text-sm text-gray-600">Service Fee</span>
                                                </div>
                                                <span class="font-semibold text-green-600">Free</span>
                                            </div>
                                        </div>

                                        <!-- Total -->
                                        <div class="pt-4 border-t-2 border-gray-300">
                                            <div class="flex justify-between items-center">
                                                <span class="text-lg font-bold text-gray-800">Total Amount</span>
                                                <div class="text-right">
                                                    <div class="text-2xl font-bold text-blue-600">
                                                        {{ $displaySymbol }}{{ number_format($displayPrice, $isKenyan ? 0 : 2) }}
                                                    </div>
                                                    @if ($isKenyan && $currency === 'USD')
                                                        <div class="text-sm text-gray-500">
                                                            ~${{ number_format($totalPrice, 2) }} USD</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment Button -->
                                        <button type="submit" form="checkoutForm" id="payButton"
                                            class="w-full mt-6 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                            <span class="flex items-center justify-center gap-2">
                                                <i data-lucide="credit-card" class="h-5 w-5"></i>
                                                <span id="payButtonText">Select Payment Method</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Features -->
                            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                    <i data-lucide="shield-check" class="h-5 w-5 text-green-600"></i>
                                    Your Booking is Protected
                                </h3>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i data-lucide="lock" class="h-4 w-4 text-green-500"></i>
                                        <span>256-bit SSL encryption</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i data-lucide="shield" class="h-4 w-4 text-green-500"></i>
                                        <span>PCI DSS compliant</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i data-lucide="clock" class="h-4 w-4 text-green-500"></i>
                                        <span>24/7 customer support</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                                        <span>Instant confirmation</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Support -->
                            <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-200">
                                <h4 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                    <i data-lucide="headphones" class="h-5 w-5 text-blue-600"></i>
                                    Need Help?
                                </h4>
                                <p class="text-sm text-gray-600 mb-4">Our support team is available 24/7 to assist you</p>
                                <div class="flex gap-2">
                                    <a href="tel:+254700123456"
                                        class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors text-center">
                                        <i data-lucide="phone" class="h-4 w-4 inline mr-1"></i>
                                        Call Us
                                    </a>
                                    <a href="#"
                                        class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors text-center">
                                        <i data-lucide="message-circle" class="h-4 w-4 inline mr-1"></i>
                                        Chat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-3xl p-8 max-w-md mx-4 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="loader" class="h-8 w-8 text-blue-600 animate-spin"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Processing Payment</h3>
                <p class="text-gray-600">Please wait while we process your payment...</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Payment method selection
            const paymentCards = document.querySelectorAll('.payment-method-card');
            const paymentFields = document.querySelectorAll('.payment-fields');
            const payButton = document.getElementById('payButton');
            const payButtonText = document.getElementById('payButtonText');
            let selectedPaymentMethod = null;

            paymentCards.forEach(card => {
                card.addEventListener('click', function() {
                    const method = this.dataset.method;

                    // Reset all cards
                    paymentCards.forEach(c => {
                        c.querySelector('div').classList.remove('border-blue-500',
                            'border-green-500', 'shadow-lg');
                        c.querySelector('div').classList.add('border-gray-200');
                    });

                    // Hide all payment fields
                    paymentFields.forEach(field => field.classList.add('hidden'));

                    // Activate selected card
                    if (method === 'mpesa') {
                        this.querySelector('div').classList.remove('border-gray-200');
                        this.querySelector('div').classList.add('border-green-500', 'shadow-lg');
                        document.getElementById('mpesaFields').classList.remove('hidden');
                        payButtonText.textContent = 'Pay with M-Pesa';
                    } else if (method === 'bank') {
                        this.querySelector('div').classList.remove('border-gray-200');
                        this.querySelector('div').classList.add('border-blue-500', 'shadow-lg');
                        document.getElementById('bankFields').classList.remove('hidden');
                        payButtonText.textContent = 'Pay with Bank Transfer';
                    }

                    selectedPaymentMethod = method;
                    payButton.disabled = false;

                    // Add hidden input for payment method
                    const existingInput = document.querySelector('input[name="payment_method"]');
                    if (existingInput) {
                        existingInput.remove();
                    }

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = 'payment_method';
                    methodInput.value = method;
                    document.getElementById('checkoutForm').appendChild(methodInput);
                });
            });

            // Form submission
            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!selectedPaymentMethod) {
                    alert('Please select a payment method');
                    return;
                }

                // Show loading modal
                document.getElementById('loadingModal').classList.remove('hidden');

                // Submit form
                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('loadingModal').classList.add('hidden');

                        if (data.success) {
                            // Redirect to success page
                            window.location.href = data.redirect_url;
                        } else {
                            alert('Payment failed: ' + data.message);
                        }
                    })
                    .catch(error => {
                        document.getElementById('loadingModal').classList.add('hidden');
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });

            // Countdown timer
            let timeLeft = 900; // 15 minutes in seconds
            const countdownElement = document.getElementById('countdown');

            function updateCountdown() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 0) {
                    alert('Session expired. Please start a new booking.');
                    window.location.href = '/';
                }

                timeLeft--;
            }

            setInterval(updateCountdown, 1000);

            // Phone number formatting
            const phoneInput = document.getElementById('phoneNumber');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 9) {
                        value = value.slice(0, 9);
                    }
                    e.target.value = value;
                });
            }
        });
    </script>

    // Add this script at the end of the checkout.blade.php file
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Payment method selection
    const paymentCards = document.querySelectorAll('.payment-method-card');
    const paymentFields = document.querySelectorAll('.payment-fields');
    const payButton = document.getElementById('payButton');
    const payButtonText = document.getElementById('payButtonText');
    let selectedPaymentMethod = null;

    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            const method = this.dataset.method;

            // Reset all cards
            paymentCards.forEach(c => {
                c.querySelector('div').classList.remove('border-blue-500',
                    'border-green-500', 'shadow-lg');
                c.querySelector('div').classList.add('border-gray-200');
            });

            // Hide all payment fields
            paymentFields.forEach(field => field.classList.add('hidden'));

            // Activate selected card
            if (method === 'mpesa') {
                this.querySelector('div').classList.remove('border-gray-200');
                this.querySelector('div').classList.add('border-green-500', 'shadow-lg');
                document.getElementById('mpesaFields').classList.remove('hidden');
                payButtonText.textContent = 'Pay with M-Pesa';
            } else if (method === 'bank') {
                this.querySelector('div').classList.remove('border-gray-200');
                this.querySelector('div').classList.add('border-blue-500', 'shadow-lg');
                document.getElementById('bankFields').classList.remove('hidden');
                payButtonText.textContent = 'Pay with Bank Transfer';
            }

            selectedPaymentMethod = method;
            payButton.disabled = false;

            // Add hidden input for payment method
            const existingInput = document.querySelector('input[name="payment_method"]');
            if (existingInput) {
                existingInput.remove();
            }

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = 'payment_method';
            methodInput.value = method;
            document.getElementById('checkoutForm').appendChild(methodInput);
        });
    });

    // Form submission with validation and loading modal
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();

        if (!selectedPaymentMethod) {
            alert('Please select a payment method');
            return;
        }

        // Additional validation for required fields based on payment method
        if (selectedPaymentMethod === 'mpesa') {
            const phoneField = document.querySelector('input[name="mpesa_phone"]');
            if (!phoneField || !phoneField.value.trim()) {
                alert('Please enter your M-Pesa phone number');
                if (phoneField) phoneField.focus();
                return;
            }
        } else if (selectedPaymentMethod === 'bank') {
            const accountField = document.querySelector('input[name="bank_account"]');
            const bankField = document.querySelector('select[name="bank_name"]');
            
            if (!accountField || !accountField.value.trim()) {
                alert('Please enter your bank account number');
                if (accountField) accountField.focus();
                return;
            }
            
            if (!bankField || !bankField.value) {
                alert('Please select your bank');
                if (bankField) bankField.focus();
                return;
            }
        }

        // Check terms acceptance
        const termsCheckbox = document.querySelector('input[name="terms_accepted"]');
        if (!termsCheckbox || !termsCheckbox.checked) {
            alert('Please accept the terms and conditions');
            return;
        }

        // Show loading modal
        document.getElementById('loadingModal').classList.remove('hidden');

        // Prepare form data
        const formData = new FormData(this);

        // Add booking data
        const flightDetails = document.querySelector('input[name="flight_details"]');
        if (flightDetails) {
            formData.append('booking_data', flightDetails.value);
        } else {
            const bookingData = {
                booking_reference: document.querySelector('input[name="booking_reference"]').value,
                booking_type: document.querySelector('input[name="booking_type"]').value,
                amount: document.querySelector('input[name="amount"]').value,
                currency: document.querySelector('input[name="currency"]').value
            };
            formData.append('booking_data', JSON.stringify(bookingData));
        }

        // Submit form via AJAX
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loadingModal').classList.add('hidden');

            if (data.success) {
                // Redirect to success page
                window.location.href = data.redirect_url;
            } else {
                alert('Payment failed: ' + data.message);
            }
        })
        .catch(error => {
            document.getElementById('loadingModal').classList.add('hidden');
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });

    // Countdown timer
    let timeLeft = 900; // 15 minutes in seconds
    const countdownElement = document.getElementById('countdown');

    function updateCountdown() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            alert('Session expired. Please start a new booking.');
            window.location.href = '/';
        }

        timeLeft--;
    }

    // Update countdown every second
    setInterval(updateCountdown, 1000);

    // Phone number formatting
    const phoneInput = document.querySelector('input[name="mpesa_phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 12) {
                value = value.slice(0, 12);
            }
            e.target.value = value;
        });
    }
});
</script>

    <style>
        .animation-delay-1000 {
            animation-delay: 1s;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .payment-method-card.selected {
            transform: scale(1.02);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
@endsection
