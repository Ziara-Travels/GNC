@extends('layouts.main')

@section('title', 'Book Flights to East Africa - Ziara Travel')

@section('content')
@php
    $routes = [
        ['to'=>'Mombasa','code'=>'MBA','dur'=>'55m','price'=>'7,500','airline'=>'Kenya Airways','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=300&h=180&fit=crop'],
        ['to'=>'Zanzibar','code'=>'ZNZ','dur'=>'1h 30m','price'=>'12,000','airline'=>'Precision Air','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=300&h=180&fit=crop'],
        ['to'=>'Dar es Salaam','code'=>'DAR','dur'=>'1h 15m','price'=>'11,000','airline'=>'Fastjet','img'=>'https://images.unsplash.com/photo-1611348524140-53c9a25263d6?w=300&h=180&fit=crop'],
        ['to'=>'Kampala','code'=>'EBB','dur'=>'1h 20m','price'=>'14,500','airline'=>'Kenya Airways','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=300&h=180&fit=crop'],
        ['to'=>'Kigali','code'=>'KGL','dur'=>'1h 35m','price'=>'16,500','airline'=>'RwandAir','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=300&h=180&fit=crop'],
        ['to'=>'Addis Ababa','code'=>'ADD','dur'=>'2h 10m','price'=>'24,000','airline'=>'Ethiopian','img'=>'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=300&h=180&fit=crop'],
    ];
    $steps = [
        ['icon'=>'fa-search','t'=>'Search','d'=>'Enter your travel details above.'],
        ['icon'=>'fa-list-check','t'=>'Compare','d'=>'Review prices across airlines.'],
        ['icon'=>'fa-credit-card','t'=>'Pay','d'=>'Pay via M-Pesa, Equity or card.'],
        ['icon'=>'fa-ticket-alt','t'=>'Fly','d'=>'Receive your e-ticket instantly.'],
    ];
    $faqs = [
        ['q'=>'How far in advance should I book?','a'=>'For the best prices, book domestic flights 2-4 weeks ahead and international flights 4-8 weeks ahead.'],
        ['q'=>'Can I change my flight after booking?','a'=>'Changes depend on your fare type. Flexible fares allow free changes; standard fares may incur a fee.'],
        ['q'=>'What baggage is included?','a'=>'Baggage allowance varies by airline and fare class. Check your ticket details for specifics.'],
        ['q'=>'How do I check in for my flight?','a'=>'Most airlines allow online check-in 24-48 hours before departure via their website or app.'],
        ['q'=>'What payment methods are accepted?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equity Eazzy Banking, credit/debit cards and bank transfers.'],
    ];
@endphp

<style>
.ziara-flight-search .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
.ziara-flight-search__widget { max-width:900px;margin:0 auto; }
.ziara-flight-search__widget .ff-trip-tab.active { color:#7b2a1b;border-bottom-color:#d9a561;background:var(--gold-pale); }
.ziara-flight-search__widget .ff-trip-tab:hover:not(.active) { background:var(--gold-pale);color:#7b2a1b; }
.ziara-flight-search__widget input:focus,
.ziara-flight-search__widget select:focus { border-color:#d9a561 !important;box-shadow:0 0 0 3px rgba(217,165,97,0.22) !important;outline:none; }
.ziara-flight-search__widget button[type="submit"] { background:#7b2a1b !important;border:1px solid #6b1d15; }
.ziara-flight-search__widget button[type="submit"]:hover { background:#6b1d15 !important; }
.ziara-flight-search__widget #sf_addLeg { color:#7b2a1b !important;border-color:#d9a561 !important; }
.ziara-flight-search__widget #sf_addLeg:hover { background:var(--gold-pale) !important; }
.ziara-flight-search__widget .ff-cal-day:hover:not(.ff-cal-disabled) { background:var(--gold-pale); }
.ziara-flight-search__widget .ff-cal-day.ff-cal-selected { background:#7b2a1b; }
.ziara-flight-search__widget .ff-cal-day.ff-cal-today:not(.ff-cal-selected) { border:1px solid #d9a561; }
.ziara-flight-search__widget .ff-calendar-popover { border-color:#e5d3b5; }
.ziara-flight-search .ziara-faqbox__item { border:1px solid var(--gold-border);border-radius:14px;background:#fff;overflow:hidden; }
.ziara-flight-search .ziara-faqbox__item .faq-toggle:hover { background:var(--gold-pale); }
.ziara-flight-search .ziara-faqbox__item .faq-toggle span { color:var(--maroon);font-family:'Cormorant Garamond',Georgia,serif;font-size:19px;font-weight:600; }
.ziara-flight-search .ziara-faqbox__item .faq-toggle i { color:var(--gold); }
.ziara-flight-search .ziara-faqbox__item .faq-content p { margin:0;color:var(--muted);font-size:14.5px;line-height:1.75; }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'Flights',
    'title'  => 'Find Your Perfect <em>Flight</em>',
    'intro'  => 'Search across 500+ airlines for the best fares to East Africa and beyond.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Flights', 'url' => route('flights.public.search')],
    ],
])

<section class="zt-section zt-section--pale ziara-flight-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        <div class="ziara-panel ziara-flight-search__widget" style="padding:26px;">
            @include('flights.partials.search-widget', ['idPrefix' => 'sf'])
        </div>
    </div>
</section>

<section class="zt-section zt-section--white ziara-flight-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Trending',
            'title' => 'Popular Routes from <em>Nairobi</em>',
            'align' => 'center',
        ])
        <div class="ziara-deals">
            @foreach($routes as $r)
                <article class="ziara-deal">
                    <div class="ziara-deal__media">
                        <img src="{{ $r['img'] }}" alt="{{ $r['to'] }}" loading="lazy">
                        <span class="ziara-deal__badge">NBO &rarr; {{ $r['code'] }}</span>
                    </div>
                    <div class="ziara-deal__body">
                        <h3 class="ziara-deal__title">{{ $r['to'] }}</h3>
                        <p class="ziara-deal__desc">{{ $r['to'] }} &bull; {{ $r['dur'] }} &bull; {{ $r['airline'] }}</p>
                        <div class="ziara-deal__price">
                            <small style="font-size:12px;color:var(--muted);">From</small>
                            <strong>KES {{ $r['price'] }}</strong>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale ziara-flight-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        <div class="ziara-split">
            <div><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="Travel" loading="lazy" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);"></div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Save More',
                    'title' => 'Flight + Hotel <em>Packages</em>',
                    'align' => 'left',
                    'desc'  => 'Bundle your flight with a hotel and save up to 30% on your total trip cost. Flexible cancellation on select packages.',
                ])
                <a href="{{ url('/') }}" class="ziara-btn ziara-btn--gold">Browse Packages <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white ziara-flight-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Simple Steps',
            'title' => 'How Flight Booking <em>Works</em>',
            'align' => 'center',
        ])
        <div class="ziara-minifeatures">
            @foreach($steps as $s)
                <div class="ziara-minifeature">
                    <span class="ziara-minifeature__icon"><i class="fas {{ $s['icon'] }}" aria-hidden="true"></i></span>
                    <h3 class="ziara-minifeature__title">{{ $s['t'] }}</h3>
                    <p class="ziara-minifeature__desc">{{ $s['d'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale ziara-flight-search" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Need Help',
            'title' => 'Flight Booking <em>FAQs</em>',
            'align' => 'center',
        ])
        <div class="ziara-flight-search__faqs">
            @foreach($faqs as $faq)
                <div class="ziara-faqbox__item">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left transition-colors" onclick="toggleFaq(this)">
                        <span class="pr-3">{{ $faq['q'] }}</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 shrink-0"></i>
                    </button>
                    <div class="faq-content hidden px-4 pb-4"><p>{{ $faq['a'] }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section ziara-cta-band ziara-flight-search" style="--maroon:#6b1d15;--maroon-deep:#4d150f;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container" style="max-width:640px;">
        <div class="gsc-heading gsc-heading--light align-center style-1 widget auto-responsive" style="margin-bottom:26px;">
            <div class="content-inner">
                <div class="sub-title"><span>Stay in the Loop</span></div>
                <h2 class="title"><span>Get <em>flight deal</em> alerts</span></h2>
                <div class="title-desc" style="margin-left:auto;margin-right:auto;max-width:520px;">Be the first to know about fare drops and flash sales.</div>
            </div>
        </div>
        <form class="flex gap-2" style="max-width:520px;margin:0 auto;" onsubmit="event.preventDefault(); alert('Subscribed!'); this.reset();">
            <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
            <button type="submit" class="ziara-btn ziara-btn--gold text-xs whitespace-nowrap">Subscribe</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){
    var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');
    document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});
    document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});
    if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}
}
</script>
@endpush
