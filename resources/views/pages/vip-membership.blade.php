@extends('layouts.main')
@section('title', 'VIP Membership - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'VIP Membership',
    'title'  => 'VIP <em>Membership</em>',
    'intro'  => 'Unlock exclusive perks and premium travel experiences',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Membership',
            'title' => 'Choose Your <em>Tier</em>',
            'align' => 'center',
        ])
        @php $tiers = [
            ['name'=>'Silver VIP','price'=>'$29','period'=>'/month','color'=>'gray','features'=>['Priority customer support','5% discount on flights','Free seat selection','Early access to deals','Monthly travel newsletter'],'popular'=>false],
            ['name'=>'Gold VIP','price'=>'$59','period'=>'/month','color'=>'amber','features'=>['Everything in Silver','10% discount on flights','Free luggage upgrade','Airport lounge access','Dedicated travel advisor','Flexible cancellations'],'popular'=>true],
            ['name'=>'Platinum VIP','price'=>'$99','period'=>'/month','color'=>'emerald','features'=>['Everything in Gold','15% discount on flights','Complimentary travel insurance','Private airport transfers','VIP concierge service','Exclusive partner perks'],'popular'=>false],
        ]; @endphp
        <div class="ziara-features">
            @foreach($tiers as $t)
            <div class="ziara-feature" style="{{ $t['popular'] ? 'position:relative;border:2px solid var(--gold);background:linear-gradient(180deg, var(--gold-pale), #fff);' : 'position:relative;' }}">
                @if($t['popular'])<span class="ziara-deal__badge" style="position:absolute;top:14px;left:14px;">Most Popular</span>@endif
                <h3 class="ziara-feature__title">{{ $t['name'] }}</h3>
                <div style="display:flex;align-items:baseline;gap:8px;margin:-2px 0 18px;">
                    <strong style="font-size:30px;color:var(--maroon);">{{ $t['price'] }}</strong>
                    <span style="color:var(--muted);font-size:14px;">{{ $t['period'] }}</span>
                </div>
                <ul class="ziara-feature__list">
                    @foreach($t['features'] as $f)<li><i class="fa-solid fa-check" aria-hidden="true"></i>{{ $f }}</li>@endforeach
                </ul>
                <button class="ziara-btn ziara-btn--gold" style="background:var(--gold);color:var(--maroon-deep);border-radius:50px;padding:15px 36px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;font-size:12.5px;border:0;cursor:pointer;" onclick="alert('VIP registration coming soon!')">Get Started</button>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Perks',
            'title' => 'VIP <em>Benefits</em>',
            'align' => 'center',
        ])
        <div class="ziara-minifeatures">
            @foreach([
                ['icon'=>'fa-tags','title'=>'Exclusive Discounts','text'=>'Save up to 15% on every booking'],
                ['icon'=>'fa-couch','title'=>'Lounge Access','text'=>'Relax in airport lounges worldwide'],
                ['icon'=>'fa-headset','title'=>'Priority Support','text'=>'Skip the queue with VIP support'],
                ['icon'=>'fa-shield-alt','title'=>'Free Insurance','text'=>'Complimentary travel protection'],
            ] as $p)
            <div class="ziara-minifeature">
                <span class="ziara-minifeature__icon"><i class="fa-solid {{ $p['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $p['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $p['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-panel" style="padding:0;overflow:hidden;">
            <div class="ziara-split" style="gap:0;">
                <div>
                    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=600&h=400&fit=crop" alt="" style="width:100%;height:100%;min-height:280px;object-fit:cover;" loading="lazy">
                </div>
                <div style="padding:32px;">
                    @include('partials.ziara-section-head', [
                        'sub'   => 'Limited Offer',
                        'title' => 'Start Your VIP Journey <em>Today</em>',
                        'align' => 'left',
                    ])
                    <div class="ziara-prose">
                        <p>Join now and get your first month free. Experience travel the way it was meant to be.</p>
                    </div>
                    <button class="ziara-btn ziara-btn--gold" style="background:var(--gold);color:var(--maroon-deep);border-radius:50px;padding:15px 36px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;font-size:12.5px;border:0;cursor:pointer;" onclick="alert('Coming soon!')">Claim Free Trial</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Membership Questions',
            'title' => 'Membership <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'Can I cancel my membership anytime?','a'=>'Yes, you can cancel at any time with no cancellation fees. Your benefits remain active until the end of the billing period.'],
            ['q'=>'How do I upgrade my tier?','a'=>'You can upgrade directly from your account dashboard. The price difference is prorated for the remaining billing period.'],
            ['q'=>'Do VIP discounts apply to all flights?','a'=>'Yes, VIP discounts apply to all flights booked through our platform, subject to availability and fare conditions.'],
            ['q'=>'Can I share my membership?','a'=>'Membership benefits are personal but Gold and Platinum members can add one companion for lounge access.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'VIP Travel Updates'])
@endsection
