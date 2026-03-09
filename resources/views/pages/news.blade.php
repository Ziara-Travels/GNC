@extends('layouts.main')
@section('title', 'News & Updates - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1504711434969-e33886168d6c?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">News & <span class="text-amber-400">Updates</span></h1>
        <p class="text-xs text-gray-300">Travel trends, announcements, and industry insights</p>
    </div>
</section>

{{-- ===== FEATURED ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-5 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1436491865332-7a61a109db05?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Featured</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-1">Ziara Travel Expands to Southern Africa</h2>
                <p class="text-[9px] text-gray-400 mb-2"><i class="far fa-calendar mr-1"></i> August 1, 2025</p>
                <p class="text-xs text-gray-500 leading-relaxed mb-3">New offices opening in Cape Town and Gaborone. Our expanded team of local travel experts will enhance your experiences throughout Southern Africa.</p>
                <a href="#" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Read Full Article <i class="fas fa-arrow-right text-[8px] ml-1"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- ===== LATEST NEWS ===== --}}
<section class="py-10 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Latest News</h2>
        @php $news = [
            ['title'=>'New Luxury Safari Packages Launched','date'=>'July 25, 2025','text'=>'Discover newly curated luxury safari experiences featuring exclusive lodges and private guides.','img'=>'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=250&fit=crop'],
            ['title'=>'Ziara Wins Service Excellence Award','date'=>'July 18, 2025','text'=>'Honoured with the Travel Service Excellence Award for the third consecutive year.','img'=>'https://images.unsplash.com/photo-1567521464027-f127ff144326?w=400&h=250&fit=crop'],
            ['title'=>'Sustainable Tourism Alliance Partnership','date'=>'July 10, 2025','text'=>'Joined forces to further commitment to environmentally responsible travel practices.','img'=>'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=400&h=250&fit=crop'],
            ['title'=>'Mobile App: Book Flights On-The-Go','date'=>'July 5, 2025','text'=>'Search, compare, and book flights directly from your smartphone with our new app.','img'=>'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=250&fit=crop'],
            ['title'=>'Summer Family Packages Promotion','date'=>'June 28, 2025','text'=>'Discounted rates on family travel packages to popular East African destinations.','img'=>'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&h=250&fit=crop'],
            ['title'=>'Travel Trends: What to Expect in 2026','date'=>'June 20, 2025','text'=>'Our experts analyze emerging trends and what travelers can expect in the coming year.','img'=>'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=400&h=250&fit=crop'],
        ]; @endphp
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($news as $n)
            <div class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
                <img src="{{ $n['img'] }}" alt="{{ $n['title'] }}" class="w-full h-28 object-cover" loading="lazy">
                <div class="p-3">
                    <p class="text-[9px] text-gray-400 mb-1"><i class="far fa-calendar mr-1"></i>{{ $n['date'] }}</p>
                    <h3 class="text-xs font-bold text-gray-900 mb-1 leading-tight">{{ $n['title'] }}</h3>
                    <p class="text-[10px] text-gray-500 leading-relaxed mb-2">{{ $n['text'] }}</p>
                    <a href="#" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700">Read More <i class="fas fa-arrow-right text-[7px]"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== NEWSLETTER ===== --}}
<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Stay Updated</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get the latest news, travel tips, and exclusive offers in your inbox.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection
