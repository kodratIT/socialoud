<?php

namespace FriendsOfBotble\GoogleReviews\Database\Seeders;

use Botble\Base\Enums\BaseStatusEnum;
use FriendsOfBotble\GoogleReviews\Models\GoogleReviewData;
use Illuminate\Database\Seeder;

class GoogleReviewsSeeder extends Seeder
{
    public function run(): void
    {
        GoogleReviewData::query()->truncate();

        $reviews = [
            [
                'author_name' => 'John Smith',
                'author_url' => 'https://www.google.com/maps/contrib/1234567890',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'Excellent service and quality products! I have been shopping here for years and have never been disappointed. The staff is knowledgeable and always ready to help. Highly recommended!',
                'review_date' => now()->subWeeks(2),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 1,
            ],
            [
                'author_name' => 'Sarah Johnson',
                'author_url' => 'https://www.google.com/maps/contrib/9876543210',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'Amazing experience! The product quality exceeded my expectations. Fast shipping and great customer support. Will definitely order again.',
                'review_date' => now()->subWeeks(3),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 2,
            ],
            [
                'author_name' => 'Michael Chen',
                'author_url' => 'https://www.google.com/maps/contrib/5555555555',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'Best online store I\'ve ever used. The website is easy to navigate, checkout process is smooth, and delivery was faster than expected. Five stars!',
                'review_date' => now()->subMonth(),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 3,
            ],
            [
                'author_name' => 'Emily Rodriguez',
                'author_url' => 'https://www.google.com/maps/contrib/3333333333',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'I\'m impressed with the attention to detail and quality. Every item I\'ve purchased has been perfect. The customer service team is also very responsive and helpful.',
                'review_date' => now()->subMonths(2),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 4,
            ],
            [
                'author_name' => 'David Williams',
                'author_url' => 'https://www.google.com/maps/contrib/7777777777',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'Outstanding quality and service! The products are exactly as described and the packaging was excellent. Definitely my go-to store now.',
                'review_date' => now()->subMonths(2)->subWeeks(2),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 5,
            ],
            [
                'author_name' => 'Jessica Taylor',
                'author_url' => 'https://www.google.com/maps/contrib/9999999999',
                'profile_photo_url' => null,
                'rating' => 4,
                'text' => 'Great products and fast delivery. The only reason I\'m not giving 5 stars is because I wish there were more product variations available. Overall, very satisfied!',
                'review_date' => now()->subMonths(3),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 6,
            ],
            [
                'author_name' => 'Robert Anderson',
                'author_url' => 'https://www.google.com/maps/contrib/4444444444',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'Exceptional shopping experience from start to finish. The website is user-friendly, prices are competitive, and the quality is top-notch. Highly recommend to everyone!',
                'review_date' => now()->subMonths(3)->subWeeks(2),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 7,
            ],
            [
                'author_name' => 'Maria Garcia',
                'author_url' => 'https://www.google.com/maps/contrib/6666666666',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'I love shopping here! The selection is great, prices are reasonable, and customer service is fantastic. They really care about their customers.',
                'review_date' => now()->subMonths(4),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 8,
            ],
            [
                'author_name' => 'James Wilson',
                'author_url' => 'https://www.google.com/maps/contrib/8888888888',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'Best purchase decision I\'ve made this year. The quality speaks for itself and the price is unbeatable. Will definitely be a repeat customer!',
                'review_date' => now()->subMonths(5),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 9,
            ],
            [
                'author_name' => 'Lisa Brown',
                'author_url' => 'https://www.google.com/maps/contrib/2222222222',
                'profile_photo_url' => null,
                'rating' => 5,
                'text' => 'Absolutely wonderful! From browsing to delivery, everything was perfect. The product quality exceeded my expectations. Thank you for such a great experience!',
                'review_date' => now()->subMonths(6),
                'status' => BaseStatusEnum::PUBLISHED,
                'order' => 10,
            ],
        ];

        foreach ($reviews as $review) {
            GoogleReviewData::create($review);
        }

        $this->command->info('Google Reviews seeded successfully!');
    }
}
