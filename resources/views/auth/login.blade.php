<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

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
        .airplane-animation {
            animation: fly 15s infinite linear;
        }
        @keyframes fly {
            0% {
                transform: translateX(-50px) translateY(20px);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateX(calc(100% + 50px)) translateY(-20px);
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="flex min-h-screen">
        <!-- Left Side - Image and Welcome Message -->
        <div class="hidden lg:flex lg:w-1/2 bg-secondary-600 relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-40"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-secondary-800/90 to-secondary-600/70"></div>
            
            <!-- Flying Airplane Animation -->
            <div class="absolute airplane-animation">
                <i data-lucide="plane" class="w-20 h-20 text-white" style="filter: drop-shadow(0 0 10px rgba(255,255,255,0.5));"></i>
            </div>
            
            <div class="relative z-10 w-full flex flex-col items-center justify-center p-12 text-center">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white/10 backdrop-blur-sm">
                        <i data-lucide="cloud" class="w-12 h-12 text-primary-400"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Welcome Back!</h1>
                <p class="text-lg text-white/80 max-w-md">
                    Sign in to access your account and continue your journey with us
                </p>
                
                <div class="mt-16 relative">
                    <div class="absolute -top-6 -left-6">
                        <i data-lucide="map-pin" class="w-8 h-8 text-primary-400"></i>
                    </div>
                    <div class="absolute -bottom-6 -right-6">
                        <i data-lucide="compass" class="w-8 h-8 text-primary-400"></i>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-xl">
                        <h3 class="text-xl font-semibold text-white mb-4">Why choose Ziara App?</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex-shrink-0">
                                <i data-lucide="check-circle" class="w-6 h-6 text-primary-400"></i>
                            </div>
                            <p class="text-white text-sm">Best flight deals across East Africa</p>
                        </div>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="flex-shrink-0">
                                <i data-lucide="check-circle" class="w-6 h-6 text-primary-400"></i>
                            </div>
                            <p class="text-white text-sm">Easy booking and flight management</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <i data-lucide="check-circle" class="w-6 h-6 text-primary-400"></i>
                            </div>
                            <p class="text-white text-sm">24/7 customer support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8">
                    <div class="bg-secondary-50 p-3 rounded-2xl">
                        <i data-lucide="plane" class="w-8 h-8 text-primary-500"></i>
                    </div>
                </div>
                
                <h1 class="text-3xl font-bold text-center text-secondary-800 mb-2">Sign in to your account</h1>
                <p class="text-center text-gray-500 mb-8">Welcome back! Please enter your details</p>
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif
                
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Social Login Buttons -->
                    <div class="flex flex-col space-y-3 mb-6">
                        <a href="{{ route('social.login', 'google') }}" 
                           class="flex items-center justify-center w-full py-3 px-4 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg transition duration-300 shadow-sm">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Sign in with Google
                        </a>
                        
                        <a href="{{ route('social.login', 'facebook') }}" 
                           class="flex items-center justify-center w-full py-3 px-4 bg-[#1877f2] hover:bg-[#166fe5] text-white rounded-lg transition duration-300 shadow-sm">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9.198 21.5h4v-8.01h3.604l.396-3.98h-4V7.5a1 1 0 0 1 1-1h3v-4h-3a5 5 0 0 0-5 5v2.01h-2l-.396 3.98h2.396v8.01Z" />
                            </svg>
                            Sign in with Facebook
                        </a>
                        
                        <a href="{{ route('social.login', 'x') }}" 
                           class="flex items-center justify-center w-full py-3 px-4 bg-[#14171A] hover:bg-[#272C30] text-white rounded-lg transition duration-300 shadow-sm">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/>
                            </svg>
                            Sign in with X (Twitter)
                        </a>
                    </div>
                    
                    <div class="relative flex items-center justify-center text-sm my-6">
                        <span class="px-2 bg-white text-gray-500">or continue with email</span>
                        <div class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-gray-300"></div>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-4">
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

                    <!-- Password Input -->
                    <div class="mb-4">
                        <div class="flex justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" 
                                   class="w-full py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center mb-6">
                        <input type="checkbox" id="remember_me" name="remember" class="w-4 h-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <div class="flex items-center justify-center">
                            <i data-lucide="log-in" class="w-5 h-5 mr-2"></i>
                            <span>Sign In</span>
                        </div>
                    </button>
                    
                    <!-- Register Link -->
                    <p class="text-center mt-6 text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">Create account</a>
                    </p>
                </form>
                
                <!-- Help and Support -->
                <div class="mt-12 flex justify-center">
                    <div class="flex items-center space-x-3 text-sm text-gray-500">
                        <a href="#" class="hover:text-primary-600 flex items-center">
                            <i data-lucide="help-circle" class="w-4 h-4 mr-1"></i>
                            <span>Help</span>
                        </a>
                        <span>•</span>
                        <a href="#" class="hover:text-primary-600 flex items-center">
                            <i data-lucide="message-circle" class="w-4 h-4 mr-1"></i>
                            <span>Contact Us</span>
                        </a>
                        <span>•</span>
                        <a href="#" class="hover:text-primary-600 flex items-center">
                            <i data-lucide="shield" class="w-4 h-4 mr-1"></i>
                            <span>Privacy</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


    <script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route('login') }}"]');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        // Remove previous error highlights
        form.querySelectorAll('input').forEach(input => {
            input.classList.remove('border-red-500', 'ring-red-300');
        });

        let valid = true;
        let email = form.querySelector('input[name="email"]');
        let password = form.querySelector('input[name="password"]');
        let errors = [];

        // Email validation
        if (!email.value.trim()) {
            valid = false;
            errors.push('Email is required.');
            email.classList.add('border-red-500', 'ring-red-300');
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
            valid = false;
            errors.push('Enter a valid email address.');
            email.classList.add('border-red-500', 'ring-red-300');
        }

        // Password validation
        if (!password.value.trim()) {
            valid = false;
            errors.push('Password is required.');
            password.classList.add('border-red-500', 'ring-red-300');
        }

        if (!valid) {
            e.preventDefault();
            Toastify({
                text: errors.join('\n'),
                duration: 4000,
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
            text: "Signing in...",
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
