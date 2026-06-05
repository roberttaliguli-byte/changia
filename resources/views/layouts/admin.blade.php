{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Changia Smart Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --admin-primary: #FF6F00;
            --admin-primary-dark: #e65100;
            --admin-primary-light: #FF9800;
            --admin-secondary: #FFC107;
            --admin-accent: #FFC107;
            --admin-danger: #ef4444;
            --admin-success: #10b981;
            --admin-warning: #f59e0b;
            --admin-info: #3b82f6;
            --admin-dark: #111827;
            --admin-gray: #6B7280;
            --admin-light: #f8fafc;
            --admin-border: #e2e8f0;
            --admin-sidebar-w: 280px;
            --admin-sidebar-w-sm: 80px;
            --admin-topbar-h: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
            background: #f1f5f9;
            overflow-x: hidden;
        }

        /* ============================================
           ADMIN SIDEBAR
        ============================================ */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--admin-sidebar-w);
            background: white;
            transition: width 0.25s ease;
            z-index: 1050;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border-right: 1px solid var(--admin-border);
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .admin-sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .admin-sidebar.collapsed {
            width: var(--admin-sidebar-w-sm);
        }

        /* Logo */
        .admin-logo {
            padding: 20px 16px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .admin-logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #FF6F00, #FFC107);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .admin-logo-icon i {
            font-size: 1.6rem;
            color: white;
        }

        .admin-logo-text {
            text-align: center;
            transition: opacity 0.2s ease;
        }

        .admin-logo-text h3 {
            font-size: 0.85rem;
            font-weight: 800;
            background: linear-gradient(135deg, #FF6F00, #FFC107);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .admin-logo-text p {
            font-size: 0.6rem;
            color: #000000;
            margin: 0;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .admin-sidebar.collapsed .admin-logo-text {
            opacity: 0;
            display: none;
        }

        .admin-sidebar.collapsed .admin-logo-icon {
            width: 40px;
            height: 40px;
        }

        .admin-sidebar.collapsed .admin-logo-icon i {
            font-size: 1.3rem;
        }

        /* Navigation */
        .admin-nav {
            padding: 16px 12px;
        }

        .admin-nav-section {
            margin-bottom: 20px;
        }

        .admin-nav-title {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #000000;
            padding: 8px 10px;
            margin-bottom: 4px;
        }

        .admin-sidebar.collapsed .admin-nav-title {
            display: none;
        }

        .admin-nav-item {
            margin-bottom: 2px;
            border-radius: 8px;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: #000000;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .admin-nav-link i {
            width: 20px;
            font-size: 1rem;
            color: #000000;
            flex-shrink: 0;
        }

        .admin-nav-link .nav-label {
            flex: 1;
            transition: opacity 0.2s;
            color: #000000;
            font-weight: 600;
        }

        .admin-nav-link .nav-badge {
            background: #f59e0b;
            color: white;
            font-size: 0.55rem;
            padding: 2px 6px;
            border-radius: 20px;
            font-weight: 700;
        }

        .admin-nav-link:hover {
            background: #FFF3E0;
        }

        .admin-nav-link:hover .nav-label {
            color: #e65100;
            font-weight: 700;
        }

        .admin-nav-link:hover i {
            color: #FF6F00;
        }

        .admin-nav-item.active .admin-nav-link {
            background: #FFF3E0;
        }

        .admin-nav-item.active .admin-nav-link .nav-label {
            color: #e65100;
            font-weight: 700;
        }

        .admin-nav-item.active .admin-nav-link i {
            color: #FF6F00;
        }

        .admin-sidebar.collapsed .nav-label {
            opacity: 0;
            display: none;
        }

        .admin-sidebar.collapsed .admin-nav-link {
            justify-content: center;
            padding: 10px;
        }

        .admin-sidebar.collapsed .admin-nav-link i {
            margin: 0;
        }

        /* ============================================
           ADMIN TOPBAR
        ============================================ */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: var(--admin-sidebar-w);
            right: 0;
            height: var(--admin-topbar-h);
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 1040;
            transition: left 0.25s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid var(--admin-border);
        }

        .admin-sidebar.collapsed ~ .admin-topbar {
            left: var(--admin-sidebar-w-sm);
        }

        .admin-topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-toggle-btn {
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #000000;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .admin-toggle-btn:hover {
            background: #f1f5f9;
            color: #FF6F00;
        }

        .admin-page-title h1 {
            font-size: 1rem;
            font-weight: 800;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #000000;
        }

        .admin-page-title p {
            font-size: 0.65rem;
            color: #FF6F00;
            margin: 0;
            font-weight: 700;
        }

        .admin-topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Notifications */
        .admin-notification {
            position: relative;
            cursor: pointer;
        }

        .admin-notification-btn {
            background: none;
            border: none;
            font-size: 1.1rem;
            color: #000000;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.2s;
            position: relative;
        }

        .admin-notification-btn:hover {
            background: #f1f5f9;
            color: #FF6F00;
        }

        .admin-notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #ef4444;
            color: white;
            font-size: 0.5rem;
            min-width: 16px;
            height: 16px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        /* User Dropdown */
        .admin-user {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 4px;
            border-radius: 40px;
            transition: all 0.2s;
        }

        .admin-user:hover {
            background: #f1f5f9;
        }

        .admin-user-avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #FF6F00, #FFC107);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            border: 2px solid white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }

        .admin-user-info {
            text-align: right;
        }

        .admin-user-name {
            font-size: 0.85rem;
            font-weight: 800;
            color: #000000;
        }

        .admin-user-role {
            font-size: 0.65rem;
            font-weight: 700;
            color: #FF6F00;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Dropdown Panel */
        .admin-dropdown {
            position: absolute;
            top: 70px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            min-width: 240px;
            z-index: 1060;
            display: none;
            border: 1px solid var(--admin-border);
        }

        .admin-dropdown.show {
            display: block;
            animation: fadeIn 0.15s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .admin-dropdown-header {
            padding: 10px 16px;
            border-bottom: 1px solid var(--admin-border);
            font-weight: 700;
            font-size: 0.85rem;
            color: #000000;
        }

        .admin-dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            color: #000000;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background 0.2s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .admin-dropdown-item:hover {
            background: #f1f5f9;
            color: #e65100;
        }

        .admin-dropdown-item i {
            width: 18px;
            font-size: 0.9rem;
            color: #000000;
        }

        .admin-dropdown-item:hover i {
            color: #FF6F00;
        }

        .admin-dropdown-divider {
            height: 1px;
            background: var(--admin-border);
            margin: 6px 0;
        }

        /* ============================================
           MAIN CONTENT
        ============================================ */
        .admin-main {
            margin-left: var(--admin-sidebar-w);
            margin-top: var(--admin-topbar-h);
            padding: 24px;
            min-height: calc(100vh - var(--admin-topbar-h));
            transition: margin-left 0.25s ease;
            background: #f1f5f9;
        }

        .admin-sidebar.collapsed ~ .admin-main {
            margin-left: var(--admin-sidebar-w-sm);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                left: calc(-1 * var(--admin-sidebar-w));
                width: var(--admin-sidebar-w) !important;
                transition: left 0.25s ease;
            }
            
            .admin-sidebar.mobile-open {
                left: 0;
            }
            
            .admin-topbar {
                left: 0 !important;
                padding: 0 16px;
            }
            
            .admin-main {
                margin-left: 0 !important;
                padding: 16px;
            }
            
            .admin-user-info {
                display: none;
            }
            
            .admin-page-title p {
                display: none;
            }
            
            .admin-page-title h1 {
                font-size: 0.9rem;
            }

            .admin-toggle-btn.d-md-none {
                display: inline-flex !important;
            }
        }

        @media (min-width: 769px) {
            .admin-toggle-btn.d-md-none {
                display: none !important;
            }
        }

        /* Scroll Fix */
        html, body {
            overflow: auto !important;
            height: auto !important;
            min-height: 100vh;
        }
    </style>

    @stack('admin-styles')
</head>
<body>

<!-- Sidebar Overlay for Mobile -->
<div class="admin-sidebar-overlay" id="adminSidebarOverlay" onclick="closeAdminMobile()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1045;"></div>

<!-- Admin Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-logo">
        <div class="admin-logo-icon">
            <i class="fas fa-crown"></i>
        </div>
        <div class="admin-logo-text">
            <h3>CHANGIA SMART</h3>
            <p>ADMIN PORTAL</p>
        </div>
    </div>

    <nav class="admin-nav">
        <div class="admin-nav-section">
            <div class="admin-nav-title">MAIN</div>
            <div class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
                    <i class="fas fa-chart-line"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-title">MANAGEMENT</div>
            <div class="admin-nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <a href="{{ route('admin.users') }}" class="admin-nav-link">
                    <i class="fas fa-users"></i>
                    <span class="nav-label">Watumiaji</span>
                </a>
            </div>
            <div class="admin-nav-item {{ request()->routeIs('admin.events*') ? 'active' : '' }}">
                <a href="{{ route('admin.events') }}" class="admin-nav-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-label">Matukio</span>
                </a>
            </div>
            <div class="admin-nav-item {{ request()->routeIs('admin.cards*') ? 'active' : '' }}">
                <a href="{{ route('admin.cards') }}" class="admin-nav-link">
                    <i class="fas fa-id-card"></i>
                    <span class="nav-label">Tengeneza cadi</span>
                </a>
            </div>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-title">COMMUNICATION</div>
            <div class="admin-nav-item {{ request()->routeIs('admin.sms*') ? 'active' : '' }}">
                <a href="{{ route('admin.sms') }}" class="admin-nav-link">
                    <i class="fas fa-envelope"></i>
                    <span class="nav-label">matumizi ya SMS</span>
                </a>
            </div>
        </div>

        <div class="admin-nav-section">
            <div class="admin-nav-title">ACCOUNT</div>
            <div class="admin-nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <a href="{{ route('admin.profile') }}" class="admin-nav-link">
                    <i class="fas fa-user-circle"></i>
                    <span class="nav-label">Profaili Yangu</span>
                </a>
            </div>
        </div>
    </nav>
</aside>

<!-- Admin Topbar -->
<header class="admin-topbar">
<div class="admin-topbar-left">
    <button class="admin-toggle-btn d-none d-md-inline-flex" onclick="toggleAdminDesktop()">
        <i class="fas fa-bars"></i>
    </button>
    <button class="admin-toggle-btn d-md-none" onclick="openAdminMobile()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="admin-page-title">
        <h1>@yield('page_title', 'Admin Dashboard')</h1>
        <p>@yield('page_subtitle', 'Karibu kwenye Paneli ya Msimamizi')</p>
    </div>
</div>
    <div class="admin-topbar-right">
        <div class="admin-notification" onclick="toggleAdminDropdown('adminNotifDropdown', event)">
            <button class="admin-notification-btn">
                <i class="fas fa-bell"></i>
                <span class="admin-notification-badge">3</span>
            </button>
        </div>

        <div class="admin-user" onclick="toggleAdminDropdown('adminUserDropdown', event)">
            <div class="admin-user-info">
                <div class="admin-user-name">{{ auth()->user()->name }}</div>
                <div class="admin-user-role">{{ auth()->user()->role_display }}</div>
            </div>
            <div class="admin-user-avatar">
                {{ auth()->user()->initial }}
            </div>
        </div>
    </div>
</header>

<!-- Notifications Dropdown -->
<div class="admin-dropdown" id="adminNotifDropdown">
    <div class="admin-dropdown-header">
        <strong>Arifa Mpya</strong>
    </div>
    <a href="#" class="admin-dropdown-item">
        <i class="fas fa-user-plus"></i>
        <span>Mtumiaji mpya amejiunga</span>
    </a>
    <a href="#" class="admin-dropdown-item">
        <i class="fas fa-calendar"></i>
        <span>Tukio jipya limeundwa</span>
    </a>
    <div class="admin-dropdown-divider"></div>
    <a href="#" class="admin-dropdown-item">
        <i class="fas fa-eye"></i>
        <span>Tazama zote</span>
    </a>
</div>

<!-- User Dropdown -->
<div class="admin-dropdown" id="adminUserDropdown">
    <a href="{{ route('admin.profile') }}" class="admin-dropdown-item">
        <i class="fas fa-user-circle"></i>
        <span>Profaili Yangu</span>
    </a>

    <div class="admin-dropdown-divider"></div>
    <form method="POST" action="{{ route('logout') }}" id="adminLogoutForm">
        @csrf
        <button type="submit" class="admin-dropdown-item" style="width:100%; text-align:left; border:none; background:none;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Toka Mfumo</span>
        </button>
    </form>
</div>

<!-- Main Content -->
<main class="admin-main">
    @yield('admin-content')
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const adminSidebar = document.getElementById('adminSidebar');
    let isAdminCollapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
    
    if (isAdminCollapsed) {
        adminSidebar.classList.add('collapsed');
    }

    function toggleAdminDesktop() {
        isAdminCollapsed = !isAdminCollapsed;
        localStorage.setItem('adminSidebarCollapsed', isAdminCollapsed);
        adminSidebar.classList.toggle('collapsed', isAdminCollapsed);
    }

    function openAdminMobile() {
        adminSidebar.classList.add('mobile-open');
        document.getElementById('adminSidebarOverlay').style.display = 'block';
    }

    function closeAdminMobile() {
        adminSidebar.classList.remove('mobile-open');
        document.getElementById('adminSidebarOverlay').style.display = 'none';
    }

    function toggleAdminDropdown(id, event) {
        if (event) event.stopPropagation();
        const dropdown = document.getElementById(id);
        document.querySelectorAll('.admin-dropdown.show').forEach(d => {
            if (d.id !== id) d.classList.remove('show');
        });
        dropdown.classList.toggle('show');
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.admin-dropdown.show').forEach(d => d.classList.remove('show'));
    });

    // Logout confirmation
    const logoutForm = document.getElementById('adminLogoutForm');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Una uhakika?',
                text: 'Unataka kutoka kwenye mfumo wa admin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF6F00',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ndio, Toka',
                cancelButtonText: 'Ghairi'
            }).then(result => {
                if (result.isConfirmed) this.submit();
            });
        });
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) closeAdminMobile();
    });
</script>

@stack('admin-scripts')
</body>
</html>