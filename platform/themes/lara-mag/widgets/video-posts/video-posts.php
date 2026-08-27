<?php

use Botble\Widget\AbstractWidget;

class VideoPostsWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Video Posts - LaraMag Theme',
            'description' => 'Video posts widget',
            'number_display' => 5,
        ]);
    }
}
