@extends('layouts.dashboard')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white p-4 mb-4 rounded">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center">
                <div>
                    <h2 class="mb-1 fw-bold">User Analytics Dashboard</h2>
                    <p class="mb-0 opacity-75">Comprehensive analysis and insights of all system users</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="d-flex justify-content-end">
                <div class="d-flex bg-white bg-opacity-25 rounded p-3">
                    <div class="me-4 text-center">
                        <h3 class="mb-0 fw-bold">{{ $users->total() ?? 0 }}</h3>
                        <small>Total Users</small>
                    </div>
                    <div class="me-4 text-center">
                        <h3 class="mb-0 fw-bold">{{ $activeUsers ?? 0 }}</h3>
                        <small>Active Users</small>
                    </div>
                    <div class="text-center">
                        <h3 class="mb-0 fw-bold">{{ $newUsers ?? 0 }}</h3>
                        <small>New This Week</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Role Distribution Cards -->
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
                    <div class="rounded-circle bg-primary-soft p-3">
                        <i class="fas fa-user-shield fa-2x text-primary"></i>
                    </div>
                </div>
                <h3 class="mb-2">{{ $superadminCount ?? 0 }}</h3>
                <p class="text-muted mb-3">System administrators with full access</p>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" 
                        style="width: {{ (($superadminCount ?? 0) / ($users->total() ?? 1)) * 100 }}%"></div>
                </div>
                <div class="text-muted small mt-2">
                    {{ round((($superadminCount ?? 0) / ($users->total() ?? 1)) * 100, 1) }}% of total users
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
                    <div class="rounded-circle bg-info-soft p-3">
                        <i class="fas fa-user-tie fa-2x text-info"></i>
                    </div>
                </div>
                <h3 class="mb-2">{{ $adminCount ?? 0 }}</h3>
                <p class="text-muted mb-3">Administrative users with limited access</p>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-info" role="progressbar" 
                        style="width: {{ (($adminCount ?? 0) / ($users->total() ?? 1)) * 100 }}%"></div>
                </div>
                <div class="text-muted small mt-2">
                    {{ round((($adminCount ?? 0) / ($users->total() ?? 1)) * 100, 1) }}% of total users
                </div>
            </div>
        </div>
    </div>
    
    <!-- Regular Users Card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Regular Users</h5>
                    <div class="rounded-circle bg-success-soft p-3">
                        <i class="fas fa-user fa-2x text-success"></i>
                    </div>
                </div>
                <h3 class="mb-2">{{ $regularUserCount ?? 0 }}</h3>
                <p class="text-muted mb-3">Standard users with basic permissions</p>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                        style="width: {{ (($regularUserCount ?? 0) / ($users->total() ?? 1)) * 100 }}%"></div>
                </div>
                <div class="text-muted small mt-2">
                    {{ round((($regularUserCount ?? 0) / ($users->total() ?? 1)) * 100, 1) }}% of total users
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Status & Activity -->
<div class="row mb-4">
    <!-- Quick Stats Cards -->
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success-soft p-3 me-3">
                    <i class="fas fa-user-check fa-2x text-success"></i>
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

<!-- User Activity Timeline -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Activity Summary</h5>
                <div>
                    <select class="form-select form-select-sm" id="activityPeriod">
                        <option value="day">Last 24 Hours</option>
                        <option value="week" selected>Last 7 Days</option>
                        <option value="month">Last 30 Days</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-6 mb-3 mb-md-0 text-center">
                        <div class="display-6 mb-1">{{ $onlineUsers ?? 0 }}</div>
                        <div class="text-muted">Currently Online</div>
                    </div>
                    <div class="col-md-3 col-6 mb-3 mb-md-0 text-center">
                        <div class="display-6 mb-1">{{ $activeToday ?? 0 }}</div>
                        <div class="text-muted">Active Today</div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="display-6 mb-1">{{ $activeWeek ?? 0 }}</div>
                        <div class="text-muted">Active This Week</div>
                    </div>
                    <div class="col-md-3 col-6 text-center">
                        <div class="display-6 mb-1">{{ $inactiveMonth ?? 0 }}</div>
                        <div class="text-muted">Inactive > 30 Days</div>
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
                <h5 class="mb-0">User Registration Trend</h5>
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

<!-- Demographics Analysis -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3">User Demographics Analysis</h5>
    </div>
    
    <!-- Gender Distribution -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Gender Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="genderChart" height="260"></canvas>
                <div class="d-flex justify-content-around mt-3">
                    <div class="text-center">
                        <div class="fw-bold text-primary">{{ $malePercentage ?? '48' }}%</div>
                        <div class="small text-muted">Male</div>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold text-danger">{{ $femalePercentage ?? '45' }}%</div>
                        <div class="small text-muted">Female</div>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold text-success">{{ $otherGenderPercentage ?? '7' }}%</div>
                        <div class="small text-muted">Other/Not Specified</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Age Distribution -->
    <div class="col-md-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Age Distribution</h5>
                <div>
                    <select class="form-select form-select-sm" id="ageRangeFilter">
                        <option value="all" selected>All Users</option>
                        <option value="active">Active Users Only</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="ageDistributionChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Location & Platform Analysis -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Users by Country</h5>
                <div>
                    <select class="form-select form-select-sm" id="countryMapPeriod">
                        <option value="all">All Time</option>
                        <option value="year" selected>This Year</option>
                        <option value="quarter">This Quarter</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="userCountryMap" style="height: 300px;"></div>
            </div>
            <div class="card-footer bg-transparent p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Top Countries</th>
                                <th class="text-end">Users</th>
                                <th class="text-end">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topCountries ?? [] as $country)
                            <tr>
                                <td>
                                    <span class="fi fi-{{ strtolower($country->country_code) }} me-2"></span>
                                    {{ $country->country_name }}
                                </td>
                                <td class="text-end">{{ $country->users_count }}</td>
                                <td class="text-end">{{ round($country->percentage, 1) }}%</td>
                            </tr>
                            @endforeach
                            @if(empty($topCountries))
                            <tr>
                                <td>
                                    <span class="fi fi-ke me-2"></span>
                                    Kenya
                                </td>
                                <td class="text-end">156</td>
                                <td class="text-end">31.2%</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fi fi-ug me-2"></span>
                                    Uganda
                                </td>
                                <td class="text-end">87</td>
                                <td class="text-end">17.4%</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fi fi-tz me-2"></span>
                                    Tanzania
                                </td>
                                <td class="text-end">64</td>
                                <td class="text-end">12.8%</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fi fi-us me-2"></span>
                                    United States
                                </td>
                                <td class="text-end">45</td>
                                <td class="text-end">9.0%</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="row h-100">
            <!-- Devices Chart -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Device & Platform Usage</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-center mb-3">Device Types</h6>
                                <canvas id="deviceChart" height="180"></canvas>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-center mb-3">Operating Systems</h6>
                                <canvas id="osChart" height="180"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity Time Chart -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">Activity by Time of Day</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="activityTimeChart" height="180"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Engagement & Retention -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Engagement Metrics</h5>
                <div>
                    <select class="form-select form-select-sm" id="engagementPeriod">
                        <option value="week">Last 7 Days</option>
                        <option value="month" selected>Last 30 Days</option>
                        <option value="quarter">Last 90 Days</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <canvas id="engagementChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">User Retention</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center mb-4">
                    <div class="position-relative" style="width: 200px; height: 200px;">
                        <canvas id="retentionChart"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle text-center">
                            <h3 class="mb-0">{{ $retentionRate ?? '68' }}%</h3>
                            <div class="text-muted small">30-day retention</div>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Cohort</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>7-day retention</td>
                                <td class="text-end">{{ $retention7day ?? '82' }}%</td>
                                <td class="text-end text-success">
                                    <i class="fas fa-caret-up me-1"></i>{{ $retention7dayChange ?? '3.2' }}%
                                </td>
                            </tr>
                            <tr>
                                <td>30-day retention</td>
                                <td class="text-end">{{ $retention30day ?? '68' }}%</td>
                                <td class="text-end text-success">
                                    <i class="fas fa-caret-up me-1"></i>{{ $retention30dayChange ?? '1.5' }}%
                                </td>
                            </tr>
                            <tr>
                                <td>90-day retention</td>
                                <td class="text-end">{{ $retention90day ?? '46' }}%</td>
                                <td class="text-end text-danger">
                                    <i class="fas fa-caret-down me-1"></i>{{ $retention90dayChange ?? '2.3' }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
                        <option value="user">Regular User</option>
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
                                                <a class="dropdown-item" href="{{ route('superadmin.users.edit', $user->id) }}">
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
                                                <a class="dropdown-item" href="{{ route('superadmin.users.activity-tracking', ['user' => $user->id]) }}">
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
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="user">Regular User</option>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="not_specified">Prefer not to say</option>
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
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
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

<!-- Export User Data Modal -->
<div class="modal fade" id="exportUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export User Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Export Format</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="formatCSV" value="csv" checked>
                            <label class="form-check-label" for="formatCSV">
                                CSV
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="formatExcel" value="excel">
                            <label class="form-check-label" for="formatExcel">
                                Excel
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exportFormat" id="formatPDF" value="pdf">
                            <label class="form-check-label" for="formatPDF">
                                PDF
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Data to Export</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportBasic" checked>
                        <label class="form-check-label" for="exportBasic">
                            Basic Information (Name, Email, Role, Status)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportContact" checked>
                        <label class="form-check-label" for="exportContact">
                            Contact Details (Phone, Address)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportBooking">
                        <label class="form-check-label" for="exportBooking">
                            Booking Information
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportActivity">
                        <label class="form-check-label" for="exportActivity">
                            Activity Data
                        </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Filter Users</label>
                    <select class="form-select mb-2" id="exportRole">
                        <option value="">All Roles</option>
                        <option value="superadmin">Superadmins Only</option>
                        <option value="admin">Admins Only</option>
                        <option value="user">Regular Users Only</option>
                    </select>
                    <select class="form-select" id="exportStatus">
                        <option value="">All Statuses</option>
                        <option value="active">Active Users Only</option>
                        <option value="inactive">Inactive Users Only</option>
                        <option value="pending">Pending Verification Only</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmExport">
                    <i class="fas fa-file-export me-1"></i>Export Data
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/css/flag-icons.min.css"></script>

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
                backgroundColor: 'rgba(76, 175, 80, 0.6)',
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
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        return (value * 100 / sum).toFixed(1) + "%";
                    },
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 12
                    }
                }
            },
            cutout: '70%'
        }
    });
    
    // Gender Distribution Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    
    const genderChart = new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female', 'Other/Not Specified'],
            datasets: [{
                data: [{{ $maleCount ?? 48 }}, {{ $femaleCount ?? 45 }}, {{ $otherGenderCount ?? 7 }}],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(75, 192, 192, 0.8)'
                ],
                borderColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)',
                    'rgb(75, 192, 192)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Age Distribution Chart
    const ageDistributionCtx = document.getElementById('ageDistributionChart').getContext('2d');
    
    const ageDistributionChart = new Chart(ageDistributionCtx, {
        type: 'bar',
        data: {
            labels: ['18-24', '25-34', '35-44', '45-54', '55-64', '65+'],
            datasets: [{
                label: 'Users',
                data: [{{ $age18to24 ?? 120 }}, {{ $age25to34 ?? 210 }}, {{ $age35to44 ?? 150 }}, {{ $age45to54 ?? 75 }}, {{ $age55to64 ?? 45 }}, {{ $age65plus ?? 25 }}],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
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
    
    // Device Type Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    
    const deviceChart = new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Mobile', 'Desktop', 'Tablet'],
            datasets: [{
                data: [{{ $mobileUsers ?? 58 }}, {{ $desktopUsers ?? 32 }}, {{ $tabletUsers ?? 10 }}],
                backgroundColor: [
                    'rgba(255, 159, 64, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
                ],
                borderColor: [
                    'rgb(255, 159, 64)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 206, 86)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${percentage}%`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
    
    // OS Chart
    const osCtx = document.getElementById('osChart').getContext('2d');
    
    const osChart = new Chart(osCtx, {
        type: 'doughnut',
        data: {
            labels: ['Android', 'iOS', 'Windows', 'macOS', 'Other'],
            datasets: [{
                data: [{{ $androidUsers ?? 38 }}, {{ $iosUsers ?? 32 }}, {{ $windowsUsers ?? 22 }}, {{ $macosUsers ?? 6 }}, {{ $otherOsUsers ?? 2 }}],
                backgroundColor: [
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(33, 150, 243, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(156, 39, 176, 0.8)',
                    'rgba(158, 158, 158, 0.8)'
                ],
                borderColor: [
                    'rgb(76, 175, 80)',
                    'rgb(33, 150, 243)',
                    'rgb(255, 193, 7)',
                    'rgb(156, 39, 176)',
                    'rgb(158, 158, 158)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${percentage}%`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
    
    // Activity Time Chart
    const activityTimeCtx = document.getElementById('activityTimeChart').getContext('2d');
    
    const activityTimeChart = new Chart(activityTimeCtx, {
        type: 'line',
        data: {
            labels: ['12am', '3am', '6am', '9am', '12pm', '3pm', '6pm', '9pm', '11pm'],
            datasets: [{
                label: 'User Activity',
                data: [5, 2, 3, 18, 25, 35, 28, 38, 15],
                fill: true,
                backgroundColor: 'rgba(33, 150, 243, 0.1)',
                borderColor: 'rgba(33, 150, 243, 0.8)',
                tension: 0.4,
                pointBackgroundColor: 'rgba(33, 150, 243, 1)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Active Users'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Time of Day'
                    }
                }
            }
        }
    });
    
    // Engagement Chart
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    
    const engagementChart = new Chart(engagementCtx, {
        type: 'bar',
        data: {
            labels: ['Login Frequency', 'Session Duration', 'Pages Viewed', 'Search Count', 'Booking Rate', 'Return Rate'],
            datasets: [
                {
                    label: 'Superadmin',
                    data: [9.2, 18.5, 22, 6.8, 1.2, 4.7],
                    backgroundColor: 'rgba(156, 39, 176, 0.6)',
                },
                {
                    label: 'Admin',
                    data: [7.8, 15.2, 18, 5.4, 0.9, 4.1],
                    backgroundColor: 'rgba(33, 150, 243, 0.6)',
                },
                {
                    label: 'Regular User',
                    data: [3.2, 8.5, 7, 3.2, 0.6, 2.8],
                    backgroundColor: 'rgba(76, 175, 80, 0.6)',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            let value = context.parsed.y || 0;
                            let unit = '';
                            
                            // Add units based on the metric
                            switch(context.dataIndex) {
                                case 0: // Login Frequency
                                    unit = ' logins/week';
                                    break;
                                case 1: // Session Duration
                                    unit = ' mins/session';
                                    break;
                                case 2: // Pages Viewed
                                    unit = ' pages/session';
                                    break;
                                case 3: // Search Count
                                    unit = ' searches/session';
                                    break;
                                case 4: // Booking Rate
                                    unit = ' bookings/week';
                                    break;
                                case 5: // Return Rate
                                    unit = ' visits/month';
                                    break;
                            }
                            
                            return `${label}: ${value}${unit}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Engagement Metrics'
                    }
                }
            }
        }
    });
    
    // User Retention Chart
    const retentionCtx = document.getElementById('retentionChart').getContext('2d');
    
    const retentionChart = new Chart(retentionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Retained', 'Lost'],
            datasets: [{
                data: [{{ $retentionRate ?? 68 }}, {{ 100 - ($retentionRate ?? 68) }}],
                backgroundColor: [
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(244, 67, 54, 0.8)'
                ],
                borderColor: [
                    'rgb(76, 175, 80)',
                    'rgb(244, 67, 54)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '75%'
        }
    });
    
    // User Country Map
    const mapData = {
        'KE': {{ $kenyaUsers ?? 156 }},
        'UG': {{ $ugandaUsers ?? 87 }},
        'TZ': {{ $tanzaniaUsers ?? 64 }},
        'RW': {{ $rwandaUsers ?? 40 }},
        'BI': {{ $burundiUsers ?? 20 }},
        'US': {{ $usUsers ?? 50 }},
        'GB': {{ $ukUsers ?? 45 }},
        'DE': {{ $germanyUsers ?? 30 }},
        'FR': {{ $franceUsers ?? 25 }},
        'CA': {{ $canadaUsers ?? 20 }}
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
        console.log(`Fetching registration data for period: ${period}`);
        // AJAX request to update chart data
    });
    
    document.getElementById('countryMapPeriod')?.addEventListener('change', function() {
        const period = this.value;
        // Here you would update the map data based on the selected period
        console.log(`Updating map data for period: ${period}`);
        // AJAX request to update map data
    });
    
    document.getElementById('ageRangeFilter')?.addEventListener('change', function() {
        const filter = this.value;
        console.log(`Updating age distribution for filter: ${filter}`);
        // AJAX request to update age distribution chart
    });
    
    document.getElementById('engagementPeriod')?.addEventListener('change', function() {
        const period = this.value;
        console.log(`Updating engagement data for period: ${period}`);
        // AJAX request to update engagement chart
    });
    document.getElementById('retentionPeriod')?.addEventListener('change', function() {
        const period = this.value;
        console.log(`Updating retention data for period: ${period}`);
        // AJAX request to update retention chart
    });

    // Add functions for user actions
    function impersonateUser(userId) {
        if (confirm('Are you sure you want to impersonate this user?')) {
            window.location.href = `/superadmin/users/${userId}/impersonate`;
        }
    }

    function verifyUser(userId) {
        if (confirm('Are you sure you want to verify this user\'s email?')) {
            fetch(`/superadmin/users/${userId}/verify`, {
                method: 'POST',
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
                alert('Error verifying user');
            });
        }
    }

    function activateUser(userId) {
        if (confirm('Are you sure you want to activate this user?')) {
            fetch(`/superadmin/users/${userId}/activate`, {
                method: 'POST',
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
                alert('Error activating user');
            });
        }
    }

    function deactivateUser(userId) {
        if (confirm('Are you sure you want to deactivate this user?')) {
            fetch(`/superadmin/users/${userId}/deactivate`, {
                method: 'POST',
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
                alert('Error deactivating user');
            });
        }
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            fetch(`/superadmin/users/${userId}/delete`, {
                method: 'DELETE',
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
                alert('Error deleting user');
            });
        }
    }

    // Export functionality
    document.getElementById('confirmExport')?.addEventListener('click', function() {
        const format = document.querySelector('input[name="exportFormat"]:checked').value;
        const exportBasic = document.getElementById('exportBasic').checked;
        const exportContact = document.getElementById('exportContact').checked;
        const exportBooking = document.getElementById('exportBooking').checked;
        const exportActivity = document.getElementById('exportActivity').checked;
        const exportRole = document.getElementById('exportRole').value;
        const exportStatus = document.getElementById('exportStatus').value;
        
        if (!exportBasic && !exportContact && !exportBooking && !exportActivity) {
            alert('Please select at least one data type to export');
            return;
        }
        
        const params = new URLSearchParams();
        params.append('format', format);
        params.append('exportBasic', exportBasic);
        params.append('exportContact', exportContact);
        params.append('exportBooking', exportBooking);
        params.append('exportActivity', exportActivity);
        if (exportRole) params.append('role', exportRole);
        if (exportStatus) params.append('status', exportStatus);
        
        window.location.href = `/superadmin/users/export?${params.toString()}`;
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('exportUserModal'));
        modal.hide();
    });
    
    // Activity period change
    document.getElementById('activityPeriod')?.addEventListener('change', function() {
        const period = this.value;
        console.log(`Updating activity summary for period: ${period}`);
        // AJAX request to update activity data
        fetch(`/superadmin/users/activity-summary?period=${period}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the values in the UI
                document.querySelector('.col-md-3:nth-child(1) .display-6').textContent = data.onlineUsers;
                document.querySelector('.col-md-3:nth-child(2) .display-6').textContent = data.activeToday;
                document.querySelector('.col-md-3:nth-child(3) .display-6').textContent = data.activeWeek;
                document.querySelector('.col-md-3:nth-child(4) .display-6').textContent = data.inactiveMonth;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Filter functionality
    function filterUsers() {
        const search = document.getElementById('searchUsers').value;
        const role = document.getElementById('roleFilter').value;
        const status = document.getElementById('statusFilter').value;
        const bookingStatus = document.getElementById('bookingFilter').value;
        
        // Create URL with query parameters
        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        if (role) url.searchParams.set('role', role);
        else url.searchParams.delete('role');
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        if (bookingStatus) url.searchParams.set('bookingStatus', bookingStatus);
        else url.searchParams.delete('bookingStatus');
        
        // Navigate to filtered URL
        window.location.href = url.toString();
    }

    // Attach event listeners to filter inputs
    document.getElementById('searchUsers')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            filterUsers();
            e.preventDefault();
        }
    });

    document.getElementById('roleFilter')?.addEventListener('change', filterUsers);
    document.getElementById('statusFilter')?.addEventListener('change', filterUsers);
    document.getElementById('bookingFilter')?.addEventListener('change', filterUsers);
});

</script>

    