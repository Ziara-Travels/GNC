@extends('layouts.main')
@section('title', 'Feedback - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Your <span class="text-amber-400">Feedback</span></h1>
        <p class="text-xs text-gray-300">Help us improve by sharing your experience</p>
    </div>
</section>

{{-- ===== FEEDBACK FORM ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-lg mx-auto px-4">
        <form class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-4" onsubmit="event.preventDefault();alert('Thank you for your feedback!');this.reset();">
            <div>
                <label class="text-[10px] font-semibold text-gray-700 block mb-1">Feedback Type</label>
                <select class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white" required>
                    <option value="">Select type</option>
                    <option>Compliment</option>
                    <option>Suggestion</option>
                    <option>Complaint</option>
                    <option>Bug Report</option>
                    <option>General Feedback</option>
                </select>
            </div>

            <div>
                <label class="text-[10px] font-semibold text-gray-700 block mb-1.5">Rate Your Experience</label>
                <div class="flex gap-1" id="starRating">
                    @for($i=1;$i<=5;$i++)
                    <button type="button" class="star-btn text-gray-200 hover:text-amber-400 transition-colors" data-val="{{ $i }}" onclick="setRating({{ $i }})"><i class="fas fa-star text-lg"></i></button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingVal" value="0">
            </div>

            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Your Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Email</label><input type="email" name="email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Booking Reference (optional)</label><input type="text" name="booking_ref" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="e.g. ZT-ABC123"></div>
            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Your Feedback</label><textarea name="message" rows="4" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none" placeholder="Tell us about your experience..."></textarea></div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors">Submit Feedback</button>
        </form>
    </div>
</section>

{{-- ===== WHY FEEDBACK MATTERS ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Why Your Feedback Matters</h2>
        <div class="grid sm:grid-cols-3 gap-3">
            @foreach([['icon'=>'fa-chart-line','title'=>'Improve Services','text'=>'Your input directly shapes how we enhance our platform and travel offerings.'],['icon'=>'fa-handshake','title'=>'Build Trust','text'=>'Transparent feedback helps us maintain the highest standards of service.'],['icon'=>'fa-lightbulb','title'=>'Drive Innovation','text'=>'Your ideas inspire new features and better booking experiences.']] as $f)
            <div class="bg-white rounded-xl p-4 text-center border border-gray-100">
                <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas {{ $f['icon'] }} text-emerald-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $f['title'] }}</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">{{ $f['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Feedback FAQs</h2>
        @php $faqs = [
            ['q'=>'Is my feedback anonymous?','a'=>'Your name and email are collected for follow-up but your feedback can be kept confidential upon request.'],
            ['q'=>'Will I receive a response?','a'=>'Yes, we aim to acknowledge all feedback within 24-48 hours. Complaints are prioritised for faster resolution.'],
            ['q'=>'Can I track my complaint status?','a'=>'If you\'ve submitted a complaint with your email, you\'ll receive updates on the resolution progress.'],
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
        <h2 class="text-base font-bold text-white mb-1">Stay Connected</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get updates on improvements inspired by your feedback.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}
function setRating(v){document.getElementById('ratingVal').value=v;document.querySelectorAll('.star-btn').forEach(function(b){b.querySelector('i').className='fas fa-star text-lg';if(parseInt(b.dataset.val)<=v)b.classList.replace('text-gray-200','text-amber-400');else{b.classList.remove('text-amber-400');b.classList.add('text-gray-200');}});}
</script>
@endpush
