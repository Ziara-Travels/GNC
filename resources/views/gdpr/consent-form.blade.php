@extends('layouts.app')

@section('title', 'Privacy Consent Settings - Ziara Travel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Privacy Consent Settings</h1>
            
            <div class="mb-6">
                <p class="text-gray-600">
                    We respect your privacy and want to be transparent about how we collect, use, and share your information. 
                    Please review the following consent options and choose what you're comfortable with.
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('gdpr.update-consent') }}" class="space-y-6">
                @csrf

                <!-- Essential Cookies -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="essential" name="essential" type="checkbox" 
                                   checked disabled
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded bg-gray-100">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="essential" class="font-medium text-gray-700">Essential Cookies</label>
                            <p class="text-gray-500">
                                These cookies are necessary for the website to function and cannot be switched off. 
                                They are usually only set in response to actions made by you.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Functional Cookies -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="functional" name="functional" type="checkbox" 
                                   {{ isset($consents['functional']) && $consents['functional']->consent_given ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="functional" class="font-medium text-gray-700">Functional Cookies</label>
                            <p class="text-gray-500">
                                These cookies enable the website to provide enhanced functionality and personalization. 
                                They may be set by us or by third party providers.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Analytics Cookies -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="analytics" name="analytics" type="checkbox" 
                                   {{ isset($consents['analytics']) && $consents['analytics']->consent_given ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="analytics" class="font-medium text-gray-700">Analytics Cookies</label>
                            <p class="text-gray-500">
                                These cookies allow us to count visits and traffic sources so we can measure and 
                                improve the performance of our site using tools like Google Analytics.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Marketing Communications -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="marketing" name="marketing" type="checkbox" 
                                   {{ isset($consents['marketing']) && $consents['marketing']->consent_given ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="marketing" class="font-medium text-gray-700">Marketing Communications</label>
                            <p class="text-gray-500">
                                Allow us to send you promotional emails, newsletters, and special offers about our 
                                travel deals and services.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Data Processing -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="data_processing" name="data_processing" type="checkbox" 
                                   {{ isset($consents['data_processing']) && $consents['data_processing']->consent_given ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="data_processing" class="font-medium text-gray-700">Data Processing</label>
                            <p class="text-gray-500">
                                Allow us to process your personal data for improving our services, customer support, 
                                and personalized travel recommendations.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Third Party Sharing -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="third_party" name="third_party" type="checkbox" 
                                   {{ isset($consents['third_party']) && $consents['third_party']->consent_given ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="third_party" class="font-medium text-gray-700">Third Party Sharing</label>
                            <p class="text-gray-500">
                                Allow us to share your data with trusted travel partners and service providers 
                                to enhance your travel experience.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                        Save Preferences
                    </button>
                    
                    <div class="space-x-4">
                        <a href="{{ route('gdpr.export-data') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                            Export My Data
                        </a>
                        
                        <button type="button" 
                                onclick="openDeleteModal()"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                            Delete Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-bold text-gray-900">Delete Account</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    This action cannot be undone. This will permanently delete your account and remove your data from our servers.
                </p>
                
                <form method="POST" action="{{ route('gdpr.delete-account') }}" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Enter your password to confirm:
                        </label>
                        <input type="password" name="password" id="password" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirmation" class="block text-sm font-medium text-gray-700">
                            Type "DELETE_MY_ACCOUNT" to confirm:
                        </label>
                        <input type="text" name="confirmation" id="confirmation" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="items-center px-4 py-3">
                        <button type="submit" 
                                class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Delete Account
                        </button>
                        <button type="button" onclick="closeDeleteModal()"
                                class="mt-3 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}
</script>
@endsection
