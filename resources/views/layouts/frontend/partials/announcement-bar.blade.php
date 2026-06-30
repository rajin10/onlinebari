{{-- ============================================================
     Premium Announcement Bar  (backend-controlled, scheduled)
     Managed in Admin → Marketing → Announcement Bar
============================================================ --}}
@php
    $annEnabled = setting('ANNOUNCEMENT_BAR_STATUS', '1') == '1';
    $announcements = $annEnabled ? \App\Models\Announcement::visible() : collect();
@endphp

@if ($announcements->isNotEmpty())
    @php
        $annBg    = setting('ANNOUNCEMENT_BAR_BG', '#0f172a');
        $annText  = setting('ANNOUNCEMENT_BAR_TEXT', '#ffffff');
        $annSpeed = (int) setting('ANNOUNCEMENT_BAR_SPEED', '4000');

        // Inline SVG icon set — crisp on retina, no extra font dependency.
        $annIcon = function (?string $key) {
            return match ($key) {
                'whatsapp' => '<svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-1.207zm5.439-6.32c-.075-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.521.149-.174.198-.298.298-.497.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>',
                'phone'    => '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
                'truck'    => '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
                'shield'   => '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                'fire'     => '<svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M12 23c4.97 0 8-3.13 8-7 0-2.34-1.2-4.2-2.5-5.9-.3 1-.9 1.8-1.8 1.8-1.1 0-1.2-1.4-1.1-3.2.1-2.2-.4-4.5-2.6-6.7 0 2.2-1.3 3.6-2.7 5C7.4 5.6 6 7.3 6 10.2 5 9.6 4.5 8.4 4.5 7 3.5 8.8 3 10.7 3 12.5 3 17.4 6.5 23 12 23z"/></svg>',
                'star'     => '<svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor"><path d="M12 .587l3.668 7.431 8.2 1.193-5.934 5.787 1.401 8.168L12 18.896l-7.335 3.863 1.401-8.168L.132 9.211l8.2-1.193z"/></svg>',
                'clock'    => '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                'offer'    => '<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>',
                default    => '',
            };
        };
    @endphp

    <div class="announcement-bar" role="region" aria-label="Store announcements"
        style="--ann-bg: {{ $annBg }}; --ann-fg: {{ $annText }};"
        x-data="annBar({{ $announcements->count() }}, {{ max(1500, $annSpeed) }})"
        @mouseenter="pause()" @mouseleave="resume()">

        <div class="announcement-bar__viewport">
            @foreach ($announcements as $i => $ann)
                <div class="announcement-bar__slide" x-show="current === {{ $i }}" {{ $i === 0 ? '' : 'x-cloak' }}
                    x-transition:enter="ann-fade" x-transition:enter-start="ann-fade-start"
                    x-transition:enter-end="ann-fade-end"
                    @style([
                        'background:' . $ann->bg_color => $ann->bg_color,
                        'color:' . $ann->text_color => $ann->text_color,
                    ])>

                    @if ($ann->urgency_label)
                        <span class="announcement-bar__urgency">{{ $ann->urgency_label }}</span>
                    @endif

                    @if ($ann->icon)
                        <span class="announcement-bar__icon {{ $ann->icon === 'whatsapp' ? 'is-wa' : '' }}">{!! $annIcon($ann->icon) !!}</span>
                    @endif

                    <span class="announcement-bar__text">{{ $ann->message }}</span>

                    @if ($ann->cta_text && $ann->cta_link)
                        <a class="announcement-bar__cta {{ $ann->icon === 'whatsapp' || str_contains($ann->cta_link, 'wa.me') ? 'is-wa' : '' }}"
                            href="{{ $ann->cta_link }}"
                            @if (str_contains($ann->cta_link, 'http')) target="_blank" rel="noopener" @endif>
                            {{ $ann->cta_text }}
                            <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($announcements->count() > 1)
            <div class="announcement-bar__dots" aria-hidden="true">
                @foreach ($announcements as $i => $ann)
                    <button type="button" class="announcement-bar__dot" :class="current === {{ $i }} && 'is-active'"
                        @click="go({{ $i }})"></button>
                @endforeach
            </div>
        @endif
    </div>

    <style>
        [x-cloak] { display: none !important; }

        :root {
            --ann-h: 44px;
        }

        @media (max-width: 768px) {
            :root {
                --ann-h: 40px;
            }
        }

        .announcement-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--ann-h);
            z-index: 1150;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--ann-bg);
            color: var(--ann-fg);
            overflow: hidden;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }

        /* Subtle moving sheen for a premium feel (no harsh gradient) */
        .announcement-bar::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(100deg, transparent 30%, rgba(255, 255, 255, 0.06) 50%, transparent 70%);
            transform: translateX(-100%);
            animation: ann-sheen 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes ann-sheen {
            0%, 60% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .announcement-bar__viewport {
            position: relative;
            width: 100%;
            max-width: 1400px;
            height: 100%;
            margin: 0 auto;
            padding: 0 18px;
        }

        .announcement-bar__slide {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 4px;
            font-size: 13.5px;
            font-weight: 500;
            letter-spacing: 0.1px;
            white-space: nowrap;
        }

        .ann-fade { transition: opacity 0.5s ease, transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .ann-fade-start { opacity: 0; transform: translateY(8px); }
        .ann-fade-end { opacity: 1; transform: translateY(0); }

        .announcement-bar__text {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .announcement-bar__urgency {
            display: inline-flex;
            align-items: center;
            padding: 2px 9px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            flex-shrink: 0;
            animation: ann-pulse 1.8s ease-in-out infinite;
        }

        @keyframes ann-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.55; }
        }

        .announcement-bar__icon {
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
            opacity: 0.95;
        }

        .announcement-bar__icon.is-wa { color: #25D366; }

        .announcement-bar__cta {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: inherit;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            flex-shrink: 0;
            transition: background 0.25s ease, transform 0.25s ease;
        }

        .announcement-bar__cta:hover {
            background: rgba(255, 255, 255, 0.26);
            transform: translateX(2px);
            color: inherit;
        }

        .announcement-bar__cta.is-wa {
            background: #25D366;
            color: #08311a;
        }

        .announcement-bar__cta.is-wa:hover {
            background: #1ebe5a;
            color: #08311a;
        }

        .announcement-bar__dots {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 5px;
            z-index: 2;
        }

        .announcement-bar__dot {
            width: 6px;
            height: 6px;
            padding: 0;
            border: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.35);
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .announcement-bar__dot.is-active {
            background: #fff;
            width: 16px;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .announcement-bar__slide { font-size: 11.5px; gap: 7px; }
            .announcement-bar__urgency { display: none; }
            .announcement-bar__cta { padding: 3px 9px; font-size: 11px; }
            .announcement-bar__dots { display: none; }
            .announcement-bar__viewport { padding: 0 10px; }
        }

        /* ---- Layout offsets: push the fixed header + page content down ---- */
        body.has-ann .main-header {
            top: var(--ann-h);
            transition: top 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.28s ease,
                background 0.3s ease, backdrop-filter 0.28s ease, border-color 0.28s ease;
        }

        body.has-ann .site-inner {
            padding-top: calc(68px + var(--ann-h)) !important;
        }

        @media (max-width: 767px) {
            body.has-ann .site-inner {
                padding-top: calc(58px + var(--ann-h)) !important;
            }
        }

        /* When scrolled, the bar slides up and the header snaps to the top */
        body.ann-up .announcement-bar { transform: translateY(-100%); }
        body.has-ann.ann-up .main-header { top: 0; }
    </style>

    <script>
        (function () {
            document.body.classList.add('has-ann');

            // Alpine rotator component
            document.addEventListener('alpine:init', function () {
                window.Alpine.data('annBar', function (count, speed) {
                    return {
                        current: 0,
                        timer: null,
                        init() {
                            if (count > 1) this.start();
                        },
                        start() {
                            this.timer = setInterval(() => {
                                this.current = (this.current + 1) % count;
                            }, speed);
                        },
                        pause() {
                            if (this.timer) { clearInterval(this.timer); this.timer = null; }
                        },
                        resume() {
                            if (count > 1 && !this.timer) this.start();
                        },
                        go(i) {
                            this.current = i;
                            this.pause();
                            this.resume();
                        },
                    };
                });
            });

            // Collapse the bar on scroll for a clean sticky header
            var lastUp = false;
            function onScroll() {
                var up = window.scrollY > 12;
                if (up !== lastUp) {
                    document.body.classList.toggle('ann-up', up);
                    lastUp = up;
                }
            }
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();
    </script>
@endif
