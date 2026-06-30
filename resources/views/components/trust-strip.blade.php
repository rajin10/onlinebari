{{-- ============================================================
     Trust / conversion strip (backend-controlled)
     Settings: Admin → Marketing → Announcement Bar
============================================================ --}}
@php
    $trustEnabled = setting('TRUST_BAR_STATUS', '1') == '1';
    $items = collect([
        ['icon' => 'cod',    'text' => setting('TRUST_BAR_ITEM_1', 'Cash on Delivery Available')],
        ['icon' => 'users',  'text' => setting('TRUST_BAR_ITEM_2', 'Trusted by 10,000+ Customers')],
        ['icon' => 'truck',  'text' => setting('TRUST_BAR_ITEM_3', 'Fast Delivery in 24–48 Hours')],
    ])->filter(fn ($i) => filled($i['text']))->values();
@endphp

@if ($trustEnabled && $items->isNotEmpty())
    @php
        $trustIcon = fn (string $k) => match ($k) {
            'cod'   => '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg>',
            'users' => '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'truck' => '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
            default => '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        };
    @endphp

    <div class="trust-strip" role="region" aria-label="Why shop with us">
        <div class="trust-strip__track">
            @foreach ($items as $item)
                <div class="trust-strip__item">
                    <span class="trust-strip__icon">{!! $trustIcon($item['icon']) !!}</span>
                    <span>{{ $item['text'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .trust-strip {
            border-bottom: 1px solid #efeae3;
            background: #fbfaf8;
        }

        .trust-strip__track {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px 34px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 9px 20px;
        }

        .trust-strip__item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #4b4540;
        }

        .trust-strip__icon {
            display: inline-flex;
            color: #1faf52;
        }

        @media (max-width: 600px) {
            .trust-strip__track {
                gap: 6px 18px;
                padding: 8px 12px;
            }
            .trust-strip__item { font-size: 11.5px; gap: 6px; }
            .trust-strip__icon svg { width: 14px; height: 14px; }
        }
    </style>
@endif
