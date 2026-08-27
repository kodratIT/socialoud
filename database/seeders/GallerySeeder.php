<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Gallery\Database\Traits\HasGallerySeeder;
use Botble\Gallery\Models\Gallery;
use Botble\LanguageAdvanced\Database\Seeders\Traits\HasTranslationLoader;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GallerySeeder extends BaseSeeder
{
    use HasGallerySeeder;
    use HasTranslationLoader;

    public function run(): void
    {
        Slug::query()->where('reference_type', Gallery::class)->delete();

        $this->uploadFiles('galleries');

        $galleries = [
            [
                'name' => 'Perfect',
            ],
            [
                'name' => 'New Day',
            ],
            [
                'name' => 'Happy Day',
            ],
            [
                'name' => 'Nature',
            ],
            [
                'name' => 'Morning',
            ],
            [
                'name' => 'Photography',
            ],
        ];

        $descriptions = [
            'A beautiful landscape captured in perfect lighting',
            'Nature at its finest moment',
            'Stunning photography showcasing natural beauty',
            'Peaceful scenery with vibrant colors',
            'Artistic composition of natural elements',
            'Breathtaking view of the great outdoors',
            'Captivating moment frozen in time',
            'Serene environment with perfect harmony',
            'Magnificent display of natural wonders',
            'Inspiring view that touches the soul',
        ];

        $images = [];
        for ($i = 0; $i < 10; $i++) {
            $images[] = [
                'img' => $this->filePath('galleries/' . ($i + 1) . '.jpg'),
                'description' => $descriptions[$i],
            ];
        }

        foreach ($galleries as $index => &$item) {
            $item['image'] = $this->filePath('galleries/' . ($index + 1) . '.jpg');
            $item['is_featured'] = true;
        }

        $this->createGalleries($galleries, $images);

        $this->seedGalleryTranslations();
    }

    protected function seedGalleryTranslations(): void
    {
        if (! Schema::hasTable('galleries_translations')) {
            return;
        }

        $locales = ['ar', 'vi', 'fr', 'id', 'tr'];
        $allTranslations = $this->loadAllTranslations('galleries', $locales);

        $galleries = Gallery::query()->get(['id', 'name', 'description']);

        foreach ($galleries as $gallery) {
            foreach ($locales as $locale) {
                $translation = $allTranslations[$locale][$gallery->name] ?? [];

                if (empty($translation)) {
                    continue;
                }

                DB::table('galleries_translations')->updateOrInsert(
                    [
                        'lang_code' => $locale,
                        'galleries_id' => $gallery->id,
                    ],
                    [
                        'lang_code' => $locale,
                        'galleries_id' => $gallery->id,
                        'name' => $translation['name'] ?? null,
                        'description' => $translation['description'] ?? null,
                    ]
                );
            }
        }
    }
}
