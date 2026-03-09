@extends('layouts.main')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Customer <span class="text-amber-400">Support</span></h1>
        <p class="text-xs text-gray-300">Get help with your bookings and travel needs — We're here 24/7</p>
    </div>
</section>

{{-- ===== QUICK HELP ===== --}}
<section class="py-8 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-5">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Quick Help</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Get Instant Support</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php $help = [
                ['icon'=>'fa-comments','title'=>'Live Chat','desc'=>'Real-time support','btn'=>'Start Chat','color'=>'emerald','href'=>'#'],
                ['icon'=>'fa-envelope','title'=>'Email','desc'=>'Send us a message','btn'=>'Send Email','color'=>'blue','href'=>'#contact-form'],
                ['icon'=>'fa-question-circle','title'=>'FAQ','desc'=>'Common questions','btn'=>'Browse FAQ','color'=>'purple','href'=>route('help')],
                ['icon'=>'fa-phone-alt','title'=>'Phone','desc'=>'Speak to our team','btn'=>'Call Now','color'=>'amber','href'=>'tel:+254700123456'],
            ]; @endphp
            @foreach($help as $h)
            <div class="bg-gray-50 rounded-xl p-3 text-center hover:shadow-md transition-shadow border border-gray-100">
                <div class="w-8 h-8 bg-{{ $h['color'] }}-100 rounded-lg flex items-center justify-center mx-auto mb-2"><i class="fas {{ $h['icon'] }} text-{{ $h['color'] }}-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $h['title'] }}</h3>
                <p class="text-[10px] text-gray-400 mb-2">{{ $h['desc'] }}</p>
                <a href="{{ $h['href'] }}" class="inline-block bg-{{ $h['color'] }}-600 hover:bg-{{ $h['color'] }}-700 text-white text-[10px] font-semibold px-3 py-1.5 rounded-lg transition-colors">{{ $h['btn'] }}</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CONTACT FORM ===== --}}
<section id="contact-form" class="py-10 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4">
        <div class="text-center mb-5">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Send Message</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Get in Touch</h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600 text-xs"></i>
                <span class="text-xs text-green-700 font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-xs text-red-700 font-medium mb-1"><i class="fas fa-exclamation-circle mr-1"></i>Please correct the following:</p>
                <ul class="text-[11px] text-red-600 space-y-0.5 list-disc pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ route('contactus.submit') }}" method="POST">
                @csrf
                <div class="grid md:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-2.5 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-2.5 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500">
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Phone <span class="text-gray-300">(Optional)</span></label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full px-2.5 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Subject</label>
                        <select name="subject" required class="w-full px-2.5 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500">
                            <option value="">Select a subject</option>
                            <option value="booking-inquiry" {{ old('subject')=='booking-inquiry'?'selected':'' }}>Booking Inquiry</option>
                            <option value="technical-support" {{ old('subject')=='technical-support'?'selected':'' }}>Technical Support</option>
                            <option value="payment-issue" {{ old('subject')=='payment-issue'?'selected':'' }}>Payment Issue</option>
                            <option value="flight-change" {{ old('subject')=='flight-change'?'selected':'' }}>Flight Change</option>
                            <option value="refund-request" {{ old('subject')=='refund-request'?'selected':'' }}>Refund Request</option>
                            <option value="general-inquiry" {{ old('subject')=='general-inquiry'?'selected':'' }}>General Inquiry</option>
                            <option value="other" {{ old('subject')=='other'?'selected':'' }}>Other</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-[10px] font-semibold text-gray-400 uppercase mb-0.5 block">Message</label>
                    <textarea name="message" rows="4" required class="w-full px-2.5 py-2 border border-gray-200 rounded-lg text-xs text-gray-800 focus:ring-1 focus:ring-emerald-500" placeholder="Describe your inquiry in detail...">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-1"><i class="fas fa-paper-plane text-[10px]"></i> Send Message</button>
            </form>
        </div>
    </div>
</section>

{{-- ===== CONTACT INFO ===== --}}
<section class="py-10 bg-slate-900">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-base font-bold text-white text-center mb-6">Other Ways to Reach Us</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @php $channels = [
                ['icon'=>'fa-phone-alt','title'=>'Phone','desc'=>'Available 24/7','value'=>'+254 700 123 456','href'=>'tel:+254700123456','color'=>'emerald'],
                ['icon'=>'fa-envelope','title'=>'Email','desc'=>'Response within 24h','value'=>'support@ziaratravels.com','href'=>'mailto:support@ziaratravels.com','color'=>'blue'],
                ['icon'=>'fa-brands fa-whatsapp','title'=>'WhatsApp','desc'=>'Chat instantly','value'=>'+254 700 123 456','href'=>'https://wa.me/254700123456','color'=>'green'],
            ]; @endphp
            @foreach($channels as $ch)
            <div class="text-center bg-white/5 rounded-xl p-4 border border-white/10">
                <div class="w-10 h-10 bg-{{ $ch['color'] }}-500/20 rounded-full flex items-center justify-center mx-auto mb-2"><i class="{{ $ch['icon'] }} text-{{ $ch['color'] }}-400 text-sm"></i></div>
                <h3 class="text-xs font-bold text-white mb-0.5">{{ $ch['title'] }}</h3>
                <p class="text-[10px] text-gray-400 mb-1">{{ $ch['desc'] }}</p>
                <a href="{{ $ch['href'] }}" class="text-[11px] font-medium text-{{ $ch['color'] }}-400 hover:underline">{{ $ch['value'] }}</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Contact FAQs</h2>
        @php $faqs = [
            ['q'=>'How quickly will I get a response?','a'=>'Live chat is instant. Email inquiries are typically answered within 24 hours. Phone support is available 24/7.'],
            ['q'=>'Can I change or cancel my booking?','a'=>'Yes, changes and cancellations depend on the fare rules of your ticket. Contact support with your booking reference for assistance.'],
            ['q'=>'How do I request a refund?','a'=>'Use the contact form with subject "Refund Request" and include your booking reference. Our team will process it according to the fare rules.'],
            ['q'=>'Do you have an office I can visit?','a'=>'Our main office is in Nairobi. Please call ahead to schedule an appointment for in-person meetings.'],
            ['q'=>'What information should I include in my message?','a'=>'Include your booking reference (if applicable), full name, email, and a detailed description of your issue for the fastest resolution.'],
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
        <h2 class="text-base font-bold text-white mb-1">Need Travel Tips?</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Subscribe for travel advice, exclusive deals, and booking updates.</p>
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
