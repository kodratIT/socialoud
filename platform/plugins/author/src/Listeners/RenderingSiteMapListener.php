<?php

namespace Botble\Author\Listeners;

use Botble\Author\Models\Author;
use Botble\Theme\Facades\SiteMapManager;

class RenderingSiteMapListener
{
    public function handle(): void
    {
        $authors = Author::query()
            ->wherePublished()
            ->orderByDesc('created_at')
            ->with(['slugable'])
            ->get();

        foreach ($authors as $author) {
            SiteMapManager::add($author->url, $author->updated_at, '0.8');
        }
    }
}
