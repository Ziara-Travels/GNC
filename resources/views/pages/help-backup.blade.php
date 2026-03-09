@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-[50vh] flex items-center justify-center overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
        
        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                    Help & <span class="text-gradient bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">FAQ</span>
                </h1>
                <p class="text-xl md:text-2xl text-slate-300 mb-8 max-w-2xl mx-auto">
                    Find answers to common questions about flight booking
                </p>
                <div class="flex justify-center">
                    <div class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <i data-lucide="search" class="w-5 h-5 text-amber-400 mr-2"></i>
                        <span class="text-white font-medium">Search for answers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <section class="py-12 bg-gradient-to-br from-slate-50 to-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                </div>
                <input type="text" id="faq-search" placeholder="Search FAQs..." 
                       class="w-full pl-10 pr-4 py-4 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 text-lg">
            </div>
        </div>
    </section>

    <!-- FAQ Categories -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-slate-800 mb-4">Browse by Category</h2>
                <p class="text-slate-600 text-lg">Find answers quickly by selecting a category</p>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button class="category-filter active px-6 py-3 bg-amber-500 text-white rounded-full font-medium hover:bg-amber-600 transition-colors duration-200" data-category="all">
                    All Questions
                </button>
                <button class="category-filter px-6 py-3 bg-slate-100 text-slate-700 rounded-full font-medium hover:bg-slate-200 transition-colors duration-200" data-category="booking">
                    Booking
                </button>
                <button class="category-filter px-6 py-3 bg-slate-100 text-slate-700 rounded-full font-medium hover:bg-slate-200 transition-colors duration-200" data-category="payment">
                    Payment
                </button>
                <button class="category-filter px-6 py-3 bg-slate-100 text-slate-700 rounded-full font-medium hover:bg-slate-200 transition-colors duration-200" data-category="changes">
                    Changes & Cancellations
                </button>
                <button class="category-filter px-6 py-3 bg-slate-100 text-slate-700 rounded-full font-medium hover:bg-slate-200 transition-colors duration-200" data-category="travel">
                    Travel Requirements
                </button>
                <button class="category-filter px-6 py-3 bg-slate-100 text-slate-700 rounded-full font-medium hover:bg-slate-200 transition-colors duration-200" data-category="account">
                    Account
                </button>
            </div>

            <!-- FAQ Items -->
            <div class="max-w-4xl mx-auto space-y-4" id="faq-container">
                <!-- Booking FAQs -->
                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="booking">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="plane" class="w-5 h-5 text-amber-500 mr-3"></i>
                            <span class="font-medium text-slate-800">How do I book a flight?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            To book a flight, simply enter your departure and arrival cities, select your travel dates, choose the number of passengers, and click "Search Flights". You'll see a list of available flights with prices. Select your preferred flight, fill in passenger details, and complete the payment process.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="booking">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="calendar" class="w-5 h-5 text-blue-500 mr-3"></i>
                            <span class="font-medium text-slate-800">Can I book flights for multiple passengers?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            Yes, you can book flights for up to 9 passengers in a single booking. During the search process, simply adjust the passenger count to include adults, children, and infants. You'll need to provide details for each passenger during the booking process.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="booking">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="clock" class="w-5 h-5 text-green-500 mr-3"></i>
                            <span class="font-medium text-slate-800">How far in advance can I book a flight?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            You can book flights up to 11 months in advance. Airlines typically release their schedules and fares about 10-11 months before departure. For the best prices, we recommend booking domestic flights 1-2 months in advance and international flights 2-3 months in advance.
                        </p>
                    </div>
                </div>

                <!-- Payment FAQs -->
                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="payment">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="credit-card" class="w-5 h-5 text-purple-500 mr-3"></i>
                            <span class="font-medium text-slate-800">What payment methods do you accept?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            We accept all major credit cards (Visa, Mastercard, American Express), debit cards, PayPal, and bank transfers. All payments are processed securely using industry-standard encryption to protect your financial information.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="payment">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="shield" class="w-5 h-5 text-red-500 mr-3"></i>
                            <span class="font-medium text-slate-800">Is my payment information secure?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            Yes, absolutely. We use SSL encryption and PCI DSS compliance to ensure your payment information is completely secure. We never store your credit card details on our servers, and all transactions are processed through secure payment gateways.
                        </p>
                    </div>
                </div>

                <!-- Changes & Cancellations FAQs -->
                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="changes">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="edit" class="w-5 h-5 text-amber-500 mr-3"></i>
                            <span class="font-medium text-slate-800">Can I change my flight after booking?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            Yes, you can change your flight subject to airline policies and availability. Changes may incur fees and fare differences. To change your flight, log into your account, go to "Manage Booking", and select "Change Flight". You can also contact our customer service team for assistance.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="changes">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="x-circle" class="w-5 h-5 text-red-500 mr-3"></i>
                            <span class="font-medium text-slate-800">What is your cancellation policy?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            Cancellation policies vary by airline and fare type. Most tickets can be cancelled within 24 hours of booking for a full refund. After that, cancellation fees may apply. Refundable tickets can be cancelled anytime with minimal fees, while non-refundable tickets may only offer credit for future travel.
                        </p>
                    </div>
                </div>

                <!-- Travel Requirements FAQs -->
                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="travel">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 text-blue-500 mr-3"></i>
                            <span class="font-medium text-slate-800">What documents do I need for travel?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            For domestic flights, you need a valid government-issued photo ID (driver's license, passport, or national ID). For international flights, you need a valid passport and may require a visa depending on your destination. Check with the embassy or consulate of your destination country for specific requirements.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="travel">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="luggage" class="w-5 h-5 text-green-500 mr-3"></i>
                            <span class="font-medium text-slate-800">What are the baggage allowances?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            Baggage allowances vary by airline and ticket type. Most airlines allow one carry-on bag and one personal item for free. Checked baggage policies differ - some airlines include one free checked bag, while others charge fees. Weight limits are typically 23kg (50lbs) for checked bags and 7kg (15lbs) for carry-on.
                        </p>
                    </div>
                </div>

                <!-- Account FAQs -->
                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="account">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-5 h-5 text-purple-500 mr-3"></i>
                            <span class="font-medium text-slate-800">Do I need to create an account to book?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            While you can book as a guest, creating an account offers many benefits including faster checkout, booking history, easy rebooking, exclusive deals, and the ability to manage your bookings online. It only takes a minute to sign up and will save you time on future bookings.
                        </p>
                    </div>
                </div>

                <div class="faq-item bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200" data-category="account">
                    <button class="faq-question w-full px-6 py-4 text-left flex items-center justify-between hover:bg-slate-50 transition-colors duration-200">
                        <div class="flex items-center">
                            <i data-lucide="settings" class="w-5 h-5 text-slate-500 mr-3"></i>
                            <span class="font-medium text-slate-800">How do I manage my bookings?</span>
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400 transition-transform duration-200"></i>
                    </button>
                    <div class="faq-answer hidden px-6 pb-4">
                        <p class="text-slate-600 leading-relaxed">
                            Log into your account and go to "My Bookings" to view, modify, or cancel your reservations. You can also check in online, select seats, add special services, and download your boarding passes. If you booked as a guest, use the "Manage Booking" option with your booking reference and email.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i data-lucide="message-circle" class="w-4 h-4 text-amber-600 mr-2"></i>
                    <span class="text-amber-700 font-medium text-sm">Still Need Help?</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    Contact Our Support Team
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Can't find what you're looking for? Our friendly support team is here to help you with any questions.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-2"></i>
                            <span class="text-green-700 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-2"></i>
                            <span class="text-red-700 font-medium">Please correct the following errors:</span>
                        </div>
                        <ul class="text-red-600 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('help.contact') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="user" class="w-4 h-4 inline mr-1"></i>
                                Full Name
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="mail" class="w-4 h-4 inline mr-1"></i>
                                Email Address
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i data-lucide="tag" class="w-4 h-4 inline mr-1"></i>
                            Subject
                        </label>
                        <select name="subject" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                            <option value="">Select a subject</option>
                            <option value="booking-help" {{ old('subject') == 'booking-help' ? 'selected' : '' }}>Booking Help</option>
                            <option value="payment-issue" {{ old('subject') == 'payment-issue' ? 'selected' : '' }}>Payment Issue</option>
                            <option value="flight-change" {{ old('subject') == 'flight-change' ? 'selected' : '' }}>Flight Change</option>
                            <option value="cancellation" {{ old('subject') == 'cancellation' ? 'selected' : '' }}>Cancellation</option>
                            <option value="refund" {{ old('subject') == 'refund' ? 'selected' : '' }}>Refund Request</option>
                            <option value="technical-issue" {{ old('subject') == 'technical-issue' ? 'selected' : '' }}>Technical Issue</option>
                            <option value="general-inquiry" {{ old('subject') == 'general-inquiry' ? 'selected' : '' }}>General Inquiry</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i data-lucide="message-square" class="w-4 h-4 inline mr-1"></i>
                            Message
                        </label>
                        <textarea name="message" rows="5" required
                                  class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200"
                                  placeholder="Please provide as much detail as possible about your question or issue...">{{ old('message') }}</textarea>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit"
                                class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-700 focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Quick Contact Section -->
    <section class="py-16 bg-gradient-to-br from-slate-800 to-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">Need Immediate Help?</h2>
                <p class="text-slate-300 text-lg">Get in touch with us right away</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Phone -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="phone" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Call Us</h3>
                    <p class="text-slate-300 mb-4">Available 24/7 for urgent matters</p>
                    <a href="tel:+254700123456" class="text-green-400 hover:text-green-300 font-medium text-lg">
                        +254 700 123 456
                    </a>
                </div>

                <!-- Email -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="mail" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Email Us</h3>
                    <p class="text-slate-300 mb-4">Response within 24 hours</p>
                    <a href="mailto:help@ziara.com" class="text-blue-400 hover:text-blue-300 font-medium text-lg">
                        help@ziara.com
                    </a>
                </div>

                <!-- Chat -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="message-circle" class="w-8 h-8 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Live Chat</h3>
                    <p class="text-slate-300 mb-4">Chat with our support team</p>
                    <button class="text-purple-400 hover:text-purple-300 font-medium text-lg">
                        Start Chat
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();

    // FAQ Toggle Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');
        const categoryFilters = document.querySelectorAll('.category-filter');
        const searchInput = document.getElementById('faq-search');

        // Toggle FAQ answers
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');
            const icon = question.querySelector('[data-lucide="chevron-down"]');

            question.addEventListener('click', () => {
                const isOpen = !answer.classList.contains('hidden');
                
                // Close all other FAQ items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item) {
                        const otherAnswer = otherItem.querySelector('.faq-answer');
                        const otherIcon = otherItem.querySelector('[data-lucide="chevron-down"]');
                        otherAnswer.classList.add('hidden');
                        otherIcon.style.transform = 'rotate(0deg)';
                    }
                });

                // Toggle current item
                if (isOpen) {
                    answer.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                } else {
                    answer.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                }
            });
        });

        // Category filtering
        categoryFilters.forEach(filter => {
            filter.addEventListener('click', () => {
                const category = filter.dataset.category;
                
                // Update active filter
                categoryFilters.forEach(f => {
                    f.classList.remove('active', 'bg-amber-500', 'text-white');
                    f.classList.add('bg-slate-100', 'text-slate-700');
                });
                filter.classList.add('active', 'bg-amber-500', 'text-white');
                filter.classList.remove('bg-slate-100', 'text-slate-700');

                // Show/hide FAQ items
                faqItems.forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
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