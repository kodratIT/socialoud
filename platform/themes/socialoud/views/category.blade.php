@php
    $subcategories = $category->children()->with('slugable')->get();
    $categoryPosts = method_exists($posts, 'getCollection') ? $posts->getCollection() : collect($posts);
    $initialPosts = $categoryPosts->take(10);
    $hasMorePosts = method_exists($posts, 'hasMorePages') && $posts->hasMorePages();
    $topAd = is_plugin_active('ads') ? AdsManager::display('before-featured-posts', [], false) : null;
    $popupAdItem = is_plugin_active('ads')
        ? AdsManager::getData(true, true)->where('location', 'header')->sortBy('order')->first()
        : null;
    $popupAd = $popupAdItem ? AdsManager::displayAds($popupAdItem->key) : null;
    $popupAdOrder = $popupAdItem?->order;
    $popupAdKey = $popupAdItem?->key;
    $sidebarAd = is_plugin_active('ads') ? AdsManager::display('bottom-sidebar', [], false) : null;
    $filterEndpoint = route('socialoud.category.posts', ['category' => $category->getKey()]);
@endphp

<main class="socialoud-container socialoud-category-page">
    <section class="socialoud-category-ad @if ($topAd) socialoud-ad-frame-has-ads @endif">
        @if ($topAd)
            <div class="socialoud-ad-slider" data-socialoud-ad-slider>
                <div class="socialoud-ad-slides">{!! $topAd !!}</div>
                <div class="socialoud-ad-slider-dots" data-socialoud-ad-dots hidden></div>
                <button type="button" class="socialoud-ad-slider-control socialoud-ad-slider-prev" data-socialoud-ad-prev aria-label="Iklan sebelumnya" hidden><span aria-hidden="true">‹</span></button>
                <button type="button" class="socialoud-ad-slider-control socialoud-ad-slider-next" data-socialoud-ad-next aria-label="Iklan berikutnya" hidden><span aria-hidden="true">›</span></button>
            </div>
        @else
            <span class="socialoud-ad-label">ADVERTISEMENT</span>
            <div class="socialoud-ad-placeholder socialoud-ad-placeholder-wide"><span>ADVERTISEMENT SPACE</span><strong>Promote your brand on Socialoud</strong><small>Reach readers with a focused editorial placement.</small></div>
        @endif
    </section>
    <section class="socialoud-category-heading"><span></span><h1>{!! BaseHelper::clean($category->name) !!}</h1></section>

    <div class="socialoud-subcategory-slider">
        <button class="socialoud-subcategory-arrow" type="button" data-subcategory-prev aria-label="Subkategori sebelumnya">‹</button>
        <nav class="socialoud-subcategory-nav" aria-label="Subkategori {!! BaseHelper::clean($category->name) !!}">
            <button class="is-active" type="button" data-category-filter data-category-id="{{ $category->getKey() }}" data-category-all="1" data-category-label="{{ e($category->name) }}">Semua</button>
            @foreach ($subcategories as $subcategory)
                <button type="button" data-category-filter data-category-id="{{ $subcategory->getKey() }}" data-category-label="{{ e($subcategory->name) }}">{!! BaseHelper::clean($subcategory->name) !!}</button>
            @endforeach
        </nav>
        <button class="socialoud-subcategory-arrow" type="button" data-subcategory-next aria-label="Subkategori berikutnya">›</button>
    </div>

    <section class="socialoud-category-layout">
        <div class="socialoud-category-main">
            <div class="socialoud-category-list" data-category-post-list data-filter-endpoint="{{ $filterEndpoint }}" data-category-label="{{ e($category->name) }}">
                {!! Theme::partial('category-post-list', ['posts' => $initialPosts, 'category' => $category, 'label' => $category->name]) !!}
            </div>
            @if ($categoryPosts->count() > 10 || $hasMorePosts)
                <button class="socialoud-load-more" type="button" data-socialoud-load-more data-next-url="{{ $filterEndpoint }}?all=1&page=2">Lihat lebih banyak <span aria-hidden="true">↓</span></button>
            @endif
        </div>

        <aside class="socialoud-sidebar">
            @php $popularPosts = get_popular_posts(8); @endphp
            @if ($popularPosts->isNotEmpty())
                <section class="socialoud-side-card"><div class="socialoud-side-header"><h2>Artikel Terpopuler</h2><a href="{{ route('public.search') }}">Lihat semua ›</a></div><div class="socialoud-ranking">
                    @foreach ($popularPosts as $post)
                        <a href="{{ $post->url }}" class="socialoud-rank-row"><span>{{ $loop->iteration }}</span><span class="socialoud-rank-thumb">{!! RvMedia::image($post->image, $post->name, 'thumb', attributes: ['width' => 44, 'height' => 44, 'decoding' => 'async']) !!}</span><strong>{!! BaseHelper::clean($post->name) !!}</strong></a>
                    @endforeach
                </div></section>
            @endif
            <section class="socialoud-side-card socialoud-sidebar-ad">@if ($sidebarAd){!! $sidebarAd !!}@else<div class="socialoud-small-ad"><span>ADVERTISEMENT</span><strong>Your brand here</strong></div><div class="socialoud-small-ad socialoud-small-ad-purple"><strong>Promote your next story</strong></div><div class="socialoud-small-ad socialoud-small-ad-orange"><strong>Reach Socialoud readers</strong></div>@endif</section>
        </aside>
    </section>
    {!! Theme::partial('cover-ad', compact('popupAd', 'popupAdKey', 'popupAdOrder')) !!}
</main>