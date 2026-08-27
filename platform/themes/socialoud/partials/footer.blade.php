@php
    $footerPages = class_exists(\Botble\Page\Models\Page::class)
        ? \Botble\Page\Models\Page::query()->wherePublished()->where(fn ($query) => $query->whereNull('template')->orWhere('template', '!=', 'homepage'))->with('slugable')->orderBy('id')->get()
        : collect();
    $footerCategories = class_exists(\Botble\Blog\Models\Category::class)
        ? \Botble\Blog\Models\Category::query()->wherePublished()->where(fn ($query) => $query->whereNull('parent_id')->orWhere('parent_id', 0))->withCount(['posts' => fn ($query) => $query->wherePublished()])->with('slugable')->orderByDesc('posts_count')->orderBy('name')->take(6)->get()
        : collect();
    $footerDescription = trim((string) theme_option('socialoud_footer_description'));
    $companyAddress = trim((string) theme_option('socialoud_company_address'));
    $companyPhone = trim((string) theme_option('socialoud_company_phone'));
    $companyEmail = trim((string) theme_option('socialoud_company_email'));
    $hasCompanyContact = $companyAddress || $companyPhone || $companyEmail;
@endphp

<footer class="socialoud-footer">
    <div class="socialoud-footer-main">
        <div class="socialoud-container socialoud-footer-grid">
            <section class="socialoud-footer-brand-block">
                <a href="{{ route('public.single') }}" class="socialoud-footer-brand" aria-label="{{ theme_option('site_title', 'Socialoud') }}">
                    <img class="socialoud-logo socialoud-logo-white" src="{{ Theme::asset()->url('images/branding/socialoud-white.png') }}" alt="{{ theme_option('site_title', 'Socialoud') }}">
                    <img class="socialoud-logo socialoud-logo-red" src="{{ Theme::asset()->url('images/branding/socialoud-red.png') }}" alt="" aria-hidden="true">
                </a>
                @if ($footerDescription)
                    <p class="socialoud-footer-description">{{ $footerDescription }}</p>
                @endif
            </section>

            @if ($hasCompanyContact)
                <section class="socialoud-footer-contact">
                    <h2 class="socialoud-footer-heading">Hubungi Kami</h2>
                    <div class="socialoud-footer-contact-list">
                        @if ($companyAddress)
                            <address class="socialoud-footer-contact-item">
                                <span>Alamat</span>
                                <strong>{!! nl2br(e($companyAddress)) !!}</strong>
                            </address>
                        @endif
                        @if ($companyPhone)
                            <div class="socialoud-footer-contact-item">
                                <span>Telepon / WhatsApp</span>
                                <a href="tel:{{ preg_replace('/[^\d+]/', '', $companyPhone) }}">{{ $companyPhone }}</a>
                            </div>
                        @endif
                        @if ($companyEmail)
                            <div class="socialoud-footer-contact-item">
                                <span>Email</span>
                                <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a>
                            </div>
                        @endif
                    </div>
                </section>
            @endif
            @if ($footerCategories->isNotEmpty())
                <nav class="socialoud-footer-category" aria-label="Kategori">
                    <h2 class="socialoud-footer-heading">Kategori</h2>
                    <div class="socialoud-footer-links">
                        @foreach ($footerCategories as $category)
                            <a href="{{ $category->url }}">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </nav>
            @endif

            @if ($footerPages->isNotEmpty())
                <nav class="socialoud-footer-navigation" aria-label="{{ __('Footer navigation') }}">
                    <h2 class="socialoud-footer-heading">Navigasi</h2>
                    <div class="socialoud-footer-links">
                        @foreach ($footerPages as $page)
                            <a href="{{ $page->url }}">{{ $page->name }}</a>
                        @endforeach
                    </div>
                </nav>
            @endif
        </div>
    </div>

    <div class="socialoud-footer-bottom">
        <div class="socialoud-container socialoud-footer-bottom-inner">
            <div class="socialoud-socials" aria-label="{{ __('Social media') }}">
                <a href="#" aria-label="Instagram">◎</a>
                <a href="#" aria-label="X">𝕏</a>
                <a href="#" aria-label="Facebook">f</a>
                <a href="#" aria-label="TikTok">♪</a>
                <a href="#" aria-label="YouTube">▶</a>
            </div>
            <div class="socialoud-copyright">{!! Theme::getSiteCopyright() !!}</div>
        </div>
    </div>
</footer>

@if (is_plugin_active('fob-comment'))
    @php
        Theme::asset()->container('footer')->add('fob-comment-js', asset('vendor/core/plugins/fob-comment/js/comment.js'), ['jquery'], version: '1.2.10');
    @endphp
@endif

{!! Theme::footer() !!}
<div id="fb-root"></div>
<div id="socialoud-runtime"></div>
<script src="{{ Theme::asset()->url('js/socialoud.js') }}?v={{ filemtime(public_path('themes/socialoud/js/socialoud.js')) }}"></script>
</body>
</html>
