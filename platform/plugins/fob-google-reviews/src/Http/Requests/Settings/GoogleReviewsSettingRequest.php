<?php

namespace FriendsOfBotble\GoogleReviews\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class GoogleReviewsSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'google_reviews_source_mode' => ['required', 'in:manual,api'],
            'google_reviews_api_key' => ['nullable', 'string', 'max:255'],
            'google_reviews_place_id' => ['nullable', 'string', 'max:255'],
            'google_reviews_display_type' => ['required', 'in:carousel,grid,list'],
            'google_reviews_max_reviews' => ['required', 'integer', 'min:1', 'max:20'],
            'google_reviews_min_rating' => ['required', 'integer', 'min:0', 'max:5'],
            'google_reviews_show_on_product_page' => [new OnOffRule()],
            'google_reviews_auto_refresh' => [new OnOffRule()],
            'google_reviews_cache_duration' => ['required', 'integer', 'min:5', 'max:1440'],
            'google_reviews_business_name' => ['nullable', 'string', 'max:255'],
            'google_reviews_autoplay' => [new OnOffRule()],
            'google_reviews_autoplay_speed' => ['required', 'integer', 'min:1000', 'max:30000'],
            'google_reviews_show_view_all_button' => [new OnOffRule()],
            'google_reviews_widget_title' => ['nullable', 'string', 'max:255'],
            'google_reviews_show_ratings_summary' => [new OnOffRule()],
            'google_reviews_show_author_avatar' => [new OnOffRule()],
            'google_reviews_show_author_name' => [new OnOffRule()],
            'google_reviews_show_date' => [new OnOffRule()],
            'google_reviews_show_stars' => [new OnOffRule()],
            'google_reviews_reviews_per_row' => ['required', 'integer', 'in:1,2,3,4'],
            'google_reviews_text_length' => ['required', 'integer', 'min:50', 'max:1000'],
            'google_reviews_show_read_more' => [new OnOffRule()],
        ];
    }
}
