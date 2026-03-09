@extends('layouts.dashboard')

@section('title', 'Booking Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('user.bookings.all') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Bookings
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">
                            {{ ucfirst($booking->booking_type) }} Booking
                        </h1>
                        <p class="text-blue-100">Reference: {{ $booking->reference_number }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-blue-100 mb-1">Status</div>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                            @if($booking->status === 'confirmed') bg-green-500 text-white
                            @elseif($booking->status === 'pending') bg-yellow-500 text-white
                            @elseif($booking->status === 'cancelled') bg-red-500 text-white
                            @else bg-gray-500 text-white
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Trip Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            Trip Information
                        </h3>
                        
                        @if($booking->booking_type === 'flight')
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">From:</span>
                                    <span class="font-medium">{{ $booking->origin }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">To:</span>
                                    <span class="font-medium">{{ $booking->destination }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Departure:</span>
                                    <span class="font-medium">
                                        {{ $booking->departure_date ? Carbon\Carbon::parse($booking->departure_date)->format('M d, Y H:i') : 'N/A' }}
                                    </span>
                                </div>
                                @if($booking->return_date)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Return:</span>
                                    <span class="font-medium">
                                        {{ Carbon\Carbon::parse($booking->return_date)->format('M d, Y H:i') }}
                                    </span>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Passengers:</span>
                                    <span class="font-medium">{{ $booking->adults + $booking->children + $booking->infants }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Class:</span>
                                    <span class="font-medium">{{ ucfirst($booking->travel_class ?? 'Economy') }}</span>
                                </div>
                            </div>
                        @elseif($booking->booking_type === 'hotel')
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Hotel:</span>
                                    <span class="font-medium">{{ $booking->destination }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Check-in:</span>
                                    <span class="font-medium">
                                        {{ $booking->departure_date ? Carbon\Carbon::parse($booking->departure_date)->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Check-out:</span>
                                    <span class="font-medium">
                                        {{ $booking->return_date ? Carbon\Carbon::parse($booking->return_date)->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Guests:</span>
                                    <span class="font-medium">{{ $booking->adults }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Rooms:</span>
                                    <span class="font-medium">{{ $booking->rooms ?? 1 }}</span>
                                </div>
                            </div>
                        @endif

                        @if($booking->special_requests)
                        <div class="mt-4">
                            <span class="text-gray-600">Special Requests:</span>
                            <p class="mt-1 text-sm text-gray-800 bg-gray-50 p-2 rounded">{{ $booking->special_requests }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Passenger/Guest Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                            @if($booking->booking_type === 'flight') Passenger @else Guest @endif Information
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Name:</span>
                                <span class="font-medium">{{ $booking->passenger_name ?? $booking->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium">{{ $booking->passenger_email ?? $booking->user->email }}</span>
                            </div>
                            @if($booking->passenger_phone)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $booking->passenger_phone }}</span>
                            </div>
                            @endif
                            @if($booking->passport_number)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Passport:</span>
                                <span class="font-medium">{{ $booking->passport_number }}</span>
                            </div>
                            @endif
                        </div>

                        @if($booking->bookedBy && $booking->bookedBy->id !== $booking->user_id)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Booked By</h4>
                            <div class="space-y-1 text-sm">
                                <div>{{ $booking->bookedBy->name }}</div>
                                <div class="text-gray-600">{{ $booking->bookedBy->email }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Amount:</span>
                                <span class="font-semibold text-lg">{{ $booking->currency }} {{ number_format($booking->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="font-medium
                                    @if($booking->payment_status === 'paid') text-green-600
                                    @elseif($booking->payment_status === 'pending') text-yellow-600
                                    @else text-red-600
                                    @endif">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </div>
                            @if($booking->payment_method)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium">{{ ucfirst($booking->payment_method) }}</span>
                            </div>
                            @endif
                        </div>

                        @if($booking->payment_status === 'pending')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h4 class="text-yellow-800 font-medium mb-2">Payment Required</h4>
                            <p class="text-yellow-700 text-sm mb-3">Complete your payment to confirm this booking.</p>
                            @if($booking->booking_type === 'flight')
                                <a href="{{ route('flights.booking.payment', $booking->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-sm">
                                    Pay Now
                                </a>
                            @elseif($booking->booking_type === 'hotel')
                                <a href="{{ route('hotels.booking.payment', $booking->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 text-sm">
                                    Pay Now
                                </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex flex-wrap gap-4">
                    <a href="{{ route('bookings.download', $booking->id) }}" target="_blank" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        Download Confirmation
                    </a>
                    
                    @if($booking->status === 'confirmed' && $booking->payment_status === 'paid')
                        <button class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition duration-200" onclick="window.print()">
                            Print Details
                        </button>
                    @endif

                    @if($booking->status === 'confirmed' && $booking->booking_type === 'flight')
                        <a href="#" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                            Check-in Online
                        </a>
                    @endif
                </div>

                <!-- Booking Timeline -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Timeline</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">
                                Booking created on {{ $booking->created_at->format('M d, Y \a\t H:i') }}
                            </span>
                        </div>
                        @if($booking->payment_status === 'paid')
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <span class="text-sm text-gray-600">
                                Payment confirmed on {{ $booking->updated_at->format('M d, Y \a\t H:i') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
