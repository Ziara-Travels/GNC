{{-- filepath: resources/views/auth/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Verify Email - {{ config('app.name') }}</title>
    
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
        .envelope-animation {
            animation: pulse 2s infinite ease-in-out;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="bg-primary-50 p-4 rounded-full envelope-animation">
                    <i data-lucide="mail-check" class="w-16 h-16 text-primary-500"></i>
                </div>
            </div>
            
            <h2 class="mt-6 text-center text-3xl font-extrabold text-secondary-800">
                Verify your email address
            </h2>
            
            <p class="mt-4 text-center text-sm text-gray-600 max-w-md mx-auto leading-relaxed">
                Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn't receive the email, we'll gladly send you another.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-6 shadow-md rounded-xl border border-gray-100">
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-2"></i>
                            <p class="text-sm text-green-700 font-medium">
                                A new verification link has been sent to the email address you provided during registration.
                            </p>
                        </div>
                    </div>
                @endif

                <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 bg-primary-500 hover:bg-primary-600 text-white font-medium rounded-lg transition duration-300 shadow-sm">
                            <i data-lucide="refresh-cw" class="w-5 h-5 mr-2"></i>
                            <span>Resend Verification Email</span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 bg-white border border-gray-300 hover:bg-gray-50 text-secondary-600 font-medium rounded-lg transition duration-300 shadow-sm">
                            <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    Need help? <a href="#" class="text-primary-600 hover:text-primary-700 font-medium">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>