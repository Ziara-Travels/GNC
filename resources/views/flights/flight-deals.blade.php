@extends('layouts.main')

@section('title', 'Flight Deals & Offers - Ziara Travel')

@section('content')
<style>
.ziara-flight-deals .ziara-archive__hero { --zt-maroon:#7b2a1b;--zt-maroon-deep:#6b1d15;--zt-gold:#d9a561;--zt-gold-bright:#f3d24a; }
.ziara-flight-deals .ziara-deals-offers > div {
    background:#fff;border:1px solid var(--gold-border);border-radius:16px;overflow:hidden;
    display:flex;flex-direction:column;box-shadow:var(--shadow-sm);
    transition:transform .35s var(--ease-out),box-shadow .35s var(--ease-out);
}
.ziara-flight-deals .ziara-deals-offers > div:hover { transform:translateY(-5px);box-shadow:var(--shadow-md); }
.ziara-flight-deals .ziara-deals-offers #deals-loader { background:transparent;border:0;box-shadow:none; }
.ziara-flight-deals .ziara-deals-offers h3 { font-family:'Cormorant Garamond',Georgia,serif;font-size:20px !important;font-weight:700 !important;color:var(--maroon) !important; }
.ziara-flight-deals .ziara-deals-offers .deal-book-btn { background:#7b2a1b !important; }
.ziara-flight-deals .ziara-deals-offers .deal-book-btn:hover { background:#6b1d15 !important; }
.ziara-flight-deals .ziara-deals-offers [class*="bg-red-500"] { background:#7b2a1b !important; }
.ziara-flight-deals .ziara-deals-offers [class*="bg-white/90"] { color:#7b2a1b !important; }
.ziara-flight-deals .ziara-deals-offers [class*="text-gray-900"] { color:var(--ink) !important; }
.ziara-flight-deals .ziara-faqbox__item { border:1px solid var(--gold-border);border-radius:14px;background:#fff;overflow:hidden; }
.ziara-flight-deals .ziara-faqbox__item .faq-toggle:hover { background:var(--gold-pale); }
.ziara-flight-deals .ziara-faqbox__item .faq-toggle span { color:var(--maroon);font-family:'Cormorant Garamond',Georgia,serif;font-size:19px;font-weight:600; }
.ziara-flight-deals .ziara-faqbox__item .faq-toggle i { color:var(--gold); }
.ziara-flight-deals .ziara-faqbox__item .faq-content p { margin:0;color:var(--muted);font-size:14.5px;line-height:1.75; }
</style>

@include('partials.ziara-page-hero', [
    'kicker' => 'East African Destinations',
    'title'  => 'Flight Deals <em>&amp; Offers</em>',
    'intro'  => 'Best prices on flights across East Africa.',
    'crumbs' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Flights', 'url' => route('flights.public.search')],
        ['label' => 'Deals', 'url' => route('flights.public.search')],
    ],
])

<section class="zt-section zt-section--white ziara-flight-deals" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Live Fares',
            'title'  => 'Best <em>Deals</em> Available',
            'desc'   => 'Best prices on flights across East Africa &bull; <span id="flight-offer-count" style="font-weight:700;color:var(--maroon);">0</span> deals available',
            'align'  => 'center',
        ])
        <div id="flight-offers-container" class="ziara-deals ziara-deals-offers">
            <div class="col-span-full text-center py-12" id="deals-loader">
                <div class="inline-block w-6 h-6 border-2 rounded-full animate-spin" style="border-color:#7b2a1b;border-top-color:transparent;"></div>
                <p class="text-xs mt-2" style="color:var(--muted);">Searching for the best deals&hellip;</p>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale ziara-flight-deals" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        <div class="ziara-split">
            <div><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="Travel" loading="lazy" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);"></div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Save More',
                    'title' => 'Flight + Hotel <em>Packages</em>',
                    'align' => 'left',
                    'desc'  => 'Bundle your flight with a hotel and save up to 30%. Instant confirmation, flexible cancellation.',
                ])
                <a href="{{ url('/') }}" class="ziara-btn ziara-btn--gold">See Packages</a>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white ziara-flight-deals" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Trending',
            'title' => 'Popular Routes from <em>Nairobi</em>',
            'align' => 'center',
        ])
        @php $routes = [
            ['to'=>'Mombasa','code'=>'MBA','time'=>'1h 05m','from'=>'KES 8,500','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=300&h=180&fit=crop'],
            ['to'=>'Zanzibar','code'=>'ZNZ','time'=>'1h 25m','from'=>'KES 14,500','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=300&h=180&fit=crop'],
            ['to'=>'Entebbe','code'=>'EBB','time'=>'1h 15m','from'=>'KES 12,000','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=300&h=180&fit=crop'],
            ['to'=>'Dar es Salaam','code'=>'DAR','time'=>'1h 30m','from'=>'KES 13,000','img'=>'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?w=300&h=180&fit=crop'],
            ['to'=>'Kigali','code'=>'KGL','time'=>'1h 20m','from'=>'KES 15,800','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=300&h=180&fit=crop'],
            ['to'=>'Kilimanjaro','code'=>'JRO','time'=>'1h 10m','from'=>'KES 11,500','img'=>'https://images.unsplash.com/photo-1609198092458-38a293c7ac4b?w=300&h=180&fit=crop'],
        ]; @endphp
        <div class="ziara-deals">
            @foreach($routes as $r)
                <article class="ziara-deal">
                    <div class="ziara-deal__media">
                        <img src="{{ $r['img'] }}" alt="{{ $r['to'] }}" loading="lazy">
                        <span class="ziara-deal__badge">NBO &rarr; {{ $r['to'] }}</span>
                    </div>
                    <div class="ziara-deal__body">
                        <h3 class="ziara-deal__title">{{ $r['to'] }} <span style="font-size:14px;color:var(--muted);font-family:'DM Sans',sans-serif;">{{ $r['code'] }}</span></h3>
                        <p class="ziara-deal__desc"><i class="fas fa-clock" aria-hidden="true"></i> {{ $r['time'] }}</p>
                        <div class="ziara-deal__price">
                            <strong style="color:var(--maroon);">{{ $r['from'] }}</strong>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale ziara-flight-deals" style="--maroon:#7b2a1b;--maroon-deep:#6b1d15;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Need Help',
            'title' => 'Flight Deals <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How are deal prices determined?','a'=>'Prices are sourced from Amadeus in real-time and reflect the lowest available fare for each route at the time of search.'],
            ['q'=>'Can I book a deal directly?','a'=>'Yes! Click "Book Now" on any deal card and you will be taken to the booking flow where you can complete your purchase.'],
            ['q'=>'Are these prices one-way or return?','a'=>'Deals shown are typically one-way fares. Return flights can be searched separately or bundled at checkout.'],
            ['q'=>'How often are deals updated?','a'=>'Deals are refreshed every time you load this page. Prices are subject to availability and may change.'],
            ['q'=>'Can I get a refund if the price drops?','a'=>'Refund and change policies depend on the fare rules of each ticket. Flexible fares usually allow free changes.'],
        ]; @endphp
        <div class="ziara-flight-deals__faqs">
            @foreach($faqs as $faq)
                <div class="ziara-faqbox__item">
                    <button class="faq-toggle w-full flex items-center justify-between p-4 text-left transition-colors" onclick="toggleFaq(this)">
                        <span class="pr-3">{{ $faq['q'] }}</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform duration-200 shrink-0"></i>
                    </button>
                    <div class="faq-content hidden px-4 pb-4"><p>{{ $faq['a'] }}</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section ziara-cta-band ziara-flight-deals" style="--maroon:#6b1d15;--maroon-deep:#4d150f;--gold:#d9a561;--gold-bright:#f3d24a;">
    <div class="container" style="max-width:620px;">
        <div class="gsc-heading gsc-heading--light align-center style-1 widget auto-responsive" style="margin-bottom:26px;">
            <div class="content-inner">
                <div class="sub-title"><span>Newsletter</span></div>
                <h2 class="title"><span>Deal Alerts</span></h2>
                <div class="title-desc" style="margin-left:auto;margin-right:auto;max-width:520px;">Get notified when new flight deals are available.</div>
            </div>
        </div>
        <form class="flex gap-2" style="max-width:520px;margin:0 auto;" onsubmit="event.preventDefault(); alert('Subscribed!'); this.reset();">
            <input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required>
            <button type="submit" class="ziara-btn ziara-btn--gold text-xs whitespace-nowrap">Subscribe</button>
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
