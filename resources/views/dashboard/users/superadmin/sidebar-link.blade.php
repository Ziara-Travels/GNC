<!-- User Management Sidebar Link -->
<li class="mb-1">
    <a href="{{ route('superadmin.users.index') }}" class="flex items-center p-2 text-gray-700 hover:bg-blue-50 rounded-lg {{ request()->routeIs('superadmin.users.*') ? 'bg-blue-50 text-blue-700' : '' }}">
        <i data-lucide="users" class="h-5 w-5 mr-3 {{ request()->routeIs('superadmin.users.*') ? 'text-blue-500' : 'text-gray-500' }}"></i>
        <span>User Management</span>
    </a>
</li>
