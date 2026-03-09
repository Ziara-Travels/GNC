@extends('layouts.main')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-20">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center px-4 py-2 bg-blue-500/20 rounded-full mb-6">
            <i data-lucide="shield-check" class="w-5 h-5 text-blue-400 mr-2"></i>
            <span class="text-blue-300 font-medium">Your Privacy Matters</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Privacy Policy</h1>
        <p class="text-xl text-slate-300 mb-8 max-w-2xl mx-auto">
            We are committed to protecting your personal information and your right to privacy
        </p>
    </div>
</div>

<!-- Content Section -->
<div class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Last Updated -->
        <div class="mb-12 p-6 bg-blue-50 rounded-xl border border-blue-200">
            <div class="flex items-center mb-2">
                <i data-lucide="calendar" class="w-5 h-5 text-blue-600 mr-2"></i>
                <span class="text-sm font-medium text-blue-800">Last Updated: {{ date('F j, Y') }}</span>
            </div>
            <p class="text-blue-700 text-sm">This privacy policy was last updated on {{ date('F j, Y') }} and is compliant with GDPR regulations.</p>
        </div>

        <!-- Table of Contents -->
        <div class="mb-12 p-6 bg-slate-50 rounded-xl">
            <h2 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i data-lucide="list" class="w-5 h-5 mr-2"></i>
                Table of Contents
            </h2>
            <div class="grid md:grid-cols-2 gap-2">
                <a href="#data-collection" class="text-blue-600 hover:text-blue-800 text-sm">1. Data Collection</a>
                <a href="#data-use" class="text-blue-600 hover:text-blue-800 text-sm">2. How We Use Your Data</a>
                <a href="#data-sharing" class="text-blue-600 hover:text-blue-800 text-sm">3. Data Sharing</a>
                <a href="#data-retention" class="text-blue-600 hover:text-blue-800 text-sm">4. Data Retention</a>
                <a href="#your-rights" class="text-blue-600 hover:text-blue-800 text-sm">5. Your Rights (GDPR)</a>
                <a href="#cookies" class="text-blue-600 hover:text-blue-800 text-sm">6. Cookies Policy</a>
                <a href="#security" class="text-blue-600 hover:text-blue-800 text-sm">7. Data Security</a>
                <a href="#contact" class="text-blue-600 hover:text-blue-800 text-sm">8. Contact Information</a>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="prose prose-lg max-w-none">
            <!-- Data Collection Section -->
            <section id="data-collection" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="database" class="w-6 h-6 mr-3 text-blue-600"></i>
                    1. Data Collection
                </h2>
                
                <div class="space-y-6">
                    <div class="p-6 bg-gray-50 rounded-xl">
                        <h3 class="font-semibold text-slate-800 mb-3">Personal Information We Collect:</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <i data-lucide="user" class="w-4 h-4 text-blue-600 mt-1 mr-2"></i>
                                <span><strong>Account Information:</strong> Name, email address, phone number, date of birth</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="plane" class="w-4 h-4 text-blue-600 mt-1 mr-2"></i>
                                <span><strong>Travel Information:</strong> Passport details, travel preferences, booking history</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="credit-card" class="w-4 h-4 text-blue-600 mt-1 mr-2"></i>
                                <span><strong>Payment Information:</strong> Credit card details, billing address, payment history</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="activity" class="w-4 h-4 text-blue-600 mt-1 mr-2"></i>
                                <span><strong>Usage Data:</strong> IP address, browser type, pages visited, search queries</span>
                            </li>
                        </ul>
                    </div>

                    <div class="p-6 bg-amber-50 rounded-xl border border-amber-200">
                        <h3 class="font-semibold text-amber-800 mb-3">Legal Basis for Processing (GDPR Article 6):</h3>
                        <ul class="space-y-2 text-amber-700">
                            <li>• <strong>Contract:</strong> Processing necessary for booking and travel services</li>
                            <li>• <strong>Legitimate Interest:</strong> Fraud prevention, security, and service improvement</li>
                            <li>• <strong>Consent:</strong> Marketing communications and cookies</li>
                            <li>• <strong>Legal Obligation:</strong> Compliance with aviation and travel regulations</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Data Use Section -->
            <section id="data-use" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="settings" class="w-6 h-6 mr-3 text-blue-600"></i>
                    2. How We Use Your Data
                </h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-6 bg-green-50 rounded-xl border border-green-200">
                        <h3 class="font-semibold text-green-800 mb-3">Primary Uses:</h3>
                        <ul class="space-y-2 text-green-700">
                            <li>• Process flight and hotel bookings</li>
                            <li>• Provide customer support</li>
                            <li>• Send booking confirmations</li>
                            <li>• Comply with legal requirements</li>
                        </ul>
                    </div>
                    
                    <div class="p-6 bg-blue-50 rounded-xl border border-blue-200">
                        <h3 class="font-semibold text-blue-800 mb-3">Secondary Uses:</h3>
                        <ul class="space-y-2 text-blue-700">
                            <li>• Improve our services</li>
                            <li>• Personalize user experience</li>
                            <li>• Send marketing communications (with consent)</li>
                            <li>• Analyze website usage patterns</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Data Sharing Section -->
            <section id="data-sharing" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="share-2" class="w-6 h-6 mr-3 text-blue-600"></i>
                    3. Data Sharing
                </h2>
                
                <div class="space-y-6">
                    <div class="p-6 bg-red-50 rounded-xl border border-red-200">
                        <h3 class="font-semibold text-red-800 mb-3">We Never Sell Your Data</h3>
                        <p class="text-red-700">Your personal information is never sold to third parties for marketing purposes.</p>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-xl">
                        <h3 class="font-semibold text-slate-800 mb-3">We Share Data With:</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <i data-lucide="plane" class="w-4 h-4 text-blue-600 mt-1 mr-2"></i>
                                <div>
                                    <strong>Airlines & Hotels:</strong> To process your bookings and provide services
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="credit-card" class="w-4 h-4 text-blue-600 mt-1 mr-2"></i>
                                <div>
                                    <strong>Payment Processors:</strong> To process transactions securely
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="shield" class="w-4 h-4 text-blue-600 mt-1 mr-2"></i>
                                <div>
                                    <strong>Legal Authorities:</strong> When required by law or to protect our rights
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Data Retention Section -->
            <section id="data-retention" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="clock" class="w-6 h-6 mr-3 text-blue-600"></i>
                    4. Data Retention
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-slate-300">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="border border-slate-300 p-4 text-left">Data Type</th>
                                <th class="border border-slate-300 p-4 text-left">Retention Period</th>
                                <th class="border border-slate-300 p-4 text-left">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-slate-300 p-4">Account Information</td>
                                <td class="border border-slate-300 p-4">Until account deletion</td>
                                <td class="border border-slate-300 p-4">Service provision</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="border border-slate-300 p-4">Booking Records</td>
                                <td class="border border-slate-300 p-4">7 years</td>
                                <td class="border border-slate-300 p-4">Legal/tax requirements</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-300 p-4">Payment Data</td>
                                <td class="border border-slate-300 p-4">As per PCI DSS requirements</td>
                                <td class="border border-slate-300 p-4">Compliance & dispute resolution</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="border border-slate-300 p-4">Marketing Data</td>
                                <td class="border border-slate-300 p-4">Until consent withdrawn</td>
                                <td class="border border-slate-300 p-4">Marketing communications</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Your Rights Section -->
            <section id="your-rights" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="user-check" class="w-6 h-6 mr-3 text-blue-600"></i>
                    5. Your Rights Under GDPR
                </h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
                                <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                                Right to Access
                            </h3>
                            <p class="text-blue-700 text-sm">Request a copy of your personal data</p>
                        </div>
                        
                        <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                            <h3 class="font-semibold text-green-800 mb-2 flex items-center">
                                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                                Right to Rectification
                            </h3>
                            <p class="text-green-700 text-sm">Correct inaccurate personal data</p>
                        </div>
                        
                        <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                            <h3 class="font-semibold text-red-800 mb-2 flex items-center">
                                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                Right to Erasure
                            </h3>
                            <p class="text-red-700 text-sm">Request deletion of your data</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <h3 class="font-semibold text-purple-800 mb-2 flex items-center">
                                <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                Right to Portability
                            </h3>
                            <p class="text-purple-700 text-sm">Export your data in a readable format</p>
                        </div>
                        
                        <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                            <h3 class="font-semibold text-orange-800 mb-2 flex items-center">
                                <i data-lucide="pause" class="w-4 h-4 mr-2"></i>
                                Right to Restrict Processing
                            </h3>
                            <p class="text-orange-700 text-sm">Limit how we use your data</p>
                        </div>
                        
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="font-semibold text-gray-800 mb-2 flex items-center">
                                <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                                Right to Object
                            </h3>
                            <p class="text-gray-700 text-sm">Object to data processing</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 p-6 bg-blue-50 rounded-xl border border-blue-200">
                    <h3 class="font-semibold text-blue-800 mb-3">How to Exercise Your Rights:</h3>
                    <p class="text-blue-700 mb-4">Contact our Data Protection Officer at <a href="mailto:dpo@gnc.com" class="underline">dpo@gnc.com</a> or use our <a href="{{ route('gdpr.consent') }}" class="underline">GDPR portal</a>.</p>
                    <p class="text-blue-700 text-sm">We will respond to your request within 30 days as required by GDPR.</p>
                </div>
            </section>

            <!-- Cookies Section -->
            <section id="cookies" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="cookie" class="w-6 h-6 mr-3 text-blue-600"></i>
                    6. Cookies Policy
                </h2>
                
                <div class="space-y-6">
                    <div class="p-6 bg-slate-50 rounded-xl">
                        <h3 class="font-semibold text-slate-800 mb-3">Types of Cookies We Use:</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Essential Cookies</h4>
                                <p class="text-sm text-slate-600">Required for basic site functionality</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Performance Cookies</h4>
                                <p class="text-sm text-slate-600">Help us analyze site usage</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Functional Cookies</h4>
                                <p class="text-sm text-slate-600">Remember your preferences</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Marketing Cookies</h4>
                                <p class="text-sm text-slate-600">Deliver relevant advertisements</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-amber-50 rounded-xl border border-amber-200">
                        <h3 class="font-semibold text-amber-800 mb-3">Cookie Consent</h3>
                        <p class="text-amber-700 mb-4">We obtain your consent before using non-essential cookies. You can manage your cookie preferences at any time.</p>
                        <button class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors">
                            Manage Cookie Preferences
                        </button>
                    </div>
                </div>
            </section>

            <!-- Security Section -->
            <section id="security" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="shield" class="w-6 h-6 mr-3 text-blue-600"></i>
                    7. Data Security
                </h2>
                
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="p-6 bg-green-50 rounded-xl border border-green-200 text-center">
                        <i data-lucide="lock" class="w-12 h-12 text-green-600 mx-auto mb-3"></i>
                        <h3 class="font-semibold text-green-800 mb-2">SSL Encryption</h3>
                        <p class="text-green-700 text-sm">All data transmitted is encrypted using SSL/TLS</p>
                    </div>
                    
                    <div class="p-6 bg-blue-50 rounded-xl border border-blue-200 text-center">
                        <i data-lucide="server" class="w-12 h-12 text-blue-600 mx-auto mb-3"></i>
                        <h3 class="font-semibold text-blue-800 mb-2">Secure Servers</h3>
                        <p class="text-blue-700 text-sm">Data stored on secure, monitored servers</p>
                    </div>
                    
                    <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 text-center">
                        <i data-lucide="user-check" class="w-12 h-12 text-purple-600 mx-auto mb-3"></i>
                        <h3 class="font-semibold text-purple-800 mb-2">Access Controls</h3>
                        <p class="text-purple-700 text-sm">Limited access to authorized personnel only</p>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="mail" class="w-6 h-6 mr-3 text-blue-600"></i>
                    8. Contact Information
                </h2>
                
                <div class="p-6 bg-slate-50 rounded-xl">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="font-semibold text-slate-800 mb-3">Data Protection Officer</h3>
                            <p class="text-slate-600 mb-2">Email: <a href="mailto:dpo@gnc.com" class="text-blue-600 hover:underline">dpo@gnc.com</a></p>
                            <p class="text-slate-600 mb-2">Phone: +254 700 123 456</p>
                            <p class="text-slate-600">Address: Nairobi, Kenya</p>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-slate-800 mb-3">Supervisory Authority</h3>
                            <p class="text-slate-600 mb-2">Data Protection Commissioner</p>
                            <p class="text-slate-600 mb-2">Kenya</p>
                            <p class="text-slate-600">For complaints about data processing</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
<script>
    lucide.createIcons();
</script>
@endpush