@extends('layouts.frontend.app')
@push('meta')
<meta name='description' content="Blog — Latest articles and updates"/>
<meta name='keywords' content="@foreach($blogs as $blog){{$blog->title.', '}}@endforeach" />
@endpush

@section('title', 'Blog')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');

    /* ── HERO ── */
    .bl-hero {
        padding: 64px 24px 52px;
        text-align: center;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
    }
    .bl-hero-eyebrow {
        margin: 0 0 14px;
        font-size: 11px;
        letter-spacing: 6px;
        text-transform: uppercase;
        color: #888;
        font-weight: 500;
    }
    .bl-hero-title {
        margin: 0 0 12px;
        font-family: 'Cinzel Decorative', Georgia, serif;
        font-size: 44px;
        font-weight: 700;
        color: #111;
        line-height: 1;
        text-transform: uppercase;
    }
    .bl-hero-sub {
        margin: 0;
        font-size: 14px;
        color: #999;
    }

    /* ── NAV TABS ── */
    .bl-nav {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 0 24px;
        background: #fff;
        border-bottom: 1px solid #f0f0f0;
        max-width: 1400px;
        margin: 0 auto;
    }
    .bl-nav a {
        display: inline-block;
        padding: 14px 20px;
        font-size: 13px;
        font-weight: 500;
        color: #888;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        transition: all .2s;
        letter-spacing: 0.3px;
    }
    .bl-nav a:hover { color: #111; }
    .bl-nav a.active { color: #111; border-bottom-color: #111; font-weight: 600; }

    /* ── PAGE BODY ── */
    .bl-body {
        max-width: 1400px;
        margin: 0 auto;
        padding: 52px 24px 80px;
    }

    /* ── FEATURED POST ── */
    .bl-featured {
        display: grid;
        grid-template-columns: 1fr 420px;
        gap: 0;
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #ebebeb;
        margin-bottom: 56px;
        box-shadow: 0 4px 20px rgba(0,0,0,.06);
        transition: box-shadow .3s ease, transform .3s ease;
        text-decoration: none;
        color: inherit;
    }
    .bl-featured:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,.12);
        transform: translateY(-4px);
    }
    .bl-featured-img-wrap {
        position: relative;
        overflow: hidden;
        background: #f5f5f5;
        min-height: 420px;
    }
    .bl-featured-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .6s ease;
    }
    .bl-featured:hover .bl-featured-img-wrap img { transform: scale(1.04); }
    .bl-featured-badge {
        position: absolute;
        top: 18px;
        left: 18px;
        background: #111;
        color: #fff;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 20px;
        z-index: 1;
    }
    .bl-featured-body {
        padding: 40px 36px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #fff;
    }
    .bl-featured-author {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .bl-author-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        background: #eee;
        flex-shrink: 0;
    }
    .bl-author-name {
        font-size: 13px;
        font-weight: 600;
        color: #111;
    }
    .bl-author-date {
        font-size: 11.5px;
        color: #999;
        margin-top: 2px;
    }
    .bl-featured-title {
        font-size: 26px;
        font-weight: 700;
        color: #111;
        line-height: 1.35;
        margin: 0 0 16px;
        letter-spacing: -0.3px;
    }
    .bl-featured-excerpt {
        font-size: 14px;
        color: #666;
        line-height: 1.75;
        margin: 0 0 28px;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bl-read-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #111;
        color: #fff;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        letter-spacing: 0.3px;
        transition: all .25s;
        align-self: flex-start;
    }
    .bl-read-btn:hover { background: #333; transform: translateX(3px); color: #fff; }
    .bl-read-btn svg { transition: transform .2s; }
    .bl-read-btn:hover svg { transform: translateX(3px); }

    /* ── SECTION DIVIDER ── */
    .bl-section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #888;
        margin: 0 0 28px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .bl-section-title::before,
    .bl-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e8e8e8;
    }

    /* ── BLOG GRID ── */
    .bl-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    /* ── BLOG CARD ── */
    .bl-card {
        display: flex;
        flex-direction: column;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #ebebeb;
        text-decoration: none;
        color: inherit;
        transition: all .3s ease;
        box-shadow: 0 1px 6px rgba(0,0,0,.04);
    }
    .bl-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 32px rgba(0,0,0,.10);
        border-color: #ddd;
    }
    .bl-card-img-wrap {
        position: relative;
        overflow: hidden;
        background: #f5f5f5;
        height: 220px;
        flex-shrink: 0;
    }
    .bl-card-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .5s ease;
    }
    .bl-card:hover .bl-card-img-wrap img { transform: scale(1.06); }
    .bl-card-body {
        padding: 22px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .bl-card-author {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
    }
    .bl-card-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        background: #eee;
        flex-shrink: 0;
    }
    .bl-card-author-info { min-width: 0; }
    .bl-card-author-name {
        font-size: 12px;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bl-card-date {
        font-size: 11px;
        color: #aaa;
    }
    .bl-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        line-height: 1.4;
        margin: 0 0 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bl-card-excerpt {
        font-size: 13px;
        color: #777;
        line-height: 1.7;
        margin: 0 0 18px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    .bl-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 14px;
        border-top: 1px solid #f0f0f0;
        margin-top: auto;
    }
    .bl-card-cta {
        font-size: 12px;
        font-weight: 600;
        color: #111;
        letter-spacing: 0.3px;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: gap .2s;
    }
    .bl-card:hover .bl-card-cta { gap: 8px; }

    /* ── EMPTY STATE ── */
    .bl-empty {
        text-align: center;
        padding: 80px 24px;
        color: #999;
    }
    .bl-empty h3 { font-size: 18px; font-weight: 600; color: #333; margin: 0 0 8px; }
    .bl-empty p { font-size: 14px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) {
        .bl-grid { grid-template-columns: repeat(2, 1fr); }
        .bl-featured { grid-template-columns: 1fr 360px; }
    }
    @media (max-width: 860px) {
        .bl-featured { grid-template-columns: 1fr; }
        .bl-featured-img-wrap { min-height: 280px; max-height: 320px; }
        .bl-featured-body { padding: 28px 24px; }
        .bl-featured-title { font-size: 20px; }
    }
    @media (max-width: 768px) {
        .bl-hero-title { font-size: 30px; }
        .bl-hero { padding: 42px 20px 34px; }
        .bl-body { padding: 36px 16px 64px; }
        .bl-nav { padding: 0 16px; }
        .bl-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .bl-card-img-wrap { height: 180px; }
    }
    @media (max-width: 540px) {
        .bl-grid { grid-template-columns: 1fr; }
        .bl-hero-title { font-size: 24px; }
        .bl-featured-title { font-size: 18px; }
    }
</style>

{{-- ====== HERO ====== --}}
<div class="bl-hero">
    <p class="bl-hero-eyebrow">Ideas & Inspiration</p>
    <h1 class="bl-hero-title">The Journal</h1>
    <p class="bl-hero-sub">Stories, tips, and insights from our community</p>
</div>

{{-- ====== NAV TABS ====== --}}
<nav class="bl-nav">
    <a href="{{ route('campaing') }}" class="{{ Request::is('campaing*') ? 'active' : '' }}">Campaign</a>
    <a href="{{ route('blogs') }}" class="{{ Request::is('blogs*') ? 'active' : '' }}">All Posts</a>
    <a href="{{ route('blog.ceo') }}" class="{{ Request::is('blog/ceo*') ? 'active' : '' }}">CEO</a>
</nav>

{{-- ====== BODY ====== --}}
<div class="bl-body">

    @if($blogs->isEmpty())
        <div class="bl-empty">
            <h3>No posts yet</h3>
            <p>Check back soon for new articles and updates.</p>
        </div>
    @else

        @php $featured = $blogs->first(); $rest = $blogs->skip(1); @endphp

        {{-- FEATURED POST --}}
        <a href="{{ route('blog.show', ['blog' => $featured->id]) }}" class="bl-featured">
            <div class="bl-featured-img-wrap">
                <span class="bl-featured-badge">Featured</span>
                <img src="{{ asset('uploads/blogs/' . $featured->thumbnail) }}" alt="{{ $featured->title }}">
            </div>
            <div class="bl-featured-body">
                <div class="bl-featured-author">
                    <img class="bl-author-avatar"
                         src="{{ asset('uploads/admin/' . optional($featured->user)->avatar) }}"
                         alt="{{ optional($featured->user)->name }}"
                         onerror="this.style.background='#eee';this.style.visibility='hidden'">
                    <div>
                        <div class="bl-author-name">{{ optional($featured->user)->name ?? 'Author' }}</div>
                        <div class="bl-author-date">{{ $featured->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
                <h2 class="bl-featured-title">{{ $featured->title }}</h2>
                <p class="bl-featured-excerpt">{{ strip_tags($featured->description) }}</p>
                <span class="bl-read-btn">
                    Read Article
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M2 7h10M7 2l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
        </a>

        {{-- REMAINING POSTS --}}
        @if($rest->count())
            <p class="bl-section-title">More Articles</p>
            <div class="bl-grid">
                @foreach($rest as $blog)
                <a href="{{ route('blog.show', ['blog' => $blog->id]) }}" class="bl-card">
                    <div class="bl-card-img-wrap">
                        <img src="{{ asset('uploads/blogs/' . $blog->thumbnail) }}" alt="{{ $blog->title }}">
                    </div>
                    <div class="bl-card-body">
                        <div class="bl-card-author">
                            <img class="bl-card-avatar"
                                 src="{{ asset('uploads/admin/' . optional($blog->user)->avatar) }}"
                                 alt="{{ optional($blog->user)->name }}"
                                 onerror="this.style.background='#eee';this.style.visibility='hidden'">
                            <div class="bl-card-author-info">
                                <div class="bl-card-author-name">{{ optional($blog->user)->name ?? 'Author' }}</div>
                                <div class="bl-card-date">{{ $blog->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <h3 class="bl-card-title">{{ $blog->title }}</h3>
                        <p class="bl-card-excerpt">{{ Str::limit(strip_tags($blog->description), 120) }}</p>
                        <div class="bl-card-footer">
                            <span class="bl-card-cta">
                                Read More
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                                    <path d="M2 6h8M6 2l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <span style="font-size:11px;color:#bbb;">{{ $blog->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif

    @endif

</div>

@endsection
