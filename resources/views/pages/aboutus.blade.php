@extends('layouts.main')
@section('title', 'About Us - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'About Us',
    'title'  => 'About <em>Ziara Travels</em>',
    'intro'  => 'Your trusted partner in air travel excellence — connecting dreams since 2025.',
])

{{-- ===== OUR STORY ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-split">
            <div>
                @include('partials.ziara-section-head', [
                    'sub'    => 'Our Story',
                    'title'  => 'Revolutionizing <span>Air Travel</span>',
                    'align'  => 'left',
                    'desc'   => 'Founded in 2025, Ziara Travels has been at the forefront of revolutionizing air travel booking. We believe in making air travel accessible, convenient, and enjoyable for everyone.',
                ])
                <div class="ziara-prose" style="margin-top:-20px;">
                    <p>Our platform combines cutting-edge technology with personalized service to deliver the best flight booking experience. From solo travelers to large groups, we've got you covered.</p>
                </div>
                <ul class="ziara-checklist">
                    <li class="ziara-checklist__item"><span class="ziara-checklist__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span> 24/7 Customer Support</li>
                    <li class="ziara-checklist__item"><span class="ziara-checklist__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span> Best Price Guarantee</li>
                    <li class="ziara-checklist__item"><span class="ziara-checklist__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span> Secure Booking</li>
                </ul>
            </div>
            <div>
                <img src="{{ asset('images/airplane.jpg') }}" alt="Our Story" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" onerror="this.src='https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=600&h=400&fit=crop'">
            </div>
        </div>
    </div>
</section>

{{-- ===== VALUES ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Values',
            'title' => 'What Drives <em>Us Forward</em>',
            'align' => 'center',
        ])
        @php $values = [
            ['icon'=>'fa-solid fa-users','title'=>'Customer First','desc'=>'Your satisfaction is our top priority. We\'re here 24/7 with dedicated support and personalized assistance.'],
            ['icon'=>'fa-solid fa-shield-halved','title'=>'Trust & Security','desc'=>'Your data and transactions are protected by industry-leading security measures and encryption.'],
            ['icon'=>'fa-solid fa-bolt','title'=>'Innovation','desc'=>'We continuously improve our services with cutting-edge technology for the best booking experience.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($values as $v)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $v['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $v['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== STATS ===== --}}
<section class="zt-section zt-section--dark">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Numbers',
            'title' => 'Trusted by <em>Thousands</em>',
            'align' => 'center',
            'light' => true,
        ])
        @php $stats = [['n'=>'10K+','l'=>'Flights Booked'],['n'=>'5K+','l'=>'Happy Customers'],['n'=>'100+','l'=>'Destinations'],['n'=>'24/7','l'=>'Support']]; @endphp
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

{{-- ===== TEAM ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Team',
            'title' => 'Meet the <em>Visionaries</em>',
            'align' => 'center',
        ])
        @php $team = [
            ['name'=>'Sarah Johnson','role'=>'CEO & Founder','desc'=>'15+ years in aviation — passionate about making travel accessible to everyone.'],
            ['name'=>'Michael Chen','role'=>'CTO','desc'=>'Technology expert driving innovation in travel booking with cutting-edge solutions.'],
            ['name'=>'Emily Rodriguez','role'=>'Head of Customer Experience','desc'=>'Ensuring every traveler has the best possible journey from booking to landing.'],
        ]; @endphp
        <div class="ziara-team">
            @foreach($team as $t)
            <div class="ziara-team__card">
                <span class="ziara-team__avatar"><i class="fa-solid fa-user" aria-hidden="true"></i></span>
                <h3 class="ziara-team__name">{{ $t['name'] }}</h3>
                <div class="ziara-team__role">{{ $t['role'] }}</div>
                <p class="ziara-team__desc">{{ $t['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-split">
            <div><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="Book today" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy"></div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Ready to Travel?',
                    'title' => 'Start Your Journey <em>Today</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>Book your next flight with confidence. Best prices, instant confirmation, and 24/7 support.</p>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
                    <a href="{{ url('/flights/search') }}" class="ziara-btn ziara-btn--gold">Book a Flight</a>
                    <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--outline">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Need Help?',
            'title' => 'Frequently Asked <em>Questions</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'What is Ziara Travels?','a'=>'Ziara Travels is a modern travel booking platform specializing in flights, hotels, and tour packages across East Africa and beyond.'],
            ['q'=>'How do I contact support?','a'=>'Our support team is available 24/7 via live chat, email at support@ziaratravels.com, or phone at +254 700 123 456.'],
            ['q'=>'Is my booking data secure?','a'=>'Absolutely. We use industry-standard encryption and security protocols to protect all your personal and payment data.'],
            ['q'=>'Do you offer corporate travel services?','a'=>'Yes, we offer tailored corporate packages with volume discounts, dedicated account managers, and consolidated billing.'],
            ['q'=>'What payment methods do you accept?','a'=>'We accept M-Pesa, M-Pesa STK Push, bank transfer, Equity Eazzy, and all major credit/debit cards.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Stay Updated'])
@endsection