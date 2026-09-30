@extends('layouts.main')
@section('title', 'Frequently Asked Questions - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@push('styles')
<style>
    .ziara-faq-search{max-width:480px;margin:0 auto;}
    .ziara-faq-search .ziara-faq-search__wrap{position:relative;}
    .ziara-faq-search i{position:absolute;left:18px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:14px;z-index:2;}
    .ziara-faq-search input{width:100%;padding:15px 20px 15px 46px;font-size:15px;color:rgba(255,255,255,.95);background:rgba(255,255,255,.10);border:1.5px solid rgba(255,255,255,.18);border-radius:50px;outline:none;transition:border-color .3s, background .3s;}
    .ziara-faq-search input:focus{border-color:var(--gold);background:rgba(255,255,255,.16);}
    .ziara-faq-search input::placeholder{color:rgba(255,255,255,.6);}
</style>
@endpush
@php
    $faqSearch = '<div class="ziara-faq-search"><div class="ziara-faq-search__wrap"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><input type="text" id="faqSearch" placeholder="Search FAQs..." aria-label="Search FAQs"></div></div>';
@endphp
@include('partials.ziara-page-hero', [
    'kicker' => 'FAQ',
    'title'  => 'Frequently Asked <em>Questions</em>',
    'intro'  => 'Find answers to common questions about our services.',
    'extra'  => $faqSearch,
])

{{-- ===== FAQ CATEGORIES ===== --}}
<section class="zt-section zt-section--white" style="padding-top:64px;">
    <div class="container" style="max-width:820px;">
        {{-- Category tabs --}}
        <div class="ziara-tabs" style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin-bottom:36px;">
            <button class="faq-cat-btn active bg-emerald-600 text-white text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" style="padding:9px 20px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;border:1.5px solid var(--gold);background:var(--gold);color:var(--maroon-deep);cursor:pointer;" data-cat="all">All</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" style="padding:9px 20px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;border:1.5px solid var(--line);background:#fff;color:var(--muted);cursor:pointer;" data-cat="booking">Booking</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" style="padding:9px 20px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;border:1.5px solid var(--line);background:#fff;color:var(--muted);cursor:pointer;" data-cat="payment">Payment</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" style="padding:9px 20px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;border:1.5px solid var(--line);background:#fff;color:var(--muted);cursor:pointer;" data-cat="flights">Flights</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" style="padding:9px 20px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;border:1.5px solid var(--line);background:#fff;color:var(--muted);cursor:pointer;" data-cat="hotels">Hotels</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" style="padding:9px 20px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;border:1.5px solid var(--line);background:#fff;color:var(--muted);cursor:pointer;" data-cat="account">Account</button>
        </div>

        @php $faqs = [
            ['cat'=>'booking','q'=>'How do I make a booking?','a'=>'Search for your desired flight or hotel on our homepage, select an option, fill in passenger/guest details, and complete payment. You\'ll receive an instant confirmation.'],
            ['cat'=>'booking','q'=>'Can I modify my booking after payment?','a'=>'Yes, modifications are possible depending on your fare type. Flexible fares allow free changes. Contact our support team with your booking reference.'],
            ['cat'=>'booking','q'=>'How do I cancel my booking?','a'=>'You can request cancellation through your account dashboard or by contacting support. Refund eligibility depends on the fare rules.'],
            ['cat'=>'booking','q'=>'Do you offer group bookings?','a'=>'Yes! Groups of 10 or more travelers qualify for special rates. Contact our corporate team for a custom quote.'],
            ['cat'=>'payment','q'=>'What payment methods do you accept?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equity Eazzy, bank transfers, and all major credit/debit cards (Visa, Mastercard).'],
            ['cat'=>'payment','q'=>'Is my payment information secure?','a'=>'Absolutely. We use SSL encryption for all transactions and never store your full card details. M-Pesa payments are processed through Safaricom\'s secure gateway.'],
            ['cat'=>'payment','q'=>'When will I be charged?','a'=>'Payment is processed at the time of booking. For hotels, some properties may charge at check-in depending on the rate type.'],
            ['cat'=>'payment','q'=>'How do I get a refund?','a'=>'Refund requests are processed within 5-7 business days. M-Pesa refunds are typically faster. Contact support with your booking reference.'],
            ['cat'=>'flights','q'=>'How do I get my e-ticket?','a'=>'After successful payment, your e-ticket is generated instantly and sent to your email. You can also download it from the booking confirmation page.'],
            ['cat'=>'flights','q'=>'Can I choose my seat?','a'=>'Seat selection depends on the airline. Some airlines allow free seat selection, while others charge a fee. This is handled during check-in.'],
            ['cat'=>'flights','q'=>'What happens if my flight is cancelled?','a'=>'If an airline cancels your flight, you\'re entitled to a full refund or rebooking at no extra cost. We\'ll notify you immediately.'],
            ['cat'=>'flights','q'=>'Is baggage included in the ticket price?','a'=>'Most economy tickets include a carry-on bag. Checked baggage allowance varies by airline and fare class — this is displayed during search.'],
            ['cat'=>'hotels','q'=>'Can I get a hotel price match?','a'=>'Yes! If you find a lower price on the same hotel and dates within 24 hours, we\'ll match it.'],
            ['cat'=>'hotels','q'=>'What is included in the room rate?','a'=>'Room rates typically include taxes. Extra services like breakfast, parking, or Wi-Fi depend on the hotel and rate selected.'],
            ['cat'=>'hotels','q'=>'Can I request early check-in?','a'=>'You can add special requests during booking. The hotel will do their best to accommodate, but it\'s not guaranteed.'],
            ['cat'=>'account','q'=>'How do I create an account?','a'=>'Click "Register" in the top navigation bar, fill in your details, and verify your email. You can also sign up during your first booking.'],
            ['cat'=>'account','q'=>'I forgot my password. What do I do?','a'=>'Click "Forgot Password" on the login page and enter your email. You\'ll receive a password reset link within minutes.'],
            ['cat'=>'account','q'=>'How do I earn loyalty points?','a'=>'Points are earned automatically on every booking. 1 KES spent = 1 point. Points can be redeemed for discounts on future bookings.'],
        ]; @endphp

        <div class="ziara-faqs" id="faqList" style="gap:14px;">
            @foreach($faqs as $faq)
            <div class="ziara-faq faq-item" data-cat="{{ $faq['cat'] }}">
                <button type="button" class="ziara-faq__q faq-toggle" onclick="toggleFaq(this)">
                    <span class="faq-question">{{ $faq['q'] }}</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="ziara-faq__a faq-content"><p style="margin:0;">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>

        <p class="text-[10px] text-gray-400 text-center mt-4" style="font-size:14px;color:var(--muted);display:none;" id="noResults">No matching questions found.</p>
    </div>
</section>

{{-- ===== STILL NEED HELP ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container" style="text-align:center;">
        @include('partials.ziara-section-head', [
            'sub'   => 'Still Need Help?',
            'title' => 'We\'re Here <em>for You</em>',
            'align' => 'center',
        ])
        <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-top:-16px;">
            <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--gold"><i class="fa-solid fa-envelope" aria-hidden="true"></i>Contact Us</a>
            <a href="{{ url('/live-chat') }}" class="ziara-btn ziara-btn--outline"><i class="fa-solid fa-comments" aria-hidden="true"></i>Live Chat</a>
            <a href="tel:+254700123456" class="ziara-btn ziara-btn--outline"><i class="fa-solid fa-phone" aria-hidden="true"></i>Call Us</a>
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Get Travel Tips'])
@endsection

@push('scripts')
<script>
function toggleFaq(btn){var item=btn.closest('.ziara-faq');var isOpen=item.classList.contains('is-open');document.querySelectorAll('.ziara-faq').forEach(function(el){el.classList.remove('is-open');el.querySelector('.ziara-faq__a').style.display='none';el.querySelector('.faq-toggle i').style.transform='rotate(0)';});if(!isOpen){item.classList.add('is-open');item.querySelector('.ziara-faq__a').style.display='block';item.querySelector('.faq-toggle i').style.transform='rotate(180deg)';}}

document.addEventListener('DOMContentLoaded',function(){
    function resetTabs(){
        document.querySelectorAll('.faq-cat-btn').forEach(function(b){b.style.background='#fff';b.style.color='var(--muted)';b.style.borderColor='var(--line)';});
    }
    // Category filter
    document.querySelectorAll('.faq-cat-btn').forEach(function(btn){
        btn.addEventListener('click',function(){
            resetTabs();
            this.style.background='var(--gold)';this.style.color='var(--maroon-deep)';this.style.borderColor='var(--gold)';
            var cat=this.dataset.cat, visible=0;
            document.querySelectorAll('.faq-item').forEach(function(item){
                var show=(cat==='all'||item.dataset.cat===cat);item.style.display=show?'':'none';if(show)visible++;
            });
            document.getElementById('noResults').style.display=visible?'none':'block';
            if(visible)document.getElementById('noResults').style.display='none';
        });
    });
    // Search filter
    document.getElementById('faqSearch').addEventListener('input',function(){
        var q=this.value.toLowerCase(), visible=0;
        document.querySelectorAll('.faq-item').forEach(function(item){
            var text=item.querySelector('.faq-question').textContent.toLowerCase();
            var show=text.includes(q);item.style.display=show?'':'none';if(show)visible++;
        });
        resetTabs();
        document.querySelector('.faq-cat-btn[data-cat="all"]').style.background='var(--gold)';document.querySelector('.faq-cat-btn[data-cat="all"]').style.color='var(--maroon-deep)';document.querySelector('.faq-cat-btn[data-cat="all"]').style.borderColor='var(--gold)';
        document.getElementById('noResults').style.display=visible?'none':'block';
    });
});
</script>
@endpush