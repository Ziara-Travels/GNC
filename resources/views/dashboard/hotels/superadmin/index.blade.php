@extends('layouts.main')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-gradient-success text-white p-4 mb-4 rounded">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">User Management</h2>
            <p class="mb-0">Manage your system users and their accounts</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="me-4 text-center">
                <h3 class="mb-0">{{ $users->total() ?? 0 }}</h3>
                <small>Total Users</small>
            </div>
            <div class="me-4 text-center">
                <h3 class="mb-0">{{ $activeUsers ?? 0 }}</h3>
                <small>Active</small>
            </div>
            <div class="text-center">
                <h3 class="mb-0">{{ $newUsers ?? 0 }}</h3>
                <small>New This Week</small>
            </div>
        </div>
    </div>
</div>

<!-- User Role Distribution -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3">User Role Distribution</h5>
    </div>
    
    <!-- Superadmins Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Superadmins</h5>
                    <div class="rounded-circle bg-primary-soft p-2">
                        <i class="fas fa-user-shield fa-2x text-primary"></i>
                    </div>
                </div>
                <h3 class="mb-2">{{ $superadminCount ?? 0 }}</h3>
                <p class="text-muted mb-3">Users with full system access</p>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" 
                        style="width: {{ (($superadminCount ?? 0) / ($users->total() ?? 1)) * 100 }}%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSuperadminModal">
                        <i class="fas fa-plus me-1"></i> Add Superadmin
                    </button>
                    <a href="{{ route('superadmin.users.filter', ['role' => 'superadmin']) }}" class="text-decoration-none">View All</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admins Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Admins</h5>
                    <div class="rounded-circle bg-info-soft p-2">
                        <i class="fas fa-user-tie fa-2x text-info"></i>
                    </div>
                </div>
                <h3 class="mb-2">{{ $adminCount ?? 0 }}</h3>
                <p class="text-muted mb-3">Users with administrative access</p>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-info" role="progressbar" 
                        style="width: {{ (($adminCount ?? 0) / ($users->total() ?? 1)) * 100 }}%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                        <i class="fas fa-plus me-1"></i> Add Admin
                    </button>
                    <a href="{{ route('superadmin.users.filter', ['role' => 'admin']) }}" class="text-decoration-none">View All</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Regular Users Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Users</h5>
                    <div class="rounded-circle bg-success-soft p-2">
                        <i class="fas fa-user fa-2x text-success"></i>
                    </div>
                </div>
                <h3 class="mb-2">{{ $regularUserCount ?? 0 }}</h3>
                <p class="text-muted mb-3">Regular users with standard access</p>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                        style="width: {{ (($regularUserCount ?? 0) / ($users->total() ?? 1)) * 100 }}%"></div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i> Add User
                    </button>
                    <a href="{{ route('superadmin.users.filter', ['role' => 'user']) }}" class="text-decoration-none">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <!-- Quick Stats Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success-soft p-3 me-3">
                    <i class="fas fa-users fa-2x text-success"></i>
                </div>
                <div>
                    <h6 class="mb-0">Verified Users</h6>
                    <h4 class="mb-0">{{ $verifiedUsers ?? 0 }}</h4>
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
                    <h6 class="mb-0">Pending Verification</h6>
                    <h4 class="mb-0">{{ $pendingUsers ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-info-soft p-3 me-3">
                    <i class="fas fa-plane-departure fa-2x text-info"></i>
                </div>
                <div>
                    <h6 class="mb-0">With Bookings</h6>
                    <h4 class="mb-0">{{ $usersWithBookings ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-danger-soft p-3 me-3">
                    <i class="fas fa-user-slash fa-2x text-danger"></i>
                </div>
                <div>
                    <h6 class="mb-0">Inactive Users</h6>
                    <h4 class="mb-0">{{ $inactiveUsers ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Activity Summary -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">User Activity Summary</h5>
                <div class="row">
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="d-flex flex-column align-items-center">
                            <div class="display-6 mb-1">{{ $onlineUsers ?? 0 }}</div>
                            <div class="text-muted text-center">Currently Online</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                        <div class="d-flex flex-column align-items-center">
                            <div class="display-6 mb-1">{{ $activeToday ?? 0 }}</div>
                            <div class="text-muted text-center">Active Today</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex flex-column align-items-center">
                            <div class="display-6 mb-1">{{ $activeWeek ?? 0 }}</div>
                            <div class="text-muted text-center">Active This Week</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex flex-column align-items-center">
                            <div class="display-6 mb-1">{{ $inactiveMonth ?? 0 }}</div>
                            <div class="text-muted text-center">Inactive > 30 Days</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users Registration Trend</h5>
                <div>
                    <select class="form-select form-select-sm" id="registrationPeriod">
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="userRegistrationChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">User Verification Status</h5>
            </div>
            <div class="card-body">
                <canvas id="userVerificationChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- User Role-based Charts -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">User Role Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="userRoleChart" height="260"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Active vs Inactive by Role</h5>
            </div>
            <div class="card-body">
                <canvas id="roleActivityChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">User Accounts</h5>
                    <small class="text-muted">Showing {{ $users->count() ?? 0 }} of {{ $users->total() ?? 0 }} users</small>
                </div>
                <div class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Search users..." id="searchUsers">
                    </div>
                    <select class="form-select" id="roleFilter" style="width: 150px;">
                        <option value="">All Roles</option>
                        <option value="superadmin">Superadmin</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <select class="form-select" id="statusFilter" style="width: 150px;">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <select class="form-select" id="bookingFilter" style="width: 180px;">
                        <option value="">All Booking Status</option>
                        <option value="with_bookings">With Bookings</option>
                        <option value="no_bookings">No Bookings</option>
                    </select>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i>Add User
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="bulkActionsDropdown" data-bs-toggle="dropdown">
                            Bulk Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bulkActionsDropdown">
                            <li><a class="dropdown-item" href="#" id="bulkVerify"><i class="fas fa-check-circle me-2"></i>Verify Selected</a></li>
                            <li><a class="dropdown-item" href="#" id="bulkDeactivate"><i class="fas fa-user-slash me-2"></i>Deactivate Selected</a></li>
                            <li><a class="dropdown-item" href="#" id="bulkActivate"><i class="fas fa-user-check me-2"></i>Activate Selected</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" id="bulkDelete"><i class="fas fa-trash me-2"></i>Delete Selected</a></li>
                        </ul>
                    </div>
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
                                <th>User</th>
                                <th>Role</th>
                                <th>Contact</th>
                                <th>Bookings</th>
                                <th>Joined</th>
                                <th>Last Login</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users ?? [] as $user)
                            <tr>
                                <td class="ps-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $user->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3 position-relative">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle" width="45" height="45">
                                            @else
                                                @php
                                                    $bgColors = [
                                                        'superadmin' => 'linear-gradient(45deg, #6a11cb, #2575fc)',
                                                        'admin' => 'linear-gradient(45deg, #0396FF, #0069ff)',
                                                        'user' => 'linear-gradient(45deg, #2cb978, #38ef7d)'
                                                    ];
                                                    $bg = $bgColors[$user->role ?? 'user'] ?? $bgColors['user'];
                                                @endphp
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                                    style="width: 45px; height: 45px; background: {{ $bg }}">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            
                                            @if($user->is_online)
                                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1" 
                                                      style="width: 10px; height: 10px; border: 2px solid white;"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                            <span class="text-muted small">{{ $user->username ?? 'No username' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $roleBadge = [
                                            'superadmin' => 'bg-primary',
                                            'admin' => 'bg-info',
                                            'user' => 'bg-success'
                                        ][$user->role ?? 'user'] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $roleBadge }}">{{ ucfirst($user->role ?? 'User') }}</span>
                                </td>
                                <td>
                                    <div>
                                        <div class="mb-1">{{ $user->email }}</div>
                                        <div class="small text-muted">{{ $user->phone ?? 'No phone' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-light text-dark me-2">{{ $user->bookings_count ?? 0 }}</span>
                                        @if(($user->bookings_count ?? 0) > 0)
                                            <a href="{{ route('superadmin.bookings.user', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                View Bookings
                                            </a>
                                        @else
                                            <span class="text-muted">No bookings</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        {{ $user->created_at->format('M d, Y') }}
                                        <div class="small text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->diffForHumans() }}
                                    @else
                                        <span class="text-muted small">Never logged in</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email_verified_at && $user->active)
                                        <span class="badge bg-success">Active</span>
                                    @elseif(!$user->email_verified_at)
                                        <span class="badge bg-warning text-dark">Unverified</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('superadmin.users.view', $user->id) }}">
                                                    <i class="fas fa-eye me-2"></i>View Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('superadmin.users.edit', $user->id) }}">
                                                    <i class="fas fa-edit me-2"></i>Edit Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('superadmin.bookings.user', ['user_id' => $user->id]) }}">
                                                    <i class="fas fa-ticket-alt me-2"></i>View Bookings
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('superadmin.activity.user', $user->id) }}">
                                                    <i class="fas fa-history me-2"></i>Activity Log
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="impersonateUser({{ $user->id }})">
                                                    <i class="fas fa-user-secret me-2"></i>Impersonate
                                                </a>
                                            </li>
                                            @if(!$user->email_verified_at)
                                            <li>
                                                <a class="dropdown-item text-success" href="#" onclick="verifyUser({{ $user->id }})">
                                                    <i class="fas fa-check-circle me-2"></i>Verify Email
                                                </a>
                                            </li>
                                            @endif
                                            <li>
                                                @if($user->active)
                                                <a class="dropdown-item text-warning" href="#" onclick="deactivateUser({{ $user->id }})">
                                                    <i class="fas fa-user-slash me-2"></i>Deactivate
                                                </a>
                                                @else
                                                <a class="dropdown-item text-success" href="#" onclick="activateUser({{ $user->id }})">
                                                    <i class="fas fa-user-check me-2"></i>Activate
                                                </a>
                                                @endif
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteUser({{ $user->id }})">
                                                    <i class="fas fa-trash me-2"></i>Delete Account
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5>No users found</h5>
                                        <p class="text-muted">No user accounts match your criteria</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                            <i class="fas fa-plus me-2"></i>Add First User
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($users) && $users->hasPages())
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Registrations</h5>
                <a href="{{ route('superadmin.users.recent') }}" class="text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentUsers ?? [] as $recentUser)
                    <div class="list-group-item d-flex align-items-center">
                        <div class="me-3">
                            @if($recentUser->avatar)
                                <img src="{{ $recentUser->avatar }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                            @else
                                @php
                                    $bgColors = [
                                        'superadmin' => 'linear-gradient(45deg, #6a11cb, #2575fc)',
                                        'admin' => 'linear-gradient(45deg, #0396FF, #0069ff)',
                                        'user' => 'linear-gradient(45deg, #2cb978, #38ef7d)'
                                    ];
                                    $bg = $bgColors[$recentUser->role ?? 'user'] ?? $bgColors['user'];
                                @endphp
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white" 
                                    style="width: 40px; height: 40px; background: {{ $bg }}">
                                    {{ strtoupper(substr($recentUser->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $recentUser->name }}</h6>
                            <div class="d-flex align-items-center small">
                                <span class="text-muted me-2">{{ $recentUser->email }}</span>
                                @php
                                    $roleBadge = [
                                        'superadmin' => 'bg-primary',
                                        'admin' => 'bg-info',
                                        'user' => 'bg-success'
                                    ][$recentUser->role ?? 'user'] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $roleBadge }} small">{{ ucfirst($recentUser->role ?? 'User') }}</span>
                            </div>
                        </div>
                        <div class="text-muted small">
                            {{ $recentUser->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        No recent registrations
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Distribution by Country</h5>
                <div>
                    <select class="form-select form-select-sm" id="countryMapPeriod">
                        <option value="all">All Time</option>
                        <option value="year" selected>This Year</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="userCountryMap" style="height: 300px;"></div>
            </div>
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
                        <label for="role" class="form-label">User Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="user">Regular User</option>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" name="country">
                            <option value="">Select Country</option>
                            <option value="KE">Kenya</option>
                            <option value="UG">Uganda</option>
                            <option value="TZ">Tanzania</option>
                            <option value="RW">Rwanda</option>
                            <option value="BI">Burundi</option>
                            <!-- Add more countries as needed -->
                        </select>
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
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="verify_email" name="verify_email" checked>
                        <label class="form-check-label" for="verify_email">Auto-verify email</label>
                    </div>
                    
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="welcome_email" name="welcome_email" checked>
                        <label class="form-check-label" for="welcome_email">Send welcome email</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Superadmin Modal -->
<div class="modal fade" id="addSuperadminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Superadmin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addSuperadminForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="superadmin_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="superadmin_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="superadmin_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="superadmin_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="superadmin_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="superadmin_phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="superadmin_password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="superadmin_password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleSuperadminPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="superadmin_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="superadmin_password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <input type="hidden" name="role" value="superadmin">
                    <input type="hidden" name="verify_email" value="1">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This user will be created with superadmin privileges and access to all system features.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>Create Superadmin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAdminForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="admin_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="admin_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="admin_phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="admin_permissions" class="form-label">Admin Permissions</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="perm_users" name="permissions[]" value="users">
                                    <label class="form-check-label" for="perm_users">User Management</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="perm_bookings" name="permissions[]" value="bookings" checked>
                                    <label class="form-check-label" for="perm_bookings">Booking Management</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="perm_content" name="permissions[]" value="content">
                                    <label class="form-check-label" for="perm_content">Content Management</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="perm_reports" name="permissions[]" value="reports">
                                    <label class="form-check-label" for="perm_reports">Reports Access</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="admin_password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleAdminPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="admin_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="admin_password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <input type="hidden" name="role" value="admin">
                    <input type="hidden" name="verify_email" value="1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>Create Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script>

<script>
// Initialize Charts
document.addEventListener('DOMContentLoaded', function() {
    // User Registration Chart
    const registrationCtx = document.getElementById('userRegistrationChart').getContext('2d');
    
    const registrationChart = new Chart(registrationCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'New Users',
                data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 70, 75, 90],
                backgroundColor: 'rgba(76, 175, 80, 0.5)',
                borderColor: '#4CAF50',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
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
    
    // User Verification Chart
    const verificationCtx = document.getElementById('userVerificationChart').getContext('2d');
    
    const verificationChart = new Chart(verificationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Verified', 'Pending', 'Inactive'],
            datasets: [{
                data: [{{ $verifiedUsers ?? 70 }}, {{ $pendingUsers ?? 20 }}, {{ $inactiveUsers ?? 10 }}],
                backgroundColor: [
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(244, 67, 54, 0.8)'
                ],
                borderColor: [
                    '#4CAF50',
                    '#FFC107',
                    '#F44336'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            cutout: '70%'
        }
    });
    
    // User Role Chart
    const roleCtx = document.getElementById('userRoleChart').getContext('2d');
    
    const roleChart = new Chart(roleCtx, {
        type: 'doughnut',
        data: {
            labels: ['Superadmins', 'Admins', 'Regular Users'],
            datasets: [{
                data: [{{ $superadminCount ?? 5 }}, {{ $adminCount ?? 15 }}, {{ $regularUserCount ?? 80 }}],
                backgroundColor: [
                    'rgba(123, 97, 255, 0.8)',
                    'rgba(33, 150, 243, 0.8)',
                    'rgba(76, 175, 80, 0.8)'
                ],
                borderColor: [
                    '#7b61ff',
                    '#2196F3',
                    '#4CAF50'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            cutout: '65%'
        }
    });
    
    // Role Activity Chart
    const roleActivityCtx = document.getElementById('roleActivityChart').getContext('2d');
    
    const roleActivityChart = new Chart(roleActivityCtx, {
        type: 'bar',
        data: {
            labels: ['Superadmins', 'Admins', 'Regular Users'],
            datasets: [
                {
                    label: 'Active',
                    data: [{{ $activeSuperadmins ?? 4 }}, {{ $activeAdmins ?? 12 }}, {{ $activeUsers - ($activeSuperadmins ?? 4) - ($activeAdmins ?? 12) }}],
                    backgroundColor: 'rgba(76, 175, 80, 0.7)',
                    borderColor: '#4CAF50',
                    borderWidth: 1
                },
                {
                    label: 'Inactive',
                    data: [{{ ($superadminCount ?? 5) - ($activeSuperadmins ?? 4) }}, {{ ($adminCount ?? 15) - ($activeAdmins ?? 12) }}, {{ ($regularUserCount ?? 80) - ($activeUsers - ($activeSuperadmins ?? 4) - ($activeAdmins ?? 12)) }}],
                    backgroundColor: 'rgba(244, 67, 54, 0.7)',
                    borderColor: '#F44336',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
    
    // User Country Map
    const mapData = {
        'KE': 100,
        'UG': 75,
        'TZ': 60,
        'RW': 40,
        'BI': 20,
        'US': 50,
        'GB': 45,
        'DE': 30,
        'FR': 25,
        'CA': 20
    };
    
    new jsVectorMap({
        selector: '#userCountryMap',
        map: 'world',
        zoomButtons: true,
        regionStyle: {
            initial: {
                fill: '#e9ecef'
            },
            hover: {
                fill: '#adb5bd'
            },
            selected: {
                fill: '#4CAF50'
            }
        },
        series: {
            regions: [{
                values: mapData,
                scale: ['#C8EEFF', '#0071A4'],
                normalizeFunction: 'polynomial'
            }]
        },
        onRegionTipShow: function(event, label, code) {
            label.html(
                label.html() + ' (Users: ' + (mapData[code] || 0) + ')'
            );
        }
    });
    
    // Update chart based on period selection
    document.getElementById('registrationPeriod')?.addEventListener('change', function() {
        const period = this.value;
        // Here you would fetch new data based on the selected period
        // For now, we'll just show a message
        console.log(`Fetching data for period: ${period}`);
    });
    
    document.getElementById('countryMapPeriod')?.addEventListener('change', function() {
        const period = this.value;
        // Here you would update the map data based on the selected period
        console.log(`Updating map data for period: ${period}`);
    });
});

// Add User Form Submission
document.getElementById('addUserForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/superadmin/users/add', {
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

// Add Superadmin Form Submission
document.getElementById('addSuperadminForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/superadmin/users/add', {
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
            alert('Error adding superadmin: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding superadmin');
    });
});

// Add Admin Form Submission
document.getElementById('addAdminForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/superadmin/users/add', {
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
            alert('Error adding admin: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding admin');
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

document.getElementById('toggleSuperadminPassword')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('superadmin_password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    // Toggle icon
    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
});

document.getElementById('toggleAdminPassword')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('admin_password');
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

// Bulk Actions
document.getElementById('bulkVerify')?.addEventListener('click', function(e) {
    e.preventDefault();
    bulkAction('verify', 'verify these users');
});

document.getElementById('bulkActivate')?.addEventListener('click', function(e) {
    e.preventDefault();
    bulkAction('activate', 'activate these users');
});

document.getElementById('bulkDeactivate')?.addEventListener('click', function(e) {
    e.preventDefault();
    bulkAction('deactivate', 'deactivate these users');
});

document.getElementById('bulkDelete')?.addEventListener('click', function(e) {
    e.preventDefault();
    bulkAction('delete', 'delete these users');
});

function bulkAction(action, confirmMessage) {
    const selectedUsers = Array.from(document.querySelectorAll('tbody .form-check-input:checked'))
        .map(checkbox => checkbox.value);
        
    if (selectedUsers.length === 0) {
        alert('Please select at least one user.');
        return;
    }
    
    if (confirm(`Are you sure you want to ${confirmMessage}?`)) {
        fetch(`/superadmin/users/bulk-${action}`, {
            method: 'POST',
            body: JSON.stringify({ users: selectedUsers }),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(`Error: ${data.message || 'Unknown error'}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(`Error performing bulk ${action}`);
        });
    }
}

// Search and Filter Functions
document.getElementById('searchUsers')?.addEventListener('input', function() {
    filterUsers();
});

document.getElementById('roleFilter')?.addEventListener('change', function() {
    filterUsers();
});

document.getElementById('statusFilter')?.addEventListener('change', function() {
    filterUsers();
});

document.getElementById('bookingFilter')?.addEventListener('change', function() {
    filterUsers();
});

function filterUsers() {
    const search = document.getElementById('searchUsers').value;
    const role = document.getElementById('roleFilter').value;
    const status = document.getElementById('statusFilter').value;
    const bookingStatus = document.getElementById('bookingFilter').value;
    const url = new URL(window.location.href);
    url.searchParams.set('search', search);
    url.searchParams.set('role', role);
    url.searchParams.set('status', status);
    url.searchParams.set('bookingStatus', bookingStatus);
    window.history.pushState({}, '', url);
    // Here you would typically make an AJAX request to fetch filtered users
    console.log(`Filtering users: search=${search}, role=${role}, status=${status}, bookingStatus=${bookingStatus}`);
}
</script>
@endsection