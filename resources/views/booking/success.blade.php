@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/ziara-booking.css') }}?v={{ (file_exists(public_path('css/ziara-booking.css')) ? filemtime(public_path('css/ziara-booking.css')) : 0) }}">
@endpush

@section('content')
    @php
        // Determine currency display
        $userCountry = Auth::user()->country ?? 'US';
        $isKenyan = $userCountry === 'KE' || $userCountry === 'Kenya';
        $exchangeRate = 130.50;
        
        $displaySymbol = $isKenyan ? 'KSh' : '$';
        $amount = $booking->amount;
        
        // Format amount based on currency
        $formattedAmount = $booking->currency === 'KES' ? number_format($amount, 0) : number_format($amount, 2);
        
        // Parse booking details
        $bookingDetails = $booking->booking_type === 'flight' 
            ? json_decode($booking->flight_details, true) 
            : json_decode($booking->hotel_details, true);
    @endphp

    <!-- Enhanced Hero Section -->
    <div class="ziara-bk ziara-bk-hero relative bg-gradient-to-br from-green-600 via-emerald-700 to-teal-800 py-24 overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-72 h-72 bg-green-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-emerald-400/20 rounded-full blur-3xl animate-pulse animation-delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-teal-400/10 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
        </div>

        <!-- Floating Success Elements -->
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
                <!-- Success Animation -->
                <div class="mb-8">
                    <div class="relative inline-block">
                        <div class="w-32 h-32 bg-white/20 backdrop-blur-lg rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl animate-pulse">
                            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <i data-lucide="check" class="h-12 w-12 text-green-600 animate-bounce"></i>
                            </div>
                        </div>
                        <!-- Confetti Animation -->
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-4">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-yellow-400 rounded-full animate-ping"></div>
                                <div class="w-2 h-2 bg-blue-400 rounded-full animate-ping animation-delay-300"></div>
                                <div class="w-2 h-2 bg-red-400 rounded-full animate-ping animation-delay-600"></div>
                            </div>
                        </div>
                    </div>
                    
                    <h1 class="text-5xl font-bold text-white mb-4 animate-fade-in">
                        Payment Successful!
                    </h1>
                    <p class="text-xl text-white/90 mb-6 animate-fade-in animation-delay-300">
                        Your {{ ucfirst($booking->booking_type) }} booking has been confirmed
                    </p>
                    
                    <!-- Booking Reference -->
                    <div class="bg-white/20 backdrop-blur-sm rounded-3xl p-6 inline-block border border-white/30 animate-fade-in animation-delay-600">
                        <div class="text-white/80 text-sm mb-2">Booking Reference</div>
                        <div class="text-2xl font-bold text-white font-mono tracking-wider">
                            {{ $booking->booking_reference }}
                        </div>
                    </div>
                </div>

                <!-- Quick Summary -->
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 shadow-2xl animate-fade-in animation-delay-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-white">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="{{ $booking->booking_type === 'flight' ? 'plane' : 'building' }}" class="h-8 w-8"></i>
                            </div>
                            <div class="text-lg font-semibold">{{ ucfirst($booking->booking_type) }}</div>
                            <div class="text-white/80 text-sm">
                                @if($booking->booking_type === 'flight')
                                    {{ $booking->departure_airport }} → {{ $booking->arrival_airport }}
                                @else
                                    Hotel Booking
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="credit-card" class="h-8 w-8"></i>
                            </div>
                            <div class="text-lg font-semibold">{{ $displaySymbol }}{{ $formattedAmount }}</div>
                            <div class="text-white/80 text-sm">{{ $booking->currency }} • {{ ucfirst($booking->payment_method) }}</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="shield-check" class="h-8 w-8"></i>
                            </div>
                            <div class="text-lg font-semibold">Secure Payment</div>
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
    <div class="ziara-bk ziara-bk-body container mx-auto px-4 py-12 -mt-16 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Booking Details Card -->
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden mb-8 ziara-bk-card">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-8 text-white ziara-bk-cta">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <i data-lucide="file-text" class="h-8 w-8"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">Booking Details</h2>
                            <p class="text-green-100">Reference: {{ $booking->booking_reference }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-8">
                    @if($booking->booking_type === 'flight')
                        <!-- Flight Details -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i data-lucide="plane" class="h-6 w-6 text-blue-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Flight Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="plane-takeoff" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Departure</div>
                                            <div class="text-gray-600">{{ $booking->departure_airport }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($booking->departure_time)->format('M j, Y • H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="users" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Passengers</div>
                                            <div class="text-gray-600">{{ $booking->adults }} Adult{{ $booking->adults > 1 ? 's' : '' }}</div>
                                            @if($booking->children > 0)
                                                <div class="text-gray-600">{{ $booking->children }} Child{{ $booking->children > 1 ? 'ren' : '' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="plane-landing" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Arrival</div>
                                            <div class="text-gray-600">{{ $booking->arrival_airport }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($booking->arrival_time)->format('M j, Y • H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="star" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Class</div>
                                            <div class="text-gray-600">{{ ucwords(str_replace('_', ' ', strtolower($booking->travel_class))) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Hotel Details -->
                        <div class="space-y-8">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                    <i data-lucide="building" class="h-6 w-6 text-blue-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Hotel Information</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="calendar" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Check-in</div>
                                            <div class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M j, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="users" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Guests</div>
                                            <div class="text-gray-600">{{ $booking->adults + $booking->children }} Guest{{ ($booking->adults + $booking->children) > 1 ? 's' : '' }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="calendar" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Check-out</div>
                                            <div class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M j, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="moon" class="h-5 w-5 text-gray-500"></i>
                                        <div>
                                            <div class="font-semibold text-gray-800">Duration</div>
                                            <div class="text-gray-600">
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->diffInDays(\Carbon\Carbon::parse($booking->check_in_date)) }} Night{{ \Carbon\Carbon::parse($booking->check_out_date)->diffInDays(\Carbon\Carbon::parse($booking->check_in_date)) > 1 ? 's' : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Payment Information -->
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i data-lucide="credit-card" class="h-6 w-6 text-green-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Payment Information</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Amount Paid</div>
                                <div class="text-lg font-bold text-gray-800">{{ $displaySymbol }}{{ $formattedAmount }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->currency }}</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Payment Method</div>
                                <div class="text-lg font-bold text-gray-800">{{ ucfirst($booking->payment_method) }}</div>
                                <div class="text-xs text-gray-500">Secure Payment</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Transaction ID</div>
                                <div class="text-lg font-bold text-gray-800 font-mono">{{ $booking->transaction_id }}</div>
                                <div class="text-xs text-gray-500">Jenga API</div>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4">
                                <div class="text-sm text-gray-600 mb-1">Status</div>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-lg font-bold text-green-600">{{ ucfirst($booking->status) }}</span>
                                </div>
                                <div class="text-xs text-gray-500">Payment Confirmed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
                    <span>Go to Dashboard</span>
                </a>
                
                <button onclick="window.print()" class="flex items-center justify-center gap-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i data-lucide="printer" class="h-5 w-5"></i>
                    <span>Print E-Ticket</span>
                </button>

                <a href="{{ route('booking.eticket', $booking->booking_reference) }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i data-lucide="download" class="h-5 w-5"></i>
                    <span>Download PDF</span>
                </a>
                
                <a href="{{ route('home') }}" class="flex items-center justify-center gap-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i data-lucide="plane" class="h-5 w-5"></i>
                    <span>Book Again</span>
                </a>
            </div>
            
            <!-- Social Sharing -->
            <div class="mt-8 bg-white rounded-3xl border border-gray-200 p-8 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="share-2" class="h-6 w-6 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Share Your Trip</h3>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button class="flex flex-col items-center justify-center gap-2 p-4 bg-blue-100 hover:bg-blue-200 rounded-xl transition-colors text-blue-600">
                        <i data-lucide="facebook" class="h-6 w-6"></i>
                        <span class="text-sm font-medium">Facebook</span>
                    </button>
                    
                    <button class="flex flex-col items-center justify-center gap-2 p-4 bg-sky-100 hover:bg-sky-200 rounded-xl transition-colors text-sky-600">
                        <i data-lucide="twitter" class="h-6 w-6"></i>
                        <span class="text-sm font-medium">Twitter</span>
                    </button>
                    
                    <button class="flex flex-col items-center justify-center gap-2 p-4 bg-green-100 hover:bg-green-200 rounded-xl transition-colors text-green-600">
                        <i data-lucide="mail" class="h-6 w-6"></i>
                        <span class="text-sm font-medium">Email</span>
                    </button>
                    
                    <button class="flex flex-col items-center justify-center gap-2 p-4 bg-purple-100 hover:bg-purple-200 rounded-xl transition-colors text-purple-600">
                        <i data-lucide="link" class="h-6 w-6"></i>
                        <span class="text-sm font-medium">Copy Link</span>
                    </button>
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
            
            // Add success confetti effect
            setTimeout(() => {
                createConfetti();
            }, 1000);
            
            // Setup social sharing buttons
            setupSocialSharing();
            
            // Add hover effects to cards
            addCardHoverEffects();
            
            // Add print styles when printing
            setupPrintBehavior();
        });

        function createConfetti() {
            const colors = ['#10B981', '#3B82F6', '#EF4444', '#F59E0B', '#8B5CF6'];
            const container = document.body;
            
            // Create different shapes of confetti
            const shapes = ['circle', 'square', 'triangle'];
            
            for (let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = `${Math.random() * 10 + 5}px`;
                confetti.style.height = `${Math.random() * 10 + 5}px`;
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = '-10px';
                
                // Different shapes
                const shape = shapes[Math.floor(Math.random() * shapes.length)];
                if (shape === 'circle') {
                    confetti.style.borderRadius = '50%';
                } else if (shape === 'triangle') {
                    confetti.style.width = '0';
                    confetti.style.height = '0';
                    confetti.style.backgroundColor = 'transparent';
                    confetti.style.borderLeft = `${Math.random() * 10 + 5}px solid transparent`;
                    confetti.style.borderRight = `${Math.random() * 10 + 5}px solid transparent`;
                    confetti.style.borderBottom = `${Math.random() * 10 + 10}px solid ${colors[Math.floor(Math.random() * colors.length)]}`;
                }
                
                confetti.style.pointerEvents = 'none';
                confetti.style.zIndex = '9999';
                
                // Random rotation and horizontal movement
                const duration = Math.random() * 3 + 2;
                const horizontalMovement = Math.random() * 100 - 50; // Move left or right randomly
                
                confetti.style.animation = `confetti-fall ${duration}s linear forwards`;
                confetti.style.transform = `translateX(0px) rotate(0deg)`;
                confetti.style.transition = `transform ${duration}s ease-in-out`;
                
                setTimeout(() => {
                    confetti.style.transform = `translateX(${horizontalMovement}px) rotate(${Math.random() * 360}deg)`;
                }, 10);
                
                container.appendChild(confetti);
                
                setTimeout(() => {
                    confetti.remove();
                }, duration * 1000);
            }
        }
        
        function setupSocialSharing() {
            // Facebook sharing
            document.querySelector('button i[data-lucide="facebook"]').closest('button').addEventListener('click', function() {
                const url = window.location.href;
                const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                window.open(shareUrl, 'facebook-share', 'width=580,height=296');
            });
            
            // Twitter sharing
            document.querySelector('button i[data-lucide="twitter"]').closest('button').addEventListener('click', function() {
                const url = window.location.href;
                const text = 'Just booked my trip! Check it out:';
                const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                window.open(shareUrl, 'twitter-share', 'width=550,height=420');
            });
            
            // Email sharing
            document.querySelector('button i[data-lucide="mail"]').closest('button').addEventListener('click', function() {
                const url = window.location.href;
                const subject = 'Check out my booking!';
                const body = `I've just booked a trip. Here are the details: ${url}`;
                window.location.href = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            });
            
            // Copy link
            document.querySelector('button i[data-lucide="link"]').closest('button').addEventListener('click', function() {
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    // Show toast
                    const button = this;
                    const originalText = button.querySelector('span').textContent;
                    
                    button.querySelector('i').setAttribute('data-lucide', 'check');
                    button.querySelector('span').textContent = 'Copied!';
                    
                    // Re-initialize Lucide icons
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        button.querySelector('i').setAttribute('data-lucide', 'link');
                        button.querySelector('span').textContent = originalText;
                        
                        // Re-initialize Lucide icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }, 2000);
                });
            });
        }
        
        function addCardHoverEffects() {
            // Add subtle hover effects to cards
            const cards = document.querySelectorAll('.rounded-2xl, .rounded-3xl');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                    this.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)';
                    this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });
        }
        
        function setupPrintBehavior() {
            const printButton = document.querySelector('button[onclick="window.print()"]');
            if (printButton) {
                printButton.addEventListener('click', function() {
                    // Add print classes before printing
                    document.querySelectorAll('.rounded-3xl, .rounded-2xl').forEach(el => {
                        el.classList.add('print-no-shadow');
                    });
                    
                    document.querySelectorAll('.grid').forEach(el => {
                        el.classList.add('print-break-inside-avoid');
                    });
                    
                    document.querySelectorAll('.animate-pulse').forEach(el => {
                        el.classList.remove('animate-pulse');
                    });
                    
                    // Social sharing section shouldn't be printed
                    document.querySelectorAll('[data-lucide="share-2"]').forEach(el => {
                        const section = el.closest('.bg-white.rounded-3xl');
                        if (section) {
                            section.classList.add('no-print');
                        }
                    });
                });
            }
        }
    </script>

    <style>
        @keyframes confetti-fall {
            0% {
                transform: translateY(-10px) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(360deg);
                opacity: 0;
            }
        }
        
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
        
        @keyframes scale-in {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slide-in-right {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.4;
            }
            100% {
                transform: scale(0.8);
                opacity: 0.8;
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }
        
        .animate-scale-in {
            animation: scale-in 0.6s ease-out forwards;
        }
        
        .animate-slide-in-right {
            animation: slide-in-right 0.6s ease-out forwards;
        }
        
        .animate-pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        .animation-delay-100 {
            animation-delay: 0.1s;
        }
        
        .animation-delay-200 {
            animation-delay: 0.2s;
        }
        
        .animation-delay-300 {
            animation-delay: 0.3s;
        }
        
        .animation-delay-400 {
            animation-delay: 0.4s;
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
        
        /* Print styles */
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                background: white;
            }
            
            .print-no-shadow {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            
            .print-break-inside-avoid {
                page-break-inside: avoid;
            }
        }
    </style>
    
    <!-- Auto-redirect script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create countdown element
            const countdownElement = document.createElement('div');
            countdownElement.className = 'fixed bottom-4 right-4 bg-gray-800/80 backdrop-blur-sm text-white px-4 py-2 rounded-lg shadow-lg z-50 flex items-center';
            document.body.appendChild(countdownElement);
            
            let secondsLeft = 6;
            
            // Update countdown every second
            const countdownInterval = setInterval(function() {
                secondsLeft--;
                countdownElement.innerHTML = `
                    <i data-lucide="home" class="h-5 w-5 mr-2"></i>
                    <span>Redirecting to homepage in ${secondsLeft} seconds...</span>
                `;
                
                // Initialize the Lucide icon
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
                
                if (secondsLeft <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "{{ route('home') }}";
                }
            }, 1000);
            
            // Start with initial text
            countdownElement.innerHTML = `
                <i data-lucide="home" class="h-5 w-5 mr-2"></i>
                <span>Redirecting to homepage in ${secondsLeft} seconds...</span>
            `;
            
            // Initialize the Lucide icon
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endsection