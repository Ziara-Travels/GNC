@extends('layouts.main')
@section('title', 'Live Chat Support - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Live Chat',
    'title'  => 'Live Chat <em>Support</em>',
    'intro'  => 'Get instant help from our travel experts — available 24/7',
    'extra'  => '<div class="inline-flex items-center gap-1.5" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:50px;padding:8px 18px;"><span style="width:8px;height:8px;background:#57c785;border-radius:50%;animation:pulse 1.6s infinite;"></span><span style="font-size:11px;letter-spacing:.5px;color:rgba(255,255,255,.9);">Support agents online now</span></div>',
])

{{-- ===== CHAT INTERFACE ===== --}}
<section class="zt-section zt-section--white" style="padding:72px 0;">
    <div class="container">
        <div class="ziara-panel" style="padding:0;overflow:hidden;max-width:640px;margin:0 auto;box-shadow:0 30px 60px rgba(30,12,8,.18);">
            <div style="background:linear-gradient(135deg,var(--maroon-deep),var(--maroon));padding:20px 26px;display:flex;align-items:center;justify-content:space-between;gap:12px;">
                <div style="display:flex;align-items:center;gap:13px;">
                    <div style="width:44px;height:44px;background:rgba(255,255,255,.16);border-radius:50%;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-headset" aria-hidden="true" style="color:#fff;font-size:18px;"></i></div>
                    <div>
                        <h3 style="margin:0;font-size:15px;font-weight:700;color:#fff;">Ziara Travel Support</h3>
                        <p style="margin:2px 0 0;font-size:11px;color:rgba(255,255,255,.8);">Typically replies within minutes</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;background:rgba(255,255,255,.14);border-radius:50px;padding:5px 12px;"><span style="width:7px;height:7px;background:#57c785;border-radius:50%;"></span><span style="font-size:10px;color:rgba(255,255,255,.9);letter-spacing:.5px;">Online</span></div>
            </div>
            <div style="height:340px;overflow-y:auto;padding:22px;background:#faf6f2;" id="chatMessages">
                <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:12px;">
                    <div style="width:30px;height:30px;border-radius:50%;background:var(--maroon);color:#fff;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-robot" aria-hidden="true" style="font-size:13px;"></i></div>
                    <div style="max-width:75%;background:#fff;border:1px solid var(--line);border-radius:14px;border-bottom-left-radius:4px;padding:11px 14px;box-shadow:0 4px 12px rgba(30,12,8,.06);"><p style="margin:0;font-size:13.5px;color:#3a322c;line-height:1.55;">Hello! Welcome to Ziara Travel support. How can I help you today?</p><p style="margin:5px 0 0;font-size:10px;color:var(--muted);">Just now</p></div>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:8px;" id="quickReplies">
                    <button type="button" class="quick-reply" data-msg="I need help with my booking" style="background:var(--gold-pale);color:var(--maroon);border:1px solid var(--gold-border);padding:9px 16px;border-radius:50px;font-size:12px;cursor:pointer;transition:background .25s;">Help with booking</button>
                    <button type="button" class="quick-reply" data-msg="I want to cancel my trip" style="background:var(--gold-pale);color:var(--maroon);border:1px solid var(--gold-border);padding:9px 16px;border-radius:50px;font-size:12px;cursor:pointer;transition:background .25s;">Cancel trip</button>
                    <button type="button" class="quick-reply" data-msg="I have a payment issue" style="background:var(--gold-pale);color:var(--maroon);border:1px solid var(--gold-border);padding:9px 16px;border-radius:50px;font-size:12px;cursor:pointer;transition:background .25s;">Payment issue</button>
                    <button type="button" class="quick-reply" data-msg="Tell me about travel insurance" style="background:var(--gold-pale);color:var(--maroon);border:1px solid var(--gold-border);padding:9px 16px;border-radius:50px;font-size:12px;cursor:pointer;transition:background .25s;">Travel insurance</button>
                </div>
            </div>
            <div style="padding:16px 20px;background:#fff;border-top:1px solid var(--line);">
                <div style="display:flex;gap:10px;">
                    <input type="text" id="chatInput" placeholder="Type your message..." style="flex:1;padding:13px 16px;font-size:14.5px;color:var(--ink);background:#fff;border:1px solid var(--line);border-radius:50px;outline:none;">
                    <button id="sendBtn" type="button" style="flex-shrink:0;width:48px;height:48px;border:0;border-radius:50%;background:var(--gold);color:var(--maroon-deep);cursor:pointer;"><i class="fa-solid fa-paper-plane" aria-hidden="true" style="font-size:14px;"></i></button>
                </div>
                <p style="margin:10px 0 0;font-size:11px;color:var(--muted);text-align:center;">We typically respond within 2-3 minutes</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== WHY LIVE CHAT ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Why Live Chat?',
            'title' => 'Support That Comes to <em>You</em>',
            'align' => 'center',
        ])
        @php $benefits = [
            ['icon'=>'fa-solid fa-clock','title'=>'24/7 Availability','desc'=>'Our support team is available round the clock to assist you with any travel queries.'],
            ['icon'=>'fa-solid fa-bolt','title'=>'Instant Response','desc'=>'Get immediate answers without waiting on hold or for email replies.'],
            ['icon'=>'fa-solid fa-user-tie','title'=>'Expert Support','desc'=>'Chat with experienced travel specialists for bookings, changes, and advice.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($benefits as $f)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $f['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $f['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $f['text'] ?? $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== ALTERNATIVE CONTACT ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Other Ways',
            'title' => 'Other Ways to Reach <em>Us</em>',
            'align' => 'center',
        ])
        @php $others = [
            ['icon'=>'fa-solid fa-phone','title'=>'Phone','text'=>'Call us directly for urgent matters','link'=>'tel:+254700000000','label'=>'+254 700 000 000'],
            ['icon'=>'fa-solid fa-envelope','title'=>'Email','text'=>'Send us detailed inquiries','link'=>'mailto:support@ziaratravel.com','label'=>'support@ziaratravel.com'],
            ['icon'=>'fa-solid fa-circle-question','title'=>'Help Center','text'=>'Browse our knowledge base','link'=>url('/help-center'),'label'=>'Visit Help Center'],
        ]; @endphp
        <div class="ziara-infocards">
            @foreach($others as $c)
            <div class="ziara-infocard">
                <span class="ziara-infocard__icon"><i class="{{ $c['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-infocard__title">{{ $c['title'] }}</h3>
                <div class="ziara-infocard__value">{{ $c['text'] }}<br><a href="{{ $c['link'] }}">{{ $c['label'] }}</a></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Chat Support FAQs',
            'title' => 'Frequently Asked <em>Questions</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'Is the live chat available 24/7?','a'=>'Yes! Our support agents are available around the clock to help with bookings, cancellations, and general inquiries.'],
            ['q'=>'Can I get a transcript of the chat?','a'=>'Yes, after your chat session ends you\'ll receive an email with the full transcript for your records.'],
            ['q'=>'What can I get help with via chat?','a'=>'Everything from booking, cancellations, payment issues, travel insurance, to general travel advice and recommendations.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Need Help Planning?', 'desc' => 'Subscribe for travel tips, exclusive deals, and support updates.'])
@endsection

@push('scripts')
<script>
(function(){
    var msgs=document.getElementById('chatMessages'),inp=document.getElementById('chatInput'),sendBtn=document.getElementById('sendBtn');
    var responses={booking:"I'd be happy to help with your booking! Could you please provide your booking reference number?",cancel:"I understand you'd like to cancel. Can you share your booking reference? I'll check the cancellation policy.",payment:"I'm here to help with payment issues. Could you tell me more? Are you having trouble with a charge?",insurance:"We offer comprehensive travel insurance covering trip cancellation, medical emergencies, and baggage protection. Want more details?","default":"Thank you for reaching out! Could you provide more details about what you need help with?"};
    function getReply(m){m=m.toLowerCase();if(m.indexOf('booking')>-1||m.indexOf('reservation')>-1)return responses.booking;if(m.indexOf('cancel')>-1)return responses.cancel;if(m.indexOf('payment')>-1||m.indexOf('charge')>-1)return responses.payment;if(m.indexOf('insurance')>-1)return responses.insurance;return responses["default"];}
    function addMsg(text,isUser){
        var d=document.createElement('div');
        d.style.display='flex';d.style.alignItems='flex-start';d.style.gap='10px';d.style.marginBottom='12px';
        var safeText=document.createElement('span');safeText.textContent=text;
        if(isUser){
            d.style.justifyContent='flex-end';
            d.innerHTML='<div style="max-width:75%;background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));color:#fff;border-radius:14px;border-bottom-right-radius:4px;padding:11px 14px;box-shadow:0 4px 12px rgba(30,12,8,.14);"><p style="margin:0;font-size:13.5px;line-height:1.55;color:#fff;"></p><p style="margin:5px 0 0;font-size:10px;color:rgba(255,255,255,.75);">Just now</p></div><div style="width:30px;height:30px;border-radius:50%;background:var(--gold-pale);color:var(--maroon);display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-user" aria-hidden="true" style="font-size:13px;"></i></div>';
            d.querySelector('p').textContent=text;
        }
        else{
            d.innerHTML='<div style="width:30px;height:30px;border-radius:50%;background:var(--maroon);color:#fff;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-robot" aria-hidden="true" style="font-size:13px;"></i></div><div style="max-width:75%;background:#fff;border:1px solid var(--line);border-radius:14px;border-bottom-left-radius:4px;padding:11px 14px;box-shadow:0 4px 12px rgba(30,12,8,.06);"><p style="margin:0;font-size:13.5px;color:#3a322c;line-height:1.55;"></p><p style="margin:5px 0 0;font-size:10px;color:var(--muted);">Just now</p></div>';
            d.querySelectorAll('p')[0].textContent=text;
        }
        msgs.appendChild(d);msgs.scrollTop=msgs.scrollHeight;
    }
    function send(m){if(!m.trim())return;addMsg(m,true);var qr=document.getElementById('quickReplies');if(qr)qr.remove();setTimeout(function(){addMsg(getReply(m),false);},1200);}
    document.querySelectorAll('.quick-reply').forEach(function(b){b.addEventListener('click',function(){send(this.dataset.msg);});});
    sendBtn.addEventListener('click',function(){send(inp.value);inp.value='';});
    inp.addEventListener('keypress',function(e){if(e.key==='Enter'){send(this.value);this.value='';}});
})();
</script>
@endpush