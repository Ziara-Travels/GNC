@extends('layouts.main')

@section('title', 'Travel Insurance - Ziara Travel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100">
    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-r from-blue-600 to-cyan-700 text-white overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
                    Travel Insurance
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-8 animate-slide-up">
                    Protect your journey with comprehensive travel insurance coverage
                </p>
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 inline-block">
                    <div class="text-4xl font-bold mb-2">Peace of Mind</div>
                    <div class="text-lg">For every adventure</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why You Need Travel Insurance -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Why Travel Insurance Matters</h2>
                    <p class="text-xl text-gray-600">Unexpected events can happen - be prepared with the right coverage</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center p-6">
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="heart-pulse" class="w-10 h-10 text-red-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Medical Emergencies</h3>
                        <p class="text-gray-600">Coverage for unexpected medical expenses, hospital stays, and emergency evacuations while traveling abroad.</p>
                    </div>

                    <div class="text-center p-6">
                        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="x-circle" class="w-10 h-10 text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Trip Cancellation</h3>
                        <p class="text-gray-600">Reimbursement for non-refundable trip costs if you need to cancel due to covered reasons.</p>
                    </div>

                    <div class="text-center p-6">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="luggage" class="w-10 h-10 text-purple-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Baggage Protection</h3>
                        <p class="text-gray-600">Coverage for lost, stolen, or damaged luggage and personal belongings during your trip.</p>
                    </div>

                    <div class="text-center p-6">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="clock" class="w-10 h-10 text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Travel Delays</h3>
                        <p class="text-gray-600">Compensation for additional expenses due to flight delays, missed connections, or travel interruptions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Insurance Plans -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Choose Your Coverage</h2>
                    <p class="text-xl text-gray-600">Select the plan that best fits your travel needs and budget</p>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Basic Plan -->
                    <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Basic Coverage</h3>
                            <p class="text-gray-600 mb-6">Essential protection for budget travelers</p>
                            <div class="text-4xl font-bold text-blue-600 mb-2">$29</div>
                            <div class="text-gray-600">per trip</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Medical expenses up to $50,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Trip cancellation up to $5,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Baggage coverage up to $1,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                24/7 assistance hotline
                            </li>
                            <li class="flex items-center text-gray-400">
                                <i data-lucide="x" class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0"></i>
                                Adventure sports coverage
                            </li>
                            <li class="flex items-center text-gray-400">
                                <i data-lucide="x" class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0"></i>
                                Pre-existing medical conditions
                            </li>
                        </ul>

                        <button class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition-colors duration-300">
                            Choose Basic Plan
                        </button>
                    </div>

                    <!-- Standard Plan -->
                    <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 relative border-2 border-green-400">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-bold">MOST POPULAR</span>
                        </div>
                        
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Standard Coverage</h3>
                            <p class="text-gray-600 mb-6">Comprehensive protection for most travelers</p>
                            <div class="text-4xl font-bold text-green-600 mb-2">$59</div>
                            <div class="text-gray-600">per trip</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Medical expenses up to $100,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Trip cancellation up to $10,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Baggage coverage up to $2,500
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                24/7 assistance & concierge
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Adventure sports coverage
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Emergency evacuation
                            </li>
                        </ul>

                        <button class="w-full bg-green-600 text-white py-4 rounded-2xl font-bold hover:bg-green-700 transition-colors duration-300">
                            Choose Standard Plan
                        </button>
                    </div>

                    <!-- Premium Plan -->
                    <div class="bg-white rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Premium Coverage</h3>
                            <p class="text-gray-600 mb-6">Maximum protection for luxury travelers</p>
                            <div class="text-4xl font-bold text-purple-600 mb-2">$99</div>
                            <div class="text-gray-600">per trip</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Medical expenses up to $250,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Trip cancellation up to $25,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Baggage coverage up to $5,000
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                24/7 premium assistance
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                All adventure sports covered
                            </li>
                            <li class="flex items-center text-gray-700">
                                <i data-lucide="check" class="w-5 h-5 text-green-500 mr-3 flex-shrink-0"></i>
                                Pre-existing conditions covered
                            </li>
                        </ul>

                        <button class="w-full bg-purple-600 text-white py-4 rounded-2xl font-bold hover:bg-purple-700 transition-colors duration-300">
                            Choose Premium Plan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What's Covered -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">What's Covered</h2>
                    <p class="text-xl text-gray-600">Comprehensive protection for various travel scenarios</p>
                </div>

                <div class="grid md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Medical & Health Coverage</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Emergency medical treatment</strong>
                                    <p class="text-gray-600">Hospital stays, doctor visits, and prescription medications</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Emergency evacuation</strong>
                                    <p class="text-gray-600">Air ambulance and medical transportation to appropriate facilities</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Dental emergencies</strong>
                                    <p class="text-gray-600">Emergency dental treatment due to accident or acute pain</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Repatriation</strong>
                                    <p class="text-gray-600">Return of remains or medical repatriation to home country</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6">Trip Protection</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Trip cancellation</strong>
                                    <p class="text-gray-600">Illness, injury, death, or other covered reasons</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Trip interruption</strong>
                                    <p class="text-gray-600">Early return due to covered emergencies</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Travel delays</strong>
                                    <p class="text-gray-600">Additional accommodation and meal expenses</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong class="text-gray-800">Missed connections</strong>
                                    <p class="text-gray-600">Alternative transportation and accommodation costs</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Claims Process -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Simple Claims Process</h2>
                    <p class="text-xl text-gray-600">Filing a claim is easy and straightforward</p>
                </div>

                <div class="grid md:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">1</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Contact Us</h3>
                        <p class="text-gray-600">Call our 24/7 assistance hotline or file online within 30 days of the incident</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">2</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Submit Documents</h3>
                        <p class="text-gray-600">Provide required documentation such as receipts, medical reports, or police reports</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">3</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Claim Review</h3>
                        <p class="text-gray-600">Our claims team reviews your submission and may request additional information</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl font-bold text-blue-600">4</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Get Paid</h3>
                        <p class="text-gray-600">Approved claims are typically processed and paid within 15 business days</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-800 mb-4">Insurance FAQ</h2>
                    <p class="text-xl text-gray-600">Common questions about travel insurance</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-xl">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-100 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">When should I buy travel insurance?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">It's best to buy travel insurance within 14-21 days of making your initial trip deposit to ensure maximum coverage benefits, including coverage for pre-existing medical conditions.</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-100 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">What if I need to cancel my trip?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Trip cancellation coverage reimburses you for non-refundable trip costs if you need to cancel for a covered reason such as illness, injury, or other qualifying events listed in your policy.</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-100 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Does insurance cover adventure activities?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Our Standard and Premium plans include coverage for many adventure activities. Check your policy details for the complete list of covered activities, or contact us for specific questions about your planned activities.</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-100 transition-colors duration-200"
                                onclick="toggleFAQ(this)">
                            <span class="font-semibold text-gray-800">Can I extend my insurance while traveling?</span>
                            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-500 transition-transform duration-200"></i>
                        </button>
                        <div class="hidden px-6 pb-4">
                            <p class="text-gray-600">Yes, you can extend your coverage for up to 30 additional days while traveling, subject to underwriting approval. Contact our customer service team before your current policy expires to arrange an extension.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-cyan-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-6">Ready to Protect Your Trip?</h2>
                <p class="text-xl text-blue-100 mb-8">
                    Get a quote in minutes and travel with confidence
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="px-8 py-4 bg-white text-blue-600 font-bold rounded-2xl hover:bg-gray-100 transition-colors duration-300">
                        <i data-lucide="calculator" class="w-5 h-5 inline-block mr-2"></i>
                        Get a Quote
                    </button>
                    <button class="px-8 py-4 bg-blue-800 text-white font-bold rounded-2xl hover:bg-blue-900 transition-colors duration-300">
                        <i data-lucide="phone" class="w-5 h-5 inline-block mr-2"></i>
                        Call for Assistance
                    </button>
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