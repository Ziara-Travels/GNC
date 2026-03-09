<?php
// config/seo.php

return [
    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all SEO-related configurations for tracking scripts,
    | verification codes, and other SEO tools integration.
    |
    */

    // Google Analytics and Tag Manager
    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID', 'G-XXXXXXXXXX'),
    'google_tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID', 'GTM-XXXXXXX'),
    
    // Microsoft Clarity
    'microsoft_clarity_id' => env('MICROSOFT_CLARITY_ID', 'xxxxxxxxxx'),
    
    // Social Media Tracking
    'facebook_pixel_id' => env('FACEBOOK_PIXEL_ID', '123456789012345'),
    'facebook_app_id' => env('FACEBOOK_APP_ID'),
    'twitter_handle' => '@ziaratravel',
    'twitter_pixel_id' => env('TWITTER_PIXEL_ID', 'xxxxxxxxx'),
    
    // Search Engine Verification
    'google_verification' => env('GOOGLE_SITE_VERIFICATION', 'google123456789abcdef'),
    'google_search_console_verification' => env('GOOGLE_SEARCH_CONSOLE_VERIFICATION', 'your-google-verification-code'),
    'bing_verification' => env('BING_SITE_VERIFICATION', 'bing123456789abcdef'),
    'yandex_verification' => env('YANDEX_SITE_VERIFICATION', 'yandex123456789abcdef'),
    
    // Additional Tracking Tools
    'hotjar_id' => env('HOTJAR_ID', '1234567'),
    'linkedin_insight_id' => env('LINKEDIN_INSIGHT_ID', '1234567'),
    'pinterest_tag_id' => env('PINTEREST_TAG_ID', '1234567890123'),
    
    // SEO Meta Defaults
    'default_title' => env('SEO_DEFAULT_TITLE', 'Ziara Travel - Book Flights & Hotels | Best Travel Deals Kenya & Worldwide'),
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', 'Book cheap flights and hotels with Ziara Travel. Find the best travel deals for domestic and international flights, hotels, vacation packages in Kenya and worldwide.'),
    'default_keywords' => env('SEO_DEFAULT_KEYWORDS', 'flight booking, hotel booking, cheap flights, travel deals, Kenya flights, international flights, vacation packages, travel agency Kenya'),
    'default_og_image' => '/images/og/default-og.jpg',
    
    // Social Media Links
    'social_media' => [
        'facebook' => env('SOCIAL_FACEBOOK', 'https://www.facebook.com/Ziaratravel'),
        'twitter' => env('SOCIAL_TWITTER', 'https://www.twitter.com/Ziaratravel'),
        'instagram' => env('SOCIAL_INSTAGRAM', 'https://www.instagram.com/Ziaratravel'),
        'linkedin' => env('SOCIAL_LINKEDIN', 'https://www.linkedin.com/company/Ziaratravel'),
        'youtube' => env('SOCIAL_YOUTUBE', 'https://www.youtube.com/c/Ziaratravel'),
        'tiktok' => env('SOCIAL_TIKTOK', 'https://www.tiktok.com/@Ziaratravel'),
    ],
    
    // API Keys for SEO Tools
    'google_pagespeed_api_key' => env('GOOGLE_PAGESPEED_API_KEY'),
    'serpstack_api_key' => env('SERPSTACK_API_KEY'),
    'semrush_api_key' => env('SEMRUSH_API_KEY'),
    
    // Schema.org Organization Data
    'organization' => [
        'name' => 'Ziara Travel',
        'legal_name' => 'Ziara Travel Limited',
        'url' => env('APP_URL'),
        'logo' => env('APP_URL') . '/images/logo.png',
        'phone' => '+254-700-123-456',
        'email' => 'info@Ziaratravel.com',
        'address' => [
            'street_address' => 'Nairobi CBD',
            'locality' => 'Nairobi',
            'region' => 'Nairobi',
            'postal_code' => '00100',
            'country' => 'KE'
        ]
    ],
    
    // Tracking Settings
    'enable_tracking' => env('ENABLE_TRACKING', true),
    'track_logged_in_users' => env('TRACK_LOGGED_IN_USERS', false),
    'track_admin_users' => env('TRACK_ADMIN_USERS', false),
    
    // Performance Settings
    'cache_sitemaps' => env('CACHE_SITEMAPS', true),
    'sitemap_cache_duration' => env('SITEMAP_CACHE_DURATION', 3600), // 1 hour
    'structured_data_cache_duration' => env('STRUCTURED_DATA_CACHE_DURATION', 86400), // 24 hours
];