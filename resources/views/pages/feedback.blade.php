@extends('layouts.main')
@section('title', 'Feedback - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Feedback',
    'title'  => 'Your <em>Feedback</em>',
    'intro'  => 'Help us improve by sharing your experience',
])

{{-- ===== FEEDBACK FORM ===== --}}
<section class="zt-section zt-section--white" style="padding:64px 0;">
    <div class="container">
        <div class="ziara-panel" style="max-width:620px;margin:0 auto;">
            <form class="ziara-form" onsubmit="event.preventDefault();alert('Thank you for your feedback!');this.reset();document.getElementById('ratingVal').value=0;">
                <div class="ziara-field">
                    <label for="fbType">Feedback Type</label>
                    <select id="fbType" required>
                        <option value="">Select type</option>
                        <option>Compliment</option>
                        <option>Suggestion</option>
                        <option>Complaint</option>
                        <option>Bug Report</option>
                        <option>General Feedback</option>
                    </select>
                </div>

                <div class="ziara-field">
                    <label>Rate Your Experience</label>
                    <div class="flex gap-1" id="starRating" style="display:flex;gap:6px;">
                        @for($i=1;$i<=5;$i++)
                        <button type="button" class="star-btn" data-val="{{ $i }}" onclick="setRating({{ $i }})" style="background:none;border:0;cursor:pointer;padding:0;line-height:1;"><i class="fa-solid fa-star" style="font-size:24px;color:#d8d2cd;transition:color .2s;" aria-hidden="true"></i></button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingVal" value="0">
                </div>

                <div class="ziara-field"><label for="fbName">Your Name</label><input type="text" id="fbName" name="name" required></div>
                <div class="ziara-field"><label for="fbEmail">Email</label><input type="email" id="fbEmail" name="email" required></div>
                <div class="ziara-field"><label for="fbRef">Booking Reference (optional)</label><input type="text" id="fbRef" name="booking_ref" placeholder="e.g. ZT-ABC123"></div>
                <div class="ziara-field"><label for="fbMsg">Your Feedback</label><textarea id="fbMsg" name="message" rows="4" required placeholder="Tell us about your experience..."></textarea></div>

                <div class="ziara-form__actions">
                    <button type="submit" class="ziara-btn ziara-btn--gold" style="width:100%;">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ===== WHY FEEDBACK MATTERS ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Why It Matters',
            'title' => 'Why Your Feedback <em>Matters</em>',
            'align' => 'center',
        ])
        @php $reasons = [
            ['icon'=>'fa-solid fa-chart-line','title'=>'Improve Services','text'=>'Your input directly shapes how we enhance our platform and travel offerings.'],
            ['icon'=>'fa-solid fa-handshake','title'=>'Build Trust','text'=>'Transparent feedback helps us maintain the highest standards of service.'],
            ['icon'=>'fa-solid fa-lightbulb','title'=>'Drive Innovation','text'=>'Your ideas inspire new features and better booking experiences.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($reasons as $f)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="{{ $f['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $f['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $f['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Feedback FAQs',
            'title' => 'Frequently Asked <em>Questions</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'Is my feedback anonymous?','a'=>'Your name and email are collected for follow-up but your feedback can be kept confidential upon request.'],
            ['q'=>'Will I receive a response?','a'=>'Yes, we aim to acknowledge all feedback within 24-48 hours. Complaints are prioritised for faster resolution.'],
            ['q'=>'Can I track my complaint status?','a'=>'If you\'ve submitted a complaint with your email, you\'ll receive updates on the resolution progress.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
@include('partials.ziara-newsletter', ['title' => 'Stay Connected', 'desc' => 'Get updates on improvements inspired by your feedback.'])
@endsection

@push('scripts')
<script>
function setRating(v){
    document.getElementById('ratingVal').value=v;
    document.querySelectorAll('.star-btn').forEach(function(b){
        var on = parseInt(b.dataset.val)<=v;
        b.querySelector('i').style.color = on ? 'var(--gold)' : '#d8d2cd';
    });
}
</script>
@endpush