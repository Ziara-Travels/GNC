@extends('layouts.main')

@section('title', 'Frequently Asked Questions - GNC Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-blue-600 to-indigo-700 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Frequently Asked Questions
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 animate-slide-up">
                    Find answers to the most common questions about our travel services
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="text-lg">Quick answers to help you travel better</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           id="faqSearch"
                           placeholder="Search for answers..." 
                           class="w-full px-6 py-4 pl-12 text-lg border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-lg">
                    <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Categories -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Browse by Category</h2>
                    <p class="text-xl text-gray-600">Select a category to find specific answers</p>
                </div>

                <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
                    <button class="category-btn active bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center" data-category="general">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="help-circle" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">General</h3>
                    </button>

                    <button class="category-btn bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center" data-category="booking">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="calendar" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Booking</h3>
                    </button>

                    <button class="category-btn bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center" data-category="payment">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="credit-card" class="w-8 h-8 text-orange-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Payment</h3>
                    </button>

                    <button class="category-btn bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 text-center" data-category="travel">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="plane" class="w-8 h-8 text-purple-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Travel</h3>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <!-- General FAQs -->
                <div class="faq-category" data-category="general">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8">General Questions</h3>
                    <div class="space-y-4">
                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">What is GNC Travel?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">GNC Travel is a comprehensive travel booking platform that offers flights, hotels, car rentals, and vacation packages worldwide. We provide personalized travel solutions with 24/7 customer support.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">How do I create an account?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Click on the "Sign Up" button in the top right corner of our website. Fill in your personal details, create a password, and verify your email address. Your account will be ready to use immediately.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">Is my personal information secure?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Yes, we use industry-standard SSL encryption and comply with international data protection regulations. Your personal and payment information is securely stored and never shared with third parties without your consent.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking FAQs -->
                <div class="faq-category hidden" data-category="booking">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8">Booking Questions</h3>
                    <div class="space-y-4">
                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">How do I make a booking?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Search for your desired destination and dates, select your preferred options, enter passenger details, and complete the payment. You'll receive a confirmation email with your booking details and e-tickets.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">Can I modify or cancel my booking?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Yes, you can modify or cancel most bookings through your account dashboard. Cancellation and modification policies vary by airline, hotel, or service provider. Additional fees may apply depending on the fare type and timing.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">What if I need to change passenger names?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Name changes are subject to airline policies and may incur fees. Contact our customer service team immediately if you need to correct a name. Minor spelling corrections are usually allowed, but complete name changes may require rebooking.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment FAQs -->
                <div class="faq-category hidden" data-category="payment">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8">Payment Questions</h3>
                    <div class="space-y-4">
                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">What payment methods do you accept?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">We accept all major credit cards (Visa, MasterCard, American Express), debit cards, PayPal, and bank transfers. Some destinations may have specific payment method restrictions.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">When will I be charged for my booking?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Payment is typically charged immediately upon booking confirmation. For some hotels and car rentals, you may be charged at the time of service. Payment terms will be clearly displayed before you complete your booking.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">How do refunds work?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Refunds are processed according to the fare rules and cancellation policy of your booking. Refundable bookings are processed within 7-14 business days to your original payment method. Non-refundable bookings may only be eligible for taxes and fees refund.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Travel FAQs -->
                <div class="faq-category hidden" data-category="travel">
                    <h3 class="text-2xl font-bold text-gray-800 mb-8">Travel Questions</h3>
                    <div class="space-y-4">
                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">Do I need a visa for my destination?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Visa requirements depend on your nationality and destination. We provide general visa information, but it's your responsibility to ensure you have the correct documentation. Check with the embassy or consulate of your destination country for official requirements.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">What's included in my flight ticket?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Your flight ticket includes transportation from origin to destination, taxes, and fees. Additional services like meals, baggage, seat selection, and in-flight entertainment may vary by airline and fare type. Check your booking details for specific inclusions.</p>
                            </div>
                        </div>

                        <div class="faq-item bg-white rounded-xl shadow-sm border">
                            <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                                <span class="font-semibold text-gray-800">What happens if my flight is delayed or cancelled?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                            </button>
                            <div class="hidden px-6 pb-4">
                                <p class="text-gray-600">Airlines are responsible for rebooking you on the next available flight at no extra cost. You may be entitled to compensation depending on the delay duration and reason. Our customer service team can assist you with rebooking and compensation claims.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Support -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Still Need Help?</h2>
                <p class="text-xl text-gray-600 mb-8">Can't find the answer you're looking for? Our support team is here to help.</p>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-lg">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="message-circle" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Live Chat</h3>
                        <p class="text-gray-600 mb-4">Chat with our support team in real-time</p>
                        <button class="text-blue-600 hover:text-blue-700 font-medium">Start Chat</button>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-lg">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="phone" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Phone Support</h3>
                        <p class="text-gray-600 mb-4">Call us for immediate assistance</p>
                        <a href="tel:+1-800-123-4567" class="text-green-600 hover:text-green-700 font-medium">+1-800-123-4567</a>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-lg">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="mail" class="w-8 h-8 text-orange-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 mb-2">Email Support</h3>
                        <p class="text-gray-600 mb-4">Send us your questions</p>
                        <a href="mailto:support@gnctravel.com" class="text-orange-600 hover:text-orange-700 font-medium">support@gnctravel.com</a>
                    </div>
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

    // Category switching
    const categoryBtns = document.querySelectorAll('.category-btn');
    const faqCategories = document.querySelectorAll('.faq-category');

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active button
            categoryBtns.forEach(b => b.classList.remove('active', 'bg-blue-100', 'border-blue-300'));
            this.classList.add('active', 'bg-blue-100', 'border-blue-300');
            
            // Show corresponding category
            faqCategories.forEach(cat => {
                if (cat.dataset.category === category) {
                    cat.classList.remove('hidden');
                } else {
                    cat.classList.add('hidden');
                }
            });
        });
    });

    // Search functionality
    const searchInput = document.getElementById('faqSearch');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const faqItems = document.querySelectorAll('.faq-item');
        
        if (searchTerm === '') {
            // Show all categories
            faqCategories.forEach(cat => cat.classList.add('hidden'));
            document.querySelector('.faq-category[data-category="general"]').classList.remove('hidden');
            faqItems.forEach(item => item.style.display = 'block');
        } else {
            // Hide all categories first
            faqCategories.forEach(cat => cat.classList.add('hidden'));
            
            let hasResults = false;
            faqItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = 'block';
                    item.closest('.faq-category').classList.remove('hidden');
                    hasResults = true;
                } else {
                    item.style.display = 'none';
                }
            });
        }
    });
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

.category-btn.active {
    border: 2px solid #3b82f6;
    background-color: #dbeafe;
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