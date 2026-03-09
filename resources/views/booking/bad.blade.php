@extends('layouts.main')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-red-700 to-rose-800 py-16">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-red-400/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-rose-400/10 rounded-full blur-3xl animate-pulse"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Failed Animation -->
                <div class="mb-8">
                    <div class="relative inline-block">
                        <div class="w-24 h-24 bg-white/20 backdrop-blur-lg rounded-full flex items-center justify-center mx-auto mb-6">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <i data-lucide="x" class="h-8 w-8 text-red-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <h1 class="text-4xl font-bold text-white mb-4">
                        Booking Failed
                    </h1>
                    <p class="text-xl text-white/90 mb-6">
                        We couldn't complete your booking
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Wave separator -->
        <div class="absolute bottom-0 left-0 w-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" class="w-full h-auto">
                <path fill="#ffffff" fill-opacity="1" d="M0,64L80,58.7C160,53,320,43,480,42.7C640,43,800,53,960,58.7C1120,64,1280,64,1360,64L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12 -mt-16 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Error Details Card -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-red-600 to-rose-600 p-8 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i data-lucide="alert-triangle" class="h-8 w-8"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">Error Details</h2>
                            <p class="text-red-100">We encountered an issue with your booking</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg mb-6">
                        <h3 class="font-semibold text-red-800 mb-2">What Happened?</h3>
                        <p class="text-red-700">{{ $failure_reason }}</p>
                        @if($error_code)
                            <div class="mt-2 text-sm text-red-600">
                                Error code: {{ $error_code }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-r-lg mb-6">
                        <h3 class="font-semibold text-yellow-800 mb-2">Suggested Action</h3>
                        <p class="text-yellow-700">{{ $suggested_action }}</p>
                    </div>
                    
                    @if($booking_reference != 'N/A')
                    <div class="border border-gray-200 rounded-xl p-6 mb-6">
                        <h3 class="font-semibold text-gray-700 mb-4">Booking Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Reference</span>
                                <span class="font-semibold text-gray-800">{{ $booking_reference }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Amount</span>
                                <span class="font-semibold text-gray-800">{{ $currency }} {{ $amount }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Payment Method</span>
                                <span class="font-semibold text-gray-800">{{ ucfirst($payment_method) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                        <a href="{{ route('home') }}" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl transition-colors">
                            <i data-lucide="home" class="h-5 w-5"></i>
                            <span>Back to Home</span>
                        </a>
                        <a href="{{ url()->previous() }}" class="flex items-center justify-center gap-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-xl transition-colors">
                            <i data-lucide="refresh-cw" class="h-5 w-5"></i>
                            <span>Try Again</span>
                        </a>
                    </div>
                    
                    <div class="text-center border-t border-gray-100 pt-6">
                        <p class="text-gray-600">
                            Need help? 
                            <a href="/contact" class="text-blue-600 hover:text-blue-700 font-medium">
                                Contact our support team
                                <i data-lucide="external-link" class="h-4 w-4 inline ml-1"></i>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            // Add subtle animation for error notification
            animateErrorElements();
            
            // Add card hover effects
            addCardHoverEffects();
        });
        
        function animateErrorElements() {
            const errorIcon = document.querySelector('[data-lucide="x"]');
            if (errorIcon) {
                errorIcon.classList.add('animate-pulse');
            }
            
            const errorDetails = document.querySelector('.bg-red-50');
            if (errorDetails) {
                setTimeout(() => {
                    errorDetails.classList.add('animate-fade-in');
                }, 500);
            }
            
            const suggestionDetails = document.querySelector('.bg-yellow-50');
            if (suggestionDetails) {
                setTimeout(() => {
                    suggestionDetails.classList.add('animate-fade-in');
                }, 800);
            }
        }
        
        function addCardHoverEffects() {
            // Add subtle hover effects to cards and buttons
            const cards = document.querySelectorAll('.rounded-xl, .rounded-2xl, .rounded-3xl');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
                    this.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });
        }
    </script>
    
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.5s ease-out forwards;
        }
        
        @keyframes shake {
            0%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 50%, 70%, 90% {
                transform: translateX(-5px);
            }
            20%, 40%, 60%, 80% {
                transform: translateX(5px);
            }
        }
        
        .animate-shake {
            animation: shake 0.8s cubic-bezier(.36,.07,.19,.97) both;
        }
    </style>
@endsection