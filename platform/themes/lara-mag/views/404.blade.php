@php
    SeoHelper::setTitle(__('404 - Not found'));
    Theme::fireEventGlobalAssets();
    Theme::breadcrumb()->add(__('Home'), route('public.index'))->add(SeoHelper::getTitle());
@endphp

{!! Theme::partial('header') !!}

<style>
    .error-page-wrapper {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
    }

    .error-content {
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
    }

    .error-code {
        font-size: 8rem;
        font-weight: 800;
        color: var(--color-1st, #e74c3c);
        line-height: 1;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .error-title {
        font-size: 2rem;
        font-weight: 700;
        color: #22292f;
        margin-bottom: 1rem;
    }

    .error-description {
        font-size: 1.125rem;
        color: #6c757d;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .error-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .error-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        font-weight: 600;
        text-decoration: none;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .error-btn-primary {
        background-color: var(--color-1st, #e74c3c);
        color: #fff;
    }

    .error-btn-primary:hover {
        background-color: var(--color-1st-hover, #c0392b);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        color: #fff;
    }

    .error-btn-secondary {
        background-color: #f8f9fa;
        color: #495057;
        border: 1px solid #dee2e6;
    }

    .error-btn-secondary:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: #495057;
    }

    .error-suggestions {
        text-align: left;
        background: #f8f9fa;
        padding: 2rem;
        border-radius: 0.5rem;
        border-left: 4px solid var(--color-1st, #e74c3c);
    }

    .error-suggestions h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #22292f;
        margin-bottom: 1rem;
    }

    .error-suggestions ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .error-suggestions li {
        padding: 0.5rem 0;
        color: #6c757d;
        display: flex;
        align-items: start;
        gap: 0.75rem;
    }

    .error-suggestions li:before {
        content: "→";
        color: var(--color-1st, #e74c3c);
        font-weight: bold;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .error-code {
            font-size: 5rem;
        }

        .error-title {
            font-size: 1.5rem;
        }

        .error-description {
            font-size: 1rem;
        }

        .error-actions {
            flex-direction: column;
        }

        .error-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="container">
    <div class="error-page-wrapper">
        <div class="error-content">
            <div class="error-code">404</div>
            <h1 class="error-title">{{ __('Page Not Found') }}</h1>
            <p class="error-description">
                {{ __('Sorry, the page you are looking for could not be found. It may have been moved, deleted, or never existed.') }}
            </p>

            <div class="error-actions">
                <a href="{{ route('public.index') }}" class="error-btn error-btn-primary">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 6L8 2L14 6V13C14 13.5304 13.7893 14.0391 13.4142 14.4142C13.0391 14.7893 12.5304 15 12 15H4C3.46957 15 2.96086 14.7893 2.58579 14.4142C2.21071 14.0391 2 13.5304 2 13V6Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ __('Back to Home') }}
                </a>
            </div>
        </div>
    </div>
</div>

{!! Theme::partial('footer') !!}
