{{--
    Ziara section heading (clone of the GSC heading widget).
    Required:  $title   (title text; wrap accent words in <em>Accent</em> to use the
                        template accent styling, or pass a <span>Accent</span> element)
    Optional:  $sub     (italic gold sub-title above, e.g. "Why Choose Us?")
              $desc    (paragraph under the title)
              $align   (left | center; default center)
              $light   (true to render on a dark section, default false)
--}}
@php($classes = ['gsc-heading', 'style-1', 'widget', 'auto-responsive'] + ($align === 'left' ? ['align-left'] : ['align-center']))
@if(!empty($light)) @php($classes[] = 'gsc-heading--light') @endif
<div class="{{ implode(' ', $classes) }}">
    <div class="content-inner">
        @if(!empty($sub))
            <div class="sub-title"><span>{{ $sub }}</span></div>
        @endif
        <h2 class="title">{!! $title !!}</h2>
        @if(!empty($desc))
            <div class="title-desc">{!! $desc !!}</div>
        @endif
    </div>
</div>