@php
    $activePrefix = request()->is('admin*') ? 'admin' : (request()->is('superadmin*') ? 'superadmin' : 'user');
    $isAdminSection = in_array($activePrefix, ['admin', 'superadmin']);
    $roleLabel = ucfirst(str_replace('_', ' ', optional(auth()->user())->role ?? 'User'));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Symbols -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <style>
        :root { --ziara-primary: #7a0000; --ziara-gold: #c9a227; }
        body { background: #f4f6f9; font-family: "Segoe UI", system-ui, -apple-system, sans-serif; }
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: 260px;
            background: linear-gradient(180deg, #5c0000 0%, #7a0000 60%, #8f1f1f 100%);
            color: #fff; overflow-y: auto; z-index: 1040;
        }
        .sidebar .sidebar-brand {
            display: flex; align-items: center; gap: 10px;
            padding: 18px 20px; font-weight: 700; font-size: 1.1rem; color: #fff;
            text-decoration: none; border-bottom: 1px solid rgba(255,255,255,.12); background: rgba(0,0,0,.15);
        }
        .sidebar .sidebar-heading {
            font-size: .72rem; text-transform: uppercase; letter-spacing: .08em;
            color: rgba(255,255,255,.65); padding: 16px 20px 6px; margin: 0;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.85); border-radius: 8px; margin: 2px 12px;
            display: flex; align-items: center; gap: 10px; padding: 9px 14px; font-size: .92rem;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sidebar .nav-link.active { background: var(--ziara-gold); color: #4a0000; font-weight: 600; }
        .sidebar .nav-link .material-symbols-outlined { font-size: 20px; }
        .main-panel { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; padding: 12px 24px; display: flex; align-items: center;
            justify-content: space-between; border-bottom: 1px solid #e9ecef;
            position: sticky; top: 0; z-index: 1030;
        }
        .topbar .user-chip {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 14px; border-radius: 50rem; background: #f1f3f6; font-size: .85rem;
        }
        .content-wrapper { padding: 24px; flex: 1; }
        .btn-primary { background: var(--ziara-primary); border-color: var(--ziara-primary); }
        .btn-primary:hover { background: #5c0000; border-color: #5c0000; }
        .table thead.table-dark { background: var(--ziara-primary); }
        .card { border: 0; box-shadow: 0 1px 3px rgba(0,0,0,.06); border-radius: 12px; }
        .card-header { background: #fff; border-bottom: 1px solid #eef0f3; border-radius: 12px 12px 0 0 !important; }
        .card-title { font-size: 1.02rem; font-weight: 600; }
        @media (max-width: 991.98px) {
            .sidebar { width: 220px; transform: translateX(-100%); transition: transform .2s; }
            .sidebar.show { transform: translateX(0); }
            .main-panel { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <aside class="sidebar" id="adminSidebar">
        <a href="{{ route($activePrefix . '.dashboard') }}" class="sidebar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="height: 32px; border-radius: 8px;">
            <span>Ziara Admin</span>
        </a>
        <p class="sidebar-heading">Manage</p>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ $isAdminSection ? route($activePrefix . '.dashboard') : route('user.dashboard') }}" class="nav-link {{ !$isAdminSection && request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
            </li>
            @if($isAdminSection)
            <li class="nav-item">
                <a href="{{ route($activePrefix . '.roles.index') }}" class="nav-link {{ request()->routeIs($activePrefix . '.roles.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">admin_panel_settings</span> Roles
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route($activePrefix . '.roles.add') }}" class="nav-link">
                    <span class="material-symbols-outlined">add_circle</span> Add Role
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route($activePrefix . '.permissions.index') }}" class="nav-link {{ request()->routeIs($activePrefix . '.permissions.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">security</span> Permissions
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route($activePrefix . '.permissions.add') }}" class="nav-link">
                    <span class="material-symbols-outlined">add_circle</span> Add Permission
                </a>
            </li>
            @if(Route::has($activePrefix . '.roles.all'))
            <li class="nav-item">
                <a href="{{ route($activePrefix . '.roles.all') }}" class="nav-link">
                    <span class="material-symbols-outlined">list_alt</span> All Roles &amp; Permissions
                </a>
            </li>
            @endif
            @endif
        </ul>
        <p class="sidebar-heading">Account</p>
        <ul class="nav flex-column">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                        <span class="material-symbols-outlined">logout</span> Logout
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <div class="main-panel">
        <header class="topbar">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-light d-lg-none" type="button" id="sidebarToggler">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h5 class="mb-0 fw-semibold">@yield('title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="user-chip">
                    <span class="material-symbols-outlined" style="font-size:18px">person</span>
                    {{ optional(auth()->user())->name }} <span class="text-muted">({{ $roleLabel }})</span>
                </span>
            </div>
        </header>

        <main class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="text-center text-muted small py-3">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggler = document.getElementById('sidebarToggler');
            const sidebar = document.getElementById('adminSidebar');
            if (toggler && sidebar) {
                toggler.addEventListener('click', function () { sidebar.classList.toggle('show'); });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>