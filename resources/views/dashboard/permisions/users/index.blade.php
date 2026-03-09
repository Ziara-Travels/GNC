@extends('layouts.main')

@section('title', 'My Permissions')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-md-12 d-flex align-items-center gap-4">
            <div>
                <!-- User Avatar -->
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 90px; height: 90px;">
                    @if(Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle" style="width: 90px; height: 90px;">
                    @else
                        <span class="text-white fw-bold" style="font-size: 2.5rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <h2 class="mb-1">{{ Auth::user()->name }}</h2>
                <p class="text-muted mb-0">Your assigned permissions overview</p>
            </div>
        </div>
    </div>

    <!-- Permissions Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="material-symbols-outlined me-2">lock</i> My Permissions</h5>
                </div>
                <div class="card-body">
                    @php
                        $permissions = $roles->flatMap(function($role) {
                            return $role->permissions;
                        })->unique('id');
                    @endphp
                    @if($permissions->isEmpty())
                        <div class="text-center py-5">
                            <i class="material-symbols-outlined text-muted" style="font-size: 48px;">lock</i>
                            <p class="text-muted mt-2">You have no permissions assigned.</p>
                        </div>
                    @else
                        <ul class="list-group">
                            @foreach($permissions as $permission)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $permission->display_name ?? $permission->name }}
                                    <span class="badge bg-primary">{{ $permission->name }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Section -->
    @if($roles->isNotEmpty())
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="material-symbols-outlined me-2">admin_panel_settings</i> My Roles</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($roles as $role)
                        <div class="col-md-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                        <span class="ms-2">{{ $role->display_name ?? $role->name }}</span>
                                    </h5>
                                    <p class="card-text text-muted">{{ $role->description ?? 'No description' }}</p>
                                    <div class="mb-2">
                                        <span class="badge bg-info">Permissions: {{ $role->permissions_count ?? $role->permissions->count() }}</span>
                                    </div>
                                    <div>
                                        <span class="badge {{ $role->is_system_role ? 'bg-warning' : 'bg-secondary' }}">
                                            {{ $role->is_system_role ? 'System' : 'Custom' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <small class="text-muted">Assigned on {{ $role->pivot->created_at->format('M d, Y') ?? $role->created_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- User Info Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="material-symbols-outlined me-2">person</i> My Info</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Joined:</strong> {{ Auth::user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection