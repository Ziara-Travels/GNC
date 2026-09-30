@extends('layouts.main')
@section('title', 'Contact Us - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Contact Us',
    'title'  => 'Customer <em>Support</em>',
    'intro'  => 'Get help with your bookings and travel needs — we\'re here 24/7.',
])

{{-- ===== QUICK HELP ===== --}}
<section class="zt-section zt-section--white" style="padding-bottom:48px;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Quick Help',
            'title' => 'Get <em>Instant Support</em>',
            'align' => 'center',
        ])
        @php $help = [
            ['icon'=>'fa-solid fa-comments','title'=>'Live Chat','desc'=>'Real-time support','btn'=>'Start Chat','href'=>'#'],
            ['icon'=>'fa-solid fa-envelope','title'=>'Email','desc'=>'Send us a message','btn'=>'Send Email','href'=>'#contact-form'],
            ['icon'=>'fa-solid fa-circle-question','title'=>'FAQ','desc'=>'Common questions','btn'=>'Browse FAQ','href'=>route('help')],
            ['icon'=>'fa-solid fa-phone','title'=>'Phone','desc'=>'Speak to our team','btn'=>'Call Now','href'=>'tel:+254700123456'],
        ]; @endphp
        <div class="ziara-minifeatures">
            @foreach($help as $h)
            <div class="ziara-minifeature">
                <span class="ziara-minifeature__icon"><i class="{{ $h['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $h['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $h['desc'] }}</p>
                <a href="{{ $h['href'] }}" class="ziara-btn ziara-btn--maroon" style="margin-top:14px;padding:9px 18px;font-size:11px;">{{ $h['btn'] }}</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CONTACT FORM ===== --}}
<section id="contact-form" class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-split" style="grid-template-columns:1fr 1.2fr;">
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Send Message',
                    'title' => 'Get <em>in Touch</em>',
                    'align' => 'left',
                ])
                <p class="ziara-prose" style="margin-top:-28px;">Fill in the form and our team will get back to you as soon as possible. We usually respond within 24 hours.</p>
                <ul class="ziara-checklist" style="margin-top:18px;">
                    <li class="ziara-checklist__item"><span class="ziara-checklist__icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></span> Average response under 24 hours</li>
                    <li class="ziara-checklist__item"><span class="ziara-checklist__icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></span> Dedicated travel experts on hand</li>
                    <li class="ziara-checklist__item"><span class="ziara-checklist__icon"><i class="fa-solid fa-lock" aria-hidden="true"></i></span> Your details stay private and secure</li>
                </ul>
            </div>
            <div class="ziara-panel">
                @if(session('success'))
                <div class="ziara-note ziara-success" style="margin-bottom:18px;">
                    <p style="margin:0;"><b><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Success</b> — {{ session('success') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="ziara-note ziara-error" style="margin-bottom:18px;">
                    <p style="margin:0 0 4px;"><b><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Please correct the following:</b></p>
                    <ul style="margin:0;padding-left:16px;font-size:13px;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('contactus.submit') }}" method="POST" class="ziara-form" style="max-width:none;">
                    @csrf
                    <div class="ziara-row" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                        <div class="ziara-field">
                            <label for="contact-name">Full Name</label>
                            <input type="text" id="contact-name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="ziara-field">
                            <label for="contact-email">Email Address</label>
                            <input type="email" id="contact-email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="ziara-row" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                        <div class="ziara-field">
                            <label for="contact-phone">Phone <span style="font-weight:400;text-transform:none;letter-spacing:0;">(Optional)</span></label>
                            <input type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="ziara-field">
                            <label for="contact-subject">Subject</label>
                            <select id="contact-subject" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="booking-inquiry" {{ old('subject')=='booking-inquiry'?'selected':'' }}>Booking Inquiry</option>
                                <option value="technical-support" {{ old('subject')=='technical-support'?'selected':'' }}>Technical Support</option>
                                <option value="payment-issue" {{ old('subject')=='payment-issue'?'selected':'' }}>Payment Issue</option>
                                <option value="flight-change" {{ old('subject')=='flight-change'?'selected':'' }}>Flight Change</option>
                                <option value="refund-request" {{ old('subject')=='refund-request'?'selected':'' }}>Refund Request</option>
                                <option value="general-inquiry" {{ old('subject')=='general-inquiry'?'selected':'' }}>General Inquiry</option>
                                <option value="other" {{ old('subject')=='other'?'selected':'' }}>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="ziara-field">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" name="message" rows="4" required placeholder="Describe your inquiry in detail...">{{ old('message') }}</textarea>
                    </div>
                    <div class="ziara-form__actions">
                        <button type="submit" class="ziara-btn ziara-btn--gold"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ===== CONTACT INFO ===== --}}
<section class="zt-section zt-section--dark" style="padding:72px 0;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Other Ways to Reach Us',
            'title' => 'We\'re Always <em>Available</em>',
            'align' => 'center',
            'light' => true,
        ])
        @php $channels = [
            ['icon'=>'fa-solid fa-phone','title'=>'Phone','desc'=>'Available 24/7','value'=>'+254 700 123 456','href'=>'tel:+254700123456'],
            ['icon'=>'fa-solid fa-envelope','title'=>'Email','desc'=>'Response within 24h','value'=>'support@ziaratravels.com','href'=>'mailto:support@ziaratravels.com'],
            ['icon'=>'fa-brands fa-whatsapp','title'=>'WhatsApp','desc'=>'Chat instantly','value'=>'+254 700 123 456','href'=>'https://wa.me/254700123456'],
        ]; @endphp
        <div class="ziara-infocards-init" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            @foreach($channels as $ch)
            <div class="ziara-infocard" style="background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.10);">
                <span class="ziara-infocard__icon" style="background:rgba(201,162,39,.16);color:var(--gold-bright);"><i class="{{ $ch['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-infocard__title" style="color:#fff;">{{ $ch['title'] }}</h3>
                <p class="ziara-infocard__value" style="color:rgba(255,255,255,.72);">{{ $ch['desc'] }}</p>
                <p class="ziara-infocard__value" style="margin:2px 0 0;"><a href="{{ $ch['href'] }}" style="color:var(--gold-bright);">{{ $ch['value'] }}</a></p>
            </div>
            @endforeach
        </div>
        <p class="ziara-infocard__value" style="text-align:center;color:rgba(255,255,255,.72);margin:28px 0 0;"><i class="fa-solid fa-location-dot" style="color:var(--gold);"></i> Ziara Travels, Nairobi, Kenya</p>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Before You Reach Out',
            'title' => 'Contact <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How quickly will I get a response?','a'=>'Live chat is instant. Email inquiries are typically answered within 24 hours. Phone support is available 24/7.'],
            ['q'=>'Can I change or cancel my booking?','a'=>'Yes, changes and cancellations depend on the fare rules of your ticket. Contact support with your booking reference for assistance.'],
            ['q'=>'How do I request a refund?','a'=>'Use the contact form with subject "Refund Request" and include your booking reference. Our team will process it according to the fare rules.'],
            ['q'=>'Do you have an office I can visit?','a'=>'Our main office is in Nairobi. Please call ahead to schedule an appointment for in-person meetings.'],
            ['q'=>'What information should I include in my message?','a'=>'Include your booking reference (if applicable), full name, email, and a detailed description of your issue for the fastest resolution.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Need Travel Tips?'])
@endsection