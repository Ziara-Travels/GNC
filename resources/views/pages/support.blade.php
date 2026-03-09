@extends('layouts.main')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-20">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center px-4 py-2 bg-blue-500/20 rounded-full mb-6">
            <i data-lucide="headphones" class="w-5 h-5 text-blue-400 mr-2"></i>
            <span class="text-blue-300 font-medium">24/7 Support</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Customer Support</h1>
        <p class="text-xl text-slate-300 mb-8 max-w-2xl mx-auto">
            Get help with your bookings and travel needs. We're here to assist you every step of the way.
        </p>
    </div>
</div>

<!-- Support Options Section -->
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-slate-800 mb-4">How Can We Help You?</h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                Choose the support option that works best for you
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            <!-- Live Chat -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-blue-200">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="message-circle" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4">Live Chat</h3>
                <p class="text-slate-600 mb-6">
                    Chat with our support team in real-time for immediate assistance.
                </p>
                <button onclick="openLiveChat()" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Start Live Chat
                </button>
            </div>

            <!-- WhatsApp Support -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-green-200">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="phone" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4">WhatsApp</h3>
                <p class="text-slate-600 mb-6">
                    Get quick support through WhatsApp messaging.
                </p>
                <a href="https://wa.me/254700123456" target="_blank" class="w-full bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 transition-colors font-medium inline-block text-center">
                    Chat on WhatsApp
                </a>
            </div>

            <!-- AI Assistant -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-purple-200">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="bot" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4">AI Assistant</h3>
                <p class="text-slate-600 mb-6">
                    Get instant answers from our AI-powered assistant.
                </p>
                <button onclick="openAIAssistant()" class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors font-medium">
                    Ask AI Assistant
                </button>
            </div>

            <!-- Phone Support -->
            <div class="group bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 hover:border-orange-200">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="phone-call" class="w-8 h-8 text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4">Phone Support</h3>
                <p class="text-slate-600 mb-6">
                    Call our support team for immediate assistance.
                </p>
                <a href="tel:+254700123456" class="w-full bg-orange-600 text-white py-3 px-6 rounded-lg hover:bg-orange-700 transition-colors font-medium inline-block text-center">
                    Call Now
                </a>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <div class="text-center p-6 bg-slate-50 rounded-xl">
                <i data-lucide="phone" class="w-8 h-8 text-blue-600 mx-auto mb-4"></i>
                <h3 class="font-semibold text-slate-800 mb-2">Phone</h3>
                <p class="text-slate-600">+254 700 123 456</p>
                <p class="text-slate-500 text-sm mt-1">Available 24/7</p>
            </div>
            
            <div class="text-center p-6 bg-slate-50 rounded-xl">
                <i data-lucide="mail" class="w-8 h-8 text-blue-600 mx-auto mb-4"></i>
                <h3 class="font-semibold text-slate-800 mb-2">Email</h3>
                <p class="text-slate-600">support@ziara.com</p>
                <p class="text-slate-500 text-sm mt-1">Response within 2 hours</p>
            </div>
            
            <div class="text-center p-6 bg-slate-50 rounded-xl">
                <i data-lucide="map-pin" class="w-8 h-8 text-blue-600 mx-auto mb-4"></i>
                <h3 class="font-semibold text-slate-800 mb-2">Office</h3>
                <p class="text-slate-600">Nairobi, Kenya</p>
                <p class="text-slate-500 text-sm mt-1">Mon-Fri: 9 AM - 6 PM EAT</p>
            </div>
        </div>

        <!-- Forms Section -->
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100">
                <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="mail" class="w-6 h-6 mr-3 text-blue-600"></i>
                    Contact Us
                </h3>
                
                <form action="{{ route('support.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-slate-700 mb-2">Subject</label>
                            <select id="subject" name="subject" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select a subject</option>
                                <option value="booking_inquiry">Booking Inquiry</option>
                                <option value="flight_issue">Flight Issue</option>
                                <option value="hotel_issue">Hotel Issue</option>
                                <option value="payment_issue">Payment Issue</option>
                                <option value="refund_request">Refund Request</option>
                                <option value="technical_support">Technical Support</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-slate-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="5" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Please describe your issue or inquiry..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Feedback Form -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-slate-100">
                <h3 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="star" class="w-6 h-6 mr-3 text-amber-600"></i>
                    Feedback
                </h3>
                
                <form action="{{ route('support.feedback') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="feedback_name" class="block text-sm font-medium text-slate-700 mb-2">Name</label>
                            <input type="text" id="feedback_name" name="name" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="feedback_email" class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                            <input type="email" id="feedback_email" name="email" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="service_used" class="block text-sm font-medium text-slate-700 mb-2">Service Used</label>
                        <select id="service_used" name="service" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a service</option>
                            <option value="flight_booking">Flight Booking</option>
                            <option value="hotel_booking">Hotel Booking</option>
                            <option value="flight_search">Flight Search</option>
                            <option value="trip_planning">Trip Planning</option>
                            <option value="customer_support">Customer Support</option>
                            <option value="website">Website Experience</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-700">Rate your experience</label>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                @for ($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer p-1">
                                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" {{ $i == 5 ? 'checked' : '' }}>
                                    <i data-lucide="star" class="w-8 h-8 text-slate-300 peer-checked:text-amber-500 hover:text-amber-400 transition-colors"></i>
                                </label>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="feedback_message" class="block text-sm font-medium text-slate-700 mb-2">Your Feedback</label>
                        <textarea id="feedback_message" name="feedback" rows="5" required class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Please share your experience..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-amber-600 text-white py-3 px-6 rounded-lg hover:bg-amber-700 transition-colors font-medium flex items-center justify-center">
                        <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                        Submit Feedback
                    </button>
                </form>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="mt-20">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full mb-4">
                    <i data-lucide="help-circle" class="w-5 h-5 text-blue-600 mr-2"></i>
                    <span class="text-blue-700 font-medium text-sm">Frequently Asked Questions</span>
                </div>
                <h2 class="text-3xl font-bold text-slate-800 mb-4">Common Questions</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Find quick answers to the most common questions about our services
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto space-y-4">
                <!-- FAQ Item 1 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-5 bg-white hover:bg-slate-50 transition-colors" onclick="toggleFAQ(this)">
                        <span class="font-medium text-slate-800">How do I cancel my flight booking?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500 transition-transform"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <div class="p-5 bg-slate-50 border-t border-slate-200">
                            <p class="text-slate-700">To cancel your flight booking, log in to your account, go to "My Bookings," select the flight you want to cancel, and click on the "Cancel" button. Please note that cancellation fees may apply according to the airline's policy.</p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-5 bg-white hover:bg-slate-50 transition-colors" onclick="toggleFAQ(this)">
                        <span class="font-medium text-slate-800">What payment methods do you accept?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500 transition-transform"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <div class="p-5 bg-slate-50 border-t border-slate-200">
                            <p class="text-slate-700">We accept various payment methods including credit/debit cards (Visa, MasterCard, American Express), M-Pesa mobile money, bank transfers, and selected digital wallets. All payments are processed securely.</p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-5 bg-white hover:bg-slate-50 transition-colors" onclick="toggleFAQ(this)">
                        <span class="font-medium text-slate-800">How do I modify my hotel reservation?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500 transition-transform"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <div class="p-5 bg-slate-50 border-t border-slate-200">
                            <p class="text-slate-700">To modify your hotel reservation, log in to your account and go to "My Bookings." Select the hotel reservation you want to change and click on "Modify Reservation." You can then update your dates, room type, or other details, subject to the hotel's modification policy.</p>
                        </div>
                    </div>
                </div>
                
                <!-- FAQ Item 4 -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <button class="faq-toggle w-full flex items-center justify-between p-5 bg-white hover:bg-slate-50 transition-colors" onclick="toggleFAQ(this)">
                        <span class="font-medium text-slate-800">How long does it take to process a refund?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500 transition-transform"></i>
                    </button>
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-300">
                        <div class="p-5 bg-slate-50 border-t border-slate-200">
                            <p class="text-slate-700">Refund processing times vary depending on your payment method. For credit card payments, it typically takes 7-14 business days. M-Pesa refunds usually process within 48-72 hours. Bank transfers may take up to 14 business days to reflect in your account.</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-8">
                    <a href="{{ route('support.help') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        View all FAQs
                        <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI Chatbot -->
<div id="ai-assistant-container" class="fixed bottom-8 right-8 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-80 sm:w-96 max-h-[600px] flex flex-col border border-slate-200">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-t-2xl p-4 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="bot" class="w-6 h-6 text-white"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-white font-medium">AI Travel Assistant</h3>
                    <p class="text-white/80 text-xs">Online</p>
                </div>
            </div>
            <button onclick="closeAIAssistant()" class="text-white/80 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <div id="ai-chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4" style="max-height: 400px;">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="bot" class="w-5 h-5 text-white"></i>
                </div>
                <div class="ml-3 bg-slate-100 rounded-lg rounded-tl-none p-3 max-w-[80%]">
                    <p class="text-slate-800">Hello! I'm your AI travel assistant. How can I help you today? You can ask about flights, hotels, destinations, or travel tips.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 border-t border-slate-200">
            <form id="ai-chat-form" class="flex items-center space-x-2">
                <input id="ai-chat-input" type="text" placeholder="Type your question..." class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <button type="submit" class="bg-purple-600 text-white p-2 rounded-lg hover:bg-purple-700 transition-colors">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- WhatsApp Floating Button -->
<div class="fixed bottom-8 right-8 z-40" id="whatsapp-button">
    <a href="https://wa.me/254700123456" target="_blank" class="bg-green-500 hover:bg-green-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:shadow-2xl transition-all duration-300">
        <i data-lucide="message-circle" class="w-8 h-8"></i>
    </a>
</div>

<!-- Live Chat Widget -->
<div id="live-chat-widget" class="fixed bottom-8 right-8 z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-80 sm:w-96 max-h-[600px] flex flex-col border border-slate-200">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-2xl p-4 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <i data-lucide="users" class="w-6 h-6 text-white"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-white font-medium">Live Support</h3>
                    <p class="text-white/80 text-xs">Online • Chat with our team</p>
                </div>
            </div>
            <button onclick="closeLiveChat()" class="text-white/80 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <div id="live-chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4" style="max-height: 400px;">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user" class="w-5 h-5 text-white"></i>
                </div>
                <div class="ml-3 bg-slate-100 rounded-lg rounded-tl-none p-3 max-w-[80%]">
                    <p class="text-xs text-slate-500 mb-1">Support Agent • John</p>
                    <p class="text-slate-800">Hello! Welcome to Ziara Travel support. How can I assist you today?</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 border-t border-slate-200">
            <form id="live-chat-form" class="flex items-center space-x-2">
                <input id="live-chat-input" type="text" placeholder="Type your message..." class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="send" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Cookie Consent Banner -->
<div id="cookie-consent" class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t border-slate-200 p-4 z-50 transform transition-transform duration-300 translate-y-full">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-start sm:items-center">
            <i data-lucide="cookie" class="w-8 h-8 text-amber-500 mr-3 flex-shrink-0"></i>
            <p class="text-slate-700 text-sm">
                We use cookies to enhance your experience. By continuing to visit this site, you agree to our use of cookies.
                <a href="{{ route('support.privacy-policy') }}" class="text-blue-600 hover:text-blue-800 underline">Learn more</a>
            </p>
        </div>
        <div class="flex gap-2">
            <button id="cookie-accept" class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition-colors text-sm">
                Accept All
            </button>
            <button id="cookie-settings" class="bg-slate-200 text-slate-800 px-4 py-2 rounded-lg hover:bg-slate-300 transition-colors text-sm">
                Cookie Settings
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Show cookie consent on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const cookieConsent = document.getElementById('cookie-consent');
            cookieConsent.classList.remove('translate-y-full');
        }, 1000);
        
        document.getElementById('cookie-accept').addEventListener('click', function() {
            document.getElementById('cookie-consent').classList.add('translate-y-full');
            // Set cookie accepted
            localStorage.setItem('cookie_consent', 'accepted');
        });
        
        document.getElementById('cookie-settings').addEventListener('click', function() {
            // Redirect to cookie settings page or show modal
            document.getElementById('cookie-consent').classList.add('translate-y-full');
        });
        
        // Check if cookie consent is already given
        if (localStorage.getItem('cookie_consent') === 'accepted') {
            document.getElementById('cookie-consent').classList.add('hidden');
        }
    });

    // Live Chat functionality
    function openLiveChat() {
        document.getElementById('whatsapp-button').classList.add('hidden');
        document.getElementById('ai-assistant-container').classList.add('hidden');
        document.getElementById('live-chat-widget').classList.remove('hidden');
    }
    
    function closeLiveChat() {
        document.getElementById('live-chat-widget').classList.add('hidden');
        document.getElementById('whatsapp-button').classList.remove('hidden');
    }
    
    document.getElementById('live-chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const input = document.getElementById('live-chat-input');
        const message = input.value.trim();
        
        if (message) {
            // Add user message
            const messagesContainer = document.getElementById('live-chat-messages');
            messagesContainer.innerHTML += `
                <div class="flex items-start flex-row-reverse">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                    </div>
                    <div class="mr-3 bg-blue-600 rounded-lg rounded-tr-none p-3 max-w-[80%]">
                        <p class="text-white">${message}</p>
                    </div>
                </div>
            `;
            
            // Clear input
            input.value = '';
            
            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            // Simulate agent typing
            setTimeout(() => {
                messagesContainer.innerHTML += `
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="user" class="w-5 h-5 text-white"></i>
                        </div>
                        <div class="ml-3 bg-slate-100 rounded-lg p-3">
                            <div class="typing-indicator">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                `;
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Re-initialize icons for the new content
                lucide.createIcons();
            }, 500);
            
            // Simulate agent response
            setTimeout(() => {
                const responses = [
                    "I'd be happy to help with that. Could you please provide more details?",
                    "Thank you for your message. Let me check that for you.",
                    "I understand your concern. We'll resolve this as quickly as possible.",
                    "That's a great question! Here's what you need to know...",
                    "I'm looking into this for you now. Just a moment please."
                ];
                const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                
                // Remove typing indicator
                const typingIndicator = messagesContainer.querySelector('.typing-indicator').parentNode.parentNode;
                messagesContainer.removeChild(typingIndicator);
                
                // Add agent response
                messagesContainer.innerHTML += `
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="user" class="w-5 h-5 text-white"></i>
                        </div>
                        <div class="ml-3 bg-slate-100 rounded-lg rounded-tl-none p-3 max-w-[80%]">
                            <p class="text-xs text-slate-500 mb-1">Support Agent • John</p>
                            <p class="text-slate-800">${randomResponse}</p>
                        </div>
                    </div>
                `;
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Re-initialize icons for the new content
                lucide.createIcons();
            }, 2000);
        }
    });
    
    // AI Assistant functionality
    function openAIAssistant() {
        document.getElementById('whatsapp-button').classList.add('hidden');
        document.getElementById('live-chat-widget').classList.add('hidden');
        document.getElementById('ai-assistant-container').classList.remove('hidden');
    }
    
    function closeAIAssistant() {
        document.getElementById('ai-assistant-container').classList.add('hidden');
        document.getElementById('whatsapp-button').classList.remove('hidden');
    }
    
    document.getElementById('ai-chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const input = document.getElementById('ai-chat-input');
        const message = input.value.trim();
        
        if (message) {
            // Add user message
            const messagesContainer = document.getElementById('ai-chat-messages');
            messagesContainer.innerHTML += `
                <div class="flex items-start flex-row-reverse">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user" class="w-5 h-5 text-purple-600"></i>
                    </div>
                    <div class="mr-3 bg-purple-600 rounded-lg rounded-tr-none p-3 max-w-[80%]">
                        <p class="text-white">${message}</p>
                    </div>
                </div>
            `;
            
            // Clear input
            input.value = '';
            
            // Scroll to bottom
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            // Simulate AI typing
            setTimeout(() => {
                messagesContainer.innerHTML += `
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="bot" class="w-5 h-5 text-white"></i>
                        </div>
                        <div class="ml-3 bg-slate-100 rounded-lg p-3">
                            <div class="typing-indicator">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                `;
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Re-initialize icons for the new content
                lucide.createIcons();
            }, 500);
            
            // Simulate AI response based on keywords
            setTimeout(() => {
                let aiResponse = "";
                
                if (message.toLowerCase().includes('flight')) {
                    aiResponse = "I can help you with flight bookings! You can search for flights on our website by entering your departure and destination cities, travel dates, and passenger information. Would you like me to guide you through the process?";
                } else if (message.toLowerCase().includes('hotel')) {
                    aiResponse = "Looking for hotel recommendations? We have a wide selection of accommodations from budget-friendly to luxury options. You can filter by amenities, location, and price on our hotel search page.";
                } else if (message.toLowerCase().includes('refund') || message.toLowerCase().includes('cancel')) {
                    aiResponse = "For cancellations and refunds, please go to 'My Bookings' in your account and select the booking you wish to cancel. Refund policies vary by airline and hotel. Would you like more specific information?";
                } else if (message.toLowerCase().includes('price') || message.toLowerCase().includes('cost')) {
                    aiResponse = "Prices vary based on travel dates, class of service, and availability. Our best price guarantee ensures you get the most competitive rates. I recommend booking in advance for the best deals!";
                } else {
                    aiResponse = "Thank you for your question. I can help with flight and hotel bookings, travel recommendations, booking modifications, and general travel inquiries. Could you provide more details about what you need assistance with?";
                }
                
                // Remove typing indicator
                const typingIndicator = messagesContainer.querySelector('.typing-indicator').parentNode.parentNode;
                messagesContainer.removeChild(typingIndicator);
                
                // Add AI response
                messagesContainer.innerHTML += `
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="bot" class="w-5 h-5 text-white"></i>
                        </div>
                        <div class="ml-3 bg-slate-100 rounded-lg rounded-tl-none p-3 max-w-[80%]">
                            <p class="text-slate-800">${aiResponse}</p>
                        </div>
                    </div>
                `;
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Re-initialize icons for the new content
                lucide.createIcons();
            }, 2000);
        }
    });
    
    // FAQ Accordion
    function toggleFAQ(element) {
        const content = element.nextElementSibling;
        const icon = element.querySelector('[data-lucide="chevron-down"]');
        
        // Toggle the current FAQ
        if (content.style.maxHeight) {
            content.style.maxHeight = null;
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.style.transform = 'rotate(180deg)';
        }
    }
</script>
<style>
    /* Typing indicator animation */
    .typing-indicator {
        display: flex;
        align-items: center;
    }
    
    .typing-indicator span {
        height: 8px;
        width: 8px;
        background: #606060;
        display: block;
        border-radius: 50%;
        margin-right: 3px;
        animation: typing 1s infinite ease-in-out;
    }
    
    .typing-indicator span:nth-child(1) {
        animation-delay: 0s;
    }
    
    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
        margin-right: 0;
    }
    
    @keyframes typing {
        0% { transform: scale(1); }
        50% { transform: scale(1.5); }
        100% { transform: scale(1); }
    }
</style>
@endpush