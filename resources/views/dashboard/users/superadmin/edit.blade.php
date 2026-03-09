@extends('layouts.main')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold flex items-center gap-3">
                        <i data-lucide="user-cog" class="h-10 w-10"></i>
                        Edit User
                    </h1>
                    <p class="mt-2 text-blue-100">Update user information</p>
                </div>
                <div>
                    <a href="{{ route('superadmin.users.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-lg px-5 py-2.5 text-white flex items-center gap-2 transition-all">
                        <i data-lucide="arrow-left" class="h-5 w-5"></i>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="h-5 w-5 text-green-600"></i>
                    <span class="text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="h-5 w-5 text-red-600"></i>
                    <span class="text-red-800">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- User Status Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-2xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="flex items-center mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-800' : 
                                   ($user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            
                            @if($user->trashed())
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Deleted
                                </span>
                            @elseif($user->is_active)
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Inactive
                                </span>
                            @endif
                            
                            <span class="ml-2 text-xs text-gray-500">
                                Created {{ $user->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($user->trashed())
                    <form action="{{ route('superadmin.users.restore', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white hover:bg-green-700 font-medium rounded-lg px-5 py-2.5 flex items-center gap-2">
                            <i data-lucide="refresh-cw" class="h-5 w-5"></i>
                            Restore User
                        </button>
                    </form>
                @else
                    <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white hover:bg-red-700 font-medium rounded-lg px-5 py-2.5 flex items-center gap-2">
                            <i data-lucide="trash-2" class="h-5 w-5"></i>
                            Delete User
                        </button>
                    </form>
                @endif
            </div>
        </div>
        
        <!-- Edit User Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Edit User Information</h2>
                <p class="text-gray-600 text-sm">Update user details and permissions</p>
            </div>
            
            <div class="p-6">
                <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                <i data-lucide="user" class="inline-block h-4 w-4 mr-1"></i>
                                Full Name
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('name') border-red-300 @enderror" 
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                <i data-lucide="mail" class="inline-block h-4 w-4 mr-1"></i>
                                Email Address
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('email') border-red-300 @enderror" 
                                required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                <i data-lucide="lock" class="inline-block h-4 w-4 mr-1"></i>
                                New Password
                            </label>
                            <input type="password" name="password" id="password" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep current password</p>
                        </div>
                        
                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                <i data-lucide="lock" class="inline-block h-4 w-4 mr-1"></i>
                                Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <p class="mt-1 text-xs text-gray-500">Leave blank if not changing password</p>
                        </div>
                        
                        <!-- Role Field -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                <i data-lucide="shield" class="inline-block h-4 w-4 mr-1"></i>
                                Role
                            </label>
                            <select name="role" id="role" 
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('role') border-red-300 @enderror" 
                                required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Active Status Field -->
                        <div class="flex items-center mt-6">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} 
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <label for="is_active" class="ml-2 block text-sm text-gray-700">
                                Active Account
                            </label>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-8 border-t border-gray-200 pt-6">
                        <button type="button" onclick="window.location='{{ route('superadmin.users.index') }}'" class="bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium rounded-lg px-5 py-2.5 text-center mr-4">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium rounded-lg px-5 py-2.5 text-center">
                            Update User
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
    
    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this user? This action can be undone.')) {
                this.submit();
            }
        });
    });
});
</script>
@endsection
