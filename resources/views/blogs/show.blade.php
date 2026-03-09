@extends('layouts.main')

@section('content')
<!-- Blog Post Header -->
<section class="bg-gradient-to-r from-blue-900 to-indigo-800 text-white">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-center gap-2 mb-6">
                <a href="{{ route('blogs.index') }}" class="text-blue-200 hover:text-white flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to Articles</span>
                </a>
                <span class="text-blue-200">/</span>
                <span class="text-blue-200">{{ $post['category'] }}</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-bold mb-6 text-center">
                {{ $post['title'] }}
            </h1>
            
            <div class="flex items-center justify-center mb-8">
                <div class="flex items-center mr-6">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold mr-3">
                        {{ substr($post['author'], 0, 2) }}
                    </div>
                    <div>
                        <p class="font-medium text-white">{{ $post['author'] }}</p>
                        <p class="text-blue-200 text-sm">Travel Expert</p>
                    </div>
                </div>
                <span class="mx-3 text-blue-300">•</span>
                <span class="text-blue-200">{{ $post['date'] }}</span>
                <span class="mx-3 text-blue-300">•</span>
                <span class="text-blue-200">{{ $post['readTime'] }}</span>
            </div>
            
            <div class="relative h-80 md:h-96 rounded-xl overflow-hidden shadow-xl">
                <img src="{{ asset('images/' . $post['image']) }}" 
                     class="w-full h-full object-cover" 
                     alt="{{ $post['title'] }}">
                <div class="absolute top-0 left-0 p-4">
                    <span class="inline-block bg-blue-600 text-white text-sm font-semibold px-3 py-1 rounded-full">
                        {{ $post['category'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <!-- Social Share Buttons -->
            <div class="flex justify-end mb-8">
                <div class="flex space-x-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.77,7.46H14.5V5.9a0.9,0.9,0,0,1,.94-1h2.43V0.5H14.82a4.26,4.26,0,0,0-4.52,4.58V7.46H7.5v5H10.3V23.5h4.2V12.48h3.52L18.77,7.46Z"></path>
                        </svg>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-sky-500 text-white hover:bg-sky-600">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.44,4.83A10.05,10.05,0,0,1,20.77,5.8a4.79,4.79,0,0,0-3.48-1.5A4.79,4.79,0,0,0,12.5,9.09,4.45,4.45,0,0,0,12.62,10a13.49,13.49,0,0,1-9.87-5,4.78,4.78,0,0,0,1.47,6.38,4.74,4.74,0,0,1-2.17-.6v0.06a4.78,4.78,0,0,0,3.84,4.69,4.89,4.89,0,0,1-2.16.08,4.79,4.79,0,0,0,4.46,3.32A9.77,9.77,0,0,1,.5,20.16,13.73,13.73,0,0,0,7.88,22a13.63,13.63,0,0,0,13.73-14c0-.21,0-0.42-0.02-0.63A9.78,9.78,0,0,0,24,5.05,9.66,9.66,0,0,1,21.7,5.89,4.9,4.9,0,0,0,23.44,4.83Z"></path>
                        </svg>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-indigo-600 text-white hover:bg-indigo-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                        </svg>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-full bg-emerald-600 text-white hover:bg-emerald-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12,2.162c3.204,0,3.584,0.012,4.849,0.07c1.308,0.06,2.655,0.358,3.608,1.311c0.962,0.962,1.251,2.296,1.311,3.608c0.058,1.265,0.07,1.645,0.07,4.849c0,3.204-0.012,3.584-0.07,4.849c-0.059,1.301-0.364,2.661-1.311,3.608c-0.962,0.962-2.295,1.251-3.608,1.311c-1.265,0.058-1.645,0.07-4.849,0.07s-3.584-0.012-4.849-0.07c-1.291-0.059-2.669-0.371-3.608-1.311c-0.957-0.957-1.251-2.304-1.311-3.608c-0.058-1.265-0.07-1.645-0.07-4.849c0-3.204,0.012-3.584,0.07-4.849c0.059-1.296,0.367-2.664,1.311-3.608c0.96-0.96,2.299-1.251,3.608-1.311C8.416,2.174,8.796,2.162,12,2.162 M12,0C8.741,0,8.332,0.014,7.052,0.072C5.197,0.157,3.355,0.673,2.014,2.014C0.668,3.36,0.157,5.198,0.072,7.052C0.014,8.332,0,8.741,0,12c0,3.259,0.014,3.668,0.072,4.948c0.085,1.853,0.603,3.7,1.942,5.038c1.345,1.345,3.186,1.857,5.038,1.942C8.332,23.986,8.741,24,12,24c3.259,0,3.668-0.014,4.948-0.072c1.854-0.085,3.698-0.602,5.038-1.942c1.347-1.347,1.857-3.184,1.942-5.038C23.986,15.668,24,15.259,24,12c0-3.259-0.014-3.668-0.072-4.948c-0.085-1.855-0.602-3.698-1.942-5.038c-1.343-1.343-3.189-1.858-5.038-1.942C15.668,0.014,15.259,0,12,0z M12,5.838c-3.403,0-6.162,2.759-6.162,6.162c0,3.403,2.759,6.162,6.162,6.162s6.162-2.759,6.162-6.162C18.162,8.597,15.403,5.838,12,5.838z M12,16c-2.209,0-4-1.791-4-4c0-2.209,1.791-4,4-4s4,1.791,4,4C16,14.209,14.209,16,12,16z M19.846,5.595c0,0.798-0.646,1.441-1.44,1.441c-0.798,0-1.44-0.646-1.44-1.441c0-0.794,0.646-1.439,1.44-1.439C19.2,4.155,19.846,4.801,19.846,5.595z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Article Content -->
            <div class="prose prose-lg max-w-none">
                {!! $post['content'] !!}
            </div>
            
            <!-- Author Box -->
            <div class="mt-12 bg-gray-50 rounded-xl p-6 flex flex-col md:flex-row items-center md:items-start gap-4">
                <div class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl font-bold">
                    {{ substr($post['author'], 0, 2) }}
                </div>
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $post['author'] }}</h3>
                    <p class="text-sm text-gray-600 mb-3">Travel Writer & Destination Expert</p>
                    <p class="text-gray-600">A passionate traveler who has explored over 50 countries across 6 continents. Specializes in adventure travel, cultural experiences, and finding the best local food spots that tourists often miss.</p>
                    <div class="mt-3 flex justify-center md:justify-start space-x-3">
                        <a href="#" class="text-blue-500 hover:text-blue-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.77,7.46H14.5V5.9a0.9,0.9,0,0,1,.94-1h2.43V0.5H14.82a4.26,4.26,0,0,0-4.52,4.58V7.46H7.5v5H10.3V23.5h4.2V12.48h3.52L18.77,7.46Z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-blue-400 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.44,4.83A10.05,10.05,0,0,1,20.77,5.8a4.79,4.79,0,0,0-3.48-1.5A4.79,4.79,0,0,0,12.5,9.09,4.45,4.45,0,0,0,12.62,10a13.49,13.49,0,0,1-9.87-5,4.78,4.78,0,0,0,1.47,6.38,4.74,4.74,0,0,1-2.17-.6v0.06a4.78,4.78,0,0,0,3.84,4.69,4.89,4.89,0,0,1-2.16.08,4.79,4.79,0,0,0,4.46,3.32A9.77,9.77,0,0,1,.5,20.16,13.73,13.73,0,0,0,7.88,22a13.63,13.63,0,0,0,13.73-14c0-.21,0-0.42-0.02-0.63A9.78,9.78,0,0,0,24,5.05,9.66,9.66,0,0,1,21.7,5.89,4.9,4.9,0,0,0,23.44,4.83Z"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-600 hover:text-gray-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Tags -->
            <div class="mt-8">
                <div class="flex flex-wrap gap-2">
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Travel Tips</span>
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">{{ $post['category'] }}</span>
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Destinations</span>
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">Adventure</span>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="mt-12 border-t pt-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Comments (8)</h3>
                
                <!-- Comment Form -->
                <div class="mb-8">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0"></div>
                        <div class="flex-1">
                            <textarea class="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      rows="3" placeholder="Share your thoughts..."></textarea>
                            <div class="mt-2 flex justify-end">
                                <button class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                                    Post Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sample Comments -->
                <div class="space-y-6">
                    <!-- Comment 1 -->
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">JD</div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between mb-2">
                                    <h4 class="font-bold text-gray-800">John Doe</h4>
                                    <span class="text-sm text-gray-500">2 days ago</span>
                                </div>
                                <p class="text-gray-600">This article is incredibly helpful! I've been planning my trip to Southeast Asia and these insights are exactly what I needed. Particularly appreciated the tips about optimal booking windows.</p>
                                <div class="mt-3 flex items-center gap-4">
                                    <button class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                        <span>12 Likes</span>
                                    </button>
                                    <button class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <span>Reply</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Nested Reply -->
                            <div class="ml-8 mt-4">
                                <div class="flex gap-4">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">SM</div>
                                    <div class="flex-1">
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between mb-1">
                                                <h4 class="font-bold text-gray-800 text-sm">Sarah Mitchell</h4>
                                                <span class="text-xs text-gray-500">1 day ago</span>
                                            </div>
                                            <p class="text-gray-600 text-sm">Glad you found it helpful, John! Let me know if you have any specific questions about Southeast Asia - I've spent several months traveling through the region.</p>
                                            <div class="mt-2 flex items-center gap-4">
                                                <button class="text-xs text-gray-500 hover:text-blue-600 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                    </svg>
                                                    <span>5 Likes</span>
                                                </button>
                                                <button class="text-xs text-gray-500 hover:text-blue-600 flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    <span>Reply</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Comment 2 -->
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-teal-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">AS</div>
                        <div class="flex-1">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between mb-2">
                                    <h4 class="font-bold text-gray-800">Alice Smith</h4>
                                    <span class="text-sm text-gray-500">3 days ago</span>
                                </div>
                                <p class="text-gray-600">Great insights! I especially loved the section on cultural experiences. It's so important to immerse yourself in local traditions when traveling.</p>
                                <div class="mt-3 flex items-center gap-4">
                                    <button class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                        <span>8 Likes</span>
                                    </button>
                                    <button class="text-sm text-gray-500 hover:text-blue-600 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                        <span>Reply</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Subscription -->
<section class="bg-gradient-to-r from-blue-800 to-indigo-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">Subscribe to Our Newsletter</h2>
            <p class="text-blue-200 mb-8">Stay updated with the latest travel tips, exclusive deals, and destination insights delivered straight to your inbox.</p>
            
            <form id="newsletterForm" class="space-y-4">
                @csrf
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <input type="text" id="newsletterName" name="name" placeholder="Your Name" class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-blue-300 placeholder-blue-200 text-white focus:outline-none focus:ring-2 focus:ring-white" required>
                    </div>
                    <div>
                        <input type="email" id="newsletterEmail" name="email" placeholder="Your Email Address" class="w-full px-4 py-3 rounded-lg bg-white bg-opacity-20 border border-blue-300 placeholder-blue-200 text-white focus:outline-none focus:ring-2 focus:ring-white" required>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="weeklyEmails" name="weekly_emails" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" checked>
                        <label for="weeklyEmails" class="ml-2 text-blue-200">Weekly Updates</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="monthlyEmails" name="monthly_emails" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" checked>
                        <label for="monthlyEmails" class="ml-2 text-blue-200">Monthly Digest</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="promotionalEmails" name="promotional_emails" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4" checked>
                        <label for="promotionalEmails" class="ml-2 text-blue-200">Exclusive Offers</label>
                    </div>
                </div>
                
                <div>
                    <button type="submit" class="bg-white text-indigo-800 font-bold px-8 py-3 rounded-lg hover:bg-blue-100 transition duration-300 flex items-center justify-center gap-2 mx-auto">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        <span>Subscribe Now</span>
                    </button>
                </div>
                
                <p class="text-xs text-blue-300 mt-4">By subscribing, you agree to receive marketing communications from us and confirm that you have read our <a href="#" class="underline">Privacy Policy</a>.</p>
            </form>
        </div>
    </div>
</section>

<!-- Related Articles Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Related Articles</h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Related Article 1 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition duration-300">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('images/holiday.jpg') }}" alt="Related Article" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                </div>
                <div class="p-6">
                    <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full mb-3">Travel Tips</span>
                    <h3 class="font-bold text-xl mb-2 text-gray-800">10 Essential Items to Pack for Any Trip</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">Don't leave home without these travel essentials that will make your journey smoother and more comfortable.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">5 min read</span>
                        <a href="#" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                            <span>Read More</span>
                            <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Related Article 2 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition duration-300">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('images/airplane.jpg') }}" alt="Related Article" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                </div>
                <div class="p-6">
                    <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full mb-3">Budget Travel</span>
                    <h3 class="font-bold text-xl mb-2 text-gray-800">How to Travel on a Tight Budget</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">Smart strategies to help you see the world without breaking the bank. Learn how to find deals and save money while traveling.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">7 min read</span>
                        <a href="#" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                            <span>Read More</span>
                            <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Related Article 3 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition duration-300">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('images/destination.jpg') }}" alt="Related Article" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                </div>
                <div class="p-6">
                    <span class="inline-block bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-1 rounded-full mb-3">Destinations</span>
                    <h3 class="font-bold text-xl mb-2 text-gray-800">Hidden Gems: Underrated Destinations You Must Visit</h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">Discover these amazing off-the-beaten-path destinations that offer authentic experiences without the crowds.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">8 min read</span>
                        <a href="#" class="text-blue-600 font-medium hover:text-blue-800 flex items-center">
                            <span>Read More</span>
                            <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Add Toastify script for notification
    document.addEventListener('DOMContentLoaded', function() {
        const newsletterForm = document.getElementById('newsletterForm');
        
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitButton = newsletterForm.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;
                submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Processing...</span>';
                submitButton.disabled = true;
                
                const name = document.getElementById('newsletterName').value;
                const email = document.getElementById('newsletterEmail').value;
                const weeklyEmails = document.getElementById('weeklyEmails').checked;
                const monthlyEmails = document.getElementById('monthlyEmails').checked;
                const promotionalEmails = document.getElementById('promotionalEmails').checked;
                
                // Send subscription request via fetch
                fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        weekly_emails: weeklyEmails,
                        monthly_emails: monthlyEmails,
                        promotional_emails: promotionalEmails,
                        source: 'blog'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    
                    if (data.success) {
                        // Show success toast
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: data.message,
                                duration: 5000,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#10B981",
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #10B981, #059669)",
                                    borderRadius: "8px",
                                    boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                }
                            }).showToast();
                        } else {
                            alert(data.message);
                        }
                        
                        // Reset form
                        newsletterForm.reset();
                        
                        // Recheck checkboxes
                        document.getElementById('weeklyEmails').checked = true;
                        document.getElementById('monthlyEmails').checked = true;
                        document.getElementById('promotionalEmails').checked = true;
                    } else {
                        // Show error toast
                        if (typeof Toastify !== 'undefined') {
                            Toastify({
                                text: data.message || 'Something went wrong. Please try again.',
                                duration: 5000,
                                gravity: "top",
                                position: "center",
                                backgroundColor: "#EF4444",
                                className: "info",
                                style: {
                                    background: "linear-gradient(to right, #EF4444, #DC2626)",
                                    borderRadius: "8px",
                                    boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                                }
                            }).showToast();
                        } else {
                            alert(data.message || 'Something went wrong. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    // Reset button
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    
                    // Show error toast
                    if (typeof Toastify !== 'undefined') {
                        Toastify({
                            text: 'Network error. Please try again later.',
                            duration: 5000,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#EF4444",
                            className: "info",
                            style: {
                                background: "linear-gradient(to right, #EF4444, #DC2626)",
                                borderRadius: "8px",
                                boxShadow: "0 3px 6px rgba(0,0,0,0.16)"
                            }
                        }).showToast();
                    } else {
                        alert('Network error. Please try again later.');
                    }
                    
                    console.error('Newsletter subscription error:', error);
                });
            });
        }
        
        // Social Share Functionality
        const shareBtns = document.querySelectorAll('.flex.justify-end.mb-8 button');
        if (shareBtns.length) {
            shareBtns.forEach((btn, index) => {
                btn.addEventListener('click', function() {
                    const pageTitle = document.title;
                    const pageUrl = window.location.href;
                    
                    let shareUrl = '';
                    
                    switch(index) {
                        case 0: // Facebook
                            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`;
                            break;
                        case 1: // Twitter
                            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent(pageTitle)}`;
                            break;
                        case 2: // LinkedIn
                            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}`;
                            break;
                        case 3: // Instagram (Note: Instagram doesn't support direct sharing links)
                            alert('Instagram sharing is not available through direct links. Please copy the URL and share it manually.');
                            return;
                    }
                    
                    window.open(shareUrl, '_blank', 'width=600,height=400');
                });
            });
        }
    });
    
    // Load Toastify if it doesn't exist
    if (typeof Toastify === 'undefined') {
        const toastifyCSS = document.createElement('link');
        toastifyCSS.rel = 'stylesheet';
        toastifyCSS.href = 'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css';
        document.head.appendChild(toastifyCSS);

        const toastifyScript = document.createElement('script');
        toastifyScript.src = 'https://cdn.jsdelivr.net/npm/toastify-js';
        document.body.appendChild(toastifyScript);
    }
</script>
@endsection