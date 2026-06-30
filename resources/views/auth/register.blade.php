@extends('layouts.frontend.app')

@section('title', 'Create Account — ' . setting('site_name', 'Anas Luxyworld'))

@push('override_css')
.site-footer > footer { display: block !important; }
@endpush

@push('css')
<style>
/* ─────────────────────────────────────────────────
   AUTH DESIGN SYSTEM  ·  Anas Luxyworld (register)
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

.au-page {
    display: flex;
    min-height: calc(100vh - 80px);
    font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
}

/* LEFT PANEL */
.au-left {
    flex: 0 0 40%;
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 52px 52px;
    background: var(--au-navy);
    overflow: hidden;
}
.au-left-bg {
    position: absolute; inset: 0;
    background: linear-gradient(145deg, #0d2347 0%, #0b1930 55%, #06111f 100%);
    z-index: 0;
}
.au-glow {
    position: absolute; right: -120px; top: 50%;
    transform: translateY(-50%);
    width: 500px; height: 500px; border-radius: 50%;
    background: radial-gradient(circle, rgba(201,168,76,0.14) 0%, rgba(201,168,76,0.04) 45%, transparent 70%);
    z-index: 1; pointer-events: none;
}
.au-glow-sm {
    position: absolute; left: -60px; bottom: 100px;
    width: 240px; height: 240px; border-radius: 50%;
    background: radial-gradient(circle, rgba(201,168,76,0.07) 0%, transparent 70%);
    z-index: 1; pointer-events: none;
}
.au-ring {
    position: absolute; right: -160px; top: 50%;
    transform: translateY(-50%);
    width: 460px; height: 460px; border-radius: 50%;
    border: 1px solid rgba(201,168,76,0.2);
    z-index: 1; pointer-events: none;
}
.au-ring::before {
    content: ''; position: absolute; inset: 48px;
    border-radius: 50%; border: 1px solid rgba(201,168,76,0.10);
}
.au-left-bar {
    position: absolute; top: 0; right: 0;
    width: 2px; height: 100%;
    background: linear-gradient(to bottom, transparent 0%, var(--au-gold) 25%, var(--au-gold) 75%, transparent 100%);
    opacity: 0.35; z-index: 2;
}
.au-dots {
    position: absolute; top: 44px; left: 44px;
    width: 110px; height: 110px;
    background-image: radial-gradient(circle, rgba(201,168,76,0.28) 1.3px, transparent 1.3px);
    background-size: 14px 14px; z-index: 2; pointer-events: none;
}
.au-left-inner {
    position: relative; z-index: 10;
    display: flex; flex-direction: column; height: 100%;
}
.au-logo { margin-bottom: auto; padding-bottom: 20px; }
.au-logo img { height: 52px; width: auto; filter: brightness(0) invert(1); opacity: 0.9; }
.au-headline-block {
    flex: 1; display: flex; flex-direction: column;
    justify-content: center; padding: 32px 0;
}
.au-eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    font-size: 10.5px; font-weight: 700; letter-spacing: 4px;
    text-transform: uppercase; color: var(--au-gold); margin-bottom: 22px;
}
.au-eyebrow::before {
    content: ''; display: block; width: 28px; height: 1.5px;
    background: var(--au-gold); opacity: 0.7;
}
.au-left h1 {
    font-family: Georgia, "Times New Roman", serif;
    font-size: clamp(38px, 3.5vw, 52px); font-weight: 400;
    line-height: 1.12; color: #ffffff; margin: 0 0 6px; letter-spacing: -0.5px;
}
.au-left h1 span { display: block; font-style: italic; color: var(--au-gold2); font-weight: 300; }
.au-left-desc {
    font-size: 14.5px; line-height: 1.75;
    color: rgba(255,255,255,0.62); margin: 22px 0 38px; max-width: 300px;
}
.au-features { list-style: none; padding: 0; margin: 0 0 40px; display: flex; flex-direction: column; gap: 14px; }
.au-features li { display: flex; align-items: center; gap: 13px; font-size: 13.5px; color: rgba(255,255,255,0.75); }
.au-feat-icon {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(201,168,76,0.15); border: 1.5px solid rgba(201,168,76,0.45);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 9px; color: var(--au-gold);
}
.au-stats {
    display: flex; gap: 28px;
    padding-top: 26px; border-top: 1px solid rgba(255,255,255,0.08);
}
.au-stat strong { display: block; font-family: Georgia, serif; font-size: 26px; color: #ffffff; line-height: 1; margin-bottom: 4px; }
.au-stat span { font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.38); }
.au-quote-block { margin-top: 32px; padding: 14px 0 0 18px; border-left: 2px solid rgba(201,168,76,0.4); }
.au-quote-block p { font-family: Georgia, serif; font-size: 13px; font-style: italic; color: rgba(255,255,255,0.36); margin: 0; line-height: 1.7; }

/* RIGHT PANEL */
.au-right {
    flex: 1; display: flex; align-items: center; justify-content: center;
    padding: 48px 44px;
    background: var(--au-cream);
    position: relative; overflow: auto;
}
.au-right::after {
    content: ''; position: absolute; top: 0; right: 0;
    width: 160px; height: 160px; border-bottom-left-radius: 100%;
    border: 1px solid rgba(201,168,76,0.12); pointer-events: none;
}

/* Form card */
.au-card {
    width: 100%; max-width: 580px;
    background: var(--au-white); border-radius: 22px;
    padding: 44px 44px 40px;
    position: relative;
    box-shadow:
        0 0 0 1px rgba(0,0,0,0.04),
        0 2px 8px rgba(0,0,0,0.04),
        0 8px 32px rgba(0,0,0,0.07),
        0 24px 72px rgba(0,0,0,0.05);
    overflow: hidden;
}
.au-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, transparent, var(--au-gold) 25%, var(--au-gold2) 60%, transparent);
}

/* Header */
.au-card-eyebrow {
    font-size: 10px; font-weight: 700; letter-spacing: 3.5px;
    text-transform: uppercase; color: var(--au-gold); margin-bottom: 12px;
    display: flex; align-items: center; gap: 8px;
}
.au-card-eyebrow::before { content: ''; display: block; width: 20px; height: 1.5px; background: currentColor; opacity: 0.7; }
.au-card-title { font-family: Georgia, "Times New Roman", serif; font-size: clamp(26px, 3vw, 36px); font-weight: 400; color: var(--au-text); margin: 0 0 6px; letter-spacing: -0.3px; }
.au-card-sub { font-size: 14px; color: var(--au-muted); margin: 0 0 28px; line-height: 1.6; }

/* Grid row */
.au-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media (max-width: 540px) { .au-row2 { grid-template-columns: 1fr; } }

/* Fields */
.au-field { margin-bottom: 16px; }
.au-label { display: block; font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: #546070; margin-bottom: 7px; }
.au-req { color: #e53e3e; }

.au-input {
    width: 100%; height: 50px; padding: 0 16px;
    font-size: 15px; color: var(--au-text); font-family: inherit;
    background: #faf9f6; border: 1.5px solid var(--au-border);
    border-radius: 11px; outline: none;
    transition: border-color .2s var(--au-ease), box-shadow .2s var(--au-ease), background .15s;
    box-sizing: border-box; -webkit-appearance: none;
}
.au-input:hover:not(:focus) { border-color: #cfc4b4; }
.au-input:focus { background: var(--au-white); border-color: var(--au-gold); box-shadow: 0 0 0 4px rgba(201,168,76,0.10); }
.au-input::placeholder { color: #bec6d0; }
.au-input.is-invalid { border-color: #e53e3e !important; box-shadow: 0 0 0 3px rgba(229,62,62,0.08) !important; }

.au-pw-wrap { position: relative; }
.au-pw-wrap .au-input { padding-right: 52px; }
.au-eye-btn {
    position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: #9eaab6;
    cursor: pointer; font-size: 15px; padding: 4px; line-height: 1;
    transition: color .2s;
}
.au-eye-btn:hover { color: var(--au-gold); }

.au-field-error { margin-top: 5px; font-size: 12px; color: #c53030; display: flex; align-items: center; gap: 5px; }

/* Strength meter */
.au-strength { margin-top: 8px; }
.au-strength-bar { height: 3px; background: #e8e0d5; border-radius: 99px; overflow: hidden; margin-bottom: 5px; }
.au-strength-fill { height: 100%; border-radius: 99px; width: 0%; transition: width .3s ease, background .3s ease; }
.au-strength-lbl { font-size: 11px; font-weight: 600; letter-spacing: 0.5px; }

/* Match msg */
.au-match { font-size: 12px; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
.au-match-ok  { color: #276749; }
.au-match-err { color: #c53030; }

/* OTP section */
.au-otp-wrap {
    background: #faf8f4; border: 1px solid #ede5d5;
    border-radius: 12px; padding: 18px; margin-bottom: 16px;
}
.au-otp-row { display: flex; align-items: flex-end; gap: 12px; }
.au-otp-row .au-field { flex: 1; margin-bottom: 0; }
.au-otp-btn {
    display: flex; align-items: center; gap: 8px;
    height: 50px; padding: 0 20px;
    font-size: 13px; font-weight: 600; letter-spacing: 0.5px; font-family: inherit;
    color: var(--au-navy); background: var(--au-gold2);
    border: none; border-radius: 10px; cursor: pointer; white-space: nowrap; flex-shrink: 0;
    transition: background .2s, transform .15s;
}
.au-otp-btn:hover { background: var(--au-gold); transform: translateY(-1px); }
.au-otp-msg { font-size: 12px; margin-top: 6px; display: flex; align-items: center; gap: 5px; }
.au-otp-ok  { color: #276749; }
.au-otp-err { color: #c53030; }

/* Submit */
.au-btn {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; height: 52px;
    font-size: 13px; font-weight: 700; letter-spacing: 2px;
    text-transform: uppercase; font-family: inherit;
    color: var(--au-white); background: var(--au-navy);
    border: 1.5px solid rgba(201,168,76,0.18); border-radius: 11px;
    cursor: pointer; position: relative; overflow: hidden;
    transition: background .25s var(--au-ease), border-color .25s, transform .15s, box-shadow .25s;
    box-shadow: 0 4px 18px rgba(11,25,48,0.28);
    margin-top: 8px; margin-bottom: 20px;
}
.au-btn::after {
    content: ''; position: absolute; top: 0; left: -110%; width: 80%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
    transform: skewX(-20deg); transition: left .6s ease;
}
.au-btn:hover::after { left: 130%; }
.au-btn:hover { background: #102848; border-color: rgba(201,168,76,0.45); transform: translateY(-1px); box-shadow: 0 8px 30px rgba(11,25,48,0.35); }
.au-btn:active { transform: none; }
.au-btn-arrow { color: var(--au-gold); font-size: 13px; transition: transform .2s; }
.au-btn:hover .au-btn-arrow { transform: translateX(3px); }

.au-divider { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.au-divider::before, .au-divider::after { content: ''; flex: 1; height: 1px; background: var(--au-border); }
.au-divider span { font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #b8c0ca; }

.au-alt { text-align: center; font-size: 14px; color: var(--au-muted); margin-bottom: 24px; }
.au-alt a { font-weight: 700; color: var(--au-text); text-decoration: none; border-bottom: 1.5px solid var(--au-gold); padding-bottom: 1px; transition: color .2s; }
.au-alt a:hover { color: var(--au-gold); }

.au-trust { display: flex; align-items: center; justify-content: center; gap: 18px; padding-top: 20px; border-top: 1px solid var(--au-border); }
.au-trust-item { display: flex; align-items: center; gap: 6px; font-size: 11.5px; color: #a8b4c0; }
.au-trust-item i { color: var(--au-gold); font-size: 12px; }

.au-alert { padding: 12px 16px; border-radius: 10px; background: #fff5f5; border: 1px solid #fecaca; color: #b91c1c; font-size: 13px; margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px; }
.au-alert i { margin-top: 2px; flex-shrink: 0; }

/* Mobile bar */
.au-mobile-top {
    display: none; background: var(--au-navy);
    padding: 16px 20px; align-items: center; justify-content: space-between;
    border-bottom: 1px solid rgba(201,168,76,0.2);
}
.au-mobile-top img { height: 38px; width: auto; filter: brightness(0) invert(1); opacity: .9; }
.au-mobile-top-tag { font-size: 10px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; color: var(--au-gold); }

/* Responsive */
@media (max-width: 960px) {
    .au-left { display: none; }
    .au-mobile-top { display: flex; }
    .au-page { min-height: auto; }
    .au-right { padding: 32px 18px; align-items: flex-start; }
    .au-card { padding: 30px 22px 26px; border-radius: 16px; }
}
@media (max-width: 540px) {
    .au-right { padding: 24px 12px; }
    .au-card { padding: 24px 16px 20px; border-radius: 14px; }
    .au-btn { height: 48px; font-size: 12px; }
    .au-trust { flex-wrap: wrap; gap: 10px; }
    .au-otp-row { flex-direction: column; align-items: stretch; }
    .au-otp-btn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')
    @if (setting('regVerify') == "sms")
        @include('auth.partial.regsms')
    @else
        @include('auth.partial.regemail')
    @endif
@endsection
