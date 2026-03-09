@extends('layouts.main')

@section('title', 'Flight Booking Confirmation')

@section('content')
@if(isset($flight))
@php
    $firstSegment = $flight->itineraries[0]->segments[0];
    $segments = $flight->itineraries[0]->segments;
    $lastSegment = end($segments);
    $carrierCode = $firstSegment->carrierCode;
    $airlineName = isset($flight->dictionaries->carriers->{$carrierCode})
        ? $flight->dictionaries->carriers->{$carrierCode}
        : $carrierCode;
    $numberOfStops = count($segments) - 1;
    $cabin = $flight->travelerPricings[0]->fareDetailsBySegment[0]->cabin ?? 'N/A';
    $duration = $flight->itineraries[0]->duration ?? '';
    $durationFormatted = str_replace(['PT','H','M'], ['','h ','m'], $duration);
@endphp

<div class="bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 text-white py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
                <h1 class="text-2xl font-bold">Confirm Your Flight</h1>
            </div>
            <p class="text-slate-400 ml-13">Review the details below before proceeding to booking.</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Progress Steps --}}
        <div class="flex items-center justify-center gap-0 mb-10 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-semibold text-xs">1</span>
                <span class="font-medium text-gray-900">Search</span>
            </div>
            <div class="w-12 h-px bg-indigo-300 mx-2"></div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-semibold text-xs">2</span>
                <span class="font-medium text-gray-900">Review</span>
            </div>
            <div class="w-12 h-px bg-gray-300 mx-2"></div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-semibold text-xs">3</span>
                <span class="text-gray-400">Payment</span>
            </div>
            <div class="w-12 h-px bg-gray-300 mx-2"></div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-semibold text-xs">4</span>
                <span class="text-gray-400">Confirmed</span>
            </div>
        </div>

        {{-- Flight Summary Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-slate-800 text-white px-6 py-3 flex items-center justify-between">
                <h2 class="font-semibold text-sm"><i class="fas fa-plane mr-2"></i>Flight Summary</h2>
                <span class="text-xs bg-white/10 px-3 py-1 rounded-full">{{ $carrierCode }}-{{ $firstSegment->number }}</span>
            </div>
            <div class="p-6">
                {{-- Airline & Class --}}
                <div class="flex flex-wrap items-center gap-4 mb-6 text-sm">
                    <span class="font-semibold text-gray-900">{{ $airlineName }}</span>
                    <span class="bg-indigo-50 text-indigo-700 px-3 py-0.5 rounded-full text-xs font-medium">{{ ucwords(str_replace('_', ' ', strtolower($cabin))) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $numberOfStops === 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $numberOfStops === 0 ? 'Non-stop' : $numberOfStops.' '.($numberOfStops === 1 ? 'Stop' : 'Stops') }}
                    </span>
                    @if($durationFormatted)
                        <span class="text-gray-500"><i class="far fa-clock mr-1"></i>{{ $durationFormatted }}</span>
                    @endif
                </div>

                {{-- Departure → Arrival --}}
                <div class="flex items-center gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ date('H:i', strtotime($firstSegment->departure->at)) }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ date('D, M d', strtotime($firstSegment->departure->at)) }}</div>
                        <div class="text-sm font-bold text-indigo-600 mt-1">{{ $firstSegment->departure->iataCode }}</div>
                    </div>
                    <div class="flex-1 flex flex-col items-center px-4">
                        <div class="w-full flex items-center">
                            <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                            <div class="flex-1 h-px bg-indigo-300 mx-1 relative">
                                @if($numberOfStops > 0)
                                    @for($s = 0; $s < $numberOfStops; $s++)
                                        <div class="absolute top-1/2 -translate-y-1/2 w-2 h-2 bg-amber-500 rounded-full" style="left: {{ ($s + 1) * 100 / ($numberOfStops + 1) }}%"></div>
                                    @endfor
                                @endif
                            </div>
                            <i class="fas fa-plane text-indigo-600 text-xs"></i>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ date('H:i', strtotime($lastSegment->arrival->at)) }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ date('D, M d', strtotime($lastSegment->arrival->at)) }}</div>
                        <div class="text-sm font-bold text-indigo-600 mt-1">{{ $lastSegment->arrival->iataCode }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Price Breakdown --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-slate-800 text-white px-6 py-3">
                <h2 class="font-semibold text-sm"><i class="fas fa-receipt mr-2"></i>Price Breakdown</h2>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($flight->travelerPricings as $index => $traveler)
                        <div class="flex justify-between items-center py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <span class="font-medium text-gray-900">{{ ucfirst(strtolower($traveler->travelerType)) }} {{ $index + 1 }}</span>
                                <span class="text-gray-400 mx-2">&middot;</span>
                                <span class="text-sm text-gray-500">Base: {{ $flight->price->currency }} {{ $traveler->price->base }}</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ $flight->price->currency }} {{ $traveler->price->total }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t-2 border-gray-200 flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-2xl font-extrabold text-indigo-600">{{ $flight->price->currency }} {{ $flight->price->total }}</span>
                </div>
            </div>
        </div>

        @guest
            {{-- Login Prompt --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Login to Complete Your Booking</h3>
                <p class="text-gray-600 mb-6">Create an account or sign in to proceed with your reservation.</p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center bg-white hover:bg-gray-50 text-indigo-600 border border-indigo-200 px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-user-plus mr-2"></i> Create Account
                    </a>
                </div>
            </div>
        @else
            {{-- Booking Form --}}
            <form action="{{ route('booking.create') }}" method="POST">
                @csrf
                <input type="hidden" name="flight_data" value="{{ json_encode($flight) }}">

                {{-- Passengers --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="bg-slate-800 text-white px-6 py-3">
                        <h2 class="font-semibold text-sm"><i class="fas fa-users mr-2"></i>Passenger Information</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        @for($i = 0; $i < count($flight->travelerPricings); $i++)
                            <div class="{{ $i > 0 ? 'pt-5 border-t border-gray-100' : '' }}">
                                <h3 class="font-semibold text-gray-800 mb-3">
                                    Passenger {{ $i + 1 }}
                                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full ml-2">{{ ucfirst(strtolower($flight->travelerPricings[$i]->travelerType)) }}</span>
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" name="passengers[{{ $i }}][firstName]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" name="passengers[{{ $i }}][lastName]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                        <input type="date" name="passengers[{{ $i }}][dob]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                        <select name="passengers[{{ $i }}][gender]" required
                                            class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">Select Gender</option>
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="bg-slate-800 text-white px-6 py-3">
                        <h2 class="font-semibold text-sm"><i class="fas fa-envelope mr-2"></i>Contact Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="contact[email]" value="{{ Auth::user()->email }}" required
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" name="contact[phone]" required
                                    class="w-full border border-gray-300 rounded-lg py-2.5 px-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-between items-center">
                    <a href="{{ route('flights.results') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 text-sm font-medium transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Results
                    </a>
                    <button type="submit" class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3 rounded-lg font-semibold text-sm transition shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i> Confirm &amp; Book
                    </button>
                </div>
            </form>
        @endguest
    </div>
</div>

@else
    {{-- No Flight Data --}}
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center max-w-md">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Flight Data Found</h3>
            <p class="text-gray-500 mb-6">Please return to the search page and try again.</p>
            <a href="/" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-search mr-2"></i> New Search
            </a>
        </div>
    </div>
@endif
@endsection