@extends('layouts.frontend.app')

@section('title', 'Sign In — ' . setting('site_name', 'Onlinebari'))

@push('override_css')
.site-footer > footer { display: block !important; }
@endpush

@push('css')
<style>
/* ─────────────────────────────────────────────────
   AUTH DESIGN SYSTEM  ·  Onlinebari
   ───────────────────────────────────────────────── */
:root {
    --au-navy:   #0b1930;
    --au-navy2:  #0f2347;
    --au-gold:   #c9a84c;
    --au-gold2:  #e8c76e;
    --au-cream:  #f5f1ea;
    --au-border: #e6ddd0;
    --au-text:   #1c2636;
    --au-muted:  #7b8fa4;
    --au-white:  #ffffff;
    --au-ease:   cubic-bezier(.4,0,.2,1);
}

/* PAGE */
.au-page {
    display: flex;
    min-height: calc(100vh - 80px);
    font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
}

/* ══════════════════════════════════════════
   LEFT PANEL
══════════════════════════════════════════ */
.au-left {
    flex: 0 0 46%;
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 52px 56px;
    background: var(--au-navy);
    overflow: hidden;
}

/* Layered background gradient */
.au-left-bg {
    position: absolute;
    inset: 0;
    background:
        linear-gradient(145deg, #0d2347 0%, #0b1930 55%, #06111f 100%);
    z-index: 0;
}

/* Large warm glow behind content */
.au-glow {
    position: absolute;
    right: -140px;
    top: 50%;
    transform: translateY(-50%);
    width: 520px;
    height: 520px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(201,168,76,0.13) 0%, rgba(201,168,76,0.04) 45%, transparent 70%);
    z-index: 1;
    pointer-events: none;
}
.au-glow-sm {
    position: absolute;
    left: -60px;
    bottom: 80px;
    width: 260px;
    height: 260px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(201,168,76,0.07) 0%, transparent 70%);
    z-index: 1;
    pointer-events: none;
}

/* Concentric gold rings */
.au-ring {
    position: absolute;
    right: -180px;
    top: 50%;
    transform: translateY(-50%);
    width: 480px;
    height: 480px;
    border-radius: 50%;
    border: 1px solid rgba(201,168,76,0.18);
    z-index: 1;
    pointer-events: none;
}
.au-ring::before {
    content: '';
    position: absolute;
    inset: 48px;
    border-radius: 50%;
    border: 1px solid rgba(201,168,76,0.10);
}
.au-ring::after {
    content: '';
    position: absolute;
    inset: 96px;
    border-radius: 50%;
    border: 1px solid rgba(201,168,76,0.06);
}

/* Right-side gold accent bar */
.au-left-bar {
    position: absolute;
    top: 0; right: 0;
    width: 2px; height: 100%;
    background: linear-gradient(to bottom, transparent 0%, var(--au-gold) 25%, var(--au-gold) 75%, transparent 100%);
    opacity: 0.35;
    z-index: 2;
}

/* Dot grid */
.au-dots {
    position: absolute;
    top: 44px; left: 44px;
    width: 110px; height: 110px;
    background-image: radial-gradient(circle, rgba(201,168,76,0.28) 1.3px, transparent 1.3px);
    background-size: 14px 14px;
    z-index: 2;
    pointer-events: none;
}

/* ALL REAL CONTENT — sits on z-index: 10 */
.au-left-inner {
    position: relative;
    z-index: 10;
    display: flex;
    flex-direction: column;
    height: 100%;
}

/* Logo */
.au-logo { margin-bottom: auto; padding-bottom: 20px; }
.au-logo img {
    height: 52px; width: auto;
    filter: brightness(0) invert(1);
    opacity: 0.9;
}

/* Main headline block */
.au-headline-block {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 32px 0;
}

.au-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--au-gold);
    margin-bottom: 22px;
}
.au-eyebrow::before {
    content: '';
    display: block;
    width: 28px; height: 1.5px;
    background: var(--au-gold);
    opacity: 0.7;
}

.au-left h1 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(40px, 4vw, 58px);
    font-weight: 400;
    line-height: 1.12;
    color: #ffffff;
    margin: 0 0 6px;
    letter-spacing: -0.5px;
}
.au-left h1 span {
    display: block;
    font-style: italic;
    color: var(--au-gold2);
    font-weight: 300;
}

.au-left-desc {
    font-size: 15px;
    line-height: 1.75;
    color: rgba(255,255,255,0.62);
    margin: 22px 0 38px;
    max-width: 330px;
}

/* Feature list */
.au-features {
    list-style: none;
    padding: 0; margin: 0 0 40px;
    display: flex; flex-direction: column; gap: 14px;
}
.au-features li {
    display: flex;
    align-items: center;
    gap: 13px;
    font-size: 14px;
    color: rgba(255,255,255,0.75);
}
.au-feat-icon {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: rgba(201,168,76,0.15);
    border: 1.5px solid rgba(201,168,76,0.45);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 9px;
    color: var(--au-gold);
}

/* Stats */
.au-stats {
    display: flex;
    gap: 32px;
    padding-top: 28px;
    border-top: 1px solid rgba(255,255,255,0.08);
}
.au-stat strong {
    display: block;
    font-family: Georgia, serif;
    font-size: 28px;
    font-weight: 400;
    color: #ffffff;
    line-height: 1;
    margin-bottom: 4px;
}
.au-stat span {
    font-size: 10.5px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.38);
}

/* Quote */
.au-quote-block {
    margin-top: 36px;
    padding: 16px 0 0 18px;
    border-left: 2px solid rgba(201,168,76,0.4);
}
.au-quote-block p {
    font-family: Georgia, serif;
    font-size: 13.5px;
    font-style: italic;
    color: rgba(255,255,255,0.38);
    margin: 0;
    line-height: 1.7;
}

/* ══════════════════════════════════════════
   RIGHT PANEL
══════════════════════════════════════════ */
.au-right {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 56px 48px;
    background: var(--au-cream);
    position: relative;
}

/* Corner ornament */
.au-right::after {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 160px; height: 160px;
    border-bottom-left-radius: 100%;
    border: 1px solid rgba(201,168,76,0.12);
    pointer-events: none;
}
.au-right::before {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 100px; height: 100px;
    border-top-right-radius: 100%;
    border: 1px solid rgba(201,168,76,0.08);
    pointer-events: none;
}

/* Form card */
.au-card {
    width: 100%;
    max-width: 448px;
    background: var(--au-white);
    border-radius: 22px;
    padding: 48px 44px 44px;
    position: relative;
    box-shadow:
        0 0 0 1px rgba(0,0,0,0.04),
        0 2px 8px rgba(0,0,0,0.04),
        0 8px 32px rgba(0,0,0,0.07),
        0 24px 72px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* Gold strip at card top */
.au-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg,
        transparent 0%,
        var(--au-gold)  25%,
        var(--au-gold2) 60%,
        transparent 100%);
}

/* Card header */
.au-card-eyebrow {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 3.5px;
    text-transform: uppercase;
    color: var(--au-gold);
    margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
}
.au-card-eyebrow::before {
    content: '';
    display: block;
    width: 20px; height: 1.5px;
    background: currentColor; opacity: 0.7;
}

.au-card-title {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(28px, 3vw, 38px);
    font-weight: 400;
    color: var(--au-text);
    margin: 0 0 6px;
    line-height: 1.1;
    letter-spacing: -0.3px;
}
.au-card-sub {
    font-size: 14px;
    color: var(--au-muted);
    margin: 0 0 32px;
    line-height: 1.6;
}

/* ── FIELD ── */
.au-field { margin-bottom: 18px; }
.au-label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #546070;
    margin-bottom: 7px;
}
.au-input {
    width: 100%;
    height: 52px;
    padding: 0 16px;
    font-size: 15px;
    color: var(--au-text);
    background: #faf9f6;
    border: 1.5px solid var(--au-border);
    border-radius: 11px;
    outline: none;
    transition: border-color .2s var(--au-ease), box-shadow .2s var(--au-ease), background .15s;
    box-sizing: border-box;
    -webkit-appearance: none;
    font-family: inherit;
}
.au-input:hover:not(:focus) { border-color: #cfc4b4; }
.au-input:focus {
    background: var(--au-white);
    border-color: var(--au-gold);
    box-shadow: 0 0 0 4px rgba(201,168,76,0.10);
}
.au-input::placeholder { color: #bec6d0; }
.au-input.is-invalid {
    border-color: #e53e3e !important;
    box-shadow: 0 0 0 3px rgba(229,62,62,0.08) !important;
}

/* Password wrapper */
.au-pw-wrap { position: relative; }
.au-pw-wrap .au-input { padding-right: 52px; }
.au-eye-btn {
    position: absolute;
    right: 14px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: #9eaab6;
    cursor: pointer;
    font-size: 15px;
    padding: 4px;
    line-height: 1;
    transition: color .2s;
}
.au-eye-btn:hover { color: var(--au-gold); }

.au-field-error {
    margin-top: 5px;
    font-size: 12px;
    color: #c53030;
    display: flex; align-items: center; gap: 5px;
}

/* Row between password field and forgot link */
.au-forgot-row {
    display: flex;
    justify-content: flex-end;
    margin-top: -6px;
    margin-bottom: 26px;
}
.au-forgot {
    font-size: 13px;
    color: var(--au-muted);
    text-decoration: none;
    transition: color .2s;
}
.au-forgot:hover { color: var(--au-gold); }

/* ── SUBMIT BUTTON ── */
.au-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    height: 54px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--au-white);
    background: var(--au-navy);
    border: 1.5px solid rgba(201,168,76,0.18);
    border-radius: 11px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: background .25s var(--au-ease), border-color .25s, transform .15s, box-shadow .25s;
    box-shadow: 0 4px 18px rgba(11,25,48,0.28);
    margin-bottom: 24px;
    font-family: inherit;
}
/* Shimmer on hover */
.au-btn::after {
    content: '';
    position: absolute;
    top: 0; left: -110%; width: 80%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
    transform: skewX(-20deg);
    transition: left .6s ease;
}
.au-btn:hover::after { left: 130%; }
.au-btn:hover {
    background: #102848;
    border-color: rgba(201,168,76,0.45);
    transform: translateY(-1px);
    box-shadow: 0 8px 30px rgba(11,25,48,0.35);
}
.au-btn:active { transform: none; box-shadow: 0 2px 8px rgba(11,25,48,0.2); }
.au-btn-arrow {
    color: var(--au-gold);
    font-size: 13px;
    transition: transform .2s;
}
.au-btn:hover .au-btn-arrow { transform: translateX(3px); }

/* ── DIVIDER ── */
.au-divider {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 20px;
}
.au-divider::before, .au-divider::after {
    content: ''; flex: 1; height: 1px;
    background: var(--au-border);
}
.au-divider span {
    font-size: 11px; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    color: #b8c0ca;
}

/* Signup row */
.au-alt {
    text-align: center;
    font-size: 14px;
    color: var(--au-muted);
    margin-bottom: 28px;
}
.au-alt a {
    font-weight: 700;
    color: var(--au-text);
    text-decoration: none;
    border-bottom: 1.5px solid var(--au-gold);
    padding-bottom: 1px;
    transition: color .2s, border-color .2s;
}
.au-alt a:hover { color: var(--au-gold); }

/* Trust row */
.au-trust {
    display: flex; align-items: center; justify-content: center; gap: 20px;
    padding-top: 22px;
    border-top: 1px solid var(--au-border);
}
.au-trust-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 11.5px; color: #a8b4c0;
}
.au-trust-item i { color: var(--au-gold); font-size: 12px; }

/* Alert */
.au-alert {
    padding: 12px 16px; border-radius: 10px;
    background: #fff5f5; border: 1px solid #fecaca;
    color: #b91c1c;
    font-size: 13px; margin-bottom: 20px;
    display: flex; align-items: flex-start; gap: 10px;
}
.au-alert i { margin-top: 2px; flex-shrink: 0; }

/* ══════════════════════════════════════════
   MOBILE TOP BAR  (shown at ≤960px)
══════════════════════════════════════════ */
.au-mobile-top {
    display: none;
    background: var(--au-navy);
    padding: 16px 20px;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(201,168,76,0.2);
}
.au-mobile-top img {
    height: 38px; width: auto;
    filter: brightness(0) invert(1); opacity: .9;
}
.au-mobile-top-tag {
    font-size: 10px; font-weight: 700;
    letter-spacing: 3px; text-transform: uppercase;
    color: var(--au-gold);
}

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
@media (max-width: 960px) {
    .au-left { display: none; }
    .au-mobile-top { display: flex; }
    .au-page { min-height: auto; }
    .au-right { padding: 36px 20px; align-items: flex-start; }
}
@media (max-width: 560px) {
    .au-right { padding: 28px 14px; }
    .au-card { padding: 28px 22px 24px; border-radius: 16px; }
    .au-trust { flex-wrap: wrap; gap: 12px; }
    .au-btn { height: 50px; font-size: 12px; }
}
</style>
@endpush

@section('content')
<?php
Session::forget('link');
if (route('home').'/register' == url()->previous()) {
    Session::put(['link' => route('home')]);
} elseif (route('home').'/join' == url()->previous()) {
    Session::put(['link' => route('home')]);
} elseif (route('home').'/login' == url()->previous()) {
    Session::put(['link' => route('home')]);
} elseif (route('home').'/seller' == url()->previous()) {
    Session::put(['link' => route('home')]);
} elseif (route('home').'/password/reset' == url()->previous()) {
    Session::put(['link' => route('home')]);
} else {
    Session::put(['link' => url()->previous()]);
}
?>

{{-- Mobile top bar --}}
<div class="au-mobile-top">
    <img src="{{ asset('uploads/setting/'.setting('logo')) }}" alt="{{ setting('site_name') }}">
    <span class="au-mobile-top-tag">Member Portal</span>
</div>

<div class="au-page">

    {{-- ══ LEFT PANEL ══ --}}
    <aside class="au-left" aria-hidden="true">
        {{-- Background layers (all decorative, z-index ≤ 2) --}}
        <div class="au-left-bg"></div>
        <div class="au-glow"></div>
        <div class="au-glow-sm"></div>
        <div class="au-ring"></div>
        <div class="au-left-bar"></div>
        <div class="au-dots"></div>

        {{-- ALL REAL CONTENT — z-index: 10, nothing can overlap it --}}
        <div class="au-left-inner">

            {{-- Logo --}}
            <div class="au-logo">
                <img src="{{ asset('uploads/setting/'.setting('logo')) }}" alt="{{ setting('site_name') }}">
            </div>

            {{-- Headline + features --}}
            <div class="au-headline-block">
                <p class="au-eyebrow">Member Portal</p>

                <h1>
                    Welcome<br>
                    <span>Back.</span>
                </h1>

                <p class="au-left-desc">
                    Sign in to explore our exclusive collection — your wishlist, order history, and member perks await.
                </p>

                <ul class="au-features">
                    <li>
                        <span class="au-feat-icon"><i class="fas fa-check"></i></span>
                        Exclusive members-only pricing
                    </li>
                    <li>
                        <span class="au-feat-icon"><i class="fas fa-check"></i></span>
                        Priority express delivery
                    </li>
                    <li>
                        <span class="au-feat-icon"><i class="fas fa-check"></i></span>
                        Real-time order tracking
                    </li>
                    <li>
                        <span class="au-feat-icon"><i class="fas fa-check"></i></span>
                        Dedicated concierge support
                    </li>
                </ul>

                {{-- Stats --}}
                <div class="au-stats">
                    <div class="au-stat">
                        <strong>50K+</strong>
                        <span>Members</span>
                    </div>
                    <div class="au-stat">
                        <strong>4.9 ★</strong>
                        <span>Rating</span>
                    </div>
                    <div class="au-stat">
                        <strong>100%</strong>
                        <span>Authentic</span>
                    </div>
                </div>
            </div>

            {{-- Bottom quote --}}
            <div class="au-quote-block">
                <p>"True luxury is not about price — it is the feeling of exceptional quality."</p>
            </div>

        </div>
    </aside>

    {{-- ══ RIGHT PANEL ══ --}}
    <main class="au-right">
        <div class="au-card">

            {{-- Header --}}
            <p class="au-card-eyebrow">Secure Sign In</p>
            <h2 class="au-card-title">Sign In</h2>
            <p class="au-card-sub">Access your account and luxury experience.</p>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="au-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif
            @if (session('error'))
                <div class="au-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login.get') }}" method="get">

                <div class="au-field">
                    <label class="au-label" for="au-user">Email / Phone / Username</label>
                    <input
                        type="text" id="au-user" name="username"
                        class="au-input @error('username') is-invalid @enderror"
                        placeholder="you@example.com"
                        value="{{ old('username') }}"
                        autocomplete="username" required
                    />
                    @error('username')
                        <p class="au-field-error"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="au-field">
                    <label class="au-label" for="au-pass">Password</label>
                    <div class="au-pw-wrap">
                        <input
                            type="password" id="au-pass" name="password"
                            class="au-input @error('password') is-invalid @enderror"
                            placeholder="Enter your password"
                            autocomplete="current-password" required
                        />
                        <button type="button" class="au-eye-btn" id="au-eye" aria-label="Toggle password">
                            <i class="fal fa-eye" id="au-eye-ic"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="au-field-error"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="au-forgot-row">
                    @if (setting('recovrAC') == 'sms')
                        <a href="{{ route('password.recover.mobile') }}" class="au-forgot">Forgot password?</a>
                    @else
                        <a href="{{ route('password.request') }}" class="au-forgot">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="au-btn">
                    Sign In
                    <i class="fas fa-arrow-right au-btn-arrow"></i>
                </button>

            </form>

            <div class="au-divider"><span>or</span></div>

            <p class="au-alt">
                New to {{ setting('site_name', 'Onlinebari') }}?&nbsp;
                <a href="{{ route('register') }}">Create an account</a>
            </p>

            <div class="au-trust">
                <span class="au-trust-item"><i class="fas fa-lock"></i> SSL Secured</span>
                <span class="au-trust-item"><i class="fas fa-shield-alt"></i> Privacy Safe</span>
                <span class="au-trust-item"><i class="fas fa-headset"></i> 24/7 Support</span>
            </div>

        </div>
    </main>

</div>

@push('js')
<script>
(function () {
    var btn  = document.getElementById('au-eye');
    var inp  = document.getElementById('au-pass');
    var icon = document.getElementById('au-eye-ic');
    if (!btn) return;
    btn.addEventListener('click', function () {
        var show = inp.type === 'password';
        inp.type  = show ? 'text' : 'password';
        icon.classList.toggle('fa-eye',      !show);
        icon.classList.toggle('fa-eye-slash', show);
    });
})();
</script>
@endpush

@endsection
