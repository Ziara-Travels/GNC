@extends('layouts.user-dashboard')

@section('title', 'My Referrals - User Dashboard')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">My Referrals</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Referrals</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2">Total Referrals</p>
                                    <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                </div>
                                <div class="text-primary">
                                    <i class="bx bx-user-plus font-size-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2">Pending</p>
                                    <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                                </div>
                                <div class="text-warning">
                                    <i class="bx bx-time font-size-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2">Approved</p>
                                    <h4 class="mb-0">{{ $stats['approved'] }}</h4>
                                </div>
                                <div class="text-success">
                                    <i class="bx bx-check-circle font-size-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-truncate font-size-14 mb-2">Completed</p>
                                    <h4 class="mb-0">{{ $stats['completed'] }}</h4>
                                </div>
                                <div class="text-info">
                                    <i class="bx bx-trophy font-size-24"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral Form -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Submit New Referral</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('referrals.submit') }}" id="referralForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" id="name" name="name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="relationship" class="form-label">Relationship</label>
                                            <select class="form-control" id="relationship" name="relationship">
                                                <option value="">Select relationship</option>
                                                <option value="family">Family</option>
                                                <option value="friend">Friend</option>
                                                <option value="colleague">Colleague</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="message" class="form-label">Additional Message</label>
                                            <textarea class="form-control" id="message" name="message" rows="3" placeholder="Optional message about why you're referring this person..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit Referral</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Referral List -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Recent Referrals</h4>
                        </div>
                        <div class="card-body">
                            @if($referrals->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-nowrap align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($referrals as $referral)
                                                <tr>
                                                    <td>
                                                        <h6 class="mb-0">{{ $referral->name }}</h6>
                                                        <small class="text-muted">{{ $referral->email }}</small>
                                                    </td>
                                                    <td>
                                                        @if($referral->status === 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($referral->status === 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @elseif($referral->status === 'completed')
                                                            <span class="badge bg-info">Completed</span>
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $referral->created_at->format('M j, Y') }}</td>
                                                    <td>
                                                        @if($referral->code)
                                                            <code>{{ $referral->code }}</code>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    {{ $referrals->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-user-plus display-4 text-muted"></i>
                                    <h5 class="mt-3">No referrals yet</h5>
                                    <p class="text-muted">Start referring friends and family to earn rewards!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('referralForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Referral submitted successfully!');
            this.reset();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
</script>
@endsection
