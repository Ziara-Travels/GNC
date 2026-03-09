@extends('layouts.main')

@section('title', 'Loyalty Points - Ziara Travels')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-yellow-500 to-orange-600 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Loyalty Points
                </h1>
                <p class="text-xl md:text-2xl text-yellow-100 mb-8 animate-slide-up">
                    Earn points on every booking and unlock exclusive rewards
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="text-4xl font-bold mb-2">1 Point = $1</div>
                    <div class="text-lg">Simple and rewarding</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Points Dashboard -->
    @auth
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Your Points Dashboard</h2>
                    <p class="text-xl text-gray-600">Track your earnings and redeem rewards</p>
                </div>

                <div class="grid md:grid-cols-4 gap-6 mb-12">
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-yellow-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="coins" class="w-8 h-8 text-yellow-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-yellow-600 mb-2">2,450</div>
                        <div class="text-gray-600">Available Points</div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-green-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="trending-up" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-green-600 mb-2">8,920</div>
                        <div class="text-gray-600">Total Earned</div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="gift" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-blue-600 mb-2">Gold</div>
                        <div class="text-gray-600">Tier Status</div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="calendar" class="w-8 h-8 text-purple-600"></i>
                        </div>
                        <div class="text-3xl font-bold text-purple-600 mb-2">Dec 2025</div>
                        <div class="text-gray-600">Next Expiry</div>
                    </div>
                </div>

                <!-- Tier Progress -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Tier Progress</h3>
                    <div class="relative">
                        <div class="flex justify-between items-center mb-4">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mb-2">
                                    <i data-lucide="user" class="w-6 h-6 text-white"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Silver</span>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mb-2">
                                    <i data-lucide="award" class="w-6 h-6 text-white"></i>
                                </div>
                                <span class="text-sm font-medium text-yellow-600">Gold</span>
                                <div class="text-xs text-gray-500">Current</div>
                            </div>
                            <div class="text-center">
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mb-2">
                                    <i data-lucide="crown" class="w-6 h-6 text-white"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-600">Platinum</span>
                                <div class="text-xs text-gray-500">1,550 points to go</div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 h-2 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endauth

    <!-- How It Works -->
    <section class="py-20 {{ auth()->check() ? 'bg-gray-50' : 'bg-white' }}">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">How to Earn Points</h2>
                    <p class="text-xl text-gray-600">Multiple ways to earn and maximize your rewards</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6 bg-white rounded-2xl shadow-lg">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="plane" class="w-10 h-10 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Flight Bookings</h3>
                        <p class="text-gray-600 mb-4">Earn 1 point for every $1 spent on flight bookings</p>
                        <div class="bg-blue-50 rounded-lg p-3">
                            <span class="text-sm font-medium text-blue-700">Bonus: 2x points on international flights</span>
                        </div>
                    </div>

                    <div class="text-center p-6 bg-white rounded-2xl shadow-lg">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="hotel" class="w-10 h-10 text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Hotel Bookings</h3>
                        <p class="text-gray-600 mb-4">Earn 1 point for every $1 spent on hotel reservations</p>
                        <div class="bg-green-50 rounded-lg p-3">
                            <span class="text-sm font-medium text-green-700">Bonus: 3x points on luxury hotels</span>
                        </div>
                    </div>

                    <div class="text-center p-6 bg-white rounded-2xl shadow-lg">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="car" class="w-10 h-10 text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Car Rentals</h3>
                        <p class="text-gray-600 mb-4">Earn 1 point for every $1 spent on car rentals</p>
                        <div class="bg-red-50 rounded-lg p-3">
                            <span class="text-sm font-medium text-red-700">Bonus: 2x points on weekend rentals</span>
                        </div>
                    </div>

                    <div class="text-center p-6 bg-white rounded-2xl shadow-lg">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="user-plus" class="w-10 h-10 text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Referrals</h3>
                        <p class="text-gray-600 mb-4">Earn 500 points for each successful referral</p>
                        <div class="bg-orange-50 rounded-lg p-3">
                            <span class="text-sm font-medium text-orange-700">Bonus: Extra 200 points for premium referrals</span>
                        </div>
                    </div>

                    <div class="text-center p-6 bg-white rounded-2xl shadow-lg">
                        <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="star" class="w-10 h-10 text-indigo-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Reviews & Ratings</h3>
                        <p class="text-gray-600 mb-4">Earn 50 points for each verified review you submit</p>
                        <div class="bg-indigo-50 rounded-lg p-3">
                            <span class="text-sm font-medium text-indigo-700">Bonus: 100 points for detailed reviews</span>
                        </div>
                    </div>

                    <div class="text-center p-6 bg-white rounded-2xl shadow-lg">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="gift" class="w-10 h-10 text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Special Promotions</h3>
                        <p class="text-gray-600 mb-4">Participate in seasonal promotions to earn extra points</p>
                        <div class="bg-purple-50 rounded-lg p-3">
                            <span class="text-sm font-medium text-purple-700">Bonus: Up to 5x points during holidays</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tier Benefits -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Tier Benefits</h2>
                    <p class="text-xl text-gray-600">Unlock exclusive benefits as you advance through tiers</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Silver Tier -->
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-8 relative overflow-hidden">
                        <div class="absolute top-4 right-4">
                            <div class="w-12 h-12 bg-gray-400 rounded-full flex items-center justify-center">
                                <i data-lucide="user" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Silver</h3>
                        <p class="text-gray-600 mb-6">0 - 2,999 points</p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Earn 1x points on all bookings
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Free customer support
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Monthly newsletter
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Standard booking flexibility
                            </li>
                        </ul>
                    </div>

                    <!-- Gold Tier -->
                    <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-2xl p-8 relative overflow-hidden border-2 border-yellow-400">
                        <div class="absolute top-4 right-4">
                            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                                <i data-lucide="award" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <div class="absolute top-0 left-0 bg-yellow-500 text-white px-3 py-1 rounded-br-lg text-sm font-bold">
                            CURRENT
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Gold</h3>
                        <p class="text-gray-600 mb-6">3,000 - 9,999 points</p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Earn 1.5x points on all bookings
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Priority customer support
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Free seat selection
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Enhanced booking flexibility
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                10% bonus on partner hotels
                            </li>
                        </ul>
                    </div>

                    <!-- Platinum Tier -->
                    <div class="bg-gradient-to-br from-purple-100 to-purple-200 rounded-2xl p-8 relative overflow-hidden">
                        <div class="absolute top-4 right-4">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center">
                                <i data-lucide="crown" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Platinum</h3>
                        <p class="text-gray-600 mb-6">10,000+ points</p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Earn 2x points on all bookings
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Dedicated concierge service
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Complimentary upgrades
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Free cancellation anytime
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                20% bonus on all partners
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3"></i>
                                Exclusive VIP lounge access
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Redeem Points -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Redeem Your Points</h2>
                    <p class="text-xl text-gray-600">Turn your points into amazing rewards and savings</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Flight Discounts -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="plane" class="w-8 h-8 text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Flight Discounts</h3>
                        <p class="text-gray-600 text-center mb-4">Use points for instant flight discounts</p>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 mb-1">500 pts</div>
                            <div class="text-sm text-gray-500">= $50 off</div>
                        </div>
                    </div>

                    <!-- Hotel Upgrades -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="hotel" class="w-8 h-8 text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Hotel Upgrades</h3>
                        <p class="text-gray-600 text-center mb-4">Upgrade to premium rooms</p>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 mb-1">750 pts</div>
                            <div class="text-sm text-gray-500">= Room upgrade</div>
                        </div>
                    </div>

                    <!-- Free Nights -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="moon" class="w-8 h-8 text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Free Nights</h3>
                        <p class="text-gray-600 text-center mb-4">Redeem for complimentary stays</p>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 mb-1">2,000 pts</div>
                            <div class="text-sm text-gray-500">= 1 free night</div>
                        </div>
                    </div>

                    <!-- Gift Cards -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="gift" class="w-8 h-8 text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2 text-center">Gift Cards</h3>
                        <p class="text-gray-600 text-center mb-4">Convert to travel gift cards</p>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600 mb-1">1,000 pts</div>
                            <div class="text-sm text-gray-500">= $100 gift card</div>
                        </div>
                    </div>
                </div>

                @auth
                <div class="text-center mt-12">
                    <button class="px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold rounded-full hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i data-lucide="shopping-cart" class="w-5 h-5 inline-block mr-2"></i>
                        Start Redeeming Points
                    </button>
                </div>
                @else
                <div class="text-center mt-12">
                    <p class="text-gray-600 mb-4">Join our loyalty program to start earning and redeeming points!</p>
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold rounded-full hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:scale-105 shadow-lg inline-block">
                        <i data-lucide="user-plus" class="w-5 h-5 inline-block mr-2"></i>
                        Join Now
                    </a>
                </div>
                @endauth
            </div>
        </div>
    </section>

    @auth
    <!-- Recent Activity -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Recent Points Activity</h2>
                    <p class="text-xl text-gray-600">Track your latest points earnings and redemptions</p>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Date</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Activity</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Points</th>
                                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">Jan 15, 2025</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Flight booking to Paris</td>
                                    <td class="px-6 py-4 text-sm font-medium text-green-600">+850 pts</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Earned
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">Jan 12, 2025</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Hotel upgrade redemption</td>
                                    <td class="px-6 py-4 text-sm font-medium text-red-600">-750 pts</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                            Redeemed
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">Jan 10, 2025</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Referral bonus</td>
                                    <td class="px-6 py-4 text-sm font-medium text-green-600">+500 pts</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Earned
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">Jan 8, 2025</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Hotel booking in Tokyo</td>
                                    <td class="px-6 py-4 text-sm font-medium text-green-600">+1,200 pts</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Earned
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">Jan 5, 2025</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Review submission</td>
                                    <td class="px-6 py-4 text-sm font-medium text-green-600">+100 pts</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                            Earned
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 text-center">
                        <button class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                            View All Activity
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endauth

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h2>
                    <p class="text-xl text-gray-600">Everything you need to know about our loyalty program</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">How do I earn loyalty points?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">You earn points on every booking you make through GNC Travel. The standard rate is 1 point per $1 spent, with bonus multipliers for certain services and tier levels.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Do my points expire?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Points expire 24 months after they are earned. However, any activity in your account (earning or redeeming points) will extend the expiration date of all your points.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">How do I advance to the next tier?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Tier status is based on your total points earned in the current calendar year. Silver: 0-2,999 pts, Gold: 3,000-9,999 pts, Platinum: 10,000+ pts.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Can I transfer points to someone else?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Points transfers are available for Gold and Platinum members. You can transfer up to 10,000 points per year to family members or friends for a small processing fee.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">What happens if I cancel a booking?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">If you cancel a booking, any points earned from that booking will be deducted from your account. Points used for redemption will be returned to your account within 5-7 business days.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});

function toggleFAQ(element) {
    const content = element.nextElementSibling;
    const icon = element.querySelector('i[data-lucide="chevron-down"]');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>

@push('styles')
<style>
.animate-fade-in {
    animation: fadeIn 1s ease-out;
}

.animate-slide-up {
    animation: slideUp 1s ease-out 0.3s both;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush
@endsection