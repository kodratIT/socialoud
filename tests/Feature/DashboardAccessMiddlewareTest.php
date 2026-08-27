<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DashboardAccessMiddlewareTest extends TestCase
{
    #[DataProvider('restrictedRouteProvider')]
    public function test_restricted_admin_routes_use_access_middleware(string $routeName): void
    {
        $route = app('router')->getRoutes()->getByName($routeName);

        self::assertNotNull($route);
        self::assertContains('restrict.dashboard.management', $route->gatherMiddleware());
    }


    public function test_non_allowlisted_user_menu_hides_management_groups(): void
    {
        Auth::shouldReceive('user')->andReturn((object) ['email' => 'someone@example.com']);

        $items = collect([
            ['id' => 'cms-core-tools', 'parent_id' => null],
            ['id' => 'cms-core-theme', 'parent_id' => 'cms-core-appearance'],
            ['id' => 'dashboard', 'parent_id' => null],
        ]);

        $filtered = apply_filters('dashboard_menu', $items);

        self::assertInstanceOf(Collection::class, $filtered);
        self::assertSame(['dashboard'], $filtered->pluck('id')->all());
    }

    public static function restrictedRouteProvider(): array
    {
        return [
            'tools' => ['tools.data-synchronize.export.pages.index'],
            'settings' => ['settings.index'],
            'sitemap settings' => ['sitemap.settings'],
            'slug settings' => ['slug.settings'],
            'optimize settings' => ['optimize.settings'],
            'appearance' => ['theme.options'],
            'plugins' => ['plugins.index'],
        ];
    }
}
