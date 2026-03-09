@extends('layouts.main')

@section('content')
<div class="container-fluid px-4">
    <!-- Hero Section -->
    <div class="hero-section bg-gradient-primary text-white rounded-3 p-5 mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Notifications Center</h1>
                <p class="lead mb-4">Monitor and manage all system notifications across your organization. Stay informed about password changes, security alerts, and user activities.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="notification-stat">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bell text-warning me-2 fs-4"></i>
                            <div>
                                <div class="fw-bold fs-5" id="total-notifications">{{ $totalNotifications ?? 0 }}</div>
                                <small class="opacity-75">Total Notifications</small>
                            </div>
                        </div>
                    </div>
                    <div class="notification-stat">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-info me-2 fs-4"></i>
                            <div>
                                <div class="fw-bold fs-5" id="unread-count">{{ $unreadCount ?? 0 }}</div>
                                <small class="opacity-75">Unread</small>
                            </div>
                        </div>
                    </div>
                    <div class="notification-stat">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users text-success me-2 fs-4"></i>
                            <div>
                                <div class="fw-bold fs-5" id="active-users">{{ $activeUsers ?? 0 }}</div>
                                <small class="opacity-75">Active Users</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <i class="fas fa-shield-alt opacity-25" style="font-size: 8rem;"></i>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double me-1"></i> Mark All Read
            </button>
            <button class="btn btn-secondary" onclick="markAllAsUnread()">
                <i class="fas fa-envelope me-1"></i> Mark All Unread
            </button>
            <button class="btn btn-danger" onclick="deleteSelected()" id="delete-selected" disabled>
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
            <button class="btn btn-warning" onclick="deleteAll()">
                <i class="fas fa-trash-alt me-1"></i> Delete All
            </button>
            <button class="btn btn-success" onclick="exportToCsv()">
                <i class="fas fa-download me-1"></i> Export CSV
            </button>
            <button class="btn btn-info" onclick="refreshNotifications()">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>Filters
            </h5>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-2">
                    <label for="role-filter" class="form-label">Role</label>
                    <select class="form-select" id="role-filter" name="role">
                        <option value="">All Roles</option>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status-filter" class="form-label">Status</label>
                    <select class="form-select" id="status-filter" name="status">
                        <option value="">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                        <option value="opened">Opened</option>
                        <option value="deleted">Deleted</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type-filter" class="form-label">Type</label>
                    <select class="form-select" id="type-filter" name="type">
                        <option value="">All Types</option>
                        <option value="password_change">Password Change</option>
                        <option value="security_alert">Security Alert</option>
                        <option value="system_update">System Update</option>
                        <option value="user_activity">User Activity</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date-from" class="form-label">From Date</label>
                    <input type="date" class="form-control" id="date-from" name="date_from">
                </div>
                <div class="col-md-2">
                    <label for="date-to" class="form-label">To Date</label>
                    <input type="date" class="form-control" id="date-to" name="date_to">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Notifications Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>All Notifications
            </h5>
            <div class="d-flex align-items-center gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="select-all">
                    <label class="form-check-label" for="select-all">
                        Select All
                    </label>
                </div>
                <span class="badge bg-primary" id="showing-count">Showing {{ $notifications->count() ?? 0 }} of {{ $notifications->total() ?? 0 }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="notifications-table">
                    <thead class="table-light">
                        <tr>
                            <th width="50">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all-header">
                                </div>
                            </th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications ?? [] as $notification)
                        <tr class="notification-row {{ $notification->read_at ? 'read' : 'unread' }}" data-id="{{ $notification->id }}">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input notification-checkbox" type="checkbox" value="{{ $notification->id }}">
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $notification->read_at ? 'bg-success' : 'bg-warning' }}">
                                    <i class="fas {{ $notification->read_at ? 'fa-check-circle' : 'fa-envelope' }} me-1"></i>
                                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                                </span>
                                @if($notification->opened_at)
                                    <span class="badge bg-info ms-1">
                                        <i class="fas fa-eye me-1"></i>Opened
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <i class="fas {{ $notification->type_icon ?? 'fa-bell' }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $notification->type ?? 'General')) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $notification->user->avatar ?? asset('images/default-avatar.png') }}" 
                                         alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <div class="fw-semibold">{{ $notification->user->name ?? 'Unknown User' }}</div>
                                        <small class="text-muted">{{ $notification->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    @if(($notification->user->role ?? '') === 'superadmin') bg-danger
                                    @elseif(($notification->user->role ?? '') === 'admin') bg-warning
                                    @else bg-primary
                                    @endif">
                                    {{ ucfirst($notification->user->role ?? 'User') }}
                                </span>
                            </td>
                            <td>
                                <div class="notification-message">
                                    <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                    <div class="text-muted small">{{ Str::limit($notification->data['message'] ?? '', 80) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="text-nowrap">
                                    <div class="fw-semibold">{{ $notification->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $notification->created_at->format('h:i A') }}</small>
                                    <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="viewNotification('{{ $notification->id }}')" 
                                            title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-{{ $notification->read_at ? 'warning' : 'success' }}" 
                                            onclick="toggleReadStatus('{{ $notification->id }}')"
                                            title="{{ $notification->read_at ? 'Mark as Unread' : 'Mark as Read' }}">
                                        <i class="fas fa-{{ $notification->read_at ? 'envelope' : 'check' }}"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteNotification('{{ $notification->id }}')" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                    <h5>No notifications found</h5>
                                    <p>There are no notifications matching your current filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(isset($notifications) && $notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

<!-- My Notifications Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-user-shield me-2"></i>My Notifications
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="notification-list" style="max-height: 300px; overflow-y: auto;">
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $myNotification)
                        <div class="notification-item d-flex align-items-center p-3 border-bottom {{ $myNotification->read_at ? 'read' : 'unread' }}" 
                             data-id="{{ $myNotification->id }}">
                            <div class="notification-icon me-3">
                                <i class="fas {{ $myNotification->read_at ? 'fa-check-circle text-success' : 'fa-bell text-warning' }} fs-5"></i>
                            </div>
                            <div class="notification-content flex-grow-1">
                                <div class="fw-semibold">{{ $myNotification->data['title'] ?? 'Notification' }}</div>
                                <div class="text-muted small">{{ Str::limit($myNotification->data['message'] ?? '', 60) }}</div>
                                <small class="text-muted">{{ $myNotification->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="notification-actions">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewMyNotification('{{ $myNotification->id }}')" 
                                        title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if(!$myNotification->read_at)
                                <button class="btn btn-sm btn-outline-success ms-1" onclick="markMyNotificationAsRead('{{ $myNotification->id }}')" 
                                        title="Mark as Read">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">No notifications for you</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="my-notifications-stats">
                        <div class="stat-card bg-light p-3 rounded mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <div>
                                    <div class="fw-bold">{{ auth()->user()->unreadNotifications->count() }}</div>
                                    <small class="text-muted">Unread</small>
                                </div>
                            </div>
                        </div>
                        <div class="stat-card bg-light p-3 rounded mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <div>
                                    <div class="fw-bold">{{ auth()->user()->readNotifications->count() }}</div>
                                    <small class="text-muted">Read</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-sm" onclick="markAllMyNotificationsAsRead()">
                                <i class="fas fa-check-double me-1"></i> Mark All Read
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="viewAllMyNotifications()">
                                <i class="fas fa-list me-1"></i> View All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Notification Detail Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="notification-details">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="markAsRead()">Mark as Read</button>
            </div>
        </div>
    </div>
</div>

<!-- My Notification Detail Modal -->
<div class="modal fade" id="myNotificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">My Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="my-notification-details">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="markMyNotificationAsReadFromModal()">Mark as Read</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.notification-stat {
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 0.5rem;
    backdrop-filter: blur(10px);
}

.notification-row.unread {
    background-color: rgba(13, 110, 253, 0.05);
    border-left: 4px solid #0d6efd;
}

.notification-row.read {
    opacity: 0.8;
}

.notification-message {
    max-width: 300px;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

@media (max-width: 768px) {
    .hero-section {
        text-align: center;
    }
    
    .hero-section .col-lg-4 {
        display: none;
    }
    
    .notification-stat {
        flex: 1;
        min-width: 120px;
    }

    /* My Notifications Styles */
.notification-item.unread {
    background-color: rgba(13, 110, 253, 0.05);
    border-left: 3px solid #0d6efd !important;
}

.notification-item.read {
    opacity: 0.8;
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.my-notifications-stats .stat-card {
    border: 1px solid #dee2e6;
}

.notification-list {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.notification-list .notification-item:last-child {
    border-bottom: none !important;
}
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let selectedNotifications = [];
let currentNotificationId = null;


$(document).ready(function() {
    initializeEventHandlers();
    updateCounts();
});

function initializeEventHandlers() {
    // Select all functionality
    $('#select-all, #select-all-header').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.notification-checkbox').prop('checked', isChecked);
        $('#select-all, #select-all-header').prop('checked', isChecked);
        updateSelectedNotifications();
    });

    // Individual checkbox functionality
    $(document).on('change', '.notification-checkbox', function() {
        updateSelectedNotifications();
        updateSelectAllState();
    });

    // Filter form changes
    $('#filter-form select, #filter-form input').on('change', function() {
        applyFilters();
    });
}

function updateSelectedNotifications() {
    selectedNotifications = $('.notification-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    $('#delete-selected').prop('disabled', selectedNotifications.length === 0);
}

function updateSelectAllState() {
    const totalCheckboxes = $('.notification-checkbox').length;
    const checkedCheckboxes = $('.notification-checkbox:checked').length;
    
    $('#select-all, #select-all-header').prop('checked', totalCheckboxes === checkedCheckboxes);
}

function applyFilters() {
    const formData = $('#filter-form').serialize();
    showLoading();
    
    $.ajax({
        url: '{{ route("superadmin.notifications.filter") }}',
        method: 'GET',
        data: formData,
        success: function(response) {
            $('#notifications-table tbody').html(response.html);
            updateCounts();
            hideLoading();
        },
        error: function() {
            showAlert('Error applying filters', 'error');
            hideLoading();
        }
    });
}

function clearFilters() {
    $('#filter-form')[0].reset();
    applyFilters();
}

function viewNotification(id) {
    currentNotificationId = id;
    
    $.ajax({
        url: `{{ url('superadmin/notifications') }}/${id}`,
        method: 'GET',
        success: function(response) {
            $('#notification-details').html(response.html);
            $('#notificationModal').modal('show');
            
            // Mark as opened
            markAsOpened(id);
        },
        error: function() {
            showAlert('Error loading notification details', 'error');
        }
    });
}

function toggleReadStatus(id) {
    $.ajax({
        url: `{{ url('superadmin/notifications') }}/${id}/toggle-read`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function() {
            showAlert('Error updating notification status', 'error');
        }
    });
}

function markAllAsRead() {
    if (!confirm('Are you sure you want to mark all notifications as read?')) return;
    
    showLoading();
    
    $.ajax({
        url: '{{ route("superadmin.notifications.mark-all-read") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showAlert('All notifications marked as read', 'success');
                location.reload();
            }
        },
        error: function() {
            showAlert('Error marking notifications as read', 'error');
            hideLoading();
        }
    });
}

function markAllAsUnread() {
    if (!confirm('Are you sure you want to mark all notifications as unread?')) return;
    
    showLoading();
    
    $.ajax({
        url: '{{ route("superadmin.notifications.mark-all-unread") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showAlert('All notifications marked as unread', 'success');
                location.reload();
            }
        },
        error: function() {
            showAlert('Error marking notifications as unread', 'error');
            hideLoading();
        }
    });
}

function deleteSelected() {
    if (selectedNotifications.length === 0) return;
    
    if (!confirm(`Are you sure you want to delete ${selectedNotifications.length} selected notification(s)?`)) return;
    
    showLoading();
    
    $.ajax({
        url: '{{ route("superadmin.notifications.delete-selected") }}',
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}',
            notifications: selectedNotifications
        },
        success: function(response) {
            if (response.success) {
                showAlert(`${selectedNotifications.length} notification(s) deleted successfully`, 'success');
                location.reload();
            }
        },
        error: function() {
            showAlert('Error deleting notifications', 'error');
            hideLoading();
        }
    });
}

function deleteAll() {
    if (!confirm('Are you sure you want to delete ALL notifications? This action cannot be undone.')) return;
    
    showLoading();
    
    $.ajax({
        url: '{{ route("superadmin.notifications.delete-all") }}',
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                showAlert('All notifications deleted successfully', 'success');
                location.reload();
            }
        },
        error: function() {
            showAlert('Error deleting all notifications', 'error');
            hideLoading();
        }
    });
}

function deleteNotification(id) {
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    $.ajax({
        url: `{{ url('superadmin/notifications') }}/${id}`,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $(`tr[data-id="${id}"]`).fadeOut(300, function() {
                    $(this).remove();
                    updateCounts();
                });
                showAlert('Notification deleted successfully', 'success');
            }
        },
        error: function() {
            showAlert('Error deleting notification', 'error');
        }
    });
}

function exportToCsv() {
    showLoading();
    
    const formData = $('#filter-form').serialize();
    window.location.href = `{{ route('superadmin.notifications.export') }}?${formData}`;
    
    setTimeout(hideLoading, 2000);
}

function refreshNotifications() {
    location.reload();
}

function markAsRead() {
    if (currentNotificationId) {
        toggleReadStatus(currentNotificationId);
        $('#notificationModal').modal('hide');
    }
}

function markAsOpened(id) {
    $.ajax({
        url: `{{ url('superadmin/notifications') }}/${id}/mark-opened`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        }
    });
}

function updateCounts() {
    // Update notification counts
    const totalRows = $('.notification-row').length;
    const unreadRows = $('.notification-row.unread').length;
    
    $('#total-notifications').text(totalRows);
    $('#unread-count').text(unreadRows);
    $('#showing-count').text(`Showing ${totalRows} notifications`);
}

function showLoading() {
    $('body').addClass('loading');
    $('.btn').prop('disabled', true);
}

function hideLoading() {
    $('body').removeClass('loading');
    $('.btn').prop('disabled', false);
    $('#delete-selected').prop('disabled', selectedNotifications.length === 0);
}

function showAlert(message, type = 'info') {
    const alertClass = type === 'error' ? 'alert-danger' : `alert-${type}`;
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.container-fluid').prepend(alert);
    
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// My Notifications Functions
function viewMyNotification(id) {
    currentMyNotificationId = id;
    
    $.ajax({
        url: `{{ url('superadmin/my-notifications') }}/${id}`,
        method: 'GET',
        success: function(response) {
            $('#my-notification-details').html(response.html);
            $('#myNotificationModal').modal('show');
            
            // Update the notification item as read
            $(`.notification-item[data-id="${id}"]`).removeClass('unread').addClass('read');
            $(`.notification-item[data-id="${id}"] .notification-icon i`).removeClass('fa-bell text-warning').addClass('fa-check-circle text-success');
        },
        error: function() {
            showAlert('Error loading notification details', 'error');
        }
    });
}

function markMyNotificationAsRead(id) {
    $.ajax({
        url: `{{ url('superadmin/my-notifications') }}/${id}/mark-read`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $(`.notification-item[data-id="${id}"]`).removeClass('unread').addClass('read');
                $(`.notification-item[data-id="${id}"] .notification-icon i`).removeClass('fa-bell text-warning').addClass('fa-check-circle text-success');
                $(`.notification-item[data-id="${id}"] .btn-outline-success`).remove();
                showAlert('Notification marked as read', 'success');
                updateMyNotificationStats();
            }
        },
        error: function() {
            showAlert('Error marking notification as read', 'error');
        }
    });
}

function markMyNotificationAsReadFromModal() {
    if (currentMyNotificationId) {
        markMyNotificationAsRead(currentMyNotificationId);
        $('#myNotificationModal').modal('hide');
    }
}

function markAllMyNotificationsAsRead() {
    if (!confirm('Are you sure you want to mark all your notifications as read?')) return;
    
    showLoading();
    
    $.ajax({
        url: '{{ url("superadmin/my-notifications/mark-all-read") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('.notification-item').removeClass('unread').addClass('read');
                $('.notification-item .notification-icon i').removeClass('fa-bell text-warning').addClass('fa-check-circle text-success');
                $('.notification-item .btn-outline-success').remove();
                showAlert('All your notifications marked as read', 'success');
                updateMyNotificationStats();
                hideLoading();
            }
        },
        error: function() {
            showAlert('Error marking notifications as read', 'error');
            hideLoading();
        }
    });
}

function viewAllMyNotifications() {
    window.open('{{ url("superadmin/my-notifications") }}', '_blank');
}

function updateMyNotificationStats() {
    const myUnreadCount = $('.notification-item.unread').length;
    const myReadCount = $('.notification-item.read').length;
    
    $('.my-notifications-stats .stat-card').eq(0).find('.fw-bold').text(myUnreadCount);
    $('.my-notifications-stats .stat-card').eq(1).find('.fw-bold').text(myReadCount);
}
</script>
@endpush