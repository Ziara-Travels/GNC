@extends('layouts.main')

@section('title', $tour->name)

@section('content')
<style>
.ziara-single__tags { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px; }
.ziara-single__tag { padding: 6px 15px; border-radius: 50px; font-size: 10.5px; font-weight: 700; letter-spacing: 1.1px; text-transform: uppercase; }
.ziara-single__tag--gold { background: var(--gold-bright); color: var(--maroon-deep); box-shadow: 0 4px 12px rgba(243,210,74,.4); }
.ziara-single__tag--line { background: rgba(255,255,255,.14); color: #fff; border: 1px solid rgba(201,162,39,.6); }
.ziara-single__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 26px; margin-top: 18px; }
.ziara-hero__price {
    background: rgba(28,14,10,.72);
    border: 1px solid rgba(201,162,39,.5);
    border-radius: 18px;
    padding: 26px 24px;
    backdrop-filter: blur(6px);
    text-align: center;
}
.ziara-hero__price .ziara-single__tag--gold { display: inline-block; margin-bottom: 14px; }
.ziara-hero__price s { display: block; font-size: 15px; color: rgba(255,255,255,.62); }
.ziara-hero__price .amt { display: block; font-family: var(--font-display); font-size: 40px; font-weight: 700; line-height: 1; color: var(--gold-bright); }
.ziara-hero__price .per { display: block; margin-top: 8px; font-size: 12.5px; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,.72); }
.ziara-rate-row { display: flex; align-items: center; gap: 14px; font-size: 14px; }
.ziara-rate-row b { width: 62px; font-weight: 600; color: var(--muted); }
.ziara-rate-row i { flex: 1; height: 8px; border-radius: 999px; background: var(--gold-pale); overflow: hidden; display: block; }
.ziara-rate-row i span { display: block; height: 100%; border-radius: 999px; background: linear-gradient(90deg, var(--maroon) 0%, var(--gold) 100%); }
.ziara-rate-row em { width: 46px; text-align: right; font-style: normal; color: var(--muted); }
.ziara-review__head { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
.ziara-review__head img { width: 50px; height: 50px; border-radius: 50%; flex-shrink: 0; }
.ziara-review__head strong { display: block; font-size: 15px; color: var(--ink); }
.ziara-review__head small { color: var(--muted); font-size: 13px; }
.ziara-review__stars { margin-left: auto; color: var(--gold); font-size: 14px; letter-spacing: 2px; }
.ziara-review__quote { font-family: var(--font-display); font-size: 20px; font-weight: 600; color: var(--maroon-deep); margin: 0 0 8px; }
.ziara-review__text { font-size: 14.5px; line-height: 1.75; color: var(--muted); margin: 0; }
.ziara-related { display: flex; gap: 14px; align-items: stretch; padding: 14px 0; border-bottom: 1px dashed var(--line); }
.ziara-related:last-child { border-bottom: 0; padding-bottom: 0; }
.ziara-related img { width: 104px; height: 78px; border-radius: 10px; object-fit: cover; flex-shrink: 0; }
.ziara-related h6 { font-family: var(--font-display); font-size: 17px; font-weight: 700; color: var(--maroon-deep); margin: 0 0 3px; }
.ziara-related .ziara-related__stars { color: var(--gold); font-size: 12px; letter-spacing: 1px; }
.ziara-related__price { font-weight: 700; color: var(--maroon); font-size: 14.5px; }
a.ziara-related:hover h6 { color: var(--gold-dim); }
</style>

<div class="ziara-single">
    <section class="ziara-single__hero" style="background-image: linear-gradient(180deg, rgba(28,22,18,.30) 0%, rgba(74,29,19,.86) 100%), url('{{ $tour->main_image }}'); background-size: cover; background-position: center;">
        <div class="ziara-single__hero-overlay"></div>
        <div class="ziara-single__hero-inner">
            <nav class="ziara-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <a href="{{ route('tours.index') }}">Tours</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">{{ $tour->name }}</span>
            </nav>
            <div style="display: flex; flex-wrap: wrap; gap: 40px; align-items: flex-end; justify-content: space-between;">
                <div style="flex: 1 1 460px;">
                    <div class="ziara-single__kicker">{{ $tour->category }}</div>
                    <h1 class="ziara-single__title">{{ $tour->name }}</h1>
                    <div class="ziara-single__meta">
                        <p class="ziara-single__loc">
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            {{ $tour->city }}, {{ $tour->country }}
                        </p>
                        <p class="ziara-single__loc">
                            <i class="fa-regular fa-clock" aria-hidden="true"></i>
                            {{ $tour->duration }}
                        </p>
                        <p class="ziara-single__loc">
                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                            {{ number_format($tour->rating, 1) }}
                            <small>({{ $tour->review_count }} reviews)</small>
                        </p>
                    </div>
                    <div class="ziara-single__tags">
                        <span class="ziara-single__tag ziara-single__tag--gold">{{ $tour->category }}</span>
                        @if($tour->free_cancellation)
                            <span class="ziara-single__tag ziara-single__tag--line">Free Cancellation</span>
                        @endif
                        @if($tour->hotel_pickup)
                            <span class="ziara-single__tag ziara-single__tag--line">Hotel Pickup</span>
                        @endif
                    </div>
                </div>
                <div class="ziara-hero__price" style="flex: 0 0 280px;">
                    @if($tour->has_discount)
                        <span class="ziara-single__tag ziara-single__tag--gold">{{ $tour->discount_percent }}% OFF</span>
                        <s>{{ $tour->currency }} {{ number_format($tour->original_price, 2) }}</s>
                    @endif
                    <span class="amt">{{ $tour->currency }} {{ number_format($tour->price, 2) }}</span>
                    <span class="per">per person</span>
                    <a href="{{ route('tours.booking', $tour->slug) }}" class="ziara-single__btn" style="margin-top: 18px;">
                        <i class="fa-solid fa-ticket" aria-hidden="true"></i> Book Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="ziara-single__wrap">
        <article class="ziara-single__content">
            @if($tour->images && count($tour->images) > 1)
                <div class="ziara-block">
                    <h2 class="ziara-block__title">Gallery</h2>
                    <div class="ziara-gallery">
                        @foreach(array_slice($tour->images, 0, 6) as $index => $image)
                            <a href="{{ $image }}" data-fancybox="gallery" class="ziara-gallery__item">
                                <img src="{{ $image }}" alt="{{ $tour->name }} - Image {{ $index + 1 }}">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="ziara-block">
                <h2 class="ziara-block__title">Tour Description</h2>
                <div class="ziara-prose">
                    <p>{{ $tour->full_description ?? $tour->short_description }}</p>
                </div>
            </div>

            @if($tour->highlights)
                <div class="ziara-block">
                    <h2 class="ziara-block__title">Highlights</h2>
                    <ul class="ziara-checklist">
                        @foreach($tour->highlights as $highlight)
                            <li class="ziara-checklist__item">
                                <span class="ziara-checklist__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                                <span>{{ $highlight }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($tour->included_services)
                <div class="ziara-block">
                    <h2 class="ziara-block__title">What's Included</h2>
                    <ul class="ziara-checklist">
                        @foreach($tour->included_services as $service)
                            <li class="ziara-checklist__item">
                                <span class="ziara-checklist__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                                <span>{{ $service }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="ziara-block">
                <h2 class="ziara-block__title">Important Information</h2>

                <div class="ziara-panel">
                    <h3 class="ziara-panel__title"><i class="fa-solid fa-circle-info" style="color: var(--gold);" aria-hidden="true"></i> Tour Details</h3>
                    <ul class="ziara-single__facts">
                        <li>
                            <i class="fa-regular fa-clock" aria-hidden="true"></i>
                            <span>Duration</span>
                            <b>{{ $tour->duration }}</b>
                        </li>
                        <li>
                            <i class="fa-solid fa-mountain-sun" aria-hidden="true"></i>
                            <span>Difficulty</span>
                            <b>{{ $tour->difficulty_level }}</b>
                        </li>
                        <li>
                            <i class="fa-solid fa-users" aria-hidden="true"></i>
                            <span>Group Size</span>
                            <b>Maximum {{ $tour->max_participants ?? 20 }} people</b>
                        </li>
                        <li>
                            <i class="fa-solid fa-language" aria-hidden="true"></i>
                            <span>Languages</span>
                            <b>English</b>
                        </li>
                    </ul>
                </div>

                <div class="ziara-panel">
                    <h3 class="ziara-panel__title"><i class="fa-regular fa-calendar-xmark" style="color: var(--gold);" aria-hidden="true"></i> Cancellation Policy</h3>
                    <div class="ziara-note">
                        {{ $tour->cancellation_policy ?? 'Free cancellation up to 24 hours before the tour starts. If you cancel less than 24 hours before the tour, you will not be eligible for a refund.' }}
                    </div>
                </div>

                <div class="ziara-panel">
                    <h3 class="ziara-panel__title"><i class="fa-solid fa-suitcase-rolling" style="color: var(--gold);" aria-hidden="true"></i> What to Bring</h3>
                    <ul class="ziara-amenities">
                        <li><i class="fa-solid fa-shoe-prints" aria-hidden="true"></i> Comfortable walking shoes</li>
                        <li><i class="fa-solid fa-sun" aria-hidden="true"></i> Sunscreen and hat</li>
                        <li><i class="fa-solid fa-bottle-droplet" aria-hidden="true"></i> Water bottle</li>
                        <li><i class="fa-solid fa-camera" aria-hidden="true"></i> Camera</li>
                        <li><i class="fa-solid fa-shirt" aria-hidden="true"></i> Weather-appropriate clothing</li>
                    </ul>
                </div>
            </div>

            <div class="ziara-block">
                <h2 class="ziara-block__title">Reviews</h2>

                <div class="ziara-panel">
                    <div style="display: flex; flex-wrap: wrap; gap: 34px; align-items: center;">
                        <div style="text-align: center; min-width: 150px;">
                            <div style="font-family: var(--font-display); font-size: 54px; font-weight: 700; line-height: 1; color: var(--maroon);">
                                {{ number_format($tour->rating, 1) }}
                            </div>
                            <div class="ziara-single__loc" style="justify-content: center; margin: 8px 0; color: var(--muted);">out of 5</div>
                            <div style="color: var(--gold); letter-spacing: 2px;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($tour->rating))
                                        <i class="fa-solid fa-star" aria-hidden="true"></i>
                                    @else
                                        <i class="fa-regular fa-star" aria-hidden="true"></i>
                                    @endif
                                @endfor
                            </div>
                            <div style="margin-top: 10px; font-size: 14px; color: var(--muted);">{{ $tour->review_count }} reviews</div>
                        </div>
                        <div style="flex: 1 1 280px; display: grid; gap: 10px;">
                            <div class="ziara-rate-row"><b>5 stars</b><i><span style="width: 70%"></span></i><em>70%</em></div>
                            <div class="ziara-rate-row"><b>4 stars</b><i><span style="width: 20%"></span></i><em>20%</em></div>
                            <div class="ziara-rate-row"><b>3 stars</b><i><span style="width: 7%"></span></i><em>7%</em></div>
                            <div class="ziara-rate-row"><b>2 stars</b><i><span style="width: 2%"></span></i><em>2%</em></div>
                            <div class="ziara-rate-row"><b>1 star</b><i><span style="width: 1%"></span></i><em>1%</em></div>
                        </div>
                    </div>
                </div>

                <div class="ziara-panel">
                    <div class="ziara-review__head">
                        <img src="https://ui-avatars.com/api/?name=John+D&background=random" alt="John D.">
                        <div>
                            <strong>John D.</strong>
                            <small>Traveled last month</small>
                        </div>
                        <div class="ziara-review__stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                    <h3 class="ziara-review__quote">"Absolutely amazing experience!"</h3>
                    <p class="ziara-review__text">This tour exceeded all my expectations. The guide was knowledgeable and friendly, and we saw so much wildlife. Definitely the highlight of our trip to East Africa!</p>
                </div>

                <div class="ziara-panel">
                    <div class="ziara-review__head">
                        <img src="https://ui-avatars.com/api/?name=Sarah+M&background=random" alt="Sarah M.">
                        <div>
                            <strong>Sarah M.</strong>
                            <small>Traveled 2 months ago</small>
                        </div>
                        <div class="ziara-review__stars">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                    <h3 class="ziara-review__quote">"Great tour, worth the price"</h3>
                    <p class="ziara-review__text">We had a wonderful time on this tour. The only reason for 4 stars instead of 5 is that we felt slightly rushed at some points. But overall, it was a great experience and I would recommend it.</p>
                </div>

                <div style="text-align: center; margin-top: 28px;">
                    <button class="ziara-btn ziara-btn--outline">
                        <i class="fa-solid fa-comments" aria-hidden="true"></i> View All Reviews
                    </button>
                </div>
            </div>
        </article>

        <aside class="ziara-single__aside">
            <div class="ziara-single__box">
                <h2 class="ziara-panel__title">Book this tour</h2>

                <div class="ziara-single__price">
                    <div>
                        @if($tour->has_discount)
                            <s style="display: block; font-size: 14px; color: var(--muted);">
                                {{ $tour->currency }} {{ number_format($tour->original_price, 2) }}
                            </s>
                            <span class="ziara-card__badge" style="position: static; display: inline-block; margin-bottom: 8px;">{{ $tour->discount_percent }}% OFF</span>
                        @endif
                        <strong style="display: block;">{{ $tour->currency }} {{ number_format($tour->price, 2) }}</strong>
                        <span>per person</span>
                    </div>
                </div>

                <a href="{{ route('tours.booking', $tour->slug) }}" class="ziara-single__btn">
                    <i class="fa-solid fa-ticket" aria-hidden="true"></i> Book Now
                </a>

                <ul class="ziara-single__facts" style="margin-top: 24px;">
                    <li>
                        <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                        <span>Available daily</span>
                        <b>Subject to availability</b>
                    </li>
                    @if($tour->instant_confirmation)
                        <li>
                            <i class="fa-solid fa-bolt" aria-hidden="true"></i>
                            <span>Instant confirmation</span>
                            <b>Receive confirmation immediately</b>
                        </li>
                    @endif
                    @if($tour->free_cancellation)
                        <li>
                            <i class="fa-solid fa-circle-xmark" aria-hidden="true"></i>
                            <span>Free cancellation</span>
                            <b>Up to 24 hours before</b>
                        </li>
                    @endif
                    @if($tour->hotel_pickup)
                        <li>
                            <i class="fa-solid fa-car" aria-hidden="true"></i>
                            <span>Hotel pickup available</span>
                            <b>For most city hotels</b>
                        </li>
                    @endif
                </ul>
            </div>

            @if(count($relatedTours) > 0)
                <div class="ziara-panel" style="margin-top: 24px;">
                    <h2 class="ziara-panel__title">You Might Also Like</h2>
                    @foreach($relatedTours as $relatedTour)
                        <a href="{{ route('tours.show', $relatedTour->slug) }}" class="ziara-related">
                            <img src="{{ $relatedTour->main_image }}" alt="{{ $relatedTour->name }}">
                            <div>
                                <h6>{{ Str::limit($relatedTour->name, 40) }}</h6>
                                <div class="ziara-related__stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($relatedTour->rating))
                                            <i class="fa-solid fa-star" aria-hidden="true"></i>
                                        @else
                                            <i class="fa-regular fa-star" aria-hidden="true"></i>
                                        @endif
                                    @endfor
                                </div>
                                <div class="ziara-related__price">{{ $relatedTour->currency }} {{ number_format($relatedTour->price, 2) }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </aside>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Fancybox.bind("[data-fancybox='gallery']", {
        });
    });
</script>
@endsection
