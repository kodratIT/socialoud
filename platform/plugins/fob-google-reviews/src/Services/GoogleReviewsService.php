<?php

namespace FriendsOfBotble\GoogleReviews\Services;

use FriendsOfBotble\GoogleReviews\Models\GoogleReviewData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GoogleReviewsService
{
    public function getReviews(?string $placeId = null): array
    {
        $sourceMode = setting('google_reviews_source_mode', 'manual');

        if ($sourceMode === 'manual') {
            return $this->getManualReviews();
        }

        return $this->getApiReviews($placeId);
    }

    protected function getManualReviews(): array
    {
        $maxReviews = (int) setting('google_reviews_max_reviews', 5);
        $minRating = (int) setting('google_reviews_min_rating', 0);

        $reviews = GoogleReviewData::query()
            ->active()
            ->ordered()
            ->when($minRating > 0, function ($query) use ($minRating) {
                return $query->where('rating', '>=', $minRating);
            })
            ->limit($maxReviews)
            ->get()
            ->map(function ($review) {
                return [
                    'author_name' => $review->author_name,
                    'author_url' => $review->author_url,
                    'profile_photo_url' => $review->profile_photo_url,
                    'rating' => $review->rating,
                    'relative_time_description' => $review->relative_time_description,
                    'text' => $review->text,
                    'time' => $review->review_date ? $review->review_date->timestamp : time(),
                ];
            })
            ->toArray();

        $totalReviews = GoogleReviewData::query()->active()->count();
        $avgRating = GoogleReviewData::query()->active()->avg('rating') ?: 0;

        return [
            'place_name' => setting('google_reviews_business_name', 'Our Business'),
            'overall_rating' => round($avgRating, 1),
            'total_reviews' => $totalReviews,
            'reviews' => $reviews,
        ];
    }

    protected function getApiReviews(?string $placeId = null): array
    {
        $apiKey = setting('google_reviews_api_key');
        $placeId = $placeId ?: setting('google_reviews_place_id');
        $cacheDuration = (int) setting('google_reviews_cache_duration', 60);

        if (! $apiKey || ! $placeId) {
            return $this->getEmptyResponse();
        }

        $cacheKey = "google_reviews_{$placeId}";

        return Cache::remember($cacheKey, now()->addMinutes($cacheDuration), function () use ($apiKey, $placeId) {
            try {
                $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                    'place_id' => $placeId,
                    'fields' => 'name,rating,user_ratings_total,reviews',
                    'key' => $apiKey,
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['result'])) {
                        return [
                            'place_name' => $data['result']['name'] ?? '',
                            'overall_rating' => $data['result']['rating'] ?? 0,
                            'total_reviews' => $data['result']['user_ratings_total'] ?? 0,
                            'reviews' => $this->filterApiReviews($data['result']['reviews'] ?? []),
                        ];
                    }
                }
            } catch (\Exception $e) {
                report($e);
            }

            return $this->getEmptyResponse();
        });
    }

    protected function getEmptyResponse(): array
    {
        return [
            'place_name' => '',
            'overall_rating' => 0,
            'total_reviews' => 0,
            'reviews' => [],
        ];
    }

    protected function filterApiReviews(array $reviews): array
    {
        $minRating = (int) setting('google_reviews_min_rating', 0);
        $maxReviews = (int) setting('google_reviews_max_reviews', 5);

        $filtered = collect($reviews)
            ->filter(function ($review) use ($minRating) {
                return ($review['rating'] ?? 0) >= $minRating;
            })
            ->sortByDesc('time')
            ->take($maxReviews)
            ->map(function ($review) {
                return [
                    'author_name' => $review['author_name'] ?? '',
                    'author_url' => $review['author_url'] ?? '',
                    'profile_photo_url' => $review['profile_photo_url'] ?? '',
                    'rating' => $review['rating'] ?? 0,
                    'relative_time_description' => $review['relative_time_description'] ?? '',
                    'text' => $review['text'] ?? '',
                    'time' => $review['time'] ?? 0,
                ];
            })
            ->values()
            ->toArray();

        return $filtered;
    }

    public function clearCache(?string $placeId = null): void
    {
        $placeId = $placeId ?: setting('google_reviews_place_id');

        if ($placeId) {
            Cache::forget("google_reviews_{$placeId}");
        }
    }

    public function getPlaceInfo(?string $placeId = null): array
    {
        $data = $this->getReviews($placeId);

        return [
            'name' => $data['place_name'] ?? '',
            'rating' => $data['overall_rating'] ?? 0,
            'total_reviews' => $data['total_reviews'] ?? 0,
        ];
    }
}
