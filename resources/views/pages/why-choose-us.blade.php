@extends('layouts.main')
@section('title', 'Why Choose Us - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Why Choose Us',
    'title'  => 'Why Choose <em>Ziara Travels</em>',
    'intro'  => 'Trusted by thousands of travelers across East Africa',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Difference',
            'title' => 'What Sets <em>Us Apart</em>',
            'align' => 'center',
        ])
        @php $reasons = [
            ['icon'=>'fa-solid fa-tag','title'=>'Best Prices Guaranteed','desc'=>'We compare fares from 500+ airlines and negotiate directly with hotels for the lowest available rates.'],
            ['icon'=>'fa-solid fa-bolt','title'=>'Instant Booking','desc'=>'Search, compare, and book in seconds. Get e-tickets and confirmations delivered instantly to your email.'],
            ['icon'=>'fa-solid fa-shield-alt','title'=>'Secure Payments','desc'=>'M-Pesa, STK Push, Equity Eazzy, bank transfer, and cards — all protected with end-to-end encryption.'],
            ['icon'=>'fa-solid fa-headset','title'=>'24/7 Expert Support','desc'=>'Our team of travel specialists is available around the clock via chat, email, and phone.'],
            ['icon'=>'fa-solid fa-undo','title'=>'Flexible Cancellation','desc'=>'Free cancellation on most bookings. Change your plans without penalties on flexible-rate rooms.'],
            ['icon'=>'fa-solid fa-globe-africa','title'=>'East Africa Specialists','desc'=>'Deep expertise in East African destinations — Kenya, Tanzania, Uganda, Rwanda, and beyond.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($reasons as $r)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $r['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $r['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $r['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--dark">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Difference',
            'title' => 'Trusted by <em>Thousands</em>',
            'align' => 'center',
            'light' => true,
        ])
        @php $stats = [['n'=>'10K+','l'=>'Flights Booked'],['n'=>'5K+','l'=>'Happy Travelers'],['n'=>'190+','l'=>'Countries'],['n'=>'98%','l'=>'Satisfaction Rate']]; @endphp
        <div class="ziara-stats">
            @foreach($stats as $s)
            <div class="ziara-stat">
                <div class="ziara-stat__num">{{ $s['n'] }}</div>
                <div class="ziara-stat__label">{{ $s['l'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-split">
            <div>
                <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&h=400&fit=crop" alt="" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Start Today',
                    'title' => 'Ready to Experience the <em>Difference?</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>Join thousands of happy travelers who trust Ziara Travels for their booking needs.</p>
                </div>
                <div class="ziara-btns" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
                    <a href="{{ url('/flights/search') }}" class="ziara-btn ziara-btn--gold">Search Flights</a>
                    <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--outline">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Why Choose Us',
            'title' => 'Common <em>Questions</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'Do you offer a price match guarantee?','a'=>'Yes! If you find a lower price for the same flight or hotel within 24 hours of booking, we\'ll match it.'],
            ['q'=>'How quickly do I get my e-ticket?','a'=>'E-tickets are generated instantly after payment confirmation and sent to your email within minutes.'],
            ['q'=>'What if I need to cancel?','a'=>'Most flexible-rate bookings allow free cancellation. Non-refundable fares may have change fees — check your booking terms.'],
            ['q'=>'Is my payment information safe?','a'=>'Absolutely. We use SSL encryption and never store your full card details. M-Pesa transactions are processed through Safaricom\'s secure gateway.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Travel Updates'])
@endsection
