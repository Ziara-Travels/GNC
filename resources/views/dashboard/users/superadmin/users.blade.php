{{-- filepath: resources/views/dashboard/users/index.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="content-header">
    <h1 class="content-title">User Management</h1>
    <p class="content-subtitle">Manage system users and their permissions</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">All Users</h5>
                    <small class="text-muted">Total: {{ $users->total() ?? 0 }} users</small>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Search users..." id="searchUsers">
                    <select class="form-select form-select-sm" id="roleFilter">
                        <option value="">All Roles</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i>Add User
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users ?? [] as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #8b5cf6); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                            @else
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="user-role role-{{ $user->role }}">
                                        @if($user->role === 'superadmin')
                                            Super Admin
                                        @elseif($user->role === 'admin')
                                            Administrator
                                        @else
                                            User
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="status-badge status-completed">Active</span>
                                    @else
                                        <span class="status-badge status-pending">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="viewUser({{ $user->id }})">
                                                    <i class="fas fa-eye me-2"></i>View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="editUser({{ $user->id }})">
                                                    <i class="fas fa-edit me-2"></i>Edit
                                                </a>
                                            </li>
                                            @if($user->id !== auth()->id())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteUser({{ $user->id }})">
                                                    <i class="fas fa-trash me-2"></i>Delete
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No users found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($users) && $users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            @can('manage-users')
                            <option value="superadmin">Super Admin</option>
                            @endcan
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Add User Form Submission
document.getElementById('addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/dashboard/users', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error adding user: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding user');
    });
});

// Search and Filter Functions
document.getElementById('searchUsers').addEventListener('input', function() {
    // Implement search functionality
    filterUsers();
});

document.getElementById('roleFilter').addEventListener('change', function() {
    filterUsers();
});

function filterUsers() {
    const search = document.getElementById('searchUsers').value;
    const role = document.getElementById('roleFilter').value;
    
    const url = new URL(window.location);
    
    if (search) {
        url.searchParams.set('search', search);
    } else {
        url.searchParams.delete('search');
    }
    
    if (role) {
        url.searchParams.set('role', role);
    } else {
        url.searchParams.delete('role');
    }
    
    window.location.href = url.toString();
}

function viewUser(userId) {
    // Implementation for viewing user details
    window.location.href = `/dashboard/users/${userId}`;
}

function editUser(userId) {
    // Implementation for editing user
    window.location.href = `/dashboard/users/${userId}/edit`;
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/dashboard/users/${userId}`, {
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
                alert('Error deleting user: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting user');
        });
    }
}
</script>
@endsection