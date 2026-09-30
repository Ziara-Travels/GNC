@extends('layouts.main')

@section('title', 'Hotel Booking Confirmation - Ziara Travel')

@section('content')
@php
    $hotel = $booking['hotel'] ?? [];
    $params = $booking['search_params'] ?? [];
    $name = $hotel['name'] ?? 'Stay';
    $total = floatval($hotel['totalPrice'] ?? 0);
    $currency = $hotel['currency'] ?? 'KES';
    $reference = $booking['booking_reference'] ?? '';
    $bookingUrl = $booking['booking_url'] ?? '#';
@endphp

<style>
.ziara-confirmation-page .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'Booking Confirmed',
    'title'  => 'Your Stay Is <em>Confirmed</em>',
    'intro'  => 'Your reservation details are ready and your booking handoff is complete.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Hotels', 'url' => route('hotels.search.form')],
        ['label' => 'Confirmation', 'url' => route('hotels.search.form')],
    ],
])

<section class="zt-section zt-section--pale ziara-confirmation-page" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        <div class="ziara-panel" style="max-width:760px;margin:0 auto;padding:48px;">
            <div style="width:88px;height:88px;margin:0 auto 24px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--gold-pale);color:var(--maroon);border:2px solid var(--gold);">
                <i class="fa-solid fa-check text-3xl" aria-hidden="true"></i>
            </div>
            <div style="text-align:center;margin-bottom:34px;">
                <h1 class="ziara-panel__title" style="font-size:38px;text-align:center;">Your stay is booked!</h1>
                <p style="margin:0;color:var(--muted);font-size:15.5px;line-height:1.75;">
                    We've passed your details to Booking.com — you should have received a confirmation email from them.
                </p>
            </div>

            <div style="padding:26px;background:var(--gold-pale);border:1px solid var(--gold-border);border-radius:14px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:22px;">
                    <div>
                        <h2 class="ziara-deal__title" style="font-size:27px;margin-bottom:5px;">{{ $name }}</h2>
                        <p style="margin:0;color:var(--muted);font-size:14px;">{{ $params['destination_label'] ?? ($hotel['city'] ?? '') }}</p>
                    </div>
                    <div class="ziara-deal__price" style="margin:0;padding:0;border:0;white-space:nowrap;">
                        <strong>{{ number_format($total, 0) }} <span style="font-size:13px;font-weight:500;">{{ $currency }}</span></strong>
                    </div>
                </div>
                <ul class="ziara-summary" style="border-top:1px solid var(--gold-border);padding-top:8px;">
                    <li>
                        <span><i class="fa-regular fa-calendar" aria-hidden="true" style="color:var(--gold);margin-right:7px;"></i>Stay dates</span>
                        <strong>{{ \Carbon\Carbon::parse($params['checkin'] ?? null)->format('M d') }} – {{ \Carbon\Carbon::parse($params['checkout'] ?? null)->format('M d, Y') }}</strong>
                    </li>
                    <li>
                        <span><i class="fa-solid fa-ticket" aria-hidden="true" style="color:var(--gold);margin-right:7px;"></i>Booking reference</span>
                        <strong style="font-family:monospace;">{{ $reference }}</strong>
                    </li>
                </ul>
            </div>

            @if(!empty($params['checkin']))
                <div style="text-align:center;margin-top:30px;">
                    <a href="{{ $bookingUrl }}" target="_blank" rel="nofollow noopener"
                       class="ziara-btn ziara-btn--gold">
                        <i class="fab fa-booking" aria-hidden="true"></i> View on Booking.com
                    </a>
                </div>
            @endif

            <p style="margin:24px 0 0;text-align:center;font-size:14px;color:var(--muted);">
                <a href="{{ route('home') }}" class="ziara-deal__btn" style="margin:0;padding:9px 18px;"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to home</a>
            </p>
        </div>
    </div>
</section>
@endsection
