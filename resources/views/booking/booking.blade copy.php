@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-700 to-indigo-800 py-16">
        <div class="container mx-auto px-4">
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
                @endphp
                <div class="flex items-center mb-4">
                    <a href="javascript:history.back()" class="text-white hover:text-blue-200 mr-4">
                        <i data-lucide="arrow-left" class="h-5 w-5"></i>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-8">
            <!-- Left Side - Flight Details -->
            <div class="flex-1">
                <!-- Flight Information Card -->
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Flight Details</h2>
                    
                    @foreach($flight->itineraries as $itineraryIndex => $itinerary)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">
                                {{ $itineraryIndex == 0 ? 'Outbound' : 'Return' }} Flight
                            </h3>
                            
                            @foreach($itinerary->segments as $segment)
                                <div class="border-l-4 border-blue-500 pl-6 mb-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-semibold text-gray-800">
                                                {{ $segment->departure->iataCode }} → {{ $segment->arrival->iataCode }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ date('M j, Y', strtotime($segment->departure->at)) }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ date('H:i', strtotime($segment->departure->at)) }} - 
                                                {{ date('H:i', strtotime($segment->arrival->at)) }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-semibold">{{ $segment->carrierCode }} {{ $segment->number }}</div>
                                            <div class="text-sm text-gray-600">{{ $segment->aircraft->code ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
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
                    <form id="passengerForm" class="space-y-6">
                        @csrf
                        <input type="hidden" name="flight_data" value="{{ json_encode($flight) }}">
                        
                        @for($i = 0; $i < $passengerCount; $i++)
                            <div class="passenger-form border border-gray-200 rounded-xl p-6" data-passenger-index="{{ $i }}">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-semibold text-gray-800">Passenger {{ $i + 1 }}</h4>
                                    <div class="flex items-center gap-2">
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
                        
                        <!-- Additional Options -->
                        <div class="border border-gray-200 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Additional Options</h4>
                            
                            <div class="space-y-3">
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="meal_preference" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Special meal requirements</span>
                                </label>
                                
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="seat_preference" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Seat selection</span>
                                </label>
                                
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="travel_insurance" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Travel insurance</span>
                                </label>
                                
                                <label class="flex items-center gap-3">
                                    <input type="checkbox" name="marketing_consent" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700">Receive promotional emails</span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-blue-700 transition-colors">
                                <i data-lucide="credit-card" class="w-5 h-5 inline mr-2"></i>
                                Continue to Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Right Side - Booking Summary -->
            <div class="w-96 flex-shrink-0">
                <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 sticky top-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">Booking Summary</h3>
                    
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
                            
                            <div class="border-t pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-800">Total</span>
                                    <span class="text-2xl font-bold text-blue-600">${{ number_format($flight->price->total * $passengerCount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-6 space-y-3">
                        <button onclick="saveDraft()" 
                                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                            <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                            Save Draft
                        </button>
                        
                        <button onclick="shareBooking()" 
                                class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg hover:bg-gray-200 transition-colors">
                            <i data-lucide="share-2" class="w-4 h-4 inline mr-2"></i>
                            Share Details
                        </button>
                    </div>
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
                    <div class="w-32 h-32 bg-gray-100 rounded-lg mx-auto mb-4 flex items-center justify-center">
                        <i data-lucide="camera" class="w-16 h-16 text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 mb-4">Take a photo of your passport to auto-fill passenger information</p>
                    
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

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // Smart Auto-Fill Functions
        function fillFromProfile() {
            @auth
                fetch('/api/user/profile')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const profile = data.profile;
                            const firstPassenger = document.querySelector('.passenger-form');
                            
                            if (firstPassenger) {
                                firstPassenger.querySelector('select[name*="[title]"]').value = profile.title || 'Mr';
                                firstPassenger.querySelector('input[name*="[first_name]"]').value = profile.first_name || '';
                                firstPassenger.querySelector('input[name*="[last_name]"]').value = profile.last_name || '';
                                firstPassenger.querySelector('input[name*="[date_of_birth]"]').value = profile.date_of_birth || '';
                                firstPassenger.querySelector('select[name*="[gender]"]').value = profile.gender || '';
                                firstPassenger.querySelector('input[name*="[email]"]').value = profile.email || '';
                                firstPassenger.querySelector('input[name*="[phone]"]').value = profile.phone || '';
                                
                                // Show success message
                                showNotification('Profile information loaded successfully!', 'success');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading profile:', error);
                        showNotification('Error loading profile information', 'error');
                    });
            @else
                showNotification('Please log in to use profile auto-fill', 'error');
            @endauth
        }

        function fillFromPreviousBooking() {
            fetch('/api/user/previous-booking')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.booking) {
                        const booking = data.booking;
                        const passengers = booking.passengers;
                        
                        passengers.forEach((passenger, index) => {
                            const passengerForm = document.querySelector(`[data-passenger-index="${index}"]`);
                            if (passengerForm) {
                                passengerForm.querySelector('select[name*="[title]"]').value = passenger.title || '';
                                passengerForm.querySelector('input[name*="[first_name]"]').value = passenger.first_name || '';
                                passengerForm.querySelector('input[name*="[last_name]"]').value = passenger.last_name || '';
                                passengerForm.querySelector('input[name*="[date_of_birth]"]').value = passenger.date_of_birth || '';
                                passengerForm.querySelector('select[name*="[gender]"]').value = passenger.gender || '';
                                passengerForm.querySelector('input[name*="[email]"]').value = passenger.email || '';
                                passengerForm.querySelector('input[name*="[phone]"]').value = passenger.phone || '';
                            }
                        });
                        
                        showNotification('Previous booking information loaded!', 'success');
                    } else {
                        showNotification('No previous booking found', 'info');
                    }
                })
                .catch(error => {
                    console.error('Error loading previous booking:', error);
                    showNotification('Error loading previous booking', 'error');
                });
        }

        function openPassportScanner() {
            document.getElementById('passportScannerModal').classList.remove('hidden');
            document.getElementById('passportScannerModal').classList.add('flex');
        }

        function closePassportScanner() {
            document.getElementById('passportScannerModal').classList.add('hidden');
            document.getElementById('passportScannerModal').classList.remove('flex');
        }

        function openAIAssistant() {
            document.getElementById('aiAssistantModal').classList.remove('hidden');
            document.getElementById('aiAssistantModal').classList.add('flex');
        }

        function closeAIAssistant() {
            document.getElementById('aiAssistantModal').classList.add('hidden');
            document.getElementById('aiAssistantModal').classList.remove('flex');
        }

        function autoFillPassenger(index) {
            const passengerForm = document.querySelector(`[data-passenger-index="${index}"]`);
            const aiSuggestions = document.getElementById(`aiSuggestions${index}`);
            
            // Show AI suggestions
            aiSuggestions.classList.remove('hidden');
            document.getElementById(`aiSuggestionText${index}`).textContent = 
                'AI analyzing passenger information patterns...';
            
            // Simulate AI auto-fill logic
            setTimeout(() => {
                // In a real implementation, this would use ML models
                const mockData = {
                    title: 'Mr',
                    first_name: 'John',
                    last_name: 'Doe',
                    gender: 'MALE',
                    nationality: 'US'
                };
                
                Object.entries(mockData).forEach(([field, value]) => {
                    const input = passengerForm.querySelector(`[name*="[${field}]"]`);
                    if (input) {
                        input.value = value;
                    }
                });
                
                document.getElementById(`aiSuggestionText${index}`).textContent = 
                    'AI has suggested common values. Please verify and update as needed.';
            }, 2000);
        }

        function clearPassengerForm(index) {
            const passengerForm = document.querySelector(`[data-passenger-index="${index}"]`);
            const inputs = passengerForm.querySelectorAll('input, select');
            
            inputs.forEach(input => {
                input.value = '';
            });
            
            // Hide AI suggestions
            document.getElementById(`aiSuggestions${index}`).classList.add('hidden');
        }

        // Price Prediction Functions
        function loadBookingPricePrediction() {
            const flightData = @json($flight);
            
            document.getElementById('pricePredictionWidget').innerHTML = `
                <div class="text-center py-6">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Analyzing price trends for this route...</p>
                </div>
            `;
            
            const data = {
                from: '{{ $fromCity }}',
                to: '{{ $toCity }}',
                date: '{{ date('Y-m-d', strtotime($flight->itineraries[0]->segments[0]->departure->at)) }}',
                current_price: {{ $flight->price->total }}
            };
            
            fetch('/api/ai/price-prediction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const prediction = result.prediction;
                    document.getElementById('pricePredictionWidget').innerHTML = `
                        <div class="space-y-4">
                            <!-- Current Price Status -->
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <div class="text-sm font-medium text-gray-500 mb-1">Current Price</div>
                                <div class="text-2xl font-bold text-gray-800">$${prediction.current_price}</div>
                                <div class="inline-block px-3 py-1 rounded-full text-xs font-bold mt-2 ${
                                    prediction.urgency_level === 'high' ? 'bg-red-100 text-red-800' :
                                    prediction.urgency_level === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-green-100 text-green-800'
                                }">
                                    ${prediction.urgency_level.toUpperCase()} URGENCY
                                </div>
                            </div>
                            
                            <!-- Price Forecast -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <div class="text-sm text-blue-600 mb-1">Next Week</div>
                                    <div class="text-lg font-bold text-blue-800">$${prediction.predicted_prices.next_week}</div>
                                    <div class="text-xs ${prediction.price_changes.next_week >= 0 ? 'text-red-600' : 'text-green-600'}">
                                        ${prediction.price_changes.next_week >= 0 ? '+' : ''}${prediction.price_changes.next_week}%
                                    </div>
                                </div>
                                
                                <div class="bg-purple-50 rounded-lg p-4 text-center">
                                    <div class="text-sm text-purple-600 mb-1">Next Month</div>
                                    <div class="text-lg font-bold text-purple-800">$${prediction.predicted_prices.next_month}</div>
                                    <div class="text-xs ${prediction.price_changes.next_month >= 0 ? 'text-red-600' : 'text-green-600'}">
                                        ${prediction.price_changes.next_month >= 0 ? '+' : ''}${prediction.price_changes.next_month}%
                                    </div>
                                </div>
                            </div>
                            
                            <!-- AI Recommendation -->
                            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg p-4 border border-purple-200">
                                <div class="flex items-center gap-2 mb-2">
                                    <i data-lucide="brain" class="w-5 h-5 text-purple-600"></i>
                                    <span class="font-medium text-purple-800">AI Recommendation</span>
                                </div>
                                <div class="text-sm text-purple-700 mb-2">${prediction.recommendation.action}</div>
                                <div class="text-xs text-purple-600">${prediction.recommendation.reason}</div>
                            </div>
                            
                            ${prediction.savings_potential > 0 ? `
                                <div class="bg-green-50 rounded-lg p-4 text-center border border-green-200">
                                    <div class="text-sm text-green-600 mb-1">Potential Savings</div>
                                    <div class="text-xl font-bold text-green-700">$${prediction.savings_potential}</div>
                                    <div class="text-xs text-green-600">if you wait for optimal booking time</div>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    lucide.createIcons();
                } else {
                    document.getElementById('pricePredictionWidget').innerHTML = `
                        <div class="text-center py-6">
                            <i data-lucide="alert-circle" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-gray-600">Unable to load price prediction</p>
                        </div>
                    `;
                    lucide.createIcons();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('pricePredictionWidget').innerHTML = `
                    <div class="text-center py-6">
                        <i data-lucide="wifi-off" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                        <p class="text-gray-600">Network error</p>
                    </div>
                `;
                lucide.createIcons();
            });
        }

        // Passport Scanner Handler
        document.getElementById('passportFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('passport_image', file);
                
                // Show loading
                showNotification('Processing passport image...', 'info');
                
                fetch('/api/ai/scan-passport', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const extractedData = data.extracted_data;
                        const firstPassenger = document.querySelector('.passenger-form');
                        
                        if (firstPassenger) {
                            firstPassenger.querySelector('input[name*="[first_name]"]').value = extractedData.first_name || '';
                            firstPassenger.querySelector('input[name*="[last_name]"]').value = extractedData.last_name || '';
                            firstPassenger.querySelector('input[name*="[date_of_birth]"]').value = extractedData.date_of_birth || '';
                            firstPassenger.querySelector('select[name*="[gender]"]').value = extractedData.gender || '';
                            firstPassenger.querySelector('input[name*="[passport_number]"]').value = extractedData.passport_number || '';
                            firstPassenger.querySelector('input[name*="[passport_expiry]"]').value = extractedData.passport_expiry || '';
                            firstPassenger.querySelector('select[name*="[nationality]"]').value = extractedData.nationality || '';
                        }
                        
                        closePassportScanner();
                        showNotification('Passport information extracted successfully!', 'success');
                    } else {
                        showNotification('Error extracting passport information', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error processing passport image', 'error');
                });
            }
        });

        // AI Assistant Handler
        document.getElementById('aiAssistantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const input = document.getElementById('aiAssistantInput');
            const message = input.value.trim();
            
            if (message) {
                addAIAssistantMessage(message, 'user');
                input.value = '';
                
                fetch('/api/ai/booking-assistant', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addAIAssistantMessage(data.response, 'assistant');
                        
                        // If AI provides form suggestions, apply them
                        if (data.form_suggestions) {
                            applyFormSuggestions(data.form_suggestions);
                        }
                    } else {
                        addAIAssistantMessage('Sorry, I encountered an error. Please try again.', 'assistant');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    addAIAssistantMessage('Sorry, I\'m having trouble connecting right now.', 'assistant');
                });
            }
        });

        function addAIAssistantMessage(message, sender) {
            const chatContainer = document.getElementById('aiAssistantChat');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message mb-4';
            
            if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="flex items-start gap-3 justify-end">
                        <div class="bg-blue-600 text-white rounded-lg p-3 max-w-sm">
                            <p class="text-sm">${message}</p>
                        </div>
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-white"></i>
                        </div>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i data-lucide="bot" class="w-4 h-4 text-blue-600"></i>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-3 max-w-sm">
                            <p class="text-sm">${message}</p>
                        </div>
                    </div>
                `;
            }
            
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
            lucide.createIcons();
        }

        function applyFormSuggestions(suggestions) {
            // Apply AI suggestions to form fields
            Object.entries(suggestions).forEach(([field, value]) => {
                const input = document.querySelector(`[name*="[${field}]"]`);
                if (input) {
                    input.value = value;
                }
            });
        }

        // Utility Functions
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                type === 'info' ? 'bg-blue-500 text-white' :
                'bg-gray-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function saveDraft() {
            const formData = new FormData(document.getElementById('passengerForm'));
            const data = Object.fromEntries(formData);
            
            fetch('/api/booking/save-draft', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Draft saved successfully!', 'success');
                } else {
                    showNotification('Error saving draft', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error saving draft', 'error');
            });
        }

        function shareBooking() {
            const bookingDetails = {
                flight: '{{ $fromCity }} → {{ $toCity }}',
                date: '{{ date('M j, Y', strtotime($flight->itineraries[0]->segments[0]->departure->at)) }}',
                passengers: {{ $passengerCount }},
                price: '{{ $flight->price->total * $passengerCount }}'
            };
            
            if (navigator.share) {
                navigator.share({
                    title: 'Flight Booking Details',
                    text: `Flight: ${bookingDetails.flight} on ${bookingDetails.date}`,
                    url: window.location.href
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const shareText = `Flight: ${bookingDetails.flight} on ${bookingDetails.date} - $${bookingDetails.price}`;
                navigator.clipboard.writeText(shareText).then(() => {
                    showNotification('Booking details copied to clipboard!', 'success');
                });
            }
        }

        // Form Validation
        document.getElementById('passengerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all required fields
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (isValid) {
                // Submit the form
                this.submit();
            } else {
                showNotification('Please fill in all required fields', 'error');
            }
        });
    </script>
@endsection