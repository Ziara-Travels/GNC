@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold flex items-center gap-3">
                        <i data-lucide="shield" class="h-10 w-10"></i>
                        Admin Profile
                    </h1>
                    <p class="mt-2 text-purple-100">Manage your profile and oversee user accounts</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.users') }}" class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 hover:bg-white/20 transition-colors">
                        <i data-lucide="users" class="h-5 w-5 inline mr-2"></i>
                        Manage Users
                    </a>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                        <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Profile Completion Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8 -mt-16 relative z-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Profile Completion</h2>
                    <p class="text-gray-600">Complete your profile to get the best experience</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-purple-600">{{ $completionPercentage }}%</div>
                    <div class="w-32 bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" style="width: {{ $completionPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600"></i>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-600"></i>
                    <span class="text-red-800 font-medium">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Picture Section -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="camera" class="h-5 w-5"></i>
                        Profile Picture
                    </h3>
                    
                    <div class="text-center">
                        <div class="relative inline-block">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" 
                                     alt="Profile" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-gray-200"
                                     id="profileImagePreview">
                            @else
                                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center border-4 border-gray-200" id="profileImagePreview">
                                    <i data-lucide="shield" class="h-16 w-16 text-gray-400"></i>
                                </div>
                            @endif
                            
                            <label for="profile_image" class="absolute bottom-0 right-0 w-8 h-8 bg-purple-600 text-white rounded-full flex items-center justify-center cursor-pointer hover:bg-purple-700 transition-colors">
                                <i data-lucide="camera" class="h-4 w-4"></i>
                            </label>
                        </div>
                        
                        <p class="text-sm text-gray-600 mt-4">
                            Upload a profile picture to personalize your account
                        </p>
                    </div>
                </div>

                <!-- Role & Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <i data-lucide="shield-check" class="h-5 w-5"></i>
                        Account Information
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Role</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i data-lucide="shield" class="w-3 h-3 mr-1"></i>
                                {{ ucfirst($user->roles->first()->name ?? 'Admin') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i data-lucide="circle" class="w-3 h-3 mr-1 {{ $user->status === 'active' ? 'fill-green-500' : 'fill-red-500' }}"></i>
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Member Since</span>
                            <span class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form Section -->
            <div class="lg:col-span-2">
                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden" onchange="previewImage(event)">
                    
                    <!-- Personal Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i data-lucide="user-circle" class="h-5 w-5"></i>
                            Personal Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="user" class="h-4 w-4 inline mr-1"></i>
                                    Full Name
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="mail" class="h-4 w-4 inline mr-1"></i>
                                    Email Address
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                       required>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="phone" class="h-4 w-4 inline mr-1"></i>
                                    Phone Number
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="globe" class="h-4 w-4 inline mr-1"></i>
                                    Country
                                </label>
                                <select id="country" 
                                        name="country"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Select Country</option>
                                    <option value="Kenya" {{ old('country', $user->country) === 'Kenya' ? 'selected' : '' }}>Kenya</option>
                                    <option value="Uganda" {{ old('country', $user->country) === 'Uganda' ? 'selected' : '' }}>Uganda</option>
                                    <option value="Tanzania" {{ old('country', $user->country) === 'Tanzania' ? 'selected' : '' }}>Tanzania</option>
                                    <option value="Rwanda" {{ old('country', $user->country) === 'Rwanda' ? 'selected' : '' }}>Rwanda</option>
                                    <option value="Other" {{ old('country', $user->country) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Details -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
                            <i data-lucide="file-text" class="h-5 w-5"></i>
                            Additional Details
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="calendar" class="h-4 w-4 inline mr-1"></i>
                                    Date of Birth
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $user->date_of_birth) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="users" class="h-4 w-4 inline mr-1"></i>
                                    Gender
                                </label>
                                <select id="gender" 
                                        name="gender"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="map-pin" class="h-4 w-4 inline mr-1"></i>
                                    Address
                                </label>
                                <textarea id="address" 
                                          name="address" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                          placeholder="Enter your full address">{{ old('address', $user->address) }}</textarea>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i data-lucide="edit-3" class="h-4 w-4 inline mr-1"></i>
                                    Bio
                                </label>
                                <textarea id="bio" 
                                          name="bio" 
                                          rows="4"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                          placeholder="Tell us about yourself">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <div class="flex justify-end">
                            <button type="submit" class="bg-purple-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-purple-700 transition-colors flex items-center gap-2">
                                <i data-lucide="save" class="h-5 w-5"></i>
                                Update Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i data-lucide="lock" class="h-5 w-5"></i>
                    Change Password
                </h3>
                <button id="togglePasswordForm" class="text-purple-600 hover:text-purple-700 flex items-center gap-2">
                    <i data-lucide="chevron-down" class="h-4 w-4"></i>
                    <span>Show/Hide</span>
                </button>
            </div>
            
            <div id="passwordForm" class="hidden">
                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i data-lucide="key" class="h-4 w-4 inline mr-1"></i>
                                Current Password
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i data-lucide="lock" class="h-4 w-4 inline mr-1"></i>
                                New Password
                            </label>
                            <input type="password" 
                                   id="new_password" 
                                   name="new_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   required>
                        </div>
                        
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                <i data-lucide="check" class="h-4 w-4 inline mr-1"></i>
                                Confirm Password
                            </label>
                            <input type="password" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-red-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-red-700 transition-colors flex items-center gap-2">
                            <i data-lucide="shield-check" class="h-5 w-5"></i>
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    
    // Toggle password form
    const toggleBtn = document.getElementById('togglePasswordForm');
    const passwordForm = document.getElementById('passwordForm');
    
    toggleBtn.addEventListener('click', function() {
        passwordForm.classList.toggle('hidden');
    });
});

function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('profileImagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">`;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection