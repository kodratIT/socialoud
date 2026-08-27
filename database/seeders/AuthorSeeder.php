<?php

namespace Database\Seeders;

use Botble\Author\Models\Author;
use Botble\Base\Supports\BaseSeeder;
use Botble\Blog\Models\Post;
use Botble\Slug\Facades\SlugHelper;

class AuthorSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('authors');

        Author::query()->truncate();

        $authors = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'description' => 'Experienced journalist with over 10 years of writing about technology and innovation. Passionate about exploring the intersection of tech and society.',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'description' => 'Award-winning writer specializing in lifestyle and culture. Known for insightful articles that resonate with modern readers.',
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'description' => 'Senior editor and columnist focused on business and economics. Brings deep analytical perspective to complex topics.',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@example.com',
                'description' => 'Creative content creator with expertise in digital media and entertainment. Loves telling stories that inspire and engage.',
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@example.com',
                'description' => 'Investigative reporter dedicated to uncovering important stories. Committed to truth and journalistic integrity.',
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'description' => 'Health and wellness writer helping readers live their best lives. Evidence-based approach to holistic wellbeing.',
            ],
            [
                'name' => 'Robert Martinez',
                'email' => 'robert.martinez@example.com',
                'description' => 'Sports journalist covering major leagues and international competitions. Brings excitement and analysis to every game.',
            ],
            [
                'name' => 'Jennifer Lee',
                'email' => 'jennifer.lee@example.com',
                'description' => 'Travel writer and photographer exploring destinations around the world. Sharing unique perspectives and hidden gems.',
            ],
            [
                'name' => 'James Taylor',
                'email' => 'james.taylor@example.com',
                'description' => 'Political analyst providing balanced commentary on current affairs. Dedicated to helping readers understand complex issues.',
            ],
            [
                'name' => 'Amanda Brown',
                'email' => 'amanda.brown@example.com',
                'description' => 'Food and culture writer celebrating culinary traditions. Exploring the stories behind every dish and ingredient.',
            ],
        ];

        foreach ($authors as $i => $authorData) {
            $author = Author::query()->create([
                'name' => $authorData['name'],
                'email' => $authorData['email'],
                'description' => $authorData['description'],
                'avatar' => $this->filePath('authors/' . ($i + 1) . '.jpg'),
            ]);

            $author->save();

            SlugHelper::createSlug($author);
        }

        foreach (Post::query()->get() as $post) {
            $post->author_id = rand(1, 10);
            $post->author_type = Author::class;
            $post->save();
        }
    }
}
