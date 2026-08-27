<?php

namespace FriendsOfBotble\GoogleReviews;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('google_reviews');
        Schema::dropIfExists('google_reviews_data');

        Setting::delete([
            'google_reviews_source_mode',
            'google_reviews_api_key',
            'google_reviews_place_id',
            'google_reviews_display_type',
            'google_reviews_max_reviews',
            'google_reviews_min_rating',
            'google_reviews_show_on_product_page',
            'google_reviews_auto_refresh',
            'google_reviews_cache_duration',
            'google_reviews_business_name',
            'google_reviews_autoplay',
            'google_reviews_autoplay_speed',
            'google_reviews_show_view_all_button',
            'google_reviews_widget_title',
            'google_reviews_show_ratings_summary',
            'google_reviews_show_author_avatar',
            'google_reviews_show_author_name',
            'google_reviews_show_date',
            'google_reviews_show_stars',
            'google_reviews_reviews_per_row',
            'google_reviews_text_length',
            'google_reviews_show_read_more',
        ]);
    }
}
