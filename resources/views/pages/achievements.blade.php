@extends('layouts.main')
@section('title', 'Achievements - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Our Achievements',
    'title'  => 'Our <em>Achievements</em>',
    'intro'  => 'Milestones and awards that define our journey',
])

<section class="zt-section zt-section--dark">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Achievements',
            'title' => 'Milestones and Awards That Define <em>Our Journey</em>',
            'align' => 'center',
            'light' => true,
        ])
        <div class="ziara-stats">
            @foreach([['num'=>'50K+','label'=>'Happy Travelers'],['num'=>'120+','label'=>'Destinations'],['num'=>'15K+','label'=>'Flights Booked'],['num'=>'4.8/5','label'=>'Customer Rating']] as $s)
            <div class="ziara-stat">
                <div class="ziara-stat__num">{{ $s['num'] }}</div>
                <div class="ziara-stat__label">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Achievements',
            'title' => 'Awards &amp; <em>Recognition</em>',
            'align' => 'center',
        ])
        <div class="ziara-features">
            @foreach([['year'=>'2025','title'=>'Best Travel Agency – East Africa','org'=>'Africa Travel Awards','icon'=>'fa-solid fa-trophy'],['year'=>'2024','title'=>'Service Excellence Award','org'=>'Kenya Tourism Board','icon'=>'fa-solid fa-award'],['year'=>'2024','title'=>'Top Innovation in Travel Tech','org'=>'Tech in Travel Conference','icon'=>'fa-solid fa-microchip'],['year'=>'2023','title'=>'Customer Choice Award','org'=>'Travel Weekly Africa','icon'=>'fa-solid fa-heart'],['year'=>'2023','title'=>'Sustainable Tourism Partner','org'=>'Sustainable Tourism Alliance','icon'=>'fa-solid fa-leaf'],['year'=>'2022','title'=>'Best New Travel Platform','org'=>'Startup Awards Africa','icon'=>'fa-solid fa-star']] as $a)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $a['icon'] }}" aria-hidden="true"></i></span>
                <span class="ziara-deal__badge" style="position:static;display:inline-flex;margin-bottom:12px;">{{ $a['year'] }}</span>
                <h3 class="ziara-feature__title">{{ $a['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $a['org'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Milestones',
            'title' => 'Our <em>Journey</em>',
            'align' => 'center',
        ])
        <div class="ziara-features">
            @foreach([['year'=>'2022','title'=>'Founded','text'=>'Ziara Travel launches with a vision to make African travel seamless and affordable.'],['year'=>'2023','title'=>'10,000 Bookings','text'=>'Reached our first major milestone with 10K successful bookings in under a year.'],['year'=>'2023','title'=>'Expanded to 5 Countries','text'=>'Extended our services to Kenya, Tanzania, Uganda, Rwanda, and Ethiopia.'],['year'=>'2024','title'=>'Mobile App Launch','text'=>'Released our mobile app for iOS and Android, enabling travel on-the-go.'],['year'=>'2025','title'=>'Southern Africa Expansion','text'=>'Opened new offices in Cape Town and Gaborone, marking our continental growth.']] as $m)
            <div class="ziara-feature">
                <span class="ziara-deal__badge" style="position:static;display:inline-flex;margin-bottom:12px;">{{ $m['year'] }}</span>
                <h3 class="ziara-feature__title">{{ $m['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $m['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-split">
            <div>
                <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Join Our Story',
                    'title' => 'Be Part of Our <em>Next Chapter</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>Over 50,000 travelers trust us. Start your journey with an award-winning team.</p>
                </div>
                <a href="{{ url('/') }}" class="ziara-btn ziara-btn--gold" style="margin-top:8px;">Book Your Trip</a>
            </div>
        </div>
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Follow Our Journey'])
@endsection
