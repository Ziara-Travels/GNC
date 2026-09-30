@extends('layouts.admin')

@section('title', 'View Permission')

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
                        <i class="material-symbols-outlined me-2">visibility</i>
                        Permission Details
                    </h4>
                    <div class="btn-group" role="group">
                        @if($canManage)
                        <a href="{{ route($prefix . '.permissions.edit', $permission->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="material-symbols-outlined me-1" style="vertical-align:middle">edit</i>
                            Edit
                        </a>
                        @endif
                        <a href="{{ $canManage ? route($prefix . '.permissions.index') : route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="material-symbols-outlined me-1" style="vertical-align:middle">arrow_back</i>
                            Back
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Name</h6>
                            <p class="fs-5">
                                <span class="badge bg-primary">{{ $permission->name }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Display Name</h6>
                            <p class="fs-5">{{ $permission->display_name ?? $permission->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Group</h6>
                            <p class="fs-5">
                                @if($permission->group)
                                    <span class="badge bg-info">{{ $permission->group }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Type</h6>
                            <p class="fs-5">
                                @if($permission->is_system_permission)
                                    <span class="badge bg-warning">System Permission</span>
                                @else
                                    <span class="badge bg-secondary">Custom Permission</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Created</h6>
                            <p class="fs-5">{{ $permission->created_at ? $permission->created_at->format('M d, Y H:i') : '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase fw-bold">Guard</h6>
                            <p class="fs-5"><code>{{ $permission->guard_name }}</code></p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted text-uppercase fw-bold">Description</h6>
                        <p class="lead">{{ $permission->description ?? 'No description provided.' }}</p>
                    </div>

                    <div>
                        <h6 class="text-muted text-uppercase fw-bold">Roles with this permission</h6>
                        @forelse($permission->roles as $role)
                            <span class="badge bg-success me-1 mb-1" style="font-size:0.9rem">
                                {{ $role->display_name ?? $role->name }}
                            </span>
                        @empty
                            <p class="text-muted">No roles currently have this permission.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection