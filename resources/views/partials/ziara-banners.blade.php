{{--
    Destination banners — mirrors the gsc-booking-banner "style-1" cards on
    ziaratravels.co.ke. Rendered in two slices to reproduce the reference
    layout: the first two cards sit beside the section heading, the remaining
    four fill the full-width row underneath.

    Usage:
        @include('partials.ziara-banners', ['from' => 0, 'to' => 2])
        @include('partials.ziara-banners', ['from' => 2])
--}}
@php
    // Each banner deep-links to its local tour category. Categories that the
    // reference splits by destination (Tanzania, Uganda, South Africa) have no
    // dedicated local category yet, so they fall back to the closest one.
    $banners = [
        ['title' => 'Kenya Safaris', 'image' => 'images/ziara/008.jpeg', 'category' => 'kenya-safaris'],
        ['title' => 'Tanzania Safaris', 'image' => 'images/ziara/010.jpeg', 'category' => 'international-packages'],
        ['title' => 'Uganda Safaris', 'image' => 'images/ziara/003.jpeg', 'category' => 'safaris'],
        ['title' => 'Dubai Packages', 'image' => 'images/ziara/011.jpeg', 'category' => 'dubai-package'],
        ['title' => 'South Africa Packages', 'image' => 'images/ziara/001.jpeg', 'category' => 'international-packages'],
        ['title' => 'Bangkok Packages', 'image' => 'images/ziara/005.jpeg', 'category' => 'bangkok-packages'],
    ];

    $from = $from ?? 0;
    $to = $to ?? count($banners);
@endphp

@foreach (array_slice($banners, $from, $to - $from) as $banner)
    <div class="gsc-booking-banner text-left style-1">
        <div class="listings-banner-content">
            <div class="banner-image">
                <img decoding="async" loading="lazy" src="{{ asset($banner['image']) }}" alt="{{ $banner['title'] }}">
            </div>
            <div class="banner-content">
                <h3 class="title">{{ $banner['title'] }}</h3>
            </div>
            <a class="link-term-overlay" href="{{ route('tours.category', $banner['category']) }}" aria-label="{{ $banner['title'] }}"></a>
        </div>
    </div>
@endforeach