@if(isset($data['reviews']) && count($data['reviews']) > 0)
<div class="fob-google-reviews-widget"
     data-autoplay="{{ setting('google_reviews_autoplay', 'true') }}"
     data-autoplay-speed="{{ setting('google_reviews_autoplay_speed', '5000') }}"
     data-loop="true">

    {{-- Header Section --}}
    <div class="fob-gr-header">
        <svg class="fob-gr-google-logo" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>

        <div class="fob-gr-header-content">
            <h3 class="fob-gr-business-name">{{ $data['place_name'] ?? setting('google_reviews_business_name', 'Our Business') }}</h3>

            <div class="fob-gr-rating-summary">
                <span class="fob-gr-overall-rating">{{ number_format($data['overall_rating'] ?? 0, 1) }}</span>

                <div class="fob-gr-stars" role="img" aria-label="Rated {{ number_format($data['overall_rating'] ?? 0, 1) }} out of 5 stars">
                    @for($i = 1; $i <= 5; $i++)
                        @php
                            $rating = $data['overall_rating'] ?? 0;
                            $filled = $i <= floor($rating);
                            $half = !$filled && $i <= ceil($rating);
                        @endphp
                        @if($filled)
                            <span class="fob-gr-star">★</span>
                        @else
                            <span class="fob-gr-star empty">★</span>
                        @endif
                    @endfor
                </div>

                <span class="fob-gr-total-reviews">{{ __('Based on :count reviews', ['count' => $data['total_reviews'] ?? 0]) }}</span>
            </div>

            <div class="fob-gr-powered-by">
                {{ __('Powered by') }} <a href="https://www.google.com/maps" target="_blank" rel="noopener noreferrer">Google</a>
            </div>
        </div>
    </div>

    {{-- Reviews Carousel --}}
    <div class="fob-gr-reviews-container">
        <div class="fob-gr-carousel-wrapper">
            <div class="fob-gr-reviews-track">
                @foreach($data['reviews'] as $review)
                <div class="fob-gr-review-card">
                    <div class="fob-gr-review-header">
                        @if(!empty($review['profile_photo_url']))
                            <img src="{{ $review['profile_photo_url'] }}"
                                 alt="{{ $review['author_name'] }}"
                                 class="fob-gr-reviewer-avatar"
                                 loading="lazy">
                        @else
                            <div class="fob-gr-reviewer-avatar"
                                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 16px;">
                                {{ strtoupper(substr($review['author_name'] ?? 'U', 0, 1)) }}
                            </div>
                        @endif

                        <div class="fob-gr-reviewer-info">
                            <h4 class="fob-gr-reviewer-name">{{ $review['author_name'] ?? 'Anonymous' }}</h4>

                            <div class="fob-gr-review-meta">
                                <div class="fob-gr-review-stars" role="img" aria-label="Rated {{ $review['rating'] ?? 0 }} out of 5 stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($review['rating'] ?? 0))
                                            <span class="fob-gr-review-star">★</span>
                                        @else
                                            <span class="fob-gr-review-star empty">★</span>
                                        @endif
                                    @endfor
                                </div>

                                @if(!empty($review['relative_time_description']))
                                    <span class="fob-gr-review-time">{{ $review['relative_time_description'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(!empty($review['text']))
                        @php
                            $text = $review['text'];
                            $shouldTruncate = strlen($text) > 200;
                        @endphp

                        <p class="fob-gr-review-text {{ $shouldTruncate ? 'has-more' : '' }}">
                            {{ $text }}
                        </p>

                        @if($shouldTruncate)
                            <button class="fob-gr-read-more"
                                    data-more-text="{{ __('Read more') }}"
                                    data-less-text="{{ __('Show less') }}">
                                {{ __('Read more') }}
                            </button>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Navigation --}}
        <div class="fob-gr-nav">
            <button class="fob-gr-nav-button fob-gr-prev" aria-label="{{ __('Previous reviews') }}">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>

            <div class="fob-gr-dots" role="tablist" aria-label="{{ __('Review slides') }}"></div>

            <button class="fob-gr-nav-button fob-gr-next" aria-label="{{ __('Next reviews') }}">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- View on Google Button --}}
    @if(setting('google_reviews_show_view_all_button', true))
        <div class="fob-gr-view-all">
            <a href="https://search.google.com/local/writereview?placeid={{ setting('google_reviews_place_id') }}"
               target="_blank"
               rel="noopener noreferrer"
               class="fob-gr-view-all-button">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                {{ __('View all Google reviews') }}
            </a>
        </div>
    @endif
</div>

{{-- Include CSS --}}
@if (File::exists(public_path('vendor/core/plugins/fob-google-reviews/css/google-reviews.css')))
    <link rel="stylesheet" href="{{ asset('vendor/core/plugins/fob-google-reviews/css/google-reviews.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('vendor/core/plugins/fob-google-reviews/public/css/google-reviews.css') }}">
@endif

{{-- Include JavaScript --}}
@if (File::exists(public_path('vendor/core/plugins/fob-google-reviews/js/google-reviews.js')))
    <script src="{{ asset('vendor/core/plugins/fob-google-reviews/js/google-reviews.js') }}" defer></script>
@else
    <script src="{{ asset('vendor/core/plugins/fob-google-reviews/public/js/google-reviews.js') }}" defer></script>
@endif

@elseif(isset($data) && empty($data['reviews']))
<div class="fob-google-reviews-widget">
    <div class="fob-gr-empty">
        <div class="fob-gr-empty-icon">★</div>
        <p class="fob-gr-empty-text">{{ __('No reviews available at the moment.') }}</p>
    </div>
</div>
@endif
