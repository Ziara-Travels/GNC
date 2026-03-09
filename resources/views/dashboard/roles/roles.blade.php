@extends('layouts.main')

@section('title', 'All Roles & Assignments')

@section('content')
<!-- Hero Section -->
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-primary text-white rounded p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="material-symbols-outlined me-2" style="font-size: 2rem;">admin_panel_settings</i>
                        All Roles & Assignments
                    </h2>
                    <p class="mb-0">View all roles created and assigned to users, with analytics and management tools.</p>
                </div>
                <div>
                    <a href="{{ route('superadmin.roles.create') }}" class="btn btn-light btn-lg">
                        <i class="material-symbols-outlined me-1">add</i>
                        Add New Role
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="container-fluid mb-4">
    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="material-symbols-outlined text-primary mb-2" style="font-size: 2rem;">groups</i>
                    <h4 class="mb-0">{{ $totalRoles }}</h4>
                    <small class="text-muted">Total Roles</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="material-symbols-outlined text-success mb-2" style="font-size: 2rem;">person</i>
                    <h4 class="mb-0">{{ $totalUsers }}</h4>
                    <small class="text-muted">Total Users</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="material-symbols-outlined text-info mb-2" style="font-size: 2rem;">verified</i>
                    <h4 class="mb-0">{{ $assignedRoles }}</h4>
                    <small class="text-muted">Assigned Roles</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="material-symbols-outlined text-warning mb-2" style="font-size: 2rem;">lock</i>
                    <h4 class="mb-0">{{ $systemRoles }}</h4>
                    <small class="text-muted">System Roles</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="container-fluid mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="material-symbols-outlined me-2">bar_chart</i> Roles Assignment Analytics</h5>
        </div>
        <div class="card-body">
            <canvas id="rolesChart" height="80"></canvas>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="container-fluid mb-4">
    <div class="card">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
            <div>
                <label class="fw-semibold me-2">Type:</label>
                <select id="typeFilter" class="form-select">
                    <option value="all">All</option>
                    <option value="system">System</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div>
                <label class="fw-semibold me-2">Search:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by role name, user...">
            </div>
            <div>
                <button id="exportCsv" class="btn btn-outline-primary">
                    <i class="material-symbols-outlined me-1">file_download</i>
                    Export CSV
                </button>
                <button id="exportExcel" class="btn btn-outline-success">
                    <i class="material-symbols-outlined me-1">file_download</i>
                    Export Excel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Roles Table Section -->
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="material-symbols-outlined me-2">table</i> All Roles & Assignments</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover" id="rolesTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Permissions</th>
                        <th>Assigned Users</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $role->name }}</span>
                        </td>
                        <td>{{ Str::limit($role->description ?? 'No description', 50) }}</td>
                        <td>
                            @if($role->is_system_role)
                                <span class="badge bg-warning">System</span>
                            @else
                                <span class="badge bg-secondary">Custom</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $role->permissions_count ?? $role->permissions->count() }}</span>
                        </td>
                        <td>
                            @if($role->users->count())
                                <span class="badge bg-success">{{ $role->users->count() }}</span>
                                <span class="ms-2">{{ $role->users->pluck('name')->join(', ') }}</span>
                            @else
                                <span class="badge bg-secondary">None</span>
                            @endif
                        </td>
                        <td>{{ $role->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('superadmin.roles.show', $role) }}" class="btn btn-outline-info" title="View">
                                    <i class="material-symbols-outlined">visibility</i>
                                </a>
                                <a href="{{ route('superadmin.roles.edit', $role) }}" class="btn btn-outline-primary" title="Edit">
                                    <i class="material-symbols-outlined">edit</i>
                                </a>
                                @if(!$role->is_system_role && $role->users->count() == 0)
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteRole({{ $role->id }})" title="Delete">
                                        <i class="material-symbols-outlined">delete</i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($roles->isEmpty())
                <div class="text-center py-4">
                    <i class="material-symbols-outlined text-muted" style="font-size: 48px;">admin_panel_settings</i>
                    <p class="text-muted mt-2">No roles found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function deleteRole(roleId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/superadmin/roles/${roleId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Chart.js roles analytics
const ctx = document.getElementById('rolesChart').getContext('2d');
const rolesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Assigned Users',
            data: {!! json_encode($chartData) !!},
            backgroundColor: '#6366f1'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

// Filter table by type and search
document.getElementById('typeFilter').addEventListener('change', filterTable);
document.getElementById('searchInput').addEventListener('input', filterTable);

function filterTable() {
    const type = document.getElementById('typeFilter').value;
    const search = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#rolesTable tbody tr');
    rows.forEach(row => {
        let show = true;
        // Type filter
        if (type !== 'all') {
            const typeText = row.querySelector('td:nth-child(4)').textContent.trim().toLowerCase();
            show = show && typeText === type;
        }
        // Search filter
        if (search.length > 0) {
            show = show && row.textContent.toLowerCase().includes(search);
        }
        row.style.display = show ? '' : 'none';
    });
}

// Export to CSV
document.getElementById('exportCsv').addEventListener('click', function() {
    let csv = [];
    const rows = document.querySelectorAll('#rolesTable tr');
    for (let row of rows) {
        let cols = row.querySelectorAll('th, td');
        let rowData = [];
        for (let col of cols) {
            rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
        }
        csv.push(rowData.join(','));
    }
    let csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    let downloadLink = document.createElement('a');
    downloadLink.download = 'roles.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.click();
});

// Export to Excel (simple CSV for Excel)
document.getElementById('exportExcel').addEventListener('click', function() {
    let csv = [];
    const rows = document.querySelectorAll('#rolesTable tr');
    for (let row of rows) {
        let cols = row.querySelectorAll('th, td');
        let rowData = [];
        for (let col of cols) {
            rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
        }
        csv.push(rowData.join(','));
    }
    let csvFile = new Blob([csv.join('\n')], { type: 'application/vnd.ms-excel' });
    let downloadLink = document.createElement('a');
    downloadLink.download = 'roles.xls';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.click();
});
</script>
@endpush