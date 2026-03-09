@extends('layouts.main')

@section('title', 'Our Achievements - Ziara Travels')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-yellow-500 to-orange-600 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Our Achievements
                </h1>
                <p class="text-xl md:text-2xl text-yellow-100 mb-8 animate-slide-up">
                    Celebrating milestones and recognitions that define our excellence in travel services
                </p>
                <div class="flex justify-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <i data-lucide="trophy" class="w-10 h-10 text-yellow-200"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Awards Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Awards & Recognition</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Industry recognition that validates our commitment to excellence
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Award 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 text-center border-t-4 border-yellow-500">
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="award" class="w-10 h-10 text-yellow-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Best Travel Platform 2023</h3>
                    <p class="text-gray-600 mb-4">World Travel Awards</p>
                    <div class="text-sm text-yellow-600 bg-yellow-50 px-4 py-2 rounded-lg">
                        Recognized for innovation and customer satisfaction
                    </div>
                </div>

                <!-- Award 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 text-center border-t-4 border-blue-500">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="star" class="w-10 h-10 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Excellence in Customer Service</h3>
                    <p class="text-gray-600 mb-4">Travel Industry Association</p>
                    <div class="text-sm text-blue-600 bg-blue-50 px-4 py-2 rounded-lg">
                        Outstanding 24/7 customer support
                    </div>
                </div>

                <!-- Award 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 text-center border-t-4 border-green-500">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="shield-check" class="w-10 h-10 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Most Trusted Travel Brand</h3>
                    <p class="text-gray-600 mb-4">Consumer Choice Awards</p>
                    <div class="text-sm text-green-600 bg-green-50 px-4 py-2 rounded-lg">
                        Based on customer trust and reliability
                    </div>
                </div>

                <!-- Award 4 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 text-center border-t-4 border-purple-500">
                    <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="zap" class="w-10 h-10 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Technology Innovation Award</h3>
                    <p class="text-gray-600 mb-4">Digital Travel Summit</p>
                    <div class="text-sm text-purple-600 bg-purple-50 px-4 py-2 rounded-lg">
                        AI-powered booking technology
                    </div>
                </div>

                <!-- Award 5 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 text-center border-t-4 border-red-500">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="globe" class="w-10 h-10 text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Global Expansion Excellence</h3>
                    <p class="text-gray-600 mb-4">International Business Awards</p>
                    <div class="text-sm text-red-600 bg-red-50 px-4 py-2 rounded-lg">
                        Fastest growing travel platform
                    </div>
                </div>

                <!-- Award 6 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 text-center border-t-4 border-indigo-500">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="users" class="w-10 h-10 text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Best Employer in Travel</h3>
                    <p class="text-gray-600 mb-4">HR Excellence Awards</p>
                    <div class="text-sm text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg">
                        Outstanding workplace culture
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Milestones Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Major Milestones</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Key achievements that mark our journey to becoming a leading travel platform
                </p>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="relative">
                    <!-- Timeline line -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-yellow-400 to-orange-500"></div>

                    <!-- Timeline items -->
                    <div class="space-y-12">
                        <!-- Milestone 1 -->
                        <div class="relative flex items-center">
                            <div class="flex-1 text-right pr-8">
                                <div class="bg-yellow-50 p-6 rounded-xl">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Company Founded</h3>
                                    <p class="text-gray-600">Started with a vision to revolutionize travel booking</p>
                                </div>
                            </div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold">
                                2015
                            </div>
                            <div class="flex-1 pl-8"></div>
                        </div>

                        <!-- Milestone 2 -->
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8"></div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                2017
                            </div>
                            <div class="flex-1 text-left pl-8">
                                <div class="bg-blue-50 p-6 rounded-xl">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">1 Million Users</h3>
                                    <p class="text-gray-600">Reached our first major user milestone</p>
                                </div>
                            </div>
                        </div>

                        <!-- Milestone 3 -->
                        <div class="relative flex items-center">
                            <div class="flex-1 text-right pr-8">
                                <div class="bg-green-50 p-6 rounded-xl">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">Global Expansion</h3>
                                    <p class="text-gray-600">Launched in 50+ countries worldwide</p>
                                </div>
                            </div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                2019
                            </div>
                            <div class="flex-1 pl-8"></div>
                        </div>

                        <!-- Milestone 4 -->
                        <div class="relative flex items-center">
                            <div class="flex-1 pr-8"></div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                2021
                            </div>
                            <div class="flex-1 text-left pl-8">
                                <div class="bg-purple-50 p-6 rounded-xl">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">AI Integration</h3>
                                    <p class="text-gray-600">Launched AI-powered booking assistant</p>
                                </div>
                            </div>
                        </div>

                        <!-- Milestone 5 -->
                        <div class="relative flex items-center">
                            <div class="flex-1 text-right pr-8">
                                <div class="bg-orange-50 p-6 rounded-xl">
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">10 Million Bookings</h3>
                                    <p class="text-gray-600">Processed our 10 millionth booking</p>
                                </div>
                            </div>
                            <div class="absolute left-1/2 transform -translate-x-1/2 w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                2023
                            </div>
                            <div class="flex-1 pl-8"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-yellow-500 to-orange-600 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-4">By the Numbers</h2>
                <p class="text-xl text-yellow-100 max-w-3xl mx-auto">
                    Our achievements in numbers
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-6xl font-bold mb-4">50+</div>
                    <div class="text-xl font-medium text-yellow-100">Awards Won</div>
                </div>
                <div class="text-center">
                    <div class="text-6xl font-bold mb-4">190+</div>
                    <div class="text-xl font-medium text-yellow-100">Countries Served</div>
                </div>
                <div class="text-center">
                    <div class="text-6xl font-bold mb-4">2M+</div>
                    <div class="text-xl font-medium text-yellow-100">Happy Customers</div>
                </div>
                <div class="text-center">
                    <div class="text-6xl font-bold mb-4">99%</div>
                    <div class="text-xl font-medium text-yellow-100">Customer Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Future Goals Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Looking Ahead</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our vision for the future of travel
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Goal 1 -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="target" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Carbon Neutral by 2025</h3>
                    <p class="text-gray-600">
                        Committed to sustainable travel and offsetting our carbon footprint
                    </p>
                </div>

                <!-- Goal 2 -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="rocket" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Next-Gen AI Platform</h3>
                    <p class="text-gray-600">
                        Launching the most advanced AI-powered travel planning system
                    </p>
                </div>

                <!-- Goal 3 -->
                <div class="bg-white rounded-2xl p-8 text-center shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="globe" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">5 Million Active Users</h3>
                    <p class="text-gray-600">
                        Expanding our community of travelers to 5 million active users
                    </p>
                </div>
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