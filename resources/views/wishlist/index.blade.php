@extends('layouts.main')
@section('title', 'My Wishlist - Ziara Travel')
@section('meta_description', 'View and manage all your favorite flights, hotels, tours, and destinations in one place')

@section('content')
{{-- ===== HERO ===== --}}
@include('partials.ziara-page-hero', [
    'kicker' => 'Wishlist',
    'title'  => 'My <em>Wishlist</em>',
    'intro'  => 'All your favorite travel items in one place',
])

{{-- ===== STATS ===== --}}
@php $statsCards = [
    ['label' => 'Total Items',  'value' => $stats['total']],
    ['label' => 'Flights',      'value' => $stats['flights']],
    ['label' => 'Hotels',       'value' => $stats['hotels']],
    ['label' => 'Tours',        'value' => $stats['tours']],
    ['label' => 'Destinations', 'value' => $stats['destinations']],
]; @endphp
<section class="zt-section zt-section--white" style="padding:56px 0 28px;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:18px;">
            @foreach($statsCards as $s)
            <div style="background:#fff;border:1px solid var(--line);border-radius:16px;padding:26px 16px;text-align:center;box-shadow:var(--shadow-sm);">
                <p style="margin:0 0 8px;font-size:12px;letter-spacing:.8px;text-transform:uppercase;color:var(--muted);">{{ $s['label'] }}</p>
                <h3 style="margin:0;font-family:"Cormorant Garamond",Georgia,serif;font-size:34px;font-weight:700;color:var(--maroon);">{{ $s['value'] }}</h3>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== WISHLIST ===== --}}
<section class="zt-section zt-section--white" style="padding:28px 0 72px;">
    <div class="container">
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:30px;justify-content:center;">
            <button class="filter-tab active ziara-faqs__tab is-active" data-filter="all" style="padding:9px 20px;font-size:11.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;cursor:pointer;border:1.5px solid var(--gold);background:var(--gold);color:var(--maroon-deep);">All Items</button>
            <button class="filter-tab" data-filter="flight" style="padding:9px 20px;font-size:11.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;cursor:pointer;border:1.5px solid var(--line);background:#fff;color:var(--muted);">Flights</button>
            <button class="filter-tab" data-filter="hotel" style="padding:9px 20px;font-size:11.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;cursor:pointer;border:1.5px solid var(--line);background:#fff;color:var(--muted);">Hotels</button>
            <button class="filter-tab" data-filter="tour" style="padding:9px 20px;font-size:11.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;cursor:pointer;border:1.5px solid var(--line);background:#fff;color:var(--muted);">Tours & Activities</button>
            <button class="filter-tab" data-filter="destination" style="padding:9px 20px;font-size:11.5px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;cursor:pointer;border:1.5px solid var(--line);background:#fff;color:var(--muted);">Destinations</button>
        </div>

        @if($wishlistItems->isEmpty())
            <div style="text-align:center;padding:56px 24px;background:#fff;border:1px solid var(--line);border-radius:18px;max-width:640px;margin:0 auto;">
                <span style="width:76px;height:76px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;background:var(--gold-pale);color:var(--maroon);font-size:30px;margin-bottom:18px;"><i class="fa-solid fa-heart" aria-hidden="true"></i></span>
                <h3 style="margin:0 0 8px;font-family:"Cormorant Garamond",Georgia,serif;font-size:26px;color:var(--maroon-deep);">Your wishlist is empty</h3>
                <p style="margin:0 0 24px;color:var(--muted);font-size:15px;">Start adding your favorite travel items as you browse</p>
                <a href="{{ route('home') }}" class="ziara-btn ziara-btn--gold">Browse Offers</a>
            </div>
        @else
            <div class="ziara-deals">
                @foreach($wishlistItems as $item)
                    <div class="ziara-deal wishlist-card" data-type="{{ $item->type }}">
                        <div class="ziara-deal__media">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--gold-pale);"><i class="fa-solid fa-image" aria-hidden="true" style="font-size:34px;color:var(--gold);"></i></div>
                            @endif
                            <span class="ziara-deal__badge">{{ ucfirst($item->type) }}</span>
                            <button class="remove-from-wishlist" data-id="{{ $item->id }}" data-type="{{ $item->type }}" title="Remove from wishlist" style="position:absolute;top:14px;right:14px;z-index:3;width:36px;height:36px;border:0;border-radius:50%;background:rgba(255,255,255,.9);color:var(--muted);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;"><i class="fa-solid fa-heart" aria-hidden="true"></i></button>
                        </div>
                        <div class="ziara-deal__body">
                            <h3 class="ziara-deal__title">{{ $item->title }}</h3>

                            @if($item->location)
                                <p class="ziara-deal__desc" style="margin-bottom:6px;"><i class="fa-solid fa-location-dot" aria-hidden="true" style="margin-right:6px;color:var(--gold);"></i>{{ $item->location }}</p>
                            @endif

                            @if($item->description)
                                <p class="ziara-deal__desc">{{ $item->description }}</p>
                            @endif

                            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;margin-top:auto;padding-top:16px;border-top:1px solid var(--line);">
                                <div class="ziara-deal__price" style="padding:0;border:0;">
                                    @if($item->price)
                                        <strong>${{ number_format($item->price, 2) }}</strong>
                                        @if($item->original_price && $item->original_price > $item->price)
                                            <s>${{ number_format($item->original_price, 2) }}</s>
                                        @endif
                                    @endif
                                </div>
                                @if($item->type == 'flight')
                                    <a href="{{ route('flights.booking', $item->item_id) }}" class="ziara-deal__btn">View Flight</a>
                                @elseif($item->type == 'hotel')
                                    <a href="{{ route('hotels.show', $item->item_id) }}" class="ziara-deal__btn">View Hotel</a>
                                @elseif($item->type == 'tour')
                                    <a href="{{ route('tours.show', ['slug' => $item->item_id]) }}" class="ziara-deal__btn">View Tour</a>
                                @else
                                    <a href="{{ route('destinations.east-african') }}" class="ziara-deal__btn">View Destination</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($wishlistItems->hasPages())
                <div style="margin-top:48px;display:flex;justify-content:center;">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterTabs = document.querySelectorAll('.filter-tab');
        const wishlistCards = document.querySelectorAll('.wishlist-card');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                filterTabs.forEach(t => {
                    t.classList.remove('active', 'is-active');
                    t.style.background = '#fff';
                    t.style.color = 'var(--muted)';
                    t.style.borderColor = 'var(--line)';
                });
                tab.classList.add('active', 'is-active');
                tab.style.background = 'var(--gold)';
                tab.style.color = 'var(--maroon-deep)';
                tab.style.borderColor = 'var(--gold)';

                const filter = this.getAttribute('data-filter');

                wishlistCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-type') === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        document.querySelectorAll('.remove-from-wishlist').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const card = this.closest('.wishlist-card');

                if (confirm('Are you sure you want to remove this item from your wishlist?')) {
                    fetch(`/wishlist/remove/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            card.style.opacity = '0';
                            card.style.transform = 'scale(0.9)';

                            setTimeout(() => {
                                card.remove();
                                document.dispatchEvent(new CustomEvent('wishlistUpdated'));
                                if (document.querySelectorAll('.wishlist-card').length === 0) {
                                    location.reload();
                                }
                            }, 300);
                        }
                    })
                    .catch(error => console.error('Error removing item from wishlist:', error));
                }
            });
        });
    });
</script>
@endpush