@extends('layouts.main')
@section('title', 'What We Do - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">What We <span class="text-amber-400">Do</span></h1>
        <p class="text-xs text-gray-300">End-to-end travel solutions — from searching to boarding</p>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">How It Works</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Book in 4 Simple Steps</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php $steps = [
                ['n'=>'1','icon'=>'fa-search','title'=>'Search','desc'=>'Enter your destination and dates to find flights and hotels.'],
                ['n'=>'2','icon'=>'fa-list','title'=>'Compare','desc'=>'Browse results, compare prices, and pick your ideal option.'],
                ['n'=>'3','icon'=>'fa-credit-card','title'=>'Book & Pay','desc'=>'Secure checkout with M-Pesa, card, or bank transfer.'],
                ['n'=>'4','icon'=>'fa-ticket-alt','title'=>'Travel','desc'=>'Receive your e-ticket instantly and you\'re ready to go!'],
            ]; @endphp
            @foreach($steps as $s)
            <div class="text-center bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center mx-auto mb-2 text-xs font-bold">{{ $s['n'] }}</div>
                <i class="fas {{ $s['icon'] }} text-emerald-600 text-sm mb-1"></i>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $s['title'] }}</h3>
                <p class="text-[10px] text-gray-500">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CORE SERVICES ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Our Core Services</h2>
        @php $svc = [
            ['icon'=>'fa-plane','title'=>'Flight Booking','desc'=>'Real-time search across 500+ airlines. One-way, return, and multi-city flights with instant e-tickets.','link'=>url('/flights/search'),'img'=>'https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=400&h=250&fit=crop'],
            ['icon'=>'fa-hotel','title'=>'Hotel Reservations','desc'=>'Compare 2M+ hotels worldwide. Best rate guarantee, free cancellation, and verified guest reviews.','link'=>url('/hotels/search'),'img'=>'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&h=250&fit=crop'],
            ['icon'=>'fa-map-marked-alt','title'=>'Safari & Tour Packages','desc'=>'Curated East African tours — from Masai Mara to Zanzibar, with expert local guides.','link'=>url('/tours'),'img'=>'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=250&fit=crop'],
        ]; @endphp
        <div class="grid md:grid-cols-3 gap-3">
            @foreach($svc as $s)
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="h-32 overflow-hidden"><img src="{{ $s['img'] }}" alt="{{ $s['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy"></div>
                <div class="p-3">
                    <h3 class="text-xs font-bold text-gray-900 mb-1"><i class="fas {{ $s['icon'] }} text-emerald-600 mr-1 text-[10px]"></i>{{ $s['title'] }}</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed mb-2">{{ $s['desc'] }}</p>
                    <a href="{{ $s['link'] }}" class="text-[10px] font-semibold text-emerald-600 hover:underline">Explore →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row-reverse items-center gap-6 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Get Started</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Plan Your Next Adventure</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Whether it's a business trip or a holiday getaway, we have the tools and support to make it seamless.</p>
                <div class="flex gap-2">
                    <a href="{{ url('/flights/search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Search Flights</a>
                    <a href="{{ url('/contactus') }}" class="border border-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-white transition-colors">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Frequently Asked Questions</h2>
        @php $faqs = [
            ['q'=>'How do I search for flights?','a'=>'Use the search form on our homepage or flights page. Enter your origin, destination, dates, and passenger count to see available flights.'],
            ['q'=>'Can I book a flight and hotel together?','a'=>'Yes! Use the Packages tab on the homepage to search for bundled deals and save up to 30%.'],
            ['q'=>'What payment methods are accepted?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equity Eazzy, bank transfers, and all major credit/debit cards.'],
            ['q'=>'How do I get my e-ticket?','a'=>'After successful payment, your e-ticket is generated instantly and sent to your registered email. You can also download it from the booking confirmation page.'],
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
        <h2 class="text-base font-bold text-white mb-1">Stay Connected</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get travel tips and exclusive deals in your inbox.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
