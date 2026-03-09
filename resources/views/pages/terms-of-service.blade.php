@extends('layouts.main')
@section('title', 'Terms of Service - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Terms of <span class="text-amber-400">Service</span></h1>
        <p class="text-xs text-gray-300">Last updated: August 2025</p>
    </div>
</section>

{{-- ===== TERMS CONTENT ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-3xl mx-auto px-4 space-y-6">
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
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
            <h2 class="text-xs font-bold text-gray-900 mb-1.5">{{ $s['title'] }}</h2>
            <p class="text-[11px] text-gray-500 leading-relaxed">{{ $s['text'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <p class="text-xs text-gray-500 mb-3">Have questions about our terms?</p>
        <a href="{{ url('/contactus') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors">Contact Support</a>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Stay Updated</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get notified about policy changes and travel updates.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection
