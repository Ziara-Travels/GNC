@extends('layouts.main')

@section('title', 'All Permissions & Assignments')

@section('content')
<!-- Hero Section -->
<div class="container-fluid mb-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-primary text-white rounded p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="material-symbols-outlined me-2" style="font-size: 2rem;">lock</i>
                        All Permissions & Assignments
                    </h2>
                    <p class="mb-0">View all permissions assigned to users, with analytics and management tools.</p>
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
                    <i class="material-symbols-outlined text-primary mb-2" style="font-size: 2rem;">lock</i>
                    <h4 class="mb-0">{{ $totalPermissions }}</h4>
                    <small class="text-muted">Total Permissions</small>
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
                    <h4 class="mb-0">{{ $assignedPermissions }}</h4>
                    <small class="text-muted">Assigned Permissions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="material-symbols-outlined text-warning mb-2" style="font-size: 2rem;">admin_panel_settings</i>
                    <h4 class="mb-0">{{ $rolesCount }}</h4>
                    <small class="text-muted">Roles with Permissions</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="container-fluid mb-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="material-symbols-outlined me-2">bar_chart</i> Permissions Assignment Analytics</h5>
        </div>
        <div class="card-body">
            <canvas id="permissionsChart" height="80"></canvas>
        </div>
    </div>
</div>

<!-- Filters Section -->
<div class="container-fluid mb-4">
    <div class="card">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center">
            <div>
                <label class="fw-semibold me-2">Permission Type:</label>
                <select id="typeFilter" class="form-select">
                    <option value="all">All</option>
                    <option value="system">System</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div>
                <label class="fw-semibold me-2">Search:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by permission, user, role...">
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

<!-- Permissions Table Section -->
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="material-symbols-outlined me-2">table</i> All Permissions & Assignments</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover" id="permissionsTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Permission Name</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Assigned Roles</th>
                        <th>Assigned Users</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $permission->name }}</span>
                        </td>
                        <td>{{ Str::limit($permission->description ?? 'No description', 50) }}</td>
                        <td>
                            @if($permission->is_system_permission)
                                <span class="badge bg-warning">System</span>
                            @else
                                <span class="badge bg-secondary">Custom</span>
                            @endif
                        </td>
                        <td>
                            @if($permission->roles->count())
                                <span class="badge bg-info">{{ $permission->roles->count() }}</span>
                                <span class="ms-2">{{ $permission->roles->pluck('name')->join(', ') }}</span>
                            @else
                                <span class="badge bg-secondary">None</span>
                            @endif
                        </td>
                        <td>
                            @if($permission->users->count())
                                <span class="badge bg-success">{{ $permission->users->count() }}</span>
                                <span class="ms-2">{{ $permission->users->pluck('name')->join(', ') }}</span>
                            @else
                                <span class="badge bg-secondary">None</span>
                            @endif
                        </td>
                        <td>{{ $permission->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($permissions->isEmpty())
                <div class="text-center py-4">
                    <i class="material-symbols-outlined text-muted" style="font-size: 48px;">lock</i>
                    <p class="text-muted mt-2">No permissions found</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js permissions analytics
const ctx = document.getElementById('permissionsChart').getContext('2d');
const permissionsChart = new Chart(ctx, {
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
    const rows = document.querySelectorAll('#permissionsTable tbody tr');
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
    const rows = document.querySelectorAll('#permissionsTable tr');
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
    downloadLink.download = 'permissions.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.click();
});

// Export to Excel (simple CSV for Excel)
document.getElementById('exportExcel').addEventListener('click', function() {
    let csv = [];
    const rows = document.querySelectorAll('#permissionsTable tr');
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
    downloadLink.download = 'permissions.xls';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.click();
});
</script>
@endpush