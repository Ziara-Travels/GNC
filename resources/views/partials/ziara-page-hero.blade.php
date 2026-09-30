{{--
    Full-bleed maroon inner-page hero (clone of ziaratravels.co.ke archive hero).
    Required:  $title   (H1 text, can contain <em>Accent</em>)
    Optional:  $kicker  (small uppercase line above title, e.g. "About Us")
              $intro   (one-liner under the title)
              $crumbs  (breadcrumb array of ['label' => ..., 'url' => ...] in order;
                        last item is rendered as current. Defaults to Home > $kicker)
--}}
<section class="ziara-archive__hero" aria-label="Page intro">
    <div class="ziara-archive__hero-inner">
        @php $cr = $crumbs ?? null; @endphp
        <nav class="ziara-breadcrumb" aria-label="Breadcrumb">
            @if($cr)
                @foreach($cr as $i => $item)
                    @if($i === count($cr) - 1)
                        <span aria-current="page">{{ $item['label'] }}</span>
                    @else
                        <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    @endif
                @endforeach
            @else
                <a href="{{ url('/') }}">Home</a>
                <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                <span aria-current="page">{{ $kicker ?? __('Ziara Travels') }}</span>
            @endif
        </nav>
        @if(!empty($kicker))
            <div class="ziara-archive__kick" style="font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--zt-gold-bright,#f3d24a);margin-bottom:8px;">{{ $kicker }}</div>
        @endif
        <h1 class="ziara-archive__title">{!! $title !!}</h1>
        @if(!empty($intro))
            <p class="ziara-archive__intro">{{ $intro }}</p>
        @endif
        @isset($slot)
            <div class="ziara-archive__cta" style="margin-top:24px;">{{ $slot }}</div>
        @endisset
        @if(!empty($extra))
            <div class="ziara-archive__extra" style="margin-top:24px;">{!! $extra !!}</div>
        @endif
    </div>
</section>