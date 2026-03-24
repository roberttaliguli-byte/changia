<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Changia Smart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6F00;
            --primary-dark: #e65100;
            --primary-light: #FF9800;
            --accent: #FFC107;
            --sidebar-w: 240px;
            --sidebar-w-sm: 70px;
            --topbar-h: 60px;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-600: #475569;
            --gray-700: #334155;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: var(--gray-100);
            color: var(--gray-700);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: white;
            border-right: 1px solid var(--gray-200);
            display: flex;
            flex-direction: column;
            transition: width 0.25s ease;
            z-index: 1050;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 2px 0 8px rgba(0,0,0,0.03);
        }

        .sidebar::-webkit-scrollbar {
            width: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .sidebar.collapsed {
            width: var(--sidebar-w-sm);
        }

        /* Logo */
        .sidebar-logo {
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray-200);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .sidebar-logo i {
            font-size: 1.4rem;
            color: var(--primary);
            flex-shrink: 0;
        }

        .sidebar-logo span {
            font-size: 1rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            transition: opacity 0.2s;
        }

        .sidebar.collapsed .sidebar-logo span {
            opacity: 0;
            pointer-events: none;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 12px 8px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .nav-section {
            margin-bottom: 16px;
        }

        .nav-section-title {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-600);
            padding: 6px 10px;
            opacity: 0.6;
        }

        .sidebar.collapsed .nav-section-title {
            display: none;
        }

        .menu-item {
            margin-bottom: 2px;
            border-radius: 8px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
            cursor: pointer;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }

        .menu-link i {
            width: 20px;
            font-size: 0.95rem;
            color: var(--gray-600);
            flex-shrink: 0;
        }

        .menu-link .label {
            flex: 1;
            transition: opacity 0.2s;
        }

        .menu-link .arrow {
            font-size: 0.65rem;
            transition: transform 0.25s;
        }

        .menu-link[aria-expanded="true"] .arrow {
            transform: rotate(90deg);
        }

        .menu-item:hover .menu-link,
        .menu-item.active .menu-link {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
        }

        .menu-item:hover .menu-link i,
        .menu-item.active .menu-link i {
            color: white;
        }

        /* Submenu */
        .submenu {
            padding-left: 32px;
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.25s ease;
        }

        .submenu.show {
            max-height: 200px;
        }

        .sidebar.collapsed .submenu {
            display: none;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            color: var(--gray-600);
            font-size: 0.8rem;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .submenu-link i {
            width: 14px;
            font-size: 0.7rem;
        }

        .submenu-link:hover,
        .submenu-link.active {
            background: var(--gray-100);
            color: var(--primary);
            transform: translateX(3px);
        }

        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: white;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1040;
            transition: left 0.25s ease;
        }

        .sidebar.collapsed ~ .topbar {
            left: var(--sidebar-w-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-toggle {
            background: none;
            border: none;
            color: var(--gray-600);
            font-size: 1.1rem;
            padding: 6px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-toggle:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .page-title {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .page-title i {
            color: var(--primary);
            font-size: 0.95rem;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .btn-bell {
            position: relative;
            background: none;
            border: none;
            font-size: 1.1rem;
            color: var(--gray-600);
            padding: 6px;
            border-radius: 50%;
            cursor: pointer;
        }

        .btn-bell:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .notif-count {
            position: absolute;
            top: 0;
            right: 0;
            background: var(--danger);
            color: white;
            font-size: 0.55rem;
            min-width: 16px;
            padding: 1px 4px;
            border-radius: 20px;
            text-align: center;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid white;
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .user-avatar:hover {
            transform: scale(1.02);
        }

        .user-meta {
            text-align: right;
        }

        .user-meta .name {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-700);
        }

        .user-meta .role {
            font-size: 0.65rem;
            color: var(--gray-600);
        }

        /* Dropdown Panels */
        .dropdown-panel {
            position: fixed;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            z-index: 1060;
            display: none;
            min-width: 220px;
            overflow: hidden;
        }

        .dropdown-panel.show {
            display: block;
            animation: fadeIn 0.15s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #userDropdown {
            top: calc(var(--topbar-h) + 5px);
            right: 16px;
        }

        #notifDropdown {
            top: calc(var(--topbar-h) + 5px);
            right: 70px;
            width: 280px;
        }

        .dd-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 0.8rem;
            transition: background 0.2s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dd-item:hover {
            background: var(--gray-100);
        }

        .dd-item i {
            width: 18px;
            font-size: 0.85rem;
            color: var(--gray-600);
        }

        .dd-item.danger {
            color: var(--danger);
        }

        .dd-item.danger i {
            color: var(--danger);
        }

        .dd-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 4px 0;
        }

        /* Main Content */
        .main-content {
            position: relative;
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 20px;
            height: calc(100vh - var(--topbar-h));
            overflow-y: auto;
            transition: margin-left 0.25s ease;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1045;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-w));
                width: var(--sidebar-w) !important;
                transition: left 0.25s ease;
            }
            .sidebar.mobile-open {
                left: 0;
            }
            .topbar {
                left: 0 !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 16px;
            }
            .btn-toggle-desktop {
                display: none !important;
            }
            .user-meta {
                display: none;
            }
        }

        @media (min-width: 769px) {
            .btn-toggle-mobile {
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobile()"></div>

<aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-logo">
        <i class="fas fa-hand-holding-heart"></i>
        <span>CHANGIA SMART</span>
    </a>

    <nav class="sidebar-nav">
        @php
            $userRole = auth()->user()->role;
            $isEventUser = in_array($userRole, ['event_user', 'user']);
            $isAccountant = $userRole === 'accountant';
            $authUser = auth()->user();
            $userEvents = $isEventUser ? $authUser->ownedEvents()->get() : $authUser->events()->get();
            $hasEvents = $userEvents->isNotEmpty();
            $firstEvent = $userEvents->first();
        @endphp

        <!-- Dashboard -->
        <div class="nav-section">
            
            <div class="menu-item @if(request()->routeIs('dashboard')) active @endif">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="fas fa-chart-line"></i>
                    <span class="label">Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Events Section -->
        @if($isEventUser || $isAccountant)
        <div class="nav-section">
            
            @php $eventsActive = request()->routeIs('events.*'); @endphp
            
            @if($isEventUser)
            <div class="menu-item @if($eventsActive) active @endif">
                <button class="menu-link" onclick="toggleSub('subEvents', this)" aria-expanded="{{ $eventsActive ? 'true' : 'false' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="label">Matukio</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="submenu @if($eventsActive) show @endif" id="subEvents">
                    <a href="{{ route('events.create') }}" class="submenu-link"><i class="fas fa-plus-circle"></i> Sajili</a>
                    <a href="{{ route('events.index') }}" class="submenu-link"><i class="fas fa-list"></i> Orodha</a>
                </div>
            </div>
            @else
            <div class="menu-item @if($eventsActive) active @endif">
                <a href="{{ route('events.index') }}" class="menu-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="label">Matukio</span>
                </a>
            </div>
            @endif
        </div>
        @endif

        <!-- Contributors Section -->
        @if($hasEvents && $firstEvent)
        <div class="nav-section">
            
            @php $contribActive = request()->routeIs('contributors.*'); @endphp
            <div class="menu-item @if($contribActive) active @endif">
                <button class="menu-link" onclick="toggleSub('subContrib', this)" aria-expanded="{{ $contribActive ? 'true' : 'false' }}">
                    <i class="fas fa-users"></i>
                    <span class="label">Wachangiaji</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="submenu @if($contribActive) show @endif" id="subContrib">
                    <a href="{{ route('contributors.create', $firstEvent->id) }}" class="submenu-link"><i class="fas fa-user-plus"></i> Sajili</a>
                    <a href="{{ route('contributors.index', $firstEvent->id) }}" class="submenu-link"><i class="fas fa-address-book"></i> Orodha</a>
                </div>
            </div>
        </div>
        @endif

        <!-- Mhasibu Confirmation -->
        @if($isAccountant)
        <div class="nav-section">
            
            <div class="menu-item @if(request()->routeIs('mhasibu.confirm')) active @endif">
                <a href="{{ route('mhasibu.confirm') }}" class="menu-link">
                    <i class="fas fa-check-double"></i>
                    <span class="label">Thibitisha Michango</span>
                    @php
                        $pendingCount = \App\Models\Contribution::where('status', 'pending')
                            ->whereIn('contributor_id', function($query) use ($authUser) {
                                $eventIds = DB::table('event_accountant')
                                    ->where('accountant_id', $authUser->id)
                                    ->pluck('event_id');
                                $query->select('id')->from('contributors')->whereIn('event_id', $eventIds);
                            })->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning ms-2" style="background: var(--warning); color: white; padding: 2px 6px; border-radius: 20px; font-size: 0.65rem;">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>
        </div>
        @endif

        <!-- Mhasibu Management -->
        @if($isEventUser)
        <div class="nav-section">
            
            @php $mhasibuActive = request()->routeIs('mhasibu.*'); @endphp
            <div class="menu-item @if($mhasibuActive) active @endif">
                <button class="menu-link" onclick="toggleSub('subMhasibu', this)" aria-expanded="{{ $mhasibuActive ? 'true' : 'false' }}">
                    <i class="fas fa-user-tie"></i>
                    <span class="label">Mhasibu</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="submenu @if($mhasibuActive) show @endif" id="subMhasibu">
                    <a href="{{ route('mhasibu.create') }}" class="submenu-link"><i class="fas fa-user-plus"></i> Sajili</a>
                    <a href="{{ route('mhasibu.index') }}" class="submenu-link"><i class="fas fa-list"></i> Orodha</a>
                </div>
            </div>
        </div>
        @endif

        <!-- Cards Section -->
        <div class="nav-section">
            
            @php $cardActive = request()->routeIs('cards.*'); @endphp
            <div class="menu-item @if($cardActive) active @endif">
                <button class="menu-link" onclick="toggleSub('subCards', this)" aria-expanded="{{ $cardActive ? 'true' : 'false' }}">
                    <i class="fas fa-id-card"></i>
                    <span class="label">Card za Mwaliko</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="submenu @if($cardActive) show @endif" id="subCards">
                    <a href="{{ route('cards.create') }}" class="submenu-link"><i class="fas fa-plus-circle"></i> Tengeneza</a>
                    <a href="{{ route('cards.send') }}" class="submenu-link"><i class="fas fa-paper-plane"></i> Tuma</a>
                </div>
            </div>
        </div>

        <!-- Ujumbe Section -->
        <div class="nav-section">
            
            @php $ujumbeActive = request()->routeIs('ujumbe.*'); @endphp
            <div class="menu-item @if($ujumbeActive) active @endif">
                <button class="menu-link" onclick="toggleSub('subUjumbe', this)" aria-expanded="{{ $ujumbeActive ? 'true' : 'false' }}">
                    <i class="fas fa-envelope"></i>
                    <span class="label">Ujumbe</span>
                    <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="submenu @if($ujumbeActive) show @endif" id="subUjumbe">
                    <a href="{{ route('ujumbe.michango') }}" class="submenu-link"><i class="fas fa-hand-holding-heart"></i> Michango</a>
                    <a href="{{ route('ujumbe.mwaliko') }}" class="submenu-link"><i class="fas fa-envelope-open-text"></i> Mwaliko</a>
                </div>
            </div>
        </div>

        <!-- Reports -->
        <div class="nav-section">
            
            <div class="menu-item @if(request()->routeIs('reports.*')) active @endif">
                <a href="{{ route('reports.summary') }}" class="menu-link">
                    <i class="fas fa-file-alt"></i>
                    <span class="label">Ripoti</span>
                </a>
            </div>
        </div>

        <!-- Settings -->
        <div class="nav-section">
            
            <div class="menu-item @if(request()->routeIs('settings.*')) active @endif">
                <a href="{{ route('settings.index') }}" class="menu-link">
                    <i class="fas fa-cog"></i>
                    <span class="label">Mipangilio</span>
                </a>
            </div>
        </div>

        <!-- Logout -->
        <div class="nav-section">
            <div class="menu-item">
                <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                    @csrf
                    <button type="submit" class="menu-link" style="color: var(--danger);">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="label">Toka</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>
</aside>

<header class="topbar" id="topbar">
    <div class="topbar-left">
        <button class="btn-toggle btn-toggle-desktop" onclick="toggleDesktop()"><i class="fas fa-bars"></i></button>
        <button class="btn-toggle btn-toggle-mobile" onclick="openMobile()"><i class="fas fa-bars"></i></button>
        <h1 class="page-title">
            @hasSection('page_title')
                @yield('page_title')
            @else
                <i class="fas fa-tachometer-alt"></i>@yield('title', 'Dashboard')
            @endif
        </h1>
    </div>

    <div class="topbar-right">
        <button class="btn-bell" onclick="togglePanel('notifDropdown', event)">
            <i class="fas fa-bell"></i>
            @php $unread = auth()->user()?->unreadNotifications->count() ?? 0; @endphp
            @if($unread > 0)<span class="notif-count">{{ $unread }}</span>@endif
        </button>

        <div class="user-meta d-none d-md-block">
            <div class="name">{{ auth()->user()?->name ?? 'Mtumiaji' }}</div>
            <div class="role">{{ ucfirst(auth()->user()?->role ?? 'Mratibu') }}</div>
        </div>
        <div class="user-avatar" onclick="togglePanel('userDropdown', event)">
            {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
        </div>
    </div>
</header>

<!-- Notifications Dropdown -->
<div class="dropdown-panel" id="notifDropdown">
    @forelse(auth()->user()?->unreadNotifications->take(5) ?? [] as $notif)
        <div class="dd-item">
            <i class="fas fa-info-circle"></i>
            <div style="flex:1">
                <div style="font-weight:600;">{{ $notif->data['title'] ?? 'Arifa' }}</div>
                <div style="font-size:0.7rem; color:var(--gray-600);">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
        </div>
    @empty
        <div class="dd-item" style="justify-content:center; color:var(--gray-600);">Hakuna arifa mpya</div>
    @endforelse
    @if(auth()->user()?->unreadNotifications->count() > 5)
        <div class="dd-divider"></div>
        <a href="{{ route('notifications.index') }}" class="dd-item" style="justify-content:center; color:var(--primary);">Tazama zote</a>
    @endif
</div>

<!-- User Dropdown -->
<div class="dropdown-panel" id="userDropdown">
    <a href="{{ route('profile') }}" class="dd-item"><i class="fas fa-user"></i> Profaili</a>
    <a href="{{ route('settings.index') }}" class="dd-item"><i class="fas fa-cog"></i> Mipangilio</a>
    <div class="dd-divider"></div>
    <form method="POST" action="{{ route('logout') }}" id="logoutFormDropdown">
        @csrf
        <button type="submit" class="dd-item danger"><i class="fas fa-sign-out-alt"></i> Toka</button>
    </form>
</div>

<main class="main-content" id="mainContent">
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) sidebar.classList.add('collapsed');

    function toggleDesktop() {
        isCollapsed = !isCollapsed;
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        sidebar.classList.toggle('collapsed', isCollapsed);
    }

    function openMobile() {
        sidebar.classList.add('mobile-open');
        document.getElementById('sidebarOverlay').classList.add('show');
    }

    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        document.getElementById('sidebarOverlay').classList.remove('show');
    }

    function toggleSub(id, btn) {
        const submenu = document.getElementById(id);
        if (!submenu) return;
        
        const isExpanded = submenu.classList.contains('show');
        
        document.querySelectorAll('.submenu').forEach(menu => {
            if (menu.id !== id && menu.classList.contains('show')) {
                menu.classList.remove('show');
                const menuBtn = menu.previousElementSibling;
                if (menuBtn && menuBtn.classList.contains('menu-link')) {
                    menuBtn.setAttribute('aria-expanded', 'false');
                }
            }
        });
        
        if (isExpanded) {
            submenu.classList.remove('show');
            btn.setAttribute('aria-expanded', 'false');
        } else {
            submenu.classList.add('show');
            btn.setAttribute('aria-expanded', 'true');
        }
    }

    function togglePanel(id, e) {
        e.stopPropagation();
        const panel = document.getElementById(id);
        document.querySelectorAll('.dropdown-panel.show').forEach(p => {
            if (p.id !== id) p.classList.remove('show');
        });
        panel.classList.toggle('show');
    }

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-panel.show').forEach(p => p.classList.remove('show'));
    });

    // Logout Confirmation
    document.querySelectorAll('#logoutForm, #logoutFormDropdown').forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                Swal.fire({
                    title: 'Una uhakika?',
                    text: 'Unataka kutoka kwenye akaunti yako?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF6F00',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ndio, Toka',
                    cancelButtonText: 'Ghairi'
                }).then(result => {
                    if (result.isConfirmed) formElement.submit();
                });
            });
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) closeMobile();
    });
</script>

@stack('scripts')
</body>
</html>