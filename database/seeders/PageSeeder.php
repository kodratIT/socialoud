<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\CookieConsent\Database\Traits\HasCookieConsentSeeder;
use Botble\LanguageAdvanced\Database\Seeders\Traits\HasPageTranslation;
use Botble\LanguageAdvanced\Database\Seeders\Traits\HasTranslationLoader;
use Botble\Page\Database\Traits\HasPageSeeder;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;

class PageSeeder extends BaseSeeder
{
    use HasPageSeeder;
    use HasCookieConsentSeeder;
    use HasPageTranslation;
    use HasTranslationLoader;

    public function run(): void
    {
        $this->truncatePages();

        Slug::query()->where('reference_type', Page::class)->delete();

        $pages = [
            [
                'name' => 'Homepage',
                'content' => '<div>[featured-posts limit="5" enable_lazy_loading="yes"][/featured-posts]</div><div>[simple-slider key="home-slider" enable_lazy_loading="yes"][/simple-slider]</div><div>[category-posts enable_lazy_loading="yes"][/category-posts]</div><div>[all-galleries enable_lazy_loading="yes"][/all-galleries]</div>',
                'template' => 'homepage',
            ],
            [
                'name' => 'Introduction',
                'content' => '<p>Welcome to our platform, where we bring you the latest news, insights, and stories from around the world. Our mission is to provide high-quality content that informs, inspires, and engages our readers. We are committed to journalistic excellence and integrity in everything we do.</p><p>Founded by a team of experienced journalists and content creators, we have built a reputation for delivering accurate, balanced, and thought-provoking articles across a wide range of topics including technology, business, lifestyle, culture, and more.</p><p>Our editorial team works tirelessly to ensure that every piece of content meets our high standards. We believe in the power of storytelling to make a difference in people\'s lives and to help them better understand the world around them.</p><p>Thank you for being part of our community. We look forward to continuing to serve you with the best content possible.</p>',
            ],
            [
                'name' => 'About us',
                'content' => '<p>We are a leading digital media company dedicated to delivering exceptional content to our readers worldwide. With a team of talented writers, editors, and content creators, we cover the stories that matter most to you.</p><p>Our platform features in-depth analysis, breaking news, expert opinions, and engaging multimedia content. We pride ourselves on our commitment to accuracy, fairness, and transparency in our reporting.</p><p>What sets us apart is our focus on quality over quantity. Every article is carefully researched, fact-checked, and edited to ensure we deliver the most reliable information to our audience. We believe that informed readers make better decisions, and we strive to be your trusted source of information.</p><p>Join us on our journey as we continue to grow and evolve. We are always looking for ways to improve and better serve our community. Your feedback and support are what drive us to be better every day.</p>',
            ],
            [
                'name' => 'Contact',
                'content' => '<p>123 Main Street, Downtown District, Metro City, ST 12345</p><p>+1 (555) 123-4567 (4 lines) - +1 (555) 987-6543</p><p>+1 (555) 246-8135</p><p>contact@example.com&nbsp;&nbsp;</p><div>[contact-form][/contact-form]</div>',
            ],
            [
                'name' => $this->getCookieConsentPageName(),
                'content' => $this->getCookieConsentPageContent(),
            ],
        ];

        $this->createPages($pages);

        $this->seedPageTranslations(['ar', 'vi', 'fr', 'id', 'tr']);
    }
}
