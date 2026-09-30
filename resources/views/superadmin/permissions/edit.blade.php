@extends('layouts.admin')

@section('title', 'Edit Permission')

@section('content')
@php
    $prefix = request()->is('admin*') ? 'admin' : 'superadmin';
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="material-symbols-outlined me-2">edit</i>
                        Edit Permission: <span class="badge bg-primary">{{ $permission->name }}</span>
                    </h4>
                    <a href="{{ route($prefix . '.permissions.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="material-symbols-outlined me-1" style="vertical-align:middle">arrow_back</i>
                        Back to Permissions
                    </a>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($permission->is_system_permission)
                        <div class="alert alert-warning">
                            <strong><i class="material-symbols-outlined me-1" style="vertical-align:middle">warning</i>System Permission</strong>
                            <br>This is a system permission. The name cannot be changed.
                        </div>
                    @endif

                    <form action="{{ route($prefix . '.permissions.update', $permission->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        @php
                            $permissionRoleIds = $permission->roles->pluck('id')->all();
                        @endphp

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $permission->name) }}"
                                           {{ $permission->is_system_permission ? 'readonly' : '' }} required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                           id="display_name" name="display_name"
                                           value="{{ old('display_name', $permission->display_name) }}" required>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="group" class="form-label">Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('group') is-invalid @enderror"
                                   id="group" name="group" value="{{ old('group', $permission->group) }}"
                                   placeholder="e.g., User Management" required>
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Brief description...">{{ old('description', $permission->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Assign to Roles</label>
                            <div class="row">
                                @forelse($roles as $role)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="roles[]" value="{{ $role->id }}"
                                               id="role_{{ $role->id }}"
                                               {{ in_array($role->id, old('roles', $permissionRoleIds)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role_{{ $role->id }}">
                                            {{ $role->display_name ?? $role->name }}
                                        </label>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <p class="text-muted mb-0">No roles created yet.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route($prefix . '.permissions.index') }}" class="btn btn-secondary">
                                <i class="material-symbols-outlined me-1">arrow_back</i>
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-outlined me-1">save</i>
                                Update Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection