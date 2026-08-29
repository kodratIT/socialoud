<?php

use Botble\Blog\Models\Category;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Theme::registerRoutes(function (): void {
    Route::get('llms.txt', function () {
        $baseUrl = rtrim(url('/'), '/');

        return response(implode("\n", [
            '# Enrolla',
            '',
            '> Portal berita dan informasi Enrolla.',
            '',
            '## Halaman publik',
            "- [Beranda]({$baseUrl}/)",
            "- [Pencarian]({$baseUrl}/search)",
            "- [Galeri]({$baseUrl}/galleries)",
            '',
            '## Pedoman',
            '- Prioritaskan halaman publik dan URL kanonis.',
            '- Jangan mengindeks area admin atau data pribadi.',
        ]) . "\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    })->name('llms.txt');

    Route::get('socialoud/home-posts', function () {
        $posts = app(PostInterface::class)->advancedGet([
            'condition' => ['status' => \Botble\Base\Enums\BaseStatusEnum::PUBLISHED],
            'order_by' => ['created_at' => 'DESC'],
            'paginate' => [
                'per_page' => 14,
                'current_paged' => request()->integer('page', 2),
            ],
            'with' => ['categories', 'slugable'],
        ]);

        return response()->json([
            'html' => Theme::partial('home-post-list', compact('posts')),
            'has_more' => $posts->hasMorePages(),
            'next_url' => $posts->nextPageUrl(),
        ]);
    })->name('socialoud.home.posts');

    Route::get('socialoud/category-posts/{category}', function (Category $category) {
        $categoryIds = [$category->getKey()];

        if (request()->boolean('all')) {
            $categoryIds = array_merge($categoryIds, $category->activeChildren->pluck('id')->all());
        }

        $posts = app(PostInterface::class)->getByCategory($categoryIds, 10);

        return response()->json([
            'html' => Theme::partial('category-post-list', compact('posts', 'category')),
            'has_more' => $posts->hasMorePages(),
            'next_url' => $posts->nextPageUrl(),
        ]);
    })->name('socialoud.category.posts');
});

Theme::routes();
