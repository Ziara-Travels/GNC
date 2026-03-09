@extends('layouts.main')
@section('title', 'Achievements - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Our <span class="text-amber-400">Achievements</span></h1>
        <p class="text-xs text-gray-300">Milestones and awards that define our journey</p>
    </div>
</section>

{{-- ===== STATS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([['num'=>'50K+','label'=>'Happy Travelers'],['num'=>'120+','label'=>'Destinations'],['num'=>'15K+','label'=>'Flights Booked'],['num'=>'4.8/5','label'=>'Customer Rating']] as $s)
            <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100">
                <span class="text-xl font-bold text-emerald-600 block">{{ $s['num'] }}</span>
                <span class="text-[10px] text-gray-500">{{ $s['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== AWARDS ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Awards & Recognition</h2>
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
            @foreach([['year'=>'2025','title'=>'Best Travel Agency – East Africa','org'=>'Africa Travel Awards','icon'=>'fa-trophy'],['year'=>'2024','title'=>'Service Excellence Award','org'=>'Kenya Tourism Board','icon'=>'fa-award'],['year'=>'2024','title'=>'Top Innovation in Travel Tech','org'=>'Tech in Travel Conference','icon'=>'fa-microchip'],['year'=>'2023','title'=>'Customer Choice Award','org'=>'Travel Weekly Africa','icon'=>'fa-heart'],['year'=>'2023','title'=>'Sustainable Tourism Partner','org'=>'Sustainable Tourism Alliance','icon'=>'fa-leaf'],['year'=>'2022','title'=>'Best New Travel Platform','org'=>'Startup Awards Africa','icon'=>'fa-star']] as $a)
            <div class="bg-white rounded-xl p-4 border border-gray-100">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-amber-50 rounded-full flex items-center justify-center shrink-0"><i class="fas {{ $a['icon'] }} text-amber-500 text-xs"></i></div>
                    <div>
                        <span class="text-[9px] text-emerald-600 font-bold">{{ $a['year'] }}</span>
                        <h3 class="text-xs font-bold text-gray-900 leading-tight">{{ $a['title'] }}</h3>
                        <p class="text-[9px] text-gray-400">{{ $a['org'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== TIMELINE ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Our Journey</h2>
        <div class="space-y-3">
            @foreach([['year'=>'2022','title'=>'Founded','text'=>'Ziara Travel launches with a vision to make African travel seamless and affordable.'],['year'=>'2023','title'=>'10,000 Bookings','text'=>'Reached our first major milestone with 10K successful bookings in under a year.'],['year'=>'2023','title'=>'Expanded to 5 Countries','text'=>'Extended our services to Kenya, Tanzania, Uganda, Rwanda, and Ethiopia.'],['year'=>'2024','title'=>'Mobile App Launch','text'=>'Released our mobile app for iOS and Android, enabling travel on-the-go.'],['year'=>'2025','title'=>'Southern Africa Expansion','text'=>'Opened new offices in Cape Town and Gaborone, marking our continental growth.']] as $m)
            <div class="flex gap-3">
                <div class="shrink-0 text-center"><span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">{{ $m['year'] }}</span></div>
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100 flex-1">
                    <h3 class="text-xs font-bold text-gray-900">{{ $m['title'] }}</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed">{{ $m['text'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row-reverse items-center gap-6 bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1488085061387-422e29b40080?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Join Our Story</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Be Part of Our Next Chapter</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-4">Over 50,000 travelers trust us. Start your journey with an award-winning team.</p>
                <a href="{{ url('/') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors">Book Your Trip</a>
            </div>
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Follow Our Journey</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Stay updated on our latest milestones and announcements.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection
