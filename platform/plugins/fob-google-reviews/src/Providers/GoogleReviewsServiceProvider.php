<?php

namespace FriendsOfBotble\GoogleReviews\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Widget\Events\RenderingWidgetSettings;
use FriendsOfBotble\GoogleReviews\Widgets\GoogleReviewsWidget;
use Illuminate\Support\ServiceProvider;

class GoogleReviewsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('plugins/fob-google-reviews')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('google-reviews')
                    ->setTitle(trans('plugins/fob-google-reviews::google-reviews.settings.title'))
                    ->withIcon('ti ti-star')
                    ->withDescription(trans('plugins/fob-google-reviews::google-reviews.settings.description'))
                    ->withPriority(190)
                    ->withRoute('google-reviews.settings')
            );
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-google-reviews')
                        ->priority(130)
                        ->name('plugins/fob-google-reviews::google-reviews.menu_name')
                        ->icon('ti ti-star')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-google-reviews-list')
                        ->parentId('cms-plugins-google-reviews')
                        ->name('plugins/fob-google-reviews::google-reviews.reviews')
                        ->icon('ti ti-list')
                        ->route('google-reviews.index')
                        ->permission('google-reviews.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-google-reviews-settings')
                        ->parentId('cms-plugins-google-reviews')
                        ->name('plugins/fob-google-reviews::google-reviews.settings_menu')
                        ->icon('ti ti-settings')
                        ->route('google-reviews.settings')
                        ->permission('google-reviews.settings')
                );
        });

        $this->app->booted(function (): void {
            $this->app['events']->listen(RenderingWidgetSettings::class, function (): void {
                register_widget(GoogleReviewsWidget::class);
            });

            $this->app->register(HookServiceProvider::class);
        });
    }
}
