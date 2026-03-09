<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 ease-in-out bg-transparent" id="navbar">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 lg:h-20">
           <!-- Logo with 50% reduced size -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center space-x-1 group">
                    <div class="bg-white p-0.5 rounded-md shadow-md">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-5 h-5 lg:w-6 lg:h-6 object-contain">
                    </div>
                    <!-- Further reduced text size by 50% -->
                    <span class="text-xs lg:text-sm font-bold text-white hidden sm:block">
                        Ziara Travels
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-1">
                <a href="{{ route('home') }}" class="nav-link px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-white/10 hover:text-amber-400 {{ request()->routeIs('home') ? 'text-amber-400 bg-white/10' : 'text-white' }}">
                    Home
                </a>
                
                <!-- Mega Menu: Services -->
                <div class="relative group">
                    <button type="button" class="mega-menu-trigger nav-link px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-white/10 hover:text-amber-400 text-white flex items-center">
                        Our Services 
                        <svg class="w-4 h-4 ml-1 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Enhanced mega menu with image previews -->
                    <div class="mega-menu-dropdown absolute top-full left-0 mt-2 w-[600px] bg-white rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 border border-gray-100 z-50">
                        <div class="p-6 flex">
                            <!-- Menu items -->
                            <div class="space-y-2 w-2/5">
                                <h4 class="font-semibold text-gray-800 mb-3 pb-2 border-b">Travel Services</h4>
                                <a href="{{ route('search-flights') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/flight.jpg') }}">
                                    <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-blue-50 text-blue-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                        </svg>
                                    </span>
                                    Book A Flight
                                </a>
                                <a href="#" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/hotel.jpg') }}">
                                    <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-amber-50 text-amber-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </span>
                                     Book A Hotel
                                </a>
                              
                                {{-- <a href="{{ route('travel-insurance') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/insurance.jpg') }}">
                                    <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-purple-50 text-purple-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </span>
                                    Travel Insurance
                                </a> --}}
                            </div>
                            <!-- Image preview area - larger size -->
                            <div class="w-3/5 pl-6 flex items-center justify-center border-l border-gray-100">
                                <div class="h-48 w-full overflow-hidden rounded-lg">
                                    <img id="services-preview" src="{{ asset('images/flight.jpg') }}" alt="Preview" class="h-full w-full object-cover transition-all duration-500 ease-in-out">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mega Menu: Company -->
                <div class="relative group">
                    <button type="button" class="mega-menu-trigger nav-link px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-white/10 hover:text-amber-400 text-white flex items-center">
                        About Us
                        <svg class="w-4 h-4 ml-1 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mega-menu-dropdown absolute top-full left-0 mt-2 w-[700px] bg-white rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 border border-gray-100 z-50">
                        <div class="p-6 flex">
                            <div class="w-3/5 grid grid-cols-2 gap-6">
                                <!-- Company Information Column -->
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-3 pb-2 border-b">Company</h4>
                                    <a href="{{ route('why-choose-us') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/why-us.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-blue-50 text-blue-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </span>
                                        Why Choose Us
                                    </a>
                                    <a href="{{ route('aboutus') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/who-we-are.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </span>
                                        Who We Are
                                    </a>
                                    <a href="{{ route('aboutus') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/our-story.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-amber-50 text-amber-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </span>
                                        Our Story
                                    </a>
                                    <a href="{{ route('what-we-do') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/what-we-do.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-green-50 text-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </span>
                                        What We Do
                                    </a>
                                </div>
                                
                                <!-- Resources Column -->
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-3 pb-2 border-b">Resources</h4>
                                    <a href="{{ route('special-offers') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/offers.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-red-50 text-red-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                            </svg>
                                        </span>
                                        Special Offers
                                    </a>
                                    <a href="{{ route('customer-stories') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/customer-stories.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-purple-50 text-purple-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                            </svg>
                                        </span>
                                        Customer Stories
                                    </a>
                                    <a href="#" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/blog.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-blue-50 text-blue-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </span>
                                        Blog
                                    </a>
                                    <a href="{{ route('news') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/news.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-yellow-50 text-yellow-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </span>
                                        News
                                    </a>
                                    <a href="{{ route('careers') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/careers.jpg') }}">
                                        <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-teal-50 text-teal-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </span>
                                        Careers
                                    </a>
                                </div>

                                <!-- Programs Column (Moved to same grid) -->
                                <div class="col-span-2">
                                    <h4 class="font-semibold text-gray-800 mb-3 pb-2 border-b">Loyalty Programs</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('loyalty-points') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/loyalty.jpg') }}">
                                            <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-yellow-50 text-yellow-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            </span>
                                            Loyalty Points
                                        </a>
                                        <a href="{{ route('vip-membership') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/vip.jpg') }}">
                                            <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-purple-50 text-purple-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                </svg>
                                            </span>
                                            VIP Membership
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Image preview area - larger size -->
                            <div class="w-2/5 pl-6 flex items-center justify-center border-l border-gray-100">
                                <div class="h-64 w-full overflow-hidden rounded-lg shadow-md">
                                    <img id="company-preview" src="{{ asset('images/about-us.jpg') }}" alt="Preview" class="h-full w-full object-cover transition-all duration-500 ease-in-out">
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                        <p class="text-white text-sm font-medium" id="company-caption">Learn about Ziara Travels</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mega Menu: Support -->
                <div class="relative group">
                    <button type="button" class="mega-menu-trigger nav-link px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-white/10 hover:text-amber-400 text-white flex items-center">
                        Support
                        <svg class="w-4 h-4 ml-1 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="mega-menu-dropdown absolute top-full left-0 mt-2 w-[600px] bg-white rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 border border-gray-100 z-50">
                        <div class="p-6 flex">
                            <div class="space-y-2 w-2/5">
                                <h4 class="font-semibold text-gray-800 mb-3 pb-2 border-b">Help & Support</h4>
                                <a href="{{ route('contactus') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/contact.jpg') }}">
                                    <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-blue-50 text-blue-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </span>
                                    Contact Us
                                </a>
                                <a href="{{ route('live-chat') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/chat.jpg') }}">
                                    <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-green-50 text-green-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                    </span>
                                    Live Chat
                                </a>
                                <a href="{{ route('help-center') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/help.jpg') }}">
                                    <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-amber-50 text-amber-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                    </span>
                                    Help Center
                                </a>
                                <a href="{{ route('faq') }}" class="dropdown-item flex items-center p-2 text-sm text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600" data-img="{{ asset('images/faq.jpg') }}">
                                    <span class="w-8 h-8 mr-2 flex items-center justify-center rounded-full bg-purple-50 text-purple-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </span>
                                    FAQs
                                </a>
                            </div>
                            <!-- Image preview area - larger size -->
                            <div class="w-3/5 pl-6 flex items-center justify-center border-l border-gray-100">
                                <div class="h-48 w-full overflow-hidden rounded-lg">
                                    <img id="support-preview" src="{{ asset('images/contact.jpg') }}" alt="Preview" class="h-full w-full object-cover transition-all duration-500 ease-in-out">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auth Buttons / User Menu -->
            <div class="hidden lg:flex items-center space-x-3">
                @guest
                    <!-- Enhanced login button with animations -->
                    <a href="{{ route('login') }}" class="flex items-center px-5 py-2 text-sm font-medium text-white border-2 border-white hover:bg-white/10 transition-all duration-300 rounded-full relative overflow-hidden group">
                        <span class="relative z-10">Login</span>
                        <span class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-all duration-300 -z-1 transform translate-y-full group-hover:translate-y-0"></span>
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-full hover:from-amber-600 hover:to-orange-600 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        Register
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white hover:text-amber-400 transition-colors duration-300">
                        Dashboard
                    </a>
                    <a href="{{ route('wishlists.index') }}" class="flex items-center px-4 py-2 text-sm font-medium text-white hover:text-amber-400 transition-colors duration-300 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Wishlist
                        <span id="wishlist-counter" class="absolute -top-2 -right-2 bg-amber-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">0</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-amber-400 transition-colors duration-300">
                            Logout
                        </button>
                    </form>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-amber-400 hover:bg-white/10 transition-colors duration-300" id="mobile-menu-button">
                    <svg class="w-6 h-6 block" id="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="w-6 h-6 hidden" id="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Mega Menu -->
    <div class="lg:hidden hidden" id="mobile-menu">
        <div class="bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-lg">
            <div class="container mx-auto px-4 py-4 space-y-4">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-800 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                    Home
                </a>
                <!-- Mobile Mega Menu: Services -->
                <div>
                    <button type="button" class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200" onclick="toggleMobileMega('services')">
                        Services
                        <svg class="w-4 h-4 ml-2 transition-transform duration-300" id="services-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobile-mega-services" class="hidden pl-6">
                        <a href="#" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Flight Booking</a>
                        <a href="#" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Hotel Booking</a>
                        <a href="#" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Car Rental</a>
                        <a href="{{ route('travel-insurance') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Travel Insurance</a>
                    </div>
                </div>
                <!-- Mobile Mega Menu: Company -->
                <div>
                    <button type="button" class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200" onclick="toggleMobileMega('company')">
                        Company
                        <svg class="w-4 h-4 ml-2 transition-transform duration-300" id="company-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobile-mega-company" class="hidden pl-6">
                        <a href="{{ route('aboutus') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Our Story</a>
                        <a href="{{ route('special-offers') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Special Offers</a>
                        <a href="{{ route('loyalty-points') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Loyalty Points</a>
                        <a href="{{ route('vip-membership') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">VIP Membership</a>
                    </div>
                </div>
                <!-- Mobile Mega Menu: Support -->
                <div>
                    <button type="button" class="w-full flex items-center justify-between px-4 py-3 text-gray-800 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200" onclick="toggleMobileMega('support')">
                        Support
                        <svg class="w-4 h-4 ml-2 transition-transform duration-300" id="support-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="mobile-mega-support" class="hidden pl-6">
                        <a href="{{ route('contactus') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Contact Us</a>
                        <a href="{{ route('live-chat') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Live Chat</a>
                        <a href="{{ route('help-center') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">Help Center</a>
                        <a href="{{ route('faq') }}" class="block py-2 text-sm text-gray-700 hover:text-blue-600">FAQs</a>
                    </div>
                </div>
                @guest
                    <hr class="border-gray-200">
                    <div class="space-y-3 pt-4">
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-full px-4 py-3 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg hover:from-amber-600 hover:to-orange-600 transition-colors duration-200">
                            Register
                        </a>
                    </div>
                @else
                    <hr class="border-gray-200">
                    <div class="pt-4">
                        <div class="flex items-center px-4 py-3 mb-2">
                            <span class="font-medium text-gray-900">{{ auth()->user()->name }}</span>
                        </div>
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full px-4 py-3 text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile mega menu toggle function
    function toggleMobileMega(menu) {
        const menuEl = document.getElementById('mobile-mega-' + menu);
        const iconEl = document.getElementById(menu + '-icon');
        
        if (menuEl.classList.contains('hidden')) {
            menuEl.classList.remove('hidden');
            if (iconEl) iconEl.style.transform = 'rotate(180deg)';
        } else {
            menuEl.classList.add('hidden');
            if (iconEl) iconEl.style.transform = 'rotate(0)';
        }
    }
    
    // Main document ready function for all navigation functionality
    document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.getElementById('navbar');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');
        
        // Enhanced image preview with captions
        document.querySelectorAll('.dropdown-item[data-img]').forEach(item => {
            item.addEventListener('mouseenter', function() {
                const imgSrc = this.getAttribute('data-img');
                const menu = this.closest('.mega-menu-dropdown');
                const previewImg = menu.querySelector('img');
                const captionEl = menu.querySelector('#company-caption');
                
                if (previewImg) {
                    // Add fade effect for smoother transition
                    previewImg.style.opacity = '0';
                    
                    setTimeout(() => {
                        previewImg.src = imgSrc;
                        previewImg.style.opacity = '1';
                        
                        // Update caption to match the hovered menu item
                        if (captionEl) {
                            captionEl.textContent = this.textContent.trim();
                        }
                    }, 200);
                }
            });
        });

        // Set default images on menu hover
        document.querySelectorAll('.mega-menu-trigger').forEach(trigger => {
            const dropdown = trigger.nextElementSibling;
            const defaultItem = dropdown.querySelector('.dropdown-item');
            const previewImg = dropdown.querySelector('img');
            
            trigger.addEventListener('mouseenter', function() {
                if (defaultItem && previewImg) {
                    const defaultImg = defaultItem.getAttribute('data-img');
                    if (defaultImg) {
                        previewImg.src = defaultImg;
                    }
                }
            });

        });
        
        // Add click handlers for desktop mega menu buttons
        document.querySelectorAll('.mega-menu-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const dropdown = this.nextElementSibling;
                
                // Toggle dropdown visibility
                if (dropdown.classList.contains('invisible')) {
                    dropdown.classList.remove('invisible', 'opacity-0');
                    dropdown.classList.add('visible', 'opacity-100');
                } else {
                    dropdown.classList.add('invisible', 'opacity-0');
                    dropdown.classList.remove('visible', 'opacity-100');
                }
            });
        });
        
        // Navbar scroll effect with throttling
        let ticking = false;
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    if (window.scrollY > 50) {
                        navbar.classList.add('bg-white', 'shadow-lg');
                        navbar.classList.remove('bg-transparent');
                        
                        // Update all text colors to black when scrolled
                        const navLinks = navbar.querySelectorAll('.nav-link');
                        navLinks.forEach(link => {
                            link.classList.remove('text-white');
                            link.classList.add('text-gray-800');
                        });
                        
                        // Update login button to match dark scheme
                        const authButtons = navbar.querySelectorAll('.lg\\:flex.items-center.space-x-3 a');
                        authButtons.forEach(btn => {
                            if (btn.textContent.trim() === 'Login') {
                                btn.classList.remove('text-white', 'border-white');
                                btn.classList.add('text-gray-800', 'border-gray-800');
                            } else if (btn.textContent.trim() === 'Dashboard' || 
                                      btn.textContent.trim() === 'Logout') {
                                btn.classList.remove('text-white');
                                btn.classList.add('text-gray-800');
                            }
                        });
                        
                        // Update brand name
                        const brandName = navbar.querySelector('.flex-shrink-0 span');
                        if (brandName) {
                            brandName.classList.remove('text-white');
                            brandName.classList.add('text-gray-800');
                        }
                        
                        // Update mobile menu button
                        const mobileButton = navbar.querySelector('#mobile-menu-button');
                        if (mobileButton) {
                            mobileButton.classList.remove('text-white');
                            mobileButton.classList.add('text-gray-800');
                        }
                    } else {
                        navbar.classList.remove('bg-white', 'shadow-lg');
                        navbar.classList.add('bg-transparent');
                        
                        // Restore original text colors
                        const navLinks = navbar.querySelectorAll('.nav-link');
                        navLinks.forEach(link => {
                            link.classList.add('text-white');
                            link.classList.remove('text-gray-800');
                        });
                        
                        // Restore login button to white scheme
                        const authButtons = navbar.querySelectorAll('.lg\\:flex.items-center.space-x-3 a');
                        authButtons.forEach(btn => {
                            if (btn.textContent.trim() === 'Login') {
                                btn.classList.add('text-white', 'border-white');
                                btn.classList.remove('text-gray-800', 'border-gray-800');
                            } else if (btn.textContent.trim() === 'Dashboard' || 
                                      btn.textContent.trim() === 'Logout') {
                                btn.classList.add('text-white');
                                btn.classList.remove('text-gray-800');
                            }
                        });
                        
                        // Restore brand name
                        const brandName = navbar.querySelector('.flex-shrink-0 span');
                        if (brandName) {
                            brandName.classList.add('text-white');
                            brandName.classList.remove('text-gray-800');
                        }
                        
                        // Restore mobile menu button
                        const mobileButton = navbar.querySelector('#mobile-menu-button');
                        if (mobileButton) {
                            mobileButton.classList.add('text-white');
                            mobileButton.classList.remove('text-gray-800');
                        }
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        // Mobile menu toggle
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', function() {
                const isOpen = !mobileMenu.classList.contains('hidden');
                if (isOpen) {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                } else {
                    mobileMenu.classList.remove('hidden');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                }
            });
        }
        
        // Close menus when clicking outside
        document.addEventListener('click', function(event) {
            // Close mobile menu when clicking outside
            if (mobileMenu && !mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
            
            // Close desktop mega menus when clicking outside
            document.querySelectorAll('.mega-menu-dropdown').forEach(dropdown => {
                const trigger = dropdown.previousElementSibling;
                if (!dropdown.classList.contains('invisible') && 
                    !dropdown.contains(event.target) && 
                    !trigger.contains(event.target)) {
                    dropdown.classList.add('invisible', 'opacity-0');
                    dropdown.classList.remove('visible', 'opacity-100');
                }
            });
        });
        
        // Update wishlist counter
        function updateWishlistCounter() {
            const counter = document.getElementById('wishlist-counter');
            if (counter) {
                fetch('{{ route("check-auth") }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.isLoggedIn) {
                            fetch('{{ route("wishlist.count") }}')
                                .then(response => response.json())
                                .then(data => {
                                    counter.textContent = data.count;
                                    if (data.count > 0) {
                                        counter.classList.remove('hidden');
                                    } else {
                                        counter.classList.add('hidden');
                                    }
                                })
                                .catch(error => console.error('Error fetching wishlist count:', error));
                        } else {
                            counter.classList.add('hidden');
                        }
                    })
                    .catch(error => console.error('Error checking authentication:', error));
            }
        }
        
        // Initial wishlist counter update
        document.addEventListener('DOMContentLoaded', function() {
            updateWishlistCounter();
        });
        
        // Update counter when wishlists change
        document.addEventListener('wishlistUpdated', function() {
            updateWishlistCounter();
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });
    });
</script>