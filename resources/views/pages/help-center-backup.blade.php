@extends('layouts.main')

@section('title', 'Help Center - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-indigo-600 to-purple-700 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Help Center
                </h1>
                <p class="text-xl md:text-2xl text-indigo-100 mb-8 animate-slide-up">
                    Find answers, guides, and support for all your travel needs
                </p>
                
                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" 
                               placeholder="Search for help articles..." 
                               class="w-full px-6 py-4 pr-12 text-lg text-gray-800 border border-transparent rounded-2xl focus:outline-none focus:ring-2 focus:ring-white/50"
                               id="help-search">
                        <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="search" class="w-6 h-6"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Help Categories -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">How Can We Help You?</h2>
                    <p class="text-xl text-gray-600">Choose a category to find specific help articles</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center hover:shadow-lg hover:border-blue-300 transition-all duration-300 cursor-pointer" onclick="scrollToSection('booking-help')">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="calendar" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Bookings & Reservations</h3>
                        <p class="text-gray-600 text-sm mb-4">How to book, modify, or cancel your trips</p>
                        <span class="text-blue-600 text-sm font-medium">15 articles</span>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center hover:shadow-lg hover:border-green-300 transition-all duration-300 cursor-pointer" onclick="scrollToSection('payment-help')">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="credit-card" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Payments & Billing</h3>
                        <p class="text-gray-600 text-sm mb-4">Payment methods, refunds, and billing issues</p>
                        <span class="text-green-600 text-sm font-medium">12 articles</span>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center hover:shadow-lg hover:border-purple-300 transition-all duration-300 cursor-pointer" onclick="scrollToSection('travel-help')">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="plane" class="w-8 h-8 text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Travel Guidelines</h3>
                        <p class="text-gray-600 text-sm mb-4">Travel requirements, documents, and tips</p>
                        <span class="text-purple-600 text-sm font-medium">20 articles</span>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6 text-center hover:shadow-lg hover:border-orange-300 transition-all duration-300 cursor-pointer" onclick="scrollToSection('account-help')">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="user" class="w-8 h-8 text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Account & Profile</h3>
                        <p class="text-gray-600 text-sm mb-4">Managing your account and personal settings</p>
                        <span class="text-orange-600 text-sm font-medium">8 articles</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Articles -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Most Popular Articles</h2>
                    <p class="text-xl text-gray-600">The most frequently accessed help articles</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i data-lucide="help-circle" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">How to cancel or modify my booking?</h3>
                                <p class="text-gray-600 text-sm mb-3">Learn about cancellation policies and how to make changes to your reservations.</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    <span>2.5k views</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i data-lucide="shield" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Travel insurance: What's covered?</h3>
                                <p class="text-gray-600 text-sm mb-3">Understand what's included in our travel insurance plans and how to file claims.</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    <span>1.8k views</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i data-lucide="file-text" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">What documents do I need to travel?</h3>
                                <p class="text-gray-600 text-sm mb-3">Complete guide to travel documents, visas, and passport requirements.</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    <span>1.6k views</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i data-lucide="luggage" class="w-6 h-6 text-orange-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Baggage allowances and restrictions</h3>
                                <p class="text-gray-600 text-sm mb-3">Everything you need to know about airline baggage policies and restrictions.</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    <span>1.4k views</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i data-lucide="alert-circle" class="w-6 h-6 text-red-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">What to do if my flight is cancelled?</h3>
                                <p class="text-gray-600 text-sm mb-3">Step-by-step guide for handling flight cancellations and rebooking.</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    <span>1.2k views</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-start mb-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                <i data-lucide="star" class="w-6 h-6 text-indigo-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">How to earn and redeem loyalty points?</h3>
                                <p class="text-gray-600 text-sm mb-3">Maximize your rewards with our comprehensive loyalty program guide.</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    <span>980 views</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Help Sections -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- Booking Help -->
                <div id="booking-help" class="mb-16">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                        <i data-lucide="calendar" class="w-6 h-6 text-blue-600 mr-3"></i>
                        Bookings & Reservations
                    </h3>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-xl">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                    onclick="toggleHelpArticle(this)">
                                <span class="font-semibold text-gray-800">How do I make a booking through GNC Travel?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <div class="prose prose-sm text-gray-600">
                                    <p>Making a booking is simple:</p>
                                    <ol>
                                        <li>Visit our homepage and use the search tool</li>
                                        <li>Enter your destination, dates, and passenger details</li>
                                        <li>Browse available options and select your preferred choice</li>
                                        <li>Review your selection and proceed to payment</li>
                                        <li>Complete payment and receive your confirmation</li>
                                    </ol>
                                    <p>You'll receive a confirmation email with all your booking details within minutes.</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                    onclick="toggleHelpArticle(this)">
                                <span class="font-semibold text-gray-800">Can I modify my booking after confirmation?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <div class="prose prose-sm text-gray-600">
                                    <p>Yes, most bookings can be modified subject to availability and the service provider's terms:</p>
                                    <ul>
                                        <li>Log into your account and go to "My Bookings"</li>
                                        <li>Select the booking you want to modify</li>
                                        <li>Click "Modify Booking" and make your changes</li>
                                        <li>Pay any difference in fare or fees</li>
                                    </ul>
                                    <p><strong>Note:</strong> Some fare types have restrictions. Additional fees may apply.</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                    onclick="toggleHelpArticle(this)">
                                <span class="font-semibold text-gray-800">What is your cancellation policy?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <div class="prose prose-sm text-gray-600">
                                    <p>Our cancellation policy varies by booking type:</p>
                                    <ul>
                                        <li><strong>Flexible fares:</strong> Free cancellation up to 24 hours before departure</li>
                                        <li><strong>Standard fares:</strong> Cancellation fees apply, varies by provider</li>
                                        <li><strong>Non-refundable fares:</strong> No refund, but may allow changes for a fee</li>
                                    </ul>
                                    <p>Travel insurance can provide additional protection for unexpected cancellations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Help -->
                <div id="payment-help" class="mb-16">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8 flex items-center">
                        <i data-lucide="credit-card" class="w-6 h-6 text-green-600 mr-3"></i>
                        Payments & Billing
                    </h3>
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-xl">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                    onclick="toggleHelpArticle(this)">
                                <span class="font-semibold text-gray-800">What payment methods do you accept?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <div class="prose prose-sm text-gray-600">
                                    <p>We accept various payment methods for your convenience:</p>
                                    <ul>
                                        <li>Credit cards: Visa, Mastercard, American Express, Discover</li>
                                        <li>Debit cards with Visa or Mastercard logo</li>
                                        <li>PayPal</li>
                                        <li>Bank transfers (for bookings over $1,000)</li>
                                        <li>Cryptocurrency (Bitcoin, Ethereum) for select bookings</li>
                                    </ul>
                                    <p>All payments are processed securely with 256-bit SSL encryption.</p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-xl">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                    onclick="toggleHelpArticle(this)">
                                <span class="font-semibold text-gray-800">How do refunds work?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <div class="prose prose-sm text-gray-600">
                                    <p>Refund processing depends on your booking type and payment method:</p>
                                    <ul>
                                        <li><strong>Credit cards:</strong> 5-10 business days</li>
                                        <li><strong>Debit cards:</strong> 5-10 business days</li>
                                        <li><strong>PayPal:</strong> 3-5 business days</li>
                                        <li><strong>Bank transfers:</strong> 7-14 business days</li>
                                    </ul>
                                    <p>Refunds are processed to the original payment method. Cancellation fees may apply based on the fare rules.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Support -->
    <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6">Still Need Help?</h2>
                <p class="text-xl text-indigo-100 mb-8">
                    Our support team is available 24/7 to assist you
                </p>
                <div class="grid md:grid-cols-3 gap-6">
                    <a href="{{ route('live-chat') }}" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors duration-300 rounded-2xl p-6 block">
                        <i data-lucide="message-circle" class="w-8 h-8 mx-auto mb-3"></i>
                        <h3 class="font-bold mb-2">Live Chat</h3>
                        <p class="text-sm text-indigo-100">Average response: 30 seconds</p>
                    </a>
                    
                    <a href="tel:+15551234567" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors duration-300 rounded-2xl p-6 block">
                        <i data-lucide="phone" class="w-8 h-8 mx-auto mb-3"></i>
                        <h3 class="font-bold mb-2">Call Us</h3>
                        <p class="text-sm text-indigo-100">+1 (555) 123-4567</p>
                    </a>
                    
                    <a href="mailto:support@gnctravel.com" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors duration-300 rounded-2xl p-6 block">
                        <i data-lucide="mail" class="w-8 h-8 mx-auto mb-3"></i>
                        <h3 class="font-bold mb-2">Email Us</h3>
                        <p class="text-sm text-indigo-100">Response within 2 hours</p>
                    </a>
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

function toggleHelpArticle(element) {
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

function scrollToSection(sectionId) {
    document.getElementById(sectionId).scrollIntoView({
        behavior: 'smooth'
    });
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

.prose ol {
    list-style-type: decimal;
    margin-left: 1.5em;
}

.prose ul {
    list-style-type: disc;
    margin-left: 1.5em;
}

.prose li {
    margin: 0.5em 0;
}
</style>
@endpush
@endsection