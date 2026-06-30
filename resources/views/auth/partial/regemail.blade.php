<?php $refer = isset($_GET['code']) ? $_GET['code'] : 'admin'; ?>

<div class="au-mobile-top">
    <img src="{{ asset('uploads/setting/'.setting('logo')) }}" alt="{{ setting('site_name') }}">
    <span class="au-mobile-top-tag">Create Account</span>
</div>

<div class="au-page">

    {{-- ══ LEFT PANEL ══ --}}
    <aside class="au-left" aria-hidden="true">
        <div class="au-left-bg"></div>
        <div class="au-glow"></div>
        <div class="au-glow-sm"></div>
        <div class="au-ring"></div>
        <div class="au-left-bar"></div>
        <div class="au-dots"></div>

        <div class="au-left-inner">
            <div class="au-logo">
                <img src="{{ asset('uploads/setting/'.setting('logo')) }}" alt="{{ setting('site_name') }}">
            </div>

            <div class="au-headline-block">
                <p class="au-eyebrow">Join The Family</p>

                <h1>
                    Start Your<br>
                    <span>Journey.</span>
                </h1>

                <p class="au-left-desc">
                    Become a member and unlock a world of exclusive luxury — curated products, special pricing, and a seamless experience.
                </p>

                <ul class="au-features">
                    <li><span class="au-feat-icon"><i class="fas fa-check"></i></span> Instant access to member deals</li>
                    <li><span class="au-feat-icon"><i class="fas fa-check"></i></span> Personalised wishlist & order history</li>
                    <li><span class="au-feat-icon"><i class="fas fa-check"></i></span> Free express delivery on all orders</li>
                    <li><span class="au-feat-icon"><i class="fas fa-check"></i></span> Exclusive new arrival previews</li>
                </ul>

                <div class="au-stats">
                    <div class="au-stat"><strong>50K+</strong><span>Members</span></div>
                    <div class="au-stat"><strong>4.9 ★</strong><span>Rating</span></div>
                    <div class="au-stat"><strong>100%</strong><span>Authentic</span></div>
                </div>
            </div>

            <div class="au-quote-block">
                <p>"Luxury is the ease of comfort in a world of exceptional quality."</p>
            </div>
        </div>
    </aside>

    {{-- ══ RIGHT PANEL ══ --}}
    <main class="au-right">
        <div class="au-card">

            <p class="au-card-eyebrow">New Account</p>
            <h2 class="au-card-title">Create Account</h2>
            <p class="au-card-sub">Join thousands of members and enjoy exclusive benefits.</p>

            @if ($errors->any())
                <div class="au-alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif

            <form action="{{ route('register.new') }}" method="post">
                @csrf
                <input type="hidden" name="refer" value="{{ $refer }}">

                {{-- Row 1: Name + Phone --}}
                <div class="au-row2">
                    <div class="au-field">
                        <label class="au-label" for="rg-name">Full Name <span class="au-req">*</span></label>
                        <input type="text" id="rg-name" name="name"
                            class="au-input @error('name') is-invalid @enderror"
                            placeholder="Your full name" value="{{ old('name') }}" required />
                        @error('name')
                            <p class="au-field-error"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    <div class="au-field">
                        <label class="au-label" for="rg-phone">Phone <span class="au-req">*</span></label>
                        <input type="tel" id="rg-phone" name="phone"
                            class="au-input @error('phone') is-invalid @enderror"
                            placeholder="01XXXXXXXXX" value="{{ old('phone') }}" required />
                        @error('phone')
                            <p class="au-field-error"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div class="au-field">
                    <label class="au-label" for="rg-email">Email Address</label>
                    <input type="email" id="rg-email" name="email"
                        class="au-input @error('email') is-invalid @enderror"
                        placeholder="you@example.com" value="{{ old('email') }}" />
                    @error('email')
                        <p class="au-field-error"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Row 2: Password + Confirm --}}
                <div class="au-row2">
                    <div class="au-field">
                        <label class="au-label" for="rg-pass">Password <span class="au-req">*</span></label>
                        <div class="au-pw-wrap">
                            <input type="password" id="rg-pass" name="password"
                                class="au-input @error('password') is-invalid @enderror"
                                placeholder="Min. 8 characters" autocomplete="new-password" required />
                            <button type="button" class="au-eye-btn" id="rg-eye1"><i class="fal fa-eye" id="rg-eye1-ic"></i></button>
                        </div>
                        @error('password')
                            <p class="au-field-error"><i class="fas fa-times-circle"></i> {{ $message }}</p>
                        @enderror
                        <div class="au-strength" id="rg-strength" style="display:none">
                            <div class="au-strength-bar"><div class="au-strength-fill" id="rg-sf"></div></div>
                            <span class="au-strength-lbl" id="rg-sl"></span>
                        </div>
                    </div>
                    <div class="au-field">
                        <label class="au-label" for="rg-confirm">Confirm Password <span class="au-req">*</span></label>
                        <div class="au-pw-wrap">
                            <input type="password" id="rg-confirm" name="password_confirmation"
                                class="au-input" placeholder="Repeat password" autocomplete="new-password" required />
                            <button type="button" class="au-eye-btn" id="rg-eye2"><i class="fal fa-eye" id="rg-eye2-ic"></i></button>
                        </div>
                        <p class="au-match" id="rg-match" style="display:none"></p>
                    </div>
                </div>

                <button type="submit" class="au-btn">
                    Create My Account
                    <i class="fas fa-arrow-right au-btn-arrow"></i>
                </button>
            </form>

            <div class="au-divider"><span>or</span></div>
            <p class="au-alt">Already have an account?&nbsp; <a href="{{ route('login') }}">Sign In</a></p>

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
    /* Eye toggles */
    function eye(btnId, inpId, icId) {
        var b = document.getElementById(btnId), i = document.getElementById(inpId), c = document.getElementById(icId);
        if (!b) return;
        b.addEventListener('click', function () {
            var s = i.type === 'password';
            i.type = s ? 'text' : 'password';
            c.classList.toggle('fa-eye', !s);
            c.classList.toggle('fa-eye-slash', s);
        });
    }
    eye('rg-eye1', 'rg-pass',    'rg-eye1-ic');
    eye('rg-eye2', 'rg-confirm', 'rg-eye2-ic');

    /* Strength meter */
    var pi = document.getElementById('rg-pass');
    var sw = document.getElementById('rg-strength');
    var sf = document.getElementById('rg-sf');
    var sl = document.getElementById('rg-sl');
    if (pi) {
        pi.addEventListener('input', function () {
            var v = this.value;
            if (!v) { sw.style.display = 'none'; return; }
            sw.style.display = 'block';
            var s = [v.length>=8, /[A-Z]/.test(v), /[0-9]/.test(v), /[^A-Za-z0-9]/.test(v)].filter(Boolean).length;
            var m = [
                {p:'25%', bg:'#e53e3e', l:'Weak',   c:'#c53030'},
                {p:'50%', bg:'#dd6b20', l:'Fair',   c:'#c05621'},
                {p:'75%', bg:'#d69e2e', l:'Good',   c:'#b7791f'},
                {p:'100%',bg:'#38a169', l:'Strong', c:'#276749'},
            ][s-1] || {p:'25%', bg:'#e53e3e', l:'Weak', c:'#c53030'};
            sf.style.width = m.p; sf.style.background = m.bg;
            sl.textContent = m.l; sl.style.color = m.c;
        });
    }

    /* Match indicator */
    var ci = document.getElementById('rg-confirm');
    var mm = document.getElementById('rg-match');
    function chk() {
        if (!ci || !mm || !ci.value) { if(mm) mm.style.display='none'; return; }
        var ok = pi && pi.value === ci.value;
        mm.style.display = 'flex';
        mm.className = 'au-match ' + (ok ? 'au-match-ok' : 'au-match-err');
        mm.innerHTML = ok
            ? '<i class="fas fa-check-circle"></i>&nbsp;Passwords match'
            : '<i class="fas fa-times-circle"></i>&nbsp;Passwords do not match';
    }
    if (ci) { ci.addEventListener('input', chk); if(pi) pi.addEventListener('input', chk); }
})();
</script>
@endpush
