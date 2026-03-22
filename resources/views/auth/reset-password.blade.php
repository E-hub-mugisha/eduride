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
            --warn: #FBBF24;
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

        /* Grid texture */
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
            background: radial-gradient(circle, rgba(0, 229, 195, .09) 0%, transparent 65%);
            bottom: -200px;
            left: -180px;
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
            top: -100px;
            right: -100px;
            animation: driftB 26s ease-in-out infinite alternate;
        }

        @keyframes driftA {
            to {
                transform: translate(80px, -60px);
            }
        }

        @keyframes driftB {
            to {
                transform: translate(-70px, 60px);
            }
        }

        /* ── Card ── */
        .rp-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
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

        /* Accent top bar */
        .rp-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--teal), var(--gold));
        }

        /* ── Header ── */
        .rp-header {
            padding: 44px 44px 28px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            position: relative;
        }

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

        .rp-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: 28px;
        }

        .rp-brand-icon {
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

        .rp-brand-name {
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--white);
        }

        .rp-brand-name span {
            color: var(--teal);
        }

        /* Shield icon */
        .rp-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(0, 229, 195, .08);
            border: 1.5px solid rgba(0, 229, 195, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: iconPulse 3s ease-in-out infinite;
            position: relative;
        }

        .rp-icon-wrap i {
            font-size: 1.8rem;
            color: var(--teal);
        }

        .rp-icon-wrap::after {
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
                opacity: .5;
            }

            50% {
                transform: scale(1.12);
                opacity: 0;
            }
        }

        .rp-header h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -.025em;
            color: var(--white);
            margin-bottom: 8px;
        }

        .rp-header p {
            color: var(--muted);
            font-size: .87rem;
            line-height: 1.65;
            max-width: 340px;
            margin: 0 auto;
        }

        /* ── Body ── */
        .rp-body {
            padding: 32px 44px 40px;
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

        /* Fields */
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
            padding: 13px 46px 13px 42px;
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

        /* Read-only email field */
        .field-input[readonly] {
            opacity: .65;
            cursor: not-allowed;
        }

        .field-input[readonly]:focus {
            border-color: rgba(255, 255, 255, .07);
            background: var(--navy-3);
            box-shadow: none;
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

        /* Password strength meter */
        .pw-strength {
            margin-top: 8px;
        }

        .pw-bars {
            display: flex;
            gap: 4px;
            margin-bottom: 5px;
        }

        .pw-bar {
            flex: 1;
            height: 3px;
            border-radius: 2px;
            background: rgba(255, 255, 255, .07);
            transition: background .3s;
        }

        .pw-bar.weak {
            background: var(--danger);
        }

        .pw-bar.fair {
            background: var(--warn);
        }

        .pw-bar.good {
            background: #63B3ED;
        }

        .pw-bar.strong {
            background: var(--success);
        }

        .pw-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .pw-label {
            font-size: .72rem;
            color: var(--muted);
        }

        .pw-label.weak {
            color: var(--danger);
        }

        .pw-label.fair {
            color: var(--warn);
        }

        .pw-label.good {
            color: #63B3ED;
        }

        .pw-label.strong {
            color: var(--success);
        }

        .pw-rules {
            font-size: .7rem;
            color: rgba(122, 139, 170, .5);
        }

        /* Requirements checklist */
        .pw-reqs {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .pw-req {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .75rem;
            color: var(--muted);
            transition: color .25s;
        }

        .pw-req i {
            font-size: .75rem;
            transition: color .25s;
        }

        .pw-req.met {
            color: var(--success);
        }

        .pw-req.met i {
            color: var(--success);
        }

        .pw-req:not(.met) i {
            color: rgba(122, 139, 170, .3);
        }

        /* Match indicator */
        .match-hint {
            margin-top: 7px;
            font-size: .75rem;
            display: flex;
            align-items: center;
            gap: 6px;
            opacity: 0;
            transition: opacity .25s;
        }

        .match-hint.visible {
            opacity: 1;
        }

        .match-hint.match {
            color: var(--success);
        }

        .match-hint.no-match {
            color: var(--danger);
        }

        .match-hint i {
            font-size: .8rem;
        }

        /* Submit */
        .btn-reset {
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
            position: relative;
            overflow: hidden;
        }

        .btn-reset::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .1), transparent);
            transform: translateX(-100%);
            transition: transform .5s ease;
        }

        .btn-reset:hover::before {
            transform: translateX(100%);
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 36px rgba(0, 229, 195, .36);
        }

        .btn-reset:active {
            transform: scale(.98);
        }

        /* Login link */
        .login-row {
            text-align: center;
            font-size: .83rem;
            color: var(--muted);
        }

        .login-row a {
            color: var(--teal);
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
            transition: color .2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .login-row a:hover {
            color: var(--white);
        }

        /* Security note */
        .security-note {
            margin-top: 20px;
            background: rgba(0, 229, 195, .04);
            border: 1px solid rgba(0, 229, 195, .1);
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .security-note i {
            color: var(--teal);
            font-size: .9rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .security-note span {
            font-size: .76rem;
            color: var(--muted);
            line-height: 1.55;
        }

        /* Footer */
        .rp-footer {
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

            .rp-header {
                padding: 40px 24px 24px;
            }

            .rp-body {
                padding: 24px 24px 32px;
            }

            .rp-footer {
                padding: 0 24px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="glow-a"></div>
    <div class="glow-b"></div>

    <div class="rp-card">

        <!-- ── Header ── -->
        <div class="rp-header">
            <a href="{{ route('login') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to Login
            </a>

            <a href="/" class="rp-brand">
                <div class="rp-brand-icon"><i class="bi bi-bus-front-fill"></i></div>
                <span class="rp-brand-name">EDU<span>RIDE</span></span>
            </a>

            <div class="rp-icon-wrap">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <h1>Create new password</h1>
            <p>Choose a strong password to keep your EDURIDE account secure.</p>
        </div>

        <!-- ── Body ── -->
        <div class="rp-body">

            @if ($errors->any())
            <div class="err-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                {{-- Hidden token --}}
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email (pre-filled, read-only display) --}}
                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <div class="field-wrap">
                        <input
                            type="email"
                            class="field-input {{ $errors->has('email') ? 'is-error' : '' }}"
                            id="email" name="email"
                            value="{{ old('email', $request->email) }}"
                            readonly
                            autocomplete="username">
                        <i class="bi bi-envelope field-icon"></i>
                    </div>
                </div>

                {{-- New Password --}}
                <div class="field-group">
                    <label class="field-label" for="password">New Password</label>
                    <div class="field-wrap">
                        <input
                            type="password"
                            class="field-input {{ $errors->has('password') ? 'is-error' : '' }}"
                            id="password" name="password"
                            placeholder="Min. 8 characters"
                            autocomplete="new-password"
                            required>
                        <i class="bi bi-lock field-icon"></i>
                        <button type="button" class="toggle-pw" data-target="password" aria-label="Toggle password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    {{-- Strength meter --}}
                    <div class="pw-strength" id="pwStrength" style="display:none;">
                        <div class="pw-bars">
                            <div class="pw-bar" id="bar1"></div>
                            <div class="pw-bar" id="bar2"></div>
                            <div class="pw-bar" id="bar3"></div>
                            <div class="pw-bar" id="bar4"></div>
                        </div>
                        <div class="pw-meta">
                            <span class="pw-label" id="pwLabel">Too short</span>
                            <span class="pw-rules">8+ chars recommended</span>
                        </div>
                        <div class="pw-reqs" id="pwReqs">
                            <div class="pw-req" id="req-len"><i class="bi bi-check-circle-fill"></i> At least 8 characters</div>
                            <div class="pw-req" id="req-upper"><i class="bi bi-check-circle-fill"></i> Uppercase & lowercase</div>
                            <div class="pw-req" id="req-num"><i class="bi bi-check-circle-fill"></i> At least one number</div>
                            <div class="pw-req" id="req-special"><i class="bi bi-check-circle-fill"></i> Special character (!@#$…)</div>
                        </div>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="field-group">
                    <label class="field-label" for="password_confirmation">Confirm New Password</label>
                    <div class="field-wrap">
                        <input
                            type="password"
                            class="field-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Repeat your new password"
                            autocomplete="new-password"
                            required>
                        <i class="bi bi-lock-fill field-icon"></i>
                        <button type="button" class="toggle-pw" data-target="password_confirmation" aria-label="Toggle confirm password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="match-hint" id="matchHint">
                        <i class="bi bi-check-circle-fill"></i>
                        <span id="matchText">Passwords match</span>
                    </div>
                </div>

                <button type="submit" class="btn-reset" id="submitBtn">
                    <i class="bi bi-shield-check"></i> Reset Password
                </button>

                <div class="login-row">
                    Remembered it?
                    <a href="{{ route('login') }}">Back to sign in <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="security-note">
                    <i class="bi bi-lock-fill"></i>
                    <span>Your new password is encrypted and stored securely. We never store passwords in plain text.</span>
                </div>

            </form>
        </div>

        <div class="rp-footer">
            © {{ date('Y') }} IRERERO Academy · Smart School Transport
        </div>

    </div>

    <script>
        // ── Toggle password visibility ──
        document.querySelectorAll('.toggle-pw').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = document.getElementById(btn.dataset.target);
                const icon = btn.querySelector('i');
                const hidden = target.type === 'password';
                target.type = hidden ? 'text' : 'password';
                icon.className = hidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });

        // ── Password strength ──
        const pwField = document.getElementById('password');
        const strength = document.getElementById('pwStrength');
        const bars = [document.getElementById('bar1'), document.getElementById('bar2'),
            document.getElementById('bar3'), document.getElementById('bar4')
        ];
        const pwLabel = document.getElementById('pwLabel');

        const reqs = {
            len: {
                el: document.getElementById('req-len'),
                fn: v => v.length >= 8
            },
            upper: {
                el: document.getElementById('req-upper'),
                fn: v => /[A-Z]/.test(v) && /[a-z]/.test(v)
            },
            num: {
                el: document.getElementById('req-num'),
                fn: v => /\d/.test(v)
            },
            special: {
                el: document.getElementById('req-special'),
                fn: v => /[^A-Za-z0-9]/.test(v)
            },
        };

        const levels = [{
                label: 'Too short',
                cls: 'weak',
                bars: 1
            },
            {
                label: 'Weak',
                cls: 'weak',
                bars: 1
            },
            {
                label: 'Fair',
                cls: 'fair',
                bars: 2
            },
            {
                label: 'Good',
                cls: 'good',
                bars: 3
            },
            {
                label: 'Strong 🔒',
                cls: 'strong',
                bars: 4
            },
        ];

        function scorePassword(v) {
            if (v.length < 6) return 0;
            let s = 1;
            if (v.length >= 8) s++;
            if (/[A-Z]/.test(v) && /[a-z]/.test(v)) s++;
            if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) s++;
            return s;
        }

        pwField.addEventListener('input', () => {
            const val = pwField.value;
            if (!val) {
                strength.style.display = 'none';
                return;
            }
            strength.style.display = 'block';

            // Bars
            const s = Math.min(scorePassword(val), 4);
            const lvl = levels[s];
            bars.forEach((b, i) => {
                b.className = 'pw-bar' + (i < lvl.bars ? ' ' + lvl.cls : '');
            });
            pwLabel.textContent = lvl.label;
            pwLabel.className = 'pw-label ' + lvl.cls;

            // Requirements
            Object.values(reqs).forEach(r => {
                r.el.classList.toggle('met', r.fn(val));
            });

            // Trigger confirm check
            confirmField.dispatchEvent(new Event('input'));
        });

        // ── Confirm match ──
        const confirmField = document.getElementById('password_confirmation');
        const matchHint = document.getElementById('matchHint');
        const matchText = document.getElementById('matchText');
        const matchIcon = matchHint.querySelector('i');

        confirmField.addEventListener('input', () => {
            const val = confirmField.value;
            if (!val) {
                matchHint.className = 'match-hint';
                return;
            }

            const ok = val === pwField.value;
            matchHint.className = 'match-hint visible ' + (ok ? 'match' : 'no-match');
            matchIcon.className = ok ? 'bi bi-check-circle-fill' : 'bi bi-x-circle-fill';
            matchText.textContent = ok ? 'Passwords match' : 'Passwords do not match';
            confirmField.style.borderColor = ok ? 'var(--success)' : 'rgba(251,113,133,.5)';
        });
    </script>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>


</body>

</html>