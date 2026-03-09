@extends('layouts.main')
@section('title', 'VIP Membership - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">VIP <span class="text-amber-400">Membership</span></h1>
        <p class="text-xs text-gray-300">Unlock exclusive perks and premium travel experiences</p>
    </div>
</section>

{{-- ===== MEMBERSHIP TIERS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Choose Your Tier</h2>
        <div class="grid sm:grid-cols-3 gap-3">
            @php $tiers = [
                ['name'=>'Silver VIP','price'=>'$29','period'=>'/month','color'=>'gray','features'=>['Priority customer support','5% discount on flights','Free seat selection','Early access to deals','Monthly travel newsletter'],'popular'=>false],
                ['name'=>'Gold VIP','price'=>'$59','period'=>'/month','color'=>'amber','features'=>['Everything in Silver','10% discount on flights','Free luggage upgrade','Airport lounge access','Dedicated travel advisor','Flexible cancellations'],'popular'=>true],
                ['name'=>'Platinum VIP','price'=>'$99','period'=>'/month','color'=>'emerald','features'=>['Everything in Gold','15% discount on flights','Complimentary travel insurance','Private airport transfers','VIP concierge service','Exclusive partner perks'],'popular'=>false],
            ]; @endphp
            @foreach($tiers as $t)
            <div class="relative bg-gray-50 rounded-xl p-4 border {{ $t['popular'] ? 'border-amber-400 ring-2 ring-amber-100' : 'border-gray-100' }}">
                @if($t['popular'])<span class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-amber-400 text-[8px] font-bold text-gray-900 px-2.5 py-0.5 rounded-full">MOST POPULAR</span>@endif
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $t['name'] }}</h3>
                <div class="mb-3"><span class="text-xl font-bold text-gray-900">{{ $t['price'] }}</span><span class="text-[10px] text-gray-400">{{ $t['period'] }}</span></div>
                <ul class="space-y-1.5 mb-4">
                    @foreach($t['features'] as $f)<li class="flex gap-1.5 text-[10px] text-gray-600"><i class="fas fa-check text-emerald-500 text-[8px] mt-0.5"></i>{{ $f }}</li>@endforeach
                </ul>
                <button class="w-full {{ $t['popular'] ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white text-xs font-semibold py-2 rounded-lg transition-colors" onclick="alert('VIP registration coming soon!')">Get Started</button>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== PERKS ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">VIP Benefits</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([['icon'=>'fa-tags','title'=>'Exclusive Discounts','text'=>'Save up to 15% on every booking'],['icon'=>'fa-couch','title'=>'Lounge Access','text'=>'Relax in airport lounges worldwide'],['icon'=>'fa-headset','title'=>'Priority Support','text'=>'Skip the queue with VIP support'],['icon'=>'fa-shield-alt','title'=>'Free Insurance','text'=>'Complimentary travel protection']] as $p)
            <div class="bg-white rounded-xl p-4 text-center border border-gray-100">
                <div class="w-8 h-8 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas {{ $p['icon'] }} text-amber-500 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $p['title'] }}</h3>
                <p class="text-[10px] text-gray-500">{{ $p['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-amber-500 uppercase tracking-wider">Limited Offer</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Start Your VIP Journey Today</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Join now and get your first month free. Experience travel the way it was meant to be.</p>
                <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors" onclick="alert('Coming soon!')">Claim Free Trial</button>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Membership FAQs</h2>
        @php $faqs = [
            ['q'=>'Can I cancel my membership anytime?','a'=>'Yes, you can cancel at any time with no cancellation fees. Your benefits remain active until the end of the billing period.'],
            ['q'=>'How do I upgrade my tier?','a'=>'You can upgrade directly from your account dashboard. The price difference is prorated for the remaining billing period.'],
            ['q'=>'Do VIP discounts apply to all flights?','a'=>'Yes, VIP discounts apply to all flights booked through our platform, subject to availability and fare conditions.'],
            ['q'=>'Can I share my membership?','a'=>'Membership benefits are personal but Gold and Platinum members can add one companion for lounge access.'],
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
        <h2 class="text-base font-bold text-white mb-1">VIP Travel Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get exclusive VIP offers and membership updates.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
