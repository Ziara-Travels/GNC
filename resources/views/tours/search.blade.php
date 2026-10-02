@extends('layouts.main')

@section('title', ($activeCategory ?? null) ? \App\Models\Tour::categoryLabel($activeCategory) . ' | Ziara Travels' : 'Tours | Ziara Travels')

@section('content')
<div class="ziara-single">
    <section class="ziara-single__hero"
        style="background-image: linear-gradient(180deg, rgba(28,22,18,.32) 0%, rgba(74,29,19,.86) 100%), url('{{ asset('images/travel/default-tour.svg') }}'); background-size: cover; background-position: center;">
        <span class="ziara-single__hero-overlay" aria-hidden="true"></span>

        <div class="ziara-single__hero-inner">
            <nav class="ziara-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ url('/') }}">Home</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <a href="{{ route('tours.index') }}">Tours</a>
                @if ($activeCategory ?? null)
                    <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    <span aria-current="page">{{ \App\Models\Tour::categoryLabel($activeCategory) }}</span>
                @endif
            </nav>

            <h1 class="ziara-single__title">{{ ($activeCategory ?? null) ? \App\Models\Tour::categoryLabel($activeCategory) : 'Tours' }}</h1>
            <p class="ziara-single__loc">
                <i class="fa-solid fa-compass" aria-hidden="true"></i>{{ $tours->total() }} {{ Str::plural('package', $tours->total()) }} available
            </p>
        </div>
    </section>

    <div class="ziara-single__wrap" style="display:block;">
        <div class="ziara-single__content">
            @if (count($categories))
                <div class="ziara-filter-bar">
                    <a class="ziara-filter-chip {{ ! ($activeCategory ?? null) ? 'is-active' : '' }}"
                        href="{{ route('tours.index') }}">All</a>
                    @foreach ($categories as $category)
                        <a class="ziara-filter-chip {{ ($activeCategory ?? null) === $category['slug'] ? 'is-active' : '' }}"
                            href="{{ route('tours.category', $category['slug']) }}">{{ $category['label'] }}</a>
                    @endforeach
                </div>
            @endif

            @if ($tours->isEmpty())
                <p class="ziara-empty">No tours match this search yet. Please try another destination.</p>
            @else
                <div class="ziara-tours" style="--ziara-cols:3;">
                    @foreach ($tours as $tour)
                        @include('partials.ziara-card', ['tour' => $tour])
                    @endforeach
                </div>

                <div class="ziara-pagination">
                    {{ $tours->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.ziara-filter-bar { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 34px; }
.ziara-filter-chip {
    display: inline-block; padding: 9px 20px; border-radius: 50px;
    border: 1px solid var(--line); font-size: 12px; font-weight: 700;
    letter-spacing: .8px; text-transform: uppercase; color: var(--maroon-deep);
    background: #fff; transition: background .25s, color .25s, border-color .25s;
}
.ziara-filter-chip:hover { background: var(--gold-pale); border-color: var(--gold); }
.ziara-filter-chip.is-active { background: var(--maroon); border-color: var(--maroon); color: #fff; }
.ziara-empty { padding: 60px 0; text-align: center; color: var(--muted); font-size: 16px; }
.ziara-pagination { margin-top: 46px; }
</style>
@endpush