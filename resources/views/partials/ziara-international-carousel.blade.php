{{--
    International packages carousel.

    Mirrors the reference `ziara-carousel` for non-Kenyan destinations
    (Bangkok, Cape Town, Dubai, Zanzibar) and reads from the seeded tour
    catalogue instead of hard-coded markup.
--}}
@php
    $internationalCategories = collect(\App\Models\Tour::active()->distinct()->pluck('category'))
        ->filter(fn ($category) => \App\Models\Tour::parentCategory($category) === 'international-packages')
        ->values();

    $internationalTours = \App\Models\Tour::active()
        ->whereIn('category', $internationalCategories)
        ->orderBy('name')
        ->get();
@endphp

@if ($internationalTours->isNotEmpty())
    <div class="ziara-carousel" data-ziara-carousel data-interval="4000" style="--ziara-cols:3;">
        <div class="ziara-carousel__track">
            @foreach ($internationalTours as $tour)
                @include('partials.ziara-card', ['tour' => $tour])
            @endforeach
        </div>
    </div>
@endif