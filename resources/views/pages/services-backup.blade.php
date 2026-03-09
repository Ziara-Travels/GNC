@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-[60vh] flex items-center justify-center overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        
        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    Our <span class="text-gradient bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Services</span>
                </h1>
                <p class="text-xl md:text-2xl text-slate-300 mb-8 max-w-2xl mx-auto">
                    Comprehensive travel solutions for every journey
                </p>
                <div class="flex justify-center">
                    <div class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <i data-lucide="briefcase" class="w-5 h-5 text-amber-400 mr-2"></i>
                        <span class="text-white font-medium">Everything You Need for Travel</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-amber-400/20 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-blue-400/20 rounded-full blur-xl animate-pulse delay-1000"></div>
    </div>

    <!-- Main Services Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i data-lucide="star" class="w-4 h-4 text-amber-600 mr-2"></i>
                    <span class="text-amber-700 font-medium text-sm">Our Services</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    Everything You Need for Perfect Travel
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    From planning to booking, we provide comprehensive travel services to make your journey seamless
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Flight Booking -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-amber-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="plane" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Flight Booking</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Search and book flights from multiple airlines worldwide with real-time pricing and instant confirmation.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Real-time flight search</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Competitive prices</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Instant confirmation</span>
                        </li>
                    </ul>
                    <a href="{{ route('flights.search') }}" class="inline-flex items-center text-amber-600 hover:text-amber-700 font-medium">
                        <span>Book Flight</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>

                <!-- Hotel Booking -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-blue-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="building" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Hotel Booking</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Find and book comfortable accommodations worldwide with detailed reviews and competitive rates.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Worldwide hotels</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Detailed reviews</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Best price guarantee</span>
                        </li>
                    </ul>
                    <a href="{{ route('hotels.search') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <span>Book Hotel</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>

                <!-- Flight Search -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-green-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="search" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Flight Search</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Advanced flight search with flexible dates, multiple airports, and price comparison tools.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Flexible date search</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Price comparison</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Multiple airports</span>
                        </li>
                    </ul>
                    <a href="{{ route('flights.search') }}" class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                        <span>Search Flights</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>

                <!-- Trip Planning -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-purple-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="map" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Trip Planning</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Comprehensive trip planning services with itinerary creation and destination recommendations.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Custom itineraries</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Destination guides</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Expert recommendations</span>
                        </li>
                    </ul>
                    <a href="{{ route('support.trip-planning') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 font-medium">
                        <span>Plan Trip</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>

                <!-- Travel Insurance -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-red-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="shield" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Travel Insurance</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Comprehensive travel insurance coverage to protect your journey and provide peace of mind.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Medical coverage</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Trip cancellation</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>24/7 assistance</span>
                        </li>
                    </ul>
                    <a href="{{ route('support.insurance') }}" class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                        <span>Get Insurance</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>

                <!-- Customer Support -->
                <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-teal-200">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="headphones" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Customer Support</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        24/7 customer support with dedicated agents ready to assist with all your travel needs.
                    </p>
                    <ul class="space-y-2 mb-6">
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>24/7 availability</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Multiple channels</span>
                        </li>
                        <li class="flex items-center text-sm text-slate-600">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            <span>Expert assistance</span>
                        </li>
                    </ul>
                    <a href="{{ route('support.contact') }}" class="inline-flex items-center text-teal-600 hover:text-teal-700 font-medium">
                        <span>Contact Support</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Services Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-800 mb-4">Additional Services</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Enhance your travel experience with our premium services
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Visa Assistance -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="file-text" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Visa Assistance</h3>
                    <p class="text-slate-600 text-sm">
                        Get help with visa applications and travel document requirements.
                    </p>
                </div>

                <!-- Car Rental -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="car" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Car Rental</h3>
                    <p class="text-slate-600 text-sm">
                        Book rental cars at your destination with competitive rates.
                    </p>
                </div>

                <!-- Airport Transfers -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="navigation" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Airport Transfers</h3>
                    <p class="text-slate-600 text-sm">
                        Convenient airport transfer services for a smooth journey.
                    </p>
                </div>

                <!-- Travel Packages -->
                <div class="text-center group">
                    <div class="w-20 h-20 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i data-lucide="package" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Travel Packages</h3>
                    <p class="text-slate-600 text-sm">
                        Complete travel packages with flights, hotels, and activities.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-gradient-to-br from-slate-800 to-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">Why Choose Our Services?</h2>
                <p class="text-slate-300 text-lg max-w-2xl mx-auto">
                    We're committed to providing exceptional travel services with unmatched quality
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="award" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Best Prices</h3>
                    <p class="text-slate-300">
                        We guarantee the best prices on flights and hotels with our price match promise.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="clock" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">24/7 Support</h3>
                    <p class="text-slate-300">
                        Our customer support team is available around the clock to assist you.
                    </p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-4">Secure Booking</h3>
                    <p class="text-slate-300">
                        All bookings are secured with industry-leading encryption and security measures.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-amber-400 to-orange-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Ready to Start Your Journey?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Let us help you create unforgettable travel experiences with our comprehensive services
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('flights.search') }}" class="inline-flex items-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-full hover:bg-slate-50 transition-colors duration-200">
                    <i data-lucide="plane" class="w-5 h-5 mr-2"></i>
                    Book Your Flight
                </a>
                <a href="{{ route('support.contact') }}" class="inline-flex items-center px-8 py-4 bg-white/20 text-white font-semibold rounded-full hover:bg-white/30 transition-colors duration-200 backdrop-blur-sm">
                    <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
                    Contact Us
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
</script>
@endpush

@push('styles')
<style>
    .text-gradient {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush