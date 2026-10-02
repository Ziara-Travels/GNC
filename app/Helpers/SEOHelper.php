<?php

namespace App\Helpers;

use App\Http\Controllers\SEOController;
use Illuminate\Support\Facades\Cache;

class SEOHelper
{
    protected $seoController;

    public function __construct()
    {
        $this->seoController = new SEOController();
    }

    /**
     * Generate complete SEO meta tags
     */
    public function generateSEOTags($page, $data = [])
    {
        $meta = $this->seoController->getMetaData($page, $data);
        
        $html = '';
        
        // Basic Meta Tags
        $html .= '<title>' . htmlspecialchars($meta['title']) . '</title>' . "\n";
        $html .= '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
        $html .= '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">' . "\n";
        $html .= '<meta name="author" content="Ziara Travels">' . "\n";
        $html .= '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        $html .= '<meta name="theme-color" content="#6b2c1f">' . "\n";
        $html .= '<meta name="msapplication-TileColor" content="#6b2c1f">' . "\n";
        
        // Open Graph Tags
        $html .= '<meta property="og:type" content="website">' . "\n";
        $html .= '<meta property="og:title" content="' . htmlspecialchars($meta['og_title']) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars($meta['og_description']) . '">' . "\n";
        $html .= '<meta property="og:image" content="' . $meta['og_image'] . '">' . "\n";
        $html .= '<meta property="og:image:width" content="1200">' . "\n";
        $html .= '<meta property="og:image:height" content="630">' . "\n";
        $html .= '<meta property="og:url" content="' . $meta['canonical'] . '">' . "\n";
        $html .= '<meta property="og:site_name" content="Ziara Travel">' . "\n";
        $html .= '<meta property="og:locale" content="en_US">' . "\n";
        
        // Twitter Card Tags
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:site" content="@ziaratravel">' . "\n";
        $html .= '<meta name="twitter:creator" content="@ziaratravel">' . "\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars($meta['og_title']) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars($meta['og_description']) . '">' . "\n";
        $html .= '<meta name="twitter:image" content="' . $meta['og_image'] . '">' . "\n";
        
        // Apple Meta Tags
        $html .= '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
        $html .= '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
        $html .= '<meta name="apple-mobile-web-app-title" content="Ziara Travel">' . "\n";
        
        // Microsoft Meta Tags — tile image matches the ziaratravels.co.ke favicon
        $html .= '<meta name="msapplication-TileImage" content="' . url('/images/ziara/cropped-ziara-270x270.jpg') . '">' . "\n";
        $html .= '<meta name="msapplication-config" content="' . url('/browserconfig.xml') . '">' . "\n";
        
        // Canonical URL
        $html .= '<link rel="canonical" href="' . $meta['canonical'] . '">' . "\n";
        
        // Alternate URLs for different languages/regions
        $html .= '<link rel="alternate" hreflang="en-ke" href="' . $meta['canonical'] . '">' . "\n";
        $html .= '<link rel="alternate" hreflang="sw-ke" href="' . $meta['canonical'] . '">' . "\n";
        $html .= '<link rel="alternate" hreflang="en" href="' . $meta['canonical'] . '">' . "\n";
        
        return $html;
    }

    /**
     * Generate structured data JSON-LD
     */
    public function generateStructuredData($page, $data = [])
    {
        $structuredData = $this->seoController->getStructuredData($page, $data);
        
        if (empty($structuredData)) {
            return '';
        }
        
        return '<script type="application/ld+json">' . json_encode($structuredData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    /**
     * Generate preload and prefetch links
     */
    public function generateResourceHints()
    {
        $html = '';
        
        // DNS prefetch
        $html .= '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        $html .= '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        $html .= '<link rel="dns-prefetch" href="//unpkg.com">' . "\n";
        
        // Preconnect
        $html .= '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        $html .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        
        // Preload critical resources
        $html .= '<link rel="preload" href="' . url('/css/app.css') . '" as="style">' . "\n";
        $html .= '<link rel="preload" href="' . url('/js/app.js') . '" as="script">' . "\n";
        
        return $html;
    }

    /**
     * Generate analytics scripts
     */
    public function generateAnalyticsScripts()
    {
        $html = '';
        
        // Google Analytics 4
        if (config('seo.google_analytics_id')) {
            $ga_id = config('seo.google_analytics_id');
            $html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga_id . '"></script>' . "\n";
            $html .= '<script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag("js", new Date());
                gtag("config", "' . $ga_id . '", {
                    page_title: document.title,
                    page_location: window.location.href,
                    send_page_view: true
                });
            </script>' . "\n";
        }
        
        // Microsoft Clarity
        if (config('seo.microsoft_clarity_id')) {
            $clarity_id = config('seo.microsoft_clarity_id');
            $html .= '<script type="text/javascript">
                (function(c,l,a,r,i,t,y){
                    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                })(window, document, "clarity", "script", "' . $clarity_id . '");
            </script>' . "\n";
        }
        
        // Facebook Pixel
        if (config('seo.facebook_pixel_id')) {
            $pixel_id = config('seo.facebook_pixel_id');
            $html .= '<script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,"script",
                "https://connect.facebook.net/en_US/fbevents.js");
                fbq("init", "' . $pixel_id . '");
                fbq("track", "PageView");
            </script>' . "\n";
        }
        
        return $html;
    }
}