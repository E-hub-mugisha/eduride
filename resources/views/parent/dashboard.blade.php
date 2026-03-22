<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Parent Dashboard · EDURIDE</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy:     #050B18; --navy-2: #0D1628; --navy-3: #121E35;
            --teal:     #00E5C3; --teal-dim: #00B89A; --gold: #FFB547;
            --white:    #F0F4FF; --muted: #7A8BAA; --danger: #FB7185;
            --success:  #34D399; --warning: #FBBF24; --info: #63B3ED;
            --fd: 'Syne', sans-serif; --fb: 'DM Sans', sans-serif;
        }
        html, body { height: 100%; }
        body { background: var(--navy); color: var(--white); font-family: var(--fb); min-height: 100vh; }

        /* ══ HEADER ══════════════════════════════════════ */
        .header {
            position: sticky; top: 0; z-index: 200;
            background: rgba(13,22,40,.96);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .header-top {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 20px; height: 60px;
        }
        .brand { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .brand-icon { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, var(--teal), var(--teal-dim)); display: flex; align-items: center; justify-content: center; color: var(--navy); font-size: 1rem; }
        .brand-name { font-family: var(--fd); font-size: 1.1rem; font-weight: 800; color: var(--white); }
        .brand-name span { color: var(--teal); }
        .header-right { display: flex; align-items: center; gap: 10px; }
        .notif-btn {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; justify-content: center;
            color: var(--muted); text-decoration: none; position: relative; transition: all .2s;
        }
        .notif-btn:hover { color: var(--white); }
        .notif-pip { position: absolute; top: 5px; right: 5px; width: 8px; height: 8px; border-radius: 50%; background: var(--danger); border: 2px solid var(--navy-2); }
        .parent-pill { display: flex; align-items: center; gap: 7px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.07); border-radius: 100px; padding: 5px 12px 5px 5px; }
        .parent-pill img { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; }
        .parent-pill-name { font-size: .78rem; font-weight: 600; color: var(--white); }
        .logout-btn { background: none; border: none; color: var(--muted); cursor: pointer; font-size: .9rem; padding: 6px; transition: color .2s; }
        .logout-btn:hover { color: var(--danger); }

        /* Nav tabs */
        .nav-tabs { display: flex; align-items: center; padding: 0 20px; gap: 4px; border-top: 1px solid rgba(255,255,255,.05); overflow-x: auto; scrollbar-width: none; }
        .nav-tabs::-webkit-scrollbar { display: none; }
        .nav-tab { display: flex; align-items: center; gap: 7px; padding: 10px 16px; font-family: var(--fd); font-size: .8rem; font-weight: 700; color: var(--muted); text-decoration: none; white-space: nowrap; border-bottom: 2px solid transparent; transition: all .2s; cursor: pointer; background: none; border-left: none; border-right: none; border-top: none; }
        .nav-tab i { font-size: .95rem; }
        .nav-tab:hover { color: var(--white); }
        .nav-tab.active { color: var(--teal); border-bottom-color: var(--teal); }
        .live-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: var(--success); animation: blink 1.4s ease-in-out infinite; }
        @@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.2;} }

        /* ══ TAB PANELS ══════════════════════════════════ */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }
        .page { max-width: 680px; margin: 0 auto; padding: 24px 16px 60px; }

        /* ══ CARDS ═══════════════════════════════════════ */
        .card { background: var(--navy-2); border: 1px solid rgba(255,255,255,.07); border-radius: 18px; overflow: hidden; margin-bottom: 16px; }
        .card-header { padding: 15px 18px; border-bottom: 1px solid rgba(255,255,255,.05); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .card-title { font-family: var(--fd); font-size: .88rem; font-weight: 700; color: var(--white); display: flex; align-items: center; gap: 7px; }
        .card-title i { color: var(--teal); }
        .card-body { padding: 16px 18px; }

        /* ══ BADGES ══════════════════════════════════════ */
        .badge { display: inline-flex; align-items: center; gap: 4px; font-size: .65rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; padding: 3px 9px; border-radius: 20px; }
        .badge-success   { background: rgba(52,211,153,.1);  color: var(--success); border: 1px solid rgba(52,211,153,.2); }
        .badge-danger    { background: rgba(251,113,133,.1); color: var(--danger);  border: 1px solid rgba(251,113,133,.2); }
        .badge-teal      { background: rgba(0,229,195,.1);   color: var(--teal);    border: 1px solid rgba(0,229,195,.2); }
        .badge-secondary { background: rgba(255,255,255,.07);color: var(--muted);   border: 1px solid rgba(255,255,255,.1); }
        .badge-warning   { background: rgba(251,191,36,.1);  color: var(--warning); border: 1px solid rgba(251,191,36,.2); }
        .badge-info      { background: rgba(99,179,237,.1);  color: var(--info);    border: 1px solid rgba(99,179,237,.2); }

        /* ══ BUTTONS ═════════════════════════════════════ */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-family: var(--fd); font-size: .82rem; font-weight: 700; padding: 10px 18px; border-radius: 10px; border: none; cursor: pointer; text-decoration: none; transition: all .25s; white-space: nowrap; }
        .btn-primary { background: linear-gradient(135deg, var(--teal), var(--teal-dim)); color: var(--navy); box-shadow: 0 3px 12px rgba(0,229,195,.2); }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,229,195,.35); color: var(--navy); }
        .btn-secondary { background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); color: var(--muted); }
        .btn-secondary:hover { color: var(--white); }
        .btn-full { width: 100%; }

        /* ══ STAT MINI CARDS ═════════════════════════════ */
        .stats-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 20px; }
        .stat-mini { background: var(--navy-3); border: 1px solid rgba(255,255,255,.06); border-radius: 12px; padding: 14px 12px; text-align: center; }
        .stat-mini-val { font-family: var(--fd); font-size: 1.4rem; font-weight: 800; line-height: 1; }
        .stat-mini-val.teal { color: var(--teal); }
        .stat-mini-val.gold { color: var(--gold); }
        .stat-mini-lbl { font-size: .68rem; color: var(--muted); margin-top: 5px; }

        /* ══ STUDENT CARD ════════════════════════════════ */
        .student-card { background: var(--navy-2); border: 1px solid rgba(255,255,255,.07); border-radius: 18px; overflow: hidden; margin-bottom: 16px; transition: border-color .25s; }
        .student-card.live { border-color: rgba(0,229,195,.25); }
        .student-card-header { padding: 16px 18px; border-bottom: 1px solid rgba(255,255,255,.05); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
        .student-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(0,229,195,.2); flex-shrink: 0; }
        .student-name { font-family: var(--fd); font-size: .95rem; font-weight: 700; color: var(--white); }
        .student-meta { font-size: .74rem; color: var(--muted); margin-top: 2px; }

        /* Bus info grid */
        .bus-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 14px; }
        .bus-stat { background: var(--navy-3); border: 1px solid rgba(255,255,255,.06); border-radius: 12px; padding: 12px; text-align: center; }
        .bus-stat-val { font-family: var(--fd); font-size: 1.1rem; font-weight: 800; color: var(--white); }
        .bus-stat-val.teal { color: var(--teal); }
        .bus-stat-val.gold  { color: var(--gold); }
        .bus-stat-lbl { font-size: .67rem; color: var(--muted); margin-top: 3px; }

        /* Driver row */
        .driver-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: var(--navy-3); border: 1px solid rgba(255,255,255,.05); border-radius: 12px; margin-bottom: 12px; }
        .driver-row img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
        .driver-row-name { font-size: .85rem; font-weight: 600; color: var(--white); }
        .driver-row-meta { font-size: .72rem; color: var(--muted); }

        /* Stop highlight */
        .stop-highlight { display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: rgba(255,181,71,.05); border: 1px solid rgba(255,181,71,.15); border-radius: 12px; margin-bottom: 12px; }
        .stop-highlight-icon { width: 32px; height: 32px; border-radius: 50%; background: rgba(255,181,71,.12); display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: .85rem; flex-shrink: 0; }
        .stop-highlight-name { font-size: .85rem; font-weight: 600; color: var(--white); }
        .stop-highlight-sub  { font-size: .72rem; color: var(--muted); }
        .eta-val { margin-left: auto; font-family: var(--fd); font-size: .9rem; font-weight: 800; color: var(--gold); }

        /* Schedule row */
        .schedule-row { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,.04); }
        .schedule-row:last-child { border-bottom: none; }
        .schedule-icon { width: 32px; height: 32px; border-radius: 9px; background: rgba(99,179,237,.08); display: flex; align-items: center; justify-content: center; color: var(--info); font-size: .8rem; flex-shrink: 0; }
        .schedule-name { font-size: .85rem; font-weight: 600; color: var(--white); }
        .schedule-meta { font-size: .72rem; color: var(--muted); }

        /* ══ NOTIFICATION ROWS ═══════════════════════════ */
        .notif-row { display: flex; align-items: flex-start; gap: 10px; padding: 12px 18px; border-bottom: 1px solid rgba(255,255,255,.04); }
        .notif-row:last-child { border-bottom: none; }
        .notif-row.unread { background: rgba(0,229,195,.025); }
        .notif-icon { width: 34px; height: 34px; border-radius: 50%; background: rgba(0,229,195,.08); display: flex; align-items: center; justify-content: center; color: var(--teal); font-size: .82rem; flex-shrink: 0; }
        .notif-title { font-size: .84rem; font-weight: 600; color: var(--white); margin-bottom: 2px; }
        .notif-title.read { color: var(--muted); font-weight: 400; }
        .notif-time  { font-size: .7rem; color: var(--muted); }
        .notif-unread-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--teal); flex-shrink: 0; margin-top: 5px; }

        /* ══ LIVE MAP ════════════════════════════════════ */
        #liveMap { width: 100%; height: 380px; border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,.07); }
        .leaflet-tile-pane { filter: brightness(.82) saturate(.65); }
        .map-empty { height: 360px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; background: var(--navy-2); border: 1px solid rgba(255,255,255,.07); border-radius: 16px; }
        .map-empty i { font-size: 2.5rem; color: rgba(122,139,170,.2); }
        .map-empty p { color: var(--muted); font-size: .85rem; text-align: center; line-height: 1.6; }

        /* ══ EMPTY ═══════════════════════════════════════ */
        .empty { text-align: center; padding: 32px 20px; }
        .empty i { font-size: 2rem; color: rgba(122,139,170,.25); display: block; margin-bottom: 10px; }
        .empty p { color: var(--muted); font-size: .84rem; }

        /* ══ BOTTOM NAV ══════════════════════════════════ */
        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; z-index: 100; background: rgba(13,22,40,.96); backdrop-filter: blur(20px); border-top: 1px solid rgba(255,255,255,.07); display: flex; padding: 10px 0 16px; }
        .bottom-tab { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; text-decoration: none; color: var(--muted); font-size: .62rem; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; transition: color .2s; cursor: pointer; background: none; border: none; }
        .bottom-tab i { font-size: 1.2rem; }
        .bottom-tab.active { color: var(--teal); }

        @@media (max-width: 420px) { .stats-row { grid-template-columns: repeat(3,1fr); } .bus-grid { grid-template-columns: repeat(3,1fr); } }
    </style>
</head>
<body>

{{-- ══ HEADER ══════════════════════════════════════ --}}
<header class="header">
    <div class="header-top">
        <a href="{{ route('parent.dashboard') }}" class="brand">
            <div class="brand-icon"><i class="bi bi-bus-front-fill"></i></div>
            <span class="brand-name">EDU<span>RIDE</span></span>
        </a>
        <div class="header-right">
            <a href="{{ route('parent.notifications') }}" class="notif-btn" title="Notifications">
                <i class="bi bi-bell"></i>
                @if($unreadCount) <span class="notif-pip"></span> @endif
            </a>
            <div class="parent-pill">
                <img src="{{ $user->avatar_url }}" alt="">
                <span class="parent-pill-name">{{ explode(' ', $user->name)[0] }}</span>
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
        <button class="nav-tab active" data-tab="dashboard">
            <i class="bi bi-grid-fill"></i> Dashboard
        </button>
        <button class="nav-tab" data-tab="livetrack">
            <i class="bi bi-geo-alt-fill"></i> Live Track
            @foreach($activeTrips as $t)
                <span class="live-dot"></span>
                @break
            @endforeach
        </button>
        <button class="nav-tab" data-tab="mychild">
            <i class="bi bi-people-fill"></i> My Child
            @if($students->count() > 1)
                <span style="background:rgba(0,229,195,.15);color:var(--teal);font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:20px;">{{ $students->count() }}</span>
            @endif
        </button>
        <button class="nav-tab" data-tab="alerts">
            <i class="bi bi-bell-fill"></i> Alerts
            @if($unreadCount)
                <span style="background:var(--danger);color:#fff;font-size:.6rem;font-weight:700;padding:2px 6px;border-radius:20px;">{{ $unreadCount }}</span>
            @endif
        </button>
    </nav>
</header>

{{-- ══ TAB: DASHBOARD ══════════════════════════════ --}}
<div class="tab-panel active" id="tab-dashboard">
<div class="page">

    {{-- Greeting --}}
    <div style="margin-bottom:20px;">
        <div style="font-family:var(--fd);font-size:1.35rem;font-weight:800;letter-spacing:-.02em;color:var(--white);margin-bottom:4px;">
            {{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }},
            {{ explode(' ', $user->name)[0] }} 👋
        </div>
        <div style="font-size:.84rem;color:var(--muted);">
            @if(count($activeTrips) > 0)
                <span style="color:var(--success);font-weight:600;">
                    <span class="live-dot"></span>
                    {{ count($activeTrips) }} bus{{ count($activeTrips) > 1 ? 'es are' : ' is' }} live right now.
                </span>
            @else
                Here's your children's transport status for today.
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-mini">
            <div class="stat-mini-val teal">{{ $students->count() }}</div>
            <div class="stat-mini-lbl">Children</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val gold">{{ count($activeTrips) }}</div>
            <div class="stat-mini-lbl">Live Buses</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val">{{ $unreadCount }}</div>
            <div class="stat-mini-lbl">Unread Alerts</div>
        </div>
    </div>

    {{-- Per-student summary cards --}}
    @forelse($students as $student)
    @php $activeTrip = $activeTrips[$student->id] ?? null; @endphp

    <div class="student-card {{ $activeTrip ? 'live' : '' }}">
        <div class="student-card-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <img src="{{ $student->photo_url }}" class="student-avatar" alt="">
                <div>
                    <div class="student-name">{{ $student->full_name }}</div>
                    <div class="student-meta">{{ $student->display_grade }} · {{ $student->route?->name ?? 'No route assigned' }}</div>
                </div>
            </div>
            @if($activeTrip)
                <span class="badge badge-teal"><span class="live-dot" style="width:6px;height:6px;"></span> Live</span>
            @else
                <span class="badge badge-secondary">No active trip</span>
            @endif
        </div>

        @if($activeTrip)
        <div style="padding:14px 18px;background:rgba(0,229,195,.02);border-bottom:1px solid rgba(0,229,195,.07);">
            <div class="bus-grid">
                <div class="bus-stat">
                    <div class="bus-stat-val teal" id="spd-{{ $student->id }}">
                        {{ $activeTrip->current_speed ? round($activeTrip->current_speed) : 0 }}
                    </div>
                    <div class="bus-stat-lbl">km/h</div>
                </div>
                <div class="bus-stat">
                    <div class="bus-stat-val">{{ $activeTrip->started_at?->format('H:i') ?? '—' }}</div>
                    <div class="bus-stat-lbl">Departed</div>
                </div>
                <div class="bus-stat">
                    <div class="bus-stat-val gold">{{ $activeTrip->delay_minutes > 0 ? '+' . $activeTrip->delay_minutes . 'm' : 'On time' }}</div>
                    <div class="bus-stat-lbl">Status</div>
                </div>
            </div>

            {{-- Driver --}}
            <div class="driver-row">
                <img src="{{ $activeTrip->driver->avatar_url }}" alt="">
                <div style="flex:1;">
                    <div class="driver-row-name">{{ $activeTrip->driver->name }}</div>
                    <div class="driver-row-meta">{{ $activeTrip->vehicle->brand }} {{ $activeTrip->vehicle->model }} · {{ $activeTrip->vehicle->plate_number }}</div>
                </div>
                @if($activeTrip->driver->phone)
                <a href="tel:{{ $activeTrip->driver->phone }}" style="width:32px;height:32px;border-radius:50%;background:rgba(0,229,195,.1);border:1px solid rgba(0,229,195,.2);display:flex;align-items:center;justify-content:center;color:var(--teal);text-decoration:none;font-size:.85rem;">
                    <i class="bi bi-telephone-fill"></i>
                </a>
                @endif
            </div>

            {{-- Stop ETA --}}
            @if($student->stop)
            <div class="stop-highlight">
                <div class="stop-highlight-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="stop-highlight-name">{{ $student->stop->name }}</div>
                    <div class="stop-highlight-sub">Your child's boarding stop</div>
                </div>
                <div class="eta-val" id="eta-{{ $student->id }}">—</div>
            </div>
            @endif

            <a href="{{ route('parent.track', $activeTrip) }}" class="btn btn-primary btn-full">
                <i class="bi bi-geo-alt-fill"></i> Track Bus Live
            </a>
        </div>

        @else
        {{-- Today schedule --}}
        <div style="padding:12px 18px;">
            @if(isset($todayTrips[$student->id]) && $todayTrips[$student->id]->isNotEmpty())
                @foreach($todayTrips[$student->id] as $trip)
                <div class="schedule-row">
                    <div class="schedule-icon">
                        <i class="bi bi-{{ $trip->type === 'morning' ? 'sunrise' : 'sunset' }}"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="schedule-name">{{ ucfirst($trip->type) }} trip</div>
                        <div class="schedule-meta">{{ $trip->scheduled_at?->format('H:i') }} · {{ $trip->driver->name }}</div>
                    </div>
                    <span class="badge badge-{{ $trip->status_badge['color'] }}">{{ $trip->status_badge['label'] }}</span>
                </div>
                @endforeach
            @else
                <div class="empty" style="padding:18px 0;">
                    <i class="bi bi-calendar-x" style="font-size:1.5rem;"></i>
                    <p>No trips scheduled today.</p>
                </div>
            @endif
        </div>
        @endif
    </div>
    @empty
    <div class="card">
        <div class="empty">
            <i class="bi bi-people" style="font-size:2rem;"></i>
            <h3 style="font-family:var(--fd);color:var(--muted);font-size:.95rem;margin-bottom:6px;">No students linked</h3>
            <p>Contact your school administrator to link your children.</p>
        </div>
    </div>
    @endforelse

    {{-- Recent alerts --}}
    @if($recentNotifications->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-bell-fill"></i> Recent Alerts</div>
            <a href="{{ route('parent.notifications') }}" style="font-size:.78rem;color:var(--teal);text-decoration:none;font-weight:600;">View all</a>
        </div>
        @foreach($recentNotifications as $n)
        <div class="notif-row {{ !$n->is_read ? 'unread' : '' }}">
            <div class="notif-icon"><i class="bi {{ $n->icon }}"></i></div>
            <div style="flex:1;">
                <div class="notif-title {{ $n->is_read ? 'read' : '' }}">{{ $n->title }}</div>
                <div class="notif-time">{{ $n->time_ago }}</div>
            </div>
            @if(!$n->is_read) <div class="notif-unread-dot"></div> @endif
        </div>
        @endforeach
    </div>
    @endif

</div>
</div>

{{-- ══ TAB: LIVE TRACK ═════════════════════════════ --}}
<div class="tab-panel" id="tab-livetrack">
<div class="page">

    <div style="margin-bottom:20px;">
        <div style="font-family:var(--fd);font-size:1.25rem;font-weight:800;color:var(--white);margin-bottom:4px;">Live Track</div>
        <div style="font-size:.84rem;color:var(--muted);">
            @if(count($activeTrips) > 0)
                Tap a bus card to open the full tracking screen.
            @else
                No buses active right now. Check back when a trip starts.
            @endif
        </div>
    </div>

    @if(count($activeTrips) > 0)

    {{-- Live buses list --}}
    @foreach($activeTrips as $studentId => $trip)
    @php $trackStudent = $students->find($studentId); @endphp
    <div style="background:var(--navy-2);border:1px solid rgba(0,229,195,.2);border-radius:18px;overflow:hidden;margin-bottom:14px;position:relative;">
        <div style="position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--teal),transparent);"></div>
        <div style="padding:16px 18px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:42px;height:42px;border-radius:12px;background:rgba(0,229,195,.1);border:1px solid rgba(0,229,195,.2);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:1rem;">
                        <i class="bi bi-bus-front-fill"></i>
                    </div>
                    <div>
                        <div style="font-family:var(--fd);font-size:.92rem;font-weight:700;color:var(--white);display:flex;align-items:center;gap:7px;">
                            <span class="live-dot"></span> {{ $trip->route->name }}
                        </div>
                        <div style="font-size:.72rem;color:var(--muted);">
                            For {{ $trackStudent?->full_name ?? 'your child' }} · {{ $trip->vehicle->plate_number }}
                        </div>
                    </div>
                </div>
                <span class="badge badge-teal">Live</span>
            </div>

            {{-- Mini map --}}
            <div id="minimap-{{ $studentId }}" style="height:200px;border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,.07);margin-bottom:14px;"></div>

            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:14px;">
                <div style="background:var(--navy-3);border-radius:10px;padding:10px;text-align:center;">
                    <div style="font-family:var(--fd);font-size:1.1rem;font-weight:800;color:var(--teal);" id="lt-spd-{{ $studentId }}">
                        {{ $trip->current_speed ? round($trip->current_speed) : 0 }}
                    </div>
                    <div style="font-size:.67rem;color:var(--muted);">km/h</div>
                </div>
                <div style="background:var(--navy-3);border-radius:10px;padding:10px;text-align:center;">
                    <div style="font-family:var(--fd);font-size:1.1rem;font-weight:800;color:var(--white);">
                        {{ $trip->started_at?->format('H:i') ?? '—' }}
                    </div>
                    <div style="font-size:.67rem;color:var(--muted);">Started</div>
                </div>
                <div style="background:var(--navy-3);border-radius:10px;padding:10px;text-align:center;">
                    <div style="font-family:var(--fd);font-size:1.1rem;font-weight:800;color:var(--gold);" id="lt-eta-{{ $studentId }}">—</div>
                    <div style="font-size:.67rem;color:var(--muted);">ETA</div>
                </div>
            </div>

            <a href="{{ route('parent.track', $trip) }}" class="btn btn-primary btn-full">
                <i class="bi bi-fullscreen"></i> Full Screen Track
            </a>
        </div>
    </div>
    @endforeach

    @else
    <div class="map-empty">
        <i class="bi bi-geo-alt"></i>
        <p>No buses are active right now.<br>You'll see live maps here when a trip starts.</p>
    </div>
    @endif

</div>
</div>

{{-- ══ TAB: MY CHILD ═══════════════════════════════ --}}
<div class="tab-panel" id="tab-mychild">
<div class="page">

    <div style="margin-bottom:20px;">
        <div style="font-family:var(--fd);font-size:1.25rem;font-weight:800;color:var(--white);margin-bottom:4px;">My Child</div>
        <div style="font-size:.84rem;color:var(--muted);">Profile and transport details for your registered children.</div>
    </div>

    @forelse($students as $student)
    <div class="card" style="margin-bottom:20px;">
        {{-- Header --}}
        <div style="padding:20px 18px;border-bottom:1px solid rgba(255,255,255,.05);display:flex;align-items:center;gap:14px;">
            <img src="{{ $student->photo_url }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid rgba(0,229,195,.25);" alt="">
            <div style="flex:1;">
                <div style="font-family:var(--fd);font-size:1.05rem;font-weight:800;color:var(--white);margin-bottom:2px;">{{ $student->full_name }}</div>
                @if($student->student_id)
                    <div style="font-size:.72rem;color:var(--muted);margin-bottom:4px;">ID: {{ $student->student_id }}</div>
                @endif
                <span class="badge {{ $student->is_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $student->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        {{-- Info grid --}}
        <div style="padding:16px 18px;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;">
                @foreach([
                    ['bi-mortarboard-fill', 'Grade',   $student->display_grade ?: '—'],
                    ['bi-calendar3',        'Age',     $student->age ? $student->age . ' yrs' : '—'],
                ] as [$icon, $lbl, $val])
                <div style="background:var(--navy-3);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;border-radius:9px;background:rgba(0,229,195,.08);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.85rem;flex-shrink:0;">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    <div>
                        <div style="font-size:.68rem;color:var(--muted);margin-bottom:2px;">{{ $lbl }}</div>
                        <div style="font-size:.88rem;font-weight:600;color:var(--white);">{{ $val }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Transport assignment --}}
            <div style="font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:10px;">
                Transport Assignment
            </div>

            @if($student->route)
            <div style="background:var(--navy-3);border:1px solid rgba(255,255,255,.06);border-radius:12px;padding:14px;margin-bottom:10px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <i class="bi bi-signpost-2-fill" style="color:var(--teal);font-size:.85rem;"></i>
                    <span style="font-family:var(--fd);font-size:.82rem;font-weight:700;color:var(--white);">{{ $student->route->name }}</span>
                </div>
                <div style="font-size:.75rem;color:var(--muted);">{{ $student->route->path_summary }}</div>
                <div style="display:flex;gap:8px;margin-top:8px;">
                    @if($student->route->morning_departure)
                    <span style="background:rgba(255,181,71,.1);color:var(--gold);border:1px solid rgba(255,181,71,.2);font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;">
                        <i class="bi bi-sunrise"></i> {{ $student->route->morning_departure }}
                    </span>
                    @endif
                    @if($student->route->afternoon_departure)
                    <span style="background:rgba(99,179,237,.1);color:var(--info);border:1px solid rgba(99,179,237,.2);font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;">
                        <i class="bi bi-sunset"></i> {{ $student->route->afternoon_departure }}
                    </span>
                    @endif
                </div>
            </div>
            @endif

            @if($student->stop)
            <div class="stop-highlight">
                <div class="stop-highlight-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="stop-highlight-name">{{ $student->stop->name }}</div>
                    <div class="stop-highlight-sub">
                        {{ $student->stop->landmark ?? 'Boarding stop' }}
                        · +{{ $student->stop->arrival_offset_min }} min from departure
                    </div>
                </div>
            </div>
            @else
            <div style="padding:12px;background:var(--navy-3);border-radius:12px;font-size:.82rem;color:var(--muted);text-align:center;">
                No boarding stop assigned yet.
            </div>
            @endif

            @if($student->medical_notes)
            <div style="margin-top:12px;padding:12px 14px;background:rgba(251,113,133,.06);border:1px solid rgba(251,113,133,.15);border-radius:12px;">
                <div style="font-size:.7rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:var(--danger);margin-bottom:5px;">
                    <i class="bi bi-heart-pulse-fill"></i> Medical Notes
                </div>
                <div style="font-size:.82rem;color:var(--white);">{{ $student->medical_notes }}</div>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="card">
        <div class="empty">
            <i class="bi bi-people" style="font-size:2rem;"></i>
            <p>No children linked to your account.<br>Contact your school administrator.</p>
        </div>
    </div>
    @endforelse

</div>
</div>

{{-- ══ TAB: ALERTS ═════════════════════════════════ --}}
<div class="tab-panel" id="tab-alerts">
<div class="page">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <div>
            <div style="font-family:var(--fd);font-size:1.25rem;font-weight:800;color:var(--white);margin-bottom:4px;">Alerts</div>
            <div style="font-size:.84rem;color:var(--muted);">All transport notifications.</div>
        </div>
        @if($unreadCount)
        <form method="POST" action="{{ route('parent.notifications.readAll') }}">
            @csrf
            <button type="submit" class="btn btn-secondary" style="font-size:.78rem;padding:8px 14px;border-radius:9px;">
                <i class="bi bi-check-all"></i> Mark all read
            </button>
        </form>
        @endif
    </div>

    <div class="card">
        @forelse($recentNotifications as $n)
        <div class="notif-row {{ !$n->is_read ? 'unread' : '' }}">
            <div class="notif-icon"><i class="bi {{ $n->icon }}"></i></div>
            <div style="flex:1;min-width:0;">
                <div class="notif-title {{ $n->is_read ? 'read' : '' }}">{{ $n->title }}</div>
                <div style="font-size:.76rem;color:var(--muted);line-height:1.5;margin:2px 0;">{{ $n->message }}</div>
                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:4px;">
                    <span class="notif-time">{{ $n->time_ago }}</span>
                    @if($n->trip) <span class="badge badge-secondary" style="font-size:.6rem;">{{ $n->trip->route->name }}</span> @endif
                    @if(isset($n->meta['stop_name'])) <span class="badge badge-teal" style="font-size:.6rem;">{{ $n->meta['stop_name'] }}</span> @endif
                </div>
            </div>
            @if(!$n->is_read) <div class="notif-unread-dot"></div> @endif
        </div>
        @empty
        <div class="empty">
            <i class="bi bi-bell-slash"></i>
            <p>No notifications yet.</p>
        </div>
        @endforelse
    </div>

    @if($recentNotifications->count() >= 5)
    <div style="text-align:center;margin-top:8px;">
        <a href="{{ route('parent.notifications') }}" class="btn btn-secondary btn-full">
            <i class="bi bi-list"></i> View All Notifications
        </a>
    </div>
    @endif

</div>
</div>

{{-- ══ BOTTOM NAV ══════════════════════════════════ --}}
<nav class="bottom-nav">
    <button class="bottom-tab active" data-tab="dashboard" onclick="switchTab('dashboard',this)">
        <i class="bi bi-house-fill"></i> Home
    </button>
    <button class="bottom-tab" data-tab="livetrack" onclick="switchTab('livetrack',this)">
        <i class="bi bi-geo-alt-fill"></i> Track
    </button>
    <button class="bottom-tab" data-tab="mychild" onclick="switchTab('mychild',this)">
        <i class="bi bi-people-fill"></i> My Child
    </button>
    <button class="bottom-tab" data-tab="alerts" onclick="switchTab('alerts',this)">
        <i class="bi bi-bell-fill"></i> Alerts
    </button>
</nav>

{{-- Pre-built PHP data for JS --}}
@php
    $activeTripsJs = [];
    foreach ($activeTrips as $studentId => $trip) {
        $st = $students->find($studentId);
        $activeTripsJs[$studentId] = [
            'trip_id'  => $trip->id,
            'lat'      => $trip->current_latitude  ? (float) $trip->current_latitude  : null,
            'lng'      => $trip->current_longitude ? (float) $trip->current_longitude : null,
            'speed'    => $trip->current_speed ? round($trip->current_speed) : 0,
            'stop_lat' => $st?->stop ? (float) $st->stop->latitude  : null,
            'stop_lng' => $st?->stop ? (float) $st->stop->longitude : null,
            'stops'    => $trip->route->stops->map(fn($s) => ['name'=>$s->name,'lat'=>(float)$s->latitude,'lng'=>(float)$s->longitude,'order'=>$s->order])->values()->all(),
        ];
    }
    $activeTripsJson = json_encode($activeTripsJs);
@endphp

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var CSRF        = document.querySelector('meta[name=csrf-token]').content;
var ACTIVE_DATA = {!! $activeTripsJson !!};

// ── Tab switching ─────────────────────────────────────────────────────────────
var headerTabs  = document.querySelectorAll('.nav-tab');
var bottomTabs  = document.querySelectorAll('.bottom-tab');
var panels      = document.querySelectorAll('.tab-panel');
var miniMapsInit = {};

function switchTab(name, callerEl) {
    panels.forEach(function(p)     { p.classList.remove('active'); });
    headerTabs.forEach(function(t) { t.classList.remove('active'); });
    bottomTabs.forEach(function(t) { t.classList.remove('active'); });

    document.getElementById('tab-' + name).classList.add('active');

    // Sync both header and bottom nav
    document.querySelectorAll('[data-tab="' + name + '"]').forEach(function(el) {
        el.classList.add('active');
    });

    if (name === 'livetrack') initMiniMaps();
}

headerTabs.forEach(function(tab) {
    tab.addEventListener('click', function() { switchTab(this.dataset.tab, this); });
});

// ── Mini maps on Live Track tab ───────────────────────────────────────────────
var miniMaps   = {};
var miniMarkers = {};

function initMiniMaps() {
    Object.keys(ACTIVE_DATA).forEach(function(sid) {
        if (miniMapsInit[sid]) return;
        miniMapsInit[sid] = true;

        var d      = ACTIVE_DATA[sid];
        var center = d.lat && d.lng ? [d.lat, d.lng] : [-1.9706, 30.1050];
        var m      = L.map('minimap-' + sid, { zoomControl: false, attributionControl: false, dragging: false })
                     .setView(center, 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(m);
        miniMaps[sid] = m;

        // Stop markers
        d.stops.forEach(function(s, i) {
            var isLast = (i === d.stops.length - 1);
            var icon = L.divIcon({ className: '', html: '<div style="width:18px;height:18px;border-radius:50%;background:' + (isLast ? '#00E5C3' : '#0D1628') + ';border:2px solid ' + (isLast ? '#00E5C3' : 'rgba(0,229,195,.4)') + ';display:flex;align-items:center;justify-content:center;color:' + (isLast ? '#050B18' : '#00E5C3') + ';font-size:.5rem;font-weight:700;">' + (isLast ? '🏫' : s.order) + '</div>', iconSize: [18,18], iconAnchor: [9,9] });
            L.marker([s.lat, s.lng], { icon: icon }).addTo(m);
        });

        // Bus marker
        if (d.lat && d.lng) {
            var busIcon = L.divIcon({ className: '', html: '<div style="width:34px;height:34px;border-radius:50%;background:#00E5C3;display:flex;align-items:center;justify-content:center;color:#050B18;font-size:.9rem;box-shadow:0 0 0 6px rgba(0,229,195,.15);">🚌</div>', iconSize: [34,34], iconAnchor: [17,17] });
            miniMarkers[sid] = L.marker([d.lat, d.lng], { icon: busIcon }).addTo(m);
        }
    });
}

// ── Haversine ─────────────────────────────────────────────────────────────────
function haversine(lat1, lng1, lat2, lng2) {
    var R = 6371000, dLat = (lat2-lat1)*Math.PI/180, dLng = (lng2-lng1)*Math.PI/180;
    var a = Math.sin(dLat/2)*Math.sin(dLat/2) + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLng/2)*Math.sin(dLng/2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

// ── Poll all active trips every 8s ───────────────────────────────────────────
function pollAll() {
    Object.keys(ACTIVE_DATA).forEach(function(sid) {
        var d = ACTIVE_DATA[sid];
        fetch('/admin/trips/' + d.trip_id + '/position', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(pos) {
                if (!pos.lat || !pos.lng) return;

                // Update speed on dashboard tab
                var spdEl = document.getElementById('spd-' + sid);
                if (spdEl) spdEl.textContent = pos.speed ? Math.round(pos.speed) : 0;

                // Update speed on livetrack tab
                var ltSpdEl = document.getElementById('lt-spd-' + sid);
                if (ltSpdEl) ltSpdEl.textContent = pos.speed ? Math.round(pos.speed) : 0;

                // ETA on dashboard
                if (d.stop_lat && d.stop_lng) {
                    var dist   = haversine(pos.lat, pos.lng, d.stop_lat, d.stop_lng);
                    var spd    = pos.speed && pos.speed > 2 ? pos.speed : 15;
                    var etaMin = dist < 100 ? 0 : Math.max(1, Math.round((dist/1000)/spd*60));
                    var etaEl  = document.getElementById('eta-' + sid);
                    var ltEtaEl = document.getElementById('lt-eta-' + sid);
                    var etaTxt  = dist < 100 ? 'Arrived!' : '~' + etaMin + ' min';
                    if (etaEl)   etaEl.textContent   = etaTxt;
                    if (ltEtaEl) ltEtaEl.textContent = etaTxt;
                }

                // Update mini map marker
                if (miniMarkers[sid]) miniMarkers[sid].setLatLng([pos.lat, pos.lng]);
                if (miniMaps[sid])    miniMaps[sid].panTo([pos.lat, pos.lng]);
            }).catch(function() {});
    });
}

if (Object.keys(ACTIVE_DATA).length > 0) {
    setInterval(pollAll, 8000);
    pollAll();
}
</script>

</body>
</html>