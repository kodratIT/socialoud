<?php

namespace Tests\Unit;

use App\Http\Middleware\RestrictDashboardManagement;
use App\Support\DashboardAccess;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DashboardAccessTest extends TestCase
{
    public function test_allowlisted_email_is_case_and_whitespace_insensitive(): void
    {
        $user = (object) ['email' => '  KODRATCOC@GMAIL.COM  '];

        self::assertTrue(DashboardAccess::canManage($user));
    }

    public function test_other_email_cannot_manage_dashboard_sections(): void
    {
        $user = (object) ['email' => 'someone@example.com'];

        self::assertFalse(DashboardAccess::canManage($user));
    }

    #[DataProvider('restrictedRouteProvider')]
    public function test_management_route_prefixes_are_restricted(string $routeName, bool $restricted): void
    {
        self::assertSame($restricted, DashboardAccess::isRestrictedRoute($routeName));
    }


    public function test_non_allowlisted_user_is_denied_restricted_route(): void
    {
        $request = Request::create('/admin/settings');
        $request->setUserResolver(fn () => (object) ['email' => 'someone@example.com']);
        $request->setRouteResolver(fn () => (new Route('GET', 'admin/settings', fn () => 'ok'))->name('settings.index'));

        try {
            (new RestrictDashboardManagement())->handle($request, fn () => new Response('ok'));
            self::fail('Expected restricted dashboard route to throw a 403.');
        } catch (HttpException $exception) {
            self::assertSame(403, $exception->getStatusCode());
        }
    }

    public function test_allowlisted_user_can_access_restricted_route(): void
    {
        $request = Request::create('/admin/settings');
        $request->setUserResolver(fn () => (object) ['email' => DashboardAccess::PRIVILEGED_EMAIL]);
        $request->setRouteResolver(fn () => (new Route('GET', 'admin/settings', fn () => 'ok'))->name('settings.index'));

        $response = (new RestrictDashboardManagement())->handle($request, fn () => new Response('ok'));

        self::assertSame(200, $response->getStatusCode());
    }

    public static function restrictedRouteProvider(): array
    {
        return [
            'plugins' => ['plugins.index', true],
            'sitemap settings' => ['sitemap.settings', true],
            'slug settings' => ['slug.settings', true],
            'optimize settings' => ['optimize.settings', true],
            'dashboard' => ['dashboard.index', false],
            'users' => ['users.index', false],
        ];
    }
}
