@extends('layouts.main')

@section('content')
{{-- ===== HERO + SEARCH ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-25" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1 text-center">Find Your Perfect Flight</h1>
        <p class="text-xs text-gray-300 text-center mb-8">Search across 500+ airlines for the best fares to East Africa and beyond.</p>

        @include('flights.partials.search-widget', ['idPrefix' => 'sf'])
    </div>
</section>

{{-- ===== POPULAR ROUTES ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div><span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">Trending</span><h2 class="text-lg font-bold text-gray-900 mt-0.5">Popular Routes from Nairobi</h2></div>
        </div>
        @php $routes = [
            ['to'=>'Mombasa','code'=>'MBA','dur'=>'55m','price'=>'7,500','airline'=>'Kenya Airways','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=300&h=180&fit=crop'],
            ['to'=>'Zanzibar','code'=>'ZNZ','dur'=>'1h 30m','price'=>'12,000','airline'=>'Precision Air','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=300&h=180&fit=crop'],
            ['to'=>'Dar es Salaam','code'=>'DAR','dur'=>'1h 15m','price'=>'11,000','airline'=>'Fastjet','img'=>'https://images.unsplash.com/photo-1611348524140-53c9a25263d6?w=300&h=180&fit=crop'],
            ['to'=>'Kampala','code'=>'EBB','dur'=>'1h 20m','price'=>'14,500','airline'=>'Kenya Airways','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=300&h=180&fit=crop'],
            ['to'=>'Kigali','code'=>'KGL','dur'=>'1h 35m','price'=>'16,500','airline'=>'RwandAir','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=300&h=180&fit=crop'],
            ['to'=>'Addis Ababa','code'=>'ADD','dur'=>'2h 10m','price'=>'24,000','airline'=>'Ethiopian','img'=>'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=300&h=180&fit=crop'],
        ]; @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($routes as $r)
            <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="relative h-28 overflow-hidden">
                    <img src="{{ $r['img'] }}" alt="{{ $r['to'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-2 left-2.5"><span class="text-xs font-bold text-white">NBO → {{ $r['code'] }}</span><br><span class="text-[9px] text-white/70">{{ $r['to'] }} &bull; {{ $r['dur'] }}</span></div>
                </div>
                <div class="p-2.5 flex items-center justify-between">
                    <div><span class="text-[10px] text-gray-400">From</span><span class="text-xs font-bold text-blue-600 ml-1">KES {{ $r['price'] }}</span></div>
                    <span class="text-[9px] text-gray-400">{{ $r['airline'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-8 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="Travel" class="w-full h-52 md:h-64 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-6">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Save More</span>
                <h2 class="text-xl font-bold text-gray-900 mt-1 mb-2">Flight + Hotel Packages</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Bundle your flight with a hotel and save up to 30% on your total trip cost. Flexible cancellation on select packages.</p>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Browse Packages <i class="fas fa-arrow-right text-[10px]"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">How Flight Booking Works</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $steps = [['icon'=>'fa-search','t'=>'Search','d'=>'Enter your travel details above.'],['icon'=>'fa-list-check','t'=>'Compare','d'=>'Review prices across airlines.'],['icon'=>'fa-credit-card','t'=>'Pay','d'=>'Pay via M-Pesa, Equity or card.'],['icon'=>'fa-ticket-alt','t'=>'Fly','d'=>'Receive your e-ticket instantly.']]; @endphp
            @foreach($steps as $i=>$s)
            <div class="text-center p-4 rounded-xl bg-gray-50">
                <div class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-100 mb-2 relative"><i class="fas {{ $s['icon'] }} text-sm text-blue-600"></i><span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[8px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $i+1 }}</span></div>
                <h3 class="text-xs font-semibold text-gray-900 mb-0.5">{{ $s['t'] }}</h3>
                <p class="text-[10px] text-gray-500">{{ $s['d'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Flight Booking FAQs</h2>
        @php $faqs = [
            ['q'=>'How far in advance should I book?','a'=>'For the best prices, book domestic flights 2-4 weeks ahead and international flights 4-8 weeks ahead.'],
            ['q'=>'Can I change my flight after booking?','a'=>'Changes depend on your fare type. Flexible fares allow free changes; standard fares may incur a fee.'],
            ['q'=>'What baggage is included?','a'=>'Baggage allowance varies by airline and fare class. Check your ticket details for specifics.'],
            ['q'=>'How do I check in for my flight?','a'=>'Most airlines allow online check-in 24-48 hours before departure via their website or app.'],
            ['q'=>'What payment methods are accepted?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equity Eazzy Banking, credit/debit cards and bank transfers.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span>
                    <i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i>
                </button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
<section class="py-10 bg-blue-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Get Flight Deal Alerts</h2>
        <p class="text-[11px] text-blue-100 mb-4">Be the first to know about fare drops and flash sales.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault(); alert('Subscribed!'); this.reset();">
            <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){
    var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');
    document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});
    document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});
    if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}
}
</script>
@endpush
