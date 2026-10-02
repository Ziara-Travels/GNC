@php
    /**
     * Reference tour card. Mirrors the `ziara-card` markup used across the
     * ziaratravels.co.ke homepage carousel, tours listing and related-tour rows.
     *
     * @var \App\Models\Tour $tour
     */
    $priceText = $tour->pricing_options[0] ?? trim($tour->currency . ' ' . number_format((float) $tour->price, 2));
@endphp

<article class="ziara-card">
    <a class="ziara-card__media" href="{{ route('tours.show', $tour->slug) }}">
        <img class="ziara-card__img" src="{{ $tour->card_image_url }}" alt="{{ $tour->name }}" loading="lazy" decoding="async">
        <span class="ziara-card__shade" aria-hidden="true"></span>
        <span class="ziara-card__loc"><i class="fa-solid fa-location-dot" aria-hidden="true"></i>{{ $tour->city }}</span>
    </a>

    <div class="ziara-card__body">
        <h3 class="ziara-card__title">
            <a href="{{ route('tours.show', $tour->slug) }}">{{ $tour->name }}</a>
        </h3>

        @if ($tour->card_excerpt)
            <p class="ziara-card__desc">{{ $tour->card_excerpt }}</p>
        @endif

        @if ($tour->duration)
            <div class="ziara-card__meta">
                <span><i class="fa-regular fa-clock" aria-hidden="true"></i>{{ $tour->duration }}</span>
            </div>
        @endif

        <div class="ziara-card__foot">
            <span class="ziara-card__price">
                <span class="ziara-card__price-prefix">From</span>
                <span class="ziara-card__price-amount">{{ $priceText }}</span>
            </span>
            <a class="ziara-card__btn" href="{{ route('tours.show', $tour->slug) }}">
                View Details <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</article>