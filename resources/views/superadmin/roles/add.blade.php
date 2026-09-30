@extends('layouts.admin')

@section('title', 'Add New Role')

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
                        Add New Role
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

                    <form action="{{ route($prefix . '.roles.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="e.g., manager, editor" required>
                                    <div class="form-text">Use lowercase with underscores (e.g., content_manager)</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="display_name" class="form-label">Display Name</label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror"
                                           id="display_name" name="display_name" value="{{ old('display_name') }}"
                                           placeholder="e.g., Content Manager">
                                    <div class="form-text">Human-readable name for display</div>
                                    @error('display_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3"
                                      placeholder="Brief description of this role...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Assign Permissions</label>
                            <div class="row">
                                @php
                                    $groupedPermissions = $permissions->groupBy('group');
                                @endphp

                                @foreach($groupedPermissions as $group => $groupPermissions)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-header py-2">
                                            <h6 class="card-title mb-0">
                                                <input type="checkbox" class="form-check-input me-2 group-checkbox"
                                                       data-group="{{ Str::slug($group) }}">
                                                {{ $group }}
                                            </h6>
                                        </div>
                                        <div class="card-body py-2">
                                            @foreach($groupPermissions as $permission)
                                            <div class="form-check mb-1">
                                                <input class="form-check-input permission-checkbox"
                                                       type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       id="permission_{{ $permission->id }}"
                                                       data-group="{{ Str::slug($group) }}"
                                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->display_name }}
                                                    @if($permission->description)
                                                        <small class="text-muted d-block">{{ $permission->description }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route($prefix . '.roles.index') }}" class="btn btn-secondary">
                                <i class="material-symbols-outlined me-1">arrow_back</i>
                                Back to Roles
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="material-symbols-outlined me-1">save</i>
                                Create Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
        groupCheckbox.addEventListener('change', function() {
            const group = this.dataset.group;
            const groupPermissions = document.querySelectorAll(`[data-group="${group}"].permission-checkbox`);

            groupPermissions.forEach(permission => {
                permission.checked = this.checked;
            });
        });
    });

    document.querySelectorAll('.permission-checkbox').forEach(permissionCheckbox => {
        permissionCheckbox.addEventListener('change', function() {
            const group = this.dataset.group;
            const groupCheckbox = document.querySelector(`[data-group="${group}"].group-checkbox`);
            const groupPermissions = document.querySelectorAll(`[data-group="${group}"].permission-checkbox`);
            const checkedPermissions = document.querySelectorAll(`[data-group="${group}"].permission-checkbox:checked`);

            if (checkedPermissions.length === 0) {
                groupCheckbox.checked = false;
                groupCheckbox.indeterminate = false;
            } else if (checkedPermissions.length === groupPermissions.length) {
                groupCheckbox.checked = true;
                groupCheckbox.indeterminate = false;
            } else {
                groupCheckbox.checked = false;
                groupCheckbox.indeterminate = true;
            }
        });
    });

    document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
        const group = groupCheckbox.dataset.group;
        const groupPermissions = document.querySelectorAll(`[data-group="${group}"].permission-checkbox`);
        const checkedPermissions = document.querySelectorAll(`[data-group="${group}"].permission-checkbox:checked`);

        if (checkedPermissions.length === 0) {
            groupCheckbox.checked = false;
            groupCheckbox.indeterminate = false;
        } else if (checkedPermissions.length === groupPermissions.length) {
            groupCheckbox.checked = true;
            groupCheckbox.indeterminate = false;
        } else {
            groupCheckbox.checked = false;
            groupCheckbox.indeterminate = true;
        }
    });
});
</script>
@endpush