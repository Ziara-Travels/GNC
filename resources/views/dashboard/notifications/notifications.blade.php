@extends('layouts.main')

@section('content')
<div class="container-fluid px-4">
    <!-- Hero Section -->
    <div class="hero-section bg-gradient-info text-white rounded-3 p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="h2 fw-bold mb-3">My Notifications</h1>
                <p class="mb-4">Stay updated with your personal notifications, security alerts, and account activities.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <div class="notification-stat">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bell text-warning me-2 fs-5"></i>
                            <div>
                                <div class="fw-bold fs-6" id="total-notifications">{{ $totalNotifications ?? 0 }}</div>
                                <small class="opacity-75">Total</small>
                            </div>
                        </div>
                    </div>
                    <div class="notification-stat">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-info me-2 fs-5"></i>
                            <div>
                                <div class="fw-bold fs-6" id="unread-count">{{ $unreadCount ?? 0 }}</div>
                                <small class="opacity-75">Unread</small>
                            </div>
                        </div>
                    </div>
                    <div class="notification-stat">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                            <div>
                                <div class="fw-bold fs-6" id="read-count">{{ $readCount ?? 0 }}</div>
                                <small class="opacity-75">Read</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <i class="fas fa-user-circle opacity-25" style="font-size: 6rem;"></i>
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
            <button class="btn btn-outline-danger" onclick="deleteSelected()" id="delete-selected" disabled>
                <i class="fas fa-archive me-1"></i> Archive Selected
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
                <i class="fas fa-filter me-2"></i>Filter Notifications
            </h5>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-3">
                    <label for="status-filter" class="form-label">Status</label>
                    <select class="form-select" id="status-filter" name="status">
                        <option value="">All Status</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type-filter" class="form-label">Notification Type</label>
                    <select class="form-select" id="type-filter" name="type">
                        <option value="">All Types</option>
                        <option value="password_change">Password Change</option>
                        <option value="security_alert">Security Alert</option>
                        <option value="account_update">Account Update</option>
                        <option value="system_notification">System Notification</option>
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
                <i class="fas fa-list me-2"></i>My Notifications
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
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    <i class="fas {{ $notification->type_icon ?? 'fa-bell' }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $notification->type ?? 'General')) }}
                                </span>
                            </td>
                            <td>
                                <div class="notification-message">
                                    <div class="fw-semibold">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                    <div class="text-muted small">{{ $notification->data['message'] ?? 'No message content' }}</div>
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
                                    <button class="btn btn-outline-danger" onclick="archiveNotification('{{ $notification->id }}')" 
                                            title="Archive Notification">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                    <h5>No notifications found</h5>
                                    <p>You don't have any notifications at the moment.</p>
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

@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border: none;
}

.notification-stat {
    background: rgba(255, 255, 255, 0.15);
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    backdrop-filter: blur(10px);
    min-width: 80px;
}

.notification-row.unread {
    background-color: rgba(13, 110, 253, 0.05);
    border-left: 3px solid #0d6efd;
}

.notification-row.read {
    opacity: 0.85;
}

.notification-message {
    max-width: 400px;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

@media (max-width: 768px) {
    .hero-section {
        text-align: center;
        padding: 2rem 1rem !important;
    }
    
    .hero-section .col-lg-4 {
        display: none;
    }
    
    .notification-stat {
        flex: 1;
        min-width: 100px;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        border-radius: 0.25rem !important;
        margin-bottom: 2px;
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
    $.ajax({
        url: '{{ route('notifications.filter') }}',
        method: 'GET',
        data: formData,
        beforeSend: function() {
            $('#notifications-table').addClass('loading');
        },
        success: function(response) {
            $('#notifications-table tbody').html(response.html);
            updateCounts();
            $('#notifications-table').removeClass('loading');
        },
        error: function() {
            alert('Error applying filters. Please try again.');
            $('#notifications-table').removeClass('loading');
        }
    });

}










