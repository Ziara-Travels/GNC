@extends('layouts.main')

@section('title', 'Why Choose Us - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-indigo-600 to-purple-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Why Choose Ziara Travel?
                </h1>
                <p class="text-xl md:text-2xl text-indigo-100 mb-8 animate-slide-up">
                    Discover what makes us the preferred choice for millions of travelers worldwide
                </p>
            </div>
        </div>
    </section>

    <!-- Key Benefits Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Competitive Advantages</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Here's why over 2 million customers trust us with their travel plans
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Best Prices -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-green-500">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="dollar-sign" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Best Price Guarantee</h3>
                    <p class="text-gray-600 mb-4">
                        We guarantee the lowest prices. If you find a better deal elsewhere, we'll match it plus give you an additional discount.
                    </p>
                    <div class="text-sm text-green-600 font-semibold">
                        ✓ Price matching within 24 hours
                    </div>
                </div>

                <!-- 24/7 Support -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-blue-500">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="headphones" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">24/7 Customer Support</h3>
                    <p class="text-gray-600 mb-4">
                        Our dedicated support team is available round the clock to assist you with any queries or emergencies.
                    </p>
                    <div class="text-sm text-blue-600 font-semibold">
                        ✓ Multi-language support available
                    </div>
                </div>

                <!-- Instant Confirmation -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-purple-500">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="zap" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Instant Confirmation</h3>
                    <p class="text-gray-600 mb-4">
                        Get immediate booking confirmations with e-tickets delivered to your email within minutes.
                    </p>
                    <div class="text-sm text-purple-600 font-semibold">
                        ✓ Real-time inventory updates
                    </div>
                </div>

                <!-- Flexible Booking -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-orange-500">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="calendar" class="w-8 h-8 text-orange-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Flexible Booking Options</h3>
                    <p class="text-gray-600 mb-4">
                        Enjoy free cancellations, date changes, and flexible payment options to suit your travel needs.
                    </p>
                    <div class="text-sm text-orange-600 font-semibold">
                        ✓ Free cancellation up to 24 hours
                    </div>
                </div>

                <!-- Global Network -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-red-500">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="globe" class="w-8 h-8 text-red-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Global Network</h3>
                    <p class="text-gray-600 mb-4">
                        Access to 500+ airlines and 1 million+ hotels worldwide through our extensive partner network.
                    </p>
                    <div class="text-sm text-red-600 font-semibold">
                        ✓ Covering 190+ countries
                    </div>
                </div>

                <!-- Secure Payments -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 border-l-4 border-indigo-500">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="shield-check" class="w-8 h-8 text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Secure Payments</h3>
                    <p class="text-gray-600 mb-4">
                        Your payment information is protected with bank-level security and SSL encryption.
                    </p>
                    <div class="text-sm text-indigo-600 font-semibold">
                        ✓ PCI DSS compliant
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Trusted by Millions</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our numbers speak for themselves
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-blue-600 mb-2">2M+</div>
                    <div class="text-gray-600 font-medium">Happy Customers</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-green-600 mb-2">500+</div>
                    <div class="text-gray-600 font-medium">Airline Partners</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-purple-600 mb-2">1M+</div>
                    <div class="text-gray-600 font-medium">Hotels Listed</div>
                </div>
                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-2">24/7</div>
                    <div class="text-gray-600 font-medium">Customer Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">What Our Customers Say</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Real feedback from real travelers
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "Exceptional service! Booked my entire European vacation through Ziara Travel. The process was seamless and their customer support was outstanding."
                    </p>
                    <div class="flex items-center">
                        <img src="https://ui-avatars.com/api/?name=Sarah+Johnson&background=3b82f6&color=ffffff" alt="Sarah Johnson" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <div class="font-semibold text-gray-800">Sarah Johnson</div>
                            <div class="text-sm text-gray-600">Business Traveler</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "Best prices guaranteed and they actually delivered! Saved over $300 on my family vacation compared to other booking sites."
                    </p>
                    <div class="flex items-center">
                        <img src="https://ui-avatars.com/api/?name=Michael+Chen&background=10b981&color=ffffff" alt="Michael Chen" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <div class="font-semibold text-gray-800">Michael Chen</div>
                            <div class="text-sm text-gray-600">Family Traveler</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "The 24/7 support really saved my trip when my flight got cancelled. They rebooked me immediately without any extra charges."
                    </p>
                    <div class="flex items-center">
                        <img src="https://ui-avatars.com/api/?name=Emma+Williams&background=8b5cf6&color=ffffff" alt="Emma Williams" class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <div class="font-semibold text-gray-800">Emma Williams</div>
                            <div class="text-sm text-gray-600">Solo Traveler</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Experience the Difference</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Join millions of satisfied customers and discover why Ziara Travel is the smart choice for your next adventure
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('search-flights') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-300">
                    Start Booking Now
                </a>
                <a href="{{ route('contactus') }}" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors duration-300">
                    Contact Our Experts
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