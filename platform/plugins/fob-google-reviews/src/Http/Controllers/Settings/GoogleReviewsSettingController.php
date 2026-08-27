<?php

namespace FriendsOfBotble\GoogleReviews\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Supports\Breadcrumb;
use Botble\Setting\Http\Controllers\SettingController;
use FriendsOfBotble\GoogleReviews\Forms\Settings\GoogleReviewsSettingForm;
use FriendsOfBotble\GoogleReviews\Http\Requests\Settings\GoogleReviewsSettingRequest;

class GoogleReviewsSettingController extends SettingController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/fob-google-reviews::google-reviews.settings.title'), route('google-reviews.settings'));
    }

    public function edit()
    {
        $this->pageTitle(trans('plugins/fob-google-reviews::google-reviews.settings.title'));

        return GoogleReviewsSettingForm::create()->renderForm();
    }

    public function update(GoogleReviewsSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
