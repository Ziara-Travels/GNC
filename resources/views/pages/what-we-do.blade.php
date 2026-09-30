@extends('layouts.main')
@section('title', 'What We Do - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'What We Do',
    'title'  => 'What We <em>Do</em>',
    'intro'  => 'End-to-end travel solutions — from searching to boarding',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'How It Works',
            'title' => 'Book in 4 Simple <em>Steps</em>',
            'align' => 'center',
        ])
        @php $steps = [
            ['n'=>'1','icon'=>'fa-solid fa-search','title'=>'Search','desc'=>'Enter your destination and dates to find flights and hotels.'],
            ['n'=>'2','icon'=>'fa-solid fa-list','title'=>'Compare','desc'=>'Browse results, compare prices, and pick your ideal option.'],
            ['n'=>'3','icon'=>'fa-solid fa-credit-card','title'=>'Book & Pay','desc'=>'Secure checkout with M-Pesa, card, or bank transfer.'],
            ['n'=>'4','icon'=>'fa-solid fa-ticket-alt','title'=>'Travel','desc'=>'Receive your e-ticket instantly and you\'re ready to go!'],
        ]; @endphp
        <div class="ziara-minifeatures">
            @foreach($steps as $s)
            <div class="ziara-minifeature">
                <span class="ziara-minifeature__icon" style="position:relative;">
                    <i class="{{ $s['icon'] }}" aria-hidden="true"></i>
                    <span style="position:absolute;right:-9px;top:-9px;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;background:var(--maroon);color:#fff;font-size:10px;font-weight:700;">{{ $s['n'] }}</span>
                </span>
                <h3 class="ziara-minifeature__title">{{ $s['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'What We Do',
            'title' => 'Our Core <em>Services</em>',
            'align' => 'center',
        ])
        @php $svc = [
            ['icon'=>'fa-solid fa-plane','title'=>'Flight Booking','desc'=>'Real-time search across 500+ airlines. One-way, return, and multi-city flights with instant e-tickets.','link'=>url('/flights/search'),'img'=>'https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=400&h=250&fit=crop'],
            ['icon'=>'fa-solid fa-hotel','title'=>'Hotel Reservations','desc'=>'Compare 2M+ hotels worldwide. Best rate guarantee, free cancellation, and verified guest reviews.','link'=>url('/hotels/search'),'img'=>'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=250&fit=crop'],
            ['icon'=>'fa-solid fa-map-marked-alt','title'=>'Safari & Tour Packages','desc'=>'Curated East African tours — from Masai Mara to Zanzibar, with expert local guides.','link'=>url('/tours'),'img'=>'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=250&fit=crop'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($svc as $s)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $s['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $s['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $s['desc'] }}</p>
                <a href="{{ $s['link'] }}" class="ziara-feature__link">Explore <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-split">
            <div>
                <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&h=400&fit=crop" alt="" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Get Started',
                    'title' => 'Plan Your <em>Next Adventure</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>Whether it's a business trip or a holiday getaway, we have the tools and support to make it seamless.</p>
                </div>
                <div class="ziara-btns" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
                    <a href="{{ url('/flights/search') }}" class="ziara-btn ziara-btn--gold">Search Flights</a>
                    <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--outline">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'What We Do',
            'title' => 'Frequently Asked <em>Questions</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How do I search for flights?','a'=>'Use the search form on our homepage or flights page. Enter your origin, destination, dates, and passenger count to see available flights.'],
            ['q'=>'Can I book a flight and hotel together?','a'=>'Yes! Use the Packages tab on the homepage to search for bundled deals and save up to 30%.'],
            ['q'=>'What payment methods are accepted?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equity Eazzy, bank transfers, and all major credit/debit cards.'],
            ['q'=>'How do I get my e-ticket?','a'=>'After successful payment, your e-ticket is generated instantly and sent to your registered email. You can also download it from the booking confirmation page.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Stay Connected'])
@endsection
