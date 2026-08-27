<?php

namespace App\Support;

final class DashboardAccess
{
    public const PRIVILEGED_EMAIL = 'kodratcoc@gmail.com';

    private const RESTRICTED_ROUTE_PREFIXES = [
        'tools.',
        'settings.',
        'theme.',
        'plugins.',
        'sitemap.settings',
        'slug.settings',
        'optimize.settings',
    ];

    public const RESTRICTED_MENU_IDS = [
        'cms-core-tools',
        'cms-core-settings',
        'cms-core-appearance',
        'cms-core-plugins',
        'cms-core-menu',
        'cms-core-widget',
        'cms-core-theme',
        'cms-core-theme-option',
        'cms-core-appearance-custom-css',
        'cms-core-appearance-custom-js',
        'cms-core-appearance-custom-html',
        'cms-core-appearance-robots-txt',
        'cms-core-plugins-installed',
        'cms-core-plugins-marketplace',
    ];

    public static function canManage(?object $user): bool
    {
        return $user !== null
            && strcasecmp(trim((string) $user->email), self::PRIVILEGED_EMAIL) === 0;
    }

    public static function isRestrictedRoute(?string $routeName): bool
    {
        if ($routeName === null) {
            return false;
        }

        foreach (self::RESTRICTED_ROUTE_PREFIXES as $prefix) {
            if (str_starts_with($routeName, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
