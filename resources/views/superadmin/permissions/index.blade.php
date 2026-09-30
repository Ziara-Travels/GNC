@extends('layouts.admin')

@section('title', 'Manage Permissions')

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
                        <i class="material-symbols-outlined me-2">security</i>
                        Permissions Management
                    </h4>
                    <a href="{{ route($prefix . '.permissions.add') }}" class="btn btn-primary">
                        <i class="material-symbols-outlined me-1">add</i>
                        Add New Permission
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

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="groupFilter">
                                <option value="">All Groups</option>
                                @foreach($permissions->pluck('group')->filter()->unique()->sort() as $group)
                                    <option value="{{ $group }}">{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search permissions...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="permissionsTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Group</th>
                                    <th>Description</th>
                                    <th>Roles</th>
                                    <th>Type</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td><span class="badge bg-primary">{{ $permission->name }}</span></td>
                                    <td>{{ $permission->display_name ?? $permission->name }}</td>
                                    <td>
                                        @if($permission->group)
                                            <span class="badge bg-info">{{ $permission->group }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($permission->description ?? 'No description', 50) }}</td>
                                    <td><span class="badge bg-success">{{ $permission->roles_count ?? $permission->roles->count() }}</span></td>
                                    <td>
                                        @if($permission->is_system_permission)
                                            <span class="badge bg-warning">System</span>
                                        @else
                                            <span class="badge bg-secondary">Custom</span>
                                        @endif
                                    </td>
                                    <td>{{ $permission->created_at ? $permission->created_at->format('M d, Y') : '—' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route($prefix . '.permissions.view', $permission->id) }}"
                                               class="btn btn-outline-info" title="View">
                                                <i class="material-symbols-outlined" style="font-size:17px">visibility</i>
                                            </a>
                                            <a href="{{ route($prefix . '.permissions.edit', $permission->id) }}"
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="material-symbols-outlined" style="font-size:17px">edit</i>
                                            </a>
                                            @if(!$permission->is_system_permission)
                                                <button type="button" class="btn btn-outline-danger"
                                                        data-url="{{ route($prefix . '.permissions.destroy', $permission->id) }}"
                                                        onclick="deletePermission(this)" title="Delete">
                                                    <i class="material-symbols-outlined" style="font-size:17px">delete</i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="material-symbols-outlined text-muted" style="font-size:48px">security</i>
                                        <p class="text-muted mt-2">No permissions found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($permissions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $permissions->links() }}
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
                <p>Are you sure you want to delete this permission? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <small><strong>Note:</strong> System permissions cannot be deleted.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Permission</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deletePermission(trigger) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = trigger.dataset.url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const groupFilter = document.getElementById('groupFilter');

    function filterTable() {
        const term = (searchInput?.value || '').toLowerCase();
        const group = groupFilter?.value || '';
        document.querySelectorAll('#permissionsTable tbody tr').forEach(function (row) {
            const groupCell = row.querySelectorAll('td')[3]?.textContent.trim() || '';
            const text = row.textContent.toLowerCase();
            row.style.display =
                text.includes(term) && (!group || groupCell === group) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (groupFilter) groupFilter.addEventListener('change', filterTable);
});
</script>
@endpush