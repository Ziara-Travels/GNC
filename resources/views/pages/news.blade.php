@extends('layouts.main')
@section('title', 'News & Updates - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'News & Updates',
    'title'  => 'News & <em>Updates</em>',
    'intro'  => 'Travel trends, announcements, and industry insights',
])

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-split">
            <div>
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=600&h=400&fit=crop" alt="" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Featured',
                    'title' => 'Ziara Travel Expands to <em>Southern Africa</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-24px;">
                    <p><i class="fa-solid fa-calendar" aria-hidden="true"></i> August 1, 2025</p>
                    <p>New offices opening in Cape Town and Gaborone. Our expanded team of local travel experts will enhance your experiences throughout Southern Africa.</p>
                </div>
                <a href="#" class="ziara-btn ziara-btn--gold">Read Full Article <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'News & Updates',
            'title' => 'Latest <em>News</em>',
            'align' => 'center',
        ])
        @php $news = [
            ['title'=>'New Luxury Safari Packages Launched','date'=>'July 25, 2025','text'=>'Discover newly curated luxury safari experiences featuring exclusive lodges and private guides.','img'=>'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=250&fit=crop'],
            ['title'=>'Ziara Wins Service Excellence Award','date'=>'July 18, 2025','text'=>'Honoured with the Travel Service Excellence Award for the third consecutive year.','img'=>'https://images.unsplash.com/photo-1567521464027-f127ff144326?w=400&h=250&fit=crop'],
            ['title'=>'Sustainable Tourism Alliance Partnership','date'=>'July 10, 2025','text'=>'Joined forces to further commitment to environmentally responsible travel practices.','img'=>'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=250&fit=crop'],
            ['title'=>'Mobile App: Book Flights On-The-Go','date'=>'July 5, 2025','text'=>'Search, compare, and book flights directly from your smartphone with our new app.','img'=>'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=250&fit=crop'],
            ['title'=>'Summer Family Packages Promotion','date'=>'June 28, 2025','text'=>'Discounted rates on family travel packages to popular East African destinations.','img'=>'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&h=250&fit=crop'],
            ['title'=>'Travel Trends: What to Expect in 2026','date'=>'June 20, 2025','text'=>'Our experts analyze emerging trends and what travelers can expect in the coming year.','img'=>'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=400&h=250&fit=crop'],
        ]; @endphp
        <div class="ziara-deals">
            @foreach($news as $n)
            <article class="ziara-deal">
                <div class="ziara-deal__media">
                    <img src="{{ $n['img'] }}" alt="{{ $n['title'] }}" loading="lazy">
                    <span class="ziara-deal__tag"><i class="fa-solid fa-calendar" aria-hidden="true"></i> {{ $n['date'] }}</span>
                </div>
                <div class="ziara-deal__body">
                    <h3 class="ziara-deal__title">{{ $n['title'] }}</h3>
                    <p class="ziara-deal__desc">{{ $n['text'] }}</p>
                    <a href="#" class="ziara-deal__btn">Read More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Stay Updated'])
@endsection
