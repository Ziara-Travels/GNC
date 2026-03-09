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
                    Customer <span class="text-gradient bg-gradient-to-r from-amber-400 to-orange-500 bg-clip-text text-transparent">Support</span>
                </h1>
                <p class="text-xl md:text-2xl text-slate-300 mb-8 max-w-2xl mx-auto">
                    Get help with your bookings and travel needs - We're here 24/7
                </p>
                <div class="flex justify-center">
                    <div class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                        <i data-lucide="headphones" class="w-5 h-5 text-amber-400 mr-2"></i>
                        <span class="text-white font-medium">Available 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Help Section -->
    <section class="py-20 bg-gradient-to-br from-slate-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-blue-100 rounded-full mb-6">
                    <i data-lucide="zap" class="w-4 h-4 text-blue-600 mr-2"></i>
                    <span class="text-blue-700 font-medium text-sm">Quick Help</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    Get Instant Support
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Choose the fastest way to get help with your travel needs
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Live Chat -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="message-circle" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Live Chat</h3>
                        <p class="text-slate-600 text-sm mb-4">Chat with our support team in real-time</p>
                        <button class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                            Start Chat
                        </button>
                    </div>
                </div>

                <!-- Email Support -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="mail" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Email Support</h3>
                        <p class="text-slate-600 text-sm mb-4">Send us a detailed message</p>
                        <a href="#contact-form" class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                            Send Email
                        </a>
                    </div>
                </div>

                <!-- FAQ -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="help-circle" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">FAQ</h3>
                        <p class="text-slate-600 text-sm mb-4">Find answers to common questions</p>
                        <a href="{{ route('help') }}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                            Browse FAQ
                        </a>
                    </div>
                </div>

                <!-- Phone Support -->
                <div class="group hover:scale-105 transition-transform duration-300">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-slate-100 hover:shadow-xl transition-all duration-300">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="phone" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Phone Support</h3>
                        <p class="text-slate-600 text-sm mb-4">Speak directly with our team</p>
                        <a href="tel:+254700123456" class="block w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-center">
                            Call Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section id="contact-form" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-amber-100 rounded-full mb-6">
                    <i data-lucide="send" class="w-4 h-4 text-amber-600 mr-2"></i>
                    <span class="text-amber-700 font-medium text-sm">Send Message</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">
                    Get in Touch
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Send us a message and we'll get back to you as soon as possible
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 p-8">
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

                <form action="{{ route('contactus.submit') }}" method="POST" class="space-y-6">
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

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                Phone Number (Optional)
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i data-lucide="tag" class="w-4 h-4 inline mr-1"></i>
                                Subject
                            </label>
                            <select name="subject" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200">
                                <option value="">Select a subject</option>
                                <option value="booking-inquiry" {{ old('subject') == 'booking-inquiry' ? 'selected' : '' }}>Booking Inquiry</option>
                                <option value="technical-support" {{ old('subject') == 'technical-support' ? 'selected' : '' }}>Technical Support</option>
                                <option value="payment-issue" {{ old('subject') == 'payment-issue' ? 'selected' : '' }}>Payment Issue</option>
                                <option value="flight-change" {{ old('subject') == 'flight-change' ? 'selected' : '' }}>Flight Change</option>
                                <option value="refund-request" {{ old('subject') == 'refund-request' ? 'selected' : '' }}>Refund Request</option>
                                <option value="general-inquiry" {{ old('subject') == 'general-inquiry' ? 'selected' : '' }}>General Inquiry</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i data-lucide="message-square" class="w-4 h-4 inline mr-1"></i>
                            Message
                        </label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200"
                                  placeholder="Please provide as much detail as possible about your inquiry...">{{ old('message') }}</textarea>
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

    <!-- Contact Information -->
    <section class="py-20 bg-gradient-to-br from-slate-800 to-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Other Ways to Reach Us
                </h2>
                <p class="text-slate-300 text-lg">
                    Choose the method that works best for you
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Phone -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="phone" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Phone Support</h3>
                    <p class="text-slate-300 mb-4">Available 24/7 for urgent matters</p>
                    <a href="tel:+254700123456" class="text-green-400 hover:text-green-300 font-medium">
                        +254 700 123 456
                    </a>
                </div>

                <!-- Email -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="mail" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Email Support</h3>
                    <p class="text-slate-300 mb-4">We'll respond within 24 hours</p>
                    <a href="mailto:support@ziara.com" class="text-blue-400 hover:text-blue-300 font-medium">
                        support@ziara.com
                    </a>
                </div>

                <!-- WhatsApp -->
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="message-circle" class="w-10 h-10 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">WhatsApp</h3>
                    <p class="text-slate-300 mb-4">Quick responses via WhatsApp</p>
                    <a href="https://wa.me/254700123456" class="text-green-400 hover:text-green-300 font-medium">
                        +254 700 123 456
                    </a>
                </div>
            </div>
        </div>
    </section>
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