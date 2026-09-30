@extends('layouts.main')
@section('title', 'Our Services - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Our Services',
    'title'  => 'Our <em>Services</em>',
    'intro'  => 'Comprehensive travel solutions for every journey.',
])

{{-- ===== MAIN SERVICES ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'What We Offer',
            'title' => 'Everything You Need <em>for Travel</em>',
            'align' => 'center',
        ])
        @php $services = [
            ['icon'=>'fa-solid fa-plane','title'=>'Flight Booking','desc'=>'Real-time search across 500+ airlines with competitive prices and instant confirmation.','bullets'=>['Real-time pricing','Flexible dates','Instant e-ticket'],'link'=>url('/flights/search')],
            ['icon'=>'fa-solid fa-hotel','title'=>'Hotel Booking','desc'=>'Find 2M+ accommodations worldwide with verified reviews and best rate guarantee.','bullets'=>['Price match guarantee','Free cancellation','Verified reviews'],'link'=>url('/hotels/search')],
            ['icon'=>'fa-solid fa-map-location-dot','title'=>'Tour Packages','desc'=>'Curated safari and adventure tours across East Africa\'s top destinations.','bullets'=>['Expert local guides','All-inclusive options','Group discounts'],'link'=>url('/tours')],
            ['icon'=>'fa-solid fa-suitcase-rolling','title'=>'Package Deals','desc'=>'Save up to 30% with flight + hotel bundles, custom-built for your trip.','bullets'=>['Bundle & save','Flexible booking','One-click checkout'],'link'=>url('/')],
            ['icon'=>'fa-solid fa-building','title'=>'Corporate Travel','desc'=>'Tailored solutions for businesses with volume discounts and dedicated account managers.','bullets'=>['Consolidated billing','Policy compliance','Priority support'],'link'=>url('/contactus')],
            ['icon'=>'fa-solid fa-shield-halved','title'=>'Travel Insurance','desc'=>'Comprehensive coverage for trip cancellations, medical emergencies, and lost baggage.','bullets'=>['Trip protection','Medical cover','24/7 claims support'],'link'=>url('/travel-insurance')],
        ]; @endphp
        <div class="ziara-features">
            @foreach($services as $s)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $s['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $s['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $s['desc'] }}</p>
                <ul class="ziara-feature__list">
                    @foreach($s['bullets'] as $b)<li><i class="fa-solid fa-circle-check" aria-hidden="true"></i>{{ $b }}</li>@endforeach
                </ul>
                <a href="{{ $s['link'] }}" class="ziara-feature__link">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== ADDITIONAL SERVICES ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'More Ways We Help',
            'title' => 'Additional <em>Services</em>',
            'align' => 'center',
        ])
        @php $extras = [
            ['icon'=>'fa-solid fa-arrow-right-arrow-left','title'=>'Currency Exchange','desc'=>'Competitive forex rates for your travels'],
            ['icon'=>'fa-solid fa-passport','title'=>'Visa Assistance','desc'=>'Hassle-free visa application support'],
            ['icon'=>'fa-solid fa-car','title'=>'Airport Transfers','desc'=>'Reliable pickup & drop-off services'],
            ['icon'=>'fa-solid fa-headset','title'=>'24/7 Support','desc'=>'Round-the-clock customer assistance'],
        ]; @endphp
        <div class="ziara-minifeatures">
            @foreach($extras as $e)
            <div class="ziara-minifeature">
                <span class="ziara-minifeature__icon"><i class="{{ $e['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $e['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $e['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-split">
            <div><img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop" alt="Travel" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy"></div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Get Started',
                    'title' => 'Ready to Plan <em>Your Trip?</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>Whether it's a quick domestic flight or an international adventure, we have the tools and expertise to make it seamless.</p>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
                    <a href="{{ url('/flights/search') }}" class="ziara-btn ziara-btn--gold">Book a Flight</a>
                    <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--outline">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== WHY CHOOSE US ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Why Choose Ziara Travels?',
            'title' => 'Reasons to <em>Travel With Us</em>',
            'align' => 'center',
        ])
        @php $why = [
            ['icon'=>'fa-solid fa-tag','title'=>'Best Prices','desc'=>'We negotiate directly with airlines and hotels to guarantee the best rates.'],
            ['icon'=>'fa-solid fa-lock','title'=>'Secure Payments','desc'=>'M-Pesa, STK Push, bank transfer, and cards — all protected with end-to-end encryption.'],
            ['icon'=>'fa-solid fa-heart','title'=>'Dedicated Support','desc'=>'Our team of travel experts is available 24/7 to help you at every step of your journey.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($why as $w)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $w['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $w['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $w['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Everything You Need to Know',
            'title' => 'Services <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'What destinations do you cover?','a'=>'We offer flights to 190+ countries and hotels in major cities worldwide, with a focus on East African destinations.'],
            ['q'=>'Do you offer group booking discounts?','a'=>'Yes! Groups of 10+ travelers receive volume discounts. Contact our corporate team for a custom quote.'],
            ['q'=>'What payment methods do you support?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equity Eazzy, bank transfers, and all major credit/debit cards.'],
            ['q'=>'Can I cancel and get a refund?','a'=>'Cancellation and refund policies depend on the specific booking type and fare rules. Flexible rates generally allow free cancellation.'],
            ['q'=>'Do you provide travel insurance?','a'=>'Yes, we offer comprehensive travel insurance covering trip cancellations, medical emergencies, lost luggage, and more.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Stay Informed'])
@endsection