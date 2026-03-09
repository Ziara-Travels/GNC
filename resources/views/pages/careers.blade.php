@extends('layouts.main')

@section('title', 'Careers - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-blue-600 to-indigo-800 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Join Our Team
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 animate-slide-up">
                    Build your career with us and help transform the future of travel
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="px-6 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i data-lucide="map" class="w-4 h-4 inline mr-2"></i>
                        Global Opportunities
                    </span>
                    <span class="px-6 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i data-lucide="trending-up" class="w-4 h-4 inline mr-2"></i>
                        Career Growth
                    </span>
                    <span class="px-6 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i data-lucide="users" class="w-4 h-4 inline mr-2"></i>
                        Inclusive Culture
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Join Us Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Why Work With Us</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    At Ziara Travel, we're building a team of passionate individuals who are excited about transforming the travel industry
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="globe" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Global Impact</h3>
                    <p class="text-gray-600">
                        Be part of a company that's changing how millions of people around the world discover, book, and experience travel.
                    </p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="trending-up" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Growth & Development</h3>
                    <p class="text-gray-600">
                        We invest in your professional growth with continuous learning opportunities, mentorship programs, and clear career paths.
                    </p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="heart" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Work-Life Balance</h3>
                    <p class="text-gray-600">
                        Enjoy flexible work arrangements, generous time off, and wellness programs designed to support your overall wellbeing.
                    </p>
                </div>

                <!-- Benefit 4 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="plane" class="w-8 h-8 text-orange-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Travel Benefits</h3>
                    <p class="text-gray-600">
                        Access special travel discounts, annual travel stipends, and opportunities to explore destinations around the world.
                    </p>
                </div>

                <!-- Benefit 5 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="zap" class="w-8 h-8 text-red-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Innovation</h3>
                    <p class="text-gray-600">
                        Work with cutting-edge technology and be encouraged to bring your creative ideas to solve complex travel challenges.
                    </p>
                </div>

                <!-- Benefit 6 -->
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-6">
                        <i data-lucide="users" class="w-8 h-8 text-indigo-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Inclusive Culture</h3>
                    <p class="text-gray-600">
                        Join a diverse team where everyone's voice is valued, and different perspectives are celebrated and encouraged.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Current Openings Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Current Openings</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Explore our open positions and find your perfect role at Ziara Travel
                </p>
            </div>

            <!-- Job Search and Filter -->
            <div class="max-w-4xl mx-auto mb-12">
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label for="department" class="block text-gray-700 font-medium mb-2">Department</label>
                            <select id="department" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">All Departments</option>
                                <option value="technology">Technology</option>
                                <option value="customer-service">Customer Service</option>
                                <option value="marketing">Marketing</option>
                                <option value="operations">Operations</option>
                                <option value="finance">Finance</option>
                                <option value="hr">Human Resources</option>
                            </select>
                        </div>
                        <div>
                            <label for="location" class="block text-gray-700 font-medium mb-2">Location</label>
                            <select id="location" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">All Locations</option>
                                <option value="nairobi">Nairobi, Kenya</option>
                                <option value="remote">Remote</option>
                                <option value="london">London, UK</option>
                                <option value="dubai">Dubai, UAE</option>
                                <option value="singapore">Singapore</option>
                            </select>
                        </div>
                        <div>
                            <label for="type" class="block text-gray-700 font-medium mb-2">Job Type</label>
                            <select id="type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">All Types</option>
                                <option value="full-time">Full Time</option>
                                <option value="part-time">Part Time</option>
                                <option value="contract">Contract</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="relative">
                            <input type="text" placeholder="Search for jobs..." class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i data-lucide="search" class="w-5 h-5 absolute text-gray-500 left-3 top-1/2 -translate-y-1/2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Listings -->
            <div class="space-y-6 max-w-4xl mx-auto">
                <!-- Job 1 -->
                <div class="bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all">
                    <div class="p-6">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Senior Software Engineer</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        Technology
                                    </span>
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        Full Time
                                    </span>
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                        Nairobi
                                    </span>
                                </div>
                            </div>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-300">
                                Apply Now
                            </button>
                        </div>
                        <p class="text-gray-600 mb-4">
                            We are looking for a Senior Software Engineer to lead our development team in building innovative travel solutions. You will work with cutting-edge technologies and have the opportunity to shape the future of travel.
                        </p>
                        <div class="text-gray-500 text-sm">
                            <p><i data-lucide="clock" class="w-4 h-4 inline mr-1"></i> Posted 2 days ago</p>
                            <p><i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i> Nairobi, Kenya</p>
                            <p><i data-lucide="users" class="w-4 h-4 inline mr-1"></i> 5+ years experience</p>
                        </div>
                    </div>
                </div>
                <!-- Job 2 -->
                <div class="bg-white border border-gray-200 rounded-xl hover:shadow-md transition
-all">
                    <div class="p-6">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Customer Service Representative</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        Customer Service
                                    </span>
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        Part Time
                                    </span>
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                        Remote
                                    </span>
                                </div>
                            </div>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-300">
                                Apply Now
                            </button>
                        </div>
                        <p class="text-gray-600 mb-4">
                            Join our customer service team to provide exceptional support to our travelers. You will assist with inquiries, bookings, and ensure a seamless travel experience for our customers.
                        </p>
                        <div class="text-gray-500 text-sm">
                            <p><i data-lucide="clock" class="w-4 h-4 inline mr-1"></i> Posted 5 days ago</p>
                            <p><i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i> Remote</p>
                            <p><i data-lucide="users" class="w-4 h-4 inline mr-1"></i> 2+ years experience</p>
                        </div>
                    </div>
                </div>
                <!-- Job 3 -->
                <div class="bg-white border border-gray-200 rounded-xl hover:shadow-md transition-all">
                    <div class="p-6">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800">Senior Software Engineer</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                        Engineering
                                    </span>
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                        Full Time
                                    </span>
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                        On-site
                                    </span>
                                </div>
                            </div>
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors duration-300">
                                Apply Now
                            </button>
                        </div>
                        <p class="text-gray-600 mb-4">
                            We are looking for a Senior Software Engineer to lead our development team in building innovative travel solutions. You will work with cutting-edge technologies and have the opportunity to shape the future of travel.
                        </p>
                        <div class="text-gray-500 text-sm">
                            <p><i data-lucide="clock" class="w-4 h-4 inline mr-1"></i> Posted 2 days ago</p>
                            <p><i data-lucide="map-pin" class="w-4 h-4 inline mr-1"></i> Nairobi, Kenya</p>
                            <p><i data-lucide="users" class="w-4 h-4 inline mr-1"></i> 5+ years experience</p>
                        </div>
                    </div>
                </div>

            </div>
            <!-- More Jobs Button -->
            <div class="text-center mt-12">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300">
                    View More Openings
                </button>
            </div>
        </div>

    </section>  
    <!-- Call to Action Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Join Us?</h2>
            <p class="text-xl mb-8">
                If you're passionate about travel and want to be part of a dynamic team, we want to hear from you!
            </p>
            <a href="#" class="inline-flex items-center px
                -8 py-4 bg-white/20 text-white font-semibold rounded-full hover:bg-white/30 transition-colors duration-200 backdrop-blur-sm">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                Apply Now
            </a>
        </div>

    </section>
</div>
@endsection 
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any JavaScript functionality here if needed
    });
</script>