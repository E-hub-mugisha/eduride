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
            overflow-x: hidden;
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
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 229, 195, .1) 0%, transparent 65%);
            top: -250px;
            right: -150px;
            animation: driftA 22s ease-in-out infinite alternate;
        }

        .glow-b {
            position: fixed;
            z-index: 0;
            pointer-events: none;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 181, 71, .07) 0%, transparent 65%);
            bottom: -100px;
            left: -100px;
            animation: driftB 26s ease-in-out infinite alternate;
        }

        @keyframes driftA {
            to {
                transform: translate(-60px, 80px);
            }
        }

        @keyframes driftB {
            to {
                transform: translate(70px, -50px);
            }
        }

        /* ── Wrapper ── */
        .reg-wrapper {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1.35fr;
            width: 100%;
            max-width: 1020px;
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

        /* ── Left panel ── */
        .panel-left {
            background: linear-gradient(160deg, var(--navy-3) 0%, var(--navy-2) 100%);
            border-right: 1px solid var(--border);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 80% 20%, rgba(0, 229, 195, .08) 0%, transparent 60%);
            pointer-events: none;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            position: relative;
            z-index: 1;
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

        .panel-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 36px 0;
            position: relative;
            z-index: 1;
        }

        .panel-body h2 {
            font-family: var(--font-display);
            font-size: 1.7rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -.025em;
            color: var(--white);
            margin-bottom: 12px;
        }

        .panel-body h2 .teal {
            color: var(--teal);
        }

        .panel-body .sub {
            color: var(--muted);
            font-size: .88rem;
            line-height: 1.7;
            max-width: 270px;
            margin-bottom: 32px;
        }

        /* Steps */
        .steps {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 14px 0;
            position: relative;
        }

        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 17px;
            top: 44px;
            width: 1px;
            height: calc(100% - 14px);
            background: rgba(0, 229, 195, .15);
        }

        .step-num {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
            background: rgba(0, 229, 195, .1);
            border: 1px solid rgba(0, 229, 195, .22);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--teal);
            font-family: var(--font-display);
            font-size: .85rem;
            font-weight: 700;
        }

        .step-info strong {
            display: block;
            font-size: .88rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 2px;
        }

        .step-info span {
            font-size: .78rem;
            color: var(--muted);
        }

        /* Testimonial-style trust card */
        .trust-card {
            background: rgba(0, 229, 195, .05);
            border: 1px solid rgba(0, 229, 195, .14);
            border-radius: 16px;
            padding: 18px 20px;
            margin-top: 28px;
        }

        .trust-stars {
            color: var(--gold);
            font-size: .8rem;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .trust-quote {
            color: var(--muted);
            font-size: .8rem;
            line-height: 1.6;
            margin-bottom: 10px;
            font-style: italic;
        }

        .trust-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .trust-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--gold));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            font-size: .75rem;
            font-weight: 700;
        }

        .trust-meta strong {
            display: block;
            font-size: .78rem;
            color: var(--white);
            font-weight: 600;
        }

        .trust-meta span {
            font-size: .72rem;
            color: var(--muted);
        }

        .panel-footer {
            font-size: .73rem;
            color: rgba(122, 139, 170, .45);
            position: relative;
            z-index: 1;
        }

        .deco-bg {
            position: absolute;
            bottom: 30px;
            left: -20px;
            font-size: 8rem;
            color: rgba(0, 229, 195, .04);
            pointer-events: none;
            line-height: 1;
            animation: floatBob 6s ease-in-out infinite;
        }

        @keyframes floatBob {

            0%,
            100% {
                transform: translateY(0) rotate(-8deg);
            }

            50% {
                transform: translateY(-12px) rotate(-8deg);
            }
        }

        /* ── Right form panel ── */
        .panel-right {
            background: var(--navy-2);
            padding: 48px 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }

        .form-head {
            margin-bottom: 28px;
        }

        .form-head h1 {
            font-family: var(--font-display);
            font-size: 1.55rem;
            font-weight: 800;
            letter-spacing: -.025em;
            color: var(--white);
            margin-bottom: 5px;
        }

        .form-head p {
            color: var(--muted);
            font-size: .87rem;
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

        /* Two-column row */
        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* Field */
        .field-group {
            margin-bottom: 18px;
        }

        .field-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .field-label .req {
            color: var(--teal);
            font-size: .7rem;
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
            border-radius: 11px;
            color: var(--white);
            font-family: var(--font-body);
            font-size: .9rem;
            padding: 12px 42px 12px 40px;
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

        .field-input.is-error:focus {
            box-shadow: 0 0 0 3px rgba(251, 113, 133, .1);
        }

        select.field-input {
            cursor: pointer;
        }

        select.field-input option {
            background: var(--navy-3);
            color: var(--white);
        }

        .toggle-pw {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            font-size: .95rem;
            padding: 4px;
            transition: color .2s;
            line-height: 1;
        }

        .toggle-pw:hover {
            color: var(--white);
        }

        /* Password strength */
        .pw-strength {
            margin-top: 8px;
        }

        .pw-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }

        .pw-bar {
            flex: 1;
            height: 3px;
            border-radius: 2px;
            background: rgba(255, 255, 255, .08);
            transition: background .3s;
        }

        .pw-bar.weak {
            background: var(--danger);
        }

        .pw-bar.fair {
            background: var(--gold);
        }

        .pw-bar.good {
            background: #63B3ED;
        }

        .pw-bar.strong {
            background: var(--success);
        }

        .pw-label {
            font-size: .72rem;
            color: var(--muted);
        }

        /* Terms */
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 22px;
            margin-top: 4px;
        }

        .terms-row input[type=checkbox] {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            min-width: 18px;
            border-radius: 6px;
            border: 1.5px solid rgba(255, 255, 255, .14);
            background: var(--navy-3);
            cursor: pointer;
            transition: all .2s;
            position: relative;
            margin-top: 1px;
        }

        .terms-row input[type=checkbox]:checked {
            background: var(--teal);
            border-color: var(--teal);
        }

        .terms-row input[type=checkbox]:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--navy);
            font-size: .63rem;
            font-weight: 700;
        }

        .terms-text {
            font-size: .8rem;
            color: var(--muted);
            line-height: 1.5;
        }

        .terms-text a {
            color: var(--teal);
            text-decoration: none;
            font-weight: 600;
        }

        .terms-text a:hover {
            color: var(--white);
        }

        /* Submit */
        .btn-register {
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
            margin-bottom: 20px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 36px rgba(0, 229, 195, .36);
        }

        .btn-register:active {
            transform: scale(.98);
        }

        .login-row {
            text-align: center;
            font-size: .84rem;
            color: var(--muted);
        }

        .login-row a {
            color: var(--teal);
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
            transition: color .2s;
        }

        .login-row a:hover {
            color: var(--white);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
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

        /* Role selector tabs */
        .role-tabs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }

        .role-tab {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            background: var(--navy-3);
            border: 1.5px solid rgba(255, 255, 255, .07);
            border-radius: 11px;
            padding: 12px 8px;
            cursor: pointer;
            transition: all .25s;
            position: relative;
        }

        .role-tab input[type=radio] {
            display: none;
        }

        .role-tab i {
            font-size: 1.2rem;
            color: var(--muted);
            transition: color .25s;
        }

        .role-tab span {
            font-size: .75rem;
            font-weight: 600;
            color: var(--muted);
            transition: color .25s;
            font-family: var(--font-display);
        }

        .role-tab:has(input:checked) {
            border-color: var(--teal);
            background: rgba(0, 229, 195, .07);
        }

        .role-tab:has(input:checked) i,
        .role-tab:has(input:checked) span {
            color: var(--teal);
        }

        .role-tab:hover:not(:has(input:checked)) {
            border-color: rgba(255, 255, 255, .15);
        }

        /* Responsive */
        @media (max-width: 780px) {
            body {
                padding: 16px;
                align-items: flex-start;
                padding-top: 32px;
            }

            .reg-wrapper {
                grid-template-columns: 1fr;
            }

            .panel-left {
                display: none;
            }

            .panel-right {
                padding: 36px 24px;
            }

            .field-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="glow-a"></div>
    <div class="glow-b"></div>

    <div class="reg-wrapper">

        <!-- ── Left panel ── -->
        <div class="panel-left">
            <a href="/" class="brand-logo">
                <div class="brand-icon"><i class="bi bi-bus-front-fill"></i></div>
                <span class="brand-name">EDU<span>RIDE</span></span>
            </a>

            <div class="panel-body">
                <h2>Join the<br><span class="teal">EDURIDE</span><br>Platform</h2>
                <p class="sub">Set up your account in minutes and start managing school transport smarter, safer, and faster.</p>

                <div class="steps">
                    <div class="step">
                        <div class="step-num">1</div>
                        <div class="step-info">
                            <strong>Create Your Account</strong>
                            <span>Fill in your details below</span>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <div class="step-info">
                            <strong>Set Up Your Role</strong>
                            <span>Admin, driver, or parent access</span>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <div class="step-info">
                            <strong>Go Live Instantly</strong>
                            <span>Start tracking from day one</span>
                        </div>
                    </div>
                </div>

                <div class="trust-card">
                    <div class="trust-stars">★★★★★</div>
                    <p class="trust-quote">"EDURIDE transformed how we manage our 30-bus fleet. Parents love the real-time updates."</p>
                    <div class="trust-author">
                        <div class="trust-avatar">MK</div>
                        <div class="trust-meta">
                            <strong>Mr. Mugisha K.</strong>
                            <span>School Director, IRERERO Academy</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel-footer">© {{ date('Y') }} IRERERO Academy · Smart School Transport</div>
            <div class="deco-bg"><i class="bi bi-shield-check"></i></div>
        </div>

        <!-- ── Right form panel ── -->
        <div class="panel-right">

            <div class="form-head">
                <h1>Create your account ✨</h1>
                <p>Start your journey with EDURIDE — it only takes a minute.</p>
            </div>

            @if ($errors->any())
            <div class="err-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Role selector --}}
                <div class="field-group">
                    <div class="field-label">I am registering as <span class="req">*</span></div>
                    <div class="role-tabs">
                        <label class="role-tab">
                            <input type="radio" name="role" value="admin" {{ old('role','admin')==='admin' ? 'checked' : '' }}>
                            <i class="bi bi-person-gear"></i>
                            <span>Admin</span>
                        </label>
                        <label class="role-tab">
                            <input type="radio" name="role" value="driver" {{ old('role')==='driver' ? 'checked' : '' }}>
                            <i class="bi bi-person-badge"></i>
                            <span>Driver</span>
                        </label>
                        <label class="role-tab">
                            <input type="radio" name="role" value="parent" {{ old('role')==='parent' ? 'checked' : '' }}>
                            <i class="bi bi-people"></i>
                            <span>Parent</span>
                        </label>
                    </div>
                </div>

                {{-- Name row --}}
                <div class="field-group">
                    <label class="field-label" for="name">Name <span class="req">*</span></label>
                    <div class="field-wrap">
                        <input type="text" class="field-input {{ $errors->has('name') ? 'is-error' : '' }}"
                            name="name" id="name"
                            placeholder="Jean Mugisha" value="{{ old('name') }}"
                            autocomplete="given-name" required>
                        <i class="bi bi-person field-icon"></i>
                    </div>
                </div>

                {{-- Email --}}
                <div class="field-group">
                    <label class="field-label" for="email">Email Address <span class="req">*</span></label>
                    <div class="field-wrap">
                        <input type="email" class="field-input {{ $errors->has('email') ? 'is-error' : '' }}"
                            name="email" id="email"
                            placeholder="you@school.edu"
                            value="{{ old('email') }}"
                            autocomplete="email" required>
                        <i class="bi bi-envelope field-icon"></i>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="field-group">
                    <label class="field-label" for="phone">Phone Number</label>
                    <div class="field-wrap">
                        <input type="tel" class="field-input {{ $errors->has('phone') ? 'is-error' : '' }}"
                            name="phone" id="phone"
                            placeholder="+250 7XX XXX XXX"
                            value="{{ old('phone') }}"
                            autocomplete="tel">
                        <i class="bi bi-telephone field-icon"></i>
                    </div>
                </div>

                {{-- Password row --}}
                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label" for="password">Password <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="password" class="field-input {{ $errors->has('password') ? 'is-error' : '' }}"
                                name="password" id="password"
                                placeholder="Min. 8 characters"
                                autocomplete="new-password" required>
                            <i class="bi bi-lock field-icon"></i>
                            <button type="button" class="toggle-pw" data-target="password" aria-label="Toggle password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        <div class="pw-strength" id="pwStrength" style="display:none;">
                            <div class="pw-bars">
                                <div class="pw-bar" id="bar1"></div>
                                <div class="pw-bar" id="bar2"></div>
                                <div class="pw-bar" id="bar3"></div>
                                <div class="pw-bar" id="bar4"></div>
                            </div>
                            <div class="pw-label" id="pwLabel">Too short</div>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="password_confirmation">Confirm Password <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="password" class="field-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}"
                                name="password_confirmation" id="password_confirmation"
                                placeholder="Repeat password"
                                autocomplete="new-password" required>
                            <i class="bi bi-lock-fill field-icon"></i>
                            <button type="button" class="toggle-pw" data-target="password_confirmation" aria-label="Toggle confirm password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Terms --}}
                <div class="terms-row">
                    <input type="checkbox" name="terms" id="terms" required {{ old('terms') ? 'checked' : '' }}>
                    <label class="terms-text" for="terms">
                        I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>. I understand my data will be used to manage student transport safely.
                    </label>
                </div>

                <button type="submit" class="btn-register">
                    Create Account <i class="bi bi-arrow-right"></i>
                </button>

                <div class="divider"><span>Already have an account?</span></div>

                <div class="login-row">
                    Already registered?
                    <a href="{{ route('login') }}">Sign in instead</a>
                </div>

            </form>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script>
        // ── Toggle password visibility ──
        document.querySelectorAll('.toggle-pw').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = document.getElementById(btn.dataset.target);
                const icon = btn.querySelector('i');
                const isHidden = target.type === 'password';
                target.type = isHidden ? 'text' : 'password';
                icon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });

        // ── Password strength meter ──
        const pwField = document.getElementById('password');
        const strength = document.getElementById('pwStrength');
        const bars = [document.getElementById('bar1'), document.getElementById('bar2'),
            document.getElementById('bar3'), document.getElementById('bar4')
        ];
        const pwLabel = document.getElementById('pwLabel');

        const levels = [{
                label: 'Too short',
                cls: 'weak',
                active: 1
            },
            {
                label: 'Weak',
                cls: 'weak',
                active: 1
            },
            {
                label: 'Fair',
                cls: 'fair',
                active: 2
            },
            {
                label: 'Good',
                cls: 'good',
                active: 3
            },
            {
                label: 'Strong 🔒',
                cls: 'strong',
                active: 4
            },
        ];

        function scorePassword(pw) {
            if (pw.length < 6) return 0;
            let score = 1;
            if (pw.length >= 8) score++;
            if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
            if (/\d/.test(pw) && /[^A-Za-z0-9]/.test(pw)) score++;
            return score;
        }

        pwField.addEventListener('input', () => {
            const val = pwField.value;
            if (!val) {
                strength.style.display = 'none';
                return;
            }
            strength.style.display = 'block';

            const s = scorePassword(val);
            const lvl = levels[Math.min(s, 4)];

            bars.forEach((b, i) => {
                b.className = 'pw-bar';
                if (i < lvl.active) b.classList.add(lvl.cls);
            });
            pwLabel.textContent = lvl.label;
        });

        // ── Confirm password match indicator ──
        const confirmField = document.getElementById('password_confirmation');
        confirmField.addEventListener('input', () => {
            const match = confirmField.value === pwField.value && confirmField.value !== '';
            confirmField.style.borderColor = confirmField.value ?
                (match ? 'var(--success)' : 'rgba(251,113,133,.5)') :
                '';
        });
    </script>

</body>

</html>