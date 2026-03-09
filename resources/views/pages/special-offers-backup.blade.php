@extends('layouts.main')

@section('title', 'Special Offers - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-50 to-red-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-orange-500 to-red-600 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Special Offers
                </h1>
                <p class="text-xl md:text-2xl text-orange-100 mb-8 animate-slide-up">
                    Discover amazing deals and exclusive discounts on your next adventure
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="text-4xl font-bold mb-2">Up to 70% OFF</div>
                    <div class="text-lg">Limited time offers</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Deals -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Featured Deals</h2>
                    <p class="text-xl text-gray-600">Don't miss these exclusive limited-time offers</p>
                </div>

                <div class="grid lg:grid-cols-2 gap-8 mb-16">
                    <!-- Flash Sale -->
                    <div class="bg-gradient-to-r from-red-500 to-pink-600 rounded-3xl p-8 text-white relative overflow-hidden">
                        <div class="absolute top-4 right-4 bg-yellow-400 text-red-600 px-3 py-1 rounded-full text-sm font-bold">
                            FLASH SALE
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold mb-4">Weekend Flash Sale</h3>
                            <p class="text-xl mb-6">Up to 60% off on selected destinations</p>
                            <div class="flex items-center mb-6">
                                <div class="text-4xl font-bold mr-4">23:45:12</div>
                                <div class="text-sm">Time Left</div>
                            </div>
                            <button class="bg-white text-red-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors duration-300">
                                Shop Now
                            </button>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full"></div>
                    </div>

                    <!-- Early Bird -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-3xl p-8 text-white relative overflow-hidden">
                        <div class="absolute top-4 right-4 bg-green-400 text-blue-600 px-3 py-1 rounded-full text-sm font-bold">
                            EARLY BIRD
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-3xl font-bold mb-4">Summer 2025 Early Bird</h3>
                            <p class="text-xl mb-6">Book now and save up to 40% on summer packages</p>
                            <div class="flex items-center mb-6">
                                <div class="text-4xl font-bold mr-4">40%</div>
                                <div class="text-sm">OFF</div>
                            </div>
                            <button class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors duration-300">
                                Book Early
                            </button>
                        </div>
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Deal Categories -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Browse Deals by Category</h2>
                    <p class="text-xl text-gray-600">Find the perfect deal for your travel style</p>
                </div>

                <!-- Category Tabs -->
                <div class="flex flex-wrap justify-center mb-12 gap-4">
                    <button class="deal-tab active px-6 py-3 bg-orange-500 text-white rounded-full font-medium hover:bg-orange-600 transition-colors duration-300" data-category="flights">
                        <i data-lucide="plane" class="w-5 h-5 inline-block mr-2"></i>
                        Flights
                    </button>
                    <button class="deal-tab px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-medium hover:bg-gray-300 transition-colors duration-300" data-category="hotels">
                        <i data-lucide="hotel" class="w-5 h-5 inline-block mr-2"></i>
                        Hotels
                    </button>
                    <button class="deal-tab px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-medium hover:bg-gray-300 transition-colors duration-300" data-category="packages">
                        <i data-lucide="map" class="w-5 h-5 inline-block mr-2"></i>
                        Packages
                    </button>
                    <button class="deal-tab px-6 py-3 bg-gray-200 text-gray-700 rounded-full font-medium hover:bg-gray-300 transition-colors duration-300" data-category="cars">
                        <i data-lucide="car" class="w-5 h-5 inline-block mr-2"></i>
                        Car Rentals
                    </button>
                </div>

                <!-- Flight Deals -->
                <div class="deal-category" data-category="flights">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1540979388789-6cee28a1cdc9?w=400&h=250&fit=crop" alt="Paris" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    50% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">New York to Paris</h3>
                                <p class="text-gray-600 mb-4">Round-trip flights starting from</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-orange-600">$399</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$799</span>
                                    </div>
                                    <button class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=400&h=250&fit=crop" alt="Tokyo" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    35% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Los Angeles to Tokyo</h3>
                                <p class="text-gray-600 mb-4">Round-trip flights starting from</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-orange-600">$649</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$999</span>
                                    </div>
                                    <button class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?w=400&h=250&fit=crop" alt="London" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    45% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Miami to London</h3>
                                <p class="text-gray-600 mb-4">Round-trip flights starting from</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-orange-600">$449</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$799</span>
                                    </div>
                                    <button class="bg-orange-500 text-white px-4 py-2 rounded-full hover:bg-orange-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hotel Deals -->
                <div class="deal-category hidden" data-category="hotels">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=250&fit=crop" alt="Luxury Hotel" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    60% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Luxury Beach Resort</h3>
                                <p class="text-gray-600 mb-4">5-star resort in Maldives</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-green-600">$199</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$499</span>
                                        <span class="text-sm text-gray-500 block">per night</span>
                                    </div>
                                    <button class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&h=250&fit=crop" alt="City Hotel" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    40% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Downtown City Hotel</h3>
                                <p class="text-gray-600 mb-4">4-star hotel in Manhattan</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-green-600">$149</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$249</span>
                                        <span class="text-sm text-gray-500 block">per night</span>
                                    </div>
                                    <button class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop" alt="Mountain Resort" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    55% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Mountain Ski Resort</h3>
                                <p class="text-gray-600 mb-4">Luxury resort in Aspen</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-green-600">$179</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$399</span>
                                        <span class="text-sm text-gray-500 block">per night</span>
                                    </div>
                                    <button class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Deals -->
                <div class="deal-category hidden" data-category="packages">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=250&fit=crop" alt="Europe Package" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-purple-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    PACKAGE DEAL
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">7-Day Europe Tour</h3>
                                <p class="text-gray-600 mb-4">Paris, Rome, Barcelona with flights & hotels</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-purple-600">$1,299</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$1,899</span>
                                        <span class="text-sm text-gray-500 block">per person</span>
                                    </div>
                                    <button class="bg-purple-500 text-white px-4 py-2 rounded-full hover:bg-purple-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Car Rental Deals -->
                <div class="deal-category hidden" data-category="cars">
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="https://images.unsplash.com/photo-1549924231-f129b911e442?w=400&h=250&fit=crop" alt="Car Rental" class="w-full h-48 object-cover">
                                <div class="absolute top-4 left-4 bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                    30% OFF
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Economy Car Rental</h3>
                                <p class="text-gray-600 mb-4">Perfect for city exploration</p>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-2xl font-bold text-blue-600">$25</span>
                                        <span class="text-lg text-gray-500 line-through ml-2">$35</span>
                                        <span class="text-sm text-gray-500 block">per day</span>
                                    </div>
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 transition-colors duration-300">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="py-20 bg-gradient-to-r from-orange-500 to-red-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-4">Never Miss a Deal</h2>
                <p class="text-xl mb-8">Subscribe to our newsletter and be the first to know about exclusive offers</p>
                
                <div class="flex flex-col md:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email" class="flex-1 px-6 py-3 rounded-full text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                    <button class="bg-white text-orange-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors duration-300">
                        Subscribe
                    </button>
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

    // Deal category tabs
    const dealTabs = document.querySelectorAll('.deal-tab');
    const dealCategories = document.querySelectorAll('.deal-category');

    dealTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active tab
            dealTabs.forEach(t => {
                t.classList.remove('active', 'bg-orange-500', 'text-white');
                t.classList.add('bg-gray-200', 'text-gray-700');
            });
            this.classList.remove('bg-gray-200', 'text-gray-700');
            this.classList.add('active', 'bg-orange-500', 'text-white');
            
            // Show corresponding category
            dealCategories.forEach(cat => {
                if (cat.dataset.category === category) {
                    cat.classList.remove('hidden');
                } else {
                    cat.classList.add('hidden');
                }
            });
        });
    });

    // Countdown timer (example)
    function updateCountdown() {
        const hours = Math.floor(Math.random() * 24);
        const minutes = Math.floor(Math.random() * 60);
        const seconds = Math.floor(Math.random() * 60);
        
        const timeString = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        const countdown = document.querySelector('.text-4xl.font-bold.mr-4');
        if (countdown) {
            countdown.textContent = timeString;
        }
    }

    // Update countdown every second
    setInterval(updateCountdown, 1000);
});
</script>

@push('styles')
<style>
.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-up {
    animation: slideUp 1s ease-out 0.3s both;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush
@endsection