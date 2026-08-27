<?php

namespace FriendsOfBotble\GoogleReviews\Forms\Settings;

use Botble\Base\Forms\FieldOptions\AlertFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\AlertField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffCheckboxField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;
use FriendsOfBotble\GoogleReviews\Http\Requests\Settings\GoogleReviewsSettingRequest;

class GoogleReviewsSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/fob-google-reviews::google-reviews.settings.title'))
            ->setSectionDescription(trans('plugins/fob-google-reviews::google-reviews.settings.description'))
            ->setValidatorClass(GoogleReviewsSettingRequest::class)
            ->add(
                'shortcode_instruction',
                AlertField::class,
                AlertFieldOption::make()
                    ->content(trans('plugins/fob-google-reviews::google-reviews.settings.shortcode_instruction'))
                    ->type('info')
            )
            ->add(
                'google_reviews_source_mode',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.source_mode'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.source_mode_help'))
                    ->choices([
                        'manual' => trans('plugins/fob-google-reviews::google-reviews.settings.source_modes.manual'),
                        'api' => trans('plugins/fob-google-reviews::google-reviews.settings.source_modes.api'),
                    ])
                    ->selected($targetValue = setting('google_reviews_source_mode', 'manual'))
            )
            ->addOpenCollapsible('google_reviews_source_mode', 'api', $targetValue)
            ->add(
                'google_reviews_api_key',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.api_key'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.api_key_help'))
                    ->value(setting('google_reviews_api_key'))
                    ->maxLength(255)
            )
            ->add(
                'google_reviews_place_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.place_id'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.place_id_help'))
                    ->value(setting('google_reviews_place_id'))
                    ->maxLength(255)
            )
            ->addCloseCollapsible('google_reviews_source_mode', 'api')
            ->add(
                'google_reviews_display_type',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.display_type'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.display_type_help'))
                    ->choices([
                        'carousel' => trans('plugins/fob-google-reviews::google-reviews.settings.display_types.carousel'),
                        'grid' => trans('plugins/fob-google-reviews::google-reviews.settings.display_types.grid'),
                        'list' => trans('plugins/fob-google-reviews::google-reviews.settings.display_types.list'),
                    ])
                    ->selected($displayType = setting('google_reviews_display_type', 'carousel'))
            )
            ->addOpenCollapsible('google_reviews_display_type', 'carousel', $displayType)
            ->add(
                'google_reviews_autoplay',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.autoplay'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.autoplay_help'))
                    ->value(setting('google_reviews_autoplay', true))
            )
            ->add(
                'google_reviews_autoplay_speed',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.autoplay_speed'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.autoplay_speed_help'))
                    ->value(setting('google_reviews_autoplay_speed', 5000))
                    ->min(1000)
                    ->max(30000)
            )
            ->addCloseCollapsible('google_reviews_display_type', 'carousel')
            ->addOpenCollapsible('google_reviews_display_type', 'grid', $displayType)
            ->add(
                'google_reviews_reviews_per_row',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.reviews_per_row'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.reviews_per_row_help'))
                    ->choices([
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                    ])
                    ->selected(setting('google_reviews_reviews_per_row', '3'))
            )
            ->addCloseCollapsible('google_reviews_display_type', 'grid')
            ->add(
                'google_reviews_max_reviews',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.max_reviews'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.max_reviews_help'))
                    ->value(setting('google_reviews_max_reviews', 5))
                    ->min(1)
                    ->max(20)
            )
            ->add(
                'google_reviews_min_rating',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.min_rating'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.min_rating_help'))
                    ->choices([
                        '0' => '0+',
                        '1' => '1+',
                        '2' => '2+',
                        '3' => '3+',
                        '4' => '4+',
                        '5' => '5',
                    ])
                    ->selected(setting('google_reviews_min_rating', '0'))
            )
            ->when(
                class_exists('Botble\\Hotel\\Models\\Room'),
                fn ($form) => $form->add(
                    'google_reviews_show_on_room_page',
                    OnOffCheckboxField::class,
                    OnOffFieldOption::make()
                        ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_on_room_page'))
                        ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_on_room_page_help'))
                        ->value(setting('google_reviews_show_on_room_page', true))
                )
            )
            ->when(
                class_exists('Botble\\Ecommerce\\Models\\Product'),
                fn ($form) => $form->add(
                    'google_reviews_show_on_product_page',
                    OnOffCheckboxField::class,
                    OnOffFieldOption::make()
                        ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_on_product_page'))
                        ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_on_product_page_help'))
                        ->value(setting('google_reviews_show_on_product_page', true))
                )
            )
            ->add(
                'google_reviews_auto_refresh',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.auto_refresh'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.auto_refresh_help'))
                    ->value(setting('google_reviews_auto_refresh', true))
            )
            ->add(
                'google_reviews_cache_duration',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.cache_duration'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.cache_duration_help'))
                    ->value(setting('google_reviews_cache_duration', 60))
                    ->min(5)
                    ->max(1440)
            )
            ->add(
                'google_reviews_business_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.business_name'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.business_name_help'))
                    ->placeholder(trans('plugins/fob-google-reviews::google-reviews.settings.business_name_placeholder'))
                    ->value(setting('google_reviews_business_name', ''))
                    ->maxLength(255)
            )
            ->add(
                'google_reviews_show_view_all_button',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_view_all_button'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_view_all_button_help'))
                    ->value(setting('google_reviews_show_view_all_button', true))
            )
            ->add(
                'google_reviews_widget_title',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.widget_title'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.widget_title_help'))
                    ->value(setting('google_reviews_widget_title', ''))
                    ->placeholder(trans('plugins/fob-google-reviews::google-reviews.settings.widget_title_placeholder'))
                    ->maxLength(255)
            )
            ->add(
                'google_reviews_show_ratings_summary',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_ratings_summary'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_ratings_summary_help'))
                    ->value(setting('google_reviews_show_ratings_summary', true))
            )
            ->add(
                'google_reviews_show_author_avatar',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_author_avatar'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_author_avatar_help'))
                    ->value(setting('google_reviews_show_author_avatar', true))
            )
            ->add(
                'google_reviews_show_author_name',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_author_name'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_author_name_help'))
                    ->value(setting('google_reviews_show_author_name', true))
            )
            ->add(
                'google_reviews_show_date',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_date'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_date_help'))
                    ->value(setting('google_reviews_show_date', true))
            )
            ->add(
                'google_reviews_show_stars',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_stars'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_stars_help'))
                    ->value(setting('google_reviews_show_stars', true))
            )
            ->add(
                'google_reviews_text_length',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.text_length'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.text_length_help'))
                    ->value(setting('google_reviews_text_length', 200))
                    ->min(50)
                    ->max(1000)
            )
            ->add(
                'google_reviews_show_read_more',
                OnOffCheckboxField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-google-reviews::google-reviews.settings.show_read_more'))
                    ->helperText(trans('plugins/fob-google-reviews::google-reviews.settings.show_read_more_help'))
                    ->value(setting('google_reviews_show_read_more', true))
            );
    }
}
