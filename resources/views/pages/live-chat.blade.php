@extends('layouts.main')
@section('title', 'Live Chat Support - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1556745757-8d76bdb6984b?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Live Chat <span class="text-amber-400">Support</span></h1>
        <p class="text-xs text-gray-300">Get instant help from our travel experts — available 24/7</p>
        <div class="inline-flex items-center gap-1.5 mt-3 bg-white/10 rounded-full px-3 py-1"><span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span><span class="text-[10px] text-gray-200">Support agents online now</span></div>
    </div>
</section>

{{-- ===== CHAT INTERFACE ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-lg mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="bg-emerald-600 px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2"><div class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center"><i class="fas fa-headset text-white text-xs"></i></div><div><h3 class="text-xs font-bold text-white">Ziara Travel Support</h3><p class="text-[9px] text-emerald-100">Typically replies within minutes</p></div></div>
                <div class="flex items-center gap-1"><span class="w-2 h-2 bg-green-300 rounded-full"></span><span class="text-[9px] text-emerald-100">Online</span></div>
            </div>
            <div class="h-64 overflow-y-auto p-4 bg-gray-50 space-y-3" id="chatMessages">
                <div class="flex items-start gap-2">
                    <div class="w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center shrink-0"><i class="fas fa-robot text-white text-[8px]"></i></div>
                    <div class="bg-white rounded-xl rounded-tl-sm p-2.5 shadow-sm max-w-[75%]"><p class="text-[11px] text-gray-800">Hello! Welcome to Ziara Travel support. How can I help you today?</p><p class="text-[8px] text-gray-400 mt-1">Just now</p></div>
                </div>
                <div class="flex flex-wrap gap-1.5" id="quickReplies">
                    <button class="quick-reply bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] hover:bg-emerald-100 transition-colors" data-msg="I need help with my booking">Help with booking</button>
                    <button class="quick-reply bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] hover:bg-emerald-100 transition-colors" data-msg="I want to cancel my trip">Cancel trip</button>
                    <button class="quick-reply bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] hover:bg-emerald-100 transition-colors" data-msg="I have a payment issue">Payment issue</button>
                    <button class="quick-reply bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] hover:bg-emerald-100 transition-colors" data-msg="Tell me about travel insurance">Travel insurance</button>
                </div>
            </div>
            <div class="p-3 bg-white border-t border-gray-100">
                <div class="flex gap-2">
                    <input type="text" id="chatInput" placeholder="Type your message..." class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <button id="sendBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg transition-colors"><i class="fas fa-paper-plane text-[10px]"></i></button>
                </div>
                <p class="text-[8px] text-gray-400 text-center mt-1.5">We typically respond within 2-3 minutes</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== WHY LIVE CHAT ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Why Live Chat?</h2>
        <div class="grid sm:grid-cols-3 gap-3">
            @foreach([['icon'=>'fa-clock','title'=>'24/7 Availability','text'=>'Our support team is available round the clock to assist you with any travel queries.'],['icon'=>'fa-bolt','title'=>'Instant Response','text'=>'Get immediate answers without waiting on hold or for email replies.'],['icon'=>'fa-user-tie','title'=>'Expert Support','text'=>'Chat with experienced travel specialists for bookings, changes, and advice.']] as $f)
            <div class="bg-white rounded-xl p-4 text-center border border-gray-100">
                <div class="w-8 h-8 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas {{ $f['icon'] }} text-emerald-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-1">{{ $f['title'] }}</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">{{ $f['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== ALTERNATIVE CONTACT ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Other Ways to Reach Us</h2>
        <div class="grid sm:grid-cols-3 gap-3">
            @foreach([['icon'=>'fa-phone','title'=>'Phone','text'=>'Call us directly for urgent matters','link'=>'tel:+254700000000','label'=>'+254 700 000 000','color'=>'blue'],['icon'=>'fa-envelope','title'=>'Email','text'=>'Send us detailed inquiries','link'=>'mailto:support@ziaratravel.com','label'=>'support@ziaratravel.com','color'=>'emerald'],['icon'=>'fa-question-circle','title'=>'Help Center','text'=>'Browse our knowledge base','link'=>url('/help-center'),'label'=>'Visit Help Center','color'=>'amber']] as $c)
            <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                <div class="w-8 h-8 bg-{{ $c['color'] }}-50 rounded-full flex items-center justify-center mx-auto mb-2"><i class="fas {{ $c['icon'] }} text-{{ $c['color'] }}-600 text-xs"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $c['title'] }}</h3>
                <p class="text-[10px] text-gray-500 mb-2">{{ $c['text'] }}</p>
                <a href="{{ $c['link'] }}" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700">{{ $c['label'] }}</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Chat Support FAQs</h2>
        @php $faqs = [
            ['q'=>'Is the live chat available 24/7?','a'=>'Yes! Our support agents are available around the clock to help with bookings, cancellations, and general inquiries.'],
            ['q'=>'Can I get a transcript of the chat?','a'=>'Yes, after your chat session ends you\'ll receive an email with the full transcript for your records.'],
            ['q'=>'What can I get help with via chat?','a'=>'Everything from booking, cancellations, payment issues, travel insurance, to general travel advice and recommendations.'],
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
        <h2 class="text-base font-bold text-white mb-1">Need Help Planning?</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Subscribe for travel tips, exclusive deals, and support updates.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>
function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}
(function(){
    var msgs=document.getElementById('chatMessages'),inp=document.getElementById('chatInput'),sendBtn=document.getElementById('sendBtn');
    var responses={booking:"I'd be happy to help with your booking! Could you please provide your booking reference number?",cancel:"I understand you'd like to cancel. Can you share your booking reference? I'll check the cancellation policy.",payment:"I'm here to help with payment issues. Could you tell me more? Are you having trouble with a charge?",insurance:"We offer comprehensive travel insurance covering trip cancellation, medical emergencies, and baggage protection. Want more details?","default":"Thank you for reaching out! Could you provide more details about what you need help with?"};
    function getReply(m){m=m.toLowerCase();if(m.indexOf('booking')>-1||m.indexOf('reservation')>-1)return responses.booking;if(m.indexOf('cancel')>-1)return responses.cancel;if(m.indexOf('payment')>-1||m.indexOf('charge')>-1)return responses.payment;if(m.indexOf('insurance')>-1)return responses.insurance;return responses["default"];}
    function addMsg(text,isUser){
        var d=document.createElement('div');
        d.className='flex items-start gap-2'+(isUser?' justify-end':'');
        var safeText=document.createElement('span');safeText.textContent=text;
        if(isUser){d.innerHTML='<div class="bg-emerald-600 text-white rounded-xl rounded-tr-sm p-2.5 shadow-sm max-w-[75%]"><p class="text-[11px]"></p><p class="text-[8px] text-emerald-100 mt-1">Just now</p></div><div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center shrink-0"><i class="fas fa-user text-gray-500 text-[8px]"></i></div>';d.querySelector('p').textContent=text;}
        else{d.innerHTML='<div class="w-6 h-6 bg-emerald-600 rounded-full flex items-center justify-center shrink-0"><i class="fas fa-robot text-white text-[8px]"></i></div><div class="bg-white rounded-xl rounded-tl-sm p-2.5 shadow-sm max-w-[75%]"><p class="text-[11px] text-gray-800"></p><p class="text-[8px] text-gray-400 mt-1">Just now</p></div>';d.querySelectorAll('p')[0].textContent=text;}
        msgs.appendChild(d);msgs.scrollTop=msgs.scrollHeight;
    }
    function send(m){if(!m.trim())return;addMsg(m,true);var qr=document.getElementById('quickReplies');if(qr)qr.remove();setTimeout(function(){addMsg(getReply(m),false);},1200);}
    document.querySelectorAll('.quick-reply').forEach(function(b){b.addEventListener('click',function(){send(this.dataset.msg);});});
    sendBtn.addEventListener('click',function(){send(inp.value);inp.value='';});
    inp.addEventListener('keypress',function(e){if(e.key==='Enter'){send(this.value);this.value='';}});
})();
</script>
@endpush
