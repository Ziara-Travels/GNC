<!-- filepath: c:\xampp\htdocs\GNC\resources\views\errors\500.blade.php -->
<!DOCTYPE html>
<html dir="ltr">
    <head>
        <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Internal Server Error - 500">

        <!-- Links Of CSS File -->
        <link rel="stylesheet" href="{{ asset('backend/css/remixicon.css') }}">
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Title -->
        <title>Internal Server Error - 500</title>

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
            
            @keyframes rotateGear {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            @keyframes reverseRotateGear {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(-360deg); }
            }
            
            @keyframes blink {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.2; }
            }
            
            @keyframes smoke {
                0% { transform: translateY(0) scale(1); opacity: 0.8; }
                100% { transform: translateY(-50px) scale(2); opacity: 0; }
            }
            
            @keyframes spark {
                0% { transform: translateY(0) translateX(0); opacity: 1; }
                100% { transform: translateY(-20px) translateX(20px); opacity: 0; }
            }
            
            @keyframes sparkLeft {
                0% { transform: translateY(0) translateX(0); opacity: 1; }
                100% { transform: translateY(-20px) translateX(-20px); opacity: 0; }
            }
            
            @keyframes shake {
                0%, 100% { transform: translateX(0) rotate(0); }
                20% { transform: translateX(-5px) rotate(-5deg); }
                40% { transform: translateX(5px) rotate(5deg); }
                60% { transform: translateX(-3px) rotate(-3deg); }
                80% { transform: translateX(3px) rotate(3deg); }
            }
            
            @keyframes glitchAnimation {
                0% {
                    clip-path: inset(40% 0 61% 0);
                    transform: translate(-2px, 2px);
                }
                20% {
                    clip-path: inset(92% 0 1% 0);
                    transform: translate(1px, 3px);
                }
                40% {
                    clip-path: inset(43% 0 1% 0);
                    transform: translate(0px, 1px);
                }
                60% {
                    clip-path: inset(25% 0 58% 0);
                    transform: translate(2px, 2px);
                }
                80% {
                    clip-path: inset(54% 0 7% 0);
                    transform: translate(-2px, -2px);
                }
                100% {
                    clip-path: inset(58% 0 43% 0);
                    transform: translate(2px, -1px);
                }
            }
            
            @keyframes glitchAnimation2 {
                0% {
                    clip-path: inset(24% 0 29% 0);
                    transform: translate(2px, -2px);
                }
                20% {
                    clip-path: inset(54% 0 26% 0);
                    transform: translate(-3px, 3px);
                }
                40% {
                    clip-path: inset(34% 0 63% 0);
                    transform: translate(0px, -3px);
                }
                60% {
                    clip-path: inset(80% 0 7% 0);
                    transform: translate(1px, -1px);
                }
                80% {
                    clip-path: inset(15% 0 62% 0);
                    transform: translate(-2px, 2px);
                }
                100% {
                    clip-path: inset(100% 0 1% 0);
                    transform: translate(-1px, 1px);
                }
            }
            
            .animate-float { animation: floatAnimation 3s ease-in-out infinite; }
            .animate-pulse-custom { animation: pulse 2s ease-in-out infinite; }
            .animate-rotate { animation: rotateGear 10s linear infinite; }
            .animate-rotate-reverse { animation: reverseRotateGear 7s linear infinite; }
            .animate-blink { animation: blink 2s ease-in-out infinite; }
            .animate-shake { animation: shake 5s ease-in-out infinite; }
            
            .animate-smoke:nth-child(1) { animation: smoke 3s ease-out infinite; animation-delay: 0.2s; }
            .animate-smoke:nth-child(2) { animation: smoke 3.5s ease-out infinite; animation-delay: 0.7s; }
            .animate-smoke:nth-child(3) { animation: smoke 3.2s ease-out infinite; animation-delay: 1.2s; }
            
            .animate-spark:nth-child(1) { animation: spark 1s ease-out infinite; animation-delay: 0.2s; }
            .animate-spark:nth-child(2) { animation: sparkLeft 1.3s ease-out infinite; animation-delay: 0.5s; }
            .animate-spark:nth-child(3) { animation: spark 1.1s ease-out infinite; animation-delay: 0.8s; }
            
            .glitch {
                position: relative;
            }
            
            .glitch::before,
            .glitch::after {
                content: attr(data-text);
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
            
            .glitch::before {
                left: 2px;
                text-shadow: -2px 0 #ff00c1;
                animation: glitchAnimation 2s infinite linear alternate-reverse;
            }
            
            .glitch::after {
                left: -2px;
                text-shadow: 2px 0 #00fff9;
                animation: glitchAnimation2 3s infinite linear alternate-reverse;
            }
            
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

        <!-- 500 Error - Machine Breakdown Theme -->
        <div class="min-h-screen w-full overflow-hidden relative py-12 flex items-center justify-center">
            <!-- Background Elements - Circuit Pattern -->
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <div class="absolute w-full h-full">
                    <!-- Horizontal lines -->
                    <div class="absolute top-1/4 w-full h-0.5 bg-red-500"></div>
                    <div class="absolute top-2/4 w-full h-0.5 bg-red-500"></div>
                    <div class="absolute top-3/4 w-full h-0.5 bg-red-500"></div>
                    
                    <!-- Vertical lines -->
                    <div class="absolute left-1/4 h-full w-0.5 bg-red-500"></div>
                    <div class="absolute left-2/4 h-full w-0.5 bg-red-500"></div>
                    <div class="absolute left-3/4 h-full w-0.5 bg-red-500"></div>
                    
                    <!-- Dots at intersections -->
                    <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-1/4 left-2/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-1/4 left-3/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-2/4 left-1/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-2/4 left-2/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-2/4 left-3/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-3/4 left-1/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-3/4 left-2/4 w-2 h-2 bg-red-500 rounded-full"></div>
                    <div class="absolute top-3/4 left-3/4 w-2 h-2 bg-red-500 rounded-full"></div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-5xl mx-auto">
                    <!-- 500 Header with Animation -->
                    <div class="flex flex-col items-center mb-8">
                        <h1 class="text-9xl font-bold glitch" data-text="500">
                            <span class="relative bg-clip-text text-transparent bg-gradient-to-r from-red-500 to-orange-500">500</span>
                        </h1>
                    </div>
                    
                    <!-- Animated Machine Breakdown Scene -->
                    <div class="relative h-64 md:h-80 mb-10 flex items-center justify-center">
                        <!-- Server/Machine -->
                        <div class="relative w-64 h-48 md:w-80 md:h-56 bg-gradient-to-b from-gray-300 to-gray-400 rounded-lg shadow-lg animate-shake">
                            <!-- Server lights -->
                            <div class="absolute top-3 right-3 flex flex-col gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-500 animate-blink"></div>
                                <div class="w-2 h-2 rounded-full bg-yellow-500 animate-blink" style="animation-delay: 0.3s"></div>
                                <div class="w-2 h-2 rounded-full bg-green-500 animate-blink" style="animation-delay: 0.6s"></div>
                            </div>
                            
                            <!-- Server front panel -->
                            <div class="absolute top-1/4 left-4 right-4 h-1/2 bg-gray-800 rounded-md flex items-center justify-center">
                                <!-- Error text on screen -->
                                <div class="text-red-500 font-mono text-lg md:text-xl font-bold animate-pulse-custom">
                                    ERROR
                                </div>
                                
                                <!-- Smoke coming out -->
                                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                    <div class="animate-smoke w-4 h-4 bg-gray-500 rounded-full opacity-70"></div>
                                    <div class="animate-smoke w-5 h-5 bg-gray-500 rounded-full opacity-60"></div>
                                    <div class="animate-smoke w-3 h-3 bg-gray-500 rounded-full opacity-80"></div>
                                </div>
                            </div>
                            
                            <!-- Sparks from the machine -->
                            <div class="absolute top-1/3 right-0">
                                <div class="animate-spark w-2 h-2 bg-yellow-400 rounded-full"></div>
                                <div class="animate-spark w-1 h-1 bg-orange-400 rounded-full"></div>
                                <div class="animate-spark w-1.5 h-1.5 bg-yellow-300 rounded-full"></div>
                            </div>
                            
                            <!-- Gears -->
                            <div class="absolute -left-8 top-1/4">
                                <!-- Main gear -->
                                <div class="w-16 h-16 border-4 border-gray-600 rounded-full animate-rotate flex items-center justify-center">
                                    <div class="absolute w-full h-2 bg-gray-600"></div>
                                    <div class="absolute w-2 h-full bg-gray-600"></div>
                                    <div class="absolute w-full h-2 bg-gray-600 transform rotate-45"></div>
                                    <div class="absolute w-2 h-full bg-gray-600 transform rotate-45"></div>
                                    <!-- Center -->
                                    <div class="w-4 h-4 bg-gray-700 rounded-full"></div>
                                </div>
                                <!-- Connected gear -->
                                <div class="w-10 h-10 border-3 border-gray-600 rounded-full animate-rotate-reverse absolute -top-5 -left-5 flex items-center justify-center">
                                    <div class="absolute w-full h-1.5 bg-gray-600"></div>
                                    <div class="absolute w-1.5 h-full bg-gray-600"></div>
                                    <div class="absolute w-full h-1.5 bg-gray-600 transform rotate-45"></div>
                                    <div class="absolute w-1.5 h-full bg-gray-600 transform rotate-45"></div>
                                    <!-- Center -->
                                    <div class="w-3 h-3 bg-gray-700 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Binary Code Falling -->
                        <div class="absolute inset-0 overflow-hidden pointer-events-none">
                            <div class="binary-code absolute left-1/4 top-0 text-xs opacity-20 text-red-500">01010111</div>
                            <div class="binary-code absolute left-1/3 top-0 text-xs opacity-20 text-red-500" style="animation-delay: 0.5s">11100110</div>
                            <div class="binary-code absolute left-2/3 top-0 text-xs opacity-20 text-red-500" style="animation-delay: 1s">00101010</div>
                            <div class="binary-code absolute left-3/4 top-0 text-xs opacity-20 text-red-500" style="animation-delay: 1.5s">11001010</div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="text-center space-y-6 relative">
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                            System Malfunction!
                        </h2>
                        <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                            Our server is currently experiencing technical difficulties. 
                            Our team has been notified and is working on fixing the issue.
                        </p>
                        <div class="flex flex-col md:flex-row items-center justify-center gap-4 mt-8">
                            <a href="{{ route('home') }}" class="btn-hover-effect px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 text-white font-medium rounded-lg transform transition-all hover:scale-105 flex items-center gap-2">
                                <span class="material-symbols-outlined">home</span>
                                Back to Home
                            </a>
                            <a href="javascript:history.back()" class="btn-hover-effect px-6 py-3 border-2 border-red-500 text-red-500 dark:border-red-400 dark:text-red-400 font-medium rounded-lg transform transition-all hover:scale-105 flex items-center gap-2">
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
                
                // Create falling binary code animation
                const binaryCodes = document.querySelectorAll('.binary-code');
                binaryCodes.forEach(code => {
                    // Random starting position
                    const leftPosition = Math.random() * 100;
                    code.style.left = `${leftPosition}%`;
                    
                    // Animation
                    setInterval(() => {
                        // Reset to top when it reaches bottom
                        if (parseInt(code.style.top) > 100) {
                            code.style.top = '0%';
                            code.style.left = `${Math.random() * 100}%`;
                            // Change binary code
                            code.textContent = Array(8).fill().map(() => Math.round(Math.random())).join('');
                        } else {
                            // Move down
                            const currentTop = parseInt(code.style.top) || 0;
                            code.style.top = `${currentTop + 1}%`;
                        }
                    }, 100);
                });
            });
        </script>
    </body>
</html>