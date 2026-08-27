@php
    Gallery::registerAssets();
    $topAd = is_plugin_active('ads') ? AdsManager::display('before-featured-posts', [], false) : null;
    $popupAdItem = is_plugin_active('ads')
        ? AdsManager::getData(true, true)->where('location', 'header')->sortBy('order')->first()
        : null;
    $popupAd = $popupAdItem ? AdsManager::displayAds($popupAdItem->key) : null;
    $popupAdOrder = $popupAdItem?->order;
    $popupAdKey = $popupAdItem?->key;
    $sidebarAd = is_plugin_active('ads') ? AdsManager::display('bottom-sidebar', [], false) : null;
    $popularPosts = get_popular_posts(8);
@endphp

<main class="socialoud-container socialoud-category-page">
    <section class="socialoud-category-ad @if ($topAd) socialoud-ad-frame-has-ads @endif">
        @if ($topAd)
            <div class="socialoud-ad-slider" data-socialoud-ad-slider>
                <div class="socialoud-ad-slides">
                    {!! $topAd !!}
                </div>
                <div class="socialoud-ad-slider-dots" data-socialoud-ad-dots hidden></div>
                <button type="button" class="socialoud-ad-slider-control socialoud-ad-slider-prev" data-socialoud-ad-prev aria-label="Previous advertisement" hidden>
                    <span aria-hidden="true">‹</span>
                </button>
                <button type="button" class="socialoud-ad-slider-control socialoud-ad-slider-next" data-socialoud-ad-next aria-label="Next advertisement" hidden>
                    <span aria-hidden="true">›</span>
                </button>
            </div>
        @else
            <span class="socialoud-ad-label">ADVERTISEMENT</span>
            <div class="socialoud-ad-placeholder socialoud-ad-placeholder-wide">
                <span>ADVERTISEMENT SPACE</span>
                <strong>Promote your brand on Socialoud</strong>
                <small>Reach readers with a focused editorial placement.</small>
            </div>
        @endif
    </section>

    <section class="socialoud-category-heading">
        <span></span>
        <h1>Gallery</h1>
    </section>

    <section class="socialoud-category-layout">
        <div class="socialoud-category-main">
            @if (isset($galleries) && $galleries->isNotEmpty())
                <div class="socialoud-gallery-grid">
                    @foreach ($galleries as $gallery)
                        <article class="socialoud-gallery-card" data-socialoud-gallery-card @if ($loop->index >= 6) hidden @endif>
                            <a href="{{ $gallery->url }}" class="socialoud-gallery-image">
                                {!! RvMedia::image($gallery->image ?? RvMedia::getDefaultImage(), $gallery->name, 'medium') !!}
                            </a>
                            <div class="socialoud-gallery-info">
                                <h2><a href="{{ $gallery->url }}">{!! BaseHelper::clean($gallery->name) !!}</a></h2>
                                @if ($gallery->user?->name)
                                    <span>By {!! BaseHelper::clean($gallery->user->name) !!}</span>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                @if ($galleries->count() > 6)
                    <button class="socialoud-load-more" type="button" data-socialoud-gallery-load-more>
                        Load more <span aria-hidden="true">↓</span>
                    </button>
                @endif
            @else
                <p class="socialoud-empty">No galleries available yet.</p>
            @endif
        </div>

        <aside class="socialoud-sidebar">
            @if ($popularPosts->isNotEmpty())
                <section class="socialoud-side-card">
                    <div class="socialoud-side-header">
                        <h2>Popular Articles</h2>
                        <a href="{{ route('public.search') }}">View all ›</a>
                    </div>
                    <div class="socialoud-ranking">
                        @foreach ($popularPosts as $post)
                            <a href="{{ $post->url }}" class="socialoud-rank-row">
                                <span>{{ $loop->iteration }}</span>
                                <span class="socialoud-rank-thumb">
                                    {!! RvMedia::image($post->image, $post->name, 'thumb') !!}
                                </span>
                                <strong>{!! BaseHelper::clean($post->name) !!}</strong>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="socialoud-side-card socialoud-sidebar-ad">
                @if ($sidebarAd)
                    {!! $sidebarAd !!}
                @else
                    <div class="socialoud-small-ad"><span>ADVERTISEMENT</span><strong>Your brand here</strong></div>
                    <div class="socialoud-small-ad socialoud-small-ad-purple"><strong>Promote your next story</strong></div>
                    <div class="socialoud-small-ad socialoud-small-ad-orange"><strong>Reach Socialoud readers</strong></div>
                @endif
            </section>
        </aside>
    </section>


    {!! Theme::partial('cover-ad', compact('popupAd', 'popupAdKey', 'popupAdOrder')) !!}
</main>
