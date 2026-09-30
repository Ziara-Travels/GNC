@extends('layouts.main')
@section('title', 'GDPR & Data Protection - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'GDPR & Data Protection',
    'title'  => 'GDPR & Data <em>Protection</em>',
    'intro'  => 'How we protect and manage your personal data',
])

{{-- ===== YOUR RIGHTS ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Your Data Rights',
            'title' => 'Your Rights, <em>Guaranteed</em>',
            'align' => 'center',
        ])
        @php $rights = [
            ['icon'=>'fa-solid fa-eye','title'=>'Right to Access','text'=>'Request a copy of the personal data we hold about you at any time.'],
            ['icon'=>'fa-solid fa-pen','title'=>'Right to Rectify','text'=>'Correct any inaccurate or incomplete personal data we have.'],
            ['icon'=>'fa-solid fa-trash','title'=>'Right to Erasure','text'=>'Request deletion of your personal data under certain conditions.'],
            ['icon'=>'fa-solid fa-hand','title'=>'Right to Restrict','text'=>'Restrict how we process your data in specific circumstances.'],
            ['icon'=>'fa-solid fa-download','title'=>'Right to Portability','text'=>'Receive your data in a portable format to transfer elsewhere.'],
            ['icon'=>'fa-solid fa-circle-xmark','title'=>'Right to Object','text'=>'Object to processing of your data for marketing purposes.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($rights as $r)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $r['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $r['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $r['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== DATA WE COLLECT ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Transparency',
            'title' => 'Data We <em>Collect</em>',
            'align' => 'center',
        ])
        @php $data = [
            ['cat'=>'Identity Data','items'=>'Full name, date of birth, passport/ID number'],
            ['cat'=>'Contact Data','items'=>'Email address, phone number, billing/postal address'],
            ['cat'=>'Booking Data','items'=>'Travel itineraries, booking references, passenger details'],
            ['cat'=>'Payment Data','items'=>'M-Pesa transaction IDs (we never store payment credentials)'],
            ['cat'=>'Usage Data','items'=>'Pages visited, search queries, device and browser information'],
        ]; @endphp
        <ul class="ziara-checklist" style="max-width:760px;margin:0 auto;">
            @foreach($data as $d)
            <li class="ziara-checklist__item">
                <span class="ziara-checklist__icon"><i class="fa-solid fa-check" aria-hidden="true"></i></span>
                <span><strong style="color:var(--maroon-deep);">{{ $d['cat'] }}:</strong> {{ $d['items'] }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- ===== CONSENT PREFERENCES ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Your Preferences',
            'title' => 'Manage <em>Consent</em>',
            'align' => 'center',
        ])
        @php $consents = [
            ['label'=>'Essential Cookies','desc'=>'Required for website to function properly','locked'=>true],
            ['label'=>'Analytics Cookies','desc'=>'Help us understand how visitors use the site','locked'=>false],
            ['label'=>'Marketing Emails','desc'=>'Receive promotional offers and travel deals','locked'=>false],
            ['label'=>'Partner Sharing','desc'=>'Share data with travel partners for bookings','locked'=>false],
        ]; @endphp
        <div class="ziara-panel" style="max-width:560px;margin:0 auto;">
            @foreach($consents as $c)
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:16px 0;{{ !$loop->last ? 'border-bottom:1px solid var(--line);' : '' }}">
                <div>
                    <h3 style="margin:0;font-size:15px;font-weight:700;color:var(--ink);">{{ $c['label'] }}</h3>
                    <p style="margin:3px 0 0;font-size:12px;color:var(--muted);">{{ $c['desc'] }}</p>
                </div>
                <label style="position:relative;display:inline-flex;align-items:center;cursor:pointer;flex-shrink:0;">
                    <input type="checkbox" class="ziara-consent-toggle" {{ $c['locked'] ? 'checked disabled' : '' }} style="width:20px;height:20px;accent-color:var(--maroon);cursor:pointer;">
                </label>
            </div>
            @endforeach
            <div style="margin-top:20px;text-align:center;">
                <button type="button" class="ziara-btn ziara-btn--gold" style="width:100%;" onclick="alert('Preferences saved!')">Save Preferences</button>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Data Protection FAQs',
            'title' => 'Frequently Asked <em>Questions</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How long do you keep my data?','a'=>'We retain personal data for as long as your account is active or as needed to provide services. Booking records are kept for 7 years for legal compliance.'],
            ['q'=>'Do you share data with third parties?','a'=>'We share necessary data with airlines and hotels to process your bookings. We never sell your personal data to advertisers or data brokers.'],
            ['q'=>'How can I request data deletion?','a'=>'Email our Data Protection Officer at dpo@ziaratravel.com or use the Contact Us page. We process requests within 30 days.'],
            ['q'=>'Is my data transferred internationally?','a'=>'When booking international travel, some data is shared with foreign airlines. All transfers comply with GDPR safeguards.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Data Protection Updates', 'desc' => 'Stay informed about changes to our data protection practices.'])
@endsection