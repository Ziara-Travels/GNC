@extends('layouts.main')
@section('title', 'Travel Insurance - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Travel Insurance',
    'title'  => 'Travel <em>Insurance</em>',
    'intro'  => 'Comprehensive coverage for worry-free travel',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Plans',
            'title' => 'Choose Your <em>Coverage</em>',
            'align' => 'center',
        ])
        @php $plans = [
            ['name'=>'Basic','price'=>'$12','per'=>'/trip','features'=>['Trip cancellation up to $1,000','Medical expenses up to $10,000','Lost baggage up to $500','24/7 emergency hotline'],'color'=>'gray','popular'=>false],
            ['name'=>'Standard','price'=>'$25','per'=>'/trip','features'=>['Trip cancellation up to $5,000','Medical expenses up to $50,000','Lost baggage up to $1,500','Trip delay coverage','24/7 emergency hotline'],'color'=>'emerald','popular'=>true],
            ['name'=>'Premium','price'=>'$45','per'=>'/trip','features'=>['Trip cancellation up to $10,000','Medical expenses up to $100,000','Lost baggage up to $3,000','Trip delay & interruption','Adventure sports cover','24/7 emergency hotline'],'color'=>'amber','popular'=>false],
        ]; @endphp
        <div class="ziara-features">
            @foreach($plans as $p)
            <div class="ziara-feature" style="{{ $p['popular'] ? 'position:relative;border:2px solid var(--gold);background:linear-gradient(180deg, var(--gold-pale), #fff);' : 'position:relative;' }}">
                @if($p['popular'])<span class="ziara-deal__badge" style="position:absolute;top:14px;left:14px;">Most Popular</span>@endif
                <h3 class="ziara-feature__title">{{ $p['name'] }}</h3>
                <div style="display:flex;align-items:baseline;gap:8px;margin:-2px 0 18px;">
                    <strong style="font-size:30px;color:var(--maroon);">{{ $p['price'] }}</strong>
                    <span style="color:var(--muted);font-size:14px;">{{ $p['per'] }}</span>
                </div>
                <ul class="ziara-feature__list">
                    @foreach($p['features'] as $f)<li><i class="fa-solid fa-check" aria-hidden="true"></i>{{ $f }}</li>@endforeach
                </ul>
                <button class="ziara-btn ziara-btn--gold" style="background:var(--gold);color:var(--maroon-deep);border-radius:50px;padding:15px 36px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;font-size:12.5px;border:0;cursor:pointer;">Choose {{ $p['name'] }}</button>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Protection',
            'title' => "What's <em>Covered</em>",
            'align' => 'center',
        ])
        <div class="ziara-minifeatures">
            @foreach([
                ['icon'=>'fa-times-circle','title'=>'Trip Cancellation','desc'=>'Reimbursement for pre-paid, non-refundable trip costs'],
                ['icon'=>'fa-hospital','title'=>'Medical Emergency','desc'=>'Coverage for illness or injury during your trip'],
                ['icon'=>'fa-suitcase','title'=>'Lost Baggage','desc'=>'Compensation for delayed, lost, or damaged luggage'],
                ['icon'=>'fa-clock','title'=>'Trip Delays','desc'=>'Coverage for expenses due to flight delays'],
            ] as $c)
            <div class="ziara-minifeature">
                <span class="ziara-minifeature__icon"><i class="fa-solid {{ $c['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $c['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $c['desc'] }}</p>
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
                    <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&h=400&fit=crop" alt="" style="width:100%;height:100%;min-height:280px;object-fit:cover;" loading="lazy">
                </div>
                <div style="padding:32px;">
                    @include('partials.ziara-section-head', [
                        'sub'   => 'Peace of Mind',
                        'title' => 'Ready to Protect Your <em>Trip?</em>',
                        'align' => 'left',
                    ])
                    <div class="ziara-prose">
                        <p>Add travel insurance during checkout for instant coverage. It takes just one click.</p>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:20px;">
                        <a href="{{ url('/flights/search') }}" class="ziara-btn ziara-btn--gold">Book With Insurance</a>
                        <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--outline">Ask a Question</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Coverage Questions',
            'title' => 'Insurance <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'When does coverage start?','a'=>'Coverage begins from the moment you purchase the insurance, or from the trip start date — whichever is later.'],
            ['q'=>'Can I buy insurance after booking?','a'=>'Yes, you can add insurance up to 48 hours before your departure date through your booking dashboard.'],
            ['q'=>'How do I file a claim?','a'=>'Contact our 24/7 emergency hotline or submit a claim through the help center with supporting documents.'],
            ['q'=>'Are pre-existing conditions covered?','a'=>'Standard and Premium plans include limited coverage for pre-existing conditions. Check plan details for specifics.'],
            ['q'=>'Can I get a refund on insurance?','a'=>'Insurance can be cancelled within 14 days of purchase (before trip start) for a full refund, if no claim has been filed.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Insurance Updates'])
@endsection
