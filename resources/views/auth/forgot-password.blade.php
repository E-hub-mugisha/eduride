<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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
            --border: rgba(0, 229, 195, .14);
            --font-display: 'Syne', sans-serif;
            --font-body: 'DM Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--navy);
            font-family: var(--font-body);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Background grid */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image:
                linear-gradient(rgba(0, 229, 195, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 229, 195, .03) 1px, transparent 1px);
            background-size: 52px 52px;
        }

        /* Glow orbs */
        .glow-a {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            width: 650px;
            height: 650px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 229, 195, .1) 0%, transparent 65%);
            top: -200px;
            left: -180px;
            animation: driftA 20s ease-in-out infinite alternate;
        }

        .glow-b {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 181, 71, .07) 0%, transparent 65%);
            bottom: -120px;
            right: -120px;
            animation: driftB 24s ease-in-out infinite alternate;
        }

        @keyframes driftA {
            to {
                transform: translate(70px, 60px);
            }
        }

        @keyframes driftB {
            to {
                transform: translate(-60px, -50px);
            }
        }

        /* ── Card ── */
        .fp-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            background: var(--navy-2);
            border: 1px solid rgba(0, 229, 195, .1);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0, 0, 0, .55), 0 0 0 1px rgba(0, 229, 195, .05);
            animation: cardIn .7s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(32px) scale(.97);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* Top accent bar */
        .fp-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--teal), var(--gold));
        }

        /* ── Card header ── */
        .fp-header {
            padding: 44px 44px 32px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            position: relative;
        }

        /* Back link */
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            font-size: .8rem;
            font-weight: 500;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, .07);
            background: var(--navy-3);
            transition: all .25s;
        }

        .back-link:hover {
            color: var(--white);
            border-color: rgba(255, 255, 255, .18);
        }

        /* Brand */
        .fp-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 28px;
        }

        .fp-brand-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: 1.1rem;
        }

        .fp-brand-name {
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--white);
        }

        .fp-brand-name span {
            color: var(--teal);
        }

        /* Icon circle */
        .fp-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(0, 229, 195, .08);
            border: 1.5px solid rgba(0, 229, 195, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            position: relative;
            animation: iconPulse 3s ease-in-out infinite;
        }

        .fp-icon-wrap i {
            font-size: 1.8rem;
            color: var(--teal);
        }

        .fp-icon-wrap::after {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1px solid rgba(0, 229, 195, .1);
            animation: ringExpand 3s ease-in-out infinite;
        }

        @keyframes iconPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(0, 229, 195, .2);
            }

            50% {
                box-shadow: 0 0 0 12px rgba(0, 229, 195, 0);
            }
        }

        @keyframes ringExpand {

            0%,
            100% {
                transform: scale(1);
                opacity: .6;
            }

            50% {
                transform: scale(1.1);
                opacity: 0;
            }
        }

        .fp-header h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -.025em;
            color: var(--white);
            margin-bottom: 10px;
        }

        .fp-header p {
            color: var(--muted);
            font-size: .87rem;
            line-height: 1.65;
            max-width: 340px;
            margin: 0 auto;
        }

        /* ── Card body ── */
        .fp-body {
            padding: 32px 44px 40px;
        }

        /* Success banner */
        .success-banner {
            background: rgba(52, 211, 153, .08);
            border: 1px solid rgba(52, 211, 153, .22);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .success-banner i {
            color: var(--success);
            font-size: 1rem;
            margin-top: 1px;
            flex-shrink: 0;
        }

        .success-banner span {
            color: var(--success);
            font-size: .83rem;
            line-height: 1.55;
        }

        /* Error alert */
        .err-alert {
            background: rgba(251, 113, 133, .08);
            border: 1px solid rgba(251, 113, 133, .22);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
        }

        .err-alert ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .err-alert li {
            color: var(--danger);
            font-size: .82rem;
            display: flex;
            align-items: flex-start;
            gap: 7px;
            padding: 2px 0;
        }

        .err-alert li i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Field */
        .field-group {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(122, 139, 170, .5);
            font-size: .95rem;
            pointer-events: none;
            transition: color .2s;
        }

        .field-wrap:focus-within .field-icon {
            color: var(--teal);
        }

        .field-input {
            width: 100%;
            background: var(--navy-3);
            border: 1px solid rgba(255, 255, 255, .07);
            border-radius: 12px;
            color: var(--white);
            font-family: var(--font-body);
            font-size: .92rem;
            padding: 13px 44px 13px 42px;
            outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
            -webkit-appearance: none;
        }

        .field-input::placeholder {
            color: rgba(122, 139, 170, .4);
        }

        .field-input:focus {
            border-color: var(--teal);
            background: rgba(0, 229, 195, .03);
            box-shadow: 0 0 0 3px rgba(0, 229, 195, .09);
        }

        .field-input.is-error {
            border-color: rgba(251, 113, 133, .5);
        }

        /* Hint text */
        .field-hint {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: .76rem;
            color: var(--muted);
        }

        .field-hint i {
            font-size: .8rem;
            color: rgba(0, 229, 195, .6);
        }

        /* Submit button */
        .btn-send {
            width: 100%;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            border: none;
            border-radius: 12px;
            color: var(--navy);
            font-family: var(--font-display);
            font-size: .95rem;
            font-weight: 700;
            padding: 14px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all .3s ease;
            box-shadow: 0 4px 20px rgba(0, 229, 195, .22);
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .btn-send::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .1), transparent);
            transform: translateX(-100%);
            transition: transform .5s ease;
        }

        .btn-send:hover::before {
            transform: translateX(100%);
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 36px rgba(0, 229, 195, .36);
        }

        .btn-send:active {
            transform: scale(.98);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, .06);
        }

        .divider span {
            color: var(--muted);
            font-size: .73rem;
            white-space: nowrap;
        }

        /* Bottom links */
        .fp-links {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .fp-link-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: .83rem;
            color: var(--muted);
        }

        .fp-link-row a {
            color: var(--teal);
            font-weight: 600;
            text-decoration: none;
            transition: color .2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .fp-link-row a:hover {
            color: var(--white);
        }

        /* Help card */
        .help-card {
            margin-top: 20px;
            background: var(--navy-3);
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .help-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            flex-shrink: 0;
            background: rgba(255, 181, 71, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold);
            font-size: .95rem;
        }

        .help-text strong {
            display: block;
            font-size: .82rem;
            color: var(--white);
            font-weight: 600;
            margin-bottom: 2px;
        }

        .help-text span {
            font-size: .76rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .help-text a {
            color: var(--teal);
            text-decoration: none;
            font-weight: 600;
        }

        .help-text a:hover {
            color: var(--white);
        }

        /* Footer */
        .fp-footer {
            text-align: center;
            padding: 0 44px 24px;
            font-size: .72rem;
            color: rgba(122, 139, 170, .4);
        }

        /* Responsive */
        @media (max-width: 540px) {
            body {
                padding: 16px;
            }

            .fp-header {
                padding: 40px 24px 24px;
            }

            .fp-body {
                padding: 24px 24px 32px;
            }

            .fp-footer {
                padding: 0 24px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="glow-a"></div>
    <div class="glow-b"></div>

    <div class="fp-card">

        <!-- ── Header ── -->
        <div class="fp-header">
            <a href="{{ route('login') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <a href="/" class="fp-brand">
                <div class="fp-brand-icon"><i class="bi bi-bus-front-fill"></i></div>
                <span class="fp-brand-name">EDU<span>RIDE</span></span>
            </a>

            <div class="fp-icon-wrap">
                <i class="bi bi-key-fill"></i>
            </div>

            <h1>Forgot your password?</h1>
            <p>No problem. Enter your email address and we'll send you a secure reset link right away.</p>
        </div>

        <!-- ── Body ── -->
        <div class="fp-body">

            @if (session('status'))
            <div class="success-banner">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('status') }} Check your inbox — the link expires in 60 minutes.</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="err-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <div class="field-wrap">
                        <input
                            type="email"
                            class="field-input {{ $errors->has('email') ? 'is-error' : '' }}"
                            name="email"
                            id="email"
                            placeholder="you@school.edu"
                            value="{{ old('email') }}"
                            autocomplete="email"
                            required>
                        <i class="bi bi-envelope field-icon"></i>
                    </div>
                    <div class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        Use the email address linked to your EDURIDE account.
                    </div>
                </div>

                <button type="submit" class="btn-send">
                    <i class="bi bi-send-fill"></i> Send Reset Link
                </button>

                <div class="divider"><span>Or go back to</span></div>

                <div class="fp-links">
                    <div class="fp-link-row">
                        <i class="bi bi-box-arrow-in-right" style="color:var(--muted);font-size:.9rem;"></i>
                        Remember your password?
                        <a href="{{ route('login') }}">Sign in <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="fp-link-row">
                        <i class="bi bi-person-plus" style="color:var(--muted);font-size:.9rem;"></i>
                        New to EDURIDE?
                        <a href="{{ route('register') }}">Create account <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>

                <div class="help-card">
                    <div class="help-icon"><i class="bi bi-question-circle-fill"></i></div>
                    <div class="help-text">
                        <strong>Still having trouble?</strong>
                        <span>Contact your school administrator or reach us at <a href="mailto:support@eduride.rw">support@eduride.rw</a></span>
                    </div>
                </div>

            </form>
        </div>

        <div class="fp-footer">
            © {{ date('Y') }} IRERERO Academy · Smart School Transport
        </div>

    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>


</body>

</html>