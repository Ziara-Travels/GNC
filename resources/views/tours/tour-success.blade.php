@extends('layouts.main')

@section('content')
<style>
    /* Custom animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }
    
    .animate-bounce-slow {
        animation: bounce 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-pulse-slow {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Success icon animation */
    .success-animation {
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    
    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    /* Countdown animation */
    .countdown-animation {
        transition: all 1s linear;
    }

    .ziara-flow-main { background: #fdf8eb; }
    .ziara-flow-card { background: #fff; border: 1px solid #eadfca; border-radius: 16px; box-shadow: 0 8px 32px rgba(74,29,19,.16); }
    .ziara-flow-hero {
        background: radial-gradient(900px 400px at 88% -20%, rgba(201,162,39,.18), transparent 60%), linear-gradient(135deg, #4a1d13 0%, #6b2c1f 60%, #7e3425 100%);
        border-bottom: 3px solid #c9a227;
    }
    .ziara-flow-hero__mark { background: #fff; color: #6b2c1f; box-shadow: 0 12px 30px rgba(28,14,10,.35); }
    .ziara-flow-hero h1 { font-family: 'Cormorant Garamond', Georgia, serif; font-weight: 700; letter-spacing: .3px; }
    .ziara-flow-hero p { color: #e8d5b0; }
    .ziara-flow-ref { background: #fdf6df; border: 1px solid rgba(201,162,39,.4); border-radius: 12px; }
    .ziara-flow-ref__label { color: #7a6a60; font-weight: 600; }
    .ziara-flow-ref__value { color: #4a1d13; font-weight: 700; }
    .ziara-flow-body { padding: 26px; }
    .ziara-flow-body__divider { border-bottom: 1px solid #eadfca; }
    .ziara-flow-body__h2 { color: #4a1d13; }
    .ziara-flow-body__h3 { color: #4a1d13; }
    .ziara-flow-loc__text { color: #7a6a60; }
    .ziara-flow-media { background: #f6efe2; border-radius: 12px; overflow: hidden; }
    .ziara-flow-loc i[data-lucide] { color: #c9a227; }
    .ziara-flow-loc span { color: #7a6a60; }
    .ziara-flow-label { color: #7a6a60; }
    .ziara-flow-value { color: #1c1612; font-weight: 500; }
    .ziara-flow-paid { color: #167a3a; font-weight: 500; display: flex; align-items: center; }
    .ziara-flow-next { background: #fdf6df; border: 1px solid #eadfca; border-radius: 14px; }
    .ziara-flow-next h3 { color: #6b1d15; font-weight: 600; }
    .ziara-flow-next__title { color: #6b1d15; font-weight: 600; }
    .ziara-flow-next__text { color: #3a322c; }
    .ziara-flow-next i[data-lucide] { color: #c9a227; }
    .ziara-flow-btn--primary { background: #6b2c1f; color: #fff; border-radius: 10px; }
    .ziara-flow-btn--primary:hover { background: #4a1d13; }
    .ziara-flow-btn--ghost { background: #fff; color: #4a1d13; border: 1px solid #eadfca; border-radius: 10px; }
    .ziara-flow-btn--ghost:hover { background: #fdf8eb; border-color: #c9a227; }
    .ziara-flow-note { background: #fff; border: 1px solid #eadfca; border-radius: 12px; }
    .ziara-flow-note i[data-lucide] { color: #c9a227; }
    .ziara-flow-note__text { color: #3a322c; }
</style>
<div class="ziara-flow-main min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Card -->
        <div class="ziara-flow-card overflow-hidden animate-fade-in-up">
            <!-- Success Header -->
            <div class="ziara-flow-hero px-6 py-8 text-center">
                <div class="success-animation mx-auto ziara-flow-hero__mark rounded-full h-24 w-24 flex items-center justify-center mb-6">
                    <i data-lucide="check" class="h-12 w-12 text-[#6b2c1f]"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Booking Successful!</h1>
                <p class="mt-2">Your tour booking has been confirmed.</p>
            </div>
            
            <!-- Booking Details -->
            <div class="ziara-flow-body">
                <div class="ziara-flow-ref flex items-center justify-between p-4 mb-6">
                    <div class="ziara-flow-ref__label">Booking Reference</div>
                    <div class="ziara-flow-ref__value text-lg">{{ $booking['reference'] ?? 'TOUR-' . strtoupper(substr(uniqid(), 0, 8)) }}</div>
                </div>
                
                <div class="space-y-6">
                    <!-- Tour Details -->
                    <div class="ziara-flow-body__divider border-b pb-6">
                        <h2 class="ziara-flow-body__h2 text-lg font-bold mb-4">Tour Details</h2>
                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Tour Image -->
                            <div class="md:w-1/3">
                                <div class="ziara-flow-media aspect-w-16 aspect-h-9">
                                    <img src="{{ asset('images/tours/' . ($tour['image'] ?? 'tour-placeholder.jpg')) }}" alt="{{ $tour['name'] ?? 'Tour' }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                            
                            <!-- Tour Info -->
                            <div class="md:w-2/3 flex flex-col justify-between">
                                <div>
                                    <h3 class="ziara-flow-body__h3 text-lg font-bold">{{ $tour['name'] ?? 'Maasai Mara Safari Adventure' }}</h3>
                                    
                                    <div class="ziara-flow-loc flex items-center mt-2">
                                        <i data-lucide="map-pin" class="h-4 w-4 mr-1"></i>
                                        <span class="ziara-flow-loc__text">{{ $tour['location'] ?? 'Maasai Mara, Kenya' }}</span>
                                    </div>
                                    
                                    <div class="mt-4 grid grid-cols-2 gap-3">
                                        <div>
                                            <div class="ziara-flow-label text-xs">Tour Date</div>
                                            <div class="ziara-flow-value">{{ $booking['tour_date'] ?? date('D, M d, Y') }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="ziara-flow-label text-xs">Duration</div>
                                            <div class="ziara-flow-value">{{ $tour['duration'] ?? '3 days' }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="ziara-flow-label text-xs">Participants</div>
                                            <div class="ziara-flow-value">{{ $booking['adults'] ?? '2' }} adults{{ ($booking['children'] ?? 0) > 0 ? ', ' . $booking['children'] . ' children' : '' }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="ziara-flow-label text-xs">Meeting Point</div>
                                            <div class="ziara-flow-value">{{ $tour['meeting_point'] ?? 'Hotel pickup' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Details -->
                    <div class="ziara-flow-body__divider border-b pb-6">
                        <h2 class="ziara-flow-body__h2 text-lg font-bold mb-4">Payment Details</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="ziara-flow-label text-sm">Payment Method</div>
                                <div class="ziara-flow-value">{{ $booking['payment_method'] ?? 'Credit Card' }}</div>
                            </div>
                            
                            <div>
                                <div class="ziara-flow-label text-sm">Payment Status</div>
                                <div class="ziara-flow-paid">
                                    <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i>
                                    Paid
                                </div>
                            </div>
                            
                            <div>
                                <div class="ziara-flow-label text-sm">Transaction ID</div>
                                <div class="ziara-flow-value">{{ $booking['transaction_id'] ?? 'TXN-' . rand(100000, 999999) }}</div>
                            </div>
                            
                            <div>
                                <div class="ziara-flow-label text-sm">Amount Paid</div>
                                <div class="ziara-flow-value">{{ $booking['currency'] ?? 'USD' }} {{ $booking['total'] ?? '699.00' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Details -->
                    <div>
                        <h2 class="ziara-flow-body__h2 text-lg font-bold mb-4">Customer Details</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="ziara-flow-label text-sm">Name</div>
                                <div class="ziara-flow-value">{{ Auth::user()->name ?? 'John Doe' }}</div>
                            </div>
                            
                            <div>
                                <div class="ziara-flow-label text-sm">Email</div>
                                <div class="ziara-flow-value">{{ Auth::user()->email ?? 'john.doe@example.com' }}</div>
                            </div>
                            
                            <div>
                                <div class="ziara-flow-label text-sm">Phone</div>
                                <div class="ziara-flow-value">{{ Auth::user()->phone ?? '+254 712 345 678' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- What's Next Section -->
                <div class="ziara-flow-next mt-8 p-5">
                    <h3 class="ziara-flow-next__title font-semibold mb-3">What's Next?</h3>
                    
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i data-lucide="mail" class="h-5 w-5 mt-0.5 mr-2"></i>
                            <span class="ziara-flow-next__text">A confirmation email with all booking details has been sent to your email address.</span>
                        </li>
                        
                        <li class="flex items-start">
                            <i data-lucide="file-text" class="h-5 w-5 mt-0.5 mr-2"></i>
                            <span class="ziara-flow-next__text">Your e-ticket is attached to the confirmation email. Please present it at the tour starting point.</span>
                        </li>
                        
                        <li class="flex items-start">
                            <i data-lucide="message-square" class="h-5 w-5 mt-0.5 mr-2"></i>
                            <span class="ziara-flow-next__text">Our customer service team will contact you 24 hours before the tour to confirm all details.</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('user.bookings.all') }}" class="w-full sm:w-auto ziara-flow-btn--primary font-medium py-3 px-6 flex items-center justify-center transition-colors">
                        <i data-lucide="layout-dashboard" class="h-5 w-5 mr-2"></i>
                        View in Dashboard
                    </a>
                    
                    <a href="{{ route('bookings.download', $booking['id'] ?? 1) }}" class="w-full sm:w-auto ziara-flow-btn--ghost font-medium py-3 px-6 flex items-center justify-center transition-colors">
                        <i data-lucide="download" class="h-5 w-5 mr-2"></i>
                        Download E-Ticket
                    </a>
                    
                    <a href="{{ route('home') }}" class="w-full sm:w-auto ziara-flow-btn--ghost font-medium py-3 px-6 flex items-center justify-center transition-colors">
                        <i data-lucide="home" class="h-5 w-5 mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Redirect Notification -->
        <div class="mt-6 text-center animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="ziara-flow-note p-4 inline-flex items-center">
                <i data-lucide="info" class="h-5 w-5 mr-2 animate-pulse-slow"></i>
                <span class="ziara-flow-note__text">You will be redirected to the homepage in <span id="countdown" class="font-semibold countdown-animation">6</span> seconds</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Lucide icons
        lucide.createIcons();
        
        // Fill stars for star ratings
        document.querySelectorAll('[data-lucide="star"]').forEach(function(star) {
            if (star.classList.contains('fill-current')) {
                star.setAttribute('fill', 'currentColor');
            }
        });
        
        // Countdown timer for redirect
        let countdown = 6;
        const countdownElement = document.getElementById('countdown');
        
        const countdownInterval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                window.location.href = "{{ route('home') }}";
            }
        }, 1000);
        
        // Show toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 flex items-center ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white transform transition-all duration-300 translate-y-20 opacity-0`;
            
            toast.innerHTML = `
                <i data-lucide="${type === 'success' ? 'check-circle' : 'alert-circle'}" class="w-5 h-5 mr-2"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(toast);
            
            // Initialize the Lucide icon in the toast
            lucide.createIcons();
            
            // Show toast with animation
            setTimeout(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
            }, 100);
            
            // Hide toast after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                
                // Remove from DOM after animation completes
                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 300);
            }, 5000);
        }
        
        // Show success toast
        showToast('Booking successful! Thank you for your purchase.', 'success');
    });
</script>
@endsection
