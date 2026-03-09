@extends('layouts.main')

@section('title', 'What We Do - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-blue-600 to-indigo-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    What We Do
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 animate-slide-up">
                    Transforming the way you travel with innovative solutions and personalized experiences
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="px-6 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i data-lucide="globe" class="w-4 h-4 inline mr-2"></i>
                        Global Reach
                    </span>
                    <span class="px-6 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i data-lucide="heart" class="w-4 h-4 inline mr-2"></i>
                        Customer First
                    </span>
                    <span class="px-6 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i data-lucide="award" class="w-4 h-4 inline mr-2"></i>
                        Excellence
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Core Services</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We provide comprehensive travel solutions that cover every aspect of your journey
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Flight Booking -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i data-lucide="plane" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Flight Booking</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Search, compare, and book flights from hundreds of airlines worldwide with our intelligent booking system.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Real-time price comparison
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Flexible date options
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Instant confirmation
                        </li>
                    </ul>
                </div>

                <!-- Hotel Reservations -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i data-lucide="hotel" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Hotel Reservations</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Find and book the perfect accommodation from luxury resorts to budget-friendly options.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Best price guarantee
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Free cancellation
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            24/7 support
                        </li>
                    </ul>
                </div>

                <!-- Travel Insurance -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i data-lucide="shield-check" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Travel Insurance</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Protect your journey with comprehensive travel insurance coverage for peace of mind.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Medical coverage
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Trip cancellation
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Luggage protection
                        </li>
                    </ul>
                </div>

                <!-- Car Rentals -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i data-lucide="car" class="w-8 h-8 text-orange-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Car Rentals</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Rent vehicles from trusted providers worldwide with competitive rates and flexible terms.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Wide vehicle selection
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            No hidden fees
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Free modifications
                        </li>
                    </ul>
                </div>

                <!-- Trip Planning -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i data-lucide="map" class="w-8 h-8 text-red-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Trip Planning</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Let our experts create customized itineraries tailored to your preferences and budget.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Personalized itineraries
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Local recommendations
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            24/7 assistance
                        </li>
                    </ul>
                </div>

                <!-- Customer Support -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6 mx-auto">
                        <i data-lucide="headphones" class="w-8 h-8 text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">Customer Support</h3>
                    <p class="text-gray-600 text-center mb-6">
                        Round-the-clock customer support to assist you before, during, and after your journey.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            24/7 availability
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Multi-language support
                        </li>
                        <li class="flex items-center">
                            <i data-lucide="check" class="w-4 h-4 text-green-500 mr-2"></i>
                            Emergency assistance
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- How We Work Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">How We Work</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our streamlined process ensures a seamless booking experience from start to finish
                </p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <i data-lucide="search" class="w-10 h-10 text-blue-600"></i>
                            <span class="absolute -top-2 -right-2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Search & Compare</h3>
                        <p class="text-gray-600">Use our advanced search to find the best deals across multiple providers</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <i data-lucide="bookmark" class="w-10 h-10 text-green-600"></i>
                            <span class="absolute -top-2 -right-2 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Select & Customize</h3>
                        <p class="text-gray-600">Choose your preferred options and customize your travel package</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <i data-lucide="credit-card" class="w-10 h-10 text-purple-600"></i>
                            <span class="absolute -top-2 -right-2 w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Secure Payment</h3>
                        <p class="text-gray-600">Complete your booking with our secure payment gateway</p>
                    </div>

                    <!-- Step 4 -->
                    <div class="text-center">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <i data-lucide="check-circle" class="w-10 h-10 text-orange-600"></i>
                            <span class="absolute -top-2 -right-2 w-8 h-8 bg-orange-600 text-white rounded-full flex items-center justify-center text-sm font-bold">4</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Enjoy Your Trip</h3>
                        <p class="text-gray-600">Receive instant confirmation and enjoy your hassle-free journey</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Start Your Journey?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Join millions of travelers who trust Ziara Travel for their booking needs
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('search-flights') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-300">
                    Search Flights
                </a>
                <a href="{{ route('contactus') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors duration-300">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endsection