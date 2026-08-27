<?php

namespace Database\Seeders;

use Botble\LanguageAdvanced\Database\Seeders\BaseTranslationSeeder;
use Botble\LanguageAdvanced\Database\Seeders\Traits\HasWidgetSeeder as HasWidgetTranslationSeeder;
use Botble\Widget\Database\Traits\HasWidgetSeeder;

class WidgetSeeder extends BaseTranslationSeeder
{
    use HasWidgetSeeder;
    use HasWidgetTranslationSeeder;

    public function run(): void
    {
        $data = [
            [
                'widget_id' => 'TextWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 0,
                'data' => [
                    'id' => 'TextWidget',
                    'name' => 'About us',
                    'content' => 'Your trusted source for the latest news, in-depth analysis, and compelling stories from around the world. We are committed to delivering quality journalism that informs and inspires.',
                ],
            ],
            [
                'widget_id' => 'RecentPostsWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'RecentPostsWidget',
                    'name' => 'Recent Posts',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'Featured categories',
                    'menu_id' => 'featured-categories',
                ],
            ],
            [
                'widget_id' => 'FacebookWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 3,
                'data' => [
                    'id' => 'FacebookWidget',
                    'name' => 'Fanpage Facebook',
                    'facebook_name' => 'Archi Elite',
                    'facebook_url' => 'https://www.facebook.com/envato',
                ],
            ],
            [
                'widget_id' => 'RecentPostsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 0,
                'data' => [
                    'id' => 'RecentPostsWidget',
                    'name' => 'Recent Posts',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'AdsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'AdsWidget',
                    'name' => null,
                    'image_link' => '#',
                    'image_new_tab' => '1',
                    'image_url' => $this->filePath('banners/2.jpg'),
                ],
            ],
            [
                'widget_id' => 'PopularPostsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'PopularPostsWidget',
                    'name' => 'Popular Posts',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'VideoPostsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 3,
                'data' => [
                    'id' => 'VideoPostsWidget',
                    'name' => 'Video Posts',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'AdsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 4,
                'data' => [
                    'id' => 'AdsWidget',
                    'name' => null,
                    'image_link' => '#',
                    'image_new_tab' => '1',
                    'image_url' => $this->filePath('banners/2.jpg'),
                ],
            ],
        ];

        $this->createWidgets($data);

        $this->seedWidgets(['ar', 'vi', 'fr', 'id', 'tr']);
    }

    protected function applyWidgetTranslations(array $data, array $translations, string $locale): array
    {
        foreach (['name', 'title', 'subtitle', 'content'] as $key) {
            if (isset($data[$key]) && is_string($data[$key])) {
                $data[$key] = $this->translateValue($translations, $data[$key]);
            }
        }

        if (isset($data['menu_id']) && is_string($data['menu_id'])) {
            $data['menu_id'] = sprintf('%s-%s', $data['menu_id'], $locale);
        }

        return $data;
    }
}
