@extends('layouts.main')
@section('title', 'Our Team - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Our Team',
    'title'  => 'Meet the <em>Team</em>',
    'intro'  => 'Passionate travel experts dedicated to crafting your perfect journey',
])

{{-- ===== LEADERSHIP ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Leadership',
            'title' => 'Our <em>Leadership</em>',
            'align' => 'center',
        ])
        @php $leadership = [
            ['name'=>'Sarah Johnson','role'=>'CEO & Founder','desc'=>'15+ years in aviation and travel tech. Passionate about making African travel accessible to everyone.','icon'=>'fa-solid fa-crown'],
            ['name'=>'Michael Chen','role'=>'CTO','desc'=>'Technology expert driving innovation in travel booking with cutting-edge solutions and scalable architecture.','icon'=>'fa-solid fa-code'],
            ['name'=>'Emily Rodriguez','role'=>'Head of Customer Experience','desc'=>'Ensuring every traveler has the best possible journey from booking to landing and beyond.','icon'=>'fa-solid fa-headset'],
            ['name'=>'David Ochieng','role'=>'Director of Operations','desc'=>'Orchestrating seamless operations across East Africa with a focus on quality and reliability.','icon'=>'fa-solid fa-gears'],
        ]; @endphp
        <div class="ziara-team">
            @foreach($leadership as $t)
                <div class="ziara-team__card">
                    <span class="ziara-team__avatar"><i class="{{ $t['icon'] }}" aria-hidden="true"></i></span>
                    <h3 class="ziara-team__name">{{ $t['name'] }}</h3>
                    <div class="ziara-team__role">{{ $t['role'] }}</div>
                    <p class="ziara-team__desc">{{ $t['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== TRAVEL EXPERTS ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Travel Experts',
            'title' => 'Our <em>Destination Specialists</em>',
            'align' => 'center',
        ])
        @php $experts = [
            ['name'=>'Grace Wanjiku','specialty'=>'Kenya Safaris','years'=>'8','icon'=>'fa-solid fa-lion'],
            ['name'=>'James Mwangi','specialty'=>'Tanzania & Zanzibar','years'=>'6','icon'=>'fa-solid fa-water'],
            ['name'=>'Aisha Njeri','specialty'=>'Gorilla Trekking','years'=>'7','icon'=>'fa-solid fa-mountain'],
            ['name'=>'Peter Kamau','specialty'=>'Luxury & Fly-in Safaris','years'=>'10','icon'=>'fa-solid fa-plane'],
            ['name'=>'Lydia Atieno','specialty'=>'Family & Group Travel','years'=>'5','icon'=>'fa-solid fa-users'],
            ['name'=>'Samuel Kiprotich','specialty'=>'Photography Safaris','years'=>'9','icon'=>'fa-solid fa-camera'],
        ]; @endphp
        <div class="ziara-team ziara-team--compact">
            @foreach($experts as $e)
                <div class="ziara-team__card ziara-team__card--compact">
                    <span class="ziara-team__avatar"><i class="{{ $e['icon'] }}" aria-hidden="true"></i></span>
                    <h3 class="ziara-team__name">{{ $e['name'] }}</h3>
                    <div class="ziara-team__role">{{ $e['specialty'] }}</div>
                    <p class="ziara-team__desc"><i class="fa-solid fa-calendar" aria-hidden="true"></i> {{ $e['years'] }}+ years experience</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== VALUES ===== --}}
<section class="zt-section zt-section--dark">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Our Culture',
            'title' => 'What Unites <em>Us</em>',
            'align' => 'center',
            'light' => true,
        ])
        @php $culture = [
            ['icon'=>'fa-solid fa-heart','title'=>'Empathy','desc'=>'We listen deeply to understand each traveler\'s unique dream.'],
            ['icon'=>'fa-solid fa-lightbulb','title'=>'Curiosity','desc'=>'Always exploring new destinations, ideas, and better ways.'],
            ['icon'=>'fa-solid fa-handshake','title'=>'Integrity','desc'=>'Transparent pricing, honest advice, and promises kept.'],
            ['icon'=>'fa-solid fa-seedling','title'=>'Growth','desc'=>'Investing in our people and the communities we touch.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($culture as $c)
                <div class="ziara-feature">
                    <span class="ziara-feature__icon" style="background:rgba(201,162,39,.16);color:var(--gold);"><i class="{{ $c['icon'] }}" aria-hidden="true"></i></span>
                    <h3 class="ziara-feature__title">{{ $c['title'] }}</h3>
                    <p class="ziara-feature__desc">{{ $c['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-split" style="align-items:center;">
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Join Us',
                    'title' => 'Love Travel? <em>Work With Us</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-24px;">
                    <p>We're always looking for passionate people who believe travel changes lives. Check our open roles or send us your CV.</p>
                </div>
                <a href="{{ route('careers') }}" class="ziara-btn ziara-btn--gold" style="margin-top:12px;">View Careers</a>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=600&h=400&fit=crop" alt="Team collaboration" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
        </div>
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Team Updates'])
@endsection

@push('styles')
<style>
.ziara-team{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:32px;}
.ziara-team__card{text-align:center;background:#fff;border-radius:20px;padding:32px 24px;box-shadow:0 12px 40px rgba(10,5,3,.08);transition:transform .3s,box-shadow .3s;}
.ziara-team__card:hover{transform:translateY(-6px);box-shadow:0 20px 50px rgba(10,5,3,.14);}
.ziara-team__avatar{width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,var(--maroon),var(--gold));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:2.5rem;color:#fff;}
.ziara-team__name{font-family:'Cormorant Garamond',Georgia,serif;font-size:1.35rem;margin:0 0 4px;color:var(--maroon);}
.ziara-team__role{font-size:.9rem;font-weight:600;color:var(--gold);text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;}
.ziara-team__desc{font-size:.95rem;color:#5a524a;line-height:1.6;margin:0;}
.ziara-team--compact .ziara-team__card{padding:24px 18px;}
.ziara-team--compact .ziara-team__avatar{width:80px;height:80px;font-size:2rem;}
@media (max-width:768px){.ziara-team{grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;}}
</style>
@endpush