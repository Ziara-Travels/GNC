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
                    About <span class="text-gradient bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Ziara Travels</span>
                </h1>
                <p class="text-xl md:text-2xl text-slate-300 mb-8 max-w-2xl mx-auto">
                    Your Trusted Partner in Air Travel Excellence
                </p>
                <div class="flex justify-center">
                    <div class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <i data-lucide="plane" class="w-5 h-5 text-amber-400 mr-2"></i>
                        <span class="text-white font-medium">Connecting Dreams Since 2025</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-amber-400/20 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-blue-400/20 rounded-full blur-xl animate-pulse delay-1000"></div>
    </div>

    <!-- Main Content -->
    <div class="bg-white">
        <!-- Our Story Section -->
        <section class="py-20 bg-gradient-to-br from-slate-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="space-y-6">
                        <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full">
                            <i data-lucide="book-open" class="w-4 h-4 text-amber-600 mr-2"></i>
                            <span class="text-amber-700 font-medium text-sm">Our Story</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-800 leading-tight">
                            Revolutionizing Air Travel Since Day One
                        </h2>
                        <p class="text-lg text-slate-600 leading-relaxed">
                            Founded in 2025, Ziara Travels has been at the forefront of revolutionizing air travel booking. We believe in making air travel accessible, convenient, and enjoyable for everyone.
                        </p>
                        <p class="text-slate-600 leading-relaxed">
                            Our platform combines cutting-edge technology with personalized service to deliver the best flight booking experience. From solo travelers to large groups, we've got you covered with competitive prices and exceptional service.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-4">
                            <div class="flex items-center text-slate-600">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                <span>24/7 Customer Support</span>
                            </div>
                            <div class="flex items-center text-slate-600">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                <span>Best Price Guarantee</span>
                            </div>
                            <div class="flex items-center text-slate-600">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                                <span>Secure Booking</span>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl transform rotate-3"></div>
                        <img src="{{ asset('images/airplane.jpg') }}" alt="Our Story" class="relative z-10 w-full rounded-2xl shadow-2xl">
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Values Section -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full mb-6">
                        <i data-lucide="heart" class="w-4 h-4 text-blue-600 mr-2"></i>
                        <span class="text-blue-700 font-medium text-sm">Our Values</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        What Drives Us Forward
                    </h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                        Our core values guide everything we do, from customer service to product development
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Customer First -->
                    <div class="group hover:scale-105 transition-transform duration-300">
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-8 h-full border border-amber-100 hover:border-amber-200 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="users" class="w-8 h-8 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-4">Customer First</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Your satisfaction is our top priority. We're here to serve you 24/7 with dedicated support and personalized assistance.
                            </p>
                        </div>
                    </div>

                    <!-- Trust & Security -->
                    <div class="group hover:scale-105 transition-transform duration-300">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8 h-full border border-blue-100 hover:border-blue-200 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-4">Trust & Security</h3>
                            <p class="text-slate-600 leading-relaxed">
                                Your data and transactions are protected by industry-leading security measures and encryption protocols.
                            </p>
                        </div>
                    </div>

                    <!-- Innovation -->
                    <div class="group hover:scale-105 transition-transform duration-300">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-8 h-full border border-green-100 hover:border-green-200 hover:shadow-xl transition-all duration-300">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="zap" class="w-8 h-8 text-white"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800 mb-4">Innovation</h3>
                            <p class="text-slate-600 leading-relaxed">
                                We continuously improve our services with cutting-edge technology to provide the best booking experience.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="py-20 bg-gradient-to-br from-slate-800 to-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Trusted by Thousands
                    </h2>
                    <p class="text-slate-300 text-lg">
                        Our numbers speak for themselves
                    </p>
                </div>

                <div class="grid md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="plane" class="w-8 h-8 text-white"></i>
                        </div>
                        <div class="text-3xl font-bold text-white mb-2">10K+</div>
                        <div class="text-slate-300">Flights Booked</div>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="users" class="w-8 h-8 text-white"></i>
                        </div>
                        <div class="text-3xl font-bold text-white mb-2">5K+</div>
                        <div class="text-slate-300">Happy Customers</div>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="globe" class="w-8 h-8 text-white"></i>
                        </div>
                        <div class="text-3xl font-bold text-white mb-2">100+</div>
                        <div class="text-slate-300">Destinations</div>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="clock" class="w-8 h-8 text-white"></i>
                        </div>
                        <div class="text-3xl font-bold text-white mb-2">24/7</div>
                        <div class="text-slate-300">Support</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team Section -->
        <section class="py-20 bg-gradient-to-br from-slate-50 to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <div class="inline-flex items-center px-4 py-2 bg-purple-100 rounded-full mb-6">
                        <i data-lucide="users" class="w-4 h-4 text-purple-600 mr-2"></i>
                        <span class="text-purple-700 font-medium text-sm">Our Team</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                        Meet the Visionaries
                    </h2>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                        Behind every great service is a passionate team dedicated to excellence
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Team Member 1 -->
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-32 h-32 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full mx-auto flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="user" class="w-16 h-16 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Sarah Johnson</h3>
                        <p class="text-amber-600 font-medium mb-3">CEO & Founder</p>
                        <p class="text-slate-600 text-sm">
                            Passionate about making travel accessible to everyone with 15+ years in aviation industry.
                        </p>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mx-auto flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="user" class="w-16 h-16 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Michael Chen</h3>
                        <p class="text-blue-600 font-medium mb-3">CTO</p>
                        <p class="text-slate-600 text-sm">
                            Technology expert driving innovation in travel booking with cutting-edge solutions.
                        </p>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-32 h-32 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full mx-auto flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i data-lucide="user" class="w-16 h-16 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Emily Rodriguez</h3>
                        <p class="text-green-600 font-medium mb-3">Head of Customer Experience</p>
                        <p class="text-slate-600 text-sm">
                            Dedicated to ensuring every customer has an exceptional experience with our platform.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-gradient-to-r from-amber-400 to-orange-500">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Ready to Experience the Difference?
                </h2>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Join thousands of satisfied customers who trust Ziara Travels for their travel needs
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('flights.search') }}" class="inline-flex items-center px-8 py-4 bg-white text-amber-600 font-semibold rounded-full hover:bg-slate-50 transition-colors duration-200">
                        <i data-lucide="plane" class="w-5 h-5 mr-2"></i>
                        Book Your Flight
                    </a>
                    <a href="{{ route('contactus') }}" class="inline-flex items-center px-8 py-4 bg-white/20 text-white font-semibold rounded-full hover:bg-white/30 transition-colors duration-200 backdrop-blur-sm">
                        <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
                        Contact Us
                    </a>
                </div>
            </div>
        </section>
    </div>
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