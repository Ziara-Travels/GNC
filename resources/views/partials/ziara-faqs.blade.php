{{--
    Ziara FAQ accordion (clone styling). Self-contained JS — safe to include once per page.
    Required:  $faqs  (array of ['q' => ..., 'a' => ...])
    Optional:  $faq_id (unique element id when several accordions exist on one page)
              $tabs  (array of ['cat' => label] to render filter tabs before the list;
                      each faq may then carry a 'cat' key to be filterable)
--}}
@php($fid = $faq_id ?? 'ziaraFaqs')
<div id="{{ $fid }}" class="ziara-faqs">
    @if(!empty($tabs))
        <div class="ziara-faqs__tabs" style="justify-content:center;display:flex;flex-wrap:wrap;gap:8px;margin-bottom:6px;">
            <button type="button" class="ziara-faqs__tab is-active" data-cat="all" style="padding:8px 18px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;cursor:pointer;border:1.5px solid var(--gold);background:var(--gold);color:var(--maroon-deep);">All</button>
            @foreach($tabs as $cat => $label)
                <button type="button" class="ziara-faqs__tab" data-cat="{{ $cat }}" style="padding:8px 18px;font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;border-radius:50px;cursor:pointer;border:1.5px solid var(--line);background:#fff;color:var(--muted);">{{ $label }}</button>
            @endforeach
        </div>
    @endif
    @foreach($faqs as $faq)
        <div class="ziara-faq" data-cat="{{ $faq['cat'] ?? 'all' }}">
            <button type="button" class="ziara-faq__q">
                <span>{{ $faq['q'] }}</span>
                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
            </button>
            <div class="ziara-faq__a"><p style="margin:0;">{!! $faq['a'] !!}</p></div>
        </div>
    @endforeach
    <p class="ziara-faqs__empty" style="display:none;text-align:center;color:var(--muted);font-size:14px;margin:18px 0 0;">No matching questions found.</p>
</div>
<script>
(function(){
    'use strict';
    var box = document.getElementById('{{ $fid }}');
    if (!box || box.dataset.ziaraFaqReady) return;
    box.dataset.ziaraFaqReady = '1';
    var items = box.querySelectorAll('.ziara-faq');
    var tabs = box.querySelectorAll('.ziara-faqs__tab');
    var empty = box.querySelector('.ziara-faqs__empty');
    function toggleOne(btn){
        var item = btn.closest('.ziara-faq');
        var ans = item.querySelector('.ziara-faq__a');
        var open = item.classList.contains('is-open');
        items.forEach(function(el){ el.classList.remove('is-open'); el.querySelector('.ziara-faq__a').style.display='none'; });
        if(!open){ item.classList.add('is-open'); ans.style.display='block'; }
    }
    box.querySelectorAll('.ziara-faq__q').forEach(function(btn){ btn.addEventListener('click', function(){ toggleOne(btn); }); });
    if(tabs.length){
        tabs.forEach(function(tab){
            tab.addEventListener('click', function(){
                tabs.forEach(function(t){ t.classList.remove('is-active'); t.style.background='#fff'; t.style.color='var(--muted)'; });
                tab.classList.add('is-active'); tab.style.background='var(--gold)'; tab.style.color='var(--maroon-deep)';
                var cat = tab.dataset.cat, visible = 0;
                items.forEach(function(el){
                    var show = cat === 'all' || el.dataset.cat === cat;
                    el.style.display = show ? '' : 'none';
                    if(show) visible++;
                });
                if(empty) empty.style.display = visible ? 'none' : 'block';
            });
        });
    }
})();
</script>