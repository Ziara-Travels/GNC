@extends('layouts.main')
@section('title', 'Careers - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Join Our <span class="text-amber-400">Team</span></h1>
        <p class="text-xs text-gray-300">Build the future of travel with us</p>
    </div>
</section>

{{-- ===== WHY JOIN ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Why Join</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Why Work at Ziara Travels</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @php $perks = [
                ['icon'=>'fa-rocket','title'=>'Growth','desc'=>'Fast-tracked career progression and learning opportunities.'],
                ['icon'=>'fa-globe-africa','title'=>'Impact','desc'=>'Shape how millions of travelers explore East Africa.'],
                ['icon'=>'fa-laptop-house','title'=>'Flexibility','desc'=>'Remote-friendly culture with flexible hours.'],
                ['icon'=>'fa-heartbeat','title'=>'Benefits','desc'=>'Health insurance, travel perks, and wellness programs.'],
                ['icon'=>'fa-users','title'=>'Culture','desc'=>'Collaborative, diverse, and supportive team environment.'],
                ['icon'=>'fa-graduation-cap','title'=>'Learning','desc'=>'Annual training budget and conference attendance.'],
            ]; @endphp
            @foreach($perks as $p)
            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center mb-1.5"><i class="fas {{ $p['icon'] }} text-emerald-600 text-[10px]"></i></div>
                <h3 class="text-xs font-bold text-gray-900 mb-0.5">{{ $p['title'] }}</h3>
                <p class="text-[10px] text-gray-500">{{ $p['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== OPEN POSITIONS ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Open Positions</h2>
        @php $jobs = [
            ['title'=>'Senior Software Engineer','dept'=>'Engineering','type'=>'Full-time','location'=>'Nairobi / Remote','desc'=>'Build and scale our booking platform, APIs, and microservices.'],
            ['title'=>'UI/UX Designer','dept'=>'Design','type'=>'Full-time','location'=>'Nairobi','desc'=>'Design intuitive interfaces for web and mobile travel products.'],
            ['title'=>'Customer Support Lead','dept'=>'Support','type'=>'Full-time','location'=>'Nairobi','desc'=>'Lead our 24/7 support team and drive customer satisfaction.'],
            ['title'=>'Marketing Manager','dept'=>'Marketing','type'=>'Full-time','location'=>'Nairobi / Remote','desc'=>'Drive brand awareness and customer acquisition across East Africa.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($jobs as $j)
            <div class="bg-white rounded-xl p-4 border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-1">
                    <h3 class="text-xs font-bold text-gray-900">{{ $j['title'] }}</h3>
                    <span class="text-[9px] bg-emerald-50 text-emerald-700 font-semibold px-2 py-0.5 rounded-full shrink-0">{{ $j['type'] }}</span>
                </div>
                <div class="flex gap-3 text-[10px] text-gray-400 mb-1">
                    <span><i class="fas fa-building mr-0.5"></i>{{ $j['dept'] }}</span>
                    <span><i class="fas fa-map-marker-alt mr-0.5"></i>{{ $j['location'] }}</span>
                </div>
                <p class="text-[10px] text-gray-500 mb-2">{{ $j['desc'] }}</p>
                <a href="{{ url('/contactus') }}" class="text-[10px] font-semibold text-emerald-600 hover:underline">Apply Now →</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row-reverse items-center gap-6 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Don't See Your Role?</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Send Us Your CV</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">We're always looking for talented people. Send us your resume and we'll get in touch when the right opportunity comes up.</p>
                <a href="{{ url('/contactus') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors">Send CV</a>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Careers FAQs</h2>
        @php $faqs = [
            ['q'=>'How do I apply?','a'=>'Click "Apply Now" on any open position or send your CV through the Contact Us page. Include the role title in the subject.'],
            ['q'=>'What is the hiring process?','a'=>'Typically: application review → phone screen → technical/skills assessment → final interview → offer. Timeline is usually 2-3 weeks.'],
            ['q'=>'Do you offer remote work?','a'=>'Yes, many of our positions are remote-friendly. Check the location field on each listing for details.'],
            ['q'=>'What benefits do you offer?','a'=>'Health insurance, travel perks (discounted flights & hotels), flexible hours, annual learning budget, and more.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-white overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-50 transition-colors" onclick="toggleFaq(this)"><span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span><i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i></button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Job Alerts</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get notified when new positions open up.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
