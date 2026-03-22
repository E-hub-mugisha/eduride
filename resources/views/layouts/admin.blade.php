<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · EDURIDE Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @stack('styles')

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:      #050B18;
            --navy-2:    #0D1628;
            --navy-3:    #121E35;
            --navy-4:    #162038;
            --teal:      #00E5C3;
            --teal-dim:  #00B89A;
            --gold:      #FFB547;
            --white:     #F0F4FF;
            --muted:     #7A8BAA;
            --danger:    #FB7185;
            --success:   #34D399;
            --warning:   #FBBF24;
            --info:      #63B3ED;
            --sidebar-w: 260px;
            --topbar-h:  64px;
            --font-d:    'Syne', sans-serif;
            --font-b:    'DM Sans', sans-serif;
        }

        body {
            background: var(--navy);
            color: var(--white);
            font-family: var(--font-b);
            font-size: 15px;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy-2);
            border-right: 1px solid rgba(0,229,195,.08);
            display: flex; flex-direction: column;
            z-index: 200;
            overflow-y: auto;
            transition: transform .3s ease;
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 22px;
            border-bottom: 1px solid rgba(255,255,255,.05);
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            display: flex; align-items: center; justify-content: center;
            color: var(--navy); font-size: 1.1rem; flex-shrink: 0;
        }
        .sidebar-logo-text {
            font-family: var(--font-d);
            font-size: 1.2rem; font-weight: 800; letter-spacing: -.02em;
            color: var(--white);
        }
        .sidebar-logo-text span { color: var(--teal); }

        .sidebar-section {
            padding: 20px 14px 8px;
            font-size: .68rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: rgba(122,139,170,.5);
        }

        .sidebar-nav { padding: 0 10px; flex: 1; }
        .nav-item {
            display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 10px;
            color: var(--muted); text-decoration: none;
            font-size: .875rem; font-weight: 500;
            transition: all .2s;
            margin-bottom: 2px;
        }
        .nav-item i { font-size: 1rem; width: 18px; text-align: center; flex-shrink: 0; }
        .nav-item:hover { background: rgba(255,255,255,.05); color: var(--white); }
        .nav-item.active {
            background: rgba(0,229,195,.1);
            color: var(--teal);
            border: 1px solid rgba(0,229,195,.15);
        }
        .nav-item .badge-count {
            margin-left: auto;
            background: var(--teal); color: var(--navy);
            font-size: .65rem; font-weight: 700;
            padding: 2px 7px; border-radius: 20px;
        }

        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid rgba(255,255,255,.05);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px; border-radius: 10px;
            cursor: pointer;
            transition: background .2s;
        }
        .sidebar-user:hover { background: rgba(255,255,255,.05); }
        .sidebar-user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            object-fit: cover; flex-shrink: 0;
        }
        .sidebar-user-name {
            font-size: .83rem; font-weight: 600;
            color: var(--white); line-height: 1.2;
        }
        .sidebar-user-role {
            font-size: .72rem; color: var(--muted);
        }
        .sidebar-user-actions { margin-left: auto; }

        /* ── Topbar ── */
        .topbar {
            position: fixed; top: 0;
            left: var(--sidebar-w); right: 0;
            height: var(--topbar-h);
            background: rgba(5,11,24,.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,.05);
            display: flex; align-items: center;
            padding: 0 28px; gap: 16px;
            z-index: 100;
        }
        .topbar-title {
            font-family: var(--font-d);
            font-size: 1.1rem; font-weight: 700;
            color: var(--white);
        }
        .topbar-breadcrumb {
            font-size: .82rem; color: var(--muted);
            display: flex; align-items: center; gap: 6px;
        }
        .topbar-breadcrumb a { color: var(--muted); text-decoration: none; }
        .topbar-breadcrumb a:hover { color: var(--teal); }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }

        .topbar-icon-btn {
            width: 38px; height: 38px; border-radius: 10px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); font-size: 1rem;
            text-decoration: none; cursor: pointer;
            transition: all .2s; position: relative;
        }
        .topbar-icon-btn:hover { color: var(--white); background: rgba(255,255,255,.1); }
        .topbar-icon-btn .dot {
            position: absolute; top: 6px; right: 6px;
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--danger);
            border: 1.5px solid var(--navy-2);
        }

        /* ── Main content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }
        .page-content {
            padding: 32px 32px;
            max-width: 1400px;
        }

        /* ── Page header ── */
        .page-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 28px; flex-wrap: wrap; gap: 16px;
        }
        .page-header-left h1 {
            font-family: var(--font-d);
            font-size: 1.5rem; font-weight: 800;
            letter-spacing: -.02em; color: var(--white);
            margin-bottom: 4px;
        }
        .page-header-left p { color: var(--muted); font-size: .875rem; }
        .page-header-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            font-family: var(--font-d); font-size: .85rem; font-weight: 700;
            padding: 10px 18px; border-radius: 10px;
            border: none; cursor: pointer;
            text-decoration: none; transition: all .25s;
            white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            color: var(--navy);
            box-shadow: 0 4px 14px rgba(0,229,195,.2);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,229,195,.35); color: var(--navy); }
        .btn-secondary {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            color: var(--muted);
        }
        .btn-secondary:hover { color: var(--white); border-color: rgba(255,255,255,.25); }
        .btn-danger {
            background: rgba(251,113,133,.1);
            border: 1px solid rgba(251,113,133,.25);
            color: var(--danger);
        }
        .btn-danger:hover { background: rgba(251,113,133,.2); }
        .btn-sm { padding: 6px 12px; font-size: .78rem; border-radius: 8px; }
        .btn-icon { padding: 8px; border-radius: 8px; }

        /* ── Cards ── */
        .card {
            background: var(--navy-2);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 18px;
            overflow: hidden;
        }
        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid rgba(255,255,255,.05);
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
        }
        .card-title {
            font-family: var(--font-d);
            font-size: 1rem; font-weight: 700;
            color: var(--white);
            display: flex; align-items: center; gap: 8px;
        }
        .card-title i { color: var(--teal); }
        .card-body { padding: 22px; }

        /* ── Stat cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: var(--navy-2);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 16px;
            padding: 20px 22px;
            transition: border-color .25s;
        }
        .stat-card:hover { border-color: rgba(0,229,195,.2); }
        .stat-card-top {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 12px;
        }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .stat-icon.teal   { background: rgba(0,229,195,.12); color: var(--teal); }
        .stat-icon.gold   { background: rgba(255,181,71,.12); color: var(--gold); }
        .stat-icon.blue   { background: rgba(99,179,237,.12); color: var(--info); }
        .stat-icon.red    { background: rgba(251,113,133,.12); color: var(--danger); }
        .stat-icon.green  { background: rgba(52,211,153,.12); color: var(--success); }
        .stat-value {
            font-family: var(--font-d);
            font-size: 1.9rem; font-weight: 800;
            color: var(--white); line-height: 1;
        }
        .stat-label { font-size: .8rem; color: var(--muted); margin-top: 4px; }
        .stat-sub {
            font-size: .75rem; color: var(--muted);
            display: flex; align-items: center; gap: 5px;
        }
        .stat-sub.up   { color: var(--success); }
        .stat-sub.down { color: var(--danger); }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            font-size: .72rem; font-weight: 700;
            letter-spacing: .08em; text-transform: uppercase;
            color: var(--muted);
            padding: 12px 16px;
            border-bottom: 1px solid rgba(255,255,255,.05);
            white-space: nowrap; text-align: left;
        }
        tbody tr {
            border-bottom: 1px solid rgba(255,255,255,.04);
            transition: background .15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,.025); }
        tbody td {
            padding: 14px 16px;
            font-size: .875rem; color: var(--white);
            vertical-align: middle;
        }
        .td-muted { color: var(--muted); font-size: .82rem; }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: .7rem; font-weight: 700; letter-spacing: .04em;
            padding: 4px 10px; border-radius: 20px;
            text-transform: uppercase;
        }
        .badge i { font-size: .7rem; }
        .badge-success { background: rgba(52,211,153,.12); color: var(--success); border: 1px solid rgba(52,211,153,.2); }
        .badge-danger  { background: rgba(251,113,133,.12); color: var(--danger);  border: 1px solid rgba(251,113,133,.2); }
        .badge-warning { background: rgba(251,191,36,.12);  color: var(--warning); border: 1px solid rgba(251,191,36,.2); }
        .badge-info    { background: rgba(99,179,237,.12);  color: var(--info);    border: 1px solid rgba(99,179,237,.2); }
        .badge-secondary { background: rgba(255,255,255,.07); color: var(--muted); border: 1px solid rgba(255,255,255,.1); }
        .badge-teal    { background: rgba(0,229,195,.1); color: var(--teal); border: 1px solid rgba(0,229,195,.2); }

        /* ── Forms ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 0; }
        .form-group.full { grid-column: 1 / -1; }
        .form-label {
            display: block; font-size: .73rem; font-weight: 700;
            letter-spacing: .06em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 8px;
        }
        .form-label .req { color: var(--teal); }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: var(--navy-3);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 10px;
            color: var(--white);
            font-family: var(--font-b);
            font-size: .9rem;
            padding: 11px 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
        }
        .form-input::placeholder, .form-textarea::placeholder { color: rgba(122,139,170,.4); }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(0,229,195,.08);
        }
        .form-input.error { border-color: rgba(251,113,133,.5); }
        .form-select option { background: var(--navy-3); }
        .form-textarea { resize: vertical; min-height: 90px; }
        .form-error { color: var(--danger); font-size: .78rem; margin-top: 5px; }
        .form-hint  { color: var(--muted);  font-size: .75rem; margin-top: 5px; }

        /* Section divider inside forms */
        .form-section {
            padding: 22px;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .form-section:last-child { border-bottom: none; }
        .form-section-title {
            font-family: var(--font-d); font-size: .78rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--teal); margin-bottom: 18px;
            display: flex; align-items: center; gap: 7px;
        }

        /* ── Alert flash ── */
        .flash {
            display: flex; align-items: center; gap: 10px;
            padding: 14px 18px; border-radius: 12px;
            font-size: .875rem; margin-bottom: 24px;
        }
        .flash i { font-size: 1rem; flex-shrink: 0; }
        .flash-success {
            background: rgba(52,211,153,.08);
            border: 1px solid rgba(52,211,153,.2);
            color: var(--success);
        }
        .flash-error {
            background: rgba(251,113,133,.08);
            border: 1px solid rgba(251,113,133,.2);
            color: var(--danger);
        }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 4px; margin-top: 20px; justify-content: center; }
        .pagination a, .pagination span {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 8px;
            font-size: .82rem; font-weight: 600;
            text-decoration: none; color: var(--muted);
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            transition: all .2s;
        }
        .pagination a:hover { color: var(--white); border-color: rgba(255,255,255,.2); }
        .pagination .active span {
            background: var(--teal); color: var(--navy);
            border-color: var(--teal);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center; padding: 60px 20px;
        }
        .empty-state i {
            font-size: 2.5rem; color: rgba(122,139,170,.3);
            display: block; margin-bottom: 14px;
        }
        .empty-state h3 {
            font-family: var(--font-d);
            font-size: 1rem; font-weight: 700;
            color: var(--muted); margin-bottom: 6px;
        }
        .empty-state p { color: rgba(122,139,170,.6); font-size: .85rem; }

        /* ── User avatar row ── */
        .avatar-row { display: flex; align-items: center; gap: 10px; }
        .avatar-sm {
            width: 34px; height: 34px; border-radius: 50%;
            object-fit: cover; flex-shrink: 0;
        }
        .avatar-name { font-weight: 600; font-size: .875rem; color: var(--white); }
        .avatar-sub  { font-size: .75rem; color: var(--muted); }

        /* ── Live dot ── */
        .live-dot {
            display: inline-block;
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--success);
            animation: blink 1.4s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.3;} }

        /* ── Responsive ── */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .topbar { left: 0; }
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .page-content { padding: 20px 16px; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="sidebar" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon"><i class="bi bi-bus-front-fill"></i></div>
        <span class="sidebar-logo-text">EDU<span>RIDE</span></span>
    </a>

    <div class="sidebar-nav">
        <div class="sidebar-section">Main</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.map') }}"
           class="nav-item {{ request()->routeIs('admin.map') ? 'active' : '' }}">
            <i class="bi bi-map-fill"></i> Live Map
        </a>
        <a href="{{ route('admin.trips.index') }}"
           class="nav-item {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">
            <i class="bi bi-play-circle-fill"></i> Trips
        </a>

        <div class="sidebar-section">Management</div>

        <a href="{{ route('admin.vehicles.index') }}"
           class="nav-item {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
            <i class="bi bi-truck-front-fill"></i> Vehicles
        </a>
        <a href="{{ route('admin.drivers.index') }}"
           class="nav-item {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge-fill"></i> Drivers
        </a>
        <a href="{{ route('admin.routes.index') }}"
           class="nav-item {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}">
            <i class="bi bi-signpost-2-fill"></i> Routes
        </a>
        <a href="{{ route('admin.students.index') }}"
           class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Students
        </a>

        <div class="sidebar-section">System</div>

        <a href="{{ route('admin.users.index') }}"
           class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> User Management
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <img src="{{ auth()->user()->avatar_url }}"
                 alt="{{ auth()->user()->name }}"
                 class="sidebar-user-avatar">
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">Administrator</div>
            </div>
            <div class="sidebar-user-actions">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:.95rem;" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- ── Topbar ── --}}
<header class="topbar">
    <button onclick="document.getElementById('sidebar').classList.toggle('open')"
            style="background:none;border:none;cursor:pointer;color:var(--muted);font-size:1.2rem;display:none;"
            id="menuBtn">
        <i class="bi bi-list"></i>
    </button>

    <div>
        <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <i class="bi bi-chevron-right" style="font-size:.65rem;"></i>
            @yield('breadcrumb', 'Dashboard')
        </div>
    </div>

    <div class="topbar-right">
        {{-- Active trips indicator --}}
        @php $activeCount = \App\Models\Trip::where('status','in_progress')->count(); @endphp
        @if($activeCount)
        <a href="{{ route('admin.map') }}" class="topbar-icon-btn" title="{{ $activeCount }} active trips">
            <i class="bi bi-geo-alt-fill" style="color:var(--teal);"></i>
            <span style="position:absolute;top:5px;right:5px;width:8px;height:8px;border-radius:50%;background:var(--teal);border:2px solid var(--navy-2);"></span>
        </a>
        @endif

        <a href="#" class="topbar-icon-btn" title="Notifications">
            <i class="bi bi-bell"></i>
            @php $unread = \App\Models\TransportNotification::where('is_read', false)->count(); @endphp
            @if($unread) <span class="dot"></span> @endif
        </a>
    </div>
</header>

{{-- ── Page content ── --}}
<main class="main-content">
    <div class="page-content">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="flash flash-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
    // Auto-hide flash after 4s
    document.querySelectorAll('.flash').forEach(el => {
        setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .4s'; setTimeout(() => el.remove(), 400); }, 4000);
    });

    // Show hamburger on mobile
    const menuBtn = document.getElementById('menuBtn');
    if (window.innerWidth <= 1024) menuBtn.style.display = 'block';
    window.addEventListener('resize', () => {
        menuBtn.style.display = window.innerWidth <= 1024 ? 'block' : 'none';
    });
</script>

@stack('scripts')
</body>
</html>