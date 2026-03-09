@extends('layouts.main')

@section('title', 'Referral Program - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-green-500 to-blue-600 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Referral Program
                </h1>
                <p class="text-xl md:text-2xl text-green-100 mb-8 animate-slide-up">
                    Share the joy of travel and earn rewards for every friend you refer
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="text-4xl font-bold mb-2">$50</div>
                    <div class="text-lg">Earn for each successful referral</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Simple steps to start earning with our referral program
                </p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <i data-lucide="user-plus" class="w-12 h-12 text-green-600"></i>
                            <span class="absolute -top-2 -right-2 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Share Your Link</h3>
                        <p class="text-gray-600">
                            Get your unique referral link and share it with friends, family, or on social media
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <i data-lucide="plane" class="w-12 h-12 text-blue-600"></i>
                            <span class="absolute -top-2 -right-2 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Friends Book</h3>
                        <p class="text-gray-600">
                            Your friends sign up and make their first booking of $200 or more
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <i data-lucide="gift" class="w-12 h-12 text-purple-600"></i>
                            <span class="absolute -top-2 -right-2 w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Earn Rewards</h3>
                        <p class="text-gray-600">
                            Both you and your friend receive $50 credit for future bookings
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Referral Benefits</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Amazing rewards for you and your friends
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Benefit 1 -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-green-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="dollar-sign" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">$50 For You</h3>
                    <p class="text-gray-600">Earn $50 credit for each successful referral</p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-blue-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="heart" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">$50 For Friends</h3>
                    <p class="text-gray-600">Your friends also get $50 off their first booking</p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="infinity" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Unlimited Referrals</h3>
                    <p class="text-gray-600">No limit on how many friends you can refer</p>
                </div>

                <!-- Benefit 4 -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-orange-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="clock" class="w-8 h-8 text-orange-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Never Expires</h3>
                    <p class="text-gray-600">Your referral credits never expire</p>
                </div>
            </div>
        </div>
    </section>

    @auth
    <!-- User Dashboard Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Your Referral Dashboard</h2>
                    
                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center p-6 bg-green-50 rounded-xl">
                            <div class="text-3xl font-bold text-green-600 mb-2">0</div>
                            <div class="text-gray-600">Successful Referrals</div>
                        </div>
                        <div class="text-center p-6 bg-blue-50 rounded-xl">
                            <div class="text-3xl font-bold text-blue-600 mb-2">$0</div>
                            <div class="text-gray-600">Total Earned</div>
                        </div>
                        <div class="text-center p-6 bg-purple-50 rounded-xl">
                            <div class="text-3xl font-bold text-purple-600 mb-2">$0</div>
                            <div class="text-gray-600">Available Credit</div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Your Referral Link</h3>
                        <div class="flex items-center gap-4">
                            <input type="text" 
                                   value="{{ url('/register?ref=' . auth()->user()->id) }}" 
                                   readonly 
                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-green-500"
                                   id="referralLink">
                            <button onclick="copyReferralLink()" 
                                    class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2">
                                <i data-lucide="copy" class="w-4 h-4"></i>
                                Copy
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Share this link with friends to start earning rewards!</p>
                    </div>

                    <!-- Social Sharing -->
                    <div class="mt-8 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">Share on Social Media</h3>
                        <div class="flex justify-center gap-4">
                            <button onclick="shareOnFacebook()" 
                                    class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <i data-lucide="facebook" class="w-5 h-5"></i>
                                Facebook
                            </button>
                            <button onclick="shareOnTwitter()" 
                                    class="flex items-center gap-2 px-6 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors duration-200">
                                <i data-lucide="twitter" class="w-5 h-5"></i>
                                Twitter
                            </button>
                            <button onclick="shareOnWhatsApp()" 
                                    class="flex items-center gap-2 px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors duration-200">
                                <i data-lucide="message-circle" class="w-5 h-5"></i>
                                WhatsApp
                            </button>
                            <button onclick="shareByEmail()" 
                                    class="flex items-center gap-2 px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                                Email
                            </button>
                        </div>
                    </div>

                    <!-- Recent Referrals -->
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Referrals</h3>
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="text-center text-gray-500">
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-4 text-gray-400"></i>
                                <p>No referrals yet. Start sharing your link to earn rewards!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <!-- Guest Call to Action -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <i data-lucide="user-plus" class="w-16 h-16 text-green-600 mx-auto mb-6"></i>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Join Our Referral Program</h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Sign up today to get your unique referral link and start earning rewards
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" 
                           class="px-8 py-4 bg-gradient-to-r from-green-500 to-blue-600 text-white rounded-lg hover:from-green-600 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i data-lucide="user-plus" class="w-5 h-5 inline-block mr-2"></i>
                            Sign Up Now
                        </a>
                        <a href="{{ route('login') }}" 
                           class="px-8 py-4 border-2 border-green-600 text-green-600 rounded-lg hover:bg-green-600 hover:text-white transition-all duration-300">
                            <i data-lucide="log-in" class="w-5 h-5 inline-block mr-2"></i>
                            Already have an account?
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endauth

    <!-- Terms and Conditions -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Program Terms & Conditions</h2>
                    
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-2"></i>
                                Eligibility
                            </h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• Must be a registered Ziara Travel user</li>
                                <li>• Referred friends must be new customers</li>
                                <li>• Minimum booking value of $200 required</li>
                                <li>• Valid for completed bookings only</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i data-lucide="gift" class="w-5 h-5 text-blue-600 mr-2"></i>
                                Rewards
                            </h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• $50 credit for successful referrals</li>
                                <li>• Credits processed within 7 days</li>
                                <li>• Credits never expire</li>
                                <li>• Can be used for any future booking</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i data-lucide="clock" class="w-5 h-5 text-purple-600 mr-2"></i>
                                Timing
                            </h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• Referral must be used within 30 days</li>
                                <li>• Booking must be completed within 60 days</li>
                                <li>• Credits issued after trip completion</li>
                                <li>• Travel must be completed successfully</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i data-lucide="shield" class="w-5 h-5 text-red-600 mr-2"></i>
                                Important Notes
                            </h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• Self-referrals are not allowed</li>
                                <li>• Credits are non-transferable</li>
                                <li>• Program subject to change</li>
                                <li>• Ziara Travel reserves final decision rights</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                            <strong>Note:</strong> This referral program is subject to our 
                            <a href="{{ route('terms-of-service') }}" class="text-blue-600 hover:underline">Terms of Service</a> 
                            and may be modified or discontinued at any time. For questions, contact our 
                            <a href="{{ route('contactus') }}" class="text-blue-600 hover:underline">support team</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Frequently Asked Questions</h2>
                    <p class="text-xl text-gray-600">
                        Everything you need to know about our referral program
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">How do I get my referral link?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Once you sign up and log in, you'll find your unique referral link in your dashboard. You can also access it from this page when logged in.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">When do I receive my referral credit?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Credits are processed within 7 days after your referred friend completes their travel. Both you and your friend will receive an email confirmation when credits are added.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Is there a limit to how many people I can refer?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">No! You can refer as many friends as you like. Each successful referral earns you $50 credit with no upper limit.</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Do my referral credits expire?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">No, your referral credits never expire. Use them whenever you're ready for your next adventure!</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Can I refer someone who already has an account?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Unfortunately, no. Referral credits are only awarded for new customers who haven't previously booked with Ziara Travel.</p>
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

function copyReferralLink() {
    const linkInput = document.getElementById('referralLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    
    try {
        document.execCommand('copy');
        
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Copied!';
        button.classList.add('bg-green-700');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-700');
            lucide.createIcons();
        }, 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy link. Please copy manually.');
    }
}

function shareOnFacebook() {
    const url = encodeURIComponent(document.getElementById('referralLink').value);
    const text = encodeURIComponent('Join me on Ziara Travel and get $50 off your first booking!');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}&quote=${text}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(document.getElementById('referralLink').value);
    const text = encodeURIComponent('Join me on Ziara Travel and get $50 off your first booking! 🛫✈️');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(document.getElementById('referralLink').value);
    const text = encodeURIComponent('Hey! Join me on Ziara Travel and get $50 off your first booking: ');
    window.open(`https://wa.me/?text=${text}${url}`, '_blank');
}

function shareByEmail() {
    const url = encodeURIComponent(document.getElementById('referralLink').value);
    const subject = encodeURIComponent('Get $50 off your first booking with Ziara Travel!');
    const body = encodeURIComponent(`Hi there!\n\nI wanted to share this amazing travel platform with you. Ziara Travel offers incredible deals on flights, hotels, and travel packages.\n\nUse my referral link to sign up and you'll get $50 off your first booking: ${document.getElementById('referralLink').value}\n\nHappy travels!\n\nBest regards`);
    window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
}

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