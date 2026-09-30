@extends('layouts.admin')

@section('title', 'Add New Permission')

@section('content')
@php
    $prefix = request()->is('admin*') ? 'admin' : 'superadmin';
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="material-symbols-outlined me-2">add</i>
                        Add New Permission
                    </h4>
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

                    <form action="{{ route($prefix . '.permissions.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Permission Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="e.g., manage_flights" required>
                                    <div class="form-text">Use lowercase with underscores (e.g., view_users)</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_name" class="form-label">Display Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                           id="display_name" name="display_name" value="{{ old('display_name') }}"
                                           placeholder="e.g., View Users" required>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="group" class="form-label">Group <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('group') is-invalid @enderror"
                                   id="group" name="group" value="{{ old('group') }}"
                                   placeholder="e.g., User Management" required>
                            <div class="form-text">Used to group permissions in role assignment forms</div>
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Brief description of this permission...">{{ old('description') }}</textarea>
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
                                               {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
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
                                Back to Permissions
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-outlined me-1">save</i>
                                Create Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection