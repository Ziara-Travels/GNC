@extends('layouts.main')
@section('title', 'GDPR & Data Protection - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1563986768609-322da13575f2?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">GDPR & Data <span class="text-amber-400">Protection</span></h1>
        <p class="text-xs text-gray-300">How we protect and manage your personal data</p>
    </div>
</section>

{{-- ===== YOUR RIGHTS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Your Data Rights</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
            @foreach([['icon'=>'fa-eye','title'=>'Right to Access','text'=>'Request a copy of the personal data we hold about you at any time.'],['icon'=>'fa-edit','title'=>'Right to Rectify','text'=>'Correct any inaccurate or incomplete personal data we have.'],['icon'=>'fa-trash-alt','title'=>'Right to Erasure','text'=>'Request deletion of your personal data under certain conditions.'],['icon'=>'fa-hand-paper','title'=>'Right to Restrict','text'=>'Restrict how we process your data in specific circumstances.'],['icon'=>'fa-download','title'=>'Right to Portability','text'=>'Receive your data in a portable format to transfer elsewhere.'],['icon'=>'fa-times-circle','title'=>'Right to Object','text'=>'Object to processing of your data for marketing purposes.']] as $r)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center mb-2"><i class="fas {{ $r['icon'] }} text-emerald-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $r['title'] }}</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">{{ $r['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== DATA WE COLLECT ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Data We Collect</h2>
        @php $data = [
            ['cat'=>'Identity Data','items'=>'Full name, date of birth, passport/ID number'],
            ['cat'=>'Contact Data','items'=>'Email address, phone number, billing/postal address'],
            ['cat'=>'Booking Data','items'=>'Travel itineraries, booking references, passenger details'],
            ['cat'=>'Payment Data','items'=>'M-Pesa transaction IDs (we never store payment credentials)'],
            ['cat'=>'Usage Data','items'=>'Pages visited, search queries, device and browser information'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($data as $d)
            <div class="bg-white rounded-lg p-3 border border-gray-100 flex items-start gap-3">
                <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-1.5 shrink-0"></div>
                <div><span class="text-xs font-bold text-gray-900">{{ $d['cat'] }}:</span> <span class="text-[11px] text-gray-500">{{ $d['items'] }}</span></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CONSENT PREFERENCES ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-lg mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Manage Consent</h2>
        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-3">
            @foreach([['label'=>'Essential Cookies','desc'=>'Required for website to function properly','locked'=>true],['label'=>'Analytics Cookies','desc'=>'Help us understand how visitors use the site','locked'=>false],['label'=>'Marketing Emails','desc'=>'Receive promotional offers and travel deals','locked'=>false],['label'=>'Partner Sharing','desc'=>'Share data with travel partners for bookings','locked'=>false]] as $c)
            <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                <div><h3 class="text-xs font-bold text-gray-900">{{ $c['label'] }}</h3><p class="text-[9px] text-gray-400">{{ $c['desc'] }}</p></div>
                <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" class="sr-only peer" {{ $c['locked'] ? 'checked disabled' : '' }}><div class="w-8 h-4 bg-gray-200 peer-focus:ring-2 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:bg-emerald-600"></div></label>
            </div>
            @endforeach
            <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-2 rounded-lg transition-colors mt-2" onclick="alert('Preferences saved!')">Save Preferences</button>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Data Protection FAQs</h2>
        @php $faqs = [
            ['q'=>'How long do you keep my data?','a'=>'We retain personal data for as long as your account is active or as needed to provide services. Booking records are kept for 7 years for legal compliance.'],
            ['q'=>'Do you share data with third parties?','a'=>'We share necessary data with airlines and hotels to process your bookings. We never sell your personal data to advertisers or data brokers.'],
            ['q'=>'How can I request data deletion?','a'=>'Email our Data Protection Officer at dpo@ziaratravel.com or use the Contact Us page. We process requests within 30 days.'],
            ['q'=>'Is my data transferred internationally?','a'=>'When booking international travel, some data is shared with foreign airlines. All transfers comply with GDPR safeguards.'],
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
        <h2 class="text-base font-bold text-white mb-1">Data Protection Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Stay informed about changes to our data protection practices.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
