<?php

namespace Tests\Feature;

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
