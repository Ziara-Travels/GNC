@extends('layouts.admin')

@section('title', 'Manage Roles')

@section('content')
@php
    $prefix = request()->is('admin*') ? 'admin' : 'superadmin';
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="material-symbols-outlined me-2">admin_panel_settings</i>
                        Roles Management
                    </h4>
                    <a href="{{ route($prefix . '.roles.add') }}" class="btn btn-primary">
                        <i class="material-symbols-outlined me-1">add</i>
                        Add New Role
                    </a>
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
                                    <th>ID</th>
                                    <th>Role Name</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Type</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    </td>
                                    <td>{{ $role->display_name ?? $role->name }}</td>
                                    <td>
                                        {{ Str::limit($role->description ?? 'No description', 50) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $role->permissions_count ?? $role->permissions->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $role->users_count ?? $role->users->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($role->is_system_role)
                                            <span class="badge bg-warning">System</span>
                                        @else
                                            <span class="badge bg-secondary">Custom</span>
                                        @endif
                                    </td>
                                    <td>{{ $role->created_at ? $role->created_at->format('M d, Y') : '—' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route($prefix . '.roles.view', $role) }}"
                                               class="btn btn-outline-info" title="View">
                                                <i class="material-symbols-outlined">visibility</i>
                                            </a>
                                            <a href="{{ route($prefix . '.roles.edit', $role) }}"
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="material-symbols-outlined">edit</i>
                                            </a>
                                            @if(!$role->is_system_role && $role->users->count() == 0)
                                                <button type="button" class="btn btn-outline-danger"
                                                        data-url="{{ route($prefix . '.roles.destroy', $role) }}"
                                                        onclick="deleteRole(this)" title="Delete">
                                                    <i class="material-symbols-outlined">delete</i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="material-symbols-outlined text-muted" style="font-size: 48px;">admin_panel_settings</i>
                                        <p class="text-muted mt-2">No roles found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($roles->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $roles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this role? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <small><strong>Note:</strong> System roles and roles assigned to users cannot be deleted.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Role</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function deleteRole(trigger) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = trigger.dataset.url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush