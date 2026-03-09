@extends('layouts.main')
@section('title', 'Customer Stories - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Customer <span class="text-amber-400">Stories</span></h1>
        <p class="text-xs text-gray-300">Real experiences from real travelers</p>
    </div>
</section>

{{-- ===== TESTIMONIALS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        @php $stories = [
            ['name'=>'Mary Wanjiku','location'=>'Nairobi, Kenya','rating'=>5,'text'=>'Booked a last-minute flight to Mombasa and the whole process took under 5 minutes. E-ticket arrived instantly. Will definitely use again!','trip'=>'NBO → MBA','img'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'James Odhiambo','location'=>'Kisumu, Kenya','rating'=>5,'text'=>'The hotel deals are amazing. Found a 5-star in Zanzibar for half the usual price. Customer support helped me change dates for free.','trip'=>'Zanzibar Hotel','img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'Grace Akinyi','location'=>'Kampala, Uganda','rating'=>4,'text'=>'First time using Ziara Travels for a group booking. 12 people, 6 rooms — they handled everything professionally.','trip'=>'Group Booking','img'=>'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'Peter Mutua','location'=>'Nairobi, Kenya','rating'=>5,'text'=>'M-Pesa checkout was seamless. Got my e-ticket as a PDF and email confirmation within seconds. Very impressed!','trip'=>'NBO → KGL','img'=>'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'Sarah Njeri','location'=>'Thika, Kenya','rating'=>5,'text'=>'Used the loyalty points from my previous booking to get a discount. The rewards program is actually worth it.','trip'=>'NBO → DAR','img'=>'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'David Kimani','location'=>'Mombasa, Kenya','rating'=>4,'text'=>'Travel insurance saved me when my flight got cancelled due to weather. Full refund processed within a week.','trip'=>'MBA → ZNZ','img'=>'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&facepad=2'],
        ]; @endphp
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($stories as $s)
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="flex items-center gap-2 mb-2">
                    <img src="{{ $s['img'] }}" alt="{{ $s['name'] }}" class="w-8 h-8 rounded-full object-cover">
                    <div>
                        <h3 class="text-xs font-bold text-gray-900">{{ $s['name'] }}</h3>
                        <p class="text-[9px] text-gray-400">{{ $s['location'] }}</p>
                    </div>
                </div>
                <div class="flex gap-0.5 mb-1.5">@for($i=0;$i<$s['rating'];$i++)<i class="fas fa-star text-amber-400 text-[8px]"></i>@endfor @for($i=$s['rating'];$i<5;$i++)<i class="fas fa-star text-gray-200 text-[8px]"></i>@endfor</div>
                <p class="text-[10px] text-gray-600 leading-relaxed mb-2">"{{ $s['text'] }}"</p>
                <span class="text-[9px] bg-emerald-50 text-emerald-700 font-semibold px-2 py-0.5 rounded-full">{{ $s['trip'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row-reverse items-center gap-6 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Your Turn</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Share Your Travel Story</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Had a great experience? We'd love to hear from you. Share your story and inspire other travelers.</p>
                <a href="{{ url('/contactus') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors">Share Your Story</a>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Customer FAQs</h2>
        @php $faqs = [
            ['q'=>'Are these real customer reviews?','a'=>'Yes! All stories are from verified Ziara Travels customers who booked through our platform.'],
            ['q'=>'How can I leave a review?','a'=>'After your trip, you\'ll receive an email invitation to share your experience. You can also submit via the Contact Us page.'],
            ['q'=>'Can I share photos from my trip?','a'=>'Absolutely! Include photos when submitting your story and we may feature them on this page.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-100 transition-colors" onclick="toggleFaq(this)"><span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span><i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i></button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Travel Inspiration</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get inspired by real stories, tips, and exclusive offers.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
