@extends('layouts.main')
@section('title', 'Support Center - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Support Center',
    'title'  => 'Support <em>Center</em>',
    'intro'  => 'We\'re here to help. Choose your preferred support channel below.',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Support Center',
            'title' => 'How Can We <em>Help?</em>',
            'align' => 'center',
        ])
        @php $channels = [
            ['icon'=>'fa-solid fa-comment-dots','title'=>'Live Chat','text'=>'Chat with our team in real-time','link'=>url('/live-chat'),'color'=>'emerald'],
            ['icon'=>'fa-solid fa-phone','title'=>'Call Us','text'=>'+254 700 000 000','link'=>'tel:+254700000000','color'=>'blue'],
            ['icon'=>'fa-solid fa-envelope','title'=>'Email','text'=>'support@ziaratravel.com','link'=>'mailto:support@ziaratravel.com','color'=>'amber'],
            ['icon'=>'fa-solid fa-book','title'=>'Help Center','text'=>'Browse knowledge base','link'=>url('/help-center'),'color'=>'purple'],
        ]; @endphp
        <div class="ziara-minifeatures">
            @foreach($channels as $ch)
            <a href="{{ $ch['link'] }}" class="ziara-minifeature" style="color:inherit;text-decoration:none;">
                <span class="ziara-minifeature__icon"><i class="{{ $ch['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $ch['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $ch['text'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Support Center',
            'title' => 'Send Us a <em>Message</em>',
            'align' => 'center',
        ])
        <div class="ziara-panel" style="max-width:620px;margin:0 auto;">
            <form action="{{ url('/contactus') }}" method="GET" class="ziara-form" style="margin:0;">
                <div class="ziara-field">
                    <label for="support-name">Your Name</label>
                    <input id="support-name" type="text" name="name" required>
                </div>
                <div class="ziara-field">
                    <label for="support-email">Email Address</label>
                    <input id="support-email" type="email" name="email" required>
                </div>
                <div class="ziara-field">
                    <label for="support-subject">Subject</label>
                    <select id="support-subject" name="subject">
                        <option value="">Select a topic</option>
                        <option>Booking Issue</option>
                        <option>Payment Problem</option>
                        <option>Flight Change</option>
                        <option>Cancellation</option>
                        <option>General Inquiry</option>
                    </select>
                </div>
                <div class="ziara-field">
                    <label for="support-message">Message</label>
                    <textarea id="support-message" name="message" rows="3" required></textarea>
                </div>
                <div class="ziara-form__actions">
                    <button type="submit" class="ziara-btn ziara-btn--gold" style="width:100%;">Send Message</button>
                </div>
            </form>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Support Center',
            'title' => 'Quick <em>Answers</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'What are your support hours?','a'=>'Our live chat and phone support are available 24/7. Email responses are typically within 2-4 hours during business days.'],
            ['q'=>'How do I track my booking status?','a'=>'Log in to your account and navigate to "My Bookings" to see real-time status updates for all your reservations.'],
            ['q'=>'Can I get a refund?','a'=>'Refund eligibility depends on your fare type and airline policy. Contact our support team with your booking reference for assistance.'],
            ['q'=>'How long does it take to receive my e-ticket?','a'=>'E-tickets are generated instantly after successful payment. You can download them from the confirmation page or your email.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Stay Informed'])
@endsection
