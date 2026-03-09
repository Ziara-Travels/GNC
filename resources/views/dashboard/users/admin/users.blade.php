{{-- filepath: c:\xampp\htdocs\GNC\resources\views\dashboard\users\admin\admins.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white p-4 mb-4 rounded">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">Admin Management</h2>
            <p class="mb-0">Manage administrator accounts and permissions</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="me-4 text-center">
                <h3 class="mb-0">{{ $admins->total() ?? 0 }}</h3>
                <small>Total Admins</small>
            </div>
            <div class="me-4 text-center">
                <h3 class="mb-0">{{ $activeAdmins ?? 0 }}</h3>
                <small>Active</small>
            </div>
            <div class="text-center">
                <h3 class="mb-0">{{ $inactiveAdmins ?? 0 }}</h3>
                <small>Inactive</small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Quick Stats Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-primary-soft p-3 me-3">
                    <i class="fas fa-user-shield fa-2x text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-0">System Admins</h6>
                    <h4 class="mb-0">{{ $systemAdmins ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success-soft p-3 me-3">
                    <i class="fas fa-user-check fa-2x text-success"></i>
                </div>
                <div>
                    <h6 class="mb-0">Content Admins</h6>
                    <h4 class="mb-0">{{ $contentAdmins ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-info-soft p-3 me-3">
                    <i class="fas fa-user-cog fa-2x text-info"></i>
                </div>
                <div>
                    <h6 class="mb-0">Support Admins</h6>
                    <h4 class="mb-0">{{ $supportAdmins ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-warning-soft p-3 me-3">
                    <i class="fas fa-user-clock fa-2x text-warning"></i>
                </div>
                <div>
                    <h6 class="mb-0">New This Month</h6>
                    <h4 class="mb-0">{{ $newAdminsThisMonth ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Administrator Accounts</h5>
                    <small class="text-muted">Showing {{ $admins->count() ?? 0 }} of {{ $admins->total() ?? 0 }} administrators</small>
                </div>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search administrators..." id="searchAdmins">
                    </div>
                    <select class="form-select" id="adminTypeFilter" style="width: 150px;">
                        <option value="">All Types</option>
                        <option value="system">System</option>
                        <option value="content">Content</option>
                        <option value="support">Support</option>
                    </select>
                    <select class="form-select" id="statusFilter" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                        <i class="fas fa-plus me-1"></i>Add Admin
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th>Admin</th>
                                <th>Contact</th>
                                <th>Admin Type</th>
                                <th>Last Active</th>
                                <th>Status</th>
                                <th>Permissions</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins ?? [] as $admin)
                            <tr>
                                <td class="ps-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $admin->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="admin-avatar me-3">
                                            @if($admin->avatar)
                                                <img src="{{ $admin->avatar }}" alt="Avatar" class="rounded-circle" width="45" height="45">
                                            @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                                    style="width: 45px; height: 45px; background: linear-gradient(45deg, #4e73df, #224abe);">
                                                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            
                                            @if($admin->is_online)
                                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1" 
                                                      style="width: 10px; height: 10px; border: 2px solid white;"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $admin->name }}</h6>
                                            <span class="text-muted small">{{ $admin->department ?? 'General' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="mb-1">{{ $admin->email }}</div>
                                        <div class="small text-muted">{{ $admin->phone ?? 'No phone' }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($admin->admin_type === 'system')
                                        <span class="badge bg-primary-soft text-primary">System Admin</span>
                                    @elseif($admin->admin_type === 'content')
                                        <span class="badge bg-success-soft text-success">Content Admin</span>
                                    @elseif($admin->admin_type === 'support')
                                        <span class="badge bg-info-soft text-info">Support Admin</span>
                                    @else
                                        <span class="badge bg-secondary-soft text-secondary">General Admin</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $admin->last_active_at ? $admin->last_active_at->diffForHumans() : 'Never' }}
                                </td>
                                <td>
                                    @if($admin->email_verified_at && $admin->active)
                                        <span class="badge bg-success">Active</span>
                                    @elseif(!$admin->email_verified_at)
                                        <span class="badge bg-warning text-dark">Unverified</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        @php $permissions = $admin->permissions()->take(3)->get(); @endphp
                                        @forelse($permissions as $permission)
                                            <span class="badge bg-light text-dark me-1">{{ $permission->name }}</span>
                                        @empty
                                            <span class="text-muted small">No special permissions</span>
                                        @endforelse
                                        
                                        @if($admin->permissions()->count() > 3)
                                            <span class="badge bg-secondary ms-1">+{{ $admin->permissions()->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.view', $admin->id) }}">
                                                    <i class="fas fa-eye me-2"></i>View Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.edit', $admin->id) }}">
                                                    <i class="fas fa-edit me-2"></i>Edit Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.permissions', $admin->id) }}">
                                                    <i class="fas fa-key me-2"></i>Manage Permissions
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.activity.user', $admin->id) }}">
                                                    <i class="fas fa-history me-2"></i>Activity Log
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="impersonateUser({{ $admin->id }})">
                                                    <i class="fas fa-user-secret me-2"></i>Impersonate
                                                </a>
                                            </li>
                                            @if($admin->id !== auth()->id())
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                @if($admin->active)
                                                <a class="dropdown-item text-warning" href="#" onclick="deactivateAdmin({{ $admin->id }})">
                                                    <i class="fas fa-user-slash me-2"></i>Deactivate
                                                </a>
                                                @else
                                                <a class="dropdown-item text-success" href="#" onclick="activateAdmin({{ $admin->id }})">
                                                    <i class="fas fa-user-check me-2"></i>Activate
                                                </a>
                                                @endif
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteAdmin({{ $admin->id }})">
                                                    <i class="fas fa-trash me-2"></i>Delete Account
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-user-shield fa-3x text-muted mb-3"></i>
                                        <h5>No administrators found</h5>
                                        <p class="text-muted">No administrator accounts match your criteria</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                            <i class="fas fa-plus me-2"></i>Add First Admin
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($admins) && $admins->hasPages())
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    Showing {{ $admins->firstItem() }} to {{ $admins->lastItem() }} of {{ $admins->total() }} administrators
                </div>
                {{ $admins->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Admin Activity Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="adminActivityChart" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Admin Signups</h5>
                <a href="#" class="text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentAdmins ?? [] as $recentAdmin)
                    <div class="list-group-item d-flex align-items-center">
                        <div class="me-3">
                            @if($recentAdmin->avatar)
                                <img src="{{ $recentAdmin->avatar }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                    style="width: 40px; height: 40px; background: linear-gradient(45deg, #4e73df, #224abe);">
                                    {{ strtoupper(substr($recentAdmin->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $recentAdmin->name }}</h6>
                            <div class="small text-muted">{{ $recentAdmin->email }}</div>
                        </div>
                        <div class="text-muted small">
                            {{ $recentAdmin->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        No recent signups
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Administrator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAdminForm">
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
                        <label for="admin_type" class="form-label">Admin Type</label>
                        <select class="form-select" id="admin_type" name="admin_type" required>
                            <option value="system">System Admin</option>
                            <option value="content">Content Admin</option>
                            <option value="support">Support Admin</option>
                            <option value="general">General Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label">Admin Permissions</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="perm_manage_users" name="permissions[]" value="manage_users">
                                    <label class="form-check-label" for="perm_manage_users">Manage Users</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="perm_manage_bookings" name="permissions[]" value="manage_bookings">
                                    <label class="form-check-label" for="perm_manage_bookings">Manage Bookings</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="perm_manage_flights" name="permissions[]" value="manage_flights">
                                    <label class="form-check-label" for="perm_manage_flights">Manage Flights</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="perm_manage_hotels" name="permissions[]" value="manage_hotels">
                                    <label class="form-check-label" for="perm_manage_hotels">Manage Hotels</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="perm_manage_payments" name="permissions[]" value="manage_payments">
                                    <label class="form-check-label" for="perm_manage_payments">Manage Payments</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="perm_access_reports" name="permissions[]" value="access_reports">
                                    <label class="form-check-label" for="perm_access_reports">Access Reports</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>Create Administrator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize Admin Activity Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('adminActivityChart').getContext('2d');
    
    const activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'System Admins',
                data: [65, 59, 80, 81, 56, 55, 40],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#4e73df',
                tension: 0.3,
                fill: true
            }, {
                label: 'Content Admins',
                data: [28, 48, 40, 19, 86, 27, 90],
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28, 200, 138, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#1cc88a',
                tension: 0.3,
                fill: true
            }, {
                label: 'Support Admins',
                data: [18, 28, 30, 29, 46, 37, 30],
                borderColor: '#36b9cc',
                backgroundColor: 'rgba(54, 185, 204, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#36b9cc',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Monthly Admin Activity'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});

// Add Admin Form Submission
document.getElementById('addAdminForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/users/add-admin', {
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
            alert('Error adding administrator: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding administrator');
    });
});

// Toggle Password Visibility
document.getElementById('togglePassword')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Toggle icon
    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
});

// Select All Checkboxes
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('tbody .form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Search and Filter Functions
document.getElementById('searchAdmins')?.addEventListener('input', function() {
    filterAdmins();
});

document.getElementById('adminTypeFilter')?.addEventListener('change', function() {
    filterAdmins();
});

document.getElementById('statusFilter')?.addEventListener('change', function() {
    filterAdmins();
});

function filterAdmins() {
    const search = document.getElementById('searchAdmins').value;
    const type = document.getElementById('adminTypeFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    const url = new URL(window.location);
    
    if (search) {
        url.searchParams.set('search', search);
    } else {
        url.searchParams.delete('search');
    }
    
    if (type) {
        url.searchParams.set('type', type);
    } else {
        url.searchParams.delete('type');
    }
    
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    window.location.href = url.toString();
}

function impersonateUser(userId) {
    if (confirm('Are you sure you want to impersonate this administrator?')) {
        fetch(`/admin/users/impersonate/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/dashboard';
            } else {
                alert('Error impersonating user: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error impersonating user');
        });
    }
}

function activateAdmin(userId) {
    if (confirm('Are you sure you want to activate this administrator?')) {
        fetch(`/admin/users/${userId}/activate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error activating administrator: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error activating administrator');
        });
    }
}

function deactivateAdmin(userId) {
    if (confirm('Are you sure you want to deactivate this administrator?')) {
        fetch(`/admin/users/${userId}/deactivate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deactivating administrator: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deactivating administrator');
        });
    }
}

function deleteAdmin(userId) {
    if (confirm('Are you sure you want to delete this administrator? This action cannot be undone.')) {
        fetch(`/admin/users/${userId}`, {
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
                alert('Error deleting administrator: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting administrator');
        });
    }
}
</script>
@endsection