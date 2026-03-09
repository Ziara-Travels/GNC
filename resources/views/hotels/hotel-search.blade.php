@extends('layouts.main')

@section('content')
{{-- ===== HERO + SEARCH ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-25" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1 text-center">Find Your Perfect Hotel</h1>
        <p class="text-xs text-gray-300 text-center mb-8">Compare 2M+ hotels worldwide for the best rates.</p>

        <div class="bg-white rounded-xl shadow-xl overflow-hidden max-w-4xl mx-auto">
            <div class="p-4">
                <form action="{{ route('hotels.search') }}" method="POST" id="hotelSearchForm">
                    @csrf
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                        <div class="col-span-2 relative">
                            <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Destination</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                                <input type="text" name="destination" id="hs_dest" class="w-full pl-7 pr-2 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500 city-autocomplete" placeholder="City or region" autocomplete="off" required>
                                <input type="hidden" name="city_code" id="hs_city_code">
                            </div>
                            <div class="city-suggestions absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-xl mt-1 hidden max-h-48 overflow-y-auto"></div>
                        </div>
                        <div>
                            <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Check-in</label>
                            <input type="date" name="check_in_date" id="hs_checkin" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Check-out</label>
                            <input type="date" name="check_out_date" id="hs_checkout" class="w-full py-2 px-2.5 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3 mb-3">
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Rooms</label><select name="rooms" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=5;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Adults</label><select name="adults" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=1;$i<=9;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                        <div><label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Children</label><select name="children" class="w-full py-2 px-2 border border-gray-200 rounded-lg text-xs text-gray-800">@for($i=0;$i<=5;$i++)<option value="{{$i}}">{{$i}}</option>@endfor</select></div>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2"><i class="fas fa-search text-[10px]"></i> Search Hotels</button>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURED HOTELS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div><span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Top Picks</span><h2 class="text-lg font-bold text-gray-900 mt-0.5">Featured Hotels in East Africa</h2></div>
        </div>
        @php $hotels = [
            ['name'=>'Zanzibar Serena','city'=>'Zanzibar','stars'=>5,'price'=>185,'img'=>'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=260&fit=crop','rating'=>'9.2'],
            ['name'=>'Sarova Whitesands','city'=>'Mombasa','stars'=>4,'price'=>120,'img'=>'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&h=260&fit=crop','rating'=>'8.7'],
            ['name'=>'Kigali Marriott','city'=>'Kigali','stars'=>5,'price'=>160,'img'=>'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=400&h=260&fit=crop','rating'=>'9.0'],
            ['name'=>'Hyatt Regency','city'=>'Addis Ababa','stars'=>5,'price'=>140,'img'=>'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&h=260&fit=crop','rating'=>'8.9'],
        ]; @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($hotels as $h)
            <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="relative h-32 overflow-hidden">
                    <img src="{{ $h['img'] }}" alt="{{ $h['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <span class="absolute top-2 right-2 bg-white/90 text-[10px] font-bold text-gray-800 px-1.5 py-0.5 rounded"><i class="fas fa-star text-amber-400 text-[8px]"></i> {{ $h['rating'] }}</span>
                </div>
                <div class="p-2.5">
                    <div class="flex gap-0.5 mb-0.5">@for($s=0;$s<$h['stars'];$s++)<i class="fas fa-star text-amber-400 text-[7px]"></i>@endfor</div>
                    <h3 class="text-xs font-semibold text-gray-900 truncate">{{ $h['name'] }}</h3>
                    <p class="text-[10px] text-gray-400 mb-2"><i class="fas fa-map-marker-alt mr-0.5"></i>{{ $h['city'] }}</p>
                    <div class="flex items-end justify-between">
                        <div><span class="text-[10px] text-gray-400">From</span><div class="text-sm font-bold text-gray-900">${{ $h['price'] }}<span class="text-[10px] font-normal text-gray-400">/night</span></div></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row-reverse items-center gap-8 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop" alt="Hotel stay" class="w-full h-52 md:h-64 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-6">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Best Price Guarantee</span>
                <h2 class="text-xl font-bold text-gray-900 mt-1 mb-2">Book With Confidence</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-3">Free cancellation on most rooms. Secure payment via M-Pesa and card. Instant confirmation.</p>
                <ul class="space-y-1.5 mb-4">
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> Free cancellation on select rooms</li>
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> Price match guarantee</li>
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> 24/7 customer support</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ===== POPULAR DESTINATIONS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 mb-6">Popular Hotel Destinations</h2>
        @php $dests = [
            ['city'=>'Mombasa','code'=>'MBA','hotels'=>'320+','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=300&h=200&fit=crop'],
            ['city'=>'Zanzibar','code'=>'ZNZ','hotels'=>'250+','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=300&h=200&fit=crop'],
            ['city'=>'Nairobi','code'=>'NBO','hotels'=>'580+','img'=>'https://images.unsplash.com/photo-1611348524140-53c9a25263d6?w=300&h=200&fit=crop'],
            ['city'=>'Kampala','code'=>'EBB','hotels'=>'180+','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=300&h=200&fit=crop'],
            ['city'=>'Kigali','code'=>'KGL','hotels'=>'140+','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=300&h=200&fit=crop'],
            ['city'=>'Dar es Salaam','code'=>'DAR','hotels'=>'290+','img'=>'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=300&h=200&fit=crop'],
        ]; @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($dests as $d)
            <a href="{{ route('hotels.search.form') }}?dest={{ $d['code'] }}" class="relative h-32 rounded-xl overflow-hidden group block">
                <img src="{{ $d['img'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-2.5 left-2.5"><h3 class="text-xs font-bold text-white">{{ $d['city'] }}</h3><p class="text-[9px] text-white/70">{{ $d['hotels'] }} hotels</p></div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Hotel Booking FAQs</h2>
        @php $faqs = [
            ['q'=>'When will I be charged?','a'=>'Most hotels charge at check-in. Some special rate rooms require a deposit at the time of booking.'],
            ['q'=>'Can I cancel my reservation?','a'=>'Cancellation policies vary by hotel and rate type. Free cancellation is available on most flexible-rate rooms.'],
            ['q'=>'What is included in my room rate?','a'=>'Room rate typically includes taxes. Extra services like breakfast, parking, or Wi-Fi depend on the hotel and rate selected.'],
            ['q'=>'How do I get my booking confirmation?','a'=>'You will receive an instant email with your confirmation details right after payment.'],
            ['q'=>'Can I request early check-in or late check-out?','a'=>'You can add a special request during the booking process. The hotel will do their best to accommodate.'],
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

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Hotel Deal Alerts</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get notified when prices drop for your favourite destinations.</p>
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
document.addEventListener('DOMContentLoaded',function(){
    var tmr=new Date();tmr.setDate(tmr.getDate()+1);var nw=new Date();nw.setDate(nw.getDate()+3);
    function fmt(d){return d.toISOString().split('T')[0];}
    var ci=document.getElementById('hs_checkin'),co=document.getElementById('hs_checkout');
    if(ci&&!ci.value){ci.value=fmt(tmr);ci.min=fmt(tmr);}
    if(co&&!co.value){co.value=fmt(nw);co.min=fmt(nw);}
    initCityAutocomplete();
});
function initCityAutocomplete(){
    var cache={};
    document.querySelectorAll('.city-autocomplete').forEach(function(input){
        var parent=input.closest('.relative').parentElement;
        var sug=parent.querySelector('.city-suggestions');
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
    container.innerHTML='';var seen={};
    data.forEach(function(item){
        var city=item.city||item.name||'',code=item.iata_code||item.iataCode||item.code||'',country=item.country||'';
        if(!city||seen[code])return;seen[code]=true;
        var d=document.createElement('div');d.className='px-3 py-2 hover:bg-emerald-50 cursor-pointer flex justify-between items-center border-b border-gray-50 text-xs';
        d.innerHTML='<span class="font-medium text-gray-900">'+city+'<span class="text-gray-400 ml-1">'+country+'</span></span><span class="font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded text-[10px]">'+code+'</span>';
        d.addEventListener('click',function(){input.value=city;hidden.value=code;container.classList.add('hidden');});
        container.appendChild(d);
    });
    container.classList.remove('hidden');
}
</script>
@endpush
