@extends('layouts.main')
@section('title', 'Customer Stories - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Customer Stories',
    'title'  => 'Customer <em>Stories</em>',
    'intro'  => 'Real experiences from real travelers',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Real Experiences',
            'title' => 'Traveler <em>Stories</em>',
            'align' => 'center',
        ])
        @php $stories = [
            ['name'=>'Mary Wanjiku','location'=>'Nairobi, Kenya','rating'=>5,'text'=>'Booked a last-minute flight to Mombasa and the whole process took under 5 minutes. E-ticket arrived instantly. Will definitely use again!','trip'=>'NBO → MBA','img'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'James Odhiambo','location'=>'Kisumu, Kenya','rating'=>5,'text'=>'The hotel deals are amazing. Found a 5-star in Zanzibar for half the usual price. Customer support helped me change dates for free.','trip'=>'Zanzibar Hotel','img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'Grace Akinyi','location'=>'Kampala, Uganda','rating'=>4,'text'=>'First time using Ziara Travels for a group booking. 12 people, 6 rooms — they handled everything professionally.','trip'=>'Group Booking','img'=>'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'Peter Mutua','location'=>'Nairobi, Kenya','rating'=>5,'text'=>'M-Pesa checkout was seamless. Got my e-ticket as a PDF and email confirmation within seconds. Very impressed!','trip'=>'NBO → KGL','img'=>'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'Sarah Njeri','location'=>'Thika, Kenya','rating'=>5,'text'=>'Used the loyalty points from my previous booking to get a discount. The rewards program is actually worth it.','trip'=>'NBO → DAR','img'=>'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&h=100&fit=crop&facepad=2'],
            ['name'=>'David Kimani','location'=>'Mombasa, Kenya','rating'=>4,'text'=>'Travel insurance saved me when my flight got cancelled due to weather. Full refund processed within a week.','trip'=>'MBA → ZNZ','img'=>'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop&facepad=2'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($stories as $s)
            <div class="ziara-feature" style="display:flex;flex-direction:column;">
                <span class="ziara-feature__icon"><i class="fa-solid fa-quote-left" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $s['name'] }}</h3>
                <div style="display:flex;align-items:center;gap:11px;margin:-2px 0 14px;">
                    <img src="{{ $s['img'] }}" alt="{{ $s['name'] }}" style="width:46px;height:46px;border-radius:50%;object-fit:cover;" loading="lazy">
                    <div>
                        <div style="font-size:13px;color:var(--muted);">{{ $s['location'] }}</div>
                        <div style="font-size:12px;color:var(--gold-dim);font-weight:700;letter-spacing:.5px;margin-top:3px;">{{ $s['trip'] }}</div>
                    </div>
                </div>
                <div style="display:flex;gap:3px;margin-bottom:12px;">
                    @for($i=0;$i<$s['rating'];$i++)<i class="fa-solid fa-star" style="color:var(--gold);" aria-hidden="true"></i>@endfor
                    @for($i=$s['rating'];$i<5;$i++)<i class="fa-solid fa-star" style="color:#e5e5e5;" aria-hidden="true"></i>@endfor
                </div>
                <p class="ziara-feature__desc">"{{ $s['text'] }}"</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        <div class="ziara-panel" style="padding:0;overflow:hidden;">
            <div class="ziara-split" style="gap:0;">
                <div>
                    <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="" style="width:100%;height:100%;min-height:280px;object-fit:cover;" loading="lazy">
                </div>
                <div style="padding:32px;">
                    @include('partials.ziara-section-head', [
                        'sub'   => 'Your Turn',
                        'title' => 'Share Your Travel <em>Story</em>',
                        'align' => 'left',
                    ])
                    <div class="ziara-prose">
                        <p>Had a great experience? We'd love to hear from you. Share your story and inspire other travelers.</p>
                    </div>
                    <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--gold">Share Your Story</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Customer Questions',
            'title' => 'Customer <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'Are these real customer reviews?','a'=>'Yes! All stories are from verified Ziara Travels customers who booked through our platform.'],
            ['q'=>'How can I leave a review?','a'=>'After your trip, you\'ll receive an email invitation to share your experience. You can also submit via the Contact Us page.'],
            ['q'=>'Can I share photos from my trip?','a'=>'Absolutely! Include photos when submitting your story and we may feature them on this page.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Travel Inspiration'])
@endsection
