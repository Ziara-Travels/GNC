
@extends('layouts.main')

@section('title', 'News & Updates - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <div class="relative py-16 md:py-24 bg-cover bg-center" style="background-image: url('{{ asset('images/backgrounds/news-bg.jpg') }}');">
        <div class="absolute inset-0 bg-blue-900 opacity-60"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">News & Updates</h1>
            <p class="text-xl text-white max-w-3xl mx-auto">Stay informed about travel trends, company announcements, and industry insights</p>
        </div>
    </div>

    <!-- Featured News Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                        <img src="{{ asset('images/news/featured-news.jpg') }}" alt="Featured News" class="rounded-lg shadow-lg">
                    </div>
                    <div class="md:w-1/2">
                        <span class="text-primary-600 font-semibold">Featured News</span>
                        <h2 class="text-3xl font-bold mb-4">Ziara Travel Expands Operations to Southern Africa</h2>
                        <p class="text-gray-500 mb-4">
                            <i class="far fa-calendar mr-2"></i> August 1, 2025
                        </p>
                        <p class="text-gray-600 mb-4">
                            We're excited to announce our expansion into Southern Africa, with new offices opening in Cape Town, South Africa and Gaborone, Botswana. This strategic move allows us to better serve our clients looking to explore the incredible destinations in this region.
                        </p>
                        <p class="text-gray-600 mb-6">
                            Our expanded team includes local travel experts who bring invaluable knowledge and connections to enhance your travel experiences throughout Southern Africa.
                        </p>
                        <a href="#" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2 px-6 rounded-md transition duration-300">
                            Read Full Article
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest News Section -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Latest News & Updates</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Stay up-to-date with the latest developments at Ziara Travel and in the travel industry
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- News Card 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/news/news-1.jpg') }}" alt="News Article" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-gray-500 text-sm mb-2">
                            <i class="far fa-calendar mr-1"></i> July 25, 2025
                        </p>
                        <h3 class="font-bold text-xl mb-2">New Luxury Safari Packages Launched</h3>
                        <p class="text-gray-600 mb-4">
                            Discover our newly curated luxury safari experiences featuring exclusive lodges and private guides for an unforgettable adventure.
                        </p>
                        <a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Read More →</a>
                    </div>
                </div>
                
                <!-- News Card 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/news/news-2.jpg') }}" alt="News Article" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-gray-500 text-sm mb-2">
                            <i class="far fa-calendar mr-1"></i> July 18, 2025
                        </p>
                        <h3 class="font-bold text-xl mb-2">Ziara Travel Wins Service Excellence Award</h3>
                        <p class="text-gray-600 mb-4">
                            We're honored to receive the prestigious Travel Service Excellence Award for the third consecutive year, recognizing our commitment to customer satisfaction.
                        </p>
                        <a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Read More →</a>
                    </div>
                </div>
                
                <!-- News Card 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/news/news-3.jpg') }}" alt="News Article" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-gray-500 text-sm mb-2">
                            <i class="far fa-calendar mr-1"></i> July 10, 2025
                        </p>
                        <h3 class="font-bold text-xl mb-2">New Partnership with Sustainable Tourism Alliance</h3>
                        <p class="text-gray-600 mb-4">
                            We've joined forces with the Sustainable Tourism Alliance to further our commitment to environmentally responsible and community-beneficial travel practices.
                        </p>
                        <a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Read More →</a>
                    </div>
                </div>
                
                <!-- News Card 4 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/news/news-4.jpg') }}" alt="News Article" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-gray-500 text-sm mb-2">
                            <i class="far fa-calendar mr-1"></i> July 5, 2025
                        </p>
                        <h3 class="font-bold text-xl mb-2">Mobile App Launch: Book Flights On-The-Go</h3>
                        <p class="text-gray-600 mb-4">
                            Our new mobile app is now available, making it easier than ever to search, compare, and book flights directly from your smartphone.
                        </p>
                        <a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Read More →</a>
                    </div>
                </div>
                
                <!-- News Card 5 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/news/news-5.jpg') }}" alt="News Article" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-gray-500 text-sm mb-2">
                            <i class="far fa-calendar mr-1"></i> June 28, 2025
                        </p>
                        <h3 class="font-bold text-xl mb-2">Special Summer Promotion: Family Packages</h3>
                        <p class="text-gray-600 mb-4">
                            Take advantage of our special summer promotion featuring discounted rates on family travel packages to popular destinations.
                        </p>
                        <a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Read More →</a>
                    </div>
                </div>
                
                <!-- News Card 6 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/news/news-6.jpg') }}" alt="News Article" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <p class="text-gray-500 text-sm mb-2">
                            <i class="far fa-calendar mr-1"></i> June 20, 2025
                        </p>
                        <h3 class="font-bold text-xl mb-2">Travel Industry Trends: What to Expect in 2026</h3>
                        <p class="text-gray-600 mb-4">
                            Our travel experts analyze the emerging trends in the industry and what travelers can expect in the coming year.
                        </p>
                        <a href="#" class="text-primary-600 hover:text-primary-800 font-medium">Read More →</a>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <a href="#" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-8 rounded-md transition duration-300">
                    Load More News
                </a>
            </div>
        </div>
    </div>
    
    <!-- Newsletter Section -->
    <div class="py-16 bg-blue-800 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
                <p class="text-xl mb-8 opacity-90">
                    Subscribe to our newsletter to receive the latest news, travel tips, and exclusive offers directly in your inbox.
                </p>
                <div class="max-w-md mx-auto">
                    <form class="flex flex-col sm:flex-row gap-4">
                        <input type="email" placeholder="Enter your email address" class="flex-grow px-4 py-3 rounded-md text-gray-800 focus:outline-none">
                        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-semibold py-3 px-6 rounded-md transition duration-300">
                            Subscribe
                        </button>
                    </form>
                    <p class="mt-4 text-sm opacity-80">
                        We respect your privacy. You can unsubscribe at any time.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Any JavaScript functionality can be added here
});
</script>
@endsection