@extends('layouts.main')
@section('title', 'Travel Insurance - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Travel <span class="text-amber-400">Insurance</span></h1>
        <p class="text-xs text-gray-300">Comprehensive coverage for worry-free travel</p>
    </div>
</section>

{{-- ===== PLANS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Plans</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Choose Your Coverage</h2>
        </div>
        @php $plans = [
            ['name'=>'Basic','price'=>'$12','per'=>'/trip','features'=>['Trip cancellation up to $1,000','Medical expenses up to $10,000','Lost baggage up to $500','24/7 emergency hotline'],'color'=>'gray','popular'=>false],
            ['name'=>'Standard','price'=>'$25','per'=>'/trip','features'=>['Trip cancellation up to $5,000','Medical expenses up to $50,000','Lost baggage up to $1,500','Trip delay coverage','24/7 emergency hotline'],'color'=>'emerald','popular'=>true],
            ['name'=>'Premium','price'=>'$45','per'=>'/trip','features'=>['Trip cancellation up to $10,000','Medical expenses up to $100,000','Lost baggage up to $3,000','Trip delay & interruption','Adventure sports cover','24/7 emergency hotline'],'color'=>'amber','popular'=>false],
        ]; @endphp
        <div class="grid md:grid-cols-3 gap-3">
            @foreach($plans as $p)
            <div class="bg-gray-50 rounded-xl p-4 border {{ $p['popular'] ? 'border-emerald-400 ring-1 ring-emerald-400' : 'border-gray-100' }} relative">
                @if($p['popular'])<span class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full">Most Popular</span>@endif
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $p['name'] }}</h3>
                <div class="mb-2"><span class="text-xl font-bold text-gray-900">{{ $p['price'] }}</span><span class="text-[10px] text-gray-400">{{ $p['per'] }}</span></div>
                <ul class="space-y-1 mb-3">@foreach($p['features'] as $f)<li class="flex items-center gap-1.5 text-[10px] text-gray-600"><i class="fas fa-check text-emerald-500 text-[8px]"></i>{{ $f }}</li>@endforeach</ul>
                <button class="w-full {{ $p['popular'] ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }} text-[10px] font-semibold py-2 rounded-lg transition-colors">Choose {{ $p['name'] }}</button>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== WHAT'S COVERED ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">What's Covered</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php $covers = [
                ['icon'=>'fa-times-circle','title'=>'Trip Cancellation','desc'=>'Reimbursement for pre-paid, non-refundable trip costs'],
                ['icon'=>'fa-hospital','title'=>'Medical Emergency','desc'=>'Coverage for illness or injury during your trip'],
                ['icon'=>'fa-suitcase','title'=>'Lost Baggage','desc'=>'Compensation for delayed, lost, or damaged luggage'],
                ['icon'=>'fa-clock','title'=>'Trip Delays','desc'=>'Coverage for expenses due to flight delays'],
            ]; @endphp
            @foreach($covers as $c)
            <div class="bg-white rounded-xl p-3 text-center border border-gray-100">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-2"><i class="fas {{ $c['icon'] }} text-blue-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $c['title'] }}</h3>
                <p class="text-[10px] text-gray-400">{{ $c['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Peace of Mind</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Ready to Protect Your Trip?</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Add travel insurance during checkout for instant coverage. It takes just one click.</p>
                <div class="flex gap-2">
                    <a href="{{ url('/flights/search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Book With Insurance</a>
                    <a href="{{ url('/contactus') }}" class="border border-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-white transition-colors">Ask a Question</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Insurance FAQs</h2>
        @php $faqs = [
            ['q'=>'When does coverage start?','a'=>'Coverage begins from the moment you purchase the insurance, or from the trip start date — whichever is later.'],
            ['q'=>'Can I buy insurance after booking?','a'=>'Yes, you can add insurance up to 48 hours before your departure date through your booking dashboard.'],
            ['q'=>'How do I file a claim?','a'=>'Contact our 24/7 emergency hotline or submit a claim through the help center with supporting documents.'],
            ['q'=>'Are pre-existing conditions covered?','a'=>'Standard and Premium plans include limited coverage for pre-existing conditions. Check plan details for specifics.'],
            ['q'=>'Can I get a refund on insurance?','a'=>'Insurance can be cancelled within 14 days of purchase (before trip start) for a full refund, if no claim has been filed.'],
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
        <h2 class="text-base font-bold text-white mb-1">Insurance Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Stay informed about our insurance products and travel safety tips.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
