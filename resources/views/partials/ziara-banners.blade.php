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
    $banners = [
        ['title' => 'Kenya Safaris', 'image' => 'images/ziara/008.jpeg', 'url' => 'https://ziaratravels.co.ke/kenya-safaris/'],
        ['title' => 'Tanzania Safaris', 'image' => 'images/ziara/010.jpeg', 'url' => 'https://ziaratravels.co.ke/tanzania-safaris/'],
        ['title' => 'Uganda Safaris', 'image' => 'images/ziara/003.jpeg', 'url' => 'https://ziaratravels.co.ke/uganda-safaris/'],
        ['title' => 'Dubai Packages', 'image' => 'images/ziara/011.jpeg', 'url' => 'https://ziaratravels.co.ke/dubai-packages/'],
        ['title' => 'South Africa Packages', 'image' => 'images/ziara/001.jpeg', 'url' => 'https://ziaratravels.co.ke/south-africa-packages/'],
        ['title' => 'Bangkok Packages', 'image' => 'images/ziara/005.jpeg', 'url' => 'https://ziaratravels.co.ke/bangkok-packages/'],
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
            <a class="link-term-overlay" href="{{ $banner['url'] }}" aria-label="{{ $banner['title'] }}"></a>
        </div>
    </div>
@endforeach