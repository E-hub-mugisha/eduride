<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Dashboard · EDURIDE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy:     #050B18;
            --navy-2:   #0D1628;
            --navy-3:   #121E35;
            --navy-4:   #162038;
            --teal:     #00E5C3;
            --teal-dim: #00B89A;
            --gold:     #FFB547;
            --white:    #F0F4FF;
            --muted:    #7A8BAA;
            --danger:   #FB7185;
            --success:  #34D399;
            --info:     #63B3ED;
            --fd: 'Syne', sans-serif;
            --fb: 'DM Sans', sans-serif;
        }
        html, body { height: 100%; }
        body {
            background: var(--navy);
            color: var(--white);
            font-family: var(--fb);
            min-height: 100vh;
        }

        /* ══════════════════════════════════════
           HEADER
        ══════════════════════════════════════ */
        .header {
            position: sticky; top: 0; z-index: 200;
            background: rgba(13,22,40,.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .header-top {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px; height: 60px;
        }
        .brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            display: flex; align-items: center; justify-content: center;
            color: var(--navy); font-size: 1rem;
        }
        .brand-name {
            font-family: var(--fd); font-size: 1.1rem; font-weight: 800;
            color: var(--white); letter-spacing: -.02em;
        }
        .brand-name span { color: var(--teal); }

        .header-right { display: flex; align-items: center; gap: 10px; }
        .driver-pill {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 100px; padding: 5px 14px 5px 5px;
        }
        .driver-pill img {
            width: 28px; height: 28px; border-radius: 50%; object-fit: cover;
        }
        .driver-pill-name { font-size: .8rem; font-weight: 600; color: var(--white); }
        .status-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--success); flex-shrink: 0;
        }
        .status-dot.busy { background: var(--gold); }
        .logout-btn {
            width: 34px; height: 34px; border-radius: 9px;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); cursor: pointer; font-size: .9rem;
            transition: all .2s;
        }
        .logout-btn:hover { color: var(--danger); background: rgba(251,113,133,.1); }

        /* ── Nav tabs ── */
        .nav-tabs {
            display: flex; align-items: center;
            padding: 0 20px; gap: 4px;
            border-top: 1px solid rgba(255,255,255,.05);
            overflow-x: auto; scrollbar-width: none;
        }
        .nav-tabs::-webkit-scrollbar { display: none; }
        .nav-tab {
            display: flex; align-items: center; gap: 7px;
            padding: 10px 16px;
            font-family: var(--fd); font-size: .8rem; font-weight: 700;
            color: var(--muted); text-decoration: none; white-space: nowrap;
            border-bottom: 2px solid transparent;
            transition: all .2s; position: relative;
        }
        .nav-tab i { font-size: .95rem; }
        .nav-tab:hover { color: var(--white); }
        .nav-tab.active {
            color: var(--teal);
            border-bottom-color: var(--teal);
        }
        .nav-tab .tab-badge {
            background: var(--danger); color: #fff;
            font-size: .6rem; font-weight: 700;
            padding: 2px 6px; border-radius: 20px;
            min-width: 18px; text-align: center;
        }

        /* ══════════════════════════════════════
           PAGE SECTIONS (tab panels)
        ══════════════════════════════════════ */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .page { max-width: 900px; margin: 0 auto; padding: 24px 20px 40px; }

        /* ══════════════════════════════════════
           HERO GREETING
        ══════════════════════════════════════ */
        .greeting-row {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
        }
        .greeting-title {
            font-family: var(--fd); font-size: 1.4rem; font-weight: 800;
            letter-spacing: -.02em; color: var(--white); margin-bottom: 4px;
        }
        .greeting-sub { font-size: .85rem; color: var(--muted); }
        .greeting-date {
            font-size: .78rem; color: var(--muted);
            background: var(--navy-3); border: 1px solid rgba(255,255,255,.07);
            border-radius: 8px; padding: 6px 12px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ══════════════════════════════════════
           ACTIVE TRIP BANNER
        ══════════════════════════════════════ */
        .active-banner {
            background: linear-gradient(135deg, rgba(0,229,195,.1), rgba(0,229,195,.04));
            border: 1px solid rgba(0,229,195,.25);
            border-radius: 18px; padding: 18px 20px;
            display: flex; align-items: center; gap: 16px;
            margin-bottom: 24px; position: relative; overflow: hidden;
        }
        .active-banner::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, var(--teal), transparent);
        }
        .active-banner-icon {
            width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
            background: rgba(0,229,195,.15); border: 1px solid rgba(0,229,195,.3);
            display: flex; align-items: center; justify-content: center;
            color: var(--teal); font-size: 1.2rem;
        }
        .active-banner-body { flex: 1; min-width: 0; }
        .active-banner-title {
            font-family: var(--fd); font-size: .95rem; font-weight: 700;
            color: var(--white); display: flex; align-items: center; gap: 8px;
            margin-bottom: 3px;
        }
        .active-banner-sub { font-size: .78rem; color: var(--muted); }
        .live-dot {
            display: inline-block; width: 8px; height: 8px; border-radius: 50%;
            background: var(--success); animation: blink 1.4s ease-in-out infinite;
        }
        @@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.2;} }

        /* ══════════════════════════════════════
           STAT CARDS GRID
        ══════════════════════════════════════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px; margin-bottom: 24px;
        }
        .stat-card {
            background: var(--navy-2);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 16px; padding: 18px 16px;
            transition: border-color .25s, transform .25s;
            position: relative; overflow: hidden;
        }
        .stat-card:hover { border-color: rgba(0,229,195,.2); transform: translateY(-2px); }
        .stat-card-icon {
            width: 40px; height: 40px; border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem; margin-bottom: 14px;
        }
        .stat-card-icon.teal   { background: rgba(0,229,195,.12); color: var(--teal); }
        .stat-card-icon.gold   { background: rgba(255,181,71,.12); color: var(--gold); }
        .stat-card-icon.blue   { background: rgba(99,179,237,.12); color: var(--info); }
        .stat-card-icon.green  { background: rgba(52,211,153,.12); color: var(--success); }
        .stat-card-val {
            font-family: var(--fd); font-size: 1.7rem; font-weight: 800;
            color: var(--white); line-height: 1; margin-bottom: 4px;
        }
        .stat-card-lbl { font-size: .75rem; color: var(--muted); }

        /* ══════════════════════════════════════
           MAIN GRID LAYOUT
        ══════════════════════════════════════ */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px; margin-bottom: 20px;
        }
        .card {
            background: var(--navy-2);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 18px; overflow: hidden;
        }
        .card.full { grid-column: 1 / -1; }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .card-title {
            font-family: var(--fd); font-size: .88rem; font-weight: 700;
            color: var(--white); display: flex; align-items: center; gap: 8px;
        }
        .card-title i { color: var(--teal); font-size: .9rem; }
        .card-body { padding: 18px 20px; }

        /* ══════════════════════════════════════
           CHART
        ══════════════════════════════════════ */
        #tripsChart { width: 100% !important; height: 180px !important; }

        /* ══════════════════════════════════════
           SCHEDULE TRIP CARDS
        ══════════════════════════════════════ */
        .trip-item {
            background: var(--navy-3);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 14px; padding: 14px 16px;
            margin-bottom: 10px; transition: border-color .25s;
        }
        .trip-item:last-child { margin-bottom: 0; }
        .trip-item:hover { border-color: rgba(0,229,195,.15); }
        .trip-item.active { border-color: rgba(0,229,195,.3); background: rgba(0,229,195,.04); }
        .trip-item-top {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 10px; margin-bottom: 10px;
        }
        .trip-item-route {
            font-family: var(--fd); font-size: .9rem; font-weight: 700;
            color: var(--white); margin-bottom: 3px;
        }
        .trip-item-meta {
            font-size: .75rem; color: var(--muted);
            display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        }
        .trip-item-actions { display: flex; gap: 8px; margin-top: 10px; }

        /* ══════════════════════════════════════
           STOPS MINI LIST
        ══════════════════════════════════════ */
        .stops-mini { display: flex; align-items: center; gap: 0; flex-wrap: wrap; }
        .stop-mini-item {
            display: flex; align-items: center; gap: 4px;
            font-size: .72rem; color: var(--muted);
        }
        .stop-mini-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: rgba(0,229,195,.4); flex-shrink: 0;
        }
        .stop-mini-dot.last { background: var(--teal); }
        .stop-mini-arrow { color: rgba(122,139,170,.4); font-size: .65rem; margin: 0 3px; }

        /* ══════════════════════════════════════
           MY TRIPS HISTORY
        ══════════════════════════════════════ */
        .history-row {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255,255,255,.04);
            transition: background .15s;
        }
        .history-row:last-child { border-bottom: none; }
        .history-row:hover { background: rgba(255,255,255,.02); }
        .history-date {
            min-width: 40px; text-align: center; flex-shrink: 0;
        }
        .history-date-day {
            font-family: var(--fd); font-size: 1.1rem; font-weight: 800;
            color: var(--white); line-height: 1;
        }
        .history-date-mon {
            font-size: .62rem; color: var(--muted);
            text-transform: uppercase; letter-spacing: .06em;
        }
        .history-divider {
            width: 1px; height: 38px; flex-shrink: 0;
            background: rgba(255,255,255,.06);
        }
        .history-info { flex: 1; min-width: 0; }
        .history-route {
            font-size: .87rem; font-weight: 600; color: var(--white);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .history-meta { font-size: .72rem; color: var(--muted); margin-top: 2px; }
        .history-right { text-align: right; flex-shrink: 0; }
        .history-time { font-size: .7rem; color: var(--muted); margin-top: 4px; }

        /* ══════════════════════════════════════
           LIVE MAP PANEL
        ══════════════════════════════════════ */
        #driverMap {
            width: 100%; height: 420px;
            border-radius: 16px; overflow: hidden;
            border: 1px solid rgba(255,255,255,.07);
        }
        .leaflet-tile-pane { filter: brightness(.82) saturate(.65); }
        .map-empty {
            height: 420px; display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 12px;
        }
        .map-empty i { font-size: 2.5rem; color: rgba(122,139,170,.2); }
        .map-empty p { color: var(--muted); font-size: .88rem; }

        /* ══════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════ */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            font-family: var(--fd); font-size: .82rem; font-weight: 700;
            padding: 9px 16px; border-radius: 10px; border: none;
            cursor: pointer; text-decoration: none; transition: all .25s;
            white-space: nowrap;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            color: var(--navy); box-shadow: 0 3px 12px rgba(0,229,195,.2); flex: 1;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,229,195,.35); color: var(--navy); }
        .btn-primary:disabled { opacity: .5; cursor: not-allowed; transform: none; }
        .btn-danger { background: rgba(251,113,133,.1); border: 1px solid rgba(251,113,133,.25); color: var(--danger); flex: 1; }
        .btn-danger:hover { background: rgba(251,113,133,.2); }
        .btn-secondary { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); color: var(--muted); }
        .btn-secondary:hover { color: var(--white); border-color: rgba(255,255,255,.2); }
        .btn-sm { padding: 7px 13px; font-size: .77rem; border-radius: 8px; }
        .btn-full { width: 100%; justify-content: center; }

        /* ══════════════════════════════════════
           BADGES
        ══════════════════════════════════════ */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: .65rem; font-weight: 700; letter-spacing: .04em;
            text-transform: uppercase; padding: 3px 9px; border-radius: 20px;
        }
        .badge-success  { background: rgba(52,211,153,.1);  color: var(--success); border: 1px solid rgba(52,211,153,.2); }
        .badge-danger   { background: rgba(251,113,133,.1); color: var(--danger);  border: 1px solid rgba(251,113,133,.2); }
        .badge-info     { background: rgba(99,179,237,.1);  color: var(--info);    border: 1px solid rgba(99,179,237,.2); }
        .badge-warning  { background: rgba(251,191,36,.1);  color: #FBBF24;        border: 1px solid rgba(251,191,36,.2); }
        .badge-secondary{ background: rgba(255,255,255,.07);color: var(--muted);   border: 1px solid rgba(255,255,255,.1); }
        .badge-teal     { background: rgba(0,229,195,.1);   color: var(--teal);    border: 1px solid rgba(0,229,195,.2); }

        /* ── Toast ── */
        .toast {
            position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
            background: var(--navy-2); border: 1px solid rgba(0,229,195,.2);
            color: var(--white); padding: 12px 22px; border-radius: 12px;
            font-size: .85rem; font-weight: 500; z-index: 999;
            box-shadow: 0 8px 32px rgba(0,0,0,.4);
            opacity: 0; transition: opacity .3s; pointer-events: none; white-space: nowrap;
        }
        .toast.show { opacity: 1; }

        /* ── Empty state ── */
        .empty { text-align: center; padding: 36px 20px; }
        .empty i { font-size: 1.8rem; color: rgba(122,139,170,.25); display: block; margin-bottom: 10px; }
        .empty p { color: var(--muted); font-size: .84rem; }

        /* ── Responsive ── */
        @@media (max-width: 680px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .main-grid  { grid-template-columns: 1fr; }
            .card.full  { grid-column: 1; }
        }
        @@media (max-width: 400px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════
     HEADER
══════════════════════════════════════ --}}
<header class="header">
    <div class="header-top">
        <a href="{{ route('driver.dashboard') }}" class="brand">
            <div class="brand-icon"><i class="bi bi-bus-front-fill"></i></div>
            <span class="brand-name">EDU<span>RIDE</span></span>
        </a>
        <div class="header-right">
            <div class="driver-pill">
                <span class="status-dot {{ $driver->status === 'on_trip' ? 'busy' : '' }}"></span>
                <img src="{{ auth()->user()->avatar_url }}" alt="">
                <span class="driver-pill-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn" title="Sign out">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

    {{-- Nav tabs --}}
    <nav class="nav-tabs">
        <a href="#" class="nav-tab active" data-tab="dashboard">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="#" class="nav-tab" data-tab="mytrips">
            <i class="bi bi-clock-history"></i> My Trips
            @if($myTrips->isNotEmpty())
                <span class="tab-badge">{{ $myTrips->count() }}</span>
            @endif
        </a>
        <a href="#" class="nav-tab" data-tab="livemap">
            <i class="bi bi-map-fill"></i> Live Map
            @if($activeTrip)
                <span class="live-dot" style="margin-left:2px;"></span>
            @endif
        </a>
    </nav>
</header>

{{-- ══════════════════════════════════════
     TAB: DASHBOARD
══════════════════════════════════════ --}}
<div class="tab-panel active" id="tab-dashboard">
<div class="page">

    {{-- Greeting --}}
    <div class="greeting-row">
        <div>
            <div class="greeting-title">
                {{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }},
                {{ explode(' ', auth()->user()->name)[0] }} 👋
            </div>
            <div class="greeting-sub">
                @if($activeTrip)
                    You have an active trip in progress.
                @elseif($todayTrips->isNotEmpty())
                    You have {{ $todayTrips->count() }} trip(s) scheduled today.
                @else
                    No trips scheduled for today.
                @endif
            </div>
        </div>
        <div class="greeting-date">
            <i class="bi bi-calendar3" style="color:var(--teal);"></i>
            {{ now()->format('D, d M Y') }}
        </div>
    </div>

    {{-- Active trip banner --}}
    @if($activeTrip)
    <div class="active-banner">
        <div class="active-banner-icon"><i class="bi bi-geo-alt-fill"></i></div>
        <div class="active-banner-body">
            <div class="active-banner-title">
                <span class="live-dot"></span> Trip in progress
            </div>
            <div class="active-banner-sub">
                {{ $activeTrip->route->name }} · {{ $activeTrip->vehicle->plate_number }}
            </div>
        </div>
        <a href="{{ route('driver.trip.show', $activeTrip) }}" class="btn btn-primary" style="flex:0;padding:10px 18px;">
            <i class="bi bi-geo-alt-fill"></i> Open
        </a>
    </div>
    @endif

    {{-- Stats grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon teal"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-card-val">{{ $totalTrips }}</div>
            <div class="stat-card-lbl">Total Trips</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon gold"><i class="bi bi-calendar-month"></i></div>
            <div class="stat-card-val">{{ $tripsThisMonth }}</div>
            <div class="stat-card-lbl">This Month</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="bi bi-calendar-week"></i></div>
            <div class="stat-card-val">{{ $tripsThisWeek }}</div>
            <div class="stat-card-lbl">This Week</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon green"><i class="bi bi-calendar3"></i></div>
            <div class="stat-card-val">{{ $todayTrips->count() }}</div>
            <div class="stat-card-lbl">Today</div>
        </div>
    </div>

    {{-- Main grid --}}
    <div class="main-grid">

        {{-- Chart --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-bar-chart-fill"></i> Last 7 Days</div>
                <span style="font-family:var(--fd);font-size:1rem;font-weight:800;color:var(--teal);">
                    {{ array_sum($chartData) }} trips
                </span>
            </div>
            <div class="card-body">
                <canvas id="tripsChart"></canvas>
            </div>
        </div>

        {{-- Today's schedule --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-calendar3"></i> Today's Schedule</div>
                <span style="font-size:.75rem;color:var(--muted);">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="card-body" style="padding:12px 16px;">
                @if($todayTrips->isEmpty())
                    <div class="empty" style="padding:20px 0;">
                        <i class="bi bi-calendar-x"></i>
                        <p>No trips today.</p>
                    </div>
                @else
                    @foreach($todayTrips as $trip)
                    <div class="trip-item {{ $trip->status === 'in_progress' ? 'active' : '' }}">
                        <div class="trip-item-top">
                            <div>
                                <div class="trip-item-route">{{ $trip->route->name }}</div>
                                <div class="trip-item-meta">
                                    <span><i class="bi bi-clock"></i> {{ $trip->scheduled_at?->format('H:i') ?? '—' }}</span>
                                    <span><i class="bi bi-bus-front"></i> {{ $trip->vehicle->plate_number }}</span>
                                </div>
                            </div>
                            @php $badge = $trip->status_badge; @endphp
                            <span class="badge badge-{{ $badge['color'] }}">
                                @if($trip->status === 'in_progress')
                                    <span class="live-dot" style="width:6px;height:6px;"></span>
                                @endif
                                {{ $badge['label'] }}
                            </span>
                        </div>

                        {{-- Stops mini --}}
                        <div class="stops-mini">
                            @foreach($trip->route->stops as $stop)
                                <div class="stop-mini-item">
                                    <div class="stop-mini-dot {{ $loop->last ? 'last' : '' }}"></div>
                                    <span>{{ Str::limit($stop->name, 14) }}</span>
                                </div>
                                @if(!$loop->last)
                                    <i class="bi bi-chevron-right stop-mini-arrow"></i>
                                @endif
                            @endforeach
                        </div>

                        <div class="trip-item-actions">
                            @if($trip->isScheduled())
                                <button class="btn btn-primary btn-sm" onclick="startTrip({{ $trip->id }}, this)">
                                    <i class="bi bi-play-fill"></i> Start
                                </button>
                            @elseif($trip->isInProgress())
                                <a href="{{ route('driver.trip.show', $trip) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-geo-alt-fill"></i> Live View
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="endTrip({{ $trip->id }}, this)">
                                    <i class="bi bi-stop-fill"></i> End
                                </button>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled style="flex:1;opacity:.5;cursor:not-allowed;">
                                    <i class="bi bi-check-circle"></i> {{ $badge['label'] }}
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Vehicle info --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-truck-front-fill"></i> My Vehicle</div>
                @if($driver->vehicle)
                    <span class="badge badge-success">Assigned</span>
                @else
                    <span class="badge badge-secondary">Unassigned</span>
                @endif
            </div>
            <div class="card-body">
                @if($driver->vehicle)
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                        @foreach([
                            ['Plate',     $driver->vehicle->plate_number],
                            ['Model',     $driver->vehicle->brand . ' ' . $driver->vehicle->model],
                            ['Capacity',  $driver->vehicle->capacity . ' seats'],
                            ['Color',     $driver->vehicle->color ?? '—'],
                        ] as [$lbl, $val])
                        <div style="background:var(--navy-3);border-radius:10px;padding:10px 12px;">
                            <div style="font-size:.68rem;color:var(--muted);margin-bottom:3px;text-transform:uppercase;letter-spacing:.06em;">{{ $lbl }}</div>
                            <div style="font-size:.88rem;font-weight:600;color:var(--white);">{{ $val }}</div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty" style="padding:16px 0;">
                        <i class="bi bi-truck-front"></i>
                        <p>No vehicle assigned yet.<br>Contact your administrator.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Driver profile --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-person-badge-fill"></i> My Profile</div>
                <span class="badge badge-{{ $driver->status_badge['color'] }}">{{ $driver->status_badge['label'] }}</span>
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <img src="{{ auth()->user()->avatar_url }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(0,229,195,.25);" alt="">
                    <div>
                        <div style="font-family:var(--fd);font-size:.95rem;font-weight:700;color:var(--white);">{{ auth()->user()->name }}</div>
                        <div style="font-size:.75rem;color:var(--muted);">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                @foreach([
                    ['Phone',   auth()->user()->phone ?? '—'],
                    ['License', $driver->license_number],
                    ['Expiry',  $driver->license_expiry?->format('d M Y') ?? '—'],
                ] as [$lbl, $val])
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.04);">
                    <span style="font-size:.78rem;color:var(--muted);">{{ $lbl }}</span>
                    <span style="font-size:.82rem;font-weight:600;color:var(--white);">{{ $val }}</span>
                </div>
                @endforeach
                @if($driver->is_license_expired)
                    <div style="margin-top:12px;padding:9px 12px;background:rgba(251,113,133,.08);border:1px solid rgba(251,113,133,.2);border-radius:9px;font-size:.78rem;color:var(--danger);display:flex;align-items:center;gap:7px;">
                        <i class="bi bi-exclamation-triangle-fill"></i> License has expired
                    </div>
                @elseif($driver->is_license_expiring_soon)
                    <div style="margin-top:12px;padding:9px 12px;background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);border-radius:9px;font-size:.78rem;color:#FBBF24;display:flex;align-items:center;gap:7px;">
                        <i class="bi bi-exclamation-triangle-fill"></i> License expiring soon
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
</div>

{{-- ══════════════════════════════════════
     TAB: MY TRIPS
══════════════════════════════════════ --}}
<div class="tab-panel" id="tab-mytrips">
<div class="page">

    <div class="greeting-row">
        <div>
            <div class="greeting-title">My Trips</div>
            <div class="greeting-sub">Your complete trip history — last 30 trips.</div>
        </div>
        <span style="font-family:var(--fd);font-size:1.3rem;font-weight:800;color:var(--teal);">
            {{ $totalTrips }} total
        </span>
    </div>

    {{-- Stats row --}}
    <div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px;">
        <div class="stat-card">
            <div class="stat-card-icon teal"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-card-val">{{ $totalTrips }}</div>
            <div class="stat-card-lbl">All Time</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon gold"><i class="bi bi-calendar-month"></i></div>
            <div class="stat-card-val">{{ $tripsThisMonth }}</div>
            <div class="stat-card-lbl">This Month</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="bi bi-calendar-week"></i></div>
            <div class="stat-card-val">{{ $tripsThisWeek }}</div>
            <div class="stat-card-lbl">This Week</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clock-history"></i> Trip History</div>
            <span style="font-size:.75rem;color:var(--muted);">{{ $myTrips->count() }} records</span>
        </div>
        @if($myTrips->isEmpty())
            <div class="empty">
                <i class="bi bi-inbox"></i>
                <p>No completed trips yet.</p>
            </div>
        @else
            @foreach($myTrips as $trip)
            <div class="history-row">
                <div class="history-date">
                    <div class="history-date-day">{{ $trip->scheduled_at?->format('d') ?? '—' }}</div>
                    <div class="history-date-mon">{{ $trip->scheduled_at?->format('M') ?? '' }}</div>
                </div>
                <div class="history-divider"></div>
                <div class="history-info">
                    <div class="history-route">{{ $trip->route->name }}</div>
                    <div class="history-meta">
                        {{ $trip->vehicle->plate_number }} ·
                        {{ ucfirst($trip->type) }}
                        @if($trip->duration) · {{ $trip->duration }} @endif
                    </div>
                </div>
                <div class="history-right">
                    <span class="badge badge-{{ $trip->status_badge['color'] }}">{{ $trip->status_badge['label'] }}</span>
                    <div class="history-time">
                        {{ $trip->started_at?->format('H:i') ?? '—' }}
                        @if($trip->ended_at) → {{ $trip->ended_at->format('H:i') }} @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
</div>

{{-- ══════════════════════════════════════
     TAB: LIVE MAP
══════════════════════════════════════ --}}
<div class="tab-panel" id="tab-livemap">
<div class="page">

    <div class="greeting-row">
        <div>
            <div class="greeting-title">Live Map</div>
            <div class="greeting-sub">
                @if($activeTrip)
                    Your current trip is shown on the map below.
                @else
                    No active trip. Start a trip to see your location here.
                @endif
            </div>
        </div>
        @if($activeTrip)
            <a href="{{ route('driver.trip.show', $activeTrip) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-fullscreen"></i> Full Screen
            </a>
        @endif
    </div>

    @if($activeTrip)
    {{-- Trip info banner on map tab --}}
    <div style="background:var(--navy-2);border:1px solid rgba(255,255,255,.07);border-radius:14px;padding:14px 16px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;border-radius:50%;background:rgba(0,229,195,.1);border:1px solid rgba(0,229,195,.2);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.85rem;flex-shrink:0;">
            <i class="bi bi-bus-front-fill"></i>
        </div>
        <div style="flex:1;">
            <div style="font-family:var(--fd);font-size:.88rem;font-weight:700;color:var(--white);display:flex;align-items:center;gap:6px;">
                <span class="live-dot"></span> {{ $activeTrip->route->name }}
            </div>
            <div style="font-size:.72rem;color:var(--muted);">
                {{ $activeTrip->vehicle->plate_number }} · Started {{ $activeTrip->started_at?->format('H:i') ?? '—' }}
            </div>
        </div>
        <span class="badge badge-teal">In Progress</span>
    </div>

    <div id="driverMap"></div>

    @else
    <div style="background:var(--navy-2);border:1px solid rgba(255,255,255,.07);border-radius:16px;">
        <div class="map-empty">
            <i class="bi bi-geo-alt"></i>
            <p>Start a trip from the Dashboard tab<br>to share your live location.</p>
        </div>
    </div>
    @endif

</div>
</div>

<div class="toast" id="toast"></div>

@php
    $chartLabelsJson = json_encode($chartLabels);
    $chartDataJson   = json_encode($chartData);
    $hasActiveTrip   = $activeTrip ? 'true' : 'false';
    $activeTripId    = $activeTrip ? $activeTrip->id : 0;
    $activeLat       = $activeTrip?->current_latitude  ? (float) $activeTrip->current_latitude  : 0;
    $activeLng       = $activeTrip?->current_longitude ? (float) $activeTrip->current_longitude : 0;
    $activeStops     = [];
    if ($activeTrip) {
        foreach ($activeTrip->route->stops as $s) {
            $activeStops[] = ['name' => $s->name, 'lat' => (float)$s->latitude, 'lng' => (float)$s->longitude, 'order' => $s->order];
        }
    }
    $activeStopsJson = json_encode($activeStops);
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var CSRF = document.querySelector('meta[name=csrf-token]').content;

// ── Tab switching ─────────────────────────────────────────────────────────────
var tabs    = document.querySelectorAll('.nav-tab');
var panels  = document.querySelectorAll('.tab-panel');
var mapInit = false;

tabs.forEach(function(tab) {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        var target = this.dataset.tab;

        tabs.forEach(function(t)   { t.classList.remove('active'); });
        panels.forEach(function(p) { p.classList.remove('active'); });

        this.classList.add('active');
        document.getElementById('tab-' + target).classList.add('active');

        // Init map only when Live Map tab first opens
        if (target === 'livemap' && !mapInit) {
            mapInit = true;
            initDriverMap();
        }
    });
});

// ── Chart ─────────────────────────────────────────────────────────────────────
var ctx = document.getElementById('tripsChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! $chartLabelsJson !!},
        datasets: [{
            label:           'Trips',
            data:            {!! $chartDataJson !!},
            backgroundColor: 'rgba(0,229,195,.2)',
            borderColor:     '#00E5C3',
            borderWidth:     2,
            borderRadius:    8,
            borderSkipped:   false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0D1628',
                borderColor:     'rgba(0,229,195,.2)',
                borderWidth:     1,
                titleColor:      '#F0F4FF',
                bodyColor:       '#7A8BAA',
                callbacks: {
                    label: function(c) { return '  ' + c.parsed.y + ' trip' + (c.parsed.y !== 1 ? 's' : ''); }
                }
            }
        },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,.04)' }, ticks: { color: '#7A8BAA', font: { size: 11 } } },
            y: {
                beginAtZero: true,
                ticks: { color: '#7A8BAA', font: { size: 11 }, stepSize: 1, precision: 0 },
                grid: { color: 'rgba(255,255,255,.04)' }
            }
        }
    }
});

// ── Live Map ──────────────────────────────────────────────────────────────────
var HAS_ACTIVE = {!! $hasActiveTrip !!};
var ACTIVE_ID  = {{ $activeTripId }};
var INIT_LAT   = {{ $activeLat }};
var INIT_LNG   = {{ $activeLng }};
var STOPS_DATA = {!! $activeStopsJson !!};
var liveMap, busMarker;

function initDriverMap() {
    if (!HAS_ACTIVE) return;

    var center = (INIT_LAT && INIT_LNG) ? [INIT_LAT, INIT_LNG] : [-1.9706, 30.1050];
    liveMap = L.map('driverMap', { zoomControl: true, attributionControl: false })
               .setView(center, 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(liveMap);

    // Stop markers
    STOPS_DATA.forEach(function(s, i) {
        var isLast = (i === STOPS_DATA.length - 1);
        var icon = L.divIcon({
            className: '',
            html: '<div style="width:24px;height:24px;border-radius:50%;background:' + (isLast ? '#00E5C3' : '#0D1628') + ';border:2px solid ' + (isLast ? '#00E5C3' : 'rgba(0,229,195,.4)') + ';display:flex;align-items:center;justify-content:center;color:' + (isLast ? '#050B18' : '#00E5C3') + ';font-size:.6rem;font-weight:700;">' + (isLast ? '🏫' : s.order) + '</div>',
            iconSize: [24, 24], iconAnchor: [12, 12],
        });
        L.marker([s.lat, s.lng], { icon: icon }).bindTooltip(s.name).addTo(liveMap);
    });

    // Bus marker
    if (INIT_LAT && INIT_LNG) {
        var busIcon = L.divIcon({
            className: '',
            html: '<div style="width:42px;height:42px;border-radius:50%;background:#00E5C3;display:flex;align-items:center;justify-content:center;color:#050B18;font-size:1.1rem;box-shadow:0 0 0 8px rgba(0,229,195,.15);">🚌</div>',
            iconSize: [42, 42], iconAnchor: [21, 21],
        });
        busMarker = L.marker([INIT_LAT, INIT_LNG], { icon: busIcon }).addTo(liveMap);
    }

    // Poll every 5s
    setInterval(function() {
        fetch('/admin/trips/' + ACTIVE_ID + '/position', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (!d.lat || !d.lng) return;
                if (!busMarker) {
                    var busIcon = L.divIcon({
                        className: '',
                        html: '<div style="width:42px;height:42px;border-radius:50%;background:#00E5C3;display:flex;align-items:center;justify-content:center;color:#050B18;font-size:1.1rem;box-shadow:0 0 0 8px rgba(0,229,195,.15);">🚌</div>',
                        iconSize: [42, 42], iconAnchor: [21, 21],
                    });
                    busMarker = L.marker([d.lat, d.lng], { icon: busIcon }).addTo(liveMap);
                } else {
                    busMarker.setLatLng([d.lat, d.lng]);
                }
            }).catch(function() {});
    }, 5000);
}

// ── Trip controls ─────────────────────────────────────────────────────────────
function toast(msg, ok) {
    var el = document.getElementById('toast');
    el.textContent = msg;
    el.style.borderColor = (ok !== false) ? 'rgba(52,211,153,.3)' : 'rgba(251,113,133,.3)';
    el.classList.add('show');
    setTimeout(function() { el.classList.remove('show'); }, 3000);
}

async function startTrip(id, btn) {
    if (!confirm('Start this trip? Location sharing will begin.')) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass"></i>';
    var res  = await fetch('/driver/trip/' + id + '/start', {
        method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    var data = await res.json();
    if (data.ok) {
        toast('Trip started!');
        setTimeout(function() { location.href = '/driver/trip/' + id; }, 800);
    } else {
        toast(data.error || 'Failed.', false);
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-play-fill"></i> Start';
    }
}

async function endTrip(id, btn) {
    if (!confirm('End this trip?')) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass"></i>';
    var res  = await fetch('/driver/trip/' + id + '/end', {
        method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    var data = await res.json();
    if (data.ok) {
        toast('Trip ended.');
        setTimeout(function() { location.reload(); }, 800);
    } else {
        toast(data.error || 'Failed.', false);
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-stop-fill"></i> End';
    }
}
</script>
</body>
</html>