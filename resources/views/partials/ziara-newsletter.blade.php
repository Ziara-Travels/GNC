{{--
    Ziara newsletter CTA band (clone style). Optional $label/$title/$desc.
--}}
@php($_nl_title = $title ?? 'Stay Updated');
@php($_nl_label = $label ?? 'Newsletter');
@php($_nl_desc  = $desc  ?? 'Subscribe for travel tips, exclusive deals, and company news straight to your inbox.');
@php($_nl_id    = $id ?? ('nl-' . substr(md5($_nl_title), 0, 8)));
<section class="zt-section ziara-cta-band" style="padding:72px 0;">
    <div class="container">
        <div class="gsc-heading gsc-heading--light align-center style-1 widget auto-responsive" style="margin-bottom:30px;">
            <div class="content-inner">
                <div class="sub-title"><span>{{ $_nl_label }}</span></div>
                <h2 class="title"><span>{{ $_nl_title }}</span></h2>
                <div class="title-desc" style="margin-left:auto;margin-right:auto;max-width:520px;">{{ $_nl_desc }}</div>
            </div>
        </div>
        <form class="ziara-nl-form" style="max-width:520px;margin:0 auto;display:flex;gap:10px;flex-wrap:wrap;justify-content:center;" onsubmit="event.preventDefault(); var f=this; var em=f.querySelector('input[type=email]'); if(em && em.checkValidity()){ var b=f.querySelector('button'); if(b){ b.disabled=true; b.innerHTML='<i class=\'fa-solid fa-circle-check\' aria-hidden=\'true\'></i> Subscribed!'; } } else if(em){ em.focus(); }">
            <input type="email" placeholder="Your email address" required style="flex:1;min-width:220px;padding:15px 20px;font-size:15px;color:#2c241f;background:#fff;border:2px solid transparent;border-radius:50px;outline:none;" class="ziara-nl-form__input">
            <button type="submit" class="ziara-btn ziara-btn--gold">Subscribe</button>
        </form>
    </div>
</section>