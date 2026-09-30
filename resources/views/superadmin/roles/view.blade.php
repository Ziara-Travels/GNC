@extends('layouts.admin')

@section('title', 'View Role')

@section('content')
@php
    $prefix = request()->is('admin*') ? 'admin' : (request()->is('superadmin*') ? 'superadmin' : 'user');
    $canManage = $prefix !== 'user';
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="material-symbols-outlined me-2">admin_panel_settings</i>
                        Role Details
                    </h4>
                    <div class="btn-group" role="group">
                        @if($canManage)
                        <a href="{{ route($prefix . '.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                            <i class="material-symbols-outlined me-1" style="vertical-align:middle">edit</i>
                            Edit
                        </a>
                        @endif
                        <a href="{{ $canManage ? route($prefix . '.roles.index') : route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="material-symbols-outlined me-1" style="vertical-align:middle">arrow_back</i>
                            Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Role Name</h6>
                            <p class="fs-5"><span class="badge bg-primary">{{ $role->name }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Display Name</h6>
                            <p class="fs-5">{{ $role->display_name ?? $role->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Type</h6>
                            <p class="fs-5">
                                @if($role->is_system_role)
                                    <span class="badge bg-warning">System Role</span>
                                @else
                                    <span class="badge bg-secondary">Custom Role</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Created</h6>
                            <p class="fs-5">{{ $role->created_at ? $role->created_at->format('M d, Y H:i') : '—' }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fw-bold">Description</h6>
                        <p class="lead">{{ $role->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fw-bold">
                            Permissions ({{ $role->permissions->count() }})
                        </h6>
                        @forelse($role->permissions->groupBy('group') as $group => $permissions)
                            <h6 class="mt-3">
                                <span class="badge bg-info">{{ $group }}</span>
                            </h6>
                            <div>
                                @foreach($permissions as $permission)
                                    <span class="badge bg-primary me-1 mb-1" style="font-size:0.9rem">
                                        {{ $permission->display_name ?? $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        @empty
                            <p class="text-muted">No permissions assigned to this role.</p>
                        @endforelse
                    </div>

                    <div>
                        <h6 class="text-muted text-uppercase fw-bold">Users with this role ({{ $role->users->count() }})</h6>
                        @if($role->users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role->users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p class="text-muted">No users currently have this role.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection