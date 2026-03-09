<!-- filepath: c:\xampp\htdocs\GNC\resources\views\errors\404.blade.php -->
<!DOCTYPE html>
<html dir="ltr">
    <head>
        <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Page Not Found - 404 Error">

        <!-- Links Of CSS File -->
        <link rel="stylesheet" href="{{ asset('backend/css/remixicon.css') }}">
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Title -->
        <title>Page Not Found - 404 Error</title>

        <!-- Font Family -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

        <!-- Material Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        
        <style>
            @keyframes floatAnimation {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }
            
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }
            
            @keyframes rotateAndScale {
                0% { transform: rotate(0) scale(1); }
                50% { transform: rotate(180deg) scale(1.2); }
                100% { transform: rotate(360deg) scale(1); }
            }
            
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-30px); }
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                50% { transform: translateX(5px); }
                75% { transform: translateX(-5px); }
            }
            
            @keyframes glowPulse {
                0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
                50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8), 0 0 30px rgba(59, 130, 246, 0.6); }
            }
            
            @keyframes shimmer {
                0% { background-position: -200% 0; }
                100% { background-position: 200% 0; }
            }
            
            @keyframes floatAstronaut {
                0% { transform: translateY(0) rotate(0); }
                25% { transform: translateY(-15px) rotate(5deg); }
                50% { transform: translateY(0) rotate(0); }
                75% { transform: translateY(15px) rotate(-5deg); }
                100% { transform: translateY(0) rotate(0); }
            }
            
            @keyframes orbitPlanet {
                0% { transform: rotate(0deg) translateX(60px) rotate(0deg); }
                100% { transform: rotate(360deg) translateX(60px) rotate(-360deg); }
            }
            
            @keyframes blinkStars {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.5; transform: scale(0.8); }
            }
            
            .shimmer-bg {
                background: linear-gradient(90deg, 
                    rgba(255,255,255,0) 0%, 
                    rgba(255,255,255,0.2) 50%, 
                    rgba(255,255,255,0) 100%);
                background-size: 200% 100%;
                animation: shimmer 2s infinite;
            }
            
            .animate-float { animation: floatAnimation 3s ease-in-out infinite; }
            .animate-pulse-custom { animation: pulse 2s ease-in-out infinite; }
            .animate-rotate-scale { animation: rotateAndScale 6s linear infinite; }
            .animate-bounce-custom { animation: bounce 2s ease-in-out infinite; }
            .animate-shake { animation: shake 0.5s ease-in-out infinite; }
            .animate-glow { animation: glowPulse 2s infinite; }
            .animate-astronaut { animation: floatAstronaut 6s ease-in-out infinite; }
            .animate-orbit { animation: orbitPlanet 12s linear infinite; }
            .animate-blink { animation: blinkStars 3s ease-in-out infinite; }
            
            .btn-hover-effect {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }
            
            .btn-hover-effect:before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: all 0.6s;
            }
            
            .btn-hover-effect:hover:before {
                left: 100%;
            }
            
            /* Theme Toggle Styles */
            body.dark {
                background-color: #0a0e19;
                color: #fff;
            }
            
            body {
                transition: background-color 0.3s ease, color 0.3s ease;
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-800">
        <!-- Light/Dark Mode Button -->
        <button type="button" class="light-dark-toggle z-50 leading-none inline-block transition-all text-amber-500 absolute top-[20px] md:top-[25px] right-[20px] md:right-[25px]" id="light-dark-toggle">
            <i class="material-symbols-outlined text-2xl md:text-3xl animate-pulse-custom">
                light_mode
            </i>
        </button>

        <!-- 404 Space Theme -->
        <div class="min-h-screen w-full overflow-hidden relative py-12 flex items-center justify-center">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Stars -->
                <div class="absolute h-2 w-2 bg-white rounded-full top-1/4 left-1/4 animate-blink" style="animation-delay: 0.3s"></div>
                <div class="absolute h-1 w-1 bg-white rounded-full top-1/3 left-1/2 animate-blink" style="animation-delay: 0.6s"></div>
                <div class="absolute h-1.5 w-1.5 bg-white rounded-full top-2/3 left-1/5 animate-blink" style="animation-delay: 1.2s"></div>
                <div class="absolute h-2 w-2 bg-white rounded-full top-1/5 left-3/4 animate-blink" style="animation-delay: 0.8s"></div>
                <div class="absolute h-1 w-1 bg-white rounded-full top-1/2 left-4/5 animate-blink" style="animation-delay: 1s"></div>
                <div class="absolute h-1.5 w-1.5 bg-white rounded-full top-3/4 left-3/5 animate-blink" style="animation-delay: 1.5s"></div>
                <!-- More stars scattered around -->
                <div class="absolute h-3 w-3 bg-blue-400 opacity-30 blur-sm rounded-full top-1/4 right-1/4 animate-blink" style="animation-delay: 0.4s"></div>
                <div class="absolute h-2 w-2 bg-purple-300 opacity-40 blur-sm rounded-full bottom-1/3 right-1/2 animate-blink" style="animation-delay: 0.7s"></div>
            </div>

            <!-- Main Content -->
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-5xl mx-auto">
                    <!-- 404 Header with Animation -->
                    <div class="flex flex-col items-center mb-8">
                        <h1 class="text-9xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600 animate-pulse-custom tracking-tighter relative">
                            404
                            <span class="absolute -top-8 -right-8 text-5xl text-yellow-400 animate-bounce-custom" style="animation-delay: 0.3s">!</span>
                            <span class="absolute -bottom-4 -left-4 text-4xl text-red-400 animate-bounce-custom" style="animation-delay: 0.6s">?</span>
                        </h1>
                    </div>
                    
                    <!-- Animated Space Scene -->
                    <div class="relative h-64 md:h-80 mb-10 flex items-center justify-center">
                        <!-- Planet -->
                        <div class="absolute w-32 h-32 md:w-40 md:h-40 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 animate-pulse-custom z-10 animate-float" style="animation-duration: 8s;">
                            <!-- Crater 1 -->
                            <div class="absolute w-6 h-6 rounded-full bg-purple-800 opacity-30 top-1/4 left-1/4"></div>
                            <!-- Crater 2 -->
                            <div class="absolute w-4 h-4 rounded-full bg-purple-800 opacity-30 bottom-1/3 right-1/4"></div>
                            <!-- Crater 3 -->
                            <div class="absolute w-8 h-8 rounded-full bg-purple-800 opacity-30 bottom-1/4 right-1/3"></div>
                        </div>

                        <!-- Orbiting Satellite -->
                        <div class="absolute z-20 animate-orbit">
                            <div class="w-6 h-6 bg-gray-300 rounded-full shadow-lg flex items-center justify-center">
                                <div class="w-4 h-1 bg-gray-400 absolute"></div>
                            </div>
                        </div>

                        <!-- Floating Astronaut -->
                        <div class="absolute right-1/4 md:right-1/3 animate-astronaut z-30">
                            <div class="w-12 h-16 md:w-16 md:h-20 relative">
                                <!-- Astronaut Body -->
                                <div class="w-8 h-10 md:w-10 md:h-12 bg-white rounded-xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                                <!-- Helmet -->
                                <div class="w-6 h-6 md:w-8 md:h-8 bg-gradient-to-b from-blue-200 to-blue-400 rounded-full absolute top-0 left-1/2 transform -translate-x-1/2"></div>
                                <!-- Arms -->
                                <div class="w-8 h-1.5 bg-white absolute top-1/2 left-1/2 transform -translate-x-1/2 rotate-45"></div>
                                <div class="w-8 h-1.5 bg-white absolute top-1/2 left-1/2 transform -translate-x-1/2 -rotate-45"></div>
                                <!-- Legs -->
                                <div class="w-1.5 h-6 bg-white absolute bottom-0 left-1/3 transform"></div>
                                <div class="w-1.5 h-6 bg-white absolute bottom-0 right-1/3 transform"></div>
                                <!-- Connecting rope -->
                                <div class="w-0.5 h-16 md:h-20 bg-gray-300 absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full"></div>
                            </div>
                        </div>
                        
                        <!-- Flying UFO -->
                        <div class="absolute left-1/4 top-1/4 animate-float" style="animation-duration: 5s; animation-delay: 1s;">
                            <div class="w-16 h-6 bg-gradient-to-r from-green-300 to-blue-300 rounded-full relative">
                                <div class="absolute w-8 h-4 bg-gradient-to-b from-blue-200 to-blue-400 rounded-full top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></div>
                                <div class="absolute w-2 h-2 bg-yellow-400 rounded-full bottom-full left-1/2 transform -translate-x-1/2 animate-pulse-custom"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="text-center space-y-6 relative">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                            Houston, we have a problem!
                        </h2>
                        <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                            The page you're looking for has drifted into deep space or never existed at all. 
                            <span class="inline-block animate-shake">¯\_(ツ)_/¯</span>
                        </p>
                        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mt-8">
                            <a href="{{ route('home') }}" class="btn-hover-effect px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-medium rounded-lg transform transition-all hover:scale-105 animate-glow flex items-center gap-2">
                                <span class="material-symbols-outlined">home</span>
                                Back to Home
                            </a>
                            <a href="javascript:history.back()" class="btn-hover-effect px-6 py-3 border-2 border-blue-500 text-blue-500 dark:border-blue-400 dark:text-blue-400 font-medium rounded-lg transform transition-all hover:scale-105 flex items-center gap-2">
                                <span class="material-symbols-outlined">arrow_back</span>
                                Go Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Theme Toggle Functionality
            document.addEventListener('DOMContentLoaded', () => {
                const themeToggleBtn = document.getElementById('light-dark-toggle');
                const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
                
                // Check for saved theme preference or use the system preference
                const currentTheme = localStorage.getItem('theme') || 
                                     (prefersDarkScheme.matches ? 'dark' : 'light');
                
                // Set initial theme
                if (currentTheme === 'dark') {
                    document.body.classList.add('dark');
                    themeToggleBtn.querySelector('i').textContent = 'dark_mode';
                }
                
                // Toggle theme when button is clicked
                themeToggleBtn.addEventListener('click', () => {
                    const isDark = document.body.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                    themeToggleBtn.querySelector('i').textContent = isDark ? 'dark_mode' : 'light_mode';
                });
            });
        </script>
    </body>
</html>