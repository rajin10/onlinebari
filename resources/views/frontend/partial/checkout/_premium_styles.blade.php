{{-- Ultra-premium checkout styles. Scoped to #premium-checkout so they never
     collide with the legacy Bootstrap layout. Shared by cart + buy-now. --}}
<style>
    [x-cloak] { display: none !important; }

    #premium-checkout {
        --pc-accent: #f85606;
        --pc-accent-dark: #d9480f;
        --pc-ink: #15171c;
        --pc-muted: #6b7280;
        --pc-line: #e7e8ec;
        --pc-bg: #f6f7f9;
        --pc-ok: #16a34a;
        --pc-err: #dc2626;
        background:
            radial-gradient(900px 380px at 100% -8%, #fff4ed 0, transparent 60%),
            var(--pc-bg);
        font-family: "Hind Siliguri", -apple-system, "Segoe UI", system-ui, sans-serif;
        color: var(--pc-ink);
        padding: 28px 14px 120px;
        min-height: 70vh;
    }
    #premium-checkout * { box-sizing: border-box; }
    #premium-checkout a { text-decoration: none; }

    .pc-shell { max-width: 1140px; margin: 0 auto; }

    .pc-head { text-align: center; margin: 6px 0 22px; }
    .pc-head h1 { font-size: 1.55rem; font-weight: 800; letter-spacing: -.4px; margin: 0; }
    .pc-head p { color: var(--pc-muted); margin: 6px 0 0; font-size: .95rem; }

    .pc-grid { display: flex; flex-direction: column; gap: 18px; }

    /* ---- Cards ---- */
    .pc-card {
        background: rgba(255,255,255,.86);
        backdrop-filter: blur(6px);
        border: 1px solid var(--pc-line);
        border-radius: 20px;
        padding: 22px 20px;
        box-shadow: 0 10px 30px rgba(17,23,28,.05);
        margin-bottom: 16px;
        transition: box-shadow .25s ease, transform .25s ease;
    }
    .pc-card:hover { box-shadow: 0 16px 40px rgba(17,23,28,.08); }
    .pc-card-label {
        display: flex; align-items: center; gap: 10px;
        font-weight: 700; font-size: 1.02rem; margin-bottom: 18px;
        padding-bottom: 12px; border-bottom: 1px dashed var(--pc-line);
    }
    .pc-step {
        width: 26px; height: 26px; flex: 0 0 26px; border-radius: 50%;
        background: linear-gradient(135deg, var(--pc-accent), var(--pc-accent-dark));
        color: #fff; font-size: .85rem; font-weight: 700;
        display: inline-flex; align-items: center; justify-content: center;
    }

    /* ---- Fields / floating labels ---- */
    .pc-field { position: relative; margin-bottom: 16px; }
    .pc-field:last-child { margin-bottom: 0; }
    .pc-field.pc-hidden { display: none; }
    #premium-checkout .pc-input {
        width: 100%; border: 1.6px solid var(--pc-line); border-radius: 13px;
        background: #fff; padding: 22px 16px 8px; font-size: .98rem; color: var(--pc-ink);
        outline: none; transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        line-height: 1.3; height: auto; box-shadow: none;
    }
    #premium-checkout .pc-textarea { padding-top: 26px; min-height: 96px; resize: vertical; }
    #premium-checkout .pc-input:focus {
        border-color: var(--pc-accent);
        box-shadow: 0 0 0 4px rgba(248,86,6,.12);
    }
    .pc-field label {
        position: absolute; left: 16px; top: 15px; color: var(--pc-muted);
        font-size: .98rem; pointer-events: none; transition: all .18s ease; background: transparent;
        margin: 0;
    }
    #premium-checkout .pc-input:focus + label,
    #premium-checkout .pc-input:not(:placeholder-shown) + label {
        top: 7px; font-size: .72rem; font-weight: 600; color: var(--pc-accent);
    }
    .pc-req { color: var(--pc-err); }

    .pc-field.is-valid .pc-input { border-color: var(--pc-ok); }
    .pc-field.is-invalid .pc-input { border-color: var(--pc-err); }
    .pc-valid-tick {
        position: absolute; right: 14px; top: 14px; width: 22px; height: 22px;
        background: var(--pc-ok); color: #fff; border-radius: 50%; font-size: 13px;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .pc-ok { display: block; color: var(--pc-ok); font-size: .78rem; margin-top: 5px; }
    .pc-err { display: block; color: var(--pc-err); font-size: .8rem; margin-top: 5px; font-weight: 500; }
    .pc-returning {
        margin: 4px 0 0; color: var(--pc-ok); font-size: .85rem; font-weight: 600;
        background: #ecfdf3; border: 1px solid #c7f0d8; border-radius: 10px; padding: 8px 12px;
    }
    .pc-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 12px; }
    .pc-field-row.pc-bank { grid-template-columns: 1fr; }
    .pc-note {
        margin-top: 14px; font-size: .82rem; color: #8a6d3b;
        background: #fff8ec; border: 1px solid #f3e2bf; border-radius: 12px; padding: 11px 13px;
    }
    .pc-alert-server {
        background: #fef2f2; border: 1px solid #fecaca; color: var(--pc-err);
        border-radius: 12px; padding: 12px 14px; margin-bottom: 14px; font-size: .9rem; font-weight: 500;
    }

    /* ---- Payment cards ---- */
    .pc-pay-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .pc-pay {
        position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 8px; text-align: center; min-height: 96px; padding: 14px 10px;
        border: 2px solid var(--pc-line); border-radius: 16px; background: #fff; cursor: pointer;
        transition: all .2s ease; font-size: .85rem; font-weight: 600; color: var(--pc-ink);
    }
    .pc-pay:hover { border-color: #f8b48a; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(248,86,6,.08); }
    .pc-pay.selected { border-color: var(--pc-accent); background: #fff6f0; box-shadow: 0 8px 22px rgba(248,86,6,.14); }
    .pc-pay input { position: absolute; opacity: 0; pointer-events: none; }
    .pc-pay img { height: 34px; width: auto; max-width: 90px; object-fit: contain; }
    .pc-pay-check {
        position: absolute; top: 8px; right: 8px; width: 20px; height: 20px; border-radius: 50%;
        background: var(--pc-accent); color: #fff; font-size: 12px; font-style: normal;
        display: none; align-items: center; justify-content: center;
    }
    .pc-pay.selected .pc-pay-check { display: inline-flex; }
    .pc-pay-hint {
        margin-top: 14px; font-size: .85rem; color: #374151;
        background: #f3f4f6; border-radius: 12px; padding: 11px 13px; line-height: 1.5;
    }

    /* ---- Order summary ---- */
    .pc-summary {
        background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
        border: 1px solid var(--pc-line); border-radius: 20px; overflow: hidden;
        box-shadow: 0 10px 30px rgba(17,23,28,.06);
    }
    .pc-summary-head {
        width: 100%; display: flex; align-items: center; justify-content: space-between;
        padding: 16px 18px; background: none; border: 0; cursor: pointer;
        font-weight: 700; font-size: .98rem; color: var(--pc-ink);
    }
    .pc-summary-total { display: flex; align-items: center; gap: 8px; color: var(--pc-accent); font-weight: 800; }
    .pc-chev { transition: transform .25s ease; display: inline-block; font-style: normal; }
    .pc-chev.open { transform: rotate(180deg); }
    .pc-summary-body { padding: 0 18px 18px; }
    .pc-items { border-top: 1px solid var(--pc-line); }
    .pc-item { display: flex; align-items: center; gap: 12px; padding: 13px 0; border-bottom: 1px solid #f1f2f4; }
    .pc-item img { width: 54px; height: 64px; border-radius: 12px; object-fit: cover; background: #f1f2f4; flex: 0 0 54px; }
    .pc-item-info { flex: 1; min-width: 0; }
    .pc-item-info a { display: block; font-weight: 600; font-size: .9rem; color: var(--pc-ink); line-height: 1.3; }
    .pc-item-qty { font-size: .78rem; color: var(--pc-muted); }
    .pc-item-price { font-weight: 700; font-size: .88rem; white-space: nowrap; }

    .pc-coupon { display: flex; gap: 8px; margin: 16px 0; }
    .pc-coupon .pc-input { padding: 12px 14px; }
    .pc-coupon-btn {
        flex: 0 0 auto; padding: 0 18px; border-radius: 12px; border: 0; cursor: pointer;
        background: var(--pc-ink); color: #fff; font-weight: 700; font-size: .85rem; transition: opacity .2s;
    }
    .pc-coupon-btn:hover { opacity: .88; }

    .pc-totals { border-top: 1px dashed var(--pc-line); padding-top: 14px; }
    .pc-trow { display: flex; justify-content: space-between; font-size: .9rem; color: var(--pc-muted); margin-bottom: 10px; }
    .pc-trow.pc-grand {
        font-size: 1.15rem; font-weight: 800; color: var(--pc-ink);
        border-top: 1px solid var(--pc-line); padding-top: 14px; margin-top: 4px; margin-bottom: 0;
    }
    .pc-trow.pc-grand span:last-child { color: var(--pc-accent); }

    /* ---- CTA ---- */
    .pc-cta-wrap { margin-top: 4px; }
    #premium-checkout .pc-cta {
        width: 100%; border: 0; cursor: pointer; border-radius: 50px;
        padding: 18px 22px; font-size: 1.08rem; font-weight: 800; color: #fff;
        background: linear-gradient(135deg, var(--pc-accent), var(--pc-accent-dark));
        box-shadow: 0 14px 30px rgba(248,86,6,.32);
        display: inline-flex; align-items: center; justify-content: center; gap: 10px;
        transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
    }
    #premium-checkout .pc-cta:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 18px 38px rgba(248,86,6,.4); }
    #premium-checkout .pc-cta:active:not(:disabled) { transform: translateY(0); }
    #premium-checkout .pc-cta:disabled { opacity: .8; cursor: not-allowed; }
    .pc-spinner {
        width: 18px; height: 18px; border-radius: 50%;
        border: 2.5px solid rgba(255,255,255,.4); border-top-color: #fff;
        animation: pc-spin .7s linear infinite;
    }
    @keyframes pc-spin { to { transform: rotate(360deg); } }
    .pc-trust {
        display: flex; flex-wrap: wrap; gap: 6px 16px; justify-content: center;
        margin-top: 14px; font-size: .82rem; color: var(--pc-muted); font-weight: 600;
    }

    /* ---- Mobile sticky CTA ---- */
    @media (max-width: 1023px) {
        .pc-cta-wrap {
            position: sticky; bottom: 10px; z-index: 30;
            background: rgba(246,247,249,.85); backdrop-filter: blur(6px);
            padding: 8px; border-radius: 56px; box-shadow: 0 -6px 24px rgba(0,0,0,.06);
        }
        .pc-trust { margin-bottom: 2px; }
    }

    /* ---- Desktop two-column ---- */
    @media (min-width: 1024px) {
        #premium-checkout { padding: 40px 20px 80px; }
        .pc-grid { display: grid; grid-template-columns: 1fr 380px; gap: 30px; align-items: start; }
        .pc-col-summary { position: sticky; top: 24px; }
        .pc-summary-head { cursor: default; }
        .pc-summary-body { display: block !important; }
        .pc-chev { display: none; }
        .pc-pay-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>
