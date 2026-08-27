@php
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
                <button type="button" class="socialoud-ad-slider-control socialoud-ad-slider-prev" data-socialoud-ad-prev aria-label="Iklan sebelumnya" hidden>
                    <span aria-hidden="true">‹</span>
                </button>
                <button type="button" class="socialoud-ad-slider-control socialoud-ad-slider-next" data-socialoud-ad-next aria-label="Iklan berikutnya" hidden>
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
        <h1>Tag: {!! BaseHelper::clean($tag->name) !!}</h1>
    </section>

    <section class="socialoud-category-layout">
        <div class="socialoud-category-main">
            <div class="socialoud-category-list">
                {!! Theme::partial('category-post-list', ['posts' => $posts, 'label' => $tag->name, 'emptyMessage' => 'Belum ada berita dengan tag ini.']) !!}
            </div>

            @if (method_exists($posts, 'links') && $posts->isNotEmpty())
                <div class="socialoud-pagination">{!! $posts->links() !!}</div>
            @endif
        </div>

        <aside class="socialoud-sidebar">
            @if ($popularPosts->isNotEmpty())
                <section class="socialoud-side-card">
                    <div class="socialoud-side-header">
                        <h2>Artikel Terpopuler</h2>
                        <a href="{{ route('public.search') }}">Lihat semua ›</a>
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
