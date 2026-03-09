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
                        <i data-lucide="users" class="h-10 w-10"></i>
                        User Management
                    </h1>
                    <p class="mt-2 text-purple-100">Manage and oversee all user accounts</p>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="exportUsers()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <i data-lucide="download" class="h-5 w-5"></i>
                        Export Users
                    </button>
                    <a href="{{ route('admin.profile') }}" class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2 hover:bg-white/20 transition-colors">
                        <i data-lucide="user" class="h-5 w-5 inline mr-2"></i>
                        My Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 -mt-16 relative z-10">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $users->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="h-6 w-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $users->where('status', 'active')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-check" class="h-6 w-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Inactive Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $users->where('status', 'inactive')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-x" class="h-6 w-6 text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">New This Month</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $users->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="user-plus" class="h-6 w-6 text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('admin.users') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select name="gender" id="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <select name="country" id="country" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">All Countries</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                            <i data-lucide="filter" class="h-4 w-4"></i>
                            Filter
                        </button>
                        <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($users as $user)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 cursor-pointer" onclick="viewUserDetails({{ $user->id }})">
                    <div class="text-center">
                        <div class="relative inline-block mb-4">
                            @if($user->profile_image)
                                <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" 
                                     alt="{{ $user->name }}" 
                                     class="w-16 h-16 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center border-4 border-gray-200">
                                    <i data-lucide="user" class="h-8 w-8 text-gray-400"></i>
                                </div>
                            @endif
                            
                            <div class="absolute -bottom-1 -right-1">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i data-lucide="circle" class="w-2 h-2 mr-1 {{ $user->status === 'active' ? 'fill-green-500' : 'fill-red-500' }}"></i>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ $user->email }}</p>
                        
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i data-lucide="user" class="w-3 h-3 mr-1"></i>
                                {{ ucfirst($user->roles->first()->name ?? 'User') }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-center text-sm text-gray-500 mb-4">
                            <i data-lucide="calendar" class="h-4 w-4 mr-1"></i>
                            <span>{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <div class="flex gap-2">
                            <button onclick="event.stopPropagation(); toggleUserStatus({{ $user->id }}, '{{ $user->status === 'active' ? 'inactive' : 'active' }}')" 
                                    class="flex-1 {{ $user->status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }} px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i data-lucide="{{ $user->status === 'active' ? 'user-x' : 'user-check' }}" class="h-4 w-4 inline mr-1"></i>
                                {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">User Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="h-6 w-6"></i>
                </button>
            </div>
        </div>
        <div id="userDetailsContent" class="p-6">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});

function viewUserDetails(userId) {
    fetch(`/admin/users/${userId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                document.getElementById('userDetailsContent').innerHTML = `
                    <div class="space-y-6">
                        <div class="flex items-start gap-6">
                            <div class="flex-shrink-0">
                                ${user.profile_image ? 
                                    `<img src="${user.profile_image}" alt="${user.name}" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">` :
                                    `<div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center border-4 border-gray-200">
                                        <i data-lucide="user" class="h-12 w-12 text-gray-400"></i>
                                    </div>`
                                }
                            </div>
                            
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 mb-2">${user.name}</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Email</p>
                                        <p class="font-medium">${user.email}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Phone</p>
                                        <p class="font-medium">${user.phone || 'Not provided'}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Country</p>
                                        <p class="font-medium">${user.country || 'Not provided'}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Gender</p>
                                        <p class="font-medium">${user.gender || 'Not specified'}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-shrink-0">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600 mb-1">${user.completion_percentage}%</div>
                                    <div class="text-sm text-gray-600">Complete</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-lg font-semibold text-gray-900">${user.role}</div>
                                <div class="text-sm text-gray-600">Role</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-lg font-semibold ${user.status === 'active' ? 'text-green-600' : 'text-red-600'}">${user.status}</div>
                                <div class="text-sm text-gray-600">Status</div>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <div class="text-lg font-semibold text-gray-900">${user.created_at}</div>
                                <div class="text-sm text-gray-600">Joined</div>
                            </div>
                        </div>
                        
                        ${user.bio ? `
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Bio</h4>
                                <p class="text-gray-600">${user.bio}</p>
                            </div>
                        ` : ''}
                        
                        ${user.address ? `
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Address</h4>
                                <p class="text-gray-600">${user.address}</p>
                            </div>
                        ` : ''}
                        
                        <div class="flex gap-4 pt-4 border-t">
                            <button onclick="toggleUserStatus(${user.id}, '${user.status === 'active' ? 'inactive' : 'active'}')" 
                                    class="flex-1 ${user.status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700'} text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                ${user.status === 'active' ? 'Deactivate User' : 'Activate User'}
                            </button>
                        </div>
                    </div>
                `;
                
                document.getElementById('userDetailsModal').classList.remove('hidden');
                document.getElementById('userDetailsModal').classList.add('flex');
                lucide.createIcons();
            } else {
                alert('Error loading user details');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading user details');
        });
}

function closeModal() {
    document.getElementById('userDetailsModal').classList.add('hidden');
    document.getElementById('userDetailsModal').classList.remove('flex');
}

function toggleUserStatus(userId, status) {
    if (confirm(`Are you sure you want to ${status === 'active' ? 'activate' : 'deactivate'} this user?`)) {
        fetch(`/admin/users/${userId}/status`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating user status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating user status');
        });
    }
}

function exportUsers() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = `/admin/users/export?${params.toString()}`;
}
</script>
@endsection