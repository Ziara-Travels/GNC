@extends('layouts.main')
@section('title', 'Why Choose Us - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Why Choose <span class="text-amber-400">Ziara Travels</span></h1>
        <p class="text-xs text-gray-300">Trusted by thousands of travelers across East Africa</p>
    </div>
</section>

{{-- ===== KEY REASONS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Our Difference</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">What Sets Us Apart</h2>
        </div>
        @php $reasons = [
            ['icon'=>'fa-tag','title'=>'Best Prices Guaranteed','desc'=>'We compare fares from 500+ airlines and negotiate directly with hotels for the lowest available rates.'],
            ['icon'=>'fa-bolt','title'=>'Instant Booking','desc'=>'Search, compare, and book in seconds. Get e-tickets and confirmations delivered instantly to your email.'],
            ['icon'=>'fa-shield-alt','title'=>'Secure Payments','desc'=>'M-Pesa, STK Push, Equity Eazzy, bank transfer, and cards — all protected with end-to-end encryption.'],
            ['icon'=>'fa-headset','title'=>'24/7 Expert Support','desc'=>'Our team of travel specialists is available around the clock via chat, email, and phone.'],
            ['icon'=>'fa-undo','title'=>'Flexible Cancellation','desc'=>'Free cancellation on most bookings. Change your plans without penalties on flexible-rate rooms.'],
            ['icon'=>'fa-globe-africa','title'=>'East Africa Specialists','desc'=>'Deep expertise in East African destinations — Kenya, Tanzania, Uganda, Rwanda, and beyond.'],
        ]; @endphp
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($reasons as $r)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mb-2"><i class="fas {{ $r['icon'] }} text-emerald-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $r['title'] }}</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">{{ $r['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== STATS ===== --}}
<section class="py-10 bg-slate-900">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $stats = [['n'=>'10K+','l'=>'Flights Booked'],['n'=>'5K+','l'=>'Happy Travelers'],['n'=>'190+','l'=>'Countries'],['n'=>'98%','l'=>'Satisfaction Rate']]; @endphp
            @foreach($stats as $s)
            <div class="text-center"><div class="text-xl font-bold text-white">{{ $s['n'] }}</div><div class="text-[10px] text-gray-400">{{ $s['l'] }}</div></div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Start Today</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Ready to Experience the Difference?</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Join thousands of happy travelers who trust Ziara Travels for their booking needs.</p>
                <div class="flex gap-2">
                    <a href="{{ url('/flights/search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Search Flights</a>
                    <a href="{{ url('/contactus') }}" class="border border-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Common Questions</h2>
        @php $faqs = [
            ['q'=>'Do you offer a price match guarantee?','a'=>'Yes! If you find a lower price for the same flight or hotel within 24 hours of booking, we\'ll match it.'],
            ['q'=>'How quickly do I get my e-ticket?','a'=>'E-tickets are generated instantly after payment confirmation and sent to your email within minutes.'],
            ['q'=>'What if I need to cancel?','a'=>'Most flexible-rate bookings allow free cancellation. Non-refundable fares may have change fees — check your booking terms.'],
            ['q'=>'Is my payment information safe?','a'=>'Absolutely. We use SSL encryption and never store your full card details. M-Pesa transactions are processed through Safaricom\'s secure gateway.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-100 transition-colors" onclick="toggleFaq(this)"><span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span><i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i></button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Travel Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get the latest deals and travel tips in your inbox.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
