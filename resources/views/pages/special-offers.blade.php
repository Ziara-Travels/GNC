@extends('layouts.main')
@section('title', 'Special Offers - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Limited Time',
    'title'  => 'Special <em>Offers</em>',
    'intro'  => "Exclusive deals on flights, hotels, and packages — book before they're gone",
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Hot Deals',
            'title' => 'Featured <em>Offers</em>',
            'align' => 'center',
        ])
        @php $deals = [
            ['title'=>'Nairobi → Mombasa','type'=>'Flight','discount'=>'25%','price'=>'KES 8,500','orig'=>'KES 11,300','desc'=>'Direct flight, economy class. Includes 23kg baggage.','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=400&h=250&fit=crop','badge'=>'Best Seller'],
            ['title'=>'Zanzibar Beach Resort','type'=>'Hotel','discount'=>'30%','price'=>'$129/night','orig'=>'$185/night','desc'=>'4-star beachfront resort with breakfast and airport transfer.','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=400&h=250&fit=crop','badge'=>'Top Rated'],
            ['title'=>'Nairobi → Kigali','type'=>'Flight','discount'=>'20%','price'=>'KES 15,800','orig'=>'KES 19,750','desc'=>'Return flight, flexible dates. Free date change.','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=400&h=250&fit=crop','badge'=>'New'],
            ['title'=>'Masai Mara Safari','type'=>'Package','discount'=>'15%','price'=>'KES 45,000','orig'=>'KES 52,900','desc'=>'3-day all-inclusive safari with game drives and accommodation.','img'=>'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=250&fit=crop','badge'=>'Popular'],
            ['title'=>'Serena Hotel Nairobi','type'=>'Hotel','discount'=>'20%','price'=>'$95/night','orig'=>'$119/night','desc'=>'5-star luxury in the heart of Nairobi. Free cancellation.','img'=>'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=400&h=250&fit=crop','badge'=>'Luxury'],
            ['title'=>'Nairobi → Entebbe','type'=>'Flight','discount'=>'18%','price'=>'KES 12,000','orig'=>'KES 14,600','desc'=>'One-way economy. KQ direct flight, instant e-ticket.','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=400&h=250&fit=crop','badge'=>'Flash Sale'],
        ]; @endphp
        <div class="ziara-deals">
            @foreach($deals as $d)
            <div class="ziara-deal">
                <div class="ziara-deal__media">
                    <img src="{{ $d['img'] }}" alt="{{ $d['title'] }}" loading="lazy">
                    <span class="ziara-deal__badge">{{ $d['discount'] }} OFF</span>
                    <span class="ziara-deal__tag" style="top:14px;right:14px;bottom:auto;left:auto;background:#fff;color:var(--ink);">{{ $d['badge'] }}</span>
                    <span class="ziara-deal__tag">{{ $d['type'] }}</span>
                </div>
                <div class="ziara-deal__body">
                    <h3 class="ziara-deal__title">{{ $d['title'] }}</h3>
                    <p class="ziara-deal__desc">{{ $d['desc'] }}</p>
                    <div class="ziara-deal__price"><strong>{{ $d['price'] }}</strong><s>{{ $d['orig'] }}</s></div>
                    <a href="{{ url('/flights/search') }}" class="ziara-deal__btn">Book</a>
                </div>
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
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop" alt="" style="width:100%;height:100%;min-height:280px;object-fit:cover;" loading="lazy">
                </div>
                <div style="padding:32px;">
                    @include('partials.ziara-section-head', [
                        'sub'   => 'Bundle & Save',
                        'title' => 'Flight + Hotel <em>Packages</em>',
                        'align' => 'left',
                    ])
                    <div class="ziara-prose">
                        <p>Save up to 30% when you book your flight and hotel together. Instant confirmation, flexible cancellation.</p>
                    </div>
                    <a href="{{ url('/') }}" class="ziara-btn ziara-btn--gold">View Packages</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Deal Questions',
            'title' => 'Offers <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How long are these offers valid?','a'=>'Offers have limited availability and can expire at any time. Book early to secure the best prices.'],
            ['q'=>'Can I combine multiple offers?','a'=>'Offers cannot be stacked. Each booking uses the best applicable discount automatically.'],
            ['q'=>'Are offer prices refundable?','a'=>'Refund policies depend on the fare type. Flexible rates allow free cancellation; promotional rates may be non-refundable.'],
            ['q'=>'How do I get notified about new offers?','a'=>'Subscribe to our newsletter below to receive deal alerts directly in your inbox.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Never Miss a Deal'])
@endsection
