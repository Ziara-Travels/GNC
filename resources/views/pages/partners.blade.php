@extends('layouts.main')
@section('title', 'Our Partners - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Our Partners',
    'title'  => 'Trusted <em>Partners</em>',
    'intro'  => 'We collaborate with the best in the industry to deliver exceptional travel experiences',
])

{{-- ===== AIRLINE PARTNERS ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Airlines',
            'title' => 'Airline <em>Partners</em>',
            'align' => 'center',
        ])
        @php $airlines = [
            ['name'=>'Kenya Airways','logo'=>'https://upload.wikimedia.org/wikipedia/en/thumb/2/2e/Kenya_Airways_Logo.svg/1200px-Kenya_Airways_Logo.svg.png','desc'=>'Pride of Africa - Kenya\'s flag carrier'],
            ['name'=>'Ethiopian Airlines','logo'=>'https://upload.wikimedia.org/wikipedia/en/thumb/6/65/Ethiopian_Airlines_Logo.svg/1200px-Ethiopian_Airlines_Logo.svg.png','desc'=>'Africa\'s largest airline network'],
            ['name'=>'RwandAir','logo'=>'https://upload.wikimedia.org/wikipedia/en/thumb/4/46/RwandAir_Logo.svg/1200px-RwandAir_Logo.svg.png','desc'=>'Connecting Rwanda to the world'],
            ['name'=>'Precision Air','logo'=>'https://upload.wikimedia.org/wikipedia/en/thumb/7/7f/Precision_Air_Logo.svg/1200px-Precision_Air_Logo.svg.png','desc'=>'Tanzania\'s regional connector'],
            ['name'=>'Air Uganda','logo'=>'https://upload.wikimedia.org/wikipedia/en/thumb/1/1c/Air_Uganda_Logo.svg/1200px-Air_Uganda_Logo.svg.png','desc'=>'Uganda\'s scheduled services'],
            ['name'=>'Fly540','logo'=>'https://upload.wikimedia.org/wikipedia/en/thumb/4/48/Fly540_Logo.svg/1200px-Fly540_Logo.svg.png','desc'=>'Low-cost East African carrier'],
        ]; @endphp
        <div class="ziara-partner-grid">
            @foreach($airlines as $a)
                <div class="ziara-partner__card">
                    <div class="ziara-partner__logo">
                        <img src="{{ $a['logo'] }}" alt="{{ $a['name'] }}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <span style="display:none;font-weight:700;color:var(--maroon);">{{ $a['name'] }}</span>
                    </div>
                    <p class="ziara-partner__desc">{{ $a['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== HOSPITALITY PARTNERS ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Hospitality',
            'title' => 'Hotel & Lodge <em>Partners</em>',
            'align' => 'center',
        ])
        @php $lodges = [
            ['name'=>'Mara Serena Safari Lodge','loc'=>'Masai Mara, Kenya','type'=>'Luxury Safari Lodge'],
            ['name'=>'Four Seasons Safari Lodge','loc'=>'Serengeti, Tanzania','type'=>'Ultra-Luxury Lodge'],
            ['name'=>'Angama Mara','loc'=>'Masai Mara, Kenya','type'=>'Boutique Safari Lodge'],
            ['name'=>'&Beyond Klein\'s Camp','loc'=>'Serengeti, Tanzania','type'=>'Exclusive Conservancy Lodge'],
            ['name'=>'Sanctuary Olonana','loc'=>'Masai Mara, Kenya','type'=>'Luxury Tented Camp'],
            ['name'=>'Singita Sasakwa','loc'=>'Grumeti, Tanzania','type'=>'Private Reserve Lodge'],
            ['name'=>'Giraffe Manor','loc'=>'Nairobi, Kenya','type'=>'Iconic Boutique Hotel'],
            ['name'=>'The Residence Zanzibar','loc'=>'Zanzibar, Tanzania','type'=>'Beachfront Resort'],
        ]; @endphp
        <div class="ziara-partner-grid ziara-partner-grid--list">
            @foreach($lodges as $l)
                <div class="ziara-partner__card ziara-partner__card--list">
                    <div class="ziara-partner__info">
                        <h3 class="ziara-partner__name">{{ $l['name'] }}</h3>
                        <p class="ziara-partner__loc"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $l['loc'] }}</p>
                        <span class="ziara-partner__type">{{ $l['type'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== TECHNOLOGY & PAYMENT ===== --}}
<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Technology',
            'title' => 'Technology & Payment <em>Partners</em>',
            'align' => 'center',
        ])
        @php $tech = [
            ['name'=>'Amadeus','desc'=>'Global Distribution System','icon'=>'fa-solid fa-plane-departure'],
            ['name'=>'Sabre','desc'=>'Travel Technology Platform','icon'=>'fa-solid fa-microchip'],
            ['name'=>'M-Pesa','desc'=>'Mobile Money Payments','icon'=>'fa-solid fa-mobile-screen'],
            ['name'=>'Equity Bank','desc'=>'Banking Partner','icon'=>'fa-solid fa-university'],
            ['name'=>'Jenga','desc'=>'API Banking Integration','icon'=>'fa-solid fa-plug'],
            ['name'=>'Pesapal','desc'=>'Payment Gateway','icon'=>'fa-solid fa-credit-card'],
            ['name'=>'Cloudflare','desc'=>'Security & Performance','icon'=>'fa-solid fa-shield-halved'],
            ['name'=>'AWS','desc'=>'Cloud Infrastructure','icon'=>'fa-solid fa-cloud'],
        ]; @endphp
        <div class="ziara-partner-grid">
            @foreach($tech as $t)
                <div class="ziara-partner__card">
                    <div class="ziara-partner__logo">
                        <i class="{{ $t['icon'] }}" aria-hidden="true" style="font-size:2.5rem;color:var(--maroon);"></i>
                    </div>
                    <h3 class="ziara-partner__name">{{ $t['name'] }}</h3>
                    <p class="ziara-partner__desc">{{ $t['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CERTIFICATIONS ===== --}}
<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Certifications',
            'title' => 'Industry <em>Certifications</em>',
            'align' => 'center',
        ])
        @php $certs = [
            ['name'=>'IATA Accredited Agent','year'=>'2023','icon'=>'fa-solid fa-certificate'],
            ['name'=>'KATO Member','year'=>'2022','icon'=>'fa-solid fa-badge'],
            ['name'=>'ATTA Member','year'=>'2023','icon'=>'fa-solid fa-globe-africa'],
            ['name'=>'ISO 9001:2015','year'=>'2024','icon'=>'fa-solid fa-award'],
            ['name'=>'GDPR Compliant','year'=>'2023','icon'=>'fa-solid fa-lock'],
            ['name'=>'PCI DSS Level 1','year'=>'2024','icon'=>'fa-solid fa-shield'],
        ]; @endphp
        <div class="ziara-partner-grid ziara-partner-grid--certs">
            @foreach($certs as $c)
                <div class="ziara-partner__card ziara-partner__cert">
                    <div class="ziara-partner__logo">
                        <i class="{{ $c['icon'] }}" aria-hidden="true" style="font-size:2rem;color:var(--gold);"></i>
                    </div>
                    <h3 class="ziara-partner__name">{{ $c['name'] }}</h3>
                    <p class="ziara-partner__desc">Certified {{ $c['year'] }}</p>
                </div>
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
                    'sub'   => 'Partnership',
                    'title' => 'Want to <em>Partner With Us</em>',
                    'align' => 'left',
                    'light' => true,
                ])
                <div class="ziara-prose" style="margin-top:-24px;">
                    <p>We're always open to new partnerships that enhance our travelers' experience. Airlines, hotels, tech providers, and local operators — let's talk.</p>
                </div>
                <a href="{{ route('contactus') }}" class="ziara-btn ziara-btn--gold" style="margin-top:12px;">Become a Partner</a>
            </div>
            <div>
                <img src="https://images.unsplash.com/photo-1553484771-371a605b060b?w=600&h=400&fit=crop" alt="Business partnership" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
        </div>
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Partner News'])
@endsection

@push('styles')
<style>
.ziara-partner-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:24px;text-align:center;}
.ziara-partner__card{background:#fff;border-radius:16px;padding:32px 20px;box-shadow:0 8px 30px rgba(10,5,3,.06);transition:transform .3s,box-shadow .3s;}
.ziara-partner__card:hover{transform:translateY(-4px);box-shadow:0 16px 40px rgba(10,5,3,.12);}
.ziara-partner__logo{width:100px;height:100px;margin:0 auto 16px;border-radius:12px;background:#faf8f5;display:flex;align-items:center;justify-content:center;overflow:hidden;}
.ziara-partner__logo img{max-width:80%;max-height:80%;object-fit:contain;}
.ziara-partner__name{font-family:'Cormorant Garamond',Georgia,serif;font-size:1.15rem;margin:0 0 8px;color:var(--maroon);}
.ziara-partner__desc{font-size:.85rem;color:#5a524a;margin:0;line-height:1.5;}
.ziara-partner-grid--list .ziara-partner__card{text-align:left;padding:24px;display:flex;align-items:center;gap:16px;}
.ziara-partner-grid--list .ziara-partner__logo{width:72px;height:72px;margin:0;flex-shrink:0;}
.ziara-partner-grid--list .ziara-partner__info{flex:1;}
.ziara-partner__loc{font-size:.8rem;color:#8a827a;margin:4px 0 8px;}
.ziara-partner__type{font-size:.75rem;font-weight:600;color:var(--gold);text-transform:uppercase;letter-spacing:.5px;display:inline-block;}
.ziara-partner-grid--certs .ziara-partner__card{padding:28px 20px;}
.ziara-partner__cert .ziara-partner__logo{background:rgba(201,162,39,.12);color:var(--gold);}
@media (max-width:768px){.ziara-partner-grid{grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:16px;}}
</style>
@endpush