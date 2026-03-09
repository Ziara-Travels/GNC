@extends('layouts.main')

@section('title', 'VIP Membership - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-indigo-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-purple-600 to-indigo-700 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    VIP Membership
                </h1>
                <p class="text-xl md:text-2xl text-purple-100 mb-8 animate-slide-up">
                    Elevate your travel experience with exclusive privileges and personalized service
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="text-4xl font-bold mb-2">Premium Benefits</div>
                    <div class="text-lg">Exclusively for VIP members</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Tiers -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Choose Your VIP Tier</h2>
                    <p class="text-xl text-gray-600">Select the membership level that best suits your travel lifestyle</p>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Gold VIP -->
                    <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-3xl p-8 relative overflow-hidden border-2 border-yellow-300">
                        <div class="absolute top-4 right-4">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i data-lucide="star" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">Gold VIP</h3>
                        <p class="text-gray-600 mb-6">Perfect for frequent travelers</p>
                        
                        <div class="mb-8">
                            <div class="text-4xl font-bold text-yellow-600 mb-2">$299</div>
                            <div class="text-gray-600">per year</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Priority customer support
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                15% discount on all bookings
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Free seat selection
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Airport lounge access (2 visits/year)
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Exclusive deals and early access
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Travel insurance included
                            </li>
                        </ul>

                        <button class="w-full bg-yellow-500 text-white py-4 rounded-2xl font-bold hover:bg-yellow-600 transition-colors duration-300">
                            Choose Gold VIP
                        </button>
                    </div>

                    <!-- Platinum VIP -->
                    <div class="bg-gradient-to-br from-gray-200 to-gray-300 rounded-3xl p-8 relative overflow-hidden border-2 border-gray-400 transform scale-105">
                        <div class="absolute top-0 left-0 bg-purple-600 text-white px-4 py-2 rounded-br-2xl text-sm font-bold">
                            MOST POPULAR
                        </div>
                        <div class="absolute top-4 right-4">
                            <div class="w-12 h-12 bg-gray-600 rounded-full flex items-center justify-center">
                                <i data-lucide="crown" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">Platinum VIP</h3>
                        <p class="text-gray-600 mb-6">For the discerning traveler</p>
                        
                        <div class="mb-8">
                            <div class="text-4xl font-bold text-gray-700 mb-2">$599</div>
                            <div class="text-gray-600">per year</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                24/7 dedicated concierge service
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                25% discount on all bookings
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Complimentary upgrades (subject to availability)
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Unlimited airport lounge access
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Fast-track security and immigration
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Premium travel insurance
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Personal travel assistant
                            </li>
                        </ul>

                        <button class="w-full bg-gray-700 text-white py-4 rounded-2xl font-bold hover:bg-gray-800 transition-colors duration-300">
                            Choose Platinum VIP
                        </button>
                    </div>

                    <!-- Diamond VIP -->
                    <div class="bg-gradient-to-br from-blue-100 to-indigo-200 rounded-3xl p-8 relative overflow-hidden border-2 border-blue-300">
                        <div class="absolute top-4 right-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                                <i data-lucide="gem" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">Diamond VIP</h3>
                        <p class="text-gray-600 mb-6">Ultimate luxury experience</p>
                        
                        <div class="mb-8">
                            <div class="text-4xl font-bold text-blue-600 mb-2">$1,299</div>
                            <div class="text-gray-600">per year</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                White-glove concierge service
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                35% discount on all bookings
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Guaranteed upgrades to premium class
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Private jet and yacht access
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Exclusive luxury experiences
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Comprehensive travel protection
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Personal travel curator
                            </li>
                        </ul>

                        <button class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition-colors duration-300">
                            Choose Diamond VIP
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIP Benefits -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Exclusive VIP Benefits</h2>
                    <p class="text-xl text-gray-600">Experience travel like never before with our premium services</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="headphones" class="w-10 h-10 text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">24/7 Concierge</h3>
                        <p class="text-gray-600">Round-the-clock personal assistance for all your travel needs, from bookings to emergency support.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="shield-check" class="w-10 h-10 text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Priority Access</h3>
                        <p class="text-gray-600">Skip the lines with fast-track services at airports, hotels, and exclusive venues worldwide.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="upgrade" class="w-10 h-10 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Complimentary Upgrades</h3>
                        <p class="text-gray-600">Enjoy automatic upgrades to premium rooms, business class flights, and luxury car rentals.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="percent" class="w-10 h-10 text-yellow-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Exclusive Discounts</h3>
                        <p class="text-gray-600">Access special rates and exclusive discounts on flights, hotels, and travel experiences.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="heart" class="w-10 h-10 text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Personalized Service</h3>
                        <p class="text-gray-600">Tailored travel experiences crafted specifically to your preferences and travel style.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="globe" class="w-10 h-10 text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Global Network</h3>
                        <p class="text-gray-600">Access to an exclusive network of luxury partners and premium services worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">VIP Member Stories</h2>
                    <p class="text-xl text-gray-600">Hear from our valued VIP members about their exceptional experiences</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl">
                        <div class="flex items-center mb-6">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=60&h=60&fit=crop&crop=face" 
                                 alt="Member" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-bold text-gray-800">Michael Chen</h4>
                                <p class="text-sm text-gray-600">Diamond VIP Member</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">"The white-glove service exceeded all my expectations. My personal travel curator arranged an unforgettable yacht experience in Monaco that I could never have planned myself."</p>
                        <div class="flex text-yellow-500 mt-4">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl">
                        <div class="flex items-center mb-6">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b566?w=60&h=60&fit=crop&crop=face" 
                                 alt="Member" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-bold text-gray-800">Sarah Williams</h4>
                                <p class="text-sm text-gray-600">Platinum VIP Member</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">"The 24/7 concierge service has been a game-changer for my business travels. They handle everything seamlessly, allowing me to focus on what matters most."</p>
                        <div class="flex text-yellow-500 mt-4">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-8 rounded-2xl">
                        <div class="flex items-center mb-6">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&h=60&fit=crop&crop=face" 
                                 alt="Member" class="w-12 h-12 rounded-full mr-4">
                            <div>
                                <h4 class="font-bold text-gray-800">David Rodriguez</h4>
                                <p class="text-sm text-gray-600">Gold VIP Member</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">"The airport lounge access and priority support have made my frequent business trips so much more comfortable. Worth every penny!"</p>
                        <div class="flex text-yellow-500 mt-4">
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                            <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-purple-600 to-indigo-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-6">Ready to Elevate Your Travel Experience?</h2>
                <p class="text-xl text-purple-100 mb-8">
                    Join thousands of satisfied VIP members who enjoy exclusive benefits and personalized service
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="px-8 py-4 bg-white text-purple-600 font-bold rounded-2xl hover:bg-gray-100 transition-colors duration-300">
                        <i data-lucide="phone" class="w-5 h-5 inline-block mr-2"></i>
                        Speak with VIP Advisor
                    </button>
                    <button class="px-8 py-4 bg-purple-800 text-white font-bold rounded-2xl hover:bg-purple-900 transition-colors duration-300">
                        <i data-lucide="crown" class="w-5 h-5 inline-block mr-2"></i>
                        Upgrade to VIP Now
                    </button>
                </div>
                <p class="text-sm text-purple-200 mt-6">
                    * 30-day money-back guarantee • No setup fees • Cancel anytime
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">VIP Membership FAQ</h2>
                    <p class="text-xl text-gray-600">Common questions about our VIP membership program</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">How quickly do VIP benefits activate?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">VIP benefits activate immediately upon payment confirmation. You'll receive your welcome package and concierge contact details within 24 hours.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Can I upgrade or downgrade my membership?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Yes, you can upgrade or downgrade your membership at any time. When upgrading, you'll only pay the prorated difference. Downgrades take effect at your next renewal cycle.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Are there any additional fees for VIP services?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Most VIP services are included in your membership fee. Some premium services like private jet bookings or exclusive experiences may have additional costs, which will be clearly communicated upfront.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">What happens if I'm not satisfied with my VIP membership?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">We offer a 30-day money-back guarantee. If you're not completely satisfied within the first 30 days, we'll provide a full refund, no questions asked.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Can family members use my VIP benefits?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Platinum and Diamond VIP memberships include benefits for immediate family members (spouse and children under 18). Gold VIP members can add family coverage for an additional fee.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

function toggleFAQ(element) {
    const content = element.nextElementSibling;
    const icon = element.querySelector('i[data-lucide="chevron-down"]');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
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