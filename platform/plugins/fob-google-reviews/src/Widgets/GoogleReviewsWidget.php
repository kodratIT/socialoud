<?php

namespace FriendsOfBotble\GoogleReviews\Widgets;

use Botble\Widget\AbstractWidget;
use FriendsOfBotble\GoogleReviews\Services\GoogleReviewsService;
use Illuminate\Support\Collection;

class GoogleReviewsWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => trans('plugins/fob-google-reviews::google-reviews.widget_name'),
            'description' => trans('plugins/fob-google-reviews::google-reviews.widget_description'),
            'title' => '',
        ]);

        $this->setFrontendTemplate('plugins/fob-google-reviews::widgets.google-reviews-widget');
        $this->setBackendTemplate('plugins/fob-google-reviews::widgets.google-reviews-backend');
    }

    public function data(): array|Collection
    {
        $reviewsService = app(GoogleReviewsService::class);
        $data = $reviewsService->getReviews();

        return compact('data');
    }

    protected function requiredPlugins(): array
    {
        return ['fob-google-reviews'];
    }
}
