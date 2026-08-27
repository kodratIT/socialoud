@php
    $query = Request::input('q');
    $topAd = is_plugin_active('ads') ? AdsManager::display('top-single-page') : null;
    $popupAdItem = is_plugin_active('ads')
        ? AdsManager::getData(true, true)->where('location', 'header')->sortBy('order')->first()
        : null;
    $popupAd = $popupAdItem ? AdsManager::displayAds($popupAdItem->key) : null;
    $popupAdOrder = $popupAdItem?->order;
    $popupAdKey = $popupAdItem?->key;
    $sidebarAd = is_plugin_active('ads') ? AdsManager::display('bottom-sidebar', [], false) : null;
    $popularPosts = get_popular_posts(8);
@endphp

<main class="socialoud-container socialoud-search-page">
    <section class="socialoud-search-ad">
        @if ($topAd)
            {!! $topAd !!}
        @else
            <span class="socialoud-ad-label">ADVERTISEMENT</span>
            <div class="socialoud-ad-placeholder socialoud-ad-placeholder-wide">
                <span>ADVERTISEMENT SPACE</span>
                <strong>Promote your brand on Socialoud</strong>
                <small>Reach readers with a focused editorial placement.</small>
            </div>
        @endif
    </section>

    <section class="socialoud-search-layout">
        <div class="socialoud-search-main">
            <div class="socialoud-category-heading">
                <span></span>
                <h1>Hasil Pencarian</h1>
            </div>
            <p class="socialoud-search-query">Menampilkan hasil untuk: <strong>“{{ $query }}”</strong></p>

            <div class="socialoud-search-list">
                @forelse ($posts as $post)
                    <article class="socialoud-news-row">
                        <a href="{{ $post->url }}" class="socialoud-news-image">
                            {!! RvMedia::image($post->image, $post->name) !!}
                        </a>
                        <div>
                            <div class="socialoud-category">Artikel <span>•</span></div>
                            <h2><a href="{{ $post->url }}">{!! BaseHelper::clean($post->name) !!}</a></h2>
                            @if ($post->description)
                                <p>{{ Str::limit($post->description, 110) }}</p>
                            @endif
                        </div>
                        <div class="socialoud-time">{{ Theme::formatDate($post->created_at) }}</div>
                    </article>
                @empty
                    <div class="socialoud-search-empty">
                        <strong>Tidak ada artikel ditemukan.</strong>
                        <span>Coba gunakan kata kunci pencarian yang berbeda.</span>
                    </div>
                @endforelse
            </div>

            @if (method_exists($posts, 'links'))
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
