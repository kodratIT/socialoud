@php
    Gallery::registerAssets();
    $topAd = is_plugin_active('ads') ? AdsManager::display('top-single-page', [], false) : null;
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
        <h1>{!! BaseHelper::clean($gallery->name) !!}</h1>
    </section>

    <section class="socialoud-category-layout">
        <article class="socialoud-category-main">
            <div class="socialoud-gallery-detail">
                @if ($gallery->description)
                    <div class="socialoud-gallery-description">{!! BaseHelper::clean($gallery->description) !!}</div>
                @endif

                @php $galleryImages = gallery_meta_data($gallery); @endphp
                @if (!empty($galleryImages))
                    <div class="socialoud-gallery-photo-grid">
                        @foreach ($galleryImages as $image)
                            @continue(!$image || !Arr::get($image, 'img'))
                            @php
                                $imageUrl = RvMedia::getImageUrl(Arr::get($image, 'img'));
                                $imageTitle = BaseHelper::clean(Arr::get($image, 'description')) ?: $gallery->name;
                            @endphp
                            <a href="{{ $imageUrl }}" class="socialoud-gallery-photo" data-socialoud-gallery-preview data-gallery-image="{{ $imageUrl }}" data-gallery-title="{{ $imageTitle }}">
                                {!! RvMedia::image(Arr::get($image, 'img'), $imageTitle, 'medium') !!}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="socialoud-empty">No images available in this gallery.</p>
                @endif

                <section class="socialoud-article-comments">
                    {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $gallery) !!}
                </section>
            </div>
        </article>

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

    <div class="socialoud-gallery-modal" data-socialoud-gallery-modal hidden role="dialog" aria-modal="true" aria-labelledby="socialoud-gallery-modal-title">
        <div class="socialoud-gallery-modal-backdrop" data-socialoud-gallery-modal-close></div>
        <div class="socialoud-gallery-modal-card">
            <button type="button" class="socialoud-gallery-modal-close" data-socialoud-gallery-modal-close aria-label="Close gallery preview">×</button>
            <img data-socialoud-gallery-preview-image src="" alt="" hidden>
            <h2 id="socialoud-gallery-modal-title" data-socialoud-gallery-preview-title></h2>
        </div>
    </div>

    {!! Theme::partial('cover-ad', compact('popupAd', 'popupAdKey', 'popupAdOrder')) !!}
</main>
