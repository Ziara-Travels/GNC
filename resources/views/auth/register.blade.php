<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - {{ config('app.name') }}</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">


    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> --}}
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Flags for Countries -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff9e6',
                            100: '#ffefc0',
                            200: '#ffe199',
                            300: '#ffd066',
                            400: '#ffc233',
                            500: '#e3a300', // Primary color
                            600: '#cc9200',
                            700: '#a67700',
                            800: '#805c00',
                            900: '#664900',
                        },
                        secondary: {
                            50: '#f5f5f7',
                            100: '#ebebef',
                            200: '#d1d2dc',
                            300: '#a6a9be',
                            400: '#6d7192',
                            500: '#4e5169',
                            600: '#2b2e3d', // Secondary color
                            700: '#242634',
                            800: '#1d1e2a',
                            900: '#17171f',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="flex min-h-screen">
        <!-- Left Side - Image and Welcome Message -->
        <div class="hidden lg:flex lg:w-1/2 bg-secondary-600 relative">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-secondary-800/90 to-secondary-600/80"></div>

            <div class="relative z-10 w-full flex flex-col items-center justify-center p-12 text-center">
                <div class="animate-float">
                    <i data-lucide="plane-takeoff" class="w-24 h-24 text-white mb-6"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Welcome to Ziara App</h1>
                <p class="text-lg text-white/80 max-w-md">
                    Join our community of travelers and experience seamless flight bookings across East Africa
                </p>

                <div class="mt-12 bg-white/10 backdrop-blur-sm rounded-xl p-6 w-full max-w-md">
                    <div class="flex items-center space-x-4 mb-4">
                        <i data-lucide="check-circle" class="w-6 h-6 text-primary-400"></i>
                        <p class="text-white">Access exclusive flight deals</p>
                    </div>
                    <div class="flex items-center space-x-4 mb-4">
                        <i data-lucide="check-circle" class="w-6 h-6 text-primary-400"></i>
                        <p class="text-white">Track your travel history</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <i data-lucide="check-circle" class="w-6 h-6 text-primary-400"></i>
                        <p class="text-white">Get personalized recommendations</p>
                    </div>
                </div>
            </div>

            <!-- Background Pattern/Decoration -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <path d="M0 4a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v24a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2 2v22h28V6H2z"
                        fill="none" stroke="currentColor" stroke-width="0.5"></path>
                    <path d="M8 4v24M16 4v24M24 4v24M0 12h32M0 20h32"
                        fill="none" stroke="currentColor" stroke-width="0.5"></path>
                </svg>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <!-- Logo and Title -->
                <div class="flex justify-center mb-6">
                    <div class="bg-secondary-50 p-3 rounded-2xl">
                        <i data-lucide="plane-takeoff" class="w-8 h-8 text-primary-500"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-center text-secondary-800 mb-2">Create Your Account</h1>
                <p class="text-center text-gray-500 mb-8">Join Ziara for the best flight booking experience</p>

                <!-- Validation Errors -->
                @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="font-medium">{{ __('Whoops! Something went wrong.') }}</div>

                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Enter your full name" required autofocus>
                        </div>
                        @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="your@email.com" required>
                        </div>
                        @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="mb-4">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <div class="relative">
                            <select id="country" name="country"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent appearance-none"
                                required>
                                <option value="">Select Country</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <div id="selected-flag" class="w-5 h-5 rounded overflow-hidden flex items-center justify-center">
                                    <div class="w-5 h-5 bg-gray-200"></div>
                                </div>
                            </div>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                            </div>
                        </div>
                        @error('country')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone
                            Number</label>
                        <div class="flex">
                            <div class="relative">
                                <input type="text" id="phone_country_code" name="phone_country_code"
                                    value="{{ old('phone_country_code', '+254') }}" readonly
                                    class="w-[85px] px-3 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-gray-50 text-center">
                            </div>
                            <input type="text" id="phone_number" name="phone_number"
                                value="{{ old('phone_number') }}"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Enter phone number without country code" required>
                        </div>
                        @error('phone_number')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        @error('phone_country_code')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password"
                                class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="check-circle" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Referral Code Input (Optional) -->
                    <div class="mb-6">
                        <label for="referral_code" class="block text-sm font-medium text-gray-700 mb-1">
                            Referral Code
                            <span class="text-xs text-gray-500 font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="gift" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="text" id="referral_code" name="referral_code"
                                value="{{ old('referral_code') }}"
                                class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('referral_code') border-red-500 @enderror"
                                placeholder="Enter referral code">
                        </div>
                        @error('referral_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                            Have a referral code? Enter it here to get special discounts on your bookings.
                        </p>
                    </div>

                    <!-- Register Button -->
                    <button type="submit"
                        class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <div class="flex items-center justify-center">
                            <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                            <span>Create Account</span>
                        </div>
                    </button>
                    
                    <!-- Social Login Separator -->
                    <div class="flex items-center my-6">
                        <div class="flex-1 border-t border-gray-300"></div>
                        <div class="px-4 text-sm text-gray-500 font-medium">Or sign up with</div>
                        <div class="flex-1 border-t border-gray-300"></div>
                    </div>
                    
                    <!-- Social Login Buttons -->
                    <div class="grid grid-cols-3 gap-3">
                        <!-- Google Login -->
                        <a href="{{ route('social.login', 'google') }}" class="flex items-center justify-center py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.8055 10.0415H21V10H12V14H17.6515C16.827 16.3285 14.6115 18 12 18C8.6865 18 6 15.3135 6 12C6 8.6865 8.6865 6 12 6C13.5295 6 14.921 6.577 15.9805 7.5195L18.809 4.691C17.023 3.0265 14.634 2 12 2C6.4775 2 2 6.4775 2 12C2 17.5225 6.4775 22 12 22C17.5225 22 22 17.5225 22 12C22 11.3295 21.931 10.675 21.8055 10.0415Z" fill="#FFC107"/>
                                <path d="M3.15302 7.3455L6.43852 9.755C7.32752 7.554 9.48052 6 12 6C13.5295 6 14.921 6.577 15.9805 7.5195L18.809 4.691C17.023 3.0265 14.634 2 12 2C8.15902 2 4.82802 4.1685 3.15302 7.3455Z" fill="#FF3D00"/>
                                <path d="M12 22C14.583 22 16.93 21.0115 18.7045 19.404L15.6095 16.785C14.5718 17.5742 13.3038 18.001 12 18C9.39897 18 7.19047 16.3415 6.35847 14.027L3.09747 16.5395C4.75247 19.778 8.11347 22 12 22Z" fill="#4CAF50"/>
                                <path d="M21.8055 10.0415H21V10H12V14H17.6515C17.2571 15.1082 16.5467 16.0766 15.608 16.7855L15.6095 16.7845L18.7045 19.4035C18.4855 19.6025 22 17 22 12C22 11.3295 21.931 10.675 21.8055 10.0415Z" fill="#1976D2"/>
                            </svg>
                        </a>
                        
                        <!-- Facebook Login -->
                        <a href="{{ route('social.login', 'facebook') }}" class="flex items-center justify-center py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                            <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        
                        <!-- X (Twitter) Login -->
                        <a href="{{ route('social.login', 'x') }}" class="flex items-center justify-center py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Login Link -->
                    <p class="text-center mt-6 text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}"
                            class="text-primary-600 hover:text-primary-700 font-medium">Sign In</a>
                    </p>
                </form>

                <!-- Terms and Privacy -->
                <p class="text-center text-xs text-gray-500 mt-8">
                    By creating an account, you agree to our
                    <a href="#" class="text-primary-600 hover:underline">Terms of Service</a> and
                    <a href="#" class="text-primary-600 hover:underline">Privacy Policy</a>
                </p>
            </div>
        </div>
    </div>

<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{{ env('FACEBOOK_CLIENT_ID') }}', 
      cookie     : true,
      xfbml      : true,
      version    : 'v19.0'
    });
    FB.AppEvents.logPageView();   
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  function statusChangeCallback(response) {
    if (response.status === 'connected') {
      window.location.href = "{{ route('social.login', 'facebook') }}";
    } else {
      Toastify({
        text: "Facebook login failed or cancelled.",
        duration: 4000,
        gravity: "top",
        position: "right",
        backgroundColor: "#ef4444",
        stopOnFocus: true,
        close: true
      }).showToast();
    }
  }
</script>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


    <script>
        const countrySelect = document.getElementById('country');
        const phoneCountryCodeInp = document.getElementById('phone_country_code');
        const selectedFlag = document.getElementById('selected-flag');
        
        // Store all country data 
        let countriesData = [];
        
        // Load all countries on page load
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                // Fetch all countries with needed fields
                const response = await fetch('https://restcountries.com/v3.1/all?fields=name,flags,idd,cca2');
                if (!response.ok) throw new Error('Failed to fetch countries');
                
                countriesData = await response.json();
                
                // Sort countries alphabetically by name
                countriesData.sort((a, b) => {
                    const nameA = a.name.common.toUpperCase();
                    const nameB = b.name.common.toUpperCase();
                    return nameA < nameB ? -1 : nameA > nameB ? 1 : 0;
                });
                
                // Populate the dropdown
                countriesData.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.name.common;
                    option.dataset.cca2 = country.cca2;
                    option.dataset.flag = country.flags.svg;
                    
                    // Create dial code from IDD data
                    const dialCode = country.idd?.root && country.idd?.suffixes?.[0] ? 
                        `${country.idd.root}${country.idd.suffixes[0]}` : '';
                    option.dataset.dialCode = dialCode;
                    
                    option.textContent = country.name.common;
                    countrySelect.appendChild(option);
                });
                
                // Set selected value if there's old input
                const oldCountry = '{{ old('country') }}';
                if (oldCountry) {
                    countrySelect.value = oldCountry;
                    updateFlagAndDialCode();
                }
                
                // Set Kenya as default if no old value and empty selection
                if (!oldCountry && !countrySelect.value) {
                    const kenyaOption = Array.from(countrySelect.options).find(option => 
                        option.value === 'Kenya' || option.value === 'kenya'
                    );
                    
                    if (kenyaOption) {
                        countrySelect.value = kenyaOption.value;
                        updateFlagAndDialCode();
                    }
                }
                
            } catch (error) {
                console.error('Error loading countries:', error);
                // Fallback to basic countries if API fails
                addFallbackCountries();
            }
        });
        
        // Function to add fallback countries if API fails
        function addFallbackCountries() {
            const fallbackCountries = [
                { name: "Kenya", code: "KE", dialCode: "+254" },
                { name: "Uganda", code: "UG", dialCode: "+256" },
                { name: "Tanzania", code: "TZ", dialCode: "+255" },
                { name: "United States", code: "US", dialCode: "+1" },
                { name: "United Kingdom", code: "GB", dialCode: "+44" }
            ];
            
            fallbackCountries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.name;
                option.dataset.dialCode = country.dialCode;
                option.textContent = country.name;
                countrySelect.appendChild(option);
            });
            
            // Set default
            countrySelect.value = "Kenya";
            phoneCountryCodeInp.value = "+254";
        }
        
        // Update flag and dial code when country changes
        countrySelect.addEventListener('change', updateFlagAndDialCode);
        
        function updateFlagAndDialCode() {
            const selectedOption = countrySelect.options[countrySelect.selectedIndex];
            const countryName = countrySelect.value;
            
            if (!countryName) {
                // Reset flag and dial code if nothing selected
                selectedFlag.innerHTML = '<div class="w-5 h-5 bg-gray-200"></div>';
                phoneCountryCodeInp.value = '';
                return;
            }
            
            // Get dial code from the dataset
            const dialCode = selectedOption.dataset.dialCode;
            if (dialCode) {
                phoneCountryCodeInp.value = dialCode;
            }
            
            // Update the flag
            if (selectedOption.dataset.flag) {
                selectedFlag.innerHTML = `<img src="${selectedOption.dataset.flag}" class="w-5 h-5 rounded object-cover">`;
            } else {
                // Fallback - show country code instead
                const countryCode = selectedOption.dataset.cca2;
                if (countryCode) {
                    selectedFlag.innerHTML = `
                        <div class="w-5 h-5 bg-gray-100 flex items-center justify-center text-xs font-bold">
                            ${countryCode}
                        </div>`;
                } else {
                    selectedFlag.innerHTML = '<div class="w-5 h-5 bg-gray-200"></div>';
                }
            }
        }
    </script>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('register') }}"]');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        // Remove previous error highlights
        form.querySelectorAll('input, select').forEach(input => {
            input.classList.remove('border-red-500', 'ring-red-300');
        });

        let valid = true;
        let errors = [];

        // Name validation
        let name = form.querySelector('input[name="name"]');
        if (!name.value.trim()) {
            valid = false;
            errors.push('Full Name is required.');
            name.classList.add('border-red-500', 'ring-red-300');
        }

        // Email validation
        let email = form.querySelector('input[name="email"]');
        if (!email.value.trim()) {
            valid = false;
            errors.push('Email is required.');
            email.classList.add('border-red-500', 'ring-red-300');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
            valid = false;
            errors.push('Enter a valid email address.');
            email.classList.add('border-red-500', 'ring-red-300');
        }

        // Country validation
        let country = form.querySelector('select[name="country"]');
        if (!country.value.trim()) {
            valid = false;
            errors.push('Country is required.');
            country.classList.add('border-red-500', 'ring-red-300');
        }

        // Phone number validation
        let phone = form.querySelector('input[name="phone_number"]');
        if (!phone.value.trim()) {
            valid = false;
            errors.push('Phone number is required.');
            phone.classList.add('border-red-500', 'ring-red-300');
        } else if (!/^[0-9]{6,15}$/.test(phone.value.trim())) {
            valid = false;
            errors.push('Enter a valid phone number (digits only, 6-15 characters).');
            phone.classList.add('border-red-500', 'ring-red-300');
        }

        // Password validation
        let password = form.querySelector('input[name="password"]');
        if (!password.value.trim()) {
            valid = false;
            errors.push('Password is required.');
            password.classList.add('border-red-500', 'ring-red-300');
        } else if (password.value.length < 8) {
            valid = false;
            errors.push('Password must be at least 8 characters.');
            password.classList.add('border-red-500', 'ring-red-300');
        }

        // Confirm password validation
        let password_confirmation = form.querySelector('input[name="password_confirmation"]');
        if (!password_confirmation.value.trim()) {
            valid = false;
            errors.push('Confirm your password.');
            password_confirmation.classList.add('border-red-500', 'ring-red-300');
        } else if (password.value !== password_confirmation.value) {
            valid = false;
            errors.push('Passwords do not match.');
            password_confirmation.classList.add('border-red-500', 'ring-red-300');
        }

        if (!valid) {
            e.preventDefault();
            Toastify({
                text: errors.join('\n'),
                duration: 5000,
                gravity: "top",
                position: "right",
                backgroundColor: "#ef4444",
                stopOnFocus: true,
                close: true,
                style: { whiteSpace: "pre-line" }
            }).showToast();
            return false;
        }

        // Show loading toast (optional)
        Toastify({
            text: "Creating your account...",
            duration: 2000,
            gravity: "top",
            position: "right",
            backgroundColor: "#e3a300",
            stopOnFocus: true,
            close: false
        }).showToast();
    });

    // Show server-side errors as toast (if any)
    @if ($errors->any())
        Toastify({
            text: `{!! implode('\n', $errors->all()) !!}`,
            duration: 5000,
            gravity: "top",
            position: "right",
            backgroundColor: "#ef4444",
            stopOnFocus: true,
            close: true,
            style: { whiteSpace: "pre-line" }
        }).showToast();
    @endif

    // Show session status as toast (if any)
    @if (session('status'))
        Toastify({
            text: "{{ session('status') }}",
            duration: 4000,
            gravity: "top",
            position: "right",
            backgroundColor: "#22c55e",
            stopOnFocus: true,
            close: true
        }).showToast();
    @endif
});
</script>
</body>

</html>
