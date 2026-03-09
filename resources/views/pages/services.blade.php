@extends('layouts.main')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Our <span class="text-amber-400">Services</span></h1>
        <p class="text-xs text-gray-300">Comprehensive travel solutions for every journey</p>
    </div>
</section>

{{-- ===== MAIN SERVICES ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">What We Offer</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Everything You Need for Travel</h2>
        </div>
        @php $services = [
            ['icon'=>'fa-plane','title'=>'Flight Booking','desc'=>'Real-time search across 500+ airlines with competitive prices and instant confirmation.','bullets'=>['Real-time pricing','Flexible dates','Instant e-ticket'],'link'=>url('/flights/search'),'color'=>'amber'],
            ['icon'=>'fa-hotel','title'=>'Hotel Booking','desc'=>'Find 2M+ accommodations worldwide with verified reviews and best rate guarantee.','bullets'=>['Price match guarantee','Free cancellation','Verified reviews'],'link'=>url('/hotels/search'),'color'=>'blue'],
            ['icon'=>'fa-map-marked-alt','title'=>'Tour Packages','desc'=>'Curated safari and adventure tours across East Africa's top destinations.','bullets'=>['Expert local guides','All-inclusive options','Group discounts'],'link'=>url('/tours'),'color'=>'emerald'],
            ['icon'=>'fa-suitcase-rolling','title'=>'Package Deals','desc'=>'Save up to 30% with flight + hotel bundles, custom-built for your trip.','bullets'=>['Bundle & save','Flexible booking','One-click checkout'],'link'=>url('/'),'color'=>'purple'],
            ['icon'=>'fa-building','title'=>'Corporate Travel','desc'=>'Tailored solutions for businesses with volume discounts and dedicated account managers.','bullets'=>['Consolidated billing','Policy compliance','Priority support'],'link'=>url('/contactus'),'color'=>'indigo'],
            ['icon'=>'fa-shield-alt','title'=>'Travel Insurance','desc'=>'Comprehensive coverage for trip cancellations, medical emergencies, and lost baggage.','bullets'=>['Trip protection','Medical cover','24/7 claims support'],'link'=>url('/travel-insurance'),'color'=>'rose'],
        ]; @endphp
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($services as $s)
            <div class="bg-gray-50 rounded-xl p-4 hover:shadow-md transition-shadow border border-gray-100 group">
                <div class="w-8 h-8 bg-{{ $s['color'] }}-100 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition-transform"><i class="fas {{ $s['icon'] }} text-{{ $s['color'] }}-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $s['title'] }}</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed mb-2">{{ $s['desc'] }}</p>
                <ul class="space-y-0.5 mb-2">@foreach($s['bullets'] as $b)<li class="flex items-center gap-1.5 text-[10px] text-gray-600"><i class="fas fa-check text-emerald-500 text-[8px]"></i>{{ $b }}</li>@endforeach</ul>
                <a href="{{ $s['link'] }}" class="text-[10px] font-semibold text-{{ $s['color'] }}-600 hover:underline">Learn More →</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== ADDITIONAL SERVICES ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Additional Services</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php $extras = [
                ['icon'=>'fa-exchange-alt','title'=>'Currency Exchange','desc'=>'Competitive forex rates for your travels'],
                ['icon'=>'fa-passport','title'=>'Visa Assistance','desc'=>'Hassle-free visa application support'],
                ['icon'=>'fa-car','title'=>'Airport Transfers','desc'=>'Reliable pickup & drop-off services'],
                ['icon'=>'fa-headset','title'=>'24/7 Support','desc'=>'Round-the-clock customer assistance'],
            ]; @endphp
            @foreach($extras as $e)
            <div class="bg-white rounded-xl p-3 text-center border border-gray-100 hover:shadow-sm transition-shadow">
                <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center mx-auto mb-2"><i class="fas {{ $e['icon'] }} text-emerald-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $e['title'] }}</h3>
                <p class="text-[10px] text-gray-400">{{ $e['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop" alt="Travel" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Get Started</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Ready to Plan Your Trip?</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Whether it's a quick domestic flight or an international adventure, we have the tools and expertise to make it seamless.</p>
                <div class="flex gap-2">
                    <a href="{{ url('/flights/search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Book a Flight</a>
                    <a href="{{ url('/contactus') }}" class="border border-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-white transition-colors">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== WHY CHOOSE US ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Why Choose Ziara Travels?</h2>
        <div class="grid md:grid-cols-3 gap-3">
            @php $why = [
                ['icon'=>'fa-tag','title'=>'Best Prices','desc'=>'We negotiate directly with airlines and hotels to guarantee the best rates.'],
                ['icon'=>'fa-lock','title'=>'Secure Payments','desc'=>'M-Pesa, STK Push, bank transfer, and cards — all protected with end-to-end encryption.'],
                ['icon'=>'fa-heart','title'=>'Dedicated Support','desc'=>'Our team of travel experts is available 24/7 to help you at every step of your journey.'],
            ]; @endphp
            @foreach($why as $w)
            <div class="bg-white rounded-xl p-4 border border-gray-100 text-center">
                <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center mx-auto mb-2"><i class="fas {{ $w['icon'] }} text-amber-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $w['title'] }}</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">{{ $w['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Services FAQs</h2>
        @php $faqs = [
            ['q'=>'What destinations do you cover?','a'=>'We offer flights to 190+ countries and hotels in major cities worldwide, with a focus on East African destinations.'],
            ['q'=>'Do you offer group booking discounts?','a'=>'Yes! Groups of 10+ travelers receive volume discounts. Contact our corporate team for a custom quote.'],
            ['q'=>'What payment methods do you support?','a'=>'We accept M-Pesa, M-Pesa STK Push, Equity Eazzy, bank transfers, and all major credit/debit cards.'],
            ['q'=>'Can I cancel and get a refund?','a'=>'Cancellation and refund policies depend on the specific booking type and fare rules. Flexible rates generally allow free cancellation.'],
            ['q'=>'Do you provide travel insurance?','a'=>'Yes, we offer comprehensive travel insurance covering trip cancellations, medical emergencies, lost luggage, and more.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-100 transition-colors" onclick="toggleFaq(this)">
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
        <h2 class="text-base font-bold text-white mb-1">Stay Informed</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get updates on new services, exclusive deals, and travel tips.</p>
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
</script>
@endpush
