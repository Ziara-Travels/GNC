@extends('layouts.user-dashboard')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
            <div>
                <h3 class="text-[20px] md:text-[24px] font-bold text-[#181818] dark:text-white mb-2">
                    Pending Payments
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Complete your pending booking payments to confirm your reservations
                </p>
            </div>
            <div class="text-right">
                <span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                    {{ $pendingBookings->count() }} Pending
                </span>
            </div>
        </div>

        <div class="trezo-card-content">
            @if($pendingBookings->count() > 0)
                <div class="grid gap-6">
                    @foreach($pendingBookings as $booking)
                    <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0">
                                        @if($booking->booking_type === 'flight')
                                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <i class="material-symbols-outlined text-blue-600 dark:text-blue-400">flight</i>
                                            </div>
                                        @elseif($booking->booking_type === 'hotel')
                                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <i class="material-symbols-outlined text-green-600 dark:text-green-400">hotel</i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $booking->title }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Booking Reference: <span class="font-mono font-medium">{{ $booking->booking_reference }}</span>
                                        </p>
                                        @if($booking->booked_by !== $booking->user_id)
                                            <p class="text-sm text-orange-600 dark:text-orange-400">
                                                Booked by: {{ $booking->bookedBy->name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                    @if($booking->booking_type === 'flight')
                                        <div class="text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Route:</span>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $booking->departure_airport }} → {{ $booking->arrival_airport }}
                                            </p>
                                        </div>
                                        @if($booking->departure_time)
                                        <div class="text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Departure:</span>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $booking->departure_time->format('M d, Y H:i') }}
                                            </p>
                                        </div>
                                        @endif
                                    @elseif($booking->booking_type === 'hotel')
                                        @if($booking->check_in_date)
                                        <div class="text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Check-in:</span>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $booking->check_in_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        @endif
                                        @if($booking->check_out_date)
                                        <div class="text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Check-out:</span>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $booking->check_out_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                        @endif
                                    @endif
                                    <div class="text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">Created:</span>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $booking->created_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">Status:</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Payment Required
                                        </span>
                                    </div>
                                </div>

                                @if($booking->created_at->addHours(24)->isPast())
                                <div class="bg-red-100 dark:bg-red-900/50 border border-red-300 dark:border-red-700 rounded-md p-3 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="material-symbols-outlined text-red-400 text-sm">warning</i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-800 dark:text-red-200">
                                                <strong>Urgent:</strong> This booking is overdue for payment. Please complete payment immediately to avoid cancellation.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="mt-4 lg:mt-0 lg:ml-6 flex-shrink-0">
                                <div class="text-right mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Amount Due</p>
                                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                        {{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    @if($booking->booking_type === 'flight')
                                        <a href="{{ route('flights.booking.payment', $booking->id) }}" 
                                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="material-symbols-outlined text-sm mr-2">payment</i>
                                            Pay Now
                                        </a>
                                    @elseif($booking->booking_type === 'hotel')
                                        <a href="{{ route('hotels.booking.payment', $booking->id) }}" 
                                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                            <i class="material-symbols-outlined text-sm mr-2">payment</i>
                                            Pay Now
                                        </a>
                                    @endif
                                    <a href="{{ route('bookings.show', $booking->id) }}" 
                                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <i class="material-symbols-outlined text-sm mr-2">visibility</i>
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="material-symbols-outlined text-4xl text-green-600 dark:text-green-400">check_circle</i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        No Pending Payments
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        All your bookings are paid and confirmed. Great job!
                    </p>
                    <div class="space-x-4">
                        <a href="{{ route('flights.search') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="material-symbols-outlined text-sm mr-2">flight_takeoff</i>
                            Search Flights
                        </a>
                        <a href="{{ route('hotels.search') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="material-symbols-outlined text-sm mr-2">hotel</i>
                            Search Hotels
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
