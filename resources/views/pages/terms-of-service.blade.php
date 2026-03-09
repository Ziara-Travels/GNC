@extends('layouts.main')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-20">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center px-4 py-2 bg-amber-500/20 rounded-full mb-6">
            <i data-lucide="file-text" class="w-5 h-5 text-amber-400 mr-2"></i>
            <span class="text-amber-300 font-medium">Terms & Conditions</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">Terms of Service</h1>
        <p class="text-xl text-slate-300 mb-8 max-w-2xl mx-auto">
            Please read these terms carefully before using our travel booking services
        </p>
    </div>
</div>

<!-- Content Section -->
<div class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Last Updated -->
        <div class="mb-12 p-6 bg-amber-50 rounded-xl border border-amber-200">
            <div class="flex items-center mb-2">
                <i data-lucide="calendar" class="w-5 h-5 text-amber-600 mr-2"></i>
                <span class="text-sm font-medium text-amber-800">Last Updated: {{ date('F j, Y') }}</span>
            </div>
            <p class="text-amber-700 text-sm">These terms of service govern your use of Ziara Travel Services.</p>
        </div>

        <!-- Quick Agreement -->
        <div class="mb-12 p-6 bg-blue-50 rounded-xl border border-blue-200">
            <h2 class="text-lg font-semibold text-blue-800 mb-4">Agreement to Terms</h2>
            <p class="text-blue-700 mb-4">By accessing and using our website, you accept and agree to be bound by the terms and provision of this agreement.</p>
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-blue-600 mr-2"></i>
                <span class="text-blue-700 font-medium">By using our services, you agree to these terms</span>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="prose prose-lg max-w-none">
            <!-- Company Information -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="building" class="w-6 h-6 mr-3 text-blue-600"></i>
                    1. Company Information
                </h2>
                
                <div class="p-6 bg-slate-50 rounded-xl">
                    <h3 class="font-semibold text-slate-800 mb-3">Ziara Travel Services</h3>
                    <ul class="space-y-2 text-slate-700">
                        <li>• <strong>Company:</strong> Ziara Travel Services Limited</li>
                        <li>• <strong>Address:</strong> Nairobi, Kenya</li>
                        <li>• <strong>Registration:</strong> Licensed travel agent in Kenya</li>
                        <li>• <strong>Contact:</strong> support@Ziara.com | +254 700 123 456</li>
                    </ul>
                </div>
            </section>

            <!-- Services -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="plane" class="w-6 h-6 mr-3 text-blue-600"></i>
                    2. Services Provided
                </h2>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-6 bg-green-50 rounded-xl border border-green-200">
                        <h3 class="font-semibold text-green-800 mb-3">Flight Services</h3>
                        <ul class="space-y-2 text-green-700 text-sm">
                            <li>• Flight search and comparison</li>
                            <li>• Booking and ticketing</li>
                            <li>• Seat selection and upgrades</li>
                            <li>• Travel insurance</li>
                        </ul>
                    </div>
                    
                    <div class="p-6 bg-blue-50 rounded-xl border border-blue-200">
                        <h3 class="font-semibold text-blue-800 mb-3">Hotel Services</h3>
                        <ul class="space-y-2 text-blue-700 text-sm">
                            <li>• Hotel search and booking</li>
                            <li>• Room selection and amenities</li>
                            <li>• Special requests handling</li>
                            <li>• Cancellation management</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Booking Terms -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="calendar-check" class="w-6 h-6 mr-3 text-blue-600"></i>
                    3. Booking Terms & Conditions
                </h2>
                
                <div class="space-y-6">
                    <div class="p-6 bg-red-50 rounded-xl border border-red-200">
                        <h3 class="font-semibold text-red-800 mb-3">Important Booking Requirements</h3>
                        <ul class="space-y-2 text-red-700">
                            <li>• All bookings must be made by persons 18+ years old</li>
                            <li>• Accurate passenger information is mandatory</li>
                            <li>• Travel documents must be valid for minimum 6 months</li>
                            <li>• Payment must be completed within 24 hours of booking</li>
                        </ul>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-xl">
                        <h3 class="font-semibold text-slate-800 mb-3">Flight Booking Conditions</h3>
                        <ul class="space-y-2 text-slate-700">
                            <li>• Prices are subject to availability and may change without notice</li>
                            <li>• Fare rules and restrictions apply as per airline policies</li>
                            <li>• Schedule changes may occur due to operational reasons</li>
                            <li>• Baggage allowances vary by airline and fare type</li>
                        </ul>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-xl">
                        <h3 class="font-semibold text-slate-800 mb-3">Hotel Booking Conditions</h3>
                        <ul class="space-y-2 text-slate-700">
                            <li>• Room rates are per night and exclude taxes unless stated</li>
                            <li>• Check-in/out times are as per hotel policy</li>
                            <li>• Special requests are subject to availability</li>
                            <li>• Cancellation policies vary by hotel and rate type</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Payment Terms -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="credit-card" class="w-6 h-6 mr-3 text-blue-600"></i>
                    4. Payment Terms
                </h2>
                
                <div class="space-y-6">
                    <div class="p-6 bg-green-50 rounded-xl border border-green-200">
                        <h3 class="font-semibold text-green-800 mb-3">Accepted Payment Methods</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <i data-lucide="smartphone" class="w-5 h-5 text-green-600 mr-2"></i>
                                <span class="text-green-700">M-Pesa Mobile Money</span>
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="credit-card" class="w-5 h-5 text-green-600 mr-2"></i>
                                <span class="text-green-700">Credit/Debit Cards</span>
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="building-2" class="w-5 h-5 text-green-600 mr-2"></i>
                                <span class="text-green-700">Bank Transfers</span>
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="wallet" class="w-5 h-5 text-green-600 mr-2"></i>
                                <span class="text-green-700">Digital Wallets</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-amber-50 rounded-xl border border-amber-200">
                        <h3 class="font-semibold text-amber-800 mb-3">Payment Policies</h3>
                        <ul class="space-y-2 text-amber-700">
                            <li>• Full payment required at time of booking</li>
                            <li>• Service fees are non-refundable</li>
                            <li>• Currency conversion rates may apply</li>
                            <li>• Failed payments result in automatic booking cancellation</li>
                            <li>• Refunds processed within 7-14 business days</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Cancellation Policy -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="x-circle" class="w-6 h-6 mr-3 text-blue-600"></i>
                    5. Cancellation & Refund Policy
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-slate-300">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="border border-slate-300 p-4 text-left">Cancellation Time</th>
                                <th class="border border-slate-300 p-4 text-left">Flights</th>
                                <th class="border border-slate-300 p-4 text-left">Hotels</th>
                                <th class="border border-slate-300 p-4 text-left">Service Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-slate-300 p-4">Within 24 hours</td>
                                <td class="border border-slate-300 p-4">Full refund*</td>
                                <td class="border border-slate-300 p-4">Full refund*</td>
                                <td class="border border-slate-300 p-4">Refundable</td>
                            </tr>
                            <tr class="bg-slate-50">
                                <td class="border border-slate-300 p-4">1-7 days</td>
                                <td class="border border-slate-300 p-4">Per airline policy</td>
                                <td class="border border-slate-300 p-4">Per hotel policy</td>
                                <td class="border border-slate-300 p-4">Non-refundable</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-300 p-4">7+ days</td>
                                <td class="border border-slate-300 p-4">Per airline policy</td>
                                <td class="border border-slate-300 p-4">Per hotel policy</td>
                                <td class="border border-slate-300 p-4">Non-refundable</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-blue-700 text-sm">*Subject to airline and hotel policies. Some promotional fares may be non-refundable.</p>
                </div>
            </section>

            <!-- Liability -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="shield-alert" class="w-6 h-6 mr-3 text-blue-600"></i>
                    6. Liability & Disclaimers
                </h2>
                
                <div class="space-y-6">
                    <div class="p-6 bg-red-50 rounded-xl border border-red-200">
                        <h3 class="font-semibold text-red-800 mb-3">Limitation of Liability</h3>
                        <ul class="space-y-2 text-red-700">
                            <li>• We act as an intermediary between you and travel suppliers</li>
                            <li>• Airlines and hotels are responsible for their services</li>
                            <li>• We are not liable for flight delays, cancellations, or schedule changes</li>
                            <li>• Maximum liability limited to the amount paid for our services</li>
                        </ul>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-xl">
                        <h3 class="font-semibold text-slate-800 mb-3">User Responsibilities</h3>
                        <ul class="space-y-2 text-slate-700">
                            <li>• Verify all booking details before payment</li>
                            <li>• Ensure travel documents are valid and current</li>
                            <li>• Check visa requirements for your destination</li>
                            <li>• Arrive at airports with adequate time for check-in</li>
                            <li>• Comply with airline and hotel policies</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Data Protection -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="shield-check" class="w-6 h-6 mr-3 text-blue-600"></i>
                    7. Data Protection & Privacy
                </h2>
                
                <div class="p-6 bg-blue-50 rounded-xl border border-blue-200">
                    <h3 class="font-semibold text-blue-800 mb-3">GDPR Compliance</h3>
                    <p class="text-blue-700 mb-4">We are committed to protecting your personal data in accordance with GDPR regulations.</p>
                    <ul class="space-y-2 text-blue-700">
                        <li>• Data collected only for booking and service purposes</li>
                        <li>• Information shared only with necessary travel suppliers</li>
                        <li>• You have the right to access, correct, or delete your data</li>
                        <li>• Data retention periods as outlined in our Privacy Policy</li>
                    </ul>
                    <div class="mt-4">
                        <a href="{{ route('support.privacy-policy') }}" class="text-blue-600 hover:text-blue-800 underline">View our full Privacy Policy</a>
                    </div>
                </div>
            </section>

            <!-- Intellectual Property -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="copyright" class="w-6 h-6 mr-3 text-blue-600"></i>
                    8. Intellectual Property
                </h2>
                
                <div class="p-6 bg-slate-50 rounded-xl">
                    <h3 class="font-semibold text-slate-800 mb-3">Copyright & Trademarks</h3>
                    <ul class="space-y-2 text-slate-700">
                        <li>• All website content is protected by copyright</li>
                        <li>• Ziara logo and branding are registered trademarks</li>
                        <li>• Users may not reproduce content without permission</li>
                        <li>• Unauthorized use may result in legal action</li>
                    </ul>
                </div>
            </section>

            <!-- Dispute Resolution -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="scales" class="w-6 h-6 mr-3 text-blue-600"></i>
                    9. Dispute Resolution
                </h2>
                
                <div class="space-y-6">
                    <div class="p-6 bg-green-50 rounded-xl border border-green-200">
                        <h3 class="font-semibold text-green-800 mb-3">Resolution Process</h3>
                        <ol class="space-y-2 text-green-700">
                            <li>1. Contact our customer service team</li>
                            <li>2. Formal complaint procedure if unresolved</li>
                            <li>3. Independent mediation if required</li>
                            <li>4. Legal proceedings as last resort</li>
                        </ol>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-xl">
                        <h3 class="font-semibold text-slate-800 mb-3">Governing Law</h3>
                        <p class="text-slate-700">These terms are governed by the laws of Kenya. Any disputes will be resolved in Kenyan courts.</p>
                    </div>
                </div>
            </section>

            <!-- Contact Information -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="phone" class="w-6 h-6 mr-3 text-blue-600"></i>
                    10. Contact Information
                </h2>
                
                <div class="p-6 bg-slate-50 rounded-xl">
                    <h3 class="font-semibold text-slate-800 mb-3">Get in Touch</h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-slate-700 mb-2">Customer Service</h4>
                            <p class="text-slate-600 mb-1">Email: support@Ziara.com</p>
                            <p class="text-slate-600 mb-1">Phone: +254 700 123 456</p>
                            <p class="text-slate-600">Hours: 24/7 Support</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-slate-700 mb-2">Legal Inquiries</h4>
                            <p class="text-slate-600 mb-1">Email: legal@Ziara.com</p>
                            <p class="text-slate-600 mb-1">Address: Nairobi, Kenya</p>
                            <p class="text-slate-600">Business Hours: 9 AM - 6 PM EAT</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Agreement Footer -->
        <div class="mt-12 p-6 bg-blue-50 rounded-xl border border-blue-200">
            <h3 class="font-semibold text-blue-800 mb-3">Agreement Acceptance</h3>
            <p class="text-blue-700 mb-4">By using our services, you acknowledge that you have read, understood, and agree to be bound by these terms of service.</p>
            <p class="text-blue-700 text-sm">Last updated: {{ date('F j, Y') }}</p>
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