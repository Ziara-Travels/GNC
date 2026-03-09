@extends('layouts.main')

@section('content')
    @php
        // Get failure reason from session or default
        $failureReason = session('failure_reason', 'Payment failed');
        $errorCode = session('error_code', 'PAYMENT_FAILED');
        $suggestedAction = session('suggested_action', 'Please try again or contact support');
        
        // Determine currency display
        $userCountry = Auth::user()->country ?? 'US';
        $isKenyan = $userCountry === 'KE' || $userCountry === 'Kenya';
        $displaySymbol = $isKenyan ? 'KSh' : '$';
        
        // Get booking details from session
        $bookingData = session('failed_booking_data', []);
        $paymentMethod = session('failed_payment_method', 'Unknown');
        $amount = session('failed_amount', '0.00');
        $currency = session('failed_currency', 'USD');
        $bookingReference = session('failed_booking_reference', 'N/A');
    @endphp

    <!-- Enhanced Hero Section -->
    <div class="relative bg-gradient-to-br from-red-600 via-rose-700 to-pink-800 py-24 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-red-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-rose-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-pink-400/10 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
        </div>

        <!-- Floating Warning Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/4 left-1/4 animate-bounce animation-delay-500">
                <div class="w-8 h-8 bg-white/20 rounded-full"></div>
            </div>
            <div class="absolute top-3/4 right-1/4 animate-bounce animation-delay-1000">
                <div class="w-6 h-6 bg-white/20 rounded-full"></div>
            </div>
            <div class="absolute top-1/2 right-1/3 animate-bounce animation-delay-1500">
                <div class="w-4 h-4 bg-white/20 rounded-full"></div>
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <!-- Failure Animation -->
                <div class="mb-8">
                    <div class="relative inline-block">
                        <div class="w-32 h-32 bg-white/20 backdrop-blur-lg rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl animate-pulse">
                            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <i data-lucide="x" class="h-12 w-12 text-red-600 animate-pulse"></i>
                            </div>
                        </div>
                        <!-- Error Animation -->
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-4">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-red-400 rounded-full animate-ping"></div>
                                <div class="w-2 h-2 bg-orange-400 rounded-full animate-ping animation-delay-300"></div>
                                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-ping animation-delay-600"></div>
                            </div>
                        </div>
                    </div>
                    
                    <h1 class="text-5xl font-bold text-white mb-4 animate-fade-in">
                        Payment Failed
                    </h1>
                    <p class="text-xl text-white/90 mb-6 animate-fade-in animation-delay-300">
                        We couldn't process your payment. Please try again.
                    </p>
                    
                    <!-- Error Code -->
                    <div class="bg-white/20 backdrop-blur-sm rounded-3xl p-6 inline-block border border-white/30 animate-fade-in animation-delay-600">
                        <div class="text-white/80 text-sm mb-2">Error Code</div>
                        <div class="text-2xl font-bold text-white font-mono tracking-wider">
                            {{ $errorCode }}
                        </div>
                    </div>
                </div>

                <!-- Quick Summary -->
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 shadow-2xl animate-fade-in animation-delay-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-white">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="alert-triangle" class="h-8 w-8"></i>
                            </div>
                            <div class="text-lg font-semibold">Payment Issue</div>
                            <div class="text-white/80 text-sm">{{ $failureReason }}</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="credit-card" class="h-8 w-8"></i>
                            </div>
                            <div class="text-lg font-semibold">{{ $displaySymbol }}{{ number_format($amount, 2) }}</div>
                            <div class="text-white/80 text-sm">{{ $currency }} • {{ ucfirst($paymentMethod) }}</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="shield-x" class="h-8 w-8"></i>
                            </div>
                            <div class="text-lg font-semibold">Secure Process</div>
                            <div class="text-white/80 text-sm">Powered by Jenga API</div>
                        </div>
                    </div>
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
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-red-600 to-rose-600 p-8 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i data-lucide="alert-circle" class="h-8 w-8"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">Payment Failed</h2>
                            <p class="text-red-100">Reference: {{ $bookingReference }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    <!-- Error Information -->
                    <div class="bg-red-50 rounded-3xl p-8 border border-red-200 mb-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="alert-triangle" class="h-6 w-6 text-red-600"></i>
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-xl font-bold text-red-800 mb-2">What went wrong?</h3>
                                <p class="text-red-700 mb-4">{{ $failureReason }}</p>
                                <div class="bg-red-100 rounded-2xl p-4">
                                    <div class="text-sm text-red-800 font-semibold mb-2">Error Code: {{ $errorCode }}</div>
                                    <div class="text-sm text-red-700">{{ $suggestedAction }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Common Issues and Solutions -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i data-lucide="help-circle" class="h-6 w-6 text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Common Issues & Solutions</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- M-Pesa Issues -->
                            <div class="bg-orange-50 rounded-2xl p-6 border border-orange-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="smartphone" class="h-4 w-4 text-orange-600"></i>
                                    </div>
                                    <h4 class="font-bold text-orange-800">M-Pesa Issues</h4>
                                </div>
                                <ul class="space-y-2 text-sm text-orange-700">
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>Insufficient balance in M-Pesa wallet</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>M-Pesa service temporarily unavailable</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>Transaction limits exceeded</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>PIN entered incorrectly multiple times</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Bank Issues -->
                            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="building-2" class="h-4 w-4 text-blue-600"></i>
                                    </div>
                                    <h4 class="font-bold text-blue-800">Bank Transfer Issues</h4>
                                </div>
                                <ul class="space-y-2 text-sm text-blue-700">
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>Insufficient funds in bank account</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>Bank service maintenance/downtime</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>Daily transaction limits reached</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i data-lucide="circle" class="h-3 w-3 mt-1 flex-shrink-0"></i>
                                        <span>Account temporarily frozen</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                <i data-lucide="receipt" class="h-6 w-6 text-gray-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Failed Payment Summary</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Attempted Amount</div>
                                <div class="text-lg font-bold text-gray-800">{{ $displaySymbol }}{{ number_format($amount, 2) }}</div>
                                <div class="text-xs text-gray-500">{{ $currency }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Payment Method</div>
                                <div class="text-lg font-bold text-gray-800">{{ ucfirst($paymentMethod) }}</div>
                                <div class="text-xs text-gray-500">Failed</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Error Code</div>
                                <div class="text-lg font-bold text-gray-800 font-mono">{{ $errorCode }}</div>
                                <div class="text-xs text-gray-500">Jenga API</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Status</div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                    <span class="text-lg font-bold text-red-600">Failed</span>
                                </div>
                                <div class="text-xs text-gray-500">Payment Not Processed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <button onclick="history.back()" class="flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                    <span>Try Again</span>
                </button>
                
                <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i data-lucide="home" class="h-5 w-5"></i>
                    <span>Go Home</span>
                </a>
                
                <a href="#" onclick="openSupportChat()" class="flex items-center justify-center gap-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i data-lucide="message-circle" class="h-5 w-5"></i>
                    <span>Contact Support</span>
                </a>
            </div>

            <!-- Support Information -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="headphones" class="h-6 w-6 text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Need Help?</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="phone" class="h-8 w-8 text-blue-600"></i>
                        </div>
                        <div class="font-semibold text-gray-800 mb-2">24/7 Phone Support</div>
                        <div class="text-blue-600 font-semibold">+254 700 123 456</div>
                        <div class="text-sm text-gray-600">Available round the clock</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="message-circle" class="h-8 w-8 text-green-600"></i>
                        </div>
                        <div class="font-semibold text-gray-800 mb-2">Live Chat</div>
                        <div class="text-green-600 font-semibold">Start Chat</div>
                        <div class="text-sm text-gray-600">Instant response</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="mail" class="h-8 w-8 text-purple-600"></i>
                        </div>
                        <div class="font-semibold text-gray-800 mb-2">Email Support</div>
                        <div class="text-purple-600 font-semibold">support@ziara-travel.com</div>
                        <div class="text-sm text-gray-600">Response within 2 hours</div>
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
        });

        function openSupportChat() {
            // Implement support chat functionality
            alert('Support chat will be available soon. Please call +254 700 123 456 for immediate assistance.');
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }
        
        .animation-delay-300 {
            animation-delay: 0.3s;
        }
        
        .animation-delay-500 {
            animation-delay: 0.5s;
        }
        
        .animation-delay-600 {
            animation-delay: 0.6s;
        }
        
        .animation-delay-900 {
            animation-delay: 0.9s;
        }
        
        .animation-delay-1000 {
            animation-delay: 1s;
        }
        
        .animation-delay-1500 {
            animation-delay: 1.5s;
        }
        
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
@endsection