@extends('layouts.main')
@section('title', $post['title'] . ' - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => $post['category'],
    'title'  => $post['title'],
    'intro'  => $post['author'] . ' &nbsp;•&nbsp; ' . $post['date'] . ' &nbsp;•&nbsp; ' . $post['readTime'],
    'crumbs' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Blog', 'url' => route('blog.index')],
        ['label' => $post['category'], 'url' => route('blog.index')],
    ],
])

{{-- ===== FEATURED IMAGE ===== --}}
<section class="zt-section zt-section--white" style="padding:56px 0 0;">
    <div class="container">
        <div style="max-width:900px;margin:0 auto;border-radius:22px;overflow:hidden;box-shadow:0 30px 60px rgba(30,12,8,.18);">
            <img src="{{ asset('images/' . $post['image']) }}" alt="{{ $post['title'] }}" style="width:100%;display:block;object-fit:cover;">
        </div>
    </div>
</section>

{{-- ===== ARTICLE BODY ===== --}}
<section class="zt-section zt-section--white" style="padding:40px 0 72px;">
    <div class="container">
        <div style="max-width:820px;margin:0 auto;">

            {{-- Share bar --}}
            <div style="display:flex;justify-content:flex-end;margin-bottom:34px;">
                <div id="shareBar" style="display:flex;gap:8px;">
                    <button type="button" class="ziara-share-btn" style="width:40px;height:40px;border:0;border-radius:50%;background:#1877F2;color:#fff;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Share on Facebook"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></button>
                    <button type="button" class="ziara-share-btn" style="width:40px;height:40px;border:0;border-radius:50%;background:#1da1f2;color:#fff;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Share on Twitter"><i class="fa-brands fa-twitter" aria-hidden="true"></i></button>
                    <button type="button" class="ziara-share-btn" style="width:40px;height:40px;border:0;border-radius:50%;background:#0a66c2;color:#fff;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Share on LinkedIn"><i class="fa-brands fa-linkedin-in" aria-hidden="true"></i></button>
                    <button type="button" class="ziara-share-btn" style="width:40px;height:40px;border:0;border-radius:50%;background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);color:#fff;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;" title="Share on Instagram"><i class="fa-brands fa-instagram" aria-hidden="true"></i></button>
                </div>
            </div>

            {{-- Article content --}}
            <div class="ziara-prose" style="max-width:none;">
                {!! $post['content'] !!}
            </div>

            {{-- Author box --}}
            <div class="ziara-panel" style="display:flex;flex-direction:column;align-items:center;gap:16px;margin-top:44px;padding:30px;">
                <span style="width:76px;height:76px;border-radius:50%;background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;">{{ substr($post['author'], 0, 2) }}</span>
                <div style="text-align:center;">
                    <h3 style="margin:0 0 4px;font-family:"Cormorant Garamond",Georgia,serif;font-size:24px;color:var(--maroon-deep);">{{ $post['author'] }}</h3>
                    <p style="margin:0 0 12px;font-size:12px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:var(--gold-dim);">Travel Writer &amp; Destination Expert</p>
                    <p style="margin:0;font-size:14.5px;color:var(--muted);line-height:1.7;">A passionate traveler who has explored over 50 countries across 6 continents. Specializes in adventure travel, cultural experiences, and finding the best local food spots that tourists often miss.</p>
                    <div style="display:flex;justify-content:center;gap:14px;margin-top:16px;">
                        <a href="#" style="color:var(--maroon);font-size:18px;"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                        <a href="#" style="color:var(--maroon);font-size:18px;"><i class="fa-brands fa-twitter" aria-hidden="true"></i></a>
                        <a href="#" style="color:var(--maroon);font-size:18px;"><i class="fa-brands fa-linkedin-in" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            <div style="margin-top:28px;display:flex;flex-wrap:wrap;gap:8px;">
                <span style="padding:8px 16px;background:var(--gold-pale);border-radius:50px;font-size:13px;color:var(--maroon);">Travel Tips</span>
                <span style="padding:8px 16px;background:var(--gold-pale);border-radius:50px;font-size:13px;color:var(--maroon);">{{ $post['category'] }}</span>
                <span style="padding:8px 16px;background:var(--gold-pale);border-radius:50px;font-size:13px;color:var(--maroon);">Destinations</span>
                <span style="padding:8px 16px;background:var(--gold-pale);border-radius:50px;font-size:13px;color:var(--maroon);">Adventure</span>
            </div>

            {{-- Comments --}}
            <div style="margin-top:44px;border-top:1px solid var(--line);padding-top:36px;">
                <h3 style="margin:0 0 26px;font-family:"Cormorant Garamond",Georgia,serif;font-size:28px;color:var(--maroon-deep);">Comments (8)</h3>

                {{-- Comment form --}}
                <div style="margin-bottom:34px;">
                    <div style="display:flex;gap:14px;">
                        <span style="width:42px;height:42px;border-radius:50%;background:var(--gold-pale);flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;"><i class="fa-solid fa-user" aria-hidden="true" style="color:var(--maroon);"></i></span>
                        <div style="flex:1;">
                            <textarea rows="3" placeholder="Share your thoughts..." style="width:100%;padding:14px;font-size:14.5px;color:var(--ink);background:#fff;border:1px solid var(--line);border-radius:12px;outline:none;box-sizing:border-box;"></textarea>
                            <div style="margin-top:12px;display:flex;justify-content:flex-end;">
                                <button type="button" class="ziara-btn ziara-btn--maroon" style="padding:12px 26px;">Post Comment</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Comment 1 --}}
                <div style="display:flex;gap:14px;margin-bottom:28px;">
                    <span style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#8b5e83,#d46a6a);flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;">JD</span>
                    <div style="flex:1;background:#faf6f2;border:1px solid var(--line);border-radius:14px;padding:18px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                            <h4 style="margin:0;font-size:15px;font-weight:700;color:var(--ink);">John Doe</h4>
                            <span style="font-size:12.5px;color:var(--muted);">2 days ago</span>
                        </div>
                        <p style="margin:0;font-size:14px;color:var(--muted);line-height:1.7;">This article is incredibly helpful! I've been planning my trip to Southeast Asia and these insights are exactly what I needed. Particularly appreciated the tips about optimal booking windows.</p>
                        <div style="margin-top:12px;display:flex;align-items:center;gap:18px;">
                            <button type="button" style="font-size:13px;color:var(--muted);background:none;border:0;cursor:pointer;"><i class="fa-solid fa-thumbs-up" aria-hidden="true" style="margin-right:5px;color:var(--gold);"></i>12 Likes</button>
                            <button type="button" style="font-size:13px;color:var(--muted);background:none;border:0;cursor:pointer;"><i class="fa-solid fa-reply" aria-hidden="true" style="margin-right:5px;"></i>Reply</button>
                        </div>
                        {{-- Nested reply --}}
                        <div style="margin:16px 0 0 24px;">
                            <div style="display:flex;gap:12px;">
                                <span style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--maroon),var(--maroon-deep));flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700;">SM</span>
                                <div style="flex:1;background:#fff;border:1px solid var(--line);border-radius:12px;padding:14px;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                        <h4 style="margin:0;font-size:14px;font-weight:700;color:var(--ink);">Sarah Mitchell</h4>
                                        <span style="font-size:12px;color:var(--muted);">1 day ago</span>
                                    </div>
                                    <p style="margin:0;font-size:13.5px;color:var(--muted);line-height:1.65;">Glad you found it helpful, John! Let me know if you have any specific questions about Southeast Asia - I've spent several months traveling through the region.</p>
                                    <div style="margin-top:10px;display:flex;align-items:center;gap:16px;">
                                        <button type="button" style="font-size:12.5px;color:var(--muted);background:none;border:0;cursor:pointer;"><i class="fa-solid fa-thumbs-up" aria-hidden="true" style="margin-right:4px;color:var(--gold);"></i>5 Likes</button>
                                        <button type="button" style="font-size:12.5px;color:var(--muted);background:none;border:0;cursor:pointer;"><i class="fa-solid fa-reply" aria-hidden="true" style="margin-right:4px;"></i>Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Comment 2 --}}
                <div style="display:flex;gap:14px;">
                    <span style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#3f8f6d,#50c2a0);flex-shrink:0;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;">AS</span>
                    <div style="flex:1;background:#faf6f2;border:1px solid var(--line);border-radius:14px;padding:18px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                            <h4 style="margin:0;font-size:15px;font-weight:700;color:var(--ink);">Alice Smith</h4>
                            <span style="font-size:12.5px;color:var(--muted);">3 days ago</span>
                        </div>
                        <p style="margin:0;font-size:14px;color:var(--muted);line-height:1.7;">Great insights! I especially loved the section on cultural experiences. It's so important to immerse yourself in local traditions when traveling.</p>
                        <div style="margin-top:12px;display:flex;align-items:center;gap:18px;">
                            <button type="button" style="font-size:13px;color:var(--muted);background:none;border:0;cursor:pointer;"><i class="fa-solid fa-thumbs-up" aria-hidden="true" style="margin-right:5px;color:var(--gold);"></i>8 Likes</button>
                            <button type="button" style="font-size:13px;color:var(--muted);background:none;border:0;cursor:pointer;"><i class="fa-solid fa-reply" aria-hidden="true" style="margin-right:5px;"></i>Reply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== RELATED ARTICLES ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Keep Reading',
            'title' => 'Related <em>Articles</em>',
            'align' => 'center',
        ])
        <div class="ziara-deals">
            @forelse($relatedPosts as $related)
            <div class="ziara-deal">
                <a href="{{ route('blog.show', Str::slug($related['title'])) }}" class="ziara-deal__media" style="display:block;">
                    <img src="{{ asset('images/' . $related['image']) }}" alt="{{ $related['title'] }}" loading="lazy"><span class="ziara-deal__badge">{{ $related['category'] }}</span>
                </a>
                <div class="ziara-deal__body">
                    <h3 class="ziara-deal__title">{{ $related['title'] }}</h3>
                    <p class="ziara-deal__desc">{{ $related['excerpt'] }}</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:14px;border-top:1px solid var(--line);">
                        <span style="font-size:13px;color:var(--muted);"><i class="fa-solid fa-clock" aria-hidden="true" style="margin-right:4px;color:var(--gold);"></i>{{ $related['readTime'] }}</span>
                        <a href="{{ route('blog.show', Str::slug($related['title'])) }}" class="ziara-deal__btn" style="margin-top:0;">Read More</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="ziara-panel" style="text-align:center;padding:26px;grid-column:1/-1;">
                <p style="margin:0;color:var(--muted);">No related articles found.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER (functional subscribe) ===== --}}
<section class="zt-section ziara-cta-band" style="padding:72px 0;">
    <div class="container">
        <div class="gsc-heading gsc-heading--light align-center style-1 widget auto-responsive" style="margin-bottom:30px;">
            <div class="content-inner">
                <div class="sub-title"><span>Newsletter</span></div>
                <h2 class="title"><span>Subscribe to Our Newsletter</span></h2>
                <div class="title-desc" style="margin-left:auto;margin-right:auto;max-width:580px;">Stay updated with the latest travel tips, exclusive deals, and destination insights delivered straight to your inbox.</div>
            </div>
        </div>
        <form id="newsletterForm" style="max-width:560px;margin:0 auto;display:flex;flex-direction:column;gap:14px;">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <input type="text" id="newsletterName" name="name" placeholder="Your Name" required style="padding:15px 20px;font-size:15px;color:#2c241f;background:#fff;border:2px solid transparent;border-radius:12px;outline:none;">
                <input type="email" id="newsletterEmail" name="email" placeholder="Your Email Address" required style="padding:15px 20px;font-size:15px;color:#2c241f;background:#fff;border:2px solid transparent;border-radius:12px;outline:none;">
            </div>
            <div style="display:flex;justify-content:center;gap:22px;flex-wrap:wrap;">
                <label style="display:inline-flex;align-items:center;gap:8px;font-size:13.5px;color:rgba(255,255,255,.9);cursor:pointer;"><input type="checkbox" id="weeklyEmails" name="weekly_emails" checked style="width:16px;height:16px;accent-color:var(--gold);"> Weekly Updates</label>
                <label style="display:inline-flex;align-items:center;gap:8px;font-size:13.5px;color:rgba(255,255,255,.9);cursor:pointer;"><input type="checkbox" id="monthlyEmails" name="monthly_emails" checked style="width:16px;height:16px;accent-color:var(--gold);"> Monthly Digest</label>
                <label style="display:inline-flex;align-items:center;gap:8px;font-size:13.5px;color:rgba(255,255,255,.9);cursor:pointer;"><input type="checkbox" id="promotionalEmails" name="promotional_emails" checked style="width:16px;height:16px;accent-color:var(--gold);"> Exclusive Offers</label>
            </div>
            <div style="text-align:center;">
                <button type="submit" class="ziara-btn ziara-btn--light" style="min-width:220px;"><i class="fa-solid fa-envelope-open-text" aria-hidden="true"></i> Subscribe Now</button>
            </div>
            <p style="font-size:12px;color:rgba(255,255,255,.7);margin:0;text-align:center;">By subscribing, you agree to receive marketing communications from us and confirm that you have read our <a href="#" style="text-decoration:underline;color:var(--gold-bright);">Privacy Policy</a>.</p>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newsletterForm = document.getElementById('newsletterForm');

        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitButton = newsletterForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
                submitButton.disabled = true;

                const name = document.getElementById('newsletterName').value;
                const email = document.getElementById('newsletterEmail').value;
                const weeklyEmails = document.getElementById('weeklyEmails').checked;
                const monthlyEmails = document.getElementById('monthlyEmails').checked;
                const promotionalEmails = document.getElementById('promotionalEmails').checked;

                fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        weekly_emails: weeklyEmails,
                        monthly_emails: monthlyEmails,
                        promotional_emails: promotionalEmails,
                        source: 'blog'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;

                    if (data.success) {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({ text: data.message, duration: 5000, gravity: "top", position: "center", className: "info", style: { background: "linear-gradient(to right, #10B981, #059669)", borderRadius: "8px", boxShadow: "0 3px 6px rgba(0,0,0,0.16)" } }).showToast();
                        } else {
                            alert(data.message);
                        }
                        newsletterForm.reset();
                        document.getElementById('weeklyEmails').checked = true;
                        document.getElementById('monthlyEmails').checked = true;
                        document.getElementById('promotionalEmails').checked = true;
                    } else {
                        if (typeof Toastify !== 'undefined') {
                            Toastify({ text: data.message || 'Something went wrong. Please try again.', duration: 5000, gravity: "top", position: "center", className: "info", style: { background: "linear-gradient(to right, #EF4444, #DC2626)", borderRadius: "8px", boxShadow: "0 3px 6px rgba(0,0,0,0.16)" } }).showToast();
                        } else {
                            alert(data.message || 'Something went wrong. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    if (typeof Toastify !== 'undefined') {
                        Toastify({ text: 'Network error. Please try again later.', duration: 5000, gravity: "top", position: "center", className: "info", style: { background: "linear-gradient(to right, #EF4444, #DC2626)", borderRadius: "8px", boxShadow: "0 3px 6px rgba(0,0,0,0.16)" } }).showToast();
                    } else {
                        alert('Network error. Please try again later.');
                    }
                    console.error('Newsletter subscription error:', error);
                });
            });
        }

        const shareBtns = document.querySelectorAll('#shareBar .ziara-share-btn');
        if (shareBtns.length) {
            shareBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const pageTitle = document.title;
                    const pageUrl = window.location.href;

                    let shareUrl = '';

                    switch(index) {
                        case 0:
                            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`;
                            break;
                        case 1:
                            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent(pageTitle)}`;
                            break;
                        case 2:
                            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`;
                            break;
                        case 3:
                            alert('Instagram sharing is not available through direct links. Please copy the URL and share it manually.');
                            return;
                    }

                    window.open(shareUrl, '_blank', 'width=600,height=400');
                });
            });
        }
    });

    if (typeof Toastify === 'undefined') {
        const toastifyCSS = document.createElement('link');
        toastifyCSS.rel = 'stylesheet';
        toastifyCSS.href = 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css';
        document.head.appendChild(toastifyCSS);

        const toastifyScript = document.createElement('script');
        toastifyScript.src = 'https://cdn.jsdelivr.net/npm/toastify-js';
        document.body.appendChild(toastifyScript);
    }
</script>
@endpush