{{-- filepath: resources/views/dashboard/bookings/index.blade.php --}}
@extends('layouts.dashboard')

@section('content')
<div class="content-header">
    <h1 class="content-title">Booking History</h1>
    <p class="content-subtitle">Manage and track all your bookings</p>
</div>

<div class="container-fluid px-4">
    <h1 class="h3 mb-4">My Bookings</h1>
    
    <div class="card">
        <div class="card-body">
            @forelse($bookings as $booking)
                <div class="booking-item">
                    <!-- Booking content -->
                </div>
            @empty
                <p class="text-muted">No bookings found.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Bookings</h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="statusFilter">
                <option value="">All Bookings</option>
                <option value="completed">Completed</option>
                <option value="processing">Processing</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
                <option value="partial">Partial Payment</option>
            </select>
            <button class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>New Booking
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Order Date</th>
                        <th>Execution Time</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Remain</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings ?? [] as $booking)
                    <tr>
                        <td>
                            <span class="badge bg-primary">
                                <i class="fas {{ $booking->type === 'flight' ? 'fa-plane' : 'fa-bed' }} me-1"></i>
                                {{ ucfirst($booking->type) }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $booking->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $booking->reference }}</small>
                            </div>
                        </td>
                        <td>{{ $booking->created_at->format('M d, Y') }}</td>
                        <td>{{ $booking->execution_date ? $booking->execution_date->format('M d, Y H:i') : 'TBD' }}</td>
                        <td><strong>KES {{ number_format($booking->total_amount, 2) }}</strong></td>
                        <td class="text-success">KES {{ number_format($booking->paid_amount, 2) }}</td>
                        <td class="text-danger">KES {{ number_format($booking->remaining_amount, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($booking->status) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="viewDetails({{ $booking->id }})">
                                            <i class="fas fa-eye me-2"></i>Details
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="viewInvoice({{ $booking->id }})">
                                            <i class="fas fa-file-invoice me-2"></i>Invoice
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No bookings found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Content loaded via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="invoiceContent">
                <!-- Content loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printInvoice()">
                    <i class="fas fa-print me-1"></i>Print
                </button>
                <button type="button" class="btn btn-success" onclick="downloadInvoice()">
                    <i class="fas fa-download me-1"></i>Download
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function viewDetails(bookingId) {
    fetch(`/dashboard/bookings/${bookingId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('detailsContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading booking details');
        });
}

function viewInvoice(bookingId) {
    fetch(`/dashboard/bookings/${bookingId}/invoice`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('invoiceContent').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('invoiceModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading invoice');
        });
}

function printInvoice() {
    const printContent = document.getElementById('invoiceContent').innerHTML;
    const originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

function downloadInvoice() {
    // Implementation for PDF download
    alert('PDF download feature coming soon');
}

// Status filter
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const url = new URL(window.location);
    
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    window.location.href = url.toString();
});
</script>
@endsection