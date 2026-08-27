@if (!empty($data['reviews']))
    @php
        Theme::asset()->add('google-reviews-css', asset('vendor/core/plugins/fob-google-reviews/css/google-reviews.css'));
        Theme::asset()->container('footer')->add('google-reviews-js', asset('vendor/core/plugins/fob-google-reviews/js/google-reviews.js'));
    @endphp

    <div class="fob-google-reviews-widget fob-google-reviews-widget-sidebar">
        @if($customTitle = ($config['title'] ?? setting('google_reviews_widget_title')))
            <h3 class="widget-title">{{ $customTitle }}</h3>
        @endif

        @if(setting('google_reviews_show_ratings_summary', true) && !empty($data['overall_rating']))
            <div class="gr-summary">
                <div class="gr-rating-number">{{ number_format($data['overall_rating'], 1) }}</div>
                <div class="gr-summary-details">
                    <div class="gr-stars-container">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($data['overall_rating']))
                                <span class="gr-star gr-star-filled">★</span>
                            @elseif ($i - 0.5 <= $data['overall_rating'])
                                <span class="gr-star gr-star-half">★</span>
                            @else
                                <span class="gr-star">★</span>
                            @endif
                        @endfor
                    </div>
                    <div class="gr-total-reviews">{{ trans('plugins/fob-google-reviews::google-reviews.based_on_reviews', ['count' => $data['total_reviews']]) }}</div>
                </div>
            </div>
        @endif

        <div class="gr-reviews-list">
            @foreach($data['reviews'] as $review)
                <div class="gr-review-card gr-review-card-sidebar">
                    @if(setting('google_reviews_show_author_avatar', true))
                        <div class="gr-author-avatar">
                            @if(!empty($review['profile_photo_url']))
                                <img src="{{ $review['profile_photo_url'] }}" alt="{{ $review['author_name'] }}">
                            @else
                                <div class="gr-avatar-placeholder">
                                    {{ strtoupper(substr($review['author_name'], 0, 1)) }}
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="gr-review-content">
                        <div class="gr-review-header">
                            @if(setting('google_reviews_show_author_name', true))
                                <div class="gr-author-name">
                                    @if(!empty($review['author_url']))
                                        <a href="{{ $review['author_url'] }}" target="_blank" rel="noopener">{{ $review['author_name'] }}</a>
                                    @else
                                        {{ $review['author_name'] }}
                                    @endif
                                </div>
                            @endif

                            @if(setting('google_reviews_show_stars', true))
                                <div class="gr-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="gr-star{{ $i <= $review['rating'] ? ' gr-star-filled' : '' }}">★</span>
                                    @endfor
                                </div>
                            @endif
                        </div>

                        @if(!empty($review['text']))
                            @php
                                $textLength = (int) setting('google_reviews_text_length', 200);
                                $reviewText = $review['text'];
                                $truncated = strlen($reviewText) > $textLength;
                                $displayText = $truncated ? substr($reviewText, 0, $textLength) . '...' : $reviewText;
                            @endphp
                            <div class="gr-review-text">
                                {{ $displayText }}
                                @if($truncated && setting('google_reviews_show_read_more', true) && !empty($review['author_url']))
                                    <a href="{{ $review['author_url'] }}" target="_blank" rel="noopener" class="gr-read-more">{{ trans('plugins/fob-google-reviews::google-reviews.read_more') }}</a>
                                @endif
                            </div>
                        @endif

                        @if(setting('google_reviews_show_date', true) && !empty($review['relative_time_description']))
                            <div class="gr-review-date">{{ $review['relative_time_description'] }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
