<?php

namespace FriendsOfBotble\GoogleReviews\Models;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class GoogleReviewData extends BaseModel
{
    protected $table = 'google_reviews_data';

    protected $fillable = [
        'author_name',
        'author_url',
        'profile_photo_url',
        'rating',
        'text',
        'review_date',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'rating' => 'integer',
        'order' => 'integer',
        'review_date' => 'datetime',
    ];

    protected function relativeTimeDescription(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->review_date) {
                    return null;
                }

                $now = now();
                $diff = $this->review_date->diff($now);

                if ($diff->y > 0) {
                    return $diff->y . ' ' . ($diff->y === 1 ? 'year' : 'years') . ' ago';
                }

                if ($diff->m > 0) {
                    return $diff->m . ' ' . ($diff->m === 1 ? 'month' : 'months') . ' ago';
                }

                if ($diff->d > 0) {
                    return $diff->d . ' ' . ($diff->d === 1 ? 'day' : 'days') . ' ago';
                }

                if ($diff->h > 0) {
                    return $diff->h . ' ' . ($diff->h === 1 ? 'hour' : 'hours') . ' ago';
                }

                if ($diff->i > 0) {
                    return $diff->i . ' ' . ($diff->i === 1 ? 'minute' : 'minutes') . ' ago';
                }

                return 'Just now';
            }
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', BaseStatusEnum::PUBLISHED);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderByDesc('review_date');
    }
}
