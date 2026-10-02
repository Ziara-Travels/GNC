@extends('layouts.main')
@section('title', 'Gallery - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Gallery',
    'title'  => 'Our <em>Gallery</em>',
    'intro'  => 'Moments captured from our journeys across East Africa and beyond',
])

{{-- ===== FILTER TABS ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-tabs" role="tablist" aria-label="Gallery categories">
            <button class="ziara-tab is-active" role="tab" aria-selected="true" data-tab="all">All</button>
            <button class="ziara-tab" role="tab" aria-selected="false" data-tab="safaris">Safaris</button>
            <button class="ziara-tab" role="tab" aria-selected="false" data-tab="destinations">Destinations</button>
            <button class="ziara-tab" role="tab" aria-selected="false" data-tab="experiences">Experiences</button>
            <button class="ziara-tab" role="tab" aria-selected="false" data-tab="team">Team</button>
        </div>
    </div>
</section>

{{-- ===== GALLERY GRID ===== --}}
<section class="zt-section zt-section--pale" style="padding-top:24px;">
    <div class="container">
        <div class="ziara-gallery-grid" id="gallery-grid" role="list">
            @php
                $images = [
                    ['tab'=>'safaris','src'=>'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=800&h=600&fit=crop','alt'=>'Maasai Mara sunrise game drive','title'=>'Maasai Mara','desc'=>'Golden hour over the savanna'],
                    ['tab'=>'safaris','src'=>'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=800&h=600&fit=crop','alt'=>'Elephant herd in Amboseli','title'=>'Amboseli Elephants','desc'=>'Herd at the foot of Kilimanjaro'],
                    ['tab'=>'safaris','src'=>'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=800&h=600&fit=crop','alt'=>'Lion pride in Ol Pejeta','title'=>'Ol Pejeta Lions','desc'=>'Conservancy lion pride'],
                    ['tab'=>'destinations','src'=>'https://images.unsplash.com/photo-1539367628448-4bc5c9d171c8?w=800&h=600&fit=crop','alt'=>'Zanzibar beach at sunset','title'=>'Zanzibar Beach','desc'=>'White sands and turquoise waters'],
                    ['tab'=>'destinations','src'=>'https://images.unsplash.com/photo-1500673922987-e212871fec22?w=800&h=600&fit=crop','alt'=>'Dubai skyline at night','title'=>'Dubai Marina','desc'=>'Iconic skyline reflections'],
                    ['tab'=>'destinations','src'=>'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&h=600&fit=crop','alt'=>'Cape Town Table Mountain','title'=>'Cape Town','desc'=>'Table Mountain backdrop'],
                    ['tab'=>'destinations','src'=>'https://images.unsplash.com/photo-1528181304800-259b08848526?w=800&h=600&fit=crop','alt'=>'Bangkok temples and canals','title'=>'Bangkok Temples','desc'=>'Grand Palace architecture'],
                    ['tab'=>'experiences','src'=>'https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800&h=600&fit=crop','alt'=>'Hot air balloon over Maasai Mara','title'=>'Balloon Safari','desc'=>'Sunrise balloon ride'],
                    ['tab'=>'experiences','src'=>'https://images.unsplash.com/photo-1472396961693-142e6e269027?w=800&h=600&fit=crop','alt'=>'Bush dinner under the stars','title'=>'Bush Dinner','desc'=>'Al fresco dining in the wild'],
                    ['tab'=>'experiences','src'=>'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=800&h=600&fit=crop','alt'=>'Cultural visit to Maasai village','title'=>'Maasai Culture','desc'=>'Traditional village experience'],
                    ['tab'=>'team','src'=>'https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=800&h=600&fit=crop','alt'=>'Ziara Travels team at work','title'=>'Our Team','desc'=>'Behind the scenes at our Nairobi office'],
                    ['tab'=>'team','src'=>'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=800&h=600&fit=crop','alt'=>'Team planning trip itineraries','title'=>'Trip Planning','desc'=>'Crafting bespoke journeys'],
                ];
            @endphp
            @foreach($images as $img)
                <article class="ziara-gallery__item" role="listitem" data-tab="{{ $img['tab'] }}">
                    <a href="{{ $img['src'] }}" class="ziara-gallery__link" data-fancybox="gallery" data-caption="{{ $img['title'] }}: {{ $img['desc'] }}">
                        <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}" loading="lazy">
                        <div class="ziara-gallery__overlay">
                            <span class="ziara-gallery__cat">{{ ucfirst($img['tab']) }}</span>
                            <h3 class="ziara-gallery__title">{{ $img['title'] }}</h3>
                            <p class="ziara-gallery__desc">{{ $img['desc'] }}</p>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="zt-section zt-section--dark">
    <div class="container">
        <div class="ziara-split" style="align-items:center;">
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Plan Your Journey',
                    'title' => 'Create Your Own <em>Memories</em>',
                    'align' => 'left',
                    'light' => true,
                ])
                <div class="ziara-prose" style="margin-top:-24px;">
                    <p>Every image in our gallery represents a real journey we've crafted. Ready to add your own chapter?</p>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px;">
                    <a href="{{ route('tours.index') }}" class="ziara-btn ziara-btn--gold">Explore Tours</a>
                    <a href="{{ route('contactus') }}" class="ziara-btn ziara-btn--outline">Custom Trip</a>
                </div>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="Travel planning" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
        </div>
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Gallery Updates'])

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabs = document.querySelectorAll('.ziara-tab');
    var items = document.querySelectorAll('.ziara-gallery__item');
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var filter = this.dataset.tab;
            tabs.forEach(function (t) {
                t.classList.remove('is-active');
                t.setAttribute('aria-selected', 'false');
            });
            this.classList.add('is-active');
            this.setAttribute('aria-selected', 'true');
            items.forEach(function (item) {
                var match = filter === 'all' || item.dataset.tab === filter;
                item.style.display = match ? '' : 'none';
            });
        });
    });
});
</script>
<style>
.ziara-tabs{display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-bottom:24px;}
.ziara-tab{padding:10px 24px;border:2px solid var(--gold);background:transparent;color:var(--maroon);font-weight:700;border-radius:50px;cursor:pointer;transition:.2s;}
.ziara-tab:hover,.ziara-tab.is-active{background:var(--gold);color:#fff;}
.ziara-gallery-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;}
.ziara-gallery__item{position:relative;border-radius:16px;overflow:hidden;aspect-ratio:4/3;}
.ziara-gallery__link{display:block;width:100%;height:100%;}
.ziara-gallery__link img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease;}
.ziara-gallery__link:hover img{transform:scale(1.06);}
.ziara-gallery__overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(10,5,3,0) 40%,rgba(10,5,3,.92) 100%);display:flex;flex-direction:column;justify-content:flex-end;padding:20px;color:#fff;opacity:0;transition:opacity .3s;}
.ziara-gallery__link:hover .ziara-gallery__overlay{opacity:1;}
.ziara-gallery__cat{font-size:11px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:var(--gold);}
.ziara-gallery__title{font-family:'Cormorant Garamond',Georgia,serif;font-size:1.25rem;margin:6px 0 2px;}
.ziara-gallery__desc{font-size:.85rem;opacity:.9;margin:0;}
@media (max-width:768px){.ziara-gallery-grid{grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}}
</style>
@endpush
@endsection