@extends('layouts.app')

@section('title', 'Booking Confirmation')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Success Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    @if($type === 'flight')
                        Flight Booking Confirmed!
                    @elseif($type === 'hotel')
                        Hotel Booking Confirmed!
                    @else
                        Booking Confirmed!
                    @endif
                </h1>
                <p class="text-gray-600">Your booking reference is: <span class="font-semibold">{{ $reference }}</span></p>
            </div>

            <!-- Booking Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Booking Information</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Reference:</span>
                            <span class="font-medium">{{ $reference }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium capitalize">{{ $type }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium">{{ now()->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">Confirmed</span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">What's Next?</h3>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            You will receive a confirmation email shortly
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            @if($type === 'flight')
                                Check-in online 24 hours before departure
                            @elseif($type === 'hotel')
                                Contact the hotel if you need special arrangements
                            @else
                                Review your booking details in your dashboard
                            @endif
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            View and manage your booking in your dashboard
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('user.dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 text-center">
                    View Dashboard
                </a>
                
                @if($type === 'flight')
                    <a href="{{ route('flights.search') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 text-center">
                        Book Another Flight
                    </a>
                @elseif($type === 'hotel')
                    <a href="{{ route('hotels.search') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 text-center">
                        Book Another Hotel
                    </a>
                @endif
                
                <a href="{{ route('user.bookings.all') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 text-center">
                    View All Bookings
                </a>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-gray-600 text-sm">
                    Need help? Contact our support team at 
                    <a href="mailto:support@gnctravels.com" class="text-blue-600 hover:text-blue-800">support@gnctravels.com</a>
                    or call <a href="tel:+1234567890" class="text-blue-600 hover:text-blue-800">+1 (234) 567-890</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
