@extends('layouts.main')

@section('styles')
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
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-fade-in-up">
            <!-- Success Header -->
            <div class="bg-green-500 px-6 py-8 text-center">
                <div class="success-animation mx-auto bg-white rounded-full h-24 w-24 flex items-center justify-center mb-6">
                    <i data-lucide="check" class="h-12 w-12 text-green-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">Booking Successful!</h1>
                <p class="text-green-100 mt-2">Your tour booking has been confirmed.</p>
            </div>
            
            <!-- Booking Details -->
            <div class="p-6">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-6">
                    <div class="font-medium text-gray-700">Booking Reference</div>
                    <div class="text-lg font-bold text-gray-900">{{ $booking['reference'] ?? 'TOUR-' . strtoupper(substr(uniqid(), 0, 8)) }}</div>
                </div>
                
                <div class="space-y-6">
                    <!-- Tour Details -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Tour Details</h2>
                        
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Tour Image -->
                            <div class="md:w-1/3">
                                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-gray-100">
                                    <img src="{{ asset('images/tours/' . ($tour['image'] ?? 'tour-placeholder.jpg')) }}" alt="{{ $tour['name'] ?? 'Tour' }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                            
                            <!-- Tour Info -->
                            <div class="md:w-2/3 flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $tour['name'] ?? 'Maasai Mara Safari Adventure' }}</h3>
                                    
                                    <div class="flex items-center mt-2">
                                        <i data-lucide="map-pin" class="h-4 w-4 text-gray-500 mr-1"></i>
                                        <span class="text-gray-600">{{ $tour['location'] ?? 'Maasai Mara, Kenya' }}</span>
                                    </div>
                                    
                                    <div class="mt-4 grid grid-cols-2 gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500">Tour Date</div>
                                            <div class="font-medium">{{ $booking['tour_date'] ?? date('D, M d, Y') }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="text-xs text-gray-500">Duration</div>
                                            <div class="font-medium">{{ $tour['duration'] ?? '3 days' }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="text-xs text-gray-500">Participants</div>
                                            <div class="font-medium">{{ $booking['adults'] ?? '2' }} adults{{ ($booking['children'] ?? 0) > 0 ? ', ' . $booking['children'] . ' children' : '' }}</div>
                                        </div>
                                        
                                        <div>
                                            <div class="text-xs text-gray-500">Meeting Point</div>
                                            <div class="font-medium">{{ $tour['meeting_point'] ?? 'Hotel pickup' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Details -->
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Payment Details</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600">Payment Method</div>
                                <div class="font-medium">{{ $booking['payment_method'] ?? 'Credit Card' }}</div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600">Payment Status</div>
                                <div class="font-medium text-green-600 flex items-center">
                                    <i data-lucide="check-circle" class="h-4 w-4 mr-1"></i>
                                    Paid
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600">Transaction ID</div>
                                <div class="font-medium">{{ $booking['transaction_id'] ?? 'TXN-' . rand(100000, 999999) }}</div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600">Amount Paid</div>
                                <div class="font-medium">{{ $booking['currency'] ?? 'USD' }} {{ $booking['total'] ?? '699.00' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Details -->
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Customer Details</h2>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-sm text-gray-600">Name</div>
                                <div class="font-medium">{{ Auth::user()->name ?? 'John Doe' }}</div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600">Email</div>
                                <div class="font-medium">{{ Auth::user()->email ?? 'john.doe@example.com' }}</div>
                            </div>
                            
                            <div>
                                <div class="text-sm text-gray-600">Phone</div>
                                <div class="font-medium">{{ Auth::user()->phone ?? '+254 712 345 678' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- What's Next Section -->
                <div class="mt-8 p-5 bg-indigo-50 rounded-lg border border-indigo-100">
                    <h3 class="font-semibold text-indigo-800 mb-3">What's Next?</h3>
                    
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i data-lucide="mail" class="h-5 w-5 text-indigo-600 mt-0.5 mr-2"></i>
                            <span class="text-gray-700">A confirmation email with all booking details has been sent to your email address.</span>
                        </li>
                        
                        <li class="flex items-start">
                            <i data-lucide="file-text" class="h-5 w-5 text-indigo-600 mt-0.5 mr-2"></i>
                            <span class="text-gray-700">Your e-ticket is attached to the confirmation email. Please present it at the tour starting point.</span>
                        </li>
                        
                        <li class="flex items-start">
                            <i data-lucide="message-square" class="h-5 w-5 text-indigo-600 mt-0.5 mr-2"></i>
                            <span class="text-gray-700">Our customer service team will contact you 24 hours before the tour to confirm all details.</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ route('dashboard.bookings') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center transition-colors">
                        <i data-lucide="layout-dashboard" class="h-5 w-5 mr-2"></i>
                        View in Dashboard
                    </a>
                    
                    <a href="{{ route('tours.download-ticket', ['booking_id' => $booking['id'] ?? '1']) }}" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg border border-gray-300 flex items-center justify-center transition-colors">
                        <i data-lucide="download" class="h-5 w-5 mr-2"></i>
                        Download E-Ticket
                    </a>
                    
                    <a href="{{ route('home') }}" class="w-full sm:w-auto bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-6 rounded-lg border border-gray-300 flex items-center justify-center transition-colors">
                        <i data-lucide="home" class="h-5 w-5 mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Redirect Notification -->
        <div class="mt-6 text-center animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="p-4 bg-blue-50 rounded-lg inline-flex items-center">
                <i data-lucide="info" class="h-5 w-5 text-blue-500 mr-2 animate-pulse-slow"></i>
                <span class="text-gray-700">You will be redirected to the homepage in <span id="countdown" class="font-semibold countdown-animation">6</span> seconds</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
