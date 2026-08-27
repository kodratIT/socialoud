<?php

namespace FriendsOfBotble\GoogleReviews\Forms;

use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use FriendsOfBotble\GoogleReviews\Http\Requests\GoogleReviewDataRequest;
use FriendsOfBotble\GoogleReviews\Models\GoogleReviewData;

class GoogleReviewDataForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(GoogleReviewData::class)
            ->setValidatorClass(GoogleReviewDataRequest::class)
            ->add(
                'author_name',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/fob-google-reviews::google-reviews.form.author_name'))
                ->required()
                ->maxLength(255)
            )
            ->add(
                'author_url',
                TextField::class,
                TextFieldOption::make()
                ->label(trans('plugins/fob-google-reviews::google-reviews.form.author_url'))
                ->placeholder('https://www.google.com/maps/contrib/...')
                ->maxLength(255)
            )
            ->add(
                'profile_photo_url',
                MediaImageField::class,
                MediaImageFieldOption::make()
                ->label(trans('plugins/fob-google-reviews::google-reviews.form.profile_photo_url'))
                ->helperText(trans('plugins/fob-google-reviews::google-reviews.form.profile_photo_url_help'))
            )
            ->add(
                'rating',
                SelectField::class,
                SelectFieldOption::make()
                ->label(trans('plugins/fob-google-reviews::google-reviews.form.rating'))
                ->required()
                ->choices([
                    5 => '5 ' . trans('plugins/fob-google-reviews::google-reviews.form.stars'),
                    4 => '4 ' . trans('plugins/fob-google-reviews::google-reviews.form.stars'),
                    3 => '3 ' . trans('plugins/fob-google-reviews::google-reviews.form.stars'),
                    2 => '2 ' . trans('plugins/fob-google-reviews::google-reviews.form.stars'),
                    1 => '1 ' . trans('plugins/fob-google-reviews::google-reviews.form.star'),
                ])
                ->selected(5)
            )
            ->add(
                'text',
                TextareaField::class,
                TextareaFieldOption::make()
                ->label(trans('plugins/fob-google-reviews::google-reviews.form.review_text'))
                ->rows(5)
            )
            ->add(
                'review_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                ->label(trans('plugins/fob-google-reviews::google-reviews.form.review_date'))
                ->defaultValue(now())
            )
            ->add(
                'order',
                NumberField::class,
                NumberFieldOption::make()
                ->label(trans('plugins/fob-google-reviews::google-reviews.form.order'))
                ->helperText(trans('plugins/fob-google-reviews::google-reviews.form.order_help'))
                ->defaultValue(0)
                ->min(0)
            )
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('order');
    }
}
