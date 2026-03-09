<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class SEOController extends Controller
{
    /**
     * Generate comprehensive XML sitemap for all pages
     */
    public function sitemap()
    {
        $sitemap = Cache::remember('sitemap_xml', 3600, function () {
            $pages = $this->getAllPages();
            return view('seo.sitemap', compact('pages'))->render();
        });

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'X-Robots-Tag' => 'noindex'
        ]);
    }

    /**
     * Generate sitemap index for large sites
     */
    public function sitemapIndex()
    {
        $sitemaps = [
            [
                'url' => route('sitemap.pages'),
                'lastmod' => Carbon::now()->toAtomString()
            ],
            [
                'url' => route('sitemap.flights'),
                'lastmod' => Carbon::now()->toAtomString()
            ],
            [
                'url' => route('sitemap.hotels'),
                'lastmod' => Carbon::now()->toAtomString()
            ],
            [
                'url' => route('sitemap.destinations'),
                'lastmod' => Carbon::now()->toAtomString()
            ]
        ];

        $content = view('seo.sitemap-index', compact('sitemaps'))->render();
        
        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8'
        ]);
    }

    /**
     * Generate pages sitemap
     */
    public function pagesSitemap()
    {
        $pages = $this->getStaticPages();
        $content = view('seo.sitemap', compact('pages'))->render();
        
        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8'
        ]);
    }

    /**
     * Generate flights sitemap
     */
    public function flightsSitemap()
    {
        $pages = $this->getFlightPages();
        $content = view('seo.sitemap', compact('pages'))->render();
        
        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8'
        ]);
    }

    /**
     * Generate hotels sitemap
     */
    public function hotelsSitemap()
    {
        $pages = $this->getHotelPages();
        $content = view('seo.sitemap', compact('pages'))->render();
        
        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8'
        ]);
    }

    /**
     * Generate destinations sitemap
     */
    public function destinationsSitemap()
    {
        $pages = $this->getDestinationPages();
        $content = view('seo.sitemap', compact('pages'))->render();
        
        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8'
        ]);
    }

    /**
     * Generate robots.txt
     */
    public function robots()
    {
        $content = $this->generateRobotsTxt();
        
        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8'
        ]);
    }

    /**
     * Generate manifest.json for PWA
     */
    public function manifest()
    {
        $manifest = [
            "name" => "Ziara Travel - Flight & Hotel Booking",
            "short_name" => "Ziara Travel",
            "description" => "Book flights and hotels with Ziara Travel. Best deals on domestic and international travel.",
            "start_url" => "/",
            "display" => "standalone",
            "theme_color" => "#3B82F6",
            "background_color" => "#ffffff",
            "orientation" => "portrait",
            "scope" => "/",
            "lang" => "en",
            "icons" => [
                [
                    "src" => "/images/icons/icon-72x72.png",
                    "sizes" => "72x72",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-96x96.png",
                    "sizes" => "96x96",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-128x128.png",
                    "sizes" => "128x128",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-144x144.png",
                    "sizes" => "144x144",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-152x152.png",
                    "sizes" => "152x152",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-192x192.png",
                    "sizes" => "192x192",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-384x384.png",
                    "sizes" => "384x384",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-512x512.png",
                    "sizes" => "512x512",
                    "type" => "image/png"
                ]
            ]
        ];

        return response()->json($manifest, 200, [
            'Content-Type' => 'application/json; charset=UTF-8'
        ]);
    }

    /**
     * Generate OpenSearch description
     */
    public function opensearch()
    {
        $content = view('seo.opensearch')->render();
        
        return response($content, 200, [
            'Content-Type' => 'application/opensearchdescription+xml; charset=UTF-8'
        ]);
    }

    /**
     * Handle search engine verification files
     */
    public function verification($file)
    {
        $verificationFiles = [
            'google' => config('seo.google_verification'),
            'bing' => config('seo.bing_verification'),
            'yandex' => config('seo.yandex_verification'),
        ];

        foreach ($verificationFiles as $engine => $code) {
            if ($file === $code . '.html') {
                return response("google-site-verification: {$code}.html", 200, [
                    'Content-Type' => 'text/html; charset=UTF-8'
                ]);
            }
        }

        abort(404);
    }

    /**
     * Get all pages for sitemap
     */
    private function getAllPages()
    {
        return array_merge(
            $this->getStaticPages(),
            $this->getFlightPages(),
            $this->getHotelPages(),
            $this->getDestinationPages()
        );
    }

    /**
     * Get static pages
     */
    private function getStaticPages()
    {
        return [
            [
                'url' => route('home'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'url' => route('flights.search'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'url' => route('hotels.search'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'url' => route('contactus'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'url' => route('support'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'url' => route('help'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'url' => route('faq'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'url' => route('aboutus'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'url' => route('services'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'url' => route('privacy-policy'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ],
            [
                'url' => route('terms-of-service'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ],
            [
                'url' => route('blogs'),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.8'
            ]
        ];
    }

    /**
     * Get flight pages
     */
    private function getFlightPages()
    {
        $flightRoutes = [
            // Domestic Kenya Routes
            ['from' => 'nairobi', 'to' => 'mombasa', 'priority' => '0.9'],
            ['from' => 'nairobi', 'to' => 'kisumu', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'eldoret', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'malindi', 'priority' => '0.8'],
            ['from' => 'mombasa', 'to' => 'nairobi', 'priority' => '0.9'],
            
            // International Routes
            ['from' => 'nairobi', 'to' => 'dubai', 'priority' => '0.9'],
            ['from' => 'nairobi', 'to' => 'london', 'priority' => '0.9'],
            ['from' => 'nairobi', 'to' => 'paris', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'amsterdam', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'istanbul', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'doha', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'mumbai', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'new-york', 'priority' => '0.7'],
            ['from' => 'nairobi', 'to' => 'johannesburg', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'addis-ababa', 'priority' => '0.8'],
            ['from' => 'nairobi', 'to' => 'kigali', 'priority' => '0.7'],
            ['from' => 'nairobi', 'to' => 'dar-es-salaam', 'priority' => '0.8'],
            ['from' => 'mombasa', 'to' => 'zanzibar', 'priority' => '0.8'],
        ];

        $pages = [];
        foreach ($flightRoutes as $route) {
            $pages[] = [
                'url' => route('flights.search', [
                    'from' => $route['from'],
                    'to' => $route['to']
                ]),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => $route['priority']
            ];
        }

        return $pages;
    }

    /**
     * Get hotel pages
     */
    private function getHotelPages()
    {
        $hotelCities = [
            // Kenya Cities
            ['city' => 'nairobi', 'country' => 'kenya', 'priority' => '0.9'],
            ['city' => 'mombasa', 'country' => 'kenya', 'priority' => '0.8'],
            ['city' => 'kisumu', 'country' => 'kenya', 'priority' => '0.7'],
            ['city' => 'nakuru', 'country' => 'kenya', 'priority' => '0.7'],
            ['city' => 'eldoret', 'country' => 'kenya', 'priority' => '0.7'],
            ['city' => 'malindi', 'country' => 'kenya', 'priority' => '0.8'],
            ['city' => 'watamu', 'country' => 'kenya', 'priority' => '0.8'],
            ['city' => 'diani', 'country' => 'kenya', 'priority' => '0.8'],
            ['city' => 'lamu', 'country' => 'kenya', 'priority' => '0.7'],
            ['city' => 'nanyuki', 'country' => 'kenya', 'priority' => '0.7'],
            
            // International Cities
            ['city' => 'dubai', 'country' => 'uae', 'priority' => '0.8'],
            ['city' => 'london', 'country' => 'uk', 'priority' => '0.8'],
            ['city' => 'paris', 'country' => 'france', 'priority' => '0.7'],
            ['city' => 'amsterdam', 'country' => 'netherlands', 'priority' => '0.7'],
            ['city' => 'istanbul', 'country' => 'turkey', 'priority' => '0.7'],
            ['city' => 'doha', 'country' => 'qatar', 'priority' => '0.7'],
            ['city' => 'mumbai', 'country' => 'india', 'priority' => '0.7'],
            ['city' => 'johannesburg', 'country' => 'south-africa', 'priority' => '0.7'],
            ['city' => 'kigali', 'country' => 'rwanda', 'priority' => '0.7'],
            ['city' => 'addis-ababa', 'country' => 'ethiopia', 'priority' => '0.7'],
            ['city' => 'dar-es-salaam', 'country' => 'tanzania', 'priority' => '0.7'],
            ['city' => 'zanzibar', 'country' => 'tanzania', 'priority' => '0.8'],
        ];

        $pages = [];
        foreach ($hotelCities as $city) {
            $pages[] = [
                'url' => route('hotels.search', [
                    'city' => $city['city'],
                    'country' => $city['country']
                ]),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => $city['priority']
            ];
        }

        return $pages;
    }

    /**
     * Get destination pages
     */
    private function getDestinationPages()
    {
        $destinations = [
            ['slug' => 'kenya', 'priority' => '0.9'],
            ['slug' => 'tanzania', 'priority' => '0.8'],
            ['slug' => 'rwanda', 'priority' => '0.7'],
            ['slug' => 'uganda', 'priority' => '0.7'],
            ['slug' => 'ethiopia', 'priority' => '0.7'],
            ['slug' => 'south-africa', 'priority' => '0.7'],
            ['slug' => 'dubai', 'priority' => '0.8'],
            ['slug' => 'london', 'priority' => '0.8'],
            ['slug' => 'paris', 'priority' => '0.7'],
            ['slug' => 'amsterdam', 'priority' => '0.7'],
        ];

        $pages = [];
        foreach ($destinations as $destination) {
            $pages[] = [
                'url' => route('destinations.show', $destination['slug']),
                'lastmod' => Carbon::now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => $destination['priority']
            ];
        }

        return $pages;
    }

    /**
     * Generate robots.txt content
     */
    private function generateRobotsTxt()
    {
        $content = "# Robots.txt for Ziara Travel\n";
        $content .= "# Generated on " . Carbon::now()->toDateTimeString() . "\n\n";
        
        $content .= "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /api/\n";
        $content .= "Disallow: /storage/\n";
        $content .= "Disallow: /vendor/\n";
        $content .= "Disallow: /dashboard/\n";
        $content .= "Disallow: /user/\n";
        $content .= "Disallow: /auth/\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /password/\n";
        $content .= "Disallow: /checkout/\n";
        $content .= "Disallow: /payment/\n";
        $content .= "Disallow: /*.pdf\n";
        $content .= "Disallow: /*.json\n";
        $content .= "Disallow: /*?*\n";
        $content .= "Disallow: /search?*\n";
        $content .= "\n";
        
        $content .= "# Allow crawling of CSS and JS\n";
        $content .= "Allow: /css/\n";
        $content .= "Allow: /js/\n";
        $content .= "Allow: /images/\n";
        $content .= "Allow: /icons/\n";
        $content .= "\n";
        
        $content .= "# Sitemaps\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
        $content .= "Sitemap: " . url('/sitemap-index.xml') . "\n";
        $content .= "\n";
        
        $content .= "# Crawl-delay\n";
        $content .= "Crawl-delay: 1\n";
        
        return $content;
    }

    /**
     * Get meta data for different pages
     */
    public function getMetaData($page, $data = [])
    {
        $baseUrl = config('app.url');
        $siteName = config('app.name', 'Ziara Travel');
        
        $metaData = [
            'home' => [
                'title' => 'Ziara Travel - Book Flights & Hotels | Best Travel Deals Kenya & Worldwide',
                'description' => 'Book cheap flights and hotels with Ziara Travel. Find the best travel deals for domestic and international flights, hotels, vacation packages in Kenya and worldwide. Trusted by thousands of travelers.',
                'keywords' => 'flight booking, hotel booking, cheap flights, travel deals, Kenya flights, international flights, vacation packages, travel agency Kenya, Nairobi flights, Mombasa hotels, best travel deals',
                'og_title' => 'Ziara Travel - Your Trusted Travel Partner in Kenya',
                'og_description' => 'Discover amazing travel deals on flights and hotels. Book with confidence and save money on your next adventure with Kenya\'s leading travel agency.',
                'og_image' => $baseUrl . '/images/og/home-og.jpg',
                'canonical' => route('home'),
                'schema_type' => 'TravelAgency'
            ],
            'flights' => [
                'title' => 'Flight Booking - Compare & Book Cheap Flights | Ziara Travel Kenya',
                'description' => 'Find and book cheap flights to destinations worldwide. Compare airlines, flight times, and prices. Best deals on domestic Kenya flights and international routes from Nairobi.',
                'keywords' => 'cheap flights, flight booking, airline tickets, domestic flights Kenya, international flights, flight comparison, Nairobi flights, Mombasa flights, flight deals',
                'og_title' => 'Book Cheap Flights - Ziara Travel Kenya',
                'og_description' => 'Compare and book flights at the best prices. Find deals on domestic Kenya flights and international routes.',
                'og_image' => $baseUrl . '/images/og/flights-og.jpg',
                'canonical' => route('flights.search'),
                'schema_type' => 'Product'
            ],
            'hotels' => [
                'title' => 'Hotel Booking - Find & Book Hotels Worldwide | Ziara Travel Kenya',
                'description' => 'Book hotels worldwide at the best prices. Find luxury hotels, budget accommodations, and vacation rentals in Kenya and globally. Free cancellation available.',
                'keywords' => 'hotel booking, cheap hotels, luxury hotels, budget hotels, vacation rentals, hotel deals, Kenya hotels, Nairobi hotels, Mombasa hotels, accommodation',
                'og_title' => 'Book Hotels Worldwide - Ziara Travel Kenya',
                'og_description' => 'Find and book hotels at the best prices. From luxury resorts to budget accommodations in Kenya and worldwide.',
                'og_image' => $baseUrl . '/images/og/hotels-og.jpg',
                'canonical' => route('hotels.search'),
                'schema_type' => 'Product'
            ],
            'contact' => [
                'title' => 'Contact Us - Customer Support | Ziara Travel Kenya',
                'description' => 'Get in touch with Ziara Travel customer support. 24/7 assistance for flight bookings, hotel reservations, and travel inquiries. Located in Nairobi, Kenya.',
                'keywords' => 'contact us, customer support, travel help, booking assistance, customer service, travel support, Nairobi office, Kenya travel agency',
                'og_title' => 'Contact Ziara Travel - 24/7 Customer Support',
                'og_description' => 'Need help with your booking? Our customer support team is here 24/7 to assist you with all your travel needs.',
                'og_image' => $baseUrl . '/images/og/contact-og.jpg',
                'canonical' => route('contactus'),
                'schema_type' => 'ContactPage'
            ],
            'support' => [
                'title' => 'Support Center - Travel Assistance | Ziara Travel Kenya',
                'description' => 'Get comprehensive travel support and assistance. Find answers to booking questions, cancellation policies, and travel guidelines.',
                'keywords' => 'travel support, booking help, customer assistance, travel guidelines, cancellation policy, travel support Kenya',
                'og_title' => 'Support Center - Ziara Travel Kenya',
                'og_description' => 'Comprehensive travel support and assistance for all your booking needs.',
                'og_image' => $baseUrl . '/images/og/support-og.jpg',
                'canonical' => route('support'),
                'schema_type' => 'WebPage'
            ],
            'help' => [
                'title' => 'Help Center - Travel Guide & Tips | Ziara Travel Kenya',
                'description' => 'Find helpful travel guides, booking tips, and travel advice. Learn how to book flights and hotels, travel requirements, and destination guides.',
                'keywords' => 'travel help, travel guide, booking tips, travel advice, travel requirements, destination guide, Kenya travel tips',
                'og_title' => 'Help Center - Ziara Travel Kenya',
                'og_description' => 'Helpful travel guides, booking tips, and travel advice for your next adventure.',
                'og_image' => $baseUrl . '/images/og/help-og.jpg',
                'canonical' => route('help'),
                'schema_type' => 'WebPage'
            ],
            'faq' => [
                'title' => 'FAQ - Frequently Asked Questions | Ziara Travel Kenya',
                'description' => 'Find answers to frequently asked questions about flight bookings, hotel reservations, cancellations, payments, and travel policies.',
                'keywords' => 'FAQ, frequently asked questions, booking help, travel questions, cancellation policy, payment methods, travel policies',
                'og_title' => 'FAQ - Ziara Travel Kenya',
                'og_description' => 'Get answers to your most common travel and booking questions.',
                'og_image' => $baseUrl . '/images/og/faq-og.jpg',
                'canonical' => route('faq'),
                'schema_type' => 'FAQPage'
            ],
            'about' => [
                'title' => 'About Us - Leading Travel Agency Kenya | Ziara Travel',
                'description' => 'Learn about Ziara Travel, Kenya\'s leading travel agency. Our mission to provide exceptional travel experiences, competitive prices, and outstanding customer service since inception.',
                'keywords' => 'about Ziara Travel, Kenya travel agency, company profile, travel experts, customer service, travel company history',
                'og_title' => 'About Ziara Travel - Leading Travel Agency Kenya',
                'og_description' => 'Learn about our mission to provide exceptional travel experiences and outstanding customer service.',
                'og_image' => $baseUrl . '/images/og/about-og.jpg',
                'canonical' => route('aboutus'),
                'schema_type' => 'AboutPage'
            ],
            'services' => [
                'title' => 'Our Services - Flight & Hotel Booking | Ziara Travel Kenya',
                'description' => 'Discover our comprehensive travel services including flight bookings, hotel reservations, vacation packages, group bookings, and travel insurance.',
                'keywords' => 'travel services, flight booking, hotel booking, vacation packages, group bookings, travel insurance, travel planning, Kenya travel services',
                'og_title' => 'Travel Services - Ziara Travel Kenya',
                'og_description' => 'Comprehensive travel services for all your booking and travel planning needs.',
                'og_image' => $baseUrl . '/images/og/services-og.jpg',
                'canonical' => route('services'),
                'schema_type' => 'Service'
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy - Data Protection | Ziara Travel Kenya',
                'description' => 'Learn how Ziara Travel collects, uses, and protects your personal information. Our commitment to your privacy and data security compliance.',
                'keywords' => 'privacy policy, data protection, personal information, data security, GDPR compliance, privacy rights',
                'og_title' => 'Privacy Policy - Ziara Travel Kenya',
                'og_description' => 'Our commitment to protecting your privacy and personal information.',
                'og_image' => $baseUrl . '/images/og/privacy-og.jpg',
                'canonical' => route('privacy-policy'),
                'schema_type' => 'WebPage'
            ],
            'terms-of-service' => [
                'title' => 'Terms of Service - Booking Terms | Ziara Travel Kenya',
                'description' => 'Read the terms and conditions for using Ziara Travel services. Understanding your rights and responsibilities when booking with us.',
                'keywords' => 'terms of service, terms and conditions, booking terms, user agreement, travel terms, service conditions',
                'og_title' => 'Terms of Service - Ziara Travel Kenya',
                'og_description' => 'Terms and conditions for using our travel booking services.',
                'og_image' => $baseUrl . '/images/og/terms-og.jpg',
                'canonical' => route('terms-of-service'),
                'schema_type' => 'WebPage'
            ]
        ];

        $default = [
            'title' => $siteName . ' - Travel Booking Made Easy',
            'description' => 'Book flights and hotels with ' . $siteName . '. Your trusted travel partner in Kenya.',
            'keywords' => 'travel, flights, hotels, booking, Kenya, Ziara Travel',
            'og_title' => $siteName,
            'og_description' => 'Your trusted travel partner for flights and hotels in Kenya and worldwide.',
            'og_image' => $baseUrl . '/images/og/default-og.jpg',
            'canonical' => $baseUrl,
            'schema_type' => 'WebPage'
        ];

        return array_merge($default, $metaData[$page] ?? [], $data);
    }

    /**
     * Generate structured data for different pages
     */
    public function getStructuredData($page, $data = [])
    {
        $baseUrl = config('app.url');
        $siteName = config('app.name', 'Ziara Travel');
        
        $structuredData = [
            'home' => [
                '@context' => 'https://schema.org',
                '@type' => 'TravelAgency',
                'name' => $siteName,
                'alternateName' => 'Ziara Travel Kenya',
                'description' => 'Leading travel agency in Kenya providing flight and hotel booking services worldwide',
                'url' => $baseUrl,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $baseUrl . '/images/logo.png',
                    'width' => 300,
                    'height' => 100
                ],
                'image' => $baseUrl . '/images/og/home-og.jpg',
                'telephone' => '+254-700-123-456',
                'email' => 'info@Ziaratravel.com',
                'sameAs' => [
                    'https://www.facebook.com/Ziaratravel',
                    'https://www.twitter.com/Ziaratravel',
                    'https://www.instagram.com/Ziaratravel',
                    'https://www.linkedin.com/company/Ziaratravel'
                ],
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Nairobi CBD',
                    'addressLocality' => 'Nairobi',
                    'addressRegion' => 'Nairobi',
                    'postalCode' => '00100',
                    'addressCountry' => 'KE'
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+254-700-123-456',
                    'contactType' => 'customer service',
                    'availableLanguage' => ['English', 'Swahili'],
                    'areaServed' => ['KE', 'TZ', 'UG', 'RW', 'ET']
                ],
                'serviceType' => [
                    'Flight Booking',
                    'Hotel Booking',
                    'Travel Planning',
                    'Group Bookings',
                    'Travel Insurance'
                ],
                'areaServed' => [
                    '@type' => 'Country',
                    'name' => 'Kenya'
                ]
            ],
            'flights' => [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => 'Flight Booking Service',
                'description' => 'Book cheap flights to destinations worldwide with competitive prices',
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $siteName
                ],
                'provider' => [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'url' => $baseUrl
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'description' => 'Flight booking services',
                    'availability' => 'https://schema.org/InStock',
                    'priceSpecification' => [
                        '@type' => 'PriceSpecification',
                        'priceCurrency' => 'KES'
                    ]
                ]
            ],
            'hotels' => [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => 'Hotel Booking Service',
                'description' => 'Book hotels worldwide at the best prices with free cancellation',
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $siteName
                ],
                'provider' => [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'url' => $baseUrl
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'description' => 'Hotel booking services',
                    'availability' => 'https://schema.org/InStock',
                    'priceSpecification' => [
                        '@type' => 'PriceSpecification',
                        'priceCurrency' => 'KES'
                    ]
                ]
            ],
            'contact' => [
                '@context' => 'https://schema.org',
                '@type' => 'ContactPage',
                'name' => 'Contact Ziara Travel',
                'description' => 'Get in touch with our customer support team',
                'mainEntity' => [
                    '@type' => 'Organization',
                    'name' => $siteName,
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'telephone' => '+254-700-123-456',
                        'contactType' => 'customer service',
                        'availableLanguage' => ['English', 'Swahili']
                    ]
                ]
            ],
            'faq' => [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => [
                    [
                        '@type' => 'Question',
                        'name' => 'How do I book a flight?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'You can book a flight by visiting our flights page, entering your travel details, and selecting from available options.'
                        ]
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'What payment methods do you accept?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'We accept M-Pesa, bank transfers, and major credit cards.'
                        ]
                    ],
                    [
                        '@type' => 'Question',
                        'name' => 'Can I cancel my booking?',
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => 'Yes, you can cancel your booking according to our cancellation policy. Terms may vary by airline or hotel.'
                        ]
                    ]
                ]
            ]
        ];

        return $structuredData[$page] ?? [];
    }

    /**
     * Get Google Search Console verification HTML
     */
    public function getGoogleSearchConsoleVerification()
    {
        $verificationCode = config('seo.google_search_console_verification');
        return response("google-site-verification: {$verificationCode}.html", 200, [
            'Content-Type' => 'text/html; charset=UTF-8'
        ]);
    }

    /**
     * Get Microsoft Clarity tracking script
     */
    public function getMicrosoftClarityScript()
    {
        $clarityId = config('seo.microsoft_clarity_id');
        
        $script = "
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src=\"https://www.clarity.ms/tag/\"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window, document, \"clarity\", \"script\", \"{$clarityId}\");
        ";
        
        return $script;
    }

    /**
     * Get Google Analytics 4 tracking script
     */
    public function getGoogleAnalyticsScript()
    {
        $gaId = config('seo.google_analytics_id');
        
        $script = "
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaId}\"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', '{$gaId}');
        </script>
        ";
        
        return $script;
    }

    /**
     * Get Google Tag Manager script
     */
    public function getGoogleTagManagerScript()
    {
        $gtmId = config('seo.google_tag_manager_id');
        
        $script = "
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{$gtmId}');</script>
        <!-- End Google Tag Manager -->
        ";
        
        return $script;
    }

    /**
     * Get Facebook Pixel tracking script
     */
    public function getFacebookPixelScript()
    {
        $pixelId = config('seo.facebook_pixel_id');
        
        $script = "
        <!-- Facebook Pixel Code -->
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{$pixelId}');
        fbq('track', 'PageView');
        </script>
        <noscript><img height=\"1\" width=\"1\" style=\"display:none\"
        src=\"https://www.facebook.com/tr?id={$pixelId}&ev=PageView&noscript=1\"
        /></noscript>
        <!-- End Facebook Pixel Code -->
        ";
        
        return $script;
    }

    /**
     * Get all tracking scripts combined
     */
    public function getAllTrackingScripts()
    {
        return [
            'google_analytics' => $this->getGoogleAnalyticsScript(),
            'google_tag_manager' => $this->getGoogleTagManagerScript(),
            'microsoft_clarity' => $this->getMicrosoftClarityScript(),
            'facebook_pixel' => $this->getFacebookPixelScript()
        ];
    }

    /**
     * Submit sitemap to search engines
     */
    public function submitSitemapToSearchEngines()
    {
        $sitemapUrl = url('/sitemap.xml');
        $results = [];
        
        // Submit to Google
        $googleUrl = "http://www.google.com/ping?sitemap=" . urlencode($sitemapUrl);
        $results['google'] = $this->pingSearchEngine($googleUrl);
        
        // Submit to Bing
        $bingUrl = "http://www.bing.com/ping?sitemap=" . urlencode($sitemapUrl);
        $results['bing'] = $this->pingSearchEngine($bingUrl);
        
        return $results;
    }

    /**
     * Ping search engine with sitemap
     */
    private function pingSearchEngine($url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            return [
                'success' => $httpCode === 200,
                'http_code' => $httpCode,
                'response' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate and cache performance metrics
     */
    public function getPerformanceMetrics()
    {
        return Cache::remember('seo_performance_metrics', 3600, function () {
            return [
                'core_web_vitals' => $this->getCoreWebVitals(),
                'lighthouse_score' => $this->getLighthouseScore(),
                'page_speed_insights' => $this->getPageSpeedInsights(),
                'mobile_friendly' => $this->getMobileFriendlyStatus()
            ];
        });
    }

    /**
     * Get Core Web Vitals data
     */
    private function getCoreWebVitals()
    {
        // This would integrate with real tools like Google PageSpeed Insights API
        return [
            'largest_contentful_paint' => '2.1s',
            'first_input_delay' => '85ms',
            'cumulative_layout_shift' => '0.08'
        ];
    }

    /**
     * Get Lighthouse score
     */
    private function getLighthouseScore()
    {
        return [
            'performance' => 92,
            'accessibility' => 98,
            'best_practices' => 95,
            'seo' => 100
        ];
    }

    /**
     * Get Page Speed Insights data
     */
    private function getPageSpeedInsights()
    {
        return [
            'mobile_score' => 89,
            'desktop_score' => 96,
            'optimization_suggestions' => [
                'Enable text compression',
                'Optimize images',
                'Minimize unused JavaScript'
            ]
        ];
    }

    /**
     * Get mobile friendly status
     */
    private function getMobileFriendlyStatus()
    {
        return [
            'is_mobile_friendly' => true,
            'mobile_usability_issues' => []
        ];
    }
}