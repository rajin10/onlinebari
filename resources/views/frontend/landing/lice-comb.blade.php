@php
    use App\Models\LandingPageContent;

    $heroImage = LandingPageContent::imageUrl($content['hero_image'] ?? null);
    $videoId   = LandingPageContent::youtubeId($content['demo_video'] ?? null);
    $phone     = '01624109210';
@endphp
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>মাত্র ৫ মিনিটে উকুন দূর করুন — বিশেষ চিরুনি</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=hind-siliguri:400,500,600" rel="stylesheet">
    <!-- Tabler Icons (CDN — mirrors the storefront's Boxicons-via-CDN convention) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">

    @vite(['resources/css/lice-comb.css', 'resources/js/landing.js'])
</head>

<body class="lice-comb-page">
    <div class="lp-card">

        {{-- Urgency bar --}}
        <div class="lp-urgency">
            <i class="ti ti-clock" aria-hidden="true"></i>
            আজকের বিশেষ ছাড় শেষ হচ্ছে শীঘ্রই
        </div>

        {{-- Hero --}}
        <div class="lp-hero" data-animate>
            <p class="lp-eyebrow">আপনি কি উকুনের জ্বালায় অতিষ্ঠ?</p>
            <h1 class="lp-title">মাত্র ৫ মিনিটে উকুন দূর করুন এই বিশেষ চিরুনি দিয়ে</h1>

            <div class="lp-media lp-hero-media">
                @if ($heroImage)
                    <img src="{{ $heroImage }}" alt="প্রিমিয়াম উকুন দূর করার চিরুনি" width="420" height="220">
                @else
                    <i class="ti ti-photo lp-media__ph-icon" aria-hidden="true"></i>
                    <span class="lp-media__ph-text">প্রোডাক্টের হিরো ছবি</span>
                @endif
            </div>

            <div class="lp-badges">
                <span class="lp-badge lp-badge--success"><i class="ti ti-check" aria-hidden="true"></i> ১০০% অরিজিনাল</span>
                <span class="lp-badge lp-badge--accent"><i class="ti ti-truck-delivery" aria-hidden="true"></i> ক্যাশ অন ডেলিভারি</span>
            </div>

            <button type="button" class="lp-cta" data-scroll-to="#lp-order">এখনই অর্ডার করুন — ২৮০৳</button>
            <p class="lp-note">সারাদেশে হোম ডেলিভারি</p>
        </div>

        {{-- Live demo video --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mb-4">লাইভ ব্যবহার দেখুন</h2>
            <p class="lp-sub lp-mb-12">মাত্র ৩০ সেকেন্ডে বুঝে নিন কীভাবে কাজ করে</p>

            <div class="lp-video">
                @if ($videoId)
                    <iframe
                        src="https://www.youtube-nocookie.com/embed/{{ $videoId }}"
                        title="প্রোডাক্ট ডেমো ভিডিও"
                        loading="lazy"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                @else
                    <div class="lp-video__play"><i class="ti ti-player-play-filled" aria-hidden="true"></i></div>
                    <span class="lp-video__hint">প্রোডাক্ট ডেমো ভিডিও যুক্ত হবে এখানে</span>
                    <span class="lp-video__time">০:৩২</span>
                @endif
            </div>
        </div>

        {{-- Why use this comb --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mb-12">কেন এই চিরুনি ব্যবহার করবেন?</h2>
            <div class="lp-features">
                <div class="lp-feature">
                    <i class="ti ti-shield-check lp-feature__icon" aria-hidden="true"></i>
                    <div>
                        <p class="lp-feature__title">কোনো কেমিক্যাল নেই</p>
                        <p class="lp-feature__desc">শ্যাম্পু ছাড়াই সরাসরি উকুন দূর করে</p>
                    </div>
                </div>
                <div class="lp-feature">
                    <i class="ti ti-target-arrow lp-feature__icon" aria-hidden="true"></i>
                    <div>
                        <p class="lp-feature__title">স্টেইনলেস স্টিলের সূক্ষ্ম দাঁত</p>
                        <p class="lp-feature__desc">উকুন ও ডিম দুটোই তুলে ফেলে</p>
                    </div>
                </div>
                <div class="lp-feature">
                    <i class="ti ti-repeat lp-feature__icon" aria-hidden="true"></i>
                    <div>
                        <p class="lp-feature__title">বারবার অর্ডার করা কাস্টমার</p>
                        <p class="lp-feature__desc">হাজারো সন্তুষ্ট পরিবার</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- How it works --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mb-12">যেভাবে কাজ করে</h2>
            <div class="lp-steps">
                <div class="lp-step">
                    <div class="lp-step__num">১</div>
                    <p class="lp-step__label">চুল আঁচড়ান</p>
                </div>
                <div class="lp-step">
                    <div class="lp-step__num">২</div>
                    <p class="lp-step__label">চিরুনি চালান</p>
                </div>
                <div class="lp-step">
                    <div class="lp-step__num">৩</div>
                    <p class="lp-step__label">উকুনমুক্ত চুল</p>
                </div>
            </div>
        </div>

        {{-- Testimonials --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mt-2">কাস্টমাররা যা বলছেন</h2>
            <p class="lp-sub lp-mb-12">২,৪০০+ পরিবার ইতিমধ্যে ব্যবহার করেছেন</p>

            <div class="lp-reviews">
                <div class="lp-review">
                    <div class="lp-review__head">
                        <div class="lp-review__who">
                            <div class="lp-avatar">সা</div>
                            <span class="lp-review__name">সাদিয়া, ঢাকা</span>
                        </div>
                        <span class="lp-badge--verify"><i class="ti ti-rosette-discount-check" aria-hidden="true"></i> ভেরিফায়েড ক্রেতা</span>
                    </div>
                    <div class="lp-stars" aria-label="৫ তারকা রেটিং">
                        <i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i>
                    </div>
                    <p class="lp-review__text">২ দিনেই বাচ্চার মাথা পুরো পরিষ্কার হয়ে গেছে, আগে যা কোনো শ্যাম্পুতেও হয়নি।</p>
                </div>

                <div class="lp-review">
                    <div class="lp-review__head">
                        <div class="lp-review__who">
                            <div class="lp-avatar">রু</div>
                            <span class="lp-review__name">রুমা, চট্টগ্রাম</span>
                        </div>
                        <span class="lp-badge--verify"><i class="ti ti-rosette-discount-check" aria-hidden="true"></i> ভেরিফায়েড ক্রেতা</span>
                    </div>
                    <div class="lp-stars" aria-label="৫ তারকা রেটিং">
                        <i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i>
                    </div>
                    <p class="lp-review__text">দাম কম দেখে সন্দেহ ছিল, কিন্তু কাজ দেখে অবাক হয়েছি। এখন সবাইকে বলি।</p>
                </div>

                <div class="lp-review">
                    <div class="lp-review__head">
                        <div class="lp-review__who">
                            <div class="lp-avatar">নি</div>
                            <span class="lp-review__name">নীলা, সিলেট</span>
                        </div>
                        <span class="lp-badge--verify"><i class="ti ti-rosette-discount-check" aria-hidden="true"></i> ভেরিফায়েড ক্রেতা</span>
                    </div>
                    <div class="lp-stars" aria-label="৫ তারকা রেটিং">
                        <i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i>
                    </div>
                    <p class="lp-review__text">ডেলিভারি খুব দ্রুত পেয়েছি, প্যাকেজিং ভালো ছিল। রিকমেন্ড করব।</p>
                </div>
            </div>

            <div class="lp-stats">
                <div class="lp-stat">
                    <p class="lp-stat__num">৪.৮</p>
                    <p class="lp-stat__label">গড় রেটিং</p>
                </div>
                <div class="lp-stat">
                    <p class="lp-stat__num">২,৪০০+</p>
                    <p class="lp-stat__label">সন্তুষ্ট গ্রাহক</p>
                </div>
                <div class="lp-stat">
                    <p class="lp-stat__num">৬৪ জেলা</p>
                    <p class="lp-stat__label">ডেলিভারি কভারেজ</p>
                </div>
            </div>
        </div>

        {{-- What you get --}}
        <div class="lp-section" data-animate>
            <h2 class="lp-h2 lp-mt-2">অর্ডারে যা যা পাবেন</h2>
            <p class="lp-sub" style="margin-bottom:14px;">সম্পূর্ণ প্যাকেজের আসল মূল্য ৬৩০৳, আজ পাচ্ছেন মাত্র ২৮০৳-তে</p>

            <div class="lp-package">
                <div class="lp-pack">
                    <div class="lp-pack__icon"><i class="ti ti-brush" aria-hidden="true"></i></div>
                    <div class="lp-pack__body">
                        <p class="lp-pack__title">১টি প্রিমিয়াম স্টেইনলেস স্টিল চিরুনি</p>
                        <p class="lp-pack__desc">মরিচামুক্ত, দীর্ঘস্থায়ী, সূক্ষ্ম দাঁত</p>
                    </div>
                    <span class="lp-pack__price lp-pack__price--strike">৫৫০৳</span>
                </div>
                <div class="lp-pack">
                    <div class="lp-pack__icon"><i class="ti ti-book" aria-hidden="true"></i></div>
                    <div class="lp-pack__body">
                        <p class="lp-pack__title">সহজ ব্যবহারবিধি গাইড</p>
                        <p class="lp-pack__desc">সঠিক নিয়মে ব্যবহারের ধাপে ধাপে নির্দেশনা</p>
                    </div>
                    <span class="lp-pack__price lp-pack__price--free">ফ্রি</span>
                </div>
                <div class="lp-pack">
                    <div class="lp-pack__icon"><i class="ti ti-box" aria-hidden="true"></i></div>
                    <div class="lp-pack__body">
                        <p class="lp-pack__title">সিকিওর প্যাকেজিং</p>
                        <p class="lp-pack__desc">ভাঙা বা ক্ষতিগ্রস্ত হওয়ার কোনো ঝুঁকি নেই</p>
                    </div>
                    <span class="lp-pack__price lp-pack__price--free">ফ্রি</span>
                </div>
                <div class="lp-pack">
                    <div class="lp-pack__icon"><i class="ti ti-truck-delivery" aria-hidden="true"></i></div>
                    <div class="lp-pack__body">
                        <p class="lp-pack__title">ক্যাশ অন ডেলিভারি</p>
                        <p class="lp-pack__desc">হাতে পেয়ে টাকা দিন, আগে পেমেন্টের ঝামেলা নেই</p>
                    </div>
                    <span class="lp-pack__price lp-pack__price--free">ফ্রি</span>
                </div>
                <div class="lp-pack">
                    <div class="lp-pack__icon"><i class="ti ti-headset" aria-hidden="true"></i></div>
                    <div class="lp-pack__body">
                        <p class="lp-pack__title">ফোনে সরাসরি সাপোর্ট</p>
                        <p class="lp-pack__desc">ব্যবহারে কোনো সমস্যায় সরাসরি কল করুন</p>
                    </div>
                    <span class="lp-pack__price lp-pack__price--free">ফ্রি</span>
                </div>
            </div>

            <div class="lp-pricerow">
                <span class="lp-pricerow__label">মোট প্যাকেজ মূল্য</span>
                <span class="lp-pricerow__strike">৬৩০৳</span>
            </div>
            <div class="lp-pricerow lp-pricerow--tight">
                <span class="lp-pricerow__label--strong">আজকের মূল্য</span>
                <span class="lp-pricerow__now">২৮০৳</span>
            </div>
        </div>

        {{-- Final CTA / order section --}}
        <div id="lp-order" class="lp-section lp-section--tint" data-animate>
            <div style="text-align:center;">
                <p class="lp-final__was">নিয়মিত মূল্য ৫৫০৳</p>
                <p class="lp-final__now">২৮০৳</p>
                <p class="lp-discount">সর্বোচ্চ ডিসকাউন্ট চলছে</p>
                <a href="tel:{{ $phone }}" class="lp-cta">এই চিরুনিটি অর্ডার করতে চাই</a>
                <p class="lp-final__trust"><i class="ti ti-shield-lock" aria-hidden="true"></i> ইনশাআল্লাহ উকুনমুক্তির প্রতিশ্রুতি</p>
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
