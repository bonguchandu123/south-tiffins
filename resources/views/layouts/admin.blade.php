<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — South Tiffins')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700;0,9..144,900;1,9..144,700;1,9..144,900&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ══ TOKENS ══ */
        :root {
            --primary:        #FF6B35;
            --primary-dark:   #E85520;
            --primary-light:  #FFF0E8;
            --green:          #2D6A4F;
            --green-light:    #E8F5EE;
            --dark:           #1A1A1A;
            --muted:          #666666;
            --border:         #EEEEEE;
            --bg:             #FAFAFA;
            --font-display:   'Fraunces', serif;
            --font-body:      'Outfit', sans-serif;
            --radius-sm:  8px; --radius-md: 12px;
            --radius-lg: 16px; --radius-xl: 20px;
            --radius-full: 9999px;
            --shadow-sm:  0 2px 8px rgba(0,0,0,0.06);
            --shadow-md:  0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg:  0 8px 32px rgba(0,0,0,0.12);
            --shadow-primary: 0 8px 24px rgba(255,107,53,0.3);
            --transition: all 0.3s ease;
            --sidebar-w: 240px;
            --topbar-h:  60px;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; -webkit-tap-highlight-color:transparent; }
        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }
        a { text-decoration:none; color:inherit; }
        img { max-width:100%; display:block; }

        /* ══ LAYOUT ══ */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ══ SIDEBAR ══ */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--dark);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 200;
            transition: transform 0.3s ease;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .admin-sidebar::-webkit-scrollbar { display:none; }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-logo-img-wrap {
            background: white;
            border-radius: 10px;
            padding: 4px 7px;
            flex-shrink: 0;
        }

        .sidebar-logo img {
            height: 32px;
            width: auto;
            display: block;
        }

        .sidebar-brand {
            font-family: var(--font-display);
            font-weight: 700;
            font-style: italic;
            font-size: 16px;
            color: var(--primary);
            line-height: 1.1;
        }

        .sidebar-role {
            font-size: 10px;
            color: rgba(255,255,255,0.35);
            font-weight: 300;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 14px 10px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar-section-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            padding: 10px 10px 6px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 12px;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255,255,255,0.55);
            transition: var(--transition);
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar-link:hover {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.9);
        }

        .sidebar-link.active {
            background: rgba(255,107,53,0.18);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-link.active i { color: var(--primary); }

        .sidebar-footer {
            padding: 12px 10px 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-counter-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.45);
            transition: var(--transition);
        }

        .sidebar-counter-link:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.8);
        }

        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.35);
            transition: var(--transition);
            cursor: pointer;
        }

        .sidebar-logout:hover {
            background: rgba(198,40,40,0.15);
            color: #ff6b6b;
        }

        /* ══ SIDEBAR OVERLAY (mobile) ══ */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 199;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.open { display: block; }

        /* ══ MAIN ══ */
        .admin-main {
            flex: 1;
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* ══ TOPBAR ══ */
        .admin-topbar {
            background: white;
            border-bottom: 1.5px solid var(--border);
            height: var(--topbar-h);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--dark);
            border-radius: 2px;
            transition: var(--transition);
        }

        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .admin-page-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 18px;
            color: var(--dark);
            letter-spacing: -0.01em;
        }

        .admin-topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .admin-date {
            font-size: 12px;
            color: var(--muted);
            font-weight: 400;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
            background: var(--bg);
            padding: 6px 12px;
            border-radius: var(--radius-full);
            border: 1.5px solid var(--border);
        }

        .admin-user i { color: var(--primary); font-size: 15px; }

        /* ══ CONTENT ══ */
        .admin-content {
            flex: 1;
            padding: 24px;
        }

        /* ══ SHARED ADMIN COMPONENTS ══ */
        .admin-panel {
            background: white;
            border-radius: var(--radius-xl);
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .admin-panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-panel-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 16px;
            color: var(--dark);
        }

        .admin-panel-body { padding: 22px; }

        /* Table */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1.5px solid var(--border);
            background: var(--bg);
            white-space: nowrap;
        }

        .admin-table td {
            padding: 13px 16px;
            font-size: 13px;
            color: var(--dark);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tbody tr:hover { background: var(--bg); }

        /* Buttons */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--primary); color: white;
            padding: 11px 22px; border-radius: var(--radius-full);
            font-family: var(--font-body); font-weight: 600; font-size: 14px;
            border: none; cursor: pointer;
            box-shadow: var(--shadow-primary); transition: var(--transition);
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); color: white; }

        .btn-outline {
            display: inline-flex; align-items: center; gap: 8px;
            background: transparent; color: var(--primary);
            padding: 9px 20px; border-radius: var(--radius-full);
            font-family: var(--font-body); font-weight: 600; font-size: 14px;
            border: 2px solid var(--primary); cursor: pointer; transition: var(--transition);
        }
        .btn-outline:hover { background: var(--primary); color: white; }

        .btn-sm {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: var(--radius-full);
            font-family: var(--font-body); font-weight: 600; font-size: 12px;
            border: none; cursor: pointer; transition: var(--transition);
            white-space: nowrap;
        }
        .btn-sm-primary { background: var(--primary); color: white; box-shadow: 0 2px 8px rgba(255,107,53,0.25); }
        .btn-sm-primary:hover { background: var(--primary-dark); color: white; }
        .btn-sm-outline { background: transparent; color: var(--primary); border: 1.5px solid var(--primary); }
        .btn-sm-outline:hover { background: var(--primary); color: white; }
        .btn-sm-danger  { background: rgba(198,40,40,0.1); color: #C62828; border: 1.5px solid rgba(198,40,40,0.2); }
        .btn-sm-danger:hover { background: rgba(198,40,40,0.18); }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--dark); margin-bottom: 7px; letter-spacing: 0.02em;
        }
        input, select, textarea {
            width: 100%; padding: 11px 14px;
            border: 1.5px solid var(--border); border-radius: var(--radius-md);
            font-family: var(--font-body); font-size: 14px;
            color: var(--dark); background: white;
            outline: none; transition: var(--transition);
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
        }
        input::placeholder, textarea::placeholder { color: #AAAAAA; font-weight: 300; }

        /* Badges */
        .badge-pending   { background:rgba(255,193,7,0.15);  color:#856404; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; border:1px solid rgba(255,193,7,0.3);  display:inline-block; }
        .badge-preparing { background:rgba(33,150,243,0.12); color:#1565C0; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; border:1px solid rgba(33,150,243,0.3); display:inline-block; }
        .badge-ready     { background:rgba(255,152,0,0.12);  color:#E65100; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; border:1px solid rgba(255,152,0,0.3);  display:inline-block; }
        .badge-served    { background:rgba(46,125,50,0.12);  color:#2E7D32; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; border:1px solid rgba(46,125,50,0.3);  display:inline-block; }
        .badge-paid      { background:rgba(46,125,50,0.12);  color:#2E7D32; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; display:inline-block; }
        .badge-unpaid    { background:rgba(198,40,40,0.12);  color:#C62828; font-size:11px; font-weight:600; padding:3px 9px; border-radius:20px; display:inline-block; }

        /* Veg dots */
        .veg-dot { display:inline-flex; align-items:center; justify-content:center; width:14px; height:14px; border:2px solid var(--green); border-radius:2px; flex-shrink:0; }
        .veg-dot::after { content:''; display:block; width:7px; height:7px; background:var(--green); border-radius:50%; }
        .nonveg-dot { display:inline-flex; align-items:center; justify-content:center; width:14px; height:14px; border:2px solid #e53935; border-radius:2px; flex-shrink:0; }
        .nonveg-dot::after { content:''; display:block; width:7px; height:7px; background:#e53935; border-radius:50%; }

        /* Modal */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 500; display: none;
            align-items: center; justify-content: center;
            backdrop-filter: blur(3px); padding: 20px;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: white; border-radius: var(--radius-xl);
            width: 100%; max-width: 480px;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .modal-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px; border-bottom: 1px solid var(--border);
            position: sticky; top: 0; background: white; z-index: 1;
        }
        .modal-title { font-family: var(--font-display); font-weight: 700; font-size: 18px; color: var(--dark); }
        .modal-close {
            width: 30px; height: 30px; background: var(--bg);
            border: 1px solid var(--border); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 13px; color: var(--muted); transition: var(--transition);
        }
        .modal-close:hover { background: var(--primary-light); color: var(--primary); }
        .modal-body   { padding: 20px 22px; }
        .modal-footer { padding: 16px 22px; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; }

        /* Skeleton */
        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        .skeleton {
            background: linear-gradient(90deg,#f0f0f0 25%,#f8f8f8 50%,#f0f0f0 75%);
            background-size: 200% 100%; animation: shimmer 1.5s infinite;
            border-radius: var(--radius-md);
        }

        /* Card */
        .card { background:white; border-radius:var(--radius-lg); box-shadow:var(--shadow-md); padding:20px; }

        /* ══ RESPONSIVE — TABLET ≤ 1024px ══ */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 200px; }
            .admin-content { padding: 18px; }
        }

        /* ══ RESPONSIVE — MOBILE ≤ 768px ══ */
        @media (max-width: 768px) {
            /* Sidebar hidden off-screen */
            .admin-sidebar {
                transform: translateX(-100%);
                width: 260px;
                box-shadow: none;
            }
            .admin-sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 24px rgba(0,0,0,0.2);
            }

            /* Main takes full width */
            .admin-main { margin-left: 0; }

            /* Show hamburger */
            .hamburger { display: flex; }

            /* Topbar */
            .admin-topbar { padding: 0 16px; }
            .admin-date   { display: none; }
            .admin-user   { padding: 5px 10px; font-size: 12px; }

            /* Content */
            .admin-content { padding: 14px; }

            /* Table — horizontal scroll */
            .admin-panel { overflow-x: auto; }
            .admin-table th,
            .admin-table td { padding: 10px 12px; font-size: 12px; }

            /* Modal */
            .modal-overlay { padding: 0; align-items: flex-end; }
            .modal { border-radius: 20px 20px 0 0; max-height: 95vh; max-width: 100%; }
            .modal-header { border-radius: 20px 20px 0 0; }
        }

        /* ══ EXTRA SMALL ≤ 480px ══ */
        @media (max-width: 480px) {
            .admin-page-title { font-size: 15px; }
            .admin-user span  { display: none; }
            .btn-primary, .btn-outline { padding: 10px 16px; font-size: 13px; }
        }
    </style>

    @yield('styles')
</head>
<body>

<div class="admin-layout">

    {{-- Sidebar overlay (mobile backdrop) --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-logo">
            <div class="sidebar-logo-img-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <div>
                <div class="sidebar-brand">South Tiffins</div>
                <div class="sidebar-role">Admin Panel</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="sidebar-section-label">Main</span>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-list-alt"></i> Orders
            </a>
            <a href="{{ route('admin.menu') }}" class="sidebar-link {{ request()->routeIs('admin.menu') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-utensils"></i> Menu
            </a>
            <a href="{{ route('admin.tables') }}" class="sidebar-link {{ request()->routeIs('admin.tables') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-chair"></i> Tables
            </a>
            <span class="sidebar-section-label" style="margin-top:8px;">Finance</span>
            <a href="{{ route('admin.billing') }}" class="sidebar-link {{ request()->routeIs('admin.billing') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-receipt"></i> Billing
            </a>
            <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-file-pdf"></i> Reports
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('counter.index') }}" class="sidebar-counter-link" target="_blank">
                <i class="fas fa-desktop"></i> Counter View
            </a>
            <a href="{{ route('logout') }}" class="sidebar-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="admin-main">

        <div class="admin-topbar">
            <div class="topbar-left">
                <button class="hamburger" id="hamburger" onclick="toggleSidebar()" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
                <div class="admin-page-title">@yield('page-title', 'Dashboard')</div>
            </div>
            <div class="admin-topbar-right">
                <div class="admin-date">{{ date('D, d M Y') }}</div>
                <div class="admin-user">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ session('admin_name', 'Admin') }}</span>
                </div>
            </div>
        </div>

        <div class="admin-content">
            @yield('content')
        </div>

    </main>

</div>

<script>
    function toggleSidebar() {
        const sidebar  = document.getElementById('adminSidebar');
        const overlay  = document.getElementById('sidebarOverlay');
        const burger   = document.getElementById('hamburger');
        const isOpen   = sidebar.classList.toggle('open');
        overlay.classList.toggle('open', isOpen);
        burger.classList.toggle('open', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';
    }

    function closeSidebar() {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('open');
        document.getElementById('hamburger').classList.remove('open');
        document.body.style.overflow = '';
    }

    // Close on resize to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) closeSidebar();
    });

    // Close modal helpers used across admin pages
    function openModal(id)  { document.getElementById(id).classList.add('open'); }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }
</script>

@yield('scripts')

</body>
</html>