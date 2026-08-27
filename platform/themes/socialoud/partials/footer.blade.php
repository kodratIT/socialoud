@php
    $footerPages = class_exists(\Botble\Page\Models\Page::class)
        ? \Botble\Page\Models\Page::query()->wherePublished()->where(fn ($query) => $query->whereNull('template')->orWhere('template', '!=', 'homepage'))->with('slugable')->orderBy('id')->get()
        : collect();
@endphp

<footer class="socialoud-footer">
    <div class="socialoud-container socialoud-footer-inner">
        <a href="{{ route('public.single') }}" class="socialoud-footer-brand" aria-label="{{ theme_option('site_title', 'Socialoud') }}">
            <img class="socialoud-logo socialoud-logo-white" src="{{ Theme::asset()->url('images/branding/socialoud-white.png') }}" alt="{{ theme_option('site_title', 'Socialoud') }}">
            <img class="socialoud-logo socialoud-logo-red" src="{{ Theme::asset()->url('images/branding/socialoud-red.png') }}" alt="" aria-hidden="true">
        </a>
        <div class="socialoud-footer-center">
            <nav class="socialoud-footer-links" aria-label="{{ __('Footer navigation') }}">
                @foreach ($footerPages as $page)
                    <a href="{{ $page->url }}">{{ $page->name }}</a>
                @endforeach
            </nav>
        </div>
        <div class="socialoud-socials" aria-label="{{ __('Social media') }}">
            <a href="#" aria-label="Instagram">◎</a>
            <a href="#" aria-label="X">𝕏</a>
            <a href="#" aria-label="Facebook">f</a>
            <a href="#" aria-label="TikTok">♪</a>
            <a href="#" aria-label="YouTube">▶</a>
        </div>
    </div>
    <div class="socialoud-copyright">{!! Theme::getSiteCopyright() !!}</div>
</footer>

{!! Theme::footer() !!}
<div id="fb-root"></div>
<div id="socialoud-runtime"></div>
<script src="{{ Theme::asset()->url('js/socialoud.js') }}?v={{ filemtime(public_path('themes/socialoud/js/socialoud.js')) }}"></script>
</body>
</html>
