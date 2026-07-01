@php
    use App\Models\LandingPageContent;

    $heroBefore  = LandingPageContent::imageUrl($content['hero_before_image'] ?? null);
    $heroAfter   = LandingPageContent::imageUrl($content['hero_after_image'] ?? null);
    $beforeImage = LandingPageContent::imageUrl($content['before_image'] ?? null);
    $afterImage  = LandingPageContent::imageUrl($content['after_image'] ?? null);
    $phone       = '01624109210';
@endphp
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Google Tag Manager + dataLayer helper --}}
    @include('partials.gtm-head')
    @include('partials.tracking')

    <title>মরিচা রডের শক্তি কেড়ে নেওয়ার আগেই থামান</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=hind-siliguri:400,500,600" rel="stylesheet">
    <!-- Tabler Icons (CDN — mirrors the storefront's Boxicons-via-CDN convention) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">

    @vite(['resources/css/rust-removal.css', 'resources/js/landing.js'])
</head>

<body class="rust-removal-page">
    {{-- Google Tag Manager (noscript) --}}
    @include('partials.gtm-body')

    <div class="lp-card">

        {{-- Urgency bar --}}
        <div class="lp-urgency">
            <i class="ti ti-clock" aria-hidden="true"></i>
            নিয়মিত মূল্য ৬৫০৳, আজ ৪৮০৳
        </div>

        {{-- Hero --}}
        <div class="lp-hero" data-animate>
            <p class="lp-eyebrow">রড খোলা জায়গায় পড়ে মরিচা ধরেছে?</p>
            <h1 class="lp-title">মরিচা রডের শক্তি কেড়ে নেওয়ার আগেই থামান</h1>

            <div class="lp-beforeafter">
                <div class="lp-ba-cell {{ $heroBefore ? 'lp-ba-cell--photo' : '' }}">
                    @if ($heroBefore)
                        <img src="{{ $heroBefore }}" alt="আগে — মরিচা ধরা রড">
                        <span class="lp-ba-cell__tag">আগে — মরিচা ধরা</span>
                    @else
                        <i class="ti ti-alert-triangle" aria-hidden="true"></i>
                        <span class="lp-ba-cell__label">আগে — মরিচা ধরা</span>
                    @endif
                </div>
                <div class="lp-ba-cell lp-ba-cell--dark {{ $heroAfter ? 'lp-ba-cell--photo' : '' }}">
                    @if ($heroAfter)
                        <img src="{{ $heroAfter }}" alt="পরে — প্রোটেক্টেড রড">
                        <span class="lp-ba-cell__tag">পরে — প্রোটেক্টেড</span>
                    @else
                        <i class="ti ti-shield-check" aria-hidden="true"></i>
                        <span class="lp-ba-cell__label">পরে — প্রোটেক্টেড</span>
                    @endif
                </div>
            </div>

            @include('frontend.landing.partials.cta', ['label' => 'অর্ডার করুন — ৪৮০৳'])
            <p class="lp-note">সারাদেশে ডেলিভারি · ক্যাশ অন ডেলিভারি</p>
        </div>

        {{-- Problem --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mb-4">সামান্য মরিচা, বড় বিপদ</h2>
            <p class="lp-lead">খোলা জায়গায় রোদ-বৃষ্টিতে পড়ে থাকা রড ধীরে ধীরে মরিচা ধরে, ভেতর থেকে দুর্বল হয়ে যায় আপনার ভিত্তির বিনিয়োগ।</p>
        </div>

        {{-- Features --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mb-12">মরিচাকে কনভার্ট করে, শুধু ঢাকে না</h2>
            <div class="lp-features">
                <div class="lp-feature">
                    <i class="ti ti-bolt lp-feature__icon" aria-hidden="true"></i>
                    <div>
                        <p class="lp-feature__title">লোহার শক্তি ফিরে আসে</p>
                        <p class="lp-feature__desc">রাসায়নিক বিক্রিয়ায় মরিচা রূপান্তরিত হয়, আয়রন আর দুর্বল হয় না</p>
                    </div>
                </div>
                <div class="lp-feature">
                    <i class="ti ti-layers-intersect lp-feature__icon" aria-hidden="true"></i>
                    <div>
                        <p class="lp-feature__title">নতুন প্রোটেক্টিভ সারফেস</p>
                        <p class="lp-feature__desc">কালো স্তর ভবিষ্যতে মরিচা পড়া প্রতিরোধ করে</p>
                    </div>
                </div>
                <div class="lp-feature">
                    <i class="ti ti-clock-bolt lp-feature__icon" aria-hidden="true"></i>
                    <div>
                        <p class="lp-feature__title">মিনিটেই কাজ শেষ</p>
                        <p class="lp-feature__desc">স্প্রে বা ব্রাশ করুন, শুকাতে দিন — জটিল প্রক্রিয়া নেই</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Four-in-one checklist --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mb-12">এক বোতলে চারটি কাজ</h2>
            <div class="lp-checks">
                <div class="lp-check">
                    <i class="ti ti-circle-check-filled" aria-hidden="true"></i>
                    <span>মরিচা দূর করে</span>
                </div>
                <div class="lp-check">
                    <i class="ti ti-circle-check-filled" aria-hidden="true"></i>
                    <span>ভবিষ্যতে মরিচা প্রতিরোধ করে</span>
                </div>
                <div class="lp-check">
                    <i class="ti ti-circle-check-filled" aria-hidden="true"></i>
                    <span>কালো প্রোটেকশন লেয়ার তৈরি করে, রড থাকে মজবুত</span>
                </div>
                <div class="lp-check">
                    <i class="ti ti-circle-check-filled" aria-hidden="true"></i>
                    <span>মিনিটের মধ্যেই রড হয়ে ওঠে নতুনের মতো</span>
                </div>
            </div>
        </div>

        {{-- Before / after real photos + testimonial --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mb-12">প্রয়োগের আগে ও পরে</h2>

            <div class="lp-ba-photos">
                <div class="lp-ba-photo">
                    @if ($beforeImage)
                        <img src="{{ $beforeImage }}" alt="প্রয়োগের আগে — মরিচা ধরা রড">
                    @else
                        <i class="ti ti-photo lp-ba-photo__ph" aria-hidden="true"></i>
                    @endif
                    <span class="lp-ba-photo__tag">আগে</span>
                </div>
                <div class="lp-ba-photo lp-ba-photo--dark">
                    @if ($afterImage)
                        <img src="{{ $afterImage }}" alt="প্রয়োগের পরে — প্রোটেক্টেড রড">
                    @else
                        <i class="ti ti-photo lp-ba-photo__ph" aria-hidden="true"></i>
                    @endif
                    <span class="lp-ba-photo__tag">পরে</span>
                </div>
            </div>

            <div class="lp-quote">
                <p class="lp-quote__text">"রডগুলো দুই মাস খোলা জায়গায় পড়ে ছিল, মরিচায় লাল হয়ে গিয়েছিল। এই লিকুইড দেওয়ার পর একদম কালো, শক্ত হয়ে গেছে।"</p>
                <p class="lp-quote__by">— একজন কাস্টমার, নির্মাণ সাইট</p>
            </div>
        </div>

        {{-- Final CTA / order section --}}
        <div id="lp-order" class="lp-section lp-section--tint" data-animate>
            <div style="text-align:center;">
                <p class="lp-final__was">নিয়মিত মূল্য ৬৫০৳</p>
                <p class="lp-final__now">৪৮০৳</p>
                <p class="lp-discount">২৬% ছাড় চলছে</p>
                @include('frontend.landing.partials.cta', ['label' => 'এই লিকুইড অর্ডার করতে চাই'])
                <p class="lp-final__trust"><i class="ti ti-truck-delivery" aria-hidden="true"></i> ক্যাশ অন ডেলিভারি · সারাদেশে ডেলিভারি</p>
            </div>
        </div>

        {{-- Phone --}}
        <div class="lp-foot">
            <a href="tel:{{ $phone }}" class="lp-phone">
                <i class="ti ti-phone" aria-hidden="true"></i>যেকোনো প্রয়োজনে কল করুন: ০১৬২৪১০৯২১০
            </a>
        </div>

    </div>
</body>

</html>
