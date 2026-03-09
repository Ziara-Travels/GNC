@extends('layouts.user-dashboard')

@section('content')
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">
    
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        <div class="trezo-card-content">
            
            <!-- Page Header -->
            <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                <div class="trezo-card-title">
                    <h5 class="mb-0 text-xl font-semibold">All Bookings</h5>
                    <p class="text-gray-500 text-sm mt-1">Manage all your travel bookings</p>
                </div>
                <div class="trezo-card-subtitle sm:flex items-center mt-[10px] sm:mt-0">
                    <a href="{{ route('user.dashboard') }}" class="inline-block rounded-md transition-all bg-gray-500 text-white py-[4.5px] px-[17px] hover:bg-gray-400">
                        <span class="inline-block relative ltr:pl-[26px] rtl:pr-[26px]">
                            <i class="ri-arrow-left-line absolute text-lg top-1/2 -translate-y-1/2 ltr:left-0 rtl:right-0"></i>
                            Back to Dashboard
                        </span>
                    </a>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-content">
                    <div class="table-responsive overflow-x-auto">
                        <table class="w-full">
                            <thead class="text-black dark:text-white">
                                <tr>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tl-md">
                                        Booking Ref
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                        Type
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                        Details
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                        Date
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                        Amount
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap">
                                        Status
                                    </th>
                                    <th class="font-medium text-center px-[20px] py-[11px] bg-gray-50 dark:bg-[#15203c] whitespace-nowrap first:rounded-tr-md">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                                @forelse($bookings ?? [] as $booking)
                                <tr>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036] ltr:first:border-l ltr:last:border-r rtl:first:border-r rtl:last:border-l">
                                        <span class="font-medium">{{ $booking->booking_reference }}</span>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                        <span class="px-[8px] py-[3px] inline-block 
                                            @if($booking->type == 'flight') bg-blue-100 dark:bg-[#15203c] text-blue-600
                                            @elseif($booking->type == 'hotel') bg-green-100 dark:bg-[#15203c] text-green-600
                                            @elseif($booking->type == 'tour') bg-purple-100 dark:bg-[#15203c] text-purple-600
                                            @else bg-gray-100 dark:bg-[#15203c] text-gray-600
                                            @endif
                                            rounded-sm font-medium text-xs">
                                            {{ ucfirst($booking->type ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                        <div>
                                            @if($booking->type == 'flight')
                                                <span class="block font-medium">
                                                    {{ $booking->departure_city ?? 'N/A' }} → {{ $booking->arrival_city ?? 'N/A' }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    {{ $booking->airline ?? 'N/A' }}
                                                </span>
                                            @elseif($booking->type == 'hotel')
                                                <span class="block font-medium">
                                                    {{ $booking->hotel_name ?? 'N/A' }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    {{ $booking->destination ?? 'N/A' }}
                                                </span>
                                            @elseif($booking->type == 'tour')
                                                <span class="block font-medium">
                                                    {{ $booking->tour_name ?? 'N/A' }}
                                                </span>
                                                <span class="text-sm text-gray-500">
                                                    {{ $booking->destination ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span class="block font-medium">{{ $booking->title ?? 'N/A' }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                        <span>{{ $booking->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                        <span class="font-medium">${{ number_format($booking->total_amount ?? 0, 2) }}</span>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                        <span class="px-[8px] py-[3px] inline-block 
                                            @if($booking->status == 'confirmed') bg-success-100 dark:bg-[#15203c] text-success-600
                                            @elseif($booking->status == 'processing') bg-warning-50 dark:bg-[#15203c] text-warning-500
                                            @elseif($booking->status == 'cancelled') bg-red-50 dark:bg-[#15203c] text-red-500
                                            @else bg-primary-50 dark:bg-[#15203c] text-primary-500
                                            @endif
                                            rounded-sm font-medium text-xs">
                                            {{ ucfirst($booking->status ?? 'Unknown') }}
                                        </span>
                                    </td>
                                    <td class="text-center whitespace-nowrap px-[20px] py-[15px] border-b border-gray-100 dark:border-[#172036]">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('bookings.show', $booking->id) }}" class="bg-primary-50 dark:bg-[#15203c] text-primary-500 p-1.5 rounded-md hover:bg-primary-500 hover:text-white transition-all">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('bookings.download', $booking->id) }}" class="bg-secondary-50 dark:bg-[#15203c] text-secondary-500 p-1.5 rounded-md hover:bg-secondary-500 hover:text-white transition-all">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-[40px] text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="ri-bookmark-line text-4xl mb-4 text-gray-300"></i>
                                            <h3 class="text-lg font-medium mb-2">No bookings found</h3>
                                            <p class="text-sm">Start exploring our deals to make your first booking!</p>
                                            <a href="{{ route('home') }}" class="mt-4 bg-primary-500 text-white px-4 py-2 rounded-md hover:bg-primary-600 transition-all">
                                                Explore Deals
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($bookings) && $bookings->hasPages())
                    <div class="px-[20px] py-[12px] md:py-[14px] rounded-b-md border-l border-r border-b border-gray-100 dark:border-[#172036] flex items-center justify-center">
                        {{ $bookings->links('pagination::tailwind') }}
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
