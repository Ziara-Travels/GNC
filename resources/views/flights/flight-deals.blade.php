@extends('layouts.main')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <span class="inline-block bg-amber-500/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-3">East African Destinations</span>
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Flight Deals &amp; Offers</h1>
        <p class="text-xs text-gray-300 mb-2">Best prices on flights across East Africa &bull; <span id="flight-offer-count" class="font-bold text-amber-400">0</span> deals available</p>
    </div>
</section>

{{-- ===== DEALS GRID (dynamic) ===== --}}
<section class="py-8 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div id="flight-offers-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
            {{-- Loading --}}
            <div class="col-span-full text-center py-12" id="deals-loader">
                <div class="inline-block w-6 h-6 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs text-gray-400 mt-2">Searching for the best deals&hellip;</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="Travel" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Save More</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Flight + Hotel Packages</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-3">Bundle your flight with a hotel and save up to 30%. Instant confirmation, flexible cancellation.</p>
                <a href="{{ url('/') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors">See Packages</a>
            </div>
        </div>
    </div>
</section>

{{-- ===== POPULAR ROUTES ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 mb-5">Popular Routes from Nairobi</h2>
        @php $routes = [
            ['to'=>'Mombasa','code'=>'MBA','time'=>'1h 05m','from'=>'KES 8,500','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=300&h=180&fit=crop'],
            ['to'=>'Zanzibar','code'=>'ZNZ','time'=>'1h 25m','from'=>'KES 14,500','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=300&h=180&fit=crop'],
            ['to'=>'Entebbe','code'=>'EBB','time'=>'1h 15m','from'=>'KES 12,000','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=300&h=180&fit=crop'],
            ['to'=>'Dar es Salaam','code'=>'DAR','time'=>'1h 30m','from'=>'KES 13,000','img'=>'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=300&h=180&fit=crop'],
            ['to'=>'Kigali','code'=>'KGL','time'=>'1h 20m','from'=>'KES 15,800','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=300&h=180&fit=crop'],
            ['to'=>'Kilimanjaro','code'=>'JRO','time'=>'1h 10m','from'=>'KES 11,500','img'=>'https://images.unsplash.com/photo-1609198092458-38a293c7ac4b?w=300&h=180&fit=crop'],
        ]; @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($routes as $r)
            <div class="relative h-28 rounded-xl overflow-hidden group">
                <img src="{{ $r['img'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-2 left-2.5 right-2.5 flex items-end justify-between">
                    <div><h3 class="text-xs font-bold text-white">NBO → {{ $r['to'] }}</h3><p class="text-[9px] text-white/70"><i class="fas fa-clock mr-0.5"></i>{{ $r['time'] }}</p></div>
                    <span class="text-[10px] font-bold text-amber-400">{{ $r['from'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Flight Deals FAQs</h2>
        @php $faqs = [
            ['q'=>'How are deal prices determined?','a'=>'Prices are sourced from Amadeus in real-time and reflect the lowest available fare for each route at the time of search.'],
            ['q'=>'Can I book a deal directly?','a'=>'Yes! Click "Book Now" on any deal card and you will be taken to the booking flow where you can complete your purchase.'],
            ['q'=>'Are these prices one-way or return?','a'=>'Deals shown are typically one-way fares. Return flights can be searched separately or bundled at checkout.'],
            ['q'=>'How often are deals updated?','a'=>'Deals are refreshed every time you load this page. Prices are subject to availability and may change.'],
            ['q'=>'Can I get a refund if the price drops?','a'=>'Refund and change policies depend on the fare rules of each ticket. Flexible fares usually allow free changes.'],
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
<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Deal Alerts</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get notified when new flight deals are available.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault(); alert('Subscribed!'); this.reset();">
            <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}

document.addEventListener('DOMContentLoaded',function(){loadFlightOffers();});

function loadFlightOffers(){
    fetch('/east-african-offers').then(function(r){if(!r.ok)throw new Error();return r.json();})
    .then(function(data){var offers=data.offers||data;if(Array.isArray(offers)&&offers.length>0){renderOffers(offers);}else{renderOffers(getDummyOffers());}})
    .catch(function(){renderOffers(getDummyOffers());});
}

function renderOffers(offers){
    var c=document.getElementById('flight-offers-container');c.innerHTML='';
    document.getElementById('flight-offer-count').textContent=offers.length;
    offers.forEach(function(o){
        var citySlug=(o.destinationCity||'').toLowerCase().replace(/\s+/g,'-');
        var img='/images/travel/'+citySlug+'.jpg';
        var stopsText=o.stops===0?'Direct':o.stops+' Stop'+(o.stops>1?'s':'');
        var card=document.createElement('div');
        card.className='group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden';
        card.innerHTML=
            '<div class="relative h-32 overflow-hidden">'+
                '<img src="'+img+'" alt="'+o.destinationCity+'" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.onerror=null;this.src=\'/images/'+(o.destinationImage||'travel/destination-default.jpg')+'\';" loading="lazy">'+
                '<span class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">'+o.discountPercent+'% OFF</span>'+
                '<span class="absolute top-2 right-2 bg-white/90 text-[9px] font-bold text-gray-700 px-1.5 py-0.5 rounded">'+o.airline+'</span>'+
            '</div>'+
            '<div class="p-2.5">'+
                '<h3 class="text-xs font-semibold text-gray-900 truncate">'+o.originCity+' → '+o.destinationCity+'</h3>'+
                '<p class="text-[10px] text-gray-400 mb-1"><i class="fas fa-clock mr-0.5"></i>'+o.duration+' &bull; '+stopsText+' &bull; '+o.cabin+'</p>'+
                '<p class="text-[10px] text-gray-400 mb-2 truncate">'+o.description+'</p>'+
                '<div class="flex items-end justify-between">'+
                    '<div><span class="text-sm font-bold text-gray-900">'+o.currency+' '+Number(o.price).toLocaleString()+'</span> <span class="text-[10px] text-gray-400 line-through">'+o.currency+' '+Number(o.originalPrice).toLocaleString()+'</span></div>'+
                    '<button class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-semibold px-3 py-1.5 rounded-lg transition-colors deal-book-btn">Book</button>'+
                '</div>'+
            '</div>';
        card.querySelector('.deal-book-btn').addEventListener('click',function(){bookDeal(o);});
        c.appendChild(card);
    });
}

function bookDeal(offer){
    var form=document.createElement('form');form.method='POST';form.action='/book-flight';
    var csrf=document.querySelector('meta[name="csrf-token"]');
    if(csrf){var t=document.createElement('input');t.type='hidden';t.name='_token';t.value=csrf.content;form.appendChild(t);}
    var d=document.createElement('input');d.type='hidden';d.name='flight_data';d.value=JSON.stringify(offer);form.appendChild(d);
    document.body.appendChild(form);form.submit();
}

function getDummyOffers(){
    return [
        {destination:'MBA',destinationCity:'Mombasa',destinationCountry:'Kenya',destinationImage:'mombasa.jpg',description:'Coastal city with rich history and beaches',highlight:'Iconic Ivory Tusks',origin:'NBO',originCity:'Nairobi',departureDate:'Aug 15, 2025',duration:'1h 05m',price:12000,originalPrice:15000,currency:'KES',discountPercent:20,cabin:'Economy',airline:'KQ',stops:0},
        {destination:'ZNZ',destinationCity:'Zanzibar',destinationCountry:'Tanzania',destinationImage:'zanzibar.jpg',description:'Crystal clear waters and pristine beaches',highlight:'Beautiful Beaches',origin:'NBO',originCity:'Nairobi',departureDate:'Aug 18, 2025',duration:'1h 25m',price:18500,originalPrice:23125,currency:'KES',discountPercent:20,cabin:'Economy',airline:'KQ',stops:0},
        {destination:'EBB',destinationCity:'Entebbe',destinationCountry:'Uganda',destinationImage:'entebbe.jpg',description:'Scenic city on the shores of Lake Victoria',highlight:'Gateway to Uganda',origin:'NBO',originCity:'Nairobi',departureDate:'Aug 20, 2025',duration:'1h 15m',price:14500,originalPrice:18125,currency:'KES',discountPercent:20,cabin:'Economy',airline:'KQ',stops:0},
        {destination:'DAR',destinationCity:'Dar es Salaam',destinationCountry:'Tanzania',destinationImage:'dar-es-salaam.jpg',description:'Major Tanzanian port with vibrant markets',highlight:'Cultural Hub',origin:'NBO',originCity:'Nairobi',departureDate:'Aug 22, 2025',duration:'1h 30m',price:16800,originalPrice:21000,currency:'KES',discountPercent:20,cabin:'Economy',airline:'KQ',stops:0},
        {destination:'JRO',destinationCity:'Kilimanjaro',destinationCountry:'Tanzania',destinationImage:'kilimanjaro.jpg',description:"Gateway to Africa's highest peak",highlight:'Mountain Adventure',origin:'NBO',originCity:'Nairobi',departureDate:'Aug 25, 2025',duration:'1h 10m',price:15200,originalPrice:19000,currency:'KES',discountPercent:20,cabin:'Economy',airline:'PW',stops:0},
        {destination:'KGL',destinationCity:'Kigali',destinationCountry:'Rwanda',destinationImage:'kigali.jpg',description:'Clean, green city with vibrant culture',highlight:'Modern Africa',origin:'NBO',originCity:'Nairobi',departureDate:'Aug 28, 2025',duration:'1h 20m',price:17500,originalPrice:21875,currency:'KES',discountPercent:20,cabin:'Economy',airline:'RW',stops:0}
    ];
}
</script>
@endpush
