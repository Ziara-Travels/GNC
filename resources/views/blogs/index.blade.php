@extends('layouts.main')

@section('content')
<!-- Blog Hero Section -->
<section class="relative bg-gradient-to-r from-blue-900 to-indigo-800 text-white">
    <!-- Background Pattern -->
    <div class="absolute inset-0 overflow-hidden opacity-10">
        <div class="absolute left-0 top-0 w-full h-full">
            <svg viewBox="0 0 100 100" preserveAspectRatio="xMidYMid slice" class="w-full h-full">
                <defs>
                    <pattern id="blog-pattern" width="10" height="10" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="currentColor" />
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#blog-pattern)" />
            </svg>
        </div>
        <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-blue-500 blur-3xl opacity-20"></div>
        <div class="absolute -left-32 -bottom-32 w-96 h-96 rounded-full bg-indigo-500 blur-3xl opacity-20"></div>
    </div>

    <div class="container mx-auto px-4 py-24 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block px-4 py-2 rounded-full bg-white/10 text-blue-200 backdrop-blur-sm text-sm font-semibold mb-6">TRAVEL BLOG & INSIGHTS</span>
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Discover Travel Wisdom</h1>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">Expert tips, destination guides, and travel hacks to elevate your journey from ordinary to extraordinary</p>
            
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <a href="#featured-posts" class="px-6 py-3 bg-white text-blue-800 rounded-lg hover:bg-blue-50 transition font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    Browse Articles
                </a>
                <a href="#categories" class="px-6 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-600 transition font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Explore Categories
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="max-w-xl mx-auto relative">
                <div class="flex items-center bg-white rounded-lg overflow-hidden shadow-lg">
                    <input type="text" placeholder="Search for travel tips, destinations, or advice..." class="w-full px-6 py-4 outline-none text-gray-700">
                    <button class="bg-blue-600 text-white px-6 py-4 hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Posts Section -->
<section id="featured-posts" class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-blue-600 font-semibold">EDITOR'S PICKS</span>
                <h2 class="text-3xl font-bold text-gray-900">Featured Articles</h2>
            </div>
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                View all articles
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $index => $post)
                @if($index < 6)
                <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <a href="{{ route('blogs.show', $post['slug']) }}" class="block relative">
                        <div class="aspect-w-16 aspect-h-9 bg-blue-100">
                            <img src="{{ asset('images/' . $post['image']) }}" 
                                class="object-cover w-full h-full" 
                                alt="{{ $post['title'] }}">
                        </div>
                        <div class="absolute top-4 left-4">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-600 text-white">
                                {{ $post['category'] }}
                            </span>
                        </div>
                    </a>
                    
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <span class="flex items-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $post['readTime'] }}
                            </span>
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ $post['views'] }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-xl mb-2 text-gray-800">
                            <a href="{{ route('blogs.show', $post['slug']) }}" class="hover:text-blue-600">{{ $post['title'] }}</a>
                        </h3>
                        
                        <p class="text-gray-600 mb-4 line-clamp-2">{{ $post['excerpt'] }}</p>
                        
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold mr-3">
                                {{ substr($post['author'], 0, 2) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $post['author'] }}</p>
                                <p class="text-sm text-gray-500">{{ $post['date'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-semibold">EXPLORE TOPICS</span>
            <h2 class="text-3xl font-bold text-gray-900">Browse By Category</h2>
            <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Discover articles curated by topic to find exactly what you're looking for</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Flight Tips Category -->
            <a href="#" class="group">
                <div class="bg-blue-50 rounded-xl p-6 text-center hover:bg-blue-100 transition duration-300">
                    <div class="w-16 h-16 rounded-full bg-blue-100 mx-auto mb-4 flex items-center justify-center group-hover:bg-blue-200 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Flight Tips</h3>
                    <p class="text-sm text-gray-600">5 articles</p>
                </div>
            </a>
            
            <!-- Travel Safety Category -->
            <a href="#" class="group">
                <div class="bg-green-50 rounded-xl p-6 text-center hover:bg-green-100 transition duration-300">
                    <div class="w-16 h-16 rounded-full bg-green-100 mx-auto mb-4 flex items-center justify-center group-hover:bg-green-200 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Travel Safety</h3>
                    <p class="text-sm text-gray-600">3 articles</p>
                </div>
            </a>
            
            <!-- Packing Tips Category -->
            <a href="#" class="group">
                <div class="bg-amber-50 rounded-xl p-6 text-center hover:bg-amber-100 transition duration-300">
                    <div class="w-16 h-16 rounded-full bg-amber-100 mx-auto mb-4 flex items-center justify-center group-hover:bg-amber-200 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Packing Tips</h3>
                    <p class="text-sm text-gray-600">4 articles</p>
                </div>
            </a>
            
            <!-- Budget Tips Category -->
            <a href="#" class="group">
                <div class="bg-purple-50 rounded-xl p-6 text-center hover:bg-purple-100 transition duration-300">
                    <div class="w-16 h-16 rounded-full bg-purple-100 mx-auto mb-4 flex items-center justify-center group-hover:bg-purple-200 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Budget Tips</h3>
                    <p class="text-sm text-gray-600">6 articles</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- All Articles Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-blue-600 font-semibold">LATEST INSIGHTS</span>
                <h2 class="text-3xl font-bold text-gray-900">All Articles</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500">Sort by:</span>
                <select class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>Most Recent</option>
                    <option>Most Popular</option>
                    <option>Trending</option>
                </select>
            </div>
        </div>

        <!-- Article Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                <a href="{{ route('blogs.show', $post['slug']) }}" class="block relative">
                    <div class="aspect-w-16 aspect-h-9 bg-blue-100">
                        <img src="{{ asset('images/' . $post['image']) }}" 
                            class="object-cover w-full h-full" 
                            alt="{{ $post['title'] }}">
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-600 text-white">
                            {{ $post['category'] }}
                        </span>
                    </div>
                </a>
                
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <span class="flex items-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $post['readTime'] }}
                        </span>
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $post['views'] }}
                        </span>
                    </div>
                    
                    <h3 class="font-bold text-xl mb-2 text-gray-800">
                        <a href="{{ route('blogs.show', $post['slug']) }}" class="hover:text-blue-600">{{ $post['title'] }}</a>
                    </h3>
                    
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $post['excerpt'] }}</p>
                    
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold mr-3">
                            {{ substr($post['author'], 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $post['author'] }}</p>
                            <p class="text-sm text-gray-500">{{ $post['date'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <nav class="inline-flex rounded-md shadow">
                <a href="#" class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Previous</a>
                <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-blue-600 hover:bg-blue-50">1</a>
                <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">2</a>
                <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">3</a>
                <span class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>
                <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">8</a>
                <a href="#" class="px-3 py-2 border-t border-b border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">9</a>
                <a href="#" class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Next</a>
            </nav>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-16 bg-blue-900 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-3">Get Travel Tips in Your Inbox</h2>
            <p class="text-blue-200 mb-6">Join our newsletter and receive expert travel advice, destination guides, and exclusive deals</p>
            
            <form class="max-w-lg mx-auto newsletter-form" id="newsletterForm">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3 mb-3">
                    <input type="text" name="name" id="newsletterName" placeholder="Your name" class="flex-grow px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="email" name="email" id="newsletterEmail" placeholder="Your email address" class="flex-grow px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-lg font-medium transition duration-200 newsletter-submit-btn">
                        Subscribe
                    </button>
                </div>
                <p class="text-sm text-blue-300 mt-3">We respect your privacy. Unsubscribe at any time.</p>
            </form>
        </div>
    </div>
</section>

<!-- Add Toastify CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<!-- Add Toastify JS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Newsletter Subscription Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newsletterForm = document.getElementById('newsletterForm');
        
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitButton = document.querySelector('.newsletter-submit-btn');
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
                submitButton.disabled = true;
                
                const name = document.getElementById('newsletterName').value;
                const email = document.getElementById('newsletterEmail').value;
                
                // Send subscription request via fetch
                fetch('{{ route("newsletter.subscribe") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    submitButton.innerHTML = 'Subscribe';
                    submitButton.disabled = false;
                    
                    if (data.success) {
                        // Show success toast
                        Toastify({
                            text: data.message,
                            duration: 5000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#10B981",
                            stopOnFocus: true,
                            onClick: function(){}
                        }).showToast();
                        
                        // Reset form
                        newsletterForm.reset();
                    } else {
                        // Show error toast
                        Toastify({
                            text: data.message || 'Something went wrong. Please try again.',
                            duration: 5000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#EF4444",
                            stopOnFocus: true,
                            onClick: function(){}
                        }).showToast();
                    }
                })
                .catch(error => {
                    // Reset button
                    submitButton.innerHTML = 'Subscribe';
                    submitButton.disabled = false;
                    
                    // Show error toast
                    Toastify({
                        text: 'Network error. Please try again later.',
                        duration: 5000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#EF4444",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    
                    console.error('Newsletter subscription error:', error);
                });
            });
        }
    });
</script>
@endsection