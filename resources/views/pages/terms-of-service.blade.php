@extends('layouts.main')
@section('title', 'Terms of Service - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Terms of Service',
    'title'  => 'Terms of <em>Service</em>',
    'intro'  => 'Last updated: August 2025',
])

<section class="zt-section zt-section--white">
    <div class="container" style="max-width:960px;">
        @include('partials.ziara-section-head', [
            'sub'   => 'Legal Terms',
            'title' => 'Our <em>Terms</em>',
            'align' => 'center',
        ])
        @php $sections = [
            ['title'=>'1. Acceptance of Terms','text'=>'By accessing and using Ziara Travel\'s website and services, you agree to be bound by these Terms of Service. If you do not agree, please discontinue use of our services.'],
            ['title'=>'2. Services Description','text'=>'Ziara Travel provides an online platform for searching, comparing, and booking flights, hotels, and travel-related services. We act as an intermediary between you and travel service providers such as airlines and hotels.'],
            ['title'=>'3. Account Registration','text'=>'To make bookings, you may need to create an account. You are responsible for maintaining the confidentiality of your credentials and for all activities under your account. Provide accurate and current information during registration.'],
            ['title'=>'4. Booking & Payment','text'=>'All bookings are subject to availability and confirmation. Prices are displayed in the currency indicated and may change until payment is confirmed. We accept M-Pesa, M-Pesa STK Push, Equitel, and Equity bank payments. Full payment is required at the time of booking.'],
            ['title'=>'5. Cancellations & Refunds','text'=>'Cancellation policies vary by airline, hotel, and fare type. Refund eligibility and processing times are determined by individual service providers. Administrative fees may apply. Contact our support team for assistance with cancellations.'],
            ['title'=>'6. User Responsibilities','text'=>'You agree to provide accurate travel information, comply with airline and hotel policies, carry valid travel documents (passport, visa), and not use our platform for unlawful purposes.'],
            ['title'=>'7. Limitation of Liability','text'=>'Ziara Travel acts as an intermediary and is not liable for acts, omissions, or failures of airlines, hotels, or other travel providers. We are not responsible for schedule changes, cancellations by providers, or force majeure events.'],
            ['title'=>'8. Intellectual Property','text'=>'All content on this website, including logos, text, graphics, and software, is the property of Ziara Travel or its licensors and is protected by intellectual property laws.'],
            ['title'=>'9. Privacy','text'=>'Your use of our services is also governed by our Privacy Policy, which outlines how we collect, use, and protect your personal information.'],
            ['title'=>'10. Modifications','text'=>'We reserve the right to modify these terms at any time. Changes will be posted on this page with an updated revision date. Continued use of our services constitutes acceptance of modified terms.'],
            ['title'=>'11. Contact','text'=>'For questions about these terms, please contact us at support@ziaratravel.com or visit our Contact Us page.'],
        ]; @endphp

        @foreach($sections as $s)
        <div class="ziara-panel">
            <h2 class="ziara-panel__title">{{ $s['title'] }}</h2>
            <div class="ziara-prose"><p>{{ $s['text'] }}</p></div>
        </div>
        @endforeach

        <div style="text-align:center;margin-top:36px;">
            <p style="font-size:15px;color:var(--muted);margin:0 0 16px;">Have questions about our terms?</p>
            <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--gold">Contact Support</a>
        </div>
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Stay Updated'])
@endsection
