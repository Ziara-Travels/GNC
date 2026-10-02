@extends('layouts.main')

@section('title', $tour->name . ' | Ziara Travels')

@section('content')
@php
    $pricingOptions = $tour->pricing_options ?? [];
    $gallery = $tour->gallery_urls;
    $waNumber = '254786336633';
    $waText = rawurlencode('Hello Ziara Travels, I would like to book the "' . $tour->name . '" tour. Please share more details.');
@endphp

<div class="ziara-single">
    <section class="ziara-single__hero"
        style="background-image: linear-gradient(180deg, rgba(28,22,18,.32) 0%, rgba(74,29,19,.86) 100%), url('{{ $tour->hero_image_url }}'); background-size: cover; background-position: center;">
        <span class="ziara-single__hero-overlay" aria-hidden="true"></span>

        <div class="ziara-single__hero-inner">
            <nav class="ziara-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <a href="{{ route('tours.index') }}">Tours</a>
                @if ($tour->category_parent !== $tour->category)
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <a href="{{ route('tours.category', $tour->category_parent) }}">{{ \App\Models\Tour::categoryLabel($tour->category_parent) }}</a>
                @endif
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <a href="{{ route('tours.category', $tour->category) }}">{{ $tour->category_label }}</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">{{ $tour->name }}</span>
            </nav>

            <h1 class="ziara-single__title">{{ $tour->name }}</h1>
            <p class="ziara-single__loc">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>{{ $tour->city }}
            </p>
        </div>
    </section>

    <div class="ziara-single__wrap">
        <div class="ziara-single__content">
            <figure class="ziara-single__feature">
                <img src="{{ $tour->hero_image_url }}" alt="{{ $tour->name }}" loading="lazy" decoding="async">
            </figure>

            @if (! empty($tour->included_services))
                <section class="ziara-block">
                    <h2 class="ziara-block__title">Inclusions</h2>
                    <div class="ziara-incl">
                        <div class="ziara-incl__col">
                            <h3 class="ziara-sec__title">What's Included</h3>
                            <ul class="ziara-checklist">
                                @foreach ($tour->included_services as $inclusion)
                                    <li><i class="fa-solid fa-check" aria-hidden="true"></i>{{ $inclusion }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>
            @endif

            @if (count($gallery))
                <section class="ziara-block">
                    <h2 class="ziara-block__title">Gallery</h2>
                    <div class="ziara-gallery">
                        @foreach ($gallery as $image)
                            <a href="{{ $image }}" class="ziara-gallery__item" target="_blank" rel="noopener">
                                <img src="{{ $image }}" alt="{{ $tour->name }}" loading="lazy" decoding="async">
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="ziara-single__aside">
            <div class="ziara-single__box">
                @if (count($pricingOptions))
                    <div class="ziara-single__pricing">
                        <span class="ziara-single__pricing-head">Pricing</span>
                        <ul class="ziara-pricing">
                            @foreach ($pricingOptions as $option)
                                <li class="ziara-pricing__note"><i class="fa-solid fa-tag" aria-hidden="true"></i>{{ $option }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <ul class="ziara-single__facts">
                    <li>
                        <i class="fa-regular fa-clock" aria-hidden="true"></i>
                        <span>Duration</span><b>{{ $tour->duration }}</b>
                    </li>
                    <li>
                        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                        <span>Location</span><b>{{ $tour->city }}</b>
                    </li>
                </ul>

                <a class="ziara-single__btn" href="{{ route('tours.booking', $tour->slug) }}">
                    Book This Tour <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
                <a class="ziara-single__wa" href="https://wa.me/{{ $waNumber }}?text={{ $waText }}"
                    target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Enquire on WhatsApp
                </a>
            </div>
        </aside>
    </div>

    @if ($relatedTours->isNotEmpty())
        <section class="ziara-single__related">
            <h2 class="ziara-single__related-title">Related Tours</h2>
            <div class="ziara-tours" style="--ziara-cols:3;">
                @foreach ($relatedTours as $relatedTour)
                    @include('partials.ziara-card', ['tour' => $relatedTour])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection