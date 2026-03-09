@extends('layouts.main')
@section('title', 'Help & FAQ - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Help & <span class="text-amber-400">FAQ</span></h1>
        <p class="text-xs text-gray-300 mb-4">Find answers to common questions about flight booking</p>
        <div class="max-w-md mx-auto"><input type="text" id="faqSearch" placeholder="Search FAQs..." class="w-full px-4 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none"></div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-2 mb-6">
            <button class="cat-btn active bg-emerald-600 text-white text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors" data-cat="all">All</button>
            <button class="cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="booking">Booking</button>
            <button class="cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="payment">Payment</button>
            <button class="cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="changes">Changes</button>
            <button class="cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors" data-cat="general">General</button>
        </div>

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

        <div class="space-y-2" id="faqList">
            @foreach($faqs as $faq)
            <div class="faq-item border border-gray-200 rounded-lg bg-gray-50 overflow-hidden" data-cat="{{ $faq['cat'] }}">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-100 transition-colors" onclick="toggleFaq(this)"><span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span><i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i></button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CONTACT CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Still Need Help?</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Contact Our Support Team</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Can't find the answer you're looking for? Our dedicated support team is ready to assist you.</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ url('/live-chat') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors"><i class="fas fa-comment-dots mr-1"></i>Live Chat</a>
                    <a href="{{ url('/contactus') }}" class="inline-block bg-white border border-emerald-600 text-emerald-600 hover:bg-emerald-50 text-xs font-semibold px-4 py-2 rounded-lg transition-colors"><i class="fas fa-envelope mr-1"></i>Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Get Travel Tips & Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Subscribe for exclusive offers and helpful travel content.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}
(function(){
    document.querySelectorAll('.cat-btn').forEach(function(b){b.addEventListener('click',function(){document.querySelectorAll('.cat-btn').forEach(function(x){x.className='cat-btn bg-gray-100 text-gray-600 text-[10px] font-semibold px-3 py-1.5 rounded-full hover:bg-gray-200 transition-colors';});this.className='cat-btn active bg-emerald-600 text-white text-[10px] font-semibold px-3 py-1.5 rounded-full transition-colors';var cat=this.dataset.cat;document.querySelectorAll('.faq-item').forEach(function(item){item.style.display=(cat==='all'||item.dataset.cat===cat)?'block':'none';});});});
    document.getElementById('faqSearch').addEventListener('input',function(){var q=this.value.toLowerCase();document.querySelectorAll('.faq-item').forEach(function(item){item.style.display=item.textContent.toLowerCase().indexOf(q)>-1?'block':'none';});});
})();
</script>
@endpush
