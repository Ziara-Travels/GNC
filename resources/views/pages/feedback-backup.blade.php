@extends('layouts.main')

@section('title', 'Feedback - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-blue-500 to-indigo-600 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Your Feedback Matters
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 animate-slide-up">
                    Help us improve your travel experience with your valuable feedback
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="flex items-center justify-center gap-4">
                        <i data-lucide="star" class="w-8 h-8 text-yellow-400"></i>
                        <span class="text-lg">Your Voice, Our Improvement</span>
                        <i data-lucide="star" class="w-8 h-8 text-yellow-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feedback Form Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-8">
                        <h2 class="text-3xl font-bold mb-4 flex items-center">
                            <i data-lucide="message-square" class="w-8 h-8 mr-3"></i>
                            Share Your Experience
                        </h2>
                        <p class="text-blue-100">
                            We value your opinion and use your feedback to enhance our services
                        </p>
                    </div>

                    <!-- Feedback Form -->
                    <div class="p-8">
                        <form action="{{ route('feedback') }}" method="POST" id="feedbackForm">
                            @csrf
                            
                            <!-- Feedback Type -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="category" class="w-4 h-4 inline-block mr-2"></i>
                                    Feedback Type
                                </label>
                                <div class="grid md:grid-cols-3 gap-4">
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors duration-200">
                                        <input type="radio" name="feedback_type" value="general" class="sr-only" required>
                                        <div class="feedback-option w-full text-center">
                                            <i data-lucide="message-circle" class="w-8 h-8 mx-auto mb-2 text-blue-500"></i>
                                            <span class="font-medium">General Feedback</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 transition-colors duration-200">
                                        <input type="radio" name="feedback_type" value="complaint" class="sr-only">
                                        <div class="feedback-option w-full text-center">
                                            <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2 text-red-500"></i>
                                            <span class="font-medium">Complaint</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500 transition-colors duration-200">
                                        <input type="radio" name="feedback_type" value="suggestion" class="sr-only">
                                        <div class="feedback-option w-full text-center">
                                            <i data-lucide="lightbulb" class="w-8 h-8 mx-auto mb-2 text-yellow-500"></i>
                                            <span class="font-medium">Suggestion</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Rating -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="star" class="w-4 h-4 inline-block mr-2"></i>
                                    Overall Rating
                                </label>
                                <div class="flex items-center gap-2">
                                    <div class="flex" id="starRating">
                                        <button type="button" class="star text-gray-300 hover:text-yellow-400 text-3xl transition-colors duration-200" data-rating="1">★</button>
                                        <button type="button" class="star text-gray-300 hover:text-yellow-400 text-3xl transition-colors duration-200" data-rating="2">★</button>
                                        <button type="button" class="star text-gray-300 hover:text-yellow-400 text-3xl transition-colors duration-200" data-rating="3">★</button>
                                        <button type="button" class="star text-gray-300 hover:text-yellow-400 text-3xl transition-colors duration-200" data-rating="4">★</button>
                                        <button type="button" class="star text-gray-300 hover:text-yellow-400 text-3xl transition-colors duration-200" data-rating="5">★</button>
                                    </div>
                                    <span id="ratingText" class="text-gray-600 ml-3"></span>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" required>
                            </div>

                            <!-- Personal Information -->
                            <div class="grid md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i data-lucide="user" class="w-4 h-4 inline-block mr-2"></i>
                                        Full Name
                                    </label>
                                    <input type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ auth()->user()->name ?? old('name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                           required>
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        <i data-lucide="mail" class="w-4 h-4 inline-block mr-2"></i>
                                        Email Address
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ auth()->user()->email ?? old('email') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                           required>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="mb-6">
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i data-lucide="bookmark" class="w-4 h-4 inline-block mr-2"></i>
                                    Subject
                                </label>
                                <input type="text" 
                                       id="subject" 
                                       name="subject" 
                                       value="{{ old('subject') }}"
                                       placeholder="Brief description of your feedback"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       required>
                            </div>

                            <!-- Feedback Message -->
                            <div class="mb-6">
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i data-lucide="message-square" class="w-4 h-4 inline-block mr-2"></i>
                                    Your Feedback
                                </label>
                                <textarea id="message" 
                                         name="message" 
                                         rows="6"
                                         placeholder="Please share your detailed feedback, suggestions, or concerns..."
                                         class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 resize-none"
                                         required>{{ old('message') }}</textarea>
                                <div class="flex justify-between mt-2">
                                    <span class="text-sm text-gray-500">Minimum 10 characters</span>
                                    <span id="charCount" class="text-sm text-gray-500">0 characters</span>
                                </div>
                            </div>

                            <!-- Service Areas -->
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    <i data-lucide="settings" class="w-4 h-4 inline-block mr-2"></i>
                                    Which service area does this feedback relate to? (Optional)
                                </label>
                                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="service_areas[]" value="flight_booking" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Flight Booking</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="service_areas[]" value="hotel_booking" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Hotel Booking</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="service_areas[]" value="customer_service" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Customer Service</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="service_areas[]" value="website_usability" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Website Usability</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="service_areas[]" value="payment_process" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Payment Process</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="service_areas[]" value="other" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Other</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Follow-up -->
                            <div class="mb-8">
                                <label class="flex items-center">
                                    <input type="checkbox" name="follow_up" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                        <i data-lucide="phone" class="w-4 h-4 inline-block mr-1"></i>
                                        I would like someone from GNC Travel to follow up with me about this feedback
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" 
                                        class="px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-indigo-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <i data-lucide="send" class="w-5 h-5 inline-block mr-2"></i>
                                    Submit Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Feedback Matters Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Why Your Feedback Matters</h2>
                    <p class="text-xl text-gray-600">
                        Every piece of feedback helps us create better travel experiences
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl">
                        <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="trending-up" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Continuous Improvement</h3>
                        <p class="text-gray-600">Your feedback drives our innovation and service enhancements</p>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl">
                        <div class="w-16 h-16 bg-green-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="users" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Community Voice</h3>
                        <p class="text-gray-600">Join thousands of travelers shaping the future of travel</p>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl">
                        <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="award" class="w-8 h-8 text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Quality Assurance</h3>
                        <p class="text-gray-600">Help us maintain the highest standards of service excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Feedback FAQ</h2>
                    <p class="text-xl text-gray-600">
                        Common questions about our feedback process
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">How long does it take to respond to feedback?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">We typically respond to feedback within 2-3 business days. For urgent issues, we aim to respond within 24 hours.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Is my feedback anonymous?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">While we collect your contact information to follow up if needed, your feedback can be processed anonymously if you prefer. Just mention this in your message.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200" onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Can I track the status of my feedback?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Yes! After submitting feedback, you'll receive a confirmation email with a reference number. You can use this to track progress through our support center.</p>
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

    // Feedback type selection
    const feedbackOptions = document.querySelectorAll('input[name="feedback_type"]');
    feedbackOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove selected class from all options
            document.querySelectorAll('.feedback-option').forEach(opt => {
                opt.parentElement.classList.remove('border-blue-500', 'bg-blue-50');
                opt.parentElement.classList.add('border-gray-200');
            });
            
            // Add selected class to chosen option
            if (this.checked) {
                const parent = this.parentElement;
                parent.classList.remove('border-gray-200');
                parent.classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    });

    // Star rating functionality
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');
    
    const ratingTexts = {
        1: 'Poor',
        2: 'Fair', 
        3: 'Good',
        4: 'Very Good',
        5: 'Excellent'
    };

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            ratingText.textContent = ratingTexts[rating];
            
            // Update star colors
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                }
            });
        });
    });

    document.getElementById('starRating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        stars.forEach((s, index) => {
            if (index < currentRating) {
                s.classList.add('text-yellow-400');
            } else {
                s.classList.remove('text-yellow-400');
            }
        });
    });

    // Character counter
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    messageTextarea.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length} characters`;
        
        if (length < 10) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-500');
        } else {
            charCount.classList.remove('text-red-500');
            charCount.classList.add('text-gray-500');
        }
    });

    // Form validation
    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
        const rating = ratingInput.value;
        const message = messageTextarea.value;
        
        if (!rating) {
            e.preventDefault();
            alert('Please provide a rating before submitting.');
            return;
        }
        
        if (message.length < 10) {
            e.preventDefault();
            alert('Please provide at least 10 characters in your feedback message.');
            return;
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

.star {
    cursor: pointer;
    user-select: none;
}
</style>
@endpush
@endsection