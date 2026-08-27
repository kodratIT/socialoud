<?php

namespace FriendsOfBotble\GoogleReviews\Providers;

use Botble\Base\Facades\MetaBox;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Shortcode\Facades\Shortcode as ShortcodeFacade;
use FriendsOfBotble\GoogleReviews\Models\GoogleReview;
use FriendsOfBotble\GoogleReviews\Services\GoogleReviewsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    protected array $supportedModels = [];

    public function boot(): void
    {
        $this->registerSupportedModels();
        $this->registerPublicCommentFilter();
        $this->registerMetaBoxes();
        $this->registerContentHooks();
        $this->registerShortcode();
    }

    protected function registerSupportedModels(): void
    {
        $models = [
            'Botble\\Hotel\\Models\\Room' => [
                'setting_key' => 'google_reviews_show_on_room_page',
                'label' => 'room',
            ],
            'Botble\\Ecommerce\\Models\\Product' => [
                'setting_key' => 'google_reviews_show_on_product_page',
                'label' => 'product',
            ],
        ];

        foreach ($models as $modelClass => $config) {
            if (class_exists($modelClass)) {
                $this->supportedModels[$modelClass] = $config;
            }
        }
    }

    protected function registerPublicCommentFilter(): void
    {
        add_filter(BASE_FILTER_PUBLIC_COMMENT_AREA, function ($html, $data) {
            $object = $data;

            foreach ($this->supportedModels as $modelClass => $config) {
                if ($object instanceof $modelClass && setting($config['setting_key'], true)) {
                    $reviewsService = app(GoogleReviewsService::class);

                    $googleReview = GoogleReview::query()
                        ->where('reviewable_id', $object->getKey())
                        ->where('reviewable_type', $modelClass)
                        ->first();

                    $placeId = $googleReview && $googleReview->custom_place_id
                        ? $googleReview->custom_place_id
                        : null;

                    $reviewData = $reviewsService->getReviews($placeId);

                    return view('plugins/fob-google-reviews::entity-reviews', ['data' => $reviewData])->render() . $html;
                }
            }

            return $html;
        }, 160, 2);
    }

    protected function registerMetaBoxes(): void
    {
        add_action(BASE_ACTION_META_BOXES, function ($context, $object): void {
            if ($context !== 'advanced') {
                return;
            }

            $objectClass = get_class($object);

            if (! isset($this->supportedModels[$objectClass])) {
                return;
            }

            MetaBox::addMetaBox(
                'google_reviews_box',
                trans('plugins/fob-google-reviews::google-reviews.name'),
                function () use ($object, $objectClass) {
                    $review = GoogleReview::query()
                        ->where('reviewable_id', $object->getKey())
                        ->where('reviewable_type', $objectClass)
                        ->first();

                    return view('plugins/fob-google-reviews::google-review-fields', compact('review'));
                },
                $objectClass,
                $context
            );
        }, 40, 2);
    }

    protected function registerContentHooks(): void
    {
        $saveCallback = function ($type, $request, $object): void {
            $this->saveGoogleReview($object, $request);
        };

        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, $saveCallback, 40, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, $saveCallback, 40, 3);
    }

    protected function registerShortcode(): void
    {
        if (! class_exists(ShortcodeFacade::class)) {
            return;
        }

        ShortcodeFacade::register(
            'google-reviews',
            trans('plugins/fob-google-reviews::google-reviews.shortcode_name'),
            trans('plugins/fob-google-reviews::google-reviews.shortcode_description'),
            [$this, 'googleReviewsShortcode']
        );

        ShortcodeFacade::setAdminConfig('google-reviews', [$this, 'googleReviewsShortcodeAdminConfig']);
    }

    public function googleReviewsShortcode(Shortcode $shortcode): string
    {
        $reviewsService = app(GoogleReviewsService::class);
        $data = $reviewsService->getReviews();

        return view('plugins/fob-google-reviews::shortcode', compact('data', 'shortcode'))->render();
    }

    public function googleReviewsShortcodeAdminConfig(array $attributes = []): string
    {
        return view('plugins/fob-google-reviews::shortcode-admin-config', compact('attributes'))->render();
    }

    protected function saveGoogleReview(Model $model, $request): void
    {
        $modelClass = get_class($model);

        if (! isset($this->supportedModels[$modelClass])) {
            return;
        }

        GoogleReview::query()->updateOrCreate(
            [
                'reviewable_id' => $model->getKey(),
                'reviewable_type' => $modelClass,
            ],
            [
                'is_enabled' => $request->input('google_reviews_enabled', false),
                'custom_place_id' => $request->input('google_reviews_custom_place_id'),
            ]
        );
    }
}
