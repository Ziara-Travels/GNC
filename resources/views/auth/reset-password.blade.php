{{-- filepath: resources/views/auth/reset-password.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Reset Password - {{ config('app.name') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
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
        .lock-animation {
            animation: unlock 4s infinite ease-in-out;
        }
        @keyframes unlock {
            0%, 50% {
                transform: rotate(0deg);
            }
            55% {
                transform: rotate(-5deg);
            }
            60% {
                transform: rotate(5deg);
            }
            65% {
                transform: rotate(-5deg);
            }
            70% {
                transform: rotate(5deg);
            }
            75% {
                transform: rotate(-5deg);
            }
            80%, 100% {
                transform: rotate(0deg);
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="flex min-h-screen">
        <!-- Left Side - Image and Info -->
        <div class="hidden lg:flex lg:w-1/2 bg-secondary-600 relative">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-secondary-800/90 to-secondary-600/80"></div>
            
            <div class="relative z-10 w-full flex flex-col items-center justify-center p-12 text-center">
                <div class="lock-animation">
                    <i data-lucide="unlock" class="w-24 h-24 text-primary-400"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4 mt-6">Reset Your Password</h1>
                <p class="text-lg text-white/80 max-w-md">
                    Create a strong new password to secure your account
                </p>
                
                <div class="mt-12 bg-white/10 backdrop-blur-sm rounded-xl p-6 w-full max-w-md">
                    <h3 class="text-white font-semibold mb-4">Password Tips:</h3>
                    <div class="flex items-start space-x-3 mb-3">
                        <i data-lucide="check" class="w-5 h-5 text-primary-400 mt-0.5"></i>
                        <p class="text-white text-left text-sm">
                            Use at least 8 characters
                        </p>
                    </div>
                    <div class="flex items-start space-x-3 mb-3">
                        <i data-lucide="check" class="w-5 h-5 text-primary-400 mt-0.5"></i>
                        <p class="text-white text-left text-sm">
                            Include uppercase and lowercase letters
                        </p>
                    </div>
                    <div class="flex items-start space-x-3 mb-3">
                        <i data-lucide="check" class="w-5 h-5 text-primary-400 mt-0.5"></i>
                        <p class="text-white text-left text-sm">
                            Include at least one number
                        </p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i data-lucide="check" class="w-5 h-5 text-primary-400 mt-0.5"></i>
                        <p class="text-white text-left text-sm">
                            Include at least one special character
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Background Pattern/Decoration -->
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                    <path d="M0 4a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v24a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2 2v22h28V6H2z" fill="currentColor"/>
                </svg>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8 lg:hidden">
                    <div class="bg-primary-50 p-4 rounded-full lock-animation">
                        <i data-lucide="unlock" class="w-16 h-16 text-primary-500"></i>
                    </div>
                </div>
            
                <h1 class="text-3xl font-bold text-center text-secondary-800 mb-2">Create New Password</h1>
                <p class="text-center text-gray-500 mb-8">
                    Please set a new password for your account
                </p>
                
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                        <div class="flex items-center mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500 mr-2"></i>
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                        </div>
                        <ul class="ml-5 list-disc text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('password.update') }}" class="bg-white p-8 rounded-xl shadow-md border border-gray-100">
                    @csrf
                    
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <!-- Email Input -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" 
                                   class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   placeholder="your@email.com" required autofocus readonly>
                        </div>
                    </div>
                    
                    <!-- Password Input -->
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
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
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="check-circle" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition duration-300 shadow-sm hover:shadow-md">
                        <div class="flex items-center justify-center">
                            <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                            <span>Reset Password</span>
                        </div>
                    </button>
                </form>
                
                <!-- Back to Login Link -->
                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-700 inline-flex items-center font-medium">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                        <span>Back to Login</span>
                    </a>
                </div>
                
                <!-- Help and Support -->
                <div class="mt-12 text-center">
                    <div class="text-sm text-gray-500">
                        Need help? <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>