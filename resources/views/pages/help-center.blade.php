@extends('layouts.main')
@section('title', 'Help Center - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Help <span class="text-amber-400">Center</span></h1>
        <p class="text-xs text-gray-300 mb-4">Find answers, guides, and support resources</p>
        <div class="max-w-md mx-auto relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
            <input type="text" id="helpSearch" placeholder="Search help topics..." class="w-full pl-8 pr-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
        </div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
<section class="py-8 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php $cats = [
                ['icon'=>'fa-plane','title'=>'Flights','desc'=>'Booking, changes, cancellations','link'=>url('/faq').'#flights'],
                ['icon'=>'fa-hotel','title'=>'Hotels','desc'=>'Reservations, check-in, amenities','link'=>url('/faq').'#hotels'],
                ['icon'=>'fa-credit-card','title'=>'Payments','desc'=>'Methods, refunds, invoices','link'=>url('/faq').'#payments'],
                ['icon'=>'fa-user-circle','title'=>'Account','desc'=>'Registration, profile, points','link'=>url('/faq').'#account'],
            ]; @endphp
            @foreach($cats as $c)
            <a href="{{ $c['link'] }}" class="bg-gray-50 rounded-xl p-3 text-center hover:shadow-md transition-shadow border border-gray-100 block">
                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-2"><i class="fas {{ $c['icon'] }} text-emerald-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $c['title'] }}</h3>
                <p class="text-[10px] text-gray-400">{{ $c['desc'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== POPULAR ARTICLES ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Popular Help Articles</h2>
        @php $articles = [
            ['q'=>'How do I download my e-ticket?','a'=>'After booking, go to your booking confirmation page and click "Download PDF". The e-ticket is also emailed to you automatically.'],
            ['q'=>'How do I change my flight dates?','a'=>'Contact support with your booking reference. If you have a flexible fare, changes are free. Non-flexible fares may incur a change fee.'],
            ['q'=>'My payment failed — what should I do?','a'=>'Check your balance/limit and try again. If using M-Pesa, ensure you have sufficient funds and enter the correct PIN. Contact support if the issue persists.'],
            ['q'=>'How do I request a refund?','a'=>'Submit a refund request via the Contact Us page with your booking reference. Refunds are processed within 5-7 business days.'],
            ['q'=>'Can I add extra baggage to my booking?','a'=>'Extra baggage can sometimes be added through the airline\'s website after booking. Contact support for assistance.'],
            ['q'=>'How do I earn and redeem loyalty points?','a'=>'Points are earned automatically on every booking (1 KES = 1 point). Redeem them for discounts on your next booking through your account dashboard.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($articles as $a)
            <div class="help-article border border-gray-200 rounded-lg bg-white overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)">
                    <span class="text-xs font-semibold text-gray-900 pr-3 help-q">{{ $a['q'] }}</span>
                    <i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i>
                </button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $a['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CONTACT OPTIONS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-lg font-bold text-gray-900 mb-2">Still Need Help?</h2>
        <p class="text-xs text-gray-500 mb-4">Our team is ready to assist you 24/7.</p>
        <div class="grid grid-cols-3 gap-3 max-w-lg mx-auto">
            @php $opts = [
                ['icon'=>'fa-comments','title'=>'Live Chat','href'=>url('/live-chat'),'color'=>'emerald'],
                ['icon'=>'fa-phone-alt','title'=>'Call Us','href'=>'tel:+254700123456','color'=>'blue'],
                ['icon'=>'fa-envelope','title'=>'Email','href'=>url('/contactus'),'color'=>'amber'],
            ]; @endphp
            @foreach($opts as $o)
            <a href="{{ $o['href'] }}" class="bg-gray-50 rounded-xl p-3 text-center hover:shadow-md transition-shadow border border-gray-100">
                <div class="w-8 h-8 bg-{{ $o['color'] }}-100 rounded-lg flex items-center justify-center mx-auto mb-1"><i class="fas {{ $o['icon'] }} text-{{ $o['color'] }}-600 text-xs"></i></div>
                <span class="text-[10px] font-semibold text-gray-700">{{ $o['title'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Help Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Subscribe for service updates and travel tips.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}
document.getElementById('helpSearch').addEventListener('input',function(){var q=this.value.toLowerCase();document.querySelectorAll('.help-article').forEach(function(a){a.style.display=a.querySelector('.help-q').textContent.toLowerCase().includes(q)?'':'none';});});
</script>
@endpush
