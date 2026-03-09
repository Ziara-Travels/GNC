@extends('layouts.main')
@section('title', 'Support Center - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Support <span class="text-amber-400">Center</span></h1>
        <p class="text-xs text-gray-300">We're here to help. Choose your preferred support channel below.</p>
    </div>
</section>

{{-- ===== SUPPORT CHANNELS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">How Can We Help?</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([['icon'=>'fa-comment-dots','title'=>'Live Chat','text'=>'Chat with our team in real-time','link'=>url('/live-chat'),'color'=>'emerald'],['icon'=>'fa-phone','title'=>'Call Us','text'=>'+254 700 000 000','link'=>'tel:+254700000000','color'=>'blue'],['icon'=>'fa-envelope','title'=>'Email','text'=>'support@ziaratravel.com','link'=>'mailto:support@ziaratravel.com','color'=>'amber'],['icon'=>'fa-book','title'=>'Help Center','text'=>'Browse knowledge base','link'=>url('/help-center'),'color'=>'purple']] as $ch)
            <a href="{{ $ch['link'] }}" class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100 hover:shadow-md transition-shadow block">
                <div class="w-8 h-8 bg-{{ $ch['color'] }}-50 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas {{ $ch['icon'] }} text-{{ $ch['color'] }}-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $ch['title'] }}</h3>
                <p class="text-[10px] text-gray-500">{{ $ch['text'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CONTACT FORM ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-lg mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Send Us a Message</h2>
        <form action="{{ url('/contactus') }}" method="GET" class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm space-y-3">
            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Your Name</label><input type="text" name="name" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Email Address</label><input type="email" name="email" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></div>
            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Subject</label>
                <select name="subject" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                    <option value="">Select a topic</option>
                    <option>Booking Issue</option>
                    <option>Payment Problem</option>
                    <option>Flight Change</option>
                    <option>Cancellation</option>
                    <option>General Inquiry</option>
                </select>
            </div>
            <div><label class="text-[10px] font-semibold text-gray-700 block mb-1">Message</label><textarea name="message" rows="3" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none resize-none"></textarea></div>
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-2 rounded-lg transition-colors">Send Message</button>
        </form>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Quick Answers</h2>
        @php $faqs = [
            ['q'=>'What are your support hours?','a'=>'Our live chat and phone support are available 24/7. Email responses are typically within 2-4 hours during business days.'],
            ['q'=>'How do I track my booking status?','a'=>'Log in to your account and navigate to "My Bookings" to see real-time status updates for all your reservations.'],
            ['q'=>'Can I get a refund?','a'=>'Refund eligibility depends on your fare type and airline policy. Contact our support team with your booking reference for assistance.'],
            ['q'=>'How long does it take to receive my e-ticket?','a'=>'E-tickets are generated instantly after successful payment. You can download them from the confirmation page or your email.'],
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
        <h2 class="text-base font-bold text-white mb-1">Stay Informed</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get support updates, travel tips, and exclusive offers.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
