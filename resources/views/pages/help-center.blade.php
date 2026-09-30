@extends('layouts.main')
@section('title', 'Help Center - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Help Center',
    'title'  => 'Help <em>Center</em>',
    'intro'  => 'Find answers, guides, and support resources',
    'extra'  => '<div style="max-width:460px;margin:0 auto;"><div style="position:relative;"><i class="fa-solid fa-magnifying-glass" aria-hidden="true" style="position:absolute;left:18px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:14px;"></i><input type="text" id="helpSearch" placeholder="Search help topics..." style="width:100%;padding:15px 20px 15px 46px;font-size:15px;color:#2c241f;background:#fff;border:0;border-radius:50px;outline:none;box-shadow:0 12px 30px rgba(0,0,0,.18);"></div></div>',
])

{{-- ===== TOPICS ===== --}}
<section class="zt-section zt-section--white" style="padding:64px 0 56px;">
    <div class="container">
        @php $cats = [
            ['icon'=>'fa-solid fa-plane','title'=>'Flights','desc'=>'Booking, changes, cancellations','link'=>url('/faq').'#flights'],
            ['icon'=>'fa-solid fa-hotel','title'=>'Hotels','desc'=>'Reservations, check-in, amenities','link'=>url('/faq').'#hotels'],
            ['icon'=>'fa-solid fa-credit-card','title'=>'Payments','desc'=>'Methods, refunds, invoices','link'=>url('/faq').'#payments'],
            ['icon'=>'fa-solid fa-user-circle','title'=>'Account','desc'=>'Registration, profile, points','link'=>url('/faq').'#account'],
        ]; @endphp
        <div class="ziara-minifeatures">
            @foreach($cats as $c)
            <a href="{{ $c['link'] }}" class="ziara-minifeature" style="text-decoration:none;color:inherit;display:block;">
                <span class="ziara-minifeature__icon"><i class="{{ $c['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $c['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $c['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== POPULAR ARTICLES ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Knowledge Base',
            'title' => 'Popular Help <em>Articles</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How do I download my e-ticket?','a'=>'After booking, go to your booking confirmation page and click "Download PDF". The e-ticket is also emailed to you automatically.'],
            ['q'=>'How do I change my flight dates?','a'=>'Contact support with your booking reference. If you have a flexible fare, changes are free. Non-flexible fares may incur a change fee.'],
            ['q'=>'My payment failed — what should I do?','a'=>'Check your balance/limit and try again. If using M-Pesa, ensure you have sufficient funds and enter the correct PIN. Contact support if the issue persists.'],
            ['q'=>'How do I request a refund?','a'=>'Submit a refund request via the Contact Us page with your booking reference. Refunds are processed within 5-7 business days.'],
            ['q'=>'Can I add extra baggage to my booking?','a'=>'Extra baggage can sometimes be added through the airline\'s website after booking. Contact support for assistance.'],
            ['q'=>'How do I earn and redeem loyalty points?','a'=>'Points are earned automatically on every booking (1 KES = 1 point). Redeem them for discounts on your next booking through your account dashboard.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

{{-- ===== CONTACT OPTIONS ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Still Need Help?',
            'title' => 'Reach Us <em>Anytime</em>',
            'desc'  => 'Our team is ready to assist you 24/7.',
            'align' => 'center',
        ])
        @php $opts = [
            ['icon'=>'fa-solid fa-comments','title'=>'Live Chat','desc'=>'Chat with a support agent in real time','link'=>url('/live-chat'),'label'=>'Start Chatting'],
            ['icon'=>'fa-solid fa-phone-volume','title'=>'Call Us','desc'=>'Speak to us directly on the phone','link'=>'tel:+254700123456','label'=>'+254 700 123 456'],
            ['icon'=>'fa-solid fa-envelope','title'=>'Email','desc'=>'Send us a message any time','link'=>url('/contactus'),'label'=>'Send an Email'],
        ]; @endphp
        <div class="ziara-infocards">
            @foreach($opts as $o)
            <div class="ziara-infocard">
                <span class="ziara-infocard__icon"><i class="{{ $o['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-infocard__title">{{ $o['title'] }}</h3>
                <div class="ziara-infocard__value">{{ $o['desc'] }}<br><a href="{{ $o['link'] }}">{{ $o['label'] }}</a></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Help Updates', 'desc' => 'Subscribe for service updates and travel tips.'])
@endsection

@push('scripts')
<script>
(function(){
    'use strict';
    var box = document.getElementById('ziaraFaqs');
    var srch = document.getElementById('helpSearch');
    if (!box || !srch) return;
    var items = box.querySelectorAll('.ziara-faq');
    var empty = box.querySelector('.ziara-faqs__empty');
    srch.addEventListener('input', function(){
        var q = this.value.toLowerCase(), visible = 0;
        items.forEach(function(item){
            var show = item.textContent.toLowerCase().indexOf(q) > -1;
            item.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        if (empty) empty.style.display = visible ? 'none' : 'block';
    });
})();
</script>
@endpush