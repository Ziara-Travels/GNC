{{--
    "Our Safaris" carousel on the homepage.

    Mirrors the reference `ziara-carousel` (25 tours, 3 columns, 4000ms
    autoplay). The card order comes from the `featured` flag written by
    TourSeeder, which preserves the order scraped from the reference site.
--}}
@php
    $safariTours = \App\Models\Tour::active()
        ->where('featured', true)
        ->orderBy('id')
        ->get();
@endphp

@if ($safariTours->isNotEmpty())
    <div class="ziara-carousel" data-ziara-carousel data-interval="4000" style="--ziara-cols:3;">
        <div class="ziara-carousel__track">
            @foreach ($safariTours as $safariTour)
                @include('partials.ziara-card', ['tour' => $safariTour])
            @endforeach
        </div>
    </div>
@endif