@extends('layouts.main')
@section('title', 'Careers - Ziara Travel')

@section('content')
@include('partials.ziara-page-hero', [
    'kicker' => 'Careers',
    'title'  => 'Join Our <em>Team</em>',
    'intro'  => 'Build the future of travel with us',
])

<section class="zt-section zt-section--white">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Why Join',
            'title' => 'Why Work at <em>Ziara Travels</em>',
            'align' => 'center',
        ])
        @php $perks = [
            ['icon'=>'fa-solid fa-rocket','title'=>'Growth','desc'=>'Fast-tracked career progression and learning opportunities.'],
            ['icon'=>'fa-solid fa-globe-africa','title'=>'Impact','desc'=>'Shape how millions of travelers explore East Africa.'],
            ['icon'=>'fa-solid fa-laptop-house','title'=>'Flexibility','desc'=>'Remote-friendly culture with flexible hours.'],
            ['icon'=>'fa-solid fa-heartbeat','title'=>'Benefits','desc'=>'Health insurance, travel perks, and wellness programs.'],
            ['icon'=>'fa-solid fa-users','title'=>'Culture','desc'=>'Collaborative, diverse, and supportive team environment.'],
            ['icon'=>'fa-solid fa-graduation-cap','title'=>'Learning','desc'=>'Annual training budget and conference attendance.'],
        ]; @endphp
        <div class="ziara-minifeatures">
            @foreach($perks as $p)
            <div class="ziara-minifeature">
                <span class="ziara-minifeature__icon"><i class="{{ $p['icon'] }}" aria-hidden="true"></i></span>
                <h3 class="ziara-minifeature__title">{{ $p['title'] }}</h3>
                <p class="ziara-minifeature__desc">{{ $p['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Careers',
            'title' => 'Open <em>Positions</em>',
            'align' => 'center',
        ])
        @php $jobs = [
            ['title'=>'Senior Software Engineer','dept'=>'Engineering','type'=>'Full-time','location'=>'Nairobi / Remote','desc'=>'Build and scale our booking platform, APIs, and microservices.'],
            ['title'=>'UI/UX Designer','dept'=>'Design','type'=>'Full-time','location'=>'Nairobi','desc'=>'Design intuitive interfaces for web and mobile travel products.'],
            ['title'=>'Customer Support Lead','dept'=>'Support','type'=>'Full-time','location'=>'Nairobi','desc'=>'Lead our 24/7 support team and drive customer satisfaction.'],
            ['title'=>'Marketing Manager','dept'=>'Marketing','type'=>'Full-time','location'=>'Nairobi / Remote','desc'=>'Drive brand awareness and customer acquisition across East Africa.'],
        ]; @endphp
        <div class="ziara-features">
            @foreach($jobs as $j)
            <div class="ziara-feature">
                <span class="ziara-feature__icon"><i class="fa-solid fa-building" aria-hidden="true"></i></span>
                <h3 class="ziara-feature__title">{{ $j['title'] }}</h3>
                <span style="display:inline-flex;padding:5px 12px;border-radius:50px;background:var(--gold-pale);color:var(--maroon);font-size:10px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;">{{ $j['type'] }}</span>
                <p class="ziara-feature__desc" style="margin-top:14px;">{{ $j['desc'] }}</p>
                <ul class="ziara-feature__list">
                    <li><i class="fa-solid fa-building" aria-hidden="true"></i>{{ $j['dept'] }}</li>
                    <li><i class="fa-solid fa-map-marker-alt" aria-hidden="true"></i>{{ $j['location'] }}</li>
                </ul>
                <a href="{{ url('/contactus') }}" class="ziara-feature__link">Apply Now <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="zt-section zt-section--white">
    <div class="container">
        <div class="ziara-split">
            <div>
                <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=600&h=400&fit=crop" alt="" style="width:100%;border-radius:20px;box-shadow:0 24px 60px rgba(10,5,3,.18);" loading="lazy">
            </div>
            <div>
                @include('partials.ziara-section-head', [
                    'sub'   => 'Don\'t See Your Role?',
                    'title' => 'Send Us Your <em>CV</em>',
                    'align' => 'left',
                ])
                <div class="ziara-prose" style="margin-top:-30px;">
                    <p>We\'re always looking for talented people. Send us your resume and we\'ll get in touch when the right opportunity comes up.</p>
                </div>
                <a href="{{ url('/contactus') }}" class="ziara-btn ziara-btn--gold" style="margin-top:8px;">Send CV</a>
            </div>
        </div>
    </div>
</section>

<section class="zt-section zt-section--pale">
    <div class="container">
        @include('partials.ziara-section-head', [
            'sub'   => 'Careers',
            'title' => 'Careers <em>FAQs</em>',
            'align' => 'center',
        ])
        @php $faqs = [
            ['q'=>'How do I apply?','a'=>'Click "Apply Now" on any open position or send your CV through the Contact Us page. Include the role title in the subject.'],
            ['q'=>'What is the hiring process?','a'=>'Typically: application review → phone screen → technical/skills assessment → final interview → offer. Timeline is usually 2-3 weeks.'],
            ['q'=>'Do you offer remote work?','a'=>'Yes, many of our positions are remote-friendly. Check the location field on each listing for details.'],
            ['q'=>'What benefits do you offer?','a'=>'Health insurance, travel perks (discounted flights & hotels), flexible hours, annual learning budget, and more.'],
        ]; @endphp
        @include('partials.ziara-faqs', ['faqs' => $faqs])
    </div>
</section>

@include('partials.ziara-newsletter', ['title' => 'Job Alerts'])
@endsection
