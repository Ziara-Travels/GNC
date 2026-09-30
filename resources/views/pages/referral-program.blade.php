@extends('layouts.main')
@section('title', 'Referral Program - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Referral Program',
    'title'  => 'Referral <em>Program</em>',
    'intro'  => 'Share the love of travel and earn rewards for every friend you refer',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Simple Steps',
            'title' => 'How It <em>Works</em>',
            'align' => 'center',
        ])
        <div class="ziara-features">
            @foreach([
                ['step'=>'1','icon'=>'fa-share-alt','title'=>'Share Your Link','text'=>'Get your unique referral link from your dashboard and share it with friends & family.'],
                ['step'=>'2','icon'=>'fa-user-plus','title'=>'Friend Signs Up','text'=>'Your friend creates an account and makes their first booking through your link.'],
                ['step'=>'3','icon'=>'fa-gift','title'=>'Both Get Rewarded','text'=>'You earn $15 credit and your friend gets $10 off their first booking!'],
            ] as $s)
            <div class="ziara-feature" style="text-align:center;position:relative;">
                <span class="ziara-feature__icon" style="margin-left:auto;margin-right:auto;"><i class="fa-solid {{ $s['icon'] }}" aria-hidden="true"></i></span>
                <span style="position:absolute;top:18px;right:18px;width:30px;height:30px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;background:var(--maroon);color:#fff;font-size:13px;font-weight:700;">{{ $s['step'] }}</span>
                <h3 class="ziara-feature__title">{{ $s['title'] }}</h3>
                <p class="ziara-feature__desc">{{ $s['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Rewards',
            'title' => 'Reward <em>Tiers</em>',
            'align' => 'center',
        ])
        <div class="ziara-panel" style="padding:0;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:var(--maroon);color:#fff;">
                        <th style="padding:16px 20px;text-align:left;font-weight:700;">Referrals</th>
                        <th style="padding:16px 20px;text-align:left;font-weight:700;">Your Reward</th>
                        <th style="padding:16px 20px;text-align:left;font-weight:700;">Bonus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['range'=>'1 – 5','reward'=>'$15 per referral','bonus'=>'—'],
                        ['range'=>'6 – 15','reward'=>'$20 per referral','bonus'=>'Free seat upgrade'],
                        ['range'=>'16 – 30','reward'=>'$25 per referral','bonus'=>'Airport lounge pass'],
                        ['range'=>'31+','reward'=>'$30 per referral','bonus'=>'VIP Silver membership'],
                    ] as $r)
                    <tr style="background:{{ $loop->even ? 'var(--gold-pale)' : '#fff' }};color:var(--ink);">
                        <td style="padding:16px 20px;font-weight:700;border-bottom:1px solid var(--line);">{{ $r['range'] }}</td>
                        <td style="padding:16px 20px;border-bottom:1px solid var(--line);">{{ $r['reward'] }}</td>
                        <td style="padding:16px 20px;color:var(--maroon);font-weight:700;border-bottom:1px solid var(--line);">{{ $r['bonus'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-panel" style="padding:0;overflow:hidden;">
            <div class="ziara-split" style="gap:0;">
                <div>
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&h=400&fit=crop" alt="" style="width:100%;height:100%;min-height:280px;object-fit:cover;" loading="lazy">
                </div>
                <div style="padding:32px;">
                    @include('partials.ziara-section-head', [
                        'sub'   => 'Start Earning',
                        'title' => 'Share Your <em>Referral Link</em>',
                        'align' => 'left',
                    ])
                    <div class="ziara-prose">
                        <p>Log in to your account to get your unique referral link. Share it via email, social media, or messaging apps.</p>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:20px;">
                        <button class="ziara-btn ziara-btn--gold" style="background:var(--gold);color:var(--maroon-deep);border-radius:50px;padding:15px 36px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;font-size:12.5px;border:0;cursor:pointer;" onclick="alert('Log in to access your referral link')"><i class="fa-solid fa-link" aria-hidden="true"></i> Get My Link</button>
                        <button class="ziara-btn" style="background:var(--maroon);color:#fff;border-radius:50px;padding:12px 22px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;font-size:12.5px;border:0;cursor:pointer;"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i> Share</button>
                        <button class="ziara-btn" style="background:#0ea5e9;color:#fff;border-radius:50px;padding:12px 22px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;font-size:12.5px;border:0;cursor:pointer;"><i class="fa-brands fa-twitter" aria-hidden="true"></i> Tweet</button>
                        <button class="ziara-btn" style="background:#25d366;color:#fff;border-radius:50px;padding:12px 22px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;font-size:12.5px;border:0;cursor:pointer;"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Questions',
            'title' => 'Referral <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'When do I receive my referral credit?','a'=>'Credits are added to your account within 24 hours after your friend completes their first booking.'],
            ['q'=>'Is there a limit on referrals?','a'=>'No limit! The more friends you refer, the higher your reward tier grows. Keep sharing and earning.'],
            ['q'=>'Can I use credits for any booking?','a'=>'Yes, referral credits can be applied to flights, hotels, or any service available on our platform.'],
            ['q'=>'What if my friend doesn\'t book right away?','a'=>'Your referral link stays active for 90 days. If your friend books within that period, you\'ll still earn the reward.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Referral Updates'])
@endsection
