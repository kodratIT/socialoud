@php
    $topAd = is_plugin_active('ads') ? AdsManager::display('top-single-page', [], false) : null;
    $popupAdItem = is_plugin_active('ads')
        ? AdsManager::getData(true, true)->where('location', 'header')->sortBy('order')->first()
        : null;
    $popupAd = $popupAdItem ? AdsManager::displayAds($popupAdItem->key) : null;
    $popupAdOrder = $popupAdItem?->order;
    $popupAdKey = $popupAdItem?->key;
    $sidebarAd = is_plugin_active('ads') ? AdsManager::display('bottom-sidebar', [], false) : null;
    $popularPosts = get_popular_posts(8);
    $relatedPosts = get_related_posts($post->id, 5);
    $category = $post->first_category;
    $authorName = class_exists($post->author_type) && $post->author ? $post->author->name : '-';
    $editorValue = $post->getMetaData('editor', true);
    $editorName = $authorName;
    if ($editorValue) {
        $editorName = is_numeric($editorValue)
            ? (\Botble\Author\Models\Author::query()->whereKey((int) $editorValue)->value('name') ?: $authorName)
            : $editorValue;
    }
@endphp

<main class="socialoud-container socialoud-article-page">
    <section class="socialoud-article-ad @if ($topAd) socialoud-ad-frame-has-ads @endif">
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

    <section class="socialoud-article-layout">
        <article class="socialoud-article-main">
            <div class="socialoud-article-breadcrumbs">
                <a href="{{ route('public.single') }}">Beranda</a>
                @if ($category)
                    <span>/</span><a href="{{ $category->url }}">{!! BaseHelper::clean($category->name) !!}</a>
                @endif
            </div>

            @if ($category)
                <div class="socialoud-article-category">{!! BaseHelper::clean($category->name) !!}</div>
            @endif
            <h1 class="socialoud-article-title">{!! BaseHelper::clean($post->name) !!}</h1>
            <div class="socialoud-article-byline">
                <span><strong>Penulis:</strong> {{ $authorName }}</span>
                <span><strong>Editor:</strong> {{ $editorName }}</span>
                <span>{{ Theme::formatDate($post->created_at) }} • {{ number_format($post->views) }} views</span>
            </div>

            @if ($post->getMetaData('display_featured_image_at_the_top', true))
                <figure class="socialoud-article-cover">
                    {!! RvMedia::image($post->image, $post->name) !!}
                </figure>
            @endif

            @if ($post->format_type === 'video')
                @php $videoUrl = str_replace('watch?v=', 'embed/', MetaBox::getMetaData($post, 'video_link', true)); @endphp
                @if ($videoUrl)
                    <div class="socialoud-article-video"><iframe allowfullscreen src="{{ $videoUrl }}" title="{{ $post->name }}"></iframe></div>
                @endif
            @endif

            @if (defined('GALLERY_MODULE_SCREEN_NAME') && !empty($galleries = gallery_meta_data($post)))
                <div class="socialoud-article-gallery">{!! render_object_gallery($galleries, $category?->name ?: __('Uncategorized')) !!}</div>
            @endif

            <div class="socialoud-article-content">
                <div class="ck-content">{!! BaseHelper::clean($post->content) !!}</div>
            </div>

            <div class="socialoud-article-share">
                <span>Bagikan:</span>
                {!! Theme::renderSocialSharing($post->url, SeoHelper::getDescription(), $post->image) !!}
            </div>

            @if ($post->tags && $post->tags->isNotEmpty())
                <div class="socialoud-article-tags">
                    <strong>Tags:</strong>
                    @foreach ($post->tags as $tag)
                        <a href="{{ $tag->url }}">{!! BaseHelper::clean($tag->name) !!}</a>
                    @endforeach
                </div>
            @endif

            <section class="socialoud-article-comments">
                {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $post) !!}
            </section>

            @if ($relatedPosts->isNotEmpty())
                <section class="socialoud-related-posts">
                    <h2 class="socialoud-section-title">Artikel Terkait</h2>
                    <div class="socialoud-related-list">
                        @foreach ($relatedPosts as $relatedPost)
                            <a href="{{ $relatedPost->url }}">{!! BaseHelper::clean($relatedPost->name) !!}<span>›</span></a>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        <aside class="socialoud-sidebar">
            @if ($popularPosts->isNotEmpty())
                <section class="socialoud-side-card">
                    <div class="socialoud-side-header">
                        <h2>Artikel Terpopuler</h2>
                        <a href="{{ route('public.search') }}">Lihat semua ›</a>
                    </div>
                    <div class="socialoud-ranking">
                        @foreach ($popularPosts as $popularPost)
                            <a href="{{ $popularPost->url }}" class="socialoud-rank-row">
                                <span>{{ $loop->iteration }}</span>
                                <span class="socialoud-rank-thumb">
                                    {!! RvMedia::image($popularPost->image, $popularPost->name, 'thumb') !!}
                                </span>
                                <strong>{!! BaseHelper::clean($popularPost->name) !!}</strong>
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
