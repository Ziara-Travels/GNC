@extends('layouts.admin-dashboard')

@section('content')

  <!-- Main Content -->
<div class="main-content transition-all flex flex-col overflow-hidden min-h-screen" id="main-content">

    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] !pb-0 rounded-md">
        <div class="trezo-card-content">

            <!-- Stats Stats -->
            <div class="trezo-card rounded-md mb-[25px]">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="mb-0 md:text-xl flex items-center font-normal">
                            Welcome Back, <span class="text-primary-500 font-medium">{{ $user->name ?? 'Admin' }}</span>
                            <img src="assets/images/icons/dog.svg" class="inline-block ltr:ml-[5px] rtl:mr-[5px]" alt="dog">
                        </h5>
                    </div>
                    <div class="trezo-card-subtitle sm:flex items-center mt-[10px] sm:mt-0">
                        <button type="button" class="ltr:mr-[10px] rtl:ml-[10px] rounded-md inline-block transition-all py-[3.5px] px-[17px] border border-gray-100 dark:border-[#172036] hover:border-primary-500 hover:bg-primary-500 hover:text-white">
                            <span class="inline-block relative ltr:pl-[26px] rtl:pr-[26px]">
                                <i class="ri-calendar-line absolute text-lg top-1/2 -translate-y-1/2 ltr:left-0 rtl:right-0"></i>
                                {{ \Carbon\Carbon::now()->startOfMonth()->format('M d, Y') }} - {{ \Carbon\Carbon::now()->endOfMonth()->format('M d, Y') }}
                            </span>
                        </button>
                        <button type="button" class="inline-block rounded-md transition-all bg-primary-500 text-white py-[4.5px] px-[17px] hover:bg-primary-400 mt-[10px] sm:mt-0">
                            <span class="inline-block relative ltr:pl-[26px] rtl:pr-[26px]">
                                <i class="ri-download-2-line absolute text-lg top-1/2 -translate-y-1/2 ltr:left-0 rtl:right-0"></i>
                                Export CSV
                            </span>
                        </button>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-[25px]">

                        <!-- Total Sales -->
                        <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                            <div class="flex items-center">
                                <div class="bg-primary-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                    <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-primary-500"></span>
                                    <i class="ri-shopping-cart-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                </div>
                                <div>
                                    <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                        ${{ number_format($totalRevenue, 2) }}
                                    </h6>
                                    <span class="block">
                                        Total Sales
                                    </span>
                                </div>
                            </div>
                            <div class="mt-[25px] md:mt-[45px] flex items-center">
                                <span class="font-medium inline-block relative text-xs rounded-full border border-success-300 text-success-700 bg-success-100 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                    <i class="ri-arrow-up-fill absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                    {{ $apiStats['booking_conversion_rate'] ?? '0' }}%
                                </span>
                                <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                    Conversion Rate
                                </span>
                            </div>
                            <div class="absolute left-0 right-0 -bottom-[30px] ltr:-ml-[12px] rtl:-mr-[12px] ltr:-mr-[10px] rtl:-ml-[10px]">
                                <div id="salesTotalSalesChart"></div>
                            </div>
                        </div>

                        <!-- Total Orders -->
                        <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                            <div class="flex items-center">
                                <div class="bg-purple-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                    <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-purple-500"></span>
                                    <i class="ri-shopping-bag-3-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                </div>
                                <div>
                                    <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                        {{ $totalBookings }}
                                    </h6>
                                    <span class="block">
                                        Total Orders
                                    </span>
                                </div>
                            </div>
                            <div class="mt-[25px] md:mt-[45px] flex items-center">
                                <span class="font-medium inline-block relative text-xs rounded-full border border-success-300 text-success-700 bg-success-100 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                    <i class="ri-arrow-up-fill absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                    {{ $completedBookings }}
                                </span>
                                <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                    Completed
                                </span>
                            </div>
                            <div class="absolute -bottom-[31px] ltr:left-0 rtl:right-0 ltr:right-[10px] rtl:left-[10px]">
                                <div id="salesTotalOrdersChart"></div>
                            </div>
                        </div>

                        <!-- Total Profit -->
                        <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                            <div class="flex items-center">
                                <div class="bg-secondary-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                    <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-secondary-500"></span>
                                    <i class="ri-wallet-2-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                </div>
                                <div>
                                    <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                        ${{ number_format($flightRevenue + $hotelRevenue, 2) }}
                                    </h6>
                                    <span class="block">
                                        Total Profit
                                    </span>
                                </div>
                            </div>
                            <div class="mt-[25px] md:mt-[45px] flex items-center">
                                <span class="font-medium inline-block relative text-xs rounded-full border border-danger-300 text-orange-600 bg-danger-200 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                    <i class="ri-arrow-down-fill absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                    {{ $failedPayments }}
                                </span>
                                <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                    Failed Payments
                                </span>
                            </div>
                            <div class="absolute left-0 right-0 -bottom-[30px] ltr:-ml-[12px] rtl:-mr-[12px] ltr:-mr-[10px] rtl:-ml-[10px]">
                                <div id="salesTotalProfitChart"></div>
                            </div>
                        </div>

                        <!-- Total Revenue -->
                        <div class="relative rounded-md p-[20px] md:p-[25px] !pb-[65px] border border-gray-100 dark:border-[#172036]">
                            <div class="flex items-center">
                                <div class="bg-orange-100 dark:bg-[#15203c] text-white text-center relative z-[1] ltr:mr-[15px] rtl:ml-[15px] rounded-[5px] text-[25px] w-[55px] h-[55px]">
                                    <span class="inset-0 -z-[1] m-[8px] absolute rounded-[5px] bg-orange-500"></span>
                                    <i class="ri-money-dollar-circle-line absolute left-0 right-0 top-1/2 -translate-y-1/2"></i>
                                </div>
                                <div>
                                    <h6 class="text-lg md:text-xl mb-[2px] font-medium">
                                        ${{ number_format($totalRevenue, 2) }}
                                    </h6>
                                    <span class="block">
                                        Total Revenue
                                    </span>
                                </div>
                            </div>
                            <div class="mt-[25px] md:mt-[45px] flex items-center">
                                <span class="font-medium inline-block relative text-xs rounded-full border border-success-300 text-success-700 bg-success-100 dark:bg-[#15203c] dark:border-[#15203c] py-[2px] ltr:pl-[20px] rtl:pr-[20px] ltr:pr-[10px] rtl:pl-[10px]">
                                    <i class="ri-arrow-up-fill absolute top-1/2 -translate-y-1/2 text-base -mt-px ltr:left-[6px] rtl:right-[6px]"></i>
                                    {{ $successfulPayments }}
                                </span>
                                <span class="block ltr:ml-[10px] rtl:mr-[10px] text-xs">
                                    Successful Payments
                                </span>
                            </div>
                            <div class="absolute left-[15px] right-[15px] -bottom-[30px]">
                                <div id="salesTotalRevenueChart"></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">

                    <!-- Recent Earnings -->
                    <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="mb-0">
                                    Recent Earnings
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block transition-all hover:text-primary-500" id="dropdownToggleBtn">
                                        <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                                            This Week
                                            <i class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"></i>
                                        </span>
                                    </button>
                                    <!-- Dropdown menu omitted for brevity -->
                                </div>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="-mt-[5px] -mb-[27px] ltr:-ml-[16px] rtl:-mr-[16px]">
                                <div id="salesRecentEarningsChart"></div>
                                <div>
                                    <ul>
                                        @foreach($recentBookings as $booking)
                                            <li>
                                                <span>{{ $booking->user->name ?? 'N/A' }}</span> -
                                                <span>${{ number_format($booking->total_price, 2) }}</span> -
                                                <span>{{ ucfirst($booking->status) }}</span>
                                                <span class="text-xs text-gray-500">{{ $booking->created_at->format('M d, Y') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="lg:col-span-1">

                    <!-- Sales by Locations -->
                    <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="mb-0">
                                    Sales by Locations
                                </h5>
                            </div>
                            <div class="trezo-card-subtitle">
                                <div class="trezo-card-dropdown relative">
                                    <button type="button" class="trezo-card-dropdown-btn inline-block transition-all text-[26px] text-gray-500 dark:text-gray-400 leading-none hover:text-primary-500" id="dropdownToggleBtn">
                                        <i class="ri-more-fill"></i>
                                    </button>
                                    <!-- Dropdown menu omitted for brevity -->
                                </div>
                            </div>
                        </div>
                        <div class="trezo-card-content">
                            <div class="flex items-center justify-center min-h-[160px]">
                                <div id="salesByLocationsJsVectorMap"></div>
                            </div>
                            <div class="table-responsive overflow-x-auto mt-[9px] without-border">
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th class="font-medium text-xs ltr:text-left rtl:text-right px-[14px] pb-[8px] whitespace-nowrap">Country</th>
                                            <th class="font-medium text-xs ltr:text-right rtl:text-left px-[14px] pb-[8px] whitespace-nowrap">Orders</th>
                                            <th class="font-medium text-xs ltr:text-right rtl:text-left px-[14px] pb-[8px] whitespace-nowrap">Earnings</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-black dark:text-white">
                                        @foreach($topDestinations as $destination)
                                            <tr>
                                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[14px] py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                    {{ $destination->destination_city }}
                                                </td>
                                                <td class="font-medium ltr:text-right rtl:text-left whitespace-nowrap px-[14px] py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                    {{ $destination->bookings_count }}
                                                </td>
                                                <td class="font-medium ltr:text-right rtl:text-left whitespace-nowrap px-[14px] py-[11px] border-b border-gray-100 dark:border-[#172036]">
                                                    ${{ number_format($destination->earnings ?? 0, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Advanced Admin Dashboard Features -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-[25px] mb-[25px]">
                
                <!-- Real-Time Dashboard Overview -->
                <div class="trezo-card border border-blue-200 dark:border-blue-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/dashboard/real-time'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-500 text-white rounded-full p-3">
                            <i class="ri-dashboard-3-line text-xl"></i>
                        </div>
                        <span class="text-blue-600 dark:text-blue-300 text-xs font-medium bg-blue-200 dark:bg-blue-700 px-2 py-1 rounded-full">LIVE</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Real-Time Overview</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Monitor live bookings, payments, and system performance with auto-refresh capabilities.</p>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium" data-live-stat="active-sessions">{{ $liveStats['active_sessions'] ?? 156 }}</span>
                            <span class="text-gray-500">Active Sessions</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-green-600" data-live-stat="api-health">{{ $liveStats['api_health'] ?? '99.8' }}%</span>
                            <span class="text-gray-500">API Health</span>
                        </div>
                    </div>
                    <div class="flex items-center text-blue-600 dark:text-blue-300 text-sm font-medium">
                        <span>View Dashboard</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Advanced User Management -->
                <div class="trezo-card border border-purple-200 dark:border-purple-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/users/management'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-500 text-white rounded-full p-3">
                            <i class="ri-team-line text-xl"></i>
                        </div>
                        <span class="text-purple-600 dark:text-purple-300 text-xs font-medium bg-purple-200 dark:bg-purple-700 px-2 py-1 rounded-full">USERS</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">User Management</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Create, edit, and manage users with advanced role restrictions and bulk operations.</p>
                    <div class="grid grid-cols-3 gap-1 text-xs mb-3">
                        <div class="bg-white dark:bg-gray-700 p-2 rounded text-center">
                            <span class="block font-medium">{{ $userStats['total_users'] ?? 1247 }}</span>
                            <span class="text-gray-500">Total</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded text-center">
                            <span class="block font-medium text-green-600">{{ $userStats['active_users'] ?? 892 }}</span>
                            <span class="text-gray-500">Active</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded text-center">
                            <span class="block font-medium text-blue-600">{{ $userStats['new_today'] ?? 12 }}</span>
                            <span class="text-gray-500">New</span>
                        </div>
                    </div>
                    <div class="flex items-center text-purple-600 dark:text-purple-300 text-sm font-medium">
                        <span>Manage Users</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Comprehensive Booking Management -->
                <div class="trezo-card border border-green-200 dark:border-green-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/bookings/management'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-500 text-white rounded-full p-3">
                            <i class="ri-bookmark-3-line text-xl"></i>
                        </div>
                        <span class="text-green-600 dark:text-green-300 text-xs font-medium bg-green-200 dark:bg-green-700 px-2 py-1 rounded-full">BOOKINGS</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Booking Control</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Full booking lifecycle control with real-time monitoring and modification tools.</p>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-green-600">{{ $bookingStats['pending'] ?? 23 }}</span>
                            <span class="text-gray-500">Pending</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-orange-600">{{ $bookingStats['processing'] ?? 8 }}</span>
                            <span class="text-gray-500">Processing</span>
                        </div>
                    </div>
                    <div class="flex items-center text-green-600 dark:text-green-300 text-sm font-medium">
                        <span>Manage Bookings</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Intelligent Notification Center -->
                <div class="trezo-card border border-orange-200 dark:border-orange-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900 dark:to-orange-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/notifications/center'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-500 text-white rounded-full p-3">
                            <i class="ri-notification-3-line text-xl"></i>
                        </div>
                        <span class="text-orange-600 dark:text-orange-300 text-xs font-medium bg-orange-200 dark:bg-orange-700 px-2 py-1 rounded-full">ALERTS</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Notification Hub</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Advanced notification management with real-time alerts and broadcast messaging.</p>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-red-600">{{ $notifications['unread'] ?? 15 }}</span>
                            <span class="text-gray-500">Unread</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-blue-600">{{ $notifications['broadcasts'] ?? 3 }}</span>
                            <span class="text-gray-500">Broadcasts</span>
                        </div>
                    </div>
                    <div class="flex items-center text-orange-600 dark:text-orange-300 text-sm font-medium">
                        <span>View Notifications</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Advanced Search & Booking Tools -->
                <div class="trezo-card border border-indigo-200 dark:border-indigo-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900 dark:to-indigo-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/search/advanced'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-indigo-500 text-white rounded-full p-3">
                            <i class="ri-search-2-line text-xl"></i>
                        </div>
                        <span class="text-indigo-600 dark:text-indigo-300 text-xs font-medium bg-indigo-200 dark:bg-indigo-700 px-2 py-1 rounded-full">SEARCH</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Advanced Search</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Priority flight search with admin privileges and bulk booking management tools.</p>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium">{{ $searchStats['admin_bookings'] ?? 45 }}</span>
                            <span class="text-gray-500">Admin Bookings</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-purple-600">{{ $searchStats['bulk_operations'] ?? 7 }}</span>
                            <span class="text-gray-500">Bulk Ops</span>
                        </div>
                    </div>
                    <div class="flex items-center text-indigo-600 dark:text-indigo-300 text-sm font-medium">
                        <span>Advanced Search</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- AI-Powered Analytics -->
                <div class="trezo-card border border-pink-200 dark:border-pink-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-900 dark:to-pink-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/analytics/ai'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-pink-500 text-white rounded-full p-3">
                            <i class="ri-brain-line text-xl"></i>
                        </div>
                        <span class="text-pink-600 dark:text-pink-300 text-xs font-medium bg-pink-200 dark:bg-pink-700 px-2 py-1 rounded-full">AI</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">AI Analytics</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Machine learning insights with predictive booking trends and fraud detection.</p>
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-green-600">{{ $aiStats['predictions'] ?? '94.2' }}%</span>
                            <span class="text-gray-500">Accuracy</span>
                        </div>
                        <div class="bg-white dark:bg-gray-700 p-2 rounded">
                            <span class="block font-medium text-red-600">{{ $aiStats['fraud_detected'] ?? 3 }}</span>
                            <span class="text-gray-500">Fraud Alerts</span>
                        </div>
                    </div>
                    <div class="flex items-center text-pink-600 dark:text-pink-300 text-sm font-medium">
                        <span>View Analytics</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

            </div>

            <!-- Additional Feature Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-[25px] mb-[25px]">
                
                <!-- Financial Management -->
                <div class="trezo-card border border-emerald-200 dark:border-emerald-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900 dark:to-emerald-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/finance/management'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-emerald-500 text-white rounded-full p-3">
                            <i class="ri-funds-line text-xl"></i>
                        </div>
                        <span class="text-emerald-600 dark:text-emerald-300 text-xs font-medium bg-emerald-200 dark:bg-emerald-700 px-2 py-1 rounded-full">FINANCE</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Financial Control</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Revenue analytics, payment management, and financial forecasting tools.</p>
                    <div class="flex items-center text-emerald-600 dark:text-emerald-300 text-sm font-medium">
                        <span>Manage Finances</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- System Health Monitor -->
                <div class="trezo-card border border-cyan-200 dark:border-cyan-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-900 dark:to-cyan-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/system/health'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-cyan-500 text-white rounded-full p-3">
                            <i class="ri-heart-pulse-line text-xl"></i>
                        </div>
                        <span class="text-cyan-600 dark:text-cyan-300 text-xs font-medium bg-cyan-200 dark:bg-cyan-700 px-2 py-1 rounded-full">SYSTEM</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">System Health</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Monitor server performance, database optimization, and security threats.</p>
                    <div class="flex items-center text-cyan-600 dark:text-cyan-300 text-sm font-medium">
                        <span>System Status</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Customer Support Hub -->
                <div class="trezo-card border border-amber-200 dark:border-amber-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900 dark:to-amber-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/support/hub'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-amber-500 text-white rounded-full p-3">
                            <i class="ri-customer-service-2-line text-xl"></i>
                        </div>
                        <span class="text-amber-600 dark:text-amber-300 text-xs font-medium bg-amber-200 dark:bg-amber-700 px-2 py-1 rounded-full">SUPPORT</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Support Center</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Integrated helpdesk, live chat monitoring, and customer feedback analysis.</p>
                    <div class="flex items-center text-amber-600 dark:text-amber-300 text-sm font-medium">
                        <span>Support Dashboard</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

                <!-- Security & Compliance -->
                <div class="trezo-card border border-red-200 dark:border-red-800 p-[20px] md:p-[25px] rounded-xl bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 hover:shadow-lg transition-all cursor-pointer" onclick="window.location.href='/admin/security/compliance'">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-red-500 text-white rounded-full p-3">
                            <i class="ri-shield-check-line text-xl"></i>
                        </div>
                        <span class="text-red-600 dark:text-red-300 text-xs font-medium bg-red-200 dark:bg-red-700 px-2 py-1 rounded-full">SECURITY</span>
                    </div>
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Security Center</h5>
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-3">Advanced security management, audit trails, and GDPR compliance tools.</p>
                    <div class="flex items-center text-red-600 dark:text-red-300 text-sm font-medium">
                        <span>Security Dashboard</span>
                        <i class="ri-arrow-right-line ml-2"></i>
                    </div>
                </div>

            </div>

            <!-- Advanced Analytics & Reporting Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] mb-[25px]">
                
                <!-- Real-Time Analytics Dashboard -->
                <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                        <div class="trezo-card-title">
                            <h5 class="mb-0 flex items-center">
                                <i class="ri-line-chart-line mr-2 text-blue-500"></i>
                                Real-Time Performance
                            </h5>
                        </div>
                        <div class="flex space-x-2">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                                <i class="ri-refresh-line mr-1"></i>
                                Auto-Refresh
                            </button>
                            <button class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition-colors">
                                <i class="ri-download-line mr-1"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-blue-100 text-sm">Conversion Rate</p>
                                        <p class="text-2xl font-bold" data-realtime="conversion-rate">{{ $realTimeStats['conversion_rate'] ?? '24.8' }}%</p>
                                    </div>
                                    <i class="ri-trophy-line text-3xl text-blue-200"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-green-100 text-sm">Active Bookings</p>
                                        <p class="text-2xl font-bold" data-realtime="active-bookings">{{ $realTimeStats['active_bookings'] ?? 127 }}</p>
                                    </div>
                                    <i class="ri-flight-takeoff-line text-3xl text-green-200"></i>
                                </div>
                            </div>
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-purple-100 text-sm">Revenue/Hour</p>
                                        <p class="text-2xl font-bold" data-realtime="revenue-hour">${{ $realTimeStats['revenue_hour'] ?? '2,450' }}</p>
                                    </div>
                                    <i class="ri-money-dollar-circle-line text-3xl text-purple-200"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Real-time Chart Placeholder -->
                        <div class="h-64 bg-gray-50 dark:bg-gray-800 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="ri-bar-chart-line text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">Real-time Analytics Chart</p>
                                <p class="text-xs text-gray-400">Chart.js integration coming soon</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Management Tools -->
                <div class="trezo-card border border-gray-100 dark:border-[#172036] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px]">
                        <h5 class="mb-0 flex items-center">
                            <i class="ri-tools-line mr-2 text-purple-500"></i>
                            Quick Actions
                        </h5>
                    </div>
                    <div class="trezo-card-content">
                        <div class="grid grid-cols-1 gap-3">
                            <!-- Quick Action Buttons -->
                            <div class="grid grid-cols-2 gap-3">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-lg transition-colors text-left">
                                    <i class="ri-user-add-line text-xl mb-2"></i>
                                    <p class="font-medium">Create User</p>
                                    <p class="text-xs text-blue-100">Add new admin/user</p>
                                </button>
                                <button class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-lg transition-colors text-left">
                                    <i class="ri-flight-takeoff-line text-xl mb-2"></i>
                                    <p class="font-medium">Book Flight</p>
                                    <p class="text-xs text-green-100">Admin booking</p>
                                </button>
                                <button class="bg-purple-500 hover:bg-purple-600 text-white p-3 rounded-lg transition-colors text-left">
                                    <i class="ri-message-3-line text-xl mb-2"></i>
                                    <p class="font-medium">Broadcast</p>
                                    <p class="text-xs text-purple-100">Send to all users</p>
                                </button>
                                <button class="bg-orange-500 hover:bg-orange-600 text-white p-3 rounded-lg transition-colors text-left">
                                    <i class="ri-file-download-line text-xl mb-2"></i>
                                    <p class="font-medium">Export Data</p>
                                    <p class="text-xs text-orange-100">Generate reports</p>
                                </button>
                            </div>
                            
                            <!-- Recent Admin Activities -->
                            <div class="mt-4">
                                <h6 class="font-medium mb-3 text-gray-800 dark:text-white">Recent Admin Activities</h6>
                                <div class="space-y-2 max-h-32 overflow-y-auto">
                                    @php
                                        $recentActivities = $adminActivities ?? [
                                            ['action' => 'Created new user', 'user' => 'John Doe', 'time' => '2 minutes ago'],
                                            ['action' => 'Modified booking', 'user' => 'Jane Smith', 'time' => '5 minutes ago'],
                                            ['action' => 'Sent broadcast notification', 'user' => 'System', 'time' => '10 minutes ago'],
                                            ['action' => 'Exported user data', 'user' => 'Admin User', 'time' => '15 minutes ago']
                                        ];
                                    @endphp
                                    
                                    @foreach($recentActivities as $activity)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $activity['action'] }}</p>
                                            <p class="text-xs text-gray-500">by {{ $activity['user'] }}</p>
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="grow"></div>

            <!-- Footer -->
            <footer class="bg-white dark:bg-[#0c1427] rounded-t-md px-[20px] md:px-[25px] py-[15px] md:py-[20px] text-center">
                <p>
                    © <span class="text-purple-500">Trezo</span> is Proudly Owned by <a href="https://envytheme.com/" target="_blank" class="text-primary-500 transition-all hover:underline">EnvyTheme</a>
                </p>
            </footer>
            
        </div>
        <!-- End Main Content -->

@endsection

@section('scripts')
<script>
// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Dashboard Loaded');
    
    // Initialize any dashboard-specific functionality
    initializeAdminDashboard();
    
    // Set up real-time dashboard updates
    setupDashboardUpdates();
});

function initializeAdminDashboard() {
    // Add any dashboard-specific JavaScript here
    console.log('Admin dashboard initialized for user: {{ $user->name ?? "Unknown" }}');
}

function setupDashboardUpdates() {
    // Set up periodic refresh for dashboard stats
    setInterval(refreshDashboardStats, 30000); // Refresh every 30 seconds
    
    // Set up Pusher for real-time updates (if available)
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private('dashboard.admin')
            .listen('booking.updated', (e) => {
                console.log('Booking updated event received', e);
                refreshDashboardStats();
            });
    }
}

function refreshDashboardStats() {
    // Fetch updated stats using AJAX
    fetch('/admin/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            // Update booking stats
            if (data.booking_stats) {
                updateBookingStatsDisplay(data.booking_stats);
            }
            
            // Update payment stats
            if (data.payment_stats) {
                updatePaymentStatsDisplay(data.payment_stats);
            }
        })
        .catch(error => {
            console.error('Error refreshing dashboard stats:', error);
        });
}

function updateBookingStatsDisplay(stats) {
    // Update booking stats in the UI
    const elements = {
        'all': document.querySelector('[data-stat="bookings-all"]'),
        'completed': document.querySelector('[data-stat="bookings-completed"]'),
        'failed': document.querySelector('[data-stat="bookings-failed"]'),
        'processing': document.querySelector('[data-stat="bookings-processing"]')
    };
    
    // Update each element if it exists
    Object.keys(elements).forEach(key => {
        if (elements[key] && stats[key] !== undefined) {
            elements[key].textContent = stats[key];
        }
    });
}

function updatePaymentStatsDisplay(stats) {
    // Update payment stats in the UI
    const elements = {
        'total': document.querySelector('[data-stat="payments-total"]'),
        'completed': document.querySelector('[data-stat="payments-completed"]'),
        'failed': document.querySelector('[data-stat="payments-failed"]'),
        'pending': document.querySelector('[data-stat="payments-pending"]')
    };
    
    // Update each element if it exists
    Object.keys(elements).forEach(key => {
        if (elements[key] && stats[key] !== undefined) {
            elements[key].textContent = stats[key];
        }
    });
}
</script>
@endsection
