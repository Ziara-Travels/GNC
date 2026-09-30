@extends('layouts.admin')

@section('title', 'All Roles & Permissions')

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
                        <i class="material-symbols-outlined me-2">list_alt</i>
                        All Roles &amp; Permissions
                    </h4>
                    @if($canManage)
                    <a href="{{ route($prefix . '.roles.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="material-symbols-outlined me-1" style="vertical-align:middle">arrow_back</i>
                        Back to Roles
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td style="width: 220px;">
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    </td>
                                    <td>
                                        @forelse($role->permissions as $permission)
                                            <span class="badge bg-info me-1 mb-1">{{ $permission->display_name ?? $permission->name }}</span>
                                        @empty
                                            <span class="text-muted">No permissions assigned</span>
                                        @endforelse
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <i class="material-symbols-outlined text-muted" style="font-size:48px">admin_panel_settings</i>
                                        <p class="text-muted mt-2">No roles found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection