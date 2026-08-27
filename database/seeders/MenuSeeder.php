<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Category;
use Botble\Language\Models\LanguageMeta;
use Botble\Menu\Facades\Menu;
use Botble\Menu\Models\Menu as MenuModel;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MenuSeeder extends BaseSeeder
{
    public function run(): void
    {
        MenuModel::query()->truncate();
        MenuLocation::query()->truncate();
        MenuNode::query()->truncate();

        $this->createMainMenu();
        $this->createSecondMenu();
        $this->createHeaderMenu();
        $this->createFeaturedCategoriesMenu();

        Menu::clearCacheMenuItems();
    }

    protected function createMainMenu(): void
    {
        $categoryIds = Category::query()->pluck('id', 'name')->all();
        $pageIds = Page::query()->pluck('id', 'name')->all();

        $enData = [
            'name' => 'Main menu',
            'slug' => 'main-menu',
            'location' => 'main-menu',
            'items' => [
                ['title' => 'Business', 'reference_id' => $categoryIds['Business'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Economy', 'reference_id' => $categoryIds['Economy'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Law',
                    'reference_id' => $categoryIds['Law'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Behind the scenes', 'reference_id' => $categoryIds['Behind the scenes'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Discovery', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Entertainment',
                    'reference_id' => $categoryIds['Entertainment'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Football', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                        ['title' => 'Fashion', 'reference_id' => $categoryIds['Fashion'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Hot & Cool', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Life',
                    'reference_id' => $categoryIds['Life'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Beautify', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Sport', 'reference_id' => $categoryIds['Sport'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'About us',
                    'reference_id' => $pageIds['About us'] ?? null,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Contact', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
                        ['title' => 'Introduction', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                    ],
                ],
            ],
        ];

        $arData = [
            'name' => 'القائمة الرئيسية',
            'slug' => 'main-menu-ar',
            'location' => 'main-menu',
            'items' => [
                ['title' => 'أعمال', 'reference_id' => $categoryIds['Business'] ?? null, 'reference_type' => Category::class],
                ['title' => 'اقتصاد', 'reference_id' => $categoryIds['Economy'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'قانون',
                    'reference_id' => $categoryIds['Law'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'وراء الكواليس', 'reference_id' => $categoryIds['Behind the scenes'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'اكتشاف', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'ترفيه',
                    'reference_id' => $categoryIds['Entertainment'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'كرة القدم', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                        ['title' => 'أزياء', 'reference_id' => $categoryIds['Fashion'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'ساخن ورائع', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'حياة',
                    'reference_id' => $categoryIds['Life'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'تجميل', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'رياضة', 'reference_id' => $categoryIds['Sport'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'من نحن',
                    'reference_id' => $pageIds['About us'] ?? null,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'اتصل بنا', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
                        ['title' => 'مقدمة', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                    ],
                ],
            ],
        ];

        $viData = [
            'name' => 'Menu chính',
            'slug' => 'main-menu-vi',
            'location' => 'main-menu',
            'items' => [
                ['title' => 'Kinh doanh', 'reference_id' => $categoryIds['Business'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Kinh tế', 'reference_id' => $categoryIds['Economy'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Pháp luật',
                    'reference_id' => $categoryIds['Law'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Hậu trường', 'reference_id' => $categoryIds['Behind the scenes'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Khám phá', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Giải trí',
                    'reference_id' => $categoryIds['Entertainment'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Bóng đá', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                        ['title' => 'Thời trang', 'reference_id' => $categoryIds['Fashion'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Nóng & Mát', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Cuộc sống',
                    'reference_id' => $categoryIds['Life'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Làm đẹp', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Thể thao', 'reference_id' => $categoryIds['Sport'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Về chúng tôi',
                    'reference_id' => $pageIds['About us'] ?? null,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Liên hệ', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
                        ['title' => 'Giới thiệu', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                    ],
                ],
            ],
        ];

        $frData = [
            'name' => 'Menu principal',
            'slug' => 'main-menu-fr',
            'location' => 'main-menu',
            'items' => [
                ['title' => 'Affaires', 'reference_id' => $categoryIds['Business'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Économie', 'reference_id' => $categoryIds['Economy'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Droit',
                    'reference_id' => $categoryIds['Law'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Coulisses', 'reference_id' => $categoryIds['Behind the scenes'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Découverte', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Divertissement',
                    'reference_id' => $categoryIds['Entertainment'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Football', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                        ['title' => 'Mode', 'reference_id' => $categoryIds['Fashion'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Chaud & Cool', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Vie',
                    'reference_id' => $categoryIds['Life'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Beauté', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Sport', 'reference_id' => $categoryIds['Sport'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'À propos',
                    'reference_id' => $pageIds['About us'] ?? null,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Contact', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
                        ['title' => 'Introduction', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                    ],
                ],
            ],
        ];

        $idData = [
            'name' => 'Menu Utama',
            'slug' => 'main-menu-id',
            'location' => 'main-menu',
            'items' => [
                ['title' => 'Bisnis', 'reference_id' => $categoryIds['Business'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Ekonomi', 'reference_id' => $categoryIds['Economy'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Hukum',
                    'reference_id' => $categoryIds['Law'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Di Balik Layar', 'reference_id' => $categoryIds['Behind the scenes'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Penemuan', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Hiburan',
                    'reference_id' => $categoryIds['Entertainment'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Sepak Bola', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                        ['title' => 'Mode', 'reference_id' => $categoryIds['Fashion'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Panas & Keren', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Kehidupan',
                    'reference_id' => $categoryIds['Life'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Kecantikan', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Olahraga', 'reference_id' => $categoryIds['Sport'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Tentang Kami',
                    'reference_id' => $pageIds['About us'] ?? null,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'Kontak', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
                        ['title' => 'Pengantar', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                    ],
                ],
            ],
        ];

        $trData = [
            'name' => 'Ana Menü',
            'slug' => 'main-menu-tr',
            'location' => 'main-menu',
            'items' => [
                ['title' => 'İş Dünyası', 'reference_id' => $categoryIds['Business'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Ekonomi', 'reference_id' => $categoryIds['Economy'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Hukuk',
                    'reference_id' => $categoryIds['Law'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Kamera Arkası', 'reference_id' => $categoryIds['Behind the scenes'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Keşif', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Eğlence',
                    'reference_id' => $categoryIds['Entertainment'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Futbol', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                        ['title' => 'Moda', 'reference_id' => $categoryIds['Fashion'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Sıcak & Serin', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Yaşam',
                    'reference_id' => $categoryIds['Life'] ?? null,
                    'reference_type' => Category::class,
                    'children' => [
                        ['title' => 'Güzelleştirme', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                    ],
                ],
                ['title' => 'Spor', 'reference_id' => $categoryIds['Sport'] ?? null, 'reference_type' => Category::class],
                [
                    'title' => 'Hakkımızda',
                    'reference_id' => $pageIds['About us'] ?? null,
                    'reference_type' => Page::class,
                    'children' => [
                        ['title' => 'İletişim', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
                        ['title' => 'Tanıtım', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                    ],
                ],
            ],
        ];

        $this->createMenuWithTranslations($enData, $arData, $viData, $frData, $idData, $trData);
    }

    protected function createSecondMenu(): void
    {
        $categoryIds = Category::query()->pluck('id', 'name')->all();

        $enData = [
            'name' => 'Second menu',
            'slug' => 'second-menu',
            'location' => 'second-menu',
            'items' => [
                ['title' => 'Beautify', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Love', 'reference_id' => $categoryIds['Love'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Other sports', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $arData = [
            'name' => 'القائمة الثانية',
            'slug' => 'second-menu-ar',
            'location' => 'second-menu',
            'items' => [
                ['title' => 'تجميل', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                ['title' => 'حب', 'reference_id' => $categoryIds['Love'] ?? null, 'reference_type' => Category::class],
                ['title' => 'رياضات أخرى', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $viData = [
            'name' => 'Menu phụ',
            'slug' => 'second-menu-vi',
            'location' => 'second-menu',
            'items' => [
                ['title' => 'Làm đẹp', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Tình yêu', 'reference_id' => $categoryIds['Love'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Thể thao khác', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $frData = [
            'name' => 'Menu secondaire',
            'slug' => 'second-menu-fr',
            'location' => 'second-menu',
            'items' => [
                ['title' => 'Beauté', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Amour', 'reference_id' => $categoryIds['Love'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Autres sports', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $idData = [
            'name' => 'Menu Kedua',
            'slug' => 'second-menu-id',
            'location' => 'second-menu',
            'items' => [
                ['title' => 'Kecantikan', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Cinta', 'reference_id' => $categoryIds['Love'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Olahraga Lain', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $trData = [
            'name' => 'İkinci Menü',
            'slug' => 'second-menu-tr',
            'location' => 'second-menu',
            'items' => [
                ['title' => 'Güzelleştirme', 'reference_id' => $categoryIds['Beautify'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Aşk', 'reference_id' => $categoryIds['Love'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Diğer Sporlar', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $this->createMenuWithTranslations($enData, $arData, $viData, $frData, $idData, $trData);
    }

    protected function createHeaderMenu(): void
    {
        $pageIds = Page::query()->pluck('id', 'name')->all();

        $enData = [
            'name' => 'Header menu',
            'slug' => 'header-menu',
            'location' => 'header-menu',
            'items' => [
                ['title' => 'Introduction', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                ['title' => 'About us', 'reference_id' => $pageIds['About us'] ?? null, 'reference_type' => Page::class],
                ['title' => 'Contact', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
            ],
        ];

        $arData = [
            'name' => 'قائمة الرأس',
            'slug' => 'header-menu-ar',
            'location' => 'header-menu',
            'items' => [
                ['title' => 'مقدمة', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                ['title' => 'من نحن', 'reference_id' => $pageIds['About us'] ?? null, 'reference_type' => Page::class],
                ['title' => 'اتصل بنا', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
            ],
        ];

        $viData = [
            'name' => 'Menu trên cùng',
            'slug' => 'header-menu-vi',
            'location' => 'header-menu',
            'items' => [
                ['title' => 'Giới thiệu', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                ['title' => 'Về chúng tôi', 'reference_id' => $pageIds['About us'] ?? null, 'reference_type' => Page::class],
                ['title' => 'Liên hệ', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
            ],
        ];

        $frData = [
            'name' => 'Menu en-tête',
            'slug' => 'header-menu-fr',
            'location' => 'header-menu',
            'items' => [
                ['title' => 'Introduction', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                ['title' => 'À propos', 'reference_id' => $pageIds['About us'] ?? null, 'reference_type' => Page::class],
                ['title' => 'Contact', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
            ],
        ];

        $idData = [
            'name' => 'Menu Header',
            'slug' => 'header-menu-id',
            'location' => 'header-menu',
            'items' => [
                ['title' => 'Pengantar', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                ['title' => 'Tentang Kami', 'reference_id' => $pageIds['About us'] ?? null, 'reference_type' => Page::class],
                ['title' => 'Kontak', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
            ],
        ];

        $trData = [
            'name' => 'Üst Menü',
            'slug' => 'header-menu-tr',
            'location' => 'header-menu',
            'items' => [
                ['title' => 'Tanıtım', 'reference_id' => $pageIds['Introduction'] ?? null, 'reference_type' => Page::class],
                ['title' => 'Hakkımızda', 'reference_id' => $pageIds['About us'] ?? null, 'reference_type' => Page::class],
                ['title' => 'İletişim', 'reference_id' => $pageIds['Contact'] ?? null, 'reference_type' => Page::class],
            ],
        ];

        $this->createMenuWithTranslations($enData, $arData, $viData, $frData, $idData, $trData);
    }

    protected function createFeaturedCategoriesMenu(): void
    {
        $categoryIds = Category::query()->pluck('id', 'name')->all();

        $enData = [
            'name' => 'Featured categories',
            'slug' => 'featured-categories',
            'items' => [
                ['title' => 'Discovery', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Football', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Hot & Cool', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Other sports', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Video', 'reference_id' => $categoryIds['Video'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $arData = [
            'name' => 'التصنيفات المميزة',
            'slug' => 'featured-categories-ar',
            'items' => [
                ['title' => 'اكتشاف', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                ['title' => 'كرة القدم', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                ['title' => 'ساخن ورائع', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                ['title' => 'رياضات أخرى', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
                ['title' => 'فيديو', 'reference_id' => $categoryIds['Video'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $viData = [
            'name' => 'Chuyên mục nổi bật',
            'slug' => 'featured-categories-vi',
            'items' => [
                ['title' => 'Khám phá', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Bóng đá', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Nóng & Mát', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Thể thao khác', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Video', 'reference_id' => $categoryIds['Video'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $frData = [
            'name' => 'Catégories en vedette',
            'slug' => 'featured-categories-fr',
            'items' => [
                ['title' => 'Découverte', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Football', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Chaud & Cool', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Autres sports', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Vidéo', 'reference_id' => $categoryIds['Video'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $idData = [
            'name' => 'Kategori Unggulan',
            'slug' => 'featured-categories-id',
            'items' => [
                ['title' => 'Penemuan', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Sepak Bola', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Panas & Keren', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Olahraga Lain', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Video', 'reference_id' => $categoryIds['Video'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $trData = [
            'name' => 'Öne Çıkan Kategoriler',
            'slug' => 'featured-categories-tr',
            'items' => [
                ['title' => 'Keşif', 'reference_id' => $categoryIds['Discovery'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Futbol', 'reference_id' => $categoryIds['Football'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Sıcak & Serin', 'reference_id' => $categoryIds['Hot & Cool'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Diğer Sporlar', 'reference_id' => $categoryIds['Other sports'] ?? null, 'reference_type' => Category::class],
                ['title' => 'Video', 'reference_id' => $categoryIds['Video'] ?? null, 'reference_type' => Category::class],
            ],
        ];

        $this->createMenuWithTranslations($enData, $arData, $viData, $frData, $idData, $trData);
    }

    protected function createMenuWithTranslations(array $enData, array $arData, array $viData, array $frData, array $idData, array $trData): void
    {
        $menuOrigin = md5(Str::random(20) . time());
        $locationOrigin = md5(Str::random(20) . time() . 'loc');

        $this->createMenu($enData, 'en_US', $menuOrigin, $locationOrigin);
        $this->createMenu($arData, 'ar', $menuOrigin, $locationOrigin);
        $this->createMenu($viData, 'vi', $menuOrigin, $locationOrigin);
        $this->createMenu($frData, 'fr', $menuOrigin, $locationOrigin);
        $this->createMenu($idData, 'id', $menuOrigin, $locationOrigin);
        $this->createMenu($trData, 'tr', $menuOrigin, $locationOrigin);
    }

    protected function createMenu(array $data, string $locale, string $menuOrigin, string $locationOrigin): MenuModel
    {
        $menu = MenuModel::query()->create([
            'name' => $data['name'],
            'slug' => $data['slug'],
        ]);

        if (isset($data['location'])) {
            $menuLocation = MenuLocation::query()->create([
                'menu_id' => $menu->getKey(),
                'location' => $data['location'],
            ]);

            if (is_plugin_active('language')) {
                LanguageMeta::saveMetaData($menuLocation, $locale, $locationOrigin);
            }
        }

        foreach ($data['items'] as $position => $menuNode) {
            $this->createMenuNode($position, $menuNode, $menu->getKey());
        }

        if (is_plugin_active('language')) {
            LanguageMeta::saveMetaData($menu, $locale, $menuOrigin);
        }

        return $menu;
    }

    protected function createMenuNode(int $position, array $menuNode, int|string $menuId, int|string $parentId = 0): void
    {
        $menuNode['menu_id'] = $menuId;
        $menuNode['parent_id'] = $parentId;
        $menuNode['position'] = $position;

        if (isset($menuNode['url'])) {
            $menuNode['url'] = str_replace(url(''), '', $menuNode['url']);
        }

        $children = [];
        if (Arr::has($menuNode, 'children') && ! empty($menuNode['children'])) {
            $children = $menuNode['children'];
            $menuNode['has_child'] = true;
        } else {
            $menuNode['has_child'] = false;
        }

        Arr::forget($menuNode, 'children');

        $createdNode = MenuNode::query()->create($menuNode);

        foreach ($children as $childPosition => $child) {
            $this->createMenuNode($childPosition, $child, $menuId, $createdNode->getKey());
        }
    }
}
