@forelse ($posts as $post)
    <article class="socialoud-news-row">
        <a href="{{ $post->url }}" class="socialoud-news-image">
            {!! RvMedia::image($post->image, $post->name) !!}
        </a>
        <div>
            <div class="socialoud-category">{!! BaseHelper::clean($category->name) !!} <span>•</span></div>
            <h2><a href="{{ $post->url }}">{!! BaseHelper::clean($post->name) !!}</a></h2>
            @if ($post->description)
                <p>{{ Str::limit($post->description, 110) }}</p>
            @endif
        </div>
        <div class="socialoud-time">{{ Theme::formatDate($post->created_at) }}</div>
    </article>
@empty
    <p class="socialoud-empty">Belum ada berita di kategori ini.</p>
@endforelse
