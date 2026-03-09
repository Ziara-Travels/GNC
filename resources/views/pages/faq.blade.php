@extends('layouts.main')
@section('title', 'Frequently Asked Questions - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Frequently Asked <span class="text-amber-400">Questions</span></h1>
        <p class="text-xs text-gray-300 mb-4">Find answers to common questions about our services</p>
        <div class="max-w-md mx-auto relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
            <input type="text" id="faqSearch" placeholder="Search FAQs..." class="w-full pl-8 pr-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
    </div>
</section>

{{-- ===== FAQ CATEGORIES ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        {{-- Category tabs --}}
        <div class="flex flex-wrap gap-1.5 justify-center mb-6">
            <button class="faq-cat-btn active bg-emerald-600 text-white text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" data-cat="all">All</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="booking">Booking</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="payment">Payment</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="flights">Flights</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="hotels">Hotels</button>
            <button class="faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="account">Account</button>
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

        <div class="space-y-2" id="faqList">
            @foreach($faqs as $faq)
            <div class="faq-item border border-gray-200 rounded-lg bg-gray-50 overflow-hidden" data-cat="{{ $faq['cat'] }}">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-100 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-xs font-semibold text-gray-900 pr-3 faq-question">{{ $faq['q'] }}</span>
                    <i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i>
                </button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>

        <p class="text-[10px] text-gray-400 text-center mt-4" id="noResults" style="display:none;">No matching questions found.</p>
    </div>
</section>

{{-- ===== STILL NEED HELP ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-lg font-bold text-gray-900 mb-2">Still Need Help?</h2>
        <p class="text-xs text-gray-500 mb-4">Our support team is available 24/7 to assist you.</p>
        <div class="flex justify-center gap-2">
            <a href="{{ url('/contactus') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors"><i class="fas fa-envelope mr-1 text-[10px]"></i>Contact Us</a>
            <a href="{{ url('/live-chat') }}" class="border border-gray-200 text-gray-700 text-xs font-semibold px-5 py-2 rounded-lg hover:bg-white transition-colors"><i class="fas fa-comments mr-1 text-[10px]"></i>Live Chat</a>
            <a href="tel:+254700123456" class="border border-gray-200 text-gray-700 text-xs font-semibold px-5 py-2 rounded-lg hover:bg-white transition-colors"><i class="fas fa-phone mr-1 text-[10px]"></i>Call Us</a>
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Get Travel Tips</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Subscribe for helpful tips, FAQs, and exclusive deals.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}

document.addEventListener('DOMContentLoaded',function(){
    // Category filter
    document.querySelectorAll('.faq-cat-btn').forEach(function(btn){
        btn.addEventListener('click',function(){
            document.querySelectorAll('.faq-cat-btn').forEach(function(b){b.className='faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors';});
            this.className='faq-cat-btn active bg-emerald-600 text-white text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors';
            var cat=this.dataset.cat;
            document.querySelectorAll('.faq-item').forEach(function(item){item.style.display=(cat==='all'||item.dataset.cat===cat)?'':'none';});
            document.getElementById('noResults').style.display=document.querySelectorAll('.faq-item[style=""],.faq-item:not([style])').length?'none':'block';
        });
    });
    // Search filter
    document.getElementById('faqSearch').addEventListener('input',function(){
        var q=this.value.toLowerCase();
        document.querySelectorAll('.faq-item').forEach(function(item){
            var text=item.querySelector('.faq-question').textContent.toLowerCase();
            item.style.display=text.includes(q)?'':'none';
        });
        document.querySelectorAll('.faq-cat-btn').forEach(function(b){b.className='faq-cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors';});
        document.querySelector('.faq-cat-btn[data-cat="all"]').className='faq-cat-btn active bg-emerald-600 text-white text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors';
        document.getElementById('noResults').style.display=document.querySelectorAll('.faq-item[style=""],.faq-item:not([style])').length?'none':'block';
    });
});
</script>
@endpush
