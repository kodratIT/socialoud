<?php

use Botble\Shortcode\View\View;
use Botble\Theme\Theme;

$config = [
    'inherit' => 'lara-mag',
    'events' => [
        'beforeRenderTheme' => function (Theme $theme): void {
            if (function_exists('shortcode')) {
                $theme->composer(
                    ['page', 'post', 'category', 'tag', 'gallery'],
                    function (View $view): void {
                        $view->withShortcodes();
                    }
                );
            }
        },
    ],
];

return $config;
