<?php

namespace FriendsOfBotble\GoogleReviews\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GoogleReviewDataRequest extends Request
{
    public function rules(): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'author_url' => ['nullable', 'string', 'max:255', 'url'],
            'profile_photo_url' => ['nullable', 'string', 'max:255', 'url'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'text' => ['nullable', 'string'],
            'review_date' => ['nullable', 'date'],
            'order' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', Rule::in(BaseStatusEnum::values())],
        ];
    }

    public function attributes(): array
    {
        return [
            'author_name' => trans('plugins/fob-google-reviews::google-reviews.form.author_name'),
            'author_url' => trans('plugins/fob-google-reviews::google-reviews.form.author_url'),
            'profile_photo_url' => trans('plugins/fob-google-reviews::google-reviews.form.profile_photo_url'),
            'rating' => trans('plugins/fob-google-reviews::google-reviews.form.rating'),
            'text' => trans('plugins/fob-google-reviews::google-reviews.form.review_text'),
            'review_date' => trans('plugins/fob-google-reviews::google-reviews.form.review_date'),
            'order' => trans('plugins/fob-google-reviews::google-reviews.form.order'),
            'status' => trans('core/base::forms.status'),
        ];
    }
}
