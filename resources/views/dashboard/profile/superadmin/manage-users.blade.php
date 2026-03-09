@extends('layouts.main')

@section('content')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold flex items-center gap-3">
                            <i data-lucide="users" class="h-10 w-10"></i>
                            User Management
                        </h1>
                        <p class="mt-2 text-purple-100">Manage all system users and their permissions</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-2">
                            <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                        </div>
                        <button onclick="openCreateUserModal()"
                            class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                            Create User
                        </button>
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
                            <p class="text-sm font-medium text-gray-600">Superadmins</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $users->where('roles.0.name', 'superadmin')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="crown" class="h-6 w-6 text-purple-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Admins</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ $users->where('roles.0.name', 'admin')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="shield-check" class="h-6 w-6 text-green-600"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Regular Users</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $users->where('roles.0.name', 'user')->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="user" class="h-6 w-6 text-orange-600"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700">Filters:</label>
                        <select id="roleFilter" onchange="applyFilters()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">All Roles</option>
                            <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Superadmin
                            </option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                        </select>

                        <select id="statusFilter" onchange="applyFilters()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>

                        <select id="genderFilter" onchange="applyFilters()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>

                        <select id="countryFilter" onchange="applyFilters()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="">All Countries</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country }}"
                                    {{ request('country') === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="ml-auto flex gap-2">
                        <button onclick="exportUsers()"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition-colors flex items-center gap-2">
                            <i data-lucide="download" class="h-4 w-4"></i>
                            Export
                        </button>
                        <button onclick="clearFilters()"
                            class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition-colors flex items-center gap-2">
                            <i data-lucide="x" class="h-4 w-4"></i>
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Users Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($users as $user)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    @if ($user->profile_image)
                                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}"
                                            alt="{{ $user->name }}"
                                            class="w-12 h-12 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div
                                            class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center border-2 border-gray-200">
                                            <i data-lucide="user" class="h-6 w-6 text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $user->roles->first()->name === 'superadmin'
                                    ? 'bg-purple-100 text-purple-800'
                                    : ($user->roles->first()->name === 'admin'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-blue-100 text-blue-800') }}">
                                        <i data-lucide="{{ $user->roles->first()->name === 'superadmin'
                                            ? 'crown'
                                            : ($user->roles->first()->name === 'admin'
                                                ? 'shield-check'
                                                : 'user') }}"
                                            class="w-3 h-3 mr-1"></i>
                                        {{ ucfirst($user->roles->first()->name ?? 'User') }}
                                    </span>

                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <i data-lucide="circle"
                                            class="w-3 h-3 mr-1 {{ $user->status === 'active' ? 'fill-green-500' : 'fill-red-500' }}"></i>
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                @if ($user->phone)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i data-lucide="phone" class="h-4 w-4"></i>
                                        <span>{{ $user->phone }}</span>
                                    </div>
                                @endif

                                @if ($user->country)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i data-lucide="map-pin" class="h-4 w-4"></i>
                                        <span>{{ $user->country }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <i data-lucide="calendar" class="h-4 w-4"></i>
                                    <span>{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button onclick="viewUserDetails({{ $user->id }})"
                                    class="flex-1 bg-blue-50 text-blue-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
                                    <i data-lucide="eye" class="h-4 w-4"></i>
                                    View
                                </button>

                                <button onclick="editUser({{ $user->id }})"
                                    class="flex-1 bg-green-50 text-green-600 px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors flex items-center justify-center gap-2">
                                    <i data-lucide="edit" class="h-4 w-4"></i>
                                    Edit
                                </button>

                                <div class="relative">
                                    <button onclick="toggleUserActions({{ $user->id }})"
                                        class="bg-gray-50 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 transition-colors">
                                        <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                                    </button>

                                    <div id="userActions{{ $user->id }}"
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden">
                                        <div class="py-1">
                                            <button
                                                onclick="toggleUserStatus({{ $user->id }}, '{{ $user->status === 'active' ? 'inactive' : 'active' }}')"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 flex items-center gap-2">
                                                <i data-lucide="{{ $user->status === 'active' ? 'user-x' : 'user-check' }}"
                                                    class="h-4 w-4"></i>
                                                {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>

                                            <button onclick="impersonateUser({{ $user->id }})"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 flex items-center gap-2">
                                                <i data-lucide="user-check" class="h-4 w-4"></i>
                                                Impersonate User
                                            </button>

                                            <button onclick="changeUserRole({{ $user->id }})"
                                                class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100 flex items-center gap-2">
                                                <i data-lucide="user-cog" class="h-4 w-4"></i>
                                                Change Role
                                            </button>

                                            @if ($user->id !== auth()->id())
                                                <button onclick="deleteUser({{ $user->id }})"
                                                    class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                    <i data-lucide="trash-2" class="h-4 w-4"></i>
                                                    Delete User
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($users->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $users->links() }}
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
                    <button onclick="closeModal('userDetailsModal')" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>
            </div>
            <div id="userDetailsContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Edit User</h3>
                    <button onclick="closeModal('editUserModal')" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>
            </div>
            <div id="editUserContent" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div id="createUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900">Create New User</h3>
                    <button onclick="closeModal('createUserModal')" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-6 w-6"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <form id="createUserForm" onsubmit="submitCreateUser(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select name="role" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Select Role</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="superadmin">Superadmin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" name="phone"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <select name="country"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">Select Country</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Tanzania">Tanzania</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 gap-4">
                        <button type="button" onclick="closeModal('createUserModal')"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                const dropdowns = document.querySelectorAll('[id^="userActions"]');
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(event.target) && !event.target.closest(
                            '[onclick*="toggleUserActions"]')) {
                        dropdown.classList.add('hidden');
                    }
                });
            });
        });

        function applyFilters() {
            const role = document.getElementById('roleFilter').value;
            const status = document.getElementById('statusFilter').value;
            const gender = document.getElementById('genderFilter').value;
            const country = document.getElementById('countryFilter').value;

            const params = new URLSearchParams();
            if (role) params.append('role', role);
            if (status) params.append('status', status);
            if (gender) params.append('gender', gender);
            if (country) params.append('country', country);

            window.location.search = params.toString();
        }

        function clearFilters() {
            window.location.search = '';
        }

        function exportUsers() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = `/superadmin/users/export?${params.toString()}`;
        }

        function toggleUserActions(userId) {
            const dropdown = document.getElementById(`userActions${userId}`);
            dropdown.classList.toggle('hidden');
        }

        function impersonateUser(userId) {
            if (confirm('Are you sure you want to impersonate this user?')) {
                fetch(`/impersonate/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.href = '/dashboard';
                        } else {
                            alert('Error starting impersonation');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error starting impersonation');
                    });
            }
        }

        function viewUserDetails(userId) {
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        document.getElementById('userDetailsContent').innerHTML = generateUserDetailsHTML(user);
                        document.getElementById('userDetailsModal').classList.remove('hidden');
                        document.getElementById('userDetailsModal').classList.add('flex');
                        lucide.createIcons();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user details');
                });
        }

        function editUser(userId) {
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        document.getElementById('editUserContent').innerHTML = generateEditUserHTML(user);
                        document.getElementById('editUserModal').classList.remove('hidden');
                        document.getElementById('editUserModal').classList.add('flex');
                        lucide.createIcons();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user details');
                });
        }

        function openCreateUserModal() {
            document.getElementById('createUserModal').classList.remove('hidden');
            document.getElementById('createUserModal').classList.add('flex');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.getElementById(modalId).classList.remove('flex');
        }

        function toggleUserStatus(userId, newStatus) {
            if (confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this user?`)) {
                fetch(`/api/users/${userId}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
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

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                fetch(`/api/users/${userId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Error deleting user');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting user');
                    });
            }
        }

        function submitCreateUser(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            fetch('/api/users', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('createUserModal');
                        location.reload();
                    } else {
                        alert(data.message || 'Error creating user');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating user');
                });
        }

        function generateUserDetailsHTML(user) {
            return `
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
                    <div class="flex items-center gap-4 mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                            user.role === 'superadmin' ? 'bg-purple-100 text-purple-800' : 
                            (user.role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')
                        }">
                            <i data-lucide="${user.role === 'superadmin' ? 'crown' : (user.role === 'admin' ? 'shield-check' : 'user')}" class="w-3 h-3 mr-1"></i>
                            ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                            user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        }">
                            <i data-lucide="circle" class="w-3 h-3 mr-1 ${user.status === 'active' ? 'fill-green-500' : 'fill-red-500'}"></i>
                            ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <i data-lucide="mail" class="h-4 w-4 text-gray-500"></i>
                            <span>${user.email}</span>
                        </div>
                        ${user.phone ? `
                                <div class="flex items-center gap-2">
                                    <i data-lucide="phone" class="h-4 w-4 text-gray-500"></i>
                                    <span>${user.phone}</span>
                                </div>
                            ` : ''}
                        ${user.country ? `
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map-pin" class="h-4 w-4 text-gray-500"></i>
                                    <span>${user.country}</span>
                                </div>
                            ` : ''}
                        ${user.date_of_birth ? `
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="h-4 w-4 text-gray-500"></i>
                                    <span>${user.date_of_birth}</span>
                                </div>
                            ` : ''}
                        ${user.gender ? `
                                <div class="flex items-center gap-2">
                                    <i data-lucide="users" class="h-4 w-4 text-gray-500"></i>
                                    <span>${user.gender.charAt(0).toUpperCase() + user.gender.slice(1)}</span>
                                </div>
                            ` : ''}
                        <div class="flex items-center gap-2">
                            <i data-lucide="calendar-plus" class="h-4 w-4 text-gray-500"></i>
                            <span>Joined ${user.created_at}</span>
                        </div>
                    </div>
                    ${user.address ? `
                            <div class="mt-4">
                                <h4 class="font-medium text-gray-900 mb-2">Address</h4>
                                <p class="text-gray-600">${user.address}</p>
                            </div>
                        ` : ''}
                    ${user.bio ? `
                            <div class="mt-4">
                                <h4 class="font-medium text-gray-900 mb-2">Bio</h4>
                                <p class="text-gray-600">${user.bio}</p>
                            </div>
                        ` : ''}
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-2">Profile Completion</h4>
                <div class="flex items-center gap-4">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: ${user.completion_percentage}%"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-600">${user.completion_percentage}%</span>
                </div>
            </div>
        </div>
    `;
        }

        function generateEditUserHTML(user) {
            return `
        <form onsubmit="submitEditUser(event, ${user.id})">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="${user.name}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="${user.email}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="user" ${user.role === 'user' ? 'selected' : ''}>User</option>
                        <option value="admin" ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                        <option value="superadmin" ${user.role === 'superadmin' ? 'selected' : ''}>Superadmin</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="active" ${user.status === 'active' ? 'selected' : ''}>Active</option>
                        <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="tel" name="phone" value="${user.phone || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                    <select name="country" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select Country</option>
                        <option value="Kenya" ${user.country === 'Kenya' ? 'selected' : ''}>Kenya</option>
                        <option value="Uganda" ${user.country === 'Uganda' ? 'selected' : ''}>Uganda</option>
                        <option value="Tanzania" ${user.country === 'Tanzania' ? 'selected' : ''}>Tanzania</option>
                        <option value="Rwanda" ${user.country === 'Rwanda' ? 'selected' : ''}>Rwanda</option>
                        <option value="Other" ${user.country === 'Other' ? 'selected' : ''}>Other</option>
                    </select>
                      <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="${user.date_of_birth || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="male" ${user.gender === 'male' ? 'selected' : ''}>Male</option>
                        <option value="female" ${user.gender === 'female' ? 'selected' : ''}>Female</option>
                        <option value="other" ${user.gender === 'other' ? 'selected' : ''}>Other</option>
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">${user.address || ''}</textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                    <textarea name="bio" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">${user.bio || ''}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 gap-4">
                <button type="button" onclick="closeModal('editUserModal')" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                    <i data-lucide="save" class="h-4 w-4"></i>
                    Update User
                </button>
            </div>
        </form>
    `;
        }

        function submitEditUser(event, userId) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = Object.fromEntries(formData);

            fetch(`/api/users/${userId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('editUserModal');
                        location.reload();
                    } else {
                        alert(data.message || 'Error updating user');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating user');
                });
        }

        function changeUserRole(userId) {
            fetch(`/api/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        const newRole = prompt(
                            `Change role for ${user.name}. Current role: ${user.role}\n\nEnter new role (user/admin/superadmin):`,
                            user.role);

                        if (newRole && ['user', 'admin', 'superadmin'].includes(newRole.toLowerCase())) {
                            updateUserRole(userId, newRole.toLowerCase());
                        } else if (newRole !== null) {
                            alert('Invalid role. Please enter: user, admin, or superadmin');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user details');
                });
        }

        function updateUserRole(userId, newRole) {
            if (confirm(`Are you sure you want to change this user's role to ${newRole}?`)) {
                fetch(`/api/users/${userId}/role`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            role: newRole
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Error updating user role');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating user role');
                    });
            }
        }

        // Add date filtering functionality
        function addDateFilter() {
            const filterContainer = document.querySelector('.flex.flex-wrap.items-center.gap-4');
            const dateFilterHTML = `
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700">Registration Date:</label>
            <input type="date" id="dateFromFilter" onchange="applyFilters()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <span class="text-sm text-gray-500">to</span>
            <input type="date" id="dateToFilter" onchange="applyFilters()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
    `;

            // Add date filter if not already exists
            if (!document.getElementById('dateFromFilter')) {
                const labelElement = filterContainer.querySelector('label');
                labelElement.insertAdjacentHTML('afterend', dateFilterHTML);
            }
        }

        // Enhanced filter function to include date range
        function applyFiltersWithDate() {
            const role = document.getElementById('roleFilter').value;
            const status = document.getElementById('statusFilter').value;
            const gender = document.getElementById('genderFilter').value;
            const country = document.getElementById('countryFilter').value;
            const dateFrom = document.getElementById('dateFromFilter')?.value;
            const dateTo = document.getElementById('dateToFilter')?.value;

            const params = new URLSearchParams();
            if (role) params.append('role', role);
            if (status) params.append('status', status);
            if (gender) params.append('gender', gender);
            if (country) params.append('country', country);
            if (dateFrom) params.append('date_from', dateFrom);
            if (dateTo) params.append('date_to', dateTo);

            window.location.search = params.toString();
        }

        // Bulk actions functionality
        function selectAllUsers() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const selectAllCheckbox = document.getElementById('selectAllUsers');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            updateBulkActions();
        }

        function updateBulkActions() {
            const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
            const bulkActionsContainer = document.getElementById('bulkActions');

            if (selectedUsers.length > 0) {
                bulkActionsContainer.classList.remove('hidden');
                document.getElementById('selectedCount').textContent = selectedUsers.length;
            } else {
                bulkActionsContainer.classList.add('hidden');
            }
        }

        function bulkActivateUsers() {
            const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);

            if (selectedUsers.length === 0) {
                alert('Please select users to activate');
                return;
            }

            if (confirm(`Are you sure you want to activate ${selectedUsers.length} users?`)) {
                fetch('/api/users/bulk-activate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_ids: selectedUsers
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Error activating users');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error activating users');
                    });
            }
        }

        function bulkDeactivateUsers() {
            const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);

            if (selectedUsers.length === 0) {
                alert('Please select users to deactivate');
                return;
            }

            if (confirm(`Are you sure you want to deactivate ${selectedUsers.length} users?`)) {
                fetch('/api/users/bulk-deactivate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_ids: selectedUsers
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Error deactivating users');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deactivating users');
                    });
            }
        }

        function bulkDeleteUsers() {
            const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);

            if (selectedUsers.length === 0) {
                alert('Please select users to delete');
                return;
            }

            if (confirm(`Are you sure you want to delete ${selectedUsers.length} users? This action cannot be undone.`)) {
                fetch('/api/users/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            user_ids: selectedUsers
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Error deleting users');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error deleting users');
                    });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add date filter on load
            addDateFilter();

            // Initialize checkbox handlers
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            userCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });
        });
    </script>
@endsection
