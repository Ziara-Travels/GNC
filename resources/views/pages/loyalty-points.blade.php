@extends('layouts.main')
@section('title', 'Loyalty Points - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Rewards Program',
    'title'  => 'Loyalty <em>Points</em>',
    'intro'  => 'Earn points on every booking and redeem them for discounts',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'How It Works',
            'title' => 'Earn &amp; Redeem in <em>3 Steps</em>',
            'align' => 'center',
        ])
        @php $steps = [
            ['n'=>'1','icon'=>'fa-solid fa-plane','title'=>'Book & Earn','desc'=>'Earn 1 point for every KES spent on flights, hotels, and packages.'],
            ['n'=>'2','icon'=>'fa-solid fa-coins','title'=>'Accumulate','desc'=>'Points stack up across all your bookings. Track them in your dashboard.'],
            ['n'=>'3','icon'=>'fa-solid fa-gift','title'=>'Redeem','desc'=>'Use your points for discounts on future bookings. 100 points = KES 1 off.'],
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
            'sub'   => 'Rewards Program',
            'title' => 'Membership <em>Tiers</em>',
            'align' => 'center',
        ])
        @php $tiers = [
            ['name'=>'Bronze','points'=>'0 - 4,999','perks'=>['1x earning rate','Basic support','Birthday bonus'],'color'=>'orange','icon'=>'fa-solid fa-medal'],
            ['name'=>'Silver','points'=>'5,000 - 19,999','perks'=>['1.5x earning rate','Priority support','Free date changes','Birthday bonus'],'color'=>'gray','icon'=>'fa-solid fa-award'],
            ['name'=>'Gold','points'=>'20,000+','perks'=>['2x earning rate','VIP support','Free changes & upgrades','Lounge access','Birthday bonus'],'color'=>'yellow','icon'=>'fa-solid fa-crown'],
        ]; @endphp
        <div class="ziara-panel" style="max-width:1040px;margin:0 auto;padding:0;overflow-x:auto;">
            <table style="width:100%;min-width:760px;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="background:var(--maroon);color:#fff;padding:16px 20px;text-align:left;font-size:12px;letter-spacing:.8px;text-transform:uppercase;">Membership Tier</th>
                        <th style="background:var(--maroon);color:#fff;padding:16px 20px;text-align:left;font-size:12px;letter-spacing:.8px;text-transform:uppercase;">Points Range</th>
                        <th style="background:var(--maroon);color:#fff;padding:16px 20px;text-align:left;font-size:12px;letter-spacing:.8px;text-transform:uppercase;">Benefits</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiers as $t)
                    <tr>
                        <td style="padding:20px;border-bottom:1px solid var(--line);">
                            <span style="display:inline-flex;align-items:center;gap:10px;color:var(--maroon);font-family:var(--font-display);font-size:22px;font-weight:700;">{{ $t['name'] }} <i class="{{ $t['icon'] }}" aria-hidden="true"></i></span>
                        </td>
                        <td style="padding:20px;border-bottom:1px solid var(--line);color:var(--ink);font-size:14px;white-space:nowrap;">{{ $t['points'] }} pts</td>
                        <td style="padding:20px;border-bottom:1px solid var(--line);">
                            <ul class="ziara-checklist" style="gap:8px;">
                                @foreach($t['perks'] as $p)
                                <li class="ziara-checklist__item" style="font-size:13px;">
                                    <span class="ziara-checklist__icon" style="width:24px;height:24px;margin-top:0;"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                                    {{ $p }}
                                </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-split">
            <div>
                <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Start Earning',
                    'title' => 'Book Your Next Trip &amp; Earn <em>Points</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>Every booking earns you points towards free travel. Start your rewards journey today.</p>
                </div>
                <div class="ziara-btns" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
                    <a href="{{ url('/flights/search') }}" class="ziara-btn ziara-btn--gold">Search Flights</a>
                    @auth <a href="{{ url('/dashboard') }}" class="ziara-btn ziara-btn--outline">My Points</a> @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Rewards Program',
            'title' => 'Points <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How do I check my point balance?','a'=>'Log in to your account and visit your dashboard. Your current points balance and tier status are displayed prominently.'],
            ['q'=>'Do points expire?','a'=>'Points are valid for 24 months from the date they were earned. Keep booking to maintain your balance!'],
            ['q'=>'Can I transfer points to someone else?','a'=>'Point transfers are not currently available. However, you can use your points to book travel for other people.'],
            ['q'=>'How do I redeem points?','a'=>'During checkout, you\'ll see an option to apply your available points as a discount. Select the amount you wish to redeem.'],
            ['q'=>'How do I get promoted to a higher tier?','a'=>'Tiers are based on total lifetime points earned. Keep booking and you\'ll automatically be upgraded when you reach the threshold.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Rewards Updates'])
@endsection
