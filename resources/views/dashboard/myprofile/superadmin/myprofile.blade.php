<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes glow {
            from { box-shadow: 0 0 20px #3B82F6; }
            to { box-shadow: 0 0 30px #6366F1, 0 0 40px #8B5CF6; }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-indigo-900 via-purple-900 to-pink-800 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
        
        <!-- Animated background elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float delay-1000"></div>
            <div class="absolute -bottom-40 left-40 w-80 h-80 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float delay-2000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="w-32 h-32 bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 rounded-full flex items-center justify-center shadow-2xl animate-glow">
                            <img id="heroProfileImage" src="" alt="Profile" class="w-28 h-28 rounded-full object-cover border-4 border-white/30 hidden">
                            <i data-lucide="user" class="h-16 w-16 text-white" id="heroProfileIcon"></i>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-green-500 rounded-full border-4 border-white flex items-center justify-center">
                            <i data-lucide="check" class="h-5 w-5 text-white"></i>
                        </div>
                    </div>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-4 tracking-tight">
                    My
                    <span class="bg-gradient-to-r from-cyan-400 to-purple-400 bg-clip-text text-transparent">Profile</span>
                </h1>
                <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto" id="heroSubtitle">
                    Manage your personal information, preferences, and account settings
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold" id="userRoleBadge">🔵 User</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold" id="accountStatusBadge">✅ Active</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold" id="loyaltyPointsBadge">⭐ 0 Points</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 -mt-16 relative z-10">
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Loyalty Points</p>
                        <p class="text-3xl font-bold text-gray-900" id="loyaltyPointsCount">0</p>
                        <p class="text-sm text-purple-600 mt-1">Reward points</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="star" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Referrals</p>
                        <p class="text-3xl font-bold text-gray-900" id="referralCount">0</p>
                        <p class="text-sm text-green-600 mt-1">People referred</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="users" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Earnings</p>
                        <p class="text-3xl font-bold text-gray-900">$<span id="referralEarnings">0.00</span></p>
                        <p class="text-sm text-blue-600 mt-1">From referrals</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="dollar-sign" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 p-6 hover:transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Member Since</p>
                        <p class="text-xl font-bold text-gray-900" id="memberSince">2024</p>
                        <p class="text-sm text-orange-600 mt-1">Years of membership</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i data-lucide="calendar" class="h-7 w-7 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <form id="profileForm" class="space-y-8">
            <!-- Personal Information Section -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
                <div class="p-8 border-b border-gray-200 bg-gradient-to-r from-blue-500 to-purple-600">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i data-lucide="user" class="h-6 w-6 mr-3"></i>
                        Personal Information
                    </h3>
                    <p class="text-blue-100 mt-2">Update your personal details and profile information</p>
                </div>

                <div class="p-8">
                    <!-- Profile Image Upload -->
                    <div class="mb-8 text-center">
                        <div class="relative inline-block">
                            <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center overflow-hidden shadow-lg">
                                <img id="profileImagePreview" src="" alt="Profile" class="w-full h-full object-cover hidden">
                                <i data-lucide="user" class="h-16 w-16 text-gray-500" id="profileImagePlaceholder"></i>
                            </div>
                            <label for="profileImageInput" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full cursor-pointer transition-colors shadow-lg">
                                <i data-lucide="camera" class="h-5 w-5"></i>
                            </label>
                            <input type="file" id="profileImageInput" accept="image/*" class="hidden">
                        </div>
                        <p class="text-sm text-gray-500 mt-4">Click the camera icon to upload a new profile picture</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="user" class="h-4 w-4 inline mr-2"></i>
                                Full Name *
                            </label>
                            <input type="text" id="name" name="name" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition-all"
                                   placeholder="Enter your full name">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="mail" class="h-4 w-4 inline mr-2"></i>
                                Email Address *
                            </label>
                            <input type="email" id="email" name="email" required 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition-all"
                                   placeholder="Enter your email address">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="phone" class="h-4 w-4 inline mr-2"></i>
                                Phone Number
                            </label>
                            <div class="flex">
                                <select id="phoneCountryCode" name="phone_country_code" 
                                        class="px-3 py-3 border border-gray-200 rounded-l-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+254">🇰🇪 +254</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+91">🇮🇳 +91</option>
                                    <option value="+86">🇨🇳 +86</option>
                                </select>
                                <input type="tel" id="phoneNumber" name="phone_number" 
                                       class="flex-1 px-4 py-3 border border-l-0 border-gray-200 rounded-r-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                                       placeholder="Enter your phone number">
                            </div>
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="globe" class="h-4 w-4 inline mr-2"></i>
                                Country
                            </label>
                            <select id="country" name="country" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="">Select your country</option>
                                <option value="US">🇺🇸 United States</option>
                                <option value="KE">🇰🇪 Kenya</option>
                                <option value="GB">🇬🇧 United Kingdom</option>
                                <option value="IN">🇮🇳 India</option>
                                <option value="CN">🇨🇳 China</option>
                                <option value="CA">🇨🇦 Canada</option>
                                <option value="AU">🇦🇺 Australia</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="calendar" class="h-4 w-4 inline mr-2"></i>
                                Date of Birth
                            </label>
                            <input type="date" id="dateOfBirth" name="date_of_birth" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="user-check" class="h-4 w-4 inline mr-2"></i>
                                Gender
                            </label>
                            <select id="gender" name="gender" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i data-lucide="map-pin" class="h-4 w-4 inline mr-2"></i>
                            Address
                        </label>
                        <textarea id="address" name="address" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white resize-none"
                                  placeholder="Enter your full address"></textarea>
                    </div>

                    <!-- Bio -->
                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i data-lucide="file-text" class="h-4 w-4 inline mr-2"></i>
                            Bio
                        </label>
                        <textarea id="bio" name="bio" rows="4" 
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white resize-none"
                                  placeholder="Tell us about yourself..."></textarea>
                        <p class="text-sm text-gray-500 mt-2">Write a brief description about yourself (optional)</p>
                    </div>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
                <div class="p-8 border-b border-gray-200 bg-gradient-to-r from-green-500 to-teal-600">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i data-lucide="settings" class="h-6 w-6 mr-3"></i>
                        Account Information
                    </h3>
                    <p class="text-green-100 mt-2">View your account status and settings</p>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="shield" class="h-4 w-4 inline mr-2"></i>
                                Account Role
                            </label>
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div id="roleIcon" class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <i data-lucide="user" class="h-5 w-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900" id="userRole">User</p>
                                    <p class="text-sm text-gray-500">Standard user account</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="activity" class="h-4 w-4 inline mr-2"></i>
                                Account Status
                            </label>
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div id="statusIcon" class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <i data-lucide="check" class="h-5 w-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900" id="accountStatus">Active</p>
                                    <p class="text-sm text-gray-500">Account is active and verified</p>
                                </div>
                            </div>
                        </div>

                        <!-- Email Verification -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="mail-check" class="h-4 w-4 inline mr-2"></i>
                                Email Verification
                            </label>
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div id="emailVerificationIcon" class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <i data-lucide="check" class="h-5 w-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900" id="emailVerificationStatus">Verified</p>
                                    <p class="text-sm text-gray-500" id="emailVerificationDate">Email verified</p>
                                </div>
                            </div>
                        </div>

                        <!-- Last Login -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="clock" class="h-4 w-4 inline mr-2"></i>
                                Last Login
                            </label>
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <i data-lucide="clock" class="h-5 w-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900" id="lastLoginTime">Never</p>
                                    <p class="text-sm text-gray-500">Last login time</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral System Section -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
                <div class="p-8 border-b border-gray-200 bg-gradient-to-r from-purple-500 to-pink-600">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i data-lucide="users" class="h-6 w-6 mr-3"></i>
                        Referral System
                    </h3>
                    <p class="text-purple-100 mt-2">Share your referral code and earn rewards</p>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Referral Code -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="link" class="h-4 w-4 inline mr-2"></i>
                                Your Referral Code
                            </label>
                            <div class="flex">
                                <input type="text" id="referralCode" name="referral_code" readonly 
                                       class="flex-1 px-4 py-3 border border-gray-200 rounded-l-xl bg-gray-50 text-gray-600">
                                <button type="button" onclick="copyReferralCode()" 
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-r-xl transition-colors flex items-center">
                                    <i data-lucide="copy" class="h-4 w-4 mr-2"></i>
                                    Copy
                                </button>
                            </div>
                        </div>

                        <!-- Referred By -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="user-plus" class="h-4 w-4 inline mr-2"></i>
                                Referred By
                            </label>
                            <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                    <i data-lucide="user" class="h-5 w-5 text-white"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900" id="referredByName">No referrer</p>
                                    <p class="text-sm text-gray-500">Person who referred you</p>
                                </div>
                            </div>
                        </div>

                        <!-- Referral Stats Grid -->
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gradient-to-br from-green-50 to-emerald-100 p-6 rounded-2xl border border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-green-700">Total Referrals</p>
                                            <p class="text-2xl font-bold text-green-900" id="totalReferrals">0</p>
                                        </div>
                                        <i data-lucide="users" class="h-8 w-8 text-green-600"></i>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-6 rounded-2xl border border-blue-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-blue-700">Total Earnings</p>
                                            <p class="text-2xl font-bold text-blue-900">$<span id="totalEarnings">0.00</span></p>
                                        </div>
                                        <i data-lucide="dollar-sign" class="h-8 w-8 text-blue-600"></i>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-br from-purple-50 to-pink-100 p-6 rounded-2xl border border-purple-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-semibold text-purple-700">Status</p>
                                            <p class="text-lg font-bold text-purple-900" id="referralStatus">Active</p>
                                        </div>
                                        <i data-lucide="activity" class="h-8 w-8 text-purple-600"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Section -->
            <div class="bg-white/90 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
                <div class="p-8 border-b border-gray-200 bg-gradient-to-r from-red-500 to-pink-600">
                    <h3 class="text-2xl font-bold text-white flex items-center">
                        <i data-lucide="shield" class="h-6 w-6 mr-3"></i>
                        Security Settings
                    </h3>
                    <p class="text-red-100 mt-2">Manage your account security and password</p>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Change Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="lock" class="h-4 w-4 inline mr-2"></i>
                                New Password
                            </label>
                            <input type="password" id="newPassword" name="new_password" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white"
                                   placeholder="Enter new password">
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i data-lucide="lock" class="h-4 w-4 inline mr-2"></i>
                                Confirm Password
                            </label>
                            <input type="password" id="confirmPassword" name="confirm_password" 
                                   class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white"
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                        <div class="flex items-start">
                            <i data-lucide="alert-triangle" class="h-5 w-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="text-sm font-semibold text-yellow-800">Password Security Tips</h4>
                                <ul class="text-sm text-yellow-700 mt-2 space-y-1">
                                    <li>• Use at least 8 characters</li>
                                    <li>• Include uppercase and lowercase letters</li>
                                    <li>• Add numbers and special characters</li>
                                    <li>• Avoid common words or personal information</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button type="button" onclick="resetForm()" 
                        class="px-8 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-2xl font-semibold transition-all duration-300 flex items-center justify-center gap-3 transform hover:scale-105">
                    <i data-lucide="refresh-cw" class="h-5 w-5"></i>
                    Reset Changes
                </button>
                
                <button type="submit" 
                        class="px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-semibold transition-all duration-300 flex items-center justify-center gap-3 transform hover:scale-105 shadow-lg">
                    <i data-lucide="save" class="h-5 w-5"></i>
                    Save Profile
                </button>

                <button type="button" onclick="deleteAccount()" 
                        class="px-8 py-4 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white rounded-2xl font-semibold transition-all duration-300 flex items-center justify-center gap-3 transform hover:scale-105 shadow-lg">
                    <i data-lucide="trash-2" class="h-5 w-5"></i>
                    Delete Account
                </button>
            </div>
        </form>
    </div>

    <script>
        // Sample user data - this would normally come from your backend
        const userData = {
            id: 1,
            name: "John Doe",
            email: "john.doe@example.com",
            phone: "+1234567890",
            country: "US",
            phone_country_code: "+1",
            phone_number: "234567890",
            date_of_birth: "1990-05-15",
            gender: "male",
            address: "123 Main Street, New York, NY 10001",
            bio: "Software developer passionate about creating innovative solutions and helping others learn programming.",
            profile_image: null,
            role: "user",
            is_active: true,
            status: "active",
            email_verified_at: "2024-01-01T10:00:00Z",
            loyalty_points: 1250,
            last_login_at: "2024-01-15T14:30:00Z",
            referral_code: "REF123ABC",
            referred_by: null,
            referral_count: 5,
            referral_earnings: 125.50,
            referral_active: true,
            created_at: "2024-01-01T00:00:00Z"
        };

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            populateUserData();
            setupEventListeners();
        });

        // Populate form with user data
        function populateUserData() {
            // Hero section
            document.getElementById('heroSubtitle').textContent = `Welcome back, ${userData.name}! Manage your account settings and preferences.`;
            
            // Role badge
            const roleColors = {
                'superadmin': { emoji: '🔴', text: 'Superadmin', color: 'text-red-600' },
                'admin': { emoji: '🟡', text: 'Admin', color: 'text-yellow-600' },
                'user': { emoji: '🔵', text: 'User', color: 'text-blue-600' }
            };
            const roleInfo = roleColors[userData.role] || roleColors['user'];
            document.getElementById('userRoleBadge').textContent = `${roleInfo.emoji} ${roleInfo.text}`;
            
            // Status badge
            const statusBadge = userData.status === 'active' ? '✅ Active' : '❌ Inactive';
            document.getElementById('accountStatusBadge').textContent = statusBadge;
            
            // Loyalty points badge
            document.getElementById('loyaltyPointsBadge').textContent = `⭐ ${userData.loyalty_points.toLocaleString()} Points`;

            // Quick stats
            document.getElementById('loyaltyPointsCount').textContent = userData.loyalty_points.toLocaleString();
            document.getElementById('referralCount').textContent = userData.referral_count.toString();
            document.getElementById('referralEarnings').textContent = userData.referral_earnings.toFixed(2);
            
            // Member since calculation
            const memberSince = new Date(userData.created_at).getFullYear();
            const yearsAsMember = new Date().getFullYear() - memberSince + 1;
            document.getElementById('memberSince').textContent = yearsAsMember.toString();

            // Personal information
            document.getElementById('name').value = userData.name || '';
            document.getElementById('email').value = userData.email || '';
            document.getElementById('phoneCountryCode').value = userData.phone_country_code || '+1';
            document.getElementById('phoneNumber').value = userData.phone_number || '';
            document.getElementById('country').value = userData.country || '';
            document.getElementById('dateOfBirth').value = userData.date_of_birth || '';
            document.getElementById('gender').value = userData.gender || '';
            document.getElementById('address').value = userData.address || '';
            document.getElementById('bio').value = userData.bio || '';

            // Profile image
            if (userData.profile_image) {
                document.getElementById('profileImagePreview').src = userData.profile_image;
                document.getElementById('profileImagePreview').classList.remove('hidden');
                document.getElementById('profileImagePlaceholder').classList.add('hidden');
                
                document.getElementById('heroProfileImage').src = userData.profile_image;
                document.getElementById('heroProfileImage').classList.remove('hidden');
                document.getElementById('heroProfileIcon').classList.add('hidden');
            }

            // Account information
            document.getElementById('userRole').textContent = roleInfo.text;
            document.getElementById('accountStatus').textContent = userData.status === 'active' ? 'Active' : 'Inactive';
            
            // Email verification
            const emailVerified = userData.email_verified_at !== null;
            if (emailVerified) {
                document.getElementById('emailVerificationStatus').textContent = 'Verified';
                document.getElementById('emailVerificationDate').textContent = `Verified on ${new Date(userData.email_verified_at).toLocaleDateString()}`;
            } else {
                document.getElementById('emailVerificationStatus').textContent = 'Not Verified';
                document.getElementById('emailVerificationDate').textContent = 'Please verify your email';
                document.getElementById('emailVerificationIcon').className = 'w-10 h-10 bg-red-500 rounded-full flex items-center justify-center mr-3';
                document.getElementById('emailVerificationIcon').innerHTML = '<i data-lucide="x" class="h-5 w-5 text-white"></i>';
            }

            // Last login
            if (userData.last_login_at) {
                const lastLogin = new Date(userData.last_login_at);
                document.getElementById('lastLoginTime').textContent = lastLogin.toLocaleString();
            }

            // Referral system
            document.getElementById('referralCode').value = userData.referral_code || 'Not generated';
            document.getElementById('totalReferrals').textContent = userData.referral_count.toString();
            document.getElementById('totalEarnings').textContent = userData.referral_earnings.toFixed(2);
            document.getElementById('referralStatus').textContent = userData.referral_active ? 'Active' : 'Inactive';

            if (userData.referred_by) {
                // In a real app, you'd fetch the referrer's name
                document.getElementById('referredByName').textContent = 'User #' + userData.referred_by;
            }

            // Update role-based styling
            updateRoleBasedStyling(userData.role);
        }

        // Update styling based on user role
        function updateRoleBasedStyling(role) {
            const roleIcon = document.getElementById('roleIcon');
            const statusIcon = document.getElementById('statusIcon');
            
            const roleStyles = {
                'superadmin': { bg: 'bg-red-500', icon: 'crown' },
                'admin': { bg: 'bg-yellow-500', icon: 'star' },
                'user': { bg: 'bg-blue-500', icon: 'user' }
            };
            
            const style = roleStyles[role] || roleStyles['user'];
            roleIcon.className = `w-10 h-10 ${style.bg} rounded-full flex items-center justify-center mr-3`;
            roleIcon.innerHTML = `<i data-lucide="${style.icon}" class="h-5 w-5 text-white"></i>`;
            
            lucide.createIcons();
        }

        // Setup event listeners
        function setupEventListeners() {
            // Profile image upload
            document.getElementById('profileImageInput').addEventListener('change', handleImageUpload);
            
            // Form submission
            document.getElementById('profileForm').addEventListener('submit', handleFormSubmit);
            
            // Password validation
            document.getElementById('confirmPassword').addEventListener('input', validatePasswords);
        }

        // Handle image upload
        function handleImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageUrl = e.target.result;
                    
                    // Update preview image
                    document.getElementById('profileImagePreview').src = imageUrl;
                    document.getElementById('profileImagePreview').classList.remove('hidden');
                    document.getElementById('profileImagePlaceholder').classList.add('hidden');
                    
                    // Update hero image
                    document.getElementById('heroProfileImage').src = imageUrl;
                    document.getElementById('heroProfileImage').classList.remove('hidden');
                    document.getElementById('heroProfileIcon').classList.add('hidden');
                };
                reader.readAsDataURL(file);
                
                showNotification('Profile image updated successfully!', 'success');
            }
        }

        // Handle form submission
        function handleFormSubmit(event) {
            event.preventDefault();
            
            // Validate passwords if they're being changed
            if (!validatePasswords()) {
                return;
            }
            
            // Show loading state
            const submitButton = event.target.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i data-lucide="loader" class="h-5 w-5 animate-spin mr-2"></i>Saving...';
            submitButton.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Reset button
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                lucide.createIcons();
                
                // Show success message
                showNotification('Profile updated successfully!', 'success');
                
                // Update userData object with new values (in real app, this would come from server response)
                updateUserDataFromForm();
                
            }, 2000);
        }

        // Validate passwords
        function validatePasswords() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword && newPassword !== confirmPassword) {
                showNotification('Passwords do not match!', 'error');
                return false;
            }
            
            if (newPassword && newPassword.length < 8) {
                showNotification('Password must be at least 8 characters long!', 'error');
                return false;
            }
            
            return true;
        }

        // Update userData object from form
        function updateUserDataFromForm() {
            userData.name = document.getElementById('name').value;
            userData.email = document.getElementById('email').value;
            userData.phone_country_code = document.getElementById('phoneCountryCode').value;
            userData.phone_number = document.getElementById('phoneNumber').value;
            userData.country = document.getElementById('country').value;
            userData.date_of_birth = document.getElementById('dateOfBirth').value;
            userData.gender = document.getElementById('gender').value;
            userData.address = document.getElementById('address').value;
            userData.bio = document.getElementById('bio').value;
            
            // Update hero subtitle with new name
            document.getElementById('heroSubtitle').textContent = `Welcome back, ${userData.name}! Manage your account settings and preferences.`;
        }

        // Copy referral code
        function copyReferralCode() {
            const referralCode = document.getElementById('referralCode');
            referralCode.select();
            document.execCommand('copy');
            showNotification('Referral code copied to clipboard!', 'success');
        }

        // Reset form
        function resetForm() {
            if (confirm('Are you sure you want to reset all changes? This will restore all fields to their original values.')) {
                populateUserData();
                showNotification('Form reset to original values', 'info');
            }
        }

        // Delete account
        function deleteAccount() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                if (confirm('This will permanently delete all your data. Are you absolutely sure?')) {
                    showNotification('Account deletion initiated. Please check your email for confirmation.', 'warning');
                }
            }
        }

        // Notification system
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full max-w-sm`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-yellow-500 text-black'
            };
            
            const icons = {
                success: 'check-circle',
                error: 'x-circle',
                info: 'info',
                warning: 'alert-triangle'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i data-lucide="${icons[type] || icons.info}" class="h-5 w-5 mr-3 flex-shrink-0"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            lucide.createIcons();
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Animate out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }

        // Auto-save functionality (optional)
        function enableAutoSave() {
            const formElements = document.querySelectorAll('#profileForm input, #profileForm textarea, #profileForm select');
            
            formElements.forEach(element => {
                element.addEventListener('change', function() {
                    // Debounce auto-save
                    clearTimeout(window.autoSaveTimeout);
                    window.autoSaveTimeout = setTimeout(() => {
                        showNotification('Changes auto-saved', 'info');
                    }, 2000);
                });
            });
        }

        // Initialize auto-save (uncomment if needed)
        // enableAutoSave();
    </script>
</body>
</html>