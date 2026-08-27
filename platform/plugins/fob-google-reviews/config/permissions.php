<?php

return [
    [
        'name' => 'Google Reviews',
        'flag' => 'google-reviews.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'google-reviews.create',
        'parent_flag' => 'google-reviews.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'google-reviews.edit',
        'parent_flag' => 'google-reviews.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'google-reviews.destroy',
        'parent_flag' => 'google-reviews.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'google-reviews.settings',
        'parent_flag' => 'google-reviews.index',
    ],
];
