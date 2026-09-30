@extends('layouts.main')

@section('title', 'Hotel Details - Ziara Travel')

@section('content')
@php
    $id = $hotelData['hotel_id'] ?? '';
    $name = $hotelData['name'] ?? 'Hotel';
    $photo = $hotelData['photo'] ?? '';
    $address = $hotelData['address'] ?? '';
    $stars = $hotelData['stars'] ?? $hotelData['star_rating'] ?? 0;
    $rating = $hotelData['rating'] ?? $hotelData['guest_review_score'] ?? 0;
    $reviewCount = $hotelData['review_count'] ?? 0;
    $amenities = $hotelData['amenities'] ?? [];
    $description = $hotelData['description'] ?? '';
    $price = $hotelData['price'] ?? 0;
    $currency = $hotelData['currency'] ?? 'KES';
    $bookingUrl = $hotelData['booking_url'] ?? '#';
    $bookingUrlReady = !empty($bookingUrl) && $bookingUrl !== '#';
    $city = $hotelData['city'] ?? '';
@endphp

<style>
.ziara-hotel-detail .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
.ziara-hotel-detail__amenities { display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:10px; }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'Where You Will Stay',
    'title'  => 'Discover <em>' . $name . '</em>',
    'intro'  => 'Review the property, amenities and nightly pricing before you complete your booking.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Hotels', 'url' => route('hotels.search.form')],
        ['label' => $name, 'url' => route('hotels.show', ['hotelId' => $id])],
    ],
])

<section class="zt-section zt-section--pale ziara-hotel-detail" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        <div class="ziara-split" style="align-items:start;gap:44px;">
            <div>
                <div class="ziara-panel" style="padding:0;overflow:hidden;margin-bottom:30px;">
                    <img src="{{ $photo }}" alt="{{ $name }}" style="width:100%;height:400px;object-fit:cover;display:block;">
                </div>

                <div class="ziara-panel" style="margin-bottom:26px;">
                    <h2 class="ziara-panel__title" style="font-size:26px;">About This Property</h2>
                    <p style="margin:0;color:var(--muted);line-height:1.85;">{{ $description }}</p>
                </div>

                <div class="ziara-panel">
                    <h2 class="ziara-panel__title" style="font-size:26px;">Amenities</h2>
                    <div class="ziara-hotel-detail__amenities">
                        @if(is_array($amenities))
                            @foreach($amenities as $amenity)
                                <div class="ziara-minifeature">
                                    <span class="ziara-minifeature__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                                    <div>
                                        <h3 class="ziara-minifeature__title" style="font-size:16px;">{{ $amenity }}</h3>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="ziara-minifeature">
                                <span class="ziara-minifeature__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                                <div>
                                    <h3 class="ziara-minifeature__title" style="font-size:16px;">{{ $amenities }}</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside>
                <div class="ziara-panel" style="position:sticky;top:104px;">
                    <div class="ziara-deal__badge" style="position:static;display:inline-flex;margin-bottom:16px;">
                        <i class="fa-solid fa-star" aria-hidden="true"></i> {{ $stars }}-Star Stay
                    </div>
                    <h2 class="ziara-panel__title" style="font-size:29px;">{{ $name }}</h2>
                    <p style="margin:6px 0 18px;color:var(--muted);font-size:14.5px;">
                        <i class="fa-solid fa-location-dot" aria-hidden="true" style="color:var(--gold);margin-right:6px;"></i>{{ $address }}
                    </p>

                    <div style="display:flex;align-items:center;gap:14px;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid var(--line);">
                        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:52px;padding:9px 12px;border-radius:12px;background:var(--maroon);color:var(--gold-bright);font-weight:700;font-size:17px;">{{ number_format((float)$rating, 1) }}</span>
                        <div>
                            <div style="color:var(--gold);font-size:13px;letter-spacing:1px;">★★★★★</div>
                            <div style="font-size:13px;color:var(--muted);">{{ number_format((float)$reviewCount) }} guest reviews</div>
                        </div>
                    </div>

                    <div class="ziara-deal__price" style="padding:0;border:0;margin-bottom:20px;">
                        <strong>{{ number_format((float)$price, 0) }} <span style="font-size:14px;font-weight:500;">{{ $currency }}</span></strong>
                        <small style="color:var(--muted);">per night</small>
                    </div>

                    @if($bookingUrlReady)
                        <a href="{{ $bookingUrl }}" target="_blank" rel="nofollow noopener" class="ziara-btn ziara-btn--gold" style="width:100%;">
                            <i class="fab fa-booking" aria-hidden="true"></i> Reserve This Stay
                        </a>
                        <a href="{{ route('hotels.results') }}" class="ziara-btn ziara-btn--outline" style="width:100%;margin-top:10px;">
                            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Browse More Hotels
                        </a>
                    @else
                        <button disabled class="ziara-btn ziara-btn--gold" style="width:100%;cursor:not-allowed;opacity:0.6;">
                            Unavailable
                        </button>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
