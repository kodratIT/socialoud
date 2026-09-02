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
                    <img class="socialoud-logo socialoud-logo-white" src="{{ Theme::asset()->url('images/branding/socialoud-white.png') }}" alt="{{ theme_option('site_title', 'Socialoud') }}" width="600" height="113">
                    <img class="socialoud-logo socialoud-logo-red" src="{{ Theme::asset()->url('images/branding/socialoud-red.png') }}" alt="" aria-hidden="true" width="600" height="113">
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
                <a href="https://www.instagram.com/socialoud.id" target="_blank" rel="noopener noreferrer" aria-label="Instagram: socialoud.id"><span class="socialoud-social-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.5" cy="6.5" r="1" class="socialoud-social-icon-fill"></circle></svg></span><strong>socialoud.id</strong></a>
                <a href="https://www.tiktok.com/@socialoud.id" target="_blank" rel="noopener noreferrer" aria-label="TikTok: socialoud.id"><span class="socialoud-social-icon" aria-hidden="true"><svg viewBox="0 0 24 24" focusable="false"><path d="M14 4v10.2a4.8 4.8 0 1 1-3.5-4.6v3.2a1.8 1.8 0 1 0 1.8 1.8V4h1.7c.8 1.5 2.3 2.4 4 2.6v3.1A8 8 0 0 1 14 8.2"></path></svg></span><strong>socialoud.id</strong></a>
                <a href="https://www.threads.net/@socialoud.id" target="_blank" rel="noopener noreferrer" aria-label="Threads: socialoud.id"><span class="socialoud-social-icon" aria-hidden="true">@</span><strong>socialoud.id</strong></a>
            </div>
            <div class="socialoud-copyright">{!! Theme::getSiteCopyright() !!}</div>
        </div>
    </div>
</footer>


@php
    $footerAssets = Theme::asset()->container('footer');
    if (($footerAssets->get('gallery-js') || $footerAssets->get('fob-comment-js')) && ! $footerAssets->get('jquery')) {
        $footerAssets
            ->usePath(false)
            ->add('jquery', asset('vendor/core/core/base/libraries/jquery.min.js'), [], [], '4.0.0');
    }
@endphp
{!! Theme::footer() !!}
<div id="fb-root"></div>
<div id="socialoud-runtime"></div>
<script defer src="{{ Theme::asset()->url('js/socialoud.js') }}?v={{ md5_file(public_path('themes/socialoud/js/socialoud.js')) }}"></script>
</body>
</html>
