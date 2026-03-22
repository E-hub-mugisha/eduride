@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Overview')

@push('styles')
<style>
    .chart-canvas { width:100% !important; }
    .chart-wrap-sm { position:relative; height:200px; }
    .chart-wrap-md { position:relative; height:240px; }
    .chart-wrap-lg { position:relative; height:280px; }
    .chart-wrap-donut { position:relative; height:200px; display:flex; align-items:center; justify-content:center; }
</style>
@endpush

@section('content')

{{-- ── KPI stats ── --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-value">{{ $stats['active_trips'] }}</div>
                <div class="stat-label">Active Trips</div>
            </div>
            <div class="stat-icon teal"><i class="bi bi-play-circle-fill"></i></div>
        </div>
        <div class="stat-sub {{ $stats['active_trips'] > 0 ? 'up' : '' }}">
            @if($stats['active_trips'] > 0)
                <span class="live-dot"></span> Live now
            @else
                No active trips
            @endif
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-value">{{ $stats['trips_today'] }}</div>
                <div class="stat-label">Trips Today</div>
            </div>
            <div class="stat-icon gold"><i class="bi bi-calendar-check-fill"></i></div>
        </div>
        <div class="stat-sub">Scheduled for today</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-value">{{ $totalTripsThisMonth }}</div>
                <div class="stat-label">This Month</div>
            </div>
            <div class="stat-icon teal"><i class="bi bi-calendar-month"></i></div>
        </div>
        <div class="stat-sub up">
            <i class="bi bi-check-circle-fill"></i> {{ $totalTripsAllTime }} all time
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-value">{{ $stats['total_vehicles'] }}</div>
                <div class="stat-label">Vehicles</div>
            </div>
            <div class="stat-icon blue"><i class="bi bi-truck-front-fill"></i></div>
        </div>
        <div class="stat-sub up">
            <i class="bi bi-check-circle-fill"></i> {{ $stats['active_vehicles'] }} active
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-value">{{ $stats['total_drivers'] }}</div>
                <div class="stat-label">Drivers</div>
            </div>
            <div class="stat-icon green"><i class="bi bi-person-badge-fill"></i></div>
        </div>
        <div class="stat-sub">{{ $stats['on_trip_drivers'] }} on trip now</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-top">
            <div>
                <div class="stat-value">{{ $stats['total_students'] }}</div>
                <div class="stat-label">Students</div>
            </div>
            <div class="stat-icon gold"><i class="bi bi-people-fill"></i></div>
        </div>
        <div class="stat-sub">{{ $stats['total_routes'] }} active routes</div>
    </div>
</div>

{{-- ── Analytics row 1: Line chart + Donut ── --}}
<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;margin-bottom:20px;">

    {{-- Trips last 7 days — line chart --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-graph-up-arrow"></i> Trips — Last 7 Days</div>
            <span style="font-family:var(--font-d);font-size:1rem;font-weight:800;color:var(--teal);">
                {{ array_sum($chartTrips) }} trips
            </span>
        </div>
        <div class="card-body">
            <div class="chart-wrap-md">
                <canvas id="lineChart" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>

    {{-- Trip status breakdown — donut --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-pie-chart-fill"></i> Trip Status</div>
        </div>
        <div class="card-body">
            <div class="chart-wrap-donut">
                <canvas id="donutChart" class="chart-canvas" style="max-height:180px;max-width:180px;"></canvas>
            </div>
            {{-- Legend --}}
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;justify-content:center;">
                @php
                    $statusColors = ['#7A8BAA','#63B3ED','#34D399','#FB7185','#FBBF24'];
                @endphp
                @foreach($statusLabels as $i => $lbl)
                @if($statusData[$i] > 0)
                <div style="display:flex;align-items:center;gap:5px;font-size:.72rem;color:var(--muted);">
                    <span style="width:8px;height:8px;border-radius:50%;background:{{ $statusColors[$i] }};flex-shrink:0;"></span>
                    {{ $lbl }} ({{ $statusData[$i] }})
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ── Analytics row 2: Monthly + Route ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

    {{-- Monthly completed trips — area chart --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-bar-chart-line-fill"></i> Monthly Trips (6 months)</div>
            <span style="font-size:.75rem;color:var(--muted);">Completed only</span>
        </div>
        <div class="card-body">
            <div class="chart-wrap-md">
                <canvas id="monthlyChart" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>

    {{-- Trips per route — horizontal bar --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-signpost-2-fill"></i> Trips by Route (30 days)</div>
            <span style="font-size:.75rem;color:var(--muted);">Completed only</span>
        </div>
        <div class="card-body">
            <div class="chart-wrap-md">
                <canvas id="routeChart" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ── Analytics row 3: Top drivers ── --}}
@if(count($driverLabels) > 0)
<div style="margin-bottom:20px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-person-badge-fill"></i> Top Drivers — Last 30 Days</div>
            <span style="font-size:.75rem;color:var(--muted);">By completed trips</span>
        </div>
        <div style="padding:20px;">
            @foreach($driverLabels as $i => $name)
            @php
                $max = max($driverData);
                $pct = $max > 0 ? round($driverData[$i] / $max * 100) : 0;
            @endphp
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div style="width:130px;font-size:.82rem;font-weight:600;color:var(--white);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex-shrink:0;">
                    {{ $name }}
                </div>
                <div style="flex:1;height:28px;background:var(--navy-3);border-radius:8px;overflow:hidden;position:relative;">
                    <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,var(--teal),var(--teal-dim));border-radius:8px;transition:width .8s ease;display:flex;align-items:center;justify-content:flex-end;padding-right:8px;">
                        @if($pct > 20)
                        <span style="font-family:var(--font-d);font-size:.72rem;font-weight:700;color:var(--navy);">{{ $driverData[$i] }}</span>
                        @endif
                    </div>
                </div>
                @if($pct <= 20)
                <span style="font-family:var(--font-d);font-size:.75rem;font-weight:700;color:var(--teal);min-width:24px;">{{ $driverData[$i] }}</span>
                @endif
                <span style="font-size:.72rem;color:var(--muted);min-width:32px;">trips</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ── Existing panels: Schedule + Live Buses + Notifications ── --}}
<div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">

    {{-- Today's schedule --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-calendar3"></i> Today's Trip Schedule</div>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.trips.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Schedule
                </a>
                <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary btn-sm">View all</a>
            </div>
        </div>
        <div class="table-wrap">
            @if($todayTrips->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <h3>No trips scheduled today</h3>
                    <p><a href="{{ route('admin.trips.create') }}" style="color:var(--teal);">Schedule the first one →</a></p>
                </div>
            @else
            <table>
                <thead>
                    <tr>
                        <th>Route</th><th>Driver</th><th>Vehicle</th><th>Time</th><th>Status</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($todayTrips as $trip)
                    <tr>
                        <td>
                            <div style="font-weight:600;">{{ $trip->route->name }}</div>
                            <div class="td-muted">{{ ucfirst($trip->type) }}</div>
                        </td>
                        <td>
                            <div class="avatar-row">
                                <img src="{{ $trip->driver->avatar_url }}" class="avatar-sm" alt="">
                                <div class="avatar-name">{{ $trip->driver->name }}</div>
                            </div>
                        </td>
                        <td class="td-muted">{{ $trip->vehicle->plate_number }}</td>
                        <td class="td-muted">{{ $trip->scheduled_at?->format('H:i') ?? '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $trip->status_badge['color'] }}">
                                @if($trip->status === 'in_progress')
                                    <span class="live-dot" style="width:6px;height:6px;background:var(--success);border-radius:50%;display:inline-block;"></span>
                                @endif
                                {{ $trip->status_badge['label'] }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.trips.show', $trip) }}" class="btn btn-secondary btn-sm btn-icon">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- Right sidebar --}}
    <div style="display:flex;flex-direction:column;gap:20px;">

        {{-- Live buses --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    @if($activeTrips->count()) <span class="live-dot"></span> @endif
                    Live Buses
                </div>
                <a href="{{ route('admin.map') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-map"></i> Map
                </a>
            </div>
            <div class="card-body" style="padding:10px;">
                @if($activeTrips->isEmpty())
                    <div class="empty-state" style="padding:24px 10px;">
                        <i class="bi bi-geo-alt" style="font-size:1.8rem;"></i>
                        <h3>No buses live</h3>
                        <p>Active trips appear here.</p>
                    </div>
                @else
                    @foreach($activeTrips as $trip)
                    <a href="{{ route('admin.trips.track', $trip) }}" style="display:flex;align-items:center;gap:12px;padding:10px 10px;border-radius:10px;text-decoration:none;transition:background .2s;" onmouseover="this.style.background='rgba(255,255,255,.04)'" onmouseout="this.style.background='transparent'">
                        <div style="width:36px;height:36px;border-radius:50%;background:rgba(0,229,195,.1);border:1px solid rgba(0,229,195,.2);display:flex;align-items:center;justify-content:center;color:var(--teal);font-size:.9rem;flex-shrink:0;">
                            <i class="bi bi-bus-front-fill"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:.84rem;font-weight:600;color:var(--white);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $trip->route->name }}</div>
                            <div style="font-size:.72rem;color:var(--muted);">{{ $trip->driver->name }} · {{ $trip->vehicle->plate_number }}</div>
                        </div>
                        <span class="live-dot"></span>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Recent notifications --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-bell-fill"></i> Recent Alerts</div>
            </div>
            <div class="card-body" style="padding:10px;">
                @forelse($recentNotifications as $n)
                <div style="display:flex;gap:10px;padding:9px 8px;border-radius:10px;border-bottom:1px solid rgba(255,255,255,.04);">
                    <div style="width:30px;height:30px;border-radius:50%;background:rgba(0,229,195,.08);display:flex;align-items:center;justify-content:center;font-size:.78rem;color:var(--teal);flex-shrink:0;">
                        <i class="bi {{ $n->icon }}"></i>
                    </div>
                    <div>
                        <div style="font-size:.81rem;font-weight:600;color:var(--white);">{{ $n->title }}</div>
                        <div style="font-size:.7rem;color:var(--muted);">{{ $n->time_ago }}</div>
                    </div>
                </div>
                @empty
                    <div class="empty-state" style="padding:20px 10px;">
                        <i class="bi bi-bell-slash" style="font-size:1.5rem;"></i>
                        <p>No recent notifications</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
{{-- Pre-build all chart data in PHP --}}
@php
    $lineLabelsJson   = json_encode($chartLabels);
    $lineDataJson     = json_encode($chartTrips);
    $statusLabelsJson = json_encode($statusLabels);
    $statusDataJson   = json_encode($statusData);
    $routeLabelsJson  = json_encode($routeLabels);
    $routeDataJson    = json_encode($routeData);
    $monthLabelsJson  = json_encode($monthLabels);
    $monthlyDataJson  = json_encode($monthlyData);
@endphp
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
var TEAL   = '#00E5C3';
var GOLD   = '#FFB547';
var MUTED  = '#7A8BAA';
var NAVY3  = '#121E35';
var WHITE  = '#F0F4FF';

var defaultFont = { family: "'DM Sans', sans-serif", size: 11 };

var gridOpts  = { color: 'rgba(255,255,255,.05)' };
var tickOpts  = { color: MUTED, font: defaultFont };

// ── 1. Line chart — trips last 7 days ────────────────────────────────────────
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: {!! $lineLabelsJson !!},
        datasets: [{
            label:           'Trips',
            data:            {!! $lineDataJson !!},
            borderColor:     TEAL,
            backgroundColor: 'rgba(0,229,195,.08)',
            borderWidth:     2.5,
            pointBackgroundColor: TEAL,
            pointRadius:     4,
            pointHoverRadius:6,
            fill:            true,
            tension:         0.4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: tooltipStyle() },
        scales: {
            x: { grid: gridOpts, ticks: tickOpts },
            y: { beginAtZero: true, grid: gridOpts, ticks: { ...tickOpts, stepSize: 1, precision: 0 } }
        }
    }
});

// ── 2. Donut chart — trips by status ─────────────────────────────────────────
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: {!! $statusLabelsJson !!},
        datasets: [{
            data:            {!! $statusDataJson !!},
            backgroundColor: ['rgba(122,139,170,.6)','rgba(99,179,237,.8)','rgba(52,211,153,.8)','rgba(251,113,133,.8)','rgba(251,191,36,.8)'],
            borderColor:     '#0D1628',
            borderWidth:     3,
            hoverOffset:     6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend:  { display: false },
            tooltip: tooltipStyle(),
        }
    }
});

// ── 3. Area chart — monthly trips (6 months) ─────────────────────────────────
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: {!! $monthLabelsJson !!},
        datasets: [{
            label:           'Completed trips',
            data:            {!! $monthlyDataJson !!},
            backgroundColor: 'rgba(0,229,195,.25)',
            borderColor:     TEAL,
            borderWidth:     2,
            borderRadius:    8,
            borderSkipped:   false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: tooltipStyle() },
        scales: {
            x: { grid: gridOpts, ticks: { ...tickOpts, maxRotation: 30 } },
            y: { beginAtZero: true, grid: gridOpts, ticks: { ...tickOpts, stepSize: 1, precision: 0 } }
        }
    }
});

// ── 4. Horizontal bar — trips by route ───────────────────────────────────────
new Chart(document.getElementById('routeChart'), {
    type: 'bar',
    data: {
        labels: {!! $routeLabelsJson !!},
        datasets: [{
            label:           'Trips',
            data:            {!! $routeDataJson !!},
            backgroundColor: 'rgba(255,181,71,.3)',
            borderColor:     GOLD,
            borderWidth:     2,
            borderRadius:    6,
            borderSkipped:   false,
        }]
    },
    options: {
        indexAxis:  'y',
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: tooltipStyle() },
        scales: {
            x: { beginAtZero: true, grid: gridOpts, ticks: { ...tickOpts, stepSize: 1, precision: 0 } },
            y: { grid: { display: false }, ticks: { color: WHITE, font: { ...defaultFont, size: 11 } } }
        }
    }
});

// ── Shared tooltip style ──────────────────────────────────────────────────────
function tooltipStyle() {
    return {
        backgroundColor: '#0D1628',
        borderColor:     'rgba(0,229,195,.2)',
        borderWidth:     1,
        titleColor:      WHITE,
        bodyColor:       MUTED,
        padding:         10,
        cornerRadius:    8,
    };
}
</script>
@endpush