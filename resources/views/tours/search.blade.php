@extends('layouts.main')

@section('title', 'Search Tours: ' . $searchTerm)

@section('content')
<style>
.ziara-filter__field { margin-bottom: 18px; }
.ziara-filter__field label { display: block; font-size: 11px; font-weight: 700; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
.ziara-filter__field select,
.ziara-filter__field input {
    width: 100%;
    padding: 12px 14px;
    font-family: var(--font-body);
    font-size: 14.5px;
    color: var(--ink);
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 10px;
}
.ziara-filter__field select:focus,
.ziara-filter__field input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-border); }
.ziara-filter__price { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.ziara-searchbar { display: flex; gap: 10px; }
.ziara-searchbar input {
    flex: 1;
    padding: 14px 16px;
    font-family: var(--font-body);
    font-size: 15px;
    color: var(--ink);
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 50px;
}
.ziara-searchbar input:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-border); }
.ziara-results__bar { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; justify-content: space-between; background: var(--gold-pale); border: 1px solid var(--gold-border); border-radius: 14px; padding: 16px 22px; }
.ziara-results__bar strong { color: var(--maroon-deep); }
.ziara-results__bar select { padding: 10px 14px; font-size: 14px; color: var(--ink); background: #fff; border: 1px solid var(--line); border-radius: 10px; }
.ziara-filter-chip { display: inline-block; margin: 4px 4px 0 0; padding: 5px 14px; border-radius: 50px; background: var(--maroon); color: #fff; font-size: 12px; font-weight: 600; letter-spacing: .4px; }
.ziara-archive__pagination .pagination { gap: 8px; flex-wrap: wrap; justify-content: center; }
.ziara-archive__pagination .page-link { border: 1px solid var(--line); border-radius: 8px; color: var(--maroon-deep); font-weight: 600; padding: 10px 15px; margin: 0; }
.ziara-archive__pagination .page-item.active .page-link { background: var(--maroon); border-color: var(--maroon); color: #fff; }
.ziara-archive__pagination .page-item.disabled .page-link { color: var(--muted); }
</style>

<div class="ziara-archive">
    @include('partials.ziara-page-hero', [
        'title' => 'Tour <em>Search</em> Results',
        'kicker' => 'Tours & Activities',
        'intro' => 'Discover amazing experiences for your journey across East Africa.',
        'crumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Tours', 'url' => route('tours.index')],
            ['label' => 'Search', 'url' => route('tours.search')],
        ],
    ])

    <div style="display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start;">
        <aside style="flex: 0 0 290px; width: 100%;">
            <div class="ziara-panel" style="position: sticky; top: 24px;">
                <h2 class="ziara-panel__title">Filter Tours</h2>
                <form method="GET" action="{{ route('tours.search') }}" id="filterForm">
                    @if($searchTerm)
                        <input type="hidden" name="q" value="{{ $searchTerm }}">
                    @endif

                    <div class="ziara-filter__field">
                        <label for="filterCity">Destination</label>
                        <select name="city" id="filterCity" class="filter-select">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="ziara-filter__field">
                        <label for="filterCategory">Tour Category</label>
                        <select name="category" id="filterCategory" class="filter-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucwords($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="ziara-filter__field">
                        <label for="filterPriceMin">Price Range</label>
                        <div class="ziara-filter__price">
                            <input type="number" name="price_min" id="filterPriceMin" class="filter-input" placeholder="Min $" value="{{ request('price_min') }}">
                            <input type="number" name="price_max" class="filter-input" placeholder="Max $" value="{{ request('price_max') }}">
                        </div>
                    </div>

                    <div class="ziara-filter__field">
                        <label for="filterSort">Sort By</label>
                        <select name="sort" id="filterSort" class="filter-select">
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                            <option value="duration" {{ request('sort') == 'duration' ? 'selected' : '' }}>Duration</option>
                        </select>
                    </div>

                    <button type="submit" class="ziara-btn ziara-btn--maroon" style="width: 100%;">
                        <i class="fa-solid fa-filter" aria-hidden="true"></i> Apply Filters
                    </button>
                    <a href="{{ route('tours.search', ['q' => $searchTerm]) }}" class="ziara-btn ziara-btn--outline" style="width: 100%; margin-top: 10px;">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i> Clear Filters
                    </a>
                </form>
            </div>
        </aside>

        <div style="flex: 1 1 560px; min-width: 0;">
            <div class="ziara-panel" style="margin-bottom: 24px;">
                @if($searchTerm)
                    <p style="margin: 0 0 6px; font-family: var(--font-display); font-size: 30px; font-weight: 700; color: var(--maroon-deep);">
                        Showing results for &ldquo;<strong>{{ $searchTerm }}</strong>&rdquo;
                    </p>
                @else
                    <p style="margin: 0 0 6px; font-family: var(--font-display); font-size: 30px; font-weight: 700; color: var(--maroon-deep);">
                        All tours matching your filters
                    </p>
                @endif

                @if(request('city') || request('category') || request('price_min') || request('price_max'))
                    <div style="font-size: 13px; letter-spacing: .8px; text-transform: uppercase; color: var(--muted); font-weight: 600;">Filters</div>
                    <div>
                        @if(request('city'))
                            <span class="ziara-filter-chip">{{ request('city') }}</span>
                        @endif
                        @if(request('category'))
                            <span class="ziara-filter-chip">{{ request('category') }}</span>
                        @endif
                        @if(request('price_min') || request('price_max'))
                            <span class="ziara-filter-chip">
                                Price:
                                {{ request('price_min') ? '$' . request('price_min') : '$0' }}
                                -
                                {{ request('price_max') ? '$' . request('price_max') : '+' }}
                            </span>
                        @endif
                    </div>
                @endif

                <form method="GET" action="{{ route('tours.search') }}" class="ziara-searchbar" style="margin-top: 18px;">
                    <input type="text" name="q" placeholder="Search tours..." value="{{ $searchTerm }}">
                    <button type="submit" class="ziara-btn ziara-btn--maroon">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    </button>
                </form>
            </div>

            @if(count($tours) > 0)
                <div class="ziara-results__bar" style="margin-bottom: 26px;">
                    <div>
                        Showing <strong>{{ $tours->firstItem() ?? 0 }}</strong> - <strong>{{ $tours->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $tours->total() }}</strong> tours
                    </div>
                    <div>
                        <select id="results-per-page" aria-label="Results per page">
                            <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12 per page</option>
                            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24 per page</option>
                            <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48 per page</option>
                        </select>
                    </div>
                </div>

                <div class="ziara-tours" style="--ziara-cols: 2;">
                    @foreach($tours as $tour)
                        <article class="ziara-card">
                            <a href="{{ route('tours.show', $tour->slug) }}" class="ziara-card__media">
                                <img src="{{ $tour->main_image }}" class="ziara-card__img" alt="{{ $tour->name }}">
                                <span class="ziara-card__shade"></span>
                                @if($tour->has_discount)
                                    <span class="ziara-card__badge">{{ $tour->discount_percent }}% OFF</span>
                                @endif
                                @if($tour->featured)
                                    <span class="ziara-deal__tag"><i class="fa-solid fa-star" aria-hidden="true"></i> Featured</span>
                                @endif
                                <span class="ziara-card__loc">
                                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                                    {{ $tour->city }}, {{ $tour->country }}
                                    <span style="margin-left: auto; display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fa-solid fa-star" aria-hidden="true"></i>{{ $tour->rating }}
                                    </span>
                                </span>
                            </a>
                            <div class="ziara-card__body">
                                <h3 class="ziara-card__title">
                                    <a href="{{ route('tours.show', $tour->slug) }}">{{ $tour->name }}</a>
                                </h3>
                                <p class="ziara-card__desc">{{ Str::limit($tour->short_description, 100) }}</p>
                                <div class="ziara-card__meta">
                                    <span><i class="fa-regular fa-clock" aria-hidden="true"></i>{{ $tour->duration }}</span>
                                    <span><i class="fa-solid fa-mountain-sun" aria-hidden="true"></i>{{ ucwords($tour->difficulty_level) }}</span>
                                    <span><i class="fa-solid fa-tag" aria-hidden="true"></i>{{ ucwords($tour->category) }}</span>
                                </div>
                                <div class="ziara-card__foot">
                                    <div class="ziara-card__price">
                                        @if($tour->has_discount)
                                            <span class="ziara-card__price-prefix" style="text-decoration: line-through;">
                                                ${{ number_format($tour->original_price, 2) }}
                                            </span>
                                        @endif
                                        <span class="ziara-card__price-amount">${{ number_format($tour->price, 2) }}</span>
                                        <span class="ziara-card__price-prefix">per person</span>
                                    </div>
                                    <a href="{{ route('tours.show', $tour->slug) }}" class="ziara-card__btn">
                                        View Details <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="ziara-archive__pagination">
                    {{ $tours->appends(request()->query())->links() }}
                </div>
            @else
                <div class="ziara-panel" style="text-align: center; padding: 60px 30px;">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 48px; color: var(--gold);" aria-hidden="true"></i>
                    <h2 class="ziara-panel__title" style="display: inline-block; margin-top: 18px;">No Tours Found</h2>
                    <p style="font-size: 15.5px; line-height: 1.75; color: var(--muted); max-width: 520px; margin: 0 auto 26px;">
                        We couldn't find any tours matching your search criteria. Try adjusting your filters or search term.
                    </p>
                    <a href="{{ route('tours.index') }}" class="ziara-btn ziara-btn--maroon">
                        <i class="fa-solid fa-globe" aria-hidden="true"></i> Browse All Tours
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    const resultsPerPage = document.getElementById('results-per-page');
    if (resultsPerPage) {
        resultsPerPage.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });
    }
});
</script>
@endsection
