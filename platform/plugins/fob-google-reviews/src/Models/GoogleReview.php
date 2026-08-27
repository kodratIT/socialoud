<?php

namespace FriendsOfBotble\GoogleReviews\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GoogleReview extends BaseModel
{
    protected $table = 'google_reviews';

    protected $fillable = [
        'reviewable_id',
        'reviewable_type',
        'is_enabled',
        'custom_place_id',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
