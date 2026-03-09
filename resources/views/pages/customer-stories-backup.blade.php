
@extends('layouts.main')

@section('title', 'Customer Stories - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <div class="relative py-16 md:py-24 bg-cover bg-center" style="background-image: url('{{ asset('images/backgrounds/testimonials-bg.jpg') }}');">
        <div class="absolute inset-0 bg-blue-900 opacity-60"></div>
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Customer Stories</h1>
            <p class="text-xl text-white max-w-3xl mx-auto">Real travel experiences from our satisfied clients around the world</p>
        </div>
    </div>

    <!-- Featured Story Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                        <img src="{{ asset('images/customers/featured-customer.jpg') }}" alt="Featured Customer Story" class="rounded-lg shadow-lg">
                    </div>
                    <div class="md:w-1/2">
                        <span class="text-primary-600 font-semibold">Featured Story</span>
                        <h2 class="text-3xl font-bold mb-4">An Unforgettable Safari Experience</h2>
                        <p class="text-gray-600 mb-4">
                            "My family and I had the most incredible experience with Ziara Travel. The safari package they arranged exceeded all our expectations. From the moment we landed in Nairobi until our departure, everything was perfectly organized."
                        </p>
                        <p class="text-gray-600 mb-6">
                            "The accommodations were exceptional, the guides were knowledgeable and friendly, and we saw all the wildlife we had hoped for. It was truly the trip of a lifetime, and we're already planning our next adventure with Ziara!"
                        </p>
                        <div class="flex items-center">
                            <img src="{{ asset('images/customers/customer-1.jpg') }}" alt="Customer" class="w-12 h-12 rounded-full object-cover mr-4">
                            <div>
                                <h4 class="font-semibold">Sarah & David Thompson</h4>
                                <p class="text-sm text-gray-500">Family Safari Tour - Masai Mara</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Stories Section -->
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">More Success Stories</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Discover how we've helped travelers create lasting memories across the globe
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Story Card 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/customers/story-1.jpg') }}" alt="Customer Story" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Beach Paradise in Zanzibar</h3>
                        <p class="text-gray-600 mb-4">
                            "The beachfront villa Ziara Travel arranged for us in Zanzibar was absolute perfection. Crystal clear waters, pristine beaches, and impeccable service."
                        </p>
                        <div class="flex items-center">
                            <img src="{{ asset('images/customers/customer-2.jpg') }}" alt="Customer" class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h4 class="font-semibold">Michael & Emma Davis</h4>
                                <p class="text-xs text-gray-500">Honeymoon Package</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Story Card 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/customers/story-2.jpg') }}" alt="Customer Story" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Cultural Tour of Morocco</h3>
                        <p class="text-gray-600 mb-4">
                            "Our guided tour through Morocco's historic cities was eye-opening. The local guides provided insights we never would have discovered on our own."
                        </p>
                        <div class="flex items-center">
                            <img src="{{ asset('images/customers/customer-3.jpg') }}" alt="Customer" class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h4 class="font-semibold">Jennifer Wilson</h4>
                                <p class="text-xs text-gray-500">Cultural Explorer Package</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Story Card 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/customers/story-3.jpg') }}" alt="Customer Story" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="font-bold text-xl mb-2">Wildlife Photography Expedition</h3>
                        <p class="text-gray-600 mb-4">
                            "As a wildlife photographer, I needed specific access and timing. Ziara Travel understood my needs perfectly and arranged the perfect expedition."
                        </p>
                        <div class="flex items-center">
                            <img src="{{ asset('images/customers/customer-4.jpg') }}" alt="Customer" class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h4 class="font-semibold">Robert Chen</h4>
                                <p class="text-xs text-gray-500">Custom Safari Package</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('contactus') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 px-8 rounded-md transition duration-300">
                    Share Your Story
                </a>
            </div>
        </div>
    </div>
    
    <!-- Testimonials Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">What Our Clients Say</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Read testimonials from travelers who have experienced our exceptional service
                </p>
            </div>
            
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-gray-600">5.0</span>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "I've traveled with many companies over the years, but none have provided the level of personalized service that Ziara Travel does. They truly listen to what you want and create an experience tailored just for you."
                    </p>
                    <div class="flex items-center">
                        <img src="{{ asset('images/customers/testimonial-1.jpg') }}" alt="Customer" class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-semibold">Patricia Gomez</h4>
                            <p class="text-sm text-gray-500">Luxury Travel Client</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="ml-2 text-gray-600">5.0</span>
                    </div>
                    <p class="text-gray-600 italic mb-6">
                        "When our flight was canceled due to bad weather, I thought our vacation was ruined. But Ziara Travel's team worked tirelessly to rebook everything and salvage our trip. Their customer service is unmatched!"
                    </p>
                    <div class="flex items-center">
                        <img src="{{ asset('images/customers/testimonial-2.jpg') }}" alt="Customer" class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-semibold">James & Laura Foster</h4>
                            <p class="text-sm text-gray-500">Family Vacation Clients</p>
                        </div>
                    </div>
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