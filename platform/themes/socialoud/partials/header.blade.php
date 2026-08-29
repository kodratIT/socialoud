<!doctype html>
<html {!! Theme::htmlAttributes() !!}>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        try {
            const themePreference = localStorage.getItem('socialoud-theme');
            const useLightTheme = themePreference === 'light'
                || (themePreference === 'auto' && window.matchMedia('(prefers-color-scheme: light)').matches);
            if (useLightTheme) document.documentElement.classList.add('socialoud-light');
        } catch (_) {}
    </script>
    <link rel="icon" type="image/png" href="{{ Theme::asset()->url('images/branding/socialoud-mark.png') }}">
    <style>
        :root {
            --socialoud-orange: #c40000;
            --socialoud-red: #c40000;
        }
    </style>
    {!! Theme::header() !!}
    <link rel="stylesheet" href="{{ Theme::asset()->url('css/socialoud.css') }}?v={{ md5_file(public_path('themes/socialoud/css/socialoud.css')) }}">
</head>
<body {!! Theme::bodyAttributes() !!}>
{!! apply_filters(THEME_FRONT_BODY, null) !!}
<header class="socialoud-header">
    <div class="socialoud-container socialoud-header-inner">
        <button class="socialoud-menu-toggle" type="button" aria-label="{{ __('Open menu') }}" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <a href="{{ route('public.single') }}" class="socialoud-brand" aria-label="{{ theme_option('site_title', 'Socialoud') }}">
            <img class="socialoud-logo socialoud-logo-white" src="{{ Theme::asset()->url('images/branding/socialoud-white.png') }}" alt="{{ theme_option('site_title', 'Socialoud') }}" width="600" height="113">
            <img class="socialoud-logo socialoud-logo-red" src="{{ Theme::asset()->url('images/branding/socialoud-red.png') }}" alt="" aria-hidden="true" width="600" height="113">
        </a>

        <nav class="socialoud-nav" aria-label="{{ __('Main navigation') }}">
            {!! Menu::renderMenuLocation('main-menu', [
                'options' => ['id' => 'socialoud-main-menu', 'class' => 'socialoud-menu'],
                'theme' => true,
                'view' => 'menu-parent',
            ]) !!}
        </nav>

        <div class="socialoud-header-actions">
            @if (is_plugin_active('language'))
                <div class="socialoud-language">{!! apply_filters('language_switcher') !!}</div>
            @endif
            <button class="socialoud-search-toggle" type="button" aria-label="{{ __('Search') }}" aria-expanded="false">⌕</button>
            <label class="sr-only" for="socialoud-theme-select">Display theme</label>
            <select id="socialoud-theme-select" class="socialoud-theme-select" data-theme-toggle aria-label="Choose display theme">
                <option value="auto">Auto</option>
                <option value="dark" selected>Dark</option>
                <option value="light">Light</option>
            </select>
        </div>
    </div>
    @if (is_plugin_active('blog'))
        <div class="socialoud-search-panel" hidden>
            <div class="socialoud-container">
                <form action="{{ route('public.search') }}" method="GET" class="socialoud-search-form">
                    <label class="sr-only" for="socialoud-search">{{ __('Search') }}</label>
                    <input id="socialoud-search" type="search" name="q" placeholder="{{ __('Type to search...') }}" autocomplete="off">
                    <button type="submit">{{ __('Search') }}</button>
                </form>
            </div>
        </div>
    @endif
</header>
