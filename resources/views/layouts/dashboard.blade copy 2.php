<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Dashboard</title>

    <!-- Tailwind CSS - Primary Styling Framework -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind CSS Configuration for Custom Colors -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        secondary: {
                            500: '#e3a300',
                            600: '#d97706',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-gentle': 'bounceGentle 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideIn: {
                            '0%': { opacity: '0', transform: 'translateX(-20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons - Main Icon System -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Chart.js for Dashboard Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Font Awesome for Additional Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>

         /* Force critical styling */
        #sidebar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            width: 70px !important;
            position: fixed !important;
            height: 100vh !important;
        }

        /* Enhanced transition styles */
        #sidebar {
            transition: width 0.4s ease-in-out;
        }

        #mainContent {
            transition: margin-left 0.4s ease-in-out, width 0.4s ease-in-out;
        }

        /* Ensure all dashboard content resizes smoothly */
        .dashboard-card,
        .content-container,
        .stats-card,
        .chart-container {
            transition: all 0.4s ease-in-out;
            width: 100%;
        }

        /* Tooltip visibility when sidebar is collapsed */
        .group:hover .absolute {
            opacity: 1;
            pointer-events: auto;
        }

        /* Adjust grid layouts during transition */
        .grid {
            transition: all 0.4s ease-in-out;
        }

        .grid-expanded {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        /* Improve tooltip behavior during sidebar state changes */
        .sidebar-tooltip {
            display: none;
        }

        #sidebar[style*="width: 70px"] .group:hover .sidebar-tooltip {
            display: block;
        }

        header {
            position: sticky !important;
            top: 0 !important;
            z-index: 200 !important;
            background-color: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px) !important;
        }

        #weatherWidget {
            background: linear-gradient(90deg, #60a5fa, #3b82f6) !important;
        }

        #currentTime {
            background: linear-gradient(90deg, #10b981, #059669) !important;
        }

        /* Content transitioning styles */
        #mainContent {
            transition: margin-left 0.4s ease-in-out, width 0.4s ease-in-out;
        }

        /* Ensure all cards and content containers transition smoothly */
        .dashboard-card,
        .content-container,
        .stats-card,
        .chart-container {
            transition: all 0.4s ease-in-out;
            width: 100%;
        }

        /* Style adjustments for when sidebar is expanded */
        .dashboard-card.expanded,
        .content-container.expanded {
            width: 100%;
            max-width: 100%;
        }

        /* Adjust grid layouts when sidebar changes */
        .grid-cols-1,
        .grid-cols-2,
        .grid-cols-3,
        .grid-cols-4 {
            transition: all 0.4s ease-in-out;
        }

        /* Chart container adjustments */
        .chart-container {
            transition: width 0.4s ease-in-out, height 0.4s ease-in-out;
        }

        /* Ensure charts resize properly */
        canvas {
            max-width: 100%;
        }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #e3a300;
            --accent-color: #3a7bd5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --dark-bg: #1e293b;
            --sidebar-bg: #0f172a;
            --border-color: #e2e8f0;
            --text-muted: #64748b;
            --user-color: #059669;
            --admin-color: #dc2626;
            --superadmin-color: #7c3aed;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--light-bg);
            overflow-x: hidden;
        }

        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Enhanced Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #1e293b 100%);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        /* Logo Section */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            color: #fff;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .logo i {
            font-size: 2rem;
            margin-right: 0.75rem;
            color: var(--secondary-color);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* User Profile Section */
        .user-profile {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 3px solid;
        }

        .user-avatar.male {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .user-avatar.female {
            border-color: #ec4899;
            background: linear-gradient(135deg, #ec4899, #be185d);
        }

        .user-avatar i {
            font-size: 2rem;
            color: white;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .sidebar.collapsed .user-avatar {
            width: 40px;
            height: 40px;
            margin: 0 auto;
        }

        .sidebar.collapsed .user-avatar i {
            font-size: 1.2rem;
        }

        .user-info {
            transition: all 0.3s ease;
        }

        .user-name {
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .user-role {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-user {
            background: rgba(5, 150, 105, 0.2);
            color: var(--user-color);
            border: 1px solid var(--user-color);
        }

        .role-admin {
            background: rgba(220, 38, 38, 0.2);
            color: var(--admin-color);
            border: 1px solid var(--admin-color);
        }

        .role-superadmin {
            background: rgba(124, 58, 237, 0.2);
            color: var(--superadmin-color);
            border: 1px solid var(--superadmin-color);
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
            height: 0;
            overflow: hidden;
        }

        /* Menu Styles */
        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
        }

        .menu-item {
            position: relative;
            margin-bottom: 0.25rem;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 0 25px 25px 0;
            margin-right: 1rem;
        }

        .menu-link:hover {
            color: #fff;
            background: rgba(37, 99, 235, 0.1);
            transform: translateX(5px);
        }

        .menu-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .menu-link.active::before {
            content: '';
            position: absolute;
            right: -1rem;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--secondary-color);
            border-radius: 2px;
        }

        .menu-icon {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .menu-text {
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .menu-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .menu-link {
            justify-content: center;
            margin-right: 0;
            border-radius: 10px;
            margin: 0.25rem 0.5rem;
        }

        .sidebar.collapsed .menu-icon {
            margin-right: 0;
        }

        /* Dropdown Styles */
        .dropdown-menu-item {
            position: relative;
        }

        .dropdown-toggle::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .dropdown-toggle.collapsed::after {
            transform: rotate(-90deg);
        }

        .dropdown-menu-custom {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(0, 0, 0, 0.2);
            margin: 0.25rem 0;
            border-radius: 8px;
        }

        .dropdown-menu-custom.show {
            max-height: 300px;
        }

        .dropdown-item-custom {
            display: block;
            padding: 0.75rem 3rem;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .dropdown-item-custom:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        /* Tooltip for collapsed sidebar */
        .tooltip-custom {
            position: absolute;
            left: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--dark-bg);
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            z-index: 1001;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .tooltip-custom::before {
            content: '';
            position: absolute;
            left: -5px;
            top: 50%;
            transform: translateY(-50%);
            border: 5px solid transparent;
            border-right-color: var(--dark-bg);
        }

        .sidebar.collapsed .menu-item:hover .tooltip-custom {
            opacity: 1;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: var(--light-bg);
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Enhanced Header */
        .header {
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
        }

        .toggle-btn:hover {
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Weather Widget */
        .weather-widget {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .weather-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .weather-icon {
            font-size: 1.25rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-4px);
            }

            60% {
                transform: translateY(-2px);
            }
        }

        /* Current Time */
        .current-time {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: 'Courier New', monospace;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Language Selector */
        .language-selector {
            position: relative;
        }

        .language-btn {
            background: none;
            border: 2px solid var(--border-color);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .language-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        .language-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 150px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .language-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .language-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .language-option:last-child {
            border-bottom: none;
        }

        .language-option:hover {
            background: var(--light-bg);
            transform: translateX(5px);
        }

        .language-option.active {
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            color: white;
        }

        .flag-icon {
            width: 20px;
            height: 15px;
            border-radius: 2px;
            background-size: cover;
            background-position: center;
        }

        .flag-en {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 30"><rect width="60" height="30" fill="%23012169"/><g fill="%23FFF"><path d="M0 0v30h60V0z"/><path d="M0 0l60 30M60 0L0 30" stroke="%23C8102E" stroke-width="6"/><path d="M30 0v30M0 15h60" stroke="%23FFF" stroke-width="10"/><path d="M30 0v30M0 15h60" stroke="%23C8102E" stroke-width="6"/></g></svg>');
        }

        .flag-sw {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 30"><rect width="60" height="30" fill="%23000"/><rect y="10" width="60" height="10" fill="%23FCF"/><rect y="20" width="60" height="10" fill="%23009"/></svg>');
        }

        .flag-es {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 30"><rect width="60" height="30" fill="%23AA151B"/><rect y="7.5" width="60" height="15" fill="%23F1BF00"/></svg>');
        }

        .flag-fr {
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 30"><rect width="20" height="30" fill="%23002395"/><rect x="20" width="20" height="30" fill="%23FFF"/><rect x="40" width="20" height="30" fill="%23ED2939"/></svg>');
        }

        /* Notifications */
        .notification-bell {
            position: relative;
            background: none;
            border: 2px solid var(--border-color);
            font-size: 1.25rem;
            color: var(--text-muted);
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 50%;
            transition: all 0.3s ease;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification-bell:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            font-weight: 600;
            min-width: 20px;
            text-align: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 350px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-title {
            font-weight: 600;
            color: var(--dark-bg);
        }

        .mark-all-read {
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 0.875rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .mark-all-read:hover {
            background: var(--light-bg);
        }

        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: start;
            gap: 0.75rem;
        }

        .notification-item:hover {
            background: var(--light-bg);
        }

        .notification-item.unread {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(37, 99, 235, 0.02));
            border-left: 4px solid var(--primary-color);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .notification-icon.success {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
        }

        .notification-icon.info {
            background: linear-gradient(135deg, var(--info-color), #0891b2);
            color: white;
        }

        .notification-icon.warning {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            color: white;
        }

        .notification-content {
            flex: 1;
        }

        .notification-text {
            font-size: 0.875rem;
            color: var(--dark-bg);
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            background: linear-gradient(135deg, var(--light-bg), #e2e8f0);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .user-dropdown-btn:hover {
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .user-dropdown-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .user-dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-color);
            text-decoration: none;
            color: var(--dark-bg);
        }

        .user-dropdown-item:last-child {
            border-bottom: none;
        }

        .user-dropdown-item:hover {
            background: var(--light-bg);
            transform: translateX(5px);
            color: var(--primary-color);
        }

        .user-dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .content-area {
            padding: 2rem;
        }

        /* Custom animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .menu-item {
            animation: slideIn 0.3s ease forwards;
        }

        .menu-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .menu-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .menu-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .menu-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        .menu-item:nth-child(5) {
            animation-delay: 0.5s;
        }

        .menu-item:nth-child(6) {
            animation-delay: 0.6s;
        }

        .menu-item:nth-child(7) {
            animation-delay: 0.7s;
        }

        .menu-item:nth-child(8) {
            animation-delay: 0.8s;
        }

        .menu-item:nth-child(9) {
            animation-delay: 0.9s;
        }

        .menu-item:nth-child(10) {
            animation-delay: 1.0s;
        }

        .menu-item:nth-child(11) {
            animation-delay: 1.1s;
        }

        .menu-item:nth-child(12) {
            animation-delay: 1.2s;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .header-right {
                gap: 0.5rem;
            }

            .weather-widget,
            .current-time {
                display: none;
            }

            .notification-dropdown,
            .language-dropdown,
            .user-dropdown-menu {
                width: 90vw;
                max-width: 300px;
            }
        }

        /* Custom Scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #1d4ed8;
        }

        /* Custom Styles for Dashboard Cards */
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            cursor: pointer;
            margin-bottom: 2rem;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-color, #6366f1), var(--card-color-light, #818cf8));
            transition: height 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .stats-card:hover::before {
            height: 6px;
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .stats-card:hover .card-overlay {
            opacity: 1;
        }

        .card-body {
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .card-content {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .stats-card:hover .icon-wrapper {
            transform: rotate(5deg) scale(1.1);
        }

        .icon-wrapper i {
            font-size: 2rem;
            color: white;
            transition: all 0.3s ease;
        }

        .stats-info {
            flex: 1;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            color: #1e293b;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .stats-card:hover .stats-number {
            transform: scale(1.05);
        }

        .stats-label {
            font-size: 1rem;
            color: #64748b;
            margin: 0.5rem 0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .stats-trend i {
            font-size: 0.875rem;
        }

        .trend-text {
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stats-trend.positive {
            color: #10b981;
        }

        .stats-trend.negative {
            color: #ef4444;
        }

        .card-progress {
            margin-top: 1rem;
        }

        .progress {
            height: 8px;
            border-radius: 10px;
            background-color: #e2e8f0;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Card Color Variants */
        .pending-card {
            --card-color: #f59e0b;
            --card-color-light: #fbbf24;
        }

        .pending-icon {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
        }

        .pending-progress {
            background: linear-gradient(90deg, #f59e0b, #fbbf24);
        }

        .earnings-card {
            --card-color: #10b981;
            --card-color-light: #34d399;
        }

        .earnings-icon {
            background: linear-gradient(135deg, #10b981, #34d399);
        }

        .earnings-progress {
            background: linear-gradient(90deg, #10b981, #34d399);
        }

        .bookings-card,
        .total-card {
            --card-color: #6366f1;
            --card-color-light: #818cf8;
        }

        .bookings-icon,
        .total-icon {
            background: linear-gradient(135deg, #6366f1, #818cf8);
        }

        .bookings-progress,
        .total-progress {
            background: linear-gradient(90deg, #6366f1, #818cf8);
        }

        .completed-card {
            --card-color: #059669;
            --card-color-light: #10b981;
        }

        .completed-icon {
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .completed-progress {
            background: linear-gradient(90deg, #059669, #10b981);
        }

        .cancelled-card {
            --card-color: #dc2626;
            --card-color-light: #ef4444;
        }

        .cancelled-icon {
            background: linear-gradient(135deg, #dc2626, #ef4444);
        }

        .cancelled-progress {
            background: linear-gradient(90deg, #dc2626, #ef4444);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-content {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .icon-wrapper {
                margin-right: 0;
                margin-bottom: 1rem;
                width: 60px;
                height: 60px;
            }

            .icon-wrapper i {
                font-size: 1.5rem;
            }

            .stats-number {
                font-size: 2rem;
            }
        }

        /* Full width container styling */
        .full-width-container {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .max-w-8xl {
            max-width: 90rem;
        }

        /* Greeting Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-card {
            animation: fadeInUp 0.6s ease-out;
        }


        /* Charts and Table Styles */
        .chart-legend {
            text-align: center;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 auto 0.25rem;
        }

        .legend-label {
            display: block;
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.125rem;
        }

        .legend-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
        }

        /* Recent Bookings Table Styles */
        .recent-bookings-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .recent-bookings-table::-webkit-scrollbar {
            width: 4px;
        }

        .recent-bookings-table::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .recent-bookings-table::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .booking-row {
            transition: all 0.2s ease;
        }

        .booking-row:hover {
            background-color: #f8fafc;
            transform: translateX(2px);
        }

        .booking-id {
            font-size: 0.8rem;
            font-weight: 600;
            color: #6366f1;
        }

        .item-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            color: white;
        }

        .flight-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .hotel-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .item-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
            line-height: 1.2;
        }

        .item-subtitle {
            font-size: 0.75rem;
            color: #64748b;
            line-height: 1.2;
        }

        .amount-text {
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e293b;
        }

        .amount-text.paid {
            color: #10b981;
        }

        .date-text {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Status badges for table */
        .status-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .empty-state {
            padding: 2rem 1rem;
        }

        /* Card enhancements */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .card-header {
            padding: 1.5rem 1.5rem 0.5rem;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-title i {
            font-size: 1rem;
        }

        /* Responsive adjustments for charts section */
        @media (max-width: 1200px) {
            .recent-bookings-table .item-subtitle {
                display: none;
            }

            .amount-text {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 768px) {

            .recent-bookings-table th,
            .recent-bookings-table td {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.75rem;
            }

            .item-icon {
                width: 24px;
                height: 24px;
                font-size: 0.75rem;
            }

            .item-title {
                font-size: 0.75rem;
            }

            .booking-id {
                font-size: 0.7rem;
            }
        }

        /* Calendar Styles */
        .calendar-container {
            padding: 1rem 0;
        }

        .calendar-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .calendar-month-year {
            font-weight: 600;
            color: #1e293b;
        }

        .calendar-grid {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .calendar-day-header {
            padding: 0.75rem;
            text-align: center;
            font-weight: 600;
            color: #64748b;
            font-size: 0.875rem;
        }

        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar-day {
            padding: 0.75rem;
            text-align: center;
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            min-height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .calendar-day:hover {
            background: #f1f5f9;
        }

        .calendar-day.other-month {
            color: #cbd5e1;
            background: #fafafa;
        }

        .calendar-day.today {
            background: #3b82f6;
            color: white;
            font-weight: 600;
        }

        .calendar-day.today:hover {
            background: #2563eb;
        }

        .calendar-day.has-booking {
            background: #ecfdf5;
            border: 2px solid #10b981;
        }

        .booking-icons {
            display: flex;
            gap: 2px;
            margin-top: 2px;
        }

        .booking-icon {
            font-size: 0.6rem;
            width: 12px;
            height: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .booking-icon.flight {
            color: #3b82f6;
        }

        .booking-icon.hotel {
            color: #10b981;
        }

        .calendar-legend {
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .legend-item {
            display: flex;
            align-items: center;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .today-dot {
            background: #3b82f6;
        }

        /* Flight Dashboard Styles */
        .next-flight-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #e2e8f0;
        }

        .flight-route {
            margin: 1rem 0;
        }

        .flight-location {
            text-align: center;
        }

        .airport-code {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .airport-name {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .flight-time {
            font-size: 1rem;
            font-weight: 600;
            color: #3b82f6;
        }

        .flight-path {
            text-align: center;
            position: relative;
        }

        .flight-path i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .flight-duration {
            font-size: 0.75rem;
            color: #64748b;
        }

        .flight-details {
            background: white;
            border-radius: 8px;
            padding: 1rem;
        }

        .flight-stat-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .flight-stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            font-size: 1.2rem;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
        }

        .quick-actions h6 {
            color: #1e293b;
            font-weight: 600;
        }

        /* Notifications Styles */
        .notifications-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .notifications-list::-webkit-scrollbar {
            width: 4px;
        }

        .notifications-list::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .notifications-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
            position: relative;
        }

        .notification-item:hover {
            background: #f8fafc;
        }

        .notification-item.unread {
            background: #fefce8;
            border-left: 4px solid #eab308;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background: #f1f5f9;
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
        }

        .notification-badge {
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        /* Activity Tracker Styles */
        .activity-stat-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .activity-stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .activity-stat-card .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .stat-content {
            flex: 1;
        }

        .stat-content .stat-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.125rem;
        }

        .stat-content .stat-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .activity-timeline {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
        }

        .timeline-marker {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            position: absolute;
            left: -1.75rem;
            top: 0.25rem;
            border: 2px solid white;
            box-shadow: 0 0 0 2px #e2e8f0;
        }

        .timeline-marker.success {
            background: #10b981;
        }

        .timeline-marker.info {
            background: #3b82f6;
        }

        .timeline-marker.warning {
            background: #f59e0b;
        }

        .timeline-marker.primary {
            background: #6366f1;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-title {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .timeline-time {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .calendar-day {
                min-height: 50px;
                padding: 0.5rem;
                font-size: 0.875rem;
            }

            .booking-icons {
                gap: 1px;
            }

            .booking-icon {
                font-size: 0.5rem;
                width: 10px;
                height: 10px;
            }

            .flight-route .d-flex {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .airport-code {
                font-size: 1.25rem;
            }

            .notification-item {
                padding: 0.75rem 1rem;
            }

            .notification-icon {
                width: 32px;
                height: 32px;
                margin-right: 0.75rem;
            }

            .activity-stat-card {
                padding: 0.75rem;
            }

            .timeline {
                padding-left: 1rem;
            }

            .timeline-marker {
                left: -1.25rem;
            }
        }

        /* Quick Actions Styles */
        .quick-actions-grid {
            margin: 0;
        }

        .quick-action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            height: 100%;
        }

        .quick-action-btn:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
            text-decoration: none;
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
            color: white;
        }

        .flight-action {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .hotel-action {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .booking-action {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .support-action {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .admin-action {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .report-action {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .wishlist-action {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .profile-action {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .action-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
        }

        /* Travel Alerts Styles */
        .travel-alerts {
            max-height: 400px;
            overflow-y: auto;
        }

        .alert-item {
            display: flex;
            align-items: flex-start;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            border-left: 4px solid;
        }

        .alert-item.warning {
            background: #fef3c7;
            border-left-color: #f59e0b;
        }

        .alert-item.info {
            background: #dbeafe;
            border-left-color: #3b82f6;
        }

        .alert-item.success {
            background: #d1fae5;
            border-left-color: #10b981;
        }

        .alert-item.danger {
            background: #fee2e2;
            border-left-color: #ef4444;
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background: rgba(255, 255, 255, 0.7);
            flex-shrink: 0;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .alert-message {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .alert-time {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* System Metrics Styles */
        .system-metrics {
            space-y: 1rem;
        }

        .metric-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .metric-label {
            font-weight: 500;
            color: #64748b;
        }

        .metric-value {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .value-number {
            font-weight: 600;
            color: #1e293b;
        }

        .metric-status.success {
            color: #10b981;
        }

        .metric-status.warning {
            color: #f59e0b;
        }

        .metric-status.danger {
            color: #ef4444;
        }

        .metric-status.info {
            color: #3b82f6;
        }

        /* Travel Stats Styles */
        .travel-stats {
            text-align: center;
        }

        .stat-circle-container {
            margin-bottom: 2rem;
        }

        .stat-circle {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }

        .circle-progress {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: conic-gradient(#3b82f6 0deg, #3b82f6 var(--percentage, 50deg), #e2e8f0 var(--percentage, 50deg));
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .circle-content {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .circle-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .circle-label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .travel-achievements {
            text-align: left;
        }

        .achievement-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 0.75rem;
        }

        .achievement-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .achievement-content {
            flex: 1;
        }

        .achievement-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .achievement-desc {
            font-size: 0.875rem;
            color: #64748b;
        }

        /* Destinations List Styles */
        .destinations-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .destination-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .destination-item:hover {
            background: #e2e8f0;
            transform: translateX(4px);
        }

        .destination-rank {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 1rem;
        }

        .destination-info {
            flex: 1;
        }

        .destination-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .destination-bookings {
            font-size: 0.875rem;
            color: #64748b;
        }

        .destination-revenue {
            font-weight: 600;
            color: #10b981;
        }

        /* Expense Tracker Styles */
        .expense-summary {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1.5rem;
        }

        .expense-stat {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .expense-stat:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .expense-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: white;
            font-size: 1.25rem;
        }

        .expense-icon.flights {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .expense-icon.hotels {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .expense-icon.food {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .expense-icon.other {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .expense-details {
            flex: 1;
        }

        .expense-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .expense-label {
            font-size: 0.875rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Budget Planner Styles */
        .budget-planner {
            space-y: 1.5rem;
        }

        .budget-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .budget-category {
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #1e293b;
        }

        .budget-amount .amount {
            font-weight: 700;
            color: #10b981;
            font-size: 1.25rem;
        }

        .budget-progress-container {
            margin-bottom: 2rem;
        }

        .budget-progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .budget-progress {
            height: 12px;
            background: #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }

        .budget-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #34d399);
            border-radius: 6px;
            transition: width 0.6s ease;
        }

        .budget-remaining {
            margin-top: 0.5rem;
            text-align: center;
        }

        .budget-tips {
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .tip-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            color: #64748b;
        }

        /* FAQ Styles */
        .faq-list {
            space-y: 0.75rem;
        }

        .faq-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 0.75rem;
            overflow: hidden;
        }

        .faq-question {
            padding: 1rem;
            background: #f8fafc;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background: #e2e8f0;
        }

        .faq-question i {
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            padding: 0 1rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            color: #64748b;
            line-height: 1.5;
        }

        .faq-item.active .faq-answer {
            padding: 1rem;
            max-height: 200px;
        }

        /* Support Options Styles */
        .support-options {
            space-y: 1rem;
        }

        .support-option {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .support-option:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .support-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            background: #f8fafc;
            font-size: 1.25rem;
        }

        .support-content {
            flex: 1;
        }

        .support-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .support-details {
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .support-hours {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .quick-action-btn {
                padding: 1rem 0.75rem;
            }

            .action-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .action-label {
                font-size: 0.75rem;
            }

            .expense-stat {
                flex-direction: column;
                text-align: center;
                padding: 1rem 0.75rem;
            }

            .expense-icon {
                margin-right: 0;
                margin-bottom: 0.75rem;
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .support-option {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem 1rem;
            }

            .support-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }

        /* Improved scrollbar styling */
        .scrollbar-thin::-webkit-scrollbar {
            width: 5px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: rgba(203, 213, 225, 0.5);
            border-radius: 20px;
        }

        /* Flag icons */
        .flag-icon {
            background-size: cover;
            background-position: center;
        }

        .flag-en {
            background-image: url('https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/flags/4x3/gb.svg');
        }

        .flag-sw {
            background-image: url('https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/flags/4x3/tz.svg');
        }

        .flag-es {
            background-image: url('https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/flags/4x3/es.svg');
        }

        .flag-fr {
            background-image: url('https://cdn.jsdelivr.net/npm/flag-icons@6.6.6/flags/4x3/fr.svg');
        }

        /* Animation for sidebar items */
        @keyframes slideIn {
            0% {
                opacity: 0;
                transform: translateX(-20px);
            }

            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slideIn {
            animation: slideIn 0.4s forwards ease-out;
        }

        /* Notification styling */
        .notification-item.unread {
            background-color: rgba(59, 130, 246, 0.05);
        }

        /* Fix header backdrop blur for Safari */
        @supports (-webkit-backdrop-filter: blur(10px)) or (backdrop-filter: blur(10px)) {
            header {
                -webkit-backdrop-filter: blur(10px);
                backdrop-filter: blur(10px);
            }
        }
    </style>


    @yield('styles')
</head>





<body>
    <!-- Google Translate hidden element -->
    <div id="google_translate_element" style="display:none;"></div>

    <div class="flex min-h-screen bg-[#f8fafc]">
        <!-- Sidebar Overlay for Mobile -->
        <div class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 z-[999] opacity-0 invisible transition-all duration-300"
            id="sidebarOverlay"></div>

        <!-- Enhanced Sidebar -->
        <nav class="fixed w-[70px] h-screen bg-gradient-to-br from-[#0f172a] to-[#1e293b] left-0 top-0 transition-all duration-400 ease-in-out z-[1000] shadow-lg overflow-y-auto scrollbar scrollbar-thin scrollbar-thumb-[#2563eb] scrollbar-track-transparent"
            id="sidebar">
            <!-- Logo Section -->
            <div class="p-4 border-b border-white border-opacity-10 flex justify-center">
                <a href="{{ route('dashboard.index') }}"
                    class="flex items-center text-white no-underline text-2xl font-bold transition-all duration-300">
                    <i data-lucide="globe" class="text-[#e3a300] h-8 w-8 transition-all duration-300"></i>
                    <span class="logo-text ml-3 hidden">Ziara Travel</span>
                </a>
            </div>

            <!-- User Profile Section -->
            <div class="py-4 border-b border-white border-opacity-10 text-center transition-all duration-300">
                <div
                    class="w-[45px] h-[45px] rounded-full mx-auto mb-4 relative overflow-hidden transition-all duration-300 border-2 {{ strtolower(auth()->user()->gender ?? 'male') == 'male' ? 'border-[#3b82f6] bg-gradient-to-br from-[#3b82f6] to-[#1d4ed8]' : 'border-[#ec4899] bg-gradient-to-br from-[#ec4899] to-[#be185d]' }}">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i data-lucide="user"
                            class="text-white h-6 w-6 absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></i>
                    @endif
                </div>
                <div class="transition-all duration-300 user-info hidden">
                    <div class="text-white font-semibold text-base mb-1">{{ auth()->user()->name }}</div>
                    <div
                        class="text-xs py-1 px-3 rounded-full font-medium uppercase tracking-wider
                    {{ auth()->user()->role === 'user'
                        ? 'bg-[rgba(5,150,105,0.2)] text-[#059669] border border-[#059669]'
                        : (auth()->user()->role === 'admin'
                            ? 'bg-[rgba(220,38,38,0.2)] text-[#dc2626] border border-[#dc2626]'
                            : 'bg-[rgba(124,58,237,0.2)] text-[#7c3aed] border border-[#7c3aed]') }}">
                        {{ auth()->user()->role === 'superadmin' ? 'Super Administrator' : (auth()->user()->role === 'admin' ? 'Administrator' : 'User') }}
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <ul class="list-none py-3 px-2">
                <!-- Dashboard -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.1s;" title="Dashboard">
                    <a href="{{ route('dashboard.index') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.index') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                        <i data-lucide="layout-dashboard" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Dashboard</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Dashboard</div>
                </li>

                <!-- Booking History -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.2s;" title="Booking History">
                    <a href="{{ route('dashboard.bookings.history') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.bookings.history') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                        <i data-lucide="history" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Booking
                            History</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Booking History</div>
                </li>

                <!-- Users (Admin/SuperAdmin Only) -->
                @if (in_array(auth()->user()->role, ['admin', 'superadmin']))
                    <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.3s;" title="Users">
                        <a href="#"
                            class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)]"
                            data-bs-toggle="collapse" data-bs-target="#usersMenu" aria-expanded="false">
                            <i data-lucide="users" class="h-6 w-6 transition-all duration-300"></i>
                            <span class="font-medium transition-all duration-300 ml-3 menu-text hidden">Users</span>
                            <i data-lucide="chevron-down" class="h-4 w-4 ml-auto menu-text hidden"></i>
                        </a>
                        <div
                            class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                            Users</div>
                        <div class="max-h-0 overflow-hidden transition-all duration-300 bg-black bg-opacity-20 rounded-lg sidebar-submenu"
                            id="usersMenu">
                            <a href="{{ route('dashboard.admin.users.index') }}"
                                class="block py-3 px-4 text-white text-opacity-60 no-underline transition-all duration-300 hover:text-white hover:bg-white hover:bg-opacity-10 hover:translate-x-[5px] text-[0.9rem]">
                                <div class="flex items-center">
                                    <i data-lucide="users" class="h-4 w-4 mr-2"></i>
                                    <span class="menu-text hidden">All Users</span>
                                </div>
                            </a>
                            <a href="{{ route('dashboard.admin.roles.index') }}"
                                class="block py-3 px-4 text-white text-opacity-60 no-underline transition-all duration-300 hover:text-white hover:bg-white hover:bg-opacity-10 hover:translate-x-[5px] text-[0.9rem]">
                                <div class="flex items-center">
                                    <i data-lucide="shield" class="h-4 w-4 mr-2"></i>
                                    <span class="menu-text hidden">Role Manager</span>
                                </div>
                            </a>
                            <a href="{{ route('dashboard.admin.permissions.index') }}"
                                class="block py-3 px-4 text-white text-opacity-60 no-underline transition-all duration-300 hover:text-white hover:bg-white hover:bg-opacity-10 hover:translate-x-[5px] text-[0.9rem]">
                                <div class="flex items-center">
                                    <i data-lucide="key" class="h-4 w-4 mr-2"></i>
                                    <span class="menu-text hidden">Permissions</span>
                                </div>
                            </a>
                        </div>
                    </li>
                @endif

                <!-- Wishlist -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.4s;" title="Wishlist">
                    <a href="{{ route('dashboard.wishlist') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.wishlist*') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                        <i data-lucide="heart" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Wishlist</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Wishlist</div>
                </li>

                <!-- Flights -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.5s;" title="Flights">
                    <a href="{{ route('dashboard.flights.search') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.flights.*') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                        <i data-lucide="plane" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Flights</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Flights</div>
                </li>

                <!-- Hotels -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.6s;" title="Hotels">
                    <a href="{{ route('dashboard.hotels.search') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.hotels.*') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                        <i data-lucide="bed" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Hotels</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Hotels</div>
                </li>

                <!-- Payments -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.7s;" title="Payments">
                    <a href="{{ route('dashboard.payments.index') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.payments.*') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                        <i data-lucide="credit-card" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Payments</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Payments</div>
                </li>

                <!-- My Profile -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 0.8s;" title="My Profile">
                    <a href="{{ route('dashboard.profile') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.profile*') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                        <i data-lucide="user-circle" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">My Profile</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        My Profile</div>
                </li>

                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 1.5s;" title="Referrals">
            <a href="#"
                class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] {{ request()->routeIs('dashboard.referrals.*') ? 'text-white bg-[#2563eb] shadow-[0_4px_15px_rgba(37,99,235,0.3)]' : '' }}">
                <i data-lucide="share-2" class="h-6 w-6 transition-all duration-300"></i>
                <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Referrals</span>
            </a>
            <div class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                Referrals
            </div>
        </li>

                <!-- Settings -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 1s;" title="Settings">
                    <a href="#"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)]"
                        data-bs-toggle="collapse" data-bs-target="#settingsMenu" aria-expanded="false">
                        <i data-lucide="settings" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Settings</span>
                        <i data-lucide="chevron-down" class="h-4 w-4 ml-auto menu-text hidden"></i>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Settings</div>
                    <div class="max-h-0 overflow-hidden transition-all duration-300 bg-black bg-opacity-20 rounded-lg sidebar-submenu"
                        id="settingsMenu">
                        <a href="{{ route('dashboard.settings.profile') }}"
                            class="block py-3 px-4 text-white text-opacity-60 no-underline transition-all duration-300 hover:text-white hover:bg-white hover:bg-opacity-10 hover:translate-x-[5px] text-[0.9rem]">
                            <div class="flex items-center">
                                <i data-lucide="user-cog" class="h-4 w-4 mr-2"></i>
                                <span class="menu-text hidden">Profile Management</span>
                            </div>
                        </a>
                        <a href="{{ route('dashboard.settings.language') }}"
                            class="block py-3 px-4 text-white text-opacity-60 no-underline transition-all duration-300 hover:text-white hover:bg-white hover:bg-opacity-10 hover:translate-x-[5px] text-[0.9rem]">
                            <div class="flex items-center">
                                <i data-lucide="languages" class="h-4 w-4 mr-2"></i>
                                <span class="menu-text hidden">Language Settings</span>
                            </div>
                        </a>
                    </div>
                </li>

                <!-- Homepage -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 1.3s;" title="Homepage">
                    <a href="{{ route('home') }}"
                        class="flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)]">
                        <i data-lucide="home" class="h-6 w-6 transition-all duration-300"></i>
                        <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Homepage</span>
                    </a>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Homepage</div>
                </li>

                <!-- Logout -->
                <li class="relative mb-2 group animate-slideIn" style="animation-delay: 1.4s;" title="Logout">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center py-3 px-2 text-white text-opacity-70 no-underline transition-all duration-300 relative rounded-xl hover:text-white hover:bg-[rgba(37,99,235,0.2)] text-left bg-transparent border-0">
                            <i data-lucide="log-out" class="h-6 w-6 transition-all duration-300"></i>
                            <span class="font-medium transition-all duration-300 ml-4 menu-text hidden">Logout</span>
                        </button>
                    </form>
                    <div
                        class="absolute left-[60px] top-1/2 transform -translate-y-1/2 bg-[#1e293b] text-white py-2 px-4 rounded-md text-sm opacity-0 pointer-events-none transition-all duration-300 z-[1001] shadow-[0_4px_15px_rgba(0,0,0,0.2)] group-hover:opacity-100 group-hover:pointer-events-auto before:content-[''] before:absolute before:left-[-5px] before:top-1/2 before:transform before:-translate-y-1/2 before:border-[5px] before:border-transparent before:border-r-[#1e293b]">
                        Logout</div>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="ml-[70px] min-h-screen w-full transition-all duration-400 ease-in-out bg-[#f8fafc]"
            id="mainContent">
            <!-- Enhanced Header - Fixed position to prevent overlapping -->
            <header class="sticky top-0 z-[200] bg-white/90 backdrop-blur-md shadow-sm border-b border-[#e2e8f0]">
                <div class="flex justify-between items-center px-6 py-3">
                    <!-- Left Side -->
                    <div class="flex items-center gap-4">
                        <button
                            class="bg-transparent border-0 text-[#64748b] cursor-pointer p-2 rounded-xl transition-all duration-300 hover:bg-[#f1f5f9] relative flex items-center justify-center group"
                            id="toggleSidebar" aria-label="Toggle Sidebar">
                            <i data-lucide="panel-left" class="h-5 w-5 stroke-[1.75] toggle-icon-expand"></i>
                            <i data-lucide="panel-right"
                                class="h-5 w-5 stroke-[1.75] toggle-icon-collapse hidden"></i>
                            <span class="sr-only">Toggle Sidebar</span>
                            <span
                                class="absolute left-full ml-2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                Toggle sidebar
                            </span>
                        </button>

                        <div class="text-[#1e293b] font-medium text-lg hidden md:block">Welcome,
                            {{ auth()->user()->name }}</div>
                    </div>

                    <!-- Right Side -->
                    <div class="flex items-center gap-3">
                        <!-- Weather Widget - Compact Version -->
                        <div class="flex items-center gap-2 bg-gradient-to-r from-[#60a5fa] to-[#3b82f6] text-white py-1.5 px-3 rounded-full text-sm font-medium transition-all duration-300 hover:-translate-y-[2px] hover:shadow-[0_4px_15px_rgba(59,130,246,0.4)] cursor-pointer"
                            id="weatherWidget">
                            <i data-lucide="sun" class="weather-icon h-4 w-4"></i>
                            <span id="weatherText" class="hidden md:inline">Sunny</span>
                        </div>

                        <!-- Current Time - Compact Version -->
                        <div class="bg-gradient-to-r from-[#10b981] to-[#059669] text-white py-1.5 px-3 rounded-full text-sm font-mono font-semibold shadow-sm hidden md:block"
                            id="currentTime">
                            00:00:00
                        </div>

                        <!-- Language Selector - Compact Version -->
                        <div class="relative">
                            <button
                                class="bg-white border border-[#e2e8f0] py-1.5 px-3 rounded-full cursor-pointer transition-all duration-300 flex items-center gap-2 font-medium text-[#64748b] hover:border-[#2563eb] hover:text-[#2563eb] hover:shadow-sm"
                                id="languageBtn">
                                <div class="w-4 h-3 rounded-sm bg-cover bg-center flag-icon flag-en"></div>
                                <span id="currentLanguage" class="hidden md:inline text-xs">EN</span>
                                <i data-lucide="chevron-down" class="h-3.5 w-3.5 ml-0 md:ml-1"></i>
                            </button>
                            <div class="absolute top-full right-0 bg-white border border-[#e2e8f0] rounded-xl shadow-lg z-[1000] min-w-[150px] opacity-0 invisible transform -translate-y-[10px] transition-all duration-300"
                                id="languageDropdown">
                                <div class="flex items-center gap-3 p-3 cursor-pointer transition-all duration-300 border-b border-[#e2e8f0] hover:bg-[#f8fafc] hover:translate-x-[5px] active:bg-gradient-to-r active:from-[#2563eb] active:to-[#1d4ed8] active:text-white"
                                    data-lang="en">
                                    <div class="w-5 h-[15px] rounded-[2px] bg-cover bg-center flag-icon flag-en"></div>
                                    <span>English</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 cursor-pointer transition-all duration-300 border-b border-[#e2e8f0] hover:bg-[#f8fafc] hover:translate-x-[5px]"
                                    data-lang="sw">
                                    <div class="w-5 h-[15px] rounded-[2px] bg-cover bg-center flag-icon flag-sw"></div>
                                    <span>Kiswahili</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 cursor-pointer transition-all duration-300 border-b border-[#e2e8f0] hover:bg-[#f8fafc] hover:translate-x-[5px]"
                                    data-lang="es">
                                    <div class="w-5 h-[15px] rounded-[2px] bg-cover bg-center flag-icon flag-es"></div>
                                    <span>Español</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 cursor-pointer transition-all duration-300 hover:bg-[#f8fafc] hover:translate-x-[5px]"
                                    data-lang="fr">
                                    <div class="w-5 h-[15px] rounded-[2px] bg-cover bg-center flag-icon flag-fr"></div>
                                    <span>Français</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications - Compact Version -->
                        <div class="relative">
                            <button
                                class="relative bg-white border border-[#e2e8f0] text-[#64748b] cursor-pointer p-1.5 rounded-full transition-all duration-300 hover:border-[#2563eb] hover:text-[#2563eb] hover:shadow-sm flex items-center justify-center h-9 w-9"
                                id="notificationBtn">
                                <i data-lucide="bell" class="h-5 w-5"></i>
                                <span
                                    class="absolute top-[-2px] right-[-2px] bg-gradient-to-r from-[#ef4444] to-[#dc2626] text-white text-xs py-0.5 px-1.5 rounded-full font-semibold min-w-[18px] text-center animate-pulse"
                                    id="notificationBadge">5</span>
                            </button>
                            <div class="absolute top-full right-0 bg-white border border-[#e2e8f0] rounded-xl shadow-lg z-[1000] w-[320px] opacity-0 invisible transform -translate-y-[10px] transition-all duration-300"
                                id="notificationDropdown">
                                <div class="p-3 border-b border-[#e2e8f0] flex justify-between items-center">
                                    <h6 class="font-medium text-[#1e293b] text-sm">Notifications</h6>
                                    <div class="flex gap-2">
                                        <button
                                            class="bg-transparent border-0 text-[#2563eb] text-xs cursor-pointer py-1 px-2 rounded-md transition-all duration-300 hover:bg-[#f8fafc]"
                                            id="markAllRead">
                                            <span class="flex items-center gap-1">
                                                <i data-lucide="check-circle" class="h-3.5 w-3.5"></i>
                                                <span class="hidden md:inline">Read all</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="max-h-[350px] overflow-y-auto scrollbar-thin" id="notificationList">
                                    <!-- Sample notifications -->
                                    <div
                                        class="notification-item flex gap-3 p-3 border-b border-[#e2e8f0] hover:bg-[#f8fafc] transition-all duration-200 cursor-pointer unread">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i data-lucide="file-text" class="h-4.5 w-4.5 text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex items-center justify-between mb-0.5">
                                                <h6 class="text-sm font-medium text-[#1e293b]">New booking confirmed
                                                </h6>
                                                <div class="unread-indicator h-2 w-2 rounded-full bg-blue-500"></div>
                                            </div>
                                            <p class="text-xs text-[#64748b] mb-1">Flight to Paris confirmed</p>
                                            <span class="text-[10px] text-[#94a3b8]">10 min ago</span>
                                        </div>
                                    </div>
                                    <!-- More notification items here -->
                                </div>
                                <div class="p-2 text-center border-t border-[#e2e8f0]">
                                    <a href="#"
                                        class="text-xs text-[#2563eb] hover:text-[#1d4ed8] transition-colors duration-200">View
                                        all</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown - Compact Version -->
                        <div class="relative">
                            <div class="flex items-center gap-2 py-1.5 px-3 rounded-full bg-white border border-[#e2e8f0] cursor-pointer transition-all duration-300 hover:border-[#2563eb] hover:shadow-sm"
                                id="userDropdownBtn">
                                <div
                                    class="w-[26px] h-[26px] rounded-full bg-gradient-to-r from-[#2563eb] to-[#1d4ed8] flex items-center justify-center text-white text-xs font-semibold border border-white">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-[#64748b]"></i>
                            </div>
                            <div class="absolute top-full right-0 bg-white border border-[#e2e8f0] rounded-xl shadow-lg z-[1000] min-w-[200px] opacity-0 invisible transform -translate-y-[10px] transition-all duration-300"
                                id="userDropdownMenu">
                                <a href="{{ route('dashboard.profile') }}"
                                    class="flex items-center gap-3 p-2.5 cursor-pointer transition-all duration-300 border-b border-[#e2e8f0] text-[#1e293b] no-underline hover:bg-[#f8fafc] hover:translate-x-[5px] hover:text-[#2563eb]">
                                    <i data-lucide="user" class="h-4 w-4"></i>
                                    <span class="text-sm">Edit Profile</span>
                                </a>
                                <a href="{{ route('dashboard.password.change') }}"
                                    class="flex items-center gap-3 p-2.5 cursor-pointer transition-all duration-300 border-b border-[#e2e8f0] text-[#1e293b] no-underline hover:bg-[#f8fafc] hover:translate-x-[5px] hover:text-[#2563eb]">
                                    <i data-lucide="key" class="h-4 w-4"></i>
                                    <span class="text-sm">Change Password</span>
                                </a>
                                <a href="{{ route('home') }}"
                                    class="flex items-center gap-3 p-2.5 cursor-pointer transition-all duration-300 border-b border-[#e2e8f0] text-[#1e293b] no-underline hover:bg-[#f8fafc] hover:translate-x-[5px] hover:text-[#2563eb]">
                                    <i data-lucide="home" class="h-4 w-4"></i>
                                    <span class="text-sm">Back to Homepage</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center gap-3 p-2.5 cursor-pointer transition-all duration-300 text-[#1e293b] w-full text-left bg-transparent border-0 hover:bg-[#f8fafc] hover:translate-x-[5px] hover:text-[#2563eb]">
                                        <i data-lucide="log-out" class="h-4 w-4"></i>
                                        <span class="text-sm">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="p-6 pb-24 pt-16 scrollbar-custom">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- <script>
     lucide.createIcons();
    </script> --}}

    {{-- <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');
            const expandIcon = document.querySelector('.toggle-icon-expand');
            const collapseIcon = document.querySelector('.toggle-icon-collapse');
            let sidebarExpanded = false;

            const menuTexts = document.querySelectorAll('.menu-text');
            const logoText = document.querySelector('.logo-text');
            const userInfo = document.querySelector('.user-info');
            const sidebarSubmenus = document.querySelectorAll('.sidebar-submenu');

            // Function to expand sidebar
            function expandSidebar() {
                // First adjust sidebar width and show toggle icon
                sidebar.style.width = '280px';
                expandIcon.classList.add('hidden');
                collapseIcon.classList.remove('hidden');

                // Adjust main content with animation
                mainContent.style.marginLeft = '280px';
                mainContent.style.width = 'calc(100% - 280px)';

                // Show text elements with slight delay for smoother transition
                setTimeout(() => {
                    menuTexts.forEach(text => text.classList.remove('hidden'));
                    logoText.classList.remove('hidden');
                    userInfo.classList.remove('hidden');
                }, 150);

                // Make all content containers adjust to new width
                const contentContainers = document.querySelectorAll(
                    '.dashboard-card, .content-container, .stats-card, .chart-container');
                contentContainers.forEach(container => {
                    container.classList.add('expanded');
                });

                // Update grid layouts if needed
                const gridLayouts = document.querySelectorAll('.grid');
                gridLayouts.forEach(grid => {
                    grid.classList.add('grid-expanded');
                });

                sidebarExpanded = true;
                localStorage.setItem('sidebarExpanded', 'true');
            }

            // Function to collapse sidebar
            function collapseSidebar() {
                // Hide text elements first for smooth transition
                menuTexts.forEach(text => text.classList.add('hidden'));
                logoText.classList.add('hidden');
                userInfo.classList.add('hidden');

                // Close any open submenus
                sidebarSubmenus.forEach(submenu => {
                    submenu.style.maxHeight = '0px';
                });

                // Then collapse sidebar after a short delay
                setTimeout(() => {
                    sidebar.style.width = '70px';
                    mainContent.style.marginLeft = '70px';
                    mainContent.style.width = 'calc(100% - 70px)';
                    expandIcon.classList.remove('hidden');
                    collapseIcon.classList.add('hidden');
                }, 50);

                // Adjust content containers
                const contentContainers = document.querySelectorAll(
                    '.dashboard-card, .content-container, .stats-card, .chart-container');
                contentContainers.forEach(container => {
                    container.classList.remove('expanded');
                });

                // Update grid layouts if needed
                const gridLayouts = document.querySelectorAll('.grid');
                gridLayouts.forEach(grid => {
                    grid.classList.remove('grid-expanded');
                });

                sidebarExpanded = false;
                localStorage.setItem('sidebarExpanded', 'false');
            }

            // Initialize sidebar state from localStorage or default to collapsed
            const savedState = localStorage.getItem('sidebarExpanded');
            if (savedState === 'true') {
                expandSidebar();
            } else {
                collapseSidebar();
            }

            // Update initial state of main content
            const contentItems = document.querySelectorAll('.content-item');
            contentItems.forEach(item => {
                if (sidebarExpanded) {
                    item.classList.add('content-expanded');
                } else {
                    item.classList.remove('content-expanded');
                }
            });

            // Initialize Lucide icons with thinner strokes for minimalistic look
            if (window.lucide) {
                window.lucide.createIcons({
                    attrs: {
                        'stroke-width': '1.5',
                        'class': 'lucide-icon'
                    }
                });
            }

            // Toggle sidebar on button click
            toggleBtn.addEventListener('click', function() {
                if (sidebarExpanded) {
                    collapseSidebar();
                } else {
                    expandSidebar();
                }

                // Resize charts after the transition completes
                setTimeout(() => {
                    window.dispatchEvent(new Event('resize'));

                    // If you're using Chart.js, resize all charts
                    const charts = document.querySelectorAll('canvas');
                    charts.forEach(canvas => {
                        if (canvas.chart) {
                            canvas.chart.resize();
                        }
                    });
                }, 450); // Slightly longer than transition duration
            });
        });

        // Mobile sidebar handling
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Show overlay when sidebar expands on mobile
        function handleMobileView() {
            if (window.innerWidth < 768) {
                if (sidebarExpanded) {
                    sidebarOverlay.classList.remove('opacity-0', 'invisible');
                } else {
                    sidebarOverlay.classList.add('opacity-0', 'invisible');
                }
            }
        }

        // Listen for window resize to handle mobile/desktop transitions
        window.addEventListener('resize', function() {
            handleMobileView();

            // Auto-collapse sidebar on small screens
            if (window.innerWidth < 768 && sidebarExpanded) {
                collapseSidebar();
            }
        });

        // Close sidebar when overlay is clicked
        sidebarOverlay.addEventListener('click', function() {
            if (sidebarExpanded) {
                collapseSidebar();
            }
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            lucide.createIcons();
            
            // Sidebar functionality
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');
            const expandIcon = document.querySelector('.toggle-icon-expand');
            const collapseIcon = document.querySelector('.toggle-icon-collapse');
            let sidebarExpanded = false;

            const menuTexts = document.querySelectorAll('.menu-text');
            const logoText = document.querySelector('.logo-text');
            const userInfo = document.querySelector('.user-info');

            function expandSidebar() {
                sidebar.style.width = '280px';
                mainContent.style.marginLeft = '280px';
                expandIcon.classList.add('hidden');
                collapseIcon.classList.remove('hidden');
                
                setTimeout(() => {
                    menuTexts.forEach(text => text.classList.remove('hidden'));
                    logoText.classList.remove('hidden');
                    userInfo.classList.remove('hidden');
                }, 150);
                
                sidebarExpanded = true;
                localStorage.setItem('sidebarExpanded', 'true');
            }

            function collapseSidebar() {
                menuTexts.forEach(text => text.classList.add('hidden'));
                logoText.classList.add('hidden');
                userInfo.classList.add('hidden');
                
                setTimeout(() => {
                    sidebar.style.width = '70px';
                    mainContent.style.marginLeft = '70px';
                    expandIcon.classList.remove('hidden');
                    collapseIcon.classList.add('hidden');
                }, 50);
                
                sidebarExpanded = false;
                localStorage.setItem('sidebarExpanded', 'false');
            }

            // Initialize sidebar state
            const savedState = localStorage.getItem('sidebarExpanded');
            if (savedState === 'true') {
                expandSidebar();
            } else {
                collapseSidebar();
            }

            // Toggle sidebar
            toggleBtn.addEventListener('click', function() {
                if (sidebarExpanded) {
                    collapseSidebar();
                } else {
                    expandSidebar();
                }
            });

            // Update current time
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('en-US', {
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const timeElement = document.getElementById('currentTime');
                if (timeElement) {
                    timeElement.textContent = timeString;
                }
            }
            
            updateTime();
            setInterval(updateTime, 1000);

            // FAQ Toggle functionality
            window.toggleFAQ = function(element) {
                const content = element.nextElementSibling;
                const icon = element.querySelector('i[data-lucide="chevron-down"]');
                
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    content.classList.add('faq-content', 'active');
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    content.classList.add('hidden');
                    content.classList.remove('active');
                    icon.style.transform = 'rotate(0deg)';
                }
                
                // Re-initialize icons after DOM changes
                lucide.createIcons();
            };

            // Additional dashboard functionality
            window.refreshAlerts = function() {
                console.log('Refreshing alerts...');
            };

            window.exportRevenue = function() {
                window.open('/dashboard/reports/revenue/export', '_blank');
            };

            window.addExpense = function() {
                alert('Add expense functionality would open a modal here');
            };

            window.openLiveChat = function() {
                alert('Live chat would open here');
            };

            // Re-initialize Lucide icons after any dynamic content changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        lucide.createIcons();
                    }
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
    </script>

    {{-- <script>
        function refreshAlerts() {
            // Refresh travel alerts
            fetch('/dashboard/travel-alerts')
                .then(response => response.json())
                .then(data => {
                    // Update alerts UI
                    console.log('Alerts refreshed');
                });
        }

        function exportRevenue() {
            // Export revenue data
            window.open('/dashboard/reports/revenue/export', '_blank');
        }

        function addExpense() {
            // Open add expense modal
            // You would implement this with a modal form
            alert('Add expense functionality would open a modal here');
        }

        function toggleFAQ(element) {
            const faqItem = element.closest('.faq-item');
            faqItem.classList.toggle('active');
        }

        function openLiveChat() {
            // Open live chat widget
            alert('Live chat would open here');
        }
    </script>    --}}

</body>

</html>
