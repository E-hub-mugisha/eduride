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
            --navy-4: #162038;
            --teal: #00E5C3;
            --teal-dim: #00B89A;
            --gold: #FFB547;
            --white: #F0F4FF;
            --muted: #7A8BAA;
            --danger: #FB7185;
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
            overflow: hidden;
            position: relative;
        }

        /* Background effects */
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

        .glow-a {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 229, 195, .11) 0%, transparent 65%);
            top: -200px;
            left: -200px;
            animation: driftA 20s ease-in-out infinite alternate;
        }

        .glow-b {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 181, 71, .07) 0%, transparent 65%);
            bottom: -150px;
            right: -150px;
            animation: driftB 24s ease-in-out infinite alternate;
        }

        @keyframes driftA {
            to {
                transform: translate(80px, 60px);
            }
        }

        @keyframes driftB {
            to {
                transform: translate(-60px, -50px);
            }
        }

        /* ── Login wrapper ── */
        .login-wrapper {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            max-width: 960px;
            min-height: 580px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0, 0, 0, .55), 0 0 0 1px rgba(0, 229, 195, .1);
            animation: wrapperIn .7s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes wrapperIn {
            from {
                opacity: 0;
                transform: translateY(28px) scale(.97);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /* ── Left panel (branding) ── */
        .login-panel-left {
            background: linear-gradient(160deg, var(--navy-3) 0%, var(--navy-2) 100%);
            border-right: 1px solid var(--border);
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 20% 80%, rgba(0, 229, 195, .08) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Logo */
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--teal), var(--teal-dim));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: 1.25rem;
        }

        .brand-name {
            font-family: var(--font-display);
            font-size: 1.45rem;
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--white);
        }

        .brand-name span {
            color: var(--teal);
        }

        /* Tagline block */
        .panel-tagline {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 0;
            padding: 40px 0;
        }

        .panel-tagline h2 {
            font-family: var(--font-display);
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -.025em;
            color: var(--white);
            margin-bottom: 14px;
        }

        .panel-tagline h2 .teal {
            color: var(--teal);
        }

        .panel-tagline p {
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.7;
            max-width: 280px;
            margin-bottom: 28px;
        }

        /* Feature pills */
        .feature-pills {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(0, 229, 195, .06);
            border: 1px solid rgba(0, 229, 195, .12);
            border-radius: 10px;
            padding: 10px 14px;
            transition: border-color .25s;
        }

        .feature-pill:hover {
            border-color: rgba(0, 229, 195, .28);
        }

        .feature-pill i {
            color: var(--teal);
            font-size: .95rem;
            flex-shrink: 0;
        }

        .feature-pill span {
            font-size: .82rem;
            color: var(--muted);
            font-weight: 500;
        }

        .feature-pill span strong {
            color: var(--white);
            font-weight: 600;
        }

        /* Panel footer */
        .panel-footer {
            font-size: .75rem;
            color: rgba(122, 139, 170, .55);
            position: relative;
            z-index: 1;
        }

        /* Decorative bus graphic */
        .deco-bus {
            position: absolute;
            bottom: 60px;
            right: -12px;
            font-size: 6rem;
            color: rgba(0, 229, 195, .06);
            line-height: 1;
            pointer-events: none;
            animation: floatBob 5s ease-in-out infinite;
        }

        @keyframes floatBob {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* ── Right panel (form) ── */
        .login-panel-right {
            background: var(--navy-2);
            padding: 52px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-head {
            margin-bottom: 36px;
        }

        .form-head h1 {
            font-family: var(--font-display);
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -.025em;
            color: var(--white);
            margin-bottom: 6px;
        }

        .form-head p {
            color: var(--muted);
            font-size: .88rem;
        }

        /* Error alert */
        .err-alert {
            background: rgba(251, 113, 133, .08);
            border: 1px solid rgba(251, 113, 133, .25);
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
            font-size: .83rem;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .err-alert li::before {
            content: '⚠';
            font-size: .8rem;
        }

        /* Form fields */
        .field-group {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: .04em;
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
            color: var(--muted);
            font-size: 1rem;
            pointer-events: none;
            transition: color .2s;
        }

        .field-input {
            width: 100%;
            background: var(--navy-3);
            border: 1px solid rgba(255, 255, 255, .08);
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
            color: rgba(122, 139, 170, .5);
        }

        .field-input:focus {
            border-color: var(--teal);
            background: rgba(0, 229, 195, .04);
            box-shadow: 0 0 0 3px rgba(0, 229, 195, .1);
        }

        .field-input:focus+.field-icon,
        .field-wrap:focus-within .field-icon {
            color: var(--teal);
        }

        .field-input.is-error {
            border-color: var(--danger);
        }

        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            font-size: 1rem;
            padding: 4px;
            transition: color .2s;
        }

        .toggle-pw:hover {
            color: var(--white);
        }

        /* Remember + forgot row */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            gap: 12px;
        }

        .custom-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .custom-check input[type=checkbox] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 1.5px solid rgba(255, 255, 255, .15);
            background: var(--navy-3);
            cursor: pointer;
            transition: all .2s;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .custom-check input[type=checkbox]:checked {
            background: var(--teal);
            border-color: var(--teal);
        }

        .custom-check input[type=checkbox]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--navy);
            font-size: .65rem;
            font-weight: 700;
        }

        .custom-check span {
            font-size: .82rem;
            color: var(--muted);
        }

        .forgot-link {
            font-size: .82rem;
            color: var(--teal);
            text-decoration: none;
            font-weight: 600;
            transition: color .2s;
            white-space: nowrap;
        }

        .forgot-link:hover {
            color: var(--white);
        }

        /* Submit button */
        .btn-signin {
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
            box-shadow: 0 4px 20px rgba(0, 229, 195, .25);
            margin-bottom: 24px;
        }

        .btn-signin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 36px rgba(0, 229, 195, .38);
        }

        .btn-signin:active {
            transform: scale(.98);
        }

        /* Register row */
        .register-row {
            text-align: center;
            font-size: .85rem;
            color: var(--muted);
        }

        .register-row a {
            color: var(--teal);
            font-weight: 600;
            text-decoration: none;
            margin-left: 5px;
            transition: color .2s;
        }

        .register-row a:hover {
            color: var(--white);
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
            font-size: .75rem;
            white-space: nowrap;
        }

        /* ── Responsive ── */
        @media (max-width: 700px) {
            body {
                padding: 16px;
                align-items: flex-start;
                padding-top: 40px;
            }

            .login-wrapper {
                grid-template-columns: 1fr;
                min-height: auto;
                border-radius: 20px;
            }

            .login-panel-left {
                display: none;
            }

            .login-panel-right {
                padding: 36px 28px;
            }
        }
    </style>
</head>

<body>
    <div class="glow-a"></div>
    <div class="glow-b"></div>

    <div class="login-wrapper">

        <!-- ── Left branding panel ── -->
        <div class="login-panel-left">
            <a href="/" class="brand-logo">
                <div class="brand-icon"><i class="bi bi-bus-front-fill"></i></div>
                <span class="brand-name">EDU<span>RIDE</span></span>
            </a>

            <div class="panel-tagline">
                <h2>Safe Routes,<br><span class="teal">Peace of Mind.</span></h2>
                <p>Real-time GPS tracking, instant alerts, and complete visibility over every student's journey.</p>

                <div class="feature-pills">
                    <div class="feature-pill">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span><strong>Live GPS Tracking</strong> — second-by-second</span>
                    </div>
                    <div class="feature-pill">
                        <i class="bi bi-bell-fill"></i>
                        <span><strong>Instant Alerts</strong> — pickup &amp; drop-off</span>
                    </div>
                    <div class="feature-pill">
                        <i class="bi bi-shield-fill-check"></i>
                        <span><strong>Student Safety</strong> — always verified</span>
                    </div>
                    <div class="feature-pill">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span><strong>Trip Reports</strong> — detailed analytics</span>
                    </div>
                </div>
            </div>

            <div class="panel-footer">© {{ date('Y') }} IRERERO Academy · Smart School Transport</div>
            <div class="deco-bus"><i class="bi bi-bus-front-fill"></i></div>
        </div>

        <!-- ── Right form panel ── -->
        <div class="login-panel-right">

            <div class="form-head">
                <h1>Welcome back 👋</h1>
                <p>Sign in to your EDURIDE account to continue</p>
            </div>

            @if ($errors->any())
            <div class="err-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
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
                </div>

                <!-- Password -->
                <div class="field-group">
                    <label class="field-label" for="passwordField">Password</label>
                    <div class="field-wrap">
                        <input
                            type="password"
                            class="field-input {{ $errors->has('password') ? 'is-error' : '' }}"
                            name="password"
                            id="passwordField"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required>
                        <i class="bi bi-lock field-icon"></i>
                        <button type="button" class="toggle-pw" id="togglePassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember + Forgot -->
                <div class="form-options">
                    <label class="custom-check">
                        <input type="checkbox" name="remember" id="remember_me">
                        <span>Remember this device</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-signin">
                    Sign In <i class="bi bi-arrow-right"></i>
                </button>

                <div class="divider"><span>New to EDURIDE?</span></div>

                <div class="register-row">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one free</a>
                </div>
            </form>
        </div>
    </div>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script>
        const toggleBtn = document.getElementById('togglePassword');
        const pwField = document.getElementById('passwordField');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleBtn.addEventListener('click', () => {
            const isHidden = pwField.type === 'password';
            pwField.type = isHidden ? 'text' : 'password';
            toggleIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    </script>

</body>

</html>