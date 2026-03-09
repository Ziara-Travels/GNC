@extends('layouts.user-dashboard')

@section('title', 'All Bookings')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex sm:items-center sm:justify-between">
            <div class="trezo-card-title">
                <h5 class="mb-0 text-xl">
                    All Your Bookings
                </h5>
                <p class="text-gray-500 text-sm mt-1">Manage and view all your travel bookings</p>
            </div>
            <div class="trezo-card-subtitle sm:flex sm:items-center">
                <form class="relative sm:w-[300px] ltr:sm:mr-[20px] rtl:sm:ml-[20px] my-[13px] sm:my-0">
                    <label class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                        <i class="ri-search-line !text-[20px]"></i>
                    </label>
                    <input type="text" placeholder="Search bookings..." class="bg-gray-50 border border-gray-50 h-[40px] text-sm rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                </form>
                <div class="trezo-card-dropdown relative">
                    <button type="button" class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[8px] px-[16px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]" id="dropdownToggleBtn">
                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                            All Types
                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                        </span>
                    </button>
                    <ul class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:left-0 sm:ltr:right-0 rtl:right-0 sm:rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none">
                        <li>
                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                All Types
                            </button>
                        </li>
                        <li>
                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                Flights
                            </button>
                        </li>
                        <li>
                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                Hotels
                            </button>
                        </li>
                        <li>
                            <button type="button" class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black">
                                Tours
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="trezo-card-content">
            <div class="table-responsive overflow-x-auto">
                <table class="w-full">
                    <thead class="text-black dark:text-white">
                        <tr>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[12px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                Booking Reference
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[12px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                Type
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[12px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                Details
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[12px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                Date Created
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[12px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                Amount
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[12px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                Status
                            </th>
                            <th class="font-medium text-center px-[20px] py-[12px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap last:rounded-tr-md">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-black dark:text-white">
                        @forelse($bookings as $booking)
                        <tr>
                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036]">
                                <span class="font-medium">{{ $booking->booking_reference }}</span>
                            </td>
                            <td class="ltr:text-left rtl:text-right px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036]">
                                <span class="px-[10px] py-[4px] inline-block 
                                    @if($booking->type == 'flight') bg-blue-100 dark:bg-[#15203c] text-blue-600
                                    @elseif($booking->type == 'hotel') bg-green-100 dark:bg-[#15203c] text-green-600
                                    @elseif($booking->type == 'tour') bg-purple-100 dark:bg-[#15203c] text-purple-600
                                    @else bg-gray-100 dark:bg-[#15203c] text-gray-600
                                    @endif
                                    rounded-md font-medium text-sm">
                                    {{ ucfirst($booking->type ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="ltr:text-left rtl:text-right px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036]">
                                <div>
                                    @if($booking->type == 'flight')
                                        <span class="block font-medium">
                                            {{ $booking->departure_city ?? 'N/A' }} → {{ $booking->arrival_city ?? 'N/A' }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $booking->airline ?? 'N/A' }}
                                            @if($booking->flight_number)
                                                • {{ $booking->flight_number }}
                                            @endif
                                        </span>
                                    @elseif($booking->type == 'hotel')
                                        <span class="block font-medium">
                                            {{ $booking->hotel_name ?? 'Hotel Booking' }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $booking->destination ?? $booking->destination_city ?? 'N/A' }}
                                        </span>
                                    @elseif($booking->type == 'tour')
                                        <span class="block font-medium">
                                            {{ $booking->tour_name ?? 'Tour Booking' }}
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            {{ $booking->destination ?? $booking->destination_city ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="block font-medium">{{ $booking->title ?? 'Booking' }}</span>
                                        <span class="text-sm text-gray-500">{{ $booking->description ?? 'N/A' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036]">
                                <div>
                                    <span class="block">{{ $booking->created_at->format('M d, Y') }}</span>
                                    <span class="text-sm text-gray-500">{{ $booking->created_at->format('g:i A') }}</span>
                                </div>
                            </td>
                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036]">
                                <span class="font-medium text-lg">${{ number_format($booking->total_amount ?? 0, 2) }}</span>
                            </td>
                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036]">
                                <span class="px-[10px] py-[4px] inline-block 
                                    @if($booking->status == 'confirmed') bg-success-100 dark:bg-[#15203c] text-success-600
                                    @elseif($booking->status == 'processing') bg-warning-50 dark:bg-[#15203c] text-warning-500
                                    @elseif($booking->status == 'cancelled') bg-red-50 dark:bg-[#15203c] text-red-500
                                    @else bg-primary-50 dark:bg-[#15203c] text-primary-500
                                    @endif
                                    rounded-md font-medium text-sm">
                                    {{ ucfirst($booking->status ?? 'Unknown') }}
                                </span>
                            </td>
                            <td class="text-center whitespace-nowrap px-[20px] py-[16px] border-b border-gray-100 dark:border-[#172036]">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="bg-primary-50 dark:bg-[#15203c] text-primary-500 p-2 rounded-md hover:bg-primary-500 hover:text-white transition-all" title="View Details">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('bookings.download', $booking->id) }}" class="bg-secondary-50 dark:bg-[#15203c] text-secondary-500 p-2 rounded-md hover:bg-secondary-500 hover:text-white transition-all" title="Download">
                                        <i class="ri-download-line"></i>
                                    </a>
                                    @if($booking->status == 'processing')
                                        <button class="bg-red-50 dark:bg-[#15203c] text-red-500 p-2 rounded-md hover:bg-red-500 hover:text-white transition-all" title="Cancel" onclick="confirmCancellation('{{ $booking->id }}', '{{ $booking->booking_reference }}')">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-[40px] text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="ri-calendar-line text-4xl mb-4 text-gray-300"></i>
                                    <h3 class="text-lg font-medium mb-2">No bookings found</h3>
                                    <p class="text-sm">Start exploring our amazing travel deals!</p>
                                    <a href="{{ route('home') }}" class="mt-4 bg-primary-500 text-white px-6 py-2 rounded-md hover:bg-primary-600 transition-all">
                                        Start Booking
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="px-[20px] py-[15px] border-l border-r border-b border-gray-100 dark:border-[#172036] rounded-b-md">
                    <div class="sm:flex sm:items-center justify-between">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <p class="text-sm text-gray-500">
                                Showing {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} of {{ $bookings->total() }} bookings
                            </p>
                        </div>
                        <div>
                            {{ $bookings->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Cancellation Modal -->
<div class="modal fade" id="cancellationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel booking <strong id="bookingRef"></strong>?</p>
                <p class="text-sm text-gray-600">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Booking</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Booking</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let bookingToCancel = null;
    
    function confirmCancellation(bookingId, bookingRef) {
        bookingToCancel = bookingId;
        document.getElementById('bookingRef').textContent = bookingRef;
        
        // Show modal (assuming you're using Bootstrap)
        const modal = new bootstrap.Modal(document.getElementById('cancellationModal'));
        modal.show();
    }
    
    document.getElementById('confirmCancelBtn')?.addEventListener('click', function() {
        if (bookingToCancel) {
            // Implement cancellation logic here
            fetch(`/bookings/${bookingToCancel}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to cancel booking. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    });
</script>
@endsection