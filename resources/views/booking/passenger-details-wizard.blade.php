@extends('layouts.main')

@php
    $fromCity = $flightData['itineraries'][0]['segments'][0]['departure']['iataCode'] ?? 'N/A';
    $lastSegments = $flightData['itineraries'][0]['segments'];
    $toCity = $lastSegments[count($lastSegments) - 1]['arrival']['iataCode'] ?? 'N/A';
    $departureAt = $flightData['itineraries'][0]['segments'][0]['departure']['at'] ?? null;
    $totalPrice = $flightData['price']['total'] ?? 0;
    $currency = $flightData['price']['currency'] ?? 'USD';

    $typeLabels = [
        'ADULT' => ['Adult', 'ff-badge-adult'],
        'CHILD' => ['Child', 'ff-badge-child'],
        'HELD_INFANT' => ['Infant', 'ff-badge-infant'],
        'SEATED_INFANT' => ['Infant', 'ff-badge-infant'],
    ];
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/flight-flow.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="relative bg-gradient-to-br from-blue-800 via-indigo-900 to-purple-900 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="flex items-center justify-between mb-8">
                    <button onclick="history.back()" class="flex items-center gap-2 text-white/90 hover:text-white transition-colors">
                        <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            <i data-lucide="arrow-left" class="h-5 w-5"></i>
                        </div>
                        <span class="font-medium">Back</span>
                    </button>
                    <div class="flex items-center gap-2 text-white/90">
                        <i data-lucide="shield-check" class="h-5 w-5"></i>
                        <span class="text-sm">Secure Checkout</span>
                    </div>
                </div>

                <div class="ff-step-track mb-6">
                    <div class="ff-step is-active">
                        <span class="ff-step-dot">1</span> Passengers
                    </div>
                    <span class="ff-step-line"></span>
                    <div class="ff-step">
                        <span class="ff-step-dot">2</span> Payment
                    </div>
                    <span class="ff-step-line"></span>
                    <div class="ff-step">
                        <span class="ff-step-dot">3</span> Confirmation
                    </div>
                </div>

                <h1 class="text-4xl font-bold text-white text-center mb-2">Who's Traveling?</h1>
                <p class="text-blue-100 text-center">{{ $fromCity }} &rarr; {{ $toCity }}
                    @if($departureAt) &bull; {{ \Carbon\Carbon::parse($departureAt)->format('D, d M Y') }} @endif
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-8 pb-16">
        <div class="max-w-4xl mx-auto">
            @if ($errors->any())
                <div class="ff-card p-4 mb-6 border-rose-200 bg-rose-50">
                    <p class="font-semibold text-rose-700 mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('booking.passengers.save') }}">
                @csrf

                <div class="ff-card p-6 mb-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Contact Information</h2>
                    <p class="text-sm text-slate-500 mb-4">We'll send your booking confirmation and e-ticket here.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                            <input type="email" name="contact_email" required
                                value="{{ old('contact_email', auth()->user()->email ?? '') }}"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                            <input type="tel" name="contact_phone" required
                                value="{{ old('contact_phone', auth()->user()->phone ?? '') }}"
                                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                @foreach ($travelerTypes as $index => $type)
                    @php [$label, $badgeClass] = $typeLabels[$type] ?? ['Traveler', 'ff-badge']; @endphp
                    <div class="ff-card p-6 mb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <h2 class="text-lg font-bold text-slate-900">Passenger {{ $index + 1 }}</h2>
                            <span class="ff-badge {{ $badgeClass }}">{{ $label }}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">First Name</label>
                                <input type="text" name="passengers[{{ $index }}][first_name]" required
                                    value="{{ old("passengers.$index.first_name") }}"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Last Name</label>
                                <input type="text" name="passengers[{{ $index }}][last_name]" required
                                    value="{{ old("passengers.$index.last_name") }}"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Date of Birth</label>
                                <input type="date" name="passengers[{{ $index }}][date_of_birth]" required
                                    value="{{ old("passengers.$index.date_of_birth") }}"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Gender</label>
                                <select name="passengers[{{ $index }}][gender]" required
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select</option>
                                    <option value="male" {{ old("passengers.$index.gender") == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old("passengers.$index.gender") == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Nationality (2-letter code)</label>
                                <input type="text" maxlength="2" placeholder="KE" name="passengers[{{ $index }}][nationality]" required
                                    value="{{ old("passengers.$index.nationality") }}"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg uppercase focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Passport Number</label>
                                <input type="text" name="passengers[{{ $index }}][passport_number]" required
                                    value="{{ old("passengers.$index.passport_number") }}"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Passport Expiry Date</label>
                                <input type="date" name="passengers[{{ $index }}][passport_expiry]" required
                                    value="{{ old("passengers.$index.passport_expiry") }}"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Passport Issuing Country (2-letter code)</label>
                                <input type="text" maxlength="2" placeholder="KE" name="passengers[{{ $index }}][passport_country]" required
                                    value="{{ old("passengers.$index.passport_country") }}"
                                    class="w-full px-4 py-2.5 border border-slate-300 rounded-lg uppercase focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="ff-card p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Total price</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $currency }} {{ number_format($totalPrice, 2) }}</p>
                    </div>
                    <button type="submit" class="ff-btn-primary">
                        Continue to Payment
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
    </script>
@endpush
