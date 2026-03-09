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
                        <i data-lucide="users" class="h-10 w-10"></i>
                        User Management
                    </h1>
                    <p class="mt-2 text-blue-100">Manage your system users</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('superadmin.users.create') }}" class="bg-white text-blue-600 hover:bg-blue-50 font-medium rounded-lg px-5 py-2.5 flex items-center gap-2">
                        <i data-lucide="user-plus" class="h-5 w-5"></i>
                        Add New User
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

        <!-- User Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">System Users</h2>
                <p class="text-gray-600 text-sm">Manage all users in the system</p>
            </div>
            
            <!-- User Filtering Controls -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <form action="{{ route('superadmin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <label for="role" class="text-sm font-medium text-gray-700">Role:</label>
                        <select name="role" id="role" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">All Roles</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <label for="status" class="text-sm font-medium text-gray-700">Status:</label>
                        <select name="status" id="status" class="rounded-md border-gray-300 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                        </select>
                    </div>
                    
                    <div class="flex-1 ml-auto">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" class="w-full rounded-md border-gray-300 pl-10 pr-4 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <i data-lucide="search" class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2 text-sm hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    
                    <a href="{{ route('superadmin.users.index') }}" class="text-gray-600 text-sm hover:text-blue-600">
                        Reset
                    </a>
                </form>
            </div>
            
            <!-- User Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="{{ $user->trashed() ? 'bg-red-50' : ($user->is_active ? '' : 'bg-yellow-50') }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-800' : 
                                           ($user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->trashed())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Deleted
                                        </span>
                                    @elseif($user->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('superadmin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                            <i data-lucide="edit" class="h-5 w-5"></i>
                                        </a>
                                        
                                        @if($user->trashed())
                                            <form action="{{ route('superadmin.users.restore', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="Restore">
                                                    <i data-lucide="refresh-cw" class="h-5 w-5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" class="inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                    <i data-lucide="trash-2" class="h-5 w-5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide icons
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
