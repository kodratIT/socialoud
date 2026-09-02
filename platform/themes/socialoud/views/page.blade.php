@if ($page->template === 'homepage' && is_plugin_active('blog'))
    @php
        $featuredPosts = get_featured_posts(7, ['categories']);
        $latestPosts = get_latest_posts(14, [], ['categories']);
        $videoPosts = app(\Botble\Blog\Repositories\Interfaces\PostInterface::class)->advancedGet([
            'take'      => 6,
            'condition' => ['status' => \Botble\Base\Enums\BaseStatusEnum::PUBLISHED, 'format_type' => 'video'],
            'order_by'  => ['created_at' => 'DESC'],
            'with'      => ['slugable'],
        ]);
        $heroPosts = $featuredPosts->merge($latestPosts)->unique('id')->take(7);
        $popularPosts = get_popular_posts(8);
    @endphp

    <main class="socialoud-container socialoud-home">
        @php
            $topAd = is_plugin_active('ads') ? AdsManager::display('before-featured-posts', [], false) : null;
            $popupAdItem = is_plugin_active('ads')
                ? AdsManager::getData(true, true)->where('location', 'header')->sortBy('order')->first()
                : null;
            $popupAd = $popupAdItem ? AdsManager::displayAds($popupAdItem->key) : null;
            $popupAdOrder = $popupAdItem?->order;
            $popupAdKey = $popupAdItem?->key;
        @endphp
        <section class="socialoud-ad-frame @if ($topAd) socialoud-ad-frame-has-ads @endif">
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


        @if ($heroPosts->isNotEmpty())
            <section class="socialoud-hero-grid" aria-label="{{ __('Featured stories') }}">
                @foreach ($heroPosts->take(2) as $post)
                    <article class="socialoud-hero-card @if ($loop->index === 1) socialoud-hero-card-small @endif">
                        {!! RvMedia::image($post->image, $post->name, attributes: [
                            'class' => 'socialoud-cover',
                            'width' => 1200,
                            'height' => 405,
                            'loading' => $loop->first ? 'eager' : 'lazy',
                            'fetchpriority' => $loop->first ? 'high' : 'auto',
                            'decoding' => 'async',
                        ]) !!}
                        <div class="socialoud-hero-content">
                            <span class="socialoud-badge">{{ $post->first_category?->name ?: __('News') }}</span>
                            <h1 class="socialoud-hero-title @if ($loop->index === 1) socialoud-hero-title-small @endif">
                                <a href="{{ $post->url }}">{!! BaseHelper::clean($post->name) !!}</a>
                            </h1>
                            @if ($loop->first)
                                <p class="socialoud-hero-description">{{ Str::limit($post->description, 150) }}</p>
                            @endif
                            <div class="socialoud-meta">
                                {{ Theme::formatDate($post->created_at) }}
                                @if ($post->time_reading)
                                    <span>•</span> {{ $post->time_reading }} {{ __('min read') }}
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            @if ($heroPosts->count() > 2)
                <section class="socialoud-mini-grid" aria-label="{{ __('More featured stories') }}">
                    @foreach ($heroPosts->slice(2, 5) as $post)
                        <article class="socialoud-mini-card">
                            <a href="{{ $post->url }}" class="socialoud-mini-image">
                                {!! RvMedia::image($post->image, $post->name, attributes: ['width' => 240, 'height' => 118, 'decoding' => 'async']) !!}
                            </a>
                            <span class="socialoud-badge socialoud-badge-small">{{ $post->first_category?->name ?: __('News') }}</span>
                            <h2><a href="{{ $post->url }}">{!! BaseHelper::clean($post->name) !!}</a></h2>
                            <div class="socialoud-meta">{{ Theme::formatDate($post->created_at) }}</div>
                        </article>
                    @endforeach
                </section>
            @endif
        @endif


        <section class="socialoud-content-layout" id="terkini">
            <div>
                <h2 class="socialoud-section-title">TERKINI</h2>
                <div class="socialoud-news-list" data-socialoud-home-post-list>
                    @forelse ($latestPosts as $post)
                        <article class="socialoud-news-row">
                            <a href="{{ $post->url }}" class="socialoud-news-image">
                                {!! RvMedia::image($post->image, $post->name, attributes: ['width' => 180, 'height' => 94, 'decoding' => 'async']) !!}
                            </a>
                            <div>
                                <div class="socialoud-category">{{ $post->first_category?->name ?: __('News') }} <span>•</span></div>
                                <h2><a href="{{ $post->url }}">{!! BaseHelper::clean($post->name) !!}</a></h2>
                                @if ($post->description)
                                    <p>{{ Str::limit($post->description, 110) }}</p>
                                @endif
                            </div>
                            <div class="socialoud-time">{{ Theme::formatDate($post->created_at) }}</div>
                        </article>
                    @empty
                        <p class="socialoud-empty">{{ __('No posts found.') }}</p>
                    @endforelse
                </div>

                @php
                    $contentAd = is_plugin_active('ads') ? AdsManager::display('after-recent-posts') : null;
                @endphp
                @if ($contentAd)
                    <section class="socialoud-academy-ad">
                        {!! $contentAd !!}
                    </section>
                @endif
                <button
                    class="socialoud-load-more socialoud-home-load-more"
                    type="button"
                    data-socialoud-home-load-more
                    data-next-url="{{ route('socialoud.home.posts', ['page' => 2]) }}"
                >
                    Lihat lebih banyak <span aria-hidden="true">↓</span>
                </button>
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
                                        {!! RvMedia::image($post->image, $post->name, 'thumb', attributes: ['width' => 44, 'height' => 44, 'decoding' => 'async']) !!}
                                    </span>
                                    <strong>{!! BaseHelper::clean($post->name) !!}</strong>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if ($videoPosts->isNotEmpty())
                    <section class="socialoud-side-card">
                        <div class="socialoud-side-header">
                            <h2>Local <span>loud</span></h2>
                            <a href="{{ route('public.search') }}">Lihat semua ›</a>
                        </div>
                        <div class="socialoud-video-list">
                            @foreach ($videoPosts as $post)
                                <a href="{{ $post->url }}" class="socialoud-video-card">
                                    <span class="socialoud-video-thumb" aria-hidden="true">
                                        {!! RvMedia::image($post->image, $post->name, 'thumb', attributes: ['width' => 84, 'height' => 52, 'decoding' => 'async']) !!}
                                    </span>
                                    <strong>{!! BaseHelper::clean($post->name) !!}</strong>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif

                @php
                    $sidebarAd = is_plugin_active('ads') ? AdsManager::display('bottom-sidebar', [], false) : null;
                @endphp
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
@elseif ($page->template != 'homepage')
    @php
        $topAd = is_plugin_active('ads') ? AdsManager::display('top-single-page') : null;
        $popupAdItem = is_plugin_active('ads')
            ? AdsManager::getData(true, true)->where('location', 'header')->sortBy('order')->first()
            : null;
        $popupAd = $popupAdItem ? AdsManager::displayAds($popupAdItem->key) : null;
        $popupAdOrder = $popupAdItem?->order;
        $popupAdKey = $popupAdItem?->key;
        $sidebarAd = is_plugin_active('ads') ? AdsManager::display('bottom-sidebar', [], false) : null;
        $popularPosts = is_plugin_active('blog') ? get_popular_posts(8) : collect();
    @endphp

    <section class="socialoud-static-page">
        <section class="socialoud-container">
            <div class="socialoud-article-ad socialoud-static-page-ad">
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
            </div>

            <div class="socialoud-article-layout socialoud-static-page-layout">
                <article class="socialoud-article-main socialoud-static-page-main">
                    <div class="socialoud-article-breadcrumbs">
                        <a href="{{ route('public.single') }}">Beranda</a><span>/</span><span>{!! BaseHelper::clean($page->name) !!}</span>
                    </div>
                    <div class="socialoud-category-heading">
                        <span></span>
                        <h1>{!! BaseHelper::clean($page->name) !!}</h1>
                    </div>
                    @if (defined('GALLERY_MODULE_SCREEN_NAME') && !empty($galleries = gallery_meta_data($page)))
                        <div class="socialoud-article-gallery">{!! render_object_gallery($galleries) !!}</div>
                    @endif
                    <div class="socialoud-article-content socialoud-static-page-content">
                        {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, Html::tag('div', BaseHelper::clean($page->content), ['class' => 'ck-content'])->toHtml(), $page) !!}
                    </div>
                    @if (is_plugin_active('ads'))
                        {!! AdsManager::display('bottom-single-page', ['style' => 'margin-top: 20px']) !!}
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
                                @foreach ($popularPosts as $post)
                                    <a href="{{ $post->url }}" class="socialoud-rank-row">
                                        <span>{{ $loop->iteration }}</span>
                                        <span class="socialoud-rank-thumb">
                                            {!! RvMedia::image($post->image, $post->name, 'thumb', attributes: ['width' => 44, 'height' => 44, 'decoding' => 'async']) !!}
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
            </div>
        </section>
        {!! Theme::partial('cover-ad', compact('popupAd', 'popupAdKey', 'popupAdOrder')) !!}
    </section>
@else
    {!! apply_filters(PAGE_FILTER_FRONT_PAGE_CONTENT, Html::tag('div', BaseHelper::clean($page->content), ['class' => 'ck-content'])->toHtml(), $page) !!}
@endif
