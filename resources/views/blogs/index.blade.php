@extends('layouts.main')
@section('title', 'Travel Blog & Insights - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Travel Blog & Insights',
    'title'  => 'Discover Travel <em>Wisdom</em>',
    'intro'  => 'Expert tips, destination guides, and travel hacks to elevate your journey from ordinary to extraordinary',
    'extra'  => '
        <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;margin-bottom:22px;">
            <a href="#featured-posts" class="ziara-btn ziara-btn--gold"><i class="fa-solid fa-arrow-down" aria-hidden="true"></i> Browse Articles</a>
            <a href="#categories" class="ziara-btn ziara-btn--light"><i class="fa-solid fa-tags" aria-hidden="true"></i> Explore Categories</a>
        </div>
        <div style="max-width:520px;margin:0 auto;"><div style="position:relative;"><i class="fa-solid fa-magnifying-glass" aria-hidden="true" style="position:absolute;left:18px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:14px;"></i><input type="text" placeholder="Search for travel tips, destinations, or advice..." style="width:100%;padding:15px 20px 15px 46px;font-size:15px;color:#2c241f;background:#fff;border:0;border-radius:50px;outline:none;box-shadow:0 12px 30px rgba(0,0,0,.18);"></div></div>
    ',
])

{{-- ===== FEATURED POSTS ===== --}}
<section class="zt-section zt-section--white" id="featured-posts">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => "Editor's Picks",
            'title' => 'Featured <em>Articles</em>',
            'align' => 'center',
        ])
        <div class="ziara-deals">
            @foreach($blogPosts as $index => $post)
                @if($index < 6)
                <div class="ziara-deal">
                    <a href="{{ route('blog.show', $post['slug']) }}" class="ziara-deal__media" style="display:block;">
                        <img src="{{ asset('images/' . $post['image']) }}" alt="{{ $post['title'] }}" loading="lazy">
                        <span class="ziara-deal__badge">{{ $post['category'] }}</span>
                    </a>
                    <div class="ziara-deal__body">
                        <div style="display:flex;align-items:center;gap:14px;font-size:12.5px;color:var(--muted);margin-bottom:10px;">
                            <span><i class="fa-solid fa-clock" aria-hidden="true" style="margin-right:5px;color:var(--gold);"></i>{{ $post['readTime'] }}</span>
                            <span><i class="fa-solid fa-eye" aria-hidden="true" style="margin-right:5px;color:var(--gold);"></i>{{ $post['views'] }}</span>
                        </div>
                        <h3 class="ziara-deal__title"><a href="{{ route('blog.show', $post['slug']) }}" style="color:inherit;text-decoration:none;">{{ $post['title'] }}</a></h3>
                        <p class="ziara-deal__desc">{{ $post['excerpt'] }}</p>
                        <div style="display:flex;align-items:center;gap:10px;margin-top:auto;padding-top:14px;border-top:1px solid var(--line);">
                            <span style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">{{ substr($post['author'], 0, 2) }}</span>
                            <div>
                                <p style="margin:0;font-size:13px;font-weight:600;color:var(--ink);">{{ $post['author'] }}</p>
                                <p style="margin:0;font-size:12px;color:var(--muted);">{{ $post['date'] }}</p>
                            </div>
                            <a href="{{ route('blog.show', $post['slug']) }}" class="ziara-deal__btn" style="margin-left:auto;margin-top:0;">Read More</a>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
<section class="zt-section zt-section--pale" id="categories">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Explore Topics',
            'title' => 'Browse By <em>Category</em>',
            'desc'  => 'Discover articles curated by topic to find exactly what you\'re looking for',
            'align' => 'center',
        ])
        @php $categories = [
            ['icon'=>'fa-solid fa-plane','title'=>'Flight Tips','count'=>'5 articles'],
            ['icon'=>'fa-solid fa-shield-halved','title'=>'Travel Safety','count'=>'3 articles'],
            ['icon'=>'fa-solid fa-briefcase','title'=>'Packing Tips','count'=>'4 articles'],
            ['icon'=>'fa-solid fa-money-bill-wave','title'=>'Budget Tips','count'=>'6 articles'],
        ]; @endphp
        <div class="ziara-minifeatures">
            @foreach($categories as $c)
            <a href="#" class="ziara-minifeature" style="text-decoration:none;color:inherit;display:block;">
                <span class="ziara-minifeature__icon"><i class="{{ $c['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $c['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $c['count'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== ALL ARTICLES ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Latest Insights',
            'title' => 'All <em>Articles</em>',
            'align' => 'center',
        ])
        <div style="display:flex;justify-content:center;align-items:center;gap:10px;margin-bottom:30px;">
            <span style="font-size:13.5px;color:var(--muted);">Sort by:</span>
            <select style="padding:10px 16px;font-size:13.5px;color:var(--ink);background:#fff;border:1px solid var(--line);border-radius:50px;outline:none;">
                <option>Most Recent</option>
                <option>Most Popular</option>
                <option>Trending</option>
            </select>
        </div>
        <div class="ziara-deals">
            @foreach($blogPosts as $post)
            <div class="ziara-deal">
                <a href="{{ route('blog.show', $post['slug']) }}" class="ziara-deal__media" style="display:block;">
                    <img src="{{ asset('images/' . $post['image']) }}" alt="{{ $post['title'] }}" loading="lazy">
                    <span class="ziara-deal__badge">{{ $post['category'] }}</span>
                </a>
                <div class="ziara-deal__body">
                    <div style="display:flex;align-items:center;gap:14px;font-size:12.5px;color:var(--muted);margin-bottom:10px;">
                        <span><i class="fa-solid fa-clock" aria-hidden="true" style="margin-right:5px;color:var(--gold);"></i>{{ $post['readTime'] }}</span>
                        <span><i class="fa-solid fa-eye" aria-hidden="true" style="margin-right:5px;color:var(--gold);"></i>{{ $post['views'] }}</span>
                    </div>
                    <h3 class="ziara-deal__title"><a href="{{ route('blog.show', $post['slug']) }}" style="color:inherit;text-decoration:none;">{{ $post['title'] }}</a></h3>
                    <p class="ziara-deal__desc">{{ $post['excerpt'] }}</p>
                    <div style="display:flex;align-items:center;gap:10px;margin-top:auto;padding-top:14px;border-top:1px solid var(--line);">
                        <span style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">{{ substr($post['author'], 0, 2) }}</span>
                        <div>
                            <p style="margin:0;font-size:13px;font-weight:600;color:var(--ink);">{{ $post['author'] }}</p>
                            <p style="margin:0;font-size:12px;color:var(--muted);">{{ $post['date'] }}</p>
                        </div>
                        <a href="{{ route('blog.show', $post['slug']) }}" class="ziara-deal__btn" style="margin-left:auto;margin-top:0;">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top:44px;display:flex;justify-content:center;gap:8px;">
            <a href="#" style="padding:10px 18px;font-size:13px;color:var(--muted);background:#fff;border:1px solid var(--line);border-radius:10px 0 0 10px;text-decoration:none;">Previous</a>
            <a href="#" style="padding:10px 18px;font-size:13px;font-weight:700;color:#fff;background:var(--maroon);border-radius:0;text-decoration:none;">1</a>
            <a href="#" style="padding:10px 18px;font-size:13px;color:var(--muted);background:#fff;border:1px solid var(--line);text-decoration:none;">2</a>
            <a href="#" style="padding:10px 18px;font-size:13px;color:var(--muted);background:#fff;border:1px solid var(--line);text-decoration:none;">3</a>
            <span style="padding:10px 12px;font-size:13px;color:var(--muted);background:#fff;border:1px solid var(--line);">...</span>
            <a href="#" style="padding:10px 18px;font-size:13px;color:var(--muted);background:#fff;border:1px solid var(--line);text-decoration:none;">8</a>
            <a href="#" style="padding:10px 18px;font-size:13px;color:var(--muted);background:#fff;border:1px solid var(--line);text-decoration:none;">9</a>
            <a href="#" style="padding:10px 18px;font-size:13px;color:var(--muted);background:#fff;border:1px solid var(--line);border-radius:0 10px 10px 0;text-decoration:none;">Next</a>
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER (functional subscribe) ===== --}}
<section class="zt-section ziara-cta-band" style="padding:72px 0;">
    <div class="container">
        <div class="gsc-heading gsc-heading--light align-center style-1 widget auto-responsive" style="margin-bottom:30px;">
            <div class="content-inner">
                <div class="sub-title"><span>Newsletter</span></div>
                <h2 class="title"><span>Get Travel Tips in Your Inbox</span></h2>
                <div class="title-desc" style="margin-left:auto;margin-right:auto;max-width:560px;">Join our newsletter and receive expert travel advice, destination guides, and exclusive deals</div>
            </div>
        </div>
        <form class="newsletter-form" id="newsletterForm" style="max-width:540px;margin:0 auto;">
            @csrf
            <div style="display:flex;flex-direction:column;gap:10px;">
                <input type="text" name="name" id="newsletterName" placeholder="Your name" required style="padding:15px 20px;font-size:15px;color:#2c241f;background:#fff;border:2px solid transparent;border-radius:12px;outline:none;">
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <input type="email" name="email" id="newsletterEmail" placeholder="Your email address" required style="flex:1;min-width:220px;padding:15px 20px;font-size:15px;color:#2c241f;background:#fff;border:2px solid transparent;border-radius:12px;outline:none;">
                    <button type="submit" class="ziara-btn ziara-btn--gold newsletter-submit-btn">Subscribe</button>
                </div>
            </div>
            <p style="font-size:12.5px;color:rgba(255,255,255,.75);margin:14px 0 0;text-align:center;">We respect your privacy. Unsubscribe at any time.</p>
        </form>
    </div>
</section>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newsletterForm = document.getElementById('newsletterForm');

        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitButton = document.querySelector('.newsletter-submit-btn');
                submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Subscribing...';
                submitButton.disabled = true;

                const name = document.getElementById('newsletterName').value;
                const email = document.getElementById('newsletterEmail').value;

                fetch('{{ route("newsletter.subscribe") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ name: name, email: email })
                })
                .then(response => response.json())
                .then(data => {
                    submitButton.innerHTML = 'Subscribe';
                    submitButton.disabled = false;

                    if (data.success) {
                        Toastify({ text: data.message, duration: 5000, gravity: "top", position: "right", backgroundColor: "#10B981", stopOnFocus: true, onClick: function(){} }).showToast();
                        newsletterForm.reset();
                    } else {
                        Toastify({ text: data.message || 'Something went wrong. Please try again.', duration: 5000, gravity: "top", position: "right", backgroundColor: "#EF4444", stopOnFocus: true, onClick: function(){} }).showToast();
                    }
                })
                .catch(error => {
                    submitButton.innerHTML = 'Subscribe';
                    submitButton.disabled = false;
                    Toastify({ text: 'Network error. Please try again later.', duration: 5000, gravity: "top", position: "right", backgroundColor: "#EF4444", stopOnFocus: true, onClick: function(){} }).showToast();
                    console.error('Newsletter subscription error:', error);
                });
            });
        }
    });
</script>
@endsection