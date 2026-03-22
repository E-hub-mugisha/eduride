<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EDURIDE | IRERERO Academy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy: #050B18;
            --navy-2: #0D1628;
            --navy-3: #121E35;
            --teal: #00E5C3;
            --teal-dim: #00B89A;
            --gold: #FFB547;
            --gold-dim: #E09A2C;
            --white: #F0F4FF;
            --muted: #7A8BAA;
            --card-border: rgba(0, 229, 195, 0.12);
            --card-bg: rgba(13, 22, 40, 0.75);
            --font-display: 'Syne', sans-serif;
            --font-body: 'DM Sans', sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--navy);
            color: var(--white);
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ── Background canvas ── */
        .bg-canvas {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            overflow: hidden;
        }
        .bg-canvas::before {
            content: '';
            position: absolute;
            width: 900px; height: 900px;
            background: radial-gradient(circle, rgba(0,229,195,.12) 0%, transparent 65%);
            top: -200px; left: -200px;
            animation: driftA 18s ease-in-out infinite alternate;
        }
        .bg-canvas::after {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(255,181,71,.08) 0%, transparent 65%);
            bottom: 0; right: -100px;
            animation: driftB 22s ease-in-out infinite alternate;
        }
        @keyframes driftA { to { transform: translate(120px, 80px); } }
        @keyframes driftB { to { transform: translate(-80px, -60px); } }

        /* Grid texture */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(0,229,195,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,229,195,.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Utilities ── */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 1; }
        .section { padding: 110px 0; }
        .section-tag {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(0,229,195,.08);
            border: 1px solid rgba(0,229,195,.2);
            color: var(--teal);
            font-family: var(--font-display);
            font-size: .75rem; font-weight: 600; letter-spacing: .12em;
            text-transform: uppercase;
            padding: 6px 16px; border-radius: 100px;
            margin-bottom: 20px;
        }
        .section-tag i { font-size: .9rem; }

        /* ── HEADER ── */
        #header {
            position: sticky; top: 0; z-index: 100;
            padding: 0 24px;
            background: rgba(5,11,24,.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,229,195,.08);
            transition: all .3s ease;
        }
        .header-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 72px;
        }
        .logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: var(--navy);
        }
        .logo-text {
            font-family: var(--font-display);
            font-size: 1.35rem; font-weight: 800;
            color: var(--white); letter-spacing: -.01em;
        }
        .logo-text span { color: var(--teal); }

        nav ul { list-style: none; display: flex; gap: 36px; }
        nav a {
            color: var(--muted);
            text-decoration: none;
            font-size: .9rem; font-weight: 500;
            transition: color .2s;
            position: relative;
        }
        nav a::after {
            content: ''; position: absolute; bottom: -4px; left: 0;
            width: 0; height: 2px;
            background: var(--teal);
            border-radius: 2px;
            transition: width .25s ease;
        }
        nav a:hover, nav a.active { color: var(--white); }
        nav a:hover::after, nav a.active::after { width: 100%; }

        .btn-nav {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--teal);
            color: var(--navy);
            font-family: var(--font-display);
            font-size: .85rem; font-weight: 700;
            padding: 10px 22px; border-radius: 8px;
            text-decoration: none;
            transition: all .25s ease;
            white-space: nowrap;
        }
        .btn-nav:hover {
            background: var(--white);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(0,229,195,.3);
        }

        /* ── HERO ── */
        #hero {
            min-height: 100vh;
            display: flex; align-items: center;
            padding: 120px 0 80px;
        }
        .hero-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 80px; align-items: center;
        }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(255,181,71,.08);
            border: 1px solid rgba(255,181,71,.25);
            color: var(--gold);
            font-size: .78rem; font-weight: 600; letter-spacing: .1em;
            text-transform: uppercase;
            padding: 7px 16px; border-radius: 100px;
            margin-bottom: 28px;
            animation: fadeSlideUp .8s ease both;
        }
        .hero-eyebrow i { font-size: .9rem; }
        .hero-headline {
            font-family: var(--font-display);
            font-size: clamp(2.6rem, 4.5vw, 3.8rem);
            font-weight: 800;
            line-height: 1.1; letter-spacing: -.03em;
            color: var(--white);
            margin-bottom: 24px;
            animation: fadeSlideUp .8s .1s ease both;
        }
        .hero-headline .accent { color: var(--teal); }
        .hero-headline .accent-gold { color: var(--gold); }
        .hero-sub {
            font-size: 1.05rem; color: var(--muted); line-height: 1.75;
            max-width: 480px; margin-bottom: 40px;
            animation: fadeSlideUp .8s .2s ease both;
        }
        .hero-actions {
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
            animation: fadeSlideUp .8s .3s ease both;
        }
        .btn-primary-hero {
            display: inline-flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            color: var(--navy); font-family: var(--font-display);
            font-weight: 700; font-size: .95rem;
            padding: 14px 28px; border-radius: 12px;
            text-decoration: none;
            transition: all .3s ease;
            box-shadow: 0 4px 24px rgba(0,229,195,.25);
        }
        .btn-primary-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0,229,195,.4);
        }
        .btn-ghost-hero {
            display: inline-flex; align-items: center; gap: 10px;
            color: var(--white); font-family: var(--font-display);
            font-weight: 600; font-size: .95rem;
            padding: 14px 28px; border-radius: 12px;
            border: 1px solid rgba(255,255,255,.12);
            text-decoration: none;
            transition: all .3s ease;
            background: rgba(255,255,255,.04);
        }
        .btn-ghost-hero:hover {
            border-color: rgba(255,255,255,.3);
            background: rgba(255,255,255,.08);
        }

        /* Floating stats panel */
        .hero-visual {
            position: relative;
            animation: fadeSlideUp .9s .15s ease both;
        }
        .hero-map-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 32px;
            backdrop-filter: blur(24px);
            box-shadow: 0 40px 80px rgba(0,0,0,.4), 0 0 0 1px rgba(0,229,195,.05);
            position: relative; overflow: hidden;
        }
        .hero-map-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--teal), var(--gold));
        }
        .map-pulse {
            width: 100%; height: 260px;
            background: var(--navy-3);
            border-radius: 16px;
            position: relative; overflow: hidden;
            margin-bottom: 24px;
        }
        /* Simulated map grid */
        .map-pulse::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(0,229,195,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,229,195,.06) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .map-route {
            position: absolute;
            top: 50%; left: 10%; right: 10%;
            height: 2px; background: rgba(0,229,195,.3);
            transform: translateY(-50%);
        }
        .map-route::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, var(--teal), transparent);
            animation: routePulse 3s ease-in-out infinite;
        }
        @keyframes routePulse {
            0%, 100% { opacity: .4; }
            50% { opacity: 1; }
        }
        .map-bus {
            position: absolute;
            top: 50%; left: 30%;
            transform: translateY(-50%);
            width: 44px; height: 44px;
            background: var(--teal);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--navy); font-size: 1.1rem; font-weight: 700;
            box-shadow: 0 0 0 8px rgba(0,229,195,.15), 0 0 0 16px rgba(0,229,195,.06);
            animation: busMove 6s ease-in-out infinite alternate;
        }
        @keyframes busMove {
            0% { left: 20%; }
            100% { left: 65%; }
        }
        .map-stop {
            position: absolute;
            width: 12px; height: 12px; border-radius: 50%;
            border: 2px solid var(--teal);
            background: var(--navy);
            top: 50%; transform: translateY(-50%);
        }
        .map-stop-a { left: 10%; }
        .map-stop-b { left: 50%; }
        .map-stop-c { right: 10%; }

        .live-badge {
            position: absolute; top: 16px; right: 16px;
            display: flex; align-items: center; gap: 6px;
            background: rgba(0,229,195,.1); border: 1px solid rgba(0,229,195,.2);
            color: var(--teal); font-size: .75rem; font-weight: 600;
            padding: 5px 12px; border-radius: 100px;
        }
        .live-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--teal);
            animation: blink 1.2s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.3;} }

        .card-stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;
        }
        .mini-stat {
            background: var(--navy-3);
            border: 1px solid rgba(0,229,195,.08);
            border-radius: 14px;
            padding: 16px;
            transition: border-color .3s;
        }
        .mini-stat:hover { border-color: rgba(0,229,195,.25); }
        .mini-stat-val {
            font-family: var(--font-display);
            font-size: 1.4rem; font-weight: 800;
            color: var(--white); margin-bottom: 2px;
        }
        .mini-stat-val.teal { color: var(--teal); }
        .mini-stat-val.gold { color: var(--gold); }
        .mini-stat-label { font-size: .75rem; color: var(--muted); }

        /* Floating badge */
        .float-badge {
            position: absolute;
            display: flex; align-items: center; gap: 10px;
            background: var(--navy-2);
            border: 1px solid var(--card-border);
            border-radius: 14px;
            padding: 12px 18px;
            backdrop-filter: blur(16px);
            box-shadow: 0 20px 40px rgba(0,0,0,.3);
            animation: floatBob 4s ease-in-out infinite;
        }
        @keyframes floatBob { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-8px);} }
        .float-badge-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
        }
        .float-badge-icon.teal { background: rgba(0,229,195,.15); color: var(--teal); }
        .float-badge-icon.gold { background: rgba(255,181,71,.15); color: var(--gold); }
        .float-badge-text { font-size: .78rem; }
        .float-badge-text strong { display: block; font-weight: 600; color: var(--white); font-size: .85rem; }
        .float-badge-text span { color: var(--muted); font-size: .73rem; }

        .badge-1 { top: -24px; left: -32px; animation-delay: 0s; }
        .badge-2 { bottom: -20px; right: -28px; animation-delay: 1.5s; }

        /* ── STATS BAR ── */
        .stats-bar {
            padding: 40px 0;
            border-top: 1px solid rgba(255,255,255,.06);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }
        .stats-bar-inner {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }
        .stat-item {
            padding: 24px 32px;
            border-right: 1px solid rgba(255,255,255,.06);
            text-align: center;
        }
        .stat-item:last-child { border-right: none; }
        .stat-number {
            font-family: var(--font-display);
            font-size: 2.2rem; font-weight: 800;
            color: var(--teal); line-height: 1;
            margin-bottom: 6px;
        }
        .stat-number.gold { color: var(--gold); }
        .stat-desc { font-size: .85rem; color: var(--muted); }

        /* ── WHY US ── */
        #why-us { background: var(--navy-2); }
        .why-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 80px; align-items: start; }
        .why-sticky { position: sticky; top: 100px; }
        .why-headline {
            font-family: var(--font-display);
            font-size: clamp(2rem, 3vw, 2.8rem);
            font-weight: 800;
            line-height: 1.15; letter-spacing: -.025em;
            margin-bottom: 20px; color: var(--white);
        }
        .why-headline .teal { color: var(--teal); }
        .why-desc { color: var(--muted); line-height: 1.75; margin-bottom: 36px; font-size: .97rem; }

        .trust-chips { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 36px; }
        .trust-chip {
            display: flex; align-items: center; gap: 6px;
            background: rgba(0,229,195,.06);
            border: 1px solid rgba(0,229,195,.14);
            color: var(--teal); font-size: .8rem; font-weight: 500;
            padding: 6px 14px; border-radius: 8px;
        }

        .cta-group { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn-main {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            color: var(--navy); font-family: var(--font-display);
            font-weight: 700; font-size: .9rem;
            padding: 12px 24px; border-radius: 10px;
            text-decoration: none;
            transition: all .3s ease;
        }
        .btn-main:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,229,195,.3); }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--muted); font-family: var(--font-display);
            font-weight: 600; font-size: .9rem;
            padding: 12px 24px; border-radius: 10px;
            border: 1px solid rgba(255,255,255,.1);
            text-decoration: none;
            transition: all .3s ease;
        }
        .btn-secondary:hover { color: var(--white); border-color: rgba(255,255,255,.25); }

        /* Feature cards */
        .feature-cards { display: flex; flex-direction: column; gap: 20px; margin-bottom: 28px; }
        .feature-card {
            background: rgba(5,11,24,.6);
            border: 1px solid rgba(0,229,195,.1);
            border-radius: 20px;
            padding: 28px 28px;
            display: flex; align-items: flex-start; gap: 20px;
            transition: all .35s ease;
            position: relative; overflow: hidden;
        }
        .feature-card::before {
            content: ''; position: absolute;
            inset: 0; border-radius: 20px;
            background: linear-gradient(135deg, rgba(0,229,195,.04), transparent);
            opacity: 0; transition: opacity .35s;
        }
        .feature-card:hover { border-color: rgba(0,229,195,.3); transform: translateX(4px); }
        .feature-card:hover::before { opacity: 1; }
        .feature-card.highlight-card {
            border-color: rgba(0,229,195,.25);
            background: rgba(0,229,195,.04);
        }
        .feature-card.highlight-card::after {
            content: 'TOP FEATURE';
            position: absolute; top: 16px; right: 16px;
            background: var(--teal);
            color: var(--navy);
            font-family: var(--font-display);
            font-size: .65rem; font-weight: 700; letter-spacing: .1em;
            padding: 4px 10px; border-radius: 6px;
        }
        .fc-icon {
            flex-shrink: 0;
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .fc-icon.teal { background: rgba(0,229,195,.12); color: var(--teal); }
        .fc-icon.gold { background: rgba(255,181,71,.12); color: var(--gold); }
        .fc-icon.blue { background: rgba(99,179,237,.12); color: #63B3ED; }
        .fc-body h4 {
            font-family: var(--font-display);
            font-size: 1.05rem; font-weight: 700;
            color: var(--white); margin-bottom: 6px;
        }
        .fc-body p { color: var(--muted); font-size: .88rem; line-height: 1.65; }

        /* Process steps */
        .process-section {
            background: var(--navy-3);
            border: 1px solid rgba(255,255,255,.06);
            border-radius: 20px;
            padding: 28px;
        }
        .process-title {
            font-family: var(--font-display);
            font-size: .8rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .process-steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        .p-step { text-align: center; }
        .p-step-num {
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(0,229,195,.1);
            border: 1px solid rgba(0,229,195,.2);
            color: var(--teal);
            font-family: var(--font-display); font-size: .9rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px;
        }
        .p-step-title {
            font-family: var(--font-display);
            font-size: .85rem; font-weight: 700;
            color: var(--white); margin-bottom: 2px;
        }
        .p-step-sub { font-size: .73rem; color: var(--muted); }

        /* ── FEATURES SECTION ── */
        #features { background: var(--navy); }
        .features-header { text-align: center; margin-bottom: 64px; }
        .features-title {
            font-family: var(--font-display);
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 800; letter-spacing: -.025em;
            color: var(--white); margin-bottom: 16px;
        }
        .features-title .teal { color: var(--teal); }
        .features-sub { color: var(--muted); font-size: 1rem; max-width: 520px; margin: 0 auto; }

        .features-bento {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: auto auto;
            gap: 20px;
        }
        .bento-card {
            background: var(--card-bg);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 24px;
            padding: 32px;
            transition: all .35s ease;
            position: relative; overflow: hidden;
        }
        .bento-card:hover {
            border-color: rgba(0,229,195,.2);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0,0,0,.3), 0 0 0 1px rgba(0,229,195,.05);
        }
        .bento-card.wide { grid-column: span 2; }
        .bento-card.featured {
            background: linear-gradient(135deg, rgba(0,229,195,.08), rgba(0,229,195,.02));
            border-color: rgba(0,229,195,.2);
        }
        .bento-icon {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 20px;
        }
        .bento-icon.teal { background: rgba(0,229,195,.12); color: var(--teal); }
        .bento-icon.gold { background: rgba(255,181,71,.12); color: var(--gold); }
        .bento-icon.blue { background: rgba(99,179,237,.12); color: #63B3ED; }
        .bento-icon.purple { background: rgba(167,139,250,.12); color: #A78BFA; }
        .bento-icon.rose { background: rgba(251,113,133,.12); color: #FB7185; }
        .bento-card h3 {
            font-family: var(--font-display);
            font-size: 1.15rem; font-weight: 700;
            color: var(--white); margin-bottom: 10px;
        }
        .bento-card p { color: var(--muted); font-size: .88rem; line-height: 1.7; }
        .bento-card .bento-link {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--teal); font-size: .83rem; font-weight: 600;
            text-decoration: none; margin-top: 16px;
            transition: gap .2s;
        }
        .bento-card .bento-link:hover { gap: 10px; }

        /* Wide card visual */
        .bento-track-visual {
            margin-top: 24px;
            background: var(--navy-3);
            border-radius: 14px; overflow: hidden;
            height: 100px; position: relative;
        }
        .bento-track-visual::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(0,229,195,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,229,195,.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .track-bus-mini {
            position: absolute;
            top: 50%; transform: translateY(-50%);
            left: 30%;
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--teal);
            display: flex; align-items: center; justify-content: center;
            color: var(--navy); font-size: .8rem;
            box-shadow: 0 0 0 6px rgba(0,229,195,.15);
            animation: busMove2 5s ease-in-out infinite alternate;
        }
        @keyframes busMove2 { 0%{left:15%;} 100%{left:65%;} }

        /* ── FOOTER ── */
        footer {
            background: var(--navy-2);
            border-top: 1px solid rgba(255,255,255,.06);
            padding: 48px 0 32px;
        }
        .footer-inner {
            display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 16px;
        }
        .footer-copy { color: var(--muted); font-size: .85rem; }
        .footer-copy strong { color: var(--teal); }
        .footer-tagline {
            display: flex; align-items: center; gap: 8px;
            color: var(--muted); font-size: .82rem;
        }
        .footer-tagline span {
            width: 4px; height: 4px; border-radius: 50%;
            background: var(--teal); display: inline-block;
        }

        /* ── SCROLL TOP ── */
        .scroll-top {
            position: fixed; bottom: 28px; right: 28px;
            width: 44px; height: 44px; border-radius: 12px;
            background: var(--teal); color: var(--navy);
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; font-size: 1.1rem;
            z-index: 50; opacity: 0; transform: translateY(20px);
            transition: all .3s ease;
            box-shadow: 0 8px 24px rgba(0,229,195,.3);
        }
        .scroll-top.visible { opacity: 1; transform: translateY(0); }
        .scroll-top:hover { transform: translateY(-3px); }

        /* ── ANIMATIONS ── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .reveal {
            opacity: 0; transform: translateY(32px);
            transition: all .65s cubic-bezier(.22,1,.36,1);
        }
        .reveal.visible { opacity: 1; transform: none; }
        .reveal-delay-1 { transition-delay: .1s; }
        .reveal-delay-2 { transition-delay: .2s; }
        .reveal-delay-3 { transition-delay: .3s; }
        .reveal-delay-4 { transition-delay: .4s; }

        /* ── RESPONSIVE ── */
        @media (max-width: 991px) {
            .hero-grid { grid-template-columns: 1fr; gap: 48px; }
            .why-grid { grid-template-columns: 1fr; }
            .why-sticky { position: static; }
            .features-bento { grid-template-columns: 1fr 1fr; }
            .bento-card.wide { grid-column: span 2; }
            .stats-bar-inner { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 640px) {
            nav ul { display: none; }
            .features-bento { grid-template-columns: 1fr; }
            .bento-card.wide { grid-column: span 1; }
            .stats-bar-inner { grid-template-columns: repeat(2, 1fr); }
            .stat-item { padding: 16px; }
            .process-steps { grid-template-columns: repeat(2, 1fr); }
            .card-stats-row { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
</head>

<body>
<div class="bg-canvas"></div>

<!-- ── HEADER ── -->
<header id="header">
    <div class="header-inner container">
        <a href="/" class="logo">
            <div class="logo-icon"><i class="bi bi-bus-front-fill"></i></div>
            <span class="logo-text">EDU<span>RIDE</span></span>
        </a>
        <nav>
            <ul>
                <li><a href="#hero" class="active">Home</a></li>
                <li><a href="#why-us">Why Us</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            </ul>
        </nav>
        <a href="{{ route('login') }}" class="btn-nav">
            Get Started <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</header>

<!-- ── HERO ── -->
<section id="hero" class="section">
    <div class="container">
        <div class="hero-grid">
            <!-- Left -->
            <div>
                <div class="hero-eyebrow">
                    <i class="bi bi-lightning-charge-fill"></i>
                    {{ config('app.name') }} — Real-Time School Transport
                </div>
                <h1 class="hero-headline">
                    Smart, <span class="accent">Safe</span> &amp;<br>
                    <span class="accent-gold">Live</span> Student<br>
                    Transport.
                </h1>
                <p class="hero-sub">GPS-enabled live tracking, instant safety alerts, and seamless school–parent–driver communication. Know where your child is, always.</p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="btn-primary-hero">
                        Get Started Free <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn-ghost-hero">
                        <i class="bi bi-play-circle"></i> Explore Features
                    </a>
                </div>
            </div>

            <!-- Right: Live tracking card -->
            <div class="hero-visual">
                <div class="float-badge badge-1 reveal">
                    <div class="float-badge-icon teal"><i class="bi bi-shield-check-fill"></i></div>
                    <div class="float-badge-text">
                        <strong>Student On Board</strong>
                        <span>Pickup confirmed · 07:42 AM</span>
                    </div>
                </div>

                <div class="hero-map-card">
                    <div class="map-pulse">
                        <div class="live-badge"><div class="live-dot"></div> LIVE</div>
                        <div class="map-route"></div>
                        <div class="map-stop map-stop-a"></div>
                        <div class="map-stop map-stop-b"></div>
                        <div class="map-stop map-stop-c"></div>
                        <div class="map-bus"><i class="bi bi-bus-front-fill"></i></div>
                    </div>
                    <div class="card-stats-row">
                        <div class="mini-stat">
                            <div class="mini-stat-val teal">{{ $totalTrips ?? '248' }}+</div>
                            <div class="mini-stat-label">Total Trips</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-val gold">{{ $totalStudents ?? '1.2k' }}+</div>
                            <div class="mini-stat-label">Students Safe</div>
                        </div>
                        <div class="mini-stat">
                            <div class="mini-stat-val">{{ $totalVehicles ?? '32' }}</div>
                            <div class="mini-stat-label">Vehicles Active</div>
                        </div>
                    </div>
                </div>

                <div class="float-badge badge-2 reveal reveal-delay-2">
                    <div class="float-badge-icon gold"><i class="bi bi-bell-fill"></i></div>
                    <div class="float-badge-text">
                        <strong>Bus Arriving Soon</strong>
                        <span>~4 min away · Route 3B</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── STATS BAR ── -->
<div class="stats-bar">
    <div class="container">
        <div class="stats-bar-inner">
            <div class="stat-item reveal">
                <div class="stat-number">{{ $totalTrips ?? '248' }}+</div>
                <div class="stat-desc">Total Trips Completed</div>
            </div>
            <div class="stat-item reveal reveal-delay-1">
                <div class="stat-number gold">{{ $totalVehicles ?? '32' }}</div>
                <div class="stat-desc">Vehicles Registered</div>
            </div>
            <div class="stat-item reveal reveal-delay-2">
                <div class="stat-number">{{ $totalDrivers ?? '18' }}</div>
                <div class="stat-desc">Verified Drivers</div>
            </div>
            <div class="stat-item reveal reveal-delay-3">
                <div class="stat-number gold">{{ $totalStudents ?? '1.2k' }}+</div>
                <div class="stat-desc">Students Monitored</div>
            </div>
        </div>
    </div>
</div>

<!-- ── WHY US ── -->
<section id="why-us" class="section">
    <div class="container">
        <div class="why-grid">
            <!-- Left sticky -->
            <div class="why-sticky">
                <div class="section-tag reveal"><i class="bi bi-stars"></i> Our Difference</div>
                <h2 class="why-headline reveal">Why Schools &amp; Parents<br>Choose <span class="teal">EDURIDE</span></h2>
                <p class="why-desc reveal reveal-delay-1">EDURIDE bridges the gap between school administrators, parents, and drivers. From real-time GPS to instant notifications, every feature is built to keep students safe and parents informed.</p>
                <div class="trust-chips reveal reveal-delay-2">
                    <div class="trust-chip"><i class="bi bi-check-circle-fill"></i> GPS Verified</div>
                    <div class="trust-chip"><i class="bi bi-check-circle-fill"></i> Instant Alerts</div>
                    <div class="trust-chip"><i class="bi bi-check-circle-fill"></i> 24/7 Monitoring</div>
                    <div class="trust-chip"><i class="bi bi-check-circle-fill"></i> Emergency SOS</div>
                </div>
                <div class="cta-group reveal reveal-delay-3">
                    <a href="{{ route('login') }}" class="btn-main">Get Started <i class="bi bi-arrow-right"></i></a>
                    <a href="#features" class="btn-secondary">Explore Features</a>
                </div>
            </div>

            <!-- Right -->
            <div>
                <div class="feature-cards">
                    <div class="feature-card highlight-card reveal">
                        <div class="fc-icon teal"><i class="bi bi-geo-alt-fill"></i></div>
                        <div class="fc-body">
                            <h4>Live GPS Tracking</h4>
                            <p>Track every bus in real time with precise, second-by-second location updates on an interactive map. No delays, no blind spots.</p>
                        </div>
                    </div>
                    <div class="feature-card reveal reveal-delay-1">
                        <div class="fc-icon gold"><i class="bi bi-bell-fill"></i></div>
                        <div class="fc-body">
                            <h4>Instant Parent Alerts</h4>
                            <p>Push notifications the moment a bus approaches a pickup or drop-off point — parents always know exactly when to be ready.</p>
                        </div>
                    </div>
                    <div class="feature-card reveal reveal-delay-2">
                        <div class="fc-icon blue"><i class="bi bi-shield-fill-check"></i></div>
                        <div class="fc-body">
                            <h4>Student Safety Control</h4>
                            <p>Monitor routes, confirm student boarding, and respond to emergencies effectively with our integrated safety dashboard.</p>
                        </div>
                    </div>
                </div>

                <!-- Process -->
                <div class="process-section reveal reveal-delay-3">
                    <div class="process-title"><i class="bi bi-diagram-3-fill"></i> How It Works</div>
                    <div class="process-steps">
                        <div class="p-step">
                            <div class="p-step-num">1</div>
                            <div class="p-step-title">Assign Routes</div>
                            <div class="p-step-sub">Link drivers &amp; buses to routes</div>
                        </div>
                        <div class="p-step">
                            <div class="p-step-num">2</div>
                            <div class="p-step-title">Start Trip</div>
                            <div class="p-step-sub">Driver activates live GPS</div>
                        </div>
                        <div class="p-step">
                            <div class="p-step-num">3</div>
                            <div class="p-step-title">Track Live</div>
                            <div class="p-step-sub">Parents see real-time map</div>
                        </div>
                        <div class="p-step">
                            <div class="p-step-num">4</div>
                            <div class="p-step-title">Get Notified</div>
                            <div class="p-step-sub">Alerts on arrival / pickup</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── FEATURES BENTO ── -->
<section id="features" class="section">
    <div class="container">
        <div class="features-header">
            <div class="section-tag reveal"><i class="bi bi-grid-3x3-gap-fill"></i> Platform Features</div>
            <h2 class="features-title reveal">Everything You Need for<br><span class="teal">Safe School Transport</span></h2>
            <p class="features-sub reveal reveal-delay-1">A complete platform — from live GPS to driver management — built for modern schools.</p>
        </div>

        <div class="features-bento">
            <!-- Wide card: Live Tracking -->
            <div class="bento-card wide featured reveal">
                <div class="bento-icon teal"><i class="bi bi-geo-alt-fill"></i></div>
                <h3>Real-Time Bus Tracking</h3>
                <p>Watch buses move across the map live with sub-10 second refresh rates. Color-coded routes, stop markers, and speed indicators — all in one dashboard.</p>
                <div class="bento-track-visual">
                    <div class="track-bus-mini"><i class="bi bi-bus-front-fill"></i></div>
                </div>
                <a href="#" class="bento-link">Explore tracking <i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- Card: Notifications -->
            <div class="bento-card reveal reveal-delay-1">
                <div class="bento-icon gold"><i class="bi bi-bell-fill"></i></div>
                <h3>Smart Notifications</h3>
                <p>Automated alerts for bus arrivals, delays, route changes, and student pickup confirmations. Delivered via app push, SMS, or email.</p>
                <a href="#" class="bento-link">Learn more <i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- Card: Route Management -->
            <div class="bento-card reveal">
                <div class="bento-icon blue"><i class="bi bi-map-fill"></i></div>
                <h3>Route Management</h3>
                <p>Build, edit, and optimize routes with drag-and-drop simplicity. Assign stops, set schedules, and handle exceptions on the fly.</p>
                <a href="#" class="bento-link">See routes <i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- Card: Driver Management -->
            <div class="bento-card reveal reveal-delay-1">
                <div class="bento-icon purple"><i class="bi bi-person-badge-fill"></i></div>
                <h3>Driver Management</h3>
                <p>Onboard drivers, assign vehicles, track performance, and manage credentials from a single admin panel.</p>
                <a href="#" class="bento-link">Manage drivers <i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- Card: Analytics -->
            <div class="bento-card reveal reveal-delay-2">
                <div class="bento-icon rose"><i class="bi bi-bar-chart-fill"></i></div>
                <h3>Trip Analytics</h3>
                <p>Detailed reports on trip history, on-time rates, distance covered, and student attendance. Export as PDF or CSV.</p>
                <a href="#" class="bento-link">View reports <i class="bi bi-arrow-right"></i></a>
            </div>

            <!-- Card: Emergency SOS -->
            <div class="bento-card reveal reveal-delay-3">
                <div class="bento-icon teal"><i class="bi bi-shield-exclamation"></i></div>
                <h3>Emergency SOS</h3>
                <p>One-tap emergency alerts for drivers. Instantly notifies admins and parents with the bus location and driver status.</p>
                <a href="#" class="bento-link">Safety features <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer>
    <div class="container">
        <div class="footer-inner">
            <p class="footer-copy">© {{ date('Y') }} <strong>EDURIDE | IRERERO Academy</strong>. All Rights Reserved.</p>
            <div class="footer-tagline">
                Smart <span></span> Safe <span></span> Reliable School Transport
            </div>
        </div>
    </div>
</footer>

<!-- Scroll top -->
<a href="#" class="scroll-top" id="scrollTop"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS (keep existing) -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

<script>
// Reveal on scroll
const revealEls = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); } });
}, { threshold: 0.12 });
revealEls.forEach(el => observer.observe(el));

// Scroll top button
const scrollBtn = document.getElementById('scrollTop');
window.addEventListener('scroll', () => {
    scrollBtn.classList.toggle('visible', window.scrollY > 400);
});

// Header shadow on scroll
const header = document.getElementById('header');
window.addEventListener('scroll', () => {
    header.style.boxShadow = window.scrollY > 20
        ? '0 4px 40px rgba(0,0,0,.4)'
        : 'none';
});

// Active nav link
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('nav a');
window.addEventListener('scroll', () => {
    let current = '';
    sections.forEach(s => {
        if (window.scrollY >= s.offsetTop - 100) current = s.getAttribute('id');
    });
    navLinks.forEach(a => {
        a.classList.toggle('active', a.getAttribute('href') === '#' + current);
    });
});
</script>
</body>
</html>