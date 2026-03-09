@extends('layouts.main')

@section('title', 'GDPR Consent - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-gray-700 to-blue-600 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    GDPR Consent
                </h1>
                <p class="text-xl md:text-2xl text-gray-100 mb-8 animate-slide-up">
                    Your privacy is our priority. Manage your data preferences and consent settings
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="flex items-center justify-center gap-4">
                        <i data-lucide="shield-check" class="w-8 h-8 text-green-400"></i>
                        <span class="text-lg">GDPR Compliant</span>
                        <i data-lucide="lock" class="w-8 h-8 text-blue-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Sidebar Navigation -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                                <i data-lucide="menu" class="w-5 h-5 mr-2"></i>
                                Quick Navigation
                            </h3>
                            <nav class="space-y-2">
                                <a href="#consent-overview" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                    <i data-lucide="info" class="w-4 h-4 mr-3"></i>
                                    Overview
                                </a>
                                <a href="#consent-settings" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                    <i data-lucide="settings" class="w-4 h-4 mr-3"></i>
                                    Consent Settings
                                </a>
                                <a href="#data-rights" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                    <i data-lucide="user-check" class="w-4 h-4 mr-3"></i>
                                    Your Rights
                                </a>
                                <a href="#data-processing" class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                    <i data-lucide="database" class="w-4 h-4 mr-3"></i>
                                    Data Processing
                                </a>
                            </nav>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Overview Section -->
                        <div id="consent-overview" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6">
                                <h2 class="text-2xl font-bold flex items-center">
                                    <i data-lucide="shield" class="w-6 h-6 mr-3"></i>
                                    Privacy & Consent Overview
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="prose max-w-none">
                                    <p class="text-gray-600 mb-4">
                                        Under the General Data Protection Regulation (GDPR), you have control over how we process your personal data. 
                                        This page allows you to view and modify your consent preferences for different types of data processing.
                                    </p>
                                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                                        <p class="text-blue-800">
                                            <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                                            <strong>Important:</strong> Some data processing is necessary for us to provide our travel services and cannot be opted out of. 
                                            These are clearly marked below.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Consent Settings Form -->
                        <div id="consent-settings" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-6">
                                <h2 class="text-2xl font-bold flex items-center">
                                    <i data-lucide="settings" class="w-6 h-6 mr-3"></i>
                                    Manage Your Consent
                                </h2>
                            </div>
                            <div class="p-6">
                                <form action="{{ route('gdpr.consent.update') }}" method="POST" id="consentForm">
                                    @csrf
                                    @method('PUT')

                                    <!-- Essential Processing -->
                                    <div class="mb-8">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                            <i data-lucide="lock" class="w-5 h-5 mr-2 text-green-600"></i>
                                            Essential Processing (Required)
                                        </h3>
                                        <div class="space-y-4">
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-800">Account Management</h4>
                                                        <p class="text-sm text-gray-600">Processing necessary to create and maintain your account, process bookings, and provide customer support.</p>
                                                    </div>
                                                    <div class="ml-4">
                                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Required</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-800">Legal Compliance</h4>
                                                        <p class="text-sm text-gray-600">Processing required by law, including tax records, fraud prevention, and regulatory compliance.</p>
                                                    </div>
                                                    <div class="ml-4">
                                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Required</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Optional Processing -->
                                    <div class="mb-8">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                            <i data-lucide="toggle-left" class="w-5 h-5 mr-2 text-blue-600"></i>
                                            Optional Processing
                                        </h3>
                                        <div class="space-y-4">
                                            <!-- Marketing Communications -->
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-800">Marketing Communications</h4>
                                                        <p class="text-sm text-gray-600">Receive promotional emails, newsletters, and special offers about travel deals and new services.</p>
                                                    </div>
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" name="marketing_consent" value="1" 
                                                               {{ auth()->user()->marketing_consent ?? false ? 'checked' : '' }}
                                                               class="sr-only">
                                                        <div class="toggle-switch w-12 h-6 bg-gray-300 rounded-full relative transition-colors duration-200">
                                                            <div class="toggle-thumb w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 transition-transform duration-200"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Analytics and Performance -->
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-800">Analytics & Performance</h4>
                                                        <p class="text-sm text-gray-600">Allow us to analyze website usage and improve our services through performance monitoring.</p>
                                                    </div>
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" name="analytics_consent" value="1"
                                                               {{ auth()->user()->analytics_consent ?? false ? 'checked' : '' }}
                                                               class="sr-only">
                                                        <div class="toggle-switch w-12 h-6 bg-gray-300 rounded-full relative transition-colors duration-200">
                                                            <div class="toggle-thumb w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 transition-transform duration-200"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Personalization -->
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-800">Personalization</h4>
                                                        <p class="text-sm text-gray-600">Customize your experience with personalized recommendations and content based on your preferences.</p>
                                                    </div>
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" name="personalization_consent" value="1"
                                                               {{ auth()->user()->personalization_consent ?? false ? 'checked' : '' }}
                                                               class="sr-only">
                                                        <div class="toggle-switch w-12 h-6 bg-gray-300 rounded-full relative transition-colors duration-200">
                                                            <div class="toggle-thumb w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 transition-transform duration-200"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Third-party Sharing -->
                                            <div class="border border-gray-200 rounded-lg p-4">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex-1">
                                                        <h4 class="font-medium text-gray-800">Third-party Partners</h4>
                                                        <p class="text-sm text-gray-600">Share your data with trusted travel partners to provide enhanced services and exclusive offers.</p>
                                                    </div>
                                                    <label class="flex items-center cursor-pointer">
                                                        <input type="checkbox" name="third_party_consent" value="1"
                                                               {{ auth()->user()->third_party_consent ?? false ? 'checked' : '' }}
                                                               class="sr-only">
                                                        <div class="toggle-switch w-12 h-6 bg-gray-300 rounded-full relative transition-colors duration-200">
                                                            <div class="toggle-thumb w-5 h-5 bg-white rounded-full absolute top-0.5 left-0.5 transition-transform duration-200"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Consent History -->
                                    @auth
                                    <div class="mb-8">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                            <i data-lucide="clock" class="w-5 h-5 mr-2 text-purple-600"></i>
                                            Consent History
                                        </h3>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="text-sm text-gray-600">
                                                <p><strong>Last Updated:</strong> {{ auth()->user()->consent_updated_at ? auth()->user()->consent_updated_at->format('F j, Y \a\t g:i A') : 'Never' }}</p>
                                                <p><strong>Account Created:</strong> {{ auth()->user()->created_at->format('F j, Y \a\t g:i A') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endauth

                                    <!-- Submit Button -->
                                    <div class="text-center">
                                        <button type="submit" 
                                                class="px-8 py-4 bg-gradient-to-r from-green-500 to-blue-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                                            <i data-lucide="save" class="w-5 h-5 inline-block mr-2"></i>
                                            Update Consent Preferences
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Your Rights Section -->
                        <div id="data-rights" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6">
                                <h2 class="text-2xl font-bold flex items-center">
                                    <i data-lucide="user-check" class="w-6 h-6 mr-3"></i>
                                    Your Data Rights
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <i data-lucide="eye" class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Right to Access</h4>
                                                <p class="text-sm text-gray-600">Request a copy of all personal data we hold about you</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start">
                                            <i data-lucide="edit" class="w-5 h-5 text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Right to Rectification</h4>
                                                <p class="text-sm text-gray-600">Correct any inaccurate or incomplete personal data</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <i data-lucide="trash-2" class="w-5 h-5 text-red-500 mt-1 mr-3 flex-shrink-0"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Right to Erasure</h4>
                                                <p class="text-sm text-gray-600">Request deletion of your personal data (right to be forgotten)</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <div class="flex items-start">
                                            <i data-lucide="pause" class="w-5 h-5 text-orange-500 mt-1 mr-3 flex-shrink-0"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Right to Restrict Processing</h4>
                                                <p class="text-sm text-gray-600">Limit how we process your personal data</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <i data-lucide="download" class="w-5 h-5 text-purple-500 mt-1 mr-3 flex-shrink-0"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Right to Data Portability</h4>
                                                <p class="text-sm text-gray-600">Receive your data in a portable format</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start">
                                            <i data-lucide="x-circle" class="w-5 h-5 text-gray-500 mt-1 mr-3 flex-shrink-0"></i>
                                            <div>
                                                <h4 class="font-semibold text-gray-800">Right to Object</h4>
                                                <p class="text-sm text-gray-600">Object to certain types of data processing</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 text-center">
                                    <a href="{{ route('contactus') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                        <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                                        Exercise Your Rights
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Data Processing Information -->
                        <div id="data-processing" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6">
                                <h2 class="text-2xl font-bold flex items-center">
                                    <i data-lucide="database" class="w-6 h-6 mr-3"></i>
                                    How We Process Your Data
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-6">
                                    <div class="border-l-4 border-blue-500 pl-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Data Collection</h4>
                                        <p class="text-gray-600">We collect data you provide directly, automatically through your use of our services, and from third-party sources when necessary.</p>
                                    </div>

                                    <div class="border-l-4 border-green-500 pl-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Data Storage</h4>
                                        <p class="text-gray-600">Your data is stored securely in EU-based data centers with appropriate technical and organizational measures.</p>
                                    </div>

                                    <div class="border-l-4 border-purple-500 pl-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Data Retention</h4>
                                        <p class="text-gray-600">We retain your data only as long as necessary for the purposes outlined in our privacy policy or as required by law.</p>
                                    </div>

                                    <div class="border-l-4 border-orange-500 pl-4">
                                        <h4 class="font-semibold text-gray-800 mb-2">Data Sharing</h4>
                                        <p class="text-gray-600">We only share your data with trusted partners necessary to provide our services and only with your explicit consent where required.</p>
                                    </div>
                                </div>

                                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-blue-800 text-sm">
                                        <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                                        For detailed information about our data processing practices, please read our 
                                        <a href="{{ route('privacy-policy') }}" class="text-blue-600 hover:underline font-medium">Privacy Policy</a>.
                                    </p>
                                </div>
                            </div>
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

    // Toggle switch functionality
    const toggles = document.querySelectorAll('.toggle-switch');
    
    toggles.forEach(toggle => {
        const checkbox = toggle.previousElementSibling;
        const thumb = toggle.querySelector('.toggle-thumb');
        
        function updateToggle() {
            if (checkbox.checked) {
                toggle.classList.remove('bg-gray-300');
                toggle.classList.add('bg-green-500');
                thumb.style.transform = 'translateX(1.5rem)';
            } else {
                toggle.classList.remove('bg-green-500');
                toggle.classList.add('bg-gray-300');
                thumb.style.transform = 'translateX(0)';
            }
        }
        
        // Initial state
        updateToggle();
        
        // Toggle on click
        toggle.addEventListener('click', function() {
            checkbox.checked = !checkbox.checked;
            updateToggle();
        });
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Form submission with confirmation
    document.getElementById('consentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to update your consent preferences?')) {
            this.submit();
        }
    });
});
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

.toggle-switch:hover {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.prose {
    line-height: 1.6;
}

.prose p {
    margin-bottom: 1rem;
}
</style>
@endpush
@endsection