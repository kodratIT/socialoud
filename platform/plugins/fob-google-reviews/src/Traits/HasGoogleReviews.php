<?php

namespace FriendsOfBotble\GoogleReviews\Traits;

use FriendsOfBotble\GoogleReviews\Models\GoogleReview;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasGoogleReviews
{
    public function googleReview(): MorphOne
    {
        return $this->morphOne(GoogleReview::class, 'reviewable');
    }

    public function getGoogleReviewPlaceId(): ?string
    {
        $review = $this->googleReview;

        return $review && $review->custom_place_id ? $review->custom_place_id : null;
    }

    public function isGoogleReviewsEnabled(): bool
    {
        $review = $this->googleReview;

        return $review && $review->is_enabled;
    }
}
