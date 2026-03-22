<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My Dashboard') · EDURIDE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --navy: #050B18;
            --navy-2: #0D1628;
            --navy-3: #121E35;
            --teal: #00E5C3;
            --teal-dim: #00B89A;
            --gold: #FFB547;
            --white: #F0F4FF;
            --muted: #7A8BAA;
            --danger: #FB7185;
            --success: #34D399;
            --warning: #FBBF24;
            --info: #63B3ED;
            --fd: 'Syne', sans-serif;
            --fb: 'DM Sans', sans-serif;
        }

        body {
            background: var(--navy);
            color: var(--white);
            font-family: var(--fb);
            min-height: 100vh;
            padding-bottom: 80px;
        }

        /* ── Topbar ── */
        .topbar {
            background: rgba(13, 22, 40, .92);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, .06);
            padding: 0 20px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 9px;
            text-decoration: none;
        }

        .brand-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: .95rem;
        }

        .brand-name {
            font-family: var(--fd);
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--white);
        }

        .brand-name span {
            color: var(--teal);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .07);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            text-decoration: none;
            position: relative;
            transition: all .2s;
        }

        .icon-btn:hover {
            color: var(--white);
        }

        .notif-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--danger);
            border: 2px solid var(--navy-2);
        }

        .avatar-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(0, 229, 195, .25);
            cursor: pointer;
        }

        /* ── Page ── */
        .page {
            max-width: 680px;
            margin: 0 auto;
            padding: 24px 16px;
        }

        .page-title {
            font-family: var(--fd);
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--white);
            margin-bottom: 4px;
        }

        .page-sub {
            font-size: .85rem;
            color: var(--muted);
            margin-bottom: 24px;
        }

        /* ── Cards ── */
        .card {
            background: var(--navy-2);
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .card-header {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .card-title {
            font-family: var(--fd);
            font-size: .9rem;
            font-weight: 700;
            color: var(--white);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .card-title i {
            color: var(--teal);
        }

        .card-body {
            padding: 18px;
        }

        /* ── Badges ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .67rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 20px;
        }

        .badge-success {
            background: rgba(52, 211, 153, .1);
            color: var(--success);
            border: 1px solid rgba(52, 211, 153, .2);
        }

        .badge-danger {
            background: rgba(251, 113, 133, .1);
            color: var(--danger);
            border: 1px solid rgba(251, 113, 133, .2);
        }

        .badge-warning {
            background: rgba(251, 191, 36, .1);
            color: var(--warning);
            border: 1px solid rgba(251, 191, 36, .2);
        }

        .badge-info {
            background: rgba(99, 179, 237, .1);
            color: var(--info);
            border: 1px solid rgba(99, 179, 237, .2);
        }

        .badge-teal {
            background: rgba(0, 229, 195, .1);
            color: var(--teal);
            border: 1px solid rgba(0, 229, 195, .2);
        }

        .badge-secondary {
            background: rgba(255, 255, 255, .07);
            color: var(--muted);
            border: 1px solid rgba(255, 255, 255, .1);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-family: var(--fd);
            font-size: .85rem;
            font-weight: 700;
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all .25s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            color: var(--navy);
            box-shadow: 0 4px 14px rgba(0, 229, 195, .2);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(0, 229, 195, .35);
            color: var(--navy);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            color: var(--muted);
        }

        .btn-secondary:hover {
            color: var(--white);
            border-color: rgba(255, 255, 255, .25);
        }

        .btn-sm {
            padding: 7px 14px;
            font-size: .78rem;
            border-radius: 8px;
        }

        /* ── Live dot ── */
        .live-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--success);
            animation: blink 1.4s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .2;
            }
        }

        /* ── Bottom nav ── */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(13, 22, 40, .95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, .07);
            display: flex;
            padding: 10px 0 16px;
        }

        .nav-tab {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: var(--muted);
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            transition: color .2s;
        }

        .nav-tab i {
            font-size: 1.2rem;
            transition: color .2s;
        }

        .nav-tab.active {
            color: var(--teal);
        }

        .nav-tab .notif-pip {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--danger);
            position: absolute;
            top: -1px;
            right: -1px;
        }

        /* ── Flash ── */
        .flash {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 16px;
            border-radius: 12px;
            font-size: .87rem;
            margin-bottom: 20px;
        }

        .flash-success {
            background: rgba(52, 211, 153, .08);
            border: 1px solid rgba(52, 211, 153, .2);
            color: var(--success);
        }

        .flash-error {
            background: rgba(251, 113, 133, .08);
            border: 1px solid rgba(251, 113, 133, .2);
            color: var(--danger);
        }

        /* ── Empty state ── */
        .empty {
            text-align: center;
            padding: 40px 20px;
        }

        .empty i {
            font-size: 2rem;
            color: rgba(122, 139, 170, .25);
            display: block;
            margin-bottom: 10px;
        }

        .empty p {
            color: var(--muted);
            font-size: .85rem;
        }
    </style>
</head>

<body>

    <header class="topbar">
        <a href="{{ route('parent.dashboard') }}" class="brand">
            <div class="brand-icon"><i class="bi bi-bus-front-fill"></i></div>
            <span class="brand-name">EDU<span>RIDE</span></span>
        </a>
        <div class="topbar-right">
            @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
            <a href="{{ route('parent.notifications') }}" class="icon-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                @if($unread) <span class="notif-badge"></span> @endif
            </a>
            <img src="{{ auth()->user()->avatar_url }}" class="avatar-btn" alt="{{ auth()->user()->name }}">
        </div>
    </header>

    <div class="page">
        @if(session('success'))
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i>{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <nav class="bottom-nav">
        <a href="{{ route('parent.dashboard') }}" class="nav-tab {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i> Home
        </a>
        <a href="{{ route('parent.notifications') }}" class="nav-tab {{ request()->routeIs('parent.notifications') ? 'active' : '' }}" style="position:relative;">
            <i class="bi bi-bell-fill"></i>
            @if(isset($unread) && $unread) <span class="notif-pip"></span> @endif
            Alerts
        </a>
        <a href="#" class="nav-tab" onclick="showLogout(event)">
            <i class="bi bi-person-fill"></i> Account
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" id="logoutForm">@csrf</form>

    <script>
        function showLogout(e) {
            e.preventDefault();
            if (confirm('Sign out of EDURIDE?')) document.getElementById('logoutForm').submit();
        }
        // Auto-hide flash
        document.querySelectorAll('.flash').forEach(el => {
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transition = 'opacity .4s';
                setTimeout(() => el.remove(), 400);
            }, 4000);
        });
    </script>

    @stack('scripts')
</body>

</html>