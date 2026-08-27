<?php

namespace App\Providers;

use App\Support\DashboardAccess;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        add_filter('dashboard_menu', function (Collection $items): Collection {
            if (DashboardAccess::canManage(Auth::user())) {
                return $items;
            }

            return $items
                ->reject(function (array $item): bool {
                    return in_array($item['id'] ?? null, DashboardAccess::RESTRICTED_MENU_IDS, true)
                        || in_array($item['parent_id'] ?? null, DashboardAccess::RESTRICTED_MENU_IDS, true);
                })
                ->values();
        });

        add_filter('panel_sections', function ($sections, string $groupId, $manager) {
            if ($groupId !== 'system' || DashboardAccess::canManage(Auth::user())) {
                return $sections;
            }

            $manager->ignoreItemIds(DashboardAccess::RESTRICTED_MENU_IDS);

            return $sections;
        }, 99, 3);
    }
}
