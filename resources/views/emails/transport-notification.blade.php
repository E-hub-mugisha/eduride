<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $notification->title }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 15px;
            color: #1a2035;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            max-width: 600px;
            margin: 32px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, .08);
        }

        /* Header */
        .header {
            background: #050B18;
            padding: 28px 36px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: #00E5C3;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .logo-text {
            font-size: 1.3rem;
            font-weight: 800;
            color: #F0F4FF;
            letter-spacing: -.02em;
        }

        .logo-text span {
            color: #00E5C3;
        }

        /* Type banner */
        .type-banner {
            padding: 14px 36px;
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .type-banner.trip_started {
            background: #e8faf4;
            color: #0f6e56;
            border-bottom: 3px solid #00E5C3;
        }

        .type-banner.trip_completed {
            background: #e8faf4;
            color: #0f6e56;
            border-bottom: 3px solid #00E5C3;
        }

        .type-banner.bus_approaching {
            background: #EBF5FF;
            color: #185FA5;
            border-bottom: 3px solid #378ADD;
        }

        .type-banner.bus_arrived {
            background: #e8faf4;
            color: #0f6e56;
            border-bottom: 3px solid #00E5C3;
        }

        .type-banner.trip_delayed {
            background: #FFFBEB;
            color: #854F0B;
            border-bottom: 3px solid #EF9F27;
        }

        .type-banner.trip_cancelled {
            background: #FFF1F2;
            color: #A32D2D;
            border-bottom: 3px solid #E24B4A;
        }

        .type-banner.sos {
            background: #FFF1F2;
            color: #A32D2D;
            border-bottom: 3px solid #E24B4A;
        }

        .type-banner.system {
            background: #f4f6f9;
            color: #5F5E5A;
            border-bottom: 3px solid #888780;
        }

        /* Body */
        .body {
            padding: 36px;
        }

        .greeting {
            font-size: 1rem;
            color: #4a5568;
            margin-bottom: 20px;
        }

        .title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #050B18;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .message {
            font-size: .95rem;
            color: #2d3748;
            margin-bottom: 24px;
            line-height: 1.7;
        }

        /* Info card */
        .info-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 28px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #edf2f7;
            font-size: .88rem;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #718096;
            font-weight: 500;
        }

        .info-value {
            color: #1a202c;
            font-weight: 600;
            text-align: right;
        }

        /* ETA highlight */
        .eta-box {
            background: linear-gradient(135deg, #00E5C3, #00B89A);
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 24px;
            text-align: center;
        }

        .eta-box .eta-label {
            font-size: .8rem;
            font-weight: 600;
            color: #050B18;
            opacity: .7;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 4px;
        }

        .eta-box .eta-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: #050B18;
        }

        /* SOS alert */
        .sos-box {
            background: #FFF1F2;
            border: 2px solid #E24B4A;
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 24px;
            text-align: center;
        }

        .sos-box .sos-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #A32D2D;
            margin-bottom: 6px;
        }

        .sos-box .sos-body {
            font-size: .9rem;
            color: #A32D2D;
        }

        /* CTA button */
        .cta-wrap {
            text-align: center;
            margin-bottom: 28px;
        }

        .cta-btn {
            display: inline-block;
            background: #050B18;
            color: #00E5C3 !important;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 700;
            padding: 13px 32px;
            border-radius: 10px;
            letter-spacing: .02em;
        }

        /* Footer */
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 36px;
            text-align: center;
            font-size: .78rem;
            color: #a0aec0;
            line-height: 1.6;
        }

        .footer a {
            color: #00B89A;
            text-decoration: none;
        }

        .footer strong {
            color: #718096;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        {{-- Header --}}
        <div class="header">
            <div class="logo-icon">🚌</div>
            <div class="logo-text">EDU<span>RIDE</span></div>
        </div>

        {{-- Type banner --}}
        <div class="type-banner {{ $notification->type }}">
            @switch($notification->type)
            @case('trip_started') 🚌 Trip Started @break
            @case('trip_completed') ✅ Trip Completed @break
            @case('bus_approaching') 📍 Bus Approaching @break
            @case('bus_arrived') 🟢 Bus Arrived @break
            @case('trip_delayed') ⚠️ Trip Delayed @break
            @case('trip_cancelled') ❌ Trip Cancelled @break
            @case('sos') 🚨 Emergency Alert @break
            @default ℹ️ Notification @break
            @endswitch
        </div>

        {{-- Body --}}
        <div class="body">

            <p class="greeting">Hello {{ $user->name }},</p>
            <h1 class="title">{{ $notification->title }}</h1>
            <p class="message">{{ $notification->message }}</p>

            {{-- ETA box for approaching notifications --}}
            @if($notification->type === 'bus_approaching' && isset($meta['eta_minutes']))
            <div class="eta-box">
                <div class="eta-label">Estimated arrival at {{ $meta['stop_name'] ?? 'your stop' }}</div>
                <div class="eta-value">~ {{ $meta['eta_minutes'] }} min away</div>
            </div>
            @endif

            {{-- SOS box --}}
            @if($notification->type === 'sos')
            <div class="sos-box">
                <div class="sos-title">🚨 Emergency Alert</div>
                <div class="sos-body">
                    The driver has triggered an emergency SOS.
                    @if(isset($meta['lat']) && isset($meta['lng']))
                    <br>Last known location:
                    <a href="https://maps.google.com/?q={{ $meta['lat'] }},{{ $meta['lng'] }}" style="color:#A32D2D;font-weight:700;">
                        View on Google Maps
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- Trip info card --}}
            @if($trip)
            <div class="info-card">
                @if($trip->route)
                <div class="info-row">
                    <span class="info-label">Route</span>
                    <span class="info-value">{{ $trip->route->name }}</span>
                </div>
                @endif
                @if($trip->driver)
                <div class="info-row">
                    <span class="info-label">Driver</span>
                    <span class="info-value">{{ $trip->driver->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Driver Phone</span>
                    <span class="info-value">{{ $trip->driver->phone ?? '—' }}</span>
                </div>
                @endif
                @if($trip->vehicle)
                <div class="info-row">
                    <span class="info-label">Vehicle</span>
                    <span class="info-value">{{ $trip->vehicle->plate_number }}</span>
                </div>
                @endif
                @if(isset($meta['stop_name']))
                <div class="info-row">
                    <span class="info-label">Stop</span>
                    <span class="info-value">{{ $meta['stop_name'] }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Time</span>
                    <span class="info-value">{{ $notification->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
            @endif

            {{-- CTA --}}
            <div class="cta-wrap">
                <a href="{{ url('/parent') }}" class="cta-btn">Open EDURIDE App →</a>
            </div>

        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>You received this because you are registered on <strong>EDURIDE · IRERERO Academy</strong>.</p>
            <p style="margin-top:6px;">
                © {{ date('Y') }} IRERERO Academy ·
                <a href="{{ url('/parent') }}">My Dashboard</a>
            </p>
        </div>

    </div>
</body>

</html>