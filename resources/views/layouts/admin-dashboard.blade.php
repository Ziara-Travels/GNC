<!DOCTYPE html>
<html dir="ltr">
    <head>
        <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Links Of CSS File -->
        <link rel="stylesheet" href="{{ asset('backend/css/remixicon.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/apexcharts.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/simplebar.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/prism.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/jsvectormap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/swiper-bundle.min.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/quill.snow.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">

        <!-- Favicon -->
		<link rel="icon" type="{{ asset('images/logo.png') }}" href="{{ asset('images/logo.png') }}">

        <!-- Title -->
        <title>Ziara Travels -  Admin Dashboard </title>

        <!-- Font Family -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

        <!-- Material Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    </head>
    <body>

      <!-- Sidebar -->
<div class="sidebar-area bg-white dark:bg-[#0c1427] fixed overflow-hidden z-[7] top-0 h-screen transition-all rounded-r-md" id="sidebar-area">
    <div class="logo bg-white dark:bg-[#0c1427] border-b border-gray-100 dark:border-[#172036] px-[25px] pt-[19px] pb-[15px] absolute z-[2] right-0 top-0 left-0">
        <a href="{{ route('admin.dashboard') }}" class="transition-none relative flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="logo-icon">
        </a>
        <button type="button" class="burger-menu inline-block absolute z-[3] top-[24px] ltr:right-[25px] rtl:left-[25px] transition-all hover:text-primary-500" id="hide-sidebar-toggle2">
            <i class="material-symbols-outlined">
                close
            </i>
        </button>
    </div>
    <div class="pt-[89px] px-[25px] pb-[20px] h-screen" data-simplebar>
        <div class="accordion">
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ route('admin.dashboard') }}" class="accordion-button toggle open active flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        dashboard
                    </i>
                    <span class="title leading-none">
                        Dashboard
                    </span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ route('admin.home') }}" class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        dashboard
                    </i>
                    <span class="title leading-none">
                        Home
                    </span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        people
                    </i>
                    <span class="title leading-none">
                        Users
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.users.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.users.user') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.users.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        book_online
                    </i>
                    <span class="title leading-none">
                        Bookings
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.bookings.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.bookings.user') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    User
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.bookings.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        flight
                    </i>
                    <span class="title leading-none">
                        Flights
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.flights.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.flights.users') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.flights.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        hotel
                    </i>
                    <span class="title leading-none">
                        Hotels
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.hotels.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.hotels.users') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.hotels.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Flight Search -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        flight
                    </i>
                    <span class="title leading-none">
                        Search Flights
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.flights.search.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.flights.search.users') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.flights.search.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Hotel Search -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        hotel
                    </i>
                    <span class="title leading-none">
                        Search Hotels
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.hotels.search.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.hotels.search.users') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.hotels.search.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Roles & Permissions -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        admin_panel_settings
                    </i>
                    <span class="title leading-none">
                        Roles & Permissions
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <span class="font-semibold text-xs text-gray-400 block mb-[2px]">Roles</span>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.roles.add') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Add Role
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.roles.index') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    View All Roles
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <span class="font-semibold text-xs text-gray-400 block mb-[2px]">Permissions</span>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.permissions.add') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Add Permission
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.permissions.index') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    View All Permissions
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        payment
                    </i>
                    <span class="title leading-none">
                        Payments
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.payments.my') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    My Payments
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.payments.banks') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Banks
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.payments.mpesa') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    M-PESA
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.payments.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Activities -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        local_activity
                    </i>
                    <span class="title leading-none">
                        Activities
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.activity.my') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    My Activities
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.activity.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.activity.user') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.activity.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        notifications
                    </i>
                    <span class="title leading-none">
                        Notifications
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.notifications.my') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    My Notifications
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.notifications.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.notifications.user') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.notifications.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Wishlists -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        favorite
                    </i>
                    <span class="title leading-none">
                        Wishlists
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.wishlist.my') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    My Wishlists
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.wishlist.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.wishlist.user') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.wishlist.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Profiles -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        manage_accounts
                    </i>
                    <span class="title leading-none">
                        Profiles
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.profile.my') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    My Profile
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.profile.admin') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Admin
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.profile.user') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Users
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.profile.all') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    All
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Add more admin sections as needed, following the same pattern -->
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ route('admin.referrals') }}" class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        people_alt
                    </i>
                    <span class="title leading-none">
                        Referrals
                    </span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ route('admin.calendar') }}" class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        date_range
                    </i>
                    <span class="title leading-none">
                        Calendar
                    </span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ route('admin.rewards.loyalties') }}" class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        card_giftcard
                    </i>
                    <span class="title leading-none">
                        Rewards and Loyalties
                    </span>
                </a>
            </div>
            <span class="block relative font-medium uppercase text-gray-400 mb-[10px] text-xs [&:not(:first-child)]:mt-[22px]">
                Others
            </span>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ route('admin.my.profile') }}" class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        account_circle
                    </i>
                    <span class="title leading-none">
                        My Profile
                    </span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]" type="button">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        settings
                    </i>
                    <span class="title leading-none">
                        Settings
                    </span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.settings.account') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Account Settings
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.settings.change.password') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Change Password
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.settings.connections') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Connections
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.settings.privacy.policy') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Privacy Policy
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a href="{{ route('admin.settings.terms.conditions') }}" class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]">
                                    Terms & Conditions
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        logout
                    </i>
                    <span class="title leading-none">
                        Logout
                    </span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Sidebar -->

<!-- Header -->
<div class="header-area bg-white dark:bg-[#0c1427] py-[13px] px-[20px] md:px-[25px] fixed top-0 z-[6] rounded-b-md transition-all" id="header-area">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex items-center justify-center md:justify-normal">
            <div class="relative leading-none top-px ltr:mr-[13px] ltr:md:mr-[18px] ltr:lg:mr-[23px] rtl:ml-[13px] rtl:md:ml-[18px] rtl:lg:ml-[23px]">
                <button type="button" class="hide-sidebar-toggle transition-all inline-block hover:text-primary-500" id="hide-sidebar-toggle">
                    <i class="material-symbols-outlined !text-[20px]">
                        menu
                    </i>
                </button>
            </div>
            <form class="relative w-[250px] lg:w-[260px]" action="{{ route('admin.flights.search.all') }}" method="GET">
                <input type="text" name="search" placeholder="Search here....." class="bg-gray-50 border border-gray-50 h-[44px] rounded-md w-full block text-black pt-[11px] pb-[12px] px-[13px] md:px-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                <button type="submit" class="absolute text-primary-500 mt-[2px] ltr:right-[13px] ltr:md:right-[15px] rtl:left-[13px] rtl:md:left-[15px] top-1/2 -translate-y-1/2">
                    <i class="material-symbols-outlined !text-[20px]">
                        search
                    </i>
                </button>
            </form>
        </div>
        <ul class="flex items-center justify-center md:justify-normal mt-[13px] md:mt-0">
            <li class="relative mx-[8px] md:mx-[10px] lg:mx-[12px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                <button type="button" class="light-dark-toggle leading-none inline-block transition-all relative top-[2px] text-[#fe7a36]" id="light-dark-toggle">
                    <i class="material-symbols-outlined !text-[20px] md:!text-[22px]">
                        light_mode
                    </i>
                </button>
            </li>
            <li class="relative language-menu mx-[8px] md:mx-[10px] lg:mx-[12px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                <button type="button" class="leading-none pr-[12px] inline-block transition-all relative top-[2px] hover:text-primary-500" id="dropdownToggleBtn">
                    <i class="material-symbols-outlined !text-[20px] md:!text-[22px]">
                        translate
                    </i>
                    <i class="ri-arrow-down-s-line text-[15px] absolute -right-[3px] top-1/2 -translate-y-1/2 -mt-[2px]"></i>
                </button>
                <div class="language-menu-dropdown bg-white dark:bg-[#0c1427] transition-all shadow-3xl dark:shadow-none pt-[13px] md:pt-[14px] absolute mt-[18px] md:mt-[21px] w-[200px] md:w-[240px] z-[1] top-full ltr:left-0 ltr:md:left-auto ltr:lg:right-0 rtl:right-0 rtl:md:right-auto rtl:lg:left-0 rounded-md">
                    <span class="block text-black dark:text-white font-semibold px-[20px] pb-[14px] text-sm md:text-[15px]">
                        Choose Language
                    </span>
                    <ul>
                        <li class="border-t border-dashed border-gray-100 dark:border-[#172036]">
                            <button type="button" class="text-black dark:text-white px-[20px] py-[12px] d-block w-full font-medium bg-primary-50 dark:bg-primary-900">
                                <div class="flex items-center">
                                    <img src="{{ asset('assets/images/flags/usa.svg') }}" class="w-[30px] ltr:mr-[10px] rtl:ml-[10px]" alt="usa">
                                    English (Default)
                                </div>
                            </button>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="relative mx-[8px] md:mx-[10px] lg:mx-[12px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                <button type="button" class="leading-none inline-block transition-all relative top-[2px] hover:text-primary-500" id="fullscreenBtn">
                    <i class="material-symbols-outlined !text-[22px] md:!text-[24px]" id="fullscreenIcon">
                        fullscreen
                    </i>
                </button>
            </li>
            <li class="relative notifications-menu mx-[8px] md:mx-[10px] lg:mx-[12px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                <button type="button" class="leading-none inline-block transition-all relative top-[2px] hover:text-primary-500" id="dropdownToggleBtn">
                    <i class="material-symbols-outlined !text-[22px] md:!text-[24px]">
                        notifications
                    </i>
                    @php
                        $unreadNotifications = auth()->user()->notifications()->unread()->count();
                    @endphp
                    @if($unreadNotifications > 0)
                        <span class="top-[3px] ltr:right-[4px] rtl:left-[4px] w-[6px] h-[6px] rounded-full absolute bg-orange-500"></span>
                    @endif
                </button>
                <div class="notifications-menu-dropdown bg-white dark:bg-[#0c1427] transition-all shadow-3xl dark:shadow-none py-[17px] absolute mt-[17px] md:mt-[20px] w-[290px] md:w-[350px] z-[1] top-full ltr:-right-[120px] ltr:md:right-0 rtl:-left-[120px] rtl:md:left-0 rounded-md">
                    <div class="flex items-center justify-between px-[20px] pb-[17px]">
                        <span class="font-semibold text-black dark:text-white text-[15px]">
                            Notifications
                            <span class="text-gray-500 dark:text-gray-400 font-normal text-base">
                                ({{ $unreadNotifications }})
                            </span>
                        </span>
                        @if($unreadNotifications > 0)
                            <a href="{{ route('admin.notifications.mark-all-read') }}" class="text-primary-500">
                                Mark All Read
                            </a>
                        @endif
                    </div>
                    <ul class="mb-[18px]">
                        @php
                            $notifications = auth()->user()->notifications()->latest()->take(3)->get();
                        @endphp
                        @forelse($notifications as $notification)
                            <li class="relative border-b border-gray-100 dark:border-[#172036] border-dashed py-[17px] ltr:pl-[75px] ltr:pr-[20px] rtl:pr-[75px] rtl:pl-[20px] first:border-t first:border-gray-100 dark:first:border-[#172036] {{ $notification->read_at ? '' : 'bg-primary-50 dark:bg-primary-900/20' }}">
                                <div class="rounded-full flex items-center justify-center absolute text-center transition-all top-1/2 -translate-y-1/2 ltr:left-[20px] rtl:right-[20px] w-[44px] h-[44px] text-primary-500 bg-[#4936f50d]">
                                    <i class="material-symbols-outlined !text-[22px]">
                                        @switch($notification->type)
                                            @case('booking')
                                                book_online
                                                @break
                                            @case('payment')
                                                payment
                                                @break
                                            @case('user')
                                                person
                                                @break
                                            @case('system')
                                                settings
                                                @break
                                            @default
                                                notifications
                                        @endswitch
                                    </i>
                                </div>
                                <span class="block mb-[3px] text-black dark:text-white">
                                    {{ $notification->title }}
                                </span>
                                <span class="block text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                @if($notification->action_url)
                                    <a href="{{ $notification->action_url }}" class="block left-0 top-0 right-0 bottom-0 z-[1] absolute"></a>
                                @endif
                                @if(!$notification->read_at)
                                    <span class="inline-block rounded-full bg-primary-500 absolute w-[6px] h-[6px] right-[20px] top-1/2 -translate-y-1/2"></span>
                                @endif
                            </li>
                        @empty
                            <li class="text-center py-[20px] text-gray-500 dark:text-gray-400">
                                No notifications found
                            </li>
                        @endforelse
                    </ul>
                    <div class="text-center">
                        <a href="{{ route('admin.notifications.my') }}" class="inline-block font-medium relative text-primary-500 transition-all hover:underline">
                            See All Notifications
                        </a>
                    </div>
                </div>
            </li>
            <li class="relative profile-menu mx-[8px] md:mx-[10px] lg:mx-[12px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                <button type="button" class="flex items-center -mx-[5px] relative ltr:pr-[14px] rtl:pl-[14px] text-black dark:text-white" id="dropdownToggleBtn">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-[35px] h-[35px] md:w-[42px] md:h-[42px] rounded-full ltr:md:mr-[2px] ltr:lg:mr-[8px] rtl:md:ml-[2px] rtl:lg:ml-[8px] border-[2px] border-primary-200 inline-block object-cover" alt="admin-avatar">
                    @else
                        <div class="w-[35px] h-[35px] md:w-[42px] md:h-[42px] rounded-full ltr:md:mr-[2px] ltr:lg:mr-[8px] rtl:md:ml-[2px] rtl:lg:ml-[8px] border-[2px] border-primary-200 inline-block bg-primary-500 flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <span class="block font-semibold text-[0] lg:text-base">
                        {{ Auth::user()->name }}
                    </span>
                    <i class="ri-arrow-down-s-line text-[15px] absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2 mt-px"></i>
                </button>
                <div class="profile-menu-dropdown bg-white dark:bg-[#0c1427] transition-all shadow-3xl dark:shadow-none py-[22px] absolute mt-[13px] md:mt-[14px] w-[195px] z-[1] top-full ltr:right-0 rtl:left-0 rounded-md">
                    <div class="flex items-center border-b border-gray-100 dark:border-[#172036] pb-[12px] mx-[20px] mb-[10px]">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-full w-[31px] h-[31px] ltr:mr-[9px] rtl:ml-[9px] border-2 border-primary-200 inline-block object-cover" alt="admin-avatar">
                        @else
                            <div class="rounded-full w-[31px] h-[31px] ltr:mr-[9px] rtl:ml-[9px] border-2 border-primary-200 inline-block bg-primary-500 flex items-center justify-center">
                                <span class="text-white font-semibold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="block text-black dark:text-white font-medium">
                                {{ Auth::user()->name }}
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>
                    </div>
                    <ul>
                        <li>
                            <a href="{{ route('admin.my.profile') }}" class="block relative py-[7px] ltr:pl-[50px] ltr:pr-[20px] rtl:pr-[50px] rtl:pl-[20px] text-black dark:text-white transition-all hover:text-primary-500">
                                <i class="material-symbols-outlined top-1/2 -translate-y-1/2 !text-[22px] absolute ltr:left-[20px] rtl:right-[20px]">
                                    account_circle
                                </i>
                                My Profile
                            </a>
                        </li>
                    </ul>
                    <div class="border-t border-gray-100 dark:border-[#172036] mx-[20px] my-[9px]"></div>
                    <ul>
                        <li>
                            <a href="{{ route('admin.settings.account') }}" class="block relative py-[7px] ltr:pl-[50px] ltr:pr-[20px] rtl:pr-[50px] rtl:pl-[20px] text-black dark:text-white transition-all hover:text-primary-500">
                                <i class="material-symbols-outlined top-1/2 -translate-y-1/2 !text-[22px] absolute ltr:left-[20px] rtl:right-[20px]">
                                    settings
                                </i>
                                Settings
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block relative py-[7px] ltr:pl-[50px] ltr:pr-[20px] rtl:pr-[50px] rtl:pl-[20px] text-black dark:text-white transition-all hover:text-primary-500">
                                <i class="material-symbols-outlined top-1/2 -translate-y-1/2 !text-[22px] absolute ltr:left-[20px] rtl:right-[20px]">
                                    support
                                </i>
                                Support
                            </a>
                        </li>
                        
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block relative py-[7px] ltr:pl-[50px] ltr:pr-[20px] rtl:pr-[50px] rtl:pl-[20px] text-black dark:text-white transition-all hover:text-primary-500">
                                <i class="material-symbols-outlined top-1/2 -translate-y-1/2 !text-[22px] absolute ltr:left-[20px] rtl:right-[20px]">
                                    logout
                                </i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- End Header -->

        <!-- Main Content -->
        @yield('content')
        <!-- End Main Content -->
        
        <!-- Links Of JS File -->
        <script src="{{ asset('backend/js/apexcharts.min.js') }}"></script>
        <script src="{{ asset('backend/js/fslightbox.js') }}"></script>
        <script src="{{ asset('backend/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('backend/js/prism.js') }}"></script>
        <script src="{{ asset('backend/js/clipboard.min.js') }}"></script>
        <script src="{{ asset('backend/js/swiper-bundle.min.js') }}"></script>
        <script src="{{ asset('backend/js/fullcalendar.min.js') }}"></script>
        <script src="{{ asset('backend/js/jsvectormap.min.js') }}"></script>
        <script src="{{ asset('backend/js/world-merc.js') }}"></script>
        <script src="{{ asset('backend/js/quill.min.js') }}"></script>
        <script src="{{ asset('backend/js/custom.js') }}"></script>
        
        <!-- Custom Scripts for Dynamic Functionality -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sidebar accordion functionality
            document.querySelectorAll('.accordion-button.toggle').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const item = this.closest('.accordion-item');
                    const collapse = item.querySelector('.accordion-collapse');
                    const isHidden = collapse.classList.contains('hidden');
                    
                    // Close all other accordions
                    document.querySelectorAll('.accordion-collapse').forEach(function (otherCollapse) {
                        if (otherCollapse !== collapse) {
                            otherCollapse.classList.add('hidden');
                        }
                    });
                    
                    // Toggle current accordion
                    if (isHidden) {
                        collapse.classList.remove('hidden');
                    } else {
                        collapse.classList.add('hidden');
                    }
                });
            });

            // Dropdown functionality
            document.querySelectorAll('#dropdownToggleBtn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const parent = this.closest('li');
                    const dropdown = parent.querySelector('[class*="dropdown"]');
                    
                    // Close all other dropdowns
                    document.querySelectorAll('[class*="dropdown"]').forEach(function (otherDropdown) {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.style.display = 'none';
                        }
                    });
                    
                    // Toggle current dropdown
                    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                        dropdown.style.display = 'block';
                    } else {
                        dropdown.style.display = 'none';
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function () {
                document.querySelectorAll('[class*="dropdown"]').forEach(function (dropdown) {
                    dropdown.style.display = 'none';
                });
            });

            // Real-time notification updates (if needed)
            function updateNotifications() {
                fetch('/admin/notifications/count')
                    .then(response => response.json())
                    .then(data => {
                        const notificationBadge = document.querySelector('.notifications-menu .bg-orange-500');
                        const notificationCount = document.querySelector('.notifications-menu .text-gray-500');
                        
                        if (data.unread_count > 0) {
                            if (!notificationBadge) {
                                const badge = document.createElement('span');
                                badge.className = 'top-[3px] ltr:right-[4px] rtl:left-[4px] w-[6px] h-[6px] rounded-full absolute bg-orange-500';
                                document.querySelector('.notifications-menu button').appendChild(badge);
                            }
                            if (notificationCount) {
                                notificationCount.textContent = `(${data.unread_count})`;
                            }
                        } else {
                            if (notificationBadge) {
                                notificationBadge.remove();
                            }
                            if (notificationCount) {
                                notificationCount.textContent = '(0)';
                            }
                        }
                    })
                    .catch(error => console.log('Notification update error:', error));
            }

            // Update notifications every 30 seconds
            setInterval(updateNotifications, 30000);
        });
        </script>
    </body>
</html>