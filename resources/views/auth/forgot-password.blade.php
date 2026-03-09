{{-- filepath: resources/views/auth/forgot-password.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Forgot Password - {{ config('app.name') }}</title>
    
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
        .key-animation {
            animation: float 3s infinite ease-in-out;
        }
        @keyframes float {
            0% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-10px) rotate(5deg);
            }
            100% {
                transform: translateY(0px) rotate(0deg);
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
                <div class="key-animation">
                    <i data-lucide="key" class="w-24 h-24 text-primary-400"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4 mt-6">Password Recovery</h1>
                <p class="text-lg text-white/80 max-w-md">
                    Don't worry! It happens to the best of us. We'll help you get back into your account in no time.
                </p>
                
                <div class="mt-12 bg-white/10 backdrop-blur-sm rounded-xl p-6 w-full max-w-md">
                    <div class="flex items-start space-x-4 mb-4">
                        <i data-lucide="info" class="w-6 h-6 text-primary-400 mt-0.5"></i>
                        <p class="text-white text-left text-sm">
                            We'll send a secure link to your email that will allow you to create a new password. The link will expire after 60 minutes.
                        </p>
                    </div>
                    <div class="flex items-start space-x-4 mb-4">
                        <i data-lucide="shield" class="w-6 h-6 text-primary-400 mt-0.5"></i>
                        <p class="text-white text-left text-sm">
                            For security reasons, we don't store your password. You'll need to create a new one.
                        </p>
                    </div>
                    <div class="flex items-start space-x-4">
                        <i data-lucide="help-circle" class="w-6 h-6 text-primary-400 mt-0.5"></i>
                        <p class="text-white text-left text-sm">
                            Need more help? Contact our support team and we'll assist you right away.
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
                    <div class="bg-primary-50 p-4 rounded-full key-animation">
                        <i data-lucide="key" class="w-16 h-16 text-primary-500"></i>
                    </div>
                </div>
            
                <h1 class="text-3xl font-bold text-center text-secondary-800 mb-2">Forgot your password?</h1>
                <p class="text-center text-gray-500 mb-8">
                    Enter your email address and we'll send you a link to reset your password
                </p>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                            <p class="text-sm text-green-700 font-medium">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                @endif
                
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
                
                <form method="POST" action="{{ route('password.email') }}" class="bg-white p-8 rounded-xl shadow-md border border-gray-100">
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   placeholder="your@email.com" required autofocus>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition duration-300 shadow-sm hover:shadow-md">
                        <div class="flex items-center justify-center">
                            <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                            <span>Send Password Reset Link</span>
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