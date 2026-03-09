@extends('layouts.main')

@section('title', 'Live Chat Support - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100">
    <!-- Hero Section -->
    <section class="relative py-16 bg-gradient-to-r from-green-600 to-emerald-700 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 animate-fade-in">
                    Live Chat Support
                </h1>
                <p class="text-xl text-green-100 mb-8 animate-slide-up">
                    Get instant help from our travel experts - available 24/7
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 inline-block">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse mr-2"></div>
                        <span class="text-lg">Support agents online now</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Chat Interface -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Chat Header -->
                    <div class="bg-gradient-to-r from-green-600 to-emerald-700 p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mr-4">
                                    <i data-lucide="headphones" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Ziara Travel Support</h3>
                                    <p class="text-green-100 text-sm">Typically replies within minutes</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-400 rounded-full mr-2"></div>
                                <span class="text-sm">Online</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="h-96 overflow-y-auto p-6 bg-gray-50" id="chatMessages">
                        <!-- Welcome Message -->
                        <div class="flex items-start mb-6">
                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                <i data-lucide="bot" class="w-5 h-5 text-white"></i>
                            </div>
                            <div class="bg-white rounded-2xl rounded-tl-lg p-4 shadow-sm max-w-md">
                                <p class="text-gray-800">Hello! Welcome to GNC Travel support. How can I help you today?</p>
                                <p class="text-xs text-gray-500 mt-2">Just now</p>
                            </div>
                        </div>

                        <!-- Quick Responses -->
                        <div class="flex flex-wrap gap-2 mb-6">
                            <button class="quick-response bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm hover:bg-green-200 transition-colors duration-200" data-message="I need help with my booking">
                                I need help with my booking
                            </button>
                            <button class="quick-response bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm hover:bg-green-200 transition-colors duration-200" data-message="I want to cancel my trip">
                                I want to cancel my trip
                            </button>
                            <button class="quick-response bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm hover:bg-green-200 transition-colors duration-200" data-message="I have a payment issue">
                                I have a payment issue
                            </button>
                            <button class="quick-response bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm hover:bg-green-200 transition-colors duration-200" data-message="Tell me about travel insurance">
                                Tell me about travel insurance
                            </button>
                        </div>
                    </div>

                    <!-- Chat Input -->
                    <div class="p-6 bg-white border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1 relative">
                                <input type="text" 
                                       id="chatInput"
                                       placeholder="Type your message..." 
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <button class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i data-lucide="paperclip" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <button id="sendMessage" class="bg-green-600 text-white p-3 rounded-2xl hover:bg-green-700 transition-colors duration-200">
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            We typically respond within 2-3 minutes during business hours
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Why Choose Live Chat?</h2>
                    <p class="text-xl text-gray-600">Get instant support when you need it most</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="clock" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">24/7 Availability</h3>
                        <p class="text-gray-600">Our support team is available round the clock to assist you with any travel-related queries.</p>
                    </div>

                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="zap" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Instant Response</h3>
                        <p class="text-gray-600">Get immediate answers to your questions without waiting on hold or for email responses.</p>
                    </div>

                    <div class="text-center p-6">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="users" class="w-8 h-8 text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Expert Support</h3>
                        <p class="text-gray-600">Chat with experienced travel specialists who can help with bookings, changes, and travel advice.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alternative Contact -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold text-gray-800 mb-8">Other Ways to Reach Us</h2>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl">
                        <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="phone" class="w-6 h-6 text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Phone Support</h3>
                        <p class="text-gray-600 mb-4">Call us directly for urgent matters</p>
                        <a href="tel:+1-800-123-4567" class="text-blue-600 font-semibold hover:text-blue-700">
                            +1 (800) 123-4567
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl">
                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="mail" class="w-6 h-6 text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Email Support</h3>
                        <p class="text-gray-600 mb-4">Send us detailed inquiries</p>
                        <a href="mailto:support@gnctravel.com" class="text-green-600 font-semibold hover:text-green-700">
                            support@gnctravel.com
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl">
                        <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="help-circle" class="w-6 h-6 text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">Help Center</h3>
                        <p class="text-gray-600 mb-4">Browse our knowledge base</p>
                        <a href="{{ route('help-center') }}" class="text-purple-600 font-semibold hover:text-purple-700">
                            Visit Help Center
                        </a>
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

    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const sendButton = document.getElementById('sendMessage');
    const quickResponses = document.querySelectorAll('.quick-response');

    // Quick response buttons
    quickResponses.forEach(button => {
        button.addEventListener('click', function() {
            const message = this.dataset.message;
            sendUserMessage(message);
            this.style.display = 'none'; // Hide used quick response
        });
    });

    // Send message function
    function sendUserMessage(message) {
        // Add user message
        const userMessageDiv = document.createElement('div');
        userMessageDiv.className = 'flex items-start mb-6 justify-end';
        userMessageDiv.innerHTML = `
            <div class="bg-green-600 text-white rounded-2xl rounded-tr-lg p-4 shadow-sm max-w-md">
                <p>${message}</p>
                <p class="text-xs text-green-100 mt-2">Just now</p>
            </div>
            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center ml-3 flex-shrink-0">
                <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
            </div>
        `;
        chatMessages.appendChild(userMessageDiv);

        // Auto-scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Simulate typing indicator
        setTimeout(() => {
            addTypingIndicator();
        }, 500);

        // Simulate bot response
        setTimeout(() => {
            removeTypingIndicator();
            addBotResponse(generateBotResponse(message));
        }, 2000);

        // Re-initialize icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Add typing indicator
    function addTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'flex items-start mb-6 typing-indicator';
        typingDiv.innerHTML = `
            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                <i data-lucide="bot" class="w-5 h-5 text-white"></i>
            </div>
            <div class="bg-white rounded-2xl rounded-tl-lg p-4 shadow-sm">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Remove typing indicator
    function removeTypingIndicator() {
        const typingIndicator = document.querySelector('.typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    // Add bot response
    function addBotResponse(message) {
        const botMessageDiv = document.createElement('div');
        botMessageDiv.className = 'flex items-start mb-6';
        botMessageDiv.innerHTML = `
            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                <i data-lucide="bot" class="w-5 h-5 text-white"></i>
            </div>
            <div class="bg-white rounded-2xl rounded-tl-lg p-4 shadow-sm max-w-md">
                <p class="text-gray-800">${message}</p>
                <p class="text-xs text-gray-500 mt-2">Just now</p>
            </div>
        `;
        chatMessages.appendChild(botMessageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Generate bot response
    function generateBotResponse(userMessage) {
        const responses = {
            'booking': "I'd be happy to help you with your booking! Could you please provide your booking reference number so I can look up the details?",
            'cancel': "I understand you'd like to cancel your trip. Let me help you with that. Can you share your booking reference number? I'll check the cancellation policy for your specific booking.",
            'payment': "I'm here to help with payment issues. Could you tell me more about the specific problem you're experiencing? Are you having trouble with a charge or need to update payment information?",
            'insurance': "Travel insurance is a great way to protect your trip! We offer comprehensive coverage including trip cancellation, medical emergencies, and baggage protection. Would you like me to provide more details about our insurance options?",
            'default': "Thank you for reaching out! I'm here to help you with any travel-related questions. Could you provide more details about what you need assistance with?"
        };

        const message = userMessage.toLowerCase();
        if (message.includes('booking') || message.includes('reservation')) {
            return responses.booking;
        } else if (message.includes('cancel')) {
            return responses.cancel;
        } else if (message.includes('payment') || message.includes('charge')) {
            return responses.payment;
        } else if (message.includes('insurance')) {
            return responses.insurance;
        } else {
            return responses.default;
        }
    }

    // Send button click
    sendButton.addEventListener('click', function() {
        const message = chatInput.value.trim();
        if (message) {
            sendUserMessage(message);
            chatInput.value = '';
        }
    });

    // Enter key to send
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const message = this.value.trim();
            if (message) {
                sendUserMessage(message);
                this.value = '';
            }
        }
    });
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

@keyframes bounce {
    0%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-6px);
    }
}

.animate-bounce {
    animation: bounce 1.4s infinite;
}
</style>
@endpush
@endsection