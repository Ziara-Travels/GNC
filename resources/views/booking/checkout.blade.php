@extends('layouts.main')

@php
    // Get booking details from session
    $bookingDetails = session('booking_details', []);
    $rawFlight = session('selected_flight');
    $flightData = is_string($rawFlight) ? json_decode($rawFlight, true) : $rawFlight;
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

    // Passenger details — Amadeus travelerPricings carries the real per-traveler type.
    $travelerTypes = array_column($flightData['travelerPricings'] ?? [], 'travelerType');
    $adults = count(array_filter($travelerTypes, fn ($t) => $t === 'ADULT')) ?: count($flightData['travelerPricings'] ?? []);
    $children = count(array_filter($travelerTypes, fn ($t) => $t === 'CHILD'));
    $infants = count(array_filter($travelerTypes, fn ($t) => in_array($t, ['HELD_INFANT', 'SEATED_INFANT'])));
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

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/flight-flow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ziara-booking.css') }}?v={{ (file_exists(public_path('css/ziara-booking.css')) ? filemtime(public_path('css/ziara-booking.css')) : 0) }}">
@endpush

@section('content')
    <div class="ziara-bk min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
        <!-- Enhanced Hero Section -->
        <div class="ziara-bk-hero relative bg-gradient-to-br from-blue-800 via-indigo-900 to-purple-900 py-24 overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400/20 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-indigo-400/10 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
            </div>

            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-6xl mx-auto">
                    <!-- Navigation -->
                    <div class="flex items-center justify-between mb-8">
                        <button onclick="history.back()" class="flex items-center gap-2 text-white/90 hover:text-white transition-colors duration-200 group">
                            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:bg-white/30 transition-colors">
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

                    @if($bookingType === 'flight')
                    <div class="ff-step-track mb-8">
                        <a href="{{ route('booking.passengers.show') }}" class="ff-step is-done" style="text-decoration:none;">
                            <span class="ff-step-dot">&#10003;</span> Passengers
                        </a>
                        <span class="ff-step-line"></span>
                        <div class="ff-step is-active">
                            <span class="ff-step-dot">2</span> Payment
                        </div>
                        <span class="ff-step-line"></span>
                        <div class="ff-step">
                            <span class="ff-step-dot">3</span> Confirmation
                        </div>
                    </div>
                    @endif

                    <!-- Checkout Header -->
                    <div class="text-center mb-8">
                        <h1 class="text-5xl md:text-6xl font-bold mb-4 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
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
                                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <i data-lucide="plane" class="h-10 w-10 text-white"></i>
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold text-white mb-2">{{ $fromCity }} → {{ $toCity }}</h2>
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
                                                <div class="text-2xl font-bold">{{ \Carbon\Carbon::parse($departureTime)->format('H:i') }}</div>
                                                <div class="text-sm">{{ \Carbon\Carbon::parse($departureTime)->format('M j') }}</div>
                                            </div>
                                            <div class="flex-1 h-0.5 bg-white/30 relative">
                                                <i data-lucide="plane" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 h-4 w-4 text-white bg-blue-600 rounded-full p-1"></i>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-2xl font-bold">{{ \Carbon\Carbon::parse($arrivalTime)->format('H:i') }}</div>
                                                <div class="text-sm">{{ \Carbon\Carbon::parse($arrivalTime)->format('M j') }}</div>
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
                    <path fill="#ffffff" fill-opacity="1" d="M0,64L80,58.7C160,53,320,43,480,42.7C640,43,800,53,960,58.7C1120,64,1280,64,1360,64L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
                </svg>
            </div>
        </div>

        <!-- Main Checkout Content -->
        <div class="ziara-bk-body container mx-auto px-4 py-12 -mt-16 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col xl:flex-row gap-8">
                    <!-- Left Column - Payment Methods -->
                    <div class="xl:w-2/3">
                        <form id="checkoutForm" method="POST" action="{{ route('booking.process') }}">
                            @csrf
                            <input type="hidden" name="booking_reference" value="{{ $bookingReference }}">
                            <input type="hidden" name="booking_type" value="{{ $bookingType }}">
                            <input type="hidden" name="amount" value="{{ $totalPrice }}">
                            <input type="hidden" name="currency" value="{{ $displayCurrency }}">
                            <input type="hidden" name="flight_details" value="{{ json_encode($flightData) }}">
                            <input type="hidden" name="booking_data" value="{{ json_encode($flightData) }}">

                            <!-- Payment Method Selection -->
                            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 mb-8 overflow-hidden ziara-bk-card">
                                <div class="bg-gradient-to-r from-green-600 to-teal-600 p-6 text-white ziara-bk-cta">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                            <i data-lucide="credit-card" class="h-6 w-6"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">Select Payment Method</h3>
                                            <p class="text-green-100">Choose your preferred payment option</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-8">
                                    <!-- PAYMENT METHOD TABS -->
                                    <div class="flex flex-wrap border-b border-gray-200 mb-8">
                                        <button type="button" class="payment-tab active py-3 px-5 border-b-2 border-blue-500 font-medium text-blue-600" data-tab="mobile-money">
                                            <i data-lucide="smartphone" class="h-5 w-5 inline mr-2"></i>
                                            Mobile Money
                                        </button>
                                        <button type="button" class="payment-tab py-3 px-5 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700" data-tab="cards">
                                            <i data-lucide="credit-card" class="h-5 w-5 inline mr-2"></i>
                                            Credit/Debit Cards
                                        </button>
                                        <button type="button" class="payment-tab py-3 px-5 border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700" data-tab="bank-methods">
                                            <i data-lucide="building-2" class="h-5 w-5 inline mr-2"></i>
                                            Bank Payments
                                        </button>
                                    </div>

                                    <!-- MOBILE MONEY TAB CONTENT -->
                                    <div class="payment-tab-content" id="mobile-money-content">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- M-Pesa Payment -->
                                            <div class="payment-method-card group cursor-pointer" data-method="mpesa">
                                                <div class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-green-500 hover:shadow-lg">
                                                    <div class="flex items-center gap-4 mb-4">
                                                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
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
                                                            <span>Instant payment confirmation</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                            <span>Secure transaction</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="info" class="h-4 w-4 text-gray-400"></i>
                                                            <span>Pay via Paybill 247247, Account 389626</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                           <!-- M-Pesa STK Push -->
                                            <div class="payment-method-card group cursor-pointer" data-method="mpesa_stk">
                                                <div class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-green-500 hover:shadow-lg">
                                                    <div class="flex items-center gap-4 mb-4">
                                                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                                            <i data-lucide="zap" class="h-8 w-8 text-green-600"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-xl font-bold text-gray-800">M-Pesa STK Push</h4>
                                                            <p class="text-gray-600">One-Click M-Pesa Payment</p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                            <span>No need to manually enter paybill</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                            <span>Receive prompt on your phone</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="info" class="h-4 w-4 text-gray-400"></i>
                                                            <span>Just enter M-Pesa PIN when prompted</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Equitel -->
                                            <div class="payment-method-card group cursor-pointer" data-method="equitel">
                                                <div class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-red-500 hover:shadow-lg">
                                                    <div class="flex items-center gap-4 mb-4">
                                                        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                                            <i data-lucide="smartphone" class="h-8 w-8 text-red-600"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-xl font-bold text-gray-800">Equitel</h4>
                                                            <p class="text-gray-600">Equity Bank Mobile Money</p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                            <span>Instant confirmation</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                                            <span>Convenient mobile banking</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="info" class="h-4 w-4 text-gray-400"></i>
                                                            <span>For Equitel customers</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mobile Money Payment Fields -->
                                        <div class="mt-8">
                                            <!-- M-Pesa Fields -->
                                            <div id="mpesaFields" class="payment-fields hidden">
                                                <div class="bg-green-50 rounded-2xl p-6 border border-green-200">
                                                    <h4 class="text-lg font-semibold text-green-800 mb-4 flex items-center gap-2">
                                                        <i data-lucide="smartphone" class="h-5 w-5"></i>
                                                        M-Pesa Payment Details
                                                    </h4>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label for="mpesa_phone" class="block text-sm font-medium text-green-700 mb-1">M-Pesa Phone Number</label>
                                                            <input type="text" name="mpesa_phone" id="mpesa_phone" class="w-full px-4 py-3 rounded-lg border border-green-300 focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white" placeholder="e.g. 07xx xxx xxx">
                                                            <p class="text-xs text-green-600 mt-1">Enter your M-Pesa registered phone number</p>
                                                        </div>

                                                        <div class="bg-white/80 rounded-lg p-4 border border-green-200">
                                                            <h5 class="font-medium text-green-800 mb-2">Payment Instructions:</h5>
                                                            <ol class="text-sm text-gray-600 space-y-2 list-decimal ml-4">
                                                                <li>Enter your phone number and click Pay with M-Pesa</li>
                                                                <li>You will receive an M-Pesa prompt on your phone</li>
                                                                <li>Enter your M-Pesa PIN to complete the payment</li>
                                                                <li>Wait for confirmation message</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- M-Pesa STK Fields -->
                                            <div id="mpesa_stkFields" class="payment-fields hidden">
                                                <div class="bg-green-50 rounded-2xl p-6 border border-green-200">
                                                    <h4 class="text-lg font-semibold text-green-800 mb-4 flex items-center gap-2">
                                                        <i data-lucide="zap" class="h-5 w-5"></i>
                                                        M-Pesa STK Push Payment
                                                    </h4>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label for="mpesa_stk_phone" class="block text-sm font-medium text-green-700 mb-1">M-Pesa Phone Number</label>
                                                            <input type="text" name="mpesa_phone" id="mpesa_stk_phone" class="w-full px-4 py-3 rounded-lg border border-green-300 focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white" placeholder="e.g. 07xx xxx xxx">
                                                            <p class="text-xs text-green-600 mt-1">Enter your M-Pesa registered phone number to receive payment prompt</p>
                                                        </div>

                                                        <div class="bg-white/80 rounded-lg p-4 border border-green-200">
                                                            <h5 class="font-medium text-green-800 mb-2">Payment Instructions:</h5>
                                                            <ol class="text-sm text-gray-600 space-y-2 list-decimal ml-4">
                                                                <li>Enter your M-Pesa phone number above</li>
                                                                <li>Click "Pay with M-Pesa STK Push" button</li>
                                                                <li>You'll receive an automatic payment prompt on your phone</li>
                                                                <li>Enter your M-Pesa PIN to complete the payment</li>
                                                            </ol>
                                                        </div>

                                                        <div class="flex items-center gap-2 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                                            <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-600"></i>
                                                            <p class="text-sm text-yellow-700">Make sure your phone is on and has network coverage to receive the payment prompt.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Equitel Fields -->
                                            <div id="equitelFields" class="payment-fields hidden">
                                                <div class="bg-red-50 rounded-2xl p-6 border border-red-200">
                                                    <h4 class="text-lg font-semibold text-red-800 mb-4 flex items-center gap-2">
                                                        <i data-lucide="smartphone" class="h-5 w-5"></i>
                                                        Equitel Payment Details
                                                    </h4>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label for="equitel_phone" class="block text-sm font-medium text-red-700 mb-1">Equitel Phone Number</label>
                                                            <input type="text" name="equitel_phone" id="equitel_phone" class="w-full px-4 py-3 rounded-lg border border-red-300 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white" placeholder="e.g. 07xx xxx xxx">
                                                            <p class="text-xs text-red-600 mt-1">Enter your Equitel registered phone number</p>
                                                        </div>

                                                        <div class="bg-white/80 rounded-lg p-4 border border-red-200">
                                                            <h5 class="font-medium text-red-800 mb-2">Payment Instructions:</h5>
                                                            <ol class="text-sm text-gray-600 space-y-2 list-decimal ml-4">
                                                                <li>Enter your Equitel phone number and click Pay with Equitel</li>
                                                                <li>You will receive a payment prompt on your Equitel line</li>
                                                                <li>Follow the instructions on your phone to complete the payment</li>
                                                                <li>Wait for confirmation message</li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CARDS TAB CONTENT -->
                                    <div class="payment-tab-content hidden" id="cards-content">
                                        <div class="grid grid-cols-1 gap-6">
                                            <!-- Credit/Debit Card Payment -->
                                            <div class="payment-method-card group cursor-pointer" data-method="card">
                                                <div class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-blue-500 hover:shadow-lg">
                                                    <div class="flex items-center gap-4 mb-4">
                                                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                                            <i data-lucide="credit-card" class="h-8 w-8 text-blue-600"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-xl font-bold text-gray-800">Credit/Debit Card</h4>
                                                            <div class="flex gap-2 mt-1">
                                                                <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" class="h-6">
                                                                <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" class="h-6">
                                                                <img src="{{ asset('images/payment/amex.svg') }}" alt="American Express" class="h-6">
                                                                <img src="{{ asset('images/payment/unionpay.svg') }}" alt="Union Pay" class="h-6">
                                                                <img src="{{ asset('images/payment/jcb.svg') }}" alt="JCB" class="h-6">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Card Payment Fields -->
                                            <div id="cardFields" class="payment-fields hidden">
                                                <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                                                    <h4 class="text-lg font-semibold text-blue-800 mb-4 flex items-center gap-2">
                                                        <i data-lucide="credit-card" class="h-5 w-5"></i>
                                                        Card Payment Details
                                                    </h4>
                                                    
                                                    <!-- Card visualization -->
                                                    <div class="relative h-56 w-full max-w-md mx-auto mb-8 bg-gradient-to-r from-gray-900 to-gray-800 rounded-xl shadow-lg p-6 overflow-hidden">
                                                        <!-- Card chip & brand -->
                                                        <div class="flex justify-between">
                                                            <div class="h-12 w-16 bg-yellow-500/60 rounded-lg flex items-center justify-center overflow-hidden backdrop-blur-sm">
                                                                <div class="h-8 w-8 bg-yellow-400/80 rounded-md"></div>
                                                            </div>
                                                            <div id="cardBrand" class="text-white text-2xl font-bold opacity-80">VISA</div>
                                                        </div>
                                                        
                                                        <!-- Card number -->
                                                        <div class="mt-6">
                                                            <div class="text-white/80 text-sm">Card Number</div>
                                                            <div id="cardNumberDisplay" class="text-white text-xl tracking-widest mt-1 font-mono">•••• •••• •••• ••••</div>
                                                        </div>
                                                        
                                                        <!-- Card holder & expiration -->
                                                        <div class="mt-6 flex justify-between">
                                                            <div>
                                                                <div class="text-white/80 text-xs">Card Holder</div>
                                                                <div id="cardHolderDisplay" class="text-white text-base mt-1 font-medium truncate max-w-[150px]">YOUR NAME</div>
                                                            </div>
                                                            <div>
                                                                <div class="text-white/80 text-xs">Expires</div>
                                                                <div id="cardExpiryDisplay" class="text-white text-base mt-1 font-medium">MM/YY</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Card background patterns -->
                                                        <div class="absolute top-0 right-0 w-full h-full overflow-hidden opacity-10">
                                                            <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full border-8 border-white/20"></div>
                                                            <div class="absolute top-20 -right-10 w-60 h-60 rounded-full border-8 border-white/20"></div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Card payment form -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label for="card_number" class="block text-sm font-medium text-blue-700 mb-1">Card Number</label>
                                                            <input type="text" name="card_number" id="card_number" class="w-full px-4 py-3 rounded-lg border border-blue-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="1234 5678 9012 3456" maxlength="19">
                                                        </div>
                                                        
                                                        <div>
                                                            <label for="card_holder" class="block text-sm font-medium text-blue-700 mb-1">Card Holder Name</label>
                                                            <input type="text" name="card_holder" id="card_holder" class="w-full px-4 py-3 rounded-lg border border-blue-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="Name on card">
                                                        </div>
                                                        
                                                        <div class="grid grid-cols-2 gap-4">
                                                            <div>
                                                                <label for="card_expiry" class="block text-sm font-medium text-blue-700 mb-1">Expiry Date</label>
                                                                <input type="text" name="card_expiry" id="card_expiry" class="w-full px-4 py-3 rounded-lg border border-blue-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="MM/YY" maxlength="5">
                                                            </div>
                                                            
                                                            <div>
                                                                <label for="card_cvv" class="block text-sm font-medium text-blue-700 mb-1">CVV/CVC</label>
                                                                <input type="password" name="card_cvv" id="card_cvv" class="w-full px-4 py-3 rounded-lg border border-blue-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white" placeholder="***" maxlength="4">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex items-center gap-2 bg-blue-100/50 p-3 rounded-lg border border-blue-200">
                                                            <i data-lucide="shield-check" class="h-5 w-5 text-blue-600"></i>
                                                            <p class="text-sm text-blue-700">Your payment information is encrypted and secure.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- BANK PAYMENT TAB CONTENT -->
                                    <div class="payment-tab-content hidden" id="bank-methods-content">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Direct Bank Transfer -->
                                            <div class="payment-method-card group cursor-pointer" data-method="bank">
                                                <div class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-indigo-500 hover:shadow-lg">
                                                    <div class="flex items-center gap-4 mb-4">
                                                        <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                                            <i data-lucide="building-2" class="h-8 w-8 text-indigo-600"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-xl font-bold text-gray-800">Bank Transfer</h4>
                                                            <p class="text-gray-600">Direct bank payment</p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-indigo-500"></i>
                                                            <span>Secure bank transfer</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-indigo-500"></i>
                                                            <span>No additional fees</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pay with Equity -->
                                            <div class="payment-method-card group cursor-pointer" data-method="equity">
                                                <div class="border-2 border-gray-200 rounded-2xl p-6 transition-all duration-300 hover:border-red-500 hover:shadow-lg">
                                                    <div class="flex items-center gap-4 mb-4">
                                                        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                                            <i data-lucide="landmark" class="h-8 w-8 text-red-600"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-xl font-bold text-gray-800">Pay with Equity</h4>
                                                            <p class="text-gray-600">Equity Bank Account</p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-red-500"></i>
                                                            <span>For Equity Bank customers</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                                            <i data-lucide="check" class="h-4 w-4 text-red-500"></i>
                                                            <span>Fast & secure processing</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bank Payment Fields -->
                                        <div class="mt-8">
                                            <!-- Direct Bank Transfer Fields -->
                                            <div id="bankFields" class="payment-fields hidden">
                                                <div class="bg-indigo-50 rounded-2xl p-6 border border-indigo-200">
                                                    <h4 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center gap-2">
                                                        <i data-lucide="building-2" class="h-5 w-5"></i>
                                                        Bank Transfer Details
                                                    </h4>
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label for="bank_name" class="block text-sm font-medium text-indigo-700 mb-1">Select Your Bank</label>
                                                            <select name="bank_name" id="bank_name" class="w-full px-4 py-3 rounded-lg border border-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                                                                <option value="">Select your bank</option>
                                                                <option value="KCB">KCB Bank</option>
                                                                <option value="Equity">Equity Bank</option>
                                                                <option value="Standard Chartered">Standard Chartered</option>
                                                                <option value="Barclays">ABSA Bank</option>
                                                                <option value="Co-operative">Co-operative Bank</option>
                                                                <option value="NCBA">NCBA Bank</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label for="account_number" class="block text-sm font-medium text-indigo-700 mb-1">Account Number</label>
                                                            <input type="text" name="bank_account" id="account_number" class="w-full px-4 py-3 rounded-lg border border-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" placeholder="Enter your account number">
                                                        </div>
                                                        <div>
                                                            <label for="account_holder_name" class="block text-sm font-medium text-indigo-700 mb-1">Account Holder Name</label>
                                                            <input type="text" name="account_holder_name" id="account_holder_name" class="w-full px-4 py-3 rounded-lg border border-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" placeholder="Enter account holder name">
                                                        </div>
                                                        <div>
                                                            <label for="branch_code" class="block text-sm font-medium text-indigo-700 mb-1">Branch Code (Optional)</label>
                                                            <input type="text" name="branch_code" id="branch_code" class="w-full px-4 py-3 rounded-lg border border-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white" placeholder="e.g. 01">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mt-4 p-4 bg-white rounded-lg border border-indigo-100">
                                                        <h5 class="font-medium text-indigo-800 mb-2">How Bank Transfer Works:</h5>
                                                        <ol class="text-sm text-gray-600 space-y-2 list-decimal ml-4">
                                                            <li>Enter your bank details and submit the form</li>
                                                            <li>You'll be redirected to your bank's secure page</li>
                                                            <li>Complete authentication as required by your bank</li>
                                                            <li>Once payment is confirmed, you'll be redirected back</li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pay with Equity Fields -->
                                            <div id="equityFields" class="payment-fields hidden">
                                                <div class="bg-red-50 rounded-2xl p-6 border border-red-200">
                                                    <h4 class="text-lg font-semibold text-red-800 mb-4 flex items-center gap-2">
                                                        <i data-lucide="landmark" class="h-5 w-5"></i>
                                                        Equity Bank Account Details
                                                    </h4>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label for="equity_account" class="block text-sm font-medium text-red-700 mb-1">Equity Account Number</label>
                                                            <input type="text" name="equity_account" id="equity_account" class="w-full px-4 py-3 rounded-lg border border-red-300 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white" placeholder="Enter your Equity account number">
                                                        </div>
                                                        
                                                        <div>
                                                            <label for="equity_name" class="block text-sm font-medium text-red-700 mb-1">Account Holder Name</label>
                                                            <input type="text" name="equity_name" id="equity_name" class="w-full px-4 py-3 rounded-lg border border-red-300 focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white" placeholder="Enter account holder name">
                                                        </div>

                                                        <div class="bg-white rounded-lg p-4 border border-red-100">
                                                            <h5 class="font-medium text-red-800 mb-2">Equity Bank Payment Process:</h5>
                                                            <ol class="text-sm text-gray-600 space-y-2 list-decimal ml-4">
                                                                <li>Enter your Equity Bank account details</li>
                                                                <li>Click "Pay with Equity Bank" button</li>
                                                                <li>You'll receive an authentication prompt</li>
                                                                <li>Authorize the payment to complete the transaction</li>
                                                            </ol>
                                                        </div>

                                                        <div class="flex items-center gap-2 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                                            <i data-lucide="alert-circle" class="h-5 w-5 text-yellow-600"></i>
                                                            <p class="text-sm text-yellow-700">This option is only available for Equity Bank account holders.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- "Payment Link" option removed: it had no backend — the button only
                                         faked a success message after a setTimeout. A real send-a-payment-
                                         link feature is a separate future feature, not implemented here. --}}

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
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
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
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                                                <i data-lucide="plane" class="h-6 w-6 text-white"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800">{{ $fromCity }} → {{ $toCity }}</div>
                                                <div class="text-sm text-gray-600">{{ $airlineName }} {{ $flightNumber }}</div>
                                            </div>
                                        </div>

                                        <!-- Passenger Breakdown -->
                                        <div class="space-y-3">
                                            @if ($adults > 0)
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="user" class="h-4 w-4 text-gray-500"></i>
                                                        <span class="text-sm text-gray-600">{{ $adults }} Adult{{ $adults > 1 ? 's' : '' }}</span>
                                                    </div>
                                                    <span class="font-semibold">{{ $displaySymbol }}{{ number_format($basePrice, $isKenyan ? 0 : 2) }}</span>
                                                </div>
                                            @endif

                                            @if ($children > 0)
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="baby" class="h-4 w-4 text-gray-500"></i>
                                                        <span class="text-sm text-gray-600">{{ $children }} Child{{ $children > 1 ? 'ren' : '' }}</span>
                                                    </div>
                                                    <span class="font-semibold">{{ $displaySymbol }}{{ number_format($basePrice * 0.75, $isKenyan ? 0 : 2) }}</span>
                                                </div>
                                            @endif

                                            @if ($infants > 0)
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <i data-lucide="heart" class="h-4 w-4 text-gray-500"></i>
                                                        <span class="text-sm text-gray-600">{{ $infants }} Infant{{ $infants > 1 ? 's' : '' }}</span>
                                                    </div>
                                                    <span class="font-semibold">{{ $displaySymbol }}{{ number_format($basePrice * 0.1, $isKenyan ? 0 : 2) }}</span>
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
                                                <span class="font-semibold text-green-600">-{{ $displaySymbol }}{{ number_format($referralDiscount, $isKenyan ? 0 : 2) }}</span>
                                            </div>
                                        @endif

                                        <!-- Fees and Taxes -->
                                        <div class="pt-4 border-t border-gray-200 space-y-3">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-2">
                                                    <i data-lucide="calculator" class="h-4 w-4 text-gray-500"></i>
                                                    <span class="text-sm text-gray-600">Taxes & Fees</span>
                                                </div>
                                                <span class="font-semibold">{{ $displaySymbol }}{{ number_format($taxes, $isKenyan ? 0 : 2) }}</span>
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
                                                        <div class="text-sm text-gray-500">~${{ number_format($totalPrice, 2) }} USD</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Payment Button -->
                                        <button type="submit" form="checkoutForm" id="payButton" disabled
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
                                    <i data-lucide="shield-check" class="h-5 w-5"></i>
                                    Secure Booking
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    Your payment is processed securely with bank-level encryption. We do not store your payment details.
                                </p>
                                <ul class="list-disc list-inside space-y-2 text-sm text-gray-600">
                                    <li>Bank-level encryption for all transactions</li>
                                    <li>PCI DSS compliant payment processing</li>
                                    <li>Two-factor authentication for added security</li>
                                    <li>24/7 fraud monitoring and alerts</li>
                                    <li>Secure payment gateways with SSL encryption</li>    
                                    <li>Data protection compliant with GDPR and local laws</li>
                                </ul>
                                <div class="mt-6 flex items-center gap-4">
                                    <img src="{{ asset('images/ssl-secure.png') }}" alt="SSL Secure" class="h-10">
                                    <img src="{{ asset('images/pci-compliant.png') }}" alt="PCI Compliant" class="h-10">
                                    <img src="{{ asset('images/two-factor-auth.png') }}" alt="Two-Factor Authentication" class="h-10">
                                    <img src="{{ asset('images/fraud-monitoring.png') }}" alt="Fraud Monitoring" class="h-10">
                                    <img src="{{ asset('images/data-protection.png') }}" alt="Data Protection" class="h-10">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Payment Methods</h4>
                    <ul class="list-disc list-inside space-y-2 text-sm text-gray-600">
                        <li>Credit/Debit Card</li>
                        <li>Mobile Money (M-Pesa)</li>
                        <li>Bank Transfer</li>
                    </ul>
                </div>
            </div>  
        </div>

        <!-- Footer Section -->
        <div class="ziara-bk-cta bg-gray-100 border-t border-gray-200 mt-12 py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-7xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Payment Methods -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Payment Methods</h4>
                            <div class="flex flex-wrap gap-3">
                                <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" class="h-8">
                                <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" class="h-8">
                                <img src="{{ asset('images/payment/mpesa.svg') }}" alt="M-Pesa" class="h-8">
                                <img src="{{ asset('images/payment/amex.svg') }}" alt="American Express" class="h-8">
                                <img src="{{ asset('images/payment/paypal.svg') }}" alt="PayPal" class="h-8">
                                <img src="{{ asset('images/payment/discover.svg') }}" alt="Discover" class="h-8">
                            </div>
                        </div>
                        
                        <!-- Customer Support -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Customer Support</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center gap-2">
                                    <i data-lucide="phone" class="h-4 w-4"></i>
                                    <span>+254 (0) 723 456 789</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="mail" class="h-4 w-4"></i>
                                    <span>support@travelbooking.com</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i data-lucide="message-circle" class="h-4 w-4"></i>
                                    <span>24/7 Live Chat Support</span>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Legal Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Legal Information</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li><a href="#" class="hover:underline hover:text-blue-600">Terms and Conditions</a></li>
                                <li><a href="#" class="hover:underline hover:text-blue-600">Privacy Policy</a></li>
                                <li><a href="#" class="hover:underline hover:text-blue-600">Cancellation Policy</a></li>
                                <li><a href="#" class="hover:underline hover:text-blue-600">Refund Policy</a></li>
                            </ul>
                        </div>
                        
                        <!-- Company Information -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Company Information</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li><a href="#" class="hover:underline hover:text-blue-600">About Us</a></li>
                                <li><a href="#" class="hover:underline hover:text-blue-600">Our Partners</a></li>
                                <li><a href="#" class="hover:underline hover:text-blue-600">Career Opportunities</a></li>
                                <li><a href="#" class="hover:underline hover:text-blue-600">Blog & News</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-300 mt-8 pt-8 text-center">
                        <p class="text-sm text-gray-600">&copy; {{ date('Y') }} Travel Booking. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment tab handling
        const paymentTabs = document.querySelectorAll('.payment-tab');
        const paymentContents = document.querySelectorAll('.payment-tab-content');
        
        paymentTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                paymentTabs.forEach(t => {
                    t.classList.remove('active', 'border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500');
                });
                
                // Add active class to clicked tab
                tab.classList.add('active', 'border-blue-500', 'text-blue-600');
                tab.classList.remove('border-transparent', 'text-gray-500');
                
                // Hide all tab contents
                paymentContents.forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Show selected tab content
                const tabId = tab.dataset.tab;
                document.getElementById(`${tabId}-content`).classList.remove('hidden');
            });
        });
        
        // Payment method cards
        const paymentMethodCards = document.querySelectorAll('.payment-method-card');
        const payButton = document.getElementById('payButton');
        const payButtonText = document.getElementById('payButtonText');
        let selectedMethod = null;
        
        paymentMethodCards.forEach(card => {
            card.addEventListener('click', () => {
                // Deselect all cards
                paymentMethodCards.forEach(c => {
                    const borderDiv = c.querySelector('div');
                    borderDiv.classList.remove('border-green-500', 'border-blue-500', 'border-indigo-500', 'border-red-500');
                    borderDiv.classList.add('border-gray-200');
                });
                
                // Select clicked card
                const method = card.dataset.method;
                selectedMethod = method;
                const borderDiv = card.querySelector('div');
                
                if (method === 'mpesa' || method === 'mpesa_stk') {
                    borderDiv.classList.add('border-green-500');
                } else if (method === 'card') {
                    borderDiv.classList.add('border-blue-500');
                } else if (method === 'bank') {
                    borderDiv.classList.add('border-indigo-500');
                } else if (method === 'equity' || method === 'equitel') {
                    borderDiv.classList.add('border-red-500');
                }
                
                // Hide all payment fields
                document.querySelectorAll('.payment-fields').forEach(field => {
                    field.classList.add('hidden');
                });
                
                // Show selected payment fields
                const fieldId = `${method}Fields`;
                if (document.getElementById(fieldId)) {
                    document.getElementById(fieldId).classList.remove('hidden');
                }
                
                // Enable pay button
                payButton.disabled = false;
                payButtonText.textContent = `Pay with ${getPaymentMethodName(method)}`;
                
                // Update form action if needed
                // updateFormAction(method);
            });
        });
        
        // Function to get payment method display name
        function getPaymentMethodName(method) {
            const methodNames = {
                'mpesa': 'M-Pesa',
                'mpesa_stk': 'M-Pesa STK Push',
                'card': 'Credit/Debit Card',
                'bank': 'Bank Transfer',
                'equity': 'Equity Bank',
                'equitel': 'Equitel'
            };
            
            return methodNames[method] || 'Selected Method';
        }
        
        // Countdown timer
        let timeLeft = 15 * 60; // 15 minutes in seconds
        const countdownDisplay = document.getElementById('countdown');
        
        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                alert('Your booking session has expired. Please start again.');
                window.location.href = "{{ route('home') }}";
            }
            
            timeLeft -= 1;
        }
        
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
        
        // Card visualizer for credit card inputs
        const cardNumberInput = document.getElementById('card_number');
        const cardHolderInput = document.getElementById('card_holder');
        const cardExpiryInput = document.getElementById('card_expiry');
        const cardNumberDisplay = document.getElementById('cardNumberDisplay');
        const cardHolderDisplay = document.getElementById('cardHolderDisplay');
        const cardExpiryDisplay = document.getElementById('cardExpiryDisplay');
        const cardBrand = document.getElementById('cardBrand');
        
        if (cardNumberInput) {
            // Format card number with spaces
            cardNumberInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                let formattedValue = '';
                
                for (let i = 0; i < value.length; i++) {
                    if (i > 0 && i % 4 === 0) {
                        formattedValue += ' ';
                    }
                    formattedValue += value[i];
                }
                
                e.target.value = formattedValue.slice(0, 19); // Limit to 16 digits + 3 spaces
                
                // Update card display
                if (value.length > 0) {
                    cardNumberDisplay.textContent = formattedValue;
                    
                    // Set card brand based on first digit
                    const firstDigit = value[0];
                    if (firstDigit === '4') {
                        cardBrand.textContent = 'VISA';
                    } else if (firstDigit === '5') {
                        cardBrand.textContent = 'MASTERCARD';
                    } else if (firstDigit === '3') {
                        cardBrand.textContent = 'AMEX';
                    } else if (firstDigit === '6') {
                        cardBrand.textContent = 'DISCOVER';
                    } else {
                        cardBrand.textContent = 'CARD';
                    }
                } else {
                    cardNumberDisplay.textContent = '•••• •••• •••• ••••';
                    cardBrand.textContent = 'CARD';
                }
            });
        }
        
        if (cardHolderInput) {
            cardHolderInput.addEventListener('input', function(e) {
                cardHolderDisplay.textContent = e.target.value || 'YOUR NAME';
            });
        }
        
        if (cardExpiryInput) {
            cardExpiryInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length > 2) {
                    value = value.slice(0, 2) + '/' + value.slice(2, 4);
                }
                
                e.target.value = value.slice(0, 5);
                cardExpiryDisplay.textContent = value || 'MM/YY';
            });
        }
        
        // Form validation
        const checkoutForm = document.getElementById('checkoutForm');
        const termsCheckbox = document.querySelector('input[name="terms_accepted"]');
        
        if (checkoutForm && termsCheckbox) {
            termsCheckbox.addEventListener('change', function() {
                if (selectedMethod && this.checked) {
                    payButton.disabled = false;
                } else {
                    payButton.disabled = true;
                }
            });
            
            checkoutForm.addEventListener('submit', function(e) {
                if (!selectedMethod) {
                    e.preventDefault();
                    alert('Please select a payment method.');
                }
                
                if (!termsCheckbox.checked) {
                    e.preventDefault();
                    alert('Please accept the terms and conditions.');
                }
            });
        }
        
        // M-Pesa STK Push specific functionality
    const mpesaStkForm = document.querySelector('.payment-method-card[data-method="mpesa_stk"]');
    const mpesaStkButton = document.getElementById('mpesaStkButton');
    
    if (mpesaStkForm && mpesaStkButton) {
        mpesaStkButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const phoneNumber = document.getElementById('mpesa_stk_phone').value;
            if (!phoneNumber) {
                alert('Please enter your M-Pesa phone number');
                return;
            }
            
            // Show loading state
            this.disabled = true;
            this.innerHTML = '<i data-lucide="loader" class="h-5 w-5 animate-spin"></i> Processing...';
            
            // Get form data
            const formData = new FormData(document.getElementById('checkoutForm'));
            formData.append('payment_method', 'mpesa_stk');
            formData.append('mpesa_phone', phoneNumber);
            
            // Send AJAX request
            fetch('{{ route("booking.process") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show STK Push status checking UI
                    document.getElementById('stkPushStatus').classList.remove('hidden');
                    document.getElementById('stkPushInitial').classList.add('hidden');
                    
                    // Start checking payment status
                    const orderRef = data.transaction_id;
                    startCheckingPaymentStatus(orderRef);
                } else {
                    // Show error
                    this.disabled = false;
                    this.innerHTML = 'Pay with M-Pesa STK Push';
                    alert('Error: ' + data.message);
                }
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.disabled = false;
                this.innerHTML = 'Pay with M-Pesa STK Push';
                alert('An error occurred. Please try again.');
                
                // Re-initialize Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    }
    
    // Function to check payment status
    function startCheckingPaymentStatus(orderRef) {
        const statusElement = document.getElementById('paymentStatusText');
        const progressBar = document.getElementById('paymentProgressBar');
        let checkCount = 0;
        
        function checkStatus() {
            fetch(`/payments/mpesa/check-status/${orderRef}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.is_paid) {
                            // Payment successful
                            statusElement.innerHTML = '<span class="text-green-600">✓ Payment successful! Redirecting...</span>';
                            progressBar.style.width = '100%';
                            progressBar.classList.remove('bg-blue-500');
                            progressBar.classList.add('bg-green-500');
                            
                            // Redirect to success page
                            setTimeout(() => {
                                window.location.href = data.redirect_url || '/booking/success/' + orderRef;
                            }, 2000);
                        } else if (checkCount < 12) { // Check for up to 1 minute (5s * 12 = 60s)
                            // Payment still processing
                            checkCount++;
                            const progressWidth = (checkCount / 12 * 100);
                            progressBar.style.width = `${progressWidth}%`;
                            
                            statusElement.textContent = `Checking payment status (${checkCount}/12)...`;
                            
                            // Check again after 5 seconds
                            setTimeout(checkStatus, 5000);
                        } else {
                            // Timeout - no confirmation yet
                            statusElement.innerHTML = '<span class="text-yellow-600">Payment not confirmed yet. Please check your M-Pesa app.</span>';
                            
                            // Add retry button
                            const retryBtn = document.createElement('button');
                            retryBtn.className = 'mt-3 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded';
                            retryBtn.textContent = 'Check Again';
                            retryBtn.onclick = function() {
                                checkCount = 0;
                                statusElement.textContent = 'Checking payment status...';
                                checkStatus();
                            };
                            
                            document.getElementById('stkPushStatusActions').innerHTML = '';
                            document.getElementById('stkPushStatusActions').appendChild(retryBtn);
                        }
                    } else {
                        // Error checking status
                        statusElement.innerHTML = '<span class="text-red-600">Error checking payment status.</span>';
                        
                        // Add retry button
                        const retryBtn = document.createElement('button');
                        retryBtn.className = 'mt-3 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded';
                        retryBtn.textContent = 'Retry';
                        retryBtn.onclick = function() {
                            statusElement.textContent = 'Checking payment status...';
                            checkStatus();
                        };
                        
                        document.getElementById('stkPushStatusActions').innerHTML = '';
                        document.getElementById('stkPushStatusActions').appendChild(retryBtn);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusElement.innerHTML = '<span class="text-red-600">Error checking payment status.</span>';
                    
                    // Add retry button
                    const retryBtn = document.createElement('button');
                    retryBtn.className = 'mt-3 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded';
                    retryBtn.textContent = 'Retry';
                    retryBtn.onclick = function() {
                        statusElement.textContent = 'Checking payment status...';
                        checkStatus();
                    };
                    
                    document.getElementById('stkPushStatusActions').innerHTML = '';
                    document.getElementById('stkPushStatusActions').appendChild(retryBtn);
                });
        }
        
        // Start the first check
        checkStatus();
    }
    });
</script>
@endsection