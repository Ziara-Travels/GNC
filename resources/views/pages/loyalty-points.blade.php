@extends('layouts.main')
@section('title', 'Loyalty Points - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1559526324-4b87b5e36e44?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <span class="inline-block bg-amber-500/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-3">Rewards Program</span>
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Loyalty <span class="text-amber-400">Points</span></h1>
        <p class="text-xs text-gray-300">Earn points on every booking and redeem them for discounts</p>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">How It Works</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Earn & Redeem in 3 Steps</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-3">
            @php $steps = [
                ['n'=>'1','icon'=>'fa-plane','title'=>'Book & Earn','desc'=>'Earn 1 point for every KES spent on flights, hotels, and packages.'],
                ['n'=>'2','icon'=>'fa-coins','title'=>'Accumulate','desc'=>'Points stack up across all your bookings. Track them in your dashboard.'],
                ['n'=>'3','icon'=>'fa-gift','title'=>'Redeem','desc'=>'Use your points for discounts on future bookings. 100 points = KES 1 off.'],
            ]; @endphp
            @foreach($steps as $s)
            <div class="text-center bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center mx-auto mb-2 text-xs font-bold">{{ $s['n'] }}</div>
                <i class="fas {{ $s['icon'] }} text-amber-500 text-sm mb-1"></i>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $s['title'] }}</h3>
                <p class="text-[10px] text-gray-500">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== TIERS ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Membership Tiers</h2>
        @php $tiers = [
            ['name'=>'Bronze','points'=>'0 - 4,999','perks'=>['1x earning rate','Basic support','Birthday bonus'],'color'=>'orange','icon'=>'fa-medal'],
            ['name'=>'Silver','points'=>'5,000 - 19,999','perks'=>['1.5x earning rate','Priority support','Free date changes','Birthday bonus'],'color'=>'gray','icon'=>'fa-award'],
            ['name'=>'Gold','points'=>'20,000+','perks'=>['2x earning rate','VIP support','Free changes & upgrades','Lounge access','Birthday bonus'],'color'=>'yellow','icon'=>'fa-crown'],
        ]; @endphp
        <div class="grid md:grid-cols-3 gap-3">
            @foreach($tiers as $t)
            <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                <i class="fas {{ $t['icon'] }} text-{{ $t['color'] }}-500 text-lg mb-1"></i>
                <h3 class="text-xs font-bold text-gray-900">{{ $t['name'] }}</h3>
                <p class="text-[10px] text-gray-400 mb-2">{{ $t['points'] }} pts</p>
                <ul class="space-y-1 text-left">@foreach($t['perks'] as $p)<li class="flex items-center gap-1.5 text-[10px] text-gray-600"><i class="fas fa-check text-emerald-500 text-[8px]"></i>{{ $p }}</li>@endforeach</ul>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Start Earning</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Book Your Next Trip & Earn Points</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Every booking earns you points towards free travel. Start your rewards journey today.</p>
                <div class="flex gap-2">
                    <a href="{{ url('/flights/search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Search Flights</a>
                    @auth <a href="{{ url('/dashboard') }}" class="border border-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-white transition-colors">My Points</a> @endauth
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Points FAQs</h2>
        @php $faqs = [
            ['q'=>'How do I check my point balance?','a'=>'Log in to your account and visit your dashboard. Your current points balance and tier status are displayed prominently.'],
            ['q'=>'Do points expire?','a'=>'Points are valid for 24 months from the date they were earned. Keep booking to maintain your balance!'],
            ['q'=>'Can I transfer points to someone else?','a'=>'Point transfers are not currently available. However, you can use your points to book travel for other people.'],
            ['q'=>'How do I redeem points?','a'=>'During checkout, you\'ll see an option to apply your available points as a discount. Select the amount you wish to redeem.'],
            ['q'=>'How do I get promoted to a higher tier?','a'=>'Tiers are based on total lifetime points earned. Keep booking and you\'ll automatically be upgraded when you reach the threshold.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)"><span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span><i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i></button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Rewards Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get notified about bonus point events and rewards promotions.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
