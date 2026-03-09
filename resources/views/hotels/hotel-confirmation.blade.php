@extends('layouts.main')

@push('styles')
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
        animation: fadeInUp 0.3s ease-out forwards;
    }
    
    /* Success check animation */
    @keyframes checkmark {
        0% {
            stroke-dashoffset: 100;
        }
        100% {
            stroke-dashoffset: 0;
        }
    }
    
    .checkmark {
        stroke-dasharray: 100;
        stroke-dashoffset: 100;
        animation: checkmark 1s ease-in-out forwards;
    }
    
    /* Timeline styles */
    .timeline-step.completed .timeline-circle {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    
    .timeline-line {
        top: 24px;
        height: 3px;
        transform: translateY(-50%);
    }
    
    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .print-only {
            display: block !important;
        }
        
        .bg-white {
            background-color: white !important;
        }
        
        .shadow-none-print {
            box-shadow: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pb-16">
    <!-- Booking Progress Timeline -->
    <div class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="relative flex items-center justify-between">
                <div class="flex items-center w-full">
                    <!-- Timeline steps -->
                    <div class="w-full grid grid-cols-3 gap-2">
                        <!-- Step 1: Search -->
                        <div class="timeline-step completed relative">
                            <div class="timeline-circle w-12 h-12 rounded-full border-2 flex items-center justify-center mx-auto">
                                <span class="text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="timeline-text text-center mt-2 text-sm">Search</div>
                            <!-- Timeline connector line -->
                            <div class="timeline-line absolute w-1/2 bg-green-500 right-0"></div>
                        </div>
                        
                        <!-- Step 2: Book -->
                        <div class="timeline-step completed relative">
                            <div class="timeline-circle w-12 h-12 rounded-full border-2 flex items-center justify-center mx-auto">
                                <span class="text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="timeline-text text-center mt-2 text-sm">Book</div>
                            <!-- Timeline connector line -->
                            <div class="timeline-line absolute w-full bg-green-500"></div>
                        </div>
                        
                        <!-- Step 3: Confirm -->
                        <div class="timeline-step completed relative">
                            <div class="timeline-circle w-12 h-12 rounded-full border-2 flex items-center justify-center mx-auto">
                                <span class="text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                            </div>
                            <div class="timeline-text text-center mt-2 text-sm">Confirm</div>
                            <!-- Timeline connector line -->
                            <div class="timeline-line absolute w-1/2 bg-green-500 left-0"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Confirmation Header -->
        <div class="text-center mb-8 animate-fade-in-up">
            <div class="inline-block mx-auto mb-4">
                <svg class="w-24 h-24" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" fill="#22c55e" />
                    <path class="checkmark" stroke="#fff" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" fill="none" d="M25 50 L40 65 L75 30" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Booking Confirmed!</h1>
            <p class="mt-2 text-gray-600">Your hotel reservation has been successfully booked and confirmed.</p>
        </div>

        <!-- E-Ticket Container -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 mb-8 shadow-none-print">
            <!-- Ticket Header -->
            <div class="bg-indigo-700 text-white p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">E-Ticket / Hotel Voucher</h2>
                        <p class="opacity-80">Present this at check-in</p>
                    </div>
                    <div>
                        <img src="{{ asset('images/logo.png') }}" alt="Ziara Travels Logo" class="h-12">
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between flex-wrap">
                    <div>
                        <p class="text-sm text-gray-500">Booking Reference</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->booking_reference }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Booking Date</p>
                        <p class="text-lg font-bold text-gray-900">{{ $booking->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Hotel Information -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Hotel Information</h3>
                
                @php
                    $hotelDetails = json_decode($booking->hotel_details);
                @endphp
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Hotel Name</p>
                        <p class="text-md font-medium text-gray-900">{{ $hotelDetails->name ?? 'Hotel information unavailable' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Room Type</p>
                        <p class="text-md font-medium text-gray-900">{{ $booking->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Check-in Date</p>
                        <p class="text-md font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('D, d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Check-out Date</p>
                        <p class="text-md font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('D, d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Guests</p>
                        <p class="text-md font-medium text-gray-900">
                            {{ $booking->adults }} {{ Str::plural('Adult', $booking->adults) }}
                            @if($booking->children > 0)
                                , {{ $booking->children }} {{ Str::plural('Child', $booking->children) }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Length of Stay</p>
                        <p class="text-md font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) }} 
                            {{ Str::plural('night', \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date))) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="text-md font-medium text-gray-900">{{ ucfirst($booking->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Status</p>
                        <p class="text-md font-medium text-gray-900">{{ ucfirst($booking->payment_status) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-md font-medium text-gray-900">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Important Information -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Important Information</h3>
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                    <li>Please present this e-ticket/voucher at check-in with a valid ID</li>
                    <li>Check-in time is typically after 2:00 PM and checkout time is typically before 12:00 PM</li>
                    <li>For any changes or cancellations, please contact us at least 24 hours before your check-in date</li>
                    <li>Additional charges may apply for amenities not included in your room package</li>
                </ul>
            </div>
            
            <!-- Barcode/QR Code (For illustration purposes) -->
            <div class="p-6 text-center">
                <div class="inline-block p-4 bg-gray-100 rounded-lg">
                    <svg class="h-32 w-32 mx-auto" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Placeholder for a real QR code -->
                        <rect x="10" y="10" width="80" height="80" fill="#000" />
                        <rect x="20" y="20" width="60" height="60" fill="#fff" />
                        <rect x="30" y="30" width="40" height="40" fill="#000" />
                        <rect x="40" y="40" width="20" height="20" fill="#fff" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">{{ $booking->booking_reference }}</p>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 justify-center no-print">
            <button onclick="window.print()" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg flex items-center space-x-2 hover:bg-indigo-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                <span>Print E-Ticket</span>
            </button>
            
            <a href="{{ route('hotels.search.form') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg flex items-center space-x-2 hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                <span>Book Another Hotel</span>
            </a>
            
            <a href="{{ route('home') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg flex items-center space-x-2 hover:bg-gray-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span>Return to Homepage</span>
            </a>
        </div>
    </div>
</div>

<!-- Toastify Container for notifications -->
<div id="toastContainer" class="fixed bottom-4 right-4 z-50"></div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show success toast on page load
        showToast('Booking confirmed successfully!', 'success');
    });
    
    // Toast notification functionality
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `px-6 py-3 rounded-lg shadow-lg flex items-center ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white transform transition-all duration-300 translate-y-20 opacity-0 mb-4`;
        
        toast.innerHTML = `
            <span class="mr-2">
                ${type === 'success' 
                    ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
                    : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>'
                }
            </span>
            <span>${message}</span>
        `;
        
        document.getElementById('toastContainer').appendChild(toast);
        
        // Show toast with animation
        setTimeout(() => {
            toast.classList.remove('translate-y-20', 'opacity-0');
        }, 100);
        
        // Hide toast after 5 seconds
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }
</script>
@endsection
