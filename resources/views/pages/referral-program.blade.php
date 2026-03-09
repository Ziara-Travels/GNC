@extends('layouts.main')
@section('title', 'Referral Program - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Referral <span class="text-amber-400">Program</span></h1>
        <p class="text-xs text-gray-300">Share the love of travel and earn rewards for every friend you refer</p>
    </div>
</section>

{{-- ===== HOW IT WORKS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">How It Works</h2>
        <div class="grid sm:grid-cols-3 gap-3">
            @foreach([['step'=>'1','icon'=>'fa-share-alt','title'=>'Share Your Link','text'=>'Get your unique referral link from your dashboard and share it with friends & family.'],['step'=>'2','icon'=>'fa-user-plus','title'=>'Friend Signs Up','text'=>'Your friend creates an account and makes their first booking through your link.'],['step'=>'3','icon'=>'fa-gift','title'=>'Both Get Rewarded','text'=>'You earn $15 credit and your friend gets $10 off their first booking!']] as $s)
            <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100 relative">
                <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-emerald-600 text-white text-[9px] font-bold w-5 h-5 rounded-full flex items-center justify-center">{{ $s['step'] }}</span>
                <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-2 mt-1"><i class="fas {{ $s['icon'] }} text-emerald-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $s['title'] }}</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">{{ $s['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== REWARDS TABLE ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Reward Tiers</h2>
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <table class="w-full text-xs">
                <thead><tr class="bg-emerald-600 text-white"><th class="p-3 text-left font-semibold">Referrals</th><th class="p-3 text-left font-semibold">Your Reward</th><th class="p-3 text-left font-semibold">Bonus</th></tr></thead>
                <tbody>
                    @foreach([['range'=>'1 – 5','reward'=>'$15 per referral','bonus'=>'—'],['range'=>'6 – 15','reward'=>'$20 per referral','bonus'=>'Free seat upgrade'],['range'=>'16 – 30','reward'=>'$25 per referral','bonus'=>'Airport lounge pass'],['range'=>'31+','reward'=>'$30 per referral','bonus'=>'VIP Silver membership']] as $r)
                    <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}"><td class="p-3 font-semibold text-gray-900">{{ $r['range'] }}</td><td class="p-3 text-gray-600">{{ $r['reward'] }}</td><td class="p-3 text-amber-600 font-semibold">{{ $r['bonus'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-6 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Start Earning</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Share Your Referral Link</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Log in to your account to get your unique referral link. Share it via email, social media, or messaging apps.</p>
                <div class="flex flex-wrap gap-2">
                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-colors" onclick="alert('Log in to access your referral link')"><i class="fas fa-link mr-1"></i>Get My Link</button>
                    <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors"><i class="fab fa-facebook-f mr-1"></i>Share</button>
                    <button class="bg-sky-400 hover:bg-sky-500 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors"><i class="fab fa-twitter mr-1"></i>Tweet</button>
                    <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors"><i class="fab fa-whatsapp mr-1"></i>Send</button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Referral FAQs</h2>
        @php $faqs = [
            ['q'=>'When do I receive my referral credit?','a'=>'Credits are added to your account within 24 hours after your friend completes their first booking.'],
            ['q'=>'Is there a limit on referrals?','a'=>'No limit! The more friends you refer, the higher your reward tier grows. Keep sharing and earning.'],
            ['q'=>'Can I use credits for any booking?','a'=>'Yes, referral credits can be applied to flights, hotels, or any service available on our platform.'],
            ['q'=>'What if my friend doesn\'t book right away?','a'=>'Your referral link stays active for 90 days. If your friend books within that period, you\'ll still earn the reward.'],
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
        <h2 class="text-base font-bold text-white mb-1">Referral Updates</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get notified about bonus referral promotions and new rewards.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
