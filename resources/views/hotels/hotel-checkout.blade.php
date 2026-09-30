@extends('layouts.main')

@section('title', 'Secure Checkout - ' . ($booking['hotel']['name'] ?? 'Hotel') . ' - Ziara Travel')

@section('content')
@php
    $hotel = $booking['hotel'] ?? [];
    $params = $booking['search_params'] ?? [];
    $name = $hotel['name'] ?? 'Hotel booking';
    $total = floatval($hotel['totalPrice'] ?? 0);
    $price = floatval($hotel['price'] ?? 0);
    $currency = $hotel['currency'] ?? 'KES';
    $guest = $booking['guest_details'] ?? null;
    $reference = $booking['booking_reference'] ?? '';
    $bookingUrl = $booking['booking_url'] ?? '#';
    $nights = 1;
    if (!empty($params['checkin']) && !empty($params['checkout'])) {
        $nights = max(1, (int) \Carbon\Carbon::parse($params['checkin'])->diffInDays(\Carbon\Carbon::parse($params['checkout'])));
    }
@endphp

<style>
.ziara-hotel-checkout-page .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
.ziara-hotel-checkout-page .ziara-hotel-checkout__tile { background:var(--gold-pale);border:1px solid var(--gold-border);border-radius:12px; }
.ziara-hotel-checkout-page .fa-map-marker-alt,
.ziara-hotel-checkout-page .fa-user,
.ziara-hotel-checkout-page .fa-bolt { color:var(--gold); }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'Step 2 of 3',
    'title'  => 'Review &amp; <em>Confirm Your Stay</em>',
    'intro'  => 'Check your stay details, guest information and handover details before you continue.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Hotels', 'url' => route('hotels.search.form')],
        ['label' => 'Checkout', 'url' => route('hotels.search.form')],
    ],
])

<div class="ziara-paper ziara-hotel-checkout-page py-8" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="max-w-4xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Booking summary --}}
            <div class="md:col-span-2 space-y-6">
                <div class="ziara-card ziara-panel p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="ziara-panel__title" style="font-size:24px;">{{ $name }}</h2>
                            <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-blue-400 mr-1"></i>{{ $hotel['city'] ?? '' }}{{ !empty($hotel['address']) ? ' &middot; ' . $hotel['address'] : '' }}</p>
                        </div>
                        @if(!empty($hotel['imageUrl']))
                            <img src="{{ $hotel['imageUrl'] }}" alt="{{ $name }}" class="w-24 h-20 object-cover rounded-lg"
                                 onerror="this.onerror=null;this.src='https://placehold.co/200x160/e2e8f0/94a3b8?text=No+Image';">
                        @endif
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        <div class="ziara-hotel-checkout__tile rounded-lg p-3">
                            <p class="text-[11px] text-gray-500">Check-in</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($params['checkin'])->format('M d, Y') }}</p>
                        </div>
                        <div class="ziara-hotel-checkout__tile rounded-lg p-3">
                            <p class="text-[11px] text-gray-500">Check-out</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($params['checkout'])->format('M d, Y') }}</p>
                        </div>
                        <div class="ziara-hotel-checkout__tile rounded-lg p-3">
                            <p class="text-[11px] text-gray-500">Guests</p>
                            <p class="font-semibold text-gray-900">{{ $params['adults'] ?? 2 }} adults &middot; {{ $params['rooms'] ?? 1 }} room</p>
                        </div>
                        <div class="ziara-hotel-checkout__tile rounded-lg p-3">
                            <p class="text-[11px] text-gray-500">Duration</p>
                            <p class="font-semibold text-gray-900">{{ $nights }} {{ $nights > 1 ? 'nights' : 'night' }}</p>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100 flex items-end justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total for your stay</p>
                            <p class="ziara-price text-3xl">{{ number_format($total > 0 ? $total : $price, 0) }} <span class="text-sm font-normal text-gray-500">{{ $currency }}</span></p>
                        </div>
                    </div>
                </div>

                {{-- Guest details --}}
                <div class="ziara-card ziara-panel p-6">
                    <h3 class="ziara-panel__title" style="font-size:22px;margin-bottom:18px;">
                        <i class="fas fa-user mr-2 text-blue-500"></i> Who's checking in?
                    </h3>
                    @if($guest)
                        <div class="ziara-hotel-checkout__tile rounded-lg p-4 text-sm text-gray-700 space-y-1 mb-4">
                            <p><strong>{{ $guest['guest_name'] }}</strong></p>
                            <p>{{ $guest['guest_email'] }}</p>
                            <p>{{ $guest['guest_phone'] }}</p>
                        </div>
                    @else
                        <form action="{{ route('hotels.book') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @csrf
                            <div class="md:col-span-2">
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Full name</label>
                                <input type="text" name="guest_name" required placeholder="Jane Wanjiru"
                                       class="w-full px-3 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Email</label>
                                <input type="email" name="guest_email" required placeholder="you@example.com"
                                       class="w-full px-3 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-1.5">Phone / WhatsApp</label>
                                <input type="tel" name="guest_phone" required placeholder="+254 7XX XXX XXX"
                                       class="w-full px-3 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            </div>
                            <div class="md:col-span-2">
                                <button type="submit" class="ziara-btn ziara-btn--gold w-full">Save guest details</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Booking.com panel --}}
            <div class="space-y-5">
                <div class="ziara-card ziara-panel p-6 lg:sticky lg:top-24">
                    <h3 class="ziara-panel__title" style="font-size:22px;margin-bottom:8px;"><i class="fas fa-bolt mr-1 text-amber-500"></i> Complete your booking</h3>
                    <p class="text-sm text-gray-500 mb-4 leading-relaxed">
                        Reservations are made securely on Booking.com — the world's largest booking platform. You'll finish payment there.
                    </p>
                    <a href="{{ $bookingUrl }}" target="_blank" rel="nofollow noopener"
                       class="ziara-btn ziara-btn--maroon w-full mb-3">
                        <i class="fab fa-booking"></i> Continue on Booking.com
                    </a>
                    <form action="{{ route('hotels.payment', ['reference' => $reference]) }}" method="POST" onsubmit="return confirm('Only mark as complete if you finished booking on Booking.com.');">
                        @csrf
                        <button type="submit" class="ziara-btn ziara-btn--outline w-full text-xs">
                            I've completed my booking
                        </button>
                    </form>
                    <p class="text-[11px] text-gray-400 mt-3 leading-relaxed">
                        Reference: <span class="font-mono">{{ $reference }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection