@extends('layouts.main')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">About <span class="text-amber-400">Ziara Travels</span></h1>
        <p class="text-xs text-gray-300">Your Trusted Partner in Air Travel Excellence</p>
        <div class="inline-flex items-center gap-2 mt-3 bg-white/10 backdrop-blur-sm text-white text-[10px] px-3 py-1.5 rounded-full border border-white/20">
            <i class="fas fa-plane text-amber-400 text-[9px]"></i> Connecting Dreams Since 2025
        </div>
    </div>
</section>

{{-- ===== OUR STORY ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-8">
            <div class="md:w-1/2">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Our Story</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Revolutionizing Air Travel</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-2">Founded in 2025, Ziara Travels has been at the forefront of revolutionizing air travel booking. We believe in making air travel accessible, convenient, and enjoyable for everyone.</p>
                <p class="text-xs text-gray-500 leading-relaxed mb-3">Our platform combines cutting-edge technology with personalized service to deliver the best flight booking experience. From solo travelers to large groups, we've got you covered.</p>
                <ul class="space-y-1">
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> 24/7 Customer Support</li>
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> Best Price Guarantee</li>
                    <li class="flex items-center gap-2 text-xs text-gray-600"><i class="fas fa-check-circle text-emerald-500 text-[10px]"></i> Secure Booking</li>
                </ul>
            </div>
            <div class="md:w-1/2">
                <img src="{{ asset('images/airplane.jpg') }}" alt="Our Story" class="w-full h-48 md:h-56 object-cover rounded-xl shadow-lg" onerror="this.src='https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=600&h=400&fit=crop'">
            </div>
        </div>
    </div>
</section>

{{-- ===== VALUES ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Our Values</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">What Drives Us Forward</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-3">
            @php $values = [
                ['icon'=>'fa-users','title'=>'Customer First','desc'=>'Your satisfaction is our top priority. We\'re here 24/7 with dedicated support and personalized assistance.','color'=>'amber'],
                ['icon'=>'fa-shield-alt','title'=>'Trust & Security','desc'=>'Your data and transactions are protected by industry-leading security measures and encryption.','color'=>'blue'],
                ['icon'=>'fa-bolt','title'=>'Innovation','desc'=>'We continuously improve our services with cutting-edge technology for the best booking experience.','color'=>'emerald'],
            ]; @endphp
            @foreach($values as $v)
            <div class="bg-white rounded-xl border border-gray-100 p-4 hover:shadow-md transition-shadow">
                <div class="w-8 h-8 bg-{{ $v['color'] }}-100 rounded-lg flex items-center justify-center mb-2"><i class="fas {{ $v['icon'] }} text-{{ $v['color'] }}-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $v['title'] }}</h3>
                <p class="text-[11px] text-gray-500 leading-relaxed">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== STATS ===== --}}
<section class="py-10 bg-slate-900">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-base font-bold text-white text-center mb-6">Trusted by Thousands</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php $stats = [['n'=>'10K+','l'=>'Flights Booked','icon'=>'fa-plane'],['n'=>'5K+','l'=>'Happy Customers','icon'=>'fa-smile'],['n'=>'100+','l'=>'Destinations','icon'=>'fa-globe'],['n'=>'24/7','l'=>'Support','icon'=>'fa-headset']]; @endphp
            @foreach($stats as $s)
            <div class="text-center">
                <div class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas {{ $s['icon'] }} text-amber-400 text-xs"></i></div>
                <div class="text-xl font-bold text-white">{{ $s['n'] }}</div>
                <div class="text-[10px] text-gray-400">{{ $s['l'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== TEAM ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Our Team</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Meet the Visionaries</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-4">
            @php $team = [
                ['name'=>'Sarah Johnson','role'=>'CEO & Founder','desc'=>'15+ years in aviation — passionate about making travel accessible to everyone.','color'=>'amber'],
                ['name'=>'Michael Chen','role'=>'CTO','desc'=>'Technology expert driving innovation in travel booking with cutting-edge solutions.','color'=>'blue'],
                ['name'=>'Emily Rodriguez','role'=>'Head of Customer Experience','desc'=>'Ensuring every traveler has the best possible journey from booking to landing.','color'=>'emerald'],
            ]; @endphp
            @foreach($team as $t)
            <div class="text-center bg-gray-50 rounded-xl p-4">
                <div class="w-14 h-14 bg-{{ $t['color'] }}-100 rounded-full mx-auto flex items-center justify-center mb-2"><i class="fas fa-user text-{{ $t['color'] }}-600 text-lg"></i></div>
                <h3 class="text-xs font-bold text-gray-900">{{ $t['name'] }}</h3>
                <p class="text-[10px] font-medium text-{{ $t['color'] }}-600 mb-1">{{ $t['role'] }}</p>
                <p class="text-[10px] text-gray-500">{{ $t['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row-reverse items-center gap-6 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="Book today" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Ready to Travel?</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Start Your Journey Today</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Book your next flight with confidence. Best prices, instant confirmation, and 24/7 support.</p>
                <div class="flex gap-2">
                    <a href="{{ url('/flights/search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors">Book a Flight</a>
                    <a href="{{ url('/contactus') }}" class="border border-gray-200 text-gray-700 text-xs font-semibold px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Frequently Asked Questions</h2>
        @php $faqs = [
            ['q'=>'What is Ziara Travels?','a'=>'Ziara Travels is a modern travel booking platform specializing in flights, hotels, and tour packages across East Africa and beyond.'],
            ['q'=>'How do I contact support?','a'=>'Our support team is available 24/7 via live chat, email at support@ziaratravels.com, or phone at +254 700 123 456.'],
            ['q'=>'Is my booking data secure?','a'=>'Absolutely. We use industry-standard encryption and security protocols to protect all your personal and payment data.'],
            ['q'=>'Do you offer corporate travel services?','a'=>'Yes, we offer tailored corporate packages with volume discounts, dedicated account managers, and consolidated billing.'],
            ['q'=>'What payment methods do you accept?','a'=>'We accept M-Pesa, M-Pesa STK Push, bank transfer, Equity Eazzy, and all major credit/debit cards.'],
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
        <h2 class="text-base font-bold text-white mb-1">Stay Updated</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get travel tips, exclusive deals, and company news straight to your inbox.</p>
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
