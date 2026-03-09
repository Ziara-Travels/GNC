@extends('layouts.main')

@section('content')
{{-- ===== HERO + SEARCH ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-25" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1 text-center">Find Your Perfect Flight</h1>
        <p class="text-xs text-gray-300 text-center mb-8">Search across 500+ airlines for the best fares to East Africa and beyond.</p>

        <div class="bg-white rounded-xl shadow-xl overflow-hidden max-w-4xl mx-auto">
            {{-- Trip type tabs --}}
            <div class="flex border-b border-gray-100">
                <button class="trip-tab active flex-1 py-2.5 text-center text-xs font-semibold transition-all" data-type="round_trip" onclick="setTripType('round_trip')"><i class="fas fa-exchange-alt text-[10px] mr-1"></i> Round Trip</button>
                <button class="trip-tab flex-1 py-2.5 text-center text-xs font-semibold transition-all" data-type="one_way" onclick="setTripType('one_way')"><i class="fas fa-long-arrow-alt-right text-[10px] mr-1"></i> One Way</button>
            </div>

            <form id="flightSearchForm" action="/api/search" method="POST" class="p-4">
                @csrf
                <input type="hidden" name="trip_type" value="round_trip" id="trip_type_input">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                    <div class="relative">
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">From</label>
                        <div class="relative">
                            <i class="fas fa-plane-departure absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                            <input type="text" name="from" id="sf_from" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                            <input type="hidden" name="from_iata" id="sf_from_iata">
                        </div>
                        <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>
                    </div>
                    <div class="relative">
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">To</label>
                        <div class="relative">
                            <i class="fas fa-plane-arrival absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                            <input type="text" name="to" id="sf_to" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500 airport-autocomplete" placeholder="City or Airport" autocomplete="off" required>
                            <input type="hidden" name="to_iata" id="sf_to_iata">
                        </div>
                        <div class="airport-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>
                    </div>
                    <div>
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Departure</label>
                        <input type="date" name="date" id="sf_depart" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500" required>
                    </div>
                    <div id="returnWrap">
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Return</label>
                        <input type="date" name="return_date" id="sf_return" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-3 mb-3">
                    <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Adults</label><select name="adults" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=9;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                    <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Children</label><select name="children" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=0;$i<=8;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                    <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Infants</label><select name="infants" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=0;$i<=4;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                    <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Cabin</label><select name="travel_class" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800"><option value="ECONOMY">Economy</option><option value="PREMIUM_ECONOMY">Premium</option><option value="BUSINESS">Business</option><option value="FIRST">First</option></select></div>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2"><i class="fas fa-search text-[10px]"></i> Search Flights</button>
            </form>
        </div>
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

@push('styles')
<style>
.trip-tab{color:#9ca3af;border-bottom:2px solid transparent;}.trip-tab.active{color:#2563eb;border-bottom-color:#2563eb;background:#eff6ff;}.trip-tab:hover:not(.active){background:#f9fafb;}
</style>
@endpush

@push('scripts')
<script>
function setTripType(type){
    document.querySelectorAll('.trip-tab').forEach(function(t){t.classList.remove('active');});
    document.querySelector('.trip-tab[data-type="'+type+'"]').classList.add('active');
    document.getElementById('trip_type_input').value=type;
    var w=document.getElementById('returnWrap');
    w.style.opacity=type==='one_way'?'0.4':'1';w.style.pointerEvents=type==='one_way'?'none':'auto';
}
function toggleFaq(btn){
    var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');
    document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});
    document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});
    if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}
}
document.addEventListener('DOMContentLoaded',function(){
    var tmr=new Date();tmr.setDate(tmr.getDate()+1);var nw=new Date();nw.setDate(nw.getDate()+8);
    function fmt(d){return d.toISOString().split('T')[0];}
    var d=document.getElementById('sf_depart'),r=document.getElementById('sf_return');
    if(d&&!d.value){d.value=fmt(tmr);d.min=fmt(tmr);}
    if(r&&!r.value){r.value=fmt(nw);r.min=fmt(nw);}
    initAirportAutocomplete();

    // Pre-fill from URL params
    var params=new URLSearchParams(window.location.search);
    if(params.get('to')){document.getElementById('sf_to').value=params.get('to');document.getElementById('sf_to_iata').value=params.get('to');}
});
function initAirportAutocomplete(){
    var cache={};
    document.querySelectorAll('.airport-autocomplete').forEach(function(input){
        var parent=input.closest('.relative').parentElement;
        var sug=parent.querySelector('.airport-suggestions');
        var hid=parent.querySelector('input[type="hidden"]');
        var to;
        input.addEventListener('input',function(){
            clearTimeout(to);var q=this.value.trim();
            if(q.length<2){sug.classList.add('hidden');return;}
            to=setTimeout(function(){
                if(cache[q]){renderSuggestions(cache[q],sug,input,hid);return;}
                fetch('/api/airports/search?q='+encodeURIComponent(q)).then(function(r){return r.json();}).then(function(d){cache[q]=d;renderSuggestions(d,sug,input,hid);}).catch(function(){sug.classList.add('hidden');});
            },200);
        });
        document.addEventListener('click',function(e){if(!input.contains(e.target)&&!sug.contains(e.target))sug.classList.add('hidden');});
    });
}
function renderSuggestions(data,container,input,hidden){
    if(!data||!data.length){container.classList.add('hidden');return;}
    container.innerHTML='';
    data.slice(0,6).forEach(function(item){
        var d=document.createElement('div');d.className='px-3 py-2 hover:bg-blue-50 cursor-pointer flex justify-between items-center border-b border-gray-50 text-xs';
        var name=item.name||item.city||'',city=item.city||'',country=item.country||'',code=item.iata_code||item.iataCode||item.code||'';
        d.innerHTML='<div><span class="font-medium text-gray-900">'+name+'</span><span class="text-gray-400 ml-1">'+city+(country?', '+country:'')+'</span></div><span class="font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded text-[10px]">'+code+'</span>';
        d.addEventListener('click',function(){input.value=name+' ('+code+')';hidden.value=code;container.classList.add('hidden');});
        container.appendChild(d);
    });
    container.classList.remove('hidden');
}
</script>
@endpush
