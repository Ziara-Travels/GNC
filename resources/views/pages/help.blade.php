@extends('layouts.main')
@section('title', 'Help & FAQ - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Help & FAQ',
    'title'  => 'Help & <em>FAQ</em>',
    'intro'  => 'Find answers to common questions about flight booking',
    'extra'  => '<div style="max-width:460px;margin:0 auto;"><div style="position:relative;"><i class="fa-solid fa-magnifying-glass" aria-hidden="true" style="position:absolute;left:18px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:14px;"></i><input type="text" id="faqSearch" placeholder="Search FAQs..." style="width:100%;padding:15px 20px 15px 46px;font-size:15px;color:#2c241f;background:#fff;border:0;border-radius:50px;outline:none;box-shadow:0 12px 30px rgba(0,0,0,.18);"></div></div>',
])

{{-- ===== FAQ SEARCH & CATEGORIES ===== --}}
<section class="zt-section zt-section--white" style="padding:72px 0 60px;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Frequently Asked Questions',
            'title' => 'Everything You Need to <em>Know</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How do I search for flights?','a'=>'Use the search form on our homepage. Enter your departure city, destination, travel dates, and number of passengers, then click Search.','cat'=>'booking'],
            ['q'=>'Can I book for multiple passengers?','a'=>'Yes. Select the number of adults, children, and infants in the search form before searching for flights.','cat'=>'booking'],
            ['q'=>'What payment methods do you accept?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equitel, and Equity bank payments via our secure Jenga API integration.','cat'=>'payment'],
            ['q'=>'Is my payment information secure?','a'=>'Absolutely. All transactions are encrypted and processed through secure payment gateways. We never store your payment details.','cat'=>'payment'],
            ['q'=>'Can I change my flight after booking?','a'=>'Changes depend on the airline\'s policy and fare conditions. Contact our support team or use the booking management section.','cat'=>'changes'],
            ['q'=>'How do I cancel a booking?','a'=>'Go to your bookings, select the booking you want to cancel, and follow the cancellation process. Refund policies vary by airline.','cat'=>'changes'],
            ['q'=>'Do you offer travel insurance?','a'=>'Yes! We offer Basic, Standard, and Premium travel insurance plans. Visit our Travel Insurance page for details.','cat'=>'general'],
            ['q'=>'How do I download my e-ticket?','a'=>'After successful booking, click the "Download E-Ticket" button on your booking confirmation page. It\'s also sent to your email as a PDF.','cat'=>'general'],
            ['q'=>'What if I don\'t receive a confirmation email?','a'=>'Check your spam/junk folder first. If you still can\'t find it, contact our support team with your booking reference.','cat'=>'general'],
            ['q'=>'How do I earn loyalty points?','a'=>'Points are earned automatically with every booking. Visit our Loyalty Points page to learn about tiers and rewards.','cat'=>'general'],
        ]; @endphp
        @include('partials.ziara-faqs', [
            'faqs' => $faqs,
            'tabs' => [
                'booking' => 'Booking',
                'payment' => 'Payment',
                'changes' => 'Changes',
                'general' => 'General',
            ],
        ])
    </div>
</section>

{{-- ===== CONTACT CTA ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-split">
            <div><img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=600&h=400&fit=crop" alt="Still need help?" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy"></div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Still Need Help?',
                    'title' => 'Contact Our Support <em>Team</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>Can't find the answer you're looking for? Our dedicated support team is ready to assist you.</p>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px;">
                    <a href="{{ url('/live-chat') }}" class="ziara-btn ziara-btn--gold"><i class="fa-solid fa-comment-dots" aria-hidden="true"></i> Live Chat</a>
                    <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--outline"><i class="fa-solid fa-envelope" aria-hidden="true"></i> Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Get Travel Tips & Updates', 'desc' => 'Subscribe for exclusive offers and helpful travel content.'])
@endsection

@push('scripts')
<script>
(function(){
    'use strict';
    var box = document.getElementById('ziaraFaqs');
    var srch = document.getElementById('faqSearch');
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