@extends('layouts.main')
@section('title', 'Special Offers - Ziara Travel')

@section('content')
{{-- ===== HERO ===== --}}
<section class="relative bg-slate-900 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1400&h=500&fit=crop" alt="" class="absolute inset-0 w-full h-full object-cover opacity-20" loading="eager">
    <div class="relative max-w-5xl mx-auto px-4 pt-28 pb-10 text-center">
        <span class="inline-block bg-red-500/20 text-red-300 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full mb-3">Limited Time</span>
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">Special <span class="text-amber-400">Offers</span></h1>
        <p class="text-xs text-gray-300">Exclusive deals on flights, hotels, and packages — book before they're gone</p>
    </div>
</section>

{{-- ===== FEATURED DEALS ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-6">
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Hot Deals</span>
            <h2 class="text-lg font-bold text-gray-900 mt-0.5">Featured Offers</h2>
        </div>
        @php $deals = [
            ['title'=>'Nairobi → Mombasa','type'=>'Flight','discount'=>'25%','price'=>'KES 8,500','orig'=>'KES 11,300','desc'=>'Direct flight, economy class. Includes 23kg baggage.','img'=>'https://images.unsplash.com/photo-1596005554384-d293674c91d7?w=400&h=250&fit=crop','badge'=>'Best Seller'],
            ['title'=>'Zanzibar Beach Resort','type'=>'Hotel','discount'=>'30%','price'=>'$129/night','orig'=>'$185/night','desc'=>'4-star beachfront resort with breakfast and airport transfer.','img'=>'https://images.unsplash.com/photo-1586861635167-e5223aadc9fe?w=400&h=250&fit=crop','badge'=>'Top Rated'],
            ['title'=>'Nairobi → Kigali','type'=>'Flight','discount'=>'20%','price'=>'KES 15,800','orig'=>'KES 19,750','desc'=>'Return flight, flexible dates. Free date change.','img'=>'https://images.unsplash.com/photo-1580060839134-75a5edca2e99?w=400&h=250&fit=crop','badge'=>'New'],
            ['title'=>'Masai Mara Safari','type'=>'Package','discount'=>'15%','price'=>'KES 45,000','orig'=>'KES 52,900','desc'=>'3-day all-inclusive safari with game drives and accommodation.','img'=>'https://images.unsplash.com/photo-1516426122078-c23e76319801?w=400&h=250&fit=crop','badge'=>'Popular'],
            ['title'=>'Serena Hotel Nairobi','type'=>'Hotel','discount'=>'20%','price'=>'$95/night','orig'=>'$119/night','desc'=>'5-star luxury in the heart of Nairobi. Free cancellation.','img'=>'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=400&h=250&fit=crop','badge'=>'Luxury'],
            ['title'=>'Nairobi → Entebbe','type'=>'Flight','discount'=>'18%','price'=>'KES 12,000','orig'=>'KES 14,600','desc'=>'One-way economy. KQ direct flight, instant e-ticket.','img'=>'https://images.unsplash.com/photo-1619122753925-a01d8bb38c20?w=400&h=250&fit=crop','badge'=>'Flash Sale'],
        ]; @endphp
        <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($deals as $d)
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                <div class="relative h-32 overflow-hidden">
                    <img src="{{ $d['img'] }}" alt="{{ $d['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded">{{ $d['discount'] }} OFF</span>
                    <span class="absolute top-2 right-2 bg-white/90 text-[9px] font-bold text-gray-700 px-1.5 py-0.5 rounded">{{ $d['badge'] }}</span>
                    <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded">{{ $d['type'] }}</span>
                </div>
                <div class="p-2.5">
                    <h3 class="text-xs font-semibold text-gray-900 truncate">{{ $d['title'] }}</h3>
                    <p class="text-[10px] text-gray-400 mb-2 line-clamp-2">{{ $d['desc'] }}</p>
                    <div class="flex items-end justify-between">
                        <div><span class="text-sm font-bold text-gray-900">{{ $d['price'] }}</span> <span class="text-[10px] text-gray-400 line-through">{{ $d['orig'] }}</span></div>
                        <a href="{{ url('/flights/search') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-semibold px-3 py-1.5 rounded-lg transition-colors">Book</a>
                    </div>
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
            <div class="md:w-1/2"><img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=600&h=400&fit=crop" alt="" class="w-full h-48 md:h-56 object-cover" loading="lazy"></div>
            <div class="md:w-1/2 p-5">
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Bundle & Save</span>
                <h2 class="text-lg font-bold text-gray-900 mt-1 mb-2">Flight + Hotel Packages</h2>
                <p class="text-xs text-gray-500 leading-relaxed mb-3">Save up to 30% when you book your flight and hotel together. Instant confirmation, flexible cancellation.</p>
                <a href="{{ url('/') }}" class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors">View Packages</a>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section class="py-10 bg-white">
    <div class="max-w-2xl mx-auto px-4">
        <h2 class="text-lg font-bold text-gray-900 text-center mb-6">Offers FAQs</h2>
        @php $faqs = [
            ['q'=>'How long are these offers valid?','a'=>'Offers have limited availability and can expire at any time. Book early to secure the best prices.'],
            ['q'=>'Can I combine multiple offers?','a'=>'Offers cannot be stacked. Each booking uses the best applicable discount automatically.'],
            ['q'=>'Are offer prices refundable?','a'=>'Refund policies depend on the fare type. Flexible rates allow free cancellation; promotional rates may be non-refundable.'],
            ['q'=>'How do I get notified about new offers?','a'=>'Subscribe to our newsletter below to receive deal alerts directly in your inbox.'],
        ]; @endphp
        <div class="space-y-2">
            @foreach($faqs as $faq)
            <div class="border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                <button class="faq-toggle w-full flex items-center justify-between p-3 text-left hover:bg-gray-100 transition-colors" onclick="toggleFaq(this)"><span class="text-xs font-semibold text-gray-900 pr-3">{{ $faq['q'] }}</span><i class="fas fa-chevron-down text-gray-300 text-[10px] transition-transform duration-200 shrink-0"></i></button>
                <div class="faq-content hidden px-3 pb-3"><p class="text-[11px] text-gray-500 leading-relaxed">{{ $faq['a'] }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-10 bg-emerald-600">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="text-base font-bold text-white mb-1">Never Miss a Deal</h2>
        <p class="text-[11px] text-emerald-100 mb-4">Get exclusive offers and flash sales straight to your inbox.</p>
        <form class="flex gap-2" onsubmit="event.preventDefault();alert('Subscribed!');this.reset();"><input type="email" placeholder="Your email" class="flex-1 px-3 py-2 rounded-lg text-xs text-gray-800 focus:ring-2 focus:ring-amber-400 focus:outline-none" required><button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-5 py-2 rounded-lg transition-colors whitespace-nowrap">Subscribe</button></form>
    </div>
</section>
@endsection

@push('scripts')
<script>function toggleFaq(btn){var c=btn.nextElementSibling,ic=btn.querySelector('i'),open=!c.classList.contains('hidden');document.querySelectorAll('.faq-content').forEach(function(x){x.classList.add('hidden');});document.querySelectorAll('.faq-toggle i').forEach(function(x){x.style.transform='rotate(0)';});if(!open){c.classList.remove('hidden');ic.style.transform='rotate(180deg)';}}</script>
@endpush
