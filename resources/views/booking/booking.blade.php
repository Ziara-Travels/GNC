@extends('layouts.main')

@push('styles')
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
        animation: fadeOutDown 0.3s ease-in forwards;
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-700 to-indigo-800 py-16">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-indigo-400/10 rounded-full blur-3xl animate-pulse"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto">
                @php
                    // Flight information from Amadeus API
                    $fromCity = $flight->itineraries[0]->segments[0]->departure->iataCode;
                    $toCity = $flight->itineraries[0]->segments[count($flight->itineraries[0]->segments) - 1]->arrival->iataCode;
                    $travelClass = $flight->travelerPricings[0]->fareDetailsBySegment[0]->cabin;
                    $passengerCount = count($flight->travelerPricings);
                    
                    // Format travel class for display
                    $formattedClass = str_replace('_', ' ', $travelClass);
                    $formattedClass = ucwords(strtolower($formattedClass));
                    
                    // Determine flight type
                    $flightType = isset($flight->itineraries[1]) ? 'Round Trip' : 'One Way';

                    // Calculate flight duration
                    $departureTime = new DateTime($flight->itineraries[0]->segments[0]->departure->at);
                    $arrivalTime = new DateTime($flight->itineraries[0]->segments[count($flight->itineraries[0]->segments) - 1]->arrival->at);
                    $duration = $departureTime->diff($arrivalTime);
                    $durationStr = ($duration->h > 0 ? $duration->h . 'h ' : '') . ($duration->i > 0 ? $duration->i . 'm' : '');

                    // Get airline info
                    $airlineCode = $flight->itineraries[0]->segments[0]->carrierCode;
                    $airlines = [
                        'KQ' => 'Kenya Airways',
                        'ET' => 'Ethiopian Airlines',
                        'EK' => 'Emirates',
                        'QR' => 'Qatar Airways',
                        'BA' => 'British Airways',
                        'LH' => 'Lufthansa',
                        'AF' => 'Air France',
                        'KL' => 'KLM',
                        'TK' => 'Turkish Airlines',
                        'SA' => 'South African Airways',
                        'DL' => 'Delta Airlines',
                        'UA' => 'United Airlines',
                        'AA' => 'American Airlines',
                    ];
                    $airlineName = $airlines[$airlineCode] ?? $airlineCode;
                @endphp
                
                <div class="flex items-center mb-4">
                    <a href="{{ route('flights.search') }}" class="text-white hover:text-blue-200 mr-4 flex items-center gap-1">
                        <i data-lucide="arrow-left" class="h-5 w-5"></i>
                        <span>Back to results</span>
                    </a>
                    <h1 class="text-3xl font-bold text-white">Flight Details</h1>
                </div>
                
                <div class="text-center">
                    <div class="flex items-center justify-center gap-4 mb-4">
                        <div class="text-white">
                            <span class="text-2xl font-bold">{{ $fromCity }}</span>
                            <div class="text-sm opacity-90">{{ date('M j, Y', strtotime($flight->itineraries[0]->segments[0]->departure->at)) }}</div>
                        </div>
                        <div class="text-white">
                            <i data-lucide="plane" class="h-8 w-8"></i>
                        </div>
                        <div class="text-white">
                            <span class="text-2xl font-bold">{{ $toCity }}</span>
                            <div class="text-sm opacity-90">{{ date('M j, Y', strtotime($flight->itineraries[0]->segments[count($flight->itineraries[0]->segments) - 1]->arrival->at)) }}</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-6 text-white opacity-90">
                        <span>{{ $flightType }}</span>
                        <span>•</span>
                        <span>{{ $passengerCount }} Passenger{{ $passengerCount > 1 ? 's' : '' }}</span>
                        <span>•</span>
                        <span>{{ $formattedClass }}</span>
                        <span>•</span>
                        <span>{{ $durationStr }}</span>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="flex justify-center gap-4 mt-8">
                    <button onclick="document.getElementById('passengerForm').scrollIntoView({behavior: 'smooth'})" class="bg-white text-blue-700 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition-colors flex items-center gap-2">
                        <i data-lucide="users" class="h-5 w-5"></i>
                        Add Passengers
                    </button>
                    
                    <button onclick="document.querySelector('.booking-summary').scrollIntoView({behavior: 'smooth'})" class="bg-blue-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-600 transition-colors flex items-center gap-2">
                        <i data-lucide="credit-card" class="h-5 w-5"></i>
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" class="w-full h-auto">
                <path fill="#ffffff" fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 -mt-6">
        <div class="flex flex-col xl:flex-row gap-8">
            <!-- Left Side - Flight Details -->
            <div class="flex-1">
                <!-- Flight Status Badge -->
                <div class="flex items-center gap-2 mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i>
                        Available for Booking
                    </span>
                    
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i data-lucide="shield" class="h-4 w-4 mr-1"></i>
                        Secure Payment
                    </span>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <i data-lucide="calendar" class="h-4 w-4 mr-1"></i>
                        Instant Confirmation
                    </span>
                </div>

                <!-- Flight Information Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i data-lucide="plane-takeoff" class="h-6 w-6 text-blue-600"></i>
                        Flight Details
                    </h2>
                    
                    <!-- Airline Information -->
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-8">
                        <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center p-2 shadow-sm">
                            <img src="{{ asset('images/airlines/' . strtolower($airlineCode) . '.png') }}" alt="{{ $airlineName }}" 
                                onerror="this.onerror=null; this.src='{{ asset('images/airlines/default.png') }}';" class="max-w-full max-h-full">
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg text-gray-800">{{ $airlineName }}</h3>
                            <p class="text-sm text-gray-600">{{ $airlineCode }} · {{ $flight->itineraries[0]->segments[0]->number }}</p>
                        </div>
                        <div class="ml-auto text-right">
                            <div class="flex items-center gap-1 text-yellow-500 mb-1">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < 4)
                                        <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                    @else
                                        <i data-lucide="star" class="h-4 w-4"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-sm text-gray-600">Excellent Airline</p>
                        </div>
                    </div>
                    
                    @foreach($flight->itineraries as $itineraryIndex => $itinerary)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                @if($itineraryIndex == 0)
                                    <i data-lucide="plane-takeoff" class="h-5 w-5 text-blue-600"></i>
                                    Outbound Flight
                                @else
                                    <i data-lucide="plane-landing" class="h-5 w-5 text-blue-600"></i>
                                    Return Flight
                                @endif
                            </h3>
                            
                            <!-- Progress Timeline -->
                            <div class="relative pt-8 pb-2">
                                <div class="absolute top-0 left-6 h-full w-0.5 bg-blue-100"></div>
                                
                                @foreach($itinerary->segments as $segmentIndex => $segment)
                                    <div class="relative mb-8">
                                        <!-- Timeline Dot -->
                                        <div class="absolute left-6 top-0 transform -translate-x-1/2 -translate-y-1/2 w-4 h-4 rounded-full border-2 border-blue-500 bg-white z-10"></div>
                                        
                                        <div class="ml-10 bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                                            <div class="flex justify-between items-start">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <div class="font-semibold text-lg text-gray-800">
                                                            {{ $segment->departure->iataCode }} → {{ $segment->arrival->iataCode }}
                                                        </div>
                                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-md">
                                                            {{ $segment->carrierCode }} {{ $segment->number }}
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-gray-600">
                                                        {{ date('M j, Y', strtotime($segment->departure->at)) }}
                                                    </div>
                                                    <div class="flex items-center gap-3 text-gray-700">
                                                        <span class="font-medium">{{ date('H:i', strtotime($segment->departure->at)) }}</span>
                                                        <div class="flex-1 h-px bg-gray-300 relative">
                                                            <i data-lucide="plane" class="absolute top-1/2 left-1/2 transform -translate-y-1/2 -translate-x-1/2 h-3 w-3 text-blue-500"></i>
                                                        </div>
                                                        <span class="font-medium">{{ date('H:i', strtotime($segment->arrival->at)) }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <!-- Calculate segment duration -->
                                                    @php
                                                        $segmentDepartureTime = new DateTime($segment->departure->at);
                                                        $segmentArrivalTime = new DateTime($segment->arrival->at);
                                                        $segmentDuration = $segmentDepartureTime->diff($segmentArrivalTime);
                                                        $segmentDurationStr = ($segmentDuration->h > 0 ? $segmentDuration->h . 'h ' : '') . ($segmentDuration->i > 0 ? $segmentDuration->i . 'm' : '');
                                                    @endphp
                                                    <div class="text-sm font-medium text-gray-700">{{ $segmentDurationStr }}</div>
                                                    <div class="text-xs text-gray-600 mt-1">{{ $segment->aircraft->code ?? 'Aircraft N/A' }}</div>
                                                </div>
                                            </div>
                                            
                                            <!-- Amenities -->
                                            <div class="mt-3 pt-3 border-t border-gray-100">
                                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                                    <span class="flex items-center gap-1">
                                                        <i data-lucide="wifi" class="h-3 w-3"></i>
                                                        WiFi
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <i data-lucide="utensils" class="h-3 w-3"></i>
                                                        Meal
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <i data-lucide="plug" class="h-3 w-3"></i>
                                                        Power
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <i data-lucide="tv" class="h-3 w-3"></i>
                                                        Entertainment
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Layover information if not the last segment -->
                                    @if(!$loop->last)
                                        @php
                                            $currentArrival = new DateTime($segment->arrival->at);
                                            $nextDeparture = new DateTime($itinerary->segments[$segmentIndex + 1]->departure->at);
                                            $layoverDuration = $currentArrival->diff($nextDeparture);
                                            $layoverDurationStr = ($layoverDuration->h > 0 ? $layoverDuration->h . 'h ' : '') . ($layoverDuration->i > 0 ? $layoverDuration->i . 'm' : '');
                                        @endphp
                                        
                                        <div class="relative mb-8">
                                            <!-- Timeline Dot for layover -->
                                            <div class="absolute left-6 top-0 transform -translate-x-1/2 -translate-y-1/2 w-3 h-3 rounded-full bg-gray-300 z-10"></div>
                                            
                                            <div class="ml-10 py-2 px-4 bg-gray-50 rounded-lg border border-gray-100 inline-flex items-center gap-2">
                                                <i data-lucide="clock" class="h-4 w-4 text-gray-500"></i>
                                                <span class="text-sm text-gray-700">Layover in {{ $segment->arrival->iataCode }}: <span class="font-medium">{{ $layoverDurationStr }}</span></span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <!-- Flight Details Table -->
                    <div class="overflow-hidden rounded-xl border border-gray-200 mt-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Information</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Flight Type</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $flightType }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Booking Class</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $formattedClass }}</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Baggage Allowance</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">23kg per passenger</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Cancellation</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Available with fee</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Seat Selection</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Available during check-in</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- AI Price Prediction Widget -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 mb-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="trending-up" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">AI Price Prediction</h3>
                            <p class="text-gray-600 text-sm">Based on historical data and market trends</p>
                        </div>
                    </div>
                    
                    <div id="pricePredictionWidget" class="space-y-4">
                        <div class="text-center py-8">
                            <button onclick="loadBookingPricePrediction()" 
                                    class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition-colors">
                                <i data-lucide="zap" class="w-5 h-5 inline mr-2"></i>
                                Get Price Forecast
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Baggage Information -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 mb-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="briefcase" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Baggage Information</h3>
                            <p class="text-gray-600 text-sm">Allowance and additional options</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="border border-gray-200 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-2 bg-blue-100 rounded-full flex items-center justify-center">
                                <i data-lucide="briefcase" class="h-6 w-6 text-blue-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Cabin Baggage</h4>
                            <p class="text-gray-600 text-sm">1 piece, 7kg</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-2 bg-blue-100 rounded-full flex items-center justify-center">
                                <i data-lucide="suitcase" class="h-6 w-6 text-blue-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Checked Baggage</h4>
                            <p class="text-gray-600 text-sm">1 piece, 23kg</p>
                        </div>
                        
                        <div class="border border-gray-200 rounded-xl p-4 text-center">
                            <div class="w-12 h-12 mx-auto mb-2 bg-blue-100 rounded-full flex items-center justify-center">
                                <i data-lucide="plus-circle" class="h-6 w-6 text-blue-600"></i>
                            </div>
                            <h4 class="font-semibold text-gray-800">Extra Baggage</h4>
                            <p class="text-gray-600 text-sm">$50 per additional 23kg</p>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4 mt-6 flex items-start gap-3">
                        <i data-lucide="info" class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-blue-800">
                            Baggage allowance may vary based on route and fare class. The above information is for standard economy class tickets. For precise baggage details, please check your e-ticket after booking or contact the airline directly.
                        </div>
                    </div>
                </div>

                <!-- Smart Auto-Fill Section -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 mb-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="user-plus" class="h-6 w-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Passenger Information</h3>
                            <p class="text-gray-600 text-sm">Smart auto-fill available for faster booking</p>
                        </div>
                    </div>
                    
                    <!-- Smart Auto-Fill Options -->
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-3 mb-4">
                            @auth
                                <button onclick="fillFromProfile()" 
                                        class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                    <i data-lucide="user" class="w-4 h-4"></i>
                                    <span class="text-sm">Use My Profile</span>
                                </button>
                            @endauth
                            
                            <button onclick="fillFromPreviousBooking()" 
                                    class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                                <i data-lucide="history" class="w-4 h-4"></i>
                                <span class="text-sm">Previous Booking</span>
                            </button>
                            
                            <button onclick="openPassportScanner()" 
                                    class="flex items-center gap-2 px-4 py-2 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                                <i data-lucide="camera" class="w-4 h-4"></i>
                                <span class="text-sm">Scan Passport</span>
                            </button>
                            
                            <button onclick="openAIAssistant()" 
                                    class="flex items-center gap-2 px-4 py-2 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition-colors">
                                <i data-lucide="bot" class="w-4 h-4"></i>
                                <span class="text-sm">AI Assistant</span>
                            </button>
                        </div>
                    </div>

                    <!-- Passenger Forms -->
                    <form id="passengerForm" action="{{ route('booking.checkout') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="flight_data" value="{{ json_encode($flight) }}">
                        
                        @for($i = 0; $i < $passengerCount; $i++)
                            <div class="passenger-form border border-gray-200 rounded-xl p-6" data-passenger-index="{{ $i }}">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-semibold text-gray-800">Passenger {{ $i + 1 }}</h4>
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="openPassportScanner()" 
                                                class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                            <i data-lucide="scan-face" class="w-4 h-4"></i>
                                            <span class="text-sm hidden md:inline">Scan Passport</span>
                                        </button>
                                        <button type="button" onclick="clearPassengerForm({{ $i }})" 
                                                class="text-gray-500 hover:text-red-500">
                                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                                        </button>
                                        <button type="button" onclick="autoFillPassenger({{ $i }})" 
                                                class="text-blue-600 hover:text-blue-800">
                                            <i data-lucide="wand-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                                        <select name="passengers[{{ $i }}][title]" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                required>
                                            <option value="">Select Title</option>
                                            <option value="Mr">Mr</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Dr">Dr</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                        <input type="text" name="passengers[{{ $i }}][first_name]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               placeholder="Enter first name" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                        <input type="text" name="passengers[{{ $i }}][last_name]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               placeholder="Enter last name" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                        <input type="date" name="passengers[{{ $i }}][date_of_birth]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                        <select name="passengers[{{ $i }}][gender]" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                required>
                                            <option value="">Select Gender</option>
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                                        <select name="passengers[{{ $i }}][nationality]" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                                required>
                                            <option value="">Select Nationality</option>
                                            <option value="US">United States</option>
                                            <option value="GB">United Kingdom</option>
                                            <option value="CA">Canada</option>
                                            <option value="AU">Australia</option>
                                            <option value="DE">Germany</option>
                                            <option value="FR">France</option>
                                            <option value="KE">Kenya</option>
                                            <option value="NG">Nigeria</option>
                                            <option value="ZA">South Africa</option>
                                            <!-- Add more countries as needed -->
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                                        <input type="text" name="passengers[{{ $i }}][passport_number]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               placeholder="Enter passport number" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Passport Expiry</label>
                                        <input type="date" name="passengers[{{ $i }}][passport_expiry]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                        <input type="email" name="passengers[{{ $i }}][email]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               placeholder="Enter email address" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                        <input type="tel" name="passengers[{{ $i }}][phone]" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                               placeholder="Enter phone number" required>
                                    </div>
                                </div>
                                
                                <!-- AI Suggestions for this passenger -->
                                <div id="aiSuggestions{{ $i }}" class="mt-4 hidden">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i data-lucide="lightbulb" class="w-4 h-4 text-blue-600"></i>
                                            <span class="text-sm font-medium text-blue-800">AI Suggestions</span>
                                        </div>
                                        <div class="text-sm text-blue-700" id="aiSuggestionText{{ $i }}"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                        
                        <!-- Contact Information -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Email</label>
                                    <input type="email" name="contact_email" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="Enter primary email" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="contact_phone" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                           placeholder="Enter phone number" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Travel Preferences -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i data-lucide="settings" class="h-5 w-5 text-blue-600"></i>
                                Travel Preferences
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Meal Preferences -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meal Preference</label>
                                    <select name="meal_preference" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="regular">Regular Meal</option>
                                        <option value="vegetarian">Vegetarian</option>
                                        <option value="vegan">Vegan</option>
                                        <option value="kosher">Kosher</option>
                                        <option value="halal">Halal</option>
                                        <option value="diabetic">Diabetic</option>
                                        <option value="gluten_free">Gluten Free</option>
                                    </select>
                                </div>
                                
                                <!-- Seat Preferences -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Seat Preference</label>
                                    <select name="seat_preference" 
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="no_preference">No Preference</option>
                                        <option value="window">Window</option>
                                        <option value="aisle">Aisle</option>
                                        <option value="middle">Middle</option>
                                        <option value="exit_row">Exit Row</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-6 space-y-3">
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="special_assistance" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Request special assistance</span>
                                </label>
                                
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="travel_insurance" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Add travel insurance (recommended) - $25.00</span>
                                </label>
                                
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="priority_boarding" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Priority boarding - $15.00</span>
                                </label>
                                
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="marketing_consent" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Receive promotional emails</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Terms and Conditions</h4>
                            
                            <div class="bg-gray-50 rounded-lg p-4 mb-4 max-h-40 overflow-y-auto">
                                <p class="text-sm text-gray-600">
                                    By proceeding with this booking, you agree to our Terms of Service, Privacy Policy, and the airline's conditions of carriage. Cancellation policies vary by fare type and airline. Please review all terms before completing your booking.
                                </p>
                                <p class="text-sm text-gray-600 mt-2">
                                    Your personal data will be processed in accordance with our Privacy Policy and shared with relevant travel providers to complete your booking. You have the right to access, modify, or delete your personal information at any time.
                                </p>
                            </div>
                            
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="terms_accepted" required
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">I have read and agree to the Terms and Conditions</span>
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center pt-4">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-blue-700 transition-colors">
                                <i data-lucide="credit-card" class="w-5 h-5 inline mr-2"></i>
                                Continue to Payment
                            </button>
                            <p class="text-sm text-gray-500 mt-2">Your payment details will be securely handled in the next step</p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right Side - Booking Summary -->
            <div class="w-96 flex-shrink-0">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 sticky top-8 booking-summary">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i data-lucide="clipboard-check" class="h-5 w-5 text-blue-600"></i>
                        Booking Summary
                    </h3>
                    
                    <!-- Flight Summary -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Flight</span>
                            <span class="font-semibold">{{ $fromCity }} → {{ $toCity }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Date</span>
                            <span class="font-semibold">{{ date('M j, Y', strtotime($flight->itineraries[0]->segments[0]->departure->at)) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Passengers</span>
                            <span class="font-semibold">{{ $passengerCount }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Class</span>
                            <span class="font-semibold">{{ $formattedClass }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Flight Number</span>
                            <span class="font-semibold">{{ $airlineCode }} {{ $flight->itineraries[0]->segments[0]->number }}</span>
                        </div>
                    </div>
                    
                    <!-- Price Prediction Widget -->
                    <div class="mt-6 mb-6">
                        <div id="pricePredictionWidget" class="border border-purple-200 rounded-xl p-4 bg-gradient-to-br from-purple-50 to-indigo-50">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-purple-800">Price Prediction</h4>
                                <button onclick="loadBookingPricePrediction()" class="text-purple-600 hover:text-purple-800 text-xs font-medium">
                                    <i data-lucide="refresh-cw" class="h-3 w-3 inline"></i> Update
                                </button>
                            </div>
                            <p class="text-sm text-purple-600 mb-2">Click "Update" to analyze current pricing trends</p>
                        </div>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="border-t pt-6">
                        <h4 class="font-semibold text-gray-800 mb-4">Price Breakdown</h4>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Base Price</span>
                                <span class="font-semibold">${{ number_format($flight->price->base * $passengerCount, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Taxes & Fees</span>
                                <span class="font-semibold">${{ number_format(($flight->price->total - $flight->price->base) * $passengerCount, 2) }}</span>
                            </div>

                            <!-- Add potential add-ons -->
                            <div id="insurancePrice" class="flex justify-between items-center hidden">
                                <span class="text-gray-600">Travel Insurance</span>
                                <span class="font-semibold">$25.00</span>
                            </div>
                            
                            <div id="priorityBoardingPrice" class="flex justify-between items-center hidden">
                                <span class="text-gray-600">Priority Boarding</span>
                                <span class="font-semibold">$15.00</span>
                            </div>
                            
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-800">Total</span>
                                    <span class="text-2xl font-bold text-blue-600">${{ number_format($flight->price->total * $passengerCount, 2) }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 text-right">
                                    Price guaranteed for the next 15 minutes
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Promotion Code -->
                    <div class="mt-6 border-t pt-6">
                        <h4 class="font-medium text-gray-700 mb-2">Promotion Code</h4>
                        <div class="flex gap-2">
                            <input type="text" placeholder="Enter code" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
                            <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded-lg">Apply</button>
                        </div>
                    </div>

                    <!-- Payment Security -->
                    <div class="mt-6 bg-blue-50 rounded-lg p-4 flex items-center gap-3">
                        <div class="bg-blue-100 rounded-full p-2">
                            <i data-lucide="shield" class="h-5 w-5 text-blue-600"></i>
                        </div>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium">Secure Payment</p>
                            <p class="text-xs text-blue-700">Your payment information is encrypted and secure</p>
                        </div>
                    </div>
                    
                    <!-- Customer Support -->
                    <div class="mt-6 bg-green-50 rounded-lg p-4 flex items-center gap-3">
                        <div class="bg-green-100 rounded-full p-2">
                            <i data-lucide="headphones" class="h-5 w-5 text-green-600"></i>
                        </div>
                        <div class="text-sm text-green-800">
                            <p class="font-medium">24/7 Customer Support</p>
                            <p class="text-xs text-green-700">We're here to help if you need assistance</p>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6 space-y-3">
                        <button type="button" onclick="document.getElementById('passengerForm').submit()" 
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            Proceed to Checkout
                        </button>
                        
                        <button onclick="window.location.href='{{ route('flight.search.results') }}'" 
                                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Back to Flight Results
                        </button>
                        
                        <button onclick="saveDraft()" 
                                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Save Draft
                        </button>
                        
                        <button onclick="shareBooking()" 
                                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                            Share Details
                        </button>
                    </div>
                </div>
                
                <!-- Recently Viewed -->
                <div class="mt-8 bg-white rounded-3xl shadow-lg border border-gray-200 p-6">
                    <h4 class="font-medium text-gray-800 mb-4 flex items-center gap-2">
                        <i data-lucide="history" class="h-4 w-4 text-gray-600"></i>
                        Recently Viewed
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="border border-gray-100 rounded-xl p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="plane" class="h-5 w-5 text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">NYC → LHR</div>
                                    <div class="text-xs text-gray-500">Aug 25 • Economy</div>
                                </div>
                                <div class="ml-auto text-right">
                                    <div class="font-medium text-blue-600">$450</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border border-gray-100 rounded-xl p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i data-lucide="plane" class="h-5 w-5 text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">JFK → NBO</div>
                                    <div class="text-xs text-gray-500">Sep 10 • Business</div>
                                </div>
                                <div class="ml-auto text-right">
                                    <div class="font-medium text-blue-600">$1,250</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Frequently Asked Questions</h2>
            
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">How can I change or cancel my booking?</h3>
                    <p class="text-gray-600">You can change or cancel your booking through your account dashboard. Depending on the fare type, fees may apply. For assistance, contact our customer support team.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">When will I receive my e-ticket?</h3>
                    <p class="text-gray-600">Your e-ticket will be sent to your email immediately after your payment is confirmed. You can also view and download it from your account dashboard.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">What is the baggage allowance?</h3>
                    <p class="text-gray-600">Baggage allowance varies by airline and fare class. For this flight, you're entitled to 1 cabin bag (7kg) and 1 checked bag (23kg) per passenger. Additional baggage can be purchased.</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Do I need travel insurance?</h3>
                    <p class="text-gray-600">While not mandatory, travel insurance is highly recommended to protect against unexpected events such as flight cancellations, medical emergencies, and lost baggage.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Modals -->
    
    <!-- Passport Scanner Modal -->
    <div id="passportScannerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Scan Passport</h3>
                    <button onclick="closePassportScanner()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="text-center">
                    <div class="aspect-video bg-gray-100 rounded-lg mx-auto mb-4 flex items-center justify-center relative border-2 border-dashed border-blue-300">
                        <div id="passportPreview" class="absolute inset-0 bg-cover bg-center hidden rounded-lg"></div>
                        <div id="passportPlaceholder" class="flex flex-col items-center">
                            <i data-lucide="camera" class="w-16 h-16 text-gray-400 mb-2"></i>
                            <span class="text-sm text-gray-500">Camera feed will appear here</span>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">Take a photo of your passport to auto-fill passenger information</p>
                    <div class="flex gap-2 justify-center mb-4">
                        <button id="startScanButton" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i data-lucide="video" class="w-4 h-4 inline mr-1"></i> 
                            Start Camera
                        </button>
                        <button id="captureScanButton" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 hidden">
                            <i data-lucide="camera" class="w-4 h-4 inline mr-1"></i> 
                            Capture
                        </button>
                    </div>
                    <div id="scanningStatus" class="hidden">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                            <div id="scanProgress" class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>
                        </div>
                        <p class="text-sm text-gray-600" id="scanStatusText">Preparing scanner...</p>
                    </div>
                    
                    <div class="space-y-3">
                        <input type="file" id="passportFile" accept="image/*" capture="environment" class="hidden">
                        <button onclick="document.getElementById('passportFile').click()" 
                                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            <i data-lucide="camera" class="w-4 h-4 inline mr-2"></i>
                            Take Photo
                        </button>
                        <button onclick="document.getElementById('passportFile').click()" 
                                class="w-full bg-gray-600 text-white py-3 rounded-lg hover:bg-gray-700 transition-colors">
                            <i data-lucide="upload" class="w-4 h-4 inline mr-2"></i>
                            Upload Image
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- AI Assistant Modal -->
    <div id="aiAssistantModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">AI Booking Assistant</h3>
                    <button onclick="closeAIAssistant()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="chat-container" style="height: 400px; overflow-y: auto;" id="aiAssistantChat">
                    <div class="chat-message mb-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i data-lucide="bot" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-3 max-w-sm">
                                <p class="text-sm">Hi! I can help you fill out passenger information quickly. What would you like me to help you with?</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <form id="aiAssistantForm" class="flex gap-2">
                        <input type="text" id="aiAssistantInput" placeholder="Ask me anything..." 
                               class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Share Flight Details</h3>
                    <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">Share this flight with friends or family via:</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <button class="flex items-center justify-center gap-2 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i data-lucide="mail" class="h-5 w-5"></i>
                            Email
                        </button>
                        
                        <button class="flex items-center justify-center gap-2 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i data-lucide="message-square" class="h-5 w-5"></i>
                            SMS
                        </button>
                        
                        <button class="flex items-center justify-center gap-2 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            <i data-lucide="facebook" class="h-5 w-5"></i>
                            Facebook
                        </button>
                        
                        <button class="flex items-center justify-center gap-2 py-3 bg-sky-500 text-white rounded-lg hover:bg-sky-600">
                            <i data-lucide="twitter" class="h-5 w-5"></i>
                            Twitter
                        </button>
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <p class="text-gray-600 mb-3">Or copy link to share:</p>
                    <div class="flex gap-2">
                        <input type="text" value="https://travel-booking.com/flights/{{ $flight->id }}" readonly 
                               class="flex-1 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-600">
                        <button onclick="copyShareLink()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors">
                            <i data-lucide="copy" class="h-5 w-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 space-y-4">
        <button onclick="openAIAssistant()" class="w-14 h-14 bg-purple-600 hover:bg-purple-700 text-white rounded-full shadow-lg flex items-center justify-center transition-transform hover:scale-110">
            <i data-lucide="bot" class="h-6 w-6"></i>
        </button>
    </div>
@endsection

@section('scripts')
<script>
    // Modal Functions
    function openPassportScanner() {
        document.getElementById('passportScannerModal').classList.remove('hidden');
        document.getElementById('passportScannerModal').classList.add('flex');
    }
    
    function closePassportScanner() {
        document.getElementById('passportScannerModal').classList.remove('flex');
        document.getElementById('passportScannerModal').classList.add('hidden');
        
        // Reset scanner state
        const startButton = document.getElementById('startScanButton');
        const captureButton = document.getElementById('captureScanButton');
        const scanningStatus = document.getElementById('scanningStatus');
        const passportPlaceholder = document.getElementById('passportPlaceholder');
        const passportPreview = document.getElementById('passportPreview');
        
        startButton.classList.remove('hidden');
        captureButton.classList.add('hidden');
        scanningStatus.classList.add('hidden');
        passportPlaceholder.classList.remove('hidden');
        passportPreview.classList.add('hidden');
        
        // Stop camera if it's running
        if (window.passportScannerStream) {
            window.passportScannerStream.getTracks().forEach(track => track.stop());
            window.passportScannerStream = null;
        }
    }
    
    // Set up passport scanner when document is loaded
    document.addEventListener('DOMContentLoaded', function() {
        const startScanButton = document.getElementById('startScanButton');
        const captureScanButton = document.getElementById('captureScanButton');
        const scanningStatus = document.getElementById('scanningStatus');
        const scanProgress = document.getElementById('scanProgress');
        const scanStatusText = document.getElementById('scanStatusText');
        const passportPreview = document.getElementById('passportPreview');
        const passportPlaceholder = document.getElementById('passportPlaceholder');
        
        if (startScanButton) {
            startScanButton.addEventListener('click', function() {
                // Check if browser supports getUserMedia
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Your browser does not support camera access. Please use a modern browser or enter details manually.');
                    return;
                }
                
                // Start camera
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
                    .then(function(stream) {
                        // Store stream for later cleanup
                        window.passportScannerStream = stream;
                        
                        // Create video element to show camera feed
                        const video = document.createElement('video');
                        video.srcObject = stream;
                        video.autoplay = true;
                        video.classList.add('absolute', 'inset-0', 'w-full', 'h-full', 'object-cover', 'rounded-lg');
                        
                        // Clear placeholder and add video
                        passportPlaceholder.classList.add('hidden');
                        const container = passportPlaceholder.parentElement;
                        container.appendChild(video);
                        
                        // Show capture button and hide start button
                        startScanButton.classList.add('hidden');
                        captureScanButton.classList.remove('hidden');
                        
                        // Handle capture button
                        captureScanButton.onclick = function() {
                            // Show scanning status
                            scanningStatus.classList.remove('hidden');
                            
                            // Simulate scanning progress
                            let progress = 0;
                            const interval = setInterval(function() {
                                progress += 5;
                                scanProgress.style.width = progress + '%';
                                
                                if (progress < 30) {
                                    scanStatusText.textContent = 'Reading MRZ data...';
                                } else if (progress < 60) {
                                    scanStatusText.textContent = 'Extracting personal information...';
                                } else if (progress < 90) {
                                    scanStatusText.textContent = 'Verifying data...';
                                } else {
                                    scanStatusText.textContent = 'Complete!';
                                }
                                
                                if (progress >= 100) {
                                    clearInterval(interval);
                                    
                                    // Remove video and show preview (would be a snapshot in real implementation)
                                    container.removeChild(video);
                                    passportPreview.style.backgroundImage = 'url("https://via.placeholder.com/400x300/4A7FB0/FFFFFF?text=Passport+Scanned")';
                                    passportPreview.classList.remove('hidden');
                                    
                                    // Stop all tracks
                                    stream.getTracks().forEach(track => track.stop());
                                    
                                    // Auto-fill form after short delay
                                    setTimeout(function() {
                                        autoFillFromPassport();
                                        closePassportScanner();
                                    }, 1000);
                                }
                            }, 50);
                        };
                    })
                    .catch(function(err) {
                        console.error('Error accessing camera: ', err);
                        alert('Could not access camera. Please check permissions or enter details manually.');
                    });
            });
        }
    });
    
    // Function to simulate auto-filling form from passport scan
    function autoFillFromPassport() {
        // This would come from actual OCR in production
        const passportData = {
            title: 'MR',
            firstName: 'JOHN',
            lastName: 'SMITH',
            dateOfBirth: '1985-07-15',
            gender: 'MALE',
            nationality: 'US',
            passportNumber: 'A1234567',
            expiryDate: '2028-05-20'
        };
        
        // Get the first passenger form (would target specific one in production)
        const firstNameInput = document.querySelector('input[name^="passengers"][name$="[first_name]"]');
        const lastNameInput = document.querySelector('input[name^="passengers"][name$="[last_name]"]');
        const dobInput = document.querySelector('input[name^="passengers"][name$="[date_of_birth]"]');
        const genderSelect = document.querySelector('select[name^="passengers"][name$="[gender]"]');
        const nationalitySelect = document.querySelector('select[name^="passengers"][name$="[nationality]"]');
        const titleSelect = document.querySelector('select[name^="passengers"][name$="[title]"]');
        
        // Fill in the form
        if (firstNameInput) firstNameInput.value = passportData.firstName;
        if (lastNameInput) lastNameInput.value = passportData.lastName;
        if (dobInput) dobInput.value = passportData.dateOfBirth;
        if (genderSelect) genderSelect.value = passportData.gender;
        if (nationalitySelect) nationalitySelect.value = passportData.nationality;
        if (titleSelect) titleSelect.value = passportData.title;
        
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-up';
        toast.innerHTML = '<i data-lucide="check-circle" class="h-5 w-5"></i> Passport data successfully imported!';
        document.body.appendChild(toast);
        
        // Initialize Lucide icons in the toast
        lucide.createIcons();
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('animate-fade-out-down');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    function openAIAssistant() {
        document.getElementById('aiAssistantModal').classList.remove('hidden');
        document.getElementById('aiAssistantModal').classList.add('flex');
    }
    
    function closeAIAssistant() {
        document.getElementById('aiAssistantModal').classList.remove('flex');
        document.getElementById('aiAssistantModal').classList.add('hidden');
    }
    
    function shareBooking() {
        document.getElementById('shareModal').classList.remove('hidden');
        document.getElementById('shareModal').classList.add('flex');
    }
    
    function closeShareModal() {
        document.getElementById('shareModal').classList.remove('flex');
        document.getElementById('shareModal').classList.add('hidden');
    }
    
    function copyShareLink() {
        const copyText = document.querySelector('#shareModal input[readonly]');
        copyText.select();
        document.execCommand('copy');
        
        // Show feedback
        const button = document.querySelector('#shareModal button:last-child');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i data-lucide="check" class="h-5 w-5"></i>';
        setTimeout(() => {
            button.innerHTML = originalHTML;
            lucide.createIcons(); // Re-init icons
        }, 2000);
    }
    
    function saveDraft() {
        // Show feedback
        const button = document.querySelector('button[onclick="saveDraft()"]');
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i data-lucide="check" class="h-5 w-5"></i> Saved';
        
        // In production, this would send an AJAX request to save the draft
        // For demo, we just show a success message
        setTimeout(() => {
            button.innerHTML = originalHTML;
            lucide.createIcons(); // Re-init icons
            alert('Your booking has been saved as a draft. You can access it from your account dashboard.');
        }, 1500);
    }
    
    function loadBookingPricePrediction() {
        const predictionWidget = document.getElementById('pricePredictionWidget');
        predictionWidget.innerHTML = '<div class="text-center py-4"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-700"></div><p class="mt-2 text-gray-600">Analyzing pricing data...</p></div>';
        
        // Simulate loading prediction (in production, this would be an API call)
        setTimeout(() => {
            const priceData = {
                current: {{ $flight->price->total }},
                trend: 'rising', // or 'falling', 'stable'
                recommendation: 'Book now, prices are likely to increase by 12% in the next 3 days.',
                priceHistory: [320, 350, 380, 370, 390, 410, {{ $flight->price->total }}]
            };
            
            renderPricePrediction(predictionWidget, priceData);
        }, 1500);
    }
    
    function renderPricePrediction(element, data) {
        const trendIcon = data.trend === 'rising' ? 
            '<i data-lucide="trending-up" class="h-5 w-5 text-red-500"></i>' : 
            (data.trend === 'falling' ? 
                '<i data-lucide="trending-down" class="h-5 w-5 text-green-500"></i>' : 
                '<i data-lucide="minus" class="h-5 w-5 text-yellow-500"></i>');
        
        element.innerHTML = `
            <div class="border border-purple-200 rounded-xl p-4 bg-gradient-to-br from-purple-50 to-indigo-50">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-purple-800">Price Prediction</h4>
                    <div class="flex items-center gap-1">
                        ${trendIcon}
                        <span class="text-sm font-medium ${data.trend === 'rising' ? 'text-red-600' : (data.trend === 'falling' ? 'text-green-600' : 'text-yellow-600')}">
                            ${data.trend.toUpperCase()}
                        </span>
                    </div>
                </div>
                
                <div class="text-center mb-6">
                    <div class="text-2xl font-bold text-purple-700">$${data.current}</div>
                    <div class="text-purple-600 font-medium">${data.recommendation}</div>
                </div>
                
                <div class="border-t border-purple-200 pt-4">
                    <h5 class="text-sm font-medium text-purple-700 mb-2">7-Day Price Trend</h5>
                    <div class="h-16 relative">
                        <!-- Simple chart would go here - for demo we're using a div -->
                        <div class="flex items-end justify-between h-full w-full">
                            ${data.priceHistory.map(price => {
                                const height = (price / Math.max(...data.priceHistory)) * 100;
                                const colorClass = price === data.current ? 'bg-purple-600' : 'bg-purple-300';
                                return `<div class="${colorClass} w-8 rounded-t" style="height: ${height}%"></div>`;
                            }).join('')}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Re-initialize Lucide icons
        lucide.createIcons();
    }
    
    // AI Assistant
    document.addEventListener('DOMContentLoaded', function() {
        const aiAssistantForm = document.getElementById('aiAssistantForm');
        const aiAssistantInput = document.getElementById('aiAssistantInput');
        const aiAssistantChat = document.getElementById('aiAssistantChat');
        
        if (aiAssistantForm) {
            aiAssistantForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const userMessage = aiAssistantInput.value.trim();
                if (!userMessage) return;
                
                // Add user message to chat
                aiAssistantChat.innerHTML += `
                    <div class="chat-message mb-4">
                        <div class="flex items-start gap-3 justify-end">
                            <div class="bg-blue-600 rounded-2xl rounded-tr-none p-4 max-w-[80%]">
                                <p class="text-white">${userMessage}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="user" class="h-5 w-5 text-gray-600"></i>
                            </div>
                        </div>
                    </div>
                `;
                
                // Clear input
                aiAssistantInput.value = '';
                
                // Auto scroll to bottom
                aiAssistantChat.scrollTop = aiAssistantChat.scrollHeight;
                
                // Re-initialize Lucide icons
                lucide.createIcons();
                
                // Simulate AI response (in production, this would be an API call)
                setTimeout(() => {
                    // Add AI message to chat
                    aiAssistantChat.innerHTML += `
                        <div class="chat-message mb-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="bot" class="h-5 w-5 text-white"></i>
                                </div>
                                <div class="bg-blue-50 rounded-2xl rounded-tl-none p-4 max-w-[80%]">
                                    <p class="text-gray-800">${getAIResponse(userMessage)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Auto scroll to bottom
                    aiAssistantChat.scrollTop = aiAssistantChat.scrollHeight;
                    
                    // Re-initialize Lucide icons
                    lucide.createIcons();
                }, 1000);
            });
        }
    });
    
    function getAIResponse(userMessage) {
        // Simple AI responses - in production, this would be a proper AI model
        const responses = [
            "That's a great question about your flight. Based on your booking details, your baggage allowance is 1 cabin bag (7kg) and 1 checked bag (23kg) per passenger.",
            "The cancellation policy for this fare allows changes with a fee. Would you like me to provide more details about the specific fees?",
            "You can add special meal requirements during the booking process in the 'Travel Preferences' section, or you can contact the airline directly after booking.",
            "For this flight, online check-in opens 24 hours before departure. You'll receive an email reminder when check-in is available.",
            "If you need to add an extra passenger to your booking, you'll need to complete this booking first, then make a separate booking for the additional passenger.",
            "Based on historical data, I recommend booking this flight soon. Prices for this route tend to increase as the departure date approaches."
        ];
        
        // Return a random response
        return responses[Math.floor(Math.random() * responses.length)];
    }
    
    // Function to auto-fill passenger details with AI suggestions
    function autoFillPassenger(index) {
        const passengerForm = document.querySelector(`[data-passenger-index="${index}"]`);
        if (!passengerForm) return;
        
        // Get form fields for this passenger
        const titleSelect = passengerForm.querySelector(`select[name="passengers[${index}][title]"]`);
        const firstNameInput = passengerForm.querySelector(`input[name="passengers[${index}][first_name]"]`);
        const lastNameInput = passengerForm.querySelector(`input[name="passengers[${index}][last_name]"]`);
        const dobInput = passengerForm.querySelector(`input[name="passengers[${index}][date_of_birth]"]`);
        const genderSelect = passengerForm.querySelector(`select[name="passengers[${index}][gender]"]`);
        const nationalitySelect = passengerForm.querySelector(`select[name="passengers[${index}][nationality]"]`);
        
        // Create a loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-xl';
        loadingIndicator.innerHTML = `
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-700"></div>
                <p class="mt-2 text-sm text-blue-700 font-medium">AI generating passenger profile...</p>
            </div>
        `;
        passengerForm.style.position = 'relative';
        passengerForm.appendChild(loadingIndicator);
        
        // Simulate AI generating passenger details
        setTimeout(() => {
            // Get names appropriate for the region/route
            const names = [
                { title: 'MR', firstName: 'David', lastName: 'Wilson', gender: 'MALE' },
                { title: 'MRS', firstName: 'Sarah', lastName: 'Johnson', gender: 'FEMALE' },
                { title: 'MS', firstName: 'Emily', lastName: 'Brown', gender: 'FEMALE' },
                { title: 'MR', firstName: 'Michael', lastName: 'Davis', gender: 'MALE' },
                { title: 'DR', firstName: 'Robert', lastName: 'Taylor', gender: 'MALE' }
            ];
            
            // Choose a random name
            const nameData = names[Math.floor(Math.random() * names.length)];
            
            // Generate a plausible birthdate (25-65 years old)
            const today = new Date();
            const age = Math.floor(Math.random() * 40) + 25;
            const birthYear = today.getFullYear() - age;
            const birthMonth = Math.floor(Math.random() * 12) + 1;
            const birthDay = Math.floor(Math.random() * 28) + 1;
            const dob = `${birthYear}-${birthMonth.toString().padStart(2, '0')}-${birthDay.toString().padStart(2, '0')}`;
            
            // Common nationalities
            const nationalities = ['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'KE'];
            const nationality = nationalities[Math.floor(Math.random() * nationalities.length)];
            
            // Fill in the form with AI-generated data
            if (titleSelect) titleSelect.value = nameData.title;
            if (firstNameInput) firstNameInput.value = nameData.firstName;
            if (lastNameInput) lastNameInput.value = nameData.lastName;
            if (dobInput) dobInput.value = dob;
            if (genderSelect) genderSelect.value = nameData.gender;
            if (nationalitySelect) nationalitySelect.value = nationality;
            
            // Remove loading indicator
            passengerForm.removeChild(loadingIndicator);
            
            // Show a success notification
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-up';
            toast.innerHTML = '<i data-lucide="sparkles" class="h-5 w-5"></i> AI-generated profile applied!';
            document.body.appendChild(toast);
            
            // Initialize Lucide icons in the toast
            lucide.createIcons();
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.classList.add('animate-fade-out-down');
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 3000);
        }, 1500);
    }
    
    // Function to clear a passenger form
    function clearPassengerForm(index) {
        const passengerForm = document.querySelector(`[data-passenger-index="${index}"]`);
        if (!passengerForm) return;
        
        // Get all inputs and selects in the form
        const inputs = passengerForm.querySelectorAll('input, select');
        
        // Reset each field
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else {
                input.value = '';
            }
        });
        
        // Show a notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-gray-700 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in-up';
        toast.innerHTML = '<i data-lucide="trash-2" class="h-5 w-5"></i> Passenger details cleared';
        document.body.appendChild(toast);
        
        // Initialize Lucide icons in the toast
        lucide.createIcons();
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('animate-fade-out-down');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Guest Registration Modal
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($isGuest) && $isGuest)
            // Show registration modal for guests
            setTimeout(() => {
                document.getElementById('registrationModal').classList.remove('hidden');
                document.getElementById('registrationModal').classList.add('flex');
            }, 1000);
        @endif
    });
    
    function closeRegistrationModal() {
        document.getElementById('registrationModal').classList.add('hidden');
        document.getElementById('registrationModal').classList.remove('flex');
    }
</script>

<!-- Guest Registration Modal -->
@if(isset($isGuest) && $isGuest)
<div id="registrationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Create Account to Continue</h3>
                <button onclick="closeRegistrationModal()" class="text-gray-400 hover:text-gray-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <p class="text-gray-600 mb-6">
                Create an account to complete your booking and manage your trips.
            </p>
            
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Hidden field for return URL -->
                <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                
                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="text" id="name" name="name" required
                            class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter your full name">
                    </div>
                </div>
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Enter your email">
                    </div>
                </div>
                
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Create a password">
                    </div>
                </div>
                
                <!-- Confirm Password Input -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i data-lucide="check-circle" class="w-5 h-5 text-gray-400"></i>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Confirm your password">
                    </div>
                </div>
                
                <!-- Register Button -->
                <button type="submit"
                    class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition duration-300 shadow-md hover:shadow-lg">
                    Create Account & Continue
                </button>
                
                <!-- Login Link -->
                <p class="text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="text-primary-600 hover:text-primary-700 font-medium">Sign In</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endif
@endsection